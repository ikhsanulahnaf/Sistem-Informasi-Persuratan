<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SK Rektor</title>
    <style>
         
        body {
            font-family: "Times New Roman", Times, serif;
            line-height: 1.5;
            margin: 0;
            padding: 40px 70px;
            font-size: 11pt;
        }
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .header-logo {
            display: table-cell;
            vertical-align: middle;
            width: 90px;
            padding-right: 15px;
        }
        .header-logo img {
            width: 80px;
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
            margin: 5px 0 2px;
            font-size: 10pt;
        }
        .header-text .contact {
            margin: 2px 0 0;
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
            font-size: 13pt;
            margin: 25px 0 5px;
            text-transform: uppercase;
        }
        .institusi {
            text-align: center;
            font-weight: bold;
            font-size: 13pt;
            margin: 5px 0 10px;
            text-transform: uppercase;
        }
        .nomor {
            text-align: center;
            margin: 10px 0 8px;
            font-size: 11pt;
        }
        .tentang-label {
            text-align: center;
            margin: 8px 0 5px;
            font-size: 11pt;
        }
        .tentang {
            text-align: center;
            margin: 5px 0 10px;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
        }
        .garis-bawah-tentang {
            border-top: 2px solid #000;
            margin: 10px 0 15px;
        }
        .jabatan {
            text-align: center;
            margin: 15px 0 25px;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
        }
        .list-section {
            margin: 20px 0;
        }
        .list-section-header {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .list-section-title {
            display: table-cell;
            width: 140px;
            font-weight: bold;
            vertical-align: top;
        }
        .list-section-separator {
            display: table-cell;
            width: 15px;
            vertical-align: top;
        }
        .list-section-content {
            display: table-cell;
            vertical-align: top;
        }
        .list-item {
            margin: 8px 0;
            text-align: justify;
        }
        .keputusan {
            text-align: center;
            font-weight: bold;
            margin: 30px 0 25px;
            font-size: 12pt;
            letter-spacing: 3px;
        }
        .signature-section {
            margin-top: 40px;
        }
        .signature-location {
            text-align: center;
            margin: 5px 0;
        }
        .signature-title {
            text-align: center;
            margin: 5px 0 60px;
        }
        .signature-name {
            text-align: center;
            font-weight: bold;
            margin: 5px 0;
        }
        .tembusan {
            margin-top: 35px;
        }
        .tembusan-title {
            font-weight: bold;
            margin-bottom: 8px;
        }
        .tembusan p {
            margin: 4px 0;
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
.place-date{
    text-align: right;
}
.title{
    text-align: right;
}
.name{
    text-align: right;
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
    </style>
</head>
<body>
    <!-- Header with Logo -->
    <div class="header-container">
        <div class="header-logo">
            <!-- Ganti dengan path logo ITI yang sebenarnya -->
            <img src="{{ public_path('images/logo-iti.png') }}" alt="Logo ITI"></div>
        <div class="header-text">
            <h1>INSTITUT TEKNOLOGI INDONESIA</h1>
            <p class="address">Jl. Raya Puspiptek, Tangerang Selatan - 15314<br>(021) 7562757</p>
            <p class="contact">www.iti.ac.id • institutteknologiindonesia • @kampusITI • Institut Teknologi Indonesia</p>
        </div>
    </div>
    
    <!-- Garis Pemisah -->
    <div class="header-line"></div>
    
    <!-- Jenis Surat -->
    <div class="jenis-surat">KEPUTUSAN REKTOR</div>
    <div class="institusi">INSTITUT TEKNOLOGI INDONESIA</div>
    
    <!-- Nomor Surat -->
    <div class="nomor">
        Nomor: {{ $surat->nomor_surat ?? '__________' }}
    </div>
    
    <!-- Tentang -->
    <div class="tentang-label">Tentang</div>
    <div class="tentang">
        {{ strtoupper($surat->perihal) }}
    </div>
    
    <!-- Garis bawah tentang -->
    <div class="garis-bawah-tentang"></div>
    
    <!-- Jabatan -->
    <div class="jabatan">
        REKTOR INSTITUT TEKNOLOGI INDONESIA
    </div>
    
    <!-- Menimbang -->
    <div class="list-section">
        <div class="list-section-header">
            <div class="list-section-title">Menimbang</div>
            <div class="list-section-separator">:</div>
            <div class="list-section-content">
                @if(!empty($surat->menimbang))
                    @foreach($surat->menimbang as $index => $item)
                        <div class="list-item">{{ $index + 1 }}. {{ $item }}</div>
                    @endforeach
                @else
                    <div class="list-item">1. [Isi pertimbangan]</div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Mengingat -->
    <div class="list-section">
        <div class="list-section-header">
            <div class="list-section-title">Mengingat</div>
            <div class="list-section-separator">:</div>
            <div class="list-section-content">
                @if(!empty($surat->mengingat))
                    @foreach($surat->mengingat as $index => $item)
                        <div class="list-item">{{ $index + 1 }}. {{ $item }}</div>
                    @endforeach
                @else
                    <div class="list-item">1. Undang Undang No. 20 Tahun 2003 tentang Sistem Pendidikan Nasional;</div>
                    <div class="list-item">2. Undang Undang No. 12 Tahun 2012 tentang Pendidikan Tinggi;</div>
                    <div class="list-item">3. Peraturan Presiden No. 8 Tahun 2012 tentang Kerangka Kualifikasi Nasional Indonesia;</div>
                    <div class="list-item">4. Peraturan Menteri Pendidikan Tinggi, Sains dan Teknologi tentang Penjaminan Mutu Pendidikan Tinggi;</div>
                    <div class="list-item">5. Peraturan Menteri Pendidikan, Kebudayaan, Riset, dan Teknologi No. 41 Tahun 2021 tentang Rekognisi Pembelajaran Lampau;</div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Memperhatikan -->
    @if(!empty($surat->memperhatikan))
    <div class="list-section">
        <div class="list-section-header">
            <div class="list-section-title">Memperhatikan</div>
            <div class="list-section-separator">:</div>
            <div class="list-section-content">
                @foreach($surat->memperhatikan as $index => $item)
                    <div class="list-item">{{ $item }}</div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    
    <!-- KEPUTUSAN -->
    <div class="keputusan">
        M E M U T U S K A N
    </div>
    
    <!-- Menetapkan -->
    <div class="list-section">
        <div class="list-section-header">
            <div class="list-section-title">Menetapkan</div>
            <div class="list-section-separator">:</div>
            <div class="list-section-content">
                @if(!empty($surat->menetapkan))
                    @foreach($surat->menetapkan as $index => $item)
                        <div class="list-item">{{ $index + 1 }}. {{ $item }}</div>
                    @endforeach
                @else
                    <div class="list-item">1. [Isi penetapan]</div>
                @endif
            </div>
        </div>
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
                Signature: {{ Str::limit($surat->digitalSignature->signature_data, 40) }}
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

    <p class="title">Pjs. Rektor,</p>
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
            <p class="tembusan-title">Tembusan Yth.:</p>
            @foreach($surat->tembusan as $index => $item)
                <p>{{ $index + 1 }}. {{ $item }}</p>
            @endforeach
        </div>
    @endif
</body>
</html>