@extends('layouts.dashboard')
@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- ── Foto de perfil ──────────────────────────────────────────────── --}}
    @include('profile.partials.update-avatar-form')

    {{-- ── Información personal ────────────────────────────────────────── --}}
    <div class="p-6 bg-white shadow sm:rounded-2xl">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    {{-- ── Cambiar contraseña ───────────────────────────────────────────── --}}
    <div class="p-6 bg-white shadow sm:rounded-2xl">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    {{-- ── Eliminar cuenta ──────────────────────────────────────────────── --}}
    <div class="p-6 bg-white shadow sm:rounded-2xl">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>
@endsection
