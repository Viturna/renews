<x-app-layout>
    {{-- Image de fond (Superposition des étoiles sur le fond noir de base) --}}
    <div class="relative min-h-full pb-10">
        {{-- Layer étoiles --}}
        <div class="absolute inset-0 z-0 pointer-events-none" 
             style="background-image: url('{{ asset('images/bg-stars.png') }}'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 px-4 pt-6 pb-20 mx-auto max-w-md">
            
            {{-- En-tête Profil --}}
            <div class="flex flex-col items-center mb-8">
                
                {{-- Avatar avec cercle de progression ou bordure --}}
                <div class="relative mb-3 group">
                    <div class="w-28 h-28 rounded-full border-[3px] border-renews-vert p-1 shadow-[0_0_15px_rgba(112,205,37,0.4)] bg-renews-gris-fonce">
                        <img src="/images/profil.png" class="w-full h-full object-cover rounded-full">
                    </div>
                    
                    {{-- Badge Niveau --}}
                    <div class="absolute -bottom-2 -right-2 bg-renews-vert text-renews-noir font-bold font-sans text-sm w-8 h-8 flex items-center justify-center rounded-full border-2 border-renews-noir-impure shadow-lg">
                        {{ $user->level->level ?? 1 }}
                    </div>
                </div>

                {{-- Nom & Titre --}}
                <h1 class="text-3xl font-sans font-bold text-white mb-1">
                    {{ $user->username }}
                </h1>
                <p class=" text-renews-gris font-sans tracking-wide">
                    Membre depuis {{ $user->created_at->format('M Y') }}
                </p>
            </div>
            
            {{-- Carte XP & Stats --}}
            <div class="bg-[#232222]/80 backdrop-blur-md rounded-2xl p-5 mb-6 border border-white/5 shadow-xl">
                
                {{-- Barre d'XP --}}
                <div class="mb-5">
                    <div class="flex justify-between text-xs font-semibold mb-2 text-gray-300 font-sans">
                        <span>XP ACTUEL</span>
                        <span class="text-renews-vert">{{ $user->experience ?? 0 }} / {{ $user->level->next_level_xp ?? 100 }} XP</span>
                    </div>
                    <div class="w-full bg-renews-noir rounded-full h-3 border border-white/5 relative overflow-hidden">
                        {{-- Effet de lueur interne --}}
                        <div class="absolute inset-0 shadow-[inset_0_2px_4px_rgba(0,0,0,0.5)] rounded-full z-10"></div>
                        
                        {{-- Progression --}}
                        <div class="bg-gradient-to-r from-renews-fonce to-renews-electric h-full rounded-full relative z-0 transition-all duration-1000 ease-out" 
                             style="width: {{ ($user->experience / ($user->level->next_level_xp ?? 100)) * 100 }}%">
                        </div>
                    </div>
                </div>

              
            </div>
              {{-- Grid Stats (Série, Quiz joués, etc) --}}
            <div class="flex flex-col gap-4">
                <div class="bg-white rounded-xl p-6 flex items-center justify-center w-32">
                    <span class="text-6xl font-bold font-sans text-black">{{ $user->current_streak ?? 0 }}</span>
                      <i class="fa-solid fa-fire text-renews-vert text-5xl mb-1"></i>
                </div>
                <div class="bg-white rounded-xl p-3 flex items-center justify-center w-32">
                    <span class="text-6xl font-bold font-sans text-black">{{ $user->max_streak ?? 0 }}</span>
                    <i class="fa-solid fa-star text-renews-electric text-xl mb-1"></i>
                </div>
            </div>

            <a href="{{ route('profile.edit') }}" class="block w-full py-3 rounded-lg bg-renews-vert text-renews-white font-sans font-bold text-center hover:bg-renews-fonce transition-all duration-300 mb-6">
                Modifier mon profil
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full mb-6">
                @csrf
                
                <button type="submit" class="block w-full py-3 rounded-lg bg-renews-vert text-white font-sans font-bold text-center hover:bg-renews-fonce transition-all duration-300">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</x-app-layout>