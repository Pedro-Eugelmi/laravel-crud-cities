<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    uf: { 
        type: Object, 
        default: () => ({ name: '', state_code: '' }) 
    },
    loading: Boolean
});

const emit = defineEmits(['save', 'cancel']);

const formData = ref({ ...props.uf });

watch(() => props.uf, (newVal) => {
    formData.value = { ...newVal };
}, { deep: true });

// Força a sigla a ser sempre maiúscula e apenas letras
const handleStateCode = (e) => {
    let value = e.target.value.toUpperCase().replace(/[^A-Z]/g, '');
    formData.value.state_code = value.substring(0, 2);
    e.target.value = formData.value.state_code;
};

const handleSubmit = () => {
    emit('save', formData.value);
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-5">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Nome do Estado</label>
            <input 
                v-model="formData.name" 
                type="text" 
                placeholder="Ex: São Paulo"
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition"
                required
            />
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-1 ml-1">Sigla (UF)</label>
            <input 
                :value="formData.state_code"
                @input="handleStateCode"
                type="text" 
                placeholder="Ex: SP"
                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition font-mono"
                required
            />
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
                {{ loading ? 'Processando...' : 'Salvar Estado' }}
            </button>
        </div>
    </form>
</template>