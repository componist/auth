# AGENTS – Componist Auth

## Zweck

Livewire-basierte Authentifizierung: Login, Registrierung, Passwort-Reset, E-Mail-Verifizierung, E-Mail-2FA, Rate-Limiting und Session-Härtung.

## Grenzen & Abhängigkeiten

- Gehört rein: Auth-UI, Middleware (2FA, Verify-Email), User-Migrationen des Packages
- Gehört nicht: User-Manager, Profil-Dashboard (→ `componist/core`)
- Root-App: `App\Models\User` implementiert `TwoFactorAuthenticatable`
- Abhängigkeit: `livewire/livewire` ^4, `componist/core` ^1.0 (UI-Primitives)

## Struktur

```
src/Domain/TwoFactorCode.php
src/Application/TwoFactorAuthService.php
src/Livewire/Auth/{UserLoginController,UserRegisterController,ForgotPassword,ResetPassword,VerifyEmail,TwoFactorAuthController}.php
src/Middleware/{Authenticate,EnsureSecondaryAuthentication,TwoFactorMiddleware,VerifyEmailMiddleware}.php
src/Support/{ComponistAuthConfig,ComponistAuthRouteAliases,TwoFactorSession}.php
src/Traits/AddComponistAuthentication.php
resources/views/components/ (Blade-Namespace componist-auth)
```

## Einbindung

- Provider: `Componist\Auth\AuthServiceProvider`
- Config: `componist_auth` (Publish-Tag: `componist.auth.publish.config`)
- Views: `componistAuth::…`, Komponenten `x-componist-auth::…`
- Routes: konfigurierbar über `componist_auth.routes.*`

## Konventionen

- UI-Texte: Deutsch
- Auth-UI: zentrierte Card über **WebGL Dot-Field**-Hintergrund (`resources/js/auth-dot-field.js`, Three.js Shader – portiert aus modern-login-signup)
- Light: Teal-Dots auf hellem Grund; Dark: weiße Dots auf Schwarz (wie Referenz)
- Theme-Toggle dispatcht `auth-theme-change`; Alpine `x-cloak` + `ready` gegen FOUC
- Kein Split-Hero; Brand über der Card
- Feature-Flags in Config (Register, 2FA, Verify-Email)
- 2FA-Code: `componist_auth.two_factor_code.charset` (`alphanumeric` \| `digits`) und `length` (4–12, Default 12) — **nur** publishbare Package-Config, nicht via `.env`
- Rate-Limits für Login, Register, 2FA
- Kein Flash-Trait nötig — Auth nutzt Session/Validation-Messages
- `prefers-reduced-motion`: Dot-Field aus

## Tests

```bash
php artisan test --compact --testsuite="Componist Auth"
```

## Security

- Rate-Limiting auf sensiblen Endpoints (Login, Register, 2FA, Forgot- + Reset-Password)
- `auth`-Alias = Package-`Authenticate` (Login + Verify + 2FA); `EnsureSecondaryAuthentication` zusätzlich auf `web`
- Livewire-Update: 2FA/Verify nur übersprungen, wenn **alle** Komponenten in der Batch Challenge-Komponenten sind (`auth.two-factor-auth-controller`, `auth.verify-email`) — kein Skip über Companion-Snapshots
- Reset-/Verify-Mail-URLs immer über `CanonicalUrl` + `config('app.url')` (kein Host-Header-Poisoning)
- 2FA-Mail: `componistAuth::emails.2fa-code` nutzt Core `x:component::mail.shell` + `mail.code` (kein paralleles HTML-Layout)
- 2FA-OK nur als Session-Flag (`TwoFactorSession`), nicht über leere `two_factor_code`-Spalte
- Passwort-Reset: generische Fehlermeldung (keine Account-Enumeration), Remember-Token rotieren, `sessions` des Users löschen, Event `PasswordReset` dispatchen
- Logout nur POST + CSRF (`<x-componist-auth::logout-form />`)
- Registrierung: Rate-Limit für Fehlversuche **und** erfolgreiche Sign-ups (3/h pro IP)
- Registrierung default aus: `COMPONIST_AUTH_REGISTER=false` (Accounts über User-Manager)
- Demo-Login nur über `COMPONIST_AUTH_EXAMPLE_EMAIL` / `PASSWORD` in `.env`, nie hart in Config
- Production: `TRUSTED_HOSTS` setzen; Skill `security-audit` bei Auth-Änderungen

## Do / Don’t

- Do: Route-Aliase über `ComponistAuthConfig`; README für Feature-Flags konsultieren
- Don’t: Auth-Logik in Root-`app/` duplizieren; Secrets in Config committen
