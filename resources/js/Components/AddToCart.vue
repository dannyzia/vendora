<template>
    <form @submit.prevent="submit">
        <input type="hidden" name="product_id" :value="productId">
        <input type="number" v-model="form.quantity" class="w-20 px-2 py-1 rounded-lg border border-gray-300 mr-4" min="1">
        <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">
            Add to Cart
        </button>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    productId: Number,
});

const form = useForm({
    product_id: props.productId,
    quantity: 1,
});

const submit = () => {
    form.post(route('cart.store'), {
        preserveScroll: true,
    });
};
</script>
