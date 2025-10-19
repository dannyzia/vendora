<template>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Vendor Applications</h1>
            <p class="text-gray-600">Review and manage vendor applications</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-6 mb-6">
            <div class="bg-amber-50 rounded-xl p-6 border border-amber-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-600">Pending</p>
                        <p class="text-3xl font-bold text-amber-900">{{ stats.pending }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Approved</p>
                        <p class="text-3xl font-bold text-green-900">{{ stats.approved }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600">Rejected</p>
                        <p class="text-3xl font-bold text-red-900">{{ stats.rejected }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-200 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex items-center gap-2">
                <Link
                    :href="route('admin.vendors.applications', { status: 'pending' })"
                    :class="[
                        'px-4 py-2 rounded-lg font-medium transition',
                        currentStatus === 'pending' 
                            ? 'bg-amber-100 text-amber-700' 
                            : 'text-gray-600 hover:bg-gray-100'
                    ]"
                >
                    Pending
                </Link>
                <Link
                    :href="route('admin.vendors.applications', { status: 'approved' })"
                    :class="[
                        'px-4 py-2 rounded-lg font-medium transition',
                        currentStatus === 'approved' 
                            ? 'bg-green-100 text-green-700' 
                            : 'text-gray-600 hover:bg-gray-100'
                    ]"
                >
                    Approved
                </Link>
                <Link
                    :href="route('admin.vendors.applications', { status: 'rejected' })"
                    :class="[
                        'px-4 py-2 rounded-lg font-medium transition',
                        currentStatus === 'rejected' 
                            ? 'bg-red-100 text-red-700' 
                            : 'text-gray-600 hover:bg-gray-100'
                    ]"
                >
                    Rejected
                </Link>
                <Link
                    :href="route('admin.vendors.applications', { status: 'all' })"
                    :class="[
                        'px-4 py-2 rounded-lg font-medium transition',
                        currentStatus === 'all' 
                            ? 'bg-indigo-100 text-indigo-700' 
                            : 'text-gray-600 hover:bg-gray-100'
                    ]"
                >
                    All
                </Link>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="app in applications.data" :key="app.id" class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ app.shop_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ app.user?.name }}</div>
                            <div class="text-sm text-gray-500">{{ app.user?.email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 capitalize">{{ app.business_type }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ app.city }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span :class="[
                                'px-2 py-1 text-xs font-medium rounded-full',
                                app.onboarding_status === 'pending' ? 'bg-amber-100 text-amber-700' : '',
                                app.onboarding_status === 'approved' ? 'bg-green-100 text-green-700' : '',
                                app.onboarding_status === 'rejected' ? 'bg-red-100 text-red-700' : '',
                            ]">
                                {{ app.onboarding_status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ new Date(app.updated_at).toLocaleDateString() }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <Link
                                :href="route('admin.vendors.show', app.id)"
                                class="text-indigo-600 hover:text-indigo-900 font-medium text-sm"
                            >
                                Review →
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Empty State -->
            <div v-if="applications.data.length === 0" class="text-center py-12">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-gray-500">No applications found</p>
            </div>

            <!-- Pagination -->
            <div v-if="applications.data.length > 0" class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ applications.from }} to {{ applications.to }} of {{ applications.total }} results
                    </div>
                    <div class="flex gap-2">
                        <Link
                            v-if="applications.prev_page_url"
                            :href="applications.prev_page_url"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Previous
                        </Link>
                        <Link
                            v-if="applications.next_page_url"
                            :href="applications.next_page_url"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Next
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    applications: Object,
    stats: Object,
    currentStatus: String,
})
</script>