<template>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">My Orders</h1>

        <div v-if="orders.data.length === 0" class="text-center py-12">
            <p class="text-gray-500">You have no orders.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="order in orders.data" :key="order.id" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <p class="font-bold">Order #{{ order.id }}</p>
                        <p class="text-sm text-gray-500">Placed on {{ new Date(order.created_at).toLocaleDateString() }}</p>
                    </div>
                    <p class="font-bold">{{ order.total }}</p>
                </div>
                <div class="border-t pt-4">
                    <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between py-2">
                        <div>
                            <p class="font-medium">{{ item.product.title }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ item.quantity }}</p>
                        </div>
                        <p>{{ item.price * item.quantity }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    orders: Object,
});
</script>
