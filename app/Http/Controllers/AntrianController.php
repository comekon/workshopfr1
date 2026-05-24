<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AntrianController extends Controller
{
    public function index()
    {
        return view('antrian.guest');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $nomor = Antrian::generateNomorAntrian();
        $antrian = Antrian::create([
            'nomor_antrian' => $nomor,
            'nama' => $request->nama,
            'status' => 'menunggu',
        ]);

        $this->broadcastEvent('antrian_baru', $antrian);

        return redirect()->route('antrian.tiket', $antrian->id);
    }

    public function show($id)
    {
        $antrian = Antrian::findOrFail($id);
        return view('antrian.tiket', compact('antrian'));
    }

    public function admin()
    {
        $menunggu = Antrian::menunggu()->get();
        $dipanggil = Antrian::dipanggil()->get();
        $terlewat = Antrian::terlewat()->get();
        return view('antrian.admin', compact('menunggu', 'dipanggil', 'terlewat'));
    }

    public function papanView()
    {
        $sedangDipanggil = Antrian::where('status', 'dipanggil')->latest('called_at')->first();
        $menunggu = Antrian::menunggu()->get();
        return view('antrian.papan', compact('sedangDipanggil', 'menunggu'));
    }

    public function panggil($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update([
            'status' => 'dipanggil',
            'called_at' => now(),
        ]);

        $this->broadcastEvent('antrian_dipanggil', $antrian);

        return response()->json(['success' => true, 'antrian' => $antrian]);
    }

    public function selesai($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update(['status' => 'selesai']);

        $this->broadcastEvent('antrian_selesai', $antrian);

        return response()->json(['success' => true, 'antrian' => $antrian]);
    }

    public function terlambat($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->update(['status' => 'terlewat']);

        $this->broadcastEvent('antrian_terlewat', $antrian);

        return response()->json(['success' => true, 'antrian' => $antrian]);
    }

    public function streamSse()
    {
        set_time_limit(300);
        $startTime = time();

        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($startTime) {
            $lastUpdate = Cache::get('antrian_last_update', 0);

            while (true) {
                if (time() - $startTime >= 300) {
                    break;
                }

                $currentUpdate = Cache::get('antrian_last_update', 0);

                if ($currentUpdate > $lastUpdate) {
                    $event = Cache::get('antrian_latest_event');
                    echo "event: {$event['event']}\n";
                    echo "data: " . json_encode($event['data']) . "\n\n";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();

                    $lastUpdate = $currentUpdate;
                }

                sleep(1);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }

    private function broadcastEvent(string $event, Antrian $antrian): void
    {
        Cache::put('antrian_latest_event', [
            'event' => $event,
            'data' => [
                'id' => $antrian->id,
                'nomor_antrian' => $antrian->nomor_antrian,
                'nama' => $antrian->nama,
                'status' => $antrian->status,
            ],
        ], now()->addHours(2));

        Cache::put('antrian_last_update', now()->timestamp, now()->addHours(2));
    }
}
