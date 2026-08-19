<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * Renders the AdminLTE 3 navbar user dropdown.
 *
 * Link and image targets are normalized before output. The right footer action
 * defaults to Yii POST semantics so logout-style actions do not mutate state
 * through GET requests.
 */
class NavbarUser extends Widget
{
    /** @var string User display name. */
    public $username = '';

    /** @var string|null User image URL/path. */
    public $userimg;

    /** @var string|null Secondary user metadata, typically account creation text. */
    public $usercreated;

    /** @var bool Whether to render the optional three-column body links. */
    public $userbody = false;

    public $userbodyname1;
    public $userbodylink1;
    public $userbodyname2;
    public $userbodylink2;
    public $userbodyname3;
    public $userbodylink3;

    /** @var bool Whether to render footer actions. */
    public $userfooter = true;

    public $userfootername1;
    public $userfooterlink1;
    public $userfootername2;
    public $userfooterlink2;

    /** @var array HTML options for the dropdown toggle link. */
    public $linkOptions = [];

    /** @var array HTML options for the left footer action. */
    public $footerLeftOptions = [];

    /** @var array HTML options for the right footer action. */
    public $footerRightOptions = ['data-method' => 'post'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $defaults = [
            'username' => '',
            'userimg' => '',
            'usercreated' => '',
            'userbodyname1' => 'Followers',
            'userbodylink1' => '#',
            'userbodyname2' => 'Sales',
            'userbodylink2' => '#',
            'userbodyname3' => 'Friends',
            'userbodylink3' => '#',
            'userfootername1' => 'Profile',
            'userfooterlink1' => '#',
            'userfootername2' => 'Sign Out',
            'userfooterlink2' => '#',
        ];

        foreach ($defaults as $property => $default) {
            if ($this->{$property} === null) {
                $this->{$property} = $default;
            }
        }

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $toggleContent = '';
        if ($this->userimg !== '') {
            $toggleContent .= Html::img($this->safeUrl($this->userimg), [
                'class' => 'img-circle elevation-2 cinghie-navbar-user-avatar',
                'alt' => $this->username,
            ]);
        }
        $toggleContent .= Html::tag(
            'span',
            Html::encode($this->username),
            ['class' => 'd-none d-md-inline ml-1']
        );

        $toggleOptions = array_merge([
            'class' => 'nav-link',
            'data-toggle' => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false',
        ], $this->linkOptions);
        $toggle = Html::a($toggleContent, '#', $toggleOptions);

        $items = [$this->renderHeader()];
        if ($this->userbody) {
            $items[] = $this->renderBodyLinks();
        }
        if ($this->userfooter) {
            $items[] = $this->renderFooter();
        }

        $menu = Html::tag('ul', implode("\n", $items), ['class' => 'dropdown-menu dropdown-menu-right']);

        return Html::tag('li', $toggle . $menu, ['class' => 'nav-item dropdown user-menu']);
    }

    /**
     * Renders the dropdown header.
     *
     * @return string
     */
    protected function renderHeader()
    {
        $content = '';
        if ($this->userimg !== '') {
            $content .= Html::img($this->safeUrl($this->userimg), [
                'class' => 'img-circle elevation-2 cinghie-navbar-user-header-avatar',
                'alt' => $this->username,
            ]);
        }

        $name = Html::encode($this->username);
        if ($this->usercreated !== '') {
            $name .= '<br>' . Html::tag('small', Html::encode($this->usercreated));
        }
        $content .= Html::tag('p', $name);

        return Html::tag('li', $content, ['class' => 'user-header']);
    }

    /**
     * Renders the optional three-column body links.
     *
     * @return string
     */
    protected function renderBodyLinks()
    {
        $links = [
            [$this->userbodyname1, $this->userbodylink1],
            [$this->userbodyname2, $this->userbodylink2],
            [$this->userbodyname3, $this->userbodylink3],
        ];

        $columns = '';
        foreach ($links as $link) {
            $columns .= Html::tag(
                'div',
                Html::a(Html::encode($link[0]), $this->safeUrl($link[1])),
                ['class' => 'col-4 text-center']
            );
        }

        return Html::tag('li', Html::tag('div', $columns, ['class' => 'row']), ['class' => 'user-body']);
    }

    /**
     * Renders footer actions.
     *
     * @return string
     */
    protected function renderFooter()
    {
        $leftOptions = array_merge(['class' => 'btn btn-default btn-flat'], $this->footerLeftOptions);
        $rightOptions = array_merge(['class' => 'btn btn-default btn-flat'], $this->footerRightOptions);

        $left = Html::tag(
            'div',
            Html::a(
                Html::encode($this->userfootername1),
                $this->safeUrl($this->userfooterlink1),
                $leftOptions
            ),
            ['class' => 'float-left']
        );

        $right = Html::tag(
            'div',
            Html::a(
                Html::encode($this->userfootername2),
                $this->safeUrl($this->userfooterlink2),
                $rightOptions
            ),
            ['class' => 'float-right']
        );

        return Html::tag('li', $left . $right, ['class' => 'user-footer']);
    }

    /**
     * Normalizes navbar-user link and image targets.
     *
     * @param mixed $url String URL or Yii route array.
     * @return string
     */
    protected function safeUrl($url)
    {
        return SafeHtml::linkUrl($url, '#');
    }
}
