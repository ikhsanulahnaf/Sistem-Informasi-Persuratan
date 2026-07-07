@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edaran Rektor</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            line-height: 1.6;
            margin: 0;
            padding: 40px 80px;
            font-size: 12pt;
        }
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: 80px;
            padding-right: 15px;
        }
        .header-logo img {
            width: 70px;
            height: auto;
        }
        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .header-text h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-text .address {
            margin: 3px 0 0;
            font-size: 10pt;
        }
        .header-text .contact {
            margin: 3px 0 0;
            font-size: 8pt;
            color: #333;
        }
        .header-line {
            border-top: 3px solid #000;
            margin: 15px 0 25px;
        }
        .jenis-surat {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 25px 0 8px;
            letter-spacing: 4px;
        }
        .nomor {
            text-align: center;
            margin: 8px 0 25px;
            font-size: 11pt;
        }
        .tentang {
            text-align: center;
            margin: 25px 0 8px;
            font-weight: normal;
            font-size: 12pt;
        }
        .judul-surat {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin: 8px 0 25px;
            text-transform: uppercase;
        }
        .content {
            text-align: justify;
            margin: 25px 0;
        }
        .content p {
            margin: 0 0 12px;
            text-indent: 50pt;
        }
        .content p:first-child {
            text-indent: 50pt;
        }
        .list-container {
            margin: 15px 0 15px 50pt;
        }
        .list-item {
            margin: 8px 0;
            display: table;
            width: 100%;
        }
        .list-number {
            display: table-cell;
            width: 25px;
            vertical-align: top;
        }
        .list-text {
            display: table-cell;
            vertical-align: top;
            text-align: justify;
        }
        .content .no-indent {
            text-indent: 0;
            margin-top: 15px;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature .place-date {
            margin-bottom: 5px;
        }
        .signature .title {
            margin: 5px 0;
        }
        .signature .name {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
        .signature-container {
            position: relative;
            text-align: right;
            margin-top: 20px;
        }
        .stempel-image {
            position: absolute;
            top: -60px;
            right: 50px;
            width: 120px;
            height: 120px;
            opacity: 0.8;
            z-index: 1;
        }
        .ttd-image {
            position: relative;
            z-index: 2;
            height: 70px;
            margin-bottom: 5px;
        }
        .ecdsa-key {
            margin-top: 15px;
            font-size: 6pt;
            font-family: monospace;
            color: #666;
            max-width: 300px;
            word-wrap: break-word;
            text-align: right;
        }
        .tembusan {
            margin-top: 40px;
        }
        .tembusan-title {
            margin: 0 0 8px;
        }
        .tembusan p {
            margin: 4px 0;
        }
        .footer-note {
            margin-top: 20px;
            font-style: italic;
        }
        .digital-signature-info {
    font-size: 9pt;
    color: #333;
    margin-bottom: 10px;
    text-align: right;
}

.digital-signature-info .hash {
    font-family: monospace;
    font-size: 5pt;
    
}

    </style>
</head>
<body>
    <!-- Header with Logo -->
    <div class="header-container">
        <div class="header-logo">
            <!-- Ganti dengan path logo ITI yang sebenarnya -->
          <img src="{{ public_path('images/logo-iti.png') }}" alt="Logo ITI">
        </div>
        <div class="header-text">
            <h1>INSTITUT TEKNOLOGI INDONESIA</h1>
            <p class="address">Jl. Raya Puspiptek, Tangerang Selatan - 15314<br>(021) 7560545</p>
            <p class="contact">www.iti.ac.id • institutteknologiindonesia • @kampusITI • Institut Teknologi Indonesia</p>
        </div>
    </div>
    
    <!-- Garis Pemisah -->
    <div class="header-line"></div>
    
    <!-- Jenis Surat -->
    <div class="jenis-surat">E D A R A N</div>
    
    <!-- Nomor Surat -->
    <div class="nomor">
        No. {{ $surat->nomor_surat ?? '__________' }}
    </div>
    
    <!-- Tentang -->
    <div class="tentang">
        T e n t a n g
    </div>
    
    <!-- Judul/Perihal -->
    <div class="judul-surat">
        {{ strtoupper($surat->perihal) }}
    </div>
    
    <!-- Isi Surat -->
    <div class="content">
        {!! nl2br(e($surat->isi_ringkas)) !!}
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
    <p class="place-date">
        Tangerang Selatan, {{ $surat->tanggal_surat->translatedFormat('d F Y') }}
    </p>

    @if($surat->digitalSignature)
        <div class="digital-signature-info">
            <div>
                Ditandatangani secara digital menggunakan
                <strong>{{ $surat->digitalSignature->algorithm }}</strong>
            </div>
            <div>
                Oleh: {{ $surat->digitalSignature->signer->name ?? 'Rektor' }}
            </div>
            <div>
                Pada: {{ $surat->digitalSignature->signed_at->addHours(7)->translatedFormat('d F Y H:i') }} WIB
            </div>
            <div class="hash">
                {{ Str::limit($surat->digitalSignature->signature_data) }}
            </div>
        </div>

        <div class="signature-container">
            @if(file_exists(public_path('images/stempel-rektor.png')))
                <img src="{{ public_path('images/stempel-rektor.png') }}" alt="Stempel Rektor" class="stempel-image">
            @endif
            @if(file_exists(public_path('images/tanda-tangan-rektor.png')))
                <img src="{{ public_path('images/tanda-tangan-rektor.png') }}" alt="Tanda Tangan Rektor" class="ttd-image">
            @endif
        </div>
    @endif

    <p class="title"> Pjs. Rektor,</p>
    <p class="name">
        Prof. Dr. Ir. Syopiansyah Jaya Putra, M.Sis., IPU, ASEAN Eng
    </p>

    @if($surat->digitalSignature && $surat->digitalSignature->public_key)
        <div class="ecdsa-key">
            <strong>ECDSA Public Key:</strong><br>
            {{ Str::limit($surat->digitalSignature->public_key, 150) }}
        </div>
    @endif
</div>

    
    <!-- Tembusan -->
    @if(!empty($surat->tembusan))
        <div class="tembusan">
            <p class="tembusan-title"><strong>Tembusan Yth.:</strong></p>
            @foreach($surat->tembusan as $index => $item)
                <p>{{ $index + 1 }}. {{ $item }}</p>
            @endforeach
        </div>
    @endif
</body>
</html>