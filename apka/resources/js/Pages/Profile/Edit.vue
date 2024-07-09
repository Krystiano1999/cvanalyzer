<template>
    <Head title="Profil" />
    <AuthenticatedLayout>
        <div class="flex justify-center py-12">
            <div class="flex w-full max-w-screen-xl">
                <!-- Menu po lewej stronie -->
                <div class="w-1/4 p-6 bg-white rounded-lg shadow space-y-4 max-h-80">
                    <button @click="activeComponent = 'profile'" :class="{'bg-gray-100': activeComponent === 'profile'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
                        <font-awesome-icon icon="user" class="text-cyan-500" />
                        <span>Profil</span>
                    </button>
                    <button @click="activeComponent = 'application'" :class="{'bg-gray-100': activeComponent === 'application'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
                        <font-awesome-icon icon="file-alt" class="text-cyan-500" />
                        <span>Twoje aplikacje</span>
                    </button>
                    <button @click="activeComponent = 'settings'" :class="{'bg-gray-100': activeComponent === 'settings'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
                        <font-awesome-icon icon="key" class="text-cyan-500" />
                        <span>Zmiana hasła</span>
                    </button>
                    <button @click="activeComponent = 'delete'" :class="{'bg-gray-100': activeComponent === 'delete'}" class="flex items-center space-x-2 w-full text-left hover:bg-gray-100 rounded-md p-2 transition">
                        <font-awesome-icon icon="trash" class="text-cyan-500" />
                        <span>Usuń Konto</span>
                    </button>
                    <form @submit.prevent="logout" class="w-full text-left">
                        <button type="submit" class="flex items-center space-x-2 w-full hover:bg-gray-100 rounded-md p-2 transition">
                            <font-awesome-icon icon="sign-out-alt" class="text-cyan-500" />
                            <span>Wyloguj</span>
                        </button>
                    </form>
                </div>

                <!-- Zawartość po prawej stronie -->
                <div class="w-3/4 bg-white rounded-lg shadow ml-6 max-h-[755px]	overflow-y-auto">
                    <div v-if="activeComponent === 'profile'" class="bg-white rounded-lg">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                    </div>
                    <div v-if="activeComponent === 'application'" class="bg-white rounded-lg">
                        <UserApplication />
                    </div>
                    <div v-if="activeComponent === 'settings'" class="bg-white rounded-lg">
                        <UpdatePasswordForm />
                    </div>
                    <div v-if="activeComponent === 'delete'" class="bg-white rounded-lg">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UserApplication from './Partials/UserApplication.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faUser, faFileAlt , faKey, faTrash, faSignOutAlt } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faUser, faFileAlt , faKey, faTrash, faSignOutAlt);

const activeComponent = ref('profile');

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const logout = () => {
    Inertia.post(route('logout'));
};
</script>
