{{-- Partial compartido por create y edit --}}
{{-- Variables: $alumnos, $planes, $matricula (edit), $alumnoSeleccionado (create) --}}

@php $editing = isset($matricula); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-5"
     x-data="{
         planId: '{{ old('plan_id', $matricula->plan_id ?? '') }}',
         planes: {{ $planes->map(fn($p) => ['id' => $p->id, 'nombre' => $p->nombre, 'precio' => $p->precio, 'duracion' => $p->duracion_meses])->toJson() }},
         get planSeleccionado() { return this.planes.find(p => p.id == this.planId) ?? null; }
     }">

    {{-- Alumno --}}
    @if($editing)
        {{-- En edición el alumno no cambia --}}
        <div class="md:col-span-2 flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
            <div class="w-10 h-10 rounded-full bg-primary-dark/10 flex items-center justify-center font-bold text-primary-dark">
                {{ $matricula->alumno->inicial() }}
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">{{ $matricula->alumno->nombreCompleto() }}</p>
                <p class="text-xs text-gray-400 font-mono">DNI: {{ $matricula->alumno->dni }}</p>
            </div>
            <span class="ml-auto text-xs text-gray-400 italic">Alumno no editable</span>
            <input type="hidden" name="alumno_id" value="{{ $matricula->alumno_id }}">
        </div>
    @else
        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Alumno <span class="text-red-500">*</span>
            </label>
            <select name="alumno_id"
                    @class([
                        'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                        'focus:border-accent focus:ring-2 focus:ring-accent/20',
                        'border-red-400 bg-red-50' => $errors->has('alumno_id'),
                        'border-gray-200'           => !$errors->has('alumno_id'),
                    ])>
                <option value="">— Selecciona un alumno —</option>
                @foreach($alumnos as $alumno)
                    <option value="{{ $alumno->id }}"
                        {{ old('alumno_id', $alumnoSeleccionado?->id) == $alumno->id ? 'selected' : '' }}>
                        {{ $alumno->nombreCompleto() }} — DNI {{ $alumno->dni }}
                    </option>
                @endforeach
            </select>
            @error('alumno_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
    @endif

    {{-- Plan --}}
    @if($editing)
        {{-- En edición el plan no cambia --}}
        <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
            <p class="text-xs text-gray-400 mb-1">Plan</p>
            <p class="font-semibold text-gray-800">{{ $matricula->plan->nombre }}</p>
            <p class="text-xs text-gray-500">{{ $matricula->plan->duracion_meses }} mes(es) · {{ $matricula->plan->precioFormateado() }}</p>
            <span class="text-xs text-gray-400 italic">No editable</span>
        </div>
    @else
        <div x-model="planId">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
                Plan <span class="text-red-500">*</span>
            </label>
            <select name="plan_id"
                    x-model="planId"
                    @class([
                        'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                        'focus:border-accent focus:ring-2 focus:ring-accent/20',
                        'border-red-400 bg-red-50' => $errors->has('plan_id'),
                        'border-gray-200'           => !$errors->has('plan_id'),
                    ])>
                <option value="">— Selecciona un plan —</option>
                @foreach($planes as $plan)
                    <option value="{{ $plan->id }}"
                        {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                        {{ $plan->nombre }} — {{ $plan->precioFormateado() }} · {{ $plan->duracion_meses }} mes(es)
                    </option>
                @endforeach
            </select>
            @error('plan_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            {{-- Preview plan seleccionado --}}
            <div x-show="planSeleccionado" x-cloak
                 class="mt-2 p-3 bg-accent/5 border border-accent/20 rounded-xl text-xs text-gray-600">
                <span class="font-semibold text-accent">Plan seleccionado:</span>
                <span x-text="planSeleccionado?.nombre"></span> ·
                Duración: <span x-text="planSeleccionado?.duracion + ' mes(es)'"></span> ·
                Precio: S/. <span x-text="parseFloat(planSeleccionado?.precio ?? 0).toFixed(2)"></span>
            </div>
        </div>
    @endif

    {{-- Fecha de inicio --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Fecha de inicio <span class="text-red-500">*</span>
        </label>
        <input type="date" name="fecha_inicio"
               value="{{ old('fecha_inicio', $editing ? $matricula->fecha_inicio?->format('Y-m-d') : date('Y-m-d')) }}"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('fecha_inicio'),
                   'border-gray-200'           => !$errors->has('fecha_inicio'),
               ])>
        @error('fecha_inicio')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Días cortesía --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Días de cortesía
            <span class="text-gray-400 font-normal text-xs">(se suman a la fecha fin)</span>
        </label>
        <input type="number" name="dias_cortesia" min="0" max="30"
               value="{{ old('dias_cortesia', $editing ? $matricula->dias_cortesia : 0) }}"
               placeholder="0"
               @class([
                   'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all',
                   'focus:border-accent focus:ring-2 focus:ring-accent/20',
                   'border-red-400 bg-red-50' => $errors->has('dias_cortesia'),
                   'border-gray-200'           => !$errors->has('dias_cortesia'),
               ])>
        @error('dias_cortesia')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tipo de pago --}}
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Tipo de pago <span class="text-red-500">*</span>
        </label>
        <select name="tipo_pago"
                @class([
                    'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                    'focus:border-accent focus:ring-2 focus:ring-accent/20',
                    'border-red-400 bg-red-50' => $errors->has('tipo_pago'),
                    'border-gray-200'           => !$errors->has('tipo_pago'),
                ])>
            @foreach(['completo' => 'Pago completo', 'mensual' => 'Mensual', 'cuotas' => 'En cuotas'] as $val => $label)
                <option value="{{ $val }}"
                    {{ old('tipo_pago', $editing ? $matricula->tipo_pago : 'completo') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('tipo_pago')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Estado (solo edición) --}}
    @if($editing)
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Estado <span class="text-red-500">*</span>
        </label>
        <select name="estado"
                @class([
                    'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all bg-white',
                    'focus:border-accent focus:ring-2 focus:ring-accent/20',
                    'border-red-400 bg-red-50' => $errors->has('estado'),
                    'border-gray-200'           => !$errors->has('estado'),
                ])>
            @foreach(['activa' => 'Activa', 'vencida' => 'Vencida', 'suspendida' => 'Suspendida', 'pendiente' => 'Pendiente'] as $val => $label)
                <option value="{{ $val }}" {{ old('estado', $matricula->estado) === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('estado')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    @endif

    {{-- Observaciones --}}
    <div class="{{ $editing ? '' : 'md:col-span-2' }}">
        <label class="block text-sm font-semibold text-gray-700 mb-1">
            Observaciones
        </label>
        <textarea name="observaciones" rows="3"
                  placeholder="Notas adicionales sobre la matrícula..."
                  @class([
                      'w-full px-4 py-2.5 rounded-xl border text-sm outline-none transition-all resize-none',
                      'focus:border-accent focus:ring-2 focus:ring-accent/20',
                      'border-red-400 bg-red-50' => $errors->has('observaciones'),
                      'border-gray-200'           => !$errors->has('observaciones'),
                  ])>{{ old('observaciones', $matricula->observaciones ?? '') }}</textarea>
        @error('observaciones')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>
