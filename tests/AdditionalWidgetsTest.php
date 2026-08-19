<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Calendar;
use cinghie\adminlte3\widgets\ChartJS;
use cinghie\adminlte3\widgets\Error404;
use cinghie\adminlte3\widgets\Error500;
use yii\helpers\Json;

/**
 * Smoke, structure, rendering-safety, and AdminLTE visual-contract coverage.
 */
final class AdditionalWidgetsTest extends HtmlDomTestCase
{
    public function testAllAdditionalPublicWidgetsRender(): void
    {
        $renders = [
            Calendar::widget(['registerAssets' => false]),
            ChartJS::widget(['registerAssets' => false]),
            Error404::widget(),
            Error500::widget(),
        ];

        foreach ($renders as $html) {
            self::assertIsString($html);
            self::assertNotSame('', $html);
        }
    }

    public function testCalendarEncodesEventDataAndMatchesAdminlteCardStructure(): void
    {
        $events = [[
            'title' => '<img src=x onerror=alert(1)>Planning',
            'start' => '2026-08-19',
            'url' => '/events/1?next="quoted"',
        ]];
        $html = Calendar::widget([
            'id' => 'team-calendar',
            'events' => $events,
            'calendarOptions' => ['height' => 'auto'],
            'registerAssets' => false,
        ]);

        self::assertStringNotContainsString('<img', $html);
        self::assertStringContainsString('&lt;img', $html);

        $xpath = $this->xpath($html);
        $card = $this->one($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " card-primary ")]');
        $body = $this->one($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " card-body ")]');
        $calendar = $this->one($xpath, '//*[@id="team-calendar"]');
        self::assertTrue($this->hasClass($card, 'card'));
        self::assertTrue($this->hasClass($body, 'p-0'));
        self::assertTrue($this->hasClass($calendar, 'cinghie-calendar'));
        self::assertSame('1', $calendar->getAttribute('data-cinghie-calendar'));
        self::assertSame($events, Json::decode($calendar->getAttribute('data-cinghie-calendar-events')));

        $options = Json::decode($calendar->getAttribute('data-cinghie-calendar-options'));
        self::assertSame('auto', $options['height']);
        self::assertSame(0, $xpath->query('//*[@id="team-calendar"]//*')->length);
    }

    public function testCalendarCanRenderBareForCustomComposition(): void
    {
        $html = Calendar::widget([
            'id' => 'bare-calendar',
            'card' => false,
            'registerAssets' => false,
        ]);
        $xpath = $this->xpath($html);

        $this->one($xpath, '//*[@id="bare-calendar"]');
        self::assertSame(0, $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " card ")]')->length);
    }

    public function testChartUsesAdminlteCardStructureAndJsonSafeConfiguration(): void
    {
        $data = [
            'labels' => ['<script>alert(1)</script>', 'Safe'],
            'datasets' => [[
                'label' => 'Orders',
                'data' => [2, 5],
            ]],
        ];
        $html = ChartJS::widget([
            'id' => 'sales-chart',
            'canvasId' => 'sales canvas"><script>',
            'type' => 'horizontalBar',
            'title' => 'Sales Chart',
            'data' => $data,
            'chartOptions' => ['legend' => ['display' => false]],
            'registerAssets' => false,
        ]);

        self::assertStringNotContainsString('<script>', $html);

        $xpath = $this->xpath($html);
        $card = $this->one($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " card-primary ")]');
        $title = $this->one($xpath, '//*[contains(concat(" ", normalize-space(@class), " "), " card-title ")]');
        $tool = $this->one($xpath, '//button[@data-card-widget="collapse"]');
        $container = $this->one($xpath, '//*[@id="sales-chart"]');
        $canvas = $this->one($xpath, '//*[@id="sales-canvas---script"]');
        self::assertTrue($this->hasClass($card, 'card'));
        self::assertSame('Sales Chart', trim($title->textContent));
        self::assertSame('collapse', $tool->getAttribute('data-card-widget'));
        self::assertTrue($this->hasClass($container, 'chart'));
        self::assertTrue($this->hasClass($canvas, 'cinghie-chartjs-canvas'));
        self::assertSame('sales-canvas---script', $container->getAttribute('data-cinghie-chartjs-canvas'));
        self::assertSame('horizontalBar', $container->getAttribute('data-cinghie-chartjs-type'));
        self::assertSame($data, Json::decode($container->getAttribute('data-cinghie-chartjs-data')));
        self::assertSame('sales-canvas---script', $canvas->getAttribute('id'));

        $options = Json::decode($container->getAttribute('data-cinghie-chartjs-options'));
        self::assertTrue($options['responsive']);
        self::assertFalse($options['maintainAspectRatio']);
        self::assertFalse($options['legend']['display']);
    }

    public function testErrorPagesMatchAdminlteStructureEncodeTextAndUseSafeLinks(): void
    {
        $html404 = Error404::widget([
            'title' => '<b>Missing</b>',
            'message' => '<script>alert(1)</script>',
            'homeUrl' => ['/site/index'],
            'homeLabel' => 'Back home',
        ]);
        $html500 = Error500::widget();
        $unsafe = Error404::widget([
            'homeUrl' => 'javascript:alert(1)',
            'homeLabel' => 'Unsafe home',
        ]);

        self::assertStringNotContainsString('<b>Missing</b>', $html404);
        self::assertStringNotContainsString('<script>', $html404);
        self::assertStringNotContainsString('RuntimeException', $html500);
        self::assertStringNotContainsString('/private/', $html500);

        $xpath404 = $this->xpath($html404);
        $headline404 = $this->one($xpath404, '//*[contains(concat(" ", normalize-space(@class), " "), " headline ")]');
        $icon404 = $this->one($xpath404, '//h3/i[contains(concat(" ", normalize-space(@class), " "), " fa-exclamation-triangle ")]');
        $home = $this->one($xpath404, '//a[@aria-label="Back home"]');
        self::assertSame('404', trim($headline404->textContent));
        self::assertTrue($this->hasClass($headline404, 'text-warning'));
        self::assertTrue($this->hasClass($icon404, 'text-warning'));
        self::assertFalse($this->hasClass($home, 'btn'));
        self::assertStringContainsString('site%2Findex', $home->getAttribute('href'));

        $xpath500 = $this->xpath($html500);
        $headline500 = $this->one($xpath500, '//*[contains(concat(" ", normalize-space(@class), " "), " headline ")]');
        $icon500 = $this->one($xpath500, '//h3/i[contains(concat(" ", normalize-space(@class), " "), " fa-exclamation-triangle ")]');
        self::assertSame('500', trim($headline500->textContent));
        self::assertTrue($this->hasClass($headline500, 'text-danger'));
        self::assertTrue($this->hasClass($icon500, 'text-danger'));
        self::assertStringContainsString('unexpected error', strtolower($xpath500->document->textContent));

        $unsafeXpath = $this->xpath($unsafe);
        $unsafeHome = $this->one($unsafeXpath, '//a[@aria-label="Unsafe home"]');
        self::assertSame('/', $unsafeHome->getAttribute('href'));
        self::assertStringNotContainsString('javascript:', $unsafe);
    }
}
