<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Top Header -->
        <header class="bg-gradient-to-r from-teal-600 to-cyan-600 text-gray-900 sticky top-0 z-50 shadow-lg">
            <div class="max-w-[1920px] mx-auto px-4">
                <div class="flex items-center gap-6 h-16">
                    <!-- Logo -->
                    <Link href="/" class="font-bold text-xl whitespace-nowrap">
                        Vendora BD
                    </Link>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-2xl">
                        <div class="relative">
                            <input
                                type="text"
                                placeholder="Search products..."
                                class="w-full px-4 py-2.5 pl-10 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white/50"
                            />
                            <svg class="w-5 h-5 text-gray-600 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Right Menu -->
                    <div class="flex items-center gap-6 text-sm">
                        <button class="hover:text-cyan-100 transition whitespace-nowrap flex items-center gap-1">
                            <span>📍</span> Dhaka
                        </button>
                        <button class="hover:text-cyan-100 transition whitespace-nowrap flex items-center gap-1">
                            <span>🌐</span> EN
                        </button>
                        <button class="hover:text-cyan-100 transition whitespace-nowrap">
                            💬
                        </button>
                        <button class="hover:text-cyan-100 transition whitespace-nowrap">
                            📧
                        </button>
                        <Link href="/login" class="hover:text-cyan-100 transition whitespace-nowrap flex items-center gap-1">
                            <span>👤</span> Login
                        </Link>

                        <!-- App Download Icons -->
                        <div class="flex items-center gap-2 ml-2">
                            <a href="#" class="hover:opacity-80 transition">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                                </svg>
                            </a>
                            <a href="#" class="hover:opacity-80 transition">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.53,12.9 20.18,13.18L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z"/>
                                </svg>
                            </a>
                            <a href="#" class="hover:opacity-80 transition">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Secondary Navigation -->
        <nav class="bg-white border-b border-gray-200 sticky top-16 z-40">
            <div class="max-w-[1920px] mx-auto px-4">
                <div class="flex items-center gap-8 h-12 text-sm">
                    <Link href="/products" class="text-gray-700 hover:text-teal-600 transition">Product By Category</Link>
                    <Link href="/sellers" class="text-gray-700 hover:text-teal-600 transition">Seller By Category</Link>
                    <Link href="/community" class="text-gray-700 hover:text-teal-600 transition">Vendora Community</Link>
                    <Link href="/forum" class="text-gray-700 hover:text-teal-600 transition">Vendora Forum</Link>
                    <Link href="/sell" class="text-blue-600 hover:text-blue-700 transition font-medium">Sell On Vendora</Link>
                    <Link href="/faq" class="text-gray-700 hover:text-teal-600 transition">FAQ</Link>
                    <Link href="/terms" class="text-gray-700 hover:text-teal-600 transition">Terms</Link>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex max-w-[1920px] mx-auto">
            <!-- Featured Sellers Sidebar -->
            <aside class="w-64 bg-white border-r border-gray-200 sticky top-28 h-[calc(100vh-7rem)] overflow-y-auto flex-shrink-0">
                <div class="p-4">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl">⭐</span>
                        <h2 class="text-lg font-bold text-gray-900">Featured Sellers</h2>
                    </div>

                    <div class="space-y-3">
                        <!-- Tech Galaxy -->
                        <div class="bg-gradient-to-br from-pink-500 to-red-500 rounded-xl p-4 text-gray-900 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">
                                    T
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-sm">Tech Galaxy</h3>
                                    <div class="flex gap-0.5">
                                        <span class="text-xs">⭐⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs opacity-90 mb-2">Electronics</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium">1.2K+ Items</span>
                                <span class="text-lg">✓</span>
                            </div>
                        </div>

                        <!-- Fashion Hub -->
                        <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl p-4 text-gray-900 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">
                                    F
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-sm">Fashion Hub</h3>
                                    <div class="flex gap-0.5">
                                        <span class="text-xs">⭐⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs opacity-90 mb-2">Fashion</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium">850+ Items</span>
                                <span class="text-lg">✓</span>
                            </div>
                        </div>

                        <!-- Home Decor -->
                        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-xl p-4 text-gray-900 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">
                                    H
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-sm">Home Decor</h3>
                                    <div class="flex gap-0.5">
                                        <span class="text-xs">⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs opacity-90 mb-2">Home</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium">620+ Items</span>
                                <span class="text-lg">✓</span>
                            </div>
                        </div>

                        <!-- Green Garden -->
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-4 text-gray-900 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">
                                    G
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-sm">Green Garden</h3>
                                    <div class="flex gap-0.5">
                                        <span class="text-xs">⭐⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs opacity-90 mb-2">Plants</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium">340+ Items</span>
                                <span class="text-lg">✓</span>
                            </div>
                        </div>

                        <!-- Sports Zone -->
                        <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-4 text-gray-900 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">
                                    S
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-sm">Sports Zone</h3>
                                    <div class="flex gap-0.5">
                                        <span class="text-xs">⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs opacity-90 mb-2">Sports</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium">580+ Items</span>
                                <span class="text-lg">✓</span>
                            </div>
                        </div>

                        <!-- Book Haven -->
                        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-4 text-gray-900 cursor-pointer hover:shadow-lg transition-all transform hover:scale-105">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center font-bold text-lg">
                                    B
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-sm">Book Haven</h3>
                                    <div class="flex gap-0.5">
                                        <span class="text-xs">⭐⭐⭐⭐⭐</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs opacity-90 mb-2">Books</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium">920+ Items</span>
                                <span class="text-lg">✓</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Product Area -->
            <main class="flex-1 p-6">
                <!-- Featured Products -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">⭐ Featured Products</h2>
                        <Link href="/products/featured" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                            View All →
                        </Link>
                    </div>

                    <!-- Row 1 -->
                    <div class="flex gap-4 mb-4">
                        <div v-for="i in 5" :key="'featured-1-' + i" class="w-56 flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all cursor-pointer group">
                            <div class="relative aspect-square bg-gray-100">
                                <img src="https://placehold.co/200x200/e2e8f0/64748b?text=Product" alt="Product" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <div class="absolute top-2 right-2 bg-red-500 text-gray-900 text-xs px-2 py-1 rounded-full font-bold">
                                    -20%
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">Premium Wireless Headphones</h3>
                                <div class="flex items-center gap-1 mb-2">
                                    <span class="text-xs text-yellow-500">⭐⭐⭐⭐⭐</span>
                                    <span class="text-xs text-gray-500">(125)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-bold text-teal-600">৳2,499</p>
                                        <p class="text-xs text-gray-600 line-through">৳3,099</p>
                                    </div>
                                    <button class="p-2 bg-teal-50 hover:bg-teal-100 rounded-lg transition">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="flex gap-4">
    <div v-for="i in 5" :key="'featured-2-' + i" class="w-56 flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all cursor-pointer group">
                            <div class="relative aspect-square bg-gray-100">
                                <img src="https://placehold.co/200x200/e2e8f0/64748b?text=Product" alt="Product" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                <div class="absolute top-2 right-2 bg-red-500 text-gray-900 text-xs px-2 py-1 rounded-full font-bold">
                                    -15%
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">Smart Watch Series 7</h3>
                                <div class="flex items-center gap-1 mb-2">
                                    <span class="text-xs text-yellow-500">⭐⭐⭐⭐⭐</span>
                                    <span class="text-xs text-gray-500">(89)</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-bold text-teal-600">৳4,250</p>
                                        <p class="text-xs text-gray-600 line-through">৳5,000</p>
                                    </div>
                                    <button class="p-2 bg-teal-50 hover:bg-teal-100 rounded-lg transition">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Deal of the Day -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">🔥 Deal of the Day</h2>
                        <Link href="/products/deals" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                            View All →
                        </Link>
                    </div>

                    <div>
                        <div class="flex gap-4">
                            <div v-for="i in 5" :key="'deal-' + i" class="w-56 bg-gradient-to-br from-orange-50 to-red-50 rounded-lg shadow-sm border-2 border-orange-200 overflow-hidden hover:shadow-md transition-all cursor-pointer group">
                                <div class="relative aspect-square bg-white">
                                    <img src="https://placehold.co/200x200/fff7ed/f97316?text=DEAL" alt="Deal" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    <div class="absolute top-2 left-2 bg-gradient-to-r from-orange-500 to-red-500 text-gray-900 text-xs px-3 py-1.5 rounded-full font-bold shadow-lg">
                                        50% OFF
                                    </div>
                                    <div class="absolute bottom-2 left-2 right-2 bg-black/70 text-gray-900 text-xs px-2 py-1 rounded text-center">
                                        ⏰ Ends in 5h 23m
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">Limited Edition Gaming Mouse</h3>
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="text-xs text-yellow-500">⭐⭐⭐⭐⭐</span>
                                        <span class="text-xs text-gray-500">(243)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-orange-600">৳1,499</p>
                                            <p class="text-xs text-gray-600 line-through">৳2,999</p>
                                        </div>
                                        <button class="p-2 bg-orange-100 hover:bg-orange-200 rounded-lg transition">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Hot Products -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">🔥 Hot Products</h2>
                        <Link href="/products/hot" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                            View All →
                        </Link>
                    </div>

                    <div>
                        <div class="flex gap-4">
                            <div v-for="i in 5" :key="'hot-' + i" class="w-56 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all cursor-pointer group">
                                <div class="relative aspect-square bg-gray-100">
                                    <img src="https://placehold.co/200x200/fef3c7/f59e0b?text=HOT" alt="Hot Product" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    <div class="absolute top-2 left-2 bg-red-500 text-gray-900 text-xs px-2 py-1 rounded-full font-bold animate-pulse">
                                        🔥 HOT
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">Trending Fashion Sneakers</h3>
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="text-xs text-yellow-500">⭐⭐⭐⭐⭐</span>
                                        <span class="text-xs text-gray-500">(456)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-teal-600">৳3,299</p>
                                            <p class="text-xs text-green-600">🔥 2.5K sold</p>
                                        </div>
                                        <button class="p-2 bg-teal-50 hover:bg-teal-100 rounded-lg transition">
                                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Trending Products -->
                <section class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900">📈 Trending Products</h2>
                        <Link href="/products/trending" class="text-teal-600 hover:text-teal-700 text-sm font-medium">
                            View All →
                        </Link>
                    </div>

                    <div>
                        <div class="flex gap-4">
                            <div v-for="i in 5" :key="'trending-' + i" class="w-56 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all cursor-pointer group">
                                <div class="relative aspect-square bg-gray-100">
                                    <img src="https://placehold.co/200x200/dbeafe/3b82f6?text=TREND" alt="Trending Product" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    <div class="absolute top-2 left-2 bg-blue-500 text-gray-900 text-xs px-2 py-1 rounded-full font-bold">
                                        📈 #{{ i }}
                                    </div>
                                </div>
                                <div class="p-3">
                                    <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">Popular Bluetooth Speaker</h3>
                                    <div class="flex items-center gap-1 mb-2">
                                        <span class="text-xs text-yellow-500">⭐⭐⭐⭐</span>
                                        <span class="text-xs text-gray-500">(678)</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-lg font-bold text-teal-600">৳1,899</p>
                                            <p class="text-xs text-blue-600">📈 Trending #{{ i }}</p>
                                        </div>
                                        <button class="p-2 bg-teal-50 hover:bg-teal-100 rounded-lg transition">
                                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>

        <!-- Footer -->
        <footer class="bg-gradient-to-br from-teal-50/90 via-cyan-50/90 to-blue-50/90 backdrop-blur-lg border-t border-white/20 shadow-lg mt-12">
            <div class="max-w-[1920px] mx-auto px-4 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <!-- About Us -->
                    <div>
                        <h3 class="text-gray-900 font-bold text-lg mb-4">About Vendora</h3>
                        <p class="text-gray-700 text-sm mb-4">Bangladesh's leading. multi-vendor marketplace connecting buyers with trusted sellers across the nation.</p>
                        <div class="flex gap-3">
                            <a href="#" class="w-8 h-8 bg-white/50 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                                <span class="text-sm">f</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white/50 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                                <span class="text-sm">𝕏</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white/50 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                                <span class="text-sm">in</span>
                            </a>
                            <a href="#" class="w-8 h-8 bg-white/50 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                                <span class="text-sm">📷</span>
                            </a>
                        </div>
                    </div>

                    <!-- Tools & App -->
                    <div>
                        <h3 class="text-gray-900 font-bold text-lg mb-4">Tools & App</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-teal-400 transition">🍎 iOS App</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">📱 Android App</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">📦 APK Download</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">💻 Desktop App</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">🔧 Seller Tools</a></li>
                            <li><a href="#" class="hover:text-teal-400 transition">📊 Analytics</a></li>
                        </ul>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-gray-900 font-bold text-lg mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li><Link href="/about" class="hover:text-teal-400 transition">About Us</Link></li>
                            <li><Link href="/contact" class="hover:text-teal-400 transition">Contact</Link></li>
                            <li><Link href="/careers" class="hover:text-teal-400 transition">Careers</Link></li>
                            <li><Link href="/blog" class="hover:text-teal-400 transition">Blog</Link></li>
                            <li><Link href="/press" class="hover:text-teal-400 transition">Press</Link></li>
                            <li><Link href="/affiliates" class="hover:text-teal-400 transition">Affiliates</Link></li>
                        </ul>
                    </div>

                    <!-- Legal -->
                    <div>
                        <h3 class="text-gray-900 font-bold text-lg mb-4">Legal</h3>
                        <ul class="space-y-2 text-sm">
                            <li><Link href="/terms" class="hover:text-teal-400 transition">Terms & Conditions</Link></li>
                            <li><Link href="/privacy" class="hover:text-teal-400 transition">Privacy Policy</Link></li>
                            <li><Link href="/return-policy" class="hover:text-teal-400 transition">Return Policy</Link></li>
                            <li><Link href="/shipping" class="hover:text-teal-400 transition">Shipping Policy</Link></li>
                            <li><Link href="/refund" class="hover:text-teal-400 transition">Refund Policy</Link></li>
                            <li><Link href="/cookie-policy" class="hover:text-teal-400 transition">Cookie Policy</Link></li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="border-t border-white/30 pt-8 flex flex-col md:flex-row justify-between items-center">
                    <p class="text-sm text-gray-600 mb-4 md:mb-0">
                        © 2024 Vendora BD. All rights reserved.
                    </p>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="text-gray-600">We accept:</span>
                        <div class="flex gap-2">
                            <div class="px-3 py-1 bg-white/50 rounded text-xs">bKash</div>
                            <div class="px-3 py-1 bg-white/50 rounded text-xs">Nagad</div>
                            <div class="px-3 py-1 bg-white/50 rounded text-xs">Rocket</div>
                            <div class="px-3 py-1 bg-white/50 rounded text-xs">Visa</div>
                            <div class="px-3 py-1 bg-white/50 rounded text-xs">Mastercard</div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    products: Object,
})
</script>

<style scoped>
/* Custom Scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    height: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #94a3b8;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}

/* Line Clamp */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
