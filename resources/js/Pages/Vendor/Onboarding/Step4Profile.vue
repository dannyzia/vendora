<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-bold">✓</div>
                            <div class="flex-1 h-1 bg-green-600 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-green-600 mt-2">Application</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-bold">✓</div>
                            <div class="flex-1 h-1 bg-green-600 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-green-600 mt-2">Documents</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-bold">✓</div>
                            <div class="flex-1 h-1 bg-green-600 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-green-600 mt-2">Verification</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-600 text-white font-bold">✓</div>
                        <p class="text-sm font-medium text-green-600 mt-2">Approval</p>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Shop Profile</h1>
                    <p class="text-gray-600">Your application is approved! Let's set up your shop.</p>
                </div>

                <form @submit.prevent="submit">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shop Logo</label>
                            <input type="file" @change="handleFileChange($event, 'shop_logo')" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            <span v-if="form.errors.shop_logo" class="text-red-500 text-sm">{{ form.errors.shop_logo }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shop Banner</label>
                            <input type="file" @change="handleFileChange($event, 'shop_banner')" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            <span v-if="form.errors.shop_banner" class="text-red-500 text-sm">{{ form.errors.shop_banner }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Hours (Optional)</label>
                            <p class="text-xs text-gray-500 mb-2">Let customers know when you are open.</p>
                            <div v-for="(day, index) in form.business_hours" :key="index" class="grid grid-cols-3 gap-2 mb-2 items-center">
                                <span class="font-medium capitalize">{{ day.day }}</span>
                                <input type="time" v-model="day.open" class="px-2 py-1 rounded-lg border border-gray-300" />
                                <input type="time" v-model="day.close" class="px-2 py-1 rounded-lg border border-gray-300" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Complete Setup & Go to Dashboard' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

defineOptions({
    layout: null
})

const props = defineProps({
    vendor: Object,
})

const form = useForm({
    shop_logo: null,
    shop_banner: null,
    business_hours: [
        { day: 'saturday', open: '09:00', close: '18:00' },
        { day: 'sunday', open: '09:00', close: '18:00' },
        { day: 'monday', open: '09:00', close: '18:00' },
        { day: 'tuesday', open: '09:00', close: '18:00' },
        { day: 'wednesday', open: '09:00', close: '18:00' },
        { day: 'thursday', open: '09:00', close: '18:00' },
        { day: 'friday', open: null, close: null },
    ],
})

const handleFileChange = (event, field) => {
    const file = event.target.files[0]
    if (file) {
        form[field] = file
    }
}

const submit = () => {
    form.post('/vendor/onboarding/complete')
}
</script>
