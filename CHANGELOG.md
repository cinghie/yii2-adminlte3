# Changelog — cinghie/yii2-adminlte3

All notable changes to this package are documented in this file.

Format based on [Keep a Changelog](https://keepachangelog.com/).

### Documentation rules

- `CHANGELOG.md` and `UPDATE.md` are written in English.
- Update both files in the same change set whenever code, configuration, assets, security behaviour, compatibility requirements, or public APIs change.
- `CHANGELOG.md` uses dated headings (`## YYYY-MM-DD`, newest first); do not use an `Unreleased` section.
- `UPDATE.md` keeps open priorities first, followed by detailed open items, processed items, future expansion ideas, and dated history.
- Public documentation must not contain credentials, private hosts, customer data, internal project names, private paths, exploit recipes, or references to non-public systems.

---

## 2026-08-19

### Added

#### Security policy and CSP support
- Added the internal `widgets/support/SafeHtml` policy helper for CSS/icon class normalization, safe link schemes, HTTP(S) validation, email links, and `_blank` hardening.
- Added `assets/js/widgets.js` for package-owned behavior that should not require inline JavaScript; Invoice browser printing delegates through `data-cinghie-action="print"`.
- Added `assets/css/progress-widths.css` with bounded 0–100 percentage classes so `InfoBox` progress keeps its existing integer precision without inline style attributes.
- Added CSP regression coverage for Invoice printing, MailboxRead attachments, SidebarMenu active submenus, InfoBox progress rendering, and package-owned external behavior.

#### Translation ownership
- Added the internal `widgets/support/Translation` helper and package-owned `adminlte3` message catalog.
- The `adminlte3` source is registered lazily only when the host application has not already configured that category, preserving application overrides.

#### Modular assets
- Added `AdminLTECoreAsset` and `AdminLTECoreMinifyAsset` for pages that only need the AdminLTE shell without optional plugin families.
- Added optional source/minified bundles for jQuery UI (`AdminLTEJqueryUiAsset`), Moment + Tempus Dominus (`AdminLTEDateTimeAsset`), and iCheck Bootstrap (`AdminLTEIcheckAsset`).
- Added asset graph tests that verify source/minified parity, dependency equivalence, declared vendor-file existence, core payload size, and historical aggregate ordering.

#### Code documentation and quality gates
- Added `docs/CODING_STYLE.md` with public Yii/PHPDoc, trust-boundary, security, CSP, and PSR-12 conventions for contributors.
- Clarified that PSR-12 defines import-block ordering but does not mandate alphabetical sorting within each import group.
- Added a documentation guard requiring every public widget class to expose a non-empty class-level PHPDoc block.
- Added `SourceMetadataTest` to prevent obsolete per-file package/version/company/GPL metadata from being reintroduced.
- Added PHPStan 2.x at level 5 and PHP_CodeSniffer 4 with PSR-12 configuration.
- Added Composer `analyse`, `cs`, and `quality` scripts plus a dedicated blocking PHP 8.3 `quality` GitHub Actions workflow.

#### Tests and continuous integration
- Added PHPUnit configuration and a headless Yii web-application bootstrap for package tests.
- Added regression coverage for mail/XSS handling, attachment icon normalization, widget rendering hardening, Invoice URL policy, formatter isolation, AdminLTE asset dependencies, CSP-safe InfoBox progress, widget option aliases, and translation ownership.
- Added `Yii2BestPracticesTest` for valid/used `Yii` imports and normative PSR-12 class/function/constant import-group ordering.
- Added a reusable `HtmlDomTestCase` based on `DOMDocument`/`DOMXPath` and migrated complex rendering assertions for classes, links, `rel`, `target`, `data-method`, ARIA attributes, and encoded text away from brittle serialized-markup ordering.
- Added structural accessibility coverage for AdminLTE Card tool attributes.
- Expanded `SidebarMenu` regression coverage for trailing default actions, module default routes, query parameters, active parents, invisible items, headers, and similarly named non-matching routes.
- Expanded GitHub Actions validation to every PHP minor from 8.1 through 8.5 with strict Composer validation, clean dependency resolution, PHP syntax linting, and PHPUnit.
- Added a separate PHP 8.1 `prefer-lowest` job that resolves minimum declared dependencies with `composer update --prefer-lowest --prefer-stable`, then attempts a clean install, lint, and test run. The job is intentionally non-blocking while legacy minimum-version combinations exhibit upstream Composer/package extraction noise.

### Changed

#### Shared rendering safety
- `Card`, `Box`, `MailboxRead`, `SidebarMenu`, `InfoBox`, `SmallBox`, `NavbarButton`, `NavbarLogo`, `NavbarUser`, and `Invoice` reuse the internal `SafeHtml` normalization policy instead of maintaining independent URL/class validation branches.
- `SafeHtml::linkUrl()` validates absolute HTTP(S) URLs and rejects protocol-relative URLs instead of treating them as ordinary relative links.
- Static package-owned presentation moved from inline style/handler patterns into package CSS/JS or Bootstrap utility classes where practical.
- `InfoBox::$progress` maps its normalized 0–100 value to package CSS classes instead of generating a `style="width: ...%"` attribute.

#### Runtime requirements and package dependencies
- Raised the supported runtime baseline to PHP 8.1+ and Yii 2.0.54+.
- Declared `yiisoft/yii2-bootstrap4` and Kartik runtime packages used by public widgets explicitly.
- Removed broad development-only runtime constraints and unused runtime dependencies.
- Removed `cinghie/yii2-ionicons`; AdminLTE3 relies on its Font Awesome icon stack.
- Aligned Composer license metadata with the repository MIT license.

#### Asset graph and browser caching
- Removed the duplicate AdminLTE-packaged Bootstrap JavaScript bundle and rely on Yii Bootstrap 4 as the single Bootstrap JS source.
- Removed the redundant direct `JqueryAsset` dependency where Yii already provides the dependency chain.
- Made `appendTimestamp` behavior consistent between minified and non-minified bundles.
- Moved stable widget styles into cacheable package CSS and package-owned behavior into `assets/js/widgets.js`.
- Converted `AdminLTEAsset` and `AdminLTEMinifyAsset` into backward-compatible convenience aggregates. They still load the historical plugin set in the same order, while new code can choose core-only or individual optional plugin bundles.
- The historical aggregate CSS order remains Tempus Dominus → iCheck → AdminLTE; JavaScript order remains jQuery UI → Moment → Tempus Dominus → AdminLTE.
- Evaluated `defer`/preload and intentionally kept them out of package defaults. Yii exposes `jsOptions` and dependency ordering for application-level opt-in, while these scripts normally load near the end of the document where global `defer` offers little default benefit.

#### Widget architecture and behaviour
- Converted legacy `Box` into a backward-compatible facade/subclass over `Card`, preserving defaults, GridView body support, footer actions, and historical aliases.
- Normalized genuinely shared option names: `Card::$icon`, `SmallBox::$url`, `NavbarButton::$options`, and sidebar `$icon` options are canonical; historical names remain deprecated aliases and canonical values win when both are configured.
- Kept role-specific URLs such as profile/logout/footer actions explicitly named rather than forcing unrelated targets into a generic alias.
- Removed redundant no-op `init()` implementations and simplified `NavTabs` link generation.

#### Translation categories
- Card, Box, ContentHeader, SidebarSearch, SidebarToggle, SidebarUser, and Invoice now use `adminlte3` for package-owned UI strings instead of unrelated `app`, `traits`, or `crm` catalogs.
- `Timeline` retains its dynamic `traits` lookup only for externally supplied action keys as an explicit compatibility fallback.

#### Source metadata and coding standards
- Removed stale per-file `@package`, hardcoded version, historical company metadata, and GPL license text so the repository `LICENSE` and Composer metadata are authoritative.
- Normalized affected source headers without duplicating release/version information that belongs in Git tags and Composer metadata.
- PHPStan bootstraps Yii explicitly and treats legacy PHPDoc declarations conservatively while keeping level 5 error-free on the supported scope.
- PHPCS treats the PSR-12 120-character line recommendation as non-blocking and keeps narrow, documented transitional formatting exceptions for legacy multi-line control structures; `Timeline` remains excluded from the initial quality scope because it imports optional external domain models.

#### Documentation
- Updated README requirements, installation guidance, modular asset registration, script-loading policy, security defaults, test instructions, and compatibility notes.
- Removed the recommendation to install the package with a broad `@dev` stability flag.
- Streamlined `UPDATE.md` so the dated processed section does not repeat “Processed” in every subsection/result.
- Closed the package-owned InfoBox CSP item, widget-option normalization, translation ownership, optional asset splitting, source/minified parity, `defer`/preload evaluation, source-metadata cleanup, static-analysis/coding-standard, DOM/XPath structural-test, and lowest-dependency-validation roadmap items.
- Documented that lowest-dependency CI remains observational/non-blocking until the complete minimum set installs and executes reliably; successful dependency resolution alone is not presented as runtime compatibility certification.
- Added future widget candidates for Calendar, ChartJS, dedicated 404, and dedicated 500 rendering.

### Fixed

#### Mail rendering security
- `MailboxRead` HTML-encodes message bodies by default; explicit HTML mode remains purified by default.
- Attachment icons are validated CSS classes rather than arbitrary HTML fragments, and dangerous attachment/image URL schemes are rejected.

#### Sidebar navigation safety and route matching
- Sanitized SidebarMenu icon/badge classes, encode badge text by default, and compare route path segments instead of substring positions.

#### GridView and DetailView formatter isolation
- Prevented `GridView` and `DetailView` from mutating the application-wide formatter when changing `nullDisplay`.
- Grid export now fails fast with `InvalidConfigException` when its required Bootstrap 4 dropdown dependency is unavailable.

#### Invoice and navigation URL hardening
- Validated HTTP(S) website URLs and email/PEC addresses before generating links and add `noopener noreferrer` to generated external new-tab links.
- Remote company logos are disabled by default and require explicit opt-in for trusted HTTP(S) sources.
- Default Invoice printing no longer uses inline JavaScript; `NavbarUser` logout uses Yii POST semantics by default.

#### InfoBox CSP compatibility
- Removed the remaining package-owned dynamic `style` attribute from InfoBox progress rendering while preserving integer 0–100 clamp semantics.

### Performance

- Reduced duplicated front-end payload by removing the second Bootstrap JS copy and redundant dependencies.
- Reduced repeated inline CSS output by moving stable widget styles into cacheable package assets.
- Added a core-only AdminLTE asset path containing one AdminLTE CSS and one AdminLTE JS file instead of forcing jQuery UI, Moment, Tempus Dominus, and iCheck onto every page.
- Preserved the complete historical bundle as an aggregate for backward compatibility while allowing page-level optional plugin registration.
- Added CI guards for asset payload, ordering, source/minified parity, and declared vendor files.
- Reduced maintenance duplication by routing legacy Box rendering through Card, shared safety normalization through one internal helper, and package UI translation through one internal category helper.

### Validation

- CI targets every PHP minor from 8.1 through 8.5.
- Every runtime CI job performs strict Composer validation, clean dependency installation, syntax linting, and PHPUnit regression tests.
- A separate blocking PHP 8.3 quality job performs PHPStan level 5 analysis and PHP_CodeSniffer/PSR-12 checks.
- A separate non-blocking PHP 8.1 lowest-dependency job resolves the minimum dependency graph and attempts installation/tests; current upstream extraction noise is retained as visible CI signal rather than weakening the normal runtime gates.

## 2026-07-30

### Fixed

#### Forms — input-group icon layout (Font Awesome)
- `adminlte-theme.css`: addon icon sizing covers Font Awesome (`.fa` / `.fas` / `.far`) and BS4 `.input-group-text`, not only `.glyphicon`.
- Removed redundant Select2 flex helpers (`s2-input-group` / `width: 1%`); with correct Kartik `bsVersion`, Krajee BS4 theme already handles Select2 input-groups.
- `AdminLTEThemeAsset`: `appendTimestamp` + debug `forceCopy` so theme CSS changes are not stuck behind Yii’s path-based publish hash / browser cache.

## 2026-07-25

### Added

- `assets/AdminLTEThemeAsset` + `assets/css/adminlte-theme.css` — shell theme overrides (typography, sidebar, content, cards, glyphicons→FA, BS3 float helpers). Registered automatically after AdminLTE assets so the package is exportable without app-level AdminLTE CSS.
- Card header vertical alignment: `.card-header` flex + `.card-title { margin: 0 }`.

### Changed

- AdminLTE bundles stay at module root (`cinghie\adminlte3`); package theme CSS lives in `cinghie\adminlte3\assets\AdminLTEThemeAsset`.
- `widgets/Card` header gets `align-items-center` by default for title/tools alignment.

## 2026-07-23

### Added

- `widgets/DetailView` — extends Kartik DetailView with AdminLTE 3 / Bootstrap 4 card panel normalization.
- `widgets/Card` — card with `type`/`outline`, collapse/remove/maximize tools, `begin()`/`end()` content capture, and backward compatibility for legacy `cardClass` / `COLOR_*`.

### Changed

- `widgets/Invoice` — data-driven AdminLTE 3 invoice aligned with the official AdminLTE example; widget-scoped CSS, safer logo handling, and print behavior aligned with the widget API.
- Invoice From/To blocks render optional fiscal fields when present: VAT, tax code, SDI, PEC, website, fax/mobile; metadata can show type, sent date, and payment method code.
