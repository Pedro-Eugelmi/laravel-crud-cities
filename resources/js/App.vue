<script setup>
import { ref } from 'vue'
import Header from './components/Header.vue'
import Card from './components/Card.vue'
import Dashboard from './pages/Dashboard.vue'
import CityList from './pages/cities/CityList.vue'
import UfList from './pages/ufs/UfList.vue'

const currentPath = ref('dashboard')

// Define as rotas e seus componentes correspondentes
const routes = {
  'dashboard': Dashboard,
  'cities': CityList,
  'ufs': UfList
}

const navigate = (page) => {
  currentPath.value = page
}

</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    
    <Header :current-page="currentPath" @change-page="navigate"/>

    <main class="flex-1">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
      <Card>
        <component :is="routes[currentPath]" />
      </Card>

      </div>
    </main>

  </div>
</template>