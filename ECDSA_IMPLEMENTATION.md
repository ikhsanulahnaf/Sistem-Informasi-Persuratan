# 🔐 ECDSA Digital Signature Implementation

## Overview

Implementasi **ECDSA (Elliptic Curve Digital Signature Algorithm)** untuk digital signature pada Tanda Tangan Rektor (TTD Rektor). Menggunakan kurva **prime256v1 (secp256r1)** yang merupakan standard industri.

---

## Komponen Utama

### 1. Service: `EcdsaSigningService`

**File:** `app/Services/EcdsaSigningService.php`

Fungsi:

- `generateKeyPair()` - Generate ECDSA public & private key pair
- `sign()` - Sign content dengan private key
- `verify()` - Verify signature dengan public key
- `getSignatureMetadata()` - Get metadata signature untuk audit trail
- `generateCertificateInfo()` - Generate certificate info untuk display

**Library:** `phpseclib3` (sudah included di vendor)

### 2. Model: `EcdsaKeyPair`

**File:** `app/Models/EcdsaKeyPair.php`

Schema:

```
- id
- user_id (FK ke users)
- public_key (OpenSSH format)
- private_key (Encrypted dengan APP_KEY)
- algorithm (default: 'ECDSA')
- curve (default: 'prime256v1')
- generated_at
- timestamps
```

Fitur:

- Automatic encryption/decryption private key dengan Laravel Crypt
- Unique per user (hanya 1 key pair per Rektor)

### 3. Controller: `ApprovalController`

**File:** `app/Http/Controllers/ApprovalController.php`

Method baru:

- `sign()` - TTD surat dengan ECDSA, generate signed copy
- `downloadSigned()` - Download signed document
- `verifySignature()` - Verify dan display signature info

### 4. Model Updates

- `DigitalSignature.php` - Add relationship ke signer (User)
- `Surat.php` - Add relationship ke digitalSignatures (hasMany)

### 5. Views

- `approval/waiting-signature.blade.php` - Update dengan tombol TTD Digital, Download, Verify
- `approval/verify-signature.blade.php` - Display signature verification info

### 6. Routes

```
GET    /approval/{surat}/download-signed       - Download signed document
GET    /approval/{surat}/verify-signature      - Verify & display signature info
```

---

## Database Schema

### New Migration: `2026_01_16_000002_create_ecdsa_key_pairs_table.php`

```sql
CREATE TABLE ecdsa_key_pairs (
    id BIGINT PRIMARY KEY,
    user_id BIGINT UNIQUE (FK to users),
    public_key LONGTEXT,
    private_key LONGTEXT (encrypted),
    algorithm VARCHAR(50) default 'ECDSA',
    curve VARCHAR(50) default 'prime256v1',
    generated_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### DigitalSignature Table (Existing)

Digunakan untuk menyimpan signature data:

```sql
- id
- surat_id (FK)
- signer_id (FK to users)  ← NEW
- algorithm ('ECDSA')
- public_key (OpenSSH format)
- signature_data (Hex string)
- signed_file_path (path ke copy signed)
- signed_at
- created_at, updated_at
```

---

## Workflow TTD Rektor

```
1. Rektor view /approval/waiting-signature
   ↓
2. Rektor klik "TTD Digital" button
   ↓
3. System check/create ECDSA key pair untuk Rektor
   ↓
4. Read original file dari storage
   ↓
5. Sign file content dengan private key (ECDSA)
   ↓
6. Verify signature untuk ensure valid
   ↓
7. Create copy file yang di-signed
   ↓
8. Save digital signature ke DB (DigitalSignature table)
   ↓
9. Update surat status → "signed_rektor"
   ↓
10. Rektor bisa download signed document
    ↓
11. Anyone bisa verify signature (cek keaslian)
```

---

## Technical Details

### ECDSA Curve

- **Curve:** prime256v1 (secp256r1)
- **Key Size:** 256 bits
- **Hash Algorithm:** SHA-256
- **Security Level:** ~128 bits (equivalent to 3072-bit RSA)

### Signature Format

- **Input:** Original file content
- **Output:** Hex-encoded ECDSA signature
- **Size:** ~512 bits (64 bytes) compressed

### Key Storage

- **Public Key:** Stored plaintext (OpenSSH format)
- **Private Key:** Encrypted dengan `Illuminate\Support\Facades\Crypt` (APP_KEY)
- **Encryption:** Laravel's default (AES-128/AES-256)

---

## Fitur Download & Verify

### Download Signed Document

**Route:** `GET /approval/{surat}/download-signed`

- Cek apakah surat sudah TTD (status: signed_rektor, numbered, archived, returned)
- Get signed file path dari DigitalSignature table
- Download dengan nama: `{nomor_surat}_signed.pdf`

### Verify Signature

**Route:** `GET /approval/{surat}/verify-signature`

Display:

- Status valid/invalid ✓/✗
- Signer info (nama, email)
- Signature timestamp
- Certificate info (subject, validity)
- Algorithm & curve info
- Public key fingerprint (SHA-256)
- Signature data (hex)
- Download button

Verification logic:

1. Get public key dari DigitalSignature
2. Get original file content
3. Get signature data
4. Verify dengan phpseclib3
5. Display result

---

## Security Considerations

✅ **Private Key Encryption**

- Private key di-encrypt dengan APP_KEY
- Hanya decrypted saat dibutuhkan (TTD)
- Tidak pernah stored plaintext

✅ **Key Pair Generation**

- Generated sekali per user (Rektor)
- Stored di database terpisah (ecdsa_key_pairs)
- Unique constraint per user

✅ **Audit Trail**

- Setiap TTD tercatat di DigitalSignature table
- Signer ID, timestamp, algorithm, signature data
- Bisa di-verify kapan saja

✅ **File Integrity**

- Signed copy dibuat & stored terpisah
- Original file tidak dimodifikasi
- Signature adalah proof of authenticity

---

## Setup Instructions

### 1. Run Migrations

```bash
php artisan migrate
```

Akan create:

- `ecdsa_key_pairs` table
- (DigitalSignature table sudah ada dari sebelumnya)

### 2. Update Services

Ensure `EcdsaSigningService` properly injected di ApprovalController constructor.

### 3. Test TTD Workflow

```bash
# Login sebagai Rektor
# Navigate ke /approval/waiting-signature
# Klik "TTD Digital" pada surat
# System akan auto-generate key pair jika belum ada
# Signature created & stored
# Click "Download" untuk get signed document
# Click "Verify" untuk check authenticity
```

---

## Files Modified/Created

| File                                                                     | Status     | Purpose                                   |
| ------------------------------------------------------------------------ | ---------- | ----------------------------------------- |
| `app/Services/EcdsaSigningService.php`                                   | ✨ Created | ECDSA signing logic                       |
| `app/Models/EcdsaKeyPair.php`                                            | ✨ Created | Store ECDSA keys                          |
| `app/Http/Controllers/ApprovalController.php`                            | 📝 Updated | Add sign, download, verify methods        |
| `app/Models/DigitalSignature.php`                                        | 📝 Updated | Add signer relationship                   |
| `app/Models/Surat.php`                                                   | 📝 Updated | Add digitalSignatures relationship        |
| `database/migrations/2026_01_16_000002_create_ecdsa_key_pairs_table.php` | ✨ Created | ECDSA keys table                          |
| `routes/web.php`                                                         | 📝 Updated | Add download/verify routes                |
| `resources/views/approval/waiting-signature.blade.php`                   | 📝 Updated | Add TTD Digital, Download, Verify buttons |
| `resources/views/approval/verify-signature.blade.php`                    | ✨ Created | Display signature verification            |

---

## API Response Examples

### Successful TTD

```json
{
    "message": "Surat telah ditandatangani dengan digital signature ECDSA.",
    "status": "success",
    "data": {
        "surat_id": 1,
        "approval_status": "signed_rektor",
        "signed_rektor_by": 2,
        "signed_rektor_at": "2026-01-16T10:30:00Z"
    }
}
```

### Signature Verification

```json
{
    "is_valid": true,
    "signer": "Dr. Ani Wijaya",
    "signed_at": "2026-01-16T10:30:00Z",
    "algorithm": "ECDSA with SHA-256",
    "curve": "prime256v1",
    "fingerprint": "a1b2c3d4e5f6..."
}
```

---

## Best Practices

1. **Key Management**
    - Generate key pair sekali, reuse untuk semua TTD
    - Backup private key securely (encrypted)
    - Don't expose private key di logs

2. **Signature Verification**
    - Always verify before trust
    - Log verification attempts
    - Alert pada failed verifications

3. **Document Integrity**
    - Keep original file immutable
    - Store signed copy separately
    - Keep audit trail complete

4. **Performance**
    - ECDSA signing is fast (~1-2ms)
    - File I/O is main bottleneck
    - Cache key pairs dalam session jika memungkinkan

---

## Troubleshooting

**Q: Private key decryption failed?**
A: Ensure APP_KEY di .env same saat key dibuat. Private key encrypted dengan APP_KEY.

**Q: Signature verification always false?**
A: Check bahwa file content sama dengan saat di-sign. Any modification invalidates signature.

**Q: Key pair not generated?**
A: Run `php artisan migrate` untuk create ecdsa_key_pairs table.

---

## References

- [phpseclib3 Documentation](https://phpseclib.com/)
- [ECDSA Standard](https://en.wikipedia.org/wiki/Elliptic_Curve_Digital_Signature_Algorithm)
- [Laravel Encryption](https://laravel.com/docs/encryption)
- [Digital Signature Best Practices](https://owasp.org/www-community/Signature)

---

## Next Steps (Optional Enhancements)

- [ ] Export certificate ke X.509 format
- [ ] Timestamp authority (TSA) integration
- [ ] Multi-signature support (multiple Rektor)
- [ ] Hardware security module (HSM) support
- [ ] Certificate revocation list (CRL)
- [ ] Long-term signature validation (LTV)
