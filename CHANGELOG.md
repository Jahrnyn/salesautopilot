# 2026-04-15

- What changed: added `docker-compose.yml` with a minimal PHP 8.3 runtime and created `src/public/index.php` as the public entrypoint returning a basic SSR HTML page on `/`.
- Why: establish the smallest runnable Docker-based application shell for later slices, aligned with the plain PHP architecture.
- Verified: repository-level runtime bootstrap reviewed; Docker Compose configuration and root entrypoint are in place for `docker compose up`.
- What changed: added a tiny explicit router, a shared layout with header navigation, and static page shells for `/`, `/lists`, and `/subscribers`.
- Why: make the SSR bootstrap navigable while keeping routing and rendering structure intentionally small and framework-free.
- Verified: route-to-template wiring and shared layout usage were checked, and the Docker Compose configuration remains unchanged and valid.
- What changed: added minimal `.env` loading, session startup, runtime state resolution for mock/env/session credential availability, and a home-page runtime summary; also added `.env.example`.
- Why: let the app determine configuration and credential source precedence without introducing API behavior or a larger config system.
- Verified: bootstrap wiring was checked so runtime state loads before rendering, the home page can show config-only status, and the Docker Compose configuration remains valid.
- What changed: added a narrow `SalesAutopilotClientInterface`, deterministic mock client, conservative live-client skeleton, integration error categories, and a single runtime resolver for the active client type.
- Why: establish the isolated SalesAutopilot boundary now while keeping endpoint uncertainty contained inside the integration layer.
- Verified: the runtime can now resolve mock vs live client selection from existing config state, and the Docker Compose configuration remains valid.
