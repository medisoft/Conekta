Conekta  2.0 WHMCS

Actualizacion 20 agosto 2015

* Se elimino el Metodo de pago Banorte
* Añadimos SPEI como metodo de Pago
* Actualizamos la Librerias de Conekta.IO
* En el Metodo de OXXO y SPEI añadimos fecha de Expiracion en la Configuracion del Gateway
* Recomendamos Borrar todos los archivos referentes a Banorte

Actualizacion 15 agosto 2015
Actualizar de 1.2 -> 1.3.1 subir  /modules/gateways/conektacard.php, conektabanorte.php y conektaoxxo.php

=======

Ya no estoy trabajando en https://github.com/CarlosCesar110988/Conekta

Solo publicare actualizaciones en https://github.com/Carlos110988/Conekta

=======

Conekta - Pagos con Tarjeta

Conekta - Pagos en Tiendas OXXO + WebHooks

Conekta - Pagos en SPEI + WebHooks

Conekta - Pagos Banco Banorte + WebHooks (Metodo Eliminado)



Modulo para procesar tarjetas de crédito y debito Visa/MasterCard desde portal de WHMCS.

Requiere Llave Privada "Private Key" ya sea en entorno de Prueba o Producción.

WHMCS V 5.2 o superior

Instrucciones:

1.- Sube por FTP en la ruta de la instalación de WHMCS la carpeta "modules"

2.- Configurar la pasarela de pago en el portal de admin del WHMCS

3.- Configurar WebHooks en el portal de Conekta.io
    
      - http://www.misitio.com/whmcs/modules/gateways/callback/conektabanorte.php (Eliminado)
      
      - http://www.misitio.com/whmcs/modules/gateways/callback/conektaspei.php
      
      - http://www.misitio.com/whmcs/modules/gateways/callback/conektaoxxo.php

3.- Subir a raiz del WHMCS los archvos conekta_oxxo.php y conekta_spei.php

4.- Realiza un par de pruebas en entorno testing

5.- Disfruta


=======

Copyright (c) 2015, Carlos Cesar Peña Gomez <CarlosCesar110988@gmail.com>

Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
