# Yii2 AdminLTE 3

![License](https://img.shields.io/packagist/l/cinghie/yii2-adminlte3.svg)
![Latest Stable Version](https://img.shields.io/github/release/cinghie/yii2-adminlte3.svg)
![Latest Release Date](https://img.shields.io/github/release-date/cinghie/yii2-adminlte3.svg)
![Latest Commit](https://img.shields.io/github/last-commit/cinghie/yii2-adminlte3.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/cinghie/yii2-adminlte3.svg)](https://packagist.org/packages/cinghie/yii2-adminlte3)

Asset Bundle to include AdminLTE 3 on your Yii 2 project: https://github.com/ColorlibHQ/AdminLTE/tree/v3/

Installation
-----------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require cinghie/yii2-adminlte3 "@dev"
```

or add this line to the require section of your `composer.json` file.

```
"cinghie/yii2-adminlte3": "@dev"
```

Configuration
-----------------

Add in the view for normal CSS and JS

```
use cinghie\adminlte3\AdminLTEAsset;

AdminLTEAsset::register($this);
```

Add in the view for minify CSS and JS

```
use cinghie\adminlte3\AdminLTEMinifyAsset;

AdminLTEMinifyAsset::register($this);
```

Widgets Examples
-----------------

| Widget | Guide |
|--------|--------|
| [Alert](docs/example_alert.md) | Alert messages |
| [Box](docs/example_box.md) | Card with header, body (content or GridView), footer |
| [Breadcrumbs](docs/example_breadcrumbs.md) | Navigation breadcrumbs |
| [Card](docs/example_card.md) | Simple card (header, body, footer) |
| [Content Header](docs/example_contentheader.md) | Page title and breadcrumbs |
| [DataColumn](docs/example_datacolumn.md) | GridView column class (sorting header) |
| [Footer](docs/example_footer.md) | Layout footer |
| [GridView](docs/example_gridview.md) | Data grid in AdminLTE 3 card |
| [InfoBox](docs/example_infobox.md) | Info box (icon, text, number, optional progress) |
| [Invoice](docs/example_invoice.md) | Invoice layout |
| [MailboxRead](docs/example_mailboxread.md) | Read-mail card (subject, body, attachments) |
| [NavTabs](docs/example_navtabs.md) | Nav tabs with tab panes (Bootstrap 4) |
| [Navbar Button](docs/example_navbarbutton.md) | Navbar link button |
| [Navbar Logo](docs/example_navbarlogo.md) | Navbar brand/logo |
| [Navbar User](docs/example_navbaruser.md) | Navbar user dropdown |
| [Sidebar Menu](docs/example_sidebarmenu.md) | Sidebar navigation menu |
| [Sidebar Search](docs/example_sidebarsearch.md) | Sidebar search form |
| [Sidebar Toggle](docs/example_sidebartoggle.md) | Sidebar toggle button |
| [Sidebar User](docs/example_sidebaruser.md) | Sidebar user panel |
| [SmallBox](docs/example_smallbox.md) | Small stat box with optional footer link |
| [Timeline](docs/example_timeline.md) | Timeline (days and items) |
