<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\DetailView;
use cinghie\adminlte3\widgets\GridView;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;

final class FormatterIsolationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Yii::$app->formatter->nullDisplay = '(global-null)';
    }

    public function testGridViewDoesNotMutateApplicationFormatter(): void
    {
        GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => [['name' => null]],
                'pagination' => false,
            ]),
            'columns' => ['name'],
            'panel' => false,
            'pjax' => false,
        ]);

        $this->assertSame('(global-null)', Yii::$app->formatter->nullDisplay);
    }

    public function testDetailViewDoesNotMutateApplicationFormatter(): void
    {
        $model = new DynamicModel(['name' => null]);

        DetailView::widget([
            'model' => $model,
            'attributes' => ['name'],
            'panel' => false,
        ]);

        $this->assertSame('(global-null)', Yii::$app->formatter->nullDisplay);
    }
}
