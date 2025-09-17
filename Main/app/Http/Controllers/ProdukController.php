<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProdukController extends Controller
{
    // Show all products and the form
    public function index()
    {
        $produks = Produk::all(); // Ambil semua produk dari database
        return view('welcome', ['produks' => $produks]);
    }

    // Store a new product
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Maks 2MB
        ]);

        // Buat folder images/products di public kalau belum ada
        $uploadPath = public_path('images/products');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        // Handle upload gambar - simpan langsung ke public
        $imageName = time() . '.' . $request->file('gambar')->getClientOriginalExtension();
        $request->file('gambar')->move($uploadPath, $imageName);

        // Simpan produk baru
        Produk::create([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'img_patch' => 'images/products/' . $imageName, // Path relatif untuk asset()
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    // Show the edit form for a product
    public function edit($id)
    {
        $produk = Produk::find($id); // Cari produk berdasarkan ID
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }
        $produks = Produk::all(); // Ambil semua produk untuk daftar
        return view('welcome', ['produks' => $produks, 'editProduk' => $produk]);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $produk = Produk::find($id); // Cari produk berdasarkan ID
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        // Validasi input
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Update data produk
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->harga = $request->harga;

        // Handle upload gambar baru kalau ada
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama kalau ada
            $oldImagePath = public_path($produk->img_patch);
            if (File::exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            // Buat folder kalau belum ada
            $uploadPath = public_path('images/products');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            // Simpan gambar baru
            $imageName = time() . '.' . $request->file('gambar')->getClientOriginalExtension();
            $request->file('gambar')->move($uploadPath, $imageName);
            $produk->img_patch = 'images/products/' . $imageName;
        }

        $produk->save(); // Simpan perubahan

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // Delete a product
    public function destroy($id)
    {
        $produk = Produk::find($id); // Cari produk berdasarkan ID
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan!');
        }

        // Hapus gambar kalau ada
        $imagePath = public_path($produk->img_patch);
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $produk->delete(); // Hapus produk

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus!');
    }
}
