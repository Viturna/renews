<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Publicités') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Ajouter une nouvelle publicité</h3>
                    <form method="POST" action="{{ route('admin.ads.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">Titre</label>
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>
                            <div>
                                <label for="link_url" class="block text-sm font-medium text-gray-700">Lien de redirection</label>
                                <x-text-input id="link_url" name="link_url" type="url" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('link_url')" class="mt-2" />
                            </div>
                            <div>
                                <label for="display_interval" class="block text-sm font-medium text-gray-700">Intervalle (après X articles)</label>
                                <x-text-input id="display_interval" name="display_interval" type="number" min="1" class="mt-1 block w-full" required value="{{ old('display_interval', 5) }}" />
                                <x-input-error :messages="$errors->get('display_interval')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="image_url" class="block text-sm font-medium text-gray-700">URL Image/Banner (Optionnel)</label>
                            <x-text-input id="image_url" name="image_url" type="url" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                        </div>

                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Contenu (Code HTML/Texte)</label>
                            <textarea id="content" name="content" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>
                        
                        <div class="flex items-center">
                            <input id="is_active" name="is_active" type="checkbox" checked value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">Est active</label>
                        </div>

                        <x-primary-button>{{ __('Ajouter la Publicité') }}</x-primary-button>
                    </form>

                    <h3 class="text-lg font-bold mt-8 mb-4 border-b pb-2">Publicités Actuelles ({{ $ads->count() }})</h3>
                    <div class="space-y-6">
                        @forelse ($ads as $ad)
                            <div class="border p-4 rounded-lg shadow-sm {{ $ad->is_active ? 'border-green-400 bg-green-50' : 'border-gray-300 bg-gray-50' }}">
                                <form method="POST" action="{{ route('admin.ads.update', $ad) }}" class="space-y-3">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                        <div class="col-span-2">
                                            <label for="title_{{ $ad->id }}" class="block text-sm font-medium text-gray-700">Titre</label>
                                            <x-text-input id="title_{{ $ad->id }}" name="title" type="text" class="mt-1 block w-full" required value="{{ $ad->title }}" />
                                        </div>
                                        <div>
                                            <label for="interval_{{ $ad->id }}" class="block text-sm font-medium text-gray-700">Intervalle</label>
                                            <x-text-input id="interval_{{ $ad->id }}" name="display_interval" type="number" min="1" class="mt-1 block w-full" required value="{{ $ad->display_interval }}" />
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="flex items-center">
                                                <input id="active_{{ $ad->id }}" name="is_active" type="checkbox" value="1" {{ $ad->is_active ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                <label for="active_{{ $ad->id }}" class="ml-2 block text-sm text-gray-900">Actif</label>
                                            </div>
                                            <x-primary-button class="ml-auto">{{ __('MAJ') }}</x-primary-button>
                                        </div>
                                    </div>

                                    <label for="link_{{ $ad->id }}" class="block text-sm font-medium text-gray-700">Lien</label>
                                    <x-text-input id="link_{{ $ad->id }}" name="link_url" type="url" class="mt-1 block w-full" required value="{{ $ad->link_url }}" />

                                    <div class="flex justify-end">
                                        <form method="POST" action="{{ route('admin.ads.destroy', $ad) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publicité ?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button type="submit">{{ __('Supprimer') }}</x-danger-button>
                                        </form>
                                    </div>
                                </form>
                            </div>
                        @empty
                            <p class="text-gray-500">Aucune publicité ajoutée pour l'instant.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>