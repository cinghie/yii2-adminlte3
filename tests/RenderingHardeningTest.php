<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Box;
use cinghie\adminlte3\widgets\Card;
use cinghie\adminlte3\widgets\NavbarUser;
use cinghie\adminlte3\widgets\NavTabs;
use cinghie\adminlte3\widgets\SidebarMenu;

final class RenderingHardeningTest extends HtmlDomTestCase
{
    public function testSidebarIconAndBadgeAreEncoded(): void
    {
        $html = SidebarMenu::widget([
            'items' => [[
                'label' => 'Dashboard',
                'url' => ['/site/index'],
                'icon' => 'fas fa-home" onclick="alert(1)',
                'badge' => '<img src=x onerror=alert(1)>',
            ]],
        ]);

        $xpath = $this->xpath($html);
        $icon = $this->one($xpath, "//*[@id='test-root']//a[contains(concat(' ', normalize-space(@class), ' '), ' nav-link ')]/i");
        $badge = $this->one($xpath, "//*[@id='test-root']//span[contains(concat(' ', normalize-space(@class), ' '), ' badge ')]");

        self::assertTrue($this->hasClass($icon, 'fas'));
        self::assertTrue($this->hasClass($icon, 'fa-home'));
        self::assertFalse($icon->hasAttribute('onclick'));
        self::assertSame('<img src=x onerror=alert(1)>', $badge->textContent);
        self::assertSame(0, $xpath->query("//*[@id='test-root']//img")->length);
    }

    public function testBoxSanitizesClassAndRejectsDangerousFooterUrl(): void
    {
        $html = Box::widget([
            'wrapperClass' => 'col-12" onclick="alert(1)',
            'title' => 'Safe',
            'footerLeftTitle' => 'Go',
            'footerLeftUrl' => 'javascript:alert(1)',
            'footerLeftType' => 'danger" onclick="alert(1)',
        ]);

        $xpath = $this->xpath($html);
        $wrapper = $this->one($xpath, "//*[@id='test-root']/div");
        $footerLink = $this->one($xpath, "//*[@id='test-root']//div[contains(concat(' ', normalize-space(@class), ' '), ' card-footer ')]//a");

        self::assertTrue($this->hasClass($wrapper, 'col-12'));
        self::assertFalse($wrapper->hasAttribute('onclick'));
        self::assertSame('#', $footerLink->getAttribute('href'));
        self::assertFalse($footerLink->hasAttribute('onclick'));
    }

    public function testNavbarLogoutUsesPostAndRejectsDangerousUrl(): void
    {
        $html = NavbarUser::widget([
            'userfooterlink2' => 'javascript:alert(1)',
        ]);

        $xpath = $this->xpath($html);
        $logout = $this->one($xpath, "//*[@id='test-root']//a[@data-method='post']");

        self::assertSame('post', $logout->getAttribute('data-method'));
        self::assertSame('#', $logout->getAttribute('href'));
        self::assertStringNotContainsString('javascript:', $logout->getAttribute('href'));
    }

    public function testCardToolExposesAdminLteAndAriaAttributes(): void
    {
        $html = Card::widget([
            'title' => 'Tools',
            'collapsible' => true,
        ]);

        $xpath = $this->xpath($html);
        $button = $this->one($xpath, "//*[@id='test-root']//button[@data-card-widget='collapse']");
        $icon = $this->one($xpath, "//*[@id='test-root']//button[@data-card-widget='collapse']/i");

        self::assertSame('Collapse', $button->getAttribute('aria-label'));
        self::assertSame('Collapse', $button->getAttribute('title'));
        self::assertTrue($this->hasClass($button, 'btn-tool'));
        self::assertTrue($this->hasClass($icon, 'fa-minus'));
    }

    public function testNavTabsSanitizesIds(): void
    {
        $html = NavTabs::widget([
            'items' => [[
                'label' => 'Tab',
                'content' => '<script>alert(1)</script>',
                'id' => 'tab" onclick="alert(1)',
                'icon' => 'fas fa-home" onclick="alert(1)',
            ]],
        ]);

        $xpath = $this->xpath($html);
        self::assertSame(0, $xpath->query("//*[@id='test-root']//*[@onclick]")->length);
        self::assertSame(0, $xpath->query("//*[@id='test-root']//script")->length);
        self::assertStringContainsString('<script>alert(1)</script>', $this->one(
            $xpath,
            "//*[@id='test-root']//div[contains(concat(' ', normalize-space(@class), ' '), ' tab-pane ')]"
        )->textContent);
    }
}
