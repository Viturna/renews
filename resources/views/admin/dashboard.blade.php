<x-app-layout>
    <div class="min-h-screen bg-gray-100 pb-12">
        <div class="bg-renews-noir-impure text-white shadow-lg border-b border-renews-vert">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h2 class="font-bold text-2xl font-gaming uppercase tracking-widest text-renews-electric">
                    Admin Dashboard
                </h2>
                <a href="{{ route('dashboard') }}" class="text-sm hover:text-renews-vert underline">Retour au site</a>
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-renews-vert p-6">
                    <div class="text-gray-500 text-sm font-medium">Utilisateurs Total</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-blue-500 p-6">
                    <div class="text-gray-500 text-sm font-medium">Vidéos Publiées</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_contents'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 border-purple-500 p-6">
                    <div class="text-gray-500 text-sm font-medium">Thèmes Actifs</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['total_themes'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm rounded-lg border-l-4 {{ $stats['today_content'] == 'Oui' ? 'border-green-500' : 'border-red-500' }} p-6">
                    <div class="text-gray-500 text-sm font-medium">Contenu Aujourd'hui ?</div>
                    <div class="text-3xl font-bold {{ $stats['today_content'] == 'Oui' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $stats['today_content'] }}
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800">Calendrier Éditorial</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                                    <th class="py-3 px-6 text-left">Date</th>
                                    <th class="py-3 px-6 text-left">Titre</th>
                                    <th class="py-3 px-6 text-center">Thème</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                @foreach($contents as $content)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap font-bold">
                                        {{ \Carbon\Carbon::parse($content->publish_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        {{ Str::limit($content->title, 30) }}
                                        <a href="{{ $content->video_url }}" target="_blank" class="text-blue-500 ml-1 text-xs hover:underline">(Voir)</a>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="bg-gray-800 text-white py-1 px-3 rounded-full text-xs">
                                            {{ $content->theme->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <!-- LE BOUTON POUR GERER LE QUIZ -->
                                        <a href="{{ route('admin.quiz.manage', $content->id) }}" class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded text-xs font-bold hover:bg-blue-200 transition">
                                            Questions ({{ $content->questions()->count() }})
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">
                        {{ $contents->links() }}
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6 h-fit sticky top-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Ajouter un Contenu</h3>
                    <form action="{{ route('admin.contents.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase">Thème</label>
                            <select name="theme_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-renews-vert focus:ring focus:ring-renews-vert focus:ring-opacity-50">
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase">Date de publication</label>
                            <input type="date" name="publish_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase">Titre</label>
                            <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase">Lien YouTube</label>
                            <input type="url" name="video_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required placeholder="https://...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase">Description</label>
                            <textarea name="description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-renews-vert hover:bg-renews-electric text-black font-bold py-3 px-4 rounded transition shadow-md">
                            Publier
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Thèmes Existants</h3>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        @foreach($themes as $theme)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded border border-gray-100">
                            <div class="flex items-center">
                                <span class="text-xl mr-3 font-mono text-gray-400">{!! $theme->icon_url !!}</span>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $theme->name }}</p>
                                    <p class="text-xs text-gray-500">/{{ $theme->slug }}</p>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                                {{ $theme->daily_contents_count }} vidéos
                            </span>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="text-sm font-bold text-gray-600 mb-2">Nouveau Thème</h4>
                        <form action="{{ route('admin.themes.store') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="name" placeholder="Nom" class="w-1/3 rounded-md border-gray-300 shadow-sm text-sm" required>
                            <input type="text" name="slug" placeholder="Slug" class="w-1/3 rounded-md border-gray-300 shadow-sm text-sm" required>
                            <input type="text" name="icon_url" placeholder="Icone (class)" class="w-1/3 rounded-md border-gray-300 shadow-sm text-sm">
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded text-sm hover:bg-gray-700">OK</button>
                        </form>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Derniers Inscrits</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <tbody class="text-gray-600 text-sm">
                                @foreach($lastUsers as $user)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 font-bold">{{ $user->username }}</td>
                                    <td class="py-3 text-gray-500">{{ $user->email }}</td>
                                    <td class="py-3 text-right text-xs">
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="#" class="text-sm text-blue-500 hover:underline">Voir tous les utilisateurs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>