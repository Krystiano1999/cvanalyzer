<template>
  <div class="max-w-md mx-auto mt-10">
    <div class="relative group">
      <div @click="triggerFileInput" class="flex flex-col items-center justify-center p-2 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-blue-50 transition duration-300 ease-in-out">
        <input type="file" @change="uploadImage" hidden ref="fileInput" accept="image/jpeg, image/png, image/webp" />
        <img v-if="preview" :src="preview" alt="Photo preview" class="max-h-56 rounded-md" />
        <div v-if="!preview" class="flex flex-col items-center justify-center">
          <svg class="h-10 w-10 text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0l-4-4m4 4V4"></path></svg>
          <span class="text-gray-600 text-center">Załącz swoje zdjęcie</span>
        </div>
      </div>
      <div v-if="preview" class="absolute inset-0 flex items-end justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-linear">
        <span class="text-white text-sm font-semibold bg-black bg-opacity-50 py-1 px-3 rounded-md cursor-pointer" @click.stop="triggerFileInput">Zmień</span>
        <span class="text-white text-sm font-semibold bg-red-600 py-1 px-3 ml-2 rounded-md cursor-pointer" @click.stop="removeImage">Usuń</span>
      </div>
    </div>
    <p v-if="errorMessage" class="text-red-600 text-center mt-2">{{ errorMessage }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import toastr from 'toastr';

const fileInput = ref(null);
const preview = ref('');
const errorMessage = ref('');

onMounted(async () => {
  try {
    const response = await fetch('/profil/existing-photo', {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();
    if (data.photo) {
      preview.value = data.photo; // URL do zdjęcia
    }
  } catch (error) {
    console.error('There was a problem with fetching existing photo:', error);
  }
});

function triggerFileInput() {
  fileInput.value.click();
}

async function uploadImage(event) {
  const file = event.target.files[0];
  if (!file) return;

  const maxFileSize = 2 * 1024 * 1024; // 2MB
  if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > maxFileSize) {
    errorMessage.value = 'Plik musi być w formacie JPEG, PNG lub WEBP i nie przekraczać 2MB.';
    return;
  }

  preview.value = URL.createObjectURL(file);

  const formData = new FormData();
  formData.append('photo', file);

  try {
    const response = await fetch('/profil/upload-photo', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    });

    if (!response.ok) throw new Error('Network response was not ok');
    const responseData = await response.json();
    toastr.success(responseData.message);
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error);
    errorMessage.value = 'Wystąpił problem podczas wysyłania zdjęcia.';
    toastr.error(errorMessage.value);
  }
}

async function removeImage() {
  try {
    const response = await fetch('/profil/remove-photo', {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    });

    if (!response.ok) throw new Error('Network response was not ok');
    const responseData = await response.json();
    toastr.success(responseData.message);
    preview.value = ''; 
    fileInput.value.value = ''; 
  } catch (error) {
    console.error('There was a problem with the fetch operation:', error);
    toastr.error('Wystąpił problem podczas usuwania zdjęcia.');
  }
}
</script>
