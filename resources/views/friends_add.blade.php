<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-24 relative overflow-hidden flex flex-col font-sans">

        <div class="absolute inset-0 z-0 pointer-events-none " 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 px-6 pt-12 max-w-md mx-auto w-full flex-1 flex flex-col">
            
            <!-- Messages Flash -->
            @if(session('success'))
                <div class="mb-6 p-3 bg-renews-vert text-black rounded-xl text-center font-medium animate-bounce">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-3 bg-gray-700 text-white rounded-xl text-center">
                    {{ session('info') }}
                </div>
            @endif

            <div class="mb-12 text-center">
                <h1 class="text-3xl font-medium leading-tight tracking-tight">
                    Trouve tes 
                    <span class="text-renews-vert text-4xl font-accent italic relative inline-block pl-1">
                        amis
                        <span class="absolute -top-1 -right-4 text-2xl">✦</span>
                        <span class="absolute bottom-1 left-0 w-full h-[3px] bg-white rounded-full"></span>
                    </span>
                </h1>
            </div>

            <!-- Formulaire Recherche -->
            <div class="mb-8 w-full">
                <h2 class="text-center text-2xl font-medium mb-4">
                    par <span class="text-renews-vert font-accent italic text-3xl">pseudo</span>
                </h2>
                
                <form action="{{ route('friends.add') }}" method="GET" class="relative">
                    <input type="text" 
                           name="search"
                           value="{{ $search ?? '' }}"
                           placeholder="Le pseudo..." 
                           class="w-full bg-white text-black font-medium text-lg py-4 px-6 rounded-xl border-none placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow shadow-lg">
                    
                    <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-black hover:text-renews-vert transition-colors p-2">
                        <i class="fa-solid fa-magnifying-glass text-xl"></i>
                    </button>
                </form>
            </div>

            <!-- Résultats de la recherche -->
            @if(isset($results) && count($results) > 0)
                <div class="flex-1 overflow-y-auto mb-4 space-y-3">
                    <p class="text-sm text-white pl-2">Utilisateurs trouvés :</p>
                    @foreach($results as $user)
                        <div class="flex items-center justify-between bg-[#232222] p-4 rounded-xl border border-gray-700 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center font-medium text-white">
                                    {{ substr($user->username, 0, 1) }}
                                </div>
                                <span class="font-medium text-lg text-white">{{ $user->username }}</span>
                            </div>
                            
                            <form action="{{ route('friends.store', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-renews-vert text-renews-noir-impure px-4 py-2 rounded-lg font-medium text-sm hover:bg-renews-electric transition-transform active:scale-95 shadow-lg">
                                    Ajouter
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @elseif(isset($search))
                <div class="text-center text-white mb-8">
                    Aucun utilisateur trouvé pour "{{ $search }}"
                </div>
            @endif

            <!-- Bouton Retour si recherche vide -->
            @if(!isset($search))
            <div class="w-full mt-auto mb-8">
                <h2 class="text-center text-2xl font-medium mb-4">
                    par <span class="text-renews-vert font-accent italic text-3xl">qr code</span>
                </h2>
                <button onclick="alert('Bientôt disponible !')" class="w-full bg-renews-vert hover:bg-renews-fonce text-white font-medium text-xl py-4 rounded-xl transition-all transform active:scale-95 flex items-center justify-center gap-3">
                    <i class="fa-solid fa-qrcode"></i>
                    Scanner un QR code
                </button>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>