<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.products') }}" class="text-blue-600 hover:text-blue-800">
                            ← Kembali Ke Produk
                        </a>
                        <h1 class="text-xl font-semibold">Edit Produk</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-700">Hi, Welcome {{ Session::get('akun_name') }}!</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition duration-200">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-6">Edit Produk: {{ $product->name }}</h2>

                    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Produk *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukan Nama Produk">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi *
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4" 
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Masukan Deskripsi Produk">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                    Harga (Rp) *
                                </label>
                                <input type="number" 
                                       id="price" 
                                       name="price" 
                                       value="{{ old('price', $product->price) }}" 
                                       min="0" 
                                       step="0.01" 
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Contoh 10000">
                            </div>

                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                                    Stok Barang *
                                </label>
                                <input type="number" 
                                       id="stock" 
                                       name="stock" 
                                       value="{{ old('stock', $product->stock) }}" 
                                       min="0" 
                                       required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0">
                            </div>
                        </div>

                        @if($product->image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Saat Ini
                                </label>
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="max-w-xs h-48 object-cover rounded border">
                            </div>
                        @endif

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $product->image ? 'Ubah Gambar Produk' : 'Add Product Image' }}
                            </label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">
                                Format Berupa: JPEG, PNG, JPG, GIF. Max size: 2MB
                                @if($product->image)
                                    <br>Biarkan kosong untuk mempertahankan gambar saat ini.
                                @endif
                            </p>
                        </div>

                        <div id="imagePreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Preview Gambar Yang Baru
                            </label>
                            <img id="preview" src="" alt="Preview" class="max-w-xs h-48 object-cover rounded border">
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Informasi Produk</h3>
                        </div>

                        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('admin.products') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batalkan
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Perbaharui Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').classList.add('hidden');
            }
        });
    </script>
</body>

</html>