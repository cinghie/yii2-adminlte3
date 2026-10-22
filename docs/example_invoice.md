# Invoice widget contract

The **Invoice** widget renders an AdminLTE 3 (Bootstrap 4) invoice layout. Public properties are a **display contract**: any host application or feature module maps its own data onto these keys. The widget does not depend on other `cinghie` feature modules.

**Reference:** [AdminLTE 3 — Invoice](https://adminlte.io/themes/v3/pages/examples/invoice.html)

Unknown Yii widget configuration keys are rejected (`UnknownPropertyException`). Additional display lines that are not part of the named contract must use `invoiceMeta`.

All visible text is HTML-encoded. Optional URLs (logo, website, PEC/email, print/PDF, extra-meta `href`) are normalized before output.

---

## Named contract

| Property | Type | Description |
|----------|------|-------------|
| `companyName` | string | Company name (header). |
| `companyLogo` | string | Logo URL/path, or a single safe `<i class="…">` icon. |
| `allowRemoteCompanyLogo` | bool | Allow remote HTTP(S) logos (default `false`). |
| `invoiceDate` | string | Document date. |
| `invoiceFromName` / `Address` / `AddressInfo` / `Phone` / `Email` | string | Sender block. |
| `invoiceFromVatCode` / `TaxCode` / `Sdi` / `Pec` / `Website` / `Fax` | string | Sender extras (shown if set). |
| `invoiceToName` / `Address` / `AddressInfo` / `Phone` / `Email` | string | Recipient block. |
| `invoiceToVatCode` / `TaxCode` / `Sdi` / `Pec` / `Website` / `Fax` / `Mobile` | string | Recipient extras (shown if set). |
| `invoiceNumber` | string | Invoice number / code. |
| `invoiceOrderID` | string | Secondary reference (e.g. year/number). |
| `invoiceType` | string | Document type (e.g. `TD01`). |
| `invoiceAccount` | string | Account / customer reference (shown if set). |
| `invoicePaymentDue` | string | Payment due date. |
| `invoiceSent` | string | Sent-on date. |
| `invoicePaid` | string | Paid-on date. |
| `invoicePaymentMethod` | string | Payment method label. |
| `invoicePaymentMethodCode` | string | Optional method code, appended in meta. |
| `invoiceBankName` | string | Bank name. |
| `invoiceBankIban` | string | IBAN. |
| `invoiceBankSwift` | string | SWIFT/BIC. |
| `invoiceBankAbi` | string | ABI (optional). |
| `invoiceBankCab` | string | CAB (optional). |
| `invoiceItems` | array | Table rows (see below). |
| `invoiceSubtotal` / `invoiceTax` / `invoiceShipping` / `invoiceTotal` | string | Formatted money strings. |
| `invoiceTaxLabel` | string | Optional tax rate text (e.g. `22%`). |
| `invoiceNotes` | string | Notes / payment terms (plain text). |
| `invoiceMeta` | array | Extra meta rows (see below). |
| `showActions` | bool | Show print / PDF row (default `true`). |
| `printUrl` / `pdfUrl` | string\|array\|null | Action URLs or Yii routes. |

Empty strings are omitted from from/to extras, payment/bank lines, totals, and `invoiceMeta`. Fiscal extras (VAT, tax code, SDI, PEC, ABI, CAB) are optional display strings, not module-specific types.

### Line item keys

Any subset of: `name` or `product` or `description`, `serial` / `nr` / `sort`, `detail`, `product_price` / `unit_price` / `price`, `quantity` / `qty`, `subtotal` / `amount`.

### Extra meta (`invoiceMeta`)

Use this channel for host-specific lines that should not become first-class widget properties.

Each row:

```php
['label' => 'CIG', 'value' => 'Z123', 'href' => null]
```

or `[ 'CIG', 'Z123' ]`. Optional `href` is rendered only for valid HTTP(S) URLs or mailto addresses. Labels and values are encoded. At most 30 rows; label max 80 characters, value max 500.

---

## Usage

```php
<?php use cinghie\adminlte3\widgets\Invoice; ?>

<?= Invoice::widget([
    'companyName' => 'My Company',
    'invoiceDate' => date('Y-m-d'),
    'invoiceFromName' => 'From Name',
    'invoiceFromAddress' => 'Street, City',
    'invoiceFromVatCode' => 'IT12345678901',
    'invoiceToName' => 'Client Name',
    'invoiceNumber' => 'INV-001',
    'invoiceType' => 'TD01',
    'invoiceAccount' => 'Client account',
    'invoicePaymentMethod' => 'Bank transfer',
    'invoicePaymentMethodCode' => 'MP05',
    'invoiceBankName' => 'Example Bank',
    'invoiceBankIban' => 'IT60X0542811101000000123456',
    'invoiceItems' => [
        ['product' => 'Item 1', 'quantity' => 2, 'product_price' => '10.00', 'subtotal' => '20.00'],
    ],
    'invoiceSubtotal' => '20.00',
    'invoiceTax' => '0.00',
    'invoiceTotal' => '20.00',
    'invoiceMeta' => [
        ['label' => 'CIG', 'value' => 'Z123'],
    ],
]) ?>
```

Host applications should map models onto this contract (and `invoiceMeta` for leftovers) instead of passing arbitrary Active Record attributes into the widget.
