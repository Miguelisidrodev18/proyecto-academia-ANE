<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family: DejaVu Sans, sans-serif; }
body { font-size:10px; color:#1F2937; background:#fff; }

.page-header {
    background: linear-gradient(135deg, #082B59, #30A9D9);
    padding: 18px 24px;
    color: #fff;
    margin-bottom: 16px;
}
.page-header .logo-row { display:table; width:100%; }
.page-header .logo-cell { display:table-cell; vertical-align:middle; }
.academia-name { font-size:18px; font-weight:bold; letter-spacing:1px; }
.academia-sub  { font-size:10px; color:#A8D8EF; margin-top:2px; }
.report-title  { font-size:12px; font-weight:bold; color:#0BC4D9; margin-top:6px; text-transform:uppercase; letter-spacing:1px; }

.info-grid {
    display:table;
    width:100%;
    border-collapse:collapse;
    margin-bottom:14px;
    background:#EBF5FB;
    border:1px solid #BFDFEF;
    border-radius:4px;
}
.info-row { display:table-row; }
.info-cell {
    display:table-cell;
    padding:6px 10px;
    width:25%;
    vertical-align:middle;
}
.info-label { font-size:8px; font-weight:bold; color:#30A9D9; text-transform:uppercase; }
.info-value { font-size:10px; color:#082B59; font-weight:bold; margin-top:2px; }

.stats-row {
    display:table;
    width:100%;
    border-collapse:separate;
    border-spacing:6px;
    margin-bottom:14px;
}
.stat-box {
    display:table-cell;
    text-align:center;
    padding:10px 6px;
    border-radius:6px;
    font-weight:bold;
}
.stat-box .num { font-size:22px; font-weight:bold; }
.stat-box .lbl { font-size:8px; text-transform:uppercase; margin-top:3px; }
.stat-presente    { background:#D1FAE5; color:#065F46; }
.stat-tardanza    { background:#FEF3C7; color:#92400E; }
.stat-justificado { background:#DBEAFE; color:#1E40AF; }
.stat-ausente     { background:#FEE2E2; color:#991B1B; }
.stat-pct         { background:#082B59; color:#fff; }

table.main {
    width:100%;
    border-collapse:collapse;
    font-size:9px;
}
table.main thead tr {
    background:#082B59;
    color:#fff;
}
table.main thead th {
    padding:7px 6px;
    text-align:center;
    font-size:8px;
    font-weight:bold;
    text-transform:uppercase;
    letter-spacing:0.5px;
}
table.main tbody tr:nth-child(even) { background:#F8FBFF; }
table.main tbody tr:nth-child(odd)  { background:#fff; }
table.main tbody td { padding:6px 6px; vertical-align:middle; }
table.main tbody td.center { text-align:center; }

.badge {
    display:inline-block;
    padding:2px 8px;
    border-radius:12px;
    font-size:8px;
    font-weight:bold;
    text-align:center;
}
.badge-presente    { background:#D1FAE5; color:#065F46; }
.badge-tardanza    { background:#FEF3C7; color:#92400E; }
.badge-justificado { background:#DBEAFE; color:#1E40AF; }
.badge-ausente     { background:#FEE2E2; color:#991B1B; }
.badge-default     { background:#F3F4F6; color:#374151; }

.section-title {
    background:#30A9D9;
    color:#fff;
    font-size:9px;
    font-weight:bold;
    padding:5px 10px;
    border-radius:4px;
    margin-bottom:8px;
    text-transform:uppercase;
    letter-spacing:1px;
}
.footer {
    margin-top:20px;
    border-top:2px solid #082B59;
    padding-top:8px;
    display:table;
    width:100%;
    color:#6B7280;
    font-size:8px;
}
.footer-left  { display:table-cell; }
.footer-right { display:table-cell; text-align:right; }
</style>
</head>
<body>

{{-- HEADER --}}
<div class="page-header">
    <div class="academia-name">ACADEMIA NUEVA ERA</div>
    <div class="academia-sub">Sistema de Gestión Académica</div>
    <div class="report-title">Reporte de Asistencia — Por Alumno</div>
</div>

{{-- INFO DEL ALUMNO --}}
<div class="info-grid">
    <div class="info-row">
        <div class="info-cell">
            <div class="info-label">Alumno</div>
            <div class="info-value">{{ $alumno->nombreCompleto() }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">DNI</div>
            <div class="info-value">{{ $alumno->dni }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Tipo</div>
            <div class="info-value">{{ ucfirst($alumno->tipo) }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Generado</div>
            <div class="info-value">{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>
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
            <div class="info-label">Tipo de Curso</div>
            <div class="info-value">{{ ucfirst($curso->tipo ?? '—') }}</div>
        </div>
        <div class="info-cell">
            <div class="info-label">Total Clases</div>
            <div class="info-value">{{ $stats['total'] }}</div>
        </div>
    </div>
</div>

{{-- STATS --}}
<div class="stats-row">
    <div class="stat-box stat-presente">
        <div class="num">{{ $stats['presente'] }}</div>
        <div class="lbl">Presentes</div>
    </div>
    <div class="stat-box stat-tardanza">
        <div class="num">{{ $stats['tardanza'] }}</div>
        <div class="lbl">Tardanzas</div>
    </div>
    <div class="stat-box stat-justificado">
        <div class="num">{{ $stats['justificado'] }}</div>
        <div class="lbl">Justificados</div>
    </div>
    <div class="stat-box stat-ausente">
        <div class="num">{{ $stats['ausente'] }}</div>
        <div class="lbl">Ausentes</div>
    </div>
    <div class="stat-box stat-pct">
        <div class="num">{{ $stats['pct'] }}%</div>
        <div class="lbl">% Asistencia</div>
    </div>
</div>

{{-- TABLA --}}
<div class="section-title">Detalle de clases</div>
<table class="main">
    <thead>
        <tr>
            <th style="width:4%">#</th>
            <th style="width:12%">Fecha</th>
            <th style="width:38%">Clase</th>
            <th style="width:13%">Estado</th>
            <th style="width:10%">Hora</th>
            <th style="width:23%">Observación</th>
        </tr>
    </thead>
    <tbody>
        @foreach($clases as $i => $clase)
            @php $asis = $asistencias->get($clase->id); @endphp
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ $clase->fecha->format('d/m/Y') }}</td>
                <td>{{ $clase->titulo }}</td>
                <td class="center">
                    @if($asis)
                        <span class="badge badge-{{ $asis->estado }}">{{ strtoupper($asis->estado) }}</span>
                    @else
                        <span class="badge badge-default">SIN REG.</span>
                    @endif
                </td>
                <td class="center">{{ ($asis && $asis->hora_ingreso) ? $asis->hora_ingreso->format('H:i') : '—' }}</td>
                <td>{{ $asis?->observacion ?? '—' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <div class="footer-left">Academia Nueva Era — Reporte generado el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</div>
    <div class="footer-right">Documento confidencial de uso interno</div>
</div>
</body>
</html>
