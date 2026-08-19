# yii2-adminlte3 — UPDATE roadmap

Maintenance, stabilization, and evolution notes for the **public** Yii2 AdminLTE 3 package.

### Documentation rules

- `CHANGELOG.md` and `UPDATE.md` are written in English.
- Update both files in the same change set whenever code, configuration, assets, security behaviour, compatibility requirements, or public APIs change.
- `CHANGELOG.md` uses dated headings (`## YYYY-MM-DD`, newest first); do not use an `Unreleased` section.
- `UPDATE.md` history uses dated headings (`YYYY-MM-DD`, newest first) to record what changed or was decided over time.
- `UPDATE.md` structure is: open priority list, detailed open items by area, processed items, possible future expansions, then dated history/operations notes.
- Public documentation must never contain credentials, private hosts, customer data, internal project names, personal paths, exploit recipes, or references to non-public systems.
- Security notes should describe safe defaults, affected surfaces, and remediation strategy without publishing attack payloads.

Urgency: **Critical** · **High** · **Medium** · **Low**.

---

## Priority list

1. Normalize source-file metadata and license headers across the package — **Low**.
2. Add static analysis and coding-standard automation after the current runtime baseline is stable — **Low**.

---

## Open — 1. Security & safe rendering

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open medium-or-higher security issue from the 2026-08-19 hardening pass | Mail body rendering, dynamic icon classes, SidebarMenu output, external Invoice links, logout semantics, and dangerous URL schemes were hardened and covered by regression tests. | Keep security regression tests mandatory in CI and review every future raw-HTML or dynamic-URL API as an explicit trust boundary. |
| **Low** | Centralize safe URL / CSS-class normalization helpers | Similar validation logic exists in multiple widgets (`Invoice`, `MailboxRead`, `SidebarMenu`, `Box`, `SmallBox`, navbar widgets). Independent copies can drift over time. | Introduce a small internal helper/trait for URL schemes, HTTP(S) validation, icon/CSS-class normalization, and external-link options. Keep the helper internal unless a stable public API is intentionally designed. |
| **Low** | Content Security Policy compatibility review | Inline handlers/patterns may be acceptable today but stronger CSP deployments can require nonce-based or external JavaScript handling. | Audit widgets for inline event handlers/scripts and prefer Yii-registered JavaScript or data attributes where practical. Document CSP expectations without weakening current defaults. |

---

## Open — 2. Correctness & architecture

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| **Low** | Normalize shared widget option semantics | Similar concepts (`type`, `icon`, `url`, link options, outline, encode flags) are not always named or normalized identically across widgets. | Gradually align property names/defaults in backward-compatible releases; use deprecation aliases before removals in a major release. |
| **Low** | Review translation categories owned by external/legacy packages | Some widgets still reference translation categories historically supplied by other modules/packages. That makes standalone rendering less predictable. | Move package-owned user-facing strings to an `adminlte3` translation category, leaving compatibility fallbacks only where needed. |

---

## Open — 3. Optimizations & performance

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open medium-or-higher performance issue from the 2026-08-19 pass | Duplicate Bootstrap JS and redundant asset dependencies were removed, stable widget CSS moved to cacheable assets, and Box now reuses Card rendering. | Keep bundle-size and asset-order checks in CI when dependencies change. |
| **Low** | Split optional plugin assets from the core bundle | The main AdminLTE bundle still carries plugins that not every page uses. | Consider small optional AssetBundles for plugin families so applications can register only what a page requires. Preserve a convenience aggregate bundle for backward compatibility. |
| **Low** | Minified/non-minified asset parity tests | Current bundles are aligned, but future edits can accidentally change dependencies or plugin ordering between debug and production variants. | Add a test asserting equivalent dependency graphs and corresponding source/minified files. |
| **Low** | Evaluate preloading/defer strategy for non-critical JS | AdminLTE/plugin scripts can delay parsing on large admin pages. | Benchmark before changing defaults. If useful, expose opt-in script-position/defer guidance rather than introducing a breaking global behaviour change. |

---

## Open — 4. Tests, QA & compatibility

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| **Low** | Add DOM/XPath assertions for complex widgets | String assertions are useful but can become brittle when harmless markup ordering changes. | Use `DOMDocument`/XPath for structural assertions around classes, links, `rel`, `data-method`, ARIA attributes, and encoded text. |
| **Low** | Add Composer lowest-dependency validation | CI validates supported PHP versions against normal dependency resolution, but minimum declared dependency versions are not independently exercised. | Add a separate `composer update --prefer-lowest --prefer-stable` job where dependency ecosystems permit it; keep it non-blocking initially if upstream constraints are noisy. |
| **Low** | Add static analysis and coding standards | Runtime tests catch behaviour but not all dead imports, type mismatches, duplicated branches, or style drift. | Introduce PHPStan/Psalm at a realistic initial level and a coding-standard tool in separate CI jobs; ratchet strictness gradually. |

---

## Open — 5. Documentation, packaging & release hygiene

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| **Low** | Normalize source-file headers | Several files still contain historical package names, hardcoded versions, outdated license labels, or duplicated metadata despite Composer and the repository license being aligned to MIT. | Remove hardcoded version tags and obsolete package/license metadata from source headers, or replace them with a minimal consistent copyright/license notice. |
| **Low** | Define a public deprecation policy | `Box` remains deprecated as a public compatibility facade and future normalization may need additional aliases/deprecations. | Document how long deprecated classes/properties remain available and reserve removals for a major release. |
| **Low** | Add release checklist | Asset packages are sensitive to dependency and browser regressions. | Document a release checklist: Composer validation, full CI, asset graph check, README/CHANGELOG/UPDATE sync, compatibility notes, and tagged release smoke install. |
| **Low** | Clarify semantic-versioning expectations | Security-safe defaults and runtime baseline changes can be breaking even when APIs remain callable. | Document which changes require major/minor releases, especially runtime minimums, implicit asset removal, default encoding, and deprecated widget removal. |

---

## Processed — 2026-08-19 hardening and stabilization

### Processed — Security

| Item | Result |
|------|--------|
| `MailboxRead` body rendered as raw HTML by default | **Processed.** Body is encoded by default; explicit HTML mode remains purified by default. |
| `MailboxRead` attachment icon accepted arbitrary HTML | **Processed.** Icons are normalized to safe CSS classes instead of emitted as arbitrary markup. |
| SidebarMenu dynamic icon/badge/template output | **Processed.** Icon/badge classes are sanitized, badges are encoded by default, and dynamic rendering relies more heavily on Yii HTML helpers. |
| Dangerous external URL schemes in widgets | **Processed.** Mailbox/Invoice/navbar/small-box style link surfaces reject unsafe schemes as applicable. |
| Invoice external link hardening | **Processed.** HTTP(S) and email values are validated; external new-tab links receive `noopener noreferrer`; remote logos require explicit opt-in. |
| Logout rendered as an ordinary GET link | **Processed.** `NavbarUser` right-footer action uses Yii POST semantics by default. |

### Processed — Correctness & architecture

| Item | Result |
|------|--------|
| SidebarMenu default action/default route matched via substring logic | **Processed.** Route matching now works on route path segments. |
| `Box` unsafe type/class normalization | **Processed.** Legacy widget input normalization was aligned with safer Card-style conventions. |
| `Box` used Kartik GridView directly | **Processed.** It uses the package-local AdminLTE GridView implementation. |
| `Box` and `Card` duplicated card rendering | **Processed.** `Box` is now a deprecated compatibility facade/subclass over `Card`; shared card classes, header, body markup, tools, and sanitization use the Card implementation while Box retains its GridView/footer-button API and legacy aliases. |
| `GridView` / `DetailView` changed the application formatter | **Processed.** Shared formatter state is no longer mutated when changing `nullDisplay`. |
| Grid export dependency failure was silently hidden | **Processed.** Explicit export configuration now fails fast with `InvalidConfigException` when the required dropdown package is unavailable. |
| Redundant empty `init()` overrides | **Processed.** Removed from simple widgets where they added no behaviour. |
| `NavTabs` duplicate/ineffective link options | **Processed.** Link construction was simplified. |

### Processed — Assets & performance

| Item | Result |
|------|--------|
| Duplicate Bootstrap JS | **Processed.** The AdminLTE-packaged Bootstrap bundle was removed from the main asset graph; Yii Bootstrap 4 is the single source. |
| Redundant direct jQuery dependency | **Processed.** Removed where Yii already provides the dependency chain. |
| Inconsistent `appendTimestamp` behaviour | **Processed.** Minified and non-minified bundles are aligned. |
| Large stable widget CSS blocks emitted inline | **Processed.** GridView, DetailView, and Invoice stable styles moved to `assets/css/widgets.css` through `AdminLTEThemeAsset`. |

### Processed — Packaging

| Item | Result |
|------|--------|
| Missing Kartik dependencies in Composer | **Processed.** Runtime packages used by public widgets are declared explicitly. |
| Broad `@dev` runtime dependencies | **Processed.** Runtime dependency declarations were stabilized/removed as appropriate. |
| Bootstrap version ambiguity | **Processed.** Yii Bootstrap 4 is explicit and the runtime baseline is PHP 8.1+ / Yii 2.0.54+. |
| License metadata mismatch | **Processed.** Composer metadata matches the repository MIT license. Source header cleanup remains an open documentation task. |
| Ionicons dependency / compatibility | **Processed.** Ionicons has been removed from the package dependency and asset graph. AdminLTE3 uses its Font Awesome icon stack; no Ionicons compatibility layer is planned for this package. |

### Processed — Tests & CI

| Item | Result |
|------|--------|
| No automated test suite | **Processed.** PHPUnit configuration and regression tests were added. |
| No GitHub Actions validation | **Processed.** CI runs Composer validation, clean dependency resolution, PHP lint, and PHPUnit on PHP 8.1, 8.3, and 8.5. |
| Headless Kartik/Yii test environment incomplete | **Processed.** Test bootstrap configures Asset Packagist aliases, assets/runtime directories, request context, Bootstrap 4, GridView module, and local translation sources. |
| Public widgets lacked broad smoke coverage | **Processed.** Public widget classes now have package-level smoke/load coverage, with dedicated behavioural/security tests retained for complex widgets. |
| SidebarMenu route matrix was narrow | **Processed.** Regression coverage now includes trailing default actions, module default routes, query parameters, active parents, invisible items, headers, and similarly named non-matching routes. |

---

## Possible future expansions

These are **not current defects**. They are public-package evolution ideas that should be evaluated against demand, maintenance cost, and backward compatibility.

### 1. Bootstrap 5 / AdminLTE 4 migration path

- Evaluate a next-generation package layer for Bootstrap 5 and the AdminLTE version that officially targets it.
- Avoid mixing Bootstrap generations in the same package runtime.
- Prefer a major-version migration with clear compatibility boundaries rather than feature-detecting both Bootstrap 4 and 5 throughout every widget.
- Provide migration documentation for renamed utility classes, JS APIs, data attributes, and Kartik dependencies.

### 2. Modular plugin AssetBundles

- Split optional AdminLTE plugins (date/time, charts, advanced inputs, etc.) into dedicated bundles.
- Keep the current aggregate bundle available for ease of adoption while allowing performance-sensitive applications to opt into smaller bundles.
- Add dependency-graph tests so optional bundles never register duplicate jQuery/Bootstrap copies.

### 3. Unified widget rendering utilities

- Introduce internal utilities for card types, icon classes, safe URLs, external-link attributes, tool buttons, and common AdminLTE markup.
- Use them to reduce repeated security-sensitive string manipulation and make widget behaviour more consistent.
- Keep the first iteration internal to avoid prematurely expanding the public API surface.

### 4. Accessibility improvements

- Review interactive tools (collapse, remove, maximize, dropdowns, tabs, sidebar) for ARIA labels/state and keyboard behaviour.
- Add semantic labels to icon-only buttons.
- Add automated structural accessibility assertions where feasible; consider browser-level testing only if it provides stable value.

### 5. Stronger CSP-friendly integration

- Reduce inline event handlers where possible.
- Register JavaScript through Yii View APIs and data attributes.
- Document CSP requirements for AdminLTE and third-party plugins and avoid recommending permissive `unsafe-inline` defaults.

### 6. Visual regression / browser smoke testing

- Add a small fixture application rendering representative widgets and layouts.
- Run browser smoke tests for collapse/dropdown/sidebar behaviours and major responsive breakpoints.
- If visual snapshots are introduced, keep them intentionally small to avoid high-maintenance pixel noise.

### 7. Theme customization contract

- Define CSS custom properties or documented extension points for package-level theme overrides where AdminLTE 3 allows it.
- Keep application overrides separate from vendor source files.
- Document asset ordering so custom themes reliably load after AdminLTE core and package defaults.

### 8. Release automation

- Add automated release validation for tags: clean install from the tagged package, Composer metadata validation, full CI, and package API smoke tests.
- Optionally generate release notes from dated changelog entries while keeping `CHANGELOG.md` human-maintained.

---

## History / operations

### 2026-08-19

- Completed the first comprehensive package hardening pass covering rendering safety, route correctness, dependency declarations, asset duplication, formatter isolation, Invoice/Navbar URL handling, and browser-cache-friendly widget CSS.
- Added a supported runtime baseline of PHP 8.1+ and Yii 2.0.54+ with explicit Yii Bootstrap 4 and Kartik dependencies.
- Added the first automated test suite and GitHub Actions matrix for PHP 8.1, 8.3, and 8.5.
- Consolidated legacy `Box` rendering onto `Card` while preserving Box defaults, aliases, GridView support, and footer actions.
- Expanded regression coverage across the public widget surface and deeper SidebarMenu route/parent/visibility cases.
- Removed Ionicons from the package dependency and asset graph permanently; the package standard icon stack is Font Awesome.
- Remaining roadmap work is intentionally low-risk: source metadata/header cleanup, static analysis/coding standards, and incremental release/documentation hygiene.
