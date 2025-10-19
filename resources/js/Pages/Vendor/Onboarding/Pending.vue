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
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-amber-500 text-white font-bold">
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-amber-600 mt-2">Approval</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 p-8 text-center">
                <div class="w-24 h-24 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-amber-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-4">Application Submitted!</h1>
                <p class="text-lg text-gray-600 mb-6">Your vendor application is under review.</p>

                <div class="max-w-md mx-auto mb-8 p-6 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-sm text-amber-900 mb-4">
                        <strong>What's next?</strong>
                    </p>
                    <ul class="text-sm text-amber-800 text-left space-y-2">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Our team will review your application within 24-48 hours
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            We'll verify your documents and business information
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            You'll receive an email notification once approved
                        </li>
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-amber-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            After approval, complete your shop profile to start selling
                        </li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <Link href="/" class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition">
                        Return to Homepage
                    </Link>
                    
                    <div>
                        <button
                            @click="refresh"
                            class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
                        >
                            🔄 Refresh Status
                        </button>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Need help?</strong> Contact our support team at <a href="mailto:support@vendora.com" class="underline">support@vendora.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'

defineOptions({
    layout: null
})

const props = defineProps({
    vendor: Object,
})

const refresh = () => {
    router.reload()
}
</script>