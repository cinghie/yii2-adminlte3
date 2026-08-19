<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Legacy AdminLTE 3 card widget.
 *
 * @deprecated Prefer {@see Card}. Box remains for backward compatibility and
 *             for its convenience GridView/footer-button API.
 */
class Box extends Widget
{
    public $wrapperClass = 'col-lg-6';
    public $type;
    public $outline = false;
    public $title;
    public $titleIcon;
    public $collapsible = true;
    public $removable = true;
    public $body;
    public $encodeBody = true;
    public $dataProvider;
    public $columns;
    public $footerLeftTitle;
    public $footerLeftUrl;
    public $footerLeftType = 'primary';
    public $footerRightTitle;
    public $footerRightUrl;
    public $footerRightType = 'secondary';
    public $options = [];
    public $bodyOptions = [];

    // Backward-compatible aliases.
    public $class;
    public $buttonLeftTitle;
    public $buttonLeftLink;
    public $buttonLeftType;
    public $buttonRightTitle;
    public $buttonRightLink;
    public $buttonRightType;

    public function init()
    {
        if ($this->wrapperClass === null && $this->class !== null) {
            $this->wrapperClass = $this->class;
        }
        if ($this->type === null) {
            $this->type = 'info';
        }
        $this->type = self::normalizeType($this->type);
        if ($this->title === null) {
            $this->title = Yii::t('app', 'Card');
        }
        if ($this->footerLeftTitle === null && $this->buttonLeftTitle !== null) {
            $this->footerLeftTitle = $this->buttonLeftTitle;
            $this->footerLeftUrl = $this->buttonLeftLink !== null ? $this->buttonLeftLink : $this->footerLeftUrl;
            if ($this->buttonLeftType !== null) {
                $this->footerLeftType = str_replace('btn-', '', $this->buttonLeftType);
            }
        }
        if ($this->footerRightTitle === null && $this->buttonRightTitle !== null) {
            $this->footerRightTitle = $this->buttonRightTitle;
            $this->footerRightUrl = $this->buttonRightLink !== null ? $this->buttonRightLink : $this->footerRightUrl;
            if ($this->buttonRightType !== null) {
                $this->footerRightType = str_replace('btn-', '', $this->buttonRightType);
            }
        }
        $this->footerLeftType = self::normalizeButtonType($this->footerLeftType, 'primary');
        $this->footerRightType = self::normalizeButtonType($this->footerRightType, 'secondary');
        parent::init();
    }

    public function run()
    {
        $cardClasses = ['card'];
        if ($this->type !== null) {
            if ($this->outline) {
                $cardClasses[] = 'card-outline';
            }
            $cardClasses[] = 'card-' . $this->type;
        }
        Html::addCssClass($this->options, $cardClasses);

        $card = Html::tag(
            'div',
            $this->renderHeader() . $this->renderBody() . $this->renderFooter(),
            $this->options
        );

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            return Html::tag('div', $card, ['class' => self::sanitizeClass($this->wrapperClass)]);
        }
        return $card;
    }

    protected function renderHeader()
    {
        if ($this->title === null || $this->title === false) {
            return '';
        }

        $titleContent = '';
        if ($this->titleIcon !== null && $this->titleIcon !== '') {
            $iconClass = self::sanitizeClass($this->titleIcon);
            if ($iconClass !== '') {
                $titleContent .= Html::tag('i', '', ['class' => $iconClass . ' mr-1']) . ' ';
            }
        }
        $titleContent .= Html::encode((string) $this->title);
        $title = Html::tag('h3', $titleContent, ['class' => 'card-title']);

        $toolsContent = '';
        if ($this->collapsible) {
            $toolsContent .= Html::button(
                Html::tag('i', '', ['class' => 'fas fa-minus']),
                ['type' => 'button', 'class' => 'btn btn-tool', 'data-card-widget' => 'collapse', 'aria-label' => Yii::t('app', 'Collapse')]
            );
        }
        if ($this->removable) {
            $toolsContent .= Html::button(
                Html::tag('i', '', ['class' => 'fas fa-times']),
                ['type' => 'button', 'class' => 'btn btn-tool', 'data-card-widget' => 'remove', 'aria-label' => Yii::t('app', 'Remove')]
            );
        }
        $tools = $toolsContent === '' ? '' : Html::tag('div', $toolsContent, ['class' => 'card-tools']);

        return Html::tag('div', $title . $tools, ['class' => 'card-header']);
    }

    protected function renderBody()
    {
        $bodyOptions = $this->bodyOptions;
        Html::addCssClass($bodyOptions, 'card-body');

        if ($this->body !== null) {
            if (is_string($this->body)) {
                $content = $this->encodeBody ? Html::encode($this->body) : $this->body;
            } elseif (is_array($this->body) && isset($this->body['dataProvider'], $this->body['columns'])) {
                $content = $this->renderGrid($this->body['dataProvider'], $this->body['columns']);
            } else {
                $content = '';
            }
        } elseif ($this->dataProvider !== null && $this->columns !== null) {
            $content = $this->renderGrid($this->dataProvider, $this->columns);
        } else {
            $content = Html::tag('p', Html::encode(Yii::t('app', 'No content.')), ['class' => 'card-text text-muted']);
        }

        return Html::tag('div', $content, $bodyOptions);
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

    protected static function sanitizeClass($value, $default = '')
    {
        if ($value === null || $value === '') {
            return $default;
        }
        $sanitized = preg_replace('/[^A-Za-z0-9_\- ]/', '', (string) $value);
        return $sanitized !== '' ? $sanitized : $default;
    }

    protected static function normalizeType($type)
    {
        if ($type === null || $type === '') {
            return null;
        }
        $type = strtolower(trim((string) $type));
        foreach (['box-', 'card-'] as $prefix) {
            if (strpos($type, $prefix) === 0) {
                $type = substr($type, strlen($prefix));
            }
        }
        $allowed = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark', 'light'];
        return in_array($type, $allowed, true) ? $type : 'info';
    }

    protected static function normalizeButtonType($type, $default)
    {
        $type = strtolower(str_replace('btn-', '', trim((string) $type)));
        $allowed = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark', 'light', 'link'];
        return in_array($type, $allowed, true) ? $type : $default;
    }
}
