<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: DejaVu Sans, sans-serif; }
body { font-size:9px; color:#1F2937; background:#fff; }
.page-header { background: linear-gradient(135deg, #082B59, #30A9D9); padding:16px 24px; color:#fff; margin-bottom:14px; }
.academia-name { font-size:16px; font-weight:bold; letter-spacing:1px; }
.academia-sub  { font-size:9px; color:#A8D8EF; margin-top:2px; }
.report-title  { font-size:11px; font-weight:bold; color:#0BC4D9; margin-top:5px; text-transform:uppercase; letter-spacing:1px; }
.info-grid { display:table; width:100%; margin-bottom:12px; background:#EBF5FB; border:1px solid #BFDFEF; }
.info-row  { display:table-row; }
.info-cell { display:table-cell; padding:5px 10px; width:20%; }
.info-label { font-size:7px; font-weight:bold; color:#30A9D9; text-transform:uppercase; }
.info-value { font-size:10px; color:#082B59; font-weight:bold; margin-top:1px; }
.stats-row { display:table; width:100%; border-collapse:separate; border-spacing:5px; margin-bottom:12px; }
.stat-box { display:table-cell; text-align:center; padding:8px 4px; border-radius:6px; }
.stat-box .num { font-size:20px; font-weight:bold; }
.stat-box .lbl { font-size:7px; text-transform:uppercase; margin-top:2px; }
.stat-primary { background:#082B59; color:#fff; }
.stat-light   { background:#EBF5FB; color:#082B59; border:1px solid #BFDFEF; }
.stat-green   { background:#D1FAE5; color:#065F46; }
.stat-yellow  { background:#FEF3C7; color:#92400E; }
.stat-red     { background:#FEE2E2; color:#991B1B; }
.section-title { background:#30A9D9; color:#fff; font-size:8px; font-weight:bold; padding:4px 8px; border-radius:3px; margin-bottom:6px; text-transform:uppercase; letter-spacing:1px; }

/* Tabla resumen clases */
table.resumen { width:100%; border-collapse:collapse; font-size:8px; margin-bottom:14px; }
table.resumen thead tr { background:#082B59; color:#fff; }
table.resumen thead th { padding:5px 5px; text-align:center; font-size:7px; font-weight:bold; text-transform:uppercase; }
table.resumen tbody tr:nth-child(even) { background:#F8FBFF; }
table.resumen tbody tr:nth-child(odd)  { background:#fff; }
table.resumen tbody td { padding:4px 5px; vertical-align:middle; }
table.resumen tbody td.center { text-align:center; }

/* Tabla detalle alumnos */
table.detalle { width:100%; border-collapse:collapse; font-size:7.5px; }
table.detalle thead tr { background:#082B59; color:#fff; }
table.detalle thead th { padding:5px 4px; text-align:center; font-size:7px; font-weight:bold; text-transform:uppercase; }
table.detalle tbody tr:nth-child(even) { background:#F8FBFF; }
table.detalle tbody tr:nth-child(odd)  { background:#fff; }
table.detalle tbody td { padding:4px 4px; vertical-align:middle; }
table.detalle tbody td.center { text-align:center; }
.badge-sm { display:inline-block; padding:1px 6px; border-radius:8px; font-size:7px; font-weight:bold; }
.b-p { background:#D1FAE5; color:#065F46; }
.b-t { background:#FEF3C7; color:#92400E; }
.b-j { background:#DBEAFE; color:#1E40AF; }
.b-a { background:#FEE2E2; color:#991B1B; }
.b-ok { background:#D1FAE5; color:#065F46; }
.b-md { background:#FEF3C7; color:#92400E; }
.b-ko { background:#FEE2E2; color:#991B1B; }
.footer { margin-top:16px; border-top:2px solid #082B59; padding-top:6px; display:table; width:100%; color:#6B7280; font-size:7px; }
.footer-left  { display:table-cell; }
.footer-right { display:table-cell; text-align:right; }
</style>
</head>
<body>

<div class="page-header">
    <div class="academia-name">ACADEMIA NUEVA ERA</div>
    <div class="academia-sub">Sistema de Gestión Académica</div>
    <div class="report-title">Reporte General del Curso</div>
</div>

<div class="info-grid">
    <div class="info-row">
        <div class="info-cell">
            <div class="info-label">Curso</div>
            <div class="info-value">{{ $curso->nombre }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Nivel</div>
            <div class="info-value">{{ ucfirst($curso->nivel) }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Tipo</div>
            <div class="info-value">{{ ucfirst($curso->tipo ?? '—') }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Total Clases</div>
            <div class="info-value">{{ $stats['totalClases'] }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Total Alumnos</div>
            <div class="info-value">{{ $stats['totalAlumnos'] }}</div>
        </div>
    </div>
</div>

<div class="stats-row">
    <div class="stat-box stat-primary">
        <div class="num">{{ $stats['totalClases'] }}</div>
        <div class="lbl">Clases</div>
    </div>
    <div class="stat-box stat-light">
        <div class="num">{{ $stats['totalAlumnos'] }}</div>
        <div class="lbl">Alumnos</div>
    </div>
    <div class="stat-box {{ $stats['promedioAsistencia'] >= 80 ? 'stat-green' : ($stats['promedioAsistencia'] >= 60 ? 'stat-yellow' : 'stat-red') }}">
        <div class="num">{{ $stats['promedioAsistencia'] }}%</div>
        <div class="lbl">Asistencia Promedio</div>
    </div>
</div>

{{-- RESUMEN POR CLASE --}}
<div class="section-title">Resumen por clase</div>
<table class="resumen">
    <thead>
        <tr>
            <th style="width:4%">#</th>
            <th style="width:14%">Fecha</th>
            <th style="width:36%">Clase</th>
            <th style="width:10%">Presentes</th>
            <th style="width:10%">Tardanzas</th>
            <th style="width:10%">Justif.</th>
            <th style="width:10%">Ausentes</th>
            <th style="width:10%">% Asist.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clases as $i => $clase)
        @php
            $asis  = $clase->asistencias;
            $pres  = $asis->where('estado','presente')->count();
            $tard  = $asis->where('estado','tardanza')->count();
            $just  = $asis->where('estado','justificado')->count();
            $ause  = $asis->where('estado','ausente')->count();
            $total = $asis->count();
            $pct   = $total > 0 ? round((($pres+$tard)/$total)*100,1) : 0;
        @endphp
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td class="center">{{ $clase->fecha->format('d/m/Y') }}</td>
            <td>{{ $clase->titulo }}</td>
            <td class="center" style="color:#065F46;font-weight:bold;">{{ $pres }}</td>
            <td class="center" style="color:#92400E;font-weight:bold;">{{ $tard }}</td>
            <td class="center" style="color:#1E40AF;font-weight:bold;">{{ $just }}</td>
            <td class="center" style="color:#991B1B;font-weight:bold;">{{ $ause }}</td>
            <td class="center">
                <span class="badge-sm {{ $pct >= 80 ? 'b-ok' : ($pct >= 60 ? 'b-md' : 'b-ko') }}">{{ $pct }}%</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- DETALLE POR ALUMNO --}}
@if($alumnos->count() > 0)
<div class="section-title">Detalle de asistencia por alumno</div>
<table class="detalle">
    <thead>
        <tr>
            <th style="width:4%">#</th>
            <th style="width:30%">Alumno</th>
            <th style="width:9%">DNI</th>
            <th style="width:9%">Pres.</th>
            <th style="width:9%">Tard.</th>
            <th style="width:9%">Just.</th>
            <th style="width:9%">Ausen.</th>
            <th style="width:11%">% Asist.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($alumnos as $i => $alumno)
        @php
            $asis  = $alumno->asistencias->whereIn('clase_id', $clases->pluck('id'));
            $pres  = $asis->where('estado','presente')->count();
            $tard  = $asis->where('estado','tardanza')->count();
            $just  = $asis->where('estado','justificado')->count();
            $ause  = $asis->where('estado','ausente')->count();
            $total = $clases->count();
            $pct   = $total > 0 ? round((($pres+$tard)/$total)*100,1) : 0;
        @endphp
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $alumno->nombreCompleto() }}</td>
            <td class="center">{{ $alumno->dni }}</td>
            <td class="center" style="color:#065F46;font-weight:bold;">{{ $pres }}</td>
            <td class="center" style="color:#92400E;font-weight:bold;">{{ $tard }}</td>
            <td class="center" style="color:#1E40AF;font-weight:bold;">{{ $just }}</td>
            <td class="center" style="color:#991B1B;font-weight:bold;">{{ $ause }}</td>
            <td class="center">
                <span class="badge-sm {{ $pct >= 80 ? 'b-ok' : ($pct >= 60 ? 'b-md' : 'b-ko') }}">{{ $pct }}%</span>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<div class="footer">
    <div class="footer-left">Academia Nueva Era — Reporte generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</div>
    <div class="footer-right">Documento confidencial de uso interno</div>
</div>
</body>
</html>
