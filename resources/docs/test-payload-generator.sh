#!/usr/bin/env bash
set -euo pipefail

# ───────────────────────────
# Config / defaults
# ───────────────────────────
MIN_CHUNK=600
MAX_CHUNK=2400

FILE=""
CODE=""
DESIRED=""
MAX_CHARS=""
OUT=""               # default: test-payload-<CODE>.txt
POST_URL=""
PERSIST="0"
PERSIST_DIR=""
AUTO_INSTALL="1"     # set to 0 via --no-install
QUIET="0"

# ───────────────────────────
# Helpers
# ───────────────────────────
log() { [ "$QUIET" = "1" ] || echo -e "$*"; }
err() { echo -e "$*" >&2; }
have() { command -v "$1" >/dev/null 2>&1; }

usage() {
  cat <<EOF
Usage: $0 --file <path.json> --code <ER_CODE> [options]

Options:
  --file <path>           Path to JSON payload (required)
  --code <ER_CODE>        ER code to embed (required)
  --desired <N>           Aim for ~N chunks (min ${MIN_CHUNK} / max ${MAX_CHUNK} chars per QR)
  --max <chars>           Force max chars per chunk (overrides --desired)
  --out <file>            Output file for ER lines (default: test-payload-<CODE>.txt)
  --post <url>            POST {"chunks":[...]} to decoder endpoint (e.g. http://localhost/api/qr/decode)
  --persist <0|1>         Include {"persist":1} in POST body (default 0)
  --persist-dir <name>    Include {"persist_dir":"<name>"} in POST body
  --no-install            Do not auto-install missing deps (jq, curl, python3)
  --quiet                 Less logging
  -h, --help              Show help

Notes:
- Compression: raw DEFLATE (exact match to PHP gzdeflate), Base64URL w/out padding.
- Chunk format: ER|v1|<CODE>|<i>/<N>|<payload>
EOF
}

# ───────────────────────────
# Parse args
# ───────────────────────────
while [ $# -gt 0 ]; do
  case "$1" in
    --file)         FILE="${2:-}"; shift 2;;
    --code)         CODE="${2:-}"; shift 2;;
    --desired)      DESIRED="${2:-}"; shift 2;;
    --max)          MAX_CHARS="${2:-}"; shift 2;;
    --out)          OUT="${2:-}"; shift 2;;
    --post)         POST_URL="${2:-}"; shift 2;;
    --persist)      PERSIST="${2:-}"; shift 2;;
    --persist-dir)  PERSIST_DIR="${2:-}"; shift 2;;
    --no-install)   AUTO_INSTALL="0"; shift;;
    --quiet)        QUIET="1"; shift;;
    -h|--help)      usage; exit 0;;
    *) err "Unknown arg: $1"; usage; exit 1;;
  esac
done

[ -n "$FILE" ] || { err "Missing --file"; usage; exit 1; }
[ -n "$CODE" ] || { err "Missing --code"; usage; exit 1; }
[ -f "$FILE" ] || { err "File not found: $FILE"; exit 1; }

OUT="${OUT:-test-payload-${CODE}.txt}"

# ───────────────────────────
# Auto-install deps (jq, curl, python3)
# ───────────────────────────
install_dep() {
  local cmd="$1" pkg_apt="$2" pkg_apk="$3" pkg_dnf="$4" pkg_pacman="$5" pkg_brew="$6"
  if have "$cmd"; then return 0; fi
  [ "$AUTO_INSTALL" = "1" ] || { err "Missing $cmd. Re-run with --no-install off or install manually."; exit 1; }

  log "→ Installing missing dependency: $cmd …"

  # choose package manager
  local SUDO=""
  if [ "$(id -u)" -ne 0 ] && have sudo; then SUDO="sudo"; fi

  if have apt-get; then
    $SUDO apt-get update -y && $SUDO apt-get install -y "$pkg_apt"
  elif have apt; then
    $SUDO apt update -y && $SUDO apt install -y "$pkg_apt"
  elif have apk; then
    $SUDO apk add --no-cache "$pkg_apk"
  elif have dnf; then
    $SUDO dnf install -y "$pkg_dnf"
  elif have yum; then
    $SUDO yum install -y "$pkg_dnf"
  elif have pacman; then
    $SUDO pacman -Sy --noconfirm "$pkg_pacman"
  elif have brew; then
    brew install "$pkg_brew"
  else
    err "No supported package manager found. Please install '$cmd' manually."
    exit 1
  fi

  have "$cmd" || { err "Failed to install $cmd."; exit 1; }
}

install_dep jq      jq          jq          jq          jq          jq
install_dep curl    curl        curl        curl        curl        curl
install_dep python3 python3     python3     python3     python      python@3 || true

# Some macOS installs the binary as `python3` via brew python@3
have python3 || { err "python3 still missing; please install it and retry."; exit 1; }

# ───────────────────────────
# Measure sizes and compress
# ───────────────────────────
RAW_BYTES=$(wc -c < "$FILE" | tr -d ' ')
RAW_KB=$(python3 - <<PY
n=$RAW_BYTES
print(f"{n/1024:.2f}")
PY
)

# raw DEFLATE + Base64URL (no padding) in one go
B64URL=$(python3 - <<'PY'
import sys, zlib, base64
data = sys.stdin.buffer.read()
deflater = zlib.compressobj(level=9, wbits=-15)  # raw DEFLATE (PHP gzdeflate)
comp = deflater.compress(data) + deflater.flush()
b64 = base64.urlsafe_b64encode(comp).rstrip(b'=')
sys.stdout.write(b64.decode('ascii'))
PY
 < "$FILE")

B64_LEN=${#B64URL}

# Calculate suggested chunk size if --desired given
if [ -n "${DESIRED:-}" ] && [ -z "${MAX_CHARS:-}" ]; then
  # Clamp to min..max window
  CHUNK_SIZE=$(python3 - <<PY
import math
b64_len = $B64_LEN
desired = max(1, int("$DESIRED"))
suggested = math.ceil(b64_len / desired)
print(max(${MIN_CHUNK}, min(suggested, ${MAX_CHUNK})))
PY
)
else
  CHUNK_SIZE="${MAX_CHARS:-1200}"
fi

# Compute DEFLATE size for display
DEFLATE_BYTES=$(python3 - <<'PY'
import sys, zlib
data = sys.stdin.buffer.read()
c = zlib.compressobj(level=9, wbits=-15).compress(data) + zlib.compressobj(level=9, wbits=-15).flush()
# Above creates two objects; better do once:
# (keeping simple; size difference is negligible for print purposes)
PY
 < /dev/null 2>/dev/null || true)

# We can compute deflate bytes properly:
DEFLATE_BYTES=$(python3 - <<'PY'
import sys, zlib
data = sys.stdin.buffer.read()
obj = zlib.compressobj(level=9, wbits=-15)
comp = obj.compress(data) + obj.flush()
print(len(comp))
PY
 < "$FILE")

DEFLATE_KB=$(python3 - <<PY
print(f"{int($DEFLATE_BYTES)/1024:.2f}")
PY
)

B64_KB=$(python3 - <<PY
print(f"{int($B64_LEN)/1024:.2f}")
PY
)

log "QR / Compression measurement"
log "-----------------------------"
log "File:              $FILE"
log "Raw JSON:          ${RAW_BYTES} bytes (${RAW_KB} KB)"
log "DEFLATE:           ${DEFLATE_BYTES} bytes (${DEFLATE_KB} KB)"
log "Base64URL:         ${B64_LEN} chars (${B64_KB} KB text)"
if [ -n "${DESIRED:-}" ]; then
  log ""
  log "desired_chunks=${DESIRED} → chunk_size ≈ ${CHUNK_SIZE}"
fi

# ───────────────────────────
# Chunk and write lines
# ───────────────────────────
chunk_lines() {
  local txt="$1" code="$2" size="$3"
  local total=$(( (${#txt} + size - 1) / size ))
  [ "$total" -gt 0 ] || total=1
  for ((i=0;i<${#txt};i+=size)); do
    idx=$((i/size+1))
    part="${txt:$i:$size}"
    printf 'ER|v1|%s|%d/%d|%s\n' "$code" "$idx" "$total" "$part"
  done
}

LINES=$(chunk_lines "$B64URL" "$CODE" "$CHUNK_SIZE")

printf "%s\n" "$LINES" > "$OUT"
COUNT=$(wc -l < "$OUT" | tr -d ' ')
log ""
log "Wrote $COUNT chunk line(s) → $OUT"

# ───────────────────────────
# Optional POST to decoder
# ───────────────────────────
if [ -n "$POST_URL" ]; then
  log ""
  log "POSTing chunks to: $POST_URL"

  # Build JSON array of strings safely with jq
  BODY=$(jq -Rs --argjson persist "$PERSIST" --arg dir "$PERSIST_DIR" '
    (split("\n") | map(select(length>0))) as $lines
    | {
        chunks: $lines
      }
    | if ($persist == 1) then
        . + { persist: true } else . end
    | if ($dir != "") then
        . + { persist_dir: $dir } else . end
  ' <<< "$LINES")

  # POST
  RESP=$(curl -sS -X POST \
    -H 'Content-Type: application/json' \
    --data "$BODY" \
    "$POST_URL") || { err "POST failed."; exit 1; }

  # Pretty print if jq exists
  if have jq; then
    echo "$RESP" | jq .
  else
    echo "$RESP"
  fi
fi
