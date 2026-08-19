<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Box;
use cinghie\adminlte3\widgets\NavbarUser;
use cinghie\adminlte3\widgets\NavTabs;
use cinghie\adminlte3\widgets\SidebarMenu;
use PHPUnit\Framework\TestCase;

final class RenderingHardeningTest extends TestCase
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

        $this->assertStringNotContainsString('onclick=', $html);
        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringContainsString('&lt;img', $html);
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

        $this->assertStringNotContainsString('onclick=', $html);
        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringContainsString('href="#"', $html);
    }

    public function testNavbarLogoutUsesPostAndRejectsDangerousUrl(): void
    {
        $html = NavbarUser::widget([
            'userfooterlink2' => 'javascript:alert(1)',
        ]);

        $this->assertStringNotContainsString('javascript:', $html);
        $this->assertStringContainsString('data-method="post"', $html);
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

        $this->assertStringNotContainsString('onclick=', $html);
        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringContainsString('&lt;script&gt;', $html);
    }
}
