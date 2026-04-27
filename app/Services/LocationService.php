<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Uf;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LocationService
{
    /**
     * Valida se o CEP existe e se os dados batem com o esperado.
     */
    public function isValidZipCode(string $zipCode, string $ibgeCode, int $ufId): bool
    {
        // Limpa o CEP, deixa apenas números
        $cleanZip = preg_replace('/[^0-9]/', '', $zipCode);

        try {
            // timeout de 5 segundos, para não travar o sistema 
            $response = Http::timeout(5)->get("https://viacep.com.br/ws/{$cleanZip}/json/");

            $data = $response->json();

            if ($response->failed() || isset($response->json()['erro'])) {
                abort(422, 'O CEP informado é inválido ou não foi encontrado.');
            }

            // O código do IBGE retornado pelo ViaCEP deve ser o mesmo enviado pelo usuário
            if ($data['ibge'] != $ibgeCode) {
                abort(422, 'O CEP informado não corresponde ao IBGE.');
            } 

            // Verifica se o Estado bate 
            $selectedUf = Uf::find($ufId);
            
            if (!$selectedUf || strtoupper($data['uf']) !== strtoupper($selectedUf->state_code)) {
                abort(422, "O CEP informado pertence ao estado ({$data['uf']}), mas você selecionou {$selectedUf->state_code}.");
            }

            return true;
            
        } catch (\Exception $e) {
            // Diferencia erros de validação de outros erros.
            if ($e instanceof HttpException) throw $e;
            
            Log::error("Erro ao consultar ViaCEP: " . $e->getMessage());
            abort(503, 'Erro ao consultar ViaCEP. Por favor, tente novamente mais tarde.');
        }
    }

    /**
     * Valida o Código IBGE diretamente na API do IBGE
     */
    public function isValidIbge(string $ibgeCode): bool
    {
        try {
            // Timeout de 5 segundos 
            $response = Http::timeout(5)->get("https://servicodados.ibge.gov.br/api/v1/localidades/municipios/{$ibgeCode}");

            return $response->successful() && !empty($response->json());

        } catch (\Exception $e) {
            // Diferencia erros de validação de outros erros.
            if ($e instanceof HttpException) throw $e;

            Log::error("Erro ao consultar API IBGE: " . $e->getMessage());
            abort(503, 'Erro ao consultar API IBGE. Por favor, tente novamente mais tarde.');
        }
    }

    /**
     * Valida o Código IBGE e CEP e estado
     */
    public function validateAll(string $zipCode, string $ibgeCode, int $ufId): void
    {
        $this->isValidZipCode($zipCode, $ibgeCode, $ufId);

        $this->isValidIbge($ibgeCode);
    }
}