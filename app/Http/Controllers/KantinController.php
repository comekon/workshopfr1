<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class KantinController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = config('midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.is_3ds');
    }

    /**
     * Halaman utama kantin — customer (tanpa login)
     */
    public function index()
    {
        $vendors = Vendor::withCount('menus')->get();
        return view('kantin.index', compact('vendors'));
    }

    /**
     * API: Ambil menu berdasarkan vendor (select berjenjang)
     */
    public function getMenuByVendor($vendorId)
    {
        $menus = Menu::where('idvendor', $vendorId)->get();

        return response()->json([
            'status' => 'success',
            'data'   => $menus->map(function ($m) {
                return [
                    'idmenu'      => $m->idmenu,
                    'nama_menu'   => $m->nama_menu,
                    'harga'       => $m->harga,
                    'path_gambar' => $m->path_gambar
                        ? asset('storage/menu-images/' . $m->path_gambar)
                        : null,
                ];
            }),
        ]);
    }

    /**
     * API: Checkout — simpan pesanan + generate Snap Token
     */
    public function checkout(Request $request)
    {
        $cart = $request->input('cart', []);
        $total = $request->input('total', 0);

        if (empty($cart)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Keranjang kosong.',
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Generate nama Guest
            $lastPesanan = Pesanan::where('nama', 'like', 'Guest_%')
                ->orderByRaw("CAST(SUBSTRING(nama FROM 7) AS INTEGER) DESC")
                ->first();

            if ($lastPesanan) {
                $lastNum = (int) substr($lastPesanan->nama, 6);
                $guestName = 'Guest_' . str_pad($lastNum + 1, 7, '0', STR_PAD_LEFT);
            } else {
                $guestName = 'Guest_0000001';
            }

            // Unique order ID untuk Midtrans
            $orderId = 'KANTIN-' . time() . '-' . rand(100, 999);

            // Simpan pesanan
            $pesanan = Pesanan::create([
                'nama'              => $guestName,
                'timestamp'         => now(),
                'total'             => $total,
                'metode_bayar'      => 0,
                'status_bayar'      => 0,
                'midtrans_order_id' => $orderId,
            ]);

            // Simpan detail pesanan
            $itemDetails = [];
            foreach ($cart as $item) {
                DetailPesanan::create([
                    'idmenu'    => $item['idmenu'],
                    'idpesanan' => $pesanan->idpesanan,
                    'jumlah'    => $item['jumlah'],
                    'harga'     => $item['harga'],
                    'subtotal'  => $item['subtotal'],
                    'timestamp' => now(),
                    'catatan'   => $item['catatan'] ?? null,
                ]);

                $itemDetails[] = [
                    'id'       => 'MENU-' . $item['idmenu'],
                    'price'    => (int) $item['harga'],
                    'quantity' => (int) $item['jumlah'],
                    'name'     => substr($item['nama_menu'], 0, 50),
                ];
            }

            // Generate Midtrans Snap Token
            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => (int) $total,
                ],
                'item_details'     => $itemDetails,
                'customer_details' => [
                    'first_name' => $guestName,
                    'email'      => $guestName . '@guest.kantin.local',
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            // Simpan snap token ke pesanan
            $pesanan->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json([
                'status'     => 'success',
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'guest_name' => $guestName,
                'idpesanan'  => $pesanan->idpesanan,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook: Notification dari Midtrans
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId     = $notification->order_id;
            $statusCode  = $notification->status_code;
            $transStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $fraudStatus = $notification->fraud_status ?? 'accept';

            $pesanan = Pesanan::where('midtrans_order_id', $orderId)->first();

            if (!$pesanan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            // Tentukan metode bayar
            $metodeBayar = 0;
            if (in_array($paymentType, ['bank_transfer', 'echannel', 'permata'])) {
                $metodeBayar = 1; // Virtual Account
            } elseif ($paymentType === 'qris' || $paymentType === 'gopay' || $paymentType === 'shopeepay') {
                $metodeBayar = 2; // QRIS / E-Wallet
            }

            if ($transStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $pesanan->update([
                        'status_bayar' => 1,
                        'metode_bayar' => $metodeBayar,
                    ]);
                }
            } elseif ($transStatus == 'settlement') {
                $pesanan->update([
                    'status_bayar' => 1,
                    'metode_bayar' => $metodeBayar,
                ]);
            } elseif (in_array($transStatus, ['cancel', 'deny', 'expire'])) {
                $pesanan->update(['status_bayar' => 2]); // 2 = gagal/expired
            } elseif ($transStatus == 'pending') {
                $pesanan->update([
                    'status_bayar' => 0,
                    'metode_bayar' => $metodeBayar,
                ]);
            }

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Halaman sukses pembayaran
     */
    public function paymentSuccess(Request $request, $orderId)
    {
        $pesanan = Pesanan::where('midtrans_order_id', $orderId)
            ->with('detailPesanans.menu')
            ->first();

        if (!$pesanan) {
            abort(404);
        }

        // Update status dari parameter Midtrans (saat user kembali dari pembayaran)
        $transactionStatus = $request->get('transaction_status');
        $paymentType = $request->get('payment_type');

        if ($transactionStatus) {
            $statusBayar = 0; // default pending

            if (in_array($transactionStatus, ['capture', 'settlement'])) {
                $statusBayar = 1; // lunas
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $statusBayar = 2; // gagal
            }

            $metodeBayar = $this->mapPaymentType($paymentType);

            $pesanan->update([
                'status_bayar' => $statusBayar,
                'metode_bayar' => $metodeBayar,
            ]);

            // Refresh data
            $pesanan->refresh();
        }

        return view('kantin.success', compact('pesanan'));
    }

    /**
     * Mapping payment type Midtrans ke kode metode bayar
     */
    private function mapPaymentType($paymentType)
    {
        if (str_contains($paymentType, 'bank_transfer')) return 1; // VA
        if (str_contains($paymentType, 'echannel')) return 1; // Mandiri VA
        if ($paymentType == 'qris') return 2; // QRIS
        if ($paymentType == 'gopay') return 2; // GoPay masuk QRIS
        if (str_contains($paymentType, 'credit_card')) return 3; // CC

        return 0; // default
    }
}
