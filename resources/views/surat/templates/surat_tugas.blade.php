<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            line-height: 1.6;
            margin: 0;
            padding: 40px 70px;
            font-size: 12pt;
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
            margin: 15px 0 30px;
        }
        .jenis-surat {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin: 30px 0 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .nomor {
            text-align: center;
            margin: 10px 0 30px;
            font-size: 11pt;
        }
        .section-row {
            display: table;
            width: 100%;
            margin: 10px 0;
        }
        .section-label {
            display: table-cell;
            width: 150px;
            vertical-align: top;
            font-weight: normal;
        }
        .section-separator {
            display: table-cell;
            width: 20px;
            vertical-align: top;
        }
        .section-content {
            display: table-cell;
            vertical-align: top;
        }
        .section-content-item {
            margin: 6px 0;
        }
        .ditugaskan {
            text-align: center;
            font-weight: bold;
            margin: 30px 0;
            font-size: 13pt;
            letter-spacing: 4px;
        }
        .signature {
            margin-top: 60px;
            text-align: center;
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
        }
        .tembusan {
            margin-top: 40px;
        }
        .tembusan-title {
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
.signature-container {
    position: relative;
    text-align: center;
    margin-top: 20px;
}
.stempel-image {
    position: absolute;
    top: -60px;
    left: 50%;
    transform: translateX(-50%);
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
    text-align: center;
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
    <div class="jenis-surat">SURAT TUGAS</div>
    
    <!-- Nomor Surat -->
    <div class="nomor">
        Nomor: {{ $surat->nomor_surat ?? '__________' }}
    </div>
    
    <!-- Pertimbangan -->
    <div class="section-row">
        <div class="section-label">Pertimbangan</div>
        <div class="section-separator">:</div>
        <div class="section-content">
            @if(!empty($surat->pertimbangan))
                @foreach($surat->pertimbangan as $index => $item)
                    <div class="section-content-item">{{ $index + 1 }}. {{ $item }}</div>
                @endforeach
            @else
                <div class="section-content-item">1. .....</div>
                <div class="section-content-item">2. .....</div>
            @endif
        </div>
    </div>
    
    <!-- Dasar -->
    <div class="section-row">
        <div class="section-label">Dasar</div>
        <div class="section-separator">:</div>
        <div class="section-content">
            {{ $surat->dasar ?? 'Surat Undangan No. ........... /' }}
        </div>
    </div>
    
    <!-- DITUGASKAN -->
    <div class="ditugaskan">D I T U G A S K A N</div>
    
    <!-- Kepada -->
    <div class="section-row">
        <div class="section-label">Kepada</div>
        <div class="section-separator">:</div>
        <div class="section-content">
            {{ $surat->penerima ?? '<< NAMA PEGAWAI >>' }}
        </div>
    </div>
    
    <!-- Untuk -->
    <div class="section-row">
        <div class="section-label">Untuk</div>
        <div class="section-separator">:</div>
        <div class="section-content">
            @if(!empty($surat->untuk))
                @foreach($surat->untuk as $index => $item)
                    <div class="section-content-item">{{ $index + 1 }}. {{ $item }}</div>
                @endforeach
            @else
                <div class="section-content-item">1. ......</div>
                <div class="section-content-item">2. .......</div>
            @endif
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
            <p class="tembusan-title"><strong>Tembusan Yth.:</strong></p>
            @foreach($surat->tembusan as $index => $item)
                <p>{{ $index + 1 }}. {{ $item }}</p>
            @endforeach
        </div>
    @endif
</body>
</html>