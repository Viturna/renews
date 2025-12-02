<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-28 relative overflow-hidden font-sans">

        <!-- Fond étoilé -->
        <div class="absolute inset-0 z-0 pointer-events-none " 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 px-6 pt-10 max-w-md mx-auto">
            
            <!-- HEADER PROFIL -->
            <div class="flex items-start justify-between mb-2">
                
                <!-- Avatar -->
                <div class="relative">
                    <div class="w-24 h-24 rounded-full border-[3px] border-white overflow-hidden shadow-lg bg-gray-800 z-10 relative">
                        <div class="w-full h-full flex items-center justify-center bg-gray-700 text-gray-400">
                            <!-- Affichage conditionnel si l'user a une photo de profil ou non -->
                            <i class="fa-solid fa-user text-4xl"></i>
                        </div>
                    </div>
                    <!-- Halo vert décoratif -->
                    <div class="absolute inset-0 rounded-full border-[3px] border-renews-vert transform scale-110 opacity-0"></div>
                </div>

                <!-- Nom et Barre Décorative -->
                <div class="flex-1 pt-4 pl-4 relative">
                    <h1 class="text-2xl font-bold leading-none tracking-tight">
                        <span class="text-white">{{ Auth::user()->username }}</span>
                    </h1>

                    <div class="mt-2 w-full h-8 relative">
                        <div class="absolute top-0 left-0 w-[110%] h-[2px] bg-white transform -rotate-1 origin-left"></div>
                        <div class="absolute top-3 left-[-20px] w-[120%] h-1.5 bg-renews-vert transform -rotate-1 shadow-lg"></div>
                    </div>
                </div>
            </div>

            <!-- SECTION NIVEAU -->
            <div class="relative mt-2 mb-10">
                <div class="absolute right-20 bottom-4 text-gray-400 font-bold text-sm tracking-widest">LVL.</div>
                
                <!-- Niveau Dynamique -->
                <div class="absolute right-0 bottom-[-10px] text-8xl font-black text-white leading-none drop-shadow-lg"
                     style="-webkit-text-stroke: 2px transparent;">
                    {{ $stats['level'] }}
                </div>

                <!-- Barre de progression Dynamique -->
                <div class="w-[70%] h-3 bg-gray-700 rounded-full mt-8 relative overflow-hidden">
                    <div class="h-full bg-renews-vert shadow-[0_0_10px_#74FD08] transition-all duration-1000 ease-out" 
                         style="width: {{ $stats['progress_percent'] }}%"></div>
                </div>
                <!-- Petit texte XP restant (optionnel) -->
                <div class="w-[70%] text-right text-xs text-gray-400 mt-1">
                    {{ $stats['current_xp'] }} / {{ $stats['next_level_xp'] }} XP
                </div>
            </div>

            <!-- SÉPARATEUR -->
            <div class="w-full h-[1px] bg-white opacity-30 mb-8"></div>

            <!-- STATS (Flamme, Top, Trophée) -->
            <div class="flex justify-between items-end mb-10 px-2">
                
                <!-- Flammes (Streaks) -->
                <div class="flex items-center gap-1" title="Série de jours consécutifs">
                    <span class="text-5xl font-black text-white tracking-tighter">{{ Auth::user()->current_streak ?? 0 }}</span>
                    <i class="fa-solid fa-fire text-renews-vert text-4xl drop-shadow-[0_0_5px_rgba(116,253,8,0.6)] {{ Auth::user()->current_streak > 0 ? 'animate-pulse' : '' }}"></i>
                </div>

                <!-- TOP % -->
                <div class="flex flex-col items-center leading-none">
                    <span class="text-xs font-bold text-white uppercase mb-1">Top</span>
                    <span class="text-5xl font-black text-white italic tracking-tighter">{{ $stats['rank_top'] }}%</span>
                </div>

                <!-- Trophée (Décoratif pour l'instant) -->
                <div class="pb-1">
                    <i class="fa-solid fa-trophy text-white text-4xl"></i>
                </div>
            </div>

            <!-- GROS BLOC CONTENU -->
            <div class="w-full aspect-square bg-[#e5e5e5] rounded-3xl shadow-inner relative overflow-hidden flex items-center justify-center">
                <div class="absolute inset-0 opacity-40 mix-blend-multiply pointer-events-none"
                     style="background-image: url('https://www.transparenttextures.com/patterns/crumpled-paper.png');">
                </div>
                
                <div class="text-center opacity-30">
                    <i class="fa-solid fa-layer-group text-6xl text-black mb-2"></i>
                    <p class="text-black font-bold uppercase">Collection</p>
                </div>
            </div>

            <!-- Liste des articles (Si tu veux les afficher en dessous) -->
            @if(isset($articles) && count($articles) > 0)
                <div class="mt-10">
                    <h2 class="text-xl font-bold mb-4">À découvrir</h2>
                    @foreach($articles as $article)
                        <div class="mb-4 bg-gray-800 rounded-lg p-4 flex gap-4">
                            <div class="w-20 h-20 bg-cover bg-center rounded-md flex-shrink-0" style="background-image: url('{{ $article->thumbnail }}');"></div>
                            <div class="flex-1">
                                <span class="text-xs text-renews-vert uppercase font-bold">{{ $article->theme->name ?? 'Actu' }}</span>
                                <h3 class="font-bold leading-tight">{{ $article->title }}</h3>
                                <a href="{{ route('content.show', $article->id) }}" class="text-sm text-gray-400 mt-1 block">Regarder →</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>