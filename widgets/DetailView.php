<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use kartik\detail\DetailView as BaseDetailView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DetailView extends BaseDetailView
{
    public $bsVersion = 4;
    public $cardClass = '';
    public $nullDisplay = '';
    protected $cardOutlineType;

    public function init()
    {
        $this->bsVersion = 4;
        $this->normalizePanelForAdminLte();
        parent::init();
        if (isset(Yii::$app->formatter) && $this->formatter === Yii::$app->formatter) {
            $this->formatter = clone $this->formatter;
        }
        if (is_object($this->formatter) && property_exists($this->formatter, 'nullDisplay')) {
            $this->formatter->nullDisplay = $this->nullDisplay;
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
            'footerOptions' => ['class' => 'card-footer'],
        ], $this->panel);

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

    protected function resolveCardOutlineClass()
    {
        $parts = array_filter([trim((string) $this->cardClass)]);
        if ($this->cardOutlineType) {
            $parts[] = 'card-outline';
            $parts[] = 'card-' . $this->cardOutlineType;
        }
        return trim(implode(' ', $parts));
    }
}
