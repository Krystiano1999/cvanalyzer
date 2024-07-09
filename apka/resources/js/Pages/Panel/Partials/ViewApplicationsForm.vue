<template>
  <div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-1/2 px-2 mb-4">
      <div class="bg-white rounded-lg p-6 h-full shadow-lg">
        <h2 class="font-semibold text-xl mb-4 text-gray-800">Przeglądaj aplikacje</h2>
        <div v-if="applications.length > 0">
          <ul class="space-y-2">
            <li v-for="application in applications" :key="application.id" class="flex justify-between items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition" @click="selectApplication(application)">
              <div class="flex items-center space-x-3">
                <font-awesome-icon :icon="['fas', 'user-circle']" class="text-gray-400 text-xl" />
                <p class="font-medium text-gray-900">{{ application.name }}</p>
              </div>
              <span :class="getPointsClass(application.user_points)" class="inline-block text-white text-sm font-medium py-1 px-3 rounded-full">{{ application.user_points }}</span>
            </li>
          </ul>
        </div>
        <div v-else class="text-gray-500 text-center py-10">
          <p>Brak aplikacji dla tej oferty pracy.</p>
        </div>
      </div>
    </div>

    <div class="w-full md:w-1/2 px-2 mb-4">
      <div class="bg-white rounded-lg p-6 h-full shadow-lg">
        <h3 class="font-semibold text-lg mb-4 text-gray-800">Szczegóły aplikacji</h3>
        <div v-if="selectedApplication" class="space-y-3">
          <p><strong>Imię i nazwisko:</strong> {{ selectedApplication.name }}</p>
          <p><strong>Email:</strong> {{ selectedApplication.email }}</p>
          <p><strong>Telefon:</strong> {{ selectedApplication.phone }}</p>
          <p><strong>Punkty:</strong> {{ selectedApplication.user_points }}</p>
          <div v-if="selectedApplication.soft_skills.length > 0">
            <strong>Umiejętności miękkie:</strong>
            <div class="flex flex-wrap mt-2">
              <span v-for="(skill, index) in selectedApplication.soft_skills" :key="index" class="bg-purple-600 text-white text-xs font-medium m-1 px-2 py-1 rounded-full">{{ skill }}</span>
            </div>
          </div>

          <div v-if="Object.keys(selectedApplication.hard_skills).length > 0">
            <strong>Umiejętności twarde:</strong>
            <div class="flex flex-wrap mt-2">
              <span v-for="(skill, key) in selectedApplication.hard_skills" :key="key" class="bg-blue-600 text-white text-xs font-medium m-1 px-2 py-1 rounded-full">{{ skill }}</span>
            </div>
          </div>

          <div v-if="selectedApplication.languages.length > 0">
            <strong>Języki:</strong>
            <div class="flex flex-wrap mt-2">
              <span v-for="(language, index) in selectedApplication.languages" :key="index" class="bg-green-600 text-white text-xs font-medium m-1 px-2 py-1 rounded-full">{{ language }}</span>
            </div>
          </div>
          <p v-if="selectedApplication.cv_path"><strong>CV:</strong> <a :href="`/storage/${selectedApplication.cv_path}`" target="_blank" class="text-blue-500 hover:underline">Pobierz</a></p>
        </div>
        <div v-else class="text-gray-500 text-center py-10">
          <p>Kliknij na aplikację, aby zobaczyć szczegóły.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faUserCircle } from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

library.add(faUserCircle);

const props = defineProps({
  jobId: Number
});
const applications = ref([]);
const selectedApplication = ref(null);

const fetchApplications = async () => {
  try {
    const response = await axios.post(`/panel/job-applications/${props.jobId}`);
    applications.value = response.data;
  } catch (error) {
    console.error('Błąd przy pobieraniu aplikacji:', error);
  }
};

onMounted(fetchApplications);

const selectApplication = (application) => {
  selectedApplication.value = application;
};

const getPointsClass = (points) => {
  if (points >= 100) return 'bg-green-500';
  else if (points >= 50) return 'bg-yellow-500';
  else return 'bg-red-500';
};
</script>
