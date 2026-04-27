<script setup>
import { ref } from 'vue';

const props = defineProps({
    currentPage: String
});

defineEmits(['change-page']);

const isMenuOpen = ref(false);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};
</script>

<template>
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100 sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-xl md:text-2xl font-bold text-gray-900 tracking-tight">
                    Gerenciador de cidades
                </h1>

                <div class="flex md:hidden">
                    <button @click="toggleMenu" type="button" class="text-gray-500 hover:text-blue-600 outline-none">
                        <svg v-if="!isMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="hidden md:flex space-x-8">
                    <button 
                        @click="$emit('change-page', 'dashboard')" 
                        :class="[currentPage === 'dashboard' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-400 border-b-2 border-transparent']"
                        class="pb-1 font-semibold transition-all duration-300">
                        Início
                    </button>
                    <button 
                        @click="$emit('change-page', 'cities')" 
                        :class="[currentPage === 'cities' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-400 border-b-2 border-transparent']"
                        class="pb-1 font-semibold transition-all duration-300">
                        Cidades
                    </button>
                    <button 
                        @click="$emit('change-page', 'ufs')" 
                        :class="[currentPage === 'ufs' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-500 hover:text-blue-400 border-b-2 border-transparent']"
                        class="pb-1 font-semibold transition-all duration-300">
                        UFs
                    </button>
                </div>
            </div>

            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform -translate-y-4 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-4 opacity-0"
            >
                <div v-if="isMenuOpen" class="md:hidden mt-4 pb-2 space-y-2 border-t border-gray-100 pt-4">
                    <button 
                        @click="$emit('change-page', 'dashboard'); isMenuOpen = false" 
                        :class="[currentPage === 'dashboard' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50']"
                        class="block w-full text-left px-4 py-2 rounded-lg font-medium transition-all duration-200">
                        Início
                    </button>
                    <button 
                        @click="$emit('change-page', 'cities'); isMenuOpen = false" 
                        :class="[currentPage === 'cities' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50']"
                        class="block w-full text-left px-4 py-2 rounded-lg font-medium transition-all duration-200">
                        Cidades
                    </button>
                    <button 
                        @click="$emit('change-page', 'ufs'); isMenuOpen = false" 
                        :class="[currentPage === 'ufs' ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50']"
                        class="block w-full text-left px-4 py-2 rounded-lg font-medium transition-all duration-200">
                        UFs
                    </button>
                </div>
            </Transition>
        </nav>
    </header>
</template>