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
| — | No known open medium-or-higher security issue from the 2026-08-19 hardening pass | Mail body rendering, dynamic icon classes, SidebarMenu output, external Invoice links, logout semantics, dangerous URL schemes, package-owned inline-script patterns, and InfoBox progress rendering have been hardened and covered by regression tests. | Keep security regression tests mandatory in CI and review every future raw-HTML, URL, CSS-class, image-source, external-link, or dynamic-presentation API as an explicit trust boundary. |

---

## Open — 2. Correctness & architecture

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open correctness item from the widget-option/translation normalization pass | Shared `icon`, `url`, and `options` semantics now have canonical names with backward-compatible aliases, and package-owned UI strings use the package translation category. | Keep aliases covered until a major release, make canonical properties win when both names are configured, and keep new package-owned UI strings in `adminlte3`. |

---

## Open — 3. Optimizations & performance

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open medium-or-higher performance issue from the 2026-08-19 pass | Duplicate Bootstrap JS and redundant asset dependencies were removed, stable widget CSS moved to cacheable assets, Box reuses Card rendering, and optional AdminLTE plugin families can now be loaded independently from the core shell. | Keep bundle-size, dependency-order, source/minified parity, and declared-file checks mandatory when asset definitions change. |

---

## Open — 4. Tests, QA & compatibility

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| **Low** | Add DOM/XPath assertions for complex widgets | String assertions are useful but can become brittle when harmless markup ordering changes. | Use `DOMDocument`/XPath for structural assertions around classes, links, `rel`, `data-method`, ARIA attributes, and encoded text. |
| **Low** | Add Composer lowest-dependency validation | CI validates supported PHP versions against normal dependency resolution, but minimum declared dependency versions are not independently exercised. | Add a separate `composer update --prefer-lowest --prefer-stable` job where dependency ecosystems permit it; keep it non-blocking initially if upstream constraints are noisy. |
| **Low** | Add static analysis and coding standards | Runtime tests catch behaviour but not all dead imports, type mismatches, duplicated branches, or style drift. | Introduce PHPStan/Psalm at a realistic initial level and a PSR-12-compatible coding-standard tool in separate CI jobs; ratchet strictness gradually. |

---

## Open — 5. Documentation, packaging & release hygiene

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| **Low** | Normalize source-file headers | Several untouched files still contain historical package names, hardcoded versions, outdated license labels, or duplicated metadata despite Composer and the repository license being aligned to MIT. | Remove hardcoded version tags and obsolete package/license metadata from source headers, or replace them with a minimal consistent copyright/license notice. Keep versioning in Git tags / Composer metadata. |
| **Low** | Define a public deprecation policy | `Box` remains deprecated as a public compatibility facade and historical widget property aliases remain available. | Document how long deprecated classes/properties remain available and reserve removals for a major release. |
| **Low** | Add release checklist | Asset packages are sensitive to dependency and browser regressions. | Document a release checklist: Composer validation, full CI, asset graph check, README/CHANGELOG/UPDATE sync, compatibility notes, and tagged release smoke install. |
| **Low** | Clarify semantic-versioning expectations | Security-safe defaults and runtime baseline changes can be breaking even when APIs remain callable. | Document which changes require major/minor releases, especially runtime minimums, implicit asset removal, default encoding, and deprecated widget removal. |

---

## Processed — 2026-08-19 hardening and stabilization

### Security

| Item | Result |
|------|--------|
| `MailboxRead` body rendered as raw HTML by default | Body is encoded by default; explicit HTML mode remains purified by default. |
| `MailboxRead` attachment icon accepted arbitrary HTML | Icons are normalized to safe CSS classes instead of emitted as arbitrary markup. |
| SidebarMenu dynamic icon/badge/template output | Icon/badge classes are normalized, badges are encoded by default, and dynamic rendering relies more heavily on Yii HTML helpers. |
| Dangerous external URL schemes in widgets | Mailbox/Invoice/navbar/small-box style link surfaces reject unsafe or unsupported explicit schemes as applicable. |
| Invoice external link hardening | HTTP(S) and email values are validated; external new-tab links receive `noopener noreferrer`; remote logos require explicit opt-in. |
| Logout rendered as an ordinary GET link | `NavbarUser` right-footer action uses Yii POST semantics by default. |
| Repeated URL / CSS-class / icon-class normalization | Internal `widgets/support/SafeHtml` is the shared policy for safe link schemes, HTTP(S), email links, CSS/icon classes, route arrays, and external-link options. The helper is marked internal rather than becoming a public extension API. |
| CSP compatibility review | Invoice browser printing moved from inline `onclick` to package JS + `data-cinghie-action`; SidebarMenu no longer writes inline submenu display state; static Navbar/Mailbox styles moved to package CSS/utility classes. |
| `InfoBox` arbitrary progress width under strict CSP | Integer progress values remain clamped to 0–100 but now map to package-owned `cinghie-progress-width-*` classes. No `style` attribute is emitted, preserving the public API while supporting `style-src-attr 'none'` for package-owned InfoBox markup. |
| Security policy regression coverage | Dedicated tests cover shared normalization, CSP-safe InfoBox progress, and avoidable package-owned inline JS/style patterns; these tests remain part of the normal PHPUnit CI matrix. |

### Correctness & architecture

| Item | Result |
|------|--------|
| SidebarMenu default action/default route matched via substring logic | Route matching works on route path segments. |
| `Box` unsafe type/class normalization | Legacy widget input normalization was aligned with safer Card-style conventions. |
| `Box` used Kartik GridView directly | It uses the package-local AdminLTE GridView implementation. |
| `Box` and `Card` duplicated card rendering | `Box` is a deprecated compatibility facade/subclass over `Card`; shared card classes, header, body markup, tools, and sanitization use the Card implementation while Box retains its GridView/footer-button API and legacy aliases. |
| `GridView` / `DetailView` changed the application formatter | Shared formatter state is no longer mutated when changing `nullDisplay`. |
| Grid export dependency failure was silently hidden | Explicit export configuration now fails fast with `InvalidConfigException` when the required dropdown package is unavailable. |
| Redundant empty `init()` overrides | Removed from simple widgets where they added no behaviour. |
| `NavTabs` duplicate/ineffective link options | Link construction was simplified. |
| Shared widget option semantics | Canonical `icon`, `url`, and `options` names are used where the underlying concept is genuinely equivalent. `titleIcon`, `searchIconClass`, `iconClass`, `link`, and `option` remain backward-compatible deprecated aliases; canonical values win when both forms are configured. Role-specific URLs such as profile/logout/footer actions remain explicitly named because they are not semantically interchangeable. |
| Package-owned translation categories | Added internal `Translation` support for the `adminlte3` category. Card, Box, ContentHeader, SidebarSearch, SidebarToggle, SidebarUser, and Invoice no longer depend on unrelated `app`, `traits`, or `crm` categories for static UI. Host applications may override `adminlte3`; Timeline keeps only its dynamic domain-action `traits` lookup as an explicit compatibility fallback. |

### Assets & performance

| Item | Result |
|------|--------|
| Duplicate Bootstrap JS | The AdminLTE-packaged Bootstrap bundle was removed from the main asset graph; Yii Bootstrap 4 is the single source. |
| Redundant direct jQuery dependency | Removed where Yii already provides the dependency chain. |
| Inconsistent `appendTimestamp` behaviour | Minified and non-minified bundles are aligned. |
| Large stable widget CSS blocks emitted inline | GridView, DetailView, and Invoice stable styles moved to `assets/css/widgets.css` through `AdminLTEThemeAsset`. |
| Static/deterministic widget presentation embedded in HTML `style` attributes | NavbarLogo, NavbarUser, MailboxRead, Invoice, and bounded InfoBox progress presentation now use package CSS/Bootstrap utility classes instead of package-owned inline styles. |
| Package-owned behavior needed inline JS | `AdminLTEThemeAsset` publishes `assets/js/widgets.js`, allowing behavior such as browser printing to be bound from a data attribute instead of an inline handler. |
| InfoBox progress width asset | Added `assets/css/progress-widths.css` with the exact 0–100 integer width classes required by the existing InfoBox normalization contract; the bundle is cacheable and avoids per-widget generated CSS/JS. |
| Optional plugin assets were coupled to the core shell | Added `AdminLTECoreAsset` / `AdminLTECoreMinifyAsset` containing only AdminLTE core plus Yii/Bootstrap/Font Awesome dependencies. jQuery UI, Moment + Tempus Dominus, and iCheck Bootstrap now have dedicated source/minified AssetBundles. Existing `AdminLTEAsset` / `AdminLTEMinifyAsset` remain backward-compatible convenience aggregates with the historical plugin set and ordering. |
| Minified/non-minified asset parity was implicit | Asset regression tests compare semantic file lists, dependency graphs, timestamp policy, and existence of every declared AdminLTE source/minified file. |
| Bundle size and plugin order were not guarded | Tests assert that the core graph contains only one AdminLTE CSS and one AdminLTE JS file, while the aggregate retains the historical CSS order `Tempus Dominus → iCheck → AdminLTE` and JS order `jQuery UI → Moment → Tempus Dominus → AdminLTE`. |
| Preload/defer strategy | No global `defer`, preload hint, or script-position change is introduced. Yii already exposes `jsOptions` and dependency ordering; AdminLTE assets are normally registered near the end of the document, so global `defer` has little default benefit. Applications may opt in when moving scripts earlier, but should apply the policy coherently to the full dependency chain and validate third-party plugins. Preload remains application-level and measurement-driven because published URLs and critical resources vary by deployment. |

### Packaging

| Item | Result |
|------|--------|
| Missing Kartik dependencies in Composer | Runtime packages used by public widgets are declared explicitly. |
| Broad `@dev` runtime dependencies | Runtime dependency declarations were stabilized/removed as appropriate. |
| Bootstrap version ambiguity | Yii Bootstrap 4 is explicit and the runtime baseline is PHP 8.1+ / Yii 2.0.54+. |
| License metadata mismatch | Composer metadata matches the repository MIT license. Source header cleanup remains an open documentation task. |
| Ionicons dependency / compatibility | Ionicons has been removed from the package dependency and asset graph. AdminLTE3 uses its Font Awesome icon stack; no Ionicons compatibility layer is planned for this package. |

### Tests, code comments & CI

| Item | Result |
|------|--------|
| No automated test suite | PHPUnit configuration and regression tests were added. |
| No GitHub Actions validation | CI runs Composer validation, clean dependency resolution, PHP lint, and PHPUnit on every PHP minor from 8.1 through 8.5. |
| Headless Kartik/Yii test environment incomplete | Test bootstrap configures Asset Packagist aliases, assets/runtime directories, request context, Bootstrap 4, GridView module, and local translation sources. |
| Public widgets lacked broad smoke coverage | Public widget classes have package-level smoke/load coverage, with dedicated behavioural/security tests retained for complex widgets. |
| SidebarMenu route matrix was narrow | Regression coverage includes trailing default actions, module default routes, query parameters, active parents, invisible items, headers, and similarly named non-matching routes. |
| Comment/PHPDoc conventions were implicit | `docs/CODING_STYLE.md` records Yii-oriented output encoding/trust-boundary rules, useful PHPDoc expectations, PSR-12 formatting, CSP conventions, and public-package documentation restrictions. A regression test requires every public widget class to retain class-level PHPDoc. |
| Source-level Yii/PSR hygiene was implicit | `Yii2BestPracticesTest` guards valid/used `Yii` imports and the normative PSR-12 ordering of class/function/constant import groups without inventing an alphabetical requirement that PSR-12 does not define. Broad formatting enforcement remains part of the separate coding-standard roadmap item. |
| Option alias and translation ownership regressions | Dedicated tests cover canonical-vs-legacy option precedence, legacy-only rendering, lazy package translation registration, application overrides, and accidental reintroduction of legacy UI translation categories. |
| Asset graph regressions | Dedicated tests now cover core-vs-aggregate payload, plugin ordering, source/minified parity, dependency equivalence, timestamp parity, and declared vendor-file existence. |

---

## Possible future expansions

These are **not current defects**. They are public-package evolution ideas that should be evaluated against demand, maintenance cost, and backward compatibility.

### 1. Bootstrap 5 / AdminLTE 4 migration path

- Evaluate a next-generation package layer for Bootstrap 5 and the AdminLTE version that officially targets it.
- Avoid mixing Bootstrap generations in the same package runtime.
- Prefer a major-version migration with clear compatibility boundaries rather than feature-detecting both Bootstrap 4 and 5 throughout every widget.
- Provide migration documentation for renamed utility classes, JS APIs, data attributes, and Kartik dependencies.

### 2. Additional modular plugin AssetBundles

- The initial split covers jQuery UI, date/time, and iCheck while preserving the historical aggregate bundle.
- Add new optional families only when a public widget or documented integration needs them, for example Calendar or ChartJS.
- Keep every new source/minified pair under the same dependency/parity/file-existence tests so optional bundles never reintroduce duplicate jQuery or Bootstrap copies.

### 3. Shared widget rendering utilities beyond safety policy

- `SafeHtml` now centralizes security-sensitive URL/class/icon normalization.
- Future internal utilities could consolidate repeated AdminLTE tool-button markup, contextual types, and common container structures without broadening the public API prematurely.
- Preserve deprecated adapters as thin layers over the primary implementation.

### 4. Accessibility improvements

- Review interactive tools (collapse, remove, maximize, dropdowns, tabs, sidebar) for ARIA labels/state and keyboard behaviour.
- Add semantic labels to icon-only buttons.
- Add automated structural accessibility assertions where feasible; consider browser-level testing only if it provides stable value.

### 5. Stronger end-to-end CSP verification

- Package-owned Invoice, SidebarMenu, Navbar, Mailbox, and InfoBox markup now avoid the previously identified inline-handler/static-style patterns.
- Add a small browser fixture with a restrictive CSP to validate the complete AdminLTE/Kartik/third-party asset stack, because upstream plugins can have requirements outside this package's generated markup.

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

### 9. Additional AdminLTE widgets

- Add a `Calendar` widget with explicit event-data encoding, bounded rendering responsibilities, and optional asset registration that does not force calendar dependencies onto unrelated pages.
- Add a `ChartJS` widget with JSON-safe dataset/configuration encoding, deterministic canvas identifiers, responsive defaults, and an optional Chart.js asset bundle.
- Add dedicated `404` and `500` error widgets/views with encoded user-facing text, no sensitive exception disclosure by default, accessible navigation actions, and package styling consistent with AdminLTE error pages.
- Cover all four additions with public smoke tests, security-focused rendering tests, documentation examples, and asset-dependency checks before promoting them to the stable public API.

---

## History / operations

### 2026-08-19

- Completed the first comprehensive package hardening pass covering rendering safety, route correctness, dependency declarations, asset duplication, formatter isolation, Invoice/Navbar URL handling, and browser-cache-friendly widget CSS.
- Added a supported runtime baseline of PHP 8.1+ and Yii 2.0.54+ with explicit Yii Bootstrap 4 and Kartik dependencies.
- Added the first automated test suite and expanded the GitHub Actions matrix to every PHP minor from 8.1 through 8.5.
- Consolidated legacy `Box` rendering onto `Card` while preserving Box defaults, aliases, GridView support, and footer actions.
- Expanded regression coverage across the public widget surface and deeper SidebarMenu route/parent/visibility cases.
- Removed Ionicons from the package dependency and asset graph permanently; the package standard icon stack is Font Awesome.
- Centralized URL, HTTP(S), email, CSS-class, icon-class, and external-link safety policy in internal `SafeHtml`; migrated the main security-sensitive widgets to it.
- Completed the package-owned CSP pass: Invoice print behavior moved to an external asset script, SidebarMenu active state no longer requires inline display styles, static Navbar/Mailbox/Invoice styles moved into package CSS/utility classes, and InfoBox progress widths now use bounded package CSS classes instead of inline styles.
- Normalized genuinely shared widget options around canonical `icon`, `url`, and `options` names while keeping deprecated aliases backward compatible and covered by tests.
- Moved static package-owned widget strings to the internal `adminlte3` translation category with application override support; retained only Timeline's dynamic domain-action translation as an explicit legacy fallback.
- Split optional AdminLTE plugin assets from a new core-only bundle while preserving the historical aggregate bundle, and added source/minified parity, vendor-file existence, bundle-size, and asset-order regression coverage.
- Evaluated script preloading/defer and kept it opt-in/application-owned rather than changing package defaults without measured benefit.
- Added public coding/PHPDoc conventions, class-level documentation guards, source-level Yii/PSR best-practice tests, option-alias regression tests, translation-ownership guards, and asset-graph regression tests.
- Remaining roadmap work is intentionally low-risk: source metadata/header cleanup, static analysis/coding standards, incremental release/documentation hygiene, and optional browser-level verification of the complete third-party CSP stack.
