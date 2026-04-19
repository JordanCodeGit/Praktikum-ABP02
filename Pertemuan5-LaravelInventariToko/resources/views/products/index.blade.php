<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inventari Toko Pak Cokomi & Mas Wowo
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('products.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700">
            + Tambah Produk
        </a>

        <table class="w-full border-collapse border border-gray-300 mt-4 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-gray-300 p-2">No</th>
                    <th class="border border-gray-300 p-2">Nama Produk</th>
                    <th class="border border-gray-300 p-2">Kategori</th>
                    <th class="border border-gray-300 p-2">Stok</th>
                    <th class="border border-gray-300 p-2">Harga</th>
                    <th class="border border-gray-300 p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $i => $product)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 p-2 text-center">
                        {{ $products->firstItem() + $i }}
                    </td>
                    <td class="border border-gray-300 p-2">{{ $product->name }}</td>
                    <td class="border border-gray-300 p-2">{{ $product->category }}</td>
                    <td class="border border-gray-300 p-2 text-center">{{ $product->stock }}</td>
                    <td class="border border-gray-300 p-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="border border-gray-300 p-2 text-center space-x-1">
                        <a href="{{ route('products.edit', $product) }}"
                           class="bg-yellow-400 hover:bg-yellow-500 px-3 py-1 rounded text-sm">
                            Edit
                        </a>
                        <button onclick="openModal({{ $product->id }})"
                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal"
         class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
         style="display:none;">
        <div class="bg-white p-6 rounded shadow-lg max-w-sm mx-auto mt-40">
            <p class="mb-4 text-lg font-semibold text-gray-800">
                Yakin ingin menghapus produk ini?
            </p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded mr-2 hover:bg-red-700">
                    Ya, Hapus
                </button>
                <button type="button" onclick="closeModal()"
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Batal
                </button>
            </form>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById('deleteForm').action = '/products/' + id;
            document.getElementById('deleteModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</x-app-layout>
