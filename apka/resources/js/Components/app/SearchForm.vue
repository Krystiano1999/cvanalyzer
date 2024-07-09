<template>
    <div class="w-[250px] h-[30px] flex items-center relative">
        <TextInput type="text"
                   class="block w-full pl-8 pr-2 py-0.5"
                   v-model="search"
                   @keyup.enter.prevent="onSearch"
                   placeholder="Szukaj: stanowisko, firma"/>
        <font-awesome-icon :icon="['fas', 'search']" class="absolute left-2 top-1/2 transform -translate-y-1/2 text-gray-400" @click="onSearch"/>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import TextInput from "@/Components/TextInput.vue";
import { defineEmits } from 'vue';

const search = ref('');
const emits = defineEmits(['search-updated']);

const onSearch = () => {
    emits('search-updated', search.value.trim());
};
</script>
