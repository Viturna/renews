<x-app-layout>
    <div class="min-h-screen bg-renews-noir-impure text-white pb-28 relative overflow-hidden font-sans">

        <div class="absolute inset-0 z-0 pointer-events-none " 
             style="background-image: url('/images/bg-stars.png'); background-size: cover; background-position: center;">
        </div>

        <div class="relative z-10 px-6 pt-12 max-w-md mx-auto">
            
            <!-- Message flash -->
            @if(session('success'))
                <div class="mb-4 p-3 bg-renews-vert/20 border border-renews-vert rounded-xl text-renews-vert text-center font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6">
                <h1 class="text-3xl font-medium leading-tight">
                    Ta liste d’<span class="text-renews-vert font-accent italic relative inline-block text-4xl ">
                        amis
                        <span class="absolute -top-1 -right-4 text-2xl">✦</span>
                        <span class="absolute bottom-1 left-0 w-full h-[2px] bg-white"></span>
                    </span>
                </h1>
                <p class="text-gray-400 text-lg mt-1 font-light">{{ count($friends) }} amis</p>
            </div>

            <div class="flex gap-2 mb-8">
               <a href="{{ route('friends.add') }}" class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-black shadow-lg hover:scale-105 transition-transform">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </a>
            </div>

            @if(isset($requests) && count($requests) > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-medium mb-3 text-white flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-renews-vert animate-pulse"></span>
                        Demandes en attente
                    </h2>
                    <div class="space-y-3">
                        @foreach($requests as $requestUser)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white border border-gray-700 shadow-md">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center font-bold text-white">
                                        {{ substr($requestUser->username, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-black">{{ $requestUser->username }}</span>
                                </div>
                                <div class="flex gap-2">
                                    <!-- Accepter -->
                                    <form action="{{ route('friends.accept', $requestUser->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-9 h-9 rounded-full bg-renews-vert text-black flex items-center justify-center hover:scale-110 transition shadow-lg">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <!-- Refuser -->
                                    <form action="{{ route('friends.destroy', $requestUser->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 rounded-full bg-red-500/20 text-red-500 border border-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- LISTE CLASSEMENT -->
            <h2 class="text-xl font-medium mb-4">
                Classement entre <span class="text-renews-vert font-accent font-medium italic text-2xl">amis</span>
            </h2>

            <div class="space-y-3 overflow-y-auto max-h-[55vh] pr-2 scrollbar-hide pb-4">
                
                @forelse($friends as $friend)
                    <div class="flex items-center justify-between p-3 rounded-xl shadow-md relative group bg-white">
                        
                        <div class="flex items-center gap-4">
                            <!-- Avatar -->
                            <div class="w-12 h-12 rounded-full {{ $friend['avatar_color'] }} flex items-center justify-center font-bold text-black text-xl">
                                {{ substr($friend['name'], 0, 1) }}
                            </div>
                            
                            <div class="flex flex-col">
                                <span class="text-xl font-medium text-black">
                                    {{ $friend['name'] }}
                                </span>
                                <span class="text-lg font-bold text-renews-vert leading-none">
                                    {{ $friend['points'] }} XP
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Rang -->
                            <span class="text-gray-400 font-bold text-lg font-mono">#{{ $friend['rank'] }}</span>
                            
                            <!-- Bouton Supprimer (Rouge) -->
                            <form action="{{ route('friends.destroy', $friend['id']) }}" method="POST" onsubmit="return confirm('Supprimer cet ami ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-red-100 flex items-center justify-center text-gray-400 hover:text-red-500 transition-colors">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-500">
                        <p>Tu n'as pas encore d'amis.</p>
                        <a href="{{ route('friends.add') }}" class="text-renews-vert underline mt-2 inline-block">En ajouter un ?</a>
                    </div>
                @endforelse

                <div class="h-20"></div>
            </div>

        </div>
    </div>
</x-app-layout>