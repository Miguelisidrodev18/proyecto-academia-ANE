<?php

namespace App\Exports;

use App\Models\Curso;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReporteCursoDetalleSheet implements FromArray, WithTitle, WithEvents
{
    private Curso $curso;
    private int   $lastRow   = 0;
    private int   $lastCol   = 0;

    public function __construct(Curso $curso)
    {
        $this->curso = $curso;
    }

    public function title(): string { return 'Detalle por Alumno'; }

    public function array(): array
    {
        $clases = $this->curso->clases;

        // Mapa de alumnos únicos
        $alumnosMap = collect();
        foreach ($clases as $clase) {
            foreach ($clase->asistencias as $a) {
                if ($a->alumno) $alumnosMap[$a->alumno_id] = $a->alumno;
            }
        }
        $alumnos = $alumnosMap->sortBy('user.name');

        $totalClases  = $clases->count();
        $totalAlumnos = $alumnos->count();
        $rows = [];

        // Cabecera curso
        $rows[] = array_merge(['ACADEMIA NUEVA ERA — DETALLE DE ASISTENCIA POR ALUMNO'], array_fill(0, $totalClases + 3, ''));
        $rows[] = array_fill(0, $totalClases + 4, '');
        $rows[] = array_merge(['CURSO:', $this->curso->nombre, '', 'ALUMNOS:', $totalAlumnos, 'CLASES:', $totalClases], array_fill(0, max(0, $totalClases - 3), ''));
        $rows[] = array_fill(0, $totalClases + 4, '');

        // Encabezado de columnas
        $headerRow = ['#', 'ALUMNO', 'DNI'];
        foreach ($clases as $clase) {
            $headerRow[] = $clase->fecha->format('d/m');
        }
        $headerRow[] = 'ASIST.';
        $headerRow[] = '%';
        $rows[] = $headerRow;

        // Filas de alumnos
        $i = 1;
        foreach ($alumnos as $alumno) {
            $asistencias = $alumno->asistencias->keyBy('clase_id');
            $row = [$i++, $alumno->nombreCompleto(), $alumno->dni];

            $presentes = 0;
            foreach ($clases as $clase) {
                $asis   = $asistencias->get($clase->id);
                $estado = $asis ? strtoupper(substr($asis->estado, 0, 1)) : '—'; // P, A, T, J
                if ($asis && in_array($asis->estado, ['presente', 'tardanza'])) $presentes++;
                $row[] = $estado;
            }

            $pct   = $totalClases > 0 ? round(($presentes / $totalClases) * 100, 1) : 0;
            $row[] = $presentes;
            $row[] = "{$pct}%";
            $rows[] = $row;
        }

        $this->lastRow = count($rows);
        $this->lastCol = $totalClases + 5; // #, Alumno, DNI + clases + Asist + %
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($this->lastCol);

                // Título
                $sheet->mergeCells("A1:{$colLetter}1");
                $sheet->getStyle('A1')->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 13],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Info curso
                $sheet->getStyle('A3:G3')->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EBF5FB']],
                    'font' => ['bold' => true],
                ]);

                // Header tabla (fila 5)
                $sheet->getStyle("A5:{$colLetter}5")->applyFromArray([
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '082B59']],
                    'font'      => ['color' => ['rgb' => 'FFFFFF'], 'bold' => true, 'size' => 9],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
                ]);

                // Datos: colorear columna % y celdas de estado
                $dataStart = 6;
                for ($r = $dataStart; $r <= $this->lastRow; $r++) {
                    $bg = ($r % 2 === 0) ? 'FFFFFF' : 'F8FBFF';
                    $sheet->getStyle("A{$r}:C{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                    ]);

                    // Columna % (última)
                    $pct = (float) str_replace('%', '', $sheet->getCell("{$colLetter}{$r}")->getValue());
                    $colorPct = $pct >= 80 ? 'D1FAE5' : ($pct >= 60 ? 'FEF3C7' : 'FEE2E2');
                    $sheet->getStyle("{$colLetter}{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colorPct]],
                        'font' => ['bold' => true],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);

                    // Colorear celdas de estado (columnas D en adelante hasta penúltima)
                    for ($c = 4; $c <= $this->lastCol - 2; $c++) {
                        $colL  = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                        $val   = strtolower($sheet->getCell("{$colL}{$r}")->getValue());
                        $color = match ($val) {
                            'p'     => 'D1FAE5',
                            't'     => 'FEF3C7',
                            'j'     => 'DBEAFE',
                            'a'     => 'FEE2E2',
                            default => $bg,
                        };
                        $sheet->getStyle("{$colL}{$r}")->applyFromArray([
                            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $color]],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                        ]);
                    }
                }

                // Bordes tabla
                if ($this->lastRow >= $dataStart) {
                    $sheet->getStyle("A5:{$colLetter}{$this->lastRow}")->applyFromArray([
                        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
                    ]);
                }

                // Anchos
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(32);
                $sheet->getColumnDimension('C')->setWidth(12);
                for ($c = 4; $c <= $this->lastCol; $c++) {
                    $colL = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
                    $sheet->getColumnDimension($colL)->setWidth(8);
                }

                $sheet->freezePane('D6');
            },
        ];
    }
}
