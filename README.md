<div align="center">
  <h1 style="color: #FF003C;">>_ CyberKavach</h1>
  <p><b>An Enterprise-Grade, Hacker-Themed Event & Certificate Management Matrix</b></p>
</div>

---

## 📖 1. The Project Concept

CyberKavach is a highly secure, hacker-themed event management and digital certificate pipeline. It acts as an impenetrable digital fortress designed specifically to handle the operations of cybersecurity clubs, hackathons, and technical organizations. By combining a gritty, terminal-inspired user interface with enterprise-grade cryptographic security, it ensures that every event, user role, and generated certificate is completely tamper-proof and verifiable.

## 🧠 2. Philosophy & Overview

**The Origin:** "Kavach" is a Sanskrit word meaning armor or shield. The philosophy behind CyberKavach is to build a system that acts as a true digital shield.

**The Problem:** Traditional event management systems are often generic, vulnerable to common web exploits, and rely on certificate generation pipelines that are incredibly easy to forge or bypass by rogue staff members. 

**The Solution:** CyberKavach solves these issues by wrapping a robust CRUD (Create, Read, Update, Delete) web application in a heavily stylized "Matrix" aesthetic, backed by genuine security measures. It enforces strict multi-tiered Role-Based Access Control (RBAC) and mandates a Cryptographic Certificate Generation Pipeline that requires authorization from three separate management tiers. This ensures that no single point of failure (or rogue staff member) can compromise the integrity of the organization's official documents.

---

## 🔤 3. Translation Glossary

CyberKavach utilizes a heavily stylized thematic vocabulary to immerse users in the "hacker" aesthetic. For developers, administrators, or users unfamiliar with Unix terminology, use this translation matrix:

| CyberKavach Term | Standard Web Counterpart | Definition |
| :--- | :--- | :--- |
| **Init System** | Registration / Sign Up | The process where a new user creates an account. |
| **Access System** | Login / Authentication | The process of verifying identity via username and password. |
| **Passkey** | Password | The secret string used to authenticate. |
| **Operative** | Standard User / Student | The lowest access tier. Can only view events and download their own certificates. |
| **Sudo / Coordinator** | Event Staff / Manager | Tier 2. Can manage events, add photos, and initiate certificate creation. |
| **Architect / Faculty** | Teacher / Core Team | Tier 3. Can approve events, manage staff roles, and apply the first signature. |
| **Root / Director** | Super Admin / Boss | Tier 4. Ultimate control. Can ban users, force password resets, and finalize certificates. |
| **Clearance Override** | Role Management | The act of promoting, demoting, or suspending a user's account. |
| **Vault** | Cloud Storage | The digital directory where dynamically generated user certificates are securely stored. |
| **Payload** | Uploaded File | Usually refers to an image (certificate template) or a data packet being sent to the server. |

---

## 🛠️ 4. Technical Stack

* **Backend:** Custom Object-Oriented PHP 8.x Framework (Zero reliance on heavy frameworks like Laravel or Symfony).
* **Database:** SQLite3 via PDO.
* **Authentication:** Stateless JWT (JSON Web Tokens) transported exclusively via HTTP-Only cookies.
* **Frontend:** HTML5, Bootstrap 5 (heavily customized with raw CSS for terminal/glitch aesthetics, CRT scanlines, and glassmorphism), Vanilla JavaScript.
* **Graphics Generation:** PHP GD Library (for dynamic text overlay and image rendering).

## ⚙️ 5. Core Technologies

* **MVC PHP Architecture:** The backend utilizes the Model-View-Controller design pattern. This ensures a clean separation between the database interactions (Models), the business logic and routing (Controllers), and the HTML rendering (Views), making the codebase highly modular and secure.
* **SQLite:** Chosen for its serverless portability, zero-configuration rapid deployment, and speed. The entire database is contained within a single `cyberkavach.sqlite` file, making backups as simple as downloading a single asset.
* **JWTs (JSON Web Tokens):** Allows the system to maintain a "stateless" server. The server doesn't need to look up session IDs in a database for every single page load; the cryptographic signature of the token proves the user's identity and role instantly.
* **PHP GD Engine:** This powerful imaging extension enables the server to dynamically read base templates, calculate X/Y coordinates, apply custom TrueType fonts, inject HEX colors, and "burn" attendee details directly into the pixels of a certificate.

---

## 🛡️ 6. Cyber-Security Defenses

CyberKavach was built with security as a priority, implementing enterprise-grade mitigations against common attack vectors. The system includes:
* JWT Authentication via HTTPOnly Cookies.
* Cross-Site Request Forgery (CSRF) Protection on all state-changing endpoints.
* SQL Injection Prevention via Strict PDO parameter binding.
* XSS Mitigation via proprietary escaping wrappers.
* Strict File Upload Validation (MIME types, extensions, size constraints).
* Mandatory Passkey Rotation/Overrides for compromised accounts.
* Anti-directory traversal protection in routing and file serving.

## 🕵️ 7. Deep Dive Cybersecurity Defenses

Security isn't just hiding UI buttons; it is enforced at the deepest levels of the architecture.

### A. HTTPOnly JWTs to defeat XSS
* **The Concept:** JSON Web Tokens represent the user's session.
* **The Defense:** A massive flaw in modern web apps is storing JWTs in `localStorage`, making them vulnerable to Cross-Site Scripting (XSS) theft. CyberKavach strictly transmits JWTs exclusively via `HTTPOnly` cookies. This means malicious JavaScript executed in the browser **cannot** read, access, or steal the session token under any circumstances.

### B. PDO Prepared Statements to defeat SQLi
* **The Concept:** SQL Injection occurs when untrusted user input is concatenated directly into a database query.
* **The Defense:** 100% of database interactions in CyberKavach utilize PDO Prepared Statements. Instead of inserting strings directly, the backend uses parameterized placeholders (`?`). The SQLite database engine pre-compiles the SQL syntax separately from the data payload, neutralizing any malicious SQL commands (like `DROP TABLE`) injected by a user into literal, harmless strings.

### C. CSRF Tokens to prevent Forgery
* **The Concept:** CSRF forces a logged-in user to execute unwanted actions (like deleting an account) without their knowledge via a malicious link or script on a different website.
* **The Defense:** Every single `<form>` in CyberKavach generates a cryptographically secure, randomized token bound to the user's session. When the form is submitted, the `Security::validateCsrfToken()` method checks if the received token matches. If an attacker tries to forge a POST request from a malicious external website, it will fail because the attacker cannot guess the CSRF token.

### D. The Mandatory Passkey Override
* **The Concept:** Incident response for compromised accounts. 
* **The Defense:** If the Root Director suspects an Operative's account is compromised, resetting the password via email isn't enough (the attacker might already have an active session). Root can trigger a "Force Reset" from the dashboard. This attaches a `must_change_password` flag directly to the database row. The `AuthMiddleware` queries the live database on every single request; if this flag is detected, it acts like a digital concrete wall. Even if the attacker has a valid session, they are instantly intercepted, locked out of all data, and redirected to a Mandatory Override page. The account ownership is immediately forced back into the hands of the legitimate user via the new one-time passkey.

---

## 🔐 8. Clearance Levels & Capabilities

CyberKavach operates on a strict 4-tier hierarchy. To deploy the default testing suite, run the `database/seed_users.php` script.

> **Default Password for all testing accounts:** `password123`

* **Operative (`user1`)**: View and enroll in active events, access the Gallery, request role upgrades, download generated certificates, send direct encrypted messages to Root, and view notifications.
* **Sudo (`coord1`)**: Propose events, assign Operatives to volunteer roles, upload Event Gallery evidence, initiate the Certificate Workflow (upload base templates), configure certificate text mapping (coordinates, size, color), trigger mass generation, and broadcast targeted communications.
* **Architect (`faculty1`)**: Approve/Deny event proposals, authorize Operative upgrades to Sudo, publish Intel Resources, revoke Sudo clearances, apply the initial Faculty signature to certificates, and verify the final Root signature.
* **Root (`boss`)**: Ultimate global override. Promote/demote any user globally, execute emergency passkey resets, extract raw database backups, monitor all system communications, and apply the final, irreversible signature to official certificates.

## 🧠 9. Clearance Matrix

The matrix defines not just what a user can do, but who they represent in a real-world organizational hierarchy.

* **Tier 1: Operative**
  * *Psychology:* The standard attendee or student. They consume information, attend events, and aim to earn certificates. They have no administrative power but represent the bulk of the matrix population.
* **Tier 2: Sudo (Event Coordinator)**
  * *Psychology:* The boots on the ground. These are the highly active event managers. They run the day-to-day operations but require faculty oversight to prevent rogue modifications or unauthorized event creation.
* **Tier 3: Architect (Core Faculty)**
  * *Psychology:* The trusted overseers. Teachers or professors who validate the actions of Sudo operatives. They act as the first layer of trust in official documents and ensure quality control over the organization's output.
* **Tier 4: Root (Director / Boss)**
  * *Psychology:* Absolute power and ultimate responsibility. The Root user ensures the entire organization runs securely. They act as the ultimate authority, stepping in only for final cryptographic authorizations or emergency incident response.

---

## ⚙️ 10. The Certificate Pipeline (End-to-End Workflow)

The Certificate Generation Pipeline is CyberKavach's most complex feature, ensuring that dynamic certificates are generated without risking forgery by rogue staff members.

1. **Conclusion:** An event officially concludes.
2. **Base Template Upload:** A Tier 2 Sudo operative creates the blank, visually designed certificate template and uploads it to the matrix.
3. **Faculty Authorization:** A Tier 3 Architect reviews the base template. If approved, they download it, digitally overlay their official Faculty signature (using external software to ensure off-server key security), and re-upload the signed payload.
4. **Director Authorization:** The Tier 4 Root Director reviews the Faculty-signed payload. They download it, apply the final, ultimate Director signature, and upload the finalized template.
5. **Final Verification:** The Tier 3 Architect visually verifies the Director's uploaded template to ensure no tampering occurred in transit, releasing it for generation.
6. **Dynamic Mapping & Generation:** The Tier 2 Sudo operative maps the X/Y coordinates for where the attendee's Name, Event Title, and Rank should appear. Sudo then triggers the PHP GD Engine, which dynamically burns the specific details of every enrolled Operative onto the authorized template.
7. **Distribution:** Operatives are notified and securely download their certificates.

## 🔄 11. The Ultimate Certificate Workflow

To ensure maximum operational flexibility, the pipeline includes advanced fallback and overwrite mechanisms at every stage of the narrative:

* **Stage 1 (Sudo Initiation):** Sudo uploads the base template.
* **Stage 2 (Architect Signing & Overwrite):** The Architect views an inline visual thumbnail of the template. 
  * *Fallback:* If the Sudo operative uploaded a flawed or incorrect template, the Sudo operative can use the `> OVERWRITE` mechanism on their dashboard to forcefully replace the base template before the Architect signs it.
* **Stage 3 (Root Signing & Overwrite):** The Director views an inline visual thumbnail of the Faculty-signed template.
  * *Fallback:* If the Architect made a mistake with their signature, the Architect can use the `> OVERWRITE` mechanism to re-upload a corrected signed payload before the Director finalizes it.
* **Stage 4 (Verification):** The Architect verifies the Director's final signature.
* **Stage 5 (Generation):** Sudo dynamically generates the certificates to the secure vaults.

---

## 📡 12. Encrypted Communications

CyberKavach features a highly intelligent, targeted communication array to ensure critical intel is delivered without spamming the entire matrix.

* **Targeted Event Notifications:** When Sudo or Root transmits an alert, they can specifically target an event (e.g., "Zero Day Chase Hackathon"). The system will exclusively deliver this encrypted transmission to the dashboards of Operatives actively enrolled in that specific event.
* **Global Broadcasts:** For system-wide intelligence, Root or Sudo can trigger a `[GLOBAL]` broadcast, sending the payload to every registered entity in the matrix.
* **Reactive UI Polling:** When an operative receives a new transmission, a blinking `[!] NOTIFICATION_ALERT` icon initiates a CSS pulse animation on their navigation bar. It dynamically updates to show event-specific messages.
* **Direct Transmissions (Contact System):** Unauthenticated guests or standard Operatives can utilize the `/contact` form to transmit messages directly to the Root's private `[ COMMUNICATIONS_TERMINAL ]`, ensuring a direct line to the Director.

---

## 💻 13. Installation & Deployment Protocol

1. **Clone the Repository** to your local web server root (e.g., XAMPP `htdocs`, NGINX `/var/www`, or Laragon).
2. **Environment Architecture:** Duplicate `.env.example` into a new `.env` file. Define your `JWT_SECRET` (make it long and cryptographically random).
3. **Fetch Dependencies:** Open a terminal in the project root and execute `composer install`. This fetches `firebase/php-jwt` and `vlucas/phpdotenv`.
4. **Initialize Database:** Ensure the `database` folder exists and is writable. Execute the schema migrations sequentially by running: 
   `php database/migrations_phase<1-12>.php` (or utilize a bundled setup script).
5. **Seed the Matrix:** Run `php database/seed_users.php` to inject the default testing accounts into the database.
6. **Configure Permissions:** The PHP GD engine requires write access. Ensure the `public/uploads` directory has proper read/write permissions (`chmod 0755` or `0777` in local dev).
7. **Engage Server:** Point your web server's document root to the `public/` directory, or use PHP's built-in server:
   `php -S localhost:8000 -t public`

> **SYSTEM SECURE. OVERRIDE COMPLETE. >_**
