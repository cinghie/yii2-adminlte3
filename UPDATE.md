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

No open priority item is currently promoted above the detailed roadmap notes below. The 2026-08-23 reusable input-widget refactor and Tempus Dominus hardening still require the normal CI matrix and a real Bootstrap 4 host interaction smoke before the next release.

---

## Open — 1. Security & safe rendering

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open medium-or-higher security issue from the 2026-08-19 hardening pass | Mail body rendering, dynamic icon classes, SidebarMenu output, external Invoice links, logout semantics, dangerous URL schemes, package-owned inline-script patterns, InfoBox progress rendering, JSON-backed Calendar/ChartJS configuration, dedicated error pages, and the reusable input widgets are covered by bounded rendering policies/regression tests. | Keep security regression tests mandatory in CI and review every future raw-HTML, URL, CSS-class, image-source, external-link, JSON-configuration, or dynamic-presentation API as an explicit trust boundary. |

---

## Open — 2. Correctness & architecture

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open correctness item from the widget-option/translation/additional-widget normalization pass | Shared `icon`, `url`, and `options` semantics now have canonical names with backward-compatible aliases; package-owned UI strings use the package translation category; Calendar, ChartJS and reusable input widgets keep browser-plugin responsibilities out of unrelated host views. | Keep aliases covered until a major release, make canonical properties win when both names are configured, keep browser-library callbacks/application logic outside bounded server-side widget serializers, preserve explicit dependency ordering for optional plugins, and keep feature modules delegating Bootstrap-specific input rendering to this package. |

---

## Open — 3. Optimizations & performance

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open medium-or-higher performance issue from the 2026-08-19 pass | Duplicate Bootstrap JS and redundant asset dependencies were removed, stable widget CSS moved to cacheable assets, Box reuses Card rendering, and optional AdminLTE plugin families including FullCalendar and Chart.js load only when requested. DateTimePicker reuses an already registered source/minified Tempus Dominus bundle when possible. | Keep bundle-size, dependency-order, source/minified parity, optional-plugin isolation, and declared-file checks mandatory when asset definitions change. |

---

## Open — 4. Tests, QA & compatibility

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| **Low** | Real browser smoke for Tempus Dominus | PHPUnit now renders the DateTimePicker, asserts its asset registration and generated initializer, but a headless PHP test cannot prove third-party popup interaction in a real browser. | Keep the rendered-widget test blocking and include one Bootstrap 4/AdminLTE3 DateTimePicker open/select/clear smoke in release validation or the representative host application. |
| — | No other known QA item from the current package-level hardening pass | Runtime coverage spans PHP 8.1–8.5, complex markup has DOM/XPath structural assertions, additional widgets have smoke/security/asset-isolation tests, reusable input widgets have rendered model-bound/standalone coverage, static quality gates are blocking, and the PHP 8.1 prefer-lowest job installs and executes the minimum declared dependency set successfully. | Keep the normal runtime, quality, asset, widget-rendering, and lowest-dependency gates mandatory; preserve cross-version AdminLTE asset checks when dependencies change. |

---

## Open — 5. Documentation, packaging & release hygiene

| Urgency | Item | Why | Recommended action |
|---------|------|-----|--------------------|
| — | No known open release-hygiene documentation item | Stable installation guidance, public SemVer/deprecation policy, reusable input-widget guide, and the release checklist are documented for the 1.0.0 line. | Keep `README.md`, `CHANGELOG.md`, `UPDATE.md`, `docs/VERSIONING.md`, `docs/example_inputwidgets.md`, and `RELEASE_CHECKLIST.md` synchronized when public compatibility or release procedures change. |

---

## Processed — 2026-08-23 Bootstrap 4 reusable input widgets and DateTimePicker hardening

### Correctness & assets

| Item | Result |
|------|--------|
| Tempus Dominus date-time bundle depended only on Bootstrap CSS | Both source and minified date-time bundles depend on `yii\bootstrap4\BootstrapPluginAsset`, guaranteeing the Yii-managed Bootstrap JavaScript layer before Tempus Dominus. |
| Host pages could mix source/minified DateTimePicker assets | `DateTimePicker` first reuses an already registered source/minified date-time bundle; otherwise it selects the variant matching `YII_DEBUG`, avoiding duplicate plugin registration. |
| Feature modules needed Bootstrap 4 date/time inputs without a Kartik/AdminLTE plugin-name collision | The package owns `DateTimePicker` and `DatePicker`, with the calendar trigger prepended on the left, configurable format/icon/plugin options, and an explicit accessibility label. |
| Feature modules carried their own color-picker rendering | Added package-owned `ColorPicker` with HEX text submission, deferred suggested palette, unrestricted native color input, responsive popup behavior, model-bound/standalone support, and no dependency on feature-module translation categories. |
| Date-only format was overwritten in `init()` | `DatePicker::$format` now provides a configurable `YYYY-MM-DD` default that Yii configuration can override normally. |

### Tests and documentation

| Item | Result |
|------|--------|
| DateTimePicker coverage was source-string-only | `DateTimePickerTest` instantiates a real Yii `DynamicModel`, renders the widget, verifies the prepended markup/accessibility label, checks the selected date-time AssetBundle, and asserts the `.datetimepicker(...)` initializer and supplied plugin options. |
| ColorPicker/DatePicker public contracts were source-only | `ColorPickerTest` renders model-bound and standalone ColorPicker instances and validates a custom DatePicker format through the generated Tempus Dominus initializer. |
| Reusable widget usage was undocumented | README and `docs/example_inputwidgets.md` document `ColorPicker`, `DatePicker`, and `DateTimePicker`; `DocumentationTest` guards those links and names. |
| Source/minified dependency parity | Tests instantiate both date-time bundles and assert `BootstrapPluginAsset` plus Moment → Tempus Dominus ordering. |

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
| Calendar/ChartJS executable configuration boundary | Event, dataset, and option values are JSON-serialized into HTML data attributes and consumed by package-owned external initializer scripts; server-side widget configuration does not accept executable JavaScript callbacks. |
| Dedicated error-page disclosure | `Error404` and `Error500` encode user-facing text through a shared view. `Error500` defaults to a generic message and has no exception/stack/path/debug input surface. |
| Security policy regression coverage | Dedicated tests cover shared normalization, CSP-safe InfoBox progress, avoidable package-owned inline JS/style patterns, JSON-backed Calendar/ChartJS markup, and error-page encoding/disclosure defaults. |

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
| `Calendar` rendering responsibilities | Added a public widget that emits only a container plus JSON-safe event/options data and optional asset registration. FullCalendar owns browser rendering/interaction; legacy FullCalendar 4 defaults are applied in the external initializer only when that version is detected. |
| `ChartJS` rendering responsibilities | Added a public widget with deterministic/sanitized canvas identifiers, JSON-safe data/options, responsive defaults, supported chart-type normalization, and optional asset registration. |
| Dedicated HTTP error widgets | Added `Error404` and `Error500` over an internal shared renderer/view so status-specific defaults remain thin while encoded markup/accessibility behavior stays consistent. |

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
| FullCalendar and Chart.js optional assets | Added source/minified `AdminLTECalendar*` and `AdminLTEChartJS*` bundles plus small package-owned initializer bundles. Neither plugin family is added to the core or historical aggregate; widget rendering registers only its own optional dependency unless `registerAssets=false`. |
| FullCalendar AdminLTE 3 version drift | Calendar bundles detect whether the installed AdminLTE uses the 3.0.x modular FullCalendar layout or the 3.1+/3.2 bundled layout and register only files that exist for that vendor generation. |
| Minified/non-minified asset parity was implicit | Asset regression tests compare semantic file lists, dependency graphs, timestamp policy, and existence of every declared AdminLTE source/minified file. |
| Bundle size and plugin order were not guarded | Tests assert that the core graph contains only one AdminLTE CSS and one AdminLTE JS file, while the aggregate retains the historical CSS order `Tempus Dominus → iCheck → AdminLTE` and JS order `jQuery UI → Moment → Tempus Dominus → AdminLTE`. |
| Preload/defer strategy | No global `defer`, preload hint, or script-position change is introduced. Yii already exposes `jsOptions` and dependency ordering; AdminLTE assets are normally registered near the end of the document, so global `defer` has little default benefit. Applications may opt in when moving scripts earlier, but should apply the policy coherently to the full dependency chain and validate third-party plugins. Preload remains application-level and measurement-driven because published URLs and critical resources vary by deployment. |

### Packaging

| Item | Result |
|------|--------|
| Missing Kartik dependencies in Composer | Runtime packages used by public widgets are declared explicitly. |
| Broad `@dev` runtime dependencies | Runtime dependency declarations were stabilized/removed as appropriate. |
| Bootstrap version ambiguity | Yii Bootstrap 4 is explicit and the runtime baseline is PHP 8.1+ / Yii 2.0.54+. |
| License metadata mismatch | Composer metadata matches the repository MIT license; historical per-file GPL/package/version/company headers were removed so `LICENSE` and Composer are the authoritative license/package metadata sources. |
| Source-file metadata regression | `SourceMetadataTest` prevents obsolete `@package`, hardcoded version, historical company metadata, and GPL header text from being reintroduced. |
| Ionicons dependency / compatibility | Ionicons has been removed from the package dependency and asset graph. AdminLTE3 uses its Font Awesome icon stack; no Ionicons compatibility layer is planned for this package. |
| Additional widgets without new Composer packages | Calendar and ChartJS reuse the FullCalendar/Chart.js copies already shipped by the supported AdminLTE dependency; no extra runtime package is required. |
| Public deprecation policy | `docs/VERSIONING.md` guarantees deprecated public APIs remain available throughout the current major release; `Box` and historical widget aliases stay supported for the complete 1.x line and may only be removed in 2.0.0 or later. |
| Semantic-versioning policy | The public compatibility contract now defines major/minor/patch expectations for API removals, runtime baselines, implicit assets, security/encoding defaults, and backward-compatible additions/fixes. |
| Release checklist | `RELEASE_CHECKLIST.md` documents scope review, Composer/CI/quality gates, asset/browser checks, clean smoke installation, tagging, Packagist verification, and post-release checks. |
| Stable installation guidance | README documents `composer require cinghie/yii2-adminlte3:^1.0` and discourages development branches for production deployments. |

### Tests, code comments & CI

| Item | Result |
|------|--------|
| No automated test suite | PHPUnit configuration and regression tests were added. |
| No GitHub Actions validation | CI runs Composer validation, clean dependency resolution, PHP lint, and PHPUnit on every PHP minor from 8.1 through 8.5. |
| Headless Kartik/Yii test environment incomplete | Test bootstrap configures Asset Packagist aliases, assets/runtime directories, request context, Bootstrap 4, GridView module, and local translation sources. |
| Public widgets lacked broad smoke coverage | Public widget classes have package-level smoke/load coverage, with dedicated behavioural/security tests retained for complex widgets. |
| SidebarMenu route matrix was narrow | Regression coverage includes trailing default actions, module default routes, query parameters, active parents, invisible items, headers, and similarly named non-matching routes. |
| Comment/PHPDoc conventions were implicit | `docs/CODING_STYLE.md` records Yii-oriented output encoding/trust-boundary rules, useful PHPDoc expectations, PSR-12 formatting, CSP conventions, and public-package documentation restrictions. A regression test requires every public widget class to retain class-level PHPDoc. |
| Source-level Yii/PSR hygiene was implicit | `Yii2BestPracticesTest` guards valid/used `Yii` imports and the normative PSR-12 ordering of class/function/constant import groups without inventing an alphabetical requirement that PSR-12 does not define. |
| Static analysis and coding-standard automation | Added PHPStan 2.x at level 5 and PHP_CodeSniffer 4 with PSR-12 in a dedicated blocking PHP 8.3 `quality` workflow. `Timeline` remains an explicit legacy exclusion because it imports optional external model packages; two narrow pre-existing control-structure formatting exceptions are documented for Invoice/SidebarMenu while the rest of those files remains scanned. |
| DOM/XPath structural widget assertions | Added reusable `HtmlDomTestCase` support around `DOMDocument`/`DOMXPath`; complex rendering tests now assert classes, URLs, `rel`, `target`, `data-method`, ARIA attributes, absence of injected nodes/attributes, and encoded text structurally while retaining targeted raw-string security assertions where useful. |
| Lowest-dependency validation | Added a separate PHP 8.1 `prefer-lowest` CI job. It resolves the minimum dependency graph independently, installs the locked set with `--prefer-source`, verifies critical autoload classes, runs syntax checks, and passes the full PHPUnit suite. The minimum declared dependency set is therefore runtime-certified by CI. |
| Additional-widget regression coverage | `AdditionalWidgetsTest` smoke-renders Calendar, ChartJS, Error404, and Error500 and validates JSON round trips, absence of injected DOM nodes, deterministic canvas ids, responsive chart defaults, encoded error text, generic 500 disclosure, and accessible navigation. |
| Additional-asset regression coverage | Asset tests verify Calendar/ChartJS source/minified parity, physical vendor-file existence for the resolved AdminLTE generation, initializer dependencies, and continued exclusion from core/historical aggregate bundles. |
| Option alias and translation ownership regressions | Dedicated tests cover canonical-vs-legacy option precedence, legacy-only rendering, lazy package translation registration, application overrides, and accidental reintroduction of legacy UI translation categories. |
| Asset graph regressions | Dedicated tests cover core-vs-aggregate payload, plugin ordering, source/minified parity, dependency equivalence, timestamp parity, declared vendor-file existence, and optional-plugin isolation. |

### Public widget documentation

| Item | Result |
|------|--------|
| Calendar example | Added `docs/example_calendar.md` covering event arrays, JSON-safe options, bounded callback policy, and explicit asset ownership. |
| ChartJS example | Added `docs/example_chartjs.md` covering dataset/configuration arrays, deterministic canvas ids, responsive defaults, and explicit asset ownership. |
| Error404 example | Added `docs/example_error404.md` covering encoded user-facing text and accessible dashboard navigation. |
| Error500 example | Added `docs/example_error500.md` covering generic safe defaults and the rule that detailed diagnostics belong in server-side logs. |
| Reusable input widgets | Added `docs/example_inputwidgets.md` and README usage for ColorPicker, DatePicker and DateTimePicker, including model-bound/standalone color inputs and configurable date/time formats. |

---

## Possible future expansions

These are **not current defects**. They are public-package evolution ideas that should be evaluated against demand, maintenance cost, and backward compatibility.

### 1. Bootstrap 5 / AdminLTE 4 migration path

- Evaluate a next-generation package layer for Bootstrap 5 and the AdminLTE version that officially targets it.
- Avoid mixing Bootstrap generations in the same package runtime.
- Prefer a major-version migration with clear compatibility boundaries rather than feature-detecting both Bootstrap 4 and 5 throughout every widget.
- Provide migration documentation for renamed utility classes, JS APIs, data attributes, and Kartik dependencies.

### 2. Additional modular plugin AssetBundles

- The package now has isolated source/minified families for jQuery UI, date/time, iCheck, FullCalendar, and Chart.js while preserving the historical aggregate bundle.
- Add further optional families only when a public widget or documented integration needs them.
- Keep every new source/minified pair under the same dependency/parity/file-existence/isolation tests so optional bundles never reintroduce duplicate jQuery or Bootstrap copies.

### 3. Shared widget rendering utilities beyond safety policy

- `SafeHtml` centralizes security-sensitive URL/class/icon normalization.
- Future internal utilities could consolidate repeated AdminLTE tool-button markup, contextual types, and common container structures without broadening the public API prematurely.
- Preserve deprecated adapters as thin layers over the primary implementation.

### 4. Accessibility improvements

- Review interactive tools (collapse, remove, maximize, dropdowns, tabs, sidebar) for ARIA labels/state and keyboard behaviour.
- Add semantic labels to icon-only buttons.
- Add automated structural accessibility assertions where feasible; consider browser-level testing only if it provides stable value.

### 5. Stronger end-to-end CSP verification

- Package-owned Invoice, SidebarMenu, Navbar, Mailbox, InfoBox, Calendar, and ChartJS markup avoid the identified inline-handler/static-style/initializer patterns.
- Add a small browser fixture with a restrictive CSP to validate the complete AdminLTE/Kartik/third-party asset stack, because upstream plugins can have requirements outside this package's generated markup.

### 6. Visual regression / browser smoke testing

- Add a small fixture application rendering representative widgets and layouts, including Calendar, ChartJS, ColorPicker, DatePicker, DateTimePicker and error pages.
- Run browser smoke tests for collapse/dropdown/sidebar/calendar/chart/color/date-time-picker behaviours and major responsive breakpoints.
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

### 2026-08-23

- Added reusable package-owned `ColorPicker`, `DatePicker`, and `DateTimePicker` widgets for Bootstrap 4/AdminLTE3 host modules.
- `ColorPicker` keeps HEX text as the submitted value, defers suggested colors until requested, supports model-bound and standalone use, and remains independent from feature-module translation categories.
- `DatePicker` now exposes a configurable date-only format default; `DateTimePicker` exposes a configurable accessible trigger label and reuses an already registered source/minified Tempus Dominus asset where possible.
- Corrected both date-time asset bundles to require `BootstrapPluginAsset`, ensuring Bootstrap JavaScript is loaded before Tempus Dominus without reintroducing AdminLTE's duplicate Bootstrap bundle.
- Expanded input-widget tests from source contracts to rendered model-bound/standalone smoke coverage and added README/guide documentation.
- A real browser/host smoke remains recommended before release because PHPUnit cannot exercise third-party popup interaction.

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
- Added optional FullCalendar and Chart.js source/minified bundles plus package-owned external initializers; both plugin families remain excluded from the core and historical aggregate and are registered only when their widgets need them.
- Added `Calendar` with JSON event/options serialization and cross-version AdminLTE 3 FullCalendar asset selection, and added `ChartJS` with deterministic canvas ids, responsive defaults, and JSON-safe configuration.
- Added dedicated `Error404` and `Error500` widgets over a shared encoded AdminLTE error view; the 500 default exposes no exception details, paths, stack traces, or debug context.
- Added smoke/security/DOM tests and asset isolation/parity/existence checks for all four additional widgets, plus public documentation examples for each.
- Evaluated script preloading/defer and kept it opt-in/application-owned rather than changing package defaults without measured benefit.
- Normalized source-file metadata so the repository MIT `LICENSE` and Composer metadata are authoritative, and added a regression guard against stale per-file package/version/license headers.
- Added PHPStan level 5 and PHP_CodeSniffer/PSR-12 as a dedicated blocking quality workflow, alongside the existing PHP 8.1–8.5 runtime matrix.
- Added DOM/XPath structural assertions for complex widget markup, including link/security attributes and Card tool accessibility semantics.
- Added a separate PHP 8.1 prefer-lowest workflow path; the minimum declared dependency set now resolves, installs from source, passes autoload verification and syntax checks, and completes the full PHPUnit suite successfully.
- Added public coding/PHPDoc conventions, class-level documentation guards, source-level Yii/PSR best-practice tests, option-alias regression tests, translation-ownership guards, asset-graph regression tests, source-metadata guards, and structural DOM assertions.
- Added stable 1.x installation guidance, a public Semantic Versioning/deprecation policy, and a complete release checklist in preparation for the first 1.0.0 tag.
- Remaining optional roadmap work is intentionally non-blocking for 1.0.0: browser-level verification of the complete third-party CSP/interaction stack and future release automation.
