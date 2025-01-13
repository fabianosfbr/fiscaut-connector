<?php

namespace App\Console\Commands;

use PDO;
use Exception;
use PDOException;
use App\Models\Empresa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class ImportEmpresas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:empresas';
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

            $row->razao_emp = $this->removeCaracteresEspeciais($row->razao_emp);

            $this->info('Empresa: ' . $row->razao_emp . ' Código: ' . $row->codi_emp);


             Empresa::updateOrCreate(
                ['codi_emp' => $row->codi_emp],
                ['razao_emp' => $row->razao_emp]
            );
        }


    }


}
