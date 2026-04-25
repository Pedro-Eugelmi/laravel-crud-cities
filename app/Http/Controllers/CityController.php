<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCityRequest; 
use App\Models\City;                   
use Illuminate\Http\JsonResponse;

/**
 * CityController
 * - A validação de dados está em App\Http\Requests\StoreCityRequest.
 * - O tratamento global de erros foi implementado em bootstrap/app.php.
 */

class CityController extends Controller
{
    /**
     * Lista todas as cidades cadastradas com filtragem por nome uf e paginação
     */
    public function index(Request $request)
    {
        $query = City::with('uf');

        if ($request->filled('s')) {
            $query->where('name', 'ilike', '%' . $request->get('s') . '%');
        }

        if ($request->filled('uf_id')) {
            $query->where('uf_id', $request->get('uf_id'));
        }

        $cities = $query->paginate(15);

        return response()->json($cities);
    }

    /**
     * Registro de cidades simples (validação por API externa futuramente)
     */
    public function store(StoreCityRequest $request)
    {
        $city = City::create($request->validated());

        return response()->json($city, 201);
    }

    /**
     * Retorna a cidade específica, incluindo os dados da UF relacionada
     */
    public function show(City $city)
    {
        return response()->json($city->load('uf'));
    }

    /**
     * Atualiza a cidade especifica e retorna os dados
     */
    public function update(StoreCityRequest $request, City $city)
    {
        $city->update($request->validated());

        return response()->json($city->load('uf'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(['message' => 'Cidade excluída com sucesso.']);
    }
}
