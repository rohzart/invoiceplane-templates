# invoiceplane-templates

Custom templates to use with InvoicePlane with a hack to generate the templates in a set currency.

This means that you'll still feed in the cost/price values in USD.

Custom invoice field is used to save the conversion rate along with the invoice. The conversion rate can also be manually entered.

**Known issues** : 

* ~~No caching of exchange rates.~~
* ~~Therefore each time the template script is run, for the invoice generated, it fetches and uses the latest exchange rate provided by the api.~~

## Possible bug in IP

* All other previously created custom fields' data is lost after creating and saving new custom fields. BACKUP database.

## Installation

1. BACKUP database
2. Add the below listed custom fields via settings
3. Place the copy over the assets and application folders

## Instructions

Select these templates in the invoice or quote settings under system settings. Default templates are prefixed with "InvoicePlane". These are prefixed with "Billing Template" or "Quote Template".

Edit/Update "Currency" custom field with the currency code you wish to have the generated templates use.

## New Payment Methods

- PayPal
- Bank Transfer - US

## Custom fields

### User **Invoice** table

**Conversion Rate** positioned in section *Custom Fields*

### Under **Client** table

**Currency** positioned in section *Custom Fields*

### Under **User** table

**PAN** positioned in section *Taxes Information*

#### Bank details

**Bank Name** positioned in section *Custom Fields*

**Bank Branch State** positioned in section *Custom Fields*

**Bank Branch City** positioned in section *Custom Fields*

**Bank Branch Name** positioned in section *Custom Fields*

**Account Number** positioned in section *Custom Fields*

**IFSC** positioned in section *Custom Fields*

**BIC/Swift Code** positioned in section *Custom Fields*

**Currency to be sent in** positioned in section *Custom Fields*

**PayPal.Me Link** positioned in section *Custom Fields*

#### US Bank details

**US Bank Name** positioned in section *Custom Fields*

**US Account Number** positioned in section *Custom Fields*

**US Beneficiary Address** positioned in section *Custom Fields*

**US ACH Routing Number** positioned in section *Custom Fields*

**US FEDWIRE Routing Number** positioned in section *Custom Fields*

**US Account Type** positioned in section *Custom Fields*

**US Account Name** positioned in section *Custom Fields*

#### AU Bank details

**AU Bank Name** positioned in section *Custom Fields*

**AU Account Number** positioned in section *Custom Fields*

**AU Beneficiary Address** positioned in section *Custom Fields*

**AU Routing Number** positioned in section *Custom Fields* 

**AU Account Type** positioned in section *Custom Fields*

**AU Account Name** positioned in section *Custom Fields*

## Code Snippets

This will help list all the content of an array (in this case, $invoice)

```
<!-- TEST -->
<pre><?php 
print_r($invoice); 
?></pre>
<!-- /TEST -->
```
