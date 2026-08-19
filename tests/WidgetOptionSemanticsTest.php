<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\Card;
use cinghie\adminlte3\widgets\NavbarButton;
use cinghie\adminlte3\widgets\SidebarSearch;
use cinghie\adminlte3\widgets\SidebarToggle;
use cinghie\adminlte3\widgets\SmallBox;
use PHPUnit\Framework\TestCase;

/**
 * Guards canonical widget option names and their backward-compatible aliases.
 */
final class WidgetOptionSemanticsTest extends TestCase
{
    public function testCardCanonicalIconWinsOverLegacyTitleIcon(): void
    {
        $html = Card::widget([
            'title' => 'Card',
            'icon' => 'fas fa-star',
            'titleIcon' => 'fas fa-circle',
        ]);

        self::assertStringContainsString('fas fa-star mr-1', $html);
        self::assertStringNotContainsString('fas fa-circle mr-1', $html);
    }

    public function testCardLegacyTitleIconStillWorks(): void
    {
        $html = Card::widget([
            'title' => 'Card',
            'titleIcon' => 'fas fa-circle',
        ]);

        self::assertStringContainsString('fas fa-circle mr-1', $html);
    }

    public function testSmallBoxCanonicalUrlWinsAndLegacyLinkStillWorks(): void
    {
        $canonical = SmallBox::widget([
            'url' => '/canonical',
            'link' => '/legacy',
        ]);
        $legacy = SmallBox::widget([
            'link' => '/legacy',
        ]);

        self::assertStringContainsString('href="/canonical"', $canonical);
        self::assertStringNotContainsString('href="/legacy"', $canonical);
        self::assertStringContainsString('href="/legacy"', $legacy);
    }

    public function testNavbarButtonCanonicalOptionsOverrideLegacyOptionKeys(): void
    {
        $html = NavbarButton::widget([
            'title' => 'Link',
            'renderAsLi' => false,
            'option' => ['class' => 'legacy', 'data-source' => 'legacy'],
            'options' => ['class' => 'canonical'],
        ]);

        self::assertStringContainsString('class="canonical"', $html);
        self::assertStringContainsString('data-source="legacy"', $html);
        self::assertStringNotContainsString('class="legacy"', $html);
    }

    public function testSidebarSearchCanonicalIconWinsAndLegacyAliasWorks(): void
    {
        $canonical = SidebarSearch::widget([
            'icon' => 'fas fa-star',
            'searchIconClass' => 'fas fa-circle',
        ]);
        $legacy = SidebarSearch::widget([
            'searchIconClass' => 'fas fa-circle',
        ]);

        self::assertStringContainsString('fas fa-star', $canonical);
        self::assertStringNotContainsString('fas fa-circle', $canonical);
        self::assertStringContainsString('fas fa-circle', $legacy);
    }

    public function testSidebarToggleCanonicalIconWinsAndLegacyAliasWorks(): void
    {
        $canonical = SidebarToggle::widget([
            'icon' => 'fas fa-star',
            'iconClass' => 'fas fa-circle',
        ]);
        $legacy = SidebarToggle::widget([
            'iconClass' => 'fas fa-circle',
        ]);

        self::assertStringContainsString('fas fa-star', $canonical);
        self::assertStringNotContainsString('fas fa-circle', $canonical);
        self::assertStringContainsString('fas fa-circle', $legacy);
    }
}
