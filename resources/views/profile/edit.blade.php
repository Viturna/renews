<x-app-layout>
    <div class="min-h-screen bg-white dark:bg-gray-900 font-sans">
        
        <form method="post" action="{{ route('profile.update') }}" class="h-full">
            @csrf
            @method('patch')

            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-gray-800">
                <a href="{{ route('profile.show') }}" class="text-black dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
                
                <h1 class="text-xl font-bold text-black dark:text-white">Edit Profile</h1>
                
                <button type="submit" class="text-blue-600 dark:text-blue-400 font-bold text-lg hover:opacity-80">
                    Save
                </button>
            </div>

            <div class="px-6 py-8">
                <div class="flex justify-center mb-10">
                    <div class="relative">
                        <img class="h-28 w-28 rounded-full object-cover border border-gray-100 dark:border-gray-800" 
                            src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=128" 
                            alt="{{ $user->name }}">
                        
                        <button type="button" class="absolute bottom-0 right-0 bg-black text-white p-2 rounded-full border-[3px] border-white dark:border-gray-900 flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-400 ml-1">Full Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-2xl border-none focus:ring-0 px-5 py-4 text-base"
                            placeholder="Jane Doe">
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-400 ml-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                            class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-2xl border-none focus:ring-0 px-5 py-4 text-base"
                            placeholder="jane.doe@gmail.com">
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-400 ml-1">Phone Number</label>
                        <input type="tel" disabled 
                            class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-2xl border-none focus:ring-0 px-5 py-4 text-base opacity-60"
                            value="+1 234 567 890">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-gray-400 ml-1">Location</label>
                        <input type="text" disabled 
                            class="w-full bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-2xl border-none focus:ring-0 px-5 py-4 text-base opacity-60"
                            value="New York, USA">
                    </div>

                </div>

                @if (session('status') === 'profile-updated')
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="fixed bottom-10 left-0 right-0 mx-auto w-max bg-black text-white px-6 py-3 rounded-full shadow-lg">
                        Profile Saved
                    </div>
                @endif
            </div>
        </form>
    </div>
</x-app-layout>