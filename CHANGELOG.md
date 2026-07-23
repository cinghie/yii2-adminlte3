# Changelog — cinghie/yii2-adminlte3

## 2026-07-23

### Added
- `widgets/DetailView` — extends Kartik DetailView with AdminLTE 3 / Bootstrap 4 card panel normalization (same pattern as GridView).

### Changed
- `widgets/Invoice` — real data-driven AdminLTE 3 invoice aligned with [official invoice example](https://adminlte.io/themes/v3/pages/examples/invoice.html); widget-scoped CSS (`.cinghie-invoice`, padding `1.75rem`); table columns Qty/Product/Serial/Description/Subtotal; safer logo handling; print without `_blank` for `javascript:window.print()`. Docs updated.
- Invoice From/To blocks also render optional fiscal fields when present: VAT, tax code, SDI, PEC, website, fax/mobile; meta can show type, sent date, and payment method code.
