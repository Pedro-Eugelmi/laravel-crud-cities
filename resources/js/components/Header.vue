<script setup>
  defineEmits(['change-page'])

  defineProps({
    currentPage: String
  })
</script>

<template>
  <header class="bg-white shadow-sm border-b border-gray-100">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
          Gerenciador de cidades
        </h1>

        <nav>
          <ul>
            <li>
              <nav class="space-x-4">
                <button 
                  @click="$emit('change-page', 'dashboard')" 
                  :class="[currentPage === 'dashboard' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-400 border-b-2 border-transparent']"
                  class="pb-1 font-medium transition-all duration-200">
                  Início
                </button>

                <button 
                    @click="$emit('change-page', 'cities')" 
                    :class="[currentPage === 'cities' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-400 border-b-2 border-transparent']"
                    class="pb-1 font-medium transition-all duration-200">
                    Cidades
                </button>

                <button 
                  @click="$emit('change-page', 'ufs')" 
                  :class="[currentPage === 'ufs' ? 'text-blue-600 border-b-2 border-blue-600' : 'hover:text-blue-400 border-b-2 border-transparent']"
                  class="pb-1 font-medium transition-all duration-200">
                  UFs
                </button>
              </nav>
            </li>
          </ul>
        </nav>

      </div>
    </nav>
  </header>
</template>