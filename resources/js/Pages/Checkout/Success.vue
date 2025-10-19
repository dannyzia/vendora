<template>
    <div class="p-6 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Order Placed Successfully!</h1>
        <p class="text-lg text-gray-600 mb-6">Your order number is <span class="font-medium">{{ order.id }}</span>.</p>
        <p class="text-gray-600">Thank you for your purchase.</p>
        <div class="mt-8">
            <Link :href="route('home')" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Continue Shopping</Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    order: Object,
});
</script>
