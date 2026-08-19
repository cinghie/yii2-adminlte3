<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Calendar;
use cinghie\adminlte3\widgets\ChartJS;
use cinghie\adminlte3\widgets\Error404;
use cinghie\adminlte3\widgets\Error500;
use yii\helpers\Json;

/**
 * Smoke, structure, and rendering-safety coverage for optional public widgets.
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

    public function testCalendarEncodesEventDataWithoutCreatingInjectedMarkup(): void
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
        $calendar = $this->one($xpath, '//*[@id="team-calendar"]');
        self::assertTrue($this->hasClass($calendar, 'cinghie-calendar'));
        self::assertSame('1', $calendar->getAttribute('data-cinghie-calendar'));
        self::assertSame($events, Json::decode($calendar->getAttribute('data-cinghie-calendar-events')));

        $options = Json::decode($calendar->getAttribute('data-cinghie-calendar-options'));
        self::assertSame('auto', $options['height']);
        self::assertSame(0, $xpath->query('//*[@id="team-calendar"]//*')->length);
    }

    public function testChartUsesDeterministicCanvasIdAndJsonSafeConfiguration(): void
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
            'data' => $data,
            'chartOptions' => ['legend' => ['display' => false]],
            'registerAssets' => false,
        ]);

        self::assertStringNotContainsString('<script>', $html);

        $xpath = $this->xpath($html);
        $container = $this->one($xpath, '//*[@id="sales-chart"]');
        $canvas = $this->one($xpath, '//*[@id="sales-canvas---script"]');
        self::assertSame('sales-canvas---script', $container->getAttribute('data-cinghie-chartjs-canvas'));
        self::assertSame('horizontalBar', $container->getAttribute('data-cinghie-chartjs-type'));
        self::assertSame($data, Json::decode($container->getAttribute('data-cinghie-chartjs-data')));
        self::assertSame('sales-canvas---script', $canvas->getAttribute('id'));

        $options = Json::decode($container->getAttribute('data-cinghie-chartjs-options'));
        self::assertTrue($options['responsive']);
        self::assertFalse($options['maintainAspectRatio']);
        self::assertFalse($options['legend']['display']);
    }

    public function testErrorPagesEncodeUserFacingTextAndExposeAccessibleHomeAction(): void
    {
        $html404 = Error404::widget([
            'title' => '<b>Missing</b>',
            'message' => '<script>alert(1)</script>',
            'homeUrl' => ['/site/index'],
            'homeLabel' => 'Back home',
        ]);
        $html500 = Error500::widget();

        self::assertStringNotContainsString('<b>Missing</b>', $html404);
        self::assertStringNotContainsString('<script>', $html404);
        self::assertStringNotContainsString('RuntimeException', $html500);
        self::assertStringNotContainsString('/private/', $html500);

        $xpath404 = $this->xpath($html404);
        $headline404 = $this->one($xpath404, '//*[contains(concat(" ", normalize-space(@class), " "), " headline ")]');
        $home = $this->one($xpath404, '//a[@aria-label="Back home"]');
        self::assertSame('404', trim($headline404->textContent));
        self::assertTrue($this->hasClass($headline404, 'text-warning'));
        self::assertStringContainsString('site%2Findex', $home->getAttribute('href'));

        $xpath500 = $this->xpath($html500);
        $headline500 = $this->one($xpath500, '//*[contains(concat(" ", normalize-space(@class), " "), " headline ")]');
        self::assertSame('500', trim($headline500->textContent));
        self::assertTrue($this->hasClass($headline500, 'text-danger'));
        self::assertStringContainsString('unexpected error', strtolower($xpath500->document->textContent));
    }
}
