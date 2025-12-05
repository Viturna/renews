<x-admin-layout>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Contenus</h1>
            <p class="text-gray-500">Planifier les actualités et quiz</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ editingContent: null }">
        
        <div class="lg:col-span-2 space-y-4">
            @foreach($contents as $content)
            <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm flex flex-col sm:flex-row gap-5 hover:shadow-md transition-all group relative">
                <div class="flex flex-col items-center justify-center bg-gray-50 rounded-lg p-3 min-w-[70px] text-center border border-gray-200">
                    <span class="text-2xl font-black text-gray-800 leading-none">{{ \Carbon\Carbon::parse($content->publish_date)->format('d') }}</span>
                    <span class="text-xs font-bold text-gray-500 uppercase">{{ \Carbon\Carbon::parse($content->publish_date)->format('M') }}</span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2 py-0.5 bg-gray-100 text-xs font-bold rounded text-gray-600 uppercase">{{ $content->theme->name }}</span>
                        @if($content->questions->count() > 0)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Quiz
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-bold rounded">
                                Sans Quiz
                            </span>
                        @endif
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 truncate group-hover:text-black transition-colors">{{ $content->title }}</h3>
                    <a href="{{ $content->video_url }}" target="_blank" class="text-sm text-blue-500 hover:underline truncate block">
                        {{ $content->video_url }}
                    </a>
                </div>

                <div class="flex sm:flex-col justify-center gap-2">
                    <a href="{{ route('admin.quiz.manage', $content->id) }}" class="px-4 py-2 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition-all text-center text-sm shadow-sm flex items-center justify-center gap-2">
                        <i class="fa-solid fa-gamepad"></i> <span class="hidden sm:inline">Quiz</span>
                    </a>
                    
                    <div class="flex gap-2 justify-center">
                        <button 
                            @click="editingContent = {{ json_encode([
                                'id' => $content->id,
                                'title' => $content->title,
                                'video_url' => $content->video_url,
                                'description' => $content->description,
                                'theme_id' => $content->theme_id,
                                'publish_date' => $content->publish_date->format('Y-m-d')
                            ]) }}"
                            class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors border border-gray-200" title="Modifier">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <form action="{{ route('admin.contents.destroy', $content->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce contenu ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-gray-200" title="Supprimer">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="mt-4">
                {{ $contents->links() }}
            </div>

            <div x-show="editingContent" style="display: none;" 
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                x-transition.opacity>
                
                <div @click.away="editingContent = null" class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all" x-transition.scale>
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Modifier le contenu</h3>
                        <button @click="editingContent = null" class="text-gray-400 hover:text-gray-600">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>

                    <form :action="'/admin/contents/' + editingContent?.id" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Titre</label>
                            <input type="text" name="title" x-model="editingContent.title" 
                                class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Date</label>
                                <input type="date" name="publish_date" x-model="editingContent.publish_date" 
                                    class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Thème</label>
                                <select name="theme_id" x-model="editingContent.theme_id" 
                                    class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert">
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">URL Vidéo</label>
                            <input type="url" name="video_url" x-model="editingContent.video_url" 
                                class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                            <textarea name="description" x-model="editingContent.description" rows="3" 
                                class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert"></textarea>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="editingContent = null" class="flex-1 px-4 py-3 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit" class="flex-1 px-4 py-3 bg-renews-vert text-black font-black uppercase tracking-wide rounded-xl hover:brightness-110 shadow-md">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-[0_4px_20px_rgba(0,0,0,0.05)] sticky top-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-renews-vert flex items-center justify-center mr-3 text-black text-sm">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    Nouveau Contenu
                </h2>

                <form action="{{ route('admin.contents.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Titre</label>
                        <input type="text" name="title" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert focus:bg-white transition-colors" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Date</label>
                            <input type="date" name="publish_date" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Thème</label>
                            <select name="theme_id" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert">
                                @foreach($themes as $theme)
                                    <option value="{{ $theme->id }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">URL Vidéo</label>
                        <input type="url" name="video_url" placeholder="https://youtube.com/..." class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-gray-50 border-gray-200 rounded-lg text-gray-900 focus:border-renews-vert focus:ring-renews-vert"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 bg-renews-vert text-black font-black uppercase tracking-wide rounded-xl hover:bg-[#66e007] transition-all shadow-md mt-2">
                        Planifier
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-admin-layout>