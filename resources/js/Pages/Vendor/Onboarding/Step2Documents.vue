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
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-bold">2</div>
                            <div class="flex-1 h-1 bg-indigo-600 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-indigo-600 mt-2">Documents</p>
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
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload Documents</h1>
                    <p class="text-gray-600">Please upload clear images of your documents (max 5MB each).</p>
                </div>

                <form @submit.prevent="submit">
                    <!-- NID Upload -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">National ID (NID)</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">NID Number *</label>
                            <input
                                v-model="form.nid_number"
                                type="text"
                                required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your NID number"
                            />
                            <span v-if="form.errors.nid_number" class="text-red-500 text-sm">{{ form.errors.nid_number }}</span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NID Front Image *</label>
                                <input
                                    type="file"
                                    accept="image/*,application/pdf"
                                    required
                                    @change="handleFileChange($event, 'nid_front_image')"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG or PDF (max 2MB)</p>
                                <span v-if="form.errors.nid_front_image" class="text-red-500 text-sm">{{ form.errors.nid_front_image }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NID Back Image *</label>
                                <input
                                    type="file"
                                    accept="image/*,application/pdf"
                                    required
                                    @change="handleFileChange($event, 'nid_back_image')"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG or PDF (max 2MB)</p>
                                <span v-if="form.errors.nid_back_image" class="text-red-500 text-sm">{{ form.errors.nid_back_image }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Trade License -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Trade License</h2>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Trade License Number *</label>
                                <input
                                    v-model="form.trade_license_number"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="License number"
                                />
                                <span v-if="form.errors.trade_license_number" class="text-red-500 text-sm">{{ form.errors.trade_license_number }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                                <input
                                    v-model="form.trade_license_expiry"
                                    type="date"
                                    required
                                    :min="tomorrow"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <span v-if="form.errors.trade_license_expiry" class="text-red-500 text-sm">{{ form.errors.trade_license_expiry }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trade License Image *</label>
                            <input
                                type="file"
                                accept="image/*,application/pdf"
                                required
                                @change="handleFileChange($event, 'trade_license_image')"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            />
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG or PDF (max 2MB)</p>
                            <span v-if="form.errors.trade_license_image" class="text-red-500 text-sm">{{ form.errors.trade_license_image }}</span>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="space-y-6 mb-8">
                        <h2 class="text-xl font-bold text-gray-900 border-b pb-2">Bank Account Details</h2>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name *</label>
                                <input
                                    v-model="form.bank_name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="e.g., Islami Bank Bangladesh"
                                />
                                <span v-if="form.errors.bank_name" class="text-red-500 text-sm">{{ form.errors.bank_name }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                                <input
                                    v-model="form.bank_account_number"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Bank account number"
                                />
                                <span v-if="form.errors.bank_account_number" class="text-red-500 text-sm">{{ form.errors.bank_account_number }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Account Name *</label>
                                <input
                                    v-model="form.bank_account_name"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Name as per bank account"
                                />
                                <span v-if="form.errors.bank_account_name" class="text-red-500 text-sm">{{ form.errors.bank_account_name }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Branch Name *</label>
                                <input
                                    v-model="form.bank_branch"
                                    type="text"
                                    required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Branch name"
                                />
                                <span v-if="form.errors.bank_branch" class="text-red-500 text-sm">{{ form.errors.bank_branch }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Routing Number (Optional)</label>
                                <input
                                    v-model="form.bank_routing_number"
                                    type="text"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Routing number"
                                />
                                <span v-if="form.errors.bank_routing_number" class="text-red-500 text-sm">{{ form.errors.bank_routing_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t">
                        <Link href="/vendor/onboarding/application" class="text-gray-600 hover:text-gray-900">← Back</Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Uploading...' : 'Continue to Verification →' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({
    layout: null
})

const props = defineProps({
    vendor: Object,
})

const form = useForm({
    nid_number: '',
    nid_front_image: null,
    nid_back_image: null,
    trade_license_number: '',
    trade_license_image: null,
    trade_license_expiry: '',
    bank_name: '',
    bank_account_number: '',
    bank_account_name: '',
    bank_branch: '',
    bank_routing_number: '',
})

const tomorrow = computed(() => {
    const date = new Date()
    date.setDate(date.getDate() + 1)
    return date.toISOString().split('T')[0]
})

const handleFileChange = (event, field) => {
    const file = event.target.files[0]
    if (file) {
        form[field] = file
    }
}

const submit = () => {
    form.post('/vendor/onboarding/documents')
}
</script>
