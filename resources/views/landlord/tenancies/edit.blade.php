<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Sewaan</h2>
            <p class="mt-1 text-sm text-gray-500">Kemas kini maklumat penyewa atau tempoh sewa.</p>
        </div>
    </x-slot>

    <div class="w-full">
            @include('landlord.partials.nav')
            <form method="POST" action="{{ route('landlord.x.tenancies.update', $tenancy) }}" class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                @csrf @method('PUT')
                @include('landlord.tenancies._form')
            </form>
        </div>
</x-landlord-layout>
