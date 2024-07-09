<template>
  <transition name="slide-in">
    <div class="fixed inset-0 z-50 flex" v-show="showModal" @click.self="closeModal">
      <div class="relative w-full max-w-md bg-white shadow-xl rounded-tr-md rounded-br-md border border-pink-600 p-8 overflow-y-auto" @click.stop>
        <button @click="closeModal" class="absolute top-0 right-0 mt-4 mr-4">
          <svg class="h-6 w-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        <h2 class="text-xl font-semibold mb-4">Aplikuj</h2>
        <form class="space-y-6" @submit.prevent="submitForm">
          <div class="space-y-2">
            <input type="hidden" id="job-id" :value="job ? job.id : null">
            <label class="block text-sm font-medium text-gray-700">Imię i nazwisko*</label>
            <input type="text" v-model="formData.name" class="w-full p-2 border border-gray-300 rounded-md" >
          </div>
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Adres email*</label>
            <input type="email" v-model="formData.email" class="w-full p-2 border border-gray-300 rounded-md" >
          </div>
          <div class="space-y-2 file-upload-area" @dragover.prevent="dragOver" @drop.prevent="handleFileDrop">
            <label class="block text-sm font-medium text-gray-700">Dodaj CV</label>
            <input ref="fileInput" type="file" @change="handleFileChange" class="hidden" accept=".pdf" />
            <div class="flex justify-center items-center p-4 border border-dashed border-cyan-700 rounded-md cursor-pointer hover:bg-cyan-100" @click="triggerInput">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              <span class="text-sm text-gray-500">Dodaj dokument (PDF)</span>
            </div>
            <div v-if="fileDisplayName" class="mt-2 flex items-center justify-between text-sm text-gray-700">
              {{ fileDisplayName }}
              <button @click="removeFile" class="ml-2 text-red-500 hover:text-red-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
            </div>
          </div>
          <div class="flex items-center">
            <input id="terms" type="checkbox" v-model="formData.terms" class="h-4 w-4 text-cyan-600 border-gray-300 rounded " >
            <label for="terms" class="ml-2 block text-sm text-gray-900 ">
              Wyrażam zgodę na przetwarzanie moich danych osobowych dla celów przyszłych rekrutacji.
            </label>
          </div>
          <button type="submit" class="w-full bg-cyan-600 text-white p-2 rounded-md hover:bg-cyan-700 focus:outline-none focus:bg-cyan-700">
            Aplikuj
          </button>
        </form>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue';
import toastr from 'toastr';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const { showModal, job } = defineProps({
  showModal: Boolean,
  job: Object
});
const emit = defineEmits(['update:showModal']);

const formData = reactive({
  name: '',
  email: '',
  terms: false,
  jobId: null 
});
const file = ref(null);
const fileInput = ref(null);
const fileDisplayName = ref('');

watch(() => showModal, (newVal) => {
  if (newVal) {
    formData.jobId = job ? job.id : null;
    updateUserData();
  } else {
    resetForm();
  }
}, { immediate: true });

const updateUserData = () => {
  const user = usePage().props.auth.user;
  if (user) {
    formData.name = `${user.name ?? ''} ${user.surname ?? ''}`.trim();
    formData.email = user.email ?? '';
    if (user.cv) {
      fileDisplayName.value = user.cv.split('/').pop();
    }
  }
};

onMounted(() => {
  updateUserData();
});

const closeModal = () => {
  emit('update:showModal', false);
};

function resetForm() {
  formData.name = '';
  formData.email = '';
  formData.terms = false;
  file.value = null;
  fileInput.value && (fileInput.value.value = '');
  fileDisplayName.value = '';
}

const triggerInput = () => {
  fileInput.value.click();
};

const handleFileChange = (event) => {
  const selectedFile = event.target.files[0];
  if (selectedFile.type !== "application/pdf") {
    toastr.error("Proszę wybrać plik w formacie PDF.");
    file.value = null;
  } else {
    file.value = selectedFile;
    fileDisplayName.value = selectedFile.name;
  }
};

const removeFile = () => {
  file.value = null;
  fileDisplayName.value = '';
  fileInput.value.value = '';
}

const submitForm = async () => {
  let errorMessage = '';
  if (!formData.name) {
    errorMessage += 'Pole imię i nazwisko jest wymagane. ';
  }
  else if (!formData.email) {
    errorMessage += 'Pole adres email jest wymagane. ';
  }
  else if (!file.value && !fileDisplayName.value) {
    errorMessage += 'Należy dodać CV w formacie PDF. ';
  }
  else if (!formData.terms) {
    errorMessage += 'Musisz wyrazić zgodę na przetwarzanie danych osobowych. ';
  }
  formData.jobId = document.getElementById('job-id').value;

  if (errorMessage) {
    toastr.error(errorMessage);
    return;
  }

  const formDataToSend = new FormData();
  formDataToSend.append('name', formData.name);
  formDataToSend.append('email', formData.email);
  formDataToSend.append('jobId', formData.jobId);
  if (file.value) {
    formDataToSend.append('cv', file.value); 
  }

  try {
    const response = await fetch('/applications', {
      method: 'POST',
      body: formDataToSend,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
      },
    });

    if (!response.ok) {
      throw new Error('Network response was not ok.');
    }

    const responseData = await response.json();

    toastr.success('Zaaplikowałeś na ofertę.');
    closeModal();
    resetForm();
  } catch (error) {
    toastr.error('Wystąpił błąd podczas wysyłania formularza: ' + error.message);
  }
};

</script>


<style>
.slide-in-enter-active {
  transition: transform 0.3s ease;
}
.slide-in-enter-from,
.slide-in-leave-to {
  transform: translateX(-100%);
}

[type='checkbox']:focus, [type='radio']:focus {
  outline: 2px solid transparent;
  outline-offset: 2px;
  --tw-ring-color: var(--tw-color-cyan-600);
  box-shadow: 0 0 0 2px var(--tw-ring-color);
}

</style>