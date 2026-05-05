<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    /**
     * Dapatkan vendor milik user yang login
     */
    private function getVendor()
    {
        return Vendor::where('user_id', Auth::id())->first();
    }

    /**
     * Dashboard Vendor
     */
    public function dashboard()
    {
        $vendor = $this->getVendor();

        if (!$vendor) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $totalMenu = Menu::where('idvendor', $vendor->idvendor)->count();

        // Pesanan lunas yang mengandung menu dari vendor ini
        $pesananLunas = Pesanan::where('status_bayar', 1)
            ->whereHas('detailPesanans.menu', function ($q) use ($vendor) {
                $q->where('idvendor', $vendor->idvendor);
            })
            ->count();

        $totalPendapatan = Pesanan::where('status_bayar', 1)
            ->whereHas('detailPesanans.menu', function ($q) use ($vendor) {
                $q->where('idvendor', $vendor->idvendor);
            })
            ->withSum(['detailPesanans' => function ($q) use ($vendor) {
                $q->whereHas('menu', fn($qq) => $qq->where('idvendor', $vendor->idvendor));
            }], 'subtotal')
            ->get()
            ->sum('detail_pesanans_sum_subtotal');

        return view('vendor.dashboard', compact('vendor', 'totalMenu', 'pesananLunas', 'totalPendapatan'));
    }

    // ═══════════════════════════════════════════════
    // CRUD Menu
    // ═══════════════════════════════════════════════

    public function index()
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $menus = Menu::where('idvendor', $vendor->idvendor)->latest('idmenu')->get();
        return view('vendor.menu-index', compact('vendor', 'menus'));
    }

    public function create()
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        return view('vendor.menu-form', [
            'vendor' => $vendor,
            'menu'   => null,
        ]);
    }

    public function store(Request $request)
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $request->validate([
            'nama_menu'    => 'required|string|max:255',
            'harga'        => 'required|integer|min:1',
            'path_gambar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
            'idvendor'  => $vendor->idvendor,
        ];

        if ($request->hasFile('path_gambar')) {
            $file = $request->file('path_gambar');
            
            // Debug: cek file valid
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'File upload gagal: ' . $file->getErrorMessage());
            }
            
            // Generate nama unik
            $extension = $file->getClientOriginalExtension();
            $filename = $vendor->idvendor . '-' . time() . '-' . \Illuminate\Support\Str::slug($request->nama_menu) . '.' . $extension;
            
            // Store file - pastikan folder exists
            $path = $file->storeAs('menu-images', $filename, 'public');
            
            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file ke storage');
            }
            
            $data['path_gambar'] = $filename;
        }

        Menu::create($data);

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $menu = Menu::where('idmenu', $id)->where('idvendor', $vendor->idvendor)->firstOrFail();

        return view('vendor.menu-form', compact('vendor', 'menu'));
    }

    public function update(Request $request, $id)
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $menu = Menu::where('idmenu', $id)->where('idvendor', $vendor->idvendor)->firstOrFail();

        $request->validate([
            'nama_menu'    => 'required|string|max:255',
            'harga'        => 'required|integer|min:1',
            'path_gambar'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $menu->nama_menu = $request->nama_menu;
        $menu->harga     = $request->harga;

        if ($request->hasFile('path_gambar')) {
            // Hapus gambar lama
            if ($menu->path_gambar) {
                Storage::disk('public')->delete('menu-images/' . $menu->path_gambar);
            }
            
            $file = $request->file('path_gambar');
            
            // Debug: cek file valid
            if (!$file->isValid()) {
                return redirect()->back()->with('error', 'File upload gagal: ' . $file->getErrorMessage());
            }
            
            // Generate nama unik
            $extension = $file->getClientOriginalExtension();
            $filename = $vendor->idvendor . '-' . time() . '-' . \Illuminate\Support\Str::slug($request->nama_menu) . '.' . $extension;
            
            // Store file
            $path = $file->storeAs('menu-images', $filename, 'public');
            
            if (!$path) {
                return redirect()->back()->with('error', 'Gagal menyimpan file ke storage');
            }
            
            $menu->path_gambar = $filename;
        }

        $menu->save();

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $menu = Menu::where('idmenu', $id)->where('idvendor', $vendor->idvendor)->firstOrFail();

        if ($menu->path_gambar) {
            Storage::disk('public')->delete('menu-images/' . $menu->path_gambar);
        }

        $menu->delete();

        return redirect()->route('vendor.menu.index')
            ->with('success', 'Menu berhasil dihapus!');
    }

    // ═══════════════════════════════════════════════
    // Pesanan Lunas
    // ═══════════════════════════════════════════════

    public function pesananLunas()
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        $pesanans = Pesanan::where('status_bayar', 1)
            ->whereHas('detailPesanans.menu', function ($q) use ($vendor) {
                $q->where('idvendor', $vendor->idvendor);
            })
            ->with(['detailPesanans' => function ($q) use ($vendor) {
                $q->whereHas('menu', fn($qq) => $qq->where('idvendor', $vendor->idvendor));
                $q->with('menu');
            }])
            ->orderBy('timestamp', 'desc')
            ->paginate(15);

        return view('vendor.pesanan-lunas', compact('vendor', 'pesanans'));
    }

    /**
     * Halaman scan QR Code untuk vendor
     */
    public function scanQr()
    {
        $vendor = $this->getVendor();
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai vendor.');
        }

        return view('vendor.scan-qr', compact('vendor'));
    }
}
