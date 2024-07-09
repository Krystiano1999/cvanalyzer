<template>
  <div v-if="!fileLoaded" @click="triggerFileInput" @drop.prevent="handleDrop" @dragover.prevent="handleDragOver" @dragleave.prevent="handleDragLeave" class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:border-cyan-500 relative" :class="{ 'bg-cyan-100 border-cyan-500': isDragOver }">
    <input type="file" @change="handleFileSelect" hidden ref="fileInput" accept="application/pdf" />
    <div v-if="loading" class="absolute top-0 right-0 bottom-0 left-0 bg-white bg-opacity-75 flex items-center justify-center">
      <font-awesome-icon icon="spinner" class="fa-spin text-cyan-500" size="2x" />
    </div>
    <p class="text-gray-700">{{ dropzoneMessage }}</p>
    <div v-if="!fileLoaded" class="mt-2 text-cyan-500 hover:text-cyan-700">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Załącz CV
    </div>
  </div>
  <div v-if="fileLoaded" class="p-6 bg-white border-2 border-gray-300 rounded-lg mt-4 flex items-center justify-center">
    <font-awesome-icon icon="file-pdf" class="text-red-500" />
    <span class="text-gray-500 text-lg ml-2 truncate">{{ fileName }}</span>
    <font-awesome-icon icon="times-circle" class="ml-2 text-red-500 cursor-pointer" @click="removeCv" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faSpinner, faFilePdf, faTimesCircle } from '@fortawesome/free-solid-svg-icons';
import toastr from 'toastr';
import axios from 'axios';

library.add(faSpinner, faFilePdf, faTimesCircle);

const fileInput = ref(null);
const loading = ref(false);
const fileLoaded = ref(false);
const fileName = ref('');
const dropzoneMessage = ref('');

onMounted(async () => {
  try {
    const response = await axios.get('/profil/get-cv');
    if (response.data.exists) {
      fileLoaded.value = true;
      fileName.value = response.data.fileName;
    }
  } catch (error) {
    //console.error(error);
  }
});

function triggerFileInput() {
  fileInput.value.click();
}

async function handleFileSelect(event) {
  const file = event.target.files[0];
  if (!file) {
    return;
  }
  if (file.type !== 'application/pdf') {
    toastr.error('Dozwolone są tylko pliki PDF!');
    return;
  }

  const formData = new FormData();
  formData.append('cv', file);

  loading.value = true;

  try {
    const response = await fetch('/profil/upload-cv', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || 'Network response was not ok');
    }

    const responseData = await response.json();
    toastr.success(responseData.message);
    fileLoaded.value = true;
    fileName.value = file.name;
  } catch (error) {
    toastr.error('Wystąpił błąd podczas ładowania CV.');
    console.error(error);
  } finally {
    loading.value = false;
  }
}

async function removeCv() {
  try {
    const response = await fetch('/profil/remove-cv', {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const responseData = await response.json();
    toastr.success(responseData.message);
    fileLoaded.value = false;
    fileName.value = '';

    if (fileInput && fileInput.value) {
      fileInput.value.value = '';
    }
  } catch (error) {
    toastr.error('Wystąpił błąd podczas usuwania CV.');
    console.error(error);
  }
}

</script>


<style scoped>
.font-awesome-icons {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.ml-2 {
  margin-left: 0.5rem;
}

.bg-white {
  background-color: white;
}

.rounded-full {
  border-radius: 9999px;
}

.cursor-pointer {
  cursor: pointer;
}

.cursor-default {
  cursor: default;
}

.text-red-500 {
  color: #ef4444;
}

.fa-times-circle {
  padding: 0.25rem;
}
</style>
