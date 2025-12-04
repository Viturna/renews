<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
        <title>Renews - Se connecter</title>

        <!-- Fonts & Scripts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.scss', 'resources/js/app.js'])
    </head>
    
    <body class="bg-renews-noir-impure text-white font-sans antialiased min-h-screen flex flex-col relative overflow-x-hidden">

        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="flex-1 flex flex-col items-center justify-center px-6 py-10 z-10 w-full max-w-md mx-auto">
        
            <a href="/" class="mb-8 -rotate-3">
                <img src="/images/logo.svg" class="h-10 w-auto" alt="Renews">
            </a>
            <div class="w-full space-y-4 mb-8">
                <a href="/" class="mb-2 hover:opacity-60">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.86665 9.86659L9.06665 5.33325H12.4L9.59998 9.33325H18C20.2985 9.33325 22.5029 10.2463 24.1282 11.8717C25.7536 13.497 26.6666 15.7014 26.6666 17.9999C26.6666 20.2985 25.7536 22.5029 24.1282 24.1282C22.5029 25.7535 20.2985 26.6666 18 26.6666H12L13.3333 23.9999H18C19.5913 23.9999 21.1174 23.3678 22.2426 22.2426C23.3678 21.1173 24 19.5912 24 17.9999C24 16.4086 23.3678 14.8825 22.2426 13.7573C21.1174 12.6321 19.5913 11.9999 18 11.9999H9.59998L12.4 15.9999H9.06665L5.86665 11.4666L5.33331 10.6666L5.86665 9.86659Z" fill="white"/>
                    </svg>
                </a>
                <h1 class="text-3xl font-medium mb-8 tracking-tight mt-4">
                    Je me <span class="text-renews-vert font-accent italic text-4xl">connecte</span>
                </h1>
            </div>
            <a href="{{ route('social.redirect', 'google') }}" 
               class="w-full flex items-center justify-center bg-white text-black font-medium text-base py-4 px-4 rounded-xl shadow-md hover:bg-gray-100 transition-transform active:scale-95 mb-8">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Se connecter avec Google
            </a>

            <div class="flex items-center justify-between w-full mb-8 px-2">
                <div class="h-[3px] bg-renews-vert flex-1 rounded-full opacity-80"></div>
                <span class="px-4 text-renews-vert font-bold text-lg lowercase mb-1">ou</span>
                <div class="h-[3px] bg-renews-vert flex-1 rounded-full opacity-80"></div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="w-full space-y-5">
                @csrf

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div>
                    <label for="email" class="block text-white font-bold mb-2 text-lg pl-1">Adresse mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           placeholder="Ton mail" 
                           class="w-full rounded-xl border-none bg-white py-3.5 px-4 text-gris placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 font-bold text-sm" />
                </div>

                <div>
                    <label for="password" class="block text-white font-bold mb-2 text-lg pl-1">Mot de passe</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password" 
                           placeholder="Ton mot de passe" 
                           class="w-full rounded-xl border-none bg-white py-3.5 px-4 text-gris placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 font-bold text-sm" />
                </div>

                <div class="flex justify-end">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gris hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-renews-vert" href="{{ route('password.request') }}">
                            {{ __('Mot de passe oublié ?') }}
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full bg-renews-vert hover:bg-renews-fonce text-white font-bold py-4 rounded-xl transition-all duration-300 hover:-translate-y-1 text-lg">
                        Je me connecte !
                    </button>
                </div>
            </form>
        </div>
    </body>
</html>