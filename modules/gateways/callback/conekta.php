<?php

// Copyright (c) 2013, Carlos Cesar Peña Gomez <CarlosCesar110988@gmail.com>
//
// Permission to use, copy, modify, and/or distribute this software for any
// purpose with or without fee is hereby granted, provided that the above copyright
// notice and this permission notice appear in all copies.

// THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL
// WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED
// WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL
// THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL
// DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA
// OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR
// OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE
// USE OR PERFORMANCE OF THIS SOFTWARE.

@mkdir("conekta_logs", 0777);

# Required File Includes
include("../../../init.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");
require_once("../conekta/vendor/conekta/conekta-php/lib/Conekta.php");

$gatewaymodule = "conekta"; # Enter your gateway module name here replacing template

$GATEWAY = getGatewayVariables($gatewaymodule);
if (!$GATEWAY["type"]) {
    die("Module Not Activated");
} # Checks gateway module is active before accepting callback

$gatewaytestmode = $gateway['testmode'];
if ($gatewaytestmode == "on") {
    \Conekta\Conekta::setApiKey($GATEWAY['private_test_key']);
} else {
    \Conekta\Conekta::setApiKey($GATEWAY['private_live_key']);
}

// Webhook

$result = @file_get_contents('php://input');

$json = json_decode($result);
$json = $json->data->object;

$invoiceid = $json->reference_id;
$fee = $json->fee;
$amount = $json->amount;
$status = $json->status;
$transid = $json->id;

logModuleCall('conekta', 'callback', $result, 'ResponseData', 'ProcessedData', array());
// Validamos que el IPN sea de Banorte
if ($json->payment_method->type == 'credit' || $json->payment_method->type == 'debit') {
    // Guardar Log de webhook (comentar esto para no guardar logs)
    if ($json->payment_method->type == 'credit')
        file_put_contents('conekta_logs/credit_' . md5(uniqid()) . ".txt", $result . "\n", FILE_APPEND);
    else
        file_put_contents('conekta_logs/debit_' . md5(uniqid()) . ".txt", $result . "\n", FILE_APPEND);

    /*    try {
            $event = \Conekta\Event::retrieve($event_id);
            $retrieved_invoice = \Conekta\Invoice::retrieve($invoice_id)->lines->all(array('count' => 1, 'offset' => 0));
        } catch (Exception $e) {
            mail($gateway["problememail"], "Conekta Failed Callback", "A problem prevented Conekta from properly processing an incoming payment webhook:" . $e);
        }*/


    // Convertimos montos con decimales
    $amount_2 = substr($amount, 0, -2);
    $decimals_2 = substr($amount, strlen($amount_2), strlen($amount));
    $amount = $amount_2 . '.' . $decimals_2;

    $amount_3 = substr($fee, 0, -2);
    $decimals_3 = substr($fee, strlen($amount_3), strlen($fee));
    $fee = $amount_3 . '.' . $decimals_3;

    $invoiceid = str_replace('factura_', '', $invoiceid);

    if ($status == 'paid') {
        $status = 1;
    } else {
        $status = 0;
    }

    $invoiceid = checkCbInvoiceID($invoiceid, $GATEWAY["name"]); # Checks invoice ID is a valid invoice number or ends processing

    checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

    if ($status == "1") {
        # Successful
        addInvoicePayment($invoiceid, $transid, $amount, $fee, $gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
        logTransaction($GATEWAY["name"], $result, "Successful"); # Save to Gateway Log: name, data array, status
    } else {
        # Unsuccessful
        logTransaction($GATEWAY["name"], $result, "Unsuccessful"); # Save to Gateway Log: name, data array, status
    }
} else if ($json->payment_method->object == 'bank_transfer_payment') {
    // Guardar Log de webhook (comentar esto para no guardar logs)
    $fp = fopen('conekta_logs/spei_' . md5(uniqid()) . ".txt", "wb");
    fwrite($fp, $result);
    fclose($fp);

    // Convertimos montos con decimales
    $amount_2 = substr($amount, 0, -2);
    $decimals_2 = substr($amount, strlen($amount_2), strlen($amount));
    $amount = $amount_2 . '.' . $decimals_2;

    $amount_3 = substr($fee, 0, -2);
    $decimals_3 = substr($fee, strlen($amount_3), strlen($fee));
    $fee = $amount_3 . '.' . $decimals_3;

    $invoiceid = str_replace('factura_', '', $invoiceid);

    if ($status == 'paid') {
        $status = 1;
    } else {
        $status = 0;
    }

    $invoiceid = checkCbInvoiceID($invoiceid, $GATEWAY["name"]); # Checks invoice ID is a valid invoice number or ends processing

    checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

    if ($status == "1") {
        # Successful
        addInvoicePayment($invoiceid, $transid, $amount, $fee, $gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
        logTransaction($GATEWAY["name"], $result, "Successful"); # Save to Gateway Log: name, data array, status
    } else {
        # Unsuccessful
        logTransaction($GATEWAY["name"], $result, "Unsuccessful"); # Save to Gateway Log: name, data array, status
    }
} else if ($json->payment_method->object == 'cash_payment') {
    // Guardar Log de webhook (comentar esto para no guardar logs)
    $fp = fopen('conekta_logs/oxxo_' . md5(uniqid()) . ".txt", "wb");
    fwrite($fp, $result);
    fclose($fp);

    // Convertimos montos con decimales
    $amount_2 = substr($amount, 0, -2);
    $decimals_2 = substr($amount, strlen($amount_2), strlen($amount));
    $amount = $amount_2 . '.' . $decimals_2;

    $amount_3 = substr($fee, 0, -2);
    $decimals_3 = substr($fee, strlen($amount_3), strlen($fee));
    $fee = $amount_3 . '.' . $decimals_3;

    $invoiceid = str_replace('factura_', '', $invoiceid);

    if ($status == 'paid') {
        $status = 1;
    } else {
        $status = 0;
    }

    $invoiceid = checkCbInvoiceID($invoiceid, $GATEWAY["name"]); # Checks invoice ID is a valid invoice number or ends processing

    checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

    if ($status == "1") {
        # Successful
        addInvoicePayment($invoiceid, $transid, $amount, $fee, $gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
        logTransaction($GATEWAY["name"], $result, "Successful"); # Save to Gateway Log: name, data array, status
    } else {
        # Unsuccessful
        logTransaction($GATEWAY["name"], $result, "Unsuccessful"); # Save to Gateway Log: name, data array, status
    }
} else {
    echo $json->payment_method->type;
    $fp = file_put_contents('conekta_logs/unknown_' . md5(uniqid()) . ".txt", $result . "\n", FILE_APPEND);
}

header("HTTP/1.0 200");
?>