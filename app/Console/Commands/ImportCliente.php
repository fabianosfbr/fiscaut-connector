<?php

namespace App\Console\Commands;

use PDO;
use Exception;
use PDOException;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class ImportCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cliente';
    protected $description = 'Import clientes from Dominio ODBC to Fiscaut Connector';
    /**
     * Execute the console command.
     */
    public function handle()
    {

        $tableName = 'bethadba.efclientes';

        $rows = DB::connection('odbc')
        ->table($tableName)
        ->first();

        dd($rows);
        foreach ($rows as $key => $row) {

            dd($row);

            $row->razao_emp = removeCaracteresEspeciais($row->razao_emp);

            $this->info('Empresa: ' . $row->razao_emp . ' Código: ' . $row->codi_emp);


             Empresa::updateOrCreate(
                ['codi_emp' => $row->codi_emp],
                [
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
