<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportEmpresa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:empresa';

    protected $description = 'Import empresas from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = 'bethadba.geempre';

        $rows = DB::connection('odbc')
            ->table($tableName)
            ->get();

        foreach ($rows as $key => $row) {
            $row->razao_emp = removeCaracteresEspeciais($row->razao_emp);

            $this->info('Empresa: '.$row->razao_emp.' Código: '.$row->codi_emp);

            Empresa::updateOrCreate(
                ['codi_emp' => $row->codi_emp],
                [
                    'codi_emp' => $row->codi_emp,
                    'razao_emp' => $row->razao_emp,
                    'cgce_emp' => $row->cgce_emp,
                    'iest_emp' => $row->iest_emp,
                    'imun_emp' => $row->imun_emp,
                    'codi_mun' => $row->codi_mun,
                ]
            );
        }
    }
}
