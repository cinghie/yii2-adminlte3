<?php

namespace cinghie\adminlte3\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Navbar user dropdown for AdminLTE 3.
 */
class NavbarUser extends Widget
{
    public $username = '';
    public $userimg;
    public $usercreated;
    public $userbody = false;
    public $userbodyname1;
    public $userbodylink1;
    public $userbodyname2;
    public $userbodylink2;
    public $userbodyname3;
    public $userbodylink3;
    public $userfooter = true;
    public $userfootername1;
    public $userfooterlink1;
    public $userfootername2;
    public $userfooterlink2;
    public $linkOptions = [];
    public $footerLeftOptions = [];
    public $footerRightOptions = ['data-method' => 'post'];

    public function init()
    {
        $defaults = [
            'username' => '', 'userimg' => '', 'usercreated' => '',
            'userbodyname1' => 'Followers', 'userbodylink1' => '#',
            'userbodyname2' => 'Sales', 'userbodylink2' => '#',
            'userbodyname3' => 'Friends', 'userbodylink3' => '#',
            'userfootername1' => 'Profile', 'userfooterlink1' => '#',
            'userfootername2' => 'Sign Out', 'userfooterlink2' => '#',
        ];
        foreach ($defaults as $property => $default) {
            if ($this->{$property} === null) {
                $this->{$property} = $default;
            }
        }
        parent::init();
    }

    public function run()
    {
        $toggleContent = '';
        if ($this->userimg !== '') {
            $toggleContent .= Html::img($this->safeUrl($this->userimg), [
                'class' => 'img-circle elevation-2',
                'alt' => $this->username,
                'style' => 'width: 2rem; height: 2rem; object-fit: cover;',
            ]);
        }
        $toggleContent .= Html::tag('span', Html::encode($this->username), ['class' => 'd-none d-md-inline ml-1']);

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

    protected function renderHeader()
    {
        $content = '';
        if ($this->userimg !== '') {
            $content .= Html::img($this->safeUrl($this->userimg), [
                'class' => 'img-circle elevation-2',
                'alt' => $this->username,
                'style' => 'height: 90px; width: 90px; object-fit: cover;',
            ]);
        }
        $name = Html::encode($this->username);
        if ($this->usercreated !== '') {
            $name .= '<br>' . Html::tag('small', Html::encode($this->usercreated));
        }
        $content .= Html::tag('p', $name);
        return Html::tag('li', $content, ['class' => 'user-header']);
    }

    protected function renderBodyLinks()
    {
        $links = [
            [$this->userbodyname1, $this->userbodylink1],
            [$this->userbodyname2, $this->userbodylink2],
            [$this->userbodyname3, $this->userbodylink3],
        ];
        $columns = '';
        foreach ($links as $link) {
            $columns .= Html::tag('div', Html::a(Html::encode($link[0]), $this->safeUrl($link[1])), ['class' => 'col-4 text-center']);
        }
        return Html::tag('li', Html::tag('div', $columns, ['class' => 'row']), ['class' => 'user-body']);
    }

    protected function renderFooter()
    {
        $leftOptions = array_merge(['class' => 'btn btn-default btn-flat'], $this->footerLeftOptions);
        $rightOptions = array_merge(['class' => 'btn btn-default btn-flat'], $this->footerRightOptions);

        $left = Html::tag('div', Html::a(
            Html::encode($this->userfootername1),
            $this->safeUrl($this->userfooterlink1),
            $leftOptions
        ), ['class' => 'float-left']);

        $right = Html::tag('div', Html::a(
            Html::encode($this->userfootername2),
            $this->safeUrl($this->userfooterlink2),
            $rightOptions
        ), ['class' => 'float-right']);

        return Html::tag('li', $left . $right, ['class' => 'user-footer']);
    }

    protected function safeUrl($url)
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
}
