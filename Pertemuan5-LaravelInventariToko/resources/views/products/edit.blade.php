<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Produk
        </h2>
    </x-slot>

    <div class="py-6 max-w-xl mx-auto px-4">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="name"
                       class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                       value="{{ old('name', $product->name) }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category"
                       class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                       value="{{ old('category', $product->category) }}">
                @error('category')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Stok</label>
                <input type="number" name="stock"
                       class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                       value="{{ old('stock', $product->stock) }}">
                @error('stock')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price"
                       class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                       value="{{ old('price', $product->price) }}">
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-yellow-400">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="bg-yellow-500 text-white px-5 py-2 rounded hover:bg-yellow-600">
                    Update
                </button>
                <a href="{{ route('products.index') }}" class="text-gray-600 hover:underline">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
