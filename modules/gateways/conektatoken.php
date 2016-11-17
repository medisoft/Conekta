<?php

// Copyright (c) 2013, Carlos Cesar Peña Gomez <CarlosCesar110988@gmail.com>
//
// Permission to use, copy, modify, and/or distribute this software for any 
// purpose with or without fee is hereby granted, provided that the above copyright 
// notice and this permission notice appear in all copies.

// THE SOFTWARE IS PROVIDED 'AS IS' AND THE AUTHOR DISCLAIMS ALL 
// WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED 
// WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL 
// THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL 
// DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA 
// OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR 
// OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE 
// USE OR PERFORMANCE OF THIS SOFTWARE.

function conektatoken_config()
{
    $configarray = array(
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Conekta Card con Token'
        ),
        'private_key' => array(
            'FriendlyName' => 'Llave Privada',
            'Type' => 'text',
            'Size' => '50'
        ),
        'public_key' => array(
            'FriendlyName' => 'Llave Publica',
            'Type' => 'text',
            'Size' => '50'
        ),
        'private_test_key' => array(
            'FriendlyName' => 'Llave Privada Sandbox',
            'Type' => 'text',
            'Size' => '50'
        ),
        'public_test_key' => array(
            'FriendlyName' => 'Llave Publica Sandbox',
            'Type' => 'text',
            'Size' => '50'
        ),
        'days_expire' => array(
            'FriendlyName' => 'Dias para Expirar',
            'Type' => 'text',
            'Size' => '2',
        ),
        'instructions' => array(
            'FriendlyName' => 'Instrucciones de pago',
            'Type' => 'textarea',
            'Rows' => '5',
            'Description' => ''
        ),
        "testmode" => array(
            "FriendlyName" => "Sandbox",
            "Type" => "yesno",
            "Description" => "Usa la llave de sandbox para probar el sistema",
        ),

    );
    return $configarray;
}

function conektatoken_capture($params)
{
    require_once('conekta/vendor/conekta/conekta-php/lib/Conekta.php');
}

function conektatoken_link($params)
{

    # Variables de Conekta
    $private_key = $params['testmode'] ? $params['private_test_key'] : $params['private_key'];
    $public_key = $params['testmode'] ? $params['public_test_key'] : $params['public_key'];


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

    # Dias para que expire el Pago
    $days_expire = $params['days_expire'];

    # Si el parametro viene vacio lo fijamos a 5 dias
    if (strlen($days_expire) == 0) {
        $days_expire = 5;
    }

    # AAAA-MM-DD

    $date = time();
    $expires = date('Y-m-d', strtotime('+' . $days_expire . ' day', $date));

    $results = array();

    # Preparamos todos los parametros para enviar a Conekta.io

    $data_amount = str_replace('.', '', $amount);
    $data_currency = strtolower($currency);
    $data_description = 'Pago Factura No. ' . $invoiceid;

    /*
        # Incluimos la libreria de Conecta 2.0

        require_once('conekta/lib_2.0/Conekta.php');

        # Creamos el Objeto de Cargo
        Conekta::setApiKey($private_key);

        # Arraglo con informacion de tarjeta
        $conekta = array(
            'description' => $data_description,
            'reference_id' => 'factura_' . $invoiceid,
            'amount' => intval($data_amount),
            'currency' => $data_currency,
            'cash' => array(
                'type' => 'token',
                'expires_at' => $expires
            ),
            'details' => array(
                'email' => $email,
                'phone' => $phone,
                'name' => $firstname . ' ' . $lastname,
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
        try {

            $charge = Conekta_Charge::create($conekta);

            # Transaccion Correcta
            $data = json_decode($charge);

            $expiry_date = $data->payment_method->expires_at;
            $barcode = $data->payment_method->barcode;
            $barcode_url = $data->payment_method->barcode_url;

            $ticket = 1;

        } catch (Exception $e) {
            $code = "Error al intentar generar TOKEN";
            $ticket = 0;
        }

        if ($ticket == 1) {
            $code = '<form action="conekta_token.php" method="post" target="_blank">';
            $code .= '<input type="hidden" name="barras" value="' . $barcode_url . '" />';
            $code .= '<input type="hidden" name="numero" value="' . $barcode . '" />';
            $code .= '<input type="hidden" name="expira" value="' . $expiry_date . '" />';
            $code .= '<input type="hidden" name="monto" value="' . $amount . '" />';
            $code .= '<input type="hidden" name="concepto" value="' . $data_description . '" />';
            $code .= '<input type="submit" value="Pagar ahora" />';
            $code .= '</form>';
        }
    */

    /*    $code = '<form action="conekta_token.php" method="post" target="_blank">';
        $code .= '<input type="hidden" name="barras" value="' . $barcode_url . '" />';
        $code .= '<input type="hidden" name="numero" value="' . $barcode . '" />';
        $code .= '<input type="hidden" name="expira" value="' . $expiry_date . '" />';
        $code .= '<input type="hidden" name="monto" value="' . $amount . '" />';
        $code .= '<input type="hidden" name="concepto" value="' . $data_description . '" />';
        $code .= '<input type="submit" value="Pagar ahora" />';
        $code .= '</form>';*/

    $code = "<link rel='stylesheet' href='assets/minified.css' />\n";
    $code .= "<script type=\"text/javascript\" src='assets/js/00-jquery.min.js'></script>\n";
    $code .= "<script type=\"text/javascript\" src='assets/js/01-bootstrap.min.js'></script>\n";
    $code .= "<div class='modal fade' id='captureCard' tabindex='-1' role='dialog'><div class='modal-dialog' role='document'><div class='modal-content'><div class='modal-header'><button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button><h4 class='modal-title'>Enter Card Data</h4></div><div class='modal-body'>";
    $code .= "<div class='container-fluid'>";
    $code .= "<form action=\"\" method=\"POST\" id=\"card-form\">\n";
    $code .= "  <span class=\"card-errors\"></span>\n";
    $code .= "  <div class=\"row\"><div class='form-group'>\n";
    $code .= "      <label>Nombre del tarjetahabiente</label>\n";
    $code .= "      <input type=\"text\" size=\"20\" data-conekta=\"card[name]\" value=\"{$firstname} {$lastname}\"/>\n";
    $code .= "  </div></div>\n";
    $code .= "  <div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>Número de tarjeta de crédito</span>\n";
    $code .= "      <input type=\"text\" size=\"20\" data-conekta=\"card[number]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "  <div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>CVC</span>\n";
    $code .= "      <input type=\"text\" size=\"4\" data-conekta=\"card[cvc]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "  <div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>Fecha de expiración (MM/AAAA)</span>\n";
    $code .= "      <input type=\"text\" size=\"2\" data-conekta=\"card[exp_month]\"/>\n";
    $code .= "    </label>\n";
    $code .= "    <span>/</span>\n";
    $code .= "    <input type=\"text\" size=\"4\" data-conekta=\"card[exp_year]\"/>\n";
    $code .= "  </div>\n";
    $code .= "<!-- Información recomendada para sistema antifraude -->\n";
    $code .= "  <div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>Calle</span>\n";
    $code .= "      <input type=\"text\" size=\"25\" data-conekta=\"card[address][street1]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "<div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>Colonia</span>\n";
    $code .= "      <input type=\"text\" size=\"25\" data-conekta=\"card[address][street2]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "<div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>Ciudad</span>\n";
    $code .= "      <input type=\"text\" size=\"25\" data-conekta=\"card[address][city]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "<div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>Estado</span>\n";
    $code .= "      <input type=\"text\" size=\"25\" data-conekta=\"card[address][state]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "<div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>CP</span>\n";
    $code .= "      <input type=\"text\" size=\"5\" data-conekta=\"card[address][zip]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "<div class=\"row\">\n";
    $code .= "    <label>\n";
    $code .= "      <span>País</span>\n";
    $code .= "      <input type=\"text\" size=\"25\" data-conekta=\"card[address][country]\"/>\n";
    $code .= "    </label>\n";
    $code .= "  </div>\n";
    $code .= "  <button type=\"submit\">¡Pagar ahora!</button>\n";
    $code .= "</form>\n";
    $code .= "</div></div><div class='modal-footer'></div></div></div></div>";
    $code .= "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#captureCard'>Pay now</button>";
    $code .= "<script type=\"text/javascript\" src=\"https://conektaapi.s3.amazonaws.com/v0.5.0/js/conekta.js\"></script>";
//    $code .="<script type="text/javascript" src='assets/minified.js'></script>\n";
    $code .= "<script type=\"text/javascript\">" .
        "Conekta.setPublicKey('{$public_key}'); //v5+ ";
    $code .= '
    $(function () {
        var conektaSuccessResponseHandler = function(token) {
            console.log("Recibi: ", token);
        }
        var conektaErrorResponseHandler = function(err) {
            console.error("Error: ", err);
            $("#card-form").find("button").prop("disabled", false);
            alert(err.message_to_purchaser);
        }
        $("#card-form").submit(function(event) {
            event.preventDefault();
            var $form = $(this);

            // Previene hacer submit más de una vez
            $form.find("button").prop("disabled", true);
            Conekta.Token.create($form, conektaSuccessResponseHandler, conektaErrorResponseHandler); //v5+

            // Previene que la información de la forma sea enviada al servidor
            return false;
        });
    });';
    $code .= "</script>";

    return $code;

}

?>
