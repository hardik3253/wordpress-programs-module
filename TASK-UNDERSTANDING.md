# Task Understanding: Programs Module

## Summary
I will convert the Figma design (Home, Programs listing, Lead form popup, Thank-you alert) into a responsive WordPress site and build a Programs module: a `program` CPT (slug `programmes`) with a hierarchical `program-type` taxonomy (Undergraduate, Postgraduate) and 10 sample programs. Meta (Academic Year, Duration, repeatable Content Sections) is edited in a custom React admin UI. The frontend has custom routing, a reusable program card, a detail page and an Interactivity API filter. Every clickable element carries a `data-track` payload, sent to a REST endpoint and written to a JSON-lines log file.


## Architecture
- **Plugin (`programs-module`):** CPT, taxonomy, meta, rewrites, React admin (`src/` → `build/`), REST routes, logger, tracking helper, card function, seeder.
- **Theme (classic):** templates, template parts, CSS, filter and tracking scripts, popup. Classic theme chosen for direct PHP template and rewrite control.

```
plugins/programs-module/  (includes/, src/, build/)
themes/university-theme/  (templates, template-parts/, assets/)
```

## Technical Approach
- **Build:** `register_post_meta()` with `show_in_rest`; React metabox via `@wordpress/scripts`; nonce + capability check and sanitization on save; custom rewrite rules flushed on activation only.
- **Tracking:** one PHP helper `program_track_attr()` builds the payload with `esc_attr( wp_json_encode() )`. One delegated vanilla JS click listener parses the closest `[data-track]` in try/catch and sends it with `fetch` (keepalive) or `sendBeacon`, never blocking navigation. `POST /programs/v1/track` validates strictly (type, event allow-list, slug labels, sanitized strings, size limit, unknown keys dropped) and appends one JSON line to `uploads/logs/data-track-YYYY-MM-DD.log` using `LOCK_EX`. No personal data is logged, and the path is filterable. The route is public, protected by validation, a REST nonce and rate limiting.
- **Interactivity API:** filter links work without JS; with JS, `data-wp-on--click` fetches via REST, shows loading and empty states, and updates the URL with `history.pushState`.

## Assumptions
- "No custom plugins" is read as "no third-party plugins"; if my own plugin is not allowed, the code moves into the theme.
- The "related programs screen" is the `/programmes` listing.
- Logs are written as daily files prefixed `data-track`.
- "Apply now" uses a filterable placeholder URL; the lead form is UI only.

## Questions
1. Are custom plugins allowed, or must everything live in the theme?
2. Single `data-track.log` or daily log files?
3. Where should "Apply now" point?

If unanswered, I will proceed with the assumptions above.

## Risks
- **Risks:** the React repeater may overrun; rewrite conflicts between archive and single URLs; Figma fidelity.
- **Cut first:** bonus items, then REST-based filtering (keep the server-rendered fallback), then drag-and-drop (keep up/down buttons).
- **Never cut:** Git workflow, security, tracking helper and log writer, required routes.
