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

use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * NavbarUser widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders the user dropdown in the navbar: image + username, dropdown with header (image + name + date),
 * optional body links (e.g. Followers, Sales, Friends), and footer (Profile, Sign out).
 * Uses AdminLTE 3 user-menu structure: .user-menu > .dropdown-menu > li.user-header, li.user-body, li.user-footer.
 *
 * @see https://adminlte.io/docs/3.1/components/navbar.html
 */
class NavbarUser extends Widget
{
    /**
     * @var string Username (navbar toggle and header)
     */
    public $username = '';

    /**
     * @var string|null URL of user image
     */
    public $userimg;

    /**
     * @var string|null Subtitle in header (e.g. "Member since Nov. 2012")
     */
    public $usercreated;

    /**
     * @var bool Whether to show the body block (three links)
     */
    public $userbody = false;

    /**
     * @var string|null Body link 1 label
     */
    public $userbodyname1;

    /**
     * @var string|array|null Body link 1 URL
     */
    public $userbodylink1;

    /**
     * @var string|null Body link 2 label
     */
    public $userbodyname2;

    /**
     * @var string|array|null Body link 2 URL
     */
    public $userbodylink2;

    /**
     * @var string|null Body link 3 label
     */
    public $userbodyname3;

    /**
     * @var string|array|null Body link 3 URL
     */
    public $userbodylink3;

    /**
     * @var bool Whether to show the footer (Profile, Sign out)
     */
    public $userfooter = true;

    /**
     * @var string|null Footer left button label (e.g. "Profile")
     */
    public $userfootername1;

    /**
     * @var string|array|null Footer left button URL
     */
    public $userfooterlink1;

    /**
     * @var string|null Footer right button label (e.g. "Sign Out")
     */
    public $userfootername2;

    /**
     * @var string|array|null Footer right button URL
     */
    public $userfooterlink2;

    /**
     * @var array HTML options for the dropdown toggle link
     */
    public $linkOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->username === null) {
            $this->username = '';
        }
        if ($this->userimg === null) {
            $this->userimg = '';
        }
        if ($this->usercreated === null) {
            $this->usercreated = '';
        }
        if ($this->userbodyname1 === null) {
            $this->userbodyname1 = 'Followers';
        }
        if ($this->userbodylink1 === null) {
            $this->userbodylink1 = '#';
        }
        if ($this->userbodyname2 === null) {
            $this->userbodyname2 = 'Sales';
        }
        if ($this->userbodylink2 === null) {
            $this->userbodylink2 = '#';
        }
        if ($this->userbodyname3 === null) {
            $this->userbodyname3 = 'Friends';
        }
        if ($this->userbodylink3 === null) {
            $this->userbodylink3 = '#';
        }
        if ($this->userfootername1 === null) {
            $this->userfootername1 = 'Profile';
        }
        if ($this->userfooterlink1 === null) {
            $this->userfooterlink1 = '#';
        }
        if ($this->userfootername2 === null) {
            $this->userfootername2 = 'Sign Out';
        }
        if ($this->userfooterlink2 === null) {
            $this->userfooterlink2 = '#';
        }

        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $toggleContent = '';
        if ($this->userimg !== null && $this->userimg !== '') {
            $toggleContent .= Html::img($this->userimg, [
                'class' => 'img-circle elevation-2',
                'alt' => Html::encode($this->username),
                'style' => 'width: 2rem; height: 2rem; object-fit: cover;',
            ]);
        }
        $toggleContent .= Html::tag('span', Html::encode($this->username), ['class' => 'd-none d-md-inline ml-1']);

        $linkOptions = array_merge([
            'class' => 'nav-link',
            'data-toggle' => 'dropdown',
            'href' => '#',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false',
        ], $this->linkOptions);

        $toggle = Html::a($toggleContent, '#', $linkOptions);

        $menuItems = [];

        $headerContent = '';
        if ($this->userimg !== null && $this->userimg !== '') {
            $headerContent .= Html::img($this->userimg, [
                'class' => 'img-circle elevation-2',
                'alt' => Html::encode($this->username),
                'style' => 'height: 90px; width: 90px; object-fit: cover;',
            ]);
        }
        $headerContent .= Html::tag('p', Html::encode($this->username) . ($this->usercreated !== '' ? '<br><small>' . Html::encode($this->usercreated) . '</small>' : ''));
        $menuItems[] = Html::tag('li', $headerContent, ['class' => 'user-header']);

        if ($this->userbody) {
            $bodyUrl1 = is_array($this->userbodylink1) ? Url::to($this->userbodylink1) : $this->userbodylink1;
            $bodyUrl2 = is_array($this->userbodylink2) ? Url::to($this->userbodylink2) : $this->userbodylink2;
            $bodyUrl3 = is_array($this->userbodylink3) ? Url::to($this->userbodylink3) : $this->userbodylink3;
            $col1 = Html::tag('div', Html::a(Html::encode($this->userbodyname1), $bodyUrl1), ['class' => 'col-4 text-center']);
            $col2 = Html::tag('div', Html::a(Html::encode($this->userbodyname2), $bodyUrl2), ['class' => 'col-4 text-center']);
            $col3 = Html::tag('div', Html::a(Html::encode($this->userbodyname3), $bodyUrl3), ['class' => 'col-4 text-center']);
            $bodyContent = Html::tag('div', $col1 . $col2 . $col3, ['class' => 'row']);
            $menuItems[] = Html::tag('li', $bodyContent, ['class' => 'user-body']);
        }

        if ($this->userfooter) {
            $footerUrl1 = is_array($this->userfooterlink1) ? Url::to($this->userfooterlink1) : $this->userfooterlink1;
            $footerUrl2 = is_array($this->userfooterlink2) ? Url::to($this->userfooterlink2) : $this->userfooterlink2;
            $footerContent = Html::tag('div', Html::a(Html::encode($this->userfootername1), $footerUrl1, ['class' => 'btn btn-default btn-flat']), ['class' => 'float-left']);
            $footerContent .= Html::tag('div', Html::a(Html::encode($this->userfootername2), $footerUrl2, ['class' => 'btn btn-default btn-flat']), ['class' => 'float-right']);
            $menuItems[] = Html::tag('li', $footerContent, ['class' => 'user-footer']);
        }

        $menu = Html::tag('ul', implode("\n", $menuItems), [
            'class' => 'dropdown-menu dropdown-menu-right',
        ]);

        return Html::tag('li', $toggle . $menu, ['class' => 'nav-item dropdown user-menu']);
    }
}
