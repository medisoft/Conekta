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
        Conekta::setApiKey($params['private_test_key']);
    } else {
        Conekta::setApiKey($params['private_live_key']);
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

?>