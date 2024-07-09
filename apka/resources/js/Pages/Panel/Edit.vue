<template>
  <Head title="Panel Rekrutera" />
  <PanelLayout>
    <div class="flex justify-center">
      <div class="flex w-full max-w-screen-2xl">
        <!-- Menu po lewej stronie -->
        <div class="w-1/4 p-6 bg-white rounded-lg shadow space-y-4 custom-box">
          <button @click="activeComponent = 'profile'" :class="{'bg-gray-100': activeComponent === 'profile'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
            <font-awesome-icon icon="building" class="text-cyan-500" />
            <span>Profil Firmy</span>
          </button>
          <button @click="activeComponent = 'jobPosts'" :class="{'bg-gray-100': activeComponent === 'jobPosts'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
            <font-awesome-icon icon="briefcase" class="text-cyan-500" />
            <span>Oferty Pracy</span>
          </button>
          <button @click="activeComponent = 'cvAnalysis'" :class="{'bg-gray-100': activeComponent === 'cvAnalysis'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
            <font-awesome-icon icon="file-alt" class="text-cyan-500" />
            <span>Analiza CV</span>
          </button>
          <!--<button @click="activeComponent = 'applications'" :class="{'bg-gray-100': activeComponent === 'applications'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
            <font-awesome-icon icon="file-alt" class="text-cyan-500" />
            <span>Aplikacje</span>
          </button>-->
          <button @click="activeComponent = 'settings'" :class="{'bg-gray-100': activeComponent === 'settings'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
            <font-awesome-icon icon="key" class="text-cyan-500" />
            <span>Zmiana hasła</span>
          </button>
          <form @submit.prevent="logout" class="w-full text-left">
            <button type="submit" class="flex items-center space-x-2 w-full hover:bg-gray-100 rounded-md p-2 transition">
              <font-awesome-icon icon="sign-out-alt" class="text-cyan-500" />
              <span>Wyloguj</span>
            </button>
          </form>
        </div>

        <!-- Zawartość po prawej stronie -->
        <div class="w-3/4 bg-white overflow-y-auto rounded-lg shadow m-3 custom-box-m-2">
          <div v-if="activeComponent === 'profile'" class="p-6">
            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
          </div>
          <div v-if="activeComponent === 'jobPosts'" class="p-6">
            <ManageJobPostsForm @changeComponent="handleChangeComponent" />
          </div>
          <div v-if="activeComponent === 'cvAnalysis'" class="p-6">
            <CVAnalysisForm />
          </div>
          <div v-if="activeComponent === 'applications'" class="p-6">
             <ViewApplicationsForm :job-id="jobId" />
          </div>
          <div v-if="activeComponent === 'settings'" class="p-6">
            <UpdatePasswordForm />
          </div>
        </div>
      </div>
    </div>
  </PanelLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import PanelLayout from '@/Layouts/PanelLayout.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import CVAnalysisForm from './Partials/CVAnalysisForm.vue';
import ManageJobPostsForm from './Partials/ManageJobPostsForm.vue';
import ViewApplicationsForm from './Partials/ViewApplicationsForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import { Head } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faBuilding, faBriefcase, faFileAlt, faKey, faSignOutAlt } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faBuilding, faBriefcase, faFileAlt, faKey, faSignOutAlt);

const activeComponent = ref('profile');
const jobId = ref(null);

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const handleChangeComponent = ({ component, jobId: id }) => {
  activeComponent.value = component;
  jobId.value = id;
};

const logout = () => {
    Inertia.post(route('logout'));
};
</script>

<style scoped>
.custom-box {
  max-height: calc(100vh - 65px);
  height: calc(100vh - 65px);
  overflow-y: auto;
}
.custom-box-m-2{
    max-height: calc(100vh - 89px);
    height: calc(100vh - 89px);
    overflow-y: auto;
}
</style>