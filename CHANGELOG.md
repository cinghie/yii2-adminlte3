# Changelog — cinghie/yii2-adminlte3

## 2026-07-30

### Fixed

#### Forms — input-group icon layout (Font Awesome)
- `adminlte-theme.css`: addon icon sizing covers Font Awesome (`.fa` / `.fas` / `.far`) and BS4 `.input-group-text`, not only `.glyphicon`.
- Removed redundant Select2 flex helpers (`s2-input-group` / `width: 1%`); with correct Kartik `bsVersion` (CRM module `$bootstrap`), Krajee BS4 theme already handles Select2 input-groups.
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

- `widgets/Invoice` — real data-driven AdminLTE 3 invoice aligned with [official invoice example](https://adminlte.io/themes/v3/pages/examples/invoice.html); widget-scoped CSS (`.cinghie-invoice`, padding `1.75rem`); table columns Qty/Product/Serial/Description/Subtotal; safer logo handling; print without `_blank` for `javascript:window.print()`. Docs updated.
- Invoice From/To blocks also render optional fiscal fields when present: VAT, tax code, SDI, PEC, website, fax/mobile; meta can show type, sent date, and payment method code.
