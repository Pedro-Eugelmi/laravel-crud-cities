<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Uf;

class UfSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'Acre', 'state_code' => 'AC'],
            ['name' => 'Alagoas', 'state_code' => 'AL'],
            ['name' => 'Amapá', 'state_code' => 'AP'],
            ['name' => 'Amazonas', 'state_code' => 'AM'],
            ['name' => 'Bahia', 'state_code' => 'BA'],
            ['name' => 'Ceará', 'state_code' => 'CE'],
            ['name' => 'Distrito Federal', 'state_code' => 'DF'],
            ['name' => 'Espírito Santo', 'state_code' => 'ES'],
            ['name' => 'Goiás', 'state_code' => 'GO'],
            ['name' => 'Maranhão', 'state_code' => 'MA'],
            ['name' => 'Mato Grosso', 'state_code' => 'MT'],
            ['name' => 'Mato Grosso do Sul', 'state_code' => 'MS'],
            ['name' => 'Minas Gerais', 'state_code' => 'MG'],
            ['name' => 'Pará', 'state_code' => 'PA'],
            ['name' => 'Paraíba', 'state_code' => 'PB'],
            ['name' => 'Paraná', 'state_code' => 'PR'],
            ['name' => 'Pernambuco', 'state_code' => 'PE'],
            ['name' => 'Piauí', 'state_code' => 'PI'],
            ['name' => 'Rio de Janeiro', 'state_code' => 'RJ'],
            ['name' => 'Rio Grande do Norte', 'state_code' => 'RN'],
            ['name' => 'Rio Grande do Sul', 'state_code' => 'RS'],
            ['name' => 'Rondônia', 'state_code' => 'RO'],
            ['name' => 'Roraima', 'state_code' => 'RR'],
            ['name' => 'Santa Catarina', 'state_code' => 'SC'],
            ['name' => 'São Paulo', 'state_code' => 'SP'],
            ['name' => 'Sergipe', 'state_code' => 'SE'],
            ['name' => 'Tocantins', 'state_code' => 'TO'],
        ];

        foreach ($states as $state) {
            Uf::create($state);
        }
    }
}
