<x-admin-layout>
    
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Dashboard</h1>
        <p class="text-gray-500">Vue d'ensemble de l'activité</p>
    </div>

    <!-- STATS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Users -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] relative overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fa-solid fa-users text-6xl text-gray-900"></i>
            </div>
            <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Utilisateurs</h3>
            <p class="text-4xl font-black text-gray-900">{{ $stats['total_users'] }}</p>
        </div>

        <!-- Contenus -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] relative overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fa-solid fa-newspaper text-6xl text-blue-600"></i>
            </div>
            <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Contenus</h3>
            <p class="text-4xl font-black text-gray-900">{{ $stats['total_contents'] }}</p>
        </div>

        <!-- Thèmes -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] relative overflow-hidden group hover:shadow-lg transition-shadow">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="fa-solid fa-layer-group text-6xl text-purple-600"></i>
            </div>
            <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Thèmes</h3>
            <p class="text-4xl font-black text-gray-900">{{ $stats['total_themes'] }}</p>
        </div>

        <!-- Statut du jour -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] relative overflow-hidden hover:shadow-lg transition-shadow">
            <h3 class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-2">Contenu du Jour</h3>
            <div class="flex items-center gap-3">
                @if($stats['today_content'])
                    <div class="w-4 h-4 rounded-full bg-renews-vert shadow-[0_0_10px_#74FD08] animate-pulse"></div>
                    <span class="text-2xl font-bold text-gray-900">En ligne</span>
                @else
                    <div class="w-4 h-4 rounded-full bg-red-500 shadow-sm"></div>
                    <span class="text-2xl font-bold text-gray-400">Manquant</span>
                @endif
            </div>
        </div>
    </div>

    <!-- DERNIERS INSCRITS -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Derniers Inscrits</h2>
            <a href="#" class="text-xs font-bold text-renews-vert hover:underline uppercase tracking-wide">Voir tout</a>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nom</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($lastUsers as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-bold text-gray-900">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            {{ $user->username }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 text-gray-400 text-sm font-medium">{{ $user->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-admin-layout>