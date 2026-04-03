<?php

namespace App\Exports;

use App\Models\Alumno;
use App\Models\Curso;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteAlumnoExport implements FromArray, WithTitle, WithEvents
{
    private Alumno $alumno;
    private Curso  $curso;
    private array  $rows = [];

    public function __construct(Alumno $alumno, Curso $curso)
    {
        $this->alumno = $alumno->load('user');
        $this->curso  = $curso->load(['clases' => fn ($q) => $q->orderBy('fecha')]);
    }

    public function title(): string { return 'Reporte Alumno'; }

    public function array(): array
    {
        $clases      = $this->curso->clases;
        $asistencias = $this->alumno->asistencias()
            ->whereIn('clase_id', $clases->pluck('id'))
            ->get()
            ->keyBy('clase_id');

        $total    = $clases->count();
        $presente = $asistencias->where('estado', 'presente')->count();
        $tardanza = $asistencias->where('estado', 'tardanza')->count();
        $ausente  = $asistencias->where('estado', 'ausente')->count();
        $justif   = $asistencias->where('estado', 'justificado')->count();
        $pct      = $total > 0 ? round((($presente + $tardanza) / $total) * 100, 1) : 0;

        $rows = [
            // Fila 1: Título (se mergeará en el evento)
            ['ACADEMIA NUEVA ERA — REPORTE DE ASISTENCIA POR ALUMNO', '', '', '', '', ''],
            // Fila 2: vacía
            ['', '', '', '', '', ''],
            // Filas de datos del alumno
            ['ALUMNO',          $this->alumno->nombreCompleto(), '', 'CURSO',  $this->curso->nombre, ''],
            ['DNI',             $this->alumno->dni,              '', 'NIVEL',  ucfirst($this->curso->nivel), ''],
            ['TIPO',            ucfirst($this->alumno->tipo),    '', 'TIPO',   ucfirst($this->curso->tipo ?? '—'), ''],
            ['FECHA DEL REPORTE', now()->format('d/m/Y H:i'),   '', 'TOTAL CLASES', $total, ''],
            // Fila vacía
            ['', '', '', '', '', ''],
            // Resumen stats
            ['RESUMEN', '', '', '', '', ''],
            ['Presentes', $presente, 'Tardanzas', $tardanza, 'Justificados', $justif],
            ['Ausentes',  $ausente,  '% Asistencia', "{$pct}%", '', ''],
            // Fila vacía
            ['', '', '', '', '', ''],
            // Encabezado de tabla
            ['#', 'FECHA', 'CLASE', 'ESTADO', 'HORA INGRESO', 'OBSERVACIÓN'],
        ];

        $i = 1;
        foreach ($clases as $clase) {
            $asis   = $asistencias->get($clase->id);
            $estado = $asis ? strtoupper($asis->estado) : 'SIN REGISTRO';
            $hora   = ($asis && $asis->hora_ingreso) ? $asis->hora_ingreso->format('H:i') : '—';
            $obs    = $asis?->observacion ?? '—';

            $rows[] = [
                $i++,
                $clase->fecha->format('d/m/Y'),
                $clase->titulo,
                $estado,
                $hora,
                $obs,
            ];
        }

        $this->rows = $rows;
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = count($this->rows) + 1;

                // Merge título
                $sheet->mergeCells('A1:F1');
                $sheet->getStyle('A1')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                // Filas de info del alumno (A3:F8)
                $sheet->getStyle('A3:F8')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF5FB']],
                    'font' => ['size' => 10],
                ]);
                // Labels en negrita
                foreach ([3, 4, 5, 6] as $row) {
                    $sheet->getStyle("A{$row}")->getFont()->setBold(true);
                    $sheet->getStyle("D{$row}")->getFont()->setBold(true);
                }

                // Fila RESUMEN
                $sheet->mergeCells('A9:F9');
                $sheet->getStyle('A9')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '30A9D9']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Stats rows (10-11)
                $sheet->getStyle('A10:F11')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D6EAF8']],
                    'font' => ['size' => 10],
                ]);
                foreach ([10, 11] as $row) {
                    foreach (['A', 'C', 'E'] as $col) {
                        $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
                    }
                }

                // Header tabla (fila 13)
                $sheet->getStyle('A13:F13')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 10],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Filas de datos + colores por estado
                $dataStart = 14;
                for ($r = $dataStart; $r <= $lastRow; $r++) {
                    $estado = strtolower($sheet->getCell("D{$r}")->getValue());
                    $color  = match ($estado) {
                        'presente'    => 'D1FAE5',
                        'tardanza'    => 'FEF3C7',
                        'justificado' => 'DBEAFE',
                        'ausente'     => 'FEE2E2',
                        default       => 'F9FAFB',
                    };

                    $sheet->getStyle("D{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                        'font' => ['bold' => true],
                    ]);

                    // Cebra en otras columnas
                    $bgCebra = ($r % 2 === 0) ? 'FFFFFF' : 'F8FBFF';
                    $sheet->getStyle("A{$r}:C{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgCebra]],
                    ]);
                    $sheet->getStyle("E{$r}:F{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgCebra]],
                    ]);
                }

                // Bordes en la tabla
                if ($lastRow >= $dataStart) {
                    $sheet->getStyle("A13:F{$lastRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                        ],
                    ]);
                }

                // Anchos de columna
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(14);
                $sheet->getColumnDimension('C')->setWidth(38);
                $sheet->getColumnDimension('D')->setWidth(16);
                $sheet->getColumnDimension('E')->setWidth(14);
                $sheet->getColumnDimension('F')->setWidth(30);

                // Centrar columnas A, B, D, E
                foreach (['A', 'B', 'D', 'E'] as $col) {
                    $sheet->getStyle("{$col}1:{$col}{$lastRow}")->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Freeze header
                $sheet->freezePane('A14');
            },
        ];
    }
}
