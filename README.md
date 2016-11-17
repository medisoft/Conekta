*Actualizacion 17 de Noviembre del 2016*

- Soporte para tarjetas de credito y debito usando tokenizacion (Mario Medina mario@opticalcube.com)
- Reparacion de varios bugs
- Carga de la ultima libreria de conekta desde composer

Trabajo basado en https://github.com/Carlos110988/Conekta


Conekta 2.1.3 WHMCS

6 Sep - Se agregar parametro "Phone" en details al enviar el cargo a Conekta. (Gracias a Marco Polo por la información)

23 Abril - Cambiando la imagen de spei a local y cambiando LANG a ConektaLANG (Gracias a medisoft Mario Medina por la correccion)

Actualizacion 21 Septiembre 2015 (Gracias a Erick Álvarez de Conekta)

* Agregamos el parametro (details->name) - se envia el nombre de quien realiza el pago en los 3 metodos de pago. 

Actualizacion 21 agosto 2015 (Gracias a Moises P. por notificarnos el bug)

* Integramos las librerias 1.0 y 2.0 de Conekta para poder procesar Tarjetas/SPEI/Oxxo sin problemas
* Replazamos modules/gateways/conekta/lib/ por modules/gateways/conekta/lib_1.0/ y modules/gateways/conekta/lib_2.0/
* Actualizamos:
	- modules/gateways/conektacard.php
	- modules/gateways/conektaoxxo.php
	- modules/gateways/conektaspei.php

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

1.- Sube por FTP en la ruta de la instalación de WHMCS la carpeta "modules" y "assets" a la raiz de WHMCS
    Copia tambien los archivos PHP que esten en la raiz del proyecto (conekta.php, conekta_oxxo.php, conekta_spei.php) tambien en
    la raiz de WHMCS.

2.- Copia el archivo clientareacreditcard-conekta.tpl a la raiz de el template que usas en la carpeta templates

3.- Configura la pasarela de pago en el portal de admin del WHMCS

4.- Configurar WebHooks en el portal de Conekta.io
    
      - http://www.misitio.com/whmcs/modules/gateways/callback/conekta.php

5.- Realiza un par de pruebas en entorno testing con cada metodo de pago


Se crea (si hay permisos) una carpeta llamada conekta_logs en la carpeta de callback, para que puedas monitorear los web hooks


=======

Copyright (c) 2015, Carlos Cesar Peña Gomez <CarlosCesar110988@gmail.com>

Permission to use, copy, modify, and/or distribute this software for any purpose with or without fee is hereby granted, provided that the above copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
