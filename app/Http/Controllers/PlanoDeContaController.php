<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Jobs\SyncPlanoDeContaFiscautJob;

class PlanoDeContaController extends Controller
{
    public function getPlanoDeContas(Request $request)
    {

        $validated = $request->validate([
            'cgce_emp' => 'required',
            'sync' => 'required|boolean',
        ], [
            'cgce_emp.required' => 'O campo cgce_emp é obrigatório.',
        ]);

        $empresa = Empresa::where('cgce_emp', $validated['cgce_emp'])
            ->first();

        if ($empresa) {

            SyncPlanoDeContaFiscautJob::dispatch($empresa);

            $empresa->updated_at = now();
            $empresa->sync = $validated['sync'];
            $empresa->save();

            return response()->json([
                'status' => true,
                'message' => "Empresa recuperada com sucesso",
                'data' => $empresa
            ], 200);
        }


        return response()->json([
            'status' => false,
            'message' => "Empresa não encontrada",
        ], 400);
    }
}
