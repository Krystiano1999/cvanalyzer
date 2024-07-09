<template>
  <div class="max-w-4xl mx-auto my-8">
    <div class="border-2 border-dashed border-gray-300 p-5 text-center cursor-pointer hover:bg-gray-100 rounded-lg" @dragover.prevent="dragOver" @drop.prevent="handleDrop" @click="triggerFileInput">
      <input type="file" ref="fileInput" @change="handleFiles" accept="application/pdf" class="hidden" />
      <p class="text-gray-700 text-lg">Przeciągnij tutaj swoje CV w formacie PDF lub kliknij, aby wybrać plik.</p>
      <p v-if="file" class="mt-2 font-semibold">{{ file.name }}</p>
    </div>

    <div v-if="responseData && responseData.entities" class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
      <template v-for="(items, entity) in responseData.entities" :key="entity">
        <div class="bg-white shadow-lg rounded-lg p-4" v-if="!['HARD_SKILL', 'SOFT_SKILL', 'LANGUAGE'].includes(entity)">
          <h2 class="text-xl font-bold text-gray-800 mb-2">{{ formatLabel(entity) }}</h2>
          <div v-if="Array.isArray(items)">
            <p v-for="(item, index) in items" :key="index" class="text-gray-700 mt-1">{{ item }}</p>
          </div>
          <div v-else>
            <p class="text-gray-700 mt-1">{{ items }}</p>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 bg-white shadow-lg rounded-lg p-4" v-if="['HARD_SKILL', 'SOFT_SKILL', 'LANGUAGE'].includes(entity)">
          <h2 class="w-full text-xl font-bold text-gray-800 mb-2">{{ formatLabel(entity) }}</h2>
          <span v-for="(item, index) in items" :key="index" class="bg-cyan-500 text-white py-1 px-3 rounded-full text-sm">
            {{ item }}
          </span>
        </div>
      </template>
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue';
import toastr from 'toastr';

const file = ref(null);
const fileInput = ref(null);
const responseData = ref(null);

const dragOver = (event) => {
  event.preventDefault();
};

const handleDrop = (event) => {
  const files = event.dataTransfer.files;
  processFiles(files);
};

const processFiles = (files) => {
  if (files.length > 0 && files[0].type === "application/pdf") {
    file.value = files[0];
    uploadFile(); 
  } else {
    alert("Proszę upuścić plik PDF.");
  }
};

const handleFiles = () => {
  if (fileInput.value.files.length > 0) {
    processFiles(fileInput.value.files);
  }
};

const triggerFileInput = () => {
  fileInput.value.click();
};

const formatLabel = (label) => {
  const labels = {
    NAME: "Imię i nazwisko",
    EMAIL: "Email",
    PHONE: "Telefon",
    EDUCATION: "Edukacja",
    EXPERIENCE: "Doświadczenie zawodowe",
    COURSE: "Kursy",
    SOFT_SKILL: "Umiejętności miękkie",
    HARD_SKILL: "Umiejętności twarde",
    LANGUAGE: "Języki",
  };
  return labels[label] || label;
};

const uploadFile = async () => {
  if (!file.value) {
    toastr.error("Nie wybrano pliku.");
    return;
  }

  const formData = new FormData();
  formData.append('cv', file.value);

 try {
    const response = await fetch('/upload-cv', {
      method: 'POST',
      body: formData,
      headers: {
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const text = await response.text();
    if (text) { 
      try {
        responseData.value = JSON.parse(text); 
        console.log(responseData.value);
        toastr.success('Plik został pomyślnie przesłany i przetworzony.');
      } catch (e) {
        console.error('Nie udało się przetworzyć odpowiedzi jako JSON:', e);
        toastr.error('Plik został przesłany, ale wystąpił problem z przetworzeniem odpowiedzi.');
      }
    } else {
      console.error('Odpowiedź z serwera jest pusta.');
      toastr.error('Plik został przesłany, ale serwer nie zwrócił żadnych danych.');
    }
  } catch (error) {
    console.error('Wystąpił problem z przesyłaniem pliku:', error);
    toastr.error('Wystąpił błąd podczas przesyłania pliku.');
  }
};
</script>
