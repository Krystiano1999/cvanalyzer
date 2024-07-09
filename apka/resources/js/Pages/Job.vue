<template>
  <Head title="Oferty" />
  <JobsLayout>
    <template #header>
      <div class="flex justify-between items-center py-2">
        <div class="flex items-center">
          <SearchForm @search-updated="onSearchUpdated" />
          <LocationSelector @location-selected="onLocationSelected" />
        </div>
        <button
          @click="showFilters = true"
          class="bg-cyan-500 text-white py-1 px-2 rounded hover:bg-cyan-700 transition duration-150 ease-in-out"
        >
          Pokaż filtry
        </button>
        <FilterPanel v-if="showFilters" @update:showFilters="showFilters = $event" @filters-applied="handleFiltersApplied" />
      </div>
    </template>

    <div class="flex">
      <div class="max-w-screen-2xl mx-auto flex w-full">
        <div class="w-1/2 bg-white overflow-hidden shadow-sm p-5 space-y-4 custom-box">
          <ul>
            <li
              v-for="job in jobPosts"
              :key="job.id"
              @click="selectJob(job)"
              class="bg-gray-100 p-3 my-1 rounded-lg flex justify-between items-start space-x-4"
            >
              <div>
                <h3 class="font-bold">{{ job.title }}</h3>
                <p class="text-sm text-gray-700">Lokalizacja: {{ job.company.location }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm font-bold">{{ job.salary_min }} - {{ job.salary_max }} PLN</p>
                <div class="flex flex-wrap justify-end mt-2">
                  <span
                    v-for="(skill, index) in job.required_skills.slice(0, 3)"
                    :key="index"
                    class="bg-pink-500 text-white text-xs px-2 py-1 rounded-full m-1"
                  >
                    {{ skill }}
                  </span>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="w-1/2 bg-white overflow-hidden shadow-sm p-0 border-l border-cyan-600 custom-box">
          <JobDetails :job="selectedJob" />
        </div>
      </div>
    </div>
  </JobsLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import JobDetails from '@/Components/app/JobDetails.vue';
import JobsLayout from '@/Layouts/JobsLayout.vue';
import SearchForm from '@/Components/app/SearchForm.vue';
import LocationSelector from '@/Components/app/LocationSelector.vue';
import FilterPanel from '@/Components/app/FilterPanel.vue';
import toastr from 'toastr';


const showFilters = ref(false);
const jobPosts = ref([]);
const selectedJob = ref(null);
const search = ref(''); 
const location = ref('');
const filters = ref({}); 

const selectJob = (job) => {
  selectedJob.value = job;
};

const updateJobs = async () => {
  const searchParams = {
    search: search.value,
    location: location.value,
    ...filters.value,
  };
  
  try {
    const response = await fetch('/oferty/szukaj', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify(searchParams),
      credentials: 'include'
    });

    if (response.ok) {
      const data = await response.json();
      jobPosts.value = data.jobPosts;
    } else {
      console.error('Błąd podczas pobierania ofert pracy');
    }
  } catch (error) {
    console.error('Błąd połączenia z serwerem:', error);
      toastr.error("DUpson Kwadrat");
  }
};

const onSearchUpdated = (newSearchTerm) => {
  search.value = newSearchTerm;
  updateJobs();
};

const onLocationSelected = (newLocation) => {
  location.value = newLocation;
  updateJobs();
};

const handleFiltersApplied = (newFilters) => {
  filters.value = newFilters;
  updateJobs();
};

onMounted(() => {
  updateJobs();
});
</script>

<style scoped>
.custom-box {
  max-height: calc(100vh - 130px);
  height: calc(100vh - 130px);
  overflow-y: auto;
}
</style>
