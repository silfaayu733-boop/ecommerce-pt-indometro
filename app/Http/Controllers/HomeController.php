<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = Kategori::all();
        $config = Config::first();

        $query = Produk::with('kategori');

        if ($request->filled('kategori')) {
            $query->where('id_kategori', $request->kategori);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $produks = $query->orderBy('id', 'asc')->paginate(36)->withQueryString();

        return view('home.index', compact('produks', 'kategoriList', 'config'));
    }

    public function detail(int $id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        $terkait = Produk::where('id_kategori', $produk->id_kategori)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();
        $config = Config::first();

        return view('home.detail', compact('produk', 'terkait', 'config'));
    }

    public function contact()
    {
        $config = Config::first();
        return view('home.contact', compact('config'));
    }
}

