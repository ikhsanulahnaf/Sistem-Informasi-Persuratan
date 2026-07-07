<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Dinas</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            line-height: 1.5;
            margin: 0;
            padding: 40px 70px;
            font-size: 11pt;
        }
        .header-line-top {
            border-top: 2px solid #000;
            margin-bottom: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-line-bottom {
            border-top: 2px solid #000;
            margin-top: 15px;
            margin-bottom: 20px;
        }
        .jenis-surat {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            margin: 20px 0 10px;
            text-transform: uppercase;
        }
        .nomor {
            text-align: center;
            margin: 10px 0 25px;
            font-size: 11pt;
        }
        .dari-kepada-perihal {
            margin: 25px 0 15px;
        }
        .dari-kepada-perihal table {
            width: 100%;
            border-collapse: collapse;
        }
        .dari-kepada-perihal td {
            padding: 3px 0;
            vertical-align: top;
        }
        .dari-kepada-perihal .label {
            width: 80px;
        }
        .dari-kepada-perihal .separator {
            width: 15px;
            text-align: center;
        }
        .dari-kepada-perihal .content-cell {
            text-align: left;
        }
        .garis {
            border-top: 1px solid #000;
            margin: 15px 0 20px;
        }
        .content {
            text-align: justify;
            margin: 20px 0 30px;
        }
        .content p {
            margin: 10px 0;
            text-indent: 40pt;
        }
        .content p:first-child {
            text-indent: 40pt;
        }
        .content ol {
            margin: 10px 0 10px 20pt;
            padding-left: 20pt;
        }
        .content ol li {
            margin: 8px 0;
            text-align: justify;
            padding-left: 5pt;
        }
        .content ol ol {
            list-style-type: lower-alpha;
            margin-top: 8px;
        }
        .content .no-indent {
            text-indent: 0;
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
        }
        .tembusan {
            margin-top: 35px;
        }
        .tembusan p {
            margin: 4px 0;
        }
        .closing-text {
            text-align: justify;
            margin: 20px 0;
            text-indent: 40pt;
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
    <!-- Garis atas -->
    <div class="header-line-top"></div>
    
    <!-- Header -->
    <div class="header">
        <h1>INSTITUT TEKNOLOGI INDONESIA</h1>
    </div>
    
    <!-- Garis bawah header -->
    <div class="header-line-bottom"></div>
    
    <!-- Jenis Surat -->
    <div class="jenis-surat">NOTA DINAS</div>
    
    <!-- Nomor Surat -->
    <div class="nomor">
        Nomor: {{ $surat->nomor_surat ?? '__________' }}
    </div>
    
    <!-- Dari, Kepada, Perihal -->
    <div class="dari-kepada-perihal">
        <table>
            <tr>
                <td class="label">Dari</td>
                <td class="separator">:</td>
                <td class="content-cell">Rektor ITI</td>
            </tr>
            <tr>
                <td class="label">Kepada</td>
                <td class="separator">:</td>
                <td class="content-cell">{{ $surat->penerima }}</td>
            </tr>
            <tr>
                <td class="label">Perihal</td>
                <td class="separator">:</td>
                <td class="content-cell">{{ $surat->perihal }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Garis pemisah -->
    <div class="garis"></div>
    
    <!-- Isi Surat -->
    <div class="content">
        {!! nl2br(e($surat->isi_ringkas)) !!}
    </div>
    
    <!-- Penutup -->
    <div class="closing-text">
        <p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p>
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
    @if($surat->tembusan)
        <div class="tembusan">
            <p><strong>Tembusan:</strong></p>
            @if(is_array($surat->tembusan))
                @foreach($surat->tembusan as $tembusan)
                    <p>{{ $tembusan }}</p>
                @endforeach
            @else
                <p>{{ $surat->tembusan }}</p>
            @endif
        </div>
    @endif

</body>
</html>