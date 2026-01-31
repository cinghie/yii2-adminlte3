<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-adminlte3
 * @license GNU GENERAL PUBLIC LICENSE VERSION 3
 * @package yii2-AdminLTE
 * @version 0.1.0
 */

namespace cinghie\adminlte3\widgets;

use yii\bootstrap4\Widget;
use yii\helpers\Html;

/**
 * MailboxRead widget for AdminLTE 3 with Bootstrap 4.
 *
 * Renders a read-mail card: subject, sender, body, attachments.
 * Markup: card > card-header (optional) > card-body (mailbox-read-info, mailbox-read-message) > card-footer (attachments).
 * Attachment items: object with getAttachmentTypeIcon(), fileUrl, filename, formatSize() or array with url, filename, size, icon.
 * Security: mailBody is output as-is (HTML). If user-generated, sanitize (e.g. HTML Purifier) before passing.
 * Attachment url is validated (only http/https or relative); size is HTML-encoded. Icon: use only trusted HTML.
 *
 * @see https://adminlte.io/docs/3.1/pages/mailbox/read-mail.html
 */
class MailboxRead extends Widget
{
    /**
     * @var array Attachments: list of objects (fileUrl, filename, formatSize(), getAttachmentTypeIcon()) or arrays (url, filename, size, icon).
     * url: only http/https or path starting with / are used; others become #. icon: use only trusted HTML (e.g. <i class="far fa-file"></i>).
     */
    public $mailAttachments = [];

    /**
     * @var string Mail body (HTML allowed). If user-generated, sanitize (e.g. HTML Purifier) before passing to prevent XSS.
     */
    public $mailBody = '';

    /**
     * @var string Sender line (e.g. "Name &lt;email@example.com&gt;")
     */
    public $mailSender = '';

    /**
     * @var string Mail subject
     */
    public $mailSubject = '';

    /**
     * @var string|null Optional timestamp/date (e.g. "15 Feb. 2015 11:03 PM")
     */
    public $mailTime;

    /**
     * @var string Display name (e.g. for alt/title of user image)
     */
    public $userName = '';

    /**
     * @var string|null URL of user/sender image. Empty = no image.
     */
    public $userImage;

    /**
     * @var string Card type: null, 'primary', 'success', etc. Adds card-{type} card-outline.
     */
    public $cardType = 'primary';

    /**
     * @var array HTML options for the card container
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!is_array($this->mailAttachments)) {
            $this->mailAttachments = [];
        }
        if ($this->mailBody === null) {
            $this->mailBody = '';
        }
        if ($this->mailSender === null) {
            $this->mailSender = '';
        }
        if ($this->mailSubject === null) {
            $this->mailSubject = '';
        }
        if ($this->userName === null) {
            $this->userName = '';
        }
        if ($this->userImage === null) {
            $this->userImage = '';
        }

        Html::addCssClass($this->options, 'card');
        if ($this->cardType) {
            Html::addCssClass($this->options, 'card-' . $this->cardType);
            Html::addCssClass($this->options, 'card-outline');
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $bodyParts = [];

        // mailbox-read-info: user-block (image + subject + description)
        $userBlockContent = '';
        if ($this->userImage !== null && $this->userImage !== '') {
            $userBlockContent .= Html::img($this->userImage, [
                'class' => 'img-circle',
                'alt' => Html::encode($this->userName),
                'title' => Html::encode($this->userName),
            ]);
        }
        $userBlockContent .= Html::tag('span', Html::encode($this->mailSubject), ['class' => 'username']);
        $userBlockContent .= Html::tag('span', Html::encode($this->mailSender), ['class' => 'description']);
        $bodyParts[] = Html::tag('div', Html::tag('div', $userBlockContent, ['class' => 'user-block']), ['class' => 'mailbox-read-info']);

        // mailbox-read-message
        $bodyParts[] = Html::tag('div', $this->mailBody, ['class' => 'mailbox-read-message']);

        $cardBody = Html::tag('div', implode("\n", $bodyParts), ['class' => 'card-body p-0']);

        $attachmentsHtml = $this->renderAttachments();
        $cardFooter = '';
        if ($attachmentsHtml !== '') {
            $cardFooter = Html::tag('div', $attachmentsHtml, ['class' => 'card-footer bg-white']);
        }

        return Html::tag('div', $cardBody . $cardFooter, $this->options);
    }

    /**
     * Validates URL for use in href: only http, https or relative path. Returns '#' for dangerous schemes.
     * @param string $url
     * @return string
     */
    protected static function safeAttachmentUrl($url)
    {
        if ($url === null || $url === '' || $url === '#') {
            return '#';
        }
        $url = (string) $url;
        if (preg_match('#^\s*javascript:#i', $url) || preg_match('#^\s*data:#i', $url)) {
            return '#';
        }
        if (preg_match('#^https?://#i', $url) || preg_match('#^/#', $url) || !preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            return $url;
        }
        return '#';
    }

    /**
     * Renders attachments list. Size is HTML-encoded; url is validated (safe schemes only).
     * @return string
     */
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
                $icon = isset($attachment['icon']) ? $attachment['icon'] : '<i class="far fa-file"></i>';
            } else {
                $url = isset($attachment->fileUrl) ? $attachment->fileUrl : '#';
                $filename = isset($attachment->filename) ? $attachment->filename : 'file';
                $size = method_exists($attachment, 'formatSize') ? $attachment->formatSize() : '';
                $icon = method_exists($attachment, 'getAttachmentTypeIcon') ? $attachment->getAttachmentTypeIcon() : '<i class="far fa-file"></i>';
            }

            $url = self::safeAttachmentUrl($url);
            $iconSpan = Html::tag('span', $icon, ['class' => 'mailbox-attachment-icon']);
            $link = Html::a(
                '<i class="fas fa-paperclip"></i> ' . Html::encode($filename),
                $url,
                ['class' => 'mailbox-attachment-name', 'style' => 'display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;']
            );
            $sizeSpan = $size !== '' ? Html::tag('span', Html::encode($size), ['class' => 'mailbox-attachment-size']) : '';
            $info = Html::tag('div', $link . $sizeSpan, ['class' => 'mailbox-attachment-info']);
            $items[] = Html::tag('li', $iconSpan . $info);
        }

        return Html::tag('ul', implode("\n", $items), [
            'class' => 'mailbox-attachments d-flex align-items-stretch clearfix',
        ]);
    }

    /**
     * Demo markup (AdminLTE 3 card + Font Awesome 5).
     * @return string
     */
    public function demo()
    {
        $cardBody = Html::tag('div', implode("\n", [
            Html::tag('div', Html::tag('div', implode('', [
                Html::img('https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg', ['class' => 'img-circle', 'alt' => 'John Doe', 'title' => 'John Doe']),
                Html::tag('span', 'Message Subject Is Placed Here', ['class' => 'username']),
                Html::tag('span', 'John Doe &lt;example@example.com&gt;', ['class' => 'description']),
            ]), ['class' => 'user-block']), ['class' => 'mailbox-read-info']),
            Html::tag('div', '<p>Hello John,</p><p>Keffiyeh blog actually fashion axe vegan...</p><p>Thanks,<br>Jane</p>', ['class' => 'mailbox-read-message']),
        ]), ['class' => 'card-body p-0']);

        $attachments = Html::tag('ul', implode('', [
            Html::tag('li', Html::tag('span', '<i class="far fa-file-pdf"></i>', ['class' => 'mailbox-attachment-icon']) . Html::tag('div', Html::a('<i class="fas fa-paperclip"></i> Sep2014-report.pdf', '#', ['class' => 'mailbox-attachment-name']) . Html::tag('span', '1,245 KB', ['class' => 'mailbox-attachment-size']), ['class' => 'mailbox-attachment-info'])),
            Html::tag('li', Html::tag('span', '<i class="far fa-file-word"></i>', ['class' => 'mailbox-attachment-icon']) . Html::tag('div', Html::a('<i class="fas fa-paperclip"></i> App Description.docx', '#', ['class' => 'mailbox-attachment-name']) . Html::tag('span', '1,245 KB', ['class' => 'mailbox-attachment-size']), ['class' => 'mailbox-attachment-info'])),
        ]), ['class' => 'mailbox-attachments d-flex align-items-stretch clearfix']);

        $footerAttachments = Html::tag('div', $attachments, ['class' => 'card-footer bg-white']);
        $footerActions = Html::tag('div', Html::tag('div', '<button type="button" class="btn btn-default"><i class="fas fa-reply"></i> Reply</button> <button type="button" class="btn btn-default"><i class="fas fa-share"></i> Forward</button>', ['class' => 'float-right']) . '<button type="button" class="btn btn-default"><i class="fas fa-trash"></i> Delete</button> <button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button>', ['class' => 'card-footer']);

        return Html::tag('div', $cardBody . $footerAttachments . $footerActions, [
            'class' => 'card card-primary card-outline',
        ]);
    }
}
