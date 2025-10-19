<template>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Checkout</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                    <form @submit.prevent="submit">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input v-model="form.name" type="text" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input v-model="form.email" type="email" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input v-model="form.phone" type="tel" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <input v-model="form.address" type="text" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input v-model="form.city" type="text" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                                    <input v-model="form.state" type="text" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                    <input v-model="form.postal_code" type="text" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    <div class="space-y-2">
                        <div v-for="item in cartItems" :key="item.product.id" class="flex justify-between">
                            <span>{{ item.product.title }} x {{ item.quantity }}</span>
                            <span>{{ item.product.price * item.quantity }}</span>
                        </div>
                    </div>
                    <div class="border-t my-4"></div>
                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span>{{ total }}</span>
                    </div>
                    <div class="mt-6">
                        <button @click="submit" :disabled="form.processing" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                            {{ form.processing ? 'Placing Order...' : 'Place Order' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    cartItems: Array,
});

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    postal_code: '',
});

const total = computed(() => {
    return props.cartItems.reduce((acc, item) => acc + item.product.price * item.quantity, 0);
});

const submit = () => {
    form.post(route('checkout.store'));
};
</script>
