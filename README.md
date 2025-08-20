# Project DNA

Project DNA is a **hybrid election system** that bridges physical voting with digital tallying.  
It provides two main interfaces:

1. **AR System Interface (Console Commands)**  
   Used by an Augmented Reality (AR) system to interact with the election backend — casting ballots, preparing election returns, certifying results, and performing preflight and finalization checks.

2. **Election Officer Interface**  
   Election officers do not directly manipulate the backend. Instead, they **view and monitor**:
    - Real-time **tallies** of votes
    - Reconstitution of ballots via **QR codes**
    - Precinct-level **Election Returns (ERs)**
    - Printing/exporting of election returns for audit and transmission


---

## 🔧 AR System Interface (Console Commands)

The AR system invokes **five console commands**:

- **PreflightStation** (`php artisan preflight-er`)  
  Ensures the system is ready before casting ballots. Verifies that election configs exist, the DB schema is initialized, and endpoints are reachable.

- **CastBallot** (`php artisan app:cast-ballot`)  
  Casts ballots either locally (direct DB insert) or via HTTP endpoint.

- **PrepareElectionReturn** (`php artisan prepare-er`)  
  Generates precinct-level election returns, computing tallies and presenting summaries.

- **CertifyElectionReturn** (`php artisan certify-er`)  
  Applies signatures from inspectors/election officers to the ER (manual entry, file input, or directory of files).

- **FinalizeElectionReturn** (`php artisan finalize-er`)  
  Closes the precinct, locks ballot acceptance, and freezes the election return.

---

## 📂 Election Configuration Files

Two configuration files define the **domain of the election**:

### `config/election.json`
Defines **positions** and **candidates**. Example:

```json
{
  "positions": [
    { "code": "PRESIDENT", "name": "President", "level": "national", "count": 1 },
    { "code": "VICE-PRESIDENT", "name": "Vice President", "level": "national", "count": 1 }
  ],
  "candidates": {
    "PRESIDENT": [
      { "code": "P_AAA", "name": "Alice A.", "alias": "AAA" },
      { "code": "P_BBB", "name": "Bob B.", "alias": "BBB" }
    ],
    "VICE-PRESIDENT": [
      { "code": "VP_CCC", "name": "Carol C.", "alias": "CCC" },
      { "code": "VP_DDD", "name": "Dan D.", "alias": "DDD" }
    ]
  }
}
```

### `config/precinct.yaml`
Defines the **precinct metadata** and **electoral inspectors**. Example:

```yaml
code: CURRIMAO-001
location_name: Currimao National High School
latitude: 17.993217
longitude: 120.488902
electoral_inspectors:
  - id: uuid-juan
    name: Juan dela Cruz
    role: chairperson
  - id: uuid-maria
    name: Maria Santos
    role: member
  - id: uuid-pedro
    name: Pedro Reyes
    role: member
```

---

## 👩‍💻 Example Usage

### Cast Ballots
```bash
php artisan app:cast-ballot --local "BAL-001|PRESIDENT:P_AAA;VICE-PRESIDENT:VP_CCC"
```

### Prepare Election Return
```bash
php artisan prepare-er
```

### Certify Election Return
```bash
php artisan certify-er --er=ER-123456 "uuid-juan|SIG123" "uuid-maria|SIG456"
php artisan certify-er --er=ER-123456 --file=storage/signatures/demo.txt
```

### Finalize Election Return
```bash
php artisan finalize-er --er=ER-123456
```

---

## ❓ FAQ

**Q: Do inspectors need PKI or smartcards?**  
A: No. Signatures are lightweight strings (e.g., `mobile+PIN`, `barcode text`, or `QR scan`). The system only records the string and timestamp.

**Q: What happens if the same ballot code is reused?**  
A: Duplicate codes with the same vote are idempotent (`OK`), while conflicting votes result in `SKIP` with a `409` conflict.

**Q: Can the AR system reinitialize a precinct?**  
A: Yes, via `PreflightStation`. Use the `--force` option to re-run initialization after `php artisan migrate:fresh`.

**Q: How are tallies verified?**  
A: `PrepareElectionReturn` regenerates counts directly from ballots in the database. Officers can view/print the report.

---

## 🚀 Project DNA Goal

Project DNA is designed for **trustworthy, auditable, and hybrid elections**:
- Physical ballots remain the source of truth.
- Digital tallies provide speed and monitoring.
- Election returns are **digitally signed** and **finalized** to prevent tampering.
