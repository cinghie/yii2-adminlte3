<?php

namespace cinghie\adminlte3\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * AdminLTE 3 / Bootstrap 4 invoice layout.
 */
class Invoice extends Widget
{
    public $companyName = '';
    public $companyLogo = '';
    /** @var bool allow remote http(s) company-logo requests */
    public $allowRemoteCompanyLogo = false;
    public $invoiceDate = '';
    public $invoiceFromName = '';
    public $invoiceFromAddress = '';
    public $invoiceFromAddressInfo = '';
    public $invoiceFromPhone = '';
    public $invoiceFromEmail = '';
    public $invoiceFromVatCode = '';
    public $invoiceFromTaxCode = '';
    public $invoiceFromSdi = '';
    public $invoiceFromPec = '';
    public $invoiceFromWebsite = '';
    public $invoiceFromFax = '';
    public $invoiceToName = '';
    public $invoiceToAddress = '';
    public $invoiceToAddressInfo = '';
    public $invoiceToPhone = '';
    public $invoiceToEmail = '';
    public $invoiceToMobile = '';
    public $invoiceToFax = '';
    public $invoiceToVatCode = '';
    public $invoiceToTaxCode = '';
    public $invoiceToSdi = '';
    public $invoiceToPec = '';
    public $invoiceToWebsite = '';
    public $invoiceNumber = '';
    public $invoiceOrderID = '';
    public $invoiceType = '';
    public $invoicePaymentDue = '';
    public $invoicePaid = '';
    public $invoiceSent = '';
    public $invoiceAccount = '';
    public $invoiceItems = [];
    public $invoiceSubtotal = '';
    public $invoiceTax = '';
    public $invoiceTaxLabel = '';
    public $invoiceShipping = '';
    public $invoiceTotal = '';
    public $invoiceNotes = '';
    public $invoicePaymentMethod = '';
    public $invoicePaymentMethodCode = '';
    public $showActions = true;
    public $printUrl;
    public $pdfUrl;

    public function run()
    {
        return $this->renderInvoice();
    }

    protected function renderInvoice()
    {
        $html = Html::beginTag('div', ['class' => 'invoice cinghie-invoice']);
        $html .= $this->renderTitleRow();
        $html .= $this->renderInfoRow();
        $html .= $this->renderItemsTable();
        $html .= $this->renderTotalsRow();
        if ($this->showActions) {
            $html .= $this->renderActionsRow();
        }
        return $html . Html::endTag('div');
    }

    protected function renderTitleRow()
    {
        $brand = Html::tag(
            'span',
            $this->renderLogo() . Html::encode((string) $this->companyName),
            ['class' => 'invoice-brand']
        );

        $date = '';
        if ($this->isFilled($this->invoiceDate)) {
            $date = Html::tag(
                'small',
                Html::encode(Yii::t('traits', 'Date') . ': ' . $this->invoiceDate),
                ['class' => 'invoice-date']
            );
        }

        return '<div class="row"><div class="col-12">'
            . Html::tag('h4', $brand . $date, ['class' => 'invoice-header'])
            . '</div></div>';
    }

    protected function renderLogo()
    {
        if (!$this->isFilled($this->companyLogo)) {
            return '<i class="fas fa-globe"></i> ';
        }

        $logo = (string) $this->companyLogo;
        if (preg_match('#^\s*<i\s+class="([^"]+)"\s*>\s*</i>\s*$#i', $logo, $matches)) {
            $class = preg_replace('/[^A-Za-z0-9_\- ]/', '', $matches[1]);
            return $class === '' ? '<i class="fas fa-globe"></i> ' : Html::tag('i', '', ['class' => $class]) . ' ';
        }

        $url = $this->normalizeCompanyLogoUrl($logo);
        return $url === null ? '<i class="fas fa-globe"></i> ' : Html::img($url, ['alt' => '']);
    }

    protected function normalizeCompanyLogoUrl(string $url)
    {
        $url = trim($url);
        if ($url === '' || strpos($url, '<') !== false || preg_match('#^\s*(?:javascript|data|vbscript):#i', $url)) {
            return null;
        }
        if (strpos($url, '//') === 0) {
            return null;
        }
        if (preg_match('#^https?://#i', $url)) {
            if (!$this->allowRemoteCompanyLogo || !$this->isValidHttpUrl($url)) {
                return null;
            }
            return $url;
        }
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            return null;
        }
        return $url;
    }

    protected function renderInfoRow()
    {
        return '<div class="row invoice-info">'
            . '<div class="col-sm-4 invoice-col"><span class="invoice-col-label">'
            . Html::encode(Yii::t('traits', 'From')) . '</span><address>'
            . $this->renderAddressBlock([
                'name' => $this->invoiceFromName,
                'address' => $this->invoiceFromAddress,
                'addressInfo' => $this->invoiceFromAddressInfo,
                'phone' => $this->invoiceFromPhone,
                'fax' => $this->invoiceFromFax,
                'email' => $this->invoiceFromEmail,
                'vatCode' => $this->invoiceFromVatCode,
                'taxCode' => $this->invoiceFromTaxCode,
                'sdi' => $this->invoiceFromSdi,
                'pec' => $this->invoiceFromPec,
                'website' => $this->invoiceFromWebsite,
            ]) . '</address></div>'
            . '<div class="col-sm-4 invoice-col"><span class="invoice-col-label">'
            . Html::encode(Yii::t('traits', 'To')) . '</span><address>'
            . $this->renderAddressBlock([
                'name' => $this->invoiceToName,
                'address' => $this->invoiceToAddress,
                'addressInfo' => $this->invoiceToAddressInfo,
                'phone' => $this->invoiceToPhone,
                'mobile' => $this->invoiceToMobile,
                'fax' => $this->invoiceToFax,
                'email' => $this->invoiceToEmail,
                'vatCode' => $this->invoiceToVatCode,
                'taxCode' => $this->invoiceToTaxCode,
                'sdi' => $this->invoiceToSdi,
                'pec' => $this->invoiceToPec,
                'website' => $this->invoiceToWebsite,
            ]) . '</address></div>'
            . '<div class="col-sm-4 invoice-col">' . $this->renderMetaBlock() . '</div></div>';
    }

    protected function isFilled($value): bool
    {
        return $value !== null && $value !== '';
    }

    protected function extraLine(string $label, string $value, $href = null): string
    {
        $content = '<b>' . Html::encode($label) . ':</b> ';
        if ($href) {
            $options = [];
            if (preg_match('#^https?://#i', (string) $href)) {
                $options = ['target' => '_blank', 'rel' => 'noopener noreferrer'];
            }
            $content .= Html::a(Html::encode($value), $href, $options);
        } else {
            $content .= Html::encode($value);
        }
        return '<span class="invoice-extra">' . $content . '</span>';
    }

    protected function normalizeWebsiteHref(string $website)
    {
        $website = trim($website);
        if ($website === '') {
            return null;
        }
        if (!preg_match('#^https?://#i', $website)) {
            if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $website)) {
                return null;
            }
            $website = 'https://' . $website;
        }
        return $this->isValidHttpUrl($website) ? $website : null;
    }

    protected function isValidHttpUrl(string $url): bool
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        $host = parse_url($url, PHP_URL_HOST);
        return in_array($scheme, ['http', 'https'], true) && is_string($host) && $host !== '';
    }

    protected function normalizeEmailHref($email)
    {
        $email = trim((string) $email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false ? 'mailto:' . $email : null;
    }

    protected function renderAddressBlock(array $party)
    {
        $parts = [];
        $plain = [
            ['name', null],
            ['address', null],
            ['addressInfo', null],
        ];
        foreach ($plain as $field) {
            $value = isset($party[$field[0]]) ? $party[$field[0]] : '';
            if ($this->isFilled($value)) {
                $parts[] = $field[0] === 'name'
                    ? '<strong>' . Html::encode($value) . '</strong>'
                    : Html::encode($value);
            }
        }

        $labels = [
            'vatCode' => 'Vat Code',
            'taxCode' => 'Tax Code',
            'sdi' => 'SDI',
        ];
        foreach ($labels as $field => $label) {
            if ($this->isFilled(isset($party[$field]) ? $party[$field] : '')) {
                $parts[] = $this->extraLine(Yii::t('traits', $label), (string) $party[$field]);
            }
        }

        if ($this->isFilled(isset($party['pec']) ? $party['pec'] : '')) {
            $parts[] = $this->extraLine(
                Yii::t('traits', 'PEC'),
                (string) $party['pec'],
                $this->normalizeEmailHref($party['pec'])
            );
        }
        foreach (['phone' => 'Phone', 'mobile' => 'Mobile', 'fax' => 'Fax'] as $field => $label) {
            if ($this->isFilled(isset($party[$field]) ? $party[$field] : '')) {
                $parts[] = $this->extraLine(Yii::t('traits', $label), (string) $party[$field]);
            }
        }
        if ($this->isFilled(isset($party['email']) ? $party['email'] : '')) {
            $parts[] = $this->extraLine(
                Yii::t('traits', 'Email'),
                (string) $party['email'],
                $this->normalizeEmailHref($party['email'])
            );
        }
        if ($this->isFilled(isset($party['website']) ? $party['website'] : '')) {
            $parts[] = $this->extraLine(
                Yii::t('traits', 'Website'),
                (string) $party['website'],
                $this->normalizeWebsiteHref((string) $party['website'])
            );
        }

        return implode('<br>', $parts);
    }

    protected function renderMetaBlock()
    {
        $lines = [];
        if ($this->isFilled($this->invoiceNumber)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Invoice') . ' #' . $this->invoiceNumber) . '</b>';
            $lines[] = '';
        }
        foreach ([
            'invoiceType' => ['traits', 'Type'],
            'invoiceOrderID' => ['traits', 'Order ID'],
            'invoicePaymentDue' => ['traits', 'Payment Due'],
        ] as $property => $label) {
            if ($this->isFilled($this->{$property})) {
                $lines[] = '<b>' . Html::encode(Yii::t($label[0], $label[1])) . ':</b> ' . Html::encode($this->{$property});
            }
        }
        $lines[] = '<b>' . Html::encode(Yii::t('crm', 'Invoice created')) . ':</b> ' . Html::encode((string) $this->invoiceDate);
        $lines[] = '<b>' . Html::encode(Yii::t('crm', 'Invoice sent')) . ':</b> ' . Html::encode((string) $this->invoiceSent);
        $lines[] = '<b>' . Html::encode(Yii::t('crm', 'Invoice paid')) . ':</b> ' . Html::encode((string) $this->invoicePaid);

        if ($this->isFilled($this->invoicePaymentMethod) || $this->isFilled($this->invoicePaymentMethodCode)) {
            $method = (string) $this->invoicePaymentMethod;
            if ($this->isFilled($this->invoicePaymentMethodCode)) {
                $method .= ($method !== '' ? ' ' : '') . '(' . $this->invoicePaymentMethodCode . ')';
            }
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Payment Method')) . ':</b> ' . Html::encode($method);
        }
        return implode('<br>', $lines);
    }

    public static function normalizeItem(array $item): array
    {
        $hasName = (isset($item['name']) && $item['name'] !== '') || (isset($item['product']) && $item['product'] !== '');
        if (isset($item['name']) && $item['name'] !== '') {
            $name = (string) $item['name'];
        } elseif (isset($item['product']) && $item['product'] !== '') {
            $name = (string) $item['product'];
        } else {
            $name = (string) (isset($item['description']) ? $item['description'] : '');
        }
        $description = $hasName
            ? (string) (isset($item['detail']) ? $item['detail'] : (isset($item['description']) ? $item['description'] : ''))
            : (string) (isset($item['detail']) ? $item['detail'] : '');
        $nr = '';
        foreach (['sort', 'nr', 'serial'] as $field) {
            if (isset($item[$field]) && $item[$field] !== '') {
                $nr = (string) $item[$field];
                break;
            }
        }
        $partial = '';
        foreach (['subtotal', 'amount', 'product_price', 'unit_price', 'price'] as $field) {
            if (isset($item[$field])) {
                $partial = (string) $item[$field];
                break;
            }
        }
        return [
            'nr' => $nr,
            'name' => $name,
            'description' => $description,
            'qty' => (string) (isset($item['quantity']) ? $item['quantity'] : (isset($item['qty']) ? $item['qty'] : '')),
            'partial' => $partial,
        ];
    }

    protected function renderItemsTable()
    {
        $html = '<div class="row"><div class="col-12 table-responsive invoice-items"><table class="table table-striped">'
            . '<thead><tr>'
            . '<th class="text-center">' . Html::encode(Yii::t('crm', 'Nr.')) . '</th>'
            . '<th class="text-center">' . Html::encode(Yii::t('traits', 'Name')) . '</th>'
            . '<th class="text-center">' . Html::encode(Yii::t('traits', 'Description')) . '</th>'
            . '<th class="text-center">' . Html::encode(Yii::t('traits', 'Quantity')) . '</th>'
            . '<th class="text-center">' . Html::encode(Yii::t('crm', 'Partial price')) . '</th>'
            . '</tr></thead><tbody>';

        $items = is_array($this->invoiceItems) ? $this->invoiceItems : [];
        if ($items === []) {
            $html .= '<tr><td colspan="5" class="text-center text-muted">'
                . Html::encode(Yii::t('traits', 'No line items')) . '</td></tr>';
        } else {
            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $row = static::normalizeItem($item);
                $html .= '<tr>'
                    . '<td class="text-center">' . Html::encode($row['nr']) . '</td>'
                    . '<td class="text-center">' . Html::encode($row['name']) . '</td>'
                    . '<td class="text-center">' . $this->formatItemDescription($row['description']) . '</td>'
                    . '<td class="text-center">' . Html::encode($row['qty']) . '</td>'
                    . '<td class="text-center">' . Html::encode($row['partial']) . '</td>'
                    . '</tr>';
            }
        }
        return $html . '</tbody></table></div></div>';
    }

    protected function formatItemDescription(string $description): string
    {
        $description = trim($description);
        if ($description === '') {
            return '';
        }
        $pattern = '#(https?://[^\s<>"\']+|www\.[^\s<>"\']+|(?<![\w.@+-])(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}(?:/[^\s<>"\']*)?)#iu';
        if (!preg_match_all($pattern, $description, $matches, PREG_OFFSET_CAPTURE)) {
            return Html::encode($description);
        }

        $html = '';
        $offset = 0;
        $length = strlen($description);
        foreach ($matches[0] as $match) {
            $raw = $match[0];
            $pos = (int) $match[1];
            if ($pos > $offset) {
                $html .= Html::encode(substr($description, $offset, $pos - $offset));
            }
            $trailing = '';
            if (preg_match('/[.,;:!?)\]]+$/u', $raw, $trailMatch)) {
                $trailing = $trailMatch[0];
                $raw = substr($raw, 0, -strlen($trailing));
            }
            $href = $this->isSafeHttpUrlCandidate($raw) ? $this->normalizeWebsiteHref($raw) : null;
            $html .= $href !== null
                ? Html::a(Html::encode($raw), $href, ['target' => '_blank', 'rel' => 'noopener noreferrer'])
                : Html::encode($raw);
            if ($trailing !== '') {
                $html .= Html::encode($trailing);
            }
            $offset = $pos + strlen($match[0]);
        }
        if ($offset < $length) {
            $html .= Html::encode(substr($description, $offset));
        }
        return $html;
    }

    protected function isSafeHttpUrlCandidate(string $value): bool
    {
        $value = trim($value);
        if ($value === '') {
            return false;
        }
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $value) && !preg_match('#^https?://#i', $value)) {
            return false;
        }
        return preg_match('#^https?://#i', $value)
            || preg_match('#^www\.#i', $value)
            || preg_match('#^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}(?:/.*)?$#i', $value);
    }

    protected function renderTotalsRow()
    {
        $left = '';
        if ($this->isFilled($this->invoicePaymentMethod)) {
            $left .= '<p class="lead">' . Html::encode(Yii::t('traits', 'Payment Methods')) . ':</p>'
                . '<p>' . Html::encode($this->invoicePaymentMethod) . '</p>';
        }
        if ($this->isFilled($this->invoiceNotes)) {
            $left .= '<p class="text-muted invoice-notes">' . nl2br(Html::encode($this->invoiceNotes), false) . '</p>';
        }

        $amountDueLabel = Yii::t('traits', 'Amount Due');
        if ($this->isFilled($this->invoicePaymentDue)) {
            $amountDueLabel .= ' ' . $this->invoicePaymentDue;
        } elseif ($this->isFilled($this->invoicePaid)) {
            $amountDueLabel = Yii::t('traits', 'Paid') . ' ' . $this->invoicePaid;
        }

        $taxLabel = Html::encode(Yii::t('traits', 'Tax'));
        if ($this->isFilled($this->invoiceTaxLabel)) {
            $taxLabel .= ' (' . Html::encode($this->invoiceTaxLabel) . ')';
        }

        $rows = '';
        foreach ([
            [$this->invoiceSubtotal, Html::encode(Yii::t('traits', 'Subtotal'))],
            [$this->invoiceTax, $taxLabel],
            [$this->invoiceShipping, Html::encode(Yii::t('traits', 'Shipping'))],
            [$this->invoiceTotal, Html::encode(Yii::t('traits', 'Total'))],
        ] as $row) {
            if ($this->isFilled($row[0])) {
                $rows .= '<tr><th>' . $row[1] . ':</th><td>' . Html::encode($row[0]) . '</td></tr>';
            }
        }

        $right = $rows === '' ? '' : '<p class="lead">' . Html::encode($amountDueLabel) . '</p>'
            . '<div class="table-responsive invoice-totals"><table class="table">' . $rows . '</table></div>';

        return '<div class="row invoice-summary"><div class="col-6">' . $left
            . '</div><div class="col-6">' . $right . '</div></div>';
    }

    protected function safeActionUrl($url)
    {
        if (is_array($url)) {
            return Url::to($url);
        }
        $url = trim((string) $url);
        if ($url === '' || preg_match('#^\s*(?:javascript|data|vbscript):#i', $url)) {
            return '#';
        }
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url) && !preg_match('#^https?://#i', $url)) {
            return '#';
        }
        if (preg_match('#^https?://#i', $url) && !$this->isValidHttpUrl($url)) {
            return '#';
        }
        return $url;
    }

    protected function renderActionsRow()
    {
        $printOptions = ['class' => 'btn btn-default'];
        if (!$this->isFilled($this->printUrl)) {
            $printUrl = '#';
            $printOptions['onclick'] = 'window.print(); return false;';
        } else {
            $printUrl = $this->safeActionUrl($this->printUrl);
            if (preg_match('#^https?://#i', $printUrl)) {
                $printOptions['target'] = '_blank';
                $printOptions['rel'] = 'noopener noreferrer';
            }
        }

        $html = '<div class="row no-print"><div class="col-12">'
            . Html::a('<i class="fas fa-print"></i> ' . Html::encode(Yii::t('traits', 'Print')), $printUrl, $printOptions);

        if ($this->isFilled($this->pdfUrl)) {
            $pdfUrl = $this->safeActionUrl($this->pdfUrl);
            $pdfOptions = ['class' => 'btn btn-primary float-right', 'style' => 'margin-right: 5px;'];
            if (preg_match('#^https?://#i', $pdfUrl)) {
                $pdfOptions['target'] = '_blank';
                $pdfOptions['rel'] = 'noopener noreferrer';
            }
            $html .= ' ' . Html::a(
                '<i class="fas fa-download"></i> ' . Html::encode(Yii::t('traits', 'Generate PDF')),
                $pdfUrl,
                $pdfOptions
            );
        }

        return $html . '</div></div>';
    }
}
