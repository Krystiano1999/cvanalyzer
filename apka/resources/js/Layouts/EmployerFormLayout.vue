<template>
    <HeaderPanelComponent/>
    <div class="flex min-h-screen">
        <div class="flex-1 flex items-center justify-center bg-gray-800">
            <div class="w-full max-w-md bg-white p-10 rounded">  
                <LogoPanel class="text-5xl  mb-4"/>
                <slot />
            </div>
        </div>

        <div class="flex-1 flex items-center justify-center bg-cover bg-center" style="background-image:  url('/img/bg.jpg');">
            <blockquote class="text-white text-3xl font-semibold p-10 bg-black bg-opacity-80 rounded max-w-xl text-center">
            Zarejestruj swoją firmę aby móc dodawać ogłoszenia i korzystać z zautomatyzowanego systemu do analizy CV
            </blockquote>
        </div>
    </div>
</template>

<script setup>
import HeaderPanelComponent from '@/Components/app/HeaderPanelComponent.vue';
import LogoPanel from '@/Components/app/LogoPanel.vue';
</script>

