<script setup>
import { ref } from 'vue'

const titulo = ref('Testando Vue 3 + Tailwind')

</script>

<template>
  <div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center">
    <div class="bg-white p-8">
      <h1 class="text-2xl font-bold text-gray-800">{{ titulo }}</h1>
      <p class="text-gray-600 mt-2">Olá mundo!!!!</p>
      <button 
        @click="titulo = 'Funcionando!'" 
        class="mt-4 px-4 py-2 bg-blue-600 text-white">
        Testar Vue
      </button>
    </div>
  </div>
</template>