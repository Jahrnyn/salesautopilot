You are working on a small PHP + Docker take-home exercise project.

Before making any changes, read and align with the project documents:
- ARCHITECTURE.md
- CHANGELOG.md
- REFLECTION.md if it already contains notes relevant to current constraints
- any existing source files relevant to this slice

Context and constraints:
- This is a deliberately small, server-side rendered PHP application
- No full framework
- Keep the solution simple, readable, and easy to review
- Do not introduce speculative API facts that are not validated
- Preserve the existing architecture and coding direction
- Keep diffs narrow and task-focused
- Do not perform unrelated refactors
- Do not over-engineer abstractions
- Prefer mockable and testable structure over premature live API assumptions
- Logical separation matters, but keep physical structure modest
- Error handling must remain explicit and user-facing
- Sensitive data must never be committed or hardcoded

Your task in this slice:
[INSERT SINGLE SLICE GOAL HERE]

Requirements for this slice:
[INSERT CONCRETE REQUIREMENTS HERE]

Out of scope for this slice:
[INSERT WHAT MUST NOT BE TOUCHED YET]

Implementation rules:
- Reuse existing code where appropriate
- Keep controllers thin
- Keep templates simple
- Keep API/integration details isolated
- Do not push sorting/filtering logic into templates
- Do not expand beyond the files needed for this slice
- If a live SalesAutopilot API detail is uncertain, keep the implementation conservative and architecture-aligned rather than guessing

Acceptance criteria:
[INSERT CHECKABLE ACCEPTANCE CRITERIA HERE]

Documentation updates required:
- Update CHANGELOG.md for this slice
- Keep the entry concise and factual
- Include what changed, why, and what was verified

Final response format:
1. What you changed
2. Files touched
3. What you verified
4. Any open issue or limitation that remains after this slice