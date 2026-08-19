<?php

declare(strict_types=1);

namespace cinghie\adminlte3\tests;

use cinghie\adminlte3\widgets\MailboxRead;
use PHPUnit\Framework\TestCase;

final class MailboxReadTest extends TestCase
{
    public function testMailBodyIsEncodedByDefault(): void
    {
        $html = MailboxRead::widget([
            'mailBody' => '<img src=x onerror=alert(1)>',
        ]);

        $this->assertStringNotContainsString('<img src=x onerror=alert(1)>', $html);
        $this->assertStringContainsString('&lt;img src=x onerror=alert(1)&gt;', $html);
    }

    public function testPurifiedHtmlModeRemovesDangerousAttributes(): void
    {
        $html = MailboxRead::widget([
            'mailBody' => '<p>Hello</p><img src="x" onerror="alert(1)">',
            'encodeMailBody' => false,
            'purifyMailBody' => true,
        ]);

        $this->assertStringContainsString('<p>Hello</p>', $html);
        $this->assertStringNotContainsString('onerror', $html);
    }

    public function testAttachmentIconHtmlCannotInjectMarkup(): void
    {
        $html = MailboxRead::widget([
            'mailAttachments' => [[
                'url' => '/file.pdf',
                'filename' => 'file.pdf',
                'icon' => '<img src=x onerror=alert(1)>',
            ]],
        ]);

        $this->assertStringNotContainsString('<img', $html);
        $this->assertStringNotContainsString('onerror', $html);
    }

    public function testDangerousAttachmentSchemesAreRejected(): void
    {
        foreach (['javascript:alert(1)', 'data:text/html,test', 'vbscript:msgbox(1)'] as $url) {
            $html = MailboxRead::widget([
                'mailAttachments' => [[
                    'url' => $url,
                    'filename' => 'file.txt',
                ]],
            ]);
            $this->assertStringNotContainsString($url, $html);
            $this->assertStringContainsString('href="#"', $html);
        }
    }
}
