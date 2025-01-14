<?php

namespace App\Console\Commands;

use App\Models\Cliente;
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

        $empresas = Empresa::where('sync', true)
            ->where('cliente', true)
            ->get();


        foreach ($empresas as $empresa) {

            $rows = DB::connection('odbc')
                ->table($tableName)
                ->where('codi_emp', $empresa->codi_emp)
                ->get();


            foreach ($rows as $key => $row) {

                $this->info('Empresa: ' . $row->nome_cli . ' Código: ' . $row->codi_emp);


                Cliente::updateOrCreate(
                    ['codi_emp' => $row->codi_emp],
                    [
                        'nome_cli' => $row->nome_cli,
                        'cgce_cli' => $row->cgce_cli,
                    ]
                );
            }
        }


    }
}
