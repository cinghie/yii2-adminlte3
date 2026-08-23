# Yii2 AdminLTE 3

![License](https://img.shields.io/packagist/l/cinghie/yii2-adminlte3.svg)
![Latest Stable Version](https://img.shields.io/github/release/cinghie/yii2-adminlte3.svg)
![Latest Release Date](https://img.shields.io/github/release-date/cinghie/yii2-adminlte3.svg)
![Latest Commit](https://img.shields.io/github/last-commit/cinghie/yii2-adminlte3.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/cinghie/yii2-adminlte3.svg)](https://packagist.org/packages/cinghie/yii2-adminlte3)

Asset bundle and widgets for AdminLTE 3 on Yii 2.

## Requirements

- PHP 8.1 or later
- Yii 2.0.54 or later
- Yii Bootstrap 4
- Composer configured to resolve `bower-asset/*` packages, for example through Asset Packagist

## Installation

Install the stable 1.x release with Composer:

```bash
composer require cinghie/yii2-adminlte3:^1.0
```

For reproducible production deployments, commit the generated `composer.lock` in applications and deploy from the validated lock file. Development branches such as `dev-main` are not recommended for production use.

## Versioning and releases

The public API follows Semantic Versioning starting with 1.0.0. Deprecated public classes and properties remain supported for the complete current major series and are removed only in a later major release. See [`docs/VERSIONING.md`](docs/VERSIONING.md) for the compatibility/deprecation contract and [`RELEASE_CHECKLIST.md`](RELEASE_CHECKLIST.md) for the release procedure.

## Configuration

### Compatibility aggregate

The historical bundles remain available and keep the previous complete plugin set and ordering:

```php
use cinghie\adminlte3\AdminLTEAsset;

AdminLTEAsset::register($this);
```

or, for minified vendor files:

```php
use cinghie\adminlte3\AdminLTEMinifyAsset;

AdminLTEMinifyAsset::register($this);
```

The aggregate bundles include jQuery UI, Moment + Tempus Dominus, iCheck Bootstrap, AdminLTE core, Yii Bootstrap 4, Font Awesome, and the package theme assets. Existing applications can therefore keep their current registration unchanged.

### Core-only bundle

For pages that do not need the historical plugin set, register the smaller core bundle instead:

```php
use cinghie\adminlte3\AdminLTECoreAsset;

AdminLTECoreAsset::register($this);
```

The core bundle keeps AdminLTE, Yii Bootstrap 4, Font Awesome, and the package theme assets, while leaving jQuery UI, date/time plugins, and iCheck out of the page.

### Optional plugin bundles

Optional plugin families can be registered independently when a page needs them:

```php
use cinghie\adminlte3\AdminLTEDateTimeAsset;
use cinghie\adminlte3\AdminLTEIcheckAsset;
use cinghie\adminlte3\AdminLTEJqueryUiAsset;

AdminLTEJqueryUiAsset::register($this);
AdminLTEDateTimeAsset::register($this);
AdminLTEIcheckAsset::register($this);
```

Minified equivalents are available as `AdminLTEJqueryUiMinifyAsset`, `AdminLTEDateTimeMinifyAsset`, and `AdminLTEIcheckMinifyAsset`.

This lets applications reduce plugin payload on pages that do not use those features without changing the historical aggregate bundles for existing installations.

FullCalendar and Chart.js follow the same page-scoped approach through `AdminLTECalendarAsset` / `AdminLTECalendarMinifyAsset` and `AdminLTEChartJSAsset` / `AdminLTEChartJSMinifyAsset`. The public `Calendar` and `ChartJS` widgets register their corresponding optional initializer/plugin assets only when they are rendered and `registerAssets` is enabled, so these plugin families are never added to the core or compatibility aggregate implicitly.

### Script loading strategy

The package intentionally does not set global `defer`, preload hints, or a different script position. Yii preserves dependency order through `AssetBundle::$depends`, and AdminLTE/plugin scripts are normally registered near the end of the document, where adding `defer` has limited default value.

Applications that deliberately move a complete dependency chain earlier in the document may opt in to script attributes through Yii asset configuration, for example by extending or configuring the relevant bundles and setting `jsOptions`. Apply the policy consistently to jQuery, Bootstrap, AdminLTE, and any dependent plugins, then verify those plugins in the target browsers. Preload decisions should remain application-level and measurement-driven because published asset URLs and critical resources vary by deployment.

## Reusable input widgets

Bootstrap 4/AdminLTE 3 applications can reuse the package-owned input widgets directly:

```php
use cinghie\adminlte3\widgets\ColorPicker;
use cinghie\adminlte3\widgets\DatePicker;
use cinghie\adminlte3\widgets\DateTimePicker;

$form->field($model, 'color')->widget(ColorPicker::class);
$form->field($model, 'date')->widget(DatePicker::class);
$form->field($model, 'starts_at')->widget(DateTimePicker::class);
```

`ColorPicker` keeps the submitted value as HEX text and opens its suggested palette only on demand. Palette entries are limited to six-digit HEX colors and its icon classes are normalized through the shared safety policy. Visual chips and the compact preview are non-interactive canvas elements painted by the shared external initializer from validated HEX data; the only native color input is the unrestricted custom picker.

`DatePicker` and `DateTimePicker` use the package Tempus Dominus stack, keep the calendar trigger on the left, reuse an already registered source/minified date-time asset when available, and expose `format`, icon and JSON-safe plugin options for host applications. Both model-bound and standalone `name`/`value` usage are supported. Per-instance configuration is stored in data attributes and consumed by a shared external initializer rather than generating inline JavaScript for every field.

Both input families keep stable presentation/behavior in package-owned cacheable AssetBundles (`ColorPickerWidgetAsset` and `DateTimePickerWidgetAsset`). Global browser listeners used by ColorPicker are shared across instances rather than registered once per widget. See [`docs/example_inputwidgets.md`](docs/example_inputwidgets.md).

## Security and CSP

Widget values that become CSS classes, icons, links, external URLs, or email links are treated as trust boundaries. Package widgets normalize or validate these values before rendering them. User-controlled text should remain encoded unless a widget explicitly documents a trusted/purified HTML mode.

Package-owned behavior avoids inline JavaScript where practical. For example, Invoice printing is bound by the package asset through a data attribute instead of an inline `onclick`, static widget presentation is kept in cacheable CSS, and the reusable ColorPicker/DateTimePicker widgets initialize from external package assets instead of per-instance inline code. ColorPicker dynamic swatch/preview colors are painted to canvas from validated data rather than written as inline style attributes. `InfoBox` progress widths are represented by package-owned 0–100 CSS classes instead of inline `style` attributes, so package-owned InfoBox markup does not require `style-src-attr 'unsafe-inline'` for dynamic progress values.

Calendar and ChartJS keep event, dataset, and configuration values as JSON data and initialize their browser plugins from package-owned external scripts. Their server-side APIs intentionally do not accept executable JavaScript callbacks; application-specific callback logic should live in application-owned JavaScript. The reusable DateTimePicker follows the same pattern for Tempus Dominus configuration. Error404/Error500 encode public text and Error500 deliberately exposes no exception, stack trace, path, or debug context by default.

A full strict-CSP deployment still needs to account for AdminLTE, Kartik, Bootstrap plugins, Tempus Dominus, FullCalendar, Chart.js, and application code outside this package. Applications targeting strict CSP should validate the complete page under their actual policy.

See [`docs/CODING_STYLE.md`](docs/CODING_STYLE.md) for the package coding, PHPDoc, security-boundary, and CSP conventions.

## Additional widgets

The package includes optional public widgets for common AdminLTE pages without forcing their plugin assets onto unrelated pages:

- `Calendar` — FullCalendar-backed rendering with JSON-safe event/options data and optional asset registration. See [`docs/example_calendar.md`](docs/example_calendar.md).
- `ChartJS` — Chart.js-backed canvas rendering with deterministic ids, JSON-safe datasets/options, responsive defaults, and optional asset registration. See [`docs/example_chartjs.md`](docs/example_chartjs.md).
- `Error404` — encoded AdminLTE-style not-found page with an accessible home action. See [`docs/example_error404.md`](docs/example_error404.md).
- `Error500` — encoded AdminLTE-style server-error page with generic non-diagnostic defaults. See [`docs/example_error500.md`](docs/example_error500.md).

## Icon compatibility

The core AdminLTE 3 stack uses Font Awesome. Ionicons is not loaded or required by this package. Applications that need Ionicons should register a compatible icon asset explicitly at application level rather than relying on an implicit package dependency.

## Testing

Install development dependencies and run:

```bash
composer validate --strict
vendor/bin/phpunit
```

CI runs Composer validation, clean dependency installation, PHP lint, and PHPUnit on PHP 8.1, 8.2, 8.3, 8.4, and 8.5. A separate PHP 8.1 `prefer-lowest` job resolves the minimum declared dependency graph, installs the locked set from source to avoid archive-extraction instability in legacy packages, verifies the critical autoload surface, runs PHP lint, and executes the full PHPUnit suite. The declared dependency floor is therefore exercised as a runtime compatibility gate rather than resolution-only telemetry.

Static quality checks are available through:

```bash
composer analyse
composer cs
composer quality
```

`composer analyse` runs PHPStan at level 5 over the supported package surface. `composer cs` runs PHP_CodeSniffer with the project PSR-12 ruleset. GitHub Actions runs both checks in a dedicated blocking quality job on PHP 8.3.

The package also carries regression tests for rendering security, shared URL/class normalization, strict-CSP-compatible package markup, formatter isolation, asset dependency/order/source-minified parity, package translation ownership, canonical-vs-legacy widget option semantics, and public widget smoke coverage. Complex widget markup is additionally checked through DOM/XPath structural assertions for classes, links, `rel`, `target`, `data-method`, ARIA attributes, and encoded text. Calendar/ChartJS/Error404/Error500 add dedicated smoke, JSON round-trip, security/disclosure, accessibility, and optional-asset isolation checks. Reusable input widgets have model-bound/standalone rendering, palette/format validation, icon-hardening, external-asset, no-inline-code, canvas-rendering, and shared-listener regression coverage. Calendar compatibility tests also guard FullCalendar option translation and ensure global resize/sidebar listeners are registered only once. Source-level guards cover public class PHPDoc, Yii/PSR import hygiene, and obsolete package/license/version metadata.

## Contributing

Contributor-facing PHPDoc, Yii security, CSP, and PSR-12 conventions are documented in [`docs/CODING_STYLE.md`](docs/CODING_STYLE.md). Comments should explain public contracts, compatibility constraints, trust boundaries, and non-obvious behavior rather than restating straightforward code.

## Widget examples

| Widget                                          | Guide                                                |
| ----------------------------------------------- | ---------------------------------------------------- |
| [Alert](docs/example_alert.md)                  | Alert messages                                       |
| [Box](docs/example_box.md)                      | Legacy card with GridView/footer helpers             |
| [Breadcrumbs](docs/example_breadcrumbs.md)      | Navigation breadcrumbs                               |
| [Calendar](docs/example_calendar.md)            | Optional FullCalendar integration                    |
| [Card](docs/example_card.md)                    | Card (header, tools, body, footer; begin/end)        |
| [ChartJS](docs/example_chartjs.md)              | Optional Chart.js canvas integration                 |
| [ColorPicker](docs/example_inputwidgets.md)     | Deferred HEX color picker                            |
| [Content Header](docs/example_contentheader.md) | Page title and breadcrumbs                           |
| [DataColumn](docs/example_datacolumn.md)        | GridView column class (sorting header)               |
| [DatePicker](docs/example_inputwidgets.md)      | Tempus Dominus date-only input                       |
| [DateTimePicker](docs/example_inputwidgets.md)  | Tempus Dominus date/time input                       |
| [DetailView](docs/example_detailview.md)        | Kartik DetailView styled as AdminLTE 3 card          |
| [Error404](docs/example_error404.md)            | Safe AdminLTE 404 page                               |
| [Error500](docs/example_error500.md)            | Safe AdminLTE 500 page                               |
| [Footer](docs/example_footer.md)                | Layout footer                                        |
| [GridView](docs/example_gridview.md)            | Data grid in AdminLTE 3 card                         |
| [InfoBox](docs/example_infobox.md)              | Info box (icon, text, number, optional progress)     |
| [Invoice](docs/example_invoice.md)              | Invoice layout (Bootstrap 4)                         |
| [MailboxRead](docs/example_mailboxread.md)      | Read-mail card (subject, body, attachments)          |
| [NavTabs](docs/example_navtabs.md)              | Nav tabs with tab panes (Bootstrap 4)                |
| [Navbar Button](docs/example_navbarbutton.md)   | Navbar link button                                   |
| [Navbar Logo](docs/example_navbarlogo.md)       | Navbar brand/logo                                    |
| [Navbar User](docs/example_navbaruser.md)       | Navbar user dropdown                                 |
| [Sidebar Menu](docs/example_sidebarmenu.md)     | Sidebar navigation menu                              |
| [Sidebar Search](docs/example_sidebarsearch.md) | Sidebar search form                                  |
| [Sidebar Toggle](docs/example_sidebartoggle.md) | Sidebar toggle button                                |
| [Sidebar User](docs/example_sidebaruser.md)     | Sidebar user panel                                   |
| [SmallBox](docs/example_smallbox.md)            | Small stat box with optional footer link             |
| [Timeline](docs/example_timeline.md)            | Timeline (days and items)                            |
