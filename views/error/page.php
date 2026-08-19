<?php

use yii\helpers\Html;

$htmlOptions = $options;
Html::addCssClass($htmlOptions, 'error-page');
$icon = Html::tag('i', '', [
    'class' => 'fas fa-exclamation-triangle ' . $headlineClass,
    'aria-hidden' => 'true',
]);
$titleHtml = $icon . ' ' . Html::encode($title);
$messageHtml = nl2br(Html::encode($message));
$homeLink = Html::a(Html::encode($homeLabel), $homeHref, ['aria-label' => $homeLabel]);
?>
<section class="content">
    <?= Html::beginTag('div', $htmlOptions) ?>
        <?= Html::tag('h2', (string) $statusCode, ['class' => 'headline ' . $headlineClass]) ?>
        <div class="error-content">
            <?= Html::tag('h3', $titleHtml) ?>
            <?= Html::tag('p', $messageHtml) ?>
            <?= Html::tag('p', 'Meanwhile, you may ' . $homeLink . '.') ?>
        </div>
    <?= Html::endTag('div') ?>
</section>
