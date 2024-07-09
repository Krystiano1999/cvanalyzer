<template>
    <FormLayout>
        <Head title="Zaloguj" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="E-mail" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Hasło" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Zapamiętaj mnie</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4">
                <PrimaryButton class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Zaloguj się
                </PrimaryButton>
            </div>

            <div class="flex mt-3 items-center justify-center">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-cyan-600 hover:text-cyan-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                >
                    Nie pamiętasz hasła?
                </Link>
            </div>

            <div class="flex mt-3 items-center justify-center">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ms-4 font-semibold text-cyan-600 hover:text-cyan-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                    >Nie masz konta? Zarejestruj się</Link
                >
            </div>
        </form>
    </FormLayout>           
</template>


<script setup>
import FormLayout from '@/Layouts/FormLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>