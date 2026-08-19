<?php

namespace cinghie\adminlte3\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * MailboxRead widget for AdminLTE 3 with Bootstrap 4.
 *
 * Mail bodies are encoded by default. Set $encodeMailBody to false to render
 * HTML; when $purifyMailBody is true (the default for HTML mode), the body is
 * passed through Yii's HTML purifier first.
 */
class MailboxRead extends Widget
{
    public $mailAttachments = [];
    public $mailBody = '';
    public $encodeMailBody = true;
    public $purifyMailBody = true;
    public $mailSender = '';
    public $mailSubject = '';
    public $mailTime;
    public $userName = '';
    public $userImage;
    public $cardType = 'primary';
    public $options = [];

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
            Html::addCssClass($this->options, 'card-' . self::sanitizeClass($this->cardType));
            Html::addCssClass($this->options, 'card-outline');
        }
        parent::init();
    }

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
        $cardFooter = $attachmentsHtml === '' ? '' : Html::tag('div', $attachmentsHtml, ['class' => 'card-footer bg-white']);

        return Html::tag('div', $cardBody . $cardFooter, $this->options);
    }

    protected static function sanitizeClass($value, $default = '')
    {
        $sanitized = preg_replace('/[^A-Za-z0-9_\- ]/', '', (string) $value);
        return $sanitized !== '' ? $sanitized : $default;
    }

    protected static function safeImageUrl($url)
    {
        if ($url === null || $url === '') {
            return '';
        }
        $url = trim((string) $url);
        if (preg_match('#^\s*(?:javascript|data|vbscript):#i', $url)) {
            return '';
        }
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) && !preg_match('#^https?://#i', $url)) {
            return '';
        }
        return $url;
    }

    protected static function safeAttachmentUrl($url)
    {
        if ($url === null || $url === '' || $url === '#') {
            return '#';
        }
        $url = (string) $url;
        if (preg_match('#^\s*(?:javascript|data|vbscript):#i', $url)) {
            return '#';
        }
        if (preg_match('#^https?://#i', $url) || preg_match('#^/#', $url) || !preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            return $url;
        }
        return '#';
    }

    protected static function normalizeIconClass($icon)
    {
        $default = 'far fa-file';
        if ($icon === null || $icon === '') {
            return $default;
        }
        $icon = (string) $icon;
        if (preg_match('/<i\s+class=["\']([^"\']+)["\']\s*><\/i>/i', trim($icon), $matches)) {
            $icon = $matches[1];
        }
        return self::sanitizeClass($icon, $default);
    }

    protected function renderAttachments()
    {
        if (empty($this->mailAttachments)) {
            return '';
        }

        $items = [];
        foreach ($this->mailAttachments as $attachment) {
            if (is_array($attachment)) {
                $url = isset($attachment['url']) ? $attachment['url'] : '#';
                $filename = isset($attachment['filename']) ? $attachment['filename'] : 'file';
                $size = isset($attachment['size']) ? $attachment['size'] : '';
                $icon = isset($attachment['icon']) ? $attachment['icon'] : 'far fa-file';
            } else {
                $url = isset($attachment->fileUrl) ? $attachment->fileUrl : '#';
                $filename = isset($attachment->filename) ? $attachment->filename : 'file';
                $size = method_exists($attachment, 'formatSize') ? $attachment->formatSize() : '';
                $icon = method_exists($attachment, 'getAttachmentTypeIcon') ? $attachment->getAttachmentTypeIcon() : 'far fa-file';
            }

            $iconHtml = Html::tag('i', '', ['class' => self::normalizeIconClass($icon)]);
            $iconSpan = Html::tag('span', $iconHtml, ['class' => 'mailbox-attachment-icon']);
            $link = Html::a(
                Html::tag('i', '', ['class' => 'fas fa-paperclip']) . ' ' . Html::encode($filename),
                self::safeAttachmentUrl($url),
                ['class' => 'mailbox-attachment-name', 'style' => 'display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;']
            );
            $sizeSpan = $size !== '' ? Html::tag('span', Html::encode($size), ['class' => 'mailbox-attachment-size']) : '';
            $items[] = Html::tag('li', $iconSpan . Html::tag('div', $link . $sizeSpan, ['class' => 'mailbox-attachment-info']));
        }

        return Html::tag('ul', implode("\n", $items), ['class' => 'mailbox-attachments d-flex align-items-stretch clearfix']);
    }

    /**
     * Backward-compatible demo markup helper.
     */
    public function demo()
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
