<?php

namespace cinghie\adminlte3\widgets;

use cinghie\adminlte3\widgets\support\SafeHtml;
use cinghie\adminlte3\widgets\support\Translation;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Renders an AdminLTE 3 / Bootstrap 4 invoice layout.
 *
 * All visible values are encoded unless a method explicitly creates trusted
 * package markup. URLs are normalized before being emitted. The built-in print
 * action uses a data attribute handled by the package asset JavaScript instead
 * of an inline event handler, which improves Content Security Policy support.
 */
class Invoice extends Widget
{
    /** @var string Company display name. */
    public $companyName = '';

    /** @var string Company logo URL/path or legacy single icon markup. */
    public $companyLogo = '';

    /** @var bool Whether trusted remote HTTP(S) company logos are allowed. */
    public $allowRemoteCompanyLogo = false;

    /** @var string Invoice creation/date label. */
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

    /** @var array<int,array> Invoice line definitions. */
    public $invoiceItems = [];

    public $invoiceSubtotal = '';
    public $invoiceTax = '';
    public $invoiceTaxLabel = '';
    public $invoiceShipping = '';
    public $invoiceTotal = '';
    public $invoiceNotes = '';
    public $invoicePaymentMethod = '';
    public $invoicePaymentMethodCode = '';

    /** @var bool Whether print/PDF actions should be rendered. */
    public $showActions = true;

    /** @var string|array|null Optional custom print target; null uses browser print. */
    public $printUrl;

    /** @var string|array|null Optional generated-PDF target. */
    public $pdfUrl;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->renderInvoice();
    }

    /**
     * Renders the complete invoice container.
     *
     * @return string
     */
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

    /**
     * Renders company branding and invoice date.
     *
     * @return string
     */
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
                Html::encode(Translation::t('Date') . ': ' . $this->invoiceDate),
                ['class' => 'invoice-date']
            );
        }

        return '<div class="row"><div class="col-12">'
            . Html::tag('h4', $brand . $date, ['class' => 'invoice-header'])
            . '</div></div>';
    }

    /**
     * Renders a safe company logo or the default globe icon.
     *
     * @return string
     */
    protected function renderLogo()
    {
        if (!$this->isFilled($this->companyLogo)) {
            return '<i class="fas fa-globe"></i> ';
        }

        $logo = (string) $this->companyLogo;
        if (preg_match('#^\s*<i\s+class="([^"]+)"\s*>\s*</i>\s*$#i', $logo, $matches)) {
            $class = SafeHtml::iconClass($matches[1]);

            return $class === ''
                ? '<i class="fas fa-globe"></i> '
                : Html::tag('i', '', ['class' => $class]) . ' ';
        }

        $url = $this->normalizeCompanyLogoUrl($logo);

        return $url === null ? '<i class="fas fa-globe"></i> ' : Html::img($url, ['alt' => '']);
    }

    /**
     * Normalizes a local or explicitly allowed remote company-logo target.
     *
     * @param string $url Candidate logo URL/path.
     * @return string|null
     */
    protected function normalizeCompanyLogoUrl(string $url)
    {
        $url = trim($url);
        if ($url === '' || strpos($url, '<') !== false || SafeHtml::hasDangerousScheme($url)) {
            return null;
        }
        if (strpos($url, '//') === 0) {
            return null;
        }
        if (preg_match('#^https?://#i', $url)) {
            return $this->allowRemoteCompanyLogo ? SafeHtml::httpUrl($url) : null;
        }
        if (preg_match('#^[a-z][a-z0-9+.-]*:#i', $url)) {
            return null;
        }

        return $url;
    }

    /**
     * Renders sender, recipient and invoice metadata columns.
     *
     * @return string
     */
    protected function renderInfoRow()
    {
        return '<div class="row invoice-info">'
            . '<div class="col-sm-4 invoice-col"><span class="invoice-col-label">'
            . Html::encode(Translation::t('From')) . '</span><address>'
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
            . Html::encode(Translation::t('To')) . '</span><address>'
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

    /**
     * Returns whether a display value is present.
     *
     * @param mixed $value Value to inspect.
     * @return bool
     */
    protected function isFilled($value): bool
    {
        return $value !== null && $value !== '';
    }

    /**
     * Renders one encoded metadata line with an optional safe link.
     *
     * @param string $label Display label.
     * @param string $value Display value.
     * @param string|null $href Optional normalized target.
     * @return string
     */
    protected function extraLine(string $label, string $value, $href = null): string
    {
        $content = '<b>' . Html::encode($label) . ':</b> ';
        if ($href) {
            $options = preg_match('#^https?://#i', (string) $href)
                ? SafeHtml::externalLinkOptions('_blank')
                : [];
            $content .= Html::a(Html::encode($value), $href, $options);
        } else {
            $content .= Html::encode($value);
        }

        return '<span class="invoice-extra">' . $content . '</span>';
    }

    /**
     * Converts a website value into a validated absolute HTTP(S) URL.
     *
     * @param string $website Website input.
     * @return string|null
     */
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

        return SafeHtml::httpUrl($website);
    }

    /**
     * Backward-compatible HTTP(S) validation wrapper.
     *
     * @param string $url URL to validate.
     * @return bool
     */
    protected function isValidHttpUrl(string $url): bool
    {
        return SafeHtml::httpUrl($url) !== null;
    }

    /**
     * Converts an email address into a validated mailto target.
     *
     * @param mixed $email Email value.
     * @return string|null
     */
    protected function normalizeEmailHref($email)
    {
        return SafeHtml::emailHref($email);
    }

    /**
     * Renders an encoded invoice-party address block.
     *
     * @param array $party Party values.
     * @return string
     */
    protected function renderAddressBlock(array $party)
    {
        $parts = [];
        foreach (['name', 'address', 'addressInfo'] as $field) {
            $value = $party[$field] ?? '';
            if ($this->isFilled($value)) {
                $parts[] = $field === 'name'
                    ? '<strong>' . Html::encode($value) . '</strong>'
                    : Html::encode($value);
            }
        }

        foreach (['vatCode' => 'Vat Code', 'taxCode' => 'Tax Code', 'sdi' => 'SDI'] as $field => $label) {
            if ($this->isFilled($party[$field] ?? '')) {
                $parts[] = $this->extraLine(Translation::t($label), (string) $party[$field]);
            }
        }

        if ($this->isFilled($party['pec'] ?? '')) {
            $parts[] = $this->extraLine(
                Translation::t('PEC'),
                (string) $party['pec'],
                $this->normalizeEmailHref($party['pec'])
            );
        }
        foreach (['phone' => 'Phone', 'mobile' => 'Mobile', 'fax' => 'Fax'] as $field => $label) {
            if ($this->isFilled($party[$field] ?? '')) {
                $parts[] = $this->extraLine(Translation::t($label), (string) $party[$field]);
            }
        }
        if ($this->isFilled($party['email'] ?? '')) {
            $parts[] = $this->extraLine(
                Translation::t('Email'),
                (string) $party['email'],
                $this->normalizeEmailHref($party['email'])
            );
        }
        if ($this->isFilled($party['website'] ?? '')) {
            $parts[] = $this->extraLine(
                Translation::t('Website'),
                (string) $party['website'],
                $this->normalizeWebsiteHref((string) $party['website'])
            );
        }

        return implode('<br>', $parts);
    }

    /**
     * Renders invoice identifiers and payment metadata.
     *
     * @return string
     */
    protected function renderMetaBlock()
    {
        $lines = [];
        if ($this->isFilled($this->invoiceNumber)) {
            $lines[] = '<b>' . Html::encode(Translation::t('Invoice') . ' #' . $this->invoiceNumber) . '</b>';
            $lines[] = '';
        }

        foreach ([
            'invoiceType' => 'Type',
            'invoiceOrderID' => 'Order ID',
            'invoicePaymentDue' => 'Payment Due',
        ] as $property => $label) {
            if ($this->isFilled($this->{$property})) {
                $lines[] = '<b>' . Html::encode(Translation::t($label)) . ':</b> '
                    . Html::encode($this->{$property});
            }
        }

        $lines[] = '<b>' . Html::encode(Translation::t('Invoice created')) . ':</b> '
            . Html::encode((string) $this->invoiceDate);
        $lines[] = '<b>' . Html::encode(Translation::t('Invoice sent')) . ':</b> '
            . Html::encode((string) $this->invoiceSent);
        $lines[] = '<b>' . Html::encode(Translation::t('Invoice paid')) . ':</b> '
            . Html::encode((string) $this->invoicePaid);

        if ($this->isFilled($this->invoicePaymentMethod) || $this->isFilled($this->invoicePaymentMethodCode)) {
            $method = (string) $this->invoicePaymentMethod;
            if ($this->isFilled($this->invoicePaymentMethodCode)) {
                $method .= ($method !== '' ? ' ' : '') . '(' . $this->invoicePaymentMethodCode . ')';
            }
            $lines[] = '<b>' . Html::encode(Translation::t('Payment Method')) . ':</b> '
                . Html::encode($method);
        }

        return implode('<br>', $lines);
    }

    /**
     * Normalizes supported invoice-line aliases into one stable shape.
     *
     * @param array $item Raw line definition.
     * @return array{nr:string,name:string,description:string,qty:string,partial:string}
     */
    public static function normalizeItem(array $item): array
    {
        $hasName = (!empty($item['name'])) || (!empty($item['product']));
        $name = !empty($item['name'])
            ? (string) $item['name']
            : (!empty($item['product']) ? (string) $item['product'] : (string) ($item['description'] ?? ''));
        $description = $hasName
            ? (string) ($item['detail'] ?? ($item['description'] ?? ''))
            : (string) ($item['detail'] ?? '');

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
            'qty' => (string) ($item['quantity'] ?? ($item['qty'] ?? '')),
            'partial' => $partial,
        ];
    }

    /**
     * Renders invoice line items.
     *
     * @return string
     */
    protected function renderItemsTable()
    {
        $html = '<div class="row"><div class="col-12 table-responsive invoice-items"><table class="table table-striped">'
            . '<thead><tr>'
            . '<th class="text-center">' . Html::encode(Translation::t('Nr.')) . '</th>'
            . '<th class="text-center">' . Html::encode(Translation::t('Name')) . '</th>'
            . '<th class="text-center">' . Html::encode(Translation::t('Description')) . '</th>'
            . '<th class="text-center">' . Html::encode(Translation::t('Quantity')) . '</th>'
            . '<th class="text-center">' . Html::encode(Translation::t('Partial price')) . '</th>'
            . '</tr></thead><tbody>';

        $items = is_array($this->invoiceItems) ? $this->invoiceItems : [];
        if ($items === []) {
            $html .= '<tr><td colspan="5" class="text-center text-muted">'
                . Html::encode(Translation::t('No line items')) . '</td></tr>';
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

    /**
     * Encodes a description while linkifying validated HTTP(S) candidates.
     *
     * @param string $description Description text.
     * @return string
     */
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
                ? Html::a(Html::encode($raw), $href, SafeHtml::externalLinkOptions('_blank'))
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

    /**
     * Checks whether plain text resembles a web URL candidate.
     *
     * @param string $value Candidate text.
     * @return bool
     */
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

    /**
     * Renders payment notes and invoice totals.
     *
     * @return string
     */
    protected function renderTotalsRow()
    {
        $left = '';
        if ($this->isFilled($this->invoicePaymentMethod)) {
            $left .= '<p class="lead">' . Html::encode(Translation::t('Payment Methods')) . ':</p>'
                . '<p>' . Html::encode($this->invoicePaymentMethod) . '</p>';
        }
        if ($this->isFilled($this->invoiceNotes)) {
            $left .= '<p class="text-muted invoice-notes">'
                . nl2br(Html::encode($this->invoiceNotes), false) . '</p>';
        }

        $amountDueLabel = Translation::t('Amount Due');
        if ($this->isFilled($this->invoicePaymentDue)) {
            $amountDueLabel .= ' ' . $this->invoicePaymentDue;
        } elseif ($this->isFilled($this->invoicePaid)) {
            $amountDueLabel = Translation::t('Paid') . ' ' . $this->invoicePaid;
        }

        $taxLabel = Html::encode(Translation::t('Tax'));
        if ($this->isFilled($this->invoiceTaxLabel)) {
            $taxLabel .= ' (' . Html::encode($this->invoiceTaxLabel) . ')';
        }

        $rows = '';
        foreach ([
            [$this->invoiceSubtotal, Html::encode(Translation::t('Subtotal'))],
            [$this->invoiceTax, $taxLabel],
            [$this->invoiceShipping, Html::encode(Translation::t('Shipping'))],
            [$this->invoiceTotal, Html::encode(Translation::t('Total'))],
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

    /**
     * Normalizes an action target and additionally validates remote HTTP(S).
     *
     * @param mixed $url String URL or Yii route array.
     * @return string
     */
    protected function safeActionUrl($url)
    {
        $url = SafeHtml::linkUrl($url, '#');
        if (preg_match('#^https?://#i', $url) && SafeHtml::httpUrl($url) === null) {
            return '#';
        }

        return $url;
    }

    /**
     * Renders print/PDF actions without inline JavaScript handlers.
     *
     * The default print control is handled by `assets/js/widgets.js` through
     * the `data-cinghie-action="print"` attribute.
     *
     * @return string
     */
    protected function renderActionsRow()
    {
        $printOptions = ['class' => 'btn btn-default'];
        if (!$this->isFilled($this->printUrl)) {
            $printUrl = '#';
            $printOptions['data-cinghie-action'] = 'print';
            $printOptions['role'] = 'button';
        } else {
            $printUrl = $this->safeActionUrl($this->printUrl);
            if (preg_match('#^https?://#i', $printUrl)) {
                $printOptions = array_merge($printOptions, SafeHtml::externalLinkOptions('_blank'));
            }
        }

        $html = '<div class="row no-print"><div class="col-12">'
            . Html::a(
                '<i class="fas fa-print"></i> ' . Html::encode(Translation::t('Print')),
                $printUrl,
                $printOptions
            );

        if ($this->isFilled($this->pdfUrl)) {
            $pdfUrl = $this->safeActionUrl($this->pdfUrl);
            $pdfOptions = ['class' => 'btn btn-primary float-right mr-1'];
            if (preg_match('#^https?://#i', $pdfUrl)) {
                $pdfOptions = array_merge($pdfOptions, SafeHtml::externalLinkOptions('_blank'));
            }
            $html .= ' ' . Html::a(
                '<i class="fas fa-download"></i> ' . Html::encode(Translation::t('Generate PDF')),
                $pdfUrl,
                $pdfOptions
            );
        }

        return $html . '</div></div>';
    }
}
