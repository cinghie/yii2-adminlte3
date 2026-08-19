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

Until the first stable release is tagged, install the development branch explicitly rather than using the broad `@dev` stability flag:

```bash
composer require cinghie/yii2-adminlte3:dev-main
```

For production deployments, prefer a tagged stable release when available, or pin the exact commit you have validated.

## Configuration

Register the normal CSS and JavaScript bundle in your view/layout:

```php
use cinghie\adminlte3\AdminLTEAsset;

AdminLTEAsset::register($this);
```

Or use the minified bundle:

```php
use cinghie\adminlte3\AdminLTEMinifyAsset;

AdminLTEMinifyAsset::register($this);
```

Both bundles register `cinghie\adminlte3\assets\AdminLTEThemeAsset`. Stable widget styles and package-owned behavior are shipped under `assets/css/` and `assets/js/`, allowing browsers to cache them rather than requiring repeated inline CSS/JavaScript from each widget.

## Security defaults

`MailboxRead` HTML-encodes message bodies by default. To render an HTML email explicitly, set `encodeMailBody` to `false`; `purifyMailBody` remains enabled by default and passes the content through Yii's HTML purifier.

Attachment icons are treated as CSS classes rather than arbitrary HTML, and dangerous or unsupported explicit attachment URL schemes are rejected.

`Invoice` validates website and email links. Remote company logos are disabled by default to avoid unintended third-party requests; set `allowRemoteCompanyLogo` to `true` only for trusted HTTP(S) logo URLs.

`NavbarUser` renders the right footer action (normally logout) with `data-method="post"` by default. This preserves Yii's POST/CSRF logout semantics when Yii JavaScript is active; override `footerRightOptions` if the action is not a logout.

Security-sensitive URL, HTTP(S), email, CSS-class, icon-class and external-link normalization is centralized in the package-internal `widgets/support/SafeHtml` helper. It is intentionally not a public extension API.

## Content Security Policy

Package-owned markup avoids inline JavaScript and inline style attributes where package assets can express the same behavior safely. The default Invoice print action uses `data-cinghie-action="print"` and is handled by the published `assets/js/widgets.js` file; active SidebarMenu state uses AdminLTE classes instead of an inline display style. Static Navbar and Mailbox presentation is kept in package CSS/Bootstrap utility classes.

`InfoBox::$progress` keeps the existing integer 0–100 behavior without inline styles: normalized values map to package-owned `cinghie-progress-width-*` classes published through `AdminLTEThemeAsset`. This allows package-owned InfoBox markup to work with strict `style-src-attr 'none'` policies while preserving backward-compatible percentage semantics.

Third-party AdminLTE/Kartik plugins may have additional CSP requirements and should be tested as part of the complete application asset stack.

`Box` is retained for backward compatibility but is deprecated. Prefer `Card` for new code; `Box` remains useful where its legacy GridView/footer-button API is required.

## Tests

The repository includes PHPUnit tests and a GitHub Actions matrix for every supported PHP minor from 8.1 through 8.5. Locally:

```bash
composer install
composer test
```

CI also runs strict Composer validation and PHP syntax checks. Security/CSP, Yii/PSR source-hygiene, and public-widget documentation guards are part of the normal regression suite.

## Contributing

Contributor-facing PHPDoc, Yii security, CSP, and PSR-12 conventions are documented in [docs/CODING_STYLE.md](docs/CODING_STYLE.md). Comments should explain public contracts, compatibility constraints, trust boundaries, and non-obvious behavior rather than restating straightforward code.

## Widgets Examples

| Widget                                          | Guide                                                |
| ----------------------------------------------- | ---------------------------------------------------- |
| [Alert](docs/example_alert.md)                  | Alert messages                                       |
| [Box](docs/example_box.md)                      | Legacy card with GridView/footer helpers             |
| [Breadcrumbs](docs/example_breadcrumbs.md)      | Navigation breadcrumbs                               |
| [Card](docs/example_card.md)                    | Card (header, tools, body, footer; begin/end)        |
| [Content Header](docs/example_contentheader.md) | Page title and breadcrumbs                           |
| [DataColumn](docs/example_datacolumn.md)        | GridView column class (sorting header)               |
| [DetailView](docs/example_detailview.md)        | Kartik DetailView styled as AdminLTE 3 card          |
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
