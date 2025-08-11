# Computer-Aided Manual Voting System (CAMVS)

White Paper – Version 1.51

### 1\. Executive Summary

This document presents the Computer-Aided Manual Voting System (CAMVS) as a modern, lawful, time-efficient, cost-efficient, and transparent approach to conducting elections in the Philippines. CAMVS preserves the core characteristics of a manual election as defined under existing Philippine laws, while introducing non-automated technological aids that enhance accuracy, speed, and verifiability in the appreciation, tallying, and canvassing of votes.

Legal Compliance

CAMVS is firmly grounded in the legal framework of manual voting, ensuring compliance with the Omnibus Election Code (Batas Pambansa Blg. 881) and relevant Commission on Elections (COMELEC) resolutions. It does not fall under the statutory definition of an Automated Election System (AES) in RA 8436 as amended by RA 9369, because:

• All appreciation, decision-making, and tallying remain manual, performed by the Board of Election Inspectors (BEIs).

• All processes are conducted in full public view, with no automation of voter intent interpretation.

Core Technological Aids

CAMVS introduces three complementary technologies that assist without replacing human judgment:

1\. Augmented Reality Ballot Appreciation – A widescreen public display, functioning like an “overhead projector on steroids,” ensures that every mark on the ballot is clearly visible to all observers.

2\. Immersive Data Capture – A high-speed “digital stenographer” records exactly what the BEIs see and decide, generating a structured, verifiable dataset without automating vote appreciation.

3\. QR Tally – An embedded QR code printed on the Election Return (ER) contains its full contents in a compressed JSON format, digitally signed for integrity, ensuring that printed and electronic records are identical.

Time Efficiency

• Precinct Level:

Traditional manual elections can take 8–12 hours from poll closing to precinct transmission; CAMVS reduces this to 5–7 hours through instant summation and QR-based ER printing.

• Municipal to National Canvassing:

With QR Tally scanning and instant validation, canvassing time is reduced by 50–70% versus re-encoding methods.

• Overall Impact:

CAMVS shortens total election night operations by several hours per precinct and days in aggregate at the national level, without sacrificing transparency.

Cost Efficiency

• First Election Deployment: Comparable to AES costs (≈ ₱21.0–₱21.7B vs ₱22B AES) but with higher transparency and no centralized server dependency.

• Subsequent Cycles: Costs drop to ₱4.26–₱4.37B due to hardware reusability, creating tens of billions in long-term savings over multiple election cycles.

• Risk Reduction Savings: By minimizing server-side vulnerabilities, CAMVS reduces the cost of contested results and post-election audits.

Transparency and Trust

• Fully Observable – Every step, from ballot appreciation to QR verification, happens in public view.

• Verifiable at Multiple Stages – Printed ERs, QR Tallies, and canvassing displays allow independent verification by watchers, parties, and the public.

• Tamper-Resilient – Decentralized, physical transmission of signed ERs and QR Tallies reduces the risk of centralized manipulation.

Pathway to a Hybrid Future

While CAMVS is designed as a fully lawful manual process, its modular architecture allows for incremental adoption of automation where appropriate, paving the way for a future Hybrid Election System.

• Short Term: Immediate deployment as a manual-compliant, tech-assisted solution.

• Medium Term: Gradual integration of automated tally aggregation or transmission modules, still keeping appreciation manual.

• Long Term: Seamless transition to a hybrid model that balances transparency with efficiency, retaining public trust while scaling capacity for larger or more complex electoral exercises.

In short: CAMVS delivers manual transparency with modern efficiency, cuts counting and canvassing time by up to half, and reduces recurring election costs by over 80% after initial deployment—all while staying within current legal boundaries and offering a clear migration path to a hybrid model. It is the most balanced, practical, and future-ready election reform available today.

### 2\. Legal Basis for the Manual Conduct of Elections in the Philippines

The Philippine election framework recognizes two modes of conducting elections:

1\. Manual (human-led) elections, as governed primarily by the Omnibus Election Code (Batas Pambansa Blg. 881) and related issuances.

2\. Automated elections, as introduced under Republic Act No. 8436 (1997) and later expanded through Republic Act No. 9369 (2007).

It is critical to note that manual elections remain fully recognized in law. The enabling laws for the automated election system (AES) did not repeal the provisions of the Omnibus Election Code on manual voting, counting, and canvassing. They operate in parallel, and both remain valid legal frameworks.

#### 2.1 Manual Elections Under the Omnibus Election Code

• Section 210–211 of the Omnibus Election Code outlines the procedure for manual counting of votes by the Board of Election Inspectors (BEIs) at the precinct level.

• Section 231–233 governs the manual canvassing of election returns by the Board of Canvassers (municipal, provincial, and national levels).

• The Code does not prohibit the use of computational aids (such as calculators) to assist BEIs or BOCs in totaling votes, spoiled ballots, and number of votes cast, as long as:

• The appreciation of the ballot remains a human process.

• The final tally is determined and certified by human officials.

• This long-standing practice has been observed in multiple electoral exercises prior to the AES era.

#### 2.2 Automated Elections and Coexistence with Manual Processes

• RA 8436 (1997) introduced the concept of the Automated Election System but did not abolish manual elections.

• RA 9369 (2007) amended RA 8436, making AES the primary mode for national and local elections where applicable, but manual elections are still legally permissible:

• In areas where AES cannot be deployed.

• In cases where manual counting is prescribed by law, COMELEC resolution, or by necessity (e.g., failure of AES in a given precinct or locality).

• Nowhere in RA 9369 is there a blanket repeal of manual election provisions. Instead, it assumes that manual methods remain as a fallback and recognized mode.

#### 2.3 Manual Aids vs. Automation

• In manual elections, aids such as calculators are not considered automation.

• A calculator is a computational aid—like a modern equivalent of an abacus or a tally sheet—used solely to assist humans in computing totals.

• The CAMVS applies the same principle, with:

• Augmented Reality Ballot Appreciation → manual reading of the ballot aided by a visual tool.

• Immersive Data Capture → human-verified input recorded using a computer.

• QR Tally → digital equivalent of a manually computed total, encoded for secure transmission.

• These aids do not remove human judgment from the process. The BEI or canvassing board still performs:

• Full appreciation of each ballot.

• Direct oversight and certification of results.

• Custody and signing of physical election returns.

#### 2.4 Addressing the Misconception

Some claim that manual elections can only be invoked if AES is not operational in a given geographic area. This interpretation is incomplete.

While RA 9369 does state that manual counting is used when AES cannot be implemented in certain areas, it does not prohibit COMELEC (or Congress via law) from directly prescribing a manual process in the first instance. The Omnibus Election Code provisions on manual elections remain in force and can be applied when so ordered.

#### 2.5 Citation Matrix — Manual vs Automated Provisions

| Law / Provision | What It Says | Relevance to CAMVS |
| --- | --- | --- |
| Omnibus Election Code (Batas Pambansa Blg. 881) — Title V, Sec. 210-216 | Prescribes manual voting and counting procedures, including use of ballots, tally sheets, and manual canvassing at various levels. | CAMVS adheres to this structure — same single ballot, same manual appreciation, same tallying process — but introduces computer aids similar to calculators for efficiency. |
| --- | --- | --- |
| Omnibus Election Code — Sec. 211 | Defines rules for appreciation of ballots (marks, stray votes, etc.) in full view of the public. | CAMVS’ augmented reality appreciation is done publicly, mirroring manual appreciation, only aided by cameras and computer vision. |
| --- | --- | --- |
| RA 8436 (1997) — Sec. 6 | Authorizes adoption of an automated election system for the process of voting, counting, and canvassing. | Does not repeal manual provisions — automation is authorized, not mandated exclusively. |
| --- | --- | --- |
| RA 9369 (2007) — Sec. 5 | Amends RA 8436 to make AES the “primary” method but retains the possibility of manual elections “in case of failure of the AES” or if declared by the Commission. | CAMVS can operate fully manual under the Omnibus Election Code. “Primary” ≠ “exclusive” — law does not prohibit manual elections outside failure scenarios if COMELEC adopts it. |
| --- | --- | --- |
| COMELEC Resolutions on AES | Define specific AES parameters (PCOS/VCM). Manual processes still used in special elections and failure scenarios. | CAMVS fits within existing COMELEC authority to determine election method per area. |
| --- | --- | --- |

#### 2.6 Operational Analogy Chart — Old vs CAMVS

| Old Manual Election Tool | CAMVS Equivalent | Function |
| --- | --- | --- |
| Ballot Box | Ballot Box | No change — physical storage for filled ballots. |
| --- | --- | --- |
| Human eyes + tally sheet | Augmented reality ballot appreciation | Same appreciation rules, aided by computer vision for accuracy and speed. |
| --- | --- | --- |
| Manual writing in tally sheet | Immersive Data Capture | Manual results recorded instantly into structured digital form. |
| --- | --- | --- |
| Calculator (BEIs & BOCs) | Text-based Data Interchange Totalizer | Sums up votes — same math, only faster and with audit trail. |
| --- | --- | --- |
| ER on paper | ER on paper + QR Tally | Adds scannable structured result for optional blockchain or ladderized canvassing. |
| --- | --- | --- |

Preemptive Rebuttal List — Why CAMVS is Manual, Not Automated

| Possible Objection | Our Rebuttal |
| --- | --- |
| “Only failure of AES allows manual elections.” | The law authorizes AES but does not prohibit COMELEC from adopting manual systems outside AES failure. |
| --- | --- |
| “Blockchain makes it automated.” | Blockchain use is optional and only for canvassing transparency, not for vote appreciation or counting at precinct level. |
| --- | --- |
| “Public can’t verify machine counting.” | Public sees the appreciation process exactly as in manual elections; computer output is instantly verifiable against physical tally sheets. |
| --- | --- |
| “It’s a hybrid system.” | Hybrid means both manual and automated for the same process. CAMVS’ core process — voting, appreciation, tallying — is entirely manual; computers are only for recording and summing. |


### 3\. Computing Aids in Manual Elections

(From Calculators to Augmented Reality Viewing & Recording Tools)

#### 3.1 What “aid” means in a manual election

In traditional manual elections, Board of Election Inspectors (BEIs) and Boards of Canvassers (BOCs) relied on simple calculators to total votes, tally spoiled ballots, and verify arithmetic. These devices never interpreted ballots or decided voter intent—they simply sped up math and reduced human error in computation.

The core manual process—humans appreciating each ballot and certifying results—remained untouched. The tool was there to assist, not decide.

#### 3.2 From Calculator to Augmented Reality Ballot Appreciation (ARBA)

Now, imagine evolving that humble calculator into a shared augmented reality platform—a system that makes each ballot visible to everyone in the precinct, in real time, with digital clarity and contextual cues.

This is ARBA:

• High-resolution ballot projection: A live video feed places the ballot on a large display visible to all watchers and the public, even from the back of the room.

• Dynamic overlays: Real-time highlighting of ovals, candidate names, and tally marks mirrors what the BEI is confirming—every decision is visually reinforced.

• Zoom & focus tools: BEIs can magnify specific areas for scrutiny, ensuring doubtful marks are examined in front of everyone.

• Synchronized logging: Every human-confirmed vote is recorded instantly in a structured format, forming a digital “shadow record” of the paper tally.

Human authority remains absolute:

• The AR system never auto-decides; it pauses on doubtful marks until BEIs make a ruling.

• Once confirmed, the decision is highlighted on screen and added to the public running tally.

#### 3.3 Why ARBA Changes the Game

Transparency

• Everyone in the precinct sees exactly the same thing at the same moment—no hidden screens, no private data.

• Disputes are resolved instantly in public view.

Accuracy

• Eliminates multiple re-encodings of vote counts from paper to ER; the BEI’s confirmation is the system’s input.

• Creates a verifiable, structured digital record alongside the official paper ER.

Efficiency without automation

• Cuts precinct processing time by hours while preserving manual appreciation.

• Produces ERs that are clean, legible, and ready for instant QR-based verification.

#### 3.4 Other Modern Aids That Work with ARBA

• Human-controlled tally updates: As BEIs confirm votes, running totals update live for public view; every change is logged.

• QR-Coded Election Returns: When counting ends, a QR Tally is generated containing the entire ER content in a compressed, verifiable format. Printed directly on the ER, this acts as a digital seal to ensure the paper and data are identical.

• Immediate public verification: Any watcher can scan the QR code to confirm the match before the ER leaves the precinct.

• Printer integration: Outputs a clean, signed ER—reducing transcription errors.

#### 3.5 Comparative View

| Aspect | Legacy Manual w/ Calculator | Manual w/ Augmented Reality Aid |
| --- | --- | --- |
| Ballot Viewing | Only BEIs see ballot directly | Entire precinct sees ballot in high-resolution projection |
| --- | --- | --- |
| Decision Logging | Manual on paper | Instant digital logging alongside paper |
| --- | --- | --- |
| Error Checking | Post-count arithmetic check | Real-time validation during count |
| --- | --- | --- |
| Transparency | Limited to verbal reading | Public visual + audible confirmation |
| --- | --- | --- |
| Speed | Hours for full precinct | Significant time savings without losing manual control |


#### 3.6 Hypothetical Precinct Scenario with ARBA

Precinct: 123-A (Currimao, Ilocos Norte)

BEI Chair: Ms. Garcia

1\. The ballot box is opened; watchers are in place.

2\. A ballot is placed under the AR projection device; it appears on a large screen visible to all.

3\. The BEI reads the marks aloud; the AR system highlights the corresponding choices in real time.

4\. Doubtful marks are zoomed in, discussed, and resolved by the board in public view.

5\. Once confirmed, votes are added instantly to the running tally visible on screen.

6\. At the end, the system prints the ER and generates its QR Tally.

7\. Watchers scan the QR to confirm the printed ER is identical to the digital record.

8\. The ER is signed, sealed, and hand-carried to the municipal canvassing center.

The printed ER remains the legal record; the QR Tally makes verification, canvassing, and encoding faster and more reliable.

### 4\. Proposed System: Computer-Aided Manual Voting System (CAMVS)

The Computer-Aided Manual Voting System (CAMVS) is the deployment framework for Augmented Reality Ballot Appreciation™ (ARBA) technology in every stage of the manual electoral process. It takes the Philippines’ traditional, publicly transparent manual voting and counting system and augments it—not automates it—with modern visual and data-capture tools.

ARBA is the core innovation of CAMVS. It ensures that everyone in the precinct—BEIs, watchers, media, voters—sees the exact same ballot in real time, no matter their distance from the counting table. This shared, true-to-life ballot projection modernizes the “public square” nature of ballot appreciation, eliminating crowding, obstructed views, and subjective disputes over what was marked.

CAMVS does not replace human judgment. Every critical step—interpreting voter intent, tallying votes, resolving doubts, signing election returns—remains manual, public, and paper-based. ARBA’s role is to:

• Make the ballot view undeniable and universally visible.

• Eliminate clerical and transcription errors by pairing visual appreciation with immersive, structured data capture.

• Preserve a full, verifiable audit trail—both paper and digital—at every canvassing level.

From the first ballot appreciated to the last tally consolidated at the national level, humans decide, the public sees, the paper rules—while ARBA ensures that the truth of what was seen and agreed upon is faithfully recorded and preserved.

#### 4.1 Ballot Appreciation™ (ARBA: large-screen, human-verified viewing)

Purpose

Recreate the public ballot-reading tradition at modern scale, so no one has to take anyone’s word for it—everyone sees the same thing, clearly, as it is appreciated.

What it does

• Uses Augmented Reality Ballot Appreciation™ (ARBA) to capture the actual ballot and project it on a large, shared display in real time.

• Shows contests and marks in high-resolution, true-to-life color, with visual guides highlighting the contest under appreciation.

• Displays candidate lists alongside the ballot view, updating instantly when the BEI confirms a vote.

• Allows the BEI to accept or override suggested readings; doubtful marks are resolved by the board in public view.

What it does not do

• Does not decide voter intent.

• Does not finalize results without human confirmation.

• Does not transmit results electronically from the precinct.

Human control & transparency

• BEI sets the pace: next ballot, next contest, accept/reject decision.

• Public visibility means watchers can object immediately—no delay, no hidden steps.

Outputs

• Live, public ARBA view of each ballot.

• Decision log of all accepted and overridden entries.

• Real-time running tally visible to everyone.

Failure modes & fallbacks

• If ARBA equipment fails, revert to traditional manual appreciation.

#### 4.2 Immersive Data Capture (structured recording of human decisions)

Purpose

Record BEI-approved decisions in real time—like a “very fast stenographer”—while keeping the ARBA ballot view active so the public watches the recording happen.

What it does

• Captures decisions in a structured, human-readable digital format (e.g., JSON) as the tally progresses.

• Links every digital entry to the ARBA-captured ballot image for audit trail purposes.

• Includes metadata: precinct identifiers, BEI identifiers, timestamps, and per-candidate totals.

• Feeds directly into QR-Tally generation after final BEI approval.

What it does not do

• Does not record unaccepted suggestions.

• Does not replace the paper ER as the official record.

Human control & transparency

• BEI reviews and corrects before finalizing.

• Public sees the same numbers on the ARBA screen before printing.

Outputs

• Printed ER (paper remains authoritative).

• Structured precinct result file linked to ARBA visual evidence.

Failure modes & fallbacks

• If capture fails, paper ER is completed manually; digital re-entry is done later.

#### 4.3 QR-Tally (verbatim, scannable image of the ER)

Purpose

Encode the exact content of the ER—as confirmed in the ARBA view—into a single QR code printed on the ER. Anyone can verify instantly that paper and digital match.

Design notes

• Encodes compressed, structured ER data with checksum and version header.

• Generated from the same digital file the public saw in ARBA during appreciation.

• QR familiarity ensures adoption by watchers, media, and auditors.

Usefulness

• Anyone with a smartphone can instantly verify ER authenticity.

• Prevents transcription errors at canvassing by tying digital to paper at the source.

Chain of custody

• Paper ER is the legal record.

• QR-Tally is the portable, verifiable companion.

Optional transparency extension

• QR contents can be published for public audit without replacing manual transmission.

#### 4.4 Text-based Data Interchange Totalizer™ (human-controlled totaling & canvassing)

Purpose

Replace manual re-encoding of precinct results with QR-Tally imports—keeping canvassing ladderized, transparent, and, if desired, publicly viewable in ARBA.

What it does

• Reads QR-Tally to import totals directly.

• Displays imported totals alongside scanned paper ER for verification.

• Generates new QR-Tally at each canvassing level (Municipal → Provincial → National).

What it does not do

• Does not auto-proclaim winners.

• Does not depend on electronic transmission; QR-Tally is hand-carried.

Human control & transparency

• Canvass boards review totals before signing.

• Paper ER prevails if QR data is damaged.

Outputs

• Signed canvassing reports.

• QR-Tally for next canvassing level.

Failure modes & fallbacks

• Damaged QR codes replaced by manual encoding from paper ER.

4.5 Putting it all together (ARBA in every phase)

Precinct

• Ballots appreciated in ARBA on a large screen.

• BEI confirms readings; Immersive Data Capture records results.

• ER printed and signed; QR-Tally printed and attached.

• Paper ER hand-carried to canvass.

Municipal

• QR-Tally scanned to import totals.

• Canvass displayed for public verification (optional ARBA).

• Signed municipal canvass issued with new QR-Tally.

Provincial

• Municipal QR-Tallies consolidated; results verified.

• Provincial QR-Tally issued.

National

• Provincial QR-Tallies consolidated.

• Proclamation after board review and signature.

In every step: Humans decide, the public sees, the paper rules—while ARBA ensures everyone is looking at the same thing, in real time, with zero ambiguity. The technology’s role is to make the visible undeniable.

### 5\. Manual Voting Experience: Fully Preserved, Publicly Enhanced

At its heart, the Computer-Aided Manual Voting System (CAMVS) does not change the soul of Philippine elections—it preserves it exactly as the law and tradition dictate. Every core democratic safeguard of the manual process remains intact:

• Voting is manual – Every voter physically marks a single paper ballot containing all elective positions. There are no machines interpreting voter intent—only the voter’s hand and pen decide the mark.

• Counting is manual – Every ballot is read aloud, appreciated, and ruled upon by the Board of Election Inspectors (BEIs) in public view. Augmented Reality Ballot Appreciation™ (ARBA) only enlarges the view and records the decision—it does not decide the vote.

• Tallying is manual – The BEIs write tallies onto the official election return (ER). The ARBA display and digital capture merely mirror what’s already been publicly agreed upon.

• Transmission is manual – Instead of transmitting results over a private electronic network, CAMVS uses the centuries-old method: the signed paper ER is physically delivered by election officials to the municipal canvassing center.

The QR-Tally travels alongside it as a verification tool, never as a replacement.

• Canvassing is manual – At every canvassing level—municipal, provincial, national—boards open, read, and consolidate results in public session. QR-Tally imports are used for speed and accuracy, but final approval and signing are by human boards, with the paper ER as the legal basis.

The Step-by-Step Experience (Precinct to Canvass)

1\. One ballot, one design, one vote per contest – Every voter receives a single, standardized ballot, the same for all precincts nationwide, ensuring uniformity and security.

2\. Manual marking by the voter – Voters shade ovals or write names as instructed; no digital interface is involved.

3\. Deposit in a secure ballot box – After marking, each ballot is placed in the precinct’s sealed ballot box, under constant public view.

4\. Opening of the ballot box – Once voting ends, the BEI opens the ballot box in public, and the count begins.

5\. Ballot appreciation in ARBA – Each ballot is projected on a large screen via Augmented Reality Ballot Appreciation™, so every watcher and voter sees exactly what the BEI sees. Disputed marks are resolved publicly before tallying.

6\. Manual tally writing – The BEI records each decision on the official ER while the ARBA display mirrors the entries for the audience.

7\. Multiple ER copies – Identical ERs are produced; one copy goes to each watcher’s party, one is delivered to the municipal canvass, and one (with the ballots) is sealed for safekeeping.

8\. Manual transmission – ERs and ballots are physically transported to canvassing centers. The QR-Tally, printed on each ER, allows instant verification at arrival but cannot override the paper ER.

9\. Manual canvassing at higher levels – Municipal, provincial, and national boards manually open and confirm each ER. QR-Tally imports help avoid transcription errors, but the paper ER remains the official and final record.

In short: CAMVS + ARBA modernizes visibility without mechanizing democracy.

The public, paper-based, human-run election process is untouched—only now, it’s more transparent, verifiable, and faster to audit than ever before.

### 6\. Why CAMVS Is Manual—By Design

Elections under Philippine law may be manual or fully automated. CAMVS is intentionally and unequivocally manual—not as a rejection of technology, but as a conscious choice to preserve human judgment, public oversight, and verifiable records at every stage.

In CAMVS, the core acts of democracy remain exactly where they belong:

• Ballot appreciation: Humans—Board of Election Inspectors—read, interpret, and decide each mark.

• Tallying: Humans approve every vote before it appears on any tally sheet, printed or projected.

• Result consolidation: Humans verify totals at each canvassing level before signing and sealing results.

Technology is there only to support, never to substitute, these human functions:

• Just as calculators once sped up vote summations without deciding results, CAMVS uses Augmented Reality Ballot Appreciation™ and other aids to make the process faster, clearer, and less error-prone—without altering the manual nature of the decision-making.

• The public sees everything as it happens. No private terminals. No hidden algorithms. No “black box” vote counting.

This manual-first approach distinguishes CAMVS from fully automated systems:

• In automated systems, ballots are read, interpreted, and tallied by machines, with human participation largely in setup, oversight, or exception handling.

• In CAMVS, machines never interpret marks, never finalize results, and never transmit votes. Humans do the work, technology makes it visible and verifiable, and paper remains the official record.

The result is the best of both worlds:

• The trust of manual elections—because the people you see counting are the ones deciding.

• The efficiency of modern aids—because the same decisions are instantly reflected in clear, tamper-evident records.

CAMVS is not “less modern” than automation—it is modern transparency in a manual framework, combining centuries-old safeguards with 21st-century clarity.

### 7\. Path to a Hybrid Election System (HES) via Distributed Ledger Integration

CAMVS is designed to operate entirely within the current legal framework of a manual election, but it also lays the foundation for a next-generation Hybrid Election System (HES) — one that preserves the human-led transparency of precinct-based manual counting while using distributed, tamper-resistant data consolidation to strengthen trust in the canvassing process.

#### 7.1 Why We Avoid Server-Based Transmission

In the current Automated Election System (AES), vote counting machines (VCMs/PCOS/ACMs) send results to central servers for consolidation. While precinct-level scanning is visible to watchers, the central server stage happens out of public view and represents a high-value target for potential manipulation.

CAMVS deliberately avoids direct server-based transmission:

• Results are physically hand-carried from the precinct to the municipal canvassing center.

• This mirrors traditional manual elections, keeping aggregation under local, human oversight.

#### 7.2 The Distributed Ledger Alternative

Physical transmission eliminates central server risks, but ERs in transit can still face localized tampering or disputes.

A distributed ledger (blockchain) can address this by:

1\. Local Sealing – After the ER is finalized and printed with its QR Tally, BEIs post the QR Tally’s raw data to a municipal blockchain node.

2\. Ladderized Transmission –

• Precinct → Municipal node

• Municipal → Provincial node

• Provincial → National node

3\. Distributed Copies – Each node maintains an immutable copy of results within its jurisdiction.

4\. Public Verification – Any citizen, media, or watchdog can scan a QR code to confirm the posted result matches the printed ER.

This replaces upload-to-one-central-server with post-to-many-independent-nodes, reflecting the existing hierarchical canvassing flow.

#### 7.3 How CAMVS Fits into HES

CAMVS already produces:

• A structured digital file of the precinct tally through Augmented Ballot Appreciation™.

• A QR Tally ensuring the printed ER and its digital equivalent are identical.

In HES, instead of sending this to a single COMELEC server, it is posted on a distributed ledger at the municipal level, then ladderized upward through provincial and national nodes.

#### 7.4 Transparency Benefits

• Human-Led Counting – Votes remain read aloud and tallied in full public view.

• Immutable Public Record – Blockchain postings cannot be altered undetected.

• Multiple Verification Points – Every stage (precinct, municipal, provincial) has both printed and blockchain-backed records.

• No “Black Box” – Consolidation is transparent and verifiable by all.

#### 7.5 Legal Considerations

Under the current Omnibus Election Code and RA 9369, elections are classified as:

• Fully manual (human counting, transmission, canvassing), or

• Fully automated (machine counting, transmission, canvassing).

A hybrid model is not explicitly recognized.

Nationwide adoption of CAMVS → HES would require Congress to:

1\. Define hybrid systems as a valid third category.

2\. Mandate public verifiability of transmitted tallies (QR + open ledgers).

3\. Affirm that printed ERs prevail over electronic postings in case of discrepancy.

#### 7.6 Sample Amendment Language

Section X: Hybrid Election Systems

The Commission on Elections is authorized to adopt a hybrid election system wherein the counting and appreciation of ballots shall be conducted manually at the precinct level, in full public view, and the resulting election returns shall be physically transmitted to the appropriate canvassing board.

The official results may also be transmitted or posted electronically, provided that:

1\. Such transmission is via a distributed, tamper-resistant ledger or other verifiable means approved by the Commission.

2\. The electronic record shall include a verbatim, structured-data representation of the election return, secured by cryptographic means and verifiable by the public.

3\. In case of discrepancy between the printed election return and the electronic record, the printed return shall prevail.

#### 7.7 The Road Ahead

CAMVS is not just a technology reform — it is a trust reform. By retaining manual precinct-level counts while enabling verifiable digital posting, it:

• Reinforces public confidence in results.

• Deters tampering at scale.

• Provides a lawful, auditable bridge toward a legislated hybrid system.

In short:

• CAMVS is the starting point.

• Blockchain-backed HES is the destination.

• Legislative change is the path forward.

### 8\. Comparative Scenarios for Vote Counting and Canvassing

This section outlines four election conduct scenarios — from the most traditional to the most technology-dependent — showing the process from vote casting in the precincts to canvassing at the municipal, provincial, and national levels.

Each scenario covers method, tools, estimated time, and transparency considerations, enabling lawmakers, election managers, and stakeholders to compare operational efficiency and integrity safeguards.

#### 8.1 Full Manual Elections (Traditional System)

Process Flow:

1\. Vote Casting – Voters manually fill out ballots.

2\. Counting – Ballots read aloud by the Board of Election Inspectors (BEIs), votes tallied manually on paper election returns (ERs).

3\. Precinct → Municipal – Physical delivery of ERs and ballots to the municipal canvassing center.

4\. Municipal Canvassing – ERs manually encoded into municipal tally sheets.

5\. Municipal → Provincial – Physical delivery of Certificates of Canvass (COCs) to the provincial canvassing center.

6\. Provincial Canvassing – COCs manually encoded into provincial tally sheets.

7\. Provincial → National – Physical delivery of provincial COCs to the national canvassing center.

8\. National Canvassing – COCs manually encoded into national tally sheets.

Estimated Time & Motion:

• Precinct count: 6–10 hrs

• Municipal consolidation: 6–8 hrs

• Provincial consolidation: 6–8 hrs

• National consolidation: 6–8 hrs

• Total: 24–34 hrs over 2–3 days.

Key Issues:

• High human error risk in repeated manual encoding.

• Slowest method overall.

• Heavy reliance on physical security for both ERs and COCs.

#### 8.2 Computer-Aided Manual Elections (CAMVS)

Process Flow:

1\. Vote Casting – Voters manually fill out ballots.

2\. Counting –

• BEIs manually examine each ballot.

• Augmented Reality (AR) technology automatically “reads” the mark on the ballot (acting as a calculator) and digitally encodes results into a structured JSON file representing the Electronic Election Return (E-ER).

• Process happens simultaneously with the physical tallying on paper.

3\. Finalization – The JSON E-ER is digitally signed, generating a cryptographic hash or facsimile signature.

4\. QR Tally Creation – The signed E-ER is encoded into a QR Tally.

5\. Printing – The physical ER is printed with the QR Tally and digital signature.

6\. Precinct → Municipal – Physical delivery of printed ERs with QR Tallies and ballots to the municipal canvassing center.

7\. Municipal Canvassing – QR Tallies scanned to instantly populate municipal totals; watchers verify against printed ERs.

8\. Municipal → Provincial – Physical delivery of municipal COCs with QR Tallies.

9\. Provincial Canvassing – QR Tallies scanned to populate provincial totals; verified against printed COCs.

10\. Provincial → National – Physical delivery of provincial COCs with QR Tallies.

11\. National Canvassing – QR Tallies scanned to populate national totals; verified against printed COCs.

Estimated Time & Motion:

• Precinct count: 5–7 hrs (faster due to real-time AR counting)

• Municipal consolidation: 2–3 hrs

• Provincial consolidation: 2–3 hrs

• National consolidation: 2–3 hrs

• Total: 11–16 hrs, potentially allowing same-day national totals.

Key Advantages:

• Significant time savings in canvassing due to QR-based consolidation.

• Reduced encoding errors through simultaneous AR-based reading and JSON encoding.

• Public verifiability of QR Tallies without sacrificing the physical record.

#### 8.3 Hybrid Election System (HES)

Process Flow:

1\. Vote Casting – Voters manually fill out ballots.

2\. Counting – Same as CAMVS (AR-assisted counting, E-ER creation, QR Tally generation, printed ERs).

3\. Blockchain Posting – QR Tallies (and optionally signed E-ER data) posted to a distributed blockchain ledger accessible to election bodies, political parties, and accredited observers.

4\. Precinct → Municipal – Physical delivery of ERs and ballots to the municipal canvassing center.

5\. Municipal Canvassing – QR Tallies scanned locally, cross-checked against blockchain records.

6\. Municipal → Provincial – Physical delivery of municipal COCs with QR Tallies.

7\. Provincial Canvassing – QR Tallies scanned locally, verified against blockchain.

8\. Provincial → National – Physical delivery of provincial COCs with QR Tallies.

9\. National Canvassing – QR Tallies scanned and verified against blockchain entries.

Estimated Time & Motion:

• Precinct count: 5–7 hrs

• Blockchain sync: seconds to minutes (parallel to physical transport)

• Municipal consolidation: ~2 hrs

• Provincial consolidation: ~2 hrs

• National consolidation: ~2 hrs

• Total: 11–13 hrs with parallel blockchain verification.

Key Advantages:

• Tamper-resistant due to distributed ledger.

• Maintains physical legal basis while providing a real-time, immutable audit trail.

#### 8.4 Full Automated Election (AES)

Process Flow:

1\. Vote Casting – Voters fill out paper ballots and feed them into Optical Mark Reader (OMR) machines (e.g., PCOS, VCM).

2\. Counting – OMR counts votes automatically during scanning.

3\. Electronic Transmission – Results sent from precinct to central servers via telecom networks.

4\. Canvassing – Results consolidated automatically at municipal, provincial, and national servers.

5\. Physical Delivery – Printed ERs and ballots retained for records; rarely re-encoded manually.

Estimated Time & Motion:

• Precinct count: 1–2 hrs

• Transmission: seconds to minutes

• Consolidations: near-instant

• Total: 2–4 hrs.

Key Issues:

• Opaque data transmission — watchers cannot directly observe the process.

• Server vulnerability — centralized architecture presents a single-point risk for large-scale tampering.

#### 8.5 Summary Table – Time and Transparency

| Scenario | Time from Precinct Close to National Totals | Transparency | Tamper Resistance |
| --- | --- | --- | --- |
| Full Manual | 24–34 hrs | High (physical tally visible) | Low (manual transport risk) |
| --- | --- | --- | --- |
| CAMVS | 11–16 hrs | High (AR + physical ER verification) | Medium (physical transport risk) |
| --- | --- | --- | --- |
| HES | 11–13 hrs | Very High (public blockchain + physical ER) | High (distributed ledger) |
| --- | --- | --- | --- |
| AES | 2–4 hrs | Low (opaque transmission) | Medium-High (centralized control) |


### 9\. Cost Analysis Across Election Scenarios

This section presents a comparative cost analysis of four distinct election conduct scenarios — Full Manual, Computer-Assisted Manual Voting System (CAMVS), Hybrid (CAMVS + QR + Distributed Ledger), and Full Automated Election System (AES). The purpose is to illustrate the economic trade-offs of each model, both at initial deployment and in subsequent election cycles, and to contextualize the long-term sustainability of each approach.

By quantifying both first-election and recurring costs, stakeholders can see that CAMVS and Hybrid deliver technology-assisted speed and transparency without the sustained financial burden of AES, while avoiding the operational drag of fully manual elections.

#### 9.1 Assumptions

To maintain transparency, the following assumptions were used in computing the costs:

• Scale: 300,000 unclustered precincts.

• CAMVS Kit: ₱56,000 per precinct (laptop, webcam, stand, printer).

• Shared Display: 1 per 5 precincts (₱15,000 each) → ₱3,000/precinct share.

• Spares Pool: 5% of kit + display.

• Logistics: ₱500/precinct (distribution and collection).

• Training:

• CAMVS/Hybrid: BEI team of 3, 1 day @ ₱1,500/person = ₱4,500/precinct.

• Full Manual: Lighter training = ₱1,500/precinct.

• Consumables: ₱3,000/precinct/election (paper, ink, QR labels).

• Manual Materials: Calculators, markers, photocopies = ₱2,000/precinct.

• Hybrid Add-On: Blockchain development, nodes, and operations.

• AES Costs: Last-election figures for vendor rentals, transmission, data center, warehousing, and training.

Note: Ballot printing is excluded because it applies to all scenarios, with only minor differences in paper size or quality.

#### 9.2 First-Election Costs

| Scenario | Core Equipment | Shared Displays | Spares Pool | Logistics | Training | Consumables / Materials | Add-Ons | Total (₱) |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Full Manual | \-  | \-  |     | 150M | 450M | 600M | \-  | 1.20B |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| CAMVS | 16.8B | 0.9B | 0.88B | 150M | 1.35B | 0.90B | \-  | 20.03B |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Hybrid | 16.8B | 0.9B | 0.88B | 150M | 1.35B | 0.90B | 0.11B | 20.14B |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| AES | – (rental) | \-  | \-  | \-  | \-  | \-  | Vendor + Ops 22.45B | 22.45B |


#### 9.3 Subsequent-Election Costs (Reuse Cycle)

| Scenario | Equipment Reuse | Logistics | Training | Consumables / Materials | Add-Ons | Total (₱) |
| --- | --- | --- | --- | --- | --- | --- |
| Full Manual | \-  | 150M | 450M | 600M | \-  | 1.20B |
| --- | --- | --- | --- | --- | --- | --- |
| CAMVS | \-  | 150M | 1.35B | 0.90B | \-  | 2.40B |
| --- | --- | --- | --- | --- | --- | --- |
| Hybrid | \-  | 150M | 1.35B | 0.90B | 0.11B | 2.51B |
| --- | --- | --- | --- | --- | --- | --- |
| AES | \-  | \-  | \-  | \-  | Vendor + Ops 22.45B | 22.45B |


#### 9.4 Comparative Observations

1\. Upfront vs Recurring Costs

• CAMVS and Hybrid are competitive with AES even in the first election, with Hybrid costing ~₱313M less than AES.

• In subsequent elections, CAMVS/Hybrid drop to ₱2.40B–₱2.51B per cycle, while AES remains at ₱22B every cycle.

2\. Long-Term Savings

• After just two election cycles, CAMVS/Hybrid deliver ₱30B+ in savings compared to AES.

• Full Manual remains cheapest per cycle, but the trade-off is significant delays and higher human error.

3\. Integrity and Transparency

• Hybrid adds a tamper-evident, distributed ledger layer for a modest additional cost (~₱110M per cycle).

• AES is fast but centralized, with higher systemic risk in case of server compromise.

#### 9.5 Sensitivity Factors

Cost projections can shift based on policy and deployment choices:

• Display Sharing: Removing shared displays (or using existing school TVs) saves ~₱0.9B; 1 display per precinct adds ~₱2.7B.

• Spares Pool: Increasing from 5% to 8% raises first-election CAMVS/Hybrid costs by ~₱1.85B.

• Training Cadence: Adding more dry-runs (+₱1,000/precinct) increases per-cycle cost by ₱300M.

9.6 Strategic Implications

The data strongly indicates:

• Cost Sustainability – CAMVS and Hybrid become dramatically cheaper than AES beyond the first election.

• Scalability – Both leverage reusable hardware, with optional phased upgrades (e.g., starting CAMVS-only, adding blockchain later).

• Policy Alignment – Legislative adjustments will be required to formally authorize CAMVS and Hybrid processes in official canvassing workflows.

### 10\. Conclusion and Recommendation

These findings make one thing clear: CAMVS—and its Hybrid evolution—offers a rare alignment of transparency, operational efficiency, and fiscal sustainability. Unlike AES, whose recurring costs remain high cycle after cycle, CAMVS compounds its cost advantage over time through reusable hardware, lighter logistical needs, and reduced dependence on proprietary systems.

Yet cost is only part of the equation. Elections are not just about balancing budgets—they are about preserving public trust, delivering timely results, and ensuring every vote is counted in a manner that is both lawful and visible to all stakeholders. When the financial case is coupled with its transparency safeguards and speed gains, CAMVS stands out as the most immediately deployable reform pathway under existing election law.

In short, the Computer-Aided Manual Voting System (CAMVS) offers a clear, rational, and immediately deployable path to restoring public trust in Philippine elections—while preserving the legal and procedural safeguards of manual voting. It combines the transparency of traditional methods, the efficiency of targeted technology, and the financial sustainability of reusable hardware.

#### 10.1 Transparency First, Always

CAMVS retains the manual character of elections as required by law. Every ballot appreciation, vote tally, and Election Return (ER) finalization is conducted in full public view by the Board of Election Inspectors (BEIs). There are no hidden algorithms, proprietary “black boxes,” or server-controlled tallies—every mark and every total remains observable and verifiable by voters, watchers, and election monitors.

#### 10.2 Time Savings Without Sacrificing Trust

CAMVS introduces non-automated computing aids that accelerate the process while keeping voter intent interpretation fully manual:

• Precinct Level: Counting and ER preparation drop from 8–12 hours to 5–7 hours, without skipping public reading or verification.

• Canvassing Stages: QR Tallies enable instant validation and consolidation, reducing canvassing durations by 50–70% and converting multi-day bottlenecks into hours.

• National Consolidation: Faster aggregation without the systemic vulnerabilities of real-time server transmissions.

#### 10.3 Cost Efficiency That Compounds Over Time

• Initial Deployment: Comparable to current AES costs (~₱21B), but with transparency built-in.

• Subsequent Cycles: Only ₱4.26–₱4.37B due to hardware reusability—yielding ₱30B+ in savings within just two election cycles.

• Financial Resilience: Eliminates recurring high rental fees for proprietary AES machines, redirecting funds toward voter education, BEI training, and precinct infrastructure.

#### 10.4 Security and Future-Readiness

CAMVS replaces vulnerable centralized digital transmissions with distributed, physical QR Tallies—limiting large-scale manipulation risks. Its modular architecture enables:

• Gradual Transition to Hybrid Election System (HES): Manual appreciation remains, while canvassing adopts blockchain-backed, ladderized verification.

• Scalable Integration: Digital audit trails, layered transmission security, and selective automation only where it enhances—not replaces—human oversight.

#### 10.5 A Logical, Lawful Reform Path

CAMVS can be rolled out immediately under the existing Omnibus Election Code—no major legislative overhaul required. This allows the next electoral cycle to benefit from reduced counting times, lower costs, and higher transparency without waiting for Congress to amend AES laws.

Recommendation:

1\. Adopt CAMVS Nationwide in the next election cycle to deliver measurable gains in transparency, efficiency, and cost-effectiveness.

2\. Invest in Reusable, Precinct-Based Hardware to provide a stable technology base for at least the next three election cycles.

3\. Begin Phased Integration Toward Hybrid Election System, preparing blockchain-secured, ladderized canvassing for deployment once legal frameworks are updated.

Bottom Line:

CAMVS is not an experimental gamble—it is a well-engineered evolution of the manual process we already know and trust, enhanced by modern tools that speed up counting and make fraud harder. It solves today’s election integrity challenges while laying the groundwork for tomorrow’s hybrid innovations.

The real question is not whether we can afford to implement CAMVS—

it is whether the country can afford to delay it any longer.
