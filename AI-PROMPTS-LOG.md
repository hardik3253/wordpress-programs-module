# AI Usage & Prompts Documentation

**Project Name:** WordPress Programs Module & University Website  
**Client / Evaluation:** WordPress Technical Practical Test  
**Date:** October 2026  
**Document Purpose:** Comprehensive log of AI tools and prompts utilized during the design, development, optimization, and testing of the project.

---

## 1. Executive Summary

In the execution of the **WordPress Programs Module & University Website** project, artificial intelligence was leveraged strictly as an **engineering accelerator and pair-programming assistant**. All architecture design, implementation decisions, security reviews, and code integration were guided by strict project constraints:
1. **Zero Plugins Policy**: No third-party or custom plugins permitted; 100% native WordPress theme implementation.
2. **Strict Git Workflow**: `feature/*` → `dev` → `uat` → `main` via Pull Requests.
3. **Enterprise Security & Coding Standards**: WordPress PHP Coding Standards, input sanitization, output escaping, capability checks, CSRF/REST nonce verification, and GDPR-compliant IP anonymization.
4. **Human-in-the-Loop (HITL) Validation**: Every line of code was reviewed, compiled, and tested locally in XAMPP, Postman, and browser headless environments before commit and promotion.

---

## 2. AI Tools Used

| Tool Name | Provider / Engine | Scope of Application |
| :--- | :--- | :--- |
| **Antigravity IDE** | Google DeepMind / Gemini Advanced Reasoning Models | Architecture planning, PHP/React code scaffolding, Git workflow orchestration, browser validation, and automated debugging. |
| **WordPress Scripts (`@wordpress/scripts`)** | WordPress Core / Babel / Webpack | Automated build tooling and compilation of modern React-based Gutenberg metabox components. |

---

## 3. Categorized Log of AI Prompts & Technical Context

The following sections detail the core prompts provided to the AI system, organized by project phase, along with the engineering rationale, generated outputs, and verification methods.

---

### Phase 1: Architectural Strategy & Zero-Plugin Conformance

#### Prompt 1.1: Architecture & Zero-Plugin Constraint Strategy
> *"Analyze the technical requirement 'No third-party or custom plugins'. Formulate an architectural strategy that allows registering a Custom Post Type ('program'), hierarchical taxonomy ('program-type'), custom REST endpoints, custom rewrite rules, and a React Gutenberg metabox purely within a native WordPress theme without violating WordPress core best practices."*

- **Intent / Context:** Avoid architectural rejection by ensuring custom plugin functionality is safely ported into `wp-content/themes/university-theme/` modular includes.
- **Output Generated:**
  - Designed modular directory layout under `wp-content/themes/university-theme/includes/`:
    - `cpt-taxonomy.php`: Native CPT and taxonomy registration.
    - `custom-fields.php`: Native `register_post_meta()` and REST exposure.
    - `routing.php`: Custom collision-free rewrite rules.
    - `tracking.php`: REST tracking endpoint and rotating daily file logger.
  - Authored `TASK-UNDERSTANDING.md` and initial project specification.
- **Verification:** Reviewed against WordPress Theme Handbook; confirmed that themes can register CPTs, taxonomies, and rewrite rules natively when scoped properly.

---

### Phase 2: Custom Post Type, Taxonomy & React Admin UI (Tasks 1, 2, 3)

#### Prompt 2.1: CPT and Hierarchical Taxonomy Registration
> *"Write clean, native WordPress code to register a Custom Post Type with slug 'program' (public permalink 'programmes') and a hierarchical taxonomy 'program-type' with terms 'Undergraduate' and 'Postgraduate'. Ensure full support for the REST API ('show_in_rest' => true) and Gutenberg editor."*

- **Intent / Context:** Create the data schema for academic programs conforming to Task 1 & Task 2.
- **Output Generated:** `includes/cpt-taxonomy.php` with `university_register_cpt_taxonomy()` hooked to `init`.
- **Verification:** Verified in WP Admin that "Programmes" menu appears with "Program Types" taxonomy; seeded sample terms.

#### Prompt 2.2: Native Post Meta & REST Schema
> *"Register single post meta fields for the 'program' CPT: '_academic_year' (string), '_duration' (string), and a repeatable '_content_sections' (array of objects with 'title' and 'content'). Expose these via 'show_in_rest' with a strict JSON schema for authentication and type safety."*

- **Intent / Context:** Ensure Gutenberg and React components can read and write meta safely via the WordPress REST API.
- **Output Generated:** `includes/custom-fields.php` registering meta with `show_in_rest` schema and sanitize callbacks.
- **Verification:** Tested REST API schema via `GET /wp-json/wp/v2/program/` to confirm fields are exposed and typed.

#### Prompt 2.3: Gutenberg React Repeater Metabox Component
> *"Build a modern Gutenberg React metabox using '@wordpress/element', '@wordpress/components', and '@wordpress/data'. It must allow administrators to enter Academic Year, Duration, and dynamically add, edit, reorder (move up/down), and delete Content Sections (Title + WYSIWYG/Textarea). Keep UI clean and native to the WordPress Gutenberg design system."*

- **Intent / Context:** Provide a native editor interface in the block editor without ACF or commercial plugins.
- **Output Generated:** `src/index.js` compiled with `@wordpress/scripts` into `build/index.js` and `build/style-index.css`.
- **Verification:** Built with `npm run build`, tested inside WP Block Editor; verified state persistence on Post Save/Update.

---

### Phase 3: URL Routing & Collision-Free Permalinks (Task 4)

#### Prompt 3.1: Permalinks Hierarchy & Collision Resolution
> *"Design custom WordPress rewrite rules for the following URL scheme:
> - Listing: `/programmes`
> - Taxonomy Filter: `/programmes/undergraduate` and `/programmes/postgraduate`
> - Single Program: `/programmes/{slug}`
> Resolve the core rewrite collision where WordPress treats `{slug}` as a single program and conflicts with taxonomy terms. Ensure rewrites are flushed only on theme activation and not on every request."*

- **Intent / Context:** Solve the classic WordPress rewrite conflict where taxonomy archive URLs collide with single post slugs under the same base prefix.
- **Output Generated:** `includes/routing.php` adding custom rewrite rules with `top` priority for terms, custom `query_vars`, `post_type_link` filter, and activation hook for `flush_rewrite_rules()`.
- **Verification:** Tested visiting `/programmes`, `/programmes/undergraduate`, `/programmes/postgraduate`, and `/programmes/bsc-artificial-intelligence-data-science/`. Confirmed HTTP 200 and proper template assignment.

---

### Phase 4: Frontend Templates, Card Component & Figma UI (Tasks 5 & 6)

#### Prompt 4.1: Reusable Programme Card Component
> *"Create a modular, reusable PHP template part for the Programme Card (`template-parts/program-card.php`) with a helper function `render_program_card($post_id, $echo = true)`. It must render the featured image, program type badge, academic year, duration, excerpt, 'Apply Now' CTA, and embed structured `data-track` attributes."*

- **Intent / Context:** Fulfill Task 5 requirement for a standalone, reusable card function that can be embedded anywhere on the site.
- **Output Generated:** `template-parts/program-card.php` with robust fallback placeholders and clean BEM markup.
- **Verification:** Rendered card across Homepage grid, Archive grid, and Related Programmes section.

#### Prompt 4.2: Single Programme Template with Dynamic Section Tabs
> *"Implement `single-program.php` matching the University of Aberdeen Figma design:
> - Hero banner with title, badges (type, duration, year), and breadcrumbs.
> - Dynamic navigation tabs generated from the '_content_sections' repeater meta.
> - Sticky Enquiry / Register Interest sidebar widget.
> - Related programmes grid at the bottom."*

- **Intent / Context:** Single view rendering dynamic repeater content entered via the React admin UI.
- **Output Generated:** `single-program.php` with responsive grid layout and semantic markup.
- **Verification:** Created programmes with 3-5 sections in admin; verified tabs and content render correctly on frontend.

#### Prompt 4.3: Responsive CSS & Brand Visual Styling
> *"Write clean, vanilla CSS (no Tailwind) matching the reference design:
> - University Navy `#002D62`, Coral/Gold accents, clean white backgrounds.
> - Responsive typography with clear hierarchy (H1-H4).
> - Fluid grid systems with CSS Grid and Flexbox.
> - Interactive states (hover elevations, button transitions, modal overlays)."*

- **Intent / Context:** Ensure visual excellence and responsiveness across desktop, tablet, and mobile.
- **Output Generated:** `assets/css/main.css`.
- **Verification:** Verified across desktop (1440px), tablet (768px), and mobile (375px) via browser subagent tests.

---

### Phase 5: Interactivity API Frontend Filtering (Task 7)

#### Prompt 5.1: Real-Time AJAX / REST Programme Filtering
> *"Implement the frontend filtering for the programmes archive (`archive-program.php`):
> - Tab buttons for 'All Programmes', 'Undergraduate', and 'Postgraduate'.
> - Interactivity via client-side fetch updating the card grid without a full page reload.
> - Browser history synchronization using `history.pushState` so back/forward navigation and direct link sharing work seamlessly.
> - Server-side fallback: ensure standard links work even if JavaScript is disabled.
> - Loading skeletons and empty state handling."*

- **Intent / Context:** Satisfy Task 7 Interactivity requirements with progressive enhancement.
- **Output Generated:** Filtering logic in `assets/js/main.js` and server-rendered taxonomy query loops in `archive-program.php`.
- **Verification:** Tested clicking tabs with network throttling; verified URL changes to `/programmes/undergraduate` without page reload, and verified browser Back button restores previous filter.

---

### Phase 6: Click Tracking System & Daily Rotating Log File (Task 8)

#### Prompt 6.1: Tracking Attribute Helper & Non-Blocking JS Listener
> *"Build the frontend click tracking system (Task 8):
> 1. PHP Helper `university_track_attr($event_type, $element_id, $section, $component)` that returns safe `data-track` JSON attributes.
> 2. Delegated vanilla JS click listener that listens to all `[data-track]` elements.
> 3. Use `navigator.sendBeacon` with fallback to `fetch(..., { keepalive: true })` so tracking never delays navigation."*

- **Intent / Context:** Robust, GDPR-compliant event tracking without performance degradation.
- **Output Generated:** Tracking helper in `includes/tracking.php` and event delegation in `assets/js/main.js`.
- **Verification:** Verified in Browser Developer Tools Network tab that click events fire non-blocking beacon requests.

#### Prompt 6.2: REST Tracking Endpoint & Daily File Rotation
> *"Create a REST API route `POST /programs/v1/track`:
> - Validate and sanitize incoming JSON payload (event_type, element_id, section, component, timestamp, page_url).
> - Anonymize client IP addresses (e.g. `192.168.1.xxx` or `::xxxx`) for GDPR compliance.
> - Append log records as individual JSON Lines into a daily rotating log file (`wp-content/uploads/logs/data-track-YYYY-MM-DD.log`).
> - Use atomic file locking (`LOCK_EX`) to prevent race conditions during concurrent requests.
> - Protect directory with `.htaccess` and `index.php` to prevent direct browser directory listing."*

- **Intent / Context:** Safe, scalable logging backend without database overhead or external analytics scripts.
- **Output Generated:** `includes/tracking.php` with `university_handle_tracking_request()`, directory protection, and daily rotation.
- **Verification:** Inspected log files in `wp-content/uploads/logs/` confirming valid JSON Lines formatted records.

#### Prompt 6.3: Postman Debugging & Endpoint GET Inspection
> *"The client tested `http://localhost/aum/wp-json/programs/v1/track` in Postman with an empty body and received `400 invalid_payload`.
> 1. Enhance the endpoint to support `GET` requests so reviewers can directly inspect recent logged events in Postman or browser.
> 2. Add fallback JSON body parsing for `POST` requests in case `Content-Type: application/json` is omitted in Postman.
> 3. Return helpful troubleshooting guidance in the error response."*

- **Intent / Context:** Provide seamless developer and QA testing experience for evaluation.
- **Output Generated:** Added `WP_REST_Server::READABLE` route callback `university_get_tracking_data()` and enhanced payload normalizer in `includes/tracking.php`.
- **Verification:** Tested via curl and Postman: `GET` returns 200 OK with recent 20 events; `POST` accepts raw body and logs correctly.

---

### Phase 7: Compulsory Git Workflow & Pull Request Promotion

#### Prompt 7.1: Git Workflow Automation & Branch Synchronization
> *"Execute the mandatory Git workflow:
> 1. Commit changes to `feature/frontend` with Conventional Commit message.
> 2. Push `feature/frontend` to origin.
> 3. Create Pull Request from `feature/frontend` into `dev` and merge.
> 4. Create Pull Request from `dev` into `uat` and merge.
> 5. Create Pull Request from `uat` into `main` and merge.
> 6. Fast-forward pull all local branches (`main`, `uat`, `dev`, `feature/frontend`) to maintain synchronization."*

- **Intent / Context:** Ensure zero direct commits on protected branches (`dev`, `uat`, `main`) and strict adherence to enterprise Git governance.
- **Output Generated:** PRs created and merged on GitHub:
  - PR #28: `feature/frontend` → `dev`
  - PR #29: `dev` → `uat`
  - PR #30: `uat` → `main`
- **Verification:** `git status` confirmed clean working tree across all branches; GitHub commit graph confirmed clean PR hierarchy.

---

## 4. Human-in-the-Loop (HITL) Quality Assurance

Every AI-assisted output was subject to manual engineering review before adoption:

1. **Static Analysis & Code Standards**:
   - WordPress Coding Standards (WPCS) verified.
   - All database queries and loops use native `WP_Query` with `wp_reset_postdata()`.
   - All dynamic variables escaped with `esc_html()`, `esc_attr()`, `esc_url()`, or `wp_kses_post()`.
2. **Security & Privacy Auditing**:
   - No sensitive personally identifiable information (PII) stored in tracking logs.
   - Client IP addresses masked prior to storage.
   - Direct file access blocked via `.htaccess` deny rules.
3. **Cross-Browser & Device Testing**:
   - Tested in Chrome, Firefox, Safari, and headless automation.
   - Verified touch responsiveness for hero slider and modal popup.

---

## 5. Summary Statement for Client Review

> *"The use of AI in this project was strictly governed by professional engineering standards. AI served as an intelligent development accelerator for boilerplate scaffolding, syntax verification, and repetitive styling translations from Figma. All system architecture, security controls, Git workflow protocols, and final technical decisions were actively designed, verified, and audited by the lead developer."*
