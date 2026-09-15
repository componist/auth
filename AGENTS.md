# AGENTS – Componist Auth

## Zweck

Livewire-basierte Authentifizierung: Login, Registrierung, Passwort-Reset, E-Mail-Verifizierung, E-Mail-2FA, Rate-Limiting und Session-Härtung.

## Grenzen & Abhängigkeiten

- Gehört rein: Auth-UI, Middleware (2FA, Verify-Email), User-Migrationen des Packages
- Gehört nicht: User-Manager, Profil-Dashboard (→ `componist/core`)
- Root-App: `App\Models\User` implementiert `TwoFactorAuthenticatable`
- Abhängigkeit: `livewire/livewire` ^4

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
- Auth-UI: Split-Screen (`auth-shell` / `auth-card`) mit Teal-Panel, Labels über den Feldern und Placeholders; sichtbare Inputs nur mit Tailwind, keine toten `auth-label`/`auth-control`-Klassen
- Feature-Flags in Config (Register, 2FA, Verify-Email)
- Rate-Limits für Login, Register, 2FA
- Kein Flash-Trait nötig — Auth nutzt Session/Validation-Messages

## Tests

```bash
php artisan test --compact --testsuite="Componist Auth"
```

## Security

- Rate-Limiting auf sensiblen Endpoints
- `auth`-Alias = Package-`Authenticate` (Login + Verify + 2FA); `EnsureSecondaryAuthentication` zusätzlich auf `web`
- 2FA-OK nur als Session-Flag (`TwoFactorSession`), nicht über leere `two_factor_code`-Spalte
- Passwort-Reset: Remember-Token rotieren, `sessions` des Users löschen, Event `PasswordReset` dispatchen
- Logout nur POST + CSRF (`<x-componist-auth::logout-form />`)
- Registrierung: Rate-Limit für Fehlversuche **und** erfolgreiche Sign-ups (3/h pro IP)
- Registrierung default aus: `COMPONIST_AUTH_REGISTER=false` (Accounts über User-Manager)
- Demo-Login nur über `COMPONIST_AUTH_EXAMPLE_EMAIL` / `PASSWORD` in `.env`, nie hart in Config
- Skill `security-audit` bei Auth-Änderungen

## Do / Don’t

- Do: Route-Aliase über `ComponistAuthConfig`; README für Feature-Flags konsultieren
- Don’t: Auth-Logik in Root-`app/` duplizieren; Secrets in Config committen
