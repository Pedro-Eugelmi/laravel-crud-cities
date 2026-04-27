<script setup>
    import { ref, onMounted, watch } from 'vue';
    import api from '../../services/api';
    import SearchInput from '../../components/ui/SearchInput.vue';
    import Table from '../../components/ui/Table.vue';
    import Modal from '../../components/ui/Modal.vue';
    import UfForm from './UfForm.vue';

    const ufs = ref([]);
    const pagination = ref({});
    const loading = ref(false);
    const error = ref(null);
    const searchQuery = ref('');

    // Controle de estado dos modais
    const showSaveModal = ref(false);
    const showDeleteModal = ref(false);
    const ufToEdit = ref(null);
    const selectedUf = ref(null);

    // Reseta estados e abre modal de criação
    const openCreateModal = () => {
        error.value = null;
        ufToEdit.value = { name: '', state_code: '' };
        showSaveModal.value = true;
    };

    // Prepara dados para edição
    const openEditModal = (uf) => {
        error.value = null;
        ufToEdit.value = { ...uf };
        showSaveModal.value = true;
    };

    const confirmDelete = (uf) => {
        selectedUf.value = uf;
        showDeleteModal.value = true;
    };

    // Orquestra a persistência de dados (Create/Update)
    const handleSave = async (formData) => {
        loading.value = true;
        error.value = null;

        try {
            if (formData.id) {
                const response = await api.put(`/ufs/${formData.id}`, formData);
                const index = ufs.value.findIndex(u => u.id === formData.id);
                ufs.value[index] = response.data;
            } else {
                const response = await api.post('/ufs', formData);
                ufs.value.unshift(response.data); // Feedback visual imediato no topo
            }
            showSaveModal.value = false;
        } catch (err) {
            error.value = err.response?.data?.message || "Erro ao salvar estado.";
        } finally {
            loading.value = false;
        }
    };

    const handleDelete = async () => {
        try {
            await api.delete(`/ufs/${selectedUf.value.id}`);
            showDeleteModal.value = false;
            fetchUfs(pagination.value.current_page);
        } catch (err) {
            alert("Erro ao excluir estado.");
        }
    };

    const fetchUfs = async (page = 1) => {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.get('/ufs', {
                params: { page, s: searchQuery.value }
            });
            ufs.value = response.data.data || [];
            pagination.value = response.data;
        } catch (err) {
            error.value = "Erro ao carregar lista de estados.";
        } finally {
            loading.value = false;
        }
    };

    //  Espera 500ms, para disparar a API
    let debounceTimer = null;
    watch(searchQuery, () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchUfs(1), 500);
    });

    onMounted(() => fetchUfs());
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
            <button @click="openCreateModal" class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 lg:py-2.5 rounded-lg font-bold shadow-md shadow-blue-100 transition-all active:scale-95 whitespace-nowrap flex items-center justify-center gap-2">
                + Nova UF
            </button>
        </div>

        <div class="overflow-hidden border border-gray-200 rounded-xl bg-white shadow-sm">
            <Table :loading="loading">
                <template #header>
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-left hidden md:table-cell">ID</th>
                    
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">Estado</th>
                    
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-left hidden md:table-cell">Sigla</th>
                    
                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Ações</th>
                </template>

                <template #body>
                    <template v-if="ufs.length > 0">
                        <tr v-for="uf in ufs" :key="uf.id" class="hover:bg-blue-50/30 transition-colors border-b border-gray-100 last:border-0">
                            
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono hidden md:table-cell">
                                #{{ uf.id }} 
                            </td>

                            <td class="px-4 md:px-6 py-4 font-semibold text-gray-800">
                                <div class="flex items-center gap-2">
                                    {{ uf.name }}
                                    <span class="font-medium text-gray-500 md:hidden font-black">
                                        ({{ uf.state_code }})
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="px-2.5 py-1 bg-gray-100 whitespace-nowrap text-gray-600 rounded-md text-xs font-black uppercase">
                                    {{ uf.state_code }}
                                </span>
                            </td>

                            <td class="px-4 md:px-6 py-4 text-right whitespace-nowrap space-x-3">
                                <button @click="openEditModal(uf)" class="text-blue-600 hover:text-blue-800 font-bold text-sm transition-colors">Editar</button>
                                <button @click="confirmDelete(uf)" class="text-red-500 hover:text-red-700 font-bold text-sm transition-colors">Excluir</button>
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
            <span class="text-sm text-gray-500">Página {{ pagination.current_page }} de {{ pagination.last_page }}</span>
            <div class="flex gap-2">
                <button @click="fetchUfs(pagination.current_page - 1)" :disabled="pagination.current_page === 1" class="px-4 py-2 bg-white border rounded shadow-sm disabled:opacity-50 font-medium transition active:scale-95">Anterior</button>
                <button @click="fetchUfs(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page" class="px-4 py-2 bg-white border rounded shadow-sm disabled:opacity-50 font-medium transition active:scale-95">Próximo</button>
            </div>
        </div>
    </div>

    <Modal :show="showSaveModal" :title="ufToEdit?.id ? 'Editar Estado' : 'Novo Estado'" @close="showSaveModal = false; error = null">
        <div v-if="error" class="mb-4 p-3 rounded-lg bg-red-50 border-l-4 border-red-500 text-red-700 text-sm flex items-center gap-2">
            <span class="font-bold">⚠️ Atenção:</span> {{ error }}
        </div>
        <UfForm :uf="ufToEdit" :loading="loading" @save="handleSave" @cancel="showSaveModal = false" />
    </Modal>

    <Modal :show="showDeleteModal" title="Confirmar Exclusão" @close="showDeleteModal = false">
        <div class="text-center">
            <div class="text-2xl mb-4 text-red-500">⚠️</div>
            <p class="text-gray-600">Deseja realmente excluir o estado <strong>{{ selectedUf?.name }}</strong>?</p>
            <div class="flex justify-center gap-3 mt-8">
                <button @click="showDeleteModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-bold text-sm transition">Cancelar</button>
                <button @click="handleDelete" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold text-sm shadow-lg shadow-red-200 transition active:scale-95">Sim, Excluir</button>
            </div>
        </div>
    </Modal>
</template>