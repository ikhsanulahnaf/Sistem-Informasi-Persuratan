<?php

namespace App\Services;

use phpseclib3\Crypt\EC;

/**
 * ECDSA Digital Signature Service
 * Menggunakan algorithm ECDSA dengan kurva prime256v1 (secp256r1)
 */
class EcdsaSigningService
{
    private $curve = 'prime256v1';

    /**
     * Generate ECDSA Key Pair (Public & Private Key)
     *
     * @return array ['public_key' => string, 'private_key' => string]
     */
    public function generateKeyPair()
    {
        $privateKey = EC::createKey($this->curve);
        $publicKey = $privateKey->getPublicKey();

        return [
            'public_key' => $publicKey->toString('PKCS8'),
            'private_key' => $privateKey->toString('PKCS8'),
        ];
    }

    /**
     * Sign content dengan private key
     *
     * @param string $content - Content yang akan di-sign
     * @param string $privateKeyStr - Private key dalam format PKCS8
     * @return string - Signature dalam format hex
     */
    public function sign($content, $privateKeyStr)
    {
        try {
            // Load private key dari format PKCS8 yang sudah di-base64
            $privateKey = EC::load($privateKeyStr);

            // Create signature dengan ECDSA
            $signature = $privateKey->sign($content);

            // Convert ke hex untuk storage
            return bin2hex($signature);
        } catch (\Exception $e) {
            \Log::error('ECDSA Signing Error: ' . $e->getMessage());
            throw new \Exception('Gagal melakukan sign: ' . $e->getMessage());
        }
    }

    /**
     * Verify signature dengan public key
     *
     * @param string $content - Original content
     * @param string $signatureHex - Signature dalam format hex
     * @param string $publicKeyStr - Public key dalam format PKCS8
     * @return bool - True jika valid, false jika invalid
     */
    public function verify($content, $signatureHex, $publicKeyStr)
    {
        try {
            $publicKey = EC::load($publicKeyStr);
            $signature = hex2bin($signatureHex);

            return $publicKey->verify($content, $signature);
        } catch (\Exception $e) {
            \Log::error('ECDSA Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get signature metadata untuk audit trail
     * 
     * @param string $signatureHex - Signature
     * @param string $publicKeyStr - Public key
     * @return array - Metadata
     */
    public function getSignatureMetadata($signatureHex, $publicKeyStr)
    {
        return [
            'algorithm' => 'ECDSA',
            'curve' => $this->curve,
            'signature_hex' => $signatureHex,
            'public_key' => $publicKeyStr,
            'timestamp' => now(),
        ];
    }

    /**
     * Generate certificate info untuk display
     * 
     * @param string $publicKeyStr - Public key
     * @param string $userName - Nama yang sign
     * @param string $signedAt - Timestamp
     * @return array - Certificate info
     */
    public function generateCertificateInfo($publicKeyStr, $userName, $signedAt)
    {
        return [
            'subject' => "CN=$userName,C=ID",
            'algorithm' => 'ECDSA with SHA-256',
            'curve' => $this->curve,
            'public_key_fingerprint' => hash('sha256', $publicKeyStr),
            'signed_date' => $signedAt,
            'validity' => '5 years',
        ];
    }
}
