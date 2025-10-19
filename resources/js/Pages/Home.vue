<template>
    <div class="min-h-screen relative overflow-hidden">
        <!-- Stripe-like Gradient Mesh Background -->
        <div class="fixed inset-0 -z-10">
            <!-- Base gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50"></div>
            
            <!-- Animated gradient blobs -->
            <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-6000"></div>
            
            <!-- Overlay for depth -->
            <div class="absolute inset-0 bg-white/30 backdrop-blur-3xl"></div>
        </div>

        <!-- Frosted Glass Header -->
        <header class="sticky top-0 z-50 backdrop-blur-xl bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 border-b border-white/20 shadow-lg">
            <!-- Top Menu Upper -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-6">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center flex-shrink-0">
                        <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Vendora
                        </span>
                    </Link>

                    <!-- Delivery Location -->
                    <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-lg bg-white/50 backdrop-blur-sm border border-white/30">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div class="text-sm">
                            <div class="text-gray-500 text-xs">Deliver to</div>
                            <div class="font-medium text-gray-900">Dhaka, Bangladesh</div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="flex-1 max-w-2xl">
                        <form @submit.prevent="search" class="relative">
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search for products, categories, or sellers..."
                                class="w-full px-6 py-3 rounded-full bg-white/80 backdrop-blur-sm border border-white/40 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition placeholder-gray-400"
                            />
                            <button
                                type="submit"
                                class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full hover:from-indigo-700 hover:to-purple-700 transition font-medium"
                            >
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Language Switcher -->
                    <div class="hidden md:block">
                        <select class="px-4 py-2 rounded-lg bg-white/50 backdrop-blur-sm border border-white/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium">
                            <option value="en">🇬🇧 English</option>
                            <option value="bn">🇧🇩 বাংলা</option>
                        </select>
                    </div>

                    <!-- Login/Account -->
                    <div v-if="$page.props.auth?.user" class="relative">
                        <button @click="showUserMenu = !showUserMenu" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/50 backdrop-blur-sm border border-white/30 hover:bg-white/70 transition">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                            <span class="hidden md:block text-sm font-medium">{{ $page.props.auth.user.name }}</span>
                        </button>
                        <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur-xl rounded-xl shadow-2xl py-2 border border-white/30">
                            <Link href="/customer/dashboard" class="block px-4 py-2 hover:bg-indigo-50 transition">Dashboard</Link>
                            <Link href="/customer/orders" class="block px-4 py-2 hover:bg-indigo-50 transition">Orders</Link>
                            <hr class="my-2">
                            <form @submit.prevent="logout">
                                <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 transition">Logout</button>
                            </form>
                        </div>
                    </div>
                    <div v-else class="flex items-center gap-3">
                        <Link href="/login" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Login</Link>
                        <Link href="/register" class="px-6 py-2 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
                            Register
                        </Link>
                    </div>

                    <!-- Orders/Cart -->
                    <Link href="/cart" class="relative flex items-center gap-2 px-4 py-2 rounded-lg bg-white/50 backdrop-blur-sm border border-white/30 hover:bg-white/70 transition">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="hidden md:block text-sm font-medium">Cart</span>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">0</span>
                    </Link>
                </div>
            </div>

            <!-- Top Menu Lower -->
            <div class="border-t border-white/20 bg-white/20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex items-center justify-center gap-12 py-3">
                        <button @click="showCategoryMenu = !showCategoryMenu" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition flex items-center gap-1">
                            Browse Products
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <Link href="/sellers" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Browse Sellers</Link>
                        <Link href="/vendor/register" class="px-6 py-2 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium hover:from-amber-600 hover:to-orange-600 transition shadow-lg">
                            🏪 Become a Seller
                        </Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
            
            <!-- Seller Display 1: Featured Sellers (3 visible, rest scroll) -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        ⭐ Featured Sellers
                    </h2>
                    <Link href="/sellers/featured" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All →
                    </Link>
                </div>
                
                <div v-if="featuredSellers.length > 0" class="relative">
                    <div class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4" ref="featuredSellersScroll">
                        <div
                            v-for="seller in featuredSellers"
                            :key="seller.id"
                            class="flex-shrink-0 w-80 bg-white/70 backdrop-blur-xl rounded-2xl p-6 border border-white/40 hover:shadow-2xl transition group cursor-pointer"
                        >
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                    {{ seller.shop_name?.charAt(0).toUpperCase() || 'V' }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-900 group-hover:text-indigo-600 transition">
                                        {{ seller.shop_name || 'Vendor Store' }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ seller.products_count || 0 }} Products</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2">{{ seller.description || 'Quality products from a trusted seller' }}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs font-medium px-3 py-1 rounded-full bg-amber-100 text-amber-700">Featured</span>
                                <button class="px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition">
                                    Visit Store
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30">
                    <p class="text-gray-500">No featured sellers yet</p>
                </div>
            </section>

            <!-- Seller Display 2: Top Sellers (1 visible, rest scroll) -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                        🏆 Top Sellers
                    </h2>
                    <Link href="/sellers/top" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All →
                    </Link>
                </div>
                
                <div v-if="topSellers.length > 0" class="relative">
                    <div class="flex gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4">
                        <div
                            v-for="(seller, index) in topSellers"
                            :key="seller.id"
                            class="flex-shrink-0 w-96 bg-white/70 backdrop-blur-xl rounded-2xl p-6 border border-white/40 hover:shadow-2xl transition group cursor-pointer"
                        >
                            <div class="flex items-center gap-4 mb-4">
                                <div class="relative">
                                    <div class="w-20 h-20 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                        {{ seller.shop_name?.charAt(0).toUpperCase() || 'V' }}
                                    </div>
                                    <span class="absolute -top-2 -right-2 bg-amber-500 text-white text-xs font-bold rounded-full w-8 h-8 flex items-center justify-center shadow-lg">
                                        #{{ index + 1 }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-xl text-gray-900 group-hover:text-amber-600 transition">
                                        {{ seller.shop_name || 'Top Vendor' }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ seller.products_count || 0 }} Products</p>
                                    <p class="text-sm font-medium text-amber-600">Top Rated Seller</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-4">{{ seller.description || 'One of our top performing sellers' }}</p>
                            <button class="w-full px-4 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium hover:from-amber-600 hover:to-orange-600 transition">
                                Visit Store
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30">
                    <p class="text-gray-500">No top sellers yet</p>
                </div>
            </section>

            <!-- Ad Banner 1 -->
            <section>
                <div class="h-48 rounded-2xl bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400 flex items-center justify-center backdrop-blur-xl border border-white/40 shadow-xl">
                    <div class="text-center text-white">
                        <h3 class="text-3xl font-bold mb-2">Advertisement Space</h3>
                        <p class="text-lg">Promote your brand here</p>
                    </div>
                </div>
            </section>

            <!-- Product Display 1: Featured Products (2 rows x 5 cols) -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        ⭐ Featured Products
                    </h2>
                    <Link href="/products?featured=1" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All →
                    </Link>
                </div>
                
                <div v-if="featuredProducts.length > 0" class="relative">
                    <div class="overflow-x-auto scrollbar-hide">
                        <div class="grid grid-rows-2 grid-flow-col gap-4 pb-4" style="grid-auto-columns: minmax(200px, 1fr);">
                            <div
                                v-for="product in featuredProducts"
                                :key="product.id"
                                class="bg-white/70 backdrop-blur-xl rounded-xl overflow-hidden border border-white/40 hover:shadow-xl transition group cursor-pointer"
                            >
                                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                                    <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">Featured</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="font-medium text-sm text-gray-900 mb-1 truncate">{{ product.name }}</h3>
                                    <p class="text-xs text-gray-500 mb-2 truncate">{{ product.vendor?.shop_name || 'Vendora' }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-indigo-600">৳{{ product.price }}</span>
                                        <button class="p-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30">
                    <p class="text-gray-500">No featured products yet</p>
                </div>
            </section>

            <!-- Product Display 2: On Sale (2 rows x 5 cols) -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                        💰 On Sale
                    </h2>
                    <Link href="/products?on_sale=1" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All →
                    </Link>
                </div>
                
                <div v-if="onSaleProducts.length > 0" class="relative">
                    <div class="overflow-x-auto scrollbar-hide">
                        <div class="grid grid-rows-2 grid-flow-col gap-4 pb-4" style="grid-auto-columns: minmax(200px, 1fr);">
                            <div
                                v-for="product in onSaleProducts"
                                :key="product.id"
                                class="bg-white/70 backdrop-blur-xl rounded-xl overflow-hidden border border-white/40 hover:shadow-xl transition group cursor-pointer"
                            >
                                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                                    <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                                    <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-lg">Sale</span>
                                    <span v-if="product.compare_at_price && product.compare_at_price > product.price" class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-lg">
                                        -{{ Math.round(((product.compare_at_price - product.price) / product.compare_at_price) * 100) }}%
                                    </span>
                                </div>
                                <div class="p-3">
                                    <h3 class="font-medium text-sm text-gray-900 mb-1 truncate">{{ product.name }}</h3>
                                    <p class="text-xs text-gray-500 mb-2 truncate">{{ product.vendor?.shop_name || 'Vendora' }}</p>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="text-lg font-bold text-green-600">৳{{ product.price }}</span>
                                            <span v-if="product.compare_at_price && product.compare_at_price > product.price" class="text-xs text-gray-400 line-through ml-1">৳{{ product.compare_at_price }}</span>
                                        </div>
                                        <button class="p-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30">
                    <p class="text-gray-500">No products on sale yet</p>
                </div>
            </section>

            <!-- Product Display 3: Hot Products (2 rows x 5 cols) -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                        🔥 Hot Products
                    </h2>
                    <Link href="/products?hot=1" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All →
                    </Link>
                </div>
                
                <div v-if="hotProducts.length > 0" class="relative">
                    <div class="overflow-x-auto scrollbar-hide">
                        <div class="grid grid-rows-2 grid-flow-col gap-4 pb-4" style="grid-auto-columns: minmax(200px, 1fr);">
                            <div
                                v-for="product in hotProducts"
                                :key="product.id"
                                class="bg-white/70 backdrop-blur-xl rounded-xl overflow-hidden border border-white/40 hover:shadow-xl transition group cursor-pointer"
                            >
                                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                                    <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                                    <span class="absolute top-2 left-2 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-lg">Hot 🔥</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="font-medium text-sm text-gray-900 mb-1 truncate">{{ product.name }}</h3>
                                    <p class="text-xs text-gray-500 mb-2 truncate">{{ product.vendor?.shop_name || 'Vendora' }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-orange-600">৳{{ product.price }}</span>
                                        <button class="p-1.5 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30">
                    <p class="text-gray-500">No hot products yet</p>
                </div>
            </section>

            <!-- Product Display 4: Deal of the Day (2 rows x 5 cols) -->
            <section>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-amber-600 bg-clip-text text-transparent">
                        ⚡ Deal of the Day
                    </h2>
                    <Link href="/products?deal_of_day=1" class="text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                        View All →
                    </Link>
                </div>
                
                <div v-if="dealOfTheDay.length > 0" class="relative">
                    <div class="overflow-x-auto scrollbar-hide">
                        <div class="grid grid-rows-2 grid-flow-col gap-4 pb-4" style="grid-auto-columns: minmax(200px, 1fr);">
                            <div
                                v-for="product in dealOfTheDay"
                                :key="product.id"
                                class="bg-white/70 backdrop-blur-xl rounded-xl overflow-hidden border border-white/40 hover:shadow-xl transition group cursor-pointer"
                            >
                                <div class="relative aspect-square bg-gray-200 overflow-hidden">
                                    <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition duration-300" />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                                    <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-lg">Deal ⚡</span>
                                </div>
                                <div class="p-3">
                                    <h3 class="font-medium text-sm text-gray-900 mb-1 truncate">{{ product.name }}</h3>
                                    <p class="text-xs text-gray-500 mb-2 truncate">{{ product.vendor?.shop_name || 'Vendora' }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-bold text-yellow-600">৳{{ product.price }}</span>
                                        <button class="p-1.5 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else class="text-center py-12 bg-white/50 backdrop-blur-sm rounded-2xl border border-white/30">
                    <p class="text-gray-500">No deals of the day yet</p>
                </div>
            </section>

            <!-- Ad Banner 2 -->
            <section>
                <div class="h-48 rounded-2xl bg-gradient-to-r from-blue-400 via-cyan-400 to-teal-400 flex items-center justify-center backdrop-blur-xl border border-white/40 shadow-xl">
                    <div class="text-center text-white">
                        <h3 class="text-3xl font-bold mb-2">Advertisement Space</h3>
                        <p class="text-lg">Promote your brand here</p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Frosted Glass Footer -->
        <footer class="mt-20 backdrop-blur-xl bg-gradient-to-r from-indigo-500/10 via-purple-500/10 to-pink-500/10 border-t border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex items-center justify-center gap-12 text-sm">
                    <Link href="/about" class="text-gray-700 hover:text-indigo-600 font-medium transition">About</Link>
                    <Link href="/contact" class="text-gray-700 hover:text-indigo-600 font-medium transition">Contact</Link>
                    <Link href="/terms" class="text-gray-700 hover:text-indigo-600 font-medium transition">Terms</Link>
                </div>
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">&copy; 2025 Vendora Bangladesh. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    featuredSellers: Array,
    topSellers: Array,
    featuredProducts: Array,
    onSaleProducts: Array,
    hotProducts: Array,
    dealOfTheDay: Array,
    categories: Array,
})

const searchQuery = ref('')
const showUserMenu = ref(false)
const showCategoryMenu = ref(false)

const search = () => {
    router.get('/products', { q: searchQuery.value })
}

const logout = () => {
    router.post('/logout')
}
</script>

<style scoped>
/* Custom scrollbar hide */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Blob animations */
@keyframes blob {
    0%, 100% {
        transform: translate(0, 0) scale(1);
    }
    25% {
        transform: translate(20px, -50px) scale(1.1);
    }
    50% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    75% {
        transform: translate(50px, 50px) scale(1.05);
    }
}

.animate-blob {
    animation: blob 20s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.animation-delay-6000 {
    animation-delay: 6s;
}
</style>