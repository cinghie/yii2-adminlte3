<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders the AdminLTE 3 sidebar brand link.
 */
class NavbarLogo extends Widget
{
    /** @var string|null Optional brand image URL/path. */
    public $logo_url;

    /** @var string|null Brand text; defaults to the Yii application name. */
    public $brandText;

    /** @var string|array|null Home target; defaults to the application home URL. */
    public $url;

    /** @var array HTML options for the brand anchor. */
    public $linkOptions = [];

    /** @var array HTML options for the optional brand image. */
    public $imageOptions = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->brandText === null) {
            $this->brandText = Yii::$app->name;
        }
        if ($this->url === null) {
            $this->url = Yii::$app->homeUrl;
        }

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Html::addCssClass($this->linkOptions, ['brand-link', 'text-center']);
        $content = '';

        if ($this->logo_url !== null && $this->logo_url !== '') {
            $imgOptions = array_merge([
                'alt' => Html::encode($this->brandText),
                'class' => 'brand-image img-circle elevation-3 cinghie-brand-image',
            ], $this->imageOptions);
            $content .= Html::img(SafeHtml::linkUrl($this->logo_url, ''), $imgOptions);
        }

        $content .= Html::tag(
            'span',
            Html::encode($this->brandText),
            ['class' => 'brand-text font-weight-light']
        );

        return Html::a($content, SafeHtml::linkUrl($this->url, '#'), $this->linkOptions);
    }
}
