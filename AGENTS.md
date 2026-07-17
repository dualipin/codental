# AGENTS.md

Laravel 13 + Inertia (Vue 3 + TypeScript) app for dental clinic management ("Codentalv3"). Domain and code (models, controllers, routes) are in **Spanish** (Paciente, Cita, Odontograma, Presupuesto, etc.) — keep new code consistent with this naming.

## Commands

- Install/setup: `composer run setup` (copies `.env`, generates key, migrates, `npm install`, `npm run build`)
- Dev servers (PHP + Vite together): `composer run dev`
- Run all tests: `composer test` (equivalent to `php artisan test`) — do NOT run raw `phpunit`, it skips the `config:clear` step
- Run a single test: `php artisan test --filter=TestName` or `php artisan test tests/Feature/SomeTest.php`
- Frontend build: `npm run build`; frontend dev only: `npm run dev`
- No lint/format/typecheck scripts are wired into `package.json` or `composer.json` (no Pint, ESLint, or `vue-tsc` script exists yet, even though those packages are dev dependencies). Don't assume `npm run lint` or `composer lint` exist.

## Architecture notes

- Inertia root view is `resources/views/layouts/app-inertia.blade.php`, configured in `app/Http/Middleware/HandleInertiaRequests.php`. Shared Inertia props go in that middleware's `share()` method.
- Two separate Vite/JS entrypoints exist: `resources/js/app.ts` (main Inertia app: Pinia, Ziggy, vue3-toastify) and `resources/js/main.ts` (currently just loads `bootstrap.ts`, used for non-Inertia pages like `agendar-cita` public flow — check `routes/web.php` for which controller/view uses which).
- Inertia pages live in `resources/js/Pages/**`, resolved relative to that path in `app.ts`'s `createInertiaApp({ pages: { path: './Pages' } })`.
- `routes/web.php` mixes active routes with large blocks of commented-out legacy routes — don't resurrect commented code without confirming with the user; treat it as historical reference only.
- Route groups: public booking flow under `/agendar-cita` (mixes plain Blade + Inertia via `InertiaAgendaMiddleware` on a subset of routes), authenticated app under `/app` (agenda, usuarios, pacientes).
- Path alias `@/*` -> `resources/js/*` (see `tsconfig.json` and used via Vite's default resolution).
- Ziggy routes are aliased to `vendor/tightenco/ziggy` in `vite.config.ts`; regenerate with `php artisan ziggy:generate` if route names change and `ziggy.d.ts`/route helpers look stale.

## Testing quirks

- Test DB is in-memory SQLite (`phpunit.xml`), so tests don't touch `database/database.sqlite`. Session/cache/queue drivers are forced to `array`/`sync` during tests regardless of `.env`.
- Only skeleton example tests exist so far (`tests/Feature/ExampleTest.php`, `tests/Unit/ExampleTest.php`) — no established feature-test conventions to follow yet; use standard Laravel `RefreshDatabase`/HTTP test patterns.

## Style

- 4-space indent, LF line endings, final newline required (`.editorconfig`); Markdown files are exempt from trailing-whitespace trimming.
