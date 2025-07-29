<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use App\Models\Redeem;
use App\Models\Reward;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    function dashboard(){
        $recent_submissions = Submission::where('user_id', Auth::id())
                                ->latest()
                                ->take(3)
                                ->get();
        return view('user.dashboard.view', compact('recent_submissions'));
    }

    public function scan_store(Request $request){
        $qrContent = $request->input('qr_content');

        if (str_starts_with($qrContent, url('pair/confirm'))) {
            // Ambil parameter dari URL
            $parts = parse_url($qrContent);
            parse_str($parts['query'] ?? '', $params);

            if (isset($params['device'], $params['token'])) {
                // Konfirmasi pairing dari backend
                $device = Device::where('name', $params['device'])->first();

                if ($device && $device->current_token === $params['token']) {
                    $device->paired_user_id = auth()->id();
                    $device->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Pairing berhasil!',
                        'username' => auth()->user()->username
                    ]);
                }
            }

            return response()->json(['success' => false, 'message' => 'Token tidak valid.']);
        }

        return response()->json(['success' => false, 'message' => 'Kode QR tidak dikenali.']);
    }

    function submissions(Request $request){
        $user = Auth::user();
        $tab = $request->query('tab', 'submissions');
        $status = $request->query('status', 'all');

        // Initialize variables
        $groupedSubmissions = collect([]);
        $groupedRedeems = collect([]);

        if ($tab === 'submissions') {
            // Query for submissions
            $query = Submission::where('user_id', $user->id);

            // Filter by status if not 'all'
            if (in_array($status, ['completed', 'pending', 'failed'])) {
                $query->where('status', $status);
            }

            // Fetch submissions, ordered by latest
            $submissions = $query->orderBy('created_at', 'desc')->get();

            // Group by month-year
            $groupedSubmissions = $submissions->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('F Y');
            });
        } elseif ($tab === 'redeems') {
            // Query for redeems
            $query = Redeem::where('user_id', $user->id);

            // Fetch redeems, ordered by latest
            $redeems = $query->orderBy('created_at', 'desc')->get();

            // Group by month-year
            $groupedRedeems = $redeems->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('F Y');
            });
        }

        return view('user.submissions.view', [
            'groupedSubmissions' => $groupedSubmissions,
            'groupedRedeems' => $groupedRedeems,
            'tab' => $tab,
            'status' => $status,
        ]);
    }

    function profile(){
        return view('user.profile.view');
    }

    function profile_update(Request $request, $id){
        $user = Auth::user();
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'description' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $extension = $request->file('profile_photo')->getClientOriginalExtension();
            $filename = uniqid() . '_' . Str::random(16) . '.' . $extension;

            // Simpan file ke disk 'public'
            $request->file('profile_photo')->storeAs('profile_photos', $filename, 'public');

            $user->profile_photo = 'profile_photos/' . $filename;
        }



        $user->username = $request->username;
        $user->email = $request->email;
        $user->description = $request->description;
        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    function leaderboard(){
        $topBottles = User::orderByDesc('bottle_stored')->take(5)->get();
        $topPoints = User::orderByDesc('total_points')->take(5)->get();

        return view('user.leaderboard.view', compact('topBottles', 'topPoints'));
    }

    function devices(){
        $devices = Device::all();
        return view('user.devices.view', compact('devices'));
    }

    function rewards(){
        $rewards = Reward::where('status', 'available')->get();
        return view('user.rewards.view', compact('rewards'));
    }

    function rewards_redeem($id){
        $reward = Reward::findOrFail($id);
        $user = Auth::user();

        // Cek stok
        if ($reward->stock < 1) {
            return back()->with('error', 'Reward is out of stock.');
        }

        // Cek poin user
        if ($user->total_points < $reward->points) {
            $morePoints = $reward->points - $user->total_points;
            return back()->withErrors("You don't have enough points, you need " . $morePoints . " more points.");
        }

        // Potong poin user
        $user->total_points -= $reward->points;
        $user->save();

        // Kurangi stok reward
        $reward->stock -= 1;
        $reward->save();

        Redeem::create([
            'user_id'     => $user->id,
            'reward_id'   => $reward->id,
            'points_used' => $reward->points,
            'voucher_code'=> strtoupper(Str::random(10)),
            'status'      => 'active',
            'redeemed_at' => now(),
            'expires_at'  => now()->addDays(7), // expired in 7 days
        ]);

        return back()->with('success', 'Reward berhasil ditukar!');
    }


    // Redeems
    function redeems(){
        $redeems = Redeem::with('reward')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
        
        return view('user.redeems.view', compact('redeems'));    
    }
}
