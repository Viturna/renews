<section>
    <header class="hidden">
        <h2 class="text-lg font-medium text-white">
            {{ __('Informations du Profil') }}
        </h2>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="flex flex-col items-center justify-center mb-6">
            <div class="relative group cursor-pointer w-56 h-56">
                <div class="absolute inset-0 rounded-full border-4 border-renews-vert z-10"></div>

                <img class="absolute inset-0 w-full h-full rounded-full object-cover p-3 z-20" 
                    src="{{ asset('images/profil.png') }}" 
                    alt="{{ $user->username ?? 'Profil' }}">
              
            </div>
        </div>

        <div>
            <label for="email" class="block text-white font-bold mb-2 text-lg pl-1">Email</label>

            <x-text-input id="email" name="email" type="text" 
                class="w-full rounded-xl border-none bg-white py-3.5 px-4 text-renews-noir placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow" 
                :value="old('email', $user->email ?? '')" 
                required autofocus autocomplete="given-name" 
                placeholder="example@gmail.com" />
            <x-input-error class="mt-2 text-red-400 font-bold text-sm" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="username" class="block text-white font-bold mb-2 text-lg pl-1">Nom d'utilisateur</label>
            
            <x-text-input id="username" name="username" type="text" 
                class="w-full rounded-xl border-none bg-white py-3.5 px-4 text-renews-noir placeholder-gray-400 focus:ring-4 focus:ring-renews-vert/50 transition-shadow" 
                :value="old('username', $user->username)" 
                required autocomplete="username" 
                placeholder="thomas.riquier" />
            <x-input-error class="mt-2 text-red-400 font-bold text-sm" :messages="$errors->get('username')" />
        </div>
        
        <div class="pt-6">
             <button type="submit" 
                    class="w-full bg-renews-vert hover:bg-renews-fonce text-white font-bold py-4 rounded-xl transition-all duration-300 hover:-translate-y-1 text-lg">
                {{ __('Valider') }}
            </button>
        </div>
        
        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition 
                 x-init="setTimeout(() => show = false, 3000)"
                 class="rounded-md bg-green-900/50 p-4 mt-4 text-green-300">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="ml-3 text-sm font-medium">Modifications enregistrées avec succès.</p>
                </div>
            </div>
        @endif
    </form>
</section>