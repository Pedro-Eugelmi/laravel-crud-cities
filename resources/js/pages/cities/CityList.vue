<script setup>
import { ref, computed } from 'vue';
import SearchInput from '../../components/ui/SearchInput.vue';
import Table from '../../components/ui/Table.vue';

    const cities = ref([
        { id: 1, nome: 'Birigui', uf_sigla: 'SP', ibge: '3506508' },
        { id: 2, nome: 'Araçatuba', uf_sigla: 'SP', ibge: '3502804' },
        { id: 3, nome: 'Rio de Janeiro', uf_sigla: 'RJ', ibge: '3304557' },
        { id: 4, nome: 'Belo Horizonte', uf_sigla: 'MG', ibge: '3106200' },
    ]);

    const searchQuery = ref('');
    const selectedUf = ref('');

</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
            
            <div class="flex flex-1 gap-4 w-full">
                <SearchInput 
                    v-model="searchCity"
                    label="Cidades"
                    placeholder="Buscar por nome ou IBGE..."
                />

                <div class="w-32">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1 ml-1">UF</label>
                    <select 
                        v-model="selectedUf"
                        class="w-full px-4 py-2.5 mt-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white font-medium"
                    >
                        <option value="">Todas</option>
                        <option value="SP">SP</option>
                        <option value="RJ">RJ</option>
                        <option value="MG">MG</option>
                    </select>
                </div>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition-all active:scale-95 whitespace-nowrap">
                + Nova Cidade
            </button>
        </div>

        <div class="overflow-hidden border border-gray-200 rounded-xl bg-white">
        <Table :loading="false">
            <template #header>
                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nome</th>
                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">UF</th>
                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">IBGE</th>
                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Ações</th>
            </template>

            <template #body>
                <tr v-for="city in cities" :key="city.id" class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ city.nome }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-black uppercase">
                            {{ city.uf_sigla }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ city.ibge }}</td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button class="text-blue-600 hover:underline font-bold text-sm">Editar</button>
                        <button class="text-red-500 hover:underline font-bold text-sm">Excluir</button>
                    </td>
                </tr>

                <tr v-if="cities.length === 0">
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                        Nenhuma cidade encontrada.
                    </td>
                </tr>
            </template>
        </Table>
        </div>
    </div>
</template>