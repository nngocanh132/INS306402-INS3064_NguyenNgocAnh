# User Profile System — PHP Mini App

A cohesive **User Profile System** built with vanilla PHP, covering registration, login, session-based access control, profile editing (avatar + bio), and logout. No external frameworks or databases required — user data is persisted in a local JSON file.

---

## Folder Structure

```
project/
├── assets/
│   ├── css/
│   │   └── style.css          # Global stylesheet
│   └── uploads/               # Uploaded avatar images (auto-created)
├── includes/
│   ├── config.php             # Constants: paths, limits, allowed types
│   ├── functions.php          # Core helpers: CRUD, validation, CSRF, avatar upload
│   ├── auth.php               # Session management: login, logout, require_login
│   └── users.json             # JSON flat-file "database" (auto-populated)
└── public/
    ├── index.php              # Home page
    ├── register.php           # Registration form
    ├── login.php              # Login form
    ├── profile.php            # Protected profile view & edit page
    └── logout.php             # Session destroy + redirect
```

> All filenames follow **snake_case** convention. No spaces.

---

## How to Run

### Requirements
- PHP **8.0+**
- A local server: [XAMPP](https://www.apachefriends.org/), [Laragon](https://laragon.org/), or PHP's built-in server

### Steps

**Option A — PHP built-in server (recommended for quick testing)**
```bash
# From the project root directory:
php -S localhost:8000 -t public/
```
Then open: [http://localhost:8000](http://localhost:8000)

**Option B — XAMPP / Laragon**
1. Copy the `project/` folder into your `htdocs/` (XAMPP) or `www/` (Laragon) directory.
2. Make sure `assets/uploads/` is **writable** by the web server:
   ```bash
   chmod 775 assets/uploads/
   ```
3. Visit: `http://localhost/project/public/`

---

## Features

### 1. Registration (`register.php`)
- Accepts: **Username**, **Email**, **Password**, **Password Confirmation**
- Server-side validation:
  - Username: 3–30 characters, alphanumeric + underscores only
  - Valid email format (`filter_var`)
  - Password minimum 8 characters
  - Passwords must match (checked before any storage)
  - Duplicate username / email detection
- **Sticky form**: fields repopulate on validation error
- Passwords stored as **bcrypt hashes** (`password_hash`)

### 2. Login (`login.php`)
- Validates credentials against `users.json`
- **Brute-force protection**: locks the session after **3 failed attempts**
- Remaining attempts shown as a live badge
- On success: stores `$_SESSION['user']` and redirects to `profile.php`

### 3. Access Control (`profile.php`)
- First line checks `is_logged_in()` — unauthenticated visitors are **immediately redirected** to `login.php`
- Works in Incognito / private browsing mode

### 4. Profile Editing (`profile.php`)
- **Avatar upload**:
  - Allowed types: JPG, PNG, GIF, WEBP
  - MIME type verified via `finfo` (not just extension)
  - Rejects `.exe`, `.pdf`, and any non-image file
  - Max size: **2 MB**
  - Filename randomised with `bin2hex(random_bytes(16))` to prevent path traversal
  - Old avatar deleted when a new one is uploaded
- **Bio**:
  - Max 500 characters
  - Fully XSS-safe on output via `htmlspecialchars()`
- Form **pre-populated** with existing values
- Changes persisted to `users.json` immediately

### 5. Logout (`logout.php`)
- Calls `session_destroy()`
- Redirects to home page
- "Logout" button only visible when the user is logged in

---

## 🔒 Security

| Threat | Mitigation |
|---|---|
| **XSS** | All output passed through `e()` = `htmlspecialchars(ENT_QUOTES)` |
| **CSRF** | Cryptographic token generated per session, verified on every POST |
| **Brute-force login** | Session-based attempt counter; form locked after 3 failures |
| **Malicious uploads** | MIME type checked with `finfo`, extension whitelist enforced |
| **Password storage** | `password_hash()` with `PASSWORD_BCRYPT`; never stored in plain text |
| **Path traversal** | Upload filenames are randomly generated, never derived from user input |
| **Session hijacking** | Session regenerated on successful login (`session_regenerate_id`) |

---

## ✅ Acceptance Test Checklist

| # | Test | Expected Result |
|---|---|---|
| 1 | Submit register form with mismatched passwords | Error: *"Passwords do not match"* — no account created |
| 2 | Enter wrong password 3 times on login | Form locked: *"Access is locked"* message |
| 3 | Open `profile.php` in Incognito (not logged in) | Redirected to `login.php` |
| 4 | Enter `<script>alert('hack')</script>` in Bio | Tag rendered as escaped text — script does **not** execute |
| 5 | Upload a `.exe` or `.pdf` as avatar | Error: *"Only JPG, PNG, GIF, and WEBP images are allowed"* |
| 6 | Upload a valid `.jpg` or `.png` | Image appears in profile header |
| 7 | Check navbar when not logged in | "Logout" button is **not visible** |
| 8 | Refresh `profile.php` after saving | Bio and avatar persist correctly |

---

## 📦 File Naming Conventions

- All PHP files: `snake_case.php`
- All folders: lowercase, no spaces
- Uploaded avatars: `<32-char-hex>.<ext>` (e.g., `a3f9c1b2....jpg`)

---

## 👨‍💻 Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.0+ (no frameworks) |
| Storage | JSON flat file (`users.json`) |
| Sessions | Native PHP `$_SESSION` |
| Styling | Custom CSS (no external libraries) |
| Security | CSRF tokens, bcrypt, finfo MIME check |