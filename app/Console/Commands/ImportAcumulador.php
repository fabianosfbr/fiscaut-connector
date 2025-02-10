<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Acumulador;
use App\Models\Cliente;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAcumulador extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:acumulador';

    protected $description = 'Import acumuladores from Dominio ODBC to Fiscaut Connector';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tableName = 'bethadba.efacumuladores';

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();

        foreach ($empresas as $empresa) {
            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->get();

            foreach ($rows as $key => $row) {
               
                $row->nome_acu = removeCaracteresEspeciais($row->nome_acu);

                $this->info('Cliente: '.$row->nome_acu);

                Acumulador::updateOrCreate(
                    [
                        'codi_acu' => $row->codi_acu,
                        'codi_emp' => $empresa->codi_emp,
                    ],
                    [
                        'codi_acu' => $row->codi_acu,
                        'nome_acu' => $row->nome_acu,
                        'descricao_acu' => $row->descricao_acu,
                        'codi_emp' => $empresa->codi_emp,
                    ]
                );
            }
        }
    }
}
