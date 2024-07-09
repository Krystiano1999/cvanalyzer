<template>
    <HeaderComponent/>
    <div class="flex min-h-screen">
        <div class="flex-1 flex items-center justify-center bg-cover bg-center" style="background-image:  url('/img/bg.jpg');">
            <blockquote class="text-white text-3xl font-semibold p-10 bg-black bg-opacity-80 rounded">
            „Praca to klucz do bogactwa”
            </blockquote>
        </div>

        <div class="flex-1 flex items-center justify-center bg-gray-800">
            <div class="w-full max-w-md bg-white p-10 rounded">  
                <Logo class="text-6xl  mb-4"/>
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import HeaderComponent from '@/Components/app/HeaderComponent.vue';
import Logo from '@/Components/app/Logo.vue';
</script>

