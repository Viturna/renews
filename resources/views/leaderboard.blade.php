<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-28 relative overflow-hidden font-sans">

        <!-- Fond étoilé -->
        <div class="absolute inset-0 z-0 pointer-events-none " 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 px-6 pt-12 max-w-md mx-auto">
            
            <!-- EN-TÊTE -->
            <div class="mb-8">
                <h1 class="text-3xl font-medium leading-tight">
                    Classement <span class="text-renews-vert font-accent italic relative inline-block text-4xl ">
                        Global
                        <span class="absolute -top-1 -right-4 text-2xl">✦</span>
                        <span class="absolute bottom-1 left-0 w-full h-[2px] bg-white"></span>
                    </span>
                </h1>
                <p class="text-gray-400 text-lg mt-1 font-light">Les meilleurs joueurs</p>
            </div>

            <!-- LISTE CLASSEMENT -->
            <div class="space-y-3 overflow-y-auto max-h-[75vh] pr-2 scrollbar-hide pb-24">
                
                @forelse($leaderboard as $player)
                    <div class="flex items-center justify-between p-3 rounded-xl shadow-md relative group {{ $player['is_me'] ? 'bg-renews-vert/10 border border-renews-vert' : 'bg-white' }}">
                        
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full {{ $player['avatar_color'] }} flex items-center justify-center font-bold text-black text-xl shadow-sm {{ $player['is_me'] ? 'border-2 border-renews-vert' : '' }}">
                                {{ substr($player['name'], 0, 1) }}
                            </div>
                            
                            <!-- Infos (Nom + XP en dessous comme page amis) -->
                            <div class="flex flex-col">
                                <span class="text-xl font-medium {{ $player['is_me'] ? 'text-renews-vert' : 'text-black' }}">
                                    {{ $player['name'] }}
                                    @if($player['is_me']) <span class="text-[10px] uppercase font-normal text-gray-500 ml-1">(Moi)</span> @endif
                                </span>
                                <span class="text-lg font-bold text-renews-vert leading-none">
                                    {{ $player['points'] }} XP
                                </span>
                            </div>
                        </div>

                        <!-- Rang (Droite) -->
                        <div class="flex items-center justify-center w-10">
                            @if($player['rank'] == 1)
                                <i class="fa-solid fa-crown text-yellow-400 text-3xl drop-shadow-md"></i>
                            @elseif($player['rank'] == 2)
                                <i class="fa-solid fa-crown text-gray-400 text-2xl drop-shadow-md"></i>
                            @elseif($player['rank'] == 3)
                                <i class="fa-solid fa-crown text-amber-700 text-xl drop-shadow-md"></i>
                            @else
                                <span class="text-gray-400 font-bold text-lg font-mono">#{{ $player['rank'] }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-500">
                        <p>Aucun joueur pour le moment.</p>
                    </div>
                @endforelse

                <div class="h-20"></div>
            </div>

        </div>
    </div>
</x-app-layout>