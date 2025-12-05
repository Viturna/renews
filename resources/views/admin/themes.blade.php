<x-admin-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Thèmes</h1>
            <p class="text-gray-500">Catégories d'actualités</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- LISTE -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-left overflow-x-scroll">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nom</th>
                        <th class="px-6 py-4 font-semibold">Slug</th>
                        <th class="px-6 py-4 text-center font-semibold">Articles</th>
                        <th class="px-6 py-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($themes as $theme)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                <i class="fa-solid fa-tag"></i>
                            </span>
                            {{ $theme->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $theme->slug }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">{{ $theme->daily_contents_count }}</span>
                        </td>
                         <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.themes.destroy', $theme->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce thème ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-gray-200" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- FORMULAIRE -->
        <div>
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.05)] sticky top-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-renews-vert flex items-center justify-center mr-3 text-black text-sm">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    Nouveau Thème
                </h2>

                <form action="{{ route('admin.themes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div x-data="{ name: '', slug: '' }">
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Nom du thème</label>
                            <input type="text" name="name" x-model="name" @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-1">Slug (auto)</label>
                            <input type="text" name="slug" x-model="slug" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-500 focus:border-renews-vert focus:ring-renews-vert cursor-not-allowed" readonly>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Icône URL (Optionnel)</label>
                        <input type="text" name="icon_url" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert">
                    </div>

                    <button type="submit" class="w-full py-3 bg-renews-vert text-black font-black uppercase tracking-wide rounded-xl hover:bg-[#66e007] transition-all shadow-md mt-2">
                        Ajouter
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>