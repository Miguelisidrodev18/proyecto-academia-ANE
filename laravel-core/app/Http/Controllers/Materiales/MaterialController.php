<?php

namespace App\Http\Controllers\Materiales;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Material;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(Request $request): View
    {
        $query = Material::with('curso')->orderByDesc('fecha_publicacion');

        if ($request->filled('curso_id')) {
            $query->where('curso_id', $request->curso_id);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('visible')) {
            $query->where('visible', $request->visible === '1');
        }

        $materiales = $query->paginate(15)->withQueryString();
        $cursos     = Curso::where('activo', true)->orderBy('nombre')->get();

        $stats = [
            'total'    => Material::count(),
            'visibles' => Material::where('visible', true)->count(),
            'ocultos'  => Material::where('visible', false)->count(),
        ];

        return view('materiales.index', compact('materiales', 'cursos', 'stats'));
    }

    public function create(Request $request): View
    {
        $cursos            = Curso::where('activo', true)->orderBy('nombre')->get();
        $cursoSeleccionado = $request->filled('curso_id') ? Curso::find($request->curso_id) : null;

        return view('materiales.create', compact('cursos', 'cursoSeleccionado'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'curso_id'          => 'required|exists:cursos,id',
            'titulo'            => 'required|string|max:200',
            'tipo'              => 'required|in:pdf,video,enlace,imagen,otro',
            'url'               => 'required|url|max:1000',
            'fecha_publicacion' => 'required|date',
            'descripcion'       => 'nullable|string|max:1000',
        ]);

        $data['visible'] = $request->boolean('visible', true);

        Material::create($data);

        return redirect()->route('materiales.index', ['curso_id' => $data['curso_id']])
            ->with('success', 'Material "' . $data['titulo'] . '" agregado correctamente.');
    }

    public function show(Material $material): RedirectResponse
    {
        return redirect()->route('materiales.index', ['curso_id' => $material->curso_id]);
    }

    public function edit(Material $material): View
    {
        $cursos = Curso::where('activo', true)->orderBy('nombre')->get();

        return view('materiales.edit', compact('material', 'cursos'));
    }

    public function update(Request $request, Material $material): RedirectResponse
    {
        $data = $request->validate([
            'curso_id'          => 'required|exists:cursos,id',
            'titulo'            => 'required|string|max:200',
            'tipo'              => 'required|in:pdf,video,enlace,imagen,otro',
            'url'               => 'required|url|max:1000',
            'fecha_publicacion' => 'required|date',
            'descripcion'       => 'nullable|string|max:1000',
        ]);

        $material->update($data);

        return redirect()->route('materiales.index', ['curso_id' => $material->curso_id])
            ->with('success', 'Material actualizado correctamente.');
    }

    public function destroy(Material $material): RedirectResponse
    {
        $titulo   = $material->titulo;
        $cursoId  = $material->curso_id;
        $material->delete();

        return redirect()->route('materiales.index', ['curso_id' => $cursoId])
            ->with('success', 'Material "' . $titulo . '" eliminado.');
    }

    public function toggle(Material $material): RedirectResponse
    {
        $material->update(['visible' => ! $material->visible]);
        $msg = $material->visible ? 'Material visible para los alumnos.' : 'Material ocultado.';

        return back()->with('success', $msg);
    }
}
