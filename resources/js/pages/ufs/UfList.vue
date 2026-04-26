<script setup>
import { ref, computed } from 'vue';
import SearchInput from '../../components/ui/SearchInput.vue';
import Table from '../../components/ui/Table.vue';

    const ufs = ref([
        { id: 1, nome: 'São Paulo', sigla: 'SP' },
        { id: 2, nome: 'Rio de Janeiro', sigla: 'RJ' },
        { id: 3, nome: 'Minas Gerais', sigla: 'MG' },
        { id: 4, nome: 'Espírito Santo', sigla: 'ES' },
        { id: 5, nome: 'Paraná', sigla: 'PR' },
    ]);

    const searchQuery = ref('');

    const filteredUfs = computed(() => {
        return ufs.value.filter(uf => {
            return uf.nome.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                   uf.sigla.toLowerCase().includes(searchQuery.value.toLowerCase());
        });
    });

</script>

<template>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row gap-4 items-end justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
            
            <div class="flex flex-1 gap-4 w-full">
                <SearchInput 
                    v-model="searchQuery"
                    label="Estados (UFs)"
                    placeholder="Buscar por nome ou sigla..."
                />
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition-all active:scale-95 whitespace-nowrap">
                + Nova UF
            </button>
        </div>

        <div class="overflow-hidden border border-gray-200 rounded-xl bg-white shadow-sm">
            <Table :loading="false">
                <template #header>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nome do Estado</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sigla</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Ações</th>
                </template>

                <template #body>
                    <tr v-for="uf in filteredUfs" :key="uf.id" class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ uf.id }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ uf.nome }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-black uppercase tracking-wider">
                                {{ uf.sigla }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="text-blue-600 hover:underline font-bold text-sm">Editar</button>
                            <button class="text-red-500 hover:underline font-bold text-sm">Excluir</button>
                        </td>
                    </tr>

                    <tr v-if="filteredUfs.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                            Nenhum estado encontrado.
                        </td>
                    </tr>
                </template>
            </Table>
        </div>
    </div>
</template>