Navbar Button Example
=====================

## Default value

```php
<?php use cinghie\adminlte3\widgets\NavbarButton; ?>

<?= NavbarButton::widget() ?>
```

## External link

```php
<?= NavbarButton::widget([
    'title' => '<i class="fas fa-external-link-alt"></i>',
    'options' => ['class' => 'nav-link'],
    'target' => '_blank',
    'url' => 'https://www.example.com',
]) ?>
```

`target="_blank"` is normalized by the package and receives `rel="noopener noreferrer"`.

## POST action

```php
<?= NavbarButton::widget([
    'title' => '<i class="fas fa-power-off"></i>',
    'options' => ['class' => 'nav-link', 'data-method' => 'post'],
    'url' => ['/user/security/logout'],
]) ?>
```

## Backward compatibility

`option` remains available as a deprecated alias for `options`. When both are supplied, canonical `options` keys win while non-conflicting legacy keys are retained.
