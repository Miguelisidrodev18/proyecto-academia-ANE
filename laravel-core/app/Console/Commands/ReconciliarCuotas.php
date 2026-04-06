<?php

namespace App\Console\Commands;

use App\Models\Matricula;
use App\Services\PagoService;
use Illuminate\Console\Command;

class ReconciliarCuotas extends Command
{
    protected $signature = 'cuotas:reconciliar {--dry-run : Mostrar qué se corregiría sin hacer cambios}';

    protected $description = 'Reconcilia el estado de las cuotas según los pagos confirmados de cada matrícula';

    public function handle(PagoService $pagoService): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Modo dry-run: no se realizarán cambios.');
        }

        $this->info('Cargando matrículas...');

        $matriculas = Matricula::with(['pagos', 'cuotas'])->get();
        $total      = $matriculas->count();
        $corregidas = 0;
        $cuotasFix  = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($matriculas as $matricula) {
            $totalPagado = (float) $matricula->pagos->where('estado', 'confirmado')->sum('monto');

            // Detectar cuotas mal marcadas
            $acumulado  = 0.0;
            $necesitaFix = false;

            foreach ($matricula->cuotas->sortBy('numero') as $cuota) {
                $acumulado += (float) $cuota->monto;
                $deberiaSerPagada = $totalPagado >= $acumulado - 0.01;

                if ($deberiaSerPagada && $cuota->estado !== 'pagada') {
                    $necesitaFix = true;
                    $cuotasFix++;
                } elseif (!$deberiaSerPagada && $cuota->estado === 'pagada') {
                    $necesitaFix = true;
                    $cuotasFix++;
                }
            }

            if ($necesitaFix) {
                $corregidas++;
                if (!$dryRun) {
                    $pagoService->reconciliarCuotas($matricula);
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($corregidas === 0) {
            $this->info('Todo está correcto. No se encontraron cuotas mal marcadas.');
        } else {
            $accion = $dryRun ? 'necesitarían corrección' : 'corregidas';
            $this->table(
                ['Detalle', 'Cantidad'],
                [
                    ['Matrículas procesadas',          $total],
                    ["Matrículas con cuotas {$accion}", $corregidas],
                    ["Cuotas {$accion}",                $cuotasFix],
                ]
            );

            if ($dryRun) {
                $this->warn('Ejecuta sin --dry-run para aplicar los cambios.');
            } else {
                $this->info('Reconciliación completada correctamente.');
            }
        }

        return Command::SUCCESS;
    }
}
