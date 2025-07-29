<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use App\Models\Redeem;
use App\Models\Reward;
use App\Models\UserOtp;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Dashboard
    function dashboard(){
    $users = User::all();
    $devices = Device::all()->map(function ($device) {
        $device->last_active_human = $device->last_active > 0 ? \Carbon\Carbon::createFromTimestamp($device->last_active)->diffForHumans()
            : 'Never active';
        return $device;
    });
    // Ambil device terakhir aktif (paling baru last_active)
    $latestDevice = $devices->sortByDesc('last_active')->first();
    $submissions = Submission::with(['user', 'device'])->latest()->take(5)->get();

    $now = now();

    $bottlesLastWeek = Submission::whereNotNull('user_id')
        ->where('submission_date', '>=', $now->copy()->subDays(7))
        ->where('status', 'completed')
        ->sum('bottle_count');

    $pointsLastWeek = Submission::whereNotNull('user_id')
        ->where('submission_date', '>=', $now->copy()->subDays(7))
        ->where('status', 'completed')
        ->sum('points_earned');

    $usersLastWeek = User::where('created_at', '>=', $now->copy()->subDays(7))
        ->count();

    $pointsDistributed = Submission::where('status', 'completed')->sum('points_earned');

    return view('admin.dashboard.view', compact(
        'submissions', 'users', 'devices', 'latestDevice',
        'bottlesLastWeek', 'pointsLastWeek', 'usersLastWeek', 'pointsDistributed'
    ));
}


    // Submissions
    function submissions(Request $request){
        $keyword = trim($request->query('search', ''));
        $tab = $request->query('tab', 'submissions');
        
        $data = [
            'keyword' => $keyword,
            'tab' => $tab,
            'submissions' => collect(),
            'redeems' => collect(),
            'users' => collect(),
            'devices' => collect(),
            'rewards' => collect(),
        ];

        if ($tab === 'redeems') {
            $data['redeems'] = Redeem::with(['user', 'reward'])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('username', 'like', "%$keyword%");
                    });
                })
                ->paginate(10);
            $data['users'] = User::select('id', 'username')->get();
            $data['rewards'] = Reward::select('id', 'name')->get();
        } else {
            $data['submissions'] = Submission::with(['user', 'device'])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('username', 'like', "%$keyword%");
                    });
                })
                ->paginate(10);
            $data['users'] = User::select('id', 'username')->get();
            $data['devices'] = Device::select('id', 'name')->get();
        }

        return view('admin.submissions.view', $data);
    }

    function submissions_detail(){
        return view('admin.submissions.detail');
    }

    function submissions_create(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'device_id'       => 'required|exists:devices,id',
            'bottle_count'    => 'required|integer|min:1',
            'points_earned'   => 'required|integer|min:0',
            'status'          => 'required|in:pending,completed,failed',
        ]);

        $submission = Submission::create([
            'user_id'         => $request->user_id,
            'device_id'       => $request->device_id,
            'bottle_count'    => $request->bottle_count,
            'points_earned'   => $request->points_earned,
            'status'          => $request->status,
        ]);

        if ($submission->status === 'completed') {
            $user = $submission->user;
            $user->increment('bottle_stored', $submission->bottle_count);
            $user->increment('total_points', $submission->points_earned);
        }
        return redirect()->route('admin.submissions')->with('success', 'Submission added successfully.');
    }

    function submissions_update_status($id, $status)
    {
        $valid_status = ['completed', 'failed'];

        if (!in_array($status, $valid_status)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $submission = Submission::findOrFail($id);
        
        if ($submission->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending submissions can be updated.');
        }

        $submission->status = $status;

        if ($submission->status === 'completed') {
            $submission->submission_date = now();

            $user = $submission->user;
            $user->increment('bottle_stored', $submission->bottle_count);
            $user->increment('total_points', $submission->points_earned);

            $device = $submission->device;
            $device->increment('capacity', $submission->bottle_count);
        }

        $submission->save();
        return redirect()->back()->with('success', $submission->user->username . " submissions was $status.");
    }

    // Users
        function users(Request $request){
            $keyword = $request->query('search');
            $sort = $request->query('sort', 'def');
            $users = User::when($keyword, function ($query, $keyword) {
            return $query->where('username', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->when($sort === 'asc', fn($q) => $q->orderBy('username', 'asc'))
            ->when($sort === 'desc', fn($q) => $q->orderBy('username', 'desc'))
            ->when($sort === 'def' || $sort === null, fn($q) => $q->latest())
            ->paginate(10)
            ->appends(['sort' => $sort, 'search' => $keyword]);

            return view('admin.users.view', compact('users', 'keyword'));
    }

    function users_create(Request $request){
        $request->validate([
            'email' => 'required|email|unique:users,email|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8|max:255',
            'role' => 'required|in:user,admin'
        ]);

        User::create([
            'email' => $request->email,
            'username' => $request->username, 
            'password' => Hash::make($request->password),
            'role' => $request->role, 
        ]);

        return redirect()->route('admin.users')->with('success', 'Successfully added an account');
    }

    function users_update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'total_points' => 'required|integer|min:0',
            'bottle_stored' => 'required|integer|min:0',
            'role' => 'required|in:user,admin',
        ]);
        User::findOrFail($id)->update($request->only(['username', 'total_points', 'bottle_stored', 'role']));
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    function users_detail(){
        return view("admin.users.detail");
    }

    function users_delete($id){
        $user = User::findOrFail($id);

        Submission::where('user_id', $user->id)->update([
            'user_deleted_id' => $user->id,
            'user_id' => null
        ]);

        Redeem::where('user_id', $user->id)->update([
            'user_deleted_id' => $user->id,
            'user_id' => null
        ]);

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    // Devices
    function devices(){
        $devices = Device::all()->map(function ($device) {
            $device->last_active_human = $device->last_active > 0 ? \Carbon\Carbon::createFromTimestamp($device->last_active)->diffForHumans()
                : 'Never active';
            return $device;
        });
        return view('admin.devices.view', compact('devices'));
    }

    function devices_create(Request $request){
        $request->validate([
            'name' => 'required|string|unique:devices,name|max:255',
            'location' => 'required|string|max:255',
            'max_capacity' => 'required|integer|min:1',
        ]);

        Device::create([
            'name' => $request->name,
            'location' => $request->location, 
            'max_capacity' => $request->max_capacity,
            'status' => 2
        ]);

        return redirect()->route('admin.devices')->with('success', 'Successfully added an account');
    }

    function devices_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:devices,name,' . $id,
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'max_capacity' => 'required|integer|min:1',
            'status' => 'required|integer|in:0,1,2',
        ]);
        Device::findOrFail($id)->update($request->only(['name', 'location', 'capacity', 'max_capacity', 'status']));
        return redirect()->route('admin.devices')->with('success', 'Device updated successfully.');
    }


    function devices_delete($id){
        $devices = Device::findOrFail($id);

        Submission::where('device_id', $devices->id)->update([
            'device_deleted_id' => $devices->id,
            'device_id' => null
        ]);

        $devices->delete();
        return redirect()->route('admin.devices')->with('success', 'Device deleted successfully.');
    }

    // Leaderboard
    function leaderboard(){
        $topBottles = User::orderByDesc('bottle_stored')->take(5)->get();
        $topPoints = User::orderByDesc('total_points')->take(5)->get();

        return view('admin.leaderboard.view', compact('topBottles', 'topPoints'));
    }

    // Rewards
    function rewards(){
        $rewards = Reward::latest()->get();
        return view('admin.rewards.view', compact('rewards'));
    }

    public function rewards_create(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'points'        => 'required|integer|min:0',
            'stock'         => 'required|integer|min:0',
            'categories'    => 'nullable|string|max:255',
            'status'        => 'required|in:available,unavailable',
            'reward_photo'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('reward_photo')) {
            $extension = $request->file('reward_photo')->getClientOriginalExtension();
            $filename = uniqid() . '_' . Str::random(16) . '.' . $extension;
            $request->file('reward_photo')->storeAs('reward_photos', $filename, 'public');
            $imagePath = 'reward_photos/' . $filename;
        }

        Reward::create([
            'name'          => $request->name,
            'description'   => $request->description,
            'points'        => $request->points,
            'stock'         => $request->stock,
            'categories'    => $request->categories,
            'status'        => $request->status,
            'reward_photo'  => $imagePath,
        ]);

        return redirect()->route('admin.rewards')->with('success', 'Reward added successfuly');
    }


    public function rewards_update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'points'      => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'categories'  => 'nullable|string|max:255',
            'status'      => 'required|in:available,unavailable',
            'reward_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('reward_photo')) {
            if ($reward->reward_photo && Storage::disk('public')->exists($reward->reward_photo)) {
                Storage::disk('public')->delete($reward->reward_photo);
            }

            $extension = $request->file('reward_photo')->getClientOriginalExtension();
            $filename = uniqid() . '_' . Str::random(16) . '.' . $extension;
            $request->file('reward_photo')->storeAs('reward_photos', $filename, 'public');
            $reward->reward_photo = 'reward_photos/' . $filename;
        }

        $reward->update([
            'name'        => $request->name,
            'description' => $request->description,
            'points'      => $request->points,
            'stock'       => $request->stock,
            'categories'  => $request->categories,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.rewards')->with('success', 'Reward updated successfully.');
    }

    public function rewards_delete($id)
    {
        $reward = Reward::findOrFail($id);

        if ($reward->image && Storage::disk('public')->exists($reward->image)) {
            Storage::disk('public')->delete($reward->image);
        }

        Redeem::where('reward_id', $reward->id)->update([
            'reward_deleted_id' => $reward->id,
            'reward_id' => null
        ]);

        $reward->delete();
        return redirect()->route('admin.rewards')->with('success', 'Reward deleted successfully.');
    }

    function redeems_create(Request $request){
        $validated = $request->validate([
        'user_id'       => 'required|exists:users,id',
        'reward_id'     => 'required|exists:rewards,id',
        'points_used'   => 'required|numeric|min:1',
        'status'        => 'required|in:active,used,expired',
        'expires_at'    => 'nullable|date',
    ]);

    // Set default values
    $redeem = new Redeem();
    $redeem->user_id       = $validated['user_id'];
    $redeem->reward_id     = $validated['reward_id'];
    $redeem->points_used   = $validated['points_used'];
    $redeem->status        = $validated['status'];
    $redeem->voucher_code  = strtoupper(Str::random(10));
    $redeem->redeemed_at   = now();
    $redeem->expires_at    = $validated['expires_at'] ?? now()->addMonth();

    $redeem->save();

    return redirect()->back()->with('success', 'Redeem added successfully.');
    }
}
