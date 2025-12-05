<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Renews') }}</title>

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

        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-renews-noir-impure text-white">
        
        <div class="min-h-screen flex flex-col">
            
            <main class="flex-1 pb-24 relative overflow-x-hidden">
                {{ $slot }}
            </main>

            <div class="fixed bottom-0 left-0 w-full z-50">
                
                <div class="absolute left-1/2 -translate-x-1/2 -top-1 z-50">
                    <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-[64px] h-[64px] bg-renews-vert rounded-full border-[5px] border-renews-noir-impure shadow-lg text-white text-xl transition-transform active:scale-95">
                        <i class="fa-solid fa-house"></i>
                    </a>
                </div>

                <nav class="w-full bg-[#1C1C1C] overflow-hidden shadow-[0_-5px_20px_rgba(0,0,0,0.5)] h-[75px] flex items-center px-4 relative z-40">
                    
                    <div class="flex-1 flex justify-around pr-8">
                         <a href="{{ route('leaderboard') }}" class="flex flex-col items-center gap-1 transition-colors group
                           {{ request()->routeIs('leaderboard') ? 'text-renews-vert' : 'text-white hover:text-renews-vert' }}">
                            <i class="fa-solid fa-trophy text-xl transition-colors"></i>
                            <span class="text-[10px] font-medium">Classement</span>
                        </a>
                        
                        <a href="{{ route('quiz') }}" class="flex flex-col items-center gap-1 transition-colors group
                           {{ request()->routeIs('quiz') ? 'text-renews-vert' : 'text-white hover:text-renews-vert' }} relative">
                            <i class="fa-solid fa-bolt text-xl transition-colors"></i>
                            <span class="text-[10px] font-medium">Quiz</span>
                            
                            @if(request()->routeIs('quiz'))
                                <span class="absolute -top-5 right-1 w-3.5 h-3.5 bg-renews-vert rounded-full"></span>
                            @endif
                        </a>
                    </div>

                    <div class="w-4"></div>

                    <div class="flex-1 flex justify-around pl-8">
                        
                        <a href="{{ route('friends') }}" class="flex flex-col items-center gap-1 transition-colors group
                           {{ request()->routeIs('friends') ? 'text-renews-vert' : 'text-white hover:text-renews-vert' }}">
                            
                            <i class="fa-solid fa-user-group text-xl transition-colors"></i>
                            <span class="text-[10px] font-medium">Amis</span>
                            
                            @if(request()->routeIs('friends'))
                                <span class="absolute -top-1.5 w-3.5 h-3.5 bg-renews-vert rounded-full"></span>
                            @endif
                        </a>

                        <a href="{{ route('profile.show') }}" class="flex flex-col items-center gap-1 transition-colors group
                           {{ request()->routeIs('profile.show') ? 'text-renews-vert' : 'text-white hover:text-renews-vert' }}">
                            <i class="fa-solid fa-user text-xl transition-colors"></i>
                            <span class="text-[10px] font-medium">Profil</span>
                        </a>
                    </div>

                </nav>
            </div>

        </div>
    </body>
</html>