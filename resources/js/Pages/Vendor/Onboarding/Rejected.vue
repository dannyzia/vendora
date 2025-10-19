<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-12">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Main Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 p-8 text-center">
                <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Application Not Approved</h1>
                <p class="text-lg text-gray-600 mb-6">We're unable to approve your vendor application at this time.</p>

                <div class="max-w-2xl mx-auto mb-8 p-6 bg-red-50 rounded-xl border border-red-200 text-left">
                    <p class="text-sm font-medium text-red-900 mb-3">
                        <strong>Reason for Rejection:</strong>
                    </p>
                    <p class="text-sm text-red-800">
                        {{ reason || 'No specific reason provided.' }}
                    </p>
                </div>

                <div class="max-w-md mx-auto mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                    <p class="text-sm text-blue-900 mb-4">
                        <strong>What you can do:</strong>
                    </p>
                    <ul class="text-sm text-blue-800 text-left space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Review the rejection reason carefully
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Update your information and correct any issues
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ensure all documents are clear and valid
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Resubmit your application with corrections
                        </li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <Link href="/vendor/onboarding/application" class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition">
                        Update & Resubmit Application
                    </Link>
                    
                    <div>
                        <Link href="/" class="text-sm text-gray-600 hover:text-gray-900">
                            Return to Homepage
                        </Link>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-amber-50 rounded-lg">
                    <p class="text-sm text-amber-800">
                        <strong>Need clarification?</strong> Contact our support team at <a href="mailto:support@vendora.com" class="underline">support@vendora.com</a> for assistance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: null
})

const props = defineProps({
    vendor: Object,
    reason: String,
})
</script>