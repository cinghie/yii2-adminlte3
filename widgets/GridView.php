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
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView as BaseGrid;
use Yii;

/**
 * GridView for AdminLTE 3 with Bootstrap 4.
 *
 * Styled like AdminLTE 3 "DataTable with default features":
 * card → card-header (h3.card-title) → card-body → table.table-bordered.table-striped
 *
 * Empty cells render as '' — never "(not set)" / "(nessun valore)".
 *
 * @see https://adminlte.io/themes/v3/pages/tables/data.html
 * @see https://adminlte.io/docs/3.1/components/cards.html
 */
class GridView extends BaseGrid
{
    /** @var int|string Bootstrap version for Kartik (AdminLTE 3 = 4) */
    public $bsVersion = 4;

    /** @var string column class for data columns */
    public $dataColumnClass = DataColumn::class;

    /** @var array options for the table tag (Bootstrap 4 / AdminLTE 3 DataTables) */
    public $tableOptions = ['class' => 'table'];

    /**
     * Extra CSS class for the outer card (e.g. card-outline card-primary).
     * Applied when Kartik panel is used or when wrapping without panel.
     *
     * @var string
     */
    public $cardClass = '';

    /**
     * Optional card header title (HTML-encoded). Used when panel is empty.
     *
     * @var string|null
     */
    public $cardTitle;

    /**
     * Raw HTML for card-header (overrides cardTitle). Used when panel is empty.
     *
     * @var string|null
     */
    public $cardHeaderHtml;

    /** @var string deprecated; use $cardClass. Kept for backward compatibility. */
    public $boxClass = '';

    /**
     * Match AdminLTE DataTables default features: bordered + striped (no table-sm).
     *
     * @var bool
     */
    public $bordered = true;

    /** @var bool */
    public $condensed = false;

    /** @var bool */
    public $striped = true;

    /** @var bool hover is optional (minimal demo uses it; default features does not) */
    public $hover = false;

    /** @var string|null layout; if null and no panel, set in init() */
    public $layout;

    /** @var bool */
    public $responsive = true;

    /** @var bool */
    public $responsiveWrap = false;

    /** @var bool */
    public $pjax = true;

    /** @var array */
    public $pjaxSettings = [
        'neverTimeout' => true,
    ];

    /**
     * Kartik export menu (needs kartik-v/yii2-bootstrap4-dropdown on BS4).
     * Disabled by default — AdminLTE DataTables demo has no export toolbar.
     *
     * @var bool|array
     */
    public $export = false;

    /**
     * AdminLTE 3 card panel templates (DataTables-style).
     *
     * @var string
     */
    public $panelTemplate = <<< HTML
{panelHeading}
<div class="card-body">
{items}
</div>
{panelAfter}
{panelFooter}
HTML;

    /**
     * @var string
     */
    public $panelHeadingTemplate = <<< HTML
{title}
<div class="card-tools">{toolbarContainer}</div>
HTML;

    /**
     * Match AdminLTE DataTables Buttons container (dt-buttons).
     * @see https://adminlte.io/themes/v3/pages/tables/data.html
     *
     * @var array
     */
    public $toolbarContainerOptions = ['class' => 'dt-buttons btn-group'];

    /**
     * @var string
     */
    public $panelFooterTemplate = <<< HTML
<div class="d-flex justify-content-between align-items-center flex-wrap w-100">
    <div>{summary}</div>
    <div class="kv-panel-pager">{pager}</div>
</div>
{footer}
HTML;

    /**
     * @var string|null original panel type mapped to card-outline
     */
    protected $cardOutlineType;

    /**
     * @inheritdoc
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        $this->bsVersion = 4;
        $this->normalizePanelForAdminLte();
        $this->ensureExportDependency();
        $this->normalizeToolbarForAdminLte();

        if ($this->layout === null && (empty($this->panel) || !is_array($this->panel))) {
            $header = $this->renderStandaloneCardHeader();
            $this->layout = $header
                . "<div class=\"card-body\">{items}</div>\n"
                . '<div class="card-footer clearfix d-flex justify-content-between align-items-center flex-wrap">'
                . '<span>{summary}</span>{pager}</div>';
        }

        parent::init();
        // Empty values: never Yii "(not set)" / "(nessun valore)".
        $this->formatter->nullDisplay = '';
        $this->registerAdminLteGridCss();
    }

    /**
     * Toolbar as AdminLTE DataTables dt-buttons (no Kartik float-right).
     */
    protected function normalizeToolbarForAdminLte()
    {
        $this->toolbarContainerOptions = ArrayHelper::merge(
            ['class' => 'dt-buttons btn-group'],
            $this->toolbarContainerOptions ?: []
        );
        Html::removeCssClass($this->toolbarContainerOptions, 'btn-toolbar');
        Html::removeCssClass($this->toolbarContainerOptions, 'kv-grid-toolbar');
        Html::removeCssClass($this->toolbarContainerOptions, 'toolbar-container');
        Html::removeCssClass($this->toolbarContainerOptions, 'float-right');
        Html::removeCssClass($this->toolbarContainerOptions, 'float-left');
        Html::addCssClass($this->toolbarContainerOptions, 'dt-buttons btn-group');

        // DataTables Buttons use solid secondary buttons
        foreach (['all', 'page'] as $tag) {
            if (!isset($this->toggleDataOptions[$tag]['class'])) {
                $this->toggleDataOptions[$tag]['class'] = 'btn btn-secondary';
            }
        }
    }

    /**
     * @inheritdoc
     * Place toolbar in card-tools without float-right (flex aligns with card-title).
     */
    protected function renderToolbarContainer()
    {
        $options = $this->toolbarContainerOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        Html::addCssClass($options, 'dt-buttons btn-group');
        Html::removeCssClass($options, 'float-right');
        Html::removeCssClass($options, 'float-left');

        return Html::tag($tag, $this->renderToolbar(), $options);
    }

    /**
     * BS4 export dropdown requires kartik-v/yii2-bootstrap4-dropdown; disable if missing.
     */
    protected function ensureExportDependency()
    {
        if ($this->export === false) {
            return;
        }
        if (!class_exists(\kartik\bs4dropdown\ButtonDropdown::class)) {
            $this->export = false;
        }
    }

    /**
     * Convert legacy BS3 panel config to AdminLTE 3 DataTables card look.
     */
    protected function normalizePanelForAdminLte()
    {
        if (!is_array($this->panel) || empty($this->panel)) {
            return;
        }

        if (isset($this->panel['heading']) && is_string($this->panel['heading'])) {
            $heading = $this->panel['heading'];
            $heading = str_replace(['panel-title', 'fa fa-', 'glyphicon glyphicon-'], ['card-title', 'fas fa-', 'fas fa-'], $heading);
            // Views often pass a full <h3>…</h3>; Kartik wraps {title} again — keep inner HTML only
            if (preg_match('#<h[1-6][^>]*>(.*)</h[1-6]>#is', $heading, $m)) {
                $heading = $m[1];
            }
            $this->panel['heading'] = $heading;
        }

        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
        // Colored panel headers diverge from AdminLTE DataTables demo → outline on card, light header
        if (in_array($type, ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark'], true)) {
            $this->cardOutlineType = $type;
            $this->panel['type'] = 'default';
        }

        $this->panel = ArrayHelper::merge([
            'headingOptions' => ['class' => 'card-header'],
            'titleOptions' => [
                'class' => 'card-title',
                'tag' => 'h3',
            ],
            'footerOptions' => ['class' => 'card-footer'],
            'summaryOptions' => ['class' => 'kv-panel-summary'],
            'before' => false,
        ], $this->panel);

        // Always hide kv-panel-before (AdminLTE DataTables has no toolbar strip)
        $this->panel['before'] = false;

        // Ensure outline class lands on Kartik's card container
        $outline = $this->resolveCardOutlineClass();
        if ($outline !== '') {
            $options = ArrayHelper::getValue($this->panel, 'options', []);
            Html::addCssClass($options, $outline);
            $this->panel['options'] = $options;
        }
    }

    /**
     * @return string
     */
    protected function resolveCardOutlineClass()
    {
        $parts = array_filter([
            trim((string) $this->cardClass),
            trim((string) $this->boxClass),
        ]);
        if ($this->cardOutlineType) {
            $parts[] = 'card-outline';
            $parts[] = 'card-' . $this->cardOutlineType;
        }

        return trim(implode(' ', $parts));
    }

    /**
     * @return string
     */
    protected function renderStandaloneCardHeader()
    {
        if ($this->cardHeaderHtml !== null && $this->cardHeaderHtml !== '') {
            return Html::tag('div', $this->cardHeaderHtml, ['class' => 'card-header']) . "\n";
        }
        if ($this->cardTitle !== null && $this->cardTitle !== '') {
            $title = Html::tag('h3', Html::encode($this->cardTitle), ['class' => 'card-title']);

            return Html::tag('div', $title, ['class' => 'card-header']) . "\n";
        }

        return '';
    }

    /**
     * Header sort links must not use Bootstrap primary blue (AdminLTE/DataTables use body text color).
     */
    protected function registerAdminLteGridCss()
    {
        $css = <<< CSS
/* AdminLTE 3 DataTables: thead sort links inherit table header color (not \$link-color) */
.kv-grid-table thead th a,
.kv-grid-table thead th a:hover,
.kv-grid-table thead th a:focus,
.kv-grid-table thead th a:active,
.card .table thead th a,
.card .table thead th a:hover,
.card .table thead th a:focus,
.card .table thead th a:active {
    color: inherit !important;
    text-decoration: none;
}
.kv-grid-table thead th a .fas,
.kv-grid-table thead th a .fa,
.kv-grid-table thead th a .glyphicon,
.card .table thead th a .fas,
.card .table thead th a .fa {
    opacity: 0.55;
    margin-left: 0.35rem;
    font-size: 0.85em;
}
.kv-grid-panel > .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .5rem;
}
.kv-grid-panel > .card-header::after {
    display: none; /* AdminLTE clearfix conflicts with flex alignment */
}
.kv-grid-panel > .card-header .card-title {
    display: inline-flex;
    align-items: center;
    float: none;
    margin: 0;
    line-height: 1.5;
}
.kv-grid-panel > .card-header .card-title > .fa,
.kv-grid-panel > .card-header .card-title > .fas,
.kv-grid-panel > .card-header .card-title > .far,
.kv-grid-panel > .card-header .card-title > .fab,
.kv-grid-panel > .card-header .card-title > .glyphicon {
    line-height: 1;
    vertical-align: middle;
}
/* AdminLTE card-tools + DataTables dt-buttons */
.kv-grid-panel > .card-header .card-tools {
    float: none;
    margin: 0 0 0 auto;
    display: inline-flex;
    align-items: center;
}
.kv-grid-panel > .card-header .card-tools .dt-buttons {
    position: initial;
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: .25rem;
}
.kv-grid-panel > .card-header .card-tools .dt-buttons > .btn-group {
    margin: 0;
}
.kv-grid-panel > .card-header .card-tools .dt-buttons .btn {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
}
.kv-grid-panel .card-body {
    padding: 1.25rem;
}
.kv-grid-panel .card-footer {
    background-color: #fff !important;
    background-image: none !important;
    border-top: 1px solid rgba(0, 0, 0, .125);
}
.kv-grid-panel .card-footer .pagination {
    margin-bottom: 0;
}
.kv-grid-panel .kv-table-footer,
.kv-grid-panel .kv-grid-table > tfoot > tr > th,
.kv-grid-panel .kv-grid-table > tfoot > tr > td {
    background-color: #fff !important;
    background-image: none !important;
}
/* AdminLTE DataTables: white thead, normal height, visible top border */
.kv-grid-panel .kv-grid-table,
.kv-grid-bs4 .card .kv-grid-table {
    margin-bottom: 0;
    border: 1px solid #dee2e6;
}
.kv-grid-panel .kv-grid-table > thead.kv-table-header > tr > th,
.kv-grid-panel .kv-grid-table > thead.kv-table-header > tr > td,
.kv-grid-panel .kv-table-header > tr > th,
.kv-grid-panel .kv-table-header > tr > td,
.kv-grid-panel .table-bordered > thead.kv-table-header > tr,
.kv-grid-panel .table-bordered > thead.kv-table-header > tr > th,
.kv-grid-panel .table-bordered > thead.kv-table-header > tr > td,
.card .kv-grid-table > thead > tr > th,
.card .table > thead > tr > th {
    background-color: #fff !important;
    background-image: none !important;
    border-top: 1px solid #dee2e6 !important;
    border-bottom: 1px solid #dee2e6 !important;
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
    line-height: 1.4;
    font-weight: 600;
}
.kv-grid-panel .kv-grid-table > thead > tr.filters > td,
.kv-grid-panel .table > thead > tr.filters > td {
    background-color: #fff;
    border-top: 0 !important;
    padding: 0.4rem 0.75rem;
    vertical-align: middle;
}
.kv-grid-panel .kv-grid-table > tbody > tr > td,
.kv-grid-panel .kv-grid-table > tbody > tr > th {
    padding: 0.5rem 0.75rem;
    vertical-align: middle;
}
.kv-grid-panel .kv-sort-link .kv-sort-icon {
    margin-left: 0.25rem;
    font-size: 0.75em;
    line-height: 1;
}
CSS;
        Yii::$app->view->registerCss($css, [], 'cinghie-adminlte3-gridview');
    }

    /**
     * @return string|void
     * @throws InvalidConfigException
     */
    public function run()
    {
        // Kartik panel already renders the BS4 card — avoid a second wrapper
        if (is_array($this->panel) && !empty($this->panel)) {
            parent::run();

            return;
        }

        $cardClass = trim('card ' . $this->resolveCardOutlineClass());
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
