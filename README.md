# WordPress Programs Module & University Website

This repository contains the implementation of the **WordPress Practical Test: Programs Module**, featuring a Custom Post Type with taxonomy, custom React-based admin UI (metabox/panel), custom frontend routing, reusable program card, single program detail template, WordPress Interactivity API filter, and JSON Lines click tracking.

---

## Git Workflow Note

This project strictly adheres to the mandated branching strategy:

```
main  <--- (PR) ---  uat  <--- (PR) ---  dev  <--- (PR) ---  feature/*
```

### Branch Hierarchy & Responsibilities:
1. **`main`**: Production-ready code. No direct commits or pushes. Receives code only via Pull Request from `uat`.
2. **`uat`**: Pre-release and user acceptance testing branch. Created from `main`. Receives merged code from `dev` via Pull Request.
3. **`dev`**: Integration branch for all active features. Created from `uat`. All feature branches are cut from `dev` and merge back into `dev` via Pull Requests.
4. **`feature/*`**: Individual feature branches (e.g., `feature/cpt-setup`, `feature/admin-ui`, `feature/frontend`, `feature/tracking`, `feature/programs-module`). All implementation and commits happen exclusively in feature branches.

### PR Progression:
- `feature/*` → `dev`
- `dev` → `uat`
- `uat` → `main`

### Commit Message Convention:
All commits use conventional commit prefixes:
- `feat: <description>` for new features
- `fix: <description>` for bug fixes
- `refactor: <description>` for refactored code
- `chore: <description>` for tooling/scaffolding
- `docs: <description>` for documentation updates

---

## Repository Structure & Build Artifacts

- **Plugin**: `wp-content/plugins/programs-module/`
  - React Admin UI built using `@wordpress/scripts`.
  - Compiled output located in `build/`.
  - *Note: `build/` files are tracked in the repository to allow the plugin to run without requiring a build step on test review environments.*
- **Theme**: `wp-content/themes/university-theme/`
- **Documentation**:
  - `TASK-UNDERSTANDING.md`
  - `README.md`

## Tracking & Logs
Click tracking payloads are posted to `/wp-json/programs/v1/track` and appended to daily log files in JSON Lines format under `wp-content/uploads/logs/` (e.g., `wp-content/uploads/logs/data-track-YYYY-MM-DD.log`). Log files are strictly excluded from git tracking via `.gitignore`.
