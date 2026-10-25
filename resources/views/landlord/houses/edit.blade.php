<x-landlord-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Rumah</h2>
            <p class="mt-1 text-sm text-gray-500">Kemas kini maklumat, gambar atau harga iklan anda.</p>
        </div>
    </x-slot>

    <div class="w-full">
            @include('landlord.partials.nav')
            <form method="POST" action="{{ route('landlord.x.houses.update', $house) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('landlord.houses._form')
            </form>
        </div>
</x-landlord-layout>
