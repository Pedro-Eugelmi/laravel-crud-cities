<script setup>
    import { ref, onMounted, watch } from 'vue';
    import api from '../../services/api';
    import SearchInput from '../../components/ui/SearchInput.vue';
    import Table from '../../components/ui/Table.vue';
    import Modal from '../../components/ui/Modal.vue';
    import CityForm from './CityForm.vue';

    // Pesquisa e filtros
    const searchCity = ref('');
    const selectedUf = ref('');

    const cities = ref([]);
    const ufs = ref([]);
    const pagination = ref({});
    const loading = ref(false);
    const error = ref(null);

    // Estados dos Modais
    const showSaveModal = ref(false);
    const showDeleteModal = ref(false);
    const selectedCity = ref(null);
    const cityToEdit = ref(null);

    // Abre modal de Editar
    const openEditModal = (city) => {
        error.value = null;
        cityToEdit.value = { ...city }; 
        showSaveModal.value = true;
    };

    // Abre modal de Criar
    const openCreateModal = () => {
        error.value = null;
        cityToEdit.value = { name: '', zip_code: '', ddd: '', ibge_code: '', uf_id: '' };
        showSaveModal.value = true;
    };

    // Abre modal de Remover
    const confirmDelete = (city) => {
        selectedCity.value = city;
        showDeleteModal.value = true;
    };

    // Função para salvar (Store e Update)
    const handleSave = async (formData) => {
        loading.value = true;
        error.value = null; 

        try {

            if (formData.id) {
                const response = await api.put(`/cities/${formData.id}`, formData);
                const index = cities.value.findIndex(c => c.id === formData.id);
                cities.value[index] = response.data;
            } else {
                const response = await api.post('/cities', formData);
                cities.value.unshift(response.data);
            }

            showSaveModal.value = false; 
            
        } catch (err) {
            
            if (err.response && err.response.data) {
                error.value = err.response.data.message;
            } else {
                error.value = "Ocorreu um erro inesperado ao salvar.";
            }
        } finally {
            loading.value = false;
        }
    };

    
    // Deletar a Cidade
    const handleDelete = async () => {
        try {
            await api.delete(`/cities/${selectedCity.value.id}`);
            showDeleteModal.value = false;
            fetchCities(); // Recarrega a lista
        } catch (err) {
            alert("Erro ao excluir");
        }
    };

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

            <button @click="openCreateModal" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-md transition-all active:scale-95 whitespace-nowrap">
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
                            <button @click="openEditModal(city)" class="text-blue-600 hover:underline font-bold text-sm">Editar</button>
                            <button @click="confirmDelete(city)" class="text-red-500 hover:underline font-bold text-sm">Excluir</button>
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

    <Modal :show="showSaveModal" title="Cadastrar Nova Cidade" @close="showSaveModal = false; error = null">
            <div v-if="error" class="mb-4 p-3 rounded-lg bg-red-50 border-l-4 border-red-500 text-red-700 text-sm flex items-center gap-2">
                <span class="font-bold">⚠️ Atenção:</span> {{ error }}
            </div>

        <CityForm 
            :city="cityToEdit" 
            :ufs="ufs" 
            :loading="loading"
            @save="handleSave" 
            @cancel="showSaveModal = false" 
        />
    </Modal>

    <Modal :show="showDeleteModal" title="Confirmar Exclusão" @close="showDeleteModal = false">
        <div class="text-center">
            <div class="text-2xl mb-4 text-red-500">
                ⚠️  
            </div>
            <p class="text-gray-600">Tem certeza que deseja excluir a cidade <strong>{{ selectedCity?.name }}</strong>?</p>
            <p class="text-xs text-gray-400 mt-2 italic">Esta ação não pode ser desfeita.</p>

            <div class="flex justify-center gap-3 mt-8">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold">Cancelar</button>
                <button @click="handleDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold shadow-lg shadow-red-200">Sim, Excluir</button>
            </div>
        </div>
    </Modal>

</template>