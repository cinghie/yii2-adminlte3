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

/**
 * Footer widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders main-footer: copyright (with optional link) and version block.
 * Markup: footer.main-footer > float-right version + strong copyright.
 *
 * @see https://adminlte.io/docs/3.1/layout/footer.html
 */
class Footer extends Widget
{
    /**
     * @var string|null Start year for copyright (e.g. '2014'). Null = '2014'.
     */
    public $copyright_date_start;

    /**
     * @var string|null End year for copyright (e.g. current year). Null = date('Y').
     */
    public $copyright_date_end;

    /**
     * @var string|null URL for copyright link. Null = Almsaeed Studio link.
     */
    public $copyright_link;

    /**
     * @var string|null Copyright text (link label). Null = 'Almsaeed Studio'.
     */
    public $copyright_text;

    /**
     * @var string|null Version string (e.g. '3.2.0'). Null = '2.3.1'.
     */
    public $version;

    /**
     * @var string|null Text after copyright (e.g. 'All rights reserved.'). Null = 'All rights reserved.'
     */
    public $rights_text = 'All rights reserved.';

    /**
     * @var array HTML options for the footer element
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->copyright_date_start === null) {
            $this->copyright_date_start = '2014';
        }
        if ($this->copyright_date_end === null) {
            $this->copyright_date_end = date('Y');
        }
        if ($this->copyright_link === null) {
            $this->copyright_link = 'https://adminlte.io';
        }
        if ($this->copyright_text === null) {
            $this->copyright_text = 'AdminLTE.io';
        }
        if ($this->version === null) {
            $this->version = '3.2.0';
        }

        Html::addCssClass($this->options, 'main-footer');
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $versionBlock = '';
        if ($this->version !== null && $this->version !== '') {
            $versionBlock = Html::tag(
                'div',
                Html::tag('b', 'Version') . ' ' . Html::encode($this->version),
                ['class' => 'float-right d-none d-sm-inline-block']
            );
        }

        $link = Html::a(
            Html::encode($this->copyright_text),
            $this->copyright_link,
            ['target' => '_blank', 'rel' => 'noopener noreferrer']
        );
        $copyright = Html::tag('strong', 'Copyright &copy; ' . Html::encode($this->copyright_date_start) . '-' . Html::encode($this->copyright_date_end) . ' ' . $link . '.');
        $rights = $this->rights_text !== null && $this->rights_text !== '' ? ' ' . Html::encode($this->rights_text) : '';

        return Html::tag('footer', $versionBlock . $copyright . $rights, $this->options);
    }
}
