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
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold">ArkanStore</h1>
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-4">Welcome To Arkan Store</h2>
                    
                    @if($role == 'admin')
                        <!-- Admin Menu -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-6 rounded-lg text-white">
                                <h3 class="text-lg font-semibold">Status Produk</h3>
                                <p class="mt-2">Total produk: {{ $totalProducts }}</p>
                                <a href="{{ route('admin.products') }}" class="inline-block mt-4 bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                                    Lihat Produk
                                </a>
                            </div>
                            <div class="bg-gradient-to-r from-green-400 to-green-600 p-6 rounded-lg text-white">
                                <h3 class="text-lg font-semibold">Tambah Produk Baru</h3>
                                <p class="mt-2">Buat daftar produk baru</p>
                                <a href="{{ route('admin.products.create') }}" class="inline-block mt-4 bg-white text-green-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                                    Tambah Produk
                                </a>
                            </div>
                            <div class="bg-gradient-to-r from-purple-400 to-purple-600 p-6 rounded-lg text-white">
                                <h3 class="text-lg font-semibold">Profile Saya</h3>
                                <p class="mt-2">Nama: {{ Session::get('akun_name') }}</p>
                                <p>Email: {{ Session::get('akun_email') }}</p>
                            </div>
                        </div>
                    @else
                        <!-- Customer Menu -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="bg-gradient-to-r from-cyan-400 to-sky-500 p-6 rounded-lg text-white">
                                <h3 class="text-lg font-semibold">Beranda Produk</h3>
                                <p class="mt-2"> Produk Tersedia: {{ $totalProducts }}</p>
                                <a href="{{ route('products') }}" class="inline-block mt-4 bg-white text-cyan-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                                    Lihat Produk
                                </a>
                            </div>
                            <div class="bg-gradient-to-r from-orange-400 to-orange-600 p-6 rounded-lg text-white">
                                <h3 class="text-lg font-semibold">Keranjang Saya</h3>
                                <p class="mt-2">Lihat Keranjang Produkmu</p>
                                <a href="{{ route('cart') }}" class="inline-block mt-4 bg-white text-orange-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                                    Lihat Keranjang
                                </a>
                            </div>
                            <div class="bg-gradient-to-r from-purple-400 to-pink-500 p-6 rounded-lg text-white">
                                <h3 class="text-lg font-semibold">Profile Saya</h3>
                                <p class="mt-2">Nama: {{ Session::get('akun_name') }}</p>
                                <p>Email: {{ Session::get('akun_email') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>

</html>