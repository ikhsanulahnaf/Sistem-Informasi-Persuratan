@extends('layouts.app')

@section('title', 'Verifikasi Digital Signature')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('approval.waitingSignature') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Status Badge -->
                <div class="mb-6">
                    @if($isValid)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-green-900">Digital Signature Valid ✓</h3>
                                <p class="text-sm text-green-700">Signature telah diverifikasi dan asli dari {{ $signer->name }}</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="font-semibold text-red-900">Digital Signature Tidak Valid ✗</h3>
                                <p class="text-sm text-red-700">Signature tidak sesuai dengan dokumen asli</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Surat Info -->
                <div class="bg-white rounded-lg card-shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Surat</h2>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Nomor Surat</p>
                            <p class="font-semibold text-gray-900">{{ $surat->nomor_surat ?? 'DRAFT' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Tanggal Surat</p>
                            <p class="font-semibold text-gray-900">{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase">Perihal</p>
                        <p class="text-gray-900">{{ $surat->perihal }}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase">Dari</p>
                        <p class="text-gray-900">{{ $surat->creator->name }} ({{ $surat->creator->unit }})</p>
                    </div>
                </div>

                <!-- Digital Signature Info -->
                <div class="bg-white rounded-lg card-shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                        </svg>
                        Informasi Digital Signature
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Algorithm</p>
                            <p class="text-gray-900 font-mono text-sm bg-gray-50 p-2 rounded">
                                {{ $certInfo['algorithm'] }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Curve</p>
                            <p class="text-gray-900 font-mono text-sm bg-gray-50 p-2 rounded">
                                {{ $certInfo['curve'] }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Public Key Fingerprint (SHA-256)</p>
                            <p class="text-gray-900 font-mono text-xs bg-gray-50 p-2 rounded break-all">
                                {{ $certInfo['public_key_fingerprint'] }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase mb-1">Signed By</p>
                                <p class="text-gray-900 font-semibold">{{ $signer->name }}</p>
                                <p class="text-xs text-gray-500">{{ $signer->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase mb-1">Signed At</p>
                                <p class="text-gray-900 font-semibold">{{ $digitalSignature->signed_at->addHours(7)->format('d M Y H:i:s') }}</p>
                                <p class="text-xs text-gray-500">{{ $digitalSignature->signed_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase mb-1">Signature Data (Hex)</p>
                            <div class="bg-gray-50 p-3 rounded max-h-32 overflow-y-auto">
                                <p class="text-gray-900 font-mono text-xs break-all">
                                    {{ $digitalSignature->signature_data }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Certificate Info Box -->
                <div class="bg-white rounded-lg card-shadow p-6 mb-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Certificate Info</h3>

                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Subject</p>
                            <p class="text-gray-900 font-mono break-all">{{ $certInfo['subject'] }}</p>
                        </div>

                        <div class="pt-3 border-t border-gray-200">
                            <p class="text-xs text-gray-500 uppercase">Validity Period</p>
                            <p class="text-gray-900">{{ $certInfo['validity'] }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase">Issued</p>
                           <p class="text-gray-900">
    {{ $certInfo['signed_date']->addHours(7)->format('d M Y H:i') }}
</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="space-y-2">
                    <a href="{{ route('approval.waitingSignature') }}"
                        class="w-full px-4 py-2 bg-gray-200 text-gray-900 rounded-lg hover:bg-gray-300 transition font-medium flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Kembali
                    </a>
                </div>

                <!-- Verification Status -->
                <div class="mt-6 p-4 rounded-lg {{ $isValid ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 {{ $isValid ? 'text-green-600' : 'text-red-600' }} flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-semibold {{ $isValid ? 'text-green-900' : 'text-red-900' }}">
                                {{ $isValid ? 'Signature Valid' : 'Signature Invalid' }}
                            </p>
                            <p class="text-xs {{ $isValid ? 'text-green-700' : 'text-red-700' }} mt-1">
                                {{ $isValid ? 'Dokumen ini asli dan belum diubah' : 'Dokumen ini telah diubah atau signature tidak valid' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
