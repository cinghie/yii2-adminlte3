SidebarToggle Example
=====================

## Default value

```php
<?php use cinghie\adminlte3\widgets\SidebarToggle; ?>

<?= SidebarToggle::widget() ?>
```

The default accessibility label is translated through the package-owned `adminlte3` category.

## Custom icon

```php
<?= SidebarToggle::widget([
    'icon' => 'fas fa-bars',
    'ariaLabel' => 'Toggle menu',
]) ?>
```

`iconClass` remains available as a deprecated alias for `icon`. When both are supplied, `icon` wins.
