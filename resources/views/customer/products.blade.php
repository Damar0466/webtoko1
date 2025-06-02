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
                        <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                            ← Kembali Ke Dashboard
                        </a>
                        <h1 class="text-xl font-semibold">Produk Toko</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('cart') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition duration-200">
                            🛒 Keranjang Saya
                        </a>
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

        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

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
                <div class="p-6 bg-white">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Produk Yang Tersedia</h2>
                        <div class="text-sm text-gray-600">
                            {{ $products->total() }} produk tersedia
                        </div>
                    </div>

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-t-lg bg-gray-200">
                                        @if($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="h-48 w-full object-cover object-center group-hover:opacity-75">
                                        @else
                                            <div class="h-48 w-full bg-gray-300 flex items-center justify-center">
                                                <span class="text-gray-500">No Image</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                            {{ Str::limit($product->description, 80) }}
                                        </p>
                                        
                                        <div class="flex items-center justify-between mb-3">
                                            <span class="text-lg font-bold text-green-600">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-sm px-2 py-1 rounded-full
                                                {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                Stock: {{ $product->stock }}
                                            </span>
                                        </div>

                                        @if($product->stock > 0)
                                            <form method="POST" action="{{ route('cart.add') }}" class="space-y-3">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                
                                                <div class="flex items-center space-x-2">
                                                    <label for="quantity_{{ $product->id }}" class="text-sm font-medium text-gray-700">
                                                        Qty:
                                                    </label>
                                                    <input type="number" 
                                                           id="quantity_{{ $product->id }}" 
                                                           name="quantity" 
                                                           value="1" 
                                                           min="1" 
                                                           max="{{ $product->stock }}" 
                                                           class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                </div>
                                                
                                                <button type="submit" 
                                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-200">
                                                    Masukan Keranjang
                                                </button>
                                            </form>
                                        @else
                                            <button disabled 
                                                    class="w-full bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded cursor-not-allowed">
                                                Stock Habis
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-xl mb-4">Tidak Ada Produk Yang Tersedia</div>
                            <p class="text-gray-400">Silakan periksa kembali nanti untuk produk baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>