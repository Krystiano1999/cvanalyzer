<template>
  <div class="w-[250px] h-[30px] max-w-xs mx-2">
    <Listbox v-model="selectedVoivodeship" @change="emitLocation">
      <div class="relative">
        <ListboxButton class="relative w-full py-0.5 pl-3 pr-10 text-left bg-white border border-gray-300 rounded-md shadow-sm focus:border-cyan-500 focus:outline-none focus:ring-1 focus:ring-cyan-500">
          <span class="block truncate">{{ selectedVoivodeship.name }}</span>
          <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
          </span>
        </ListboxButton>
        <transition leave-active-class="transition duration-100 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
          <ListboxOptions class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
            <ListboxOption v-for="voivde in voivodeship" :key="voivde.name" :value="voivde" v-slot="{ active, selected }" as="template">
              <li :class="[active ? 'text-white bg-cyan-600' : 'text-gray-900', 'cursor-default select-none relative py-2 pl-10 pr-4']">
                <span :class="[selected ? 'font-medium' : 'font-normal', 'block truncate']">{{ voivde.name }}</span>
                <span v-if="selected" class="absolute inset-y-0 left-0 flex items-center pl-3 text-cyan-600">
                  <CheckIcon class="h-5 w-5" aria-hidden="true" />
                </span>
              </li>
            </ListboxOption>
          </ListboxOptions>
        </transition>
      </div>
    </Listbox>
  </div>
</template>

<script setup>
import { ref, defineEmits, watch } from 'vue';
import {
  Listbox,
  ListboxLabel,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue'
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/vue/20/solid'

const voivodeship = [
  { name: 'Wybierz miasto' },
  { name: 'Warszawa' },
  { name: 'Kraków' },
  { name: 'Wrocław' },
  { name: 'Łódź' },
  { name: 'Poznań' },
  { name: 'Gdańsk' },
  { name: 'Bydgoszcz' },
  { name: 'Lublin' },
  { name: 'Katowice' },
  { name: 'Białystok' },
  { name: 'Gdynia' },
  { name: 'Częstochowa' },
  { name: 'Radom' },
  { name: 'Toruń' },
  { name: 'Kielce' },
  { name: 'Rzeszów' },
  { name: 'Gliwice' },
  { name: 'Zielona Góra' },
]
const selectedVoivodeship = ref(voivodeship[0]);
const emits = defineEmits(['location-selected']);

watch(selectedVoivodeship, (newValue) => {
  emits('location-selected', newValue.name);
}, { deep: true });
</script>
