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

use kartik\grid\DataColumn as KartikDataColumn;
use yii\helpers\Html;

/**
 * DataColumn for AdminLTE 3 / Bootstrap 4 tables.
 *
 * Extends Kartik DataColumn: adds sorting classes to header without mutating
 * the original headerOptions (uses merged options for output).
 */
class DataColumn extends KartikDataColumn
{
    /**
     * @return string
     */
    public function renderHeaderCell()
    {
        $provider = $this->grid->dataProvider;
        $options = array_merge([], $this->headerOptions);

        if ($this->attribute !== null && $this->enableSorting && ($sort = $provider->getSort()) !== false && $sort->hasAttribute($this->attribute)) {
            $direction = $sort->getAttributeOrder($this->attribute);
            $sortClass = $direction !== null
                ? 'sorting_' . ($direction === SORT_DESC ? 'desc' : 'asc')
                : 'sorting';
            Html::addCssClass($options, $sortClass);
        }

        return Html::tag('th', $this->renderHeaderCellContent(), $options);
    }
}
