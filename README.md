# invoiceplane-templates

Custom invoice/quote templates for [InvoicePlane](https://invoiceplane.com) with a hack to render the generated documents in a client-specific currency.

Costs/prices are still entered in **USD**; templates convert and display them in the currency set on each client.

The conversion rate is stored on the invoice via a custom field ("Conversion Rate"), so a saved invoice always shows the rate that was used at billing time, even if rates change later. The rate can also be entered manually.

## Compatibility

These files **override InvoicePlane core files** (notably `application/modules/invoices/views/view.php`). They were built against a specific InvoicePlane release — verify the version you run before installing, and re-check `view.php` after any InvoicePlane upgrade, since updates will overwrite it.

## Installation

1. **BACKUP your database** (creating/saving custom fields has historically wiped other custom fields' data in some IP versions).
2. Add the custom fields listed below via IP settings.
3. Copy the `assets` and `application` folders over your InvoicePlane installation, merging with existing folders.

## Instructions

1. Select the templates under invoice/quote system settings. Stock IP templates are prefixed "InvoicePlane"; these are prefixed **"Billing Template"** / **"Quote Template"**.
2. Set the **Currency** custom field on each client to the ISO currency code you want their documents rendered in (e.g. `EUR`, `INR`, `AUD`).

### Conversion rate behaviour

* When viewing an invoice in the admin panel, a helper box shows the current USD → client-currency rate fetched from [open.er-api.com](https://open.er-api.com) (Exchange Rate API). Copy it into the invoice's **Conversion Rate** custom field before sending.
* Rates are cached for 12 hours per currency to avoid hitting the API on every page load.
* If no rate is available (API down, unknown currency code), templates fall back to displaying amounts in USD.

## Payment methods supported

Templates print matching bank/payment details based on the invoice's payment method:

* PayPal (uses the user's **PayPal.Me Link** custom field)
* Bank Transfer (India: IFSC / BIC-Swift)
* Bank Transfer - US (ACH / FEDWIRE)
* Bank Transfer - AU

## Custom fields

### User **Invoice** table

| Field | Section |
|---|---|
| Conversion Rate | Custom Fields |

### Under **Client** table

| Field | Section |
|---|---|
| Currency | Custom Fields |

### Under **User** table

| Field | Section |
|---|---|
| PAN | Taxes Information |

#### Bank details (India)

Bank Name, Bank Branch State, Bank Branch City, Bank Branch Name, Account Number, IFSC, BIC/Swift Code, Currency to be sent in, PayPal.Me Link — all in section *Custom Fields*

#### US Bank details

US Bank Name, US Account Number, US Beneficiary Address, US ACH Routing Number, US FEDWIRE Routing Number, US Account Type, US Account Name — all in section *Custom Fields*

#### AU Bank details

AU Bank Name, AU Account Number, AU Beneficiary Address, AU Routing Number, AU Account Type, AU Account Name — all in section *Custom Fields*

## Template files

```
application/views/
├── template_helpers/BillingTemplateHelper.php   # shared formatting helpers
├── invoice_templates/pdf/BillingTemplate_{initial,overdue,paid}.php
├── invoice_templates/pdf/_BillingTemplate.php   # shared invoice PDF markup
├── invoice_templates/public/BillingTemplate_Web.php
├── quote_templates/pdf/QuoteTemplate.php
└── quote_templates/public/QuoteTemplate_Web.php
```

Status variants (`initial` / `overdue` / `paid`) are thin wrappers that set `$invoice_status` and include `_BillingTemplate.php`; colour highlighting of dates/amounts is driven by that variable.

## Code Snippets

Dump all contents of an array (e.g. `$invoice`) while debugging a template:

```php
<!-- TEST -->
<pre><?php print_r($invoice); ?></pre>
<!-- /TEST -->
```

Remove these blocks before sending documents to clients.
