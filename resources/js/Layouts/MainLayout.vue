<template>
    <div>
        <header class="bg-white shadow-md">
            <div class="container mx-auto px-4 py-4 flex justify-between items-center">
                <div>
                    <Link href="/" class="text-2xl font-bold text-gray-800">Vendora</Link>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <Link href="/" class="text-gray-600 hover:text-gray-800">Home</Link>
                    <Link href="/products" class="text-gray-600 hover:text-gray-800">Products</Link>
                    <Link href="/about" class="text-gray-600 hover:text-gray-800">About</Link>
                    <Link href="/contact" class="text-gray-600 hover:text-gray-800">Contact</Link>
                </nav>
                <div class="flex items-center space-x-4">
                    <Link href="/cart" class="relative">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span v-if="$page.props.cart.count > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $page.props.cart.count }}</span>
                    </Link>
                    <Link v-if="!$page.props.auth.user" href="/login" class="text-gray-600 hover:text-gray-800">Login</Link>
                    <Link v-if="!$page.props.auth.user" href="/register" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Register</Link>
                    <div v-if="$page.props.auth.user" class="relative">
                        <button @click="showDropdown = !showDropdown" class="flex items-center text-gray-600 hover:text-gray-800">
                            {{ $page.props.auth.user.name }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div v-if="showDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                            <Link :href="route($page.props.auth.user.role + '.dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</Link>
                            <Link @click.prevent="logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</Link>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="container mx-auto px-4 py-8">
            <slot />
        </main>

        <footer class="bg-gray-800 text-white py-8">
            <div class="container mx-auto px-4 text-center">
                <p>&copy; {{ new Date().getFullYear() }} Vendora. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>
