<template>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Add New Product</h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form @submit.prevent="submit">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Title *</label>
                        <input v-model="form.title" type="text" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea v-model="form.description" required rows="6" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        <span v-if="form.errors.description" class="text-red-500 text-sm">{{ form.errors.description }}</span>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                            <input v-model="form.price" type="number" step="0.01" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            <span v-if="form.errors.price" class="text-red-500 text-sm">{{ form.errors.price }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                            <input v-model="form.stock" type="number" required class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            <span v-if="form.errors.stock" class="text-red-500 text-sm">{{ form.errors.stock }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t flex justify-end gap-4">
                    <Link :href="route('vendor.products.index')" class="px-6 py-3 text-gray-600 rounded-lg hover:bg-gray-100">Cancel</Link>
                    <button type="submit" :disabled="form.processing" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Product' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    title: '',
    description: '',
    price: '',
    stock: '',
});

const submit = () => {
    form.post(route('vendor.products.store'));
};
</script>
