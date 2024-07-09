<template>
  <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" @click="closeModal">
    <div class="relative top-20 mx-auto p-5 border w-3/4 max-w-xl shadow-lg rounded-md bg-white" @click.stop>
      <div class="flex justify-between items-center mb-5">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Edytuj ogłoszenie </h3>
        <button @click="closeModal" class="rounded-md bg-gray-100 p-2 hover:bg-gray-200">
            <font-awesome-icon icon="times" />
        </button>
      </div>
      <form @submit.prevent="handleSubmit">
        <div class="space-y-4">
          <input v-model="jobPost.id" type="hidden" value="{{jobPost.id}}"/>
          <!-- Title Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Tytuł</label>
            <input v-model="jobPost.title" type="text" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500" />
          </div>

          <!-- Description Field -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Opis</label>
            <textarea v-model="jobPost.description" rows="3" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500"></textarea>
          </div>

          <!-- Salary Fields -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Minimalne wynagrodzenie</label>
              <input v-model.number="jobPost.salary_min" type="number" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500" />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Maksymalne wynagrodzenie</label>
              <input v-model.number="jobPost.salary_max" type="number" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-cyan-500 focus:border-cyan-500" />
            </div>
          </div>

        <!-- Rodzaj umowy -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Rodzaj umowy</label>
            <div class="mt-2 flex">
              <div v-for="contractType in contractTypes" :key="contractType" class="flex items-center">
                <input v-model="jobPost.contract_type" type="checkbox" :value="contractType" :id="contractType" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label :for="contractType" class="ml-1 mr-4 block text-sm text-gray-900">{{ contractType }}</label>
              </div>
            </div>
          </div>

          <!-- Work Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Wymiar pracy</label>
            <div class="mt-2 flex">
              <div v-for="workType in workTypes" :key="workType" class="flex items-center">
                <input v-model="jobPost.employment_type" type="checkbox" :value="workType" :id="workType" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label :for="workType" class="ml-1 mr-4 block text-sm text-gray-900">{{ workType }}</label>
              </div>
            </div>
          </div>


          <!-- Doświadczenie - jako radio buttons dla przykładu -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Doświadczenie</label>
            <div class="mt-2 flex">
                <div v-for="level in experienceLevels" :key="`exp-${level}`" class="flex items-center">
                <input v-model="jobPost.experience" type="checkbox" :value="level" :id="`exp-${level}`" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label :for="`exp-${level}`" class="ml-1 mr-4 block text-sm text-gray-900">{{ level }}</label>
                </div>
            </div>
            </div>

            <!-- Tryb pracy -->
           <div>
            <label class="block text-sm font-medium text-gray-700">Tryb pracy</label>
            <div class="mt-2 flex">
                <div v-for="mode in jobModes" :key="mode" class="flex items-center">
                <input 
                    type="radio" 
                    :checked="jobPost.job_mode === mode"
                    @change="() => handleJobModeChange(mode)" 
                    :id="`job-mode-${mode}`" 
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label :for="`job-mode-${mode}`" class="ml-1 mr-4 block text-sm text-gray-900">{{ mode }}</label>
                </div>
            </div>
            </div>
            
           <!-- Wymagane umiejętności -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Wymagane umiejętności</label>
            <div class="flex flex-wrap gap-2 mt-2">
            <span v-for="(skill, index) in jobPost.required_skills" :key="`req-skill-${index}`" class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full flex align-center">
                {{ skill }}
                <button @click.stop="removeSkill(index, 'required_skills')" class="bg-blue-200 text-blue-800 ml-2 rounded-full">&times;</button>
            </span>
            </div>
            <input type="text"
            placeholder="Dodaj wymaganą umiejętność i naciśnij przecinek"
            class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3"
            v-model="newRequiredSkill"
            @keydown="event => handleKeydown(event, 'required_skills', newRequiredSkill)" />
        </div>

        <!-- Dodatkowe umiejętności -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Dodatkowe umiejętności</label>
            <div class="flex flex-wrap gap-2 mt-2">
            <span v-for="(skill, index) in jobPost.skills" :key="`skill-${index}`" class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full flex align-center">
                {{ skill }}
                <button @click.stop="removeSkill(index, 'skills')" class="bg-green-200 text-green-800 ml-2 rounded-full">&times;</button>
            </span>
            </div>
            <input type="text"
            placeholder="Dodaj dodatkową umiejętność i naciśnij przecinek"
            class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3"
            v-model="newSkill"
            @keydown="event => handleKeydown(event, 'skills', newSkill)" />
        </div>
        <!-- Języki -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Języki</label>
            <div class="flex flex-wrap gap-2 mt-2">
                <span v-for="(language, index) in jobPost.foreign_language" :key="`lang-${index}`" class="bg-purple-100 text-purple-800 text-xs font-semibold px-2 py-1 rounded-full flex align-center">
                {{ language }}
                <button @click.stop="removeLanguage(index)" class="bg-purple-200 text-purple-800 ml-2 rounded-full">&times;</button>
                </span>
            </div>
            <input type="text"
                placeholder="Dodaj język i naciśnij przecinek"
                class="mt-1 block w-full border border-gray-300 rounded-md py-2 px-3"
                v-model="newLanguage"
                @keydown="event => handleKeydown(event, 'foreign_language', newLanguage)" />
        </div>
  
          <div class="flex justify-end mt-6">
            <button type="button" @click="closeModal" class="bg-gray-200 text-gray-700 py-2 px-4 rounded hover:bg-gray-300 mr-2">Anuluj</button>
            <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white py-2 px-4 rounded">Zapisz zmiany</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits, onMounted, watchEffect } from 'vue';
import axios from 'axios';
import toastr from 'toastr';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faTimes } from '@fortawesome/free-solid-svg-icons';

library.add(faTimes);

const experienceLevels = ['Junior', 'Mid', 'Senior', 'Manager']
const workTypes = ['Etat', 'Niepełny etat', 'Praktyki/Staż', 'Freelance']
const contractTypes = ['B2B', 'UoP', 'Zlecenie', 'Dzieło', 'Staż']
const jobModes = ['Stacjonarna','Zdalna','Hybryda']

const props = defineProps({
  jobPost: Object
});
const emit = defineEmits(['close', 'save']);
const jobPost = ref(props.jobPost);
watchEffect(() => {
  console.log(jobPost.value); 
});
const newRequiredSkill = ref('');
const newSkill = ref('');
const newLanguage = ref('');

function addSkill(type, newSkillValue) {
  if (newSkillValue.trim()) {
    jobPost.value[type] = jobPost.value[type] || []; 
    jobPost.value[type].push(newSkillValue.trim());
    if (type === 'required_skills') {
      newRequiredSkill.value = '';
    } else {
      newSkill.value = '';
    }
  }
}

function handleJobModeChange(selectedMode) {
  jobPost.value.job_mode = jobPost.value.job_mode === selectedMode ? '' : selectedMode;
}

function removeSkill(index, type) {
  jobPost.value[type].splice(index, 1);
}

function addLanguage(newLanguageValue) {
  if (newLanguage.value.trim()) {
    jobPost.value.foreign_language = jobPost.value.foreign_language || []; 
    jobPost.value.foreign_language.push(newLanguage.value.trim());
    newLanguage.value = ''; 
  }
}

function removeLanguage(index) {
  jobPost.value.foreign_language.splice(index, 1);
}

function handleKeydown(event, type, valueRef) {
  if (event.key === ',') {
    event.preventDefault();
    let newValue = valueRef.trim();
    if (!newValue) return;

    if (type === 'foreign_language') {
      addLanguage(newValue);
    } else {
      addSkill(type, newValue);
    }

    // Czyszczenie inputa
    if (type === 'required_skills') {
      newRequiredSkill.value = '';
    } else if (type === 'skills') {
      newSkill.value = '';
    } else if (type === 'foreign_language') {
      newLanguage.value = '';
    }
  }
}

const closeModal = () => {
  emit('close');
};

const handleSubmit = async () => {
  try {
    const response = await axios.put(`/panel/job-posts/${jobPost.value.id}`, jobPost.value);
    toastr.success('Ogłoszenie zostało zaktualizowane.');
    emit('save', response.data);
    closeModal();
  } catch (error) {
    toastr.error('Wystąpił błąd podczas aktualizacji ogłoszenia.');
  }
};

</script>
