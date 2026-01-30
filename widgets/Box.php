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
use yii\data\ArrayDataProvider;
use kartik\grid\GridView;

/**
 * Box (Card) widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders a card with optional header (title + tools), body (content or GridView), and footer (buttons).
 * Uses AdminLTE 3 markup: card, card-header, card-tools, card-body, card-footer.
 *
 * @see https://adminlte.io/docs/3.1/components/cards.html
 */
class Box extends Widget
{
    /**
     * @var string|null wrapper CSS class (e.g. 'col-md-6'). Null = no wrapper.
     */
    public $wrapperClass = 'col-lg-6';

    /**
     * @var string|null card type: null, 'primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark'.
     * Adds class "card-{type}" or "card-outline card-{type}" when $outline is true.
     */
    public $type;

    /**
     * @var bool whether to use outline style (card-outline card-{type})
     */
    public $outline = false;

    /**
     * @var string|null card title (header). Null or false = no header.
     */
    public $title;

    /**
     * @var string|null header icon class (e.g. 'fas fa-chart-pie'). Null = no icon.
     */
    public $titleIcon;

    /**
     * @var bool whether to show collapse button in card-tools
     */
    public $collapsible = true;

    /**
     * @var bool whether to show remove button in card-tools
     */
    public $removable = true;

    /**
     * @var string|array|null body content. String = HTML/content. Array = render via GridView (dataProvider + columns).
     * Null = default placeholder content.
     */
    public $body;

    /**
     * @var \yii\data\DataProviderInterface|null for GridView body (when $body is array config or body is null and these are set)
     */
    public $dataProvider;

    /**
     * @var array|null grid columns when using GridView body
     */
    public $columns;

    /**
     * @var string|null footer left button label
     */
    public $footerLeftTitle;

    /**
     * @var string|array|null footer left button URL (array = Url::to())
     */
    public $footerLeftUrl;

    /**
     * @var string footer left button style: 'primary', 'secondary', 'success', etc.
     */
    public $footerLeftType = 'primary';

    /**
     * @var string|null footer right button label
     */
    public $footerRightTitle;

    /**
     * @var string|array|null footer right button URL
     */
    public $footerRightUrl;

    /**
     * @var string footer right button style
     */
    public $footerRightType = 'secondary';

    /**
     * @var array HTML options for the card container
     */
    public $options = [];

    /**
     * @var array HTML options for the card body
     */
    public $bodyOptions = [];

    /** @var string|null backward compatibility: wrapper class (alias for $wrapperClass) */
    public $class;

    /** @var string|null backward compatibility: left button label */
    public $buttonLeftTitle;
    /** @var string|null backward compatibility: left button URL */
    public $buttonLeftLink;
    /** @var string|null backward compatibility: left button type */
    public $buttonLeftType;
    /** @var string|null backward compatibility: right button label */
    public $buttonRightTitle;
    /** @var string|null backward compatibility: right button URL */
    public $buttonRightLink;
    /** @var string|null backward compatibility: right button type */
    public $buttonRightType;

    /** @inheritdoc */
    public function init()
    {
        if ($this->wrapperClass === null && $this->class !== null) {
            $this->wrapperClass = $this->class;
        }
        if ($this->type === null) {
            $this->type = 'info';
        }
        if (is_string($this->type) && strpos($this->type, 'box-') === 0) {
            $this->type = substr($this->type, 4);
        }
        if ($this->title === null) {
            $this->title = Yii::t('app', 'Card');
        }
        if ($this->footerLeftTitle === null && $this->buttonLeftTitle !== null) {
            $this->footerLeftTitle = $this->buttonLeftTitle;
            $this->footerLeftUrl = $this->buttonLeftLink ?? $this->footerLeftUrl;
            if ($this->buttonLeftType !== null) {
                $this->footerLeftType = str_replace('btn-', '', $this->buttonLeftType);
            }
        }
        if ($this->footerRightTitle === null && $this->buttonRightTitle !== null) {
            $this->footerRightTitle = $this->buttonRightTitle;
            $this->footerRightUrl = $this->buttonRightLink ?? $this->footerRightUrl;
            if ($this->buttonRightType !== null) {
                $this->footerRightType = str_replace('btn-', '', $this->buttonRightType);
            }
        }
        parent::init();
    }

    /** @return string */
    public function run()
    {
        $cardClass = ['card'];
        if ($this->type !== null && $this->type !== '') {
            $cardClass[] = $this->outline ? 'card-outline' : '';
            $cardClass[] = 'card-' . $this->type;
        }
        Html::addCssClass($this->options, array_filter($cardClass));

        $content = $this->renderHeader();
        $content .= $this->renderBody();
        $content .= $this->renderFooter();

        $card = Html::tag('div', $content, $this->options);

        if ($this->wrapperClass !== null && $this->wrapperClass !== '') {
            return Html::tag('div', $card, ['class' => $this->wrapperClass]);
        }
        return $card;
    }

    /**
     * @return string
     */
    protected function renderHeader()
    {
        if ($this->title === null || $this->title === false) {
            return '';
        }

        $titleContent = '';
        if ($this->titleIcon !== null && $this->titleIcon !== '') {
            $titleContent .= Html::tag('i', '', ['class' => $this->titleIcon . ' mr-1']) . ' ';
        }
        $titleContent .= Html::encode($this->title);
        $title = Html::tag('h3', $titleContent, ['class' => 'card-title']);

        $tools = '';
        if ($this->collapsible || $this->removable) {
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
            $tools = Html::tag('div', $toolsContent, ['class' => 'card-tools']);
        }

        $headerContent = $title . $tools;
        return Html::tag('div', $headerContent, ['class' => 'card-header']);
    }

    /**
     * @return string
     */
    protected function renderBody()
    {
        $bodyOptions = array_merge(['class' => 'card-body'], $this->bodyOptions);
        $content = '';

        if ($this->body !== null) {
            if (is_string($this->body)) {
                $content = $this->body;
            } elseif (is_array($this->body) && isset($this->body['dataProvider'], $this->body['columns'])) {
                $content = GridView::widget([
                    'dataProvider' => $this->body['dataProvider'],
                    'columns' => $this->body['columns'],
                    'hover' => true,
                    'panel' => false,
                    'responsiveWrap' => true,
                    'summary' => '',
                ]);
            }
        } elseif ($this->dataProvider !== null && $this->columns !== null) {
            $content = GridView::widget([
                'dataProvider' => $this->dataProvider,
                'columns' => $this->columns,
                'hover' => true,
                'panel' => false,
                'responsiveWrap' => true,
                'summary' => '',
            ]);
        } else {
            $content = Html::tag('p', Html::encode(Yii::t('app', 'No content.')), ['class' => 'card-text text-muted']);
        }

        return Html::tag('div', $content, $bodyOptions);
    }

    /**
     * @return string
     */
    protected function renderFooter()
    {
        $hasLeft = $this->footerLeftTitle !== null && $this->footerLeftTitle !== '' && $this->footerLeftUrl !== null;
        $hasRight = $this->footerRightTitle !== null && $this->footerRightTitle !== '' && $this->footerRightUrl !== null;
        if (!$hasLeft && !$hasRight) {
            return '';
        }

        $left = '';
        if ($hasLeft) {
            $url = is_array($this->footerLeftUrl) ? Url::to($this->footerLeftUrl) : $this->footerLeftUrl;
            $left = Html::a(
                Html::encode($this->footerLeftTitle),
                $url,
                ['class' => 'btn btn-sm btn-' . $this->footerLeftType]
            );
        }
        $right = '';
        if ($hasRight) {
            $url = is_array($this->footerRightUrl) ? Url::to($this->footerRightUrl) : $this->footerRightUrl;
            $right = Html::a(
                Html::encode($this->footerRightTitle),
                $url,
                ['class' => 'btn btn-sm btn-' . $this->footerRightType . ' float-right']
            );
        }

        return Html::tag('div', $left . $right, ['class' => 'card-footer']);
    }
}
