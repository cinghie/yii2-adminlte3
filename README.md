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

## Security and CSP

Widget values that become CSS classes, icons, links, external URLs, or email links are treated as trust boundaries. Package widgets normalize or validate these values before rendering them. User-controlled text should remain encoded unless a widget explicitly documents a trusted/purified HTML mode.

Package-owned behavior avoids inline JavaScript where practical. For example, Invoice printing is bound by the package asset through a data attribute instead of an inline `onclick`, and static widget presentation is kept in cacheable CSS. `InfoBox` progress widths are represented by package-owned 0–100 CSS classes instead of inline `style` attributes, so package-owned InfoBox markup does not require `style-src-attr 'unsafe-inline'` for dynamic progress values.

Calendar and ChartJS keep event, dataset, and configuration values as JSON data and initialize their browser plugins from package-owned external scripts. Their server-side APIs intentionally do not accept executable JavaScript callbacks; application-specific callback logic should live in application-owned JavaScript. Error404/Error500 encode public text and Error500 deliberately exposes no exception, stack trace, path, or debug context by default.

A full strict-CSP deployment still needs to account for AdminLTE, Kartik, Bootstrap plugins, and application code outside this package. Applications targeting strict CSP should validate the complete page under their actual policy.

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

CI runs Composer validation, clean dependency installation, PHP lint, and PHPUnit on PHP 8.1, 8.2, 8.3, 8.4, and 8.5. It also runs a separate PHP 8.1 `prefer-lowest` job that resolves the minimum declared dependency graph before attempting lint/tests; this job is intentionally non-blocking while legacy minimum-version package combinations continue to expose upstream Composer extraction instability. A successful lowest-version resolution is therefore not advertised as full minimum-runtime certification.

Static quality checks are available through:

```bash
composer analyse
composer cs
composer quality
```

`composer analyse` runs PHPStan at level 5 over the supported package surface. `composer cs` runs PHP_CodeSniffer with the project PSR-12 ruleset. GitHub Actions runs both checks in a dedicated blocking quality job on PHP 8.3.

The package also carries regression tests for rendering security, shared URL/class normalization, strict-CSP-compatible package markup, formatter isolation, asset dependency/order/source-minified parity, package translation ownership, canonical-vs-legacy widget option semantics, and public widget smoke coverage. Complex widget markup is additionally checked through DOM/XPath structural assertions for classes, links, `rel`, `target`, `data-method`, ARIA attributes, and encoded text. Calendar/ChartJS/Error404/Error500 add dedicated smoke, JSON round-trip, security/disclosure, accessibility, and optional-asset isolation checks. Source-level guards cover public class PHPDoc, Yii/PSR import hygiene, and obsolete package/license/version metadata.