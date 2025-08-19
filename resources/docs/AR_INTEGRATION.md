# AR Integration Guide

This document describes how Augmented Reality (AR) systems can interact with the election backend using **five console commands**. These commands form the lifecycle: preflight → casting ballots → preparing the return → certifying → finalizing.

---

## 1. PreflightStation (`php artisan preflight-er`)
**Purpose:** Sanity check before casting any ballots.

**Key checks performed:**
- Presence of `config/election.json` and `config/precinct.yaml`.
- Database initialized with positions and precinct(s).
- Optional: checks connectivity to HTTP endpoints if configured.

**Usage:**
```bash
php artisan preflight-er
php artisan preflight-er --force       # initialize system if configs are present
php artisan preflight-er -v            # verbose, shows which checks passed/failed
```

**Exit codes:**
- `0` = success
- `1` = one or more checks failed

---

## 2. CastBallot (`php artisan app:cast-ballot`)
**Purpose:** Cast ballots line by line, either in local DB mode or via HTTP.

**Line format:**
```
BALLOT-CODE|POSITION:CANDIDATE[,CANDIDATE...];POSITION:CANDIDATE
```

**Examples:**
```bash
# Local mode, reads ballot specs from arguments
php artisan app:cast-ballot --local "BAL-001|PRESIDENT:SJ_002;SENATOR:JD_001,JL_004"

# With config overrides
php artisan app:cast-ballot --local --election=config/election.json --precinct=config/precinct.yaml "BAL-002|PRESIDENT:SJ_001"

# From STDIN (piped)
cat ballots.txt | php artisan app:cast-ballot --local
```

**Exit codes:**
- `0` = all lines OK or skipped
- `1` = one or more lines failed

---

## 3. PrepareElectionReturn (`php artisan prepare-er`)
**Purpose:** Aggregate ballots into a precinct-level Election Return (ER).

**Usage:**
```bash
php artisan prepare-er
```

**Sample output:**
```
📥 Generating Election Return...

🆔 Election Return ID: 1
🔐 Return Code       : ER-DNPT6VLVFF3N
📌 Precinct Code     : CURRIMAO-001
📍 Location          : Currimao National High School
🕒 Generated At      : Tue, Aug 19, 2025 10:00 AM

🧾 Total Ballots     : 120

👥 Electoral Board:
 - Chairperson: Juan dela Cruz
 - Member 1: Maria Santos
 - Member 2: Pedro Reyes
------------------------------------------------------------
🗳️  Position: PRESIDENT
1. Alice A.      60
2. Bob B.        60
🧮 Total Votes Cast for PRESIDENT: 120
```

**Exit codes:**
- `0` = success
- `1` = failure (e.g., no precincts found)

---

## 4. CertifyElectionReturn (`php artisan certify-er`)
**Purpose:** Electoral inspectors sign the election return with lightweight “digital signatures” (string-based).

**Accepted formats for `signatures` argument:**
- `id=uuid-juan,signature=ABC123`
- `uuid-juan|ABC123`
- via `--file` containing multiple lines
- via `--dir` scanning `*.txt` files
- from STDIN piped in

**Examples:**
```bash
php artisan certify-er --er=ER-DNPT6VLVFF3N "id=uuid-juan,signature=ABC123"
php artisan certify-er --er=DNPT6VLVFF3N "uuid-juan|ABC123" "uuid-maria|DEF456"
php artisan certify-er --er=DNPT6VLVFF3N --file=storage/signatures/demo.txt
php artisan certify-er --er=DNPT6VLVFF3N --dir=storage/signatures
cat storage/signatures/demo.txt | php artisan certify-er --er=ER-ABC123
```

**File format (`storage/signatures/demo.txt`):**
```
id=uuid-juan,signature=SIG-JUAN-123
id=uuid-maria,signature=SIG-MARIA-456
id=uuid-pedro,signature=SIG-PEDRO-789
```

---

## 5. FinalizeElectionReturn (`php artisan finalize-er`)
**Purpose:** Print the final ER and prevent further ballot casting or regeneration.

**Usage:**
```bash
php artisan finalize-er --er=ER-DNPT6VLVFF3N
```

---

## Config Files

### `config/election.json`
Defines positions and candidates.

```json
{
  "positions": [
    {"code": "PRESIDENT", "name": "President", "level": "national", "count": 1},
    {"code": "SENATOR", "name": "Senator", "level": "national", "count": 12}
  ],
  "candidates": {
    "PRESIDENT": [
      {"code": "SJ_002", "name": "San Juan", "alias": "SJ"},
      {"code": "LR_001", "name": "Lara R.", "alias": "LR"}
    ],
    "SENATOR": [
      {"code": "JD_001", "name": "John Doe"},
      {"code": "JL_004", "name": "Jane Lee"}
    ]
  }
}
```

### `config/precinct.yaml`
Describes the precinct metadata and inspectors.

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

## FAQ

**Q: Where should signature files go?**  
A: Place them under `storage/signatures/`, e.g. `storage/signatures/demo.txt`.

**Q: Can `--er` be used without the `ER-` prefix?**  
A: Yes, both `ER-DNPT6VLVFF3N` and `DNPT6VLVFF3N` are accepted.

**Q: What happens if I run `prepare-er` with no ballots?**  
A: It will show *“No ballots found for this precinct yet. Nothing to summarize.”*

**Q: How are digital signatures stored?**  
A: As plain strings (e.g., `09171234567-ABCD`). They can originate from SMS PINs, PDF417/QR codes, or simple tokens.

---

# End of Document
