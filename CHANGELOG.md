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

#### Tests and continuous integration
- Added PHPUnit configuration and a headless Yii web-application bootstrap for package tests.
- Added regression coverage for `MailboxRead` HTML/XSS handling, attachment icon normalization, widget rendering hardening, `Invoice` URL policy, formatter isolation, and AdminLTE asset dependencies.
- Added GitHub Actions validation across PHP 8.1, 8.3, and 8.5 with strict Composer validation, clean dependency resolution, PHP syntax linting, and PHPUnit execution.
- Added test-only Asset Packagist aliases, runtime asset directories, request context, GridView module configuration, Bootstrap 4 configuration, and local translation sources required by Kartik widgets under CLI.

### Changed

#### Runtime requirements and package dependencies
- Raised the supported runtime baseline to PHP 8.1+ and Yii 2.0.54+.
- Declared `yiisoft/yii2-bootstrap4` explicitly.
- Declared the Kartik runtime packages used by public widgets (`yii2-grid`, `yii2-detail-view`, and Bootstrap 4 dropdown support).
- Removed broad development-only runtime constraints and unused runtime dependencies.
- Removed the implicit `cinghie/yii2-ionicons` dependency from the core AdminLTE asset graph because the latest published package release pulls Yii Bootstrap 3, which conflicts with this Bootstrap 4 package. Applications requiring Ionicons can register a compatible asset explicitly.
- Aligned Composer license metadata with the repository `LICENSE` file (MIT).

#### Asset graph and browser caching
- Removed the duplicate AdminLTE-packaged Bootstrap JavaScript bundle and rely on Yii Bootstrap 4 as the single Bootstrap JS source.
- Removed the redundant direct `JqueryAsset` dependency where Yii already provides the dependency chain.
- Made `appendTimestamp` behaviour consistent between minified and non-minified AdminLTE asset bundles.
- Moved stable GridView, DetailView, and Invoice styles into `assets/css/widgets.css` and publish them through `AdminLTEThemeAsset` instead of emitting large inline CSS blocks from each widget.

#### Widget architecture and behaviour
- Marked legacy `Box` as deprecated for new code in favour of `Card`, while keeping it available for backward compatibility.
- Updated `Box` to use the package-local AdminLTE `GridView` implementation and aligned its class/type normalization with newer widget conventions.
- Removed redundant no-op `init()` implementations from simple widgets.
- Simplified `NavTabs` link generation by removing duplicate `href` handling and the ineffective `encode` HTML option.

#### Documentation
- Updated README requirements, installation guidance, security defaults, asset behaviour, test instructions, and compatibility notes.
- Removed the recommendation to install the package with a broad `@dev` stability flag.

### Fixed

#### Mail rendering security
- `MailboxRead` now HTML-encodes message bodies by default.
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
- Removed user-controlled `javascript:` print URLs; the default print action uses a fixed print handler.
- `NavbarUser` right-footer action (normally logout) uses Yii POST semantics by default.
- Hardened URL/class handling in `Box`, `SmallBox`, `NavbarUser`, and related widgets.

### Performance

- Reduced duplicated front-end payload by removing the second Bootstrap JS copy.
- Reduced repeated inline CSS output by moving stable widget styles into cacheable package assets.
- Reduced redundant asset dependencies in the main AdminLTE bundles.

### Validation

- Final CI matrix passes on PHP 8.1, 8.3, and 8.5.
- Every CI job performs strict Composer validation, clean dependency installation, syntax linting, and all 19 PHPUnit regression tests successfully.

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
