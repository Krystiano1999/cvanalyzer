<template>
  <div v-if="job" class="job-details pb-5">
    <div class="flex items-center space-x-4 mb-6 p-5 text-white " :style="{ background: backgroundColor }">
      <div v-if="logoLoaded && job.company.logo" class="logo bg-white w-28 h-28 rounded-lg flex items-center justify-center overflow-hidden">
        <img :src="`storage/${job.company.logo}`" :alt="job.company.name" @error="handleImageError" class="object-contain w-full h-full">
      </div>
      <div v-else class="logo bg-white w-28 h-28 rounded-lg flex items-center justify-center overflow-hidden">
        <span v-bind:style="gradientTextStyle" class="font-bold text-4xl">{{ companyInitials }}</span>
      </div>
      <div class="flex-1">
        <h2 class="text-4xl font-bold">{{ job.title }}</h2>
        <p class="text-xl">{{ job.company.name }}</p>
        <p class="text-xl">{{ job.salary_min }} - {{ job.salary_max }} PLN</p>
      </div>
    </div>

     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-5 text-center">
      <div class="info-tile transition duration-500 ease-in-out  hover:-translate-y-1 hover:scale-105 p-4 rounded-lg shadow-lg"
           :style="{ background: backgroundColor, color: 'white' }">
        <p class="font-bold">{{ job.employment_type.join(', ') }}</p>
      </div>
      <div class="info-tile transition duration-500 ease-in-out  hover:-translate-y-1 hover:scale-105 p-4 rounded-lg shadow-lg"
           :style="{ background: backgroundColor, color: 'white' }">
        <p class="font-bold">{{ job.experience.join(', ') }}</p>
      </div>
      <div class="info-tile transition duration-500 ease-in-out  hover:-translate-y-1 hover:scale-105 p-4 rounded-lg shadow-lg"
           :style="{ background: backgroundColor, color: 'white' }">
        <p class="font-bold">{{ job.contract_type.join(', ') }}</p>
      </div>
      <div class="info-tile transition duration-500 ease-in-out  hover:-translate-y-1 hover:scale-105 p-4 rounded-lg shadow-lg"
           :style="{ background: backgroundColor, color: 'white' }">
        <p class="font-bold">{{ job.job_mode || 'N/A' }}</p>
      </div>
    </div>

    <div class="skills p-5">
      <h3 class="mb-2 font-bold">Wymagane umiejętności:</h3>
      <div class="flex flex-wrap">
        <span v-for="(skill, index) in job.required_skills" :key="index" class="bg-pink-500 text-white text-xs px-2 py-1 rounded-full m-1">
          {{ skill }}
        </span>
      </div>
    </div>

    <div class="skills p-5" v-if="job.skills">
      <h3 class="mb-2 font-bold">Dodatkowe umiejętności:</h3>
      <div class="flex flex-wrap">
        <span v-for="(skill, index) in job.skills" :key="index" class="bg-cyan-500 text-white text-xs px-2 py-1 rounded-full m-1">
          {{ skill }}
        </span>
      </div>
    </div>

    <div class="description p-5 border-b-2 border-cyan-700">
      <h3 class="mb-2 font-bold">Opis stanowiska:</h3>
      <p>{{ job.description }}</p>
    </div>

    <div class="company-info p-5">
      <h3 class="mb-2 font-bold text-3xl">{{ job.company.name }}</h3>
      <p>{{ job.company.description }}</p>
    </div>

    <button @click="showModal = true" class="bg-cyan-500 text-white py-2 px-4 ml-5 rounded hover:bg-cyan-700 transition duration-150 ease-in-out">Aplikuj</button>
  </div>
  <div v-else>
    <p>Wybierz ofertę pracy, aby zobaczyć szczegóły.</p>
  </div>

  <ModalApplication :showModal="showModal" :job="job" @update:showModal="showModal = $event" />

</template>

<script setup>
import { defineProps, computed, ref } from 'vue';
import ModalApplication from '@/Components/app/ModalApplication.vue';

const props = defineProps({
  job: Object,
});

const logoLoaded = ref(true); 

const getInitials = (name) => name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 3);

const computeBackgroundGradient = (name) => {
  let hash1 = 0, hash2 = 0;
  for (let i = 0; i < name.length; i++) {
    hash1 = name.charCodeAt(i) + ((hash1 << 5) - hash1);
    hash2 = name.charCodeAt(name.length - i - 1) + ((hash2 << 5) - hash2); 
  }
  const hue1 = Math.abs(hash1) % 360;
  const hue2 = Math.abs(hash2) % 360;
  return `linear-gradient(135deg, hsl(${hue1}, 100%, 30%), hsl(${hue2}, 100%, 30%))`;
};

const companyInitials = computed(() => getInitials(props.job?.company?.name || ''));
const backgroundColor = computed(() => computeBackgroundGradient(props.job?.company?.name || ''));

const gradientTextStyle = computed(() => ({
  background: backgroundColor.value,
  '-webkit-background-clip': 'text',
  'background-clip': 'text',
  color: 'transparent',
}));

const handleImageError = () => {
  logoLoaded.value = false; 
};

const showModal = ref(false);

</script>