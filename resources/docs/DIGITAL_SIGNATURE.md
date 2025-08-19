# Lightweight Digital Signature Strategy (Without PKI)

## 1. Problem Statement
In election systems, we need to certify precinct-level election returns (ERs). Traditional PKI-based digital signatures may be too complex, expensive, or infeasible in rural election environments. We propose lightweight alternatives that are still auditable, human-verifiable, and integration-friendly.

---

## 2. Signature Modes

### a) SMS OTP (Mobile + PIN)
- Election officers register their mobile numbers before election day.
- During certification, the system sends a one-time PIN (OTP) via SMS.
- The signature is a concatenation: **`<mobile_number>-<PIN>`**, e.g.:  
  `09171234567-ABCD`
- Expiration: OTP valid for ~5 minutes, single-use.

### b) QR / PDF417 Tokens
- Each inspector receives a printed ID card with a QR code or PDF417 barcode.
- The barcode encodes a **unique token**, e.g.:  
  `uuid-juan|SIG-JUAN-123`
- Scanned via a mobile app or webcam during certification.
- Advantage: Offline-capable, no SMS needed.

### c) Scratch Codes (Paper-Based)
- Each inspector is given a scratch card with pre-generated codes.
- Example: `uuid-maria|SCR-4567`
- Each code can be used only once.
- Cheap, resilient fallback if SMS or scanning fails.

---

## 3. Data Model for Signatures

Each signature entry has:
```json
{
  "id": "uuid-juan",
  "name": "Juan dela Cruz",
  "role": "chairperson",
  "signature": "09171234567-ABCD",
  "signed_at": "2025-08-19T09:30:00Z"
}
```

- `id`: Inspector ID from the precinct roster (source of truth).
- `signature`: Provided string (SMS PIN, QR token, scratch code).
- `signed_at`: ISO8601 timestamp (for audit).

---

## 4. Laravel Integration

### Command: `certify-er`
Supports multiple input styles:
```bash
# SMS PIN
php artisan certify-er --er=ER-123 "id=uuid-juan,signature=09171234567-ABCD"

# QR/PDF417 pipe format
php artisan certify-er --er=ER-123 "uuid-juan|SIG-JUAN-123"

# File input
php artisan certify-er --er=ER-123 --file=storage/signatures/demo.txt

# Directory input
php artisan certify-er --er=ER-123 --dir=storage/signatures
```

### Example File (`storage/signatures/demo.txt`)
```
id=uuid-juan,signature=09171234567-ABCD
id=uuid-maria,signature=SIG-MARIA-456
id=uuid-pedro,signature=SCR-7890
```

---

## 5. Security Best Practices
- **Replay protection:** Reject reused signatures for the same inspector.
- **Expiry:** OTPs and scratch codes must have time or usage limits.
- **Audit logs:** Record every attempt (success/failure) with timestamps.
- **Offline fallback:** QR/PDF417 and scratch cards allow certification without internet.

---

## 6. Example Workflow
1. Officers cast ballots and generate election return (`prepare-er`).
2. Officers certify the ER using one of the supported signature modes.
3. System saves signatures into `ElectionReturn.signatures` array.
4. Audit log stores all attempts for post-election validation.

---

## 7. Summary
This hybrid approach balances **simplicity** (no PKI) and **auditability** (recorded, verifiable signatures). By allowing multiple lightweight signature modes (SMS OTP, QR/PDF417, scratch codes), the system adapts to varying connectivity and resource constraints.
