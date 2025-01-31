<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function getClientes(Request $request)
    {

        $cliente = Cliente::with('empresa')
            ->where('cgce_cli', $request->codi_emp)
            ->first();

        dd($cliente);
        return $request->all();
    }
}
