<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Uf;                   
use App\Http\Requests\StoreUfRequest; 

/**
 * UfController
 * - A validação de dados está em App\Http\Requests\StoreUfRequest.
 * - O tratamento global de erros foi implementado em bootstrap/app.php.
 */

class UfController extends Controller
{
    /**
     * Lista todas as UFs cadastradas com filtragem por nome e paginação
     */
    public function index(Request $request)
    {
        $query = Uf::orderBy('name', 'asc');

        if ($request->filled('s')) {
            $search_term = $request->get('s');

            $query->where('name', 'ilike', '%' . $search_term . '%')
                ->orWhere('state_code', 'ilike', '%' . $search_term . '%');
        }

        $ufs = $query->paginate(30);

        return response()->json($ufs);
    }

    /**
     * Registro de Ufs 
     */
    public function store(StoreUfRequest $request)
    {
        $uf = Uf::create($request->validated());

        return response()->json($uf, 201);
    }

    /**
     * Retorna a UF específica
     */
    public function show(Uf $uf)
    {
        return response()->json($uf);
    }

    /**
     * Atualiza a UF especifica e retorna os dados
     */
    public function update(StoreUfRequest $request, Uf $uf)
    {
        $uf->update($request->validated());

        return response()->json($uf);
    }

    /**
     * Deleta a UF especifica e retorna mensagem de sucesso
     */
    public function destroy(Uf $uf)
    {
        $uf->delete();

        return response()->json(['message' => 'UF excluída com sucesso.']);
    }
}
