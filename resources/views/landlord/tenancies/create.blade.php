<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Rekod Penyewa Baharu</h2>
            <p class="mt-1 text-sm text-gray-500">Rumah akan ditandakan disewa secara automatik.</p>
        </div>
    </x-slot>

    <div class="w-full">
            <form method="POST" action="{{ route('landlord.x.tenancies.store') }}" class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                @csrf 
                @include('landlord.tenancies._form')
            </form>
        </div>
</x-landlord-layout>

