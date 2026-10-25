<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Rumah</h2>
            <p class="mt-1 text-sm text-gray-500">Iklan anda akan terus kelihatan kepada pelajar.</p>
        </div>
    </x-slot>

    <div class="w-full">
            @include('landlord.partials.nav')
            <form method="POST" action="{{ route('landlord.x.houses.store') }}" enctype="multipart/form-data">
                @csrf 
                @include('landlord.houses._form')
            </form>
        </div>
</x-landlord-layout>
