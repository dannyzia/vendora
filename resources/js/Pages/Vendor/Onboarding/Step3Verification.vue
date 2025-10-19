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
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-600 text-white font-bold">3</div>
                            <div class="flex-1 h-1 bg-indigo-600 mx-2"></div>
                        </div>
                        <p class="text-sm font-medium text-indigo-600 mt-2">Verification</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-300 text-gray-600 font-bold">4</div>
                        <p class="text-sm font-medium text-gray-500 mt-2">Approval</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/40 p-8">
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Phone Verification</h1>
                    <p class="text-gray-600">We'll send a verification code to <span class="font-medium">{{ phone }}</span></p>
                </div>

                <!-- Send OTP Form -->
                <div v-if="!otpSent" class="text-center">
                    <button
                        @click="sendOtp"
                        :disabled="sendingOtp"
                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
                    >
                        {{ sendingOtp ? 'Sending...' : 'Send Verification Code' }}
                    </button>
                </div>

                <!-- Verify OTP Form -->
                <div v-else>
                    <form @submit.prevent="verifyOtp" class="max-w-md mx-auto">
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2 text-center">Enter 6-Digit Code</label>
                            <input
                                v-model="otpCode"
                                type="text"
                                maxlength="6"
                                required
                                pattern="[0-9]{6}"
                                class="w-full px-6 py-4 rounded-lg border-2 border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center text-2xl tracking-widest font-bold"
                                placeholder="000000"
                            />
                            <span v-if="form.errors.otp" class="text-red-500 text-sm">{{ form.errors.otp }}</span>
                        </div>

                        <button
                            type="submit"
                            :disabled="form.processing || otpCode.length !== 6"
                            class="w-full px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Verifying...' : 'Verify Code' }}
                        </button>

                        <div class="mt-4 text-center">
                            <button
                                type="button"
                                @click="sendOtp"
                                :disabled="sendingOtp"
                                class="text-sm text-indigo-600 hover:text-indigo-700 font-medium"
                            >
                                {{ sendingOtp ? 'Sending...' : 'Resend Code' }}
                            </button>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <strong>Note:</strong> The OTP has been sent to your email. Check your inbox (and spam folder). The code expires in 10 minutes.
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Back Button -->
                <div class="mt-8 text-center">
                    <Link href="/vendor/onboarding/documents" class="text-gray-600 hover:text-gray-900">← Back to Documents</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm, router } from '@inertiajs/vue3'

defineOptions({
    layout: null
})

const props = defineProps({
    vendor: Object,
    phone: String,
})

const otpSent = ref(false)
const otpCode = ref('')
const sendingOtp = ref(false)

const form = useForm({
    otp: '',
})

const sendOtp = () => {
    sendingOtp.value = true
    router.post('/vendor/onboarding/send-otp', {}, {
        onSuccess: () => {
            otpSent.value = true
        },
        onFinish: () => {
            sendingOtp.value = false
        }
    })
}

const verifyOtp = () => {
    form.otp = otpCode.value
    form.post('/vendor/onboarding/verify-otp')
}
</script>