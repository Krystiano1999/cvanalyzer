<template>
  <section class="flex flex-col h-full">
    <div class="flex-grow">
      <div class="flex">
        <div class="w-4/5 pr-4 p-6">
          <header>
            <h2 class="text-lg font-medium text-gray-900">Informacje o koncie</h2>
          </header>

            <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-6 pt-4">
            <div class="grid grid-cols-2 gap-4">
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

              <div>
                <InputLabel for="surname" value="Nazwisko" />
                <TextInput
                  id="surname"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.surname"
                  required
                />
                <InputError class="mt-2" :message="form.errors.surname" />
              </div>

              <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                  autocomplete="email"
                />
                <InputError class="mt-2" :message="form.errors.email" />
              </div>

              <div>
                <InputLabel for="phone" value="Nr. telefonu" />
                <TextInput
                  id="phone"
                  type="tel"
                  class="mt-1 block w-full"
                  v-model="form.phone"
                />
                <InputError class="mt-2" :message="form.errors.phone" />
              </div>

              <div>
                <InputLabel for="linkedin_link" value="Link LinkedIn" />
                <TextInput
                  id="linkedin_link"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.linkedin_link"
                />
                <InputError class="mt-2" :message="form.errors.linkedin_link" />
              </div>

              <div>
                <InputLabel for="github_link" value="Link GitHub" />
                <TextInput
                  id="github_link"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.github_link"
                />
                <InputError class="mt-2" :message="form.errors.github_link" />
              </div>
            </div>
            
            <div>
            <label for="description">Opis</label>
            <textarea
                ref="textareaRef"
                id="description"
                class="mt-1 block w-full rounded-lg border-2 border border-cyan-300 px-4 py-2 focus:outline-none focus:border-cyan-500 focus:ring focus:ring-cyan-200 focus:ring-opacity-50 overflow-hidden resize-none max-h-56 h-28 overflow-y-auto"
                v-model="form.description"
                @input="adjustTextareaHeight"
                placeholder="Short bio here..."
                ></textarea>
            </div>
            <div class="mt-px">
                <PrimaryButton :disabled="form.processing">Zapisz</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Zapisano.</p>
                </Transition>
            </div>
          </form>
        </div>

        <div class="w-1/5 bg-cyan-600 p-4 space-y-4">
          <ProfileImageUploader />
           
          <CvDropzone />
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ProfileImageUploader from '@/Components/app/ProfileImageUploader.vue';
import CvDropzone from '@/Components/app/CvDropzone.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const { props } = usePage();
const user = usePage().props.auth.user;

const form = useForm({
  name: user.name || '',
  surname: user.surname || '',
  email: user.email || '',
  phone: user.phone || '',
  linkedin_link: user.linkedin_link || '',
  github_link: user.github_link || '',
  description: user.description || '',
});


</script>
