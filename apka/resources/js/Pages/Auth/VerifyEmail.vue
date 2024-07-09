<template>
    <FormLayout>
        <Head title="Email Verification" />

        <div class="mb-4 text-sm text-gray-600">
            Dziękujemy za rejestrację! Zanim zaczniesz aplikować, zweryfikuj Swoje konto, link weryfikacyjny został wysłany na adres mail podany podczas rejestracji.
        </div>

        <div class="mb-4 font-medium text-sm text-green-600" v-if="verificationLinkSent">
            Link weryfikacyjny został wysłany na adres email podany podczas rejestracji.
        </div>

        <form @submit.prevent="submit">
            <div class="flex items-center justify-center mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Wyślij Link Weryfikacyjny ponownie
                </PrimaryButton>
            </div>
        </form>
        
        <div class="mt-4 flex items-center justify-center">
            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >Wyloguj</Link
            >
        </div>
    </FormLayout>
</template>

<script setup>
import { computed } from 'vue';
import FormLayout from '@/Layouts/FormLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>