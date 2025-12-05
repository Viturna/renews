<x-app-layout>
    <div class="py-12 px-4 sm:px-6 lg:px-8 w-full max-w-md mx-auto">

        <div>
            
            @include('profile.partials.update-profile-information-form')


            {{-- 
            <div class="mt-8 border-t border-gray-700 pt-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="mt-8 border-t border-gray-700 pt-6">
                @include('profile.partials.delete-user-form')
            </div>
            --}}

        </div>
    </div>
</x-app-layout>