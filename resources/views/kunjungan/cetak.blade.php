<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
@php
use Picqer\Barcode\BarcodeGeneratorPNG;

function generateBarcode($text) {
    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($text, $generator::TYPE_CODE_128, 1.5, 25);
    return 'data:image/png;base64,' . base64_encode($barcode);
}
@endphp
<style>
@page {
    size: 210mm 165mm;
    margin: 0;
}

* {
    box-sizing: border-box;
}

body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    width: 210mm;
    height: 165mm;
}

.label-wrapper {
    position: relative;
    width: 210mm;
    height: 165mm;
    overflow: hidden;
}

.label-box {
    position: absolute;
    width: 38mm;
    height: 18mm;
    text-align: center;
    overflow: hidden;
    display: table;
}

.label-content {
    display: table-cell;
    vertical-align: middle;
    text-align: center;
    width: 38mm;
    height: 18mm;
}

.barcode-img {
    display: block;
    margin: 0 auto;
    height: 20px;
    max-width: 95%;
}

.barcode-text {
    font-size: 8px;
    font-family: monospace;
    display: block;
    margin-top: 0;
    letter-spacing: 0.5px;
    line-height: 1;
}

.nama-toko {
    font-size: 9px;
    font-weight: bold;
    display: block;
    margin-top: 1px;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 36mm;
}
</style>
</head>
<body>

@php
    $labelWidth  = 38;
    $labelHeight = 18;
    $gapX        = 3;
    $gapY        = 2;
    $cols        = 5;
    $rows        = 8;
    $marginLeft  = 4;
    $marginTop   = 3.5;
@endphp

<div class="label-wrapper">
    @for ($row = 0; $row < $rows; $row++)
        @for ($col = 0; $col < $cols; $col++)
            @php
                $index = ($row * $cols) + $col;
                $left  = $marginLeft + ($col * ($labelWidth + $gapX));
                $top   = $marginTop  + ($row * ($labelHeight + $gapY));
            @endphp
            <div class="label-box" style="left: {{ $left }}mm; top: {{ $top }}mm;">
                @if(isset($labels[$index]) && $labels[$index])
                <div class="label-content">
                    <img src="{{ generateBarcode($labels[$index]->barcode) }}" class="barcode-img" alt="{{ $labels[$index]->barcode }}">
                    <span class="barcode-text">{{ $labels[$index]->barcode }}</span>
                    <span class="nama-toko">{{ $labels[$index]->nama_toko }}</span>
                </div>
                @endif
            </div>
        @endfor
    @endfor
</div>

</body>
</html>
