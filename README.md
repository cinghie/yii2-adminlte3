# Yii2 AdminLTE 3

![License](https://img.shields.io/packagist/l/cinghie/yii2-admin-lte.svg)
![Latest Stable Version](https://img.shields.io/github/release/cinghie/yii2-admin-lte.svg)
![Latest Release Date](https://img.shields.io/github/release-date/cinghie/yii2-admin-lte.svg)
![Latest Commit](https://img.shields.io/github/last-commit/cinghie/yii2-admin-lte.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/cinghie/yii2-admin-lte.svg)](https://packagist.org/packages/cinghie/yii2-admin-lte)

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

[Alert](docs/example_alert.md)  
[Box](docs/example_box.md)  
[Content Header](docs/example_contentheader.md)  
[Footer](docs/example_footer.md)  
[Navbar Button](docs/example_navbarbutton.md)  
[Navbar Logo](docs/example_navbarlogo.md)  
[Navbar User](docs/example_navbaruser.md)  
[Sidebar Menu](docs/example_sidebarmenu.md)  
[Sidebar Search](docs/example_sidebarsearch.md)  
[Sidebar Toggle](docs/example_sidebartoggle.md)  
[Sidebar User](docs/example_sidebaruser.md)  
[Simplebox](docs/example_simplebox.md)  
