<script setup>
defineProps({
    columns: Array, 
    loading: Boolean
})
</script>

<template>
    <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <slot name="header" />
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                <tr v-if="loading">
                    <td colspan="100%" class="px-6 py-12 text-center text-gray-400">
                        <span class="animate-pulse">Carregando dados...</span>
                    </td>
                </tr>

                <slot v-else name="body" />
            </tbody>
        </table>
    </div>
</template>