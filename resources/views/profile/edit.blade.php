<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
                Modifier mon profil
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                Gérez vos informations personnelles
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-2xl sm:px-10 border border-gray-100 dark:border-gray-700">
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="flex flex-col items-center justify-center mb-6">
                        <div class="relative group cursor-pointer">
                            <img class="h-28 w-28 rounded-full object-cover border-4 border-white dark:border-gray-700 shadow-lg" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->username ?? 'User') }}&background=0D8ABC&color=fff&size=128" 
                                 alt="{{ $user->username }}">
                            
                            <div class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full border-2 border-white dark:border-gray-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-medium text-blue-600 hover:text-blue-500 cursor-pointer">Changer la photo</p>
                    </div>

                    <div>
                        <x-input-label for="username" :value="__('Nom d\'utilisateur')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <x-text-input id="username" name="username" type="text" 
                                class="block w-full pl-4 pr-10 py-3 sm:text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                                :value="old('username', $user->username)" 
                                required autofocus autocomplete="username" 
                                placeholder="Votre pseudo" />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('username')" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Adresse Email')" class="text-gray-700 dark:text-gray-300 font-semibold" />
                        <div class="mt-1">
                            <x-text-input id="email" name="email" type="email" 
                                class="block w-full pl-4 pr-10 py-3 sm:text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                                :value="old('email', $user->email)" 
                                required autocomplete="email" 
                                placeholder="exemple@email.com" />
                        </div>
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <a href="{{ route('dashboard') }}" class="w-1/2 flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                            Annuler
                        </a>

                        <x-primary-button class="w-1/2 flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                            {{ __('Sauvegarder') }}
                        </x-primary-button>
                    </div>

                    @if (session('status') === 'profile-updated')
                        <div x-data="{ show: true }" x-show="show" x-transition 
                             x-init="setTimeout(() => show = false, 3000)"
                             class="rounded-md bg-green-50 p-4 mt-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">Modifications enregistrées avec succès.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>