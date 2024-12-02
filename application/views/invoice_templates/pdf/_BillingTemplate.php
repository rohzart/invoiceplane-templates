<?php
    $user_address_array = array(
        htmlsc($invoice->user_address_1), 
        htmlsc($invoice->user_address_2), 
        htmlsc($invoice->user_city), 
        htmlsc($invoice->user_state), 
        htmlsc($invoice->user_zip),
        get_country_name(trans('cldr'), $invoice->user_country)
    );
    $user_contact_array = array(
        'Mobile' => $invoice->user_mobile, 
        trans('phone_abbr') => htmlsc($invoice->user_phone), 
        trans('fax_abbr') => htmlsc($invoice->user_fax), 
        'Email' => $invoice->user_email
    );
    $user_tax_details_array = array(
        // CUSTOM FIELD
        'PAN' => htmlsc($custom_fields['user']['PAN']),
        // /CUSTOM FIELD
        trans('vat_id_short') => $invoice->user_vat_id,
        trans('tax_code_short') => $invoice->user_tax_code
    );
    // CUSTOM FIELD
    if ($payment_method->payment_method_id == 3){
        $user_bank_details_array  = array(
            'Bank Name' => htmlsc($custom_fields['user']['Bank Name']),
            'Bank Branch State' => htmlsc($custom_fields['user']['Bank Branch State']),
            'Bank Branch City' => htmlsc($custom_fields['user']['Bank Branch City']),
            'Bank Branch Name' => htmlsc($custom_fields['user']['Bank Branch Name']),
            'Account Number' => htmlsc($custom_fields['user']['Account Number']),
            'IFSC' => htmlsc($custom_fields['user']['IFSC']),
            'BIC/Swift Code' => htmlsc($custom_fields['user']['BIC/Swift Code']),
            'Currency to be sent in' => htmlsc($custom_fields['user']['Currency to be sent in'])
        );
    }
    // /CUSTOM FIELD
    $client_address_array = array(
        htmlsc($invoice->client_address_1), 
        htmlsc($invoice->client_address_2), 
        htmlsc($invoice->client_city), 
        htmlsc($invoice->client_state), 
        htmlsc($invoice->client_zip),
        get_country_name(trans('cldr'), $invoice->client_country)
    );
    $client_contact_array = array(
        'Mobile' => $invoice->client_mobile,
        trans('phone_abbr') => htmlsc($invoice->client_phone),
        'Email' => $invoice->client_email
    );
    $client_tax_details_array = array(
        trans('vat_id_short') => $invoice->client_vat_id, 
        trans('tax_code_short') => $invoice->client_tax_code
    );
?>
<!DOCTYPE html>
<html lang="<?php _trans('cldr'); ?>">
<head>
    <meta charset="utf-8">
    <title><?php _trans('invoice'); ?></title>
    <link rel="stylesheet"
          href="<?php echo base_url(); ?>assets/Billing/css/templates.css">
</head>
<body>
<header class="clearfix">

    <div id="logo">
        <?php echo invoice_logo_pdf(); ?>
    </div>

    <div id="company">

        <div class="name">
            <?php _htmlsc($invoice->user_name); ?>
        </div>

        <div class="address">
            Address: 
            <?php print_array_safely(", ", $user_address_array); ?>
        </div>

        <div class="contact">
            <?php print_array_key_value_safely(' | ', ': ', $user_contact_array); ?>
        </div>

        <div class="tax-id">
            <i>
            <?php print_array_key_value_safely(' | ', ': ', $user_tax_details_array); ?>
            </i>
        </div>

    </div>

    <div id="title"> 
        Bill
    </div>

    <div id="client">

        <div>
            To,
        </div>

        <div class="name">
            <?php _htmlsc(format_client($invoice)); ?>
        </div>
        
        <div class="address">
            <?php print_array_safely(", ", $client_address_array); ?>
        </div>

        <div class="contact">
            <?php print_array_key_value_safely(' | ', ': ', $client_contact_array); ?>
        </div> 

        <div class="tax-id">
            <i>
            <?php print_array_key_value_safely(' | ', ': ', $client_tax_details_array); ?>
            </i>
        </div>

    </div>

    <div id="invoice-summary">
        <table>
            <tr>
                <td>Invoice #</td>
                <td><?php echo $invoice->invoice_number; ?></td>
            </tr>
			<?php if ($invoice->quote_id): ?>
            <tr>
                <td>Quote #</td>
                <td><?php echo $invoice->quote_id; ?></td>
            </tr>
			<?php endif; ?>
            <tr>
                <td class="<?php echo highlight_by_status('invoice_date', $invoice_status); ?>"><?php echo trans('invoice_date') . ':'; ?></td>
                <td class="<?php echo highlight_by_status('invoice_date', $invoice_status); ?>"><?php echo date_from_mysql($invoice->invoice_date_created, true); ?></td>
            </tr>
            <tr>
                <td class="<?php echo highlight_by_status('due_date', $invoice_status); ?>"><?php echo trans('due_date') . ': '; ?></td>
                <td class="<?php echo highlight_by_status('due_date', $invoice_status); ?>"><?php echo date_from_mysql($invoice->invoice_date_due, true); ?></td>
            </tr>
            <tr>
                <td class="<?php echo highlight_by_status('amount_due', $invoice_status); ?>"><?php echo trans('amount_due') . ': '; ?></td>
                <td class="<?php echo highlight_by_status('amount_due', $invoice_status); ?>"><?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_balance); ?></td>
            </tr>
			<?php if ($payment_method): ?>
            <tr>
                <td><?php echo trans('payment_method') . ': '; ?></td>
                <td><?php _htmlsc($payment_method->payment_method_name); ?></td>
            </tr>
			<?php endif; ?>
        </table>
    </div>

</header>

<main>
 
    <table class="item-table">
        <thead>
        <tr>
            <th class="item-name"><?php _trans('item'); ?></th>
            <th class="item-desc"><?php _trans('description'); ?></th>
            <th class="item-price text-right"><?php _trans('price'); ?></th>
            <th class="item-amount text-right"><?php _trans('qty'); ?></th>
            <?php if ($show_item_discounts) : ?>
                <th class="item-discount text-right"><?php _trans('discount'); ?></th>
            <?php endif; ?>
            <th class="item-total text-right"><?php _trans('total'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($items as $item) { ?>
            <tr>
                <td><?php _htmlsc($item->item_name); ?></td>
                <td><?php echo nl2br(htmlsc($item->item_description)); ?></td>
                <td class="text-right">
                    <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $item->item_price); ?>
                </td>
                <td class="text-right">
                    <?php echo format_amount($item->item_quantity); ?>
                    <?php if ($item->item_product_unit) : ?>
                        <br>
                        <small><?php _htmlsc($item->item_product_unit); ?></small>
                    <?php endif; ?>
                </td>
                <?php if ($show_item_discounts) : ?>
                    <td class="text-right">
                        <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $item->item_discount); ?>
                    </td>
                <?php endif; ?>
                <td class="text-right">
                    <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $item->item_total); ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
        <tbody class="invoice-sums">

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <?php _trans('subtotal'); ?>
            </td>
            <td class="text-right"><?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_item_subtotal); ?></td>
        </tr>

        <?php if ($invoice->invoice_item_tax_total > 0) { ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('item_tax'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_item_tax_total); ?>
                </td>
            </tr>
        <?php } ?>

        <?php foreach ($invoice_tax_rates as $invoice_tax_rate) : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php echo htmlsc($invoice_tax_rate->invoice_tax_rate_name) . ' (' . format_amount($invoice_tax_rate->invoice_tax_rate_percent) . '%)'; ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice_tax_rate->invoice_tax_rate_amount); ?>
                </td>
            </tr>
        <?php endforeach ?>

        <?php if ($invoice->invoice_discount_percent != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_amount($invoice->invoice_discount_percent); ?>%
                </td>
            </tr>
        <?php endif; ?>
        <?php if ($invoice->invoice_discount_amount != '0.00') : ?>
            <tr>
                <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                    <?php _trans('discount'); ?>
                </td>
                <td class="text-right">
                    <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_discount_amount); ?>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <b><?php _trans('total'); ?></b>
            </td>
            <td class="text-right">
                <b><?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_total); ?></b>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right">
                <?php _trans('paid'); ?>
            </td>
            <td class="text-right">
                <?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_paid); ?>
            </td>
        </tr>
        <tr>
            <td <?php echo($show_item_discounts ? 'colspan="5"' : 'colspan="4"'); ?> class="text-right <?php echo highlight_by_status('balance', $invoice_status); ?>">
                <b><?php _trans('balance'); ?></b>
            </td>
            <td class="text-right <?php echo highlight_by_status('balance', $invoice_status); ?>">
                <b><?php echo format_currency_by_client_setting($client_currency, $conversion_rate, $invoice->invoice_balance); ?></b>
            </td>
        </tr>
        </tbody>
    </table>
    <div id="attribution" class="text-right">
        <?php echo conversion_api_attribution($conversion_rate); ?>
    </div>
</main>

<footer>
    <?php if ($payment_method): ?>
        <?php if ($payment_method->payment_method_name == 'Bank Transfer') : ?>
            <div id="bank_details">
                <b>My bank details are as below:</b><br/>
                <?php print_array_key_value_safely(' <br /> ', ': ', $user_bank_details_array); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($invoice->invoice_terms) : ?>
        <div id="notes">
            <b><?php _trans('terms'); ?></b><br/>
            <?php echo nl2br(htmlsc($invoice->invoice_terms)); ?>
        </div>
    <?php endif; ?>
    <div id="thank-you">
        Thank you
    </div>
</footer>

</body>
</html>