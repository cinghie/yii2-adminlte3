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
- Added the internal `widgets/support/SafeHtml` policy helper for CSS/icon class normalization, safe link schemes, HTTP(S) validation, email links, and `_blank` hardening. The helper is intentionally internal so the security policy can evolve without creating a new public API contract.
- Added `assets/js/widgets.js` for package-owned behavior that should not require inline JavaScript. The Invoice browser-print action now delegates through `data-cinghie-action="print"`.
- Added `assets/css/progress-widths.css` with bounded 0–100 percentage classes so `InfoBox` progress can retain its existing integer precision without emitting inline style attributes.
- Added CSP regression coverage for Invoice printing, MailboxRead attachments, SidebarMenu active submenus, InfoBox progress rendering, and the external widget behavior script.
- Added focused `SafeHtml` regression tests covering dangerous/unknown schemes, malformed and protocol-relative absolute targets, HTTP(S), email validation, CSS-class normalization, and external-link attributes.

#### Translation ownership
- Added the internal `widgets/support/Translation` helper and package-owned `adminlte3` message catalog.
- The `adminlte3` source is registered lazily only when the host application has not already configured that category, so application-level translation overrides remain authoritative.

#### Code documentation
- Added `docs/CODING_STYLE.md` with public Yii/PHPDoc, trust-boundary, security, CSP, and PSR-12 conventions for contributors.
- Clarified that PSR-12 defines import-block ordering but does not mandate alphabetical sorting within each import group.
- Added a documentation guard test requiring every public widget class to expose a non-empty class-level PHPDoc block.
- Completed class/property/trust-boundary PHPDoc for the security-sensitive widgets touched by this pass, including `Alert`, `Card`, `Box`, `MailboxRead`, `SidebarMenu`, `Invoice`, `InfoBox`, `SmallBox`, `NavbarButton`, `NavbarLogo`, and `NavbarUser`.

#### Tests and continuous integration
- Added PHPUnit configuration and a headless Yii web-application bootstrap for package tests.
- Added regression coverage for `MailboxRead` HTML/XSS handling, attachment icon normalization, widget rendering hardening, `Invoice` URL policy, formatter isolation, AdminLTE asset dependencies, and CSP-safe InfoBox progress clamping/rendering.
- Added public-widget smoke coverage across the package and explicit backward-compatibility coverage for the legacy `Box` API.
- Added `Yii2BestPracticesTest` to guard valid/used `Yii` imports and the normative PSR-12 class/function/constant import-group order without imposing a non-standard alphabetical rule.
- Added `WidgetOptionSemanticsTest` for canonical-vs-legacy option precedence and legacy-only rendering.
- Added `TranslationTest` for lazy `adminlte3` registration, host override preservation, and prevention of accidental package UI dependencies on legacy `app`, `crm`, or `traits` categories.
- Expanded `SidebarMenu` regression coverage for trailing default actions, module default routes, query-parameter matching, active parents, invisible items, headers, and similarly named non-matching routes.
- Expanded GitHub Actions validation to every PHP minor from 8.1 through 8.5 with strict Composer validation, clean dependency resolution, PHP syntax linting, and PHPUnit execution.
- Added test-only Asset Packagist aliases, runtime asset directories, request context, GridView module configuration, Bootstrap 4 configuration, and local translation sources required by Kartik widgets under CLI.

### Changed

#### Shared rendering safety
- `Card`, `Box`, `MailboxRead`, `SidebarMenu`, `InfoBox`, `SmallBox`, `NavbarButton`, `NavbarLogo`, `NavbarUser`, and `Invoice` reuse the internal `SafeHtml` normalization policy instead of maintaining independent URL/class validation branches.
- `SafeHtml::linkUrl()` validates absolute HTTP(S) URLs through the strict HTTP helper and rejects protocol-relative URLs instead of treating them as ordinary relative links.
- `NavbarLogo` and `NavbarUser` static image presentation moved from inline `style` attributes to package CSS classes.
- `MailboxRead` attachment truncation moved from an inline style declaration to Bootstrap utility classes.
- `SidebarMenu` active submenus rely on AdminLTE's `menu-open` state instead of emitting an inline `display` style.
- `Invoice` no longer emits an inline `onclick` handler for browser printing and no longer uses an inline margin style on the PDF action.
- `InfoBox::$progress` maps its existing integer 0–100 normalized value to `cinghie-progress-width-*` package CSS classes instead of generating a `style="width: ...%"` attribute.

#### Runtime requirements and package dependencies
- Raised the supported runtime baseline to PHP 8.1+ and Yii 2.0.54+.
- Declared `yiisoft/yii2-bootstrap4` explicitly.
- Declared the Kartik runtime packages used by public widgets (`yii2-grid`, `yii2-detail-view`, and Bootstrap 4 dropdown support).
- Removed broad development-only runtime constraints and unused runtime dependencies.
- Removed `cinghie/yii2-ionicons` from the package dependency and asset graph. AdminLTE3 now relies on its Font Awesome icon stack and does not provide implicit Ionicons loading.
- Aligned Composer license metadata with the repository `LICENSE` file (MIT).

#### Asset graph and browser caching
- Removed the duplicate AdminLTE-packaged Bootstrap JavaScript bundle and rely on Yii Bootstrap 4 as the single Bootstrap JS source.
- Removed the redundant direct `JqueryAsset` dependency where Yii already provides the dependency chain.
- Made `appendTimestamp` behaviour consistent between minified and non-minified AdminLTE asset bundles.
- Moved stable GridView, DetailView, Invoice, NavbarLogo, NavbarUser, and bounded InfoBox progress presentation into cacheable package CSS where applicable.
- `AdminLTEThemeAsset` publishes package-owned `js/*` and the CSP-safe progress width stylesheet in addition to the existing theme/widget CSS.

#### Widget architecture and behaviour
- Converted legacy `Box` into a backward-compatible facade/subclass over `Card`, so card class resolution, header rendering, body markup, tool buttons, and class sanitization have a single implementation path.
- Preserved `Box` defaults, GridView body support, footer action buttons, and historical property aliases while keeping the class deprecated for new code.
- Normalized genuinely shared option names: `Card::$icon` replaces `titleIcon`, `SmallBox::$url` replaces `link`, `NavbarButton::$options` replaces `option`, and sidebar icon options converge on `$icon`. Historical names remain deprecated aliases and canonical values win when both are configured.
- Kept role-specific URLs such as profile/logout/footer actions explicitly named rather than forcing unrelated targets into a generic alias.
- Removed redundant no-op `init()` implementations from simple widgets.
- Simplified `NavTabs` link generation by removing duplicate `href` handling and the ineffective `encode` HTML option.

#### Translation categories
- Card, Box, ContentHeader, SidebarSearch, SidebarToggle, SidebarUser, and Invoice now use the package-owned `adminlte3` category for static UI strings instead of relying on unrelated `app`, `traits`, or `crm` catalogs.
- `Timeline` retains its dynamic `traits` lookup only for externally supplied action keys; this is an explicit compatibility fallback rather than a package-owned UI dependency.

#### Documentation
- Updated README requirements, installation guidance, security defaults, asset behaviour, test instructions, and compatibility notes.
- Removed the recommendation to install the package with a broad `@dev` stability flag.
- Streamlined `UPDATE.md` so the dated processed section does not repeat “Processed” in every subsection/result.
- Closed the remaining package-owned InfoBox `style-src-attr 'none'` CSP roadmap item after replacing inline progress widths with bounded package CSS classes.
- Marked shared widget option semantics and package translation ownership as processed after adding aliases, `adminlte3`, and regression coverage.
- Added future widget candidates for Calendar, ChartJS, dedicated 404, and dedicated 500 rendering, with security/test expectations recorded before public implementation.

### Fixed

#### Mail rendering security
- `MailboxRead` HTML-encodes message bodies by default.
- HTML mail rendering is explicit (`encodeMailBody=false`) and remains purified by default through Yii HTML Purifier.
- Attachment icons are treated as validated CSS icon classes instead of arbitrary HTML fragments.
- Dangerous attachment and image URL schemes are rejected.
- Preserved the public `MailboxRead::demo()` helper for backward compatibility.

#### Sidebar navigation safety and route matching
- Sanitized SidebarMenu icon and badge classes and encode badge text by default.
- Reworked default-route/default-action matching to compare route path segments instead of substring positions.
- Reduced raw string-template assembly in favour of Yii HTML helpers for dynamic output.

#### GridView and DetailView formatter isolation
- Prevented `GridView` and `DetailView` from mutating the application-wide formatter when changing `nullDisplay`; a local formatter instance is used instead.
- Grid export now fails fast with `InvalidConfigException` when its required Bootstrap 4 dropdown dependency is unavailable instead of silently disabling export.

#### Invoice and navigation URL hardening
- Validated HTTP(S) website URLs and email/PEC addresses before generating links.
- Added `noopener noreferrer` to automatically generated external links opened in a new tab.
- Remote company logos are disabled by default and require explicit opt-in for trusted HTTP(S) sources.
- Removed user-controlled `javascript:` print URLs and inline print handlers; the default print action is handled by package JavaScript through a data attribute.
- `NavbarUser` right-footer action (normally logout) uses Yii POST semantics by default.
- Hardened URL/class handling in `Box`, `SmallBox`, `NavbarUser`, and related widgets.

#### InfoBox CSP compatibility
- Removed the remaining package-owned dynamic `style` attribute from `InfoBox` progress rendering.
- Preserved the existing integer clamp semantics for values below 0 and above 100, with regression coverage for 0, arbitrary in-range percentages, and 100.

### Performance

- Reduced duplicated front-end payload by removing the second Bootstrap JS copy.
- Reduced repeated inline CSS output by moving stable widget styles into cacheable package assets.
- Kept InfoBox progress handling cacheable and JavaScript-free by using one bounded static stylesheet instead of per-instance generated CSS or runtime DOM mutation.
- Reduced redundant asset dependencies in the main AdminLTE bundles.
- Reduced maintenance duplication by routing legacy `Box` card rendering through `Card`, shared safety normalization through one internal helper, and package UI translation through one internal category helper.

### Validation

- CI targets every PHP minor from 8.1 through 8.5.
- Every CI job performs strict Composer validation, clean dependency installation, syntax linting, and PHPUnit regression tests.

## 2026-07-30

### Fixed

#### Forms — input-group icon layout (Font Awesome)
- `adminlte-theme.css`: addon icon sizing covers Font Awesome (`.fa` / `.fas` / `.far`) and BS4 `.input-group-text`, not only `.glyphicon`.
- Removed redundant Select2 flex helpers (`s2-input-group` / `width: 1%`); with correct Kartik `bsVersion`, Krajee BS4 theme already handles Select2 input-groups.
- `AdminLTEThemeAsset`: `appendTimestamp` + debug `forceCopy` so theme CSS changes are not stuck behind Yii’s path-based publish hash / browser cache.

## 2026-07-25

### Added

- `assets/AdminLTEThemeAsset` + `assets/css/adminlte-theme.css` — shell theme overrides (typography, sidebar, content, cards, glyphicons→FA, BS3 float helpers). Registered automatically after `AdminLTEAsset` / `AdminLTEMinifyAsset` so the package is exportable without app-level AdminLTE CSS.
- Card header vertical alignment: `.card-header` flex + `.card-title { margin: 0 }` so titles are not pushed down by generic `h3` margins.

### Changed

- `AdminLTEAsset` / `AdminLTEMinifyAsset` stay at module root (`cinghie\adminlte3`); only theme CSS bundle lives in `assets/` (`cinghie\adminlte3\assets\AdminLTEThemeAsset`).
- `widgets/Card` — header gets `align-items-center` by default for title/tools alignment.

## 2026-07-23

### Added

- `widgets/DetailView` — extends Kartik DetailView with AdminLTE 3 / Bootstrap 4 card panel normalization (same pattern as GridView).
- `widgets/Card` — update card with `type`/`outline`, collapse/remove/maximize tools, `begin()`/`end()` content capture, and BC for legacy `cardClass` / `COLOR_*`. Docs: `docs/example_card.md`.

### Changed

- `widgets/Invoice` — real data-driven AdminLTE 3 invoice aligned with the official AdminLTE 3 invoice example; widget-scoped CSS (`.cinghie-invoice`, padding `1.75rem`); table columns Qty/Product/Serial/Description/Subtotal; safer logo handling; print behaviour aligned with the widget API. Docs updated.
- Invoice From/To blocks also render optional fiscal fields when present: VAT, tax code, SDI, PEC, website, fax/mobile; meta can show type, sent date, and payment method code.
