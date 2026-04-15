# ARCHITECTURE.md

## 1. Goal and scope

This project is a small, server-side rendered PHP web application that integrates with the SalesAutopilot API.  
The goal is not to build a general-purpose admin interface or a feature-complete client, but to deliver a small, readable, stable, and demonstrable integration within the scope of the exercise.

Primary focus areas:
- external API integration,
- error handling,
- simple and clear request flow,
- Docker-based execution,
- controlled AI-assisted development.

The project intentionally avoids a heavy framework.  
The architecture is designed so that it:
- stays small in moving parts,
- can be implemented quickly,
- can be developed in small, controlled slices,
- remains easy to review by both a human and an agent.

The solution is primarily an evaluation-oriented implementation.  
Because the task does not provide guaranteed working SalesAutopilot credentials or a known account with pre-populated lists, the system must be designed so that it remains meaningfully demonstrable, reviewable, and testable even without live external API access.

---

## 2. Principles

### 2.1 Simplicity first
For this exercise, the simpler solution is preferred if it is still clear, maintainable, and sufficient for the requirements.

### 2.2 SSR to minimize frontend overhead
The application is server-side rendered.  
There is no separate SPA, no frontend build pipeline, and no JS-heavy UI.

### 2.3 Framework minimalism
The solution is based on plain PHP, with at most a few small Composer packages where they reduce friction or improve clarity.

### 2.4 External API isolation
The SalesAutopilot integration lives in a separate layer.  
UI code, application flow, and external HTTP communication must not be mixed together.

### 2.5 Deterministic error handling
The task explicitly requires specific error scenarios.  
The application must therefore handle invalid credentials, empty lists, timeout-like failures, and rate-limit-like failures in a controlled and explainable way.

### 2.6 Demonstrability without live credentials
Because external API access is not guaranteed during development, the solution must be built with a testable integration boundary, a mockable client, and deterministic error simulation.

### 2.7 Cheap endpoint replacement
External base URL handling, endpoint definitions, and request construction must not be scattered throughout the codebase.  
If the target API endpoint structure needs adjustment later, that change should stay low-cost.

---

## 3. Technology decisions

### 3.1 PHP version
The project uses PHP 8.3.

Reasoning:
- modern and stable version,
- good language ergonomics,
- easy to run in Docker,
- suitable for a small SSR application without introducing avoidable legacy constraints.

### 3.2 Framework
No full PHP framework is used.

Reasoning:
- the task is small,
- there is no need for ORM, migrations, service container, or a large middleware stack,
- framework bootstrapping would likely take more time than it adds value in this scope,
- the exercise evaluates integration thinking and error handling more than framework usage.

### 3.3 Composer packages
Only minimal Composer packages are acceptable, for example:
- an HTTP client,
- an `.env` loader,
- optionally a very small routing helper.

The goal is not zero dependencies.  
The goal is to keep dependencies limited to what directly improves delivery speed, readability, or reliability.

### 3.4 Docker
The project is Docker Compose based.

Requirements:
- `docker compose up` should start the application,
- running the project should not depend on a preinstalled local PHP environment,
- the container setup should provide a reproducible execution environment.

### 3.5 Database
No database is used.

Reasoning:
- the exercise is small and read-oriented,
- there is no meaningful local domain persistence requirement,
- adding a database would increase setup cost and complexity without clear benefit for the task.

The application state is therefore temporary:
- request-level,
- or session-level,
- but not persistent business storage.

---

## 4. External API assumptions and validation boundaries

This project integrates with the SalesAutopilot API, but not every API detail should be treated as fully confirmed up front.  
The architecture should remain correct even where live validation is still pending.

The implementation may reasonably assume the following at a high level:
- the external system is HTTP-based,
- requests require credentials,
- the application must retrieve list-related data and subscriber-related data,
- the integration can fail in ways that must be translated into user-facing feedback,
- some form of throttling or request limitation may need to be handled gracefully.

At the same time, the following points must be treated as validation points rather than hard architectural facts until they are confirmed during implementation or live testing:
- the exact authentication mechanism and request shape,
- the exact endpoint paths used for lists and subscribers,
- which list fields are reliably available,
- which subscriber fields are reliably available,
- whether list size and creation date are directly available or need fallback handling.

Because of this, the architecture does not depend on narrowly hardcoded API assumptions in higher layers.  
The integration boundary must absorb those details so that uncertain external specifics do not leak into controllers, templates, or application flow.

---

## 5. Functional scope interpretation

### 5.1 Credential handling
The application should be able to obtain credentials from:
- `.env` as the default source,
- and optionally from a simple UI input.

From an application-flow perspective, `.env` is the primary developer path, while manual credential entry is a secondary demonstration or fallback path.

### 5.2 List retrieval
The app should display the account's lists.

Expected display fields from the task statement:
- name,
- size,
- creation date.

However, the exact source and reliability of these fields are not fully validated yet.  
This is an explicit validation point in the architecture, not only an implementation detail.

The implementation must therefore allow a composed or partially degraded list view model:
- if a field is clearly available from the external API, it can be displayed normally,
- if a field is not reliably available, the application should handle that explicitly rather than inventing or silently assuming data.

This matters especially for:
- list size,
- list creation date.

These two fields must be treated as requirement targets that still need confirmation against the actual API behavior.

### 5.3 First 20 subscribers
The app should display the first 20 subscribers of a selected list.

For the purpose of this task, a limited and readable subscriber view is enough.  
A full paging system is out of scope.

### 5.4 Interpretation of filtering / sorting
Requirement 4 is interpreted as follows: the subscriber list must support at least one simple local sorting or filtering mechanism.

Decision:
- fetch the first 20 records,
- apply application-side sorting or filtering to those records.

Reasoning:
- the task does not explicitly require API-side filtering or sorting,
- this is better aligned with the timebox,
- it reduces dependence on unvalidated API behavior,
- it still demonstrates that the requirement was intentionally implemented.

---

## 6. High-level system shape

Logical flow:

1. An HTTP request arrives
2. A small router selects the handler
3. The handler calls an application-level service or use-case function
4. That service requests data through the SalesAutopilot client boundary
5. The result is normalized into internal view data
6. A PHP template renders the HTML response
7. Failures are translated into controlled user-facing states

This remains a classic thin-controller, service-oriented, adapter-based SSR structure.

---

## 7. Layers

### 7.1 Presentation layer
Responsibilities:
- route handling,
- reading request parameters,
- minimal input validation,
- user-facing error rendering,
- HTML rendering.

It should not contain:
- direct HTTP calls,
- API endpoint construction,
- mixed transport and template logic.

### 7.2 Application layer
Responsibilities:
- executing use cases,
- resolving which credentials to use,
- building the data needed for list and subscriber pages,
- applying local sorting or filtering,
- translating integration failures into application-level outcomes.

Possible use-case-level responsibilities:
- resolve credentials,
- list available lists,
- show subscribers of a selected list,
- apply local subscriber sorting or filtering.

### 7.3 Integration layer
Responsibilities:
- communicating with the SalesAutopilot API,
- handling request construction and response parsing,
- translating external failures into internal exceptions or result types,
- keeping uncertain external details contained.

### 7.4 View layer
Responsibilities:
- simple PHP templates,
- layout and partials,
- escaped output,
- explicit empty and error states.

---

## 8. Suggested project structure

The physical folder structure should stay simpler than the logical architecture.  
For this project, logical separation matters more than a deeply nested directory tree.

A reasonable baseline structure is:

```text
/
├── docker-compose.yml
├── .env.example
├── CHANGELOG.md
├── ARCHITECTURE.md
├── REFLECTION.md
├── AI_LOG.md
└── src/
    ├── public/
    │   └── index.php
    ├── app/
    │   ├── Router.php
    │   ├── Controllers/
    │   ├── Services/
    │   ├── SalesAutopilot/
    │   ├── Support/
    │   └── View/
    ├── templates/
    ├── tests/
    └── composer.json
```

This structure is intentionally modest.  
It preserves the key logical separations:
- request handling,
- application/services,
- external API integration,
- templates,
- tests.

The exact file count and folder naming can stay flexible as long as:
- controllers remain thin,
- templates stay simple,
- API integration remains isolated,
- the codebase does not collapse into one large mixed script.

---

## 9. Routing strategy

Routing should stay very small and explicit.  
There is no need to force a REST-like structure for a small SSR exercise.

Reasonable route candidates:
- `GET /` – home / credential state / entry page
- `GET /lists` – list overview
- `GET /subscribers?listId=...` – selected list subscriber view via query parameter, or equivalent simple route
- optional `POST /credentials` if manual credential input is enabled

The exact routing shape may stay simple and pragmatic.  
The important part is clarity, not stylistic purity.

---

## 10. Credential flow

### 10.1 Primary source
Credentials come from `.env` by default.

Suggested keys:
- `SAPI_USERNAME`
- `SAPI_PASSWORD`
- `SAPI_BASE_URL`
- `APP_ENV`
- `USE_MOCK_SAPI`

### 10.2 Optional UI input
If needed for demonstration, credentials may also be entered through a simple form.

### 10.3 Storage
Manually entered credentials, if supported, should remain session-scoped only.

Reasoning:
- there is no database,
- no durable storage is required,
- session-level storage is enough for a short demonstration flow.

### 10.4 Security minimum
- credentials must not be logged,
- templates must not render credentials,
- exception output must not expose secrets,
- sensitive values must never enter committed documentation.

---

## 11. SalesAutopilot client boundary

### 11.1 Goal
External API access must go through one dedicated client boundary.

### 11.2 Interface
Higher layers should depend on application intent, not on raw endpoints or transport details.

Illustrative shape:

```php
interface SalesAutopilotClientInterface
{
    public function getLists(Credentials $credentials): array;

    public function getSubscribers(Credentials $credentials, string|int $listId, int $limit = 20): array;
}
```

This is intentionally conservative.  
The exact method set should follow only validated needs.

### 11.3 Implementations
At least two implementations should be possible:

1. `SalesAutopilotHttpClient`
   - performs real HTTP calls
   - for this project uses the legacy HTTP Basic authentication path with the existing username/password pair
   - does not use JWT or API v2 token flow

2. `MockSalesAutopilotClient`
   - returns deterministic responses
   - can simulate invalid credentials, empty lists, timeout-like failures, and rate-limit-like failures

### 11.4 Endpoint isolation
Endpoint paths, base URL handling, and request construction must be centralized inside the integration layer.  
They should not be duplicated across controllers or templates.

This keeps endpoint changes cheap and contained.

### 11.4.1 Validated live integration path for this exercise
For this implementation, the validated live integration path is the legacy/basic-auth direction only.

Validated decisions for the live client:
- HTTP Basic authentication is used
- credentials come from the existing username/password configuration
- JWT or API v2 token flow is explicitly out of scope
- `GET /getlists` is used for live list retrieval
- `GET /list/<nl_id>` is used for live subscriber retrieval

Validated status handling for this slice:
- `200` means success
- `401` maps to authentication failure
- `404`, `405`, `406`, and `500` map to controlled integration/API failure handling
- response bodies are treated as JSON in UTF-8

No additional endpoint behavior or fields are treated as confirmed beyond these validated points.

### 11.5 Raw API output vs internal models
Templates should not consume raw external JSON directly.  
The client boundary or a small mapper/normalizer should convert external responses into internal arrays or DTO-like structures suitable for rendering.

The exact internal representation can stay lightweight.  
A full domain model is not required for this task.

---

## 12. Error handling strategy

Error handling is one of the core concerns of the exercise and must be treated as a first-class architectural topic.

### 12.1 Error categories

#### a) Invalid credentials
Expected behavior:
- clear error message,
- no raw JSON dump,
- no white screen.

The exact low-level API signal for this should be mapped during implementation, but the higher-level application behavior is already fixed.

#### b) Empty list
If a selected list contains zero subscribers, the UI must show an explicit empty state:
- for example: “The selected list currently has no subscribers.”

This is not a system failure.  
It is a valid business state.

#### c) API unavailable / timeout-like failure
The HTTP integration should use controlled timeout settings.  
If the external API does not respond in time, the app must fail gracefully:
- no hanging page,
- no fatal crash,
- clear user-facing feedback.

#### d) Rate-limit-like or concurrency-related rejection
The task explicitly requires handling the case where the API rejects the request because of request limits or parallel request restrictions.  
The architecture therefore treats this as a distinct error scenario that can be surfaced with its own user-facing message.

The exact external trigger and response shape still belong to integration-level validation.

### 12.2 Internal error types
A small set of explicit internal exception or result categories is recommended, for example:
- `AuthenticationFailedException`
- `ApiTimeoutException`
- `ApiRateLimitException`
- `ApiResponseException`
- `ConfigurationException`

These names are illustrative, not mandatory.  
The important part is to separate error categories clearly enough for deterministic UI behavior.

### 12.3 User-facing rendering
Errors should use short, plain, non-technical primary messages.  
In development mode, a secondary technical detail may be shown if useful, but production-style output should remain clean.

---

## 13. Testability without live credentials

This is a deliberate architectural requirement.

### 13.1 Why it is necessary
The task explicitly asks for several failure scenarios, but no guaranteed working external account or known test dataset is provided.

### 13.2 Principle
The system must therefore be able to:
- run without live credentials,
- demonstrate the main user flows,
- reproduce error scenarios in a controlled way,
- keep the external integration design meaningful at the same time.

### 13.3 Approach
The `SalesAutopilotClientInterface` must support a mock implementation.

The mock client should support at least:
- successful list retrieval,
- successful subscriber retrieval,
- empty subscriber list,
- invalid credentials,
- timeout-like failure,
- rate-limit-like failure.

### 13.4 Activation
Mock mode should be switchable through configuration, for example with:
- `USE_MOCK_SAPI=true`
In development, mock mode may also be the default unless live credentials are intentionally provided.

### 13.5 What is not required
This project does not need a large automated integration-testing framework or complex contract testing.  
The goal is controlled and credible demonstrability.

---

## 14. Sorting and filtering

The subscriber list must support at least one simple local sorting or filtering feature.

Reasonable minimal options:
- sort by name,
- sort by email,
- simple text filtering across the already fetched records.

Sorting or filtering applies only to the already retrieved set.  
This is a conscious scope decision.

---

## 15. Rendering strategy

The UI should be simple, functional, and minimal.

Expected screens:
- home / credential state,
- list overview,
- subscriber view,
- error / empty states.

Out of scope:
- design-heavy admin interface,
- JS component system,
- client-side state management.

Primary goals:
- readability,
- clear feedback,
- simple navigation.

---

## 16. Logging and diagnostics

Given the size of the exercise, only minimal diagnostics are needed.

Suggested approach:
- application logging to stdout/stderr in Docker,
- no credential logging,
- concise diagnostic context in development where useful,
- no stack traces in normal user-facing output.

---

## 17. Non-goals

Out of scope:
- full authentication system
- user management
- database persistence
- full pagination
- caching layer
- async processing
- frontend framework
- large observability setup
- broad API coverage beyond what the task needs

---

## 18. Coding rules

- use clear, descriptive names
- keep files and responsibilities small
- keep templates light on logic
- keep controllers thin
- do not leak HTTP details into views
- minimize magic strings
- centralize external endpoint details
- never commit sensitive data

---

## 19. Build and runtime expectations

The repository should minimally contain:
- `docker-compose.yml`
- `.env.example`
- `src/`
- `ARCHITECTURE.md`
- `CHANGELOG.md`
- `AI_LOG.md`
- `REFLECTION.md`

Runtime baseline:
- `docker compose up`

Expectation:
- first project startup should stay simple and documented,
- local execution should not require ad hoc environment-specific setup beyond Docker Compose.

---

## 20. Documentation obligations during development

### 20.1 ARCHITECTURE.md
This is the main technical contract of the project.  
Codex should align with it before each meaningful implementation slice.

### 20.2 CHANGELOG.md
The changelog should be updated after each meaningful development slice.

Suggested minimum content:
- what changed,
- which files were affected,
- why the change was made,
- what was checked.

### 20.3 AI_LOG.md
This is prepared at the end, but the work should remain easy to reconstruct so that the important decision points can be documented clearly.

### 20.4 REFLECTION.md
This is also prepared at the end according to the task requirements.  
Several architecture decisions recorded here are intentionally written so they can later be justified clearly in the reflection, especially:
- local sorting/filtering,
- mockability,
- framework minimalism,
- demonstrability without guaranteed external credentials.

---

## 21. Open points / validation items

1. Which exact SalesAutopilot API paths are the most appropriate for retrieving lists and subscribers in this exercise.
2. Which list fields are actually and reliably available for rendering.
3. Whether list size and creation date can be obtained directly and consistently enough to satisfy the requirement as stated.
4. Which subscriber fields are stable enough to use in the minimal UI.
5. Which HTTP client setup gives the cleanest timeout and failure handling for this scope.
6. Whether mock mode only needs fixed fixtures or a simple scenario switch.

---

## 22. Final architectural statement

This solution is intended to demonstrate a small, controlled, and testable integration slice.

Its value comes from showing:
- clear scoping,
- simple SSR execution,
- isolated external integration,
- explicit error handling,
- credible mockability in the absence of guaranteed live credentials,
- and a documented, disciplined AI-assisted development process.

The architecture is deliberately modest, but it is structured to make the implementation understandable, reviewable, and easy to evolve within the limits of the exercise.
