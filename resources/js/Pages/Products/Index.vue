<template>
    <MainLayout>
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Search Results for "{{ query }}"</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="product in products.data" :key="product.id" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="aspect-w-16 aspect-h-9">
                    <img :src="product.image_url" alt="" class="w-full h-full object-center object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ product.title }}</h3>
                    <p class="text-sm text-gray-500">{{ product.description }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-lg font-bold text-gray-900">${{ product.price }}</p>
                        <AddToCart :product-id="product.id" />
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import AddToCart from '@/Components/AddToCart.vue';

defineProps({
    products: Object,
    query: String,
});
</script>
