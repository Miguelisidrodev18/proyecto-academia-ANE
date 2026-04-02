<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        $completion = 0;
        if ($user->avatar) $completion += 45;
        if ($user->name)   $completion += 30;
        if ($user->role)   $completion += 25;

        return view('profile.edit', [
            'user'              => $user,
            'profileCompletion' => $completion,
        ]);
    }

    /**
     * Upload or replace the user's avatar.
     */
    public function updateAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store("avatars/{$user->id}", 'public');

        $metadata = [
            'uploaded_at'   => now()->toIso8601String(),
            'original_name' => $request->file('avatar')->getClientOriginalName(),
            'size_bytes'    => $request->file('avatar')->getSize(),
            'face_detected' => null,   // Future: reconocimiento facial
            'face_encoding' => null,   // Future: embedding del rostro
        ];

        $user->update([
            'avatar'          => $path,
            'avatar_metadata' => $metadata,
        ]);

        return response()->json([
            'success'    => true,
            'avatar_url' => Storage::disk('public')->url($path),
        ]);
    }

    /**
     * Delete the user's avatar.
     */
    public function destroyAvatar(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null, 'avatar_metadata' => null]);
        }

        return response()->json([
            'success'    => true,
            'avatar_url' => $user->avatarUrl(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
