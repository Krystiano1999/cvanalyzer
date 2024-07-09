<template>
  <div class="max-w-4xl mx-auto p-6 bg-white">
    <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-800">Zarządzaj ofertami pracy</h2>
      <button @click="isAddModalOpen = true" class="text-sm bg-cyan-500 hover:bg-cyan-600 text-white py-2 px-3 rounded focus:outline-none transition duration-150 ease-in-out">
        <font-awesome-icon icon="plus" class="mr-1" /> Dodaj ofertę
      </button>
    </div>
    <div v-if="jobPosts.length" class="space-y-4">
      <ul>
        <li v-for="jobPost in jobPosts" :key="jobPost.id" class="border rounded shadow px-4 py-2 my-1 flex justify-between items-center">
          <span class="font-medium text-gray-900">{{ jobPost.title }}</span>
          <div class="flex items-center space-x-2">
            <button @click="viewApplications(jobPost.id)" class="text-sm bg-cyan-500 hover:bg-cyan-600 text-white py-1 px-3 rounded focus:outline-none transition duration-150 ease-in-out">
                <font-awesome-icon icon="eye" class="mr-1" /> Aplikacje
            </button>
            <button @click="editJobPost(jobPost.id)" class="text-sm bg-gray-500 hover:bg-gray-600 text-white py-1 px-3 rounded focus:outline-none transition duration-150 ease-in-out">
                <font-awesome-icon icon="pencil-alt" class="mr-1" /> Edytuj
            </button>
            <button @click="deleteJobPost(jobPost.id)" class="text-sm bg-pink-500 hover:bg-pink-600 text-white py-1 px-3 rounded focus:outline-none transition duration-150 ease-in-out">
                <font-awesome-icon icon="trash" class="mr-1" /> Usuń
            </button>
          </div>
        </li>
      </ul>
    </div>
    <div v-else class="text-center py-8">
      <p class="text-gray-500">Nie znaleziono ofert pracy.</p>
    </div>
  </div>

  <!-- Modal do dodawania ogłoszenia -->
  <JobPostModalAdd v-if="isAddModalOpen" @close="handleAddModalClose" @save="fetchJobPosts" />

  <!-- Modal do edycji ogłoszenia -->
  <JobPostModalUpdate v-if="isModalOpen" :job-post="selectedJobPost" @close="handleModalClose" @save="fetchJobPosts" />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import toastr from 'toastr';
import JobPostModalAdd from './JobPostModalAdd.vue';
import JobPostModalUpdate from './JobPostModalUpdate.vue';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEye, faPencilAlt, faTrash, faPlus } from '@fortawesome/free-solid-svg-icons';
import Swal from 'sweetalert2';

library.add(faEye, faPencilAlt, faTrash, faPlus);

const jobPosts = ref([]);
const selectedJobPost = ref(null);
const isModalOpen = ref(false);
const isAddModalOpen = ref(false);

const fetchJobPosts = async () => {
  try {
    const response = await axios.post('/panel/job-posts');
    jobPosts.value = response.data;
  } catch (error) {
    toastr.error('Wystąpił błąd podczas pobierania ogłoszeń.');
  }
};

const emit = defineEmits(['changeComponent']);

const viewApplications = (id) => {
  emit('changeComponent', { component: 'applications', jobId: id });
};

const editJobPost = (id) => {
  const jobPost = jobPosts.value.find((post) => post.id === id);
  if (jobPost) {
    selectedJobPost.value = {...jobPost}; 
    isModalOpen.value = true;
  } else {
    toastr.error('Nie znaleziono ogłoszenia do edycji.');
  }
};

const handleModalClose = () => {
  isModalOpen.value = false;
  selectedJobPost.value = null;
};

const handleAddModalClose = () => {
  isAddModalOpen.value = false;
};

const deleteJobPost = (id) => {
  Swal.fire({
    title: 'Czy na pewno chcesz usunąć?',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Tak!',
    cancelButtonText: 'Anuluj',
  }).then((result) => {
    if (result.isConfirmed) {
      axios.delete(`/panel/job-posts/${id}`)
        .then(() => {
          toastr.success('Oferta pracy została usunięta.');
          fetchJobPosts();
        })
        .catch(error => {
          toastr.error('Wystąpił błąd podczas usuwania ogłoszenia: ' + error.message);
        });
    }
  });
};

onMounted(fetchJobPosts);
</script>
