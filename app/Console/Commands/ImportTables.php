<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:tables';
    protected $description = 'Import tables from Dominio ODBC to Fiscaut Connector';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Connecting to ODBC database...');

        try {
            $odbc = DB::connection('odbc');
            $tables = $odbc->select("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE'");

            $this->info('Found ' . count($tables) . ' tables.');

            $this->info('Connecting to MySQL database...');
        } catch (Exception $e) {
            $this->error('Error connecting to ODBC database or importing tables.');
            $this->error($e->getMessage());
        }
    }
}
