<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use Yii;
use yii\helpers\Html;

/**
 * Legacy AdminLTE 3 card widget.
 *
 * Box is a backward-compatible facade over {@see Card}. New code should use
 * Card directly; Box keeps its historical GridView and footer-button API.
 *
 * @deprecated Prefer {@see Card}.
 */
class Box extends Card
{
    /** @var string|null Historical wrapper grid classes. */
    public $wrapperClass = 'col-lg-6';

    /** @var string|null Historical default contextual type. */
    public $type = self::TYPE_INFO;

    /** @var bool Historical default: show collapse tool. */
    public $collapsible = true;

    /** @var bool Historical default: show remove tool. */
    public $removable = true;

    /** @var mixed Optional GridView data provider. */
    public $dataProvider;

    /** @var array|null Optional GridView column configuration. */
    public $columns;

    /** @var string|null Left footer action label. */
    public $footerLeftTitle;

    /** @var string|array|null Left footer action target. */
    public $footerLeftUrl;

    /** @var string Left footer Bootstrap button type. */
    public $footerLeftType = 'primary';

    /** @var string|null Right footer action label. */
    public $footerRightTitle;

    /** @var string|array|null Right footer action target. */
    public $footerRightUrl;

    /** @var string Right footer Bootstrap button type. */
    public $footerRightType = 'secondary';

    /** @var string|null Legacy alias for {@see $wrapperClass}. */
    public $class;

    /** @var string|null Legacy alias for {@see $footerLeftTitle}. */
    public $buttonLeftTitle;

    /** @var string|array|null Legacy alias for {@see $footerLeftUrl}. */
    public $buttonLeftLink;

    /** @var string|null Legacy alias for {@see $footerLeftType}. */
    public $buttonLeftType;

    /** @var string|null Legacy alias for {@see $footerRightTitle}. */
    public $buttonRightTitle;

    /** @var string|array|null Legacy alias for {@see $footerRightUrl}. */
    public $buttonRightLink;

    /** @var string|null Legacy alias for {@see $footerRightType}. */
    public $buttonRightType;

    /**
     * Box intentionally skips Card's output-buffer initialization because the
     * legacy widget API is widget()-only. Rendering itself delegates to Card.
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

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $bodyHtml = $this->resolveLegacyBody();
        Html::addCssClass($this->options, $this->resolveCardCssClasses());

        $html = $this->renderHeader();
        $html .= parent::renderBody($bodyHtml);
        $html .= $this->renderFooter();

        $card = Html::tag('div', $html, $this->options);
        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            return Html::tag('div', $card, ['class' => self::sanitizeClass($this->wrapperClass)]);
        }

        return $card;
    }

    /**
     * Resolves legacy body modes into encoded/trusted HTML.
     *
     * @return string
     */
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

    /**
     * Renders the package-local GridView used by the historical Box API.
     *
     * @param mixed $dataProvider Grid data provider.
     * @param array $columns Grid column configuration.
     * @return string
     */
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

    /**
     * Renders legacy left/right footer action buttons.
     *
     * @return string
     */
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

    /**
     * Normalizes a footer URL using the package-wide URL policy.
     *
     * @param mixed $url String URL or Yii route array.
     * @return string
     */
    protected function normalizeUrl($url)
    {
        return SafeHtml::linkUrl($url, '#');
    }

    /**
     * Normalizes a Bootstrap button contextual type.
     *
     * @param mixed $type Candidate type.
     * @param string $default Fallback type.
     * @return string
     */
    protected static function normalizeButtonType($type, $default)
    {
        $type = strtolower(str_replace('btn-', '', trim((string) $type)));
        $allowed = ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark', 'light', 'link'];

        return in_array($type, $allowed, true) ? $type : $default;
    }
}
