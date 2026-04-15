# 2026-04-15

- What changed: added `docker-compose.yml` with a minimal PHP 8.3 runtime and created `src/public/index.php` as the public entrypoint returning a basic SSR HTML page on `/`.
- Why: establish the smallest runnable Docker-based application shell for later slices, aligned with the plain PHP architecture.
- Verified: repository-level runtime bootstrap reviewed; Docker Compose configuration and root entrypoint are in place for `docker compose up`.
- What changed: added a tiny explicit router, a shared layout with header navigation, and static page shells for `/`, `/lists`, and `/subscribers`.
- Why: make the SSR bootstrap navigable while keeping routing and rendering structure intentionally small and framework-free.
- Verified: route-to-template wiring and shared layout usage were checked, and the Docker Compose configuration remains unchanged and valid.
