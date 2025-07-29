<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Device;
use App\Models\Submission;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DeviceController extends Controller
{
    /**
     * Generate pairing URL dan token untuk device.
     */
    public function generateForDevice($deviceName){
        $device = Device::where('name', $deviceName)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $token = Str::uuid()->toString();
        $expiresAt = now()->addMinutes(60);

        $device->current_token = $token;
        $device->token_expires_at = $expiresAt;
        $device->paired_user_id = null;
        $device->save();

        return response()->json([
            'pair_url' => url("pair/confirm?device={$device->name}&token={$token}"),
            'token' => $token,
            'expires_at' => $expiresAt->toDateTimeString()
        ]);
    }

    /**
     * Cek status pairing berdasarkan token.
     */
    public function checkStatus(Request $request, $deviceName){
        $token = $request->query('token');
        $device = Device::where('name', $deviceName)->first();

        if (!$device || $device->current_token !== $token) {
            return response()->json(['status' => 'invalid'], 200);
        }

        if (Carbon::parse($device->token_expires_at)->isPast()) {
            return response()->json(['status' => 'expired'], 200);
        }

        if ($device->paired_user_id) {
            $user = User::find($device->paired_user_id);

            return response()->json([
                'status' => 'paired',
                'user_id' => $user->id,
                'username' => $user->username,
                'token' => $device->current_token
            ]);
        }

        return response()->json(['status' => 'pending'], 200);
    }

    /**
     * Konfirmasi pairing dari frontend.
     */
    public function confirm(Request $request){
        $validated = $request->validate([
            'device' => 'required|string',
            'token' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $device = Device::where('name', $validated['device'])->first();

        if (!$device || $device->current_token !== $validated['token']) {
            return response()->json(['error' => 'Token invalid'], 400);
        }

        $device->paired_user_id = $validated['user_id'];
        $device->save();

        return response()->json(['message' => 'Pairing berhasil']);
    }

    /**
     * Update status device (online/offline/maintenance).
     */
    public function updateStatus(Request $request, $deviceName){
        $device = Device::where('name', $deviceName)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $validated = $request->validate([
            'status' => 'required|in:0,1,2'
        ]);

        $device->status = $validated['status'];
        $device->last_active = time();
        $device->save();

        Log::info("📡 Device {$device->name} updated status to {$device->status} at ".date('Y-m-d H:i:s', $device->last_active));

        return response()->json(['message' => 'Status updated']);
    }

    /**
     * Ambil status device (dipakai Python untuk cek mode maintenance).
     */
    public function getStatus($deviceName){
        $device = Device::where('name', $deviceName)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        return response()->json([
            'status' => $device->status,
            'updated_at' => $device->updated_at,
        ]);
    }

    /**
     * Atur device ke mode maintenance (status = 2).
     */
    public function setMaintenance($deviceName){
        $device = Device::where('name', $deviceName)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $device->status = 2;
        $device->save();

        return response()->json(['message' => 'Device set to maintenance']);
    }

    /**
     * Submit botol dari vending machine (status = pending).
     */
    public function submit(Request $request, $deviceName){
        $device = Device::where('name', $deviceName)->first();

        if (!$device || !$device->paired_user_id) {
            return response()->json(['error' => 'Device not paired'], 400);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'bottle_count' => 'required|integer|min:1'
        ]);

        $user = User::find($validated['user_id']);

        if (!$user || $user->id !== $device->paired_user_id) {
            return response()->json(['error' => 'User tidak sesuai dengan pairing'], 400);
        }

        // Hitung poin
        $points = $validated['bottle_count'] * 10;

        // Tentukan status berdasarkan jumlah botol
        $status = $validated['bottle_count'] < 10 ? 'completed' : 'pending';

        // Simpan submission
        $submission = Submission::create([
            'submission_date' => $status === 'completed' ? now() : null,
            'user_id' => $user->id,
            'device_id' => $device->id,
            'bottle_count' => $validated['bottle_count'],
            'points_earned' => $points,
            'status' => $status,
        ]);

        // Jika status completed, langsung update user stats
        if ($status === 'completed') {
            $user->increment('bottle_stored', $validated['bottle_count']);
            $user->increment('total_points', $points);
            $device->increment('capacity', $validated['bottle_count']);
            $device->refresh();
        }

        if($device->capacity >= $device->max_capacity){
            $device->status = 2;
            $device->save();
            Log::warning("Device {$device->name} is full! Status changed to maintenance.");
        }
        return response()->json([
            'message' => $status === 'completed' 
                ? 'Data disimpan dan poin langsung ditambahkan (auto-completed)' 
                : 'Data botol disimpan sebagai pending',
            'data' => [
                'submission_code' => $submission->submission_code,
                'bottle_count' => $submission->bottle_count,
                'points_earned' => $submission->points_earned,
                'status' => $submission->status,
            ]
        ]);
    }


    /**
     * Ambil statistik user (total poin dan total botol).
     */
    public function getUserStats($userId){
        $user = User::findOrFail($userId);

        return response()->json([
            'total_points' => $user->total_points,
            'bottle_stored' => $user->bottle_stored,
        ]);
    }

    /**
 * Tampilan konfirmasi pairing untuk user setelah scan QR code.
 * (Melalui web, bukan API)
 */
    public function webConfirm(Request $request){
        $request->validate([
            'device' => 'required|string',
            'token' => 'required|string',
        ]);

        $device = Device::where('name', $request->device)->first();

        if (!$device || $device->current_token !== $request->token) {
            return redirect('/')->with('error', 'Token tidak valid atau device tidak ditemukan.');
        }

        return view('device.pair', [
            'device' => $device,
            'token' => $request->token,
        ]);
    }

    /**
     * Submit pairing dari tampilan web.
     */
    public function webConfirmSubmit(Request $request){
        $request->validate([
            'device_id' => 'required|exists:devices,id',
            'token' => 'required|string',
        ]);

        $device = Device::findOrFail($request->device_id);

        if (!$device || $device->current_token !== $request->token) {
            return redirect()->back()->with('error', 'Token pairing tidak valid atau sudah kadaluarsa.');
        }

        $device->paired_user_id = auth()->id();
        $device->save();

        Log::info("🔗 Device {$device->name} berhasil dipasangkan ke user " . auth()->user()->username);

        return redirect('/')->with('success', '✅ Device berhasil dipasangkan ke akun Anda.');
    }
}
