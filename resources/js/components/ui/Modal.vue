<script setup>
defineProps({
    show: Boolean,
    title: String
});
defineEmits(['close']);
</script>

<template>
    <Teleport to="body">
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div @click="$emit('close')" class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">{{ title }}</h3>
                    <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <div class="p-6">
                    <slot />
                </div>
            </div>
        </div>
    </Teleport>
</template>