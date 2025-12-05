<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Renews" />
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="//unpkg.com/alpinejs" defer></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-64 flex-shrink-0 bg-white border-r border-gray-200 hidden md:flex flex-col transition-all duration-300 z-20">
            <div class="h-16 flex items-center justify-center border-b border-gray-200">
                <a href="{{ route('dashboard') }}" class="text-2xl font-black text-black tracking-tighter">
                    RE<span class="text-renews-vert">NEWS</span>
                </a>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Général</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-renews-vert text-black font-bold shadow-md' : 'text-gray-600 hover:bg-gray-100 hover:text-black' }}">
                    <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i>
                    Dashboard
                </a>

                <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mt-6 mb-2">Gestion</p>

                <a href="{{ route('admin.themes.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.themes.*') ? 'bg-gray-100 text-black font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-black' }}">
                    <i class="fa-solid fa-layer-group w-6 text-center mr-2"></i>
                    Thèmes
                </a>

                <a href="{{ route('admin.contents.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.contents.*') ? 'bg-gray-100 text-black font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-black' }}">
                    <i class="fa-solid fa-newspaper w-6 text-center mr-2"></i>
                    Contenus & Quiz
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-black font-semibold' : 'text-gray-600 hover:bg-gray-100 hover:text-black' }}">
                    <i class="fa-solid fa-users w-6 text-center mr-2"></i>
                    Utilisateurs
                </a>

                <a href="{{ route('admin.ads.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-renews-vert text-black font-bold shadow-md' : 'text-gray-600 hover:bg-gray-100 hover:text-black' }}">
                    <i class="fa-solid fa-chart-line w-6 text-center mr-2"></i>
                    Ads
                </a>
            </nav>

            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm text-gray-500 hover:text-black transition-colors font-medium">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Retour au site
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative bg-gray-50">
            
            <!-- Mobile Header -->
            <header class="md:hidden flex items-center justify-between h-16 bg-white px-4 border-b border-gray-200 shadow-sm z-10">
                <span class="font-bold text-lg text-gray-800">Admin ReNews</span>
                <button class="text-gray-500 focus:outline-none hover:text-black">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-12 relative">
                <!-- Message Flash -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                         class="absolute top-6 right-6 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center animate-bounce">
                        <i class="fa-solid fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-xl shadow-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>