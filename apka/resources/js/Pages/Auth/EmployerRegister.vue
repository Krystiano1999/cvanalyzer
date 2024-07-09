<template>
    <EmployerFormLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Imię" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

             <div class="mt-4">
                <InputLabel for="surname" value="Nazwisko" />

                <TextInput
                    id="surname"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.surname"
                    required
                    autofocus
                    autocomplete="surname"
                />

                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="E-mail" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="company" value="Nazwa firmy" />

                <TextInput
                    id="company"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.company"
                    required
                    autocomplete="company"
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
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Powtórz Hasło" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <div class="flex items-center justify-center mt-4">
                <PrimaryButton class="w-full" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Zarejestruj
                </PrimaryButton>
            </div>

            <div class="flex items-center justify-center mt-4">
                <Link
                    :href="route('panel.login')"
                    class="underline text-sm text-cyan-600 hover:text-cyan-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500"
                >
                    Masz już konto?
                </Link>
            </div>
        </form>
    </EmployerFormLayout>
</template>

<script setup>
import EmployerFormLayout from '@/Layouts/EmployerFormLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    surname: '',
    email: '',
    company: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register.employer'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>


