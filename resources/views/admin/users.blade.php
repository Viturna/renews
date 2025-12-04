<x-admin-layout>
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Utilisateurs</h1>
            <p class="text-gray-500">Gérer la communauté ReNews</p>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-64">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" 
                   class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2 focus:border-renews-vert focus:ring-renews-vert shadow-sm text-sm">
            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </form>
    </div>

    <!-- LISTE DES UTILISATEURS -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Utilisateur</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 font-semibold">Niveau</th>
                        <th class="px-6 py-4 font-semibold">XP</th>
                        <th class="px-6 py-4 font-semibold">Inscription</th>
                        <th class="px-6 py-4 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100" x-data="{ editingUser: null }">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs uppercase">
                                    {{ substr($user->username, 0, 1) }}
                                </div>
                                {{ $user->username }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold border border-blue-100">
                                LVL {{ $user->level->level_number ?? 1 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600 font-mono text-sm">{{ $user->current_xp }}</td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="editingUser = {{ json_encode($user) }}" 
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Modifier">
                                    <i class="fa-solid fa-pen"></i>
                                </button>

                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($users->isEmpty())
            <div class="p-12 text-center text-gray-500">
                <i class="fa-solid fa-ghost text-4xl mb-4 text-gray-300"></i>
                <p>Aucun utilisateur trouvé.</p>
            </div>
        @endif

        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>

        <div x-show="editingUser" style="display: none;" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
             x-transition.opacity>
            
            <div @click.away="editingUser = null" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all" x-transition.scale>
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Modifier l'utilisateur</h3>
                    <button @click="editingUser = null" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <form :action="'/admin/users/' + editingUser?.id" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nom d'utilisateur</label>
                        <input type="text" name="username" x-model="editingUser.username" 
                               class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="editingUser.email" 
                               class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Niveau</label>
                            <select name="current_level_id" x-model="editingUser.current_level_id" 
                                    class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert">
                                @foreach($levels as $level)
                                    <option value="{{ $level->id }}">Niveau {{ $level->level_number }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">XP Actuel</label>
                            <input type="number" name="current_xp" x-model="editingUser.current_xp" 
                                   class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert">
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="editingUser = null" class="flex-1 px-4 py-2 bg-white border border-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-50">
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-renews-vert text-black font-black uppercase tracking-wide rounded-lg hover:brightness-110 shadow-md">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>