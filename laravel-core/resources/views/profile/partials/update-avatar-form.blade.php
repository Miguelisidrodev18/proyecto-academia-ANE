@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
<style>
    /* ── Animated gradient ring ── */
    @keyframes ring-spin {
        0%   { background-position: 0%   50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0%   50%; }
    }
    @keyframes ring-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(48,169,217,0); }
        50%       { box-shadow: 0 0 0 8px rgba(48,169,217,0.25); }
    }
    @keyframes slide-up-fade {
        from { opacity:0; transform:translateY(12px); }
        to   { opacity:1; transform:translateY(0); }
    }
    @keyframes check-pop {
        0%   { transform: scale(0) rotate(-30deg); opacity:0; }
        70%  { transform: scale(1.2) rotate(5deg);  opacity:1; }
        100% { transform: scale(1) rotate(0deg);   opacity:1; }
    }
    @keyframes sparkle {
        0%, 100% { opacity:0; transform: scale(0) rotate(0deg); }
        50%       { opacity:1; transform: scale(1) rotate(180deg); }
    }
    @keyframes msg-fade {
        0%   { opacity:0; transform:translateY(6px); }
        15%  { opacity:1; transform:translateY(0); }
        85%  { opacity:1; transform:translateY(0); }
        100% { opacity:0; transform:translateY(-6px); }
    }

    .avatar-ring {
        background: linear-gradient(270deg, #082B59, #30A9D9, #0BC4D9, #0DD0D0, #30A9D9, #082B59);
        background-size: 400% 400%;
        animation: ring-spin 5s ease infinite, ring-pulse 3s ease-in-out infinite;
        border-radius: 9999px;
        padding: 4px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .avatar-ring:hover {
        transform: scale(1.04);
        box-shadow: 0 0 28px rgba(11,196,217,0.5);
    }
    .avatar-card {
        animation: slide-up-fade 0.6s ease both;
    }
    .check-icon { animation: check-pop 0.5s cubic-bezier(0.34,1.56,0.64,1) both; }

    .sparkle-1 { animation: sparkle 1.4s ease 0.1s both; }
    .sparkle-2 { animation: sparkle 1.4s ease 0.35s both; }
    .sparkle-3 { animation: sparkle 1.4s ease 0.6s both; }

    .motivational-msg {
        animation: msg-fade 4s ease infinite;
    }

    /* Cropper overrides */
    .cropper-view-box,
    .cropper-face {
        border-radius: 50% !important;
    }
    .cropper-view-box { outline-color: #30A9D9; }
    .cropper-line, .cropper-point { background-color: #30A9D9; }
</style>
@endpush

@php
    $hasAvatar = (bool) $user->avatar;
    $avatarUrl = $user->avatarUrl();

    $roleLabels = [
        'admin'          => ['label' => 'Administrador', 'bg' => 'bg-red-100',    'text' => 'text-red-700'],
        'docente'        => ['label' => 'Docente',        'bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
        'alumno'         => ['label' => 'Alumno',         'bg' => 'bg-green-100',  'text' => 'text-green-700'],
        'representante'  => ['label' => 'Representante',  'bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
    ];
    $roleInfo = $roleLabels[$user->role] ?? ['label' => ucfirst($user->role ?? 'Usuario'), 'bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
@endphp

<div
    x-data="avatarManager({
        currentAvatar: '{{ $avatarUrl }}',
        uploadUrl:     '{{ route('profile.avatar.update') }}',
        deleteUrl:     '{{ route('profile.avatar.destroy') }}',
        csrfToken:     '{{ csrf_token() }}',
    })"
    class="avatar-card"
>
    {{-- ════════════════════════════════════════════
         PROFILE CARD
    ════════════════════════════════════════════ --}}
    <div class="bg-white shadow sm:rounded-2xl overflow-hidden">

        {{-- Header decorativo --}}
        <div class="h-28 relative" style="background: linear-gradient(135deg, #082B59 0%, #30A9D9 60%, #0BC4D9 100%);">
            {{-- Circles decorativos --}}
            <div class="absolute -top-6 -right-6 w-32 h-32 rounded-full opacity-10 bg-white"></div>
            <div class="absolute top-4 right-16 w-14 h-14 rounded-full opacity-10 bg-white"></div>
            <div class="absolute -bottom-3 left-8 w-20 h-20 rounded-full opacity-10 bg-white"></div>
        </div>

        <div class="px-6 pb-8">

            {{-- Avatar + botones (fila: avatar izq, botones der) --}}
            <div class="flex items-end justify-between -mt-12">

                {{-- Avatar clickeable --}}
                <div class="relative flex-shrink-0 cursor-pointer group" @click="openModal()">
                    <div class="avatar-ring">
                        <img
                            :src="currentAvatar"
                            src="{{ $avatarUrl }}"
                            alt="Foto de perfil"
                            class="js-user-avatar w-24 h-24 rounded-full object-cover border-4 border-white bg-gray-100 block"
                        />
                    </div>
                    {{-- Overlay cámara --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white drop-shadow" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-white text-xs mt-1 font-medium drop-shadow">Cambiar</span>
                    </div>
                </div>

                {{-- Botones alineados abajo a la derecha --}}
                <div class="flex gap-2 pb-1">
                    <button
                        @click="openModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0"
                        style="background: linear-gradient(135deg, #082B59, #30A9D9);"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586A2 2 0 0110 11h4a2 2 0 011.414.586L20 16M14 8a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ $hasAvatar ? 'Cambiar foto' : 'Subir foto' }}
                    </button>

                    @if($hasAvatar)
                    <button
                        @click="deleteAvatar()"
                        :disabled="deleting"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-all duration-200 disabled:opacity-50"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span x-text="deleting ? 'Eliminando...' : 'Eliminar'"></span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Nombre, email, rol — siempre en el área blanca, sin riesgo de clipping --}}
            <div class="mt-4">
                <h3 class="text-xl font-bold text-gray-900 leading-snug">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-xs font-semibold {{ $roleInfo['bg'] }} {{ $roleInfo['text'] }}">
                    {{ $roleInfo['label'] }}
                </span>
            </div>

            {{-- ── Mensaje motivacional ────────────────────────────────── --}}
            <div class="mt-6 flex items-center gap-3 px-4 py-3 rounded-xl" style="background: linear-gradient(135deg, rgba(8,43,89,0.06), rgba(48,169,217,0.1));">
                <div class="w-9 h-9 flex-shrink-0 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #082B59, #30A9D9);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <p x-text="currentMessage" class="text-sm font-medium text-gray-700 motivational-msg"></p>
            </div>

            {{-- ── Progreso de perfil ─────────────────────────────────── --}}
            <div class="mt-5">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Completitud del perfil</span>
                    <span class="text-sm font-bold" style="color: #082B59;">{{ $profileCompletion }}%</span>
                </div>
                <div class="h-2.5 bg-gray-100 rounded-full overflow-hidden">
                    <div
                        class="h-full rounded-full transition-all duration-1000 ease-out"
                        style="width: {{ $profileCompletion }}%; background: linear-gradient(90deg, #082B59, #30A9D9, #0BC4D9);"
                    ></div>
                </div>
                <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1">
                    @php
                        $steps = [
                            ['done' => (bool)$user->avatar, 'label' => 'Foto de perfil (45%)'],
                            ['done' => (bool)$user->name,   'label' => 'Nombre (30%)'],
                            ['done' => (bool)$user->role,   'label' => 'Rol asignado (25%)'],
                        ];
                    @endphp
                    @foreach($steps as $step)
                    <span class="flex items-center gap-1 text-xs {{ $step['done'] ? 'text-green-600' : 'text-gray-400' }}">
                        @if($step['done'])
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        @else
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="1.5"><circle cx="10" cy="10" r="8"/></svg>
                        @endif
                        {{ $step['label'] }}
                    </span>
                    @endforeach
                </div>
            </div>

        </div>{{-- /px-6 pb-8 --}}
    </div>{{-- /card --}}


    {{-- ════════════════════════════════════════════
         MODAL
    ════════════════════════════════════════════ --}}
    <template x-teleport="body">
        <div
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(8,43,89,0.6); backdrop-filter: blur(6px);"
            @click.self="closeModal()"
        >
            <div
                x-show="showModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden"
            >
                {{-- Modal header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        {{-- Steps indicator --}}
                        <div class="flex items-center gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="step === 'upload' ? 'bg-primary-light scale-125' : 'bg-gray-200'"></div>
                            <div class="w-6 h-0.5 rounded" :class="step !== 'upload' ? 'bg-primary-light' : 'bg-gray-200'"></div>
                            <div class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="step === 'crop' ? 'bg-primary-light scale-125' : (step === 'success' ? 'bg-primary-light' : 'bg-gray-200')"></div>
                            <div class="w-6 h-0.5 rounded" :class="step === 'success' ? 'bg-primary-light' : 'bg-gray-200'"></div>
                            <div class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="step === 'success' ? 'bg-green-500 scale-125' : 'bg-gray-200'"></div>
                        </div>
                        <h3 class="text-base font-bold text-gray-900">
                            <span x-show="step === 'upload'">Subir foto de perfil</span>
                            <span x-show="step === 'crop'">Ajustar recorte</span>
                            <span x-show="step === 'success'">¡Listo!</span>
                        </h3>
                    </div>
                    <button @click="closeModal()" x-show="step !== 'success'" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- ── STEP 1: Upload ── --}}
                <div x-show="step === 'upload'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="p-6">
                        {{-- Dropzone --}}
                        <label
                            for="avatar-file-input"
                            class="flex flex-col items-center justify-center gap-4 w-full h-52 rounded-2xl border-2 border-dashed cursor-pointer transition-all duration-300"
                            :class="dragover
                                ? 'border-primary-light bg-blue-50 scale-[1.02]'
                                : 'border-gray-200 bg-gray-50 hover:border-primary-light hover:bg-blue-50/50'"
                            @dragover.prevent="dragover = true"
                            @dragleave.prevent="dragover = false"
                            @drop.prevent="handleDrop($event)"
                        >
                            <div
                                class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-300"
                                :class="dragover ? 'scale-110' : ''"
                                style="background: linear-gradient(135deg, rgba(8,43,89,0.1), rgba(48,169,217,0.15));"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="#30A9D9" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-gray-700">Arrastra tu foto aquí</p>
                                <p class="text-xs text-gray-400 mt-1">o <span class="text-primary-light font-medium underline underline-offset-2">selecciona un archivo</span></p>
                            </div>
                            <div class="flex gap-2">
                                @foreach(['JPG', 'PNG', 'WebP'] as $fmt)
                                <span class="px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-500">{{ $fmt }}</span>
                                @endforeach
                                <span class="px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-500">Máx. 5 MB</span>
                            </div>
                            <input
                                id="avatar-file-input"
                                type="file"
                                accept="image/jpeg,image/png,image/webp"
                                class="sr-only"
                                @change="handleFileInput($event)"
                            />
                        </label>

                        {{-- Error --}}
                        <div x-show="errorMsg" x-transition class="mt-3 flex items-center gap-2 text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span x-text="errorMsg"></span>
                        </div>
                    </div>

                    {{-- Tip IA --}}
                    <div class="mx-6 mb-6 flex items-center gap-2 text-xs text-gray-500 bg-gray-50 rounded-xl px-3 py-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 text-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Sube una foto clara de tu rostro para habilitar el reconocimiento automático de asistencia.
                    </div>
                </div>

                {{-- ── STEP 2: Crop ── --}}
                <div x-show="step === 'crop'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="px-6 pt-4 pb-2">
                        <p class="text-xs text-center text-gray-500 mb-3">Arrastra para centrar tu rostro dentro del círculo</p>
                        <div class="w-full rounded-xl overflow-hidden bg-gray-900" style="max-height: 320px;">
                            <img x-ref="cropperImage" :src="rawImage" alt="Preview" class="max-w-full block" />
                        </div>
                    </div>
                    <div class="flex gap-3 px-6 py-4 border-t border-gray-100">
                        <button
                            @click="step = 'upload'; if(cropper){ cropper.destroy(); cropper = null; }"
                            class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors"
                        >← Volver</button>
                        <button
                            @click="cropAndUpload()"
                            :disabled="uploading"
                            class="flex-1 px-4 py-2.5 rounded-xl text-sm font-semibold text-white transition-all duration-200 disabled:opacity-60 hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2"
                            style="background: linear-gradient(135deg, #082B59, #30A9D9);"
                        >
                            <template x-if="!uploading">
                                <span class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Recortar y guardar
                                </span>
                            </template>
                            <template x-if="uploading">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                    Guardando...
                                </span>
                            </template>
                        </button>
                    </div>
                </div>

                {{-- ── STEP 3: Success ── --}}
                <div x-show="step === 'success'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex flex-col items-center justify-center py-12 px-6 text-center relative overflow-hidden">
                        {{-- Background glow --}}
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="w-48 h-48 rounded-full opacity-10" style="background: radial-gradient(circle, #0BC4D9, transparent 70%);"></div>
                        </div>

                        {{-- Sparkles --}}
                        <div class="absolute top-8 left-12 sparkle-1">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div class="absolute top-6 right-10 sparkle-2">
                            <svg class="w-4 h-4 text-primary-light" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>
                        <div class="absolute bottom-10 right-14 sparkle-3">
                            <svg class="w-3.5 h-3.5 text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </div>

                        {{-- Check circle --}}
                        <div class="check-icon w-20 h-20 rounded-full flex items-center justify-center mb-5 relative z-10" style="background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 0 30px rgba(16,185,129,0.4);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 mb-1 relative z-10">¡Foto actualizada!</h3>
                        <p class="text-sm text-gray-500 relative z-10">Tu identidad digital en la academia está lista 🚀</p>
                    </div>
                </div>

            </div>{{-- /modal inner --}}
        </div>{{-- /modal overlay --}}
    </template>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
@endpush
