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
use yii\widgets\Breadcrumbs as BaseBreadcrumbs;

/**
 * Breadcrumbs widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders breadcrumbs with AdminLTE 3 markup: <ol class="breadcrumb float-sm-right">
 * and <li class="breadcrumb-item"> / <li class="breadcrumb-item active">.
 *
 * @see https://adminlte.io/docs/3.1/components/breadcrumb.html
 */
class Breadcrumbs extends BaseBreadcrumbs
{
    /**
     * @var string the name of the breadcrumb container tag (AdminLTE 3 uses ol)
     */
    public $tag = 'ol';

    /**
     * @var array the HTML attributes for the breadcrumb container tag
     */
    public $options = ['class' => 'breadcrumb float-sm-right'];

    /**
     * @var string the template used to render each inactive item (Bootstrap 4 breadcrumb-item)
     */
    public $itemTemplate = "<li class=\"breadcrumb-item\">{link}</li>\n";

    /**
     * @var string the template used to render each active item (Bootstrap 4 breadcrumb-item active)
     */
    public $activeItemTemplate = "<li class=\"breadcrumb-item active\" aria-current=\"page\">{link}</li>\n";

}
