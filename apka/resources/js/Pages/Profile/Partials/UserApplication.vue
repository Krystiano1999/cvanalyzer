<template>
  <div class="max-w-4xl mx-auto p-4">
    <header>
        <h2 class="text-lg font-medium text-gray-900">Przeglądaj swoje aplikacje</h2>
    </header>

    <div class="space-y-4">
      <div v-for="(application, index) in applications" :key="index" class="bg-white shadow rounded-lg p-4 flex justify-between items-center">
        <div>
          <h3 class="font-semibold text-lg">{{ application.job_post.title }}</h3>
          <p class="text-gray-600">{{ application.job_post.company.name }}</p>
          <p class="text-gray-500">{{ application.job_post.salary_min }} - {{ application.job_post.salary_max }} PLN</p>
        </div>
        <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-800 rounded-full font-bold">
          {{ application.user_points }}
        </div>
      </div>
    </div>
    <p v-if="applications.length === 0" class="text-center text-gray-500">Brak aplikacji do wyświetlenia.</p>
  </div>
</template>

<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

const applications = ref([]);

onMounted(() => {
  fetchApplications();
});

function fetchApplications() {
  axios.post('/profil/applications')
    .then(response => {
      applications.value = response.data.applications;
    })
    .catch(error => {
      console.error('There was an error fetching the applications:', error);
    });
}
</script>
