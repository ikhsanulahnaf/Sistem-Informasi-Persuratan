<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lembar Disposisi Rektor</title>
    <style>
        @page {
            margin: 20mm 20mm 20mm 20mm;
        }

        body {
            font-family: Times New Roman, serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        .info-value {
            width: 10px;
        }

        .info-content {
            width: auto;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .status-options {
            display: flex;
            gap: 30px;
        }

        .status-option {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .checkbox-custom {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-block;
            vertical-align: middle;
        }

        .checkbox-custom.checked {
            background-color: #000;
            position: relative;
        }

        .checkbox-custom.checked::after {
            content: '✓';
            color: white;
            position: absolute;
            top: -2px;
            left: 1px;
            font-size: 12px;
        }

        .radio-custom {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            border-radius: 50%;
            display: inline-block;
            vertical-align: middle;
        }

        .radio-custom.checked {
            background-color: #000;
        }

        .tujuan-item {
            margin: 5px 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .disposisi-box {
            /* border: 1px solid #000; */
            padding: 10px;
            min-height: 80px;
            /* font-style: italic; */
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            padding-right: 20px;
        }

        .footer-info {
            text-align: left;
            margin-top: 10px;
        }

        .signature-space {
            margin-top: 30px;
            text-align: right;
            padding-right: 20px;
        }

        .signature-image {
            height: 80px;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
        }

        .signature-line {
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="title">LEMBAR DISPOSISI REKTOR</div>

    <table class="info-table">
        <tr>
            <td class="info-label">NOMOR</td>
            <td class="info-value">:</td>
            <td class="info-content">{{ $disposisi->nomor_surat }}</td>
        </tr>
        <tr>
            <td class="info-label">ASAL SURAT</td>
            <td class="info-value">:</td>
            <td class="info-content">{{ $disposisi->pengirim }}</td>
        </tr>
        <tr>
            <td class="info-label">TGL. TERIMA</td>
            <td class="info-value">:</td>
            <td class="info-content">{{ $disposisi->disposed_at ? $disposisi->disposed_at->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="info-label">STATUS</td>
            <td class="info-value">:</td>
            <td class="info-content"></td>
        </tr>
    </table>

    <div class="section">
        <div class="status-options">
            <div class="status-option">
                <span class="radio-custom {{ $disposisi->status === 'rahasia' ? 'checked' : '' }}"></span>
                <span>Rahasia</span>
            </div>
            <div class="status-option">
                <span class="radio-custom {{ $disposisi->status === 'segera' ? 'checked' : '' }}"></span>
                <span>Segera</span>
            </div>
            <div class="status-option">
                <span class="radio-custom {{ $disposisi->status === 'biasa' ? 'checked' : '' }}"></span>
                <span>Biasa</span>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">KEPADA</div>
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                    @foreach($tujuanKiri as $tujuan)
                        <div class="tujuan-item">
                            <span
                                class="checkbox-custom {{ in_array($tujuan, $tujuanDipilih ?? []) ? 'checked' : '' }}"></span>
                            <span>{{ $tujuan }}</span>
                        </div>
                    @endforeach
                </td>
                <td style="width: 50%; vertical-align: top;">
                    @foreach($tujuanKanan as $tujuan)
                        <div class="tujuan-item">
                            <span
                                class="checkbox-custom {{ in_array($tujuan, $tujuanDipilih ?? []) ? 'checked' : '' }}"></span>
                            <span>{{ $tujuan }}</span>
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">DISPOSISI</div>
        <div class="disposisi-box">
            {{ $disposisi->instruksi }}
        </div>
    </div>

    <div class="footer">
        <div class="footer-info">
            Tangsel, {{ $disposisi->disposed_at ? $disposisi->disposed_at->format('d F Y') : date('d F Y') }}.
        </div>
        <div class="signature-space">
            <!-- Gambar Tanda Tangan -->
            <div style="height: 60px; margin-bottom: 5px;">
                @if(file_exists(public_path('images/tanda-tangan-rektor.png')))
                    <img src="{{ public_path('images/tanda-tangan-rektor.png') }}" alt="Tanda Tangan Rektor"
                        style="height: 100%; display: block; margin-left: auto;">
                @else
                    <div style="height: 100%; border-bottom: 1px solid #ccc;"></div>
                @endif
            </div>
            <!-- Nama Rektor dinamis dari database -->
            <div class="signature-line">
                {{ $disposisi->disposer->name }}
            </div>
            <div style="margin-top: 3px;">Rektor</div>
        </div>
    </div>
</body>

</html>