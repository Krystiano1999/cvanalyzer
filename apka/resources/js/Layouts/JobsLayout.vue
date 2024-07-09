<template>
    <div>
        <div class="min-h-screen bg-gray-200">
            <HeaderComponent  v-if="!user || user.user_type === 0" />        
            <HeaderPanelComponent v-else-if="user && user.user_type === 1"/>        

            <!-- Page Heading -->
            <header class="bg-white shadow border-b border-cyan-600" v-if="$slots.header">
                <div class="max-w-screen-2xl  mx-auto py-2 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>
            
            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import HeaderComponent from '@/Components/app/HeaderComponent.vue';
import HeaderPanelComponent from '@/Components/app/HeaderPanelComponent.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;

const showingNavigationDropdown = ref(false);
</script>