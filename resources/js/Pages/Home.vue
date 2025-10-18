<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <!-- Top Bar -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center space-x-4">
                            <span>📞 +880 1234-567890</span>
                            <span>✉️ support@vendora.com.bd</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <Link href="/vendor/register" class="hover:text-indigo-200">
                                🏪 Become a Seller
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Header -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between gap-8">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center flex-shrink-0">
                        <span class="text-3xl font-bold text-indigo-600">Vendora</span>
                    </Link>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-3xl">
                        <form @submit.prevent="search" class="flex items-center">
                            <!-- Category Dropdown -->
                            <select v-model="searchCategory" class="px-4 py-3 border border-r-0 border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50">
                                <option value="">All Categories</option>
                                <option v-for="cat in categories" :key="cat.slug" :value="cat.slug">
                                    {{ cat.name }}
                                </option>
                            </select>

                            <!-- Search Input -->
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search for products..."
                                class="flex-1 px-4 py-3 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />

                            <!-- Search Button -->
                            <button
                                type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-r-lg hover:bg-indigo-700 transition font-medium"
                            >
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Right Side Icons -->
                    <div class="flex items-center space-x-6">
                        <!-- Wishlist -->
                        <Link href="/customer/wishlist" class="relative hover:text-indigo-600 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </Link>

                        <!-- Cart -->
                        <Link href="/cart" class="relative hover:text-indigo-600 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </Link>

                        <!-- User Menu -->
                        <div v-if="$page.props.auth?.user" class="relative">
                            <button @click="showUserMenu = !showUserMenu" class="flex items-center space-x-2 hover:text-indigo-600 transition">
                                <div class="w-9 h-9 bg-indigo-600 rounded-full flex items-center justify-center text-white font-medium">
                                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                </div>
                            </button>
                            <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-200">
                                <Link href="/customer/dashboard" class="block px-4 py-2 hover:bg-gray-100">Dashboard</Link>
                                <Link href="/customer/orders" class="block px-4 py-2 hover:bg-gray-100">My Orders</Link>
                                <hr class="my-2">
                                <form @submit.prevent="logout">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>

                        <!-- Login/Register (for guests) -->
                        <div v-else class="flex items-center space-x-3">
                            <Link href="/login" class="text-gray-700 hover:text-indigo-600 font-medium">Login</Link>
                            <Link href="/register" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                                Register
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Bar -->
            <div class="border-t border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <nav class="flex items-center space-x-8 py-3">
                        <Link href="/" class="text-gray-700 hover:text-indigo-600 font-medium">Home</Link>
                        <Link href="/products" class="text-gray-700 hover:text-indigo-600 font-medium">All Products</Link>
                        <Link href="/categories" class="text-gray-700 hover:text-indigo-600 font-medium">Categories</Link>
                        <Link href="/stores" class="text-gray-700 hover:text-indigo-600 font-medium">Stores</Link>
                        <Link href="/deals" class="text-gray-700 hover:text-indigo-600 font-medium">Hot Deals 🔥</Link>
                        <Link href="/about" class="text-gray-700 hover:text-indigo-600 font-medium">About</Link>
                        <Link href="/contact" class="text-gray-700 hover:text-indigo-600 font-medium">Contact</Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <h1 class="text-5xl font-bold mb-6">
                            Shop Smarter, <br>Shop Faster
                        </h1>
                        <p class="text-xl text-indigo-100 mb-8">
                            Discover thousands of products from trusted vendors across Bangladesh. Quality guaranteed, delivered to your door.
                        </p>
                        <div class="flex items-center space-x-4">
                            <Link href="/products" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition">
                                Shop Now
                            </Link>
                            <Link href="/vendor/register" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-indigo-600 transition">
                                Become a Seller
                            </Link>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8">
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-4xl font-bold">{{ stats.total_products }}+</div>
                                    <div class="text-indigo-200 text-sm">Products</div>
                                </div>
                                <div>
                                    <div class="text-4xl font-bold">{{ stats.total_vendors }}+</div>
                                    <div class="text-indigo-200 text-sm">Vendors</div>
                                </div>
                                <div>
                                    <div class="text-4xl font-bold">50+</div>
                                    <div class="text-indigo-200 text-sm">Categories</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Shop by Category</h2>
                    <Link href="/categories" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        View All →
                    </Link>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    <Link
                        v-for="category in categories"
                        :key="category.slug"
                        :href="`/products?category=${category.slug}`"
                        class="flex flex-col items-center p-6 bg-gray-50 rounded-xl hover:bg-indigo-50 hover:shadow-md transition group"
                    >
                        <div class="text-4xl mb-3 group-hover:scale-110 transition">{{ category.icon }}</div>
                        <div class="text-sm font-medium text-gray-700 text-center">{{ category.name }}</div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Featured Products</h2>
                    <Link href="/products?featured=1" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        View All →
                    </Link>
                </div>
                
                <div v-if="featuredProducts.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div v-for="product in featuredProducts" :key="product.id" class="bg-white rounded-lg shadow hover:shadow-xl transition group">
                        <div class="relative aspect-square bg-gray-200 rounded-t-lg overflow-hidden">
                            <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Featured</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 mb-1 truncate">{{ product.name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ product.vendor?.shop_name || 'Vendora' }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-indigo-600">৳{{ product.price }}</span>
                                <button class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📦</div>
                    <p class="text-gray-500 text-lg">No featured products yet</p>
                </div>
            </div>
        </section>

        <!-- Hot Products Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">🔥 Hot Products</h2>
                    <Link href="/products?hot=1" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        View All →
                    </Link>
                </div>
                
                <div v-if="hotProducts.length > 0" class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div v-for="product in hotProducts" :key="product.id" class="bg-white rounded-lg shadow hover:shadow-xl transition group border border-gray-200">
                        <div class="relative aspect-square bg-gray-200 rounded-t-lg overflow-hidden">
                            <img v-if="product.image" :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition" />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400 text-4xl">📦</div>
                            <span class="absolute top-2 left-2 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded">Hot 🔥</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 mb-1 truncate">{{ product.name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ product.vendor?.shop_name || 'Vendora' }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-indigo-600">৳{{ product.price }}</span>
                                <button class="p-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">🔥</div>
                    <p class="text-gray-500 text-lg">No hot products yet</p>
                </div>
            </div>
        </section>

        <!-- Become a Seller CTA -->
        <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-4xl font-bold mb-4">Start Selling on Vendora Today</h2>
                    <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                        Join thousands of successful vendors. Reach millions of customers across Bangladesh with zero upfront costs.
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <Link href="/vendor/register" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition text-lg">
                            Register as Vendor
                        </Link>
                        <Link href="/about/selling" class="border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-indigo-600 transition text-lg">
                            Learn More
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-white text-lg font-bold mb-4">Vendora</h3>
                        <p class="text-sm">Bangladesh's leading multi-vendor marketplace. Shop from thousands of trusted sellers.</p>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-bold mb-4">Quick Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li><Link href="/about" class="hover:text-white">About Us</Link></li>
                            <li><Link href="/contact" class="hover:text-white">Contact</Link></li>
                            <li><Link href="/terms" class="hover:text-white">Terms & Conditions</Link></li>
                            <li><Link href="/privacy" class="hover:text-white">Privacy Policy</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-bold mb-4">For Sellers</h3>
                        <ul class="space-y-2 text-sm">
                            <li><Link href="/vendor/register" class="hover:text-white">Become a Seller</Link></li>
                            <li><Link href="/vendor/login" class="hover:text-white">Seller Login</Link></li>
                            <li><Link href="/seller-guide" class="hover:text-white">Seller Guide</Link></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-white text-lg font-bold mb-4">Contact Us</h3>
                        <ul class="space-y-2 text-sm">
                            <li>📞 +880 1234-567890</li>
                            <li>✉️ support@vendora.com.bd</li>
                            <li>📍 Dhaka, Bangladesh</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                    <p>&copy; 2025 Vendora Bangladesh. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
    featuredProducts: Array,
    hotProducts: Array,
    newArrivals: Array,
    categories: Array,
    stats: Object,
})

const searchQuery = ref('')
const searchCategory = ref('')
const showUserMenu = ref(false)

const search = () => {
    router.get('/products', {
        q: searchQuery.value,
        category: searchCategory.value,
    })
}

const logout = () => {
    router.post('/logout')
}
</script>