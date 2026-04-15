# SalesAutopilot PHP Take-Home

## Project Overview

Small server-side rendered PHP application for demonstrating a SalesAutopilot integration exercise.

It focuses on:
- API integration behind a narrow client boundary
- controlled error handling
- Docker-based runtime
- mock-backed demonstrability when live credentials are unavailable

## How to Run

1. Copy `.env.example` to `.env` and adjust values if needed.
2. Start the app:

```bash
docker compose up
```

3. Open:

```text
http://localhost:8000
```

Notes:
- `docker-compose.yml` provides the runtime shell.
- `.env.example` documents the expected runtime keys.

## Key Architectural Decisions

- Server-side rendered PHP, no framework.
- Simple layered structure:
  - presentation: routes + templates
  - application: page services / use cases
  - integration: SalesAutopilot client boundary
  - runtime: config, session, client resolution
- Mock-first approach because no working reviewer credentials were provided during development.
- Deterministic error simulation for required failure scenarios.
- Legacy SalesAutopilot API + HTTP Basic auth for live mode.
- JWT / API v2 token flow was intentionally not used to keep the solution small and aligned with the validated scope.

See `ARCHITECTURE.md` for the fuller technical contract.

## SalesAutopilot Integration Approach

- `MockSalesAutopilotClient` is the primary development and testing mechanism.
- `SalesAutopilotHttpClient` is implemented conservatively from the validated legacy/basic-auth documentation.
- Live mode uses username/password from `.env` via HTTP Basic authentication.
- Live endpoints used:
  - `GET /getlists`
  - `GET /list/<nl_id>`
- Live behavior depends on valid credentials supplied by the reviewer.
- Where live response fields are uncertain, the app maps only conservative internal fields and does not invent unvalidated metadata.

## Testing

- PHPUnit is configured through `composer.json` and `phpunit.xml`.
- Tests are service-level and deterministic.
- Covered areas:
  - lists happy path
  - subscribers happy path
  - invalid credentials
  - empty list
  - timeout-like failure
  - rate-limit-like failure

Typical commands:

```bash
composer install
composer test
```

## Known Limitations

- No provided working credentials means live integration was not validated end-to-end during development.
- Real list metadata fields such as subscriber count or creation date may differ from the mock shape or may not be available from the validated live endpoints.
- UI is intentionally minimal and server-rendered.
- Runtime / service coupling is simplified for the exercise and optimized for readability over purity.

## Notes for Reviewer

- To test mock mode, use `USE_MOCK_SAPI=true` in `.env`.
- To switch to live mode, set `USE_MOCK_SAPI=false` and provide:
  - `SAPI_USERNAME`
  - `SAPI_PASSWORD`
  - `SAPI_BASE_URL`
- Mock error scenarios can be triggered with `scenario=...` query parameters, for example:
  - `scenario=invalid_credentials`
  - `scenario=timeout`
  - `scenario=rate_limit`
  - `scenario=empty_list`
- The scenario links are exposed directly on the `/lists` and `/subscribers` pages.
