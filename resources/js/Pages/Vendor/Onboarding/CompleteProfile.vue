<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Success Banner -->
            <div class="mb-8 p-6 bg-green-50 rounded-xl border border-green-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-green-900">Congratulations! 🎉</h2>
                        <p class="text-green-800">Your vendor application has been approved. Complete your shop profile to start selling!</p>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 p-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Shop Profile</h1>
                    <p class="text-gray-600">Add the finishing touches to your shop.</p>
                </div>

                <form @submit.prevent="submit">
                    <!-- Shop Logo -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-6">Shop Branding</h2>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shop Logo (Optional)</label>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange($event, 'shop_logo')"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <p class="text-xs text-gray-500 mt-1">Square image recommended (max 2MB)</p>
                                <span v-if="form.errors.shop_logo" class="text-red-500 text-sm">{{ form.errors.shop_logo }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shop Banner (Optional)</label>
                                <input
                                    type="file"
                                    accept="image/*"
                                    @change="handleFileChange($event, 'shop_banner')"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <p class="text-xs text-gray-500 mt-1">Landscape image 1920x500px (max 5MB)</p>
                                <span v-if="form.errors.shop_banner" class="text-red-500 text-sm">{{ form.errors.shop_banner }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2 mb-6">Business Hours (Optional)</h2>
                        
                        <div class="space-y-4">
                            <div v-for="day in days" :key="day.value" class="flex items-center gap-4">
                                <div class="w-32">
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            v-model="day.enabled"
                                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                        />
                                        <span class="ml-2 text-sm font-medium text-gray-700">{{ day.label }}</span>
                                    </label>
                                </div>
                                <div v-if="day.enabled" class="flex items-center gap-2 flex-1">
                                    <input
                                        v-model="day.open"
                                        type="time"
                                        class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                    <span class="text-gray-500">to</span>
                                    <input
                                        v-model="day.close"
                                        type="time"
                                        class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                </div>
                                <div v-else class="flex-1 text-sm text-gray-500">
                                    Closed
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <Link href="/" class="text-gray-600 hover:text-gray-900">Skip for Now</Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-medium hover:from-green-700 hover:to-emerald-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Complete Setup & Start Selling! 🚀' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({
    layout: null
})

const props = defineProps({
    vendor: Object,
})

const form = useForm({
    shop_logo: null,
    shop_banner: null,
    business_hours: null,
})

const days = reactive([
    { value: 'monday', label: 'Monday', enabled: true, open: '09:00', close: '18:00' },
    { value: 'tuesday', label: 'Tuesday', enabled: true, open: '09:00', close: '18:00' },
    { value: 'wednesday', label: 'Wednesday', enabled: true, open: '09:00', close: '18:00' },
    { value: 'thursday', label: 'Thursday', enabled: true, open: '09:00', close: '18:00' },
    { value: 'friday', label: 'Friday', enabled: true, open: '09:00', close: '18:00' },
    { value: 'saturday', label: 'Saturday', enabled: false, open: '09:00', close: '18:00' },
    { value: 'sunday', label: 'Sunday', enabled: false, open: '09:00', close: '18:00' },
])

const handleFileChange = (event, field) => {
    const file = event.target.files[0]
    if (file) {
        form[field] = file
    }
}

const submit = () => {
    // Convert business hours to JSON
    const businessHours = {}
    days.forEach(day => {
        if (day.enabled) {
            businessHours[day.value] = {
                open: day.open,
                close: day.close
            }
        }
    })
    
    form.business_hours = JSON.stringify(businessHours)
    form.post('/vendor/onboarding/complete')
}
</script>