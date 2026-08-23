# Coding and documentation conventions

This public package follows Yii 2 conventions together with modern PHP / PSR-12 readability practices.

## Documentation

- Public classes must explain their purpose and any important compatibility or trust assumptions.
- Public configuration properties should have useful PHPDoc when their type, default, accepted values, trust level, or compatibility meaning is not obvious.
- Overridden framework methods should use `{@inheritdoc}` unless the override has behaviour that must be explained explicitly.
- Internal methods should document non-obvious behaviour, security boundaries, compatibility adapters, side effects, or return contracts. Avoid comments that merely restate the next line of code.
- Security-sensitive options must say whether content is encoded, purified, normalized, restricted to JSON-safe data, or expected to be trusted HTML.
- Deprecated API must identify the preferred replacement and remain covered by compatibility tests until a major release removes it.
- Do not hardcode package versions in PHP source headers. Package versioning belongs to Git tags / Composer metadata.
- Source comments and public documentation must not contain private hosts, credentials, customer data, internal project names, personal paths, exploit recipes, or other non-public operational information.

## Yii security conventions

Yii's core security guidance is applied consistently:

1. **Filter / normalize input** before using it in security-sensitive contexts.
2. **Escape output** for its output context.
3. Use `Html::encode()` for plain text and `HtmlPurifier` only when HTML is intentionally supported.
4. Treat raw HTML, CSS classes, URLs, image sources, external-link targets, JSON configuration, and dynamic presentation values as explicit trust boundaries.
5. State-changing actions should not use GET links; use Yii POST semantics and keep CSRF protection enabled in the host application.

The package-internal `widgets/support/SafeHtml` helper centralizes reusable URL, email, CSS-class, icon-class, and external-link normalization. It is intentionally marked internal so security policy can evolve without creating a public compatibility promise.

## Content Security Policy

Package-owned markup should avoid inline JavaScript and inline style attributes when package CSS or published JavaScript can express the same behaviour safely.

- Widget behaviour belongs in published asset JavaScript and is activated through `data-*` attributes.
- Static presentation belongs in package CSS assets when practical.
- Dynamic values that cannot be represented safely with fixed CSS should remain data and be consumed by package/application JavaScript without constructing executable source from user values.
- JSON-backed widget configuration must be encoded once as JSON and then escaped by Yii for the surrounding HTML attribute; executable callbacks belong in application-owned JavaScript rather than PHP option arrays.
- Reusable per-instance initializers should use one shared asset instead of registering duplicate script/style blocks for every rendered widget.
- Global browser listeners should be registered once per initializer when multiple widget instances can share them.
- AssetBundles owned by this package should use package-local paths such as `__DIR__` rather than assuming an application-level alias exists, and should publish only the files they own when a narrow list is practical.
- `Invoice` browser printing is delegated to `assets/js/widgets.js` via `data-cinghie-action="print"` rather than an `onclick` handler.
- `SidebarMenu` uses AdminLTE's `menu-open` class instead of an inline `display` style for active submenus.
- `InfoBox` progress values are normalized to integer percentages from 0 through 100 and rendered with package-owned `cinghie-progress-width-*` CSS classes. This preserves the existing progress API without requiring a `style` attribute, including for applications enforcing `style-src-attr 'none'` on package-owned markup.
- `Calendar`, `ChartJS`, `DateTimePicker`, and `ColorPicker` use published initializer assets with data-backed instance configuration rather than package-owned inline scripts. ColorPicker visual swatches/previews use canvas elements painted from validated HEX data so dynamic color presentation does not require inline `style` attributes.

Third-party AdminLTE/Kartik/Tempus Dominus/FullCalendar/Chart.js assets may have their own CSP requirements; applications should test their complete asset stack before enforcing a strict production policy.

## Formatting

- Use PSR-12-compatible formatting for new and modified PHP code.
- Use four spaces, not tabs, in new and modified code.
- Keep imports in the PSR-12 block order: class imports, then `use function`, then `use const`, with one blank line between different import groups where those groups are present.
- PSR-12 does **not** require alphabetical sorting within an import group. Alphabetical sorting may be used for readability, but it is a package convention rather than a Yii/PSR requirement and must not be described as normative.
- `use Yii;` is valid in namespaced code that calls `Yii::`; it should not appear in non-namespaced views/migrations and should be removed when unused.
- Prefer early returns and small normalization helpers over repeated validation branches.
- Prefer Yii HTML helpers over manual HTML concatenation when attributes contain dynamic values.
- Keep backward-compatibility adapters small and explicit; do not duplicate primary implementations.
