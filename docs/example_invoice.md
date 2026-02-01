# Invoice Widget

The **Invoice** widget renders an AdminLTE 3 invoice layout: company logo/name, from/to blocks, date, table of items, totals, and notes. All dynamic text is HTML-encoded for security.

**Reference:** [AdminLTE 3 – Invoice](https://adminlte.io/docs/3.0/pages/examples/invoice.html)

---

## Main properties

| Property | Type | Description |
|----------|------|-------------|
| `companyName` | string | Company name (header). |
| `companyLogo` | string | Company logo URL or path. |
| `invoiceDate` | string | Invoice date. |
| `invoiceFromName` | string | Sender name. |
| `invoiceFromAddress` | string | Sender address. |
| `invoiceFromAddressInfo` | string | Sender address (extra line). |
| `invoiceFromPhone` | string | Sender phone. |
| `invoiceFromEmail` | string | Sender email. |
| `invoiceToName` | string | Recipient name. |
| `invoiceToAddress` | string | Recipient address. |
| `invoiceToAddressInfo` | string | Recipient address (extra). |
| `invoiceToPhone` | string | Recipient phone. |
| `invoiceToEmail` | string | Recipient email. |
| `invoiceNumber` | string | Invoice number. |
| `invoiceItems` | array | Table rows (e.g. description, quantity, price, amount). |
| `invoiceSubtotal` | string | Subtotal text. |
| `invoiceTax` | string | Tax text. |
| `invoiceTotal` | string | Total text. |
| `invoiceNotes` | string | Notes / payment terms. |

---

## Usage

```php
<?php use cinghie\adminlte3\widgets\Invoice; ?>

<?= Invoice::widget([
    'companyName' => 'My Company',
    'companyLogo' => '/images/logo.png',
    'invoiceDate' => date('d/m/Y'),
    'invoiceFromName' => 'From Name',
    'invoiceFromAddress' => 'Street, City',
    'invoiceFromPhone' => '+1 234 567 890',
    'invoiceFromEmail' => 'from@example.com',
    'invoiceToName' => 'Client Name',
    'invoiceToAddress' => 'Client Address',
    'invoiceNumber' => 'INV-001',
    'invoiceItems' => [
        ['description' => 'Item 1', 'quantity' => 2, 'price' => '10.00', 'amount' => '20.00'],
        ['description' => 'Item 2', 'quantity' => 1, 'price' => '30.00', 'amount' => '30.00'],
    ],
    'invoiceSubtotal' => '50.00',
    'invoiceTax' => '0.00',
    'invoiceTotal' => '50.00',
    'invoiceNotes' => 'Payment within 30 days.',
]) ?>
```

---

## Notes

- All user-facing strings are HTML-encoded by the widget to prevent XSS.
- See the widget source for the full list of properties (e.g. optional fields, table options).
- Invoice items array structure must match what the widget expects (description, quantity, price, amount or similar keys).
