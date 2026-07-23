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
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\detail\DetailView as BaseDetailView;

/**
 * DetailView for AdminLTE 3 with Bootstrap 4.
 *
 * Extends Kartik DetailView and normalizes the panel to an AdminLTE 3 card
 * (same approach as {@see GridView}).
 *
 * @see https://adminlte.io/docs/3.1/components/cards.html
 */
class DetailView extends BaseDetailView
{
    /** @var int|string Bootstrap version for Kartik (AdminLTE 3 = 4) */
    public $bsVersion = 4;

    /**
     * Extra CSS class for the outer card (e.g. card-outline card-primary).
     *
     * @var string
     */
    public $cardClass = '';

    /**
     * @var string|null original panel type mapped to card-outline
     */
    protected $cardOutlineType;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->bsVersion = 4;
        $this->normalizePanelForAdminLte();
        parent::init();
        $this->registerAdminLteDetailCss();
    }

    /**
     * Convert legacy BS3 panel config to AdminLTE 3 card look.
     */
    protected function normalizePanelForAdminLte()
    {
        if (!is_array($this->panel) || empty($this->panel)) {
            return;
        }

        if (isset($this->panel['heading']) && is_string($this->panel['heading'])) {
            $heading = $this->panel['heading'];
            $heading = str_replace(
                ['panel-title', 'fa fa-', 'glyphicon glyphicon-'],
                ['card-title', 'fas fa-', 'fas fa-'],
                $heading
            );
            if (preg_match('#<h[1-6][^>]*>(.*)</h[1-6]>#is', $heading, $m)) {
                $heading = $m[1];
            }
            $this->panel['heading'] = $heading;
        }

        $type = ArrayHelper::getValue($this->panel, 'type', 'default');
        if (in_array($type, ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark'], true)) {
            $this->cardOutlineType = $type;
            // Kartik BS4 panel "default" maps cleanly; outline color goes on the card
            $this->panel['type'] = 'default';
        }

        $this->panel = ArrayHelper::merge([
            'headingOptions' => ['class' => 'card-header'],
            'footerOptions' => ['class' => 'card-footer'],
        ], $this->panel);

        // Prefer AdminLTE card without Kartik before/after strips unless explicitly provided.
        if (!array_key_exists('before', $this->panel)) {
            $this->panel['before'] = false;
        }
        if (!array_key_exists('after', $this->panel)) {
            $this->panel['after'] = false;
        }

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
        ]);
        if ($this->cardOutlineType) {
            $parts[] = 'card-outline';
            $parts[] = 'card-' . $this->cardOutlineType;
        }

        return trim(implode(' ', $parts));
    }

    /**
     * Align Kartik detail panel with AdminLTE card headers.
     */
    protected function registerAdminLteDetailCss()
    {
        $css = <<< CSS
.kv-detail-view .card-header,
.card.kv-detail-view > .card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .5rem;
}
.kv-detail-view .card-header .card-title,
.card.kv-detail-view > .card-header .card-title {
    margin: 0;
    float: none;
    line-height: 1.5;
}
.kv-detail-view .table > tbody > tr > th,
.kv-detail-view .table > tbody > tr > td {
    vertical-align: middle;
}
CSS;
        Yii::$app->view->registerCss($css, [], 'cinghie-adminlte3-detailview');
    }
}
