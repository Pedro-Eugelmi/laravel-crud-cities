<script setup>

defineProps({
    modelValue: String,
    label: {
        type: String,
        default: 'Pesquisar'
    },
    placeholder: {
        type: String,
        default: 'Digite para buscar...'
    }
});

defineEmits(['update:modelValue']);

</script>

<template>
    <div class="flex-1">
        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1 ml-1">
            {{ label }}
        </label>
        <input 
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            type="text" 
            :placeholder="placeholder"
            class="w-full mt-2 px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white"
        />
    </div>
</template>