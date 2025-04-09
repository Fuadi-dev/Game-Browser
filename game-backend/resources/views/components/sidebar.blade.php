<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js" defer></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen">
    <div x-data="{
        isExpanded: localStorage.getItem('sidebarState') === 'true',
        currentPath: window.location.pathname
    }">
        <!-- Sidebar - Keep transition -->
        <aside
            :class="isExpanded ? 'w-64' : 'w-20'"
            class="fixed top-0 left-0 h-screen transition-all duration-300 bg-white"
        >
            <!-- Toggle Button - Keep transition -->
            <button
                @click="isExpanded = !isExpanded; localStorage.setItem('sidebarState', isExpanded)"
                class="absolute p-2 text-white transition-colors duration-200 bg-indigo-600 rounded-full shadow-lg -right-3 top-6 hover:bg-indigo-700 focus:outline-none"
            >
                <i class="ph-bold ph-caret-left" x-show="isExpanded"></i>
                <i class="ph-bold ph-caret-right" x-show="!isExpanded"></i>
            </button>

            <!-- Logo Section -->
            <div class="flex items-center p-4 mb-6">
                <i class="text-3xl text-indigo-600 ph-bold ph-game-controller"></i>
                <span
                    x-show="isExpanded"
                    x-transition
                    class="ml-3 text-xl font-bold text-gray-800"
                >
                Hello {{ Auth::user()->role }}
                </span>
            </div>

            <!-- Navigation Menu -->
            <nav class="px-4">
                <!-- Dashboard -->
                <a href="{{ url('/') }}"
                    class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-opacity-80"
                    :class="{
                        'bg-indigo-600 text-white': currentPath === '/',
                        'text-gray-700 hover:bg-indigo-50': currentPath !== '/'
                    }"
                >
                    <i class="ph-bold ph-house" :class="isExpanded ? '' : 'mx-auto'"></i>
                    <span x-show="isExpanded" class="ml-3">Dashboard</span>
                </a>

                <!-- Transaksi -->
                <a href="{{ url('/game') }}"
                    class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-opacity-80"
                    :class="{
                        'bg-indigo-600 text-white': currentPath === '/game',
                        'text-gray-700 hover:bg-indigo-50': currentPath !== '/game'
                    }"
                >
                    <i class="ph-bold ph-game-controller" :class="isExpanded ? '' : 'mx-auto'"></i>
                    <span x-show="isExpanded" class="ml-3">Game</span>
                </a>

                @if (Auth::user()->role == 'admin')
                <!-- Pelanggan -->
                <a href="{{ url('/users') }}"
                    class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-opacity-80"
                    :class="{
                        'bg-indigo-600 text-white': currentPath === '/users',
                        'text-gray-700 hover:bg-indigo-50': currentPath !== '/users'
                    }"
                >
                    <i class="ph-bold ph-users" :class="isExpanded ? '' : 'mx-auto'"></i>
                    <span x-show="isExpanded" class="ml-3">Users</span>
                </a>

              <!-- Penjualan -->
                <a href="{{ url('/log-login') }}"
                    class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-opacity-80"
                    :class="{
                        'bg-indigo-600 text-white': currentPath === '/log-login',
                        'text-gray-700 hover:bg-indigo-50': currentPath !== '/log-login'
                    }"
                >
                    <i class="ph-bold ph-clock" :class="isExpanded ? '' : 'mx-auto'"></i>
                    <span x-show="isExpanded" class="ml-3">Log Login</span>
                </a>


                <!-- Category -->
                <a href="{{ url('/category') }}"
                    class="flex items-center px-4 py-3 mb-2 rounded-lg hover:bg-opacity-80"
                    :class="{
                        'bg-indigo-600 text-white': currentPath === '/category',
                        'text-gray-700 hover:bg-indigo-50': currentPath !== '/category'
                    }"
                >
                    <i class="ph-bold ph-puzzle-piece" :class="isExpanded ? '' : 'mx-auto'"></i>
                    <span x-show="isExpanded" class="ml-3">Category</span>
                </a>
                @endif
            </nav>
           <!-- Footer Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4">
            <!-- Logout Button -->
            <a href="{{ url('/logout') }}"
                class="flex items-center w-full px-4 py-3 text-gray-800 transition-colors duration-200 bg-gray-300 rounded-lg active:bg-red-600 active:text-white"
            >
                <i class="ph-bold ph-sign-out" :class="isExpanded ? '' : 'mx-auto'"></i>
                <span x-show="isExpanded" class="ml-3">Logout</span>
            </a>
        </div>

        </aside>

        <!-- Main Content -->
        <main :class="isExpanded ? 'ml-64' : 'ml-20'" class="p-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
