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

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * AdminLTE 3 / Bootstrap 4 invoice layout.
 *
 * Layout aligned with the official AdminLTE 3 invoice example, with slightly
 * more padding. Styles are registered by this widget (not a global CSS file).
 *
 * @see https://adminlte.io/themes/v3/pages/examples/invoice.html
 */
class Invoice extends Widget
{
    /** @var string */
    public $companyName = '';

    /** @var string Logo URL/path, or a safe &lt;i&gt; icon HTML snippet */
    public $companyLogo = '';

    /** @var string */
    public $invoiceDate = '';

    /** @var string */
    public $invoiceFromName = '';

    /** @var string */
    public $invoiceFromAddress = '';

    /** @var string */
    public $invoiceFromAddressInfo = '';

    /** @var string */
    public $invoiceFromPhone = '';

    /** @var string */
    public $invoiceFromEmail = '';

    /** @var string VAT / Partita IVA (From) */
    public $invoiceFromVatCode = '';

    /** @var string Tax code / Codice fiscale (From) */
    public $invoiceFromTaxCode = '';

    /** @var string Codice Destinatario SDI (From) */
    public $invoiceFromSdi = '';

    /** @var string PEC (From) */
    public $invoiceFromPec = '';

    /** @var string */
    public $invoiceFromWebsite = '';

    /** @var string */
    public $invoiceFromFax = '';

    /** @var string */
    public $invoiceToName = '';

    /** @var string */
    public $invoiceToAddress = '';

    /** @var string */
    public $invoiceToAddressInfo = '';

    /** @var string */
    public $invoiceToPhone = '';

    /** @var string */
    public $invoiceToEmail = '';

    /** @var string */
    public $invoiceToMobile = '';

    /** @var string */
    public $invoiceToFax = '';

    /** @var string VAT / Partita IVA (To) */
    public $invoiceToVatCode = '';

    /** @var string Tax code / Codice fiscale (To) */
    public $invoiceToTaxCode = '';

    /** @var string Codice Destinatario SDI (To) */
    public $invoiceToSdi = '';

    /** @var string PEC (To) */
    public $invoiceToPec = '';

    /** @var string */
    public $invoiceToWebsite = '';

    /** @var string raw invoice number / code (label applied in view) */
    public $invoiceNumber = '';

    /** @var string */
    public $invoiceOrderID = '';

    /** @var string document type (e.g. TD01) */
    public $invoiceType = '';

    /** @var string payment due date */
    public $invoicePaymentDue = '';

    /** @var string paid-on date (shown as Paid, not Due) */
    public $invoicePaid = '';

    /** @var string sent-on date */
    public $invoiceSent = '';

    /** @var string account / customer reference */
    public $invoiceAccount = '';

    /**
     * Line items. Supported keys (any subset):
     * product|description, serial, detail, product_price|price|unit_price,
     * quantity|qty, subtotal|amount
     *
     * @var array
     */
    public $invoiceItems = [];

    /** @var string formatted subtotal */
    public $invoiceSubtotal = '';

    /** @var string formatted tax */
    public $invoiceTax = '';

    /** @var string optional tax rate label, e.g. "22%" */
    public $invoiceTaxLabel = '';

    /** @var string formatted shipping (optional) */
    public $invoiceShipping = '';

    /** @var string formatted total */
    public $invoiceTotal = '';

    /** @var string notes / payment terms (plain text, encoded) */
    public $invoiceNotes = '';

    /** @var string payment method label */
    public $invoicePaymentMethod = '';

    /** @var string payment method code (e.g. MP05) */
    public $invoicePaymentMethodCode = '';

    /** @var bool show print / PDF action row */
    public $showActions = true;

    /** @var string|null print URL (defaults to javascript:window.print()) */
    public $printUrl;

    /** @var string|null optional PDF download URL */
    public $pdfUrl;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->registerInvoiceCss();

        return $this->renderInvoice();
    }

    /**
     * Widget-scoped CSS (AdminLTE 3 invoice look + extra padding).
     */
    protected function registerInvoiceCss()
    {
        $css = <<<'CSS'
/* Scoped to this widget — mirrors AdminLTE 3 invoice, with a bit more padding */
.cinghie-invoice.invoice {
    position: relative;
    background-color: #fff;
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
    padding: 1.75rem; /* p-3 is 1rem; slightly more as requested */
}
.cinghie-invoice .invoice-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem 1rem;
    margin: 0 0 1.5rem;
    padding: 0 0 0.85rem;
    border-bottom: 1px solid #dee2e6;
    font-size: 1.5rem;
    font-weight: 400;
    line-height: 1.4;
}
.cinghie-invoice .invoice-header .invoice-brand {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.cinghie-invoice .invoice-header .invoice-brand img {
    max-height: 40px;
    width: auto;
}
.cinghie-invoice .invoice-header .invoice-date {
    margin-left: auto;
    font-size: 0.95rem;
    font-weight: 400;
    color: #6c757d;
    white-space: nowrap;
}
.cinghie-invoice .invoice-info {
    margin-bottom: 1.5rem;
}
.cinghie-invoice .invoice-col {
    margin-bottom: 1rem;
    line-height: 1.6;
}
.cinghie-invoice .invoice-col > .invoice-col-label {
    display: block;
    margin-bottom: 0.35rem;
    color: #6c757d;
}
.cinghie-invoice address {
    margin-bottom: 0;
    font-style: normal;
    line-height: 1.65;
}
.cinghie-invoice .invoice-extra {
    color: #495057;
}
.cinghie-invoice .invoice-extra b,
.cinghie-invoice .invoice-extra .invoice-extra-label {
    color: inherit;
    font-weight: 700;
}
.cinghie-invoice .invoice-items {
    margin-bottom: 1.5rem;
}
.cinghie-invoice .invoice-items .table {
    margin-bottom: 0;
}
.cinghie-invoice .table th,
.cinghie-invoice .table td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
}
.cinghie-invoice .table thead th {
    border-bottom-width: 1px;
    font-weight: 600;
}
.cinghie-invoice .invoice-summary .lead {
    font-size: 1.15rem;
    font-weight: 400;
    margin-bottom: 0.75rem;
}
.cinghie-invoice .invoice-notes {
    margin-top: 0.75rem;
    margin-bottom: 0;
    padding: 0.85rem 1rem;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.25rem;
    color: #6c757d;
}
.cinghie-invoice .invoice-totals .table {
    margin-bottom: 0;
}
.cinghie-invoice .invoice-totals .table th,
.cinghie-invoice .invoice-totals .table td {
    border-top: 1px solid #dee2e6;
}
.cinghie-invoice .no-print {
    margin-top: 1.5rem;
    padding-top: 0.25rem;
}
.cinghie-invoice .no-print .btn {
    margin-bottom: 0.35rem;
}
@media print {
    .cinghie-invoice .no-print {
        display: none !important;
    }
    .cinghie-invoice.invoice {
        border: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
    }
}
CSS;
        Yii::$app->view->registerCss($css, [], 'cinghie-adminlte3-invoice');
    }

    /**
     * @return string
     */
    protected function renderInvoice()
    {
        // AdminLTE 3 uses div.invoice.p-3.mb-3 — padding comes from widget CSS.
        $html = Html::beginTag('div', ['class' => 'invoice cinghie-invoice']);
        $html .= $this->renderTitleRow();
        $html .= $this->renderInfoRow();
        $html .= $this->renderItemsTable();
        $html .= $this->renderTotalsRow();
        if ($this->showActions) {
            $html .= $this->renderActionsRow();
        }
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @return string
     */
    protected function renderTitleRow()
    {
        $logo = $this->renderLogo();
        $brand = Html::tag(
            'span',
            $logo . Html::encode((string) $this->companyName),
            ['class' => 'invoice-brand']
        );

        $date = '';
        if ($this->invoiceDate !== '' && $this->invoiceDate !== null) {
            $date = Html::tag(
                'small',
                Html::encode(Yii::t('traits', 'Date') . ': ' . $this->invoiceDate),
                ['class' => 'invoice-date']
            );
        }

        return '<div class="row">'
            . '<div class="col-12">'
            . Html::tag('h4', $brand . $date, ['class' => 'invoice-header'])
            . '</div></div>';
    }

    /**
     * @return string
     */
    protected function renderLogo()
    {
        if ($this->companyLogo === null || $this->companyLogo === '') {
            return '<i class="fas fa-globe"></i> ';
        }
        // Allow only a single icon tag (FA) — not arbitrary HTML.
        if (is_string($this->companyLogo)
            && preg_match('#^\s*<i\s+class="[^"]+"\s*>\s*</i>\s*$#i', $this->companyLogo)
        ) {
            return trim($this->companyLogo) . ' ';
        }
        if (is_string($this->companyLogo) && strpos($this->companyLogo, '<') !== false) {
            return '<i class="fas fa-globe"></i> ';
        }

        return Html::img($this->companyLogo, ['alt' => '']);
    }

    /**
     * @return string
     */
    protected function renderInfoRow()
    {
        return '<div class="row invoice-info">'
            . '<div class="col-sm-4 invoice-col">'
            . '<span class="invoice-col-label">' . Html::encode(Yii::t('traits', 'From')) . '</span>'
            . '<address>'
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
            ])
            . '</address></div>'
            . '<div class="col-sm-4 invoice-col">'
            . '<span class="invoice-col-label">' . Html::encode(Yii::t('traits', 'To')) . '</span>'
            . '<address>'
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
            ])
            . '</address></div>'
            . '<div class="col-sm-4 invoice-col">'
            . $this->renderMetaBlock()
            . '</div></div>';
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function isFilled($value): bool
    {
        return $value !== null && $value !== '';
    }

    /**
     * Labeled extra line (VAT, SDI, PEC, …).
     *
     * @param string $label
     * @param string $value
     * @param string|null $href mailto: or https URL
     * @return string
     */
    protected function extraLine(string $label, string $value, $href = null): string
    {
        $labelHtml = '<b>' . Html::encode($label) . ':</b> ';
        if ($href) {
            return '<span class="invoice-extra">' . $labelHtml
                . Html::a(Html::encode($value), $href) . '</span>';
        }

        return '<span class="invoice-extra">' . $labelHtml . Html::encode($value) . '</span>';
    }

    /**
     * @param string $website
     * @return string|null
     */
    protected function normalizeWebsiteHref(string $website)
    {
        $website = trim($website);
        if ($website === '') {
            return null;
        }
        if (preg_match('#^https?://#i', $website)) {
            return $website;
        }

        return 'https://' . $website;
    }

    /**
     * @param array $party keys: name, address, addressInfo, phone, mobile, fax, email,
     *                      vatCode, taxCode, sdi, pec, website
     * @return string
     */
    protected function renderAddressBlock(array $party)
    {
        $parts = [];
        $name = $party['name'] ?? '';
        $address = $party['address'] ?? '';
        $addressInfo = $party['addressInfo'] ?? '';
        $phone = $party['phone'] ?? '';
        $mobile = $party['mobile'] ?? '';
        $fax = $party['fax'] ?? '';
        $email = $party['email'] ?? '';
        $vatCode = $party['vatCode'] ?? '';
        $taxCode = $party['taxCode'] ?? '';
        $sdi = $party['sdi'] ?? '';
        $pec = $party['pec'] ?? '';
        $website = $party['website'] ?? '';

        if ($this->isFilled($name)) {
            $parts[] = '<strong>' . Html::encode($name) . '</strong>';
        }
        if ($this->isFilled($address)) {
            $parts[] = Html::encode($address);
        }
        if ($this->isFilled($addressInfo)) {
            $parts[] = Html::encode($addressInfo);
        }
        if ($this->isFilled($vatCode)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'Vat Code'), (string) $vatCode);
        }
        if ($this->isFilled($taxCode)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'Tax Code'), (string) $taxCode);
        }
        if ($this->isFilled($sdi)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'SDI'), (string) $sdi);
        }
        if ($this->isFilled($pec)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'PEC'), (string) $pec, 'mailto:' . $pec);
        }
        if ($this->isFilled($phone)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'Phone'), (string) $phone);
        }
        if ($this->isFilled($mobile)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'Mobile'), (string) $mobile);
        }
        if ($this->isFilled($fax)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'Fax'), (string) $fax);
        }
        if ($this->isFilled($email)) {
            $parts[] = $this->extraLine(Yii::t('traits', 'Email'), (string) $email, 'mailto:' . $email);
        }
        if ($this->isFilled($website)) {
            $href = $this->normalizeWebsiteHref((string) $website);
            $parts[] = $this->extraLine(Yii::t('traits', 'Website'), (string) $website, $href);
        }

        return implode('<br>', $parts);
    }

    /**
     * @return string
     */
    protected function renderMetaBlock()
    {
        $lines = [];
        if ($this->isFilled($this->invoiceNumber)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Invoice') . ' #' . $this->invoiceNumber) . '</b>';
            $lines[] = '';
        }
        if ($this->isFilled($this->invoiceType)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Type')) . ':</b> '
                . Html::encode($this->invoiceType);
        }
        if ($this->isFilled($this->invoiceOrderID)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Order ID')) . ':</b> '
                . Html::encode($this->invoiceOrderID);
        }
        if ($this->isFilled($this->invoicePaymentDue)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Payment Due')) . ':</b> '
                . Html::encode($this->invoicePaymentDue);
        }
        if ($this->isFilled($this->invoiceSent)) {
            $lines[] = '<b>' . Html::encode(Yii::t('crm', 'Invoice sent')) . ':</b> '
                . Html::encode($this->invoiceSent);
        }
        if ($this->isFilled($this->invoicePaid)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Paid')) . ':</b> '
                . Html::encode($this->invoicePaid);
        }
        if ($this->isFilled($this->invoiceAccount)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Account')) . ':</b> '
                . Html::encode($this->invoiceAccount);
        }
        if ($this->isFilled($this->invoicePaymentMethod)) {
            $method = $this->invoicePaymentMethod;
            if ($this->isFilled($this->invoicePaymentMethodCode)) {
                $method .= ' (' . $this->invoicePaymentMethodCode . ')';
            }
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Payment Method')) . ':</b> '
                . Html::encode($method);
        } elseif ($this->isFilled($this->invoicePaymentMethodCode)) {
            $lines[] = '<b>' . Html::encode(Yii::t('traits', 'Payment Method')) . ':</b> '
                . Html::encode($this->invoicePaymentMethodCode);
        }

        return implode('<br>', $lines);
    }

    /**
     * Normalize a line-item row to display columns.
     *
     * @param array $item
     * @return array{product:string,serial:string,description:string,price:string,qty:string,subtotal:string}
     */
    public static function normalizeItem(array $item): array
    {
        $hasProduct = array_key_exists('product', $item) && $item['product'] !== '' && $item['product'] !== null;
        $product = $hasProduct
            ? (string) $item['product']
            : (string) ($item['description'] ?? '');
        $description = '';
        if ($hasProduct) {
            $description = (string) ($item['detail'] ?? $item['description'] ?? '');
        } else {
            $description = (string) ($item['detail'] ?? '');
        }

        return [
            'product' => $product,
            'serial' => (string) ($item['serial'] ?? ''),
            'description' => $description,
            'price' => (string) ($item['product_price'] ?? $item['unit_price'] ?? $item['price'] ?? ''),
            'qty' => (string) ($item['quantity'] ?? $item['qty'] ?? ''),
            'subtotal' => (string) ($item['subtotal'] ?? $item['amount'] ?? ''),
        ];
    }

    /**
     * AdminLTE demo columns: Qty | Product | Serial # | Description | Subtotal
     *
     * @return string
     * @see https://adminlte.io/themes/v3/pages/examples/invoice.html
     */
    protected function renderItemsTable()
    {
        $html = '<div class="row"><div class="col-12 table-responsive invoice-items">'
            . '<table class="table table-striped">'
            . '<thead><tr>'
            . '<th>' . Html::encode(Yii::t('traits', 'Qty')) . '</th>'
            . '<th>' . Html::encode(Yii::t('traits', 'Product')) . '</th>'
            . '<th>' . Html::encode(Yii::t('traits', 'Serial') . ' #') . '</th>'
            . '<th>' . Html::encode(Yii::t('traits', 'Description')) . '</th>'
            . '<th>' . Html::encode(Yii::t('traits', 'Subtotal')) . '</th>'
            . '</tr></thead><tbody>';

        $items = is_array($this->invoiceItems) ? $this->invoiceItems : [];
        if ($items === []) {
            $html .= '<tr><td colspan="5" class="text-center text-muted">'
                . Html::encode(Yii::t('traits', 'No line items'))
                . '</td></tr>';
        } else {
            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $row = static::normalizeItem($item);
                $html .= '<tr>'
                    . '<td>' . Html::encode($row['qty']) . '</td>'
                    . '<td>' . Html::encode($row['product']) . '</td>'
                    . '<td>' . Html::encode($row['serial']) . '</td>'
                    . '<td>' . Html::encode($row['description']) . '</td>'
                    . '<td>' . Html::encode($row['subtotal'] !== '' ? $row['subtotal'] : $row['price']) . '</td>'
                    . '</tr>';
            }
        }

        $html .= '</tbody></table></div></div>';

        return $html;
    }

    /**
     * @return string
     */
    protected function renderTotalsRow()
    {
        $left = '';
        if ($this->invoicePaymentMethod !== '' && $this->invoicePaymentMethod !== null) {
            $left .= '<p class="lead">' . Html::encode(Yii::t('traits', 'Payment Methods')) . ':</p>'
                . '<p>' . Html::encode($this->invoicePaymentMethod) . '</p>';
        }
        if ($this->invoiceNotes !== '' && $this->invoiceNotes !== null) {
            $left .= '<p class="text-muted invoice-notes">'
                . nl2br(Html::encode($this->invoiceNotes), false)
                . '</p>';
        }

        $amountDueLabel = Yii::t('traits', 'Amount Due');
        if ($this->invoicePaymentDue !== '' && $this->invoicePaymentDue !== null) {
            $amountDueLabel .= ' ' . $this->invoicePaymentDue;
        } elseif ($this->invoicePaid !== '' && $this->invoicePaid !== null) {
            $amountDueLabel = Yii::t('traits', 'Paid') . ' ' . $this->invoicePaid;
        }

        $taxLabel = Html::encode(Yii::t('traits', 'Tax'));
        if ($this->invoiceTaxLabel !== '' && $this->invoiceTaxLabel !== null) {
            $taxLabel .= ' (' . Html::encode($this->invoiceTaxLabel) . ')';
        }

        $rows = '';
        if ($this->invoiceSubtotal !== '' && $this->invoiceSubtotal !== null) {
            $rows .= '<tr><th style="width:50%">' . Html::encode(Yii::t('traits', 'Subtotal')) . ':</th>'
                . '<td>' . Html::encode($this->invoiceSubtotal) . '</td></tr>';
        }
        if ($this->invoiceTax !== '' && $this->invoiceTax !== null) {
            $rows .= '<tr><th>' . $taxLabel . '</th>'
                . '<td>' . Html::encode($this->invoiceTax) . '</td></tr>';
        }
        if ($this->invoiceShipping !== '' && $this->invoiceShipping !== null) {
            $rows .= '<tr><th>' . Html::encode(Yii::t('traits', 'Shipping')) . ':</th>'
                . '<td>' . Html::encode($this->invoiceShipping) . '</td></tr>';
        }
        if ($this->invoiceTotal !== '' && $this->invoiceTotal !== null) {
            $rows .= '<tr><th>' . Html::encode(Yii::t('traits', 'Total')) . ':</th>'
                . '<td>' . Html::encode($this->invoiceTotal) . '</td></tr>';
        }

        $right = '';
        if ($rows !== '') {
            $right .= '<p class="lead">' . Html::encode($amountDueLabel) . '</p>'
                . '<div class="table-responsive invoice-totals"><table class="table">' . $rows . '</table></div>';
        }

        return '<div class="row invoice-summary">'
            . '<div class="col-6">' . $left . '</div>'
            . '<div class="col-6">' . $right . '</div>'
            . '</div>';
    }

    /**
     * @return string
     */
    protected function renderActionsRow()
    {
        $printUrl = $this->printUrl;
        if ($printUrl === null || $printUrl === '') {
            $printUrl = 'javascript:window.print();';
        }
        $isJsPrint = strncmp($printUrl, 'javascript:', 11) === 0;
        $printOptions = ['class' => 'btn btn-default'];
        if (!$isJsPrint) {
            $printOptions['target'] = '_blank';
            $printOptions['rel'] = 'noopener';
        }

        $html = '<div class="row no-print"><div class="col-12">'
            . Html::a(
                '<i class="fas fa-print"></i> ' . Html::encode(Yii::t('traits', 'Print')),
                $printUrl,
                $printOptions
            );

        if ($this->pdfUrl) {
            $html .= ' ' . Html::a(
                '<i class="fas fa-download"></i> ' . Html::encode(Yii::t('traits', 'Generate PDF')),
                Url::to($this->pdfUrl),
                ['class' => 'btn btn-primary float-right', 'style' => 'margin-right: 5px;']
            );
        }

        $html .= '</div></div>';

        return $html;
    }
}
