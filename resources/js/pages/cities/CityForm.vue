<script setup>
    import { ref, watch } from 'vue';
    import axios from 'axios'; 

    // Formatar CEP
    const formatCEP = (value) => {
        if (!value) return '';
        const clean = value.replace(/\D/g, '');
        return clean.replace(/^(\d{5})(\d)/, '$1-$2').substring(0, 9);
    };

    // Define os dados
    const props = defineProps({
        city: { 
            type: Object, 
            default: () => ({ name: '', zip_code: '', ddd: '', ibge_code: '', uf_id: '' }) 
        },
        ufs: { type: Array, required: true },
        loading: Boolean
    });

    // Define os eventos emitidos
    const emit = defineEmits(['save', 'cancel']);
    const formData = ref({ 
        ...props.city,
        zip_code: formatCEP(props.city.zip_code) 
    });


    // Sincroniza os dados do formulário com as props
    watch(() => props.city, (newVal) => {
        formData.value = { 
            ...newVal,
            zip_code: formatCEP(newVal.zip_code)
        };
    }, { deep: true });

    // Buscas os dados da cidade pelo via cep e preenche os campos automaticamente
    const fetchViaCep = async (cep) => {
        const cleanCep = cep.replace(/\D/g, '');
        if (cleanCep.length !== 8) return;

        try {
            const response = await axios.get(`https://viacep.com.br/ws/${cleanCep}/json/`);
            const data = response.data;

            if (!data.erro) {
                formData.value.name = data.localidade;
                formData.value.ddd = data.ddd;
                formData.value.ibge_code = data.ibge;
                
                // Busca o UF 
                const foundUf = props.ufs.find(u => u.state_code === data.uf || u.name.includes(data.uf));
                if (foundUf) {
                    formData.value.uf_id = foundUf.id;
                }
            }
        } catch (err) {
            console.error("Erro ao buscar CEP:", err);
        }
    };

    // Formata o CEP 
    const handleZipCode = (e) => {
        const value = formatCEP(e.target.value);
        
        formData.value.zip_code = value;
        e.target.value = value; 

        const clean = value.replace(/\D/g, '');
        if (clean.length === 8) fetchViaCep(clean);
    };

    // Permite apenas números e limita o tamanho do campo
    const handleOnlyNumbers = (e, key, length) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > length) value = value.substring(0, length);
        formData.value[key] = value;
        e.target.value = value;
    };

    // Envia o formulário
    const handleSubmit = () => {
        const data = { ...formData.value, zip_code: formData.value.zip_code.replace(/\D/g, '') };
        emit('save', data);
    };

</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">CEP</label>
                <input 
                    type="text" 
                    :value="formData.zip_code"
                    @input="handleZipCode"
                    placeholder="00000-000"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition"
                    required
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Estado (UF)</label>
                <select 
                    v-model="formData.uf_id"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none bg-white transition"
                    required
                >
                    <option value="">Selecione...</option>
                    <option v-for="uf in ufs" :key="uf.id" :value="uf.id">
                        {{ uf.name }}
                    </option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Nome da Cidade</label>
            <input 
                v-model="formData.name" 
                type="text" 
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition"
                placeholder="Ex: São Paulo"
                required
            />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">DDD</label>
                <input 
                    :value="formData.ddd" 
                    @input="handleOnlyNumbers($event, 'ddd', 2)"
                    type="text" 
                    placeholder="Ex: 11"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition font-mono"
                    required
                />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Cód. IBGE</label>
                <input 
                    :value="formData.ibge_code" 
                    @input="handleOnlyNumbers($event, 'ibge_code', 7)"
                    type="text" 
                    placeholder="Ex: 0000000"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition font-mono"
                    required
                />
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 mt-4">
            <button type="button" @click="$emit('cancel')" class="px-5 py-2.5 text-gray-500 font-bold hover:bg-gray-100 rounded-lg transition">
                Cancelar
            </button>
            <button 
                type="submit" 
                :disabled="loading"
                class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-lg shadow-blue-200 transition-all active:scale-95 disabled:opacity-50"
            >
                {{ loading ? 'Processando...' : 'Salvar Cidade' }}
            </button>
        </div>
    </form>
</template>