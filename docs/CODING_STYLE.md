# Coding and documentation conventions

This public package follows Yii 2 conventions together with modern PHP / PSR-12 readability practices.

## Documentation

- Public classes must explain their purpose and any important compatibility or trust assumptions.
- Public configuration properties should have useful PHPDoc when their type, default, accepted values, trust level, or compatibility meaning is not obvious.
- Overridden framework methods should use `{@inheritdoc}` unless the override has behaviour that must be explained explicitly.
- Internal methods should document non-obvious behaviour, security boundaries, compatibility adapters, side effects, or return contracts. Avoid comments that merely restate the next line of code.
- Security-sensitive options must say whether content is encoded, purified, or expected to be trusted HTML.
- Deprecated API must identify the preferred replacement and remain covered by compatibility tests until a major release removes it.
- Do not hardcode package versions in PHP source headers. Package versioning belongs to Git tags / Composer metadata.
- Source comments and public documentation must not contain private hosts, credentials, customer data, internal project names, personal paths, exploit recipes, or other non-public operational information.

## Yii security conventions

Yii's core security guidance is applied consistently:

1. **Filter / normalize input** before using it in security-sensitive contexts.
2. **Escape output** for its output context.
3. Use `Html::encode()` for plain text and `HtmlPurifier` only when HTML is intentionally supported.
4. Treat raw HTML, CSS classes, URLs, image sources, and external-link targets as explicit trust boundaries.
5. State-changing actions should not use GET links; use Yii POST semantics and keep CSRF protection enabled in the host application.

The package-internal `widgets/support/SafeHtml` helper centralizes reusable URL, email, CSS-class, icon-class, and external-link normalization. It is intentionally marked internal so security policy can evolve without creating a public compatibility promise.

## Content Security Policy

Package-owned markup should avoid inline JavaScript and avoid static inline styles when an ordinary CSS class can express the same presentation.

- Widget behaviour belongs in published asset JavaScript and is activated through `data-*` attributes.
- Static presentation belongs in package CSS assets.
- `Invoice` browser printing is delegated to `assets/js/widgets.js` via `data-cinghie-action="print"` rather than an `onclick` handler.
- `SidebarMenu` uses AdminLTE's `menu-open` class instead of an inline `display` style for active submenus.
- A dynamic `InfoBox` progress percentage currently uses a numeric inline width because arbitrary percentages cannot be represented by Bootstrap 4 width utilities without reducing API precision. Deployments enforcing `style-src-attr 'none'` should account for this isolated dynamic style or avoid the progress option. This is the remaining known CSP limitation in package-owned widget markup.

Third-party AdminLTE/Kartik assets may have their own CSP requirements; applications should test their complete asset stack before enforcing a strict production policy.

## Formatting

- Use PSR-12-compatible formatting for new and modified PHP code.
- Use four spaces, not tabs, in new and modified code.
- Prefer early returns and small normalization helpers over repeated validation branches.
- Prefer Yii HTML helpers over manual HTML concatenation when attributes contain dynamic values.
- Keep backward-compatibility adapters small and explicit; do not duplicate primary implementations.
