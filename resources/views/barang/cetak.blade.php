<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            /* Lebar 210mm (21cm) sesuai gambar, tinggi pakai standar T&J 108 yaitu 165mm */
            size: 210mm 165mm; 
            margin: 0;
        }
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }
        .label-box {
            position: absolute;
            width: 38mm;      /* UPDATE: Lebar stiker genap 38mm */
            height: 18mm;     /* UPDATE: Tinggi stiker genap 18mm */
            border: 1px dashed #ccc; /* BORDER BAYANGAN: Hapus baris ini kalau ukuran sudah pas saat diprint! */
            text-align: center;
            overflow: hidden;
            box-sizing: border-box;
            padding-top: 3mm;
        }
        .nama-barang {
            font-size: 11px;
            font-weight: bold;
            display: block;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .harga-barang {
            font-size: 12px;
        }
    </style>
</head>
<body>

@php
    // ==========================================
    // AREA SETTING UKURAN FINAL T&J 108 (Data Terbaru)
    // ==========================================
    $labelWidth  = 38; // Lebar stiker (mm)
    $labelHeight = 18; // Tinggi stiker (mm)
    
    // JARAK ANTAR STIKER 
    $gapX = 3; // Jarak horizontal (Horizontal Pitch 41mm - Width 38mm)
    $gapY = 2; // Jarak vertikal standar
    
    // MARGIN KERTAS 
    $marginLeft = 3; // Margin Kiri (mm) sesuai Side Margin
    $marginTop  = 3; // Margin Atas (mm) sesuai Top Margin
@endphp

@for ($row = 0; $row < 8; $row++)
    @for ($col = 0; $col < 5; $col++)
        @php
            $index = ($row * 5) + $col;
            
            // RUMUS KOORDINAT ABSOLUTE (Wajib ada di dalam loop ini)
            $left = $marginLeft + ($col * ($labelWidth + $gapX));
            $top  = $marginTop + ($row * ($labelHeight + $gapY));
        @endphp

        {{-- Cetak kotak stikernya di posisi X dan Y yang tepat --}}
        <div class="label-box" style="left: {{ $left }}mm; top: {{ $top }}mm;">
            @if(isset($labels[$index]) && $labels[$index])
                <span class="nama-barang">{{ $labels[$index]->nama_barang }}</span>
                <span class="harga-barang">Rp {{ number_format($labels[$index]->harga) }}</span>
            @endif
        </div>
    @endfor
@endfor

</body>
</html>