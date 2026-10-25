<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Profil Saya</h2>
            <p class="mt-1 text-sm text-gray-500">Kemas kini maklumat akaun tuan rumah anda.</p>
        </div>
    </x-slot>

    <div class="w-full">
        @include('landlord.partials.nav')
        @include('account._profile-forms')
    </div>
</x-landlord-layout>
