<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-bold">1</div>
                            <div class="flex-1 h-1 bg-indigo-600 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-indigo-600 mt-2">Application</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold">2</div>
                            <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-gray-500 mt-2">Documents</p>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold">3</div>
                            <div class="flex-1 h-1 bg-gray-300 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-gray-500 mt-2">Verification</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold">4</div>
                        <p class="text-sm font-medium text-gray-500 mt-2">Approval</p>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 p-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Vendor Application</h1>
                    <p class="text-gray-600">Tell us about your business. All fields are required.</p>
                </div>

                <form @submit.prevent="submit">
                    <!-- Shop Information -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Shop Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shop Name *</label>
                            <input
                                v-model="form.shop_name"
                                type="text"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="e.g., Tech Galaxy BD"
                            />
                            <span v-if="errors.shop_name" class="text-red-500 text-sm">{{ errors.shop_name }}</span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Shop Description * (min. 50 characters)</label>
                            <textarea
                                v-model="form.shop_description"
                                required
                                rows="4"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Describe your business, products, and what makes you unique..."
                            ></textarea>
                            <p class="text-sm text-gray-500 mt-1">{{ form.shop_description?.length || 0 }} / 50 minimum</p>
                            <span v-if="errors.shop_description" class="text-red-500 text-sm">{{ errors.shop_description }}</span>
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Business Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Business Type *</label>
                            <select
                                v-model="form.business_type"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            >
                                <option value="">Select business type</option>
                                <option value="individual">Individual/Sole Proprietor</option>
                                <option value="company">Company/Corporation</option>
                                <option value="partnership">Partnership</option>
                            </select>
                            <span v-if="errors.business_type" class="text-red-500 text-sm">{{ errors.business_type }}</span>
                        </div>

                        <div v-if="form.business_type === 'company' || form.business_type === 'partnership'" class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Business Name *</label>
                                <input
                                    v-model="form.business_name"
                                    type="text"
                                    :required="form.business_type === 'company' || form.business_type === 'partnership'"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Registered business name"
                                />
                                <span v-if="errors.business_name" class="text-red-500 text-sm">{{ errors.business_name }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Registration Number *</label>
                                <input
                                    v-model="form.business_registration_number"
                                    type="text"
                                    :required="form.business_type === 'company' || form.business_type === 'partnership'"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Company/Partnership reg. number"
                                />
                                <span v-if="errors.business_registration_number" class="text-red-500 text-sm">{{ errors.business_registration_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Business Address -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Business Address</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                            <input
                                v-model="form.business_address"
                                type="text"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="House/Building, Road, Area"
                            />
                            <span v-if="errors.business_address" class="text-red-500 text-sm">{{ errors.business_address }}</span>
                        </div>

                        <div class="grid md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input
                                    v-model="form.city"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., Dhaka"
                                />
                                <span v-if="errors.city" class="text-red-500 text-sm">{{ errors.city }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State/Division *</label>
                                <input
                                    v-model="form.state"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., Dhaka Division"
                                />
                                <span v-if="errors.state" class="text-red-500 text-sm">{{ errors.state }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code *</label>
                                <input
                                    v-model="form.postal_code"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., 1200"
                                />
                                <span v-if="errors.postal_code" class="text-red-500 text-sm">{{ errors.postal_code }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Contact Information</h2>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Person Name *</label>
                            <input
                                v-model="form.contact_person"
                                type="text"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Full name of the person we can contact"
                            />
                            <span v-if="errors.contact_person" class="text-red-500 text-sm">{{ errors.contact_person }}</span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone *</label>
                                <input
                                    v-model="form.contact_phone"
                                    type="tel"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="+880 1XXX-XXXXXX"
                                />
                                <span v-if="errors.contact_phone" class="text-red-500 text-sm">{{ errors.contact_phone }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email *</label>
                                <input
                                    v-model="form.contact_email"
                                    type="email"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="your@email.com"
                                />
                                <span v-if="errors.contact_email" class="text-red-500 text-sm">{{ errors.contact_email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <Link href="/" class="text-gray-600 hover:text-gray-900">Cancel</Link>
                        <button
                            type="submit"
                            :disabled="processing"
                            class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
                        >
                            {{ processing ? 'Saving...' : 'Continue to Documents →' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    vendor: Object,
    errors: Object,
})

const form = useForm({
    shop_name: props.vendor?.shop_name || '',
    shop_description: props.vendor?.shop_description || '',
    business_type: props.vendor?.business_type || '',
    business_name: props.vendor?.business_name || '',
    business_registration_number: props.vendor?.business_registration_number || '',
    business_address: props.vendor?.business_address || '',
    city: props.vendor?.city || '',
    state: props.vendor?.state || '',
    postal_code: props.vendor?.postal_code || '',
    contact_person: props.vendor?.contact_person || '',
    contact_phone: props.vendor?.contact_phone || '',
    contact_email: props.vendor?.contact_email || '',
})

const processing = reactive({ value: false })

const submit = () => {
    processing.value = true
    form.post('/vendor/onboarding/application', {
        onFinish: () => {
            processing.value = false
        },
    })
}
</script>