<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Inventaris Sederhana — Tampilan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">
    <header class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-6 py-6 flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Sistem Inventaris Sederhana</h1>
            <p class="text-sm text-gray-500">Tampilan: HTML + Tailwind</p>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-6 py-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <section class="lg:col-span-1 bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-medium mb-4">{{ isset($editProduk) ? 'Edit Produk' : 'Tambah Produk' }}</h2>
            <form class="space-y-4" method="POST"
                action="{{ isset($editProduk) ? route('produk.update', $editProduk->id) : route('produk.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($editProduk))
                    @method('POST')
                @endif
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Produk <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="nama_produk" required
                        value="{{ old('nama_produk', isset($editProduk) ? $editProduk->nama_produk : '') }}"
                        class="w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-indigo-200 p-2"
                        placeholder="Contoh: Kursi Kantor" />
                    @error('nama_produk')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="w-full rounded-lg border-gray-200 shadow-sm p-2"
                        placeholder="Deskripsi singkat...">{{ old('deskripsi', isset($editProduk) ? $editProduk->deskripsi : '') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" min="0" required
                        value="{{ old('harga', isset($editProduk) ? $editProduk->harga : '') }}"
                        class="w-full rounded-lg border-gray-200 shadow-sm p-2" placeholder="100000" />
                    @error('harga')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Gambar Produk <span
                            class="text-red-500">{{ isset($editProduk) ? '' : '*' }}</span></label>
                    <input type="file" name="gambar" accept="image/png,image/jpeg,image/jpg,image/gif"
                        {{ isset($editProduk) ? '' : 'required' }} class="w-full" />
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, GIF. Maks 2MB.</p>
                    @if (isset($editProduk) && $editProduk->img_patch)
                        @if (file_exists(public_path($editProduk->img_patch)))
                            <img src="{{ asset($editProduk->img_patch) }}" alt="{{ $editProduk->nama_produk }}"
                                class="h-20 mt-2" />
                        @else
                            <p class="text-red-500 text-xs mt-1">Gambar tidak ditemukan</p>
                        @endif
                    @endif
                    @error('gambar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pt-2">
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-xl bg-indigo-600 text-white px-4 py-2 text-sm font-medium hover:bg-indigo-700">
                        {{ isset($editProduk) ? 'Update Produk' : 'Simpan Produk' }}
                    </button>
                    @if (isset($editProduk))
                        <a href="{{ route('produk.index') }}"
                            class="block text-center mt-2 text-sm text-indigo-600 hover:underline">Batal Edit</a>
                    @endif
                </div>
            </form>
        </section>

        <section class="lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-lg font-medium">Daftar Produk</h2>
                <div class="text-sm text-gray-500">Menampilkan {{ count($produks) }} produk</div>
            </div>

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($produks as $produk)
                    <article class="bg-white rounded-2xl shadow overflow-hidden">
                        <div class="h-44 bg-gray-100 flex items-center justify-center overflow-hidden">
                            @if (file_exists(public_path($produk->img_patch)))
                                <img src="{{ asset($produk->img_patch) }}" alt="{{ $produk->nama_produk }}"
                                    class="object-cover w-full h-full" />
                            @else
                                <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-500">
                                    Tidak Ada Gambar</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold">{{ $produk->nama_produk }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $produk->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="text-indigo-600 font-medium">Rp
                                    {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('produk.edit', $produk->id) }}"
                                        class="text-sm px-3 py-1 rounded-md border border-indigo-100 text-indigo-700">Edit</a>
                                    <a href="{{ route('produk.destroy', $produk->id) }}"
                                        class="text-sm px-3 py-1 rounded-md bg-red-50 text-red-600 border border-red-100"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="text-gray-500">Belum ada produk.</p>
                @endforelse
            </div>
        </section>
    </main>

    <footer class="mt-12 py-6">
        <div class="max-w-6xl mx-auto text-center text-xs text-gray-400">Generated layout — HTML + Tailwind</div>
    </footer>
</body>

</html>
