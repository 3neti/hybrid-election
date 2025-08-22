# Augmented Reality (AR) Integration Guide

This document provides step-by-step instructions for setting up and running the **Hybrid Election System AR Integration**. It covers configuring precincts, defining election positions and candidates, casting ballots, preparing election returns, and certifying results with digital signatures.

---

## 1. Database Preparation

Before running any election commands, start fresh:

```bash
php artisan migrate:fresh
```

This clears and re-applies your migrations to ensure a clean slate.

---

## 2. Precinct Configuration

Each precinct must be explicitly configured with a unique code, location details, and electoral inspectors. This is typically stored in a file like `precinct.yaml`:

```yaml
code: CURRIMAO-001
location_name: 'Currimao National High School'
latitude: 17.993217
longitude: 120.488902
electoral_inspectors:
  - id: uuid-juan
    name: 'Juan dela Cruz'
    role: 'chairperson'
  - id: uuid-maria
    name: 'Maria Santos'
    role: 'member'
  - id: uuid-pedro
    name: 'Pedro Reyes'
    role: 'member'
```

**Explanation:**
- `code`: Short code for the precinct (e.g., `CURRIMAO-001`).
- `location_name`: Human-readable location of the precinct.
- `latitude` / `longitude`: Optional geolocation for mapping or audit.
- `electoral_inspectors`: List of teachers/staff serving as inspectors.
    - Each has a unique `id`, `name`, and `role`.
    - At least one **chairperson** and one **member** are required.
    - Inspectors later provide **digital signatures** to certify the ER.

---

## 3. Election Setup

Election positions and candidate lists are configured in `election.json`. This file establishes both the offices being contested and the list of candidates available for each position.

Example excerpt:

```json
{
  "positions": [
    {
      "code": "PRESIDENT",
      "name": "President of the Philippines",
      "level": "national",
      "count": 1
    },
    {
      "code": "SENATOR",
      "name": "Senator of the Philippines",
      "level": "national",
      "count": 12
    }
  ],
  "candidates": {
    "PRESIDENT": [
      { "code": "LD_001", "name": "Leonardo DiCaprio", "alias": "LD" },
      { "code": "SJ_002", "name": "Scarlett Johansson", "alias": "SJ" }
    ],
    "SENATOR": [
      { "code": "JD_001", "name": "Johnny Depp", "alias": "JD" },
      { "code": "ES_002", "name": "Emma Stone", "alias": "ES" }
    ]
  }
}
```

**Explanation:**
- **Positions**:
    - Each entry defines an office (`code`, `name`), level (`national`, `local`, `district`), and `count` (number of slots per ballot).
    - Example: `SENATOR` has a `count` of 12 → each voter may select up to 12 candidates.
- **Candidates**:
    - Each candidate has a `code`, `name`, and optional `alias`.
    - Codes (e.g., `SJ_002`) are used in **ballot casting strings**.

---

## 4. Preflight Initialization

Load precinct + election definitions into the system:

```bash
php artisan preflight-er --force
```

This seeds:
- Precincts (from `precinct.yaml`)
- Positions and candidates (from `election.json`)

---

## 5. Casting Ballots

Ballots are cast by encoding votes into a compact string:

```bash
php artisan app:cast-ballot --local "BAL-001|PRESIDENT:SJ_002;SENATOR:ES_002,JD_001"
```

Format:
```
BALLOT_CODE | POSITION_CODE : CANDIDATE_CODE [,CANDIDATE_CODE,...] ; ...
```

Examples:
```bash
php artisan app:cast-ballot --local "BAL-002|PRESIDENT:AJ_006;VICE-PRESIDENT:TH_001;SENATOR:ES_002,LN_048,AA_018"
```

---

## 6. Preparing the Election Return

Once ballots are cast:

```bash
php artisan prepare-er
```

This aggregates tallies into a single **Election Return (ER)**.

---

## 7. Certifying with Digital Signatures

Electoral inspectors must **certify** the ER by signing digitally:

```bash
php artisan certify-er "uuid-juan|ABC123"
php artisan certify-er "id=uuid-maria,signature=XYZ789"
```

- The system validates if at least one **chairperson** + one **member** signature exists.
- `--force` can override for testing, but in production this is strictly enforced.
- See [DIGITAL_SIGNATURE.md](DIGITAL_SIGNATURE.md) for a deeper dive.

---

## 8. Printing the Election Return

Generate a signed PDF:

```bash
php artisan er:print-pdf --paper=legal --payload=minimal
```

- `--paper`: Paper size (e.g., `a4`, `legal`).
- `--payload`: Use `minimal` for summary or `full` for detailed tallies.

---

## 9. Closing Balloting

Finally, mark the precinct as closed:

```bash
php artisan close-er
```

This stamps a `closed_at` timestamp into precinct metadata and locks further ballot submissions.

---

## 10. Developer Cheat Sheet

```bash
php artisan migrate:fresh
php artisan preflight-er --force
php artisan app:cast-ballot --local "BAL-001|PRESIDENT:AJ_006;VICE-PRESIDENT:TH_001;SENATOR:ES_002,LN_048,AA_018"
php artisan prepare-er
php artisan certify-er "uuid-juan|ABC123"
php artisan certify-er "uuid-maria|XYZ789"
php artisan er:print-pdf --paper=legal --payload=minimal
php artisan close-er
```

---

✅ With **precinct.yaml** and **election.json** configured properly, this flow simulates the **end-to-end election process**: setup, ballot casting, ER preparation, certification, printing, and finalization.
