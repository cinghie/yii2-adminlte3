# MailboxRead Widget

The **MailboxRead** widget renders an AdminLTE 3 read-mail card: subject, sender, date, body, and optional attachments. Markup: card with card-header (optional), card-body (mailbox-read-info, mailbox-read-message), and card-footer (attachments). Attachment URLs are validated (only http/https or relative paths).

**Reference:** [AdminLTE 3 – Mailbox read](https://adminlte.io/docs/3.0/pages/mailbox/read-mail.html)

---

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `mailSubject` | string | `''` | Mail subject. |
| `mailSender` | string | `''` | Sender line (e.g. "Name &lt;email@example.com&gt;"). |
| `mailTime` | string\|null | null | Timestamp/date (e.g. "15 Feb. 2015 11:03 PM"). |
| `mailBody` | string | `''` | Mail body (HTML allowed). If user-generated, sanitize (e.g. HTML Purifier) before passing to prevent XSS. |
| `mailAttachments` | array | [] | List of attachments: objects (fileUrl, filename, formatSize(), getAttachmentTypeIcon()) or arrays (url, filename, size, icon). |
| `userName` | string | `''` | Display name (e.g. for alt/title of sender image). |
| `userImage` | string\|null | null | URL of sender image. Empty = no image. |
| `cardType` | string | `'primary'` | Card type: `primary`, `success`, `info`, etc. (card-outline card-{type}). |
| `options` | array | [] | HTML options for the card container. |

---

## Usage

```php
<?php use cinghie\adminlte3\widgets\MailboxRead; ?>

<?= MailboxRead::widget([
    'mailSubject' => 'Re: Your request',
    'mailSender' => 'John Doe <john@example.com>',
    'mailTime' => '15 Feb. 2015 11:03 PM',
    'mailBody' => '<p>Hello,</p><p>Message content here.</p>',
    'userName' => 'John Doe',
    'userImage' => '/images/users/john.jpg',
    'mailAttachments' => [
        [
            'url' => '/uploads/doc.pdf',
            'filename' => 'document.pdf',
            'size' => '1.2 MB',
            'icon' => '<i class="far fa-file-pdf"></i>',
        ],
    ],
]) ?>
```

Without attachments:

```php
<?= MailboxRead::widget([
    'mailSubject' => $model->subject,
    'mailSender' => $model->senderName . ' <' . $model->senderEmail . '>',
    'mailTime' => Yii::$app->formatter->asDatetime($model->createdAt),
    'mailBody' => $model->bodyHtml, // sanitize if user-generated
]) ?>
```

---

## Notes

- **Security:** `mailBody` is output as-is (HTML). If it comes from user input, sanitize it (e.g. HTML Purifier) before passing. Attachment URLs are validated (only http/https or path starting with `/`); others become `#`. Size and filename are HTML-encoded.
- Attachments: use objects with `fileUrl`, `filename`, `formatSize()`, `getAttachmentTypeIcon()` or arrays with `url`, `filename`, `size`, `icon`. Icon should be trusted HTML (e.g. `<i class="far fa-file"></i>`).
