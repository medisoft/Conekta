<?php

function conekta_config()
{
    $configarray = array(
        "FriendlyName" => array("Type" => "System", "Value" => "Conekta"),
        "public_live_key" => array("FriendlyName" => "Live Publishable Key", "Type" => "text", "Size" => "20", "Description" => "Available from Conekta's website at <a href='https://manage.conekta.com/account/apikeys' title='Conekta API Keys'>this link</a>.",),
        "private_live_key" => array("FriendlyName" => "Live Secret Key", "Type" => "text", "Size" => "20", "Description" => "Available from Conekta's website at <a href='https://manage.conekta.com/account/apikeys' title='Conekta API Keys'>this link</a>.",),
        "public_test_key" => array("FriendlyName" => "Test Publishable Key", "Type" => "text", "Size" => "20", "Description" => "Available from Conekta's website at <a href='https://manage.conekta.com/account/apikeys' title='Conekta API Keys'>this link</a>.",),
        "private_test_key" => array("FriendlyName" => "Test Secret Key", "Type" => "text", "Size" => "20", "Description" => "Available from Conekta's website at <a href='https://manage.conekta.com/account/apikeys' title='Conekta API Keys'>this link</a>.",),
        "problememail" => array("FriendlyName" => "Problem Report Email", "Type" => "text", "Size" => "20", "Description" => "Enter an email that the gateway can send a message to should an alert or other serious processing problem arise.",),
        "testmode" => array("FriendlyName" => "Test Mode", "Type" => "yesno", "Description" => "Tick this to make all transactions use your test keys above.",),
        "subscriptions" => array("FriendlyName" => "Allow Subscriptions", "Type" => "yesno", "Description" => "Tick this to make all available subscriptions for automated charges.",),
        'instructions' => array(
            'FriendlyName' => 'Instrucciones de pago',
            'Type' => 'textarea',
            'Rows' => '5',
            'Description' => ''
        ),

    );
    return $configarray;
}

function conekta_link($params)
{

    # Invoice Variables
    $invoiceid = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount']; # Format: ##.##

    $warning = "false";

    # Enter your code submit to the gateway...
    $code = '<form method="post" action="conekta.php">
			<input type="hidden" name="description" value="' . $description . '" />
			<input type="hidden" name="invoiceid" value="' . $invoiceid . '" />
			<input type="hidden" name="amount" value="' . $amount . '" />
			<input type="hidden" name="frominvoice" value="true" />
			<input type="hidden" name="payfreq" value="otp" />
			<input type="hidden" name="multiple" value="' . $warning . '" />
			<input type="submit" value="Pay Now" />
			</form>';

    /*    $code .= '<form method="post" action="conekta.php">
                <input type="hidden" name="description" value="' . $description . '" />
                <input type="hidden" name="invoiceid" value="' . $invoiceid . '" />
                <input type="hidden" name="amount" value="' . $subscribe_price . '" />
                <input type="hidden" name="frominvoice" value="true" />
                <input type="hidden" name="payfreq" value="recur" />
                <input type="hidden" name="planname" value="' . $description . '" />
                <input type="hidden" name="planid" value="' . md5($description) . '" />
                <input type="hidden" name="multiple" value="' . $warning . '" />
                <input type="submit" value="Set up Automatic Payment" />
                </form>';*/

    return $code;

}

function conekta_refund($params)
{

    require_once('conekta/vendor/conekta/conekta-php/lib/Conekta.php');

    $gatewaytestmode = $params["testmode"];

    if ($gatewaytestmode == "on") {
        \Conekta\Conekta::setApiKey($params['private_test_key']);
    } else {
        \Conekta\Conekta::setApiKey($params['private_live_key']);
    }

    # Invoice Variables
    $transid = $params['transid'];

    # Perform Refund
    try {
        $ch = Conekta_Charge::retrieve($transid);
        $ch->refund();
        return array("status" => "success", "transid" => $ch["id"], "rawdata" => $ch);
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
        return array("status" => "error", "rawdata" => $response['error']);
    }

}

// Codigo para realizar cargos automaticos al cliente. La tarjeta deberia ser guardada tokenizada
function conekta_capture($params)
{
    require_once('conekta/vendor/conekta/conekta-php/lib/Conekta.php');

    $gatewaytestmode = $params["testmode"];

    if ($gatewaytestmode == "on") {
        \Conekta\Conekta::setApiKey($params['private_test_key']);
    } else {
        \Conekta\Conekta::setApiKey($params['private_live_key']);
    }

    # Variables de la Factura
    $invoiceid = $params['invoiceid'];
    $amount = $params['amount'];
    $currency = $params['currency'];

    # Variables del cliente
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    # Informacion de la Tarjeta
    $cardtype = $params['cardtype'];
    $cardnumber = $params['cardnum'];
    $cardexpiry = $params['cardexp'];
    $cardissuenum = $params['cccvv'];

    $results = array();

    # Preparamos todos los parametros para enviar a Conekta.io
    $card_num = $cardnumber;
    $card_cvv = $cardissuenum;
    $card_exp_month = substr($cardexpiry, 0, 2);
    $card_exp_year = substr($cardexpiry, 2, 4);
    $card_name = $firstname . ' ' . $lastname;

    $data_amount = str_replace('.', '', $amount);
    $data_currency = strtolower($currency);
    $data_description = 'Pago Factura No. ' . $invoiceid;


    # Arraglo con informacion de tarjeta
    $card = array(
        'number' => $card_num,
        'exp_month' => intval($card_exp_month),
        'exp_year' => intval('20' . $card_exp_year),
        'cvc' => intval($card_cvv),
        'name' => $card_name,
        'address' => array(
            'street1' => $address1,
            'street2' => $address2,
            'city' => $city,
            'state' => $state,
            'zip' => $postcode,
            'country' => $country
        )
    );
    try {
        $conekta = array(
            'card' => $card,
            'description' => $data_description,
            'amount' => intval($data_amount),
            'currency' => $data_currency,
            'details' => array(
                'email' => $email,
                'phone' => $phone,
                'name' => $firstname . ' ' . $lastname,
                'phone' => $phone,
                'line_items' => array(
                    array(
                        'name' => $data_description,
                        'sku' => $invoiceid,
                        'unit_price' => intval($data_amount),
                        'description' => $data_description,
                        'quantity' => 1,
                        'type' => 'service-purchase'
                    )
                )

            )
        );

        $charge = \Conekta\Charge::create($conekta);

        # Transaccion Correcta
        $data = json_decode($charge);
        $results['status'] = 'success';
        $results['transid'] = $data->payment_method->auth_code;
        $results['data'] = 'OK';
    } catch (Exception $e) {
        # Transaccion Declinada
        $results['status'] = 'declined';
        $results['transid'] = 'error'; //$data->payment_method->auth_code;
        $results['data'] = $e->getMessage();
    }

    # Validamos los resultados
    if ($results['status'] == 'success') {
        return array('status' => 'success', 'transid' => $results['transid'], 'rawdata' => 'OK');
    } elseif ($results['status'] == 'declined') {
        return array('status' => 'declined', 'rawdata' => $results);
    } else {
        return array('status' => 'error', 'rawdata' => $results);
    }
}

?>