<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-adminlte3
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-AdminLTE
 * @version 0.1.0
 */

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * NavbarLogo (brand) widget for AdminLTE 3.
 *
 * Renders the sidebar brand link: logo image (optional) + app name.
 * Use in sidebar: <aside class="main-sidebar"> ... <?= NavbarLogo::widget() ?> ... </aside>
 *
 * @see https://adminlte.io/docs/3.1/components/main-sidebar.html#brand-logo
 */
class NavbarLogo extends Widget
{
    /**
     * @var string|null URL of the brand image. Null = no image, only text.
     */
    public $logo_url;

    /**
     * @var string|null Brand text (default: Yii::$app->name)
     */
    public $brandText;

    /**
     * @var string|array Home URL (default: Yii::$app->homeUrl)
     */
    public $url;

    /**
     * @var array HTML options for the anchor (brand-link)
     */
    public $linkOptions = [];

    /**
     * @var array HTML options for the image
     */
    public $imageOptions = [];

    /**
     * @inheritdoc
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
     * @return string
     */
    public function run()
    {
        $url = is_array($this->url) ? Url::to($this->url) : $this->url;

        Html::addCssClass($this->linkOptions, 'brand-link');
        Html::addCssClass($this->linkOptions, 'text-center');
        $content = '';

        if ($this->logo_url !== null && $this->logo_url !== '') {
            $imgOptions = array_merge([
                'alt' => Html::encode($this->brandText),
                'class' => 'brand-image img-circle elevation-3',
                'style' => 'opacity: .8',
            ], $this->imageOptions);
            $content .= Html::img($this->logo_url, $imgOptions);
        }

        $content .= Html::tag('span', Html::encode($this->brandText), ['class' => 'brand-text font-weight-light']);

        return Html::a($content, $url, $this->linkOptions);
    }
}
