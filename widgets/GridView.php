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

use yii\base\InvalidConfigException;
use kartik\grid\GridView as baseGrid;
use yii\helpers\Html;

/**
 * GridView for AdminLTE 3 with Bootstrap 4.
 *
 * Wraps output in an AdminLTE 3 card (card-body, card-footer). Uses Bootstrap 4 table classes.
 * Optional card header via $cardTitle (HTML-encoded). Backward compatible with $boxClass.
 *
 * @see https://adminlte.io/docs/3.1/components/cards.html
 */
class GridView extends baseGrid
{
    /** @var string column class for data columns */
    public $dataColumnClass = DataColumn::class;

    /** @var array options for the table tag (Bootstrap 4) */
    public $tableOptions = ['class' => 'table table-sm'];

    /** @var string extra CSS class for the card wrapper (e.g. card-primary). */
    public $cardClass = '';

    /** @var string|null optional card header title (HTML-encoded when rendered). */
    public $cardTitle;

    /** @var string deprecated; use $cardClass. Kept for backward compatibility. */
    public $boxClass = '';

    /**
     * @var bool is bordered
     */
    public $bordered = true;

    /**
     * @var bool is condensed
     */
    public $condensed = true;

    /**
     * @var bool is striped
     */
    public $striped = true;

    /**
     * @var bool is row have hover effect
     */
    public $hover = true;

    /** @var string layout; if null, set in init() to card-body/card-footer (and optional card-header). */
    public $layout;

    /**
     * @var boolean whether the grid will have a `responsive` style. Applicable only if `bootstrap` is `true`.
     */
    public $responsive = true;

    /**
     * @var boolean whether the grid will automatically wrap to fit columns for smaller display sizes.
     */
    public $responsiveWrap = true;

    /**
     * @var boolean whether the grid view will be rendered within a pjax container. Defaults to `false`. If set to
     * `true`, the entire GridView widget will be parsed via Pjax and auto-rendered inside a yii\widgets\Pjax
     * widget container. If set to `false` pjax will be disabled and none of the pjax settings will be applied.
     */
    public $pjax = true;

    /**
     * @var array the pjax settings for the widget. This will be considered only when [[pjax]] is set to true. The
     * following settings are recognized:
     * - `neverTimeout`: `boolean`, whether the pjax request should never timeout. Defaults to `true`. The pjax:timeout
     *   event will be configured to disable timing out of pjax requests for the pjax container.
     * - `options`: _array_, the options for the [[\yii\widgets\Pjax]] widget.
     * - `loadingCssClass`: boolean/string, the CSS class to be applied to the grid when loading via pjax. If set to
     *   `false` - no css class will be applied. If it is empty, null, or set to `true`, will default to
     *   `kv-grid-loading`.
     * - `beforeGrid`: _string_, any content to be embedded within pjax container before the Grid widget.
     * - `afterGrid`: _string_, any content to be embedded within pjax container after the Grid widget.
     */
    public $pjaxSettings = [
        'neverTimeout'=>true,
    ];

    /**
     * @inheritdoc
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->layout === null) {
            $header = '';
            if ($this->cardTitle !== null && $this->cardTitle !== '') {
                $header = Html::tag('div', Html::encode($this->cardTitle), ['class' => 'card-header']) . "\n";
            }
            $this->layout = $header . "<div class=\"card-body\">{items}</div>\n<div class=\"card-footer clearfix d-flex justify-content-between align-items-center flex-wrap\"><span>{summary}</span>{pager}</div>";
        }

        if ($this->bordered) {
            Html::addCssClass($this->tableOptions, 'table-bordered');
        }

        if ($this->condensed) {
            Html::addCssClass($this->tableOptions, 'table-sm');
        }

        if ($this->striped) {
            Html::addCssClass($this->tableOptions, 'table-striped');
        }

        if ($this->hover) {
            Html::addCssClass($this->tableOptions, 'table-hover');
        }

        parent::init();
    }

    /**
     * @return string|void
     * @throws InvalidConfigException
     */
    public function run()
    {
        $cardClass = trim('card ' . (string) $this->cardClass . ' ' . (string) $this->boxClass);
        echo Html::beginTag('div', ['class' => $cardClass]);
        parent::run();
        echo Html::endTag('div');
    }

    /**
     * @inheritdoc
     */
    public function renderPager()
    {
        return Html::tag('div', parent::renderPager(), ['class' => 'd-flex justify-content-end align-items-center']);
    }
}
