<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCityRequest; 
use App\Models\City;                   
use Illuminate\Http\JsonResponse;
use App\Services\LocationService;

/**
 * CityController
 * - A validação de dados está em App\Http\Requests\StoreCityRequest.
 * - O tratamento global de erros foi implementado em bootstrap/app.php.
 * - A validação de CEP e código do IBGE foi implementada em App\Services\LocationService
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

        $cities = $query->paginate(10);

        return response()->json($cities);
    }

    /**
     * Registro de cidades simples (validação por API externa futuramente)
     */
    public function store(StoreCityRequest $request, LocationService $service)
    {
        $data = $request->validated();

        // Valida o CEP e o código do IBGE
        $service->validateAll($data['zip_code'], $data['ibge_code'], $data['uf_id']);

        $city = City::create($request->validated());

        return response()->json($city->load("uf"), 201);
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
    public function update(StoreCityRequest $request, City $city, LocationService $service)
    {
        $data = $request->validated();

        // Valida o CEP e o código do IBGE
        $service->validateAll($data['zip_code'], $data['ibge_code'], $data['uf_id']);

        $city->update($request->validated());

        return response()->json($city->load('uf'));
    }

    /**
     * Delete a cidade especifica e retorna mensagem de sucesso
     */
    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(['message' => 'Cidade excluída com sucesso.']);
    }
}
