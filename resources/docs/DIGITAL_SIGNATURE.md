# Lightweight Digital Signature Strategy (Without PKI)

## 1. Problem Statement
In election systems, we need to certify precinct-level election returns (ERs). Traditional PKI-based digital signatures may be too complex, expensive, or infeasible in rural election environments. We propose lightweight alternatives that are still auditable, human-verifiable, and integration-friendly.

---

## 2. Precinct Inspector IDs (Source of Truth)

Certification is always tied to **precinct officers** defined in `precinct.yaml`.  
For example:

```yaml
code: CURRIMAO-001
location_name: 'Currimao National High School'
latitude: 17.993217
longitude: 120.488902
electoral_inspectors:
  -
    id: uuid-juan
    name: 'Juan dela Cruz'
    role: 'chairperson'
  -
    id: uuid-maria
    name: 'Maria Santos'
    role: 'member'
  -
    id: uuid-pedro
    name: 'Pedro Reyes'
    role: 'member'
```

- Each inspector has a **unique ID** (`uuid-juan`, `uuid-maria`, `uuid-pedro`).
- These IDs are referenced in all certification commands.
- Without a valid inspector ID from the roster, signatures are rejected.

---

## 3. Signature Modes

### a) SMS OTP (Mobile + PIN)
- Officers register their mobile numbers pre-election.
- Certification sends an OTP via SMS.
- Signature format: **`<mobile_number>-<PIN>`**  
  Example: `09171234567-ABCD`

### b) QR / PDF417 Tokens
- Inspectors are issued printed cards with QR/PDF417 barcodes.
- Token format includes their ID:  
  Example: `uuid-juan|SIG-JUAN-123`

### c) Scratch Codes (Paper-Based)
- Scratch cards pre-printed with codes bound to their inspector ID.
- Example: `uuid-maria|SCR-4567`

All three modes tie **back to the IDs in `precinct.yaml`**, ensuring the roster is the root of trust.

---

## 4. Data Model for Signatures
```json
{
  "id": "uuid-juan",
  "name": "Juan dela Cruz",
  "role": "chairperson",
  "signature": "uuid-juan|SIG-JUAN-123",
  "signed_at": "2025-08-19T09:30:00Z"
}
```

- `id`: Must match an inspector ID from `precinct.yaml`.
- `signature`: String from OTP, QR token, or scratch code.
- `signed_at`: ISO8601 timestamp.

---

## 5. Laravel Integration

### Command: `certify-er`
Supports multiple input styles:

```bash
# SMS PIN
php artisan certify-er "id=uuid-juan,signature=09171234567-ABCD"

# QR/PDF417 token
php artisan certify-er "uuid-juan|SIG-JUAN-123"

# File-based
php artisan certify-er --file=storage/signatures/demo.txt

# Directory-based
php artisan certify-er --dir=storage/signatures
```

Example file (`storage/signatures/demo.txt`):
```
id=uuid-juan,signature=09171234567-ABCD
id=uuid-maria,signature=SIG-MARIA-456
id=uuid-pedro,signature=SCR-7890
```

---

## 6. Security Best Practices
- **Roster-binding:** Only IDs in `precinct.yaml` are accepted.
- **Replay protection:** Reuse of signatures for the same inspector is blocked.
- **Expiry:** OTPs/scratch codes expire quickly.
- **Audit logs:** Every signature attempt is logged (success/failure).
- **Offline fallback:** QR and scratch code certification works without connectivity.

---

## 7. Example Workflow
1. `precinct.yaml` defines inspectors and IDs.
2. Officers cast ballots (`cast-ballot`).
3. `prepare-er` generates the election return.
4. Each officer certifies the ER with their ID + signature:
   ```bash
   php artisan certify-er "uuid-juan|SIG-JUAN-123"
   php artisan certify-er "id=uuid-maria,signature=SCR-4567"
   ```
5. Signatures are persisted under `ElectionReturn.signatures`.
6. Audit logs ensure transparency.

---

## 8. Summary
This hybrid approach ensures that **precinct.yaml IDs anchor all certifications**, providing a simple but auditable digital signature system. By combining SMS OTP, QR/PDF417, and scratch cards, the system adapts to both online and offline precinct environments.
