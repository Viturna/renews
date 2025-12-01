<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-28 relative overflow-hidden font-sans">

        <!-- Fond étoilé -->
        <div class="absolute inset-0 z-0 pointer-events-none " 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 px-6 pt-10 max-w-md mx-auto">
            
            <!-- HEADER PROFIL -->
            <div class="flex items-start justify-between mb-2">
                
                <!-- Avatar avec bordure spéciale -->
                <div class="relative">
                    <div class="w-24 h-24 rounded-full border-[3px] border-white overflow-hidden shadow-lg bg-gray-800 z-10 relative">
                        <!-- Placeholder Avatar -->
                        <div class="w-full h-full flex items-center justify-center bg-gray-700 text-gray-400">
                            <i class="fa-solid fa-user text-4xl"></i>
                        </div>
                        <!-- Si tu as une image : <img src="..." class="w-full h-full object-cover"> -->
                    </div>
                    
                    <!-- Cercle décoratif vert derrière (optionnel pour l'effet de halo) -->
                    <div class="absolute inset-0 rounded-full border-[3px] border-renews-vert transform scale-110 opacity-0"></div>
                </div>

                <!-- Nom et Barre Décorative -->
                <div class="flex-1 pt-4 pl-4 relative">
                    <!-- Nom -->
                    <h1 class="text-2xl font-bold leading-none tracking-tight">
                        <span class="text-white">{{ $user->username }}</span>
                    </h1>

                    <!-- Barre graphique verte/blanche -->
                    <div class="mt-2 w-full h-8 relative">
                        <!-- Barre Blanche -->
                        <div class="absolute top-0 left-0 w-[110%] h-[2px] bg-white transform -rotate-1 origin-left"></div>
                        <!-- Barre Verte épaisse -->
                        <div class="absolute top-3 left-[-20px] w-[120%] h-1.5 bg-renews-vert transform -rotate-1 shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- SECTION NIVEAU -->
            <div class="relative mt-2 mb-10">
                <!-- Texte LVL -->
                <div class="absolute right-20 bottom-4 text-gray-400 font-bold text-sm tracking-widest">LVL.</div>
                
                <!-- Gros Chiffre Niveau -->
                <div class="absolute right-0 bottom-[-10px] text-8xl font-black text-white leading-none drop-shadow-lg"
                     style="-webkit-text-stroke: 2px transparent;">
                    {{ $stats['level'] }}
                </div>

                <!-- Barre de progression -->
                <div class="w-[70%] h-3 bg-gray-700 rounded-full mt-8 relative overflow-hidden">
                    <div class="h-full bg-renews-vert shadow-[0_0_10px_#74FD08]" style="width: {{ ($stats['current_xp'] / $stats['next_level_xp']) * 100 }}%"></div>
                </div>
            </div>

            <!-- SÉPARATEUR -->
            <div class="w-full h-[1px] bg-white opacity-30 mb-8"></div>

            <!-- STATS (Flamme, Top, Trophée) -->
            <div class="flex justify-between items-end mb-10 px-2">
                
                <!-- XP / Flamme -->
                <div class="flex items-center gap-1">
                    <span class="text-5xl font-black text-white tracking-tighter">{{ $stats['current_xp'] }}</span>
                    <i class="fa-solid fa-fire text-renews-vert text-4xl drop-shadow-[0_0_5px_rgba(116,253,8,0.6)]"></i>
                </div>

                <!-- TOP % -->
                <div class="flex flex-col items-center leading-none">
                    <span class="text-xs font-bold text-white uppercase mb-1">Top</span>
                    <span class="text-5xl font-black text-white italic tracking-tighter">{{ $stats['rank_top'] }}%</span>
                </div>

                <!-- Trophée -->
                <div class="pb-1">
                    <i class="fa-solid fa-trophy text-white text-4xl"></i>
                </div>
            </div>

            <!-- GROS BLOC CONTENU (Texture Papier) -->
            <!-- J'utilise une div avec un fond blanc/gris texturé pour imiter la zone de ta maquette -->
            <div class="w-full aspect-square bg-[#e5e5e5] rounded-3xl shadow-inner relative overflow-hidden flex items-center justify-center">
                <!-- Texture papier (simulation CSS) -->
                <div class="absolute inset-0 opacity-40 mix-blend-multiply pointer-events-none"
                     style="background-image: url('https://www.transparenttextures.com/patterns/crumpled-paper.png');">
                </div>
                
                <!-- Contenu temporaire du bloc -->
                <div class="text-center opacity-30">
                    <i class="fa-solid fa-layer-group text-6xl text-black mb-2"></i>
                    <p class="text-black font-bold uppercase">Collection</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>