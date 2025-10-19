<template>
    <div class="p-6 max-w-6xl mx-auto">
        <div class="mb-6">
            <Link href="/admin/vendors/applications" class="text-indigo-600 hover:text-indigo-700 mb-2 inline-block">
                ← Back to Applications
            </Link>
            <h1 class="text-3xl font-bold text-gray-900">Vendor Application Review</h1>
        </div>

        <!-- Status Banner -->
        <div v-if="vendor.onboarding_status === 'pending'" class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
            <p class="text-amber-800 font-medium">⏳ This application is pending review</p>
        </div>
        <div v-else-if="vendor.onboarding_status === 'approved'" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-green-800 font-medium">✓ This application has been approved</p>
        </div>
        <div v-else-if="vendor.onboarding_status === 'rejected'" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-red-800 font-medium">✗ This application has been rejected</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Shop Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold mb-4">Shop Information</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Shop Name</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.shop_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.shop_description }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Owner Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold mb-4">Owner Information</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Name</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.user?.name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.user?.email }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Phone</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.contact_phone }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Business Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold mb-4">Business Information</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Business Type</dt>
                        <dd class="text-sm text-gray-900 mt-1 capitalize">{{ vendor.business_type }}</dd>
                    </div>
                    <div v-if="vendor.business_name">
                        <dt class="text-sm font-medium text-gray-500">Business Name</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.business_name }}</dd>
                    </div>
                    <div v-if="vendor.business_registration_number">
                        <dt class="text-sm font-medium text-gray-500">Registration Number</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.business_registration_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ vendor.business_address }}<br>
                            {{ vendor.city }}, {{ vendor.state }} {{ vendor.postal_code }}
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Documents -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-bold mb-4">Documents</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">NID Number</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.nid_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">NID Images</dt>
                        <dd class="flex gap-2">
                            <a v-if="nidFrontUrl" :href="nidFrontUrl" target="_blank" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded text-sm hover:bg-indigo-200">
                                View Front
                            </a>
                            <a v-if="nidBackUrl" :href="nidBackUrl" target="_blank" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded text-sm hover:bg-indigo-200">
                                View Back
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Trade License Number</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.trade_license_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Trade License Expiry</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ new Date(vendor.trade_license_expiry).toLocaleDateString() }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 mb-2">Trade License Image</dt>
                        <dd>
                            <a v-if="tradeLicenseUrl" :href="tradeLicenseUrl" target="_blank" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded text-sm hover:bg-indigo-200">
                                View License
                            </a>
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Bank Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
                <h2 class="text-xl font-bold mb-4">Bank Account Details</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bank Name</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.bank_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Number</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.bank_account_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Account Name</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.bank_account_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Branch</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.bank_branch }}</dd>
                    </div>
                    <div v-if="vendor.bank_routing_number">
                        <dt class="text-sm font-medium text-gray-500">Routing Number</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ vendor.bank_routing_number }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div v-if="vendor.onboarding_status === 'pending'" class="mt-8 flex gap-4">
            <button
                @click="showApproveModal = true"
                class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition"
            >
                ✓ Approve Application
            </button>
            <button
                @click="showRejectModal = true"
                class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition"
            >
                ✗ Reject Application
            </button>
        </div>

        <!-- Approve Modal -->
        <div v-if="showApproveModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showApproveModal = false">
            <div @click.stop class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <h3 class="text-2xl font-bold mb-4">Approve Application?</h3>
                <p class="text-gray-600 mb-6">This will allow the vendor to complete their profile and start selling.</p>
                <div class="flex gap-3">
                    <button
                        @click="showApproveModal = false"
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-medium hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        @click="approve"
                        :disabled="approving"
                        class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 disabled:opacity-50"
                    >
                        {{ approving ? 'Approving...' : 'Approve' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div v-if="showRejectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="showRejectModal = false">
            <div @click.stop class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <h3 class="text-2xl font-bold mb-4">Reject Application</h3>
                <p class="text-gray-600 mb-4">Please provide a reason for rejection:</p>
                <textarea
                    v-model="rejectionReason"
                    rows="4"
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 mb-4"
                    placeholder="Explain why this application is being rejected..."
                ></textarea>
                <span v-if="rejectForm.errors.reason" class="text-red-500 text-sm mb-2 block">{{ rejectForm.errors.reason }}</span>
                <div class="flex gap-3">
                    <button
                        @click="showRejectModal = false"
                        class="flex-1 px-6 py-3 border border-gray-300 rounded-lg font-medium hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button
                        @click="reject"
                        :disabled="rejectForm.processing || !rejectionReason"
                        class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 disabled:opacity-50"
                    >
                        {{ rejectForm.processing ? 'Rejecting...' : 'Reject' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router, useForm } from '@inertiajs/vue3'

const props = defineProps({
    vendor: Object,
    nidFrontUrl: String,
    nidBackUrl: String,
    tradeLicenseUrl: String,
})

const showApproveModal = ref(false)
const showRejectModal = ref(false)
const approving = ref(false)
const rejectionReason = ref('')

const rejectForm = useForm({
    reason: '',
})

const approve = () => {
    approving.value = true
    router.post(`/admin/vendors/applications/${props.vendor.id}/approve`, {}, {
        onFinish: () => {
            approving.value = false
            showApproveModal.value = false
        }
    })
}

const reject = () => {
    rejectForm.reason = rejectionReason.value
    rejectForm.post(`/admin/vendors/applications/${props.vendor.id}/reject`, {
        onSuccess: () => {
            showRejectModal.value = false
        }
    })
}
</script>