<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Menu;
use Illuminate\Support\Facades\Hash;

class KantinSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create vendor users ──────────────────────────
        $user1 = User::firstOrCreate(
            ['email' => 'vendor1@kantin.com'],
            ['nama' => 'Warung Bu Ani', 'password' => Hash::make('password')]
        );
        $user2 = User::firstOrCreate(
            ['email' => 'vendor2@kantin.com'],
            ['nama' => 'Kedai Pak Budi', 'password' => Hash::make('password')]
        );
        $user3 = User::firstOrCreate(
            ['email' => 'vendor3@kantin.com'],
            ['nama' => 'Dapur Sehat', 'password' => Hash::make('password')]
        );

        // ── Create vendors ──────────────────────────────
        $v1 = Vendor::firstOrCreate(
            ['user_id' => $user1->id],
            ['nama_vendor' => 'Warung Bu Ani']
        );
        $v2 = Vendor::firstOrCreate(
            ['user_id' => $user2->id],
            ['nama_vendor' => 'Kedai Pak Budi']
        );
        $v3 = Vendor::firstOrCreate(
            ['user_id' => $user3->id],
            ['nama_vendor' => 'Dapur Sehat']
        );

        // ── Menu Warung Bu Ani ──────────────────────────
        $menus1 = [
            ['nama_menu' => 'Nasi Goreng Spesial', 'harga' => 15000],
            ['nama_menu' => 'Mie Goreng',          'harga' => 12000],
            ['nama_menu' => 'Nasi Ayam Geprek',     'harga' => 18000],
            ['nama_menu' => 'Nasi Rendang',         'harga' => 20000],
            ['nama_menu' => 'Es Teh Manis',         'harga' => 5000],
        ];
        foreach ($menus1 as $m) {
            Menu::firstOrCreate(
                ['nama_menu' => $m['nama_menu'], 'idvendor' => $v1->idvendor],
                ['harga' => $m['harga']]
            );
        }

        // ── Menu Kedai Pak Budi ─────────────────────────
        $menus2 = [
            ['nama_menu' => 'Bakso Urat',       'harga' => 15000],
            ['nama_menu' => 'Mie Ayam Pangsit', 'harga' => 13000],
            ['nama_menu' => 'Soto Ayam',        'harga' => 14000],
            ['nama_menu' => 'Es Jeruk',         'harga' => 5000],
            ['nama_menu' => 'Teh Tarik',        'harga' => 8000],
        ];
        foreach ($menus2 as $m) {
            Menu::firstOrCreate(
                ['nama_menu' => $m['nama_menu'], 'idvendor' => $v2->idvendor],
                ['harga' => $m['harga']]
            );
        }

        // ── Menu Dapur Sehat ────────────────────────────
        $menus3 = [
            ['nama_menu' => 'Salad Bowl',         'harga' => 22000],
            ['nama_menu' => 'Smoothie Bowl',      'harga' => 25000],
            ['nama_menu' => 'Wrap Ayam',           'harga' => 18000],
            ['nama_menu' => 'Jus Alpukat',         'harga' => 12000],
            ['nama_menu' => 'Oatmeal Bowl',        'harga' => 16000],
        ];
        foreach ($menus3 as $m) {
            Menu::firstOrCreate(
                ['nama_menu' => $m['nama_menu'], 'idvendor' => $v3->idvendor],
                ['harga' => $m['harga']]
            );
        }
    }
}
