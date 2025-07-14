<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    @php
                        $logoUrl = '/';
                        if (Auth::check()) {
                            if (Auth::user()->role === 'admin') {
                                $logoUrl = route('admin.dashboard');
                            } elseif (Auth::user()->role === 'user') {
                                $logoUrl = route('orders.index');
                            }
                        }
                    @endphp
                    <a href="{{ $logoUrl }}" class="flex-shrink-0 flex items-center">
                        <i class="fas fa-shopping-cart text-primary-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Irfan Store</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative">
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-150 ease-in-out">
                            <i class="fas fa-user-plus mr-1"></i> Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar and Main Content -->
    @auth
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-4">
                <nav class="space-y-2">
                    @if(Auth::user() && Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('admin.categories.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-tags mr-3"></i>
                            Categories
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('admin.products.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-box mr-3"></i>
                            Products
                        </a>
                    @endif
                    @if(Auth::user() && Auth::user()->role === 'user')
                        <a href="{{ route('orders.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('orders.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            Orders
                        </a>
                        <a href="{{ route('orders.my-orders') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition duration-150 ease-in-out {{ request()->routeIs('orders.my-orders') ? 'bg-primary-50 text-primary-600' : '' }}">
                            <i class="fas fa-list mr-3"></i>
                            My Orders
                        </a>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    @else
        <!-- Content for non-authenticated users -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    @endauth

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Irfan Store. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
