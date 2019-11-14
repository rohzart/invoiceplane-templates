# invoiceplane-templates

Custom templates to use with InvoicePlane

## Custom fields

### Under *Client* table
*Currency* positioned in section *Custom Fields*

### Under *User* table
*PAN* positioned in section *Taxes Information*
*Bank Name* positioned in section *Custom Fields*
*Bank Branch State* positioned in section *Custom Fields*
*Bank Branch City* positioned in section *Custom Fields*
*Bank Branch Name* positioned in section *Custom Fields*
*Account Number* positioned in section *Custom Fields*
*IFSC* positioned in section *Custom Fields*
*BIC/Swift Code* positioned in section *Custom Fields*
*Currency to be sent in* positioned in section *Custom Fields*


## Possible bugs
* Add new custom fields, save values, and all other previously created custom fields' data is lost

## Code Snippets

This will help list all the content of an array (in this case, $invoice)
```
<!-- TEST -->
<pre><?php 
print_r($invoice); 
?></pre>
<!-- /TEST -->
```