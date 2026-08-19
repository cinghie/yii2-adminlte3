<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use kartik\grid\GridView as BaseGrid;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * GridView for AdminLTE 3 with Bootstrap 4.
 */
class GridView extends BaseGrid
{
    public $bsVersion = 4;
    public $dataColumnClass = DataColumn::class;
    public $tableOptions = ['class' => 'table'];
    public $cardClass = '';
    public $cardTitle;
    public $cardHeaderHtml;
    /** @deprecated use $cardClass */
    public $boxClass = '';
    public $bordered = true;
    public $condensed = false;
    public $striped = true;
    public $hover = false;
    public $layout;
    public $responsive = true;
    public $responsiveWrap = false;
    public $pjax = true;
    public $pjaxSettings = ['neverTimeout' => true];
    public $export = false;
    /** @var string display value used for null cells without mutating the app formatter */
    public $nullDisplay = '';

    public $panelTemplate = <<< HTML
{panelHeading}
<div class="card-body">
{items}
</div>
{panelAfter}
{panelFooter}
HTML;

    public $panelHeadingTemplate = <<< HTML
{title}
<div class="card-tools">{toolbarContainer}</div>
HTML;

    public $toolbarContainerOptions = ['class' => 'dt-buttons btn-group'];

    public $panelFooterTemplate = <<< HTML
<div class="d-flex justify-content-between align-items-center flex-wrap w-100">
    <div>{summary}</div>
    <div class="kv-panel-pager">{pager}</div>
</div>
{footer}
HTML;

    protected $cardOutlineType;

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
        $this->isolateFormatter();
    }

    protected function isolateFormatter()
    {
        if (isset(Yii::$app->formatter) && $this->formatter === Yii::$app->formatter) {
            $this->formatter = clone $this->formatter;
        }
        if (is_object($this->formatter) && property_exists($this->formatter, 'nullDisplay')) {
            $this->formatter->nullDisplay = $this->nullDisplay;
        }
    }

    protected function normalizeToolbarForAdminLte()
    {
        $this->toolbarContainerOptions = ArrayHelper::merge(
            ['class' => 'dt-buttons btn-group'],
            $this->toolbarContainerOptions ?: []
        );
        foreach (['btn-toolbar', 'kv-grid-toolbar', 'toolbar-container', 'float-right', 'float-left'] as $class) {
            Html::removeCssClass($this->toolbarContainerOptions, $class);
        }
        Html::addCssClass($this->toolbarContainerOptions, 'dt-buttons btn-group');

        foreach (['all', 'page'] as $tag) {
            if (!isset($this->toggleDataOptions[$tag]['class'])) {
                $this->toggleDataOptions[$tag]['class'] = 'btn btn-secondary';
            }
        }
    }

    protected function renderToolbarContainer()
    {
        $options = $this->toolbarContainerOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'div');
        Html::addCssClass($options, 'dt-buttons btn-group');
        Html::removeCssClass($options, 'float-right');
        Html::removeCssClass($options, 'float-left');

        return Html::tag($tag, $this->renderToolbar(), $options);
    }

    protected function ensureExportDependency()
    {
        if ($this->export === false) {
            return;
        }
        if (!class_exists(\kartik\bs4dropdown\ButtonDropdown::class)) {
            throw new InvalidConfigException(
                'Grid export with Bootstrap 4 requires kartik-v/yii2-bootstrap4-dropdown.'
            );
        }
    }

    protected function normalizePanelForAdminLte()
    {
        if (!is_array($this->panel) || empty($this->panel)) {
            return;
        }

        if (isset($this->panel['heading']) && is_string($this->panel['heading'])) {
            $heading = str_replace(
                ['panel-title', 'fa fa-', 'glyphicon glyphicon-'],
                ['card-title', 'fas fa-', 'fas fa-'],
                $this->panel['heading']
            );
            if (preg_match('#<h[1-6][^>]*>(.*)</h[1-6]>#is', $heading, $matches)) {
                $heading = $matches[1];
            }
            $this->panel['heading'] = $heading;
        }

        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
        if (in_array($type, ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark'], true)) {
            $this->cardOutlineType = $type;
            $this->panel['type'] = 'default';
        }

        $this->panel = ArrayHelper::merge([
            'headingOptions' => ['class' => 'card-header'],
            'titleOptions' => ['class' => 'card-title', 'tag' => 'h3'],
            'footerOptions' => ['class' => 'card-footer'],
            'summaryOptions' => ['class' => 'kv-panel-summary'],
            'before' => false,
        ], $this->panel);
        $this->panel['before'] = false;

        $outline = $this->resolveCardOutlineClass();
        if ($outline !== '') {
            $options = ArrayHelper::getValue($this->panel, 'options', []);
            Html::addCssClass($options, $outline);
            $this->panel['options'] = $options;
        }
    }

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

    public function run()
    {
        if (is_array($this->panel) && !empty($this->panel)) {
            parent::run();
            return;
        }

        $cardClass = trim('card ' . $this->resolveCardOutlineClass());
        echo Html::beginTag('div', ['class' => $cardClass]);
        parent::run();
        echo Html::endTag('div');
    }

    public function renderPager()
    {
        return Html::tag('div', parent::renderPager(), ['class' => 'd-flex justify-content-end align-items-center']);
    }
}
