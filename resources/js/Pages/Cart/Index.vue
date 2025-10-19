<template>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Shopping Cart</h1>

        <div v-if="cartItems.length === 0" class="text-center py-12">
            <p class="text-gray-500">Your cart is empty.</p>
        </div>

        <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="item in cartItems" :key="item.product.id">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ item.product.title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ item.product.price }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" v-model="item.quantity" @change="updateQuantity(item.product.id, item.quantity)" class="w-20 px-2 py-1 rounded-lg border border-gray-300" />
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ item.product.price * item.quantity }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="removeItem(item.product.id)" class="text-red-600 hover:text-red-900 font-medium text-sm">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="p-6 flex justify-end">
                <Link :href="route('checkout.index')" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Checkout</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3';

defineProps({
    cartItems: Array,
});

const updateQuantity = (productId, quantity) => {
    router.put(route('cart.update', productId), { quantity });
};

const removeItem = (productId) => {
    router.delete(route('cart.destroy', productId));
};
</script>
