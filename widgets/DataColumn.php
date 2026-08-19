<?php

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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // Avoid Bootstrap primary link color on sortable headers (AdminLTE / DataTables look)
        Html::addCssClass($this->sortLinkOptions, 'text-dark');
    }

    /**
     * @return string
     */
    public function renderHeaderCell()
    {
        $provider = $this->grid->dataProvider;
        $options = array_merge([], $this->headerOptions);

        if ($this->attribute !== null && $this->enableSorting && ($sort = $provider->getSort()) !== false && $sort->hasAttribute($this->attribute)) {
            $direction = $sort->getAttributeOrder($this->attribute);
            if ($direction !== null) {
                Html::addCssClass($options, $direction === SORT_DESC ? 'sorting_desc' : 'sorting_asc');
            } else {
                Html::addCssClass($options, 'sorting');
            }
        }

        return Html::tag('th', $this->renderHeaderCellContent(), $options);
    }
}
