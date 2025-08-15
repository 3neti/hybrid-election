# QR Tally: Secure, Fast, and Transparent Canvassing

> Notes:
> Introduce yourself and the purpose of this presentation — to present the QR Tally concept as a key innovation for election result consolidation.

---

# Objectives

- Introduce the QR Tally concept
- Demonstrate how Election Returns (ERs) can be stored and transferred without network transmissions
- Show how QR Tally enables ladderized canvassing with speed and transparency

> Notes:
> Keep it clear that this presentation focuses on QR Tally, not the full CAMVS.

---

# Statement of the Problem

- Current AES and manual systems both face **trust and speed issues**
- Electronic transmission introduces **security vulnerabilities**
- Manual consolidation is **time-consuming and error-prone**
- We need a **secure, verifiable, and efficient** way to transmit ER data without relying on a network

> Notes:
> Emphasize the balance between transparency and efficiency — and why QR Tally can bridge that gap.

---

# Hypothesis

If we encode the ER’s full digital data into multiple QR codes and print them on the ER itself,  
then canvassing centers can **decode and consolidate results instantly** without network transmission.

> Notes:
> Highlight that this approach is “manual-first” but tech-assisted.

---

# Observation

- Each ER’s digital JSON file ≈ **50 KB**
- Can be split into **5–16 QR codes** depending on camera quality
- Works like **supermarket barcode systems**
- Printed QR codes can be scanned at the canvassing center to instantly load accurate tallies

![Placeholder: QR code chunks diagram](placeholder-qr-chunks.png)

> Notes:
> Use a simple analogy (supermarket checkout) for easy comprehension.

---

# Process Flow

1. Ballot appreciation & tallying in precinct
2. ER generated (printed + digital JSON file)
3. JSON file split into multiple QR codes
4. QR codes printed on the ER
5. ER hand-carried to canvassing center
6. QR codes scanned → instant digital tally

![Placeholder: Process flow diagram](placeholder-process-flow.png)

> Notes:
> Stress that there’s **no electronic transmission**, hence lower hacking risk.

---

# Analysis

- **Security**: No network to intercept or hack
- **Speed**: Scanning multiple QR codes takes minutes
- **Accuracy**: Direct digital data from ER → minimal transcription errors
- **Scalability**: Works for thousands of precincts

| Criteria      | AES Transmission | Manual Tally | QR Tally |
|---------------|------------------|--------------|----------|
| Transparency  | Low               | High         | High     |
| Speed         | Medium            | Low          | High     |
| Cost          | High              | Low          | Low      |
| Security Risk | High              | Low          | Low      |

> Notes:
> This table can be a discussion focal point.

---

# Conclusion

- QR Tally offers a **secure, transparent, and efficient** alternative to network-based ER transmission
- Works within **manual election rules**
- Supports **ladderized canvassing** for faster aggregation at higher levels
- A **practical bridge** to future hybrid systems

> Notes:
> End with a strong emphasis on readiness and compatibility with existing law.

---

# Call to Action

- Pilot QR Tally in selected precincts in the next electoral cycle
- Train BEIs and canvassing staff
- Integrate with CAMVS for full effect

> Notes:
> Make it clear this is not just theory — it’s ready for testing.
