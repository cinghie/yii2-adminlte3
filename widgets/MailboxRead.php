<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * Renders an AdminLTE 3 mailbox message card.
 *
 * Message bodies are encoded by default. Applications that intentionally
 * render HTML must set {@see $encodeMailBody} to false; purified HTML remains
 * the default in that mode through {@see $purifyMailBody}.
 */
class MailboxRead extends Widget
{
    /** @var array<int,array|object> Attachment definitions or attachment objects. */
    public $mailAttachments = [];

    /** @var string Message body. */
    public $mailBody = '';

    /** @var bool Whether to HTML-encode the message body. */
    public $encodeMailBody = true;

    /** @var bool Whether to purify HTML when body encoding is disabled. */
    public $purifyMailBody = true;

    /** @var string Message sender label. */
    public $mailSender = '';

    /** @var string Message subject. */
    public $mailSubject = '';

    /** @var mixed Optional message timestamp retained for API compatibility. */
    public $mailTime;

    /** @var string Sender display name used for image metadata. */
    public $userName = '';

    /** @var string|null Sender image URL. */
    public $userImage;

    /** @var string Card contextual type, for example `primary`. */
    public $cardType = 'primary';

    /** @var array HTML options for the outer card element. */
    public $options = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!is_array($this->mailAttachments)) {
            $this->mailAttachments = [];
        }

        foreach (['mailBody', 'mailSender', 'mailSubject', 'userName', 'userImage'] as $property) {
            if ($this->{$property} === null) {
                $this->{$property} = '';
            }
        }

        Html::addCssClass($this->options, 'card');
        if ($this->cardType) {
            Html::addCssClass($this->options, 'card-' . SafeHtml::cssClass($this->cardType));
            Html::addCssClass($this->options, 'card-outline');
        }

        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $userBlockContent = '';
        $userImage = self::safeImageUrl($this->userImage);
        if ($userImage !== '') {
            $userBlockContent .= Html::img($userImage, [
                'class' => 'img-circle',
                'alt' => $this->userName,
                'title' => $this->userName,
            ]);
        }

        $userBlockContent .= Html::tag('span', Html::encode($this->mailSubject), ['class' => 'username']);
        $userBlockContent .= Html::tag('span', Html::encode($this->mailSender), ['class' => 'description']);

        $body = $this->encodeMailBody
            ? Html::encode($this->mailBody)
            : ($this->purifyMailBody ? HtmlPurifier::process($this->mailBody) : $this->mailBody);

        $bodyParts = [
            Html::tag('div', Html::tag('div', $userBlockContent, ['class' => 'user-block']), ['class' => 'mailbox-read-info']),
            Html::tag('div', $body, ['class' => 'mailbox-read-message']),
        ];
        $cardBody = Html::tag('div', implode("\n", $bodyParts), ['class' => 'card-body p-0']);

        $attachmentsHtml = $this->renderAttachments();
        $cardFooter = $attachmentsHtml === ''
            ? ''
            : Html::tag('div', $attachmentsHtml, ['class' => 'card-footer bg-white']);

        return Html::tag('div', $cardBody . $cardFooter, $this->options);
    }

    /**
     * Normalizes an image target while allowing relative and HTTP(S) URLs.
     *
     * @param mixed $url Candidate image URL.
     * @return string Empty string when rejected.
     */
    protected static function safeImageUrl($url): string
    {
        return SafeHtml::linkUrl($url, '');
    }

    /**
     * Normalizes an attachment download target.
     *
     * @param mixed $url Candidate attachment URL.
     * @return string Safe URL or `#`.
     */
    protected static function safeAttachmentUrl($url): string
    {
        return SafeHtml::linkUrl($url, '#');
    }

    /**
     * Converts legacy icon markup or class strings into a safe icon class list.
     *
     * Legacy callers may still provide a single `<i class="..."></i>` string;
     * arbitrary markup is rejected rather than rendered.
     *
     * @param mixed $icon Icon class string or legacy single-icon markup.
     * @return string
     */
    protected static function normalizeIconClass($icon): string
    {
        $default = 'far fa-file';
        if ($icon === null || $icon === '') {
            return $default;
        }

        $icon = trim((string) $icon);
        if (strpos($icon, '<') !== false || strpos($icon, '>') !== false) {
            if (!preg_match('/^<i\s+class=["\']([^"\']+)["\']\s*>\s*<\/i>$/i', $icon, $matches)) {
                return $default;
            }
            $icon = $matches[1];
        }

        return SafeHtml::iconClass($icon, $default);
    }

    /**
     * Renders the attachment list.
     *
     * @return string
     */
    protected function renderAttachments(): string
    {
        if (empty($this->mailAttachments)) {
            return '';
        }

        $items = [];
        foreach ($this->mailAttachments as $attachment) {
            if (is_array($attachment)) {
                $url = $attachment['url'] ?? '#';
                $filename = $attachment['filename'] ?? 'file';
                $size = $attachment['size'] ?? '';
                $icon = $attachment['icon'] ?? 'far fa-file';
            } else {
                $url = $attachment->fileUrl ?? '#';
                $filename = $attachment->filename ?? 'file';
                $size = method_exists($attachment, 'formatSize') ? $attachment->formatSize() : '';
                $icon = method_exists($attachment, 'getAttachmentTypeIcon')
                    ? $attachment->getAttachmentTypeIcon()
                    : 'far fa-file';
            }

            $iconHtml = Html::tag('i', '', ['class' => self::normalizeIconClass($icon)]);
            $iconSpan = Html::tag('span', $iconHtml, ['class' => 'mailbox-attachment-icon']);
            $link = Html::a(
                Html::tag('i', '', ['class' => 'fas fa-paperclip']) . ' ' . Html::encode($filename),
                self::safeAttachmentUrl($url),
                ['class' => 'mailbox-attachment-name d-block text-truncate']
            );
            $sizeSpan = $size !== ''
                ? Html::tag('span', Html::encode($size), ['class' => 'mailbox-attachment-size'])
                : '';
            $items[] = Html::tag(
                'li',
                $iconSpan . Html::tag('div', $link . $sizeSpan, ['class' => 'mailbox-attachment-info'])
            );
        }

        return Html::tag(
            'ul',
            implode("\n", $items),
            ['class' => 'mailbox-attachments d-flex align-items-stretch clearfix']
        );
    }

    /**
     * Returns backward-compatible demo markup.
     *
     * @return string
     */
    public function demo(): string
    {
        return static::widget([
            'mailSubject' => 'Message Subject Is Placed Here',
            'mailSender' => 'John Doe <example@example.com>',
            'mailBody' => '<p>Hello John,</p><p>This is a sample message.</p><p>Thanks,<br>Jane</p>',
            'encodeMailBody' => false,
            'purifyMailBody' => true,
            'userName' => 'John Doe',
            'mailAttachments' => [
                ['url' => '#', 'filename' => 'Sep2014-report.pdf', 'size' => '1,245 KB', 'icon' => 'far fa-file-pdf'],
                ['url' => '#', 'filename' => 'App Description.docx', 'size' => '1,245 KB', 'icon' => 'far fa-file-word'],
            ],
        ]);
    }
}
