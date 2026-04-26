<script setup>
    import { ref, onMounted, watch } from 'vue';
    import api from '../../services/api';
    import SearchInput from '../../components/ui/SearchInput.vue';
    import Table from '../../components/ui/Table.vue';

    // Pesquisa e filtros
    const searchCity = ref('');
    const selectedUf = ref('');

    const cities = ref([]);
    const ufs = ref([]);
    const pagination = ref({});
    const loading = ref(false);
    const error = ref(null);

    // Puxa as UFs
    const fetchUfs = async () => {
        try {
            const response = await api.get('/ufs', { params: { all: true } });
            ufs.value = response.data.data || response.data;
        } catch (err) {
            console.error("Erro ao carregar UFs:", err);
        }
    };

    // Puxa as cidades 
    const fetchCities = async (page = 1) => {
        loading.value = true;
        error.value = null; 

        try {
            const response = await api.get('/cities', {
                params: {
                    page: page, 
                    s: searchCity.value,     
                    uf_id: selectedUf.value  
                }
            });
            
            cities.value = response.data.data || [];
            pagination.value = response.data;

        } catch (err) {
            console.error("Erro na API:", err);
            error.value = "Erro ao carregar cidades.";
        } finally {
            loading.value = false;
        }
    };

    watch(selectedUf, () => {
        fetchCities(1);
    });

    let debounceTimer = null;

    // Espera 500ms, para disparar a API
    watch(searchCity, () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {
            fetchCities(1);
        }, 500); 
    });

    // Carrega UFs e cidades ao carregar 
    onMounted(() => {
        fetchUfs();
        fetchCities();
    });

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

                <div class="w-32 flex flex-col justify-end">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1 ml-1">UF</label>

                    <select 
                        v-model="selectedUf"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white font-medium transition mt-1"
                    >
                        <option value="">Todas</option>
        
                        <option v-for="uf in ufs" :key="uf.id" :value="uf.id">
                            {{ uf.name }} 
                        </option>
                    </select>
                </div>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition-all active:scale-95 whitespace-nowrap">
                + Nova Cidade
            </button>
        </div>

        <div class="overflow-hidden border border-gray-200 rounded-xl bg-white">
            <Table :loading="loading">
                <template #header>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nome</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">UF</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">IBGE</th>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Ações</th>
                </template>

                <template #body>
                    <tr v-for="city in cities" :key="city?.id" class="hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ city?.name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-black uppercase">
                                {{ city?.uf?.name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ city?.ibge_code }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button class="text-blue-600 hover:underline font-bold text-sm">Editar</button>
                            <button class="text-red-500 hover:underline font-bold text-sm">Excluir</button>
                        </td>
                    </tr>

                    <tr v-if="!loading && cities.length === 0">
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                            Nenhuma cidade encontrada.
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
                    @click="fetchCities(pagination.current_page - 1)"
                    :disabled="pagination.current_page === 1"
                    class="px-4 py-2 bg-white border rounded shadow-sm disabled:opacity-50 font-medium"
                >
                    Anterior
                </button>
                <button 
                    @click="fetchCities(pagination.current_page + 1)"
                    :disabled="pagination.current_page === pagination.last_page"
                    class="px-4 py-2 bg-white border rounded shadow-sm disabled:opacity-50 font-medium"
                >
                    Próximo
                </button>
            </div>
        </div>
    </div>
</template>