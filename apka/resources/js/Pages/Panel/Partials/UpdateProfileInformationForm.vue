<template>
  <div class="max-w-4xl mx-auto p-6 bg-white">
    <h2 class="text-2xl font-semibold mb-5 text-gray-800">Aktualizuj informacje o profilu firmy</h2>
    <form @submit.prevent="updateProfile" class="space-y-5">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-1">
          <label for="name" class="text-sm font-medium text-gray-700">Imię</label>
          <input v-model="form.name" type="text" id="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="Twoje imię" required>
        </div>
        <div class="space-y-1">
          <label for="surname" class="text-sm font-medium text-gray-700">Nazwisko</label>
          <input v-model="form.surname" type="text" id="surname" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="Twoje nazwisko" required>
        </div>
        <div class="space-y-1">
          <label for="email" class="text-sm font-medium text-gray-700">Email</label>
          <input v-model="form.email" type="email" id="email" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="Email firmy" required>
        </div>
        <div class="space-y-1">
          <label for="companyName" class="text-sm font-medium text-gray-700">Nazwa firmy</label>
          <input v-model="form.companyName" type="text" id="companyName" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="Nazwa firmy" required>
        </div>
        <div class="space-y-1">
          <label for="location" class="text-sm font-medium text-gray-700">Lokalizacja</label>
          <input v-model="form.location" type="text" id="location" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="Lokalizacja firmy" required>
        </div>
        <div class="space-y-1">
          <label for="nip" class="text-sm font-medium text-gray-700">NIP</label>
          <input v-model="form.nip" type="text" id="nip" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="NIP firmy" required>
        </div>
      </div>
      <div class="space-y-1">
        <label for="description" class="text-sm font-medium text-gray-700">Opis firmy</label>
        <textarea v-model="form.description" id="description" rows="4" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-cyan-500 focus:border-cyan-500" placeholder="Krótki opis firmy" required></textarea>
      </div>
      <div v-if="form.logoUrl" class="space-y-1">
        <h3 class="text-lg font-semibold text-gray-800">Aktualne Logo Firmy</h3>
        <div class="flex items-center space-x-2">
          <img :src="form.logoUrl" alt="Logo firmy" class="h-20 w-48 object-contain rounded my-2">
          <button type="button" @click="removeLogo" class="px-4 py-2 bg-red-500 text-white font-medium rounded hover:bg-red-600 transition-colors duration-200">Usuń Logo</button>
        </div>
      </div>
      <div v-else class="space-y-1">
        <label for="logo" class="text-sm font-medium text-gray-700">Logo firmy</label>
        <input type="file" id="logo" @change="handleFileUpload" class="block w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:rounded file:text-sm file:font-semibold file:bg-cyan-50 file:text-cyan-700 hover:file:bg-cyan-100 focus:ring-cyan-500 focus:border-cyan-500">
      </div>
      <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-cyan-600 text-white font-medium rounded hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">Aktualizuj</button>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import toastr from 'toastr';

const form = ref({
    name: '',
    surname: '',
    email: '',
    companyName: '',
    location: '',
    nip: '',
    description: '',
    logo: null,
    logoUrl: null,
});

const handleFileUpload = event => {
    form.value.logo = event.target.files[0];
};

const updateProfile = () => {
    const formData = new FormData();
    for (let key in form.value) {
        formData.append(key, form.value[key]);
    }

    axios.post('/panel/update', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
    .then(response => {
        toastr.success('Profil zaktualizowany pomyślnie.');
    })
    .catch(error => {
        console.error('Wystąpił błąd podczas aktualizacji profilu: ', error);
        toastr.error('Nie udało się zaktualizować profilu.');
    });
};

const removeLogo = () => {
  axios.post('/panel/remove-logo')
    .then(() => {
      form.value.logoUrl = null;
      toastr.success('Logo zostało usunięte.');
    })
    .catch(error => {
      console.error('Wystąpił błąd podczas usuwania logo: ', error);
      toastr.error('Nie udało się usunąć logo.');
    });
};

onMounted(() => {
    axios.post('/panel/data')
    .then(response => {
        const { user, company } = response.data;
        form.value.name = user.name;
        form.value.surname = user.surname;
        form.value.email = user.email;
        form.value.companyName = company.name;
        form.value.location = company.location;
        form.value.nip = company.nip;
        form.value.description = company.description;
        form.value.logoUrl = company.logo ? `/storage/${company.logo}` : null;
    })
    .catch(error => {
        toastr.error('Wystąpił błąd podczas ładowania danych');
        console.error('Wystąpił błąd podczas ładowania danych profilu: ', error);
    });
});
</script>
