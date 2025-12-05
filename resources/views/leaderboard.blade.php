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
            </div>

            @php
                $top3 = $leaderboard->take(3);
                $rest = $leaderboard->skip(3);
            @endphp

            @if($top3->isNotEmpty())
                <div class="flex justify-center items-end mb-10 gap-3 text-center h-64 mt-32">
                    
                    {{-- 2ÈME PLACE (Gauche) --}}
                    @if(isset($top3[1]))
                        @php $player2 = $top3[1]; @endphp
                        <div class="flex flex-col items-center w-1/3 mb-4"> {{-- Décalé vers le bas --}}
                            <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center font-bold text-black text-xl mb-2 border-2 border-renews-vert shadow-lg z-10 relative">
                                {{ substr($player2['name'], 0, 1) }}
                            </div>
                            <div class="text-sm font-bold truncate w-full mb-1">{{ $player2['name'] }}</div>
                            <div class="text-xs text-renews-vert font-bold mb-2">{{ $player2['points'] }} pts</div>
                            
                            {{-- Bloc Podium 2 --}}
                            <div class="w-full bg-renews-fonce py-2 text-xl font-black h-24 flex items-start justify-center pt-2 text-white">
                                #2
                            </div>
                        </div>
                    @else
                         <div class="w-1/3"></div>
                    @endif

                    {{-- 1ÈRE PLACE (Centre - Plus haut) --}}
                    @if(isset($top3[0]))
                        @php $player1 = $top3[0]; @endphp
                        <div class="flex flex-col items-center w-1/3">
                            <i class="fa-solid fa-crown text-yellow-400 text-2xl mb-1 animate-bounce"></i>
                            <div class="w-20 h-20 rounded-full bg-white flex items-center justify-center font-bold text-black text-2xl mb-2 border-4 border-yellow-400 shadow-xl z-10 relative">
                                {{ substr($player1['name'], 0, 1) }}
                            </div>
                            <div class="text-base font-bold truncate w-full mb-1 text-renews-vert">{{ $player1['name'] }}</div>
                            <div class="text-sm text-white/80 font-bold mb-2">{{ $player1['points'] }} pts</div>
                            
                            {{-- Bloc Podium 1 --}}
                            <div class="w-full bg-renews-vert text-3xl font-black h-36 flex items-start justify-center pt-4 text-white">
                                #1
                            </div>
                        </div>
                    @endif

                    {{-- 3ÈME PLACE (Droite) --}}
                    @if(isset($top3[2]))
                        @php $player3 = $top3[2]; @endphp
                        <div class="flex flex-col items-center w-1/3 mb-8"> {{-- Encore plus bas --}}
                            <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center font-bold text-black text-lg mb-2 border-2 border-amber-700 shadow-lg z-10 relative">
                                {{ substr($player3['name'], 0, 1) }}
                            </div>
                            <div class="text-sm font-bold truncate w-full mb-1">{{ $player3['name'] }}</div>
                            <div class="text-xs text-renews-vert font-bold mb-2">{{ $player3['points'] }} pts</div>
                            
                            {{-- Bloc Podium 3 --}}
                            <div class="w-full bg-[#48900E] py-2 text-lg font-black h-16 flex items-start justify-center pt-2 text-white">
                                #3
                            </div>
                        </div>
                    @else
                        <div class="w-1/3"></div>
                    @endif

                </div>
            @endif


            <!-- LISTE CLASSEMENT (Reste des joueurs) -->
            <h2 class="text-xl font-medium mb-4 text-white">
                Meilleurs joueurs <span class="text-renews-vert font-accent font-medium italic text-2xl">du reste</span>
            </h2>

            <div class="space-y-3 overflow-y-auto max-h-[40vh] pr-2 scrollbar-hide pb-24">
                
                @forelse($rest as $player)
                    {{-- Carte BLANCHE (bg-white) pour respecter la maquette --}}
                    <div class="flex items-center justify-between p-3 rounded-xl shadow-md relative group {{ $player['is_me'] ? 'bg-renews-vert/20 border-2 border-renews-vert' : 'bg-white border border-gray-200' }}">
                        
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center font-bold text-black text-lg border border-gray-200">
                                {{ substr($player['name'], 0, 1) }}
                            </div>
                            
                            <!-- Infos (Nom + XP) -->
                            <div class="flex flex-col">
                                <span class="text-lg font-medium {{ $player['is_me'] ? 'text-renews-vert font-bold' : 'text-black' }}">
                                    {{ $player['name'] }}
                                    @if($player['is_me']) <span class="text-[10px] uppercase font-normal text-gray-500 ml-1">(Moi)</span> @endif
                                </span>
                                <span class="text-sm font-bold text-renews-vert leading-none">
                                    {{ $player['points'] }} XP
                                </span>
                            </div>
                        </div>

                        <!-- Rang (Droite) -->
                        <div class="flex items-center justify-center w-10">
                            <span class="text-gray-400 font-bold text-xl font-mono">#{{ $player['rank'] }}</span>
                        </div>
                    </div>
                @empty
                    @if($top3->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <p>Aucun joueur pour le moment.</p>
                        </div>
                    @endif
                @endforelse

                <div class="h-20"></div>
            </div>

        </div>
    </div>
</x-app-layout>