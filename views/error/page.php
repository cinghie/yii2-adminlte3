<?php

use yii\helpers\Html;
use yii\helpers\Url;

$htmlOptions = $options;
Html::addCssClass($htmlOptions, 'error-page');
$homeHref = Url::to($homeUrl);
?>
<section class="content">
    <?= Html::beginTag('div', $htmlOptions) ?>
        <?= Html::tag('h2', (string) $statusCode, ['class' => 'headline ' . $headlineClass]) ?>
        <div class="error-content">
            <?= Html::tag('h3', Html::encode($title)) ?>
            <?= Html::tag('p', nl2br(Html::encode($message))) ?>
            <?= Html::a(
                Html::tag('i', '', ['class' => 'fas fa-home mr-1', 'aria-hidden' => 'true']) . Html::encode($homeLabel),
                $homeHref,
                [
                    'class' => 'btn btn-primary',
                    'aria-label' => $homeLabel,
                ]
            ) ?>
        </div>
    <?= Html::endTag('div') ?>
</section>
