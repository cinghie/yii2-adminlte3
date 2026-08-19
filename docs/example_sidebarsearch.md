Sidebar Search Example
======================

## Default value

```php
<?php use cinghie\adminlte3\widgets\SidebarSearch; ?>

<?= SidebarSearch::widget() ?>
```

The default placeholder is translated through the package-owned `adminlte3` category.

## Custom value

```php
<?= SidebarSearch::widget([
    'placeholder' => 'Find…',
    'icon' => 'fas fa-search',
]) ?>
```

`searchIconClass` remains available as a deprecated alias for `icon`. When both are configured, `icon` wins.
