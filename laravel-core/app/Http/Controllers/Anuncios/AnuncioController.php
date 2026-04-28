<?php

namespace App\Http\Controllers\Anuncios;

use App\Http\Controllers\Controller;
use App\Models\Anuncio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AnuncioController extends Controller
{
    public function index(): View
    {
        $anuncios = Anuncio::orderBy('orden')->orderByDesc('created_at')->get();

        $stats = [
            'total'   => $anuncios->count(),
            'activos' => $anuncios->where('activo', true)->count(),
        ];

        return view('anuncios.index', compact('anuncios', 'stats'));
    }

    public function create(): View
    {
        $siguienteOrden = (Anuncio::max('orden') ?? -1) + 1;
        return view('anuncios.create', compact('siguienteOrden'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        $data['destinatarios'] = $request->input('destinatarios', []);
        $data['activo']        = $request->boolean('activo', true);

        Anuncio::create($data);

        return redirect()->route('anuncios.index')
            ->with('success', 'Anuncio creado correctamente.');
    }

    public function edit(Anuncio $anuncio): View
    {
        return view('anuncios.edit', compact('anuncio'));
    }

    public function update(Request $request, Anuncio $anuncio): RedirectResponse
    {
        $data = $this->validar($request);

        if ($request->hasFile('imagen')) {
            if ($anuncio->imagen) {
                Storage::disk('public')->delete($anuncio->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('anuncios', 'public');
        }

        if ($request->boolean('eliminar_imagen') && $anuncio->imagen) {
            Storage::disk('public')->delete($anuncio->imagen);
            $data['imagen'] = null;
        }

        $data['destinatarios'] = $request->input('destinatarios', []);
        $data['activo']        = $request->boolean('activo', true);

        $anuncio->update($data);

        return redirect()->route('anuncios.index')
            ->with('success', 'Anuncio actualizado correctamente.');
    }

    public function destroy(Anuncio $anuncio): RedirectResponse
    {
        if ($anuncio->imagen) {
            Storage::disk('public')->delete($anuncio->imagen);
        }

        $anuncio->delete();

        return redirect()->route('anuncios.index')
            ->with('success', 'Anuncio eliminado.');
    }

    public function toggle(Anuncio $anuncio): RedirectResponse
    {
        $anuncio->update(['activo' => !$anuncio->activo]);
        $estado = $anuncio->activo ? 'activado' : 'desactivado';

        return back()->with('success', 'Anuncio ' . $estado . '.');
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo'       => ['nullable', 'string', 'max:120'],
            'descripcion'  => ['nullable', 'string', 'max:1000'],
            'imagen'       => ['nullable', 'image', 'max:3072'],
            'link_url'     => ['nullable', 'string', 'max:500'],
            'link_texto'   => ['nullable', 'string', 'max:60'],
            'tipo_link'    => ['nullable', 'in:whatsapp,externo'],
            'orden'        => ['nullable', 'integer', 'min:0', 'max:999'],
            'fecha_inicio' => ['nullable', 'date'],
            'fecha_fin'    => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ]);
    }
}
