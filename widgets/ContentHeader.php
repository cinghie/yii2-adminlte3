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
 * ContentHeader widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders the content header (page title + breadcrumbs) using AdminLTE 3 layout:
 * section.content-header > container-fluid > row > col-sm-6 (title) + col-sm-6 (breadcrumbs).
 *
 * @see https://adminlte.io/docs/3.1/components/content-header.html
 */
class ContentHeader extends Widget
{
    /**
     * @var string Page title
     */
    public $title;

    /**
     * @var string Subtitle (HTML, e.g. <small>Version 2.0</small>). Empty string = no subtitle.
     */
    public $subtitle = '';

    /**
     * @var array Breadcrumb links (same format as yii\widgets\Breadcrumbs). Empty array = no breadcrumbs.
     */
    public $breadcrumbs = [];

    /**
     * @var array Options for the breadcrumbs widget (homeLink, encodeLabels, etc.)
     */
    public $breadcrumbsOptions = [];

    /**
     * @var bool Whether to show the breadcrumbs column when breadcrumbs are empty (shows default "Home" only)
     */
    public $showBreadcrumbsWhenEmpty = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->title === null) {
            $this->title = Yii::t('app', 'Dashboard');
        }

        if ($this->subtitle === null) {
            $this->subtitle = '';
        } elseif ($this->subtitle !== '') {
            $this->subtitle = ' <small>' . Html::encode($this->subtitle) . '</small>';
        }

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $breadcrumbsHtml = $this->renderBreadcrumbs();

        $titleCol = Html::tag('div', '<h1 class="m-0">' . Html::encode($this->title) . $this->subtitle . '</h1>', [
            'class' => 'col-sm-6',
        ]);

        $breadcrumbsCol = Html::tag('div', $breadcrumbsHtml, [
            'class' => 'col-sm-6',
        ]);

        $row = Html::tag('div', $titleCol . $breadcrumbsCol, [
            'class' => 'row mb-2',
        ]);

        $container = Html::tag('div', $row, [
            'class' => 'container-fluid',
        ]);

        return Html::tag('div', $container, [
            'class' => 'content-header',
        ]);
    }

    /**
     * Renders the breadcrumbs block (AdminLTE 3 + Bootstrap 4 markup).
     * @return string
     */
    protected function renderBreadcrumbs()
    {
        $links = $this->breadcrumbs;
        $isEmpty = empty($links);

        if ($isEmpty && !$this->showBreadcrumbsWhenEmpty) {
            return '';
        }

        if ($isEmpty) {
            $homeUrl = is_array(Yii::$app->homeUrl) ? Url::to(Yii::$app->homeUrl) : Yii::$app->homeUrl;
            return '<ol class="breadcrumb float-sm-right">' .
                '<li class="breadcrumb-item"><a href="' . Html::encode($homeUrl) . '">' . Html::encode(Yii::t('yii', 'Home')) . '</a></li>' .
                '<li class="breadcrumb-item active" aria-current="page">' . Html::encode($this->title) . '</li>' .
                '</ol>';
        }

        return Breadcrumbs::widget(array_merge([
            'links' => $links,
        ], $this->breadcrumbsOptions));
    }
}
