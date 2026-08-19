<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Legacy AdminLTE 3 card widget.
 *
 * Box is now a backward-compatible facade over {@see Card}. New code should
 * use Card directly; Box keeps its historical GridView and footer-button API.
 *
 * @deprecated Prefer {@see Card}.
 */
class Box extends Card
{
    public $wrapperClass = 'col-lg-6';
    public $type = self::TYPE_INFO;
    public $collapsible = true;
    public $removable = true;

    public $dataProvider;
    public $columns;
    public $footerLeftTitle;
    public $footerLeftUrl;
    public $footerLeftType = 'primary';
    public $footerRightTitle;
    public $footerRightUrl;
    public $footerRightType = 'secondary';

    // Backward-compatible aliases.
    public $class;
    public $buttonLeftTitle;
    public $buttonLeftLink;
    public $buttonLeftType;
    public $buttonRightTitle;
    public $buttonRightLink;
    public $buttonRightType;

    /**
     * Box intentionally skips Card's output-buffer initialization because the
     * legacy widget API is widget()-only. Rendering itself is delegated to Card.
     */
    public function init()
    {
        if ($this->class !== null) {
            $this->wrapperClass = $this->class;
        }

        $this->type = $this->normalizeType($this->type) ?: self::TYPE_INFO;

        if ($this->title === null) {
            $this->title = Yii::t('app', 'Card');
        }

        if ($this->footerLeftTitle === null && $this->buttonLeftTitle !== null) {
            $this->footerLeftTitle = $this->buttonLeftTitle;
            $this->footerLeftUrl = $this->buttonLeftLink !== null ? $this->buttonLeftLink : $this->footerLeftUrl;
            if ($this->buttonLeftType !== null) {
                $this->footerLeftType = $this->buttonLeftType;
            }
        }

        if ($this->footerRightTitle === null && $this->buttonRightTitle !== null) {
            $this->footerRightTitle = $this->buttonRightTitle;
            $this->footerRightUrl = $this->buttonRightLink !== null ? $this->buttonRightLink : $this->footerRightUrl;
            if ($this->buttonRightType !== null) {
                $this->footerRightType = $this->buttonRightType;
            }
        }

        $this->footerLeftType = self::normalizeButtonType($this->footerLeftType, 'primary');
        $this->footerRightType = self::normalizeButtonType($this->footerRightType, 'secondary');
    }

    public function run()
    {
        $bodyHtml = $this->resolveLegacyBody();
        Html::addCssClass($this->options, $this->resolveCardCssClasses());

        $html = $this->renderHeader();
        $html .= parent::renderBody($bodyHtml);
        $html .= $this->renderFooter();

        $card = Html::tag('div', $html, $this->options);

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            return Html::tag('div', $card, ['class' => self::sanitizeClass($this->wrapperClass, '')]);
        }

        return $card;
    }

    protected function resolveLegacyBody(): string
    {
        if ($this->body !== null && $this->body !== '') {
            if (is_string($this->body)) {
                return $this->encodeBody ? Html::encode($this->body) : $this->body;
            }
            if (is_array($this->body) && isset($this->body['dataProvider'], $this->body['columns'])) {
                return $this->renderGrid($this->body['dataProvider'], $this->body['columns']);
            }

            return '';
        }

        if ($this->dataProvider !== null && $this->columns !== null) {
            return $this->renderGrid($this->dataProvider, $this->columns);
        }

        return Html::tag('p', Html::encode(Yii::t('app', 'No content.')), ['class' => 'card-text text-muted']);
    }

    protected function renderGrid($dataProvider, array $columns)
    {
        return GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $columns,
            'hover' => true,
            'panel' => false,
            'responsiveWrap' => true,
            'summary' => '',
        ]);
    }

    protected function renderFooter()
    {
        $hasLeft = $this->footerLeftTitle !== null && $this->footerLeftTitle !== '' && $this->footerLeftUrl !== null;
        $hasRight = $this->footerRightTitle !== null && $this->footerRightTitle !== '' && $this->footerRightUrl !== null;

        if (!$hasLeft && !$hasRight) {
            return '';
        }

        $left = $hasLeft ? Html::a(
            Html::encode((string) $this->footerLeftTitle),
            $this->normalizeUrl($this->footerLeftUrl),
            ['class' => 'btn btn-sm btn-' . $this->footerLeftType]
        ) : '';

        $right = $hasRight ? Html::a(
            Html::encode((string) $this->footerRightTitle),
            $this->normalizeUrl($this->footerRightUrl),
            ['class' => 'btn btn-sm btn-' . $this->footerRightType . ' float-right']
        ) : '';

        return Html::tag('div', $left . $right, ['class' => 'card-footer']);
    }

    protected function normalizeUrl($url)
    {
        if (is_array($url)) {
            return Url::to($url);
        }

        $url = (string) $url;
        if (preg_match('#^\s*(?:javascript|data|vbscript):#i', $url)) {
            return '#';
        }

        return $url;
    }

    protected static function normalizeButtonType($type, $default)
    {
        $type = strtolower(str_replace('btn-', '', trim((string) $type)));
        $allowed = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark', 'light', 'link'];

        return in_array($type, $allowed, true) ? $type : $default;
    }
}
