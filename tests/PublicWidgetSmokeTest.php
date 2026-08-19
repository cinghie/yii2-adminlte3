<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Alert;
use cinghie\adminlte3\widgets\Box;
use cinghie\adminlte3\widgets\Breadcrumbs;
use cinghie\adminlte3\widgets\Card;
use cinghie\adminlte3\widgets\ContentHeader;
use cinghie\adminlte3\widgets\DataColumn;
use cinghie\adminlte3\widgets\DetailView;
use cinghie\adminlte3\widgets\Footer;
use cinghie\adminlte3\widgets\GridView;
use cinghie\adminlte3\widgets\InfoBox;
use cinghie\adminlte3\widgets\Invoice;
use cinghie\adminlte3\widgets\MailboxRead;
use cinghie\adminlte3\widgets\NavbarButton;
use cinghie\adminlte3\widgets\NavbarLogo;
use cinghie\adminlte3\widgets\NavbarUser;
use cinghie\adminlte3\widgets\NavTabs;
use cinghie\adminlte3\widgets\SidebarMenu;
use cinghie\adminlte3\widgets\SidebarSearch;
use cinghie\adminlte3\widgets\SidebarToggle;
use cinghie\adminlte3\widgets\SidebarUser;
use cinghie\adminlte3\widgets\SmallBox;
use cinghie\adminlte3\widgets\Timeline;
use PHPUnit\Framework\TestCase;
use Yii;
use yii\base\Module;
use yii\web\Controller;

/**
 * Smoke and compatibility coverage across the package's public widget surface.
 */
final class PublicWidgetSmokeTest extends TestCase
{
    public function testSimplePublicWidgetsRender(): void
    {
        $renders = [
            Card::widget(['title' => 'Card', 'body' => 'Body']),
            Box::widget(['title' => 'Box', 'body' => 'Body']),
            Breadcrumbs::widget(['links' => [['label' => 'Page']]]),
            ContentHeader::widget(['title' => 'Dashboard', 'showBreadcrumbsWhenEmpty' => false]),
            Footer::widget(['copyright_text' => 'AdminLTE']),
            InfoBox::widget(['text' => 'Users', 'number' => '10']),
            NavbarButton::widget(['title' => 'Open', 'url' => '/site/index']),
            NavbarLogo::widget(['brandText' => 'Application', 'url' => '/']),
            NavbarUser::widget([]),
            NavTabs::widget(['items' => [['label' => 'Tab', 'content' => 'Content', 'id' => 'tab-one']]]),
            SidebarMenu::widget(['route' => 'site/index', 'items' => [['label' => 'Home', 'url' => ['/site/index']]]]),
            SidebarSearch::widget(['placeholder' => 'Search']),
            SidebarToggle::widget([]),
            SidebarUser::widget(['username' => 'User', 'userimg' => '/avatar.png']),
            SmallBox::widget(['title' => '10', 'subtitle' => 'Orders']),
            Timeline::widget(['days' => [], 'items' => []]),
        ];

        foreach ($renders as $html) {
            self::assertIsString($html);
        }

        self::assertStringContainsString('card', $renders[0]);
        self::assertStringContainsString('card', $renders[1]);
        self::assertStringContainsString('content-header', $renders[3]);
        self::assertStringContainsString('main-footer', $renders[4]);
        self::assertStringContainsString('info-box', $renders[5]);
        self::assertStringContainsString('small-box', $renders[14]);
    }

    public function testAlertRendersKnownFlashType(): void
    {
        Yii::$app->session->setFlash('success', 'Saved');
        $html = Alert::widget(['removeFlashAfterDisplay' => false]);

        self::assertStringContainsString('alert-success', $html);
        self::assertStringContainsString('Saved', $html);
    }

    public function testComplexPublicClassesAreLoadable(): void
    {
        foreach ([DataColumn::class, DetailView::class, GridView::class, Invoice::class, MailboxRead::class] as $class) {
            self::assertTrue(class_exists($class), $class . ' must remain autoloadable');
        }
    }

    public function testBoxRemainsACardCompatibilityFacade(): void
    {
        self::assertTrue(is_subclass_of(Box::class, Card::class));

        // `class` is a historical constructor alias; Widget::widget() reserves
        // the `class` configuration key for Yii object creation, so rendering
        // callers should use the public `wrapperClass` property.
        $legacy = new Box(['class' => 'col-12']);
        self::assertSame('col-12', $legacy->wrapperClass);

        $html = Box::widget([
            'wrapperClass' => 'col-12',
            'buttonLeftTitle' => 'Back',
            'buttonLeftLink' => ['/site/index'],
            'buttonLeftType' => 'btn-warning',
            'body' => 'Legacy body',
        ]);

        self::assertStringContainsString('col-12', $html);
        self::assertStringContainsString('btn-warning', $html);
        self::assertStringContainsString('Legacy body', $html);
    }

    public function testSidebarDefaultActionMatchesOnlyTrailingSegment(): void
    {
        $oldController = Yii::$app->controller;
        $controller = new Controller('site', Yii::$app);
        $controller->defaultAction = 'index';
        Yii::$app->controller = $controller;

        try {
            $html = SidebarMenu::widget([
                'route' => 'site/index',
                'items' => [
                    ['label' => 'Site', 'url' => ['/site']],
                    ['label' => 'False positive', 'url' => ['/index-tools/site']],
                ],
            ]);
        } finally {
            Yii::$app->controller = $oldController;
        }

        self::assertMatchesRegularExpression(
            '/<li class="nav-item active">.*?<p>Site<\/p>.*?<\/li>/s',
            $html
        );
        self::assertDoesNotMatchRegularExpression(
            '/<li class="nav-item active">.*?<p>False positive<\/p>.*?<\/li>/s',
            $html
        );
        self::assertSame(1, substr_count($html, 'nav-link active'));
    }

    public function testSidebarModuleDefaultRouteAndQueryParameters(): void
    {
        $oldController = Yii::$app->controller;
        $module = new Module('admin', Yii::$app);
        $module->defaultRoute = 'dashboard';
        $controller = new Controller('dashboard', $module);
        $controller->defaultAction = 'index';
        Yii::$app->controller = $controller;

        try {
            $html = SidebarMenu::widget([
                'route' => 'admin/dashboard/index',
                'params' => ['scope' => 'all'],
                'items' => [
                    ['label' => 'Admin', 'url' => ['/admin']],
                    ['label' => 'Scoped', 'url' => ['/admin/dashboard/index', 'scope' => 'mine']],
                ],
            ]);
        } finally {
            Yii::$app->controller = $oldController;
        }

        self::assertSame(1, substr_count($html, 'nav-link active'));
        self::assertStringContainsString('Admin', $html);
        self::assertStringContainsString('Scoped', $html);
    }

    public function testSidebarParentActivationInvisibleItemsAndHeaders(): void
    {
        $html = SidebarMenu::widget([
            'route' => 'reports/view',
            'items' => [
                ['label' => 'Section', 'options' => ['class' => 'nav-header']],
                [
                    'label' => 'Reports',
                    'url' => '#',
                    'items' => [
                        ['label' => 'Current', 'url' => ['/reports/view']],
                        ['label' => 'Hidden', 'url' => ['/reports/hidden'], 'visible' => false],
                    ],
                ],
            ],
        ]);

        self::assertStringContainsString('nav-header', $html);
        self::assertStringContainsString('menu-open', $html);
        self::assertStringContainsString('Current', $html);
        self::assertStringNotContainsString('Hidden', $html);
    }
}
