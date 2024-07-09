<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" @click="closePanel">
    <div class="relative top-10 mx-auto p-5 border w-md max-w-lg shadow-lg rounded-md bg-white" @click.stop>
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Filtry</h3>
        <button @click="closePanel" class="rounded-full bg-gray-100 p-1 hover:bg-gray-200">
          <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <div class="space-y-4">
        <div class="border-b pb-4">
          <h4 class="mb-2 font-semibold text-gray-800">Zarobki</h4>
          <div class="flex gap-4">
            <input type="number" placeholder="Min. wypłata" class="block w-full p-2 border border-gray-300 rounded-md" v-model="filters.minSalary">
            <input type="number" placeholder="Max. wypłata" class="block w-full p-2 border border-gray-300 rounded-md" v-model="filters.maxSalary">
          </div>
        </div>
        <div class="border-b pb-4">
          <h4 class="mb-2 font-semibold text-gray-800">Doświadczenie</h4>
          <div class="flex flex-wrap">
            <template v-for="level in experienceLevels" :key="`exp-${level}`">
              <div class="p-2 w-1/2 md:w-1/4 text-sm">
                <div
                  class="flex items-center cursor-pointer justify-center p-2 rounded"
                  :class="getFilterClass('experience', level)"
                  @click="toggleFilter('experience', level)"
                >
                  <span :class="getTextColorClass('experience', level)">{{ level }}</span>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div class="border-b pb-4">
          <h4 class="mb-2 font-semibold text-gray-800">Wymiar pracy</h4>
          <div class="flex flex-wrap">
            <template v-for="type in workTypes" :key="`workType-${type}`">
              <div class="p-2 w-1/2 md:w-1/4 text-sm">
                <div
                  class="flex items-center cursor-pointer justify-center p-2 rounded"
                  :class="getFilterClass('workType', type)"
                  @click="toggleFilter('workType', type)"
                >
                  <span :class="getTextColorClass('workType', type)">{{ type }}</span>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div class="border-b pb-4">
          <h4 class="mb-2 font-semibold text-gray-800">Rodzaj umowy</h4>
          <div class="flex flex-wrap">
            <template v-for="contract in contractTypes" :key="`contractType-${contract}`">
              <div class="p-2 w-1/2 md:w-1/4 text-sm">
                <div
                  class="flex items-center cursor-pointer justify-center p-2 rounded"
                  :class="getFilterClass('contractType', contract)"
                  @click="toggleFilter('contractType', contract)"
                >
                  <span :class="getTextColorClass('contractType', contract)">{{ contract }}</span>
                </div>
              </div>
            </template>
          </div>
        </div>
        <div>
          <h4 class="mb-2 font-semibold text-gray-800">Języki obce</h4>
          <div class="flex flex-wrap items-center mb-2">
            <span
              v-for="(language, index) in filters.languages"
              :key="`lang-${index}`"
              class="bg-cyan-500 text-white text-xs px-2 py-1 rounded-full m-1 flex items-center"
            >
              {{ language }}
              <button @click="removeLanguage(index)" class="ml-2 cursor-pointer">&times;</button>
            </span>
          </div>
          <input
            type="text"
            placeholder="Wpisz języki"
            class="block w-full p-2 border border-gray-300 rounded-md"
            v-model="languageInput"
            @keydown.enter.prevent="addLanguage"
            @keydown.space.prevent="addLanguage"
          >
        </div>
        <button @click="applyFilters" class="mt-4 w-full bg-cyan-500 text-white p-2 rounded hover:bg-cyan-600 focus:outline-none focus:ring-2 focus:ring-cyan-300">
          Filtruj
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits, onMounted } from 'vue'

const experienceLevels = ['Junior', 'Mid', 'Senior', 'Manager']
const workTypes = ['Etat', 'Niepełny etat', 'Praktyki/Staż', 'Freelance']
const contractTypes = ['B2B', 'UoP', 'Zlecenie', 'Dzieło', 'Staż']

// Inicjalizacja stanu filtrów z domyślnymi wartościami
const filters = ref({
  minSalary: '',
  maxSalary: '',
  experience: [],
  workType: [],
  contractType: [],
  languages: [],
})

const languageInput = ref('') // Dla pojedynczego wprowadzania języka

const emit = defineEmits(['update:showFilters', 'filters-applied'])

// Metoda do przełączania filtrów (bez zmian)
function toggleFilter(filterCategory, value) {
  const index = filters.value[filterCategory].indexOf(value)
  if (index === -1) {
    filters.value[filterCategory].push(value)
  } else {
    filters.value[filterCategory].splice(index, 1)
  }
}

// Funkcje pomocnicze do stylów (bez zmian)
function getFilterClass(filterCategory, value) {
  return filters.value[filterCategory].includes(value) ? 'bg-cyan-500 text-white' : 'bg-gray-200 hover:bg-cyan-400'
}

function getTextColorClass(filterCategory, value) {
  return filters.value[filterCategory].includes(value) ? 'text-white' : 'text-gray-700'
}

// Dodawanie i usuwanie języków
function addLanguage() {
  if (languageInput.value.trim()) {
    if (!Array.isArray(filters.value.languages)) {
      filters.value.languages = []; // Upewnij się, że languages jest tablicą
    }
    filters.value.languages.push(languageInput.value.trim())
    languageInput.value = ''
  }
}

function removeLanguage(index) {
  if (Array.isArray(filters.value.languages)) {
    filters.value.languages.splice(index, 1)
  }
}

// Zastosowanie filtrów
function applyFilters() {
  emit('filters-applied', filters.value)
  saveFiltersToLocalStorage()
  closePanel()
}

// Zapisywanie i ładowanie filtrów do/z localStorage
function saveFiltersToLocalStorage() {
  localStorage.setItem('jobFilters', JSON.stringify(filters.value))
}

function loadFiltersFromLocalStorage() {
  const savedFilters = JSON.parse(localStorage.getItem('jobFilters'))
  if (savedFilters) {
    // Upewnij się, że każde pole jest poprawnie przywrócone
    filters.value = {
      ...filters.value,
      ...savedFilters,
      languages: Array.isArray(savedFilters.languages) ? savedFilters.languages : [],
    }
  }
}

function closePanel() {
  emit('update:showFilters', false)
}

onMounted(() => {
  loadFiltersFromLocalStorage()
})

</script>