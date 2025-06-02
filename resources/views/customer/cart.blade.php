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
                        <a href="{{ route('products') }}" class="text-blue-600 hover:text-blue-800">
                            ← Kembali Belanja
                        </a>
                        <h1 class="text-xl font-semibold">Keranjang Belanja Saya</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-800">
                            Dashboard
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
                    <h2 class="text-2xl font-semibold mb-6">Keranjang Belanja</h2>

                    @if($carts->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div class="lg:col-span-2">
                                <div class="space-y-4">
                                    @foreach($carts as $cart)
                                        <div class="bg-gray-50 rounded-lg p-4 flex items-center space-x-4">
                                            <div class="flex-shrink-0">
                                                @if($cart->product->image)
                                                    <img src="{{ asset('storage/' . $cart->product->image) }}" 
                                                         alt="{{ $cart->product->name }}" 
                                                         class="h-20 w-20 object-cover rounded">
                                                @else
                                                    <div class="h-20 w-20 bg-gray-200 rounded flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No Image</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex-1">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    {{ $cart->product->name }}
                                                </h3>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ Str::limit($cart->product->description, 60) }}
                                                </p>
                                                <div class="mt-2">
                                                    <span class="text-lg font-semibold text-green-600">
                                                        Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 ml-2">
                                                        (Stock: {{ $cart->product->stock }})
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                <form method="POST" action="{{ route('cart.update', $cart) }}" class="flex items-center space-x-2">
                                                    @csrf
                                                    @method('PUT')
                                                    <label for="quantity_{{ $cart->id }}" class="text-sm text-gray-700">
                                                        Qty:
                                                    </label>
                                                    <input type="number" 
                                                           id="quantity_{{ $cart->id }}" 
                                                           name="quantity" 
                                                           value="{{ $cart->quantity }}" 
                                                           min="1" 
                                                           max="{{ $cart->product->stock }}" 
                                                           class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <button type="submit" 
                                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                                        Update
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('cart.destroy', $cart) }}" 
                                                      onsubmit="return confirm('Are you sure you want to remove this item?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition duration-200">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>

                                            <div class="text-right">
                                                <div class="text-lg font-semibold text-gray-900">
                                                    Rp {{ number_format($cart->total_price, 0, ',', '.') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $cart->quantity }} × Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 rounded-lg p-6 sticky top-6">
                                    <h3 class="text-lg font-semibold mb-4">Ringkasan Pesanan</h3>
                                    
                                    <div class="space-y-3">
                                        @foreach($carts as $cart)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">{{ Str::limit($cart->product->name, 20) }} ({{ $cart->quantity }}x)</span>
                                                <span class="text-gray-900">Rp {{ number_format($cart->total_price, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="border-t pt-3 mt-4">
                                        <div class="flex justify-between">
                                            <span class="text-sm text-gray-600">Subtotal</span>
                                            <span class="text-sm text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="flex justify-between mt-2">
                                            <span class="text-sm text-gray-600">Shipping</span>
                                            <span class="text-sm text-gray-900">Free</span>
                                        </div>
                                        <div class="flex justify-between mt-2 pt-2 border-t">
                                            <span class="text-lg font-semibold text-gray-900">Total</span>
                                            <span class="text-lg font-semibold text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <button class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200">
                                            Lanjut ke Pembayaran
                                        </button>
                                        <p class="text-xs text-gray-500 text-center mt-2">
                                            * Fitur Payment Gateway Belum Tersedia
                                        </p>
                                    </div>

                                    <div class="mt-4">
                                        <a href="{{ route('products') }}" 
                                           class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200 text-center block">
                                            Kembali Belanja
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">🛒</div>
                            <h3 class="text-xl font-medium text-gray-900 mb-2">Keranjang Anda Kosong</h3>
                            <p class="text-gray-500 mb-6">Mulai Belanja Dan Masukan Ke Keranjang.</p>
                            <a href="{{ route('products') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition duration-200">
                                Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>