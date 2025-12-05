<x-app-layout>
    <div class="relative w-full h-[calc(100vh-85px)] bg-renews-noir-impure overflow-hidden flex flex-col items-center pb-6 font-sans">

        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 w-full px-6 pt-24 pb-12 mx-auto max-w-sm flex flex-col items-center">
            
            {{-- AVATAR --}}
            <div class="relative mb-6">
                {{-- Cercle Avatar --}}
                <div class="w-32 h-32 rounded-full border-[3px] border-renews-vert p-1 bg-renews-gris-fonce">
                    <img src="{{ asset('images/profil.png') }}" class="w-full h-full object-cover rounded-full">
                </div>
                
                {{-- Badge Niveau --}}
                <div class="absolute -bottom-1 -right-1 bg-renews-vert text-renews-noir font-bold text-lg w-10 h-10 flex items-center justify-center rounded-full border-[3px] border-renews-noir shadow-lg">
                    {{ $user->level->level_number ?? 1 }}
                </div>
            </div>

            {{-- TEXTES --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-1 tracking-wide">
                    {{ $user->username }}
                </h1>
                <p class="text-renews-gris font-medium tracking-wide text-sm">
                    Membre depuis {{ $user->created_at->format('M Y') }}
                </p>
            </div>
            
            {{-- CARTE XP (Gris foncé translucide) --}}
            <div class="w-full bg-[#232222]/90 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-white/5 shadow-xl">
                <div class="flex justify-between text-xs font-bold mb-3 text-gray-400 tracking-wider">
                    <span>XP ACTUEL</span>
                    <span class="text-renews-vert">{{ $user->current_xp ?? 0 }} / {{ $user->level->xp_threshold ?? 100 }} XP</span>
                </div>
                
                {{-- Barre --}}
                <div class="w-full bg-renews-noir rounded-full h-4 border border-white/10 relative overflow-hidden shadow-inner">
                    <div class="bg-gradient-to-r from-renews-fonce to-renews-electric h-full rounded-full relative z-10" 
                         style="width: {{ min(100, (($user->current_xp ?? 0) / ($user->level->xp_threshold ?? 100)) * 100) }}%">
                    </div>
                </div>
            </div>

            {{-- STATS (Cartes Blanches côte à côte) --}}
            <div class="w-full flex justify-between gap-4 mb-8">
                {{-- Série --}}
                <div class="bg-white rounded-2xl p-4 flex-1 flex items-center justify-center h-32 shadow-lg relative overflow-hidden">
                    <span class="text-5xl font-black text-black z-10">{{ $user->current_streak ?? 0 }}</span>
                    <i class="fa-solid fa-fire text-renews-vert text-3xl mt-1 z-10"></i>
                    {{-- Déco fond --}}
                    <i class="fa-solid fa-fire text-gray-100 text-12xl absolute -bottom-4 -right-4 -rotate-12 z-0"></i>
                </div>
                
                {{-- Record --}}
                <div class="bg-white rounded-2xl p-4 flex-1 flex items-center justify-center h-32 shadow-lg relative overflow-hidden">
                    <span class="text-5xl font-black text-black z-10">{{ $user->max_streak ?? 0 }}</span>
                    <i class="fa-solid fa-star text-renews-electric text-3xl mt-1 z-10"></i>
                    {{-- Déco fond --}}
                    <i class="fa-solid fa-star text-gray-100 text-8xl absolute -bottom-4 -right-4 -rotate-12 z-0"></i>
                </div>
            </div>

            {{-- BOUTONS D'ACTION --}}
            <div class="w-full space-y-4">
                <a href="{{ route('profile.edit') }}" class="block w-full py-4 rounded-xl bg-renews-vert text-renews-noir font-bold text-center uppercase tracking-wide hover:brightness-110 transition shadow-lg shadow-renews-vert/20">
                    Modifier mon profil
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="block w-full py-4 rounded-xl bg-renews-vert text-renews-noir font-bold text-center uppercase tracking-wide hover:brightness-110 transition shadow-lg shadow-renews-vert/20">
                        Déconnexion
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</x-app-layout>