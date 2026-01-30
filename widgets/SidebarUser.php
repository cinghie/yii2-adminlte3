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

/**
 * SidebarUser widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders the user panel in the sidebar (avatar + name).
 * When the sidebar is collapsed (sidebar-mini / sidebar-no-expand), AdminLTE 3
 * hides the .user-panel > .info block and shows only the image centered.
 * Uses transition classes so the collapse/expand is smooth.
 *
 * @see https://adminlte.io/docs/3.1/components/main-sidebar.html
 */
class SidebarUser extends Widget
{
    /**
     * @var string Username or display name
     */
    public $username;

    /**
     * @var string URL of the user avatar image
     */
    public $userimg;

    /**
     * @var string URL for the username link (use '#' or null for no link)
     */
    public $userUrl = '#';

    /**
     * @var string Alt text for the user image
     */
    public $imageAlt;

    /**
     * @var array HTML attributes for the wrapper div (user-panel)
     */
    public $options = [];

    /**
     * @var array HTML attributes for the image
     */
    public $imageOptions = [];

    /**
     * @var array HTML attributes for the username link
     */
    public $linkOptions = [];

    /**
     * @var string Default avatar URL when none provided
     */
    public static $defaultAvatarUrl = 'https://cdn0.iconfinder.com/data/icons/user-pictures/100/matureman1-2-128.png';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->username === null) {
            $this->username = Yii::t('app', 'User');
        }
        if ($this->userimg === null) {
            $this->userimg = static::$defaultAvatarUrl;
        }
        if ($this->imageAlt === null) {
            $this->imageAlt = $this->username;
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $imageOptions = array_merge([
            'class' => 'img-circle elevation-2',
            'alt' => Html::encode($this->imageAlt),
        ], $this->imageOptions);

        $img = Html::img($this->userimg, $imageOptions);
        $imageBlock = Html::tag('div', $img, ['class' => 'image']);

        $linkOptions = array_merge(['class' => 'd-block'], $this->linkOptions);
        $linkContent = Html::encode($this->username);
        $link = $this->userUrl !== null && $this->userUrl !== ''
            ? Html::a($linkContent, $this->userUrl, $linkOptions)
            : Html::tag('span', $linkContent, $linkOptions);
        $infoBlock = Html::tag('div', $link, ['class' => 'info']);

        $options = array_merge([
            'class' => 'user-panel mt-3 pb-3 mb-3 d-flex',
        ], $this->options);

        return Html::tag('div', $imageBlock . $infoBlock, $options);
    }
}
