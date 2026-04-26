<script setup>
    import { ref, onMounted, watch } from 'vue';
    import api from '../../services/api';
    import SearchInput from '../../components/ui/SearchInput.vue';
    import Table from '../../components/ui/Table.vue';

    const ufs = ref([]);
    const pagination = ref({});
    const loading = ref(false);
    const error = ref(null);
    const searchQuery = ref('');

    // Função para buscar UFs da API
    const fetchUfs = async (page = 1) => {
        loading.value = true;
        error.value = null;

        try {
            const response = await api.get('/ufs', {
                params: {
                    page: page,
                    s: searchQuery.value 
                }
            });
            
            ufs.value = response.data.data || [];
            pagination.value = response.data;
        } catch (err) {
            console.error("Erro na API de UFs:", err);
            error.value = "Não foi possível carregar os estados.";
        } finally {
            loading.value = false;
        }
    };

    let debounceTimer = null;
    watch(searchQuery, () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchUfs(1); 
        }, 500);
    });

    onMounted(() => {
        fetchUfs();
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

        <div v-if="error" class="p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
            {{ error }}
            <button @click="fetchUfs" class="underline ml-2 font-bold">Tentar novamente</button>
        </div>

        <div class="overflow-hidden border border-gray-200 rounded-xl bg-white shadow-sm">
            <Table :loading="loading">
                <template #header>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nome do Estado</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Sigla</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Ações</th>
                </template>

                <template #body>
                    <template v-if="ufs.length > 0">
                        <tr v-for="uf in ufs" :key="uf.id" class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ uf.id }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ uf.name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-black uppercase">
                                    {{ uf.state_code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="text-blue-600 hover:underline font-bold text-sm">Editar</button>
                                <button class="text-red-500 hover:underline font-bold text-sm">Excluir</button>
                            </td>
                        </tr>
                    </template>

                    <tr v-else-if="!loading">
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                            Nenhum estado encontrado.
                        </td>
                    </tr>
                </template>
            </Table>
        </div>

        <div v-if="pagination.last_page > 1" class="flex justify-between items-center px-2 py-4">
            <span class="text-sm text-gray-500">
                Página {{ pagination.current_page }} de {{ pagination.last_page }}
            </span>
            <div class="flex gap-2">
                <button 
                    @click="fetchUfs(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-4 py-2 bg-white border rounded shadow-sm disabled:opacity-50 font-medium transition active:scale-95"
                >
                    Anterior
                </button>
                <button 
                    @click="fetchUfs(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-4 py-2 bg-white border rounded shadow-sm disabled:opacity-50 font-medium transition active:scale-95"
                >
                    Próximo
                </button>
            </div>
        </div>
    </div>
</template>