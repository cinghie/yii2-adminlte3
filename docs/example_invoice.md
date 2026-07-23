# Invoice Widget

The **Invoice** widget renders an AdminLTE 3 (Bootstrap 4) invoice layout: company logo/name, from/to blocks, meta (number, type, due/sent/paid, account, payment method), line items table, totals, notes, and print actions.

**Reference:** [AdminLTE 3 — Invoice](https://adminlte.io/themes/v3/pages/examples/invoice.html)

Extra fiscal fields (VAT, tax code, SDI, PEC, website, fax/mobile) are rendered only when non-empty — they are not part of the official demo but are useful for Italian e-invoicing.

---

## Main properties

| Property | Type | Description |
|----------|------|-------------|
| `companyName` | string | Company name (header). |
| `companyLogo` | string | Logo URL/path, or a single safe `<i class="…">` icon. |
| `invoiceDate` | string | Invoice date. |
| `invoiceFromName` / `Address` / `AddressInfo` / `Phone` / `Email` | string | Sender block. |
| `invoiceFromVatCode` / `TaxCode` / `Sdi` / `Pec` / `Website` / `Fax` | string | Sender fiscal extras (shown if set). |
| `invoiceToName` / `Address` / `AddressInfo` / `Phone` / `Email` | string | Recipient block. |
| `invoiceToVatCode` / `TaxCode` / `Sdi` / `Pec` / `Website` / `Fax` / `Mobile` | string | Recipient fiscal extras (shown if set). |
| `invoiceNumber` | string | Invoice number / code. |
| `invoiceOrderID` | string | Secondary reference (e.g. year/number). |
| `invoiceType` | string | Document type (e.g. `TD01`). |
| `invoicePaymentDue` | string | Payment due date. |
| `invoiceSent` | string | Sent-on date. |
| `invoicePaid` | string | Paid-on date (shown as Paid, not Due). |
| `invoiceAccount` | string | Account / customer reference. |
| `invoicePaymentMethod` | string | Payment method label. |
| `invoicePaymentMethodCode` | string | Optional code (e.g. `MP05`), appended in meta. |
| `invoiceItems` | array | Table rows (see below). |
| `invoiceSubtotal` / `invoiceTax` / `invoiceShipping` / `invoiceTotal` | string | Formatted money strings. |
| `invoiceTaxLabel` | string | Optional tax rate text (e.g. `22%`). |
| `invoiceNotes` | string | Notes / payment terms (plain text). |
| `showActions` | bool | Show print / PDF row (default `true`). |
| `printUrl` / `pdfUrl` | string|null | Action URLs. |

### Line item keys

Any subset of: `product` or `description`, `serial`, `detail`, `product_price` / `unit_price` / `price`, `quantity` / `qty`, `subtotal` / `amount`.

---

## Usage

```php
<?php use cinghie\adminlte3\widgets\Invoice; ?>

<?= Invoice::widget([
    'companyName' => 'My Company',
    'invoiceDate' => date('d/m/Y'),
    'invoiceFromName' => 'From Name',
    'invoiceFromAddress' => 'Street, City',
    'invoiceFromVatCode' => 'IT12345678901',
    'invoiceFromSdi' => 'XXXXXXX',
    'invoiceFromPec' => 'company@pec.example',
    'invoiceToName' => 'Client Name',
    'invoiceToVatCode' => 'IT98765432109',
    'invoiceToSdi' => 'ABCDEFG',
    'invoiceToPec' => 'client@pec.example',
    'invoiceNumber' => 'INV-001',
    'invoiceType' => 'TD01',
    'invoicePaymentMethod' => 'Bonifico',
    'invoicePaymentMethodCode' => 'MP05',
    'invoiceItems' => [
        ['product' => 'Item 1', 'quantity' => 2, 'product_price' => '10.00', 'subtotal' => '20.00'],
    ],
    'invoiceSubtotal' => '20.00',
    'invoiceTax' => '0.00',
    'invoiceTotal' => '20.00',
]) ?>
```

In **yii2-crm**, use `cinghie\crm\widgets\Invoice` (BS4 → this widget; BS3 → `InvoiceBootstrap3`). Invoice view builds config via `Invoices::getInvoiceWidgetConfig()` (maps account/contact VAT, SDI, PEC, etc.) and optional module `$invoiceCompany` for the From block.
