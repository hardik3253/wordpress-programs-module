# WordPress Programs Module & University Website

A modern, production-ready WordPress solution implementing the complete **WordPress Practical Test: Programs Module** and University Website (University of Aberdeen Mumbai campus), following strict architecture rules, the compulsory Git Workflow, and modern WordPress standards.

---

## 🔒 Architectural Decision: Zero Plugins (Strict Compliance)

Per the strict requirement:
> **"No third-party or no custom plugins."**

All functionality is built natively and cleanly inside the custom theme (`wp-content/themes/university-theme/`):
- **Custom Post Type & Hierarchical Taxonomy**: Registered via `includes/cpt-taxonomy.php`.
- **Custom Fields & React Admin UI**: Registered via `includes/custom-fields.php` with native `@wordpress/scripts` build tooling.
- **Frontend Routing & Clean Permalinks**: Registered via `includes/routing.php`.
- **Reusable Card Component**: Implemented in `template-parts/program-card.php` with helper `render_program_card()`.
- **Single Programme Detail Page**: Implemented in `single-program.php` with dynamic repeater section rendering.
- **Frontend Interactivity Filter**: Implemented in `assets/js/main.js` and `archive-program.php` with pushState URL synchronization and server-side fallback.
- **Data Tracking System**: Registered via `includes/tracking.php` logging daily JSON Lines to `wp-content/uploads/logs/`.

---

## 🌿 Compulsory Git Workflow

This repository strictly follows the hierarchical branch structure and pull request progression:

```
main  <--- (PR) ---  uat  <--- (PR) ---  dev  <--- (PR) ---  feature/*
```

### Branch Responsibilities
1. **`main`**: Production release branch. No direct commits or pushes. Receives code exclusively via Pull Request from `uat`.
2. **`uat`**: User Acceptance Testing / staging branch. Created from `main`. Receives code exclusively via Pull Request from `dev`.
3. **`dev`**: Active integration branch. Created from `uat`. All feature branches are branched from `dev` and merged back into `dev` via Pull Request.
4. **`feature/*`**: Feature branches (e.g. `feature/programs-module`). All code, changes, and commits happen exclusively here.

### Conventional Commits
All commits follow the conventional commit specification:
- `feat: <description>` - New features and components
- `fix: <description>` - Bug fixes and corrections
- `docs: <description>` - Documentation updates
- `refactor: <description>` - Code refactoring without behavior change
- `chore: <description>` - Maintenance, dependencies, and build configurations

---

## 🚀 Quick Setup & Installation

### 1. Requirements
- PHP 8.1+
- WordPress 6.4+ (supports 6.5+ and 6.6+)
- MySQL 5.7+ / MariaDB 10.4+
- Node.js 18+ & npm 9+ (only required if recompiling admin React assets)

### 2. Theme Activation
1. Clone or place this repository into your WordPress webroot.
2. In the WordPress Admin, navigate to **Appearance > Themes** and activate **University Theme**.
3. Upon activation, rewrite rules are automatically flushed and 10 sample programmes (5 Undergraduate, 5 Postgraduate) are automatically seeded with academic metadata and repeater sections.

### 3. Compiling Assets (Optional)
Pre-compiled production bundles are tracked in the repository (`wp-content/themes/university-theme/build/`) so reviewers **do not need to run npm builds**. If you wish to build or customize the React source files:
```bash
cd wp-content/themes/university-theme
npm install
npm run build   # Compiles src/index.js -> build/index.js and build/style-index.css
npm run start   # Watch mode for active development
```

---

## 🗺️ URL Routing & Rewrites (Task 4)

Clean, collision-free URL hierarchy handled by `includes/routing.php`:

| URL Path | Template | Description |
| :--- | :--- | :--- |
| `/programmes` | `archive-program.php` | All programmes listing |
| `/programmes/undergraduate` | `archive-program.php` | Filtered: Undergraduate programmes only |
| `/programmes/postgraduate` | `archive-program.php` | Filtered: Postgraduate programmes only |
| `/programmes/{slug}` | `single-program.php` | Single programme detail view |

- **No Collision**: Taxonomy terms (`undergraduate`, `postgraduate`) are prioritized with `top` precedence, preventing collisions with single programme slugs.
- **Rewrite Flush**: Flushed safely on `after_switch_theme` hook, never on every page request.
- **Breadcrumb Hierarchy**: Dynamic trail (`Home > Programmes > [Type] > [Title]`).

---

## 🧩 Reusable Programme Card Component (Task 5)

Located at `wp-content/themes/university-theme/template-parts/program-card.php`.

### Usage via PHP Helper:
```php
// Echo program card directly
render_program_card( $post_id );

// Or retrieve HTML string
$card_html = render_program_card( $post_id, false );

// With custom overrides
render_program_card( $post_id, true, array(
    'title'         => 'Custom Title',
    'track_section' => 'homepage-showcase',
) );
```

---

## ⚙️ React Admin UI & Custom Fields (Task 3)

The edit screen for any Programme (`/wp-admin/post.php?post=ID&action=edit`) features a React application:
- **Academic Meta**: Academic Year (`2025-26`) and Duration (`3 Years`).
- **Content Sections Repeater**:
  - Add, remove, and reorder sections (Move Up / Move Down).
  - Collapsible cards for effortless scanning.
  - Integration with the native WordPress Media Library modal for image selection and previews.
  - Dual-syncing to hidden inputs for native POST saving and `register_post_meta` with REST support.

---

## 📊 Data Tracking System (Task 8)

### Tracking Attribute Helper
All interactive elements include a structured `data-track` attribute:
```php
echo program_track_attr( 'link', 'header-logo', 'site-header', 'componentLink' );
// Outputs: data-track="link|header-logo|site-header|componentLink"
```

### REST API Endpoint
- **Endpoint**: `POST /wp-json/programs/v1/track`
- **Method**: `POST`
- **Payload Schema**:
```json
{
  "event_type": "click",
  "element_id": "header-apply-now",
  "section": "site-header",
  "component": "componentButton",
  "timestamp": "2026-10-13T06:15:00Z",
  "page_url": "http://localhost/aum/",
  "user_agent": "Mozilla/5.0 ..."
}
```

### Anonymization & Daily Rotating Logs
- Client IP addresses are anonymized before logging by masking the last octet (e.g., `192.168.1.xxx` or `::xxxx`).
- Log files rotate daily: `wp-content/uploads/logs/data-track-YYYY-MM-DD.log`.
- Stored as strict **JSON Lines** (one valid JSON object per line).
- Ignored in `.gitignore` to keep repositories clean.

### Sample Log Entries (10+ Required Entries)

```json
{"event_type":"click","element_id":"header-logo","section":"site-header","component":"componentLink","timestamp":"2026-10-13T04:20:11Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"nav-study","section":"main-navigation","component":"componentLink","timestamp":"2026-10-13T04:21:05Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"hero-slider-next","section":"hero-section","component":"componentButton","timestamp":"2026-10-13T04:22:15Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"hero-discover-more","section":"hero-section","component":"componentLink","timestamp":"2026-10-13T04:22:40Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"careers-see-more","section":"careers-section","component":"componentLink","timestamp":"2026-10-13T04:23:18Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"see-more-undergraduate","section":"our-programmes-section","component":"componentLink","timestamp":"2026-10-13T04:24:02Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"filter-postgraduate","section":"programme-filter-bar","component":"componentLink","timestamp":"2026-10-13T04:25:30Z","page_url":"http://localhost/aum/programmes/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"program-card-master-of-computer-applications-mca","section":"programme-listing","component":"componentCard","timestamp":"2026-10-13T04:26:10Z","page_url":"http://localhost/aum/programmes/postgraduate/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"apply-now","section":"programme-overview","component":"componentLink","timestamp":"2026-10-13T04:27:44Z","page_url":"http://localhost/aum/programmes/master-of-computer-applications-mca/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"submit-lead-form","section":"lead-form-popup","component":"componentButton","timestamp":"2026-10-13T04:28:12Z","page_url":"http://localhost/aum/programmes/master-of-computer-applications-mca/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"close-thank-you-screen","section":"lead-form-thankyou","component":"componentButton","timestamp":"2026-10-13T04:28:35Z","page_url":"http://localhost/aum/programmes/master-of-computer-applications-mca/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
{"event_type":"click","element_id":"footer-privacy","section":"footer-bottom","component":"componentLink","timestamp":"2026-10-13T04:29:50Z","page_url":"http://localhost/aum/","user_ip":"192.168.1.xxx","user_agent":"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36"}
```
