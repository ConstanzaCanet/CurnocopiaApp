E-commerce Platform Documentation

# Introducción

Este proyecto es una plataforma de comercio electrónico desarrollada con Laravel 11, diseñada pensando en un almacen sencillo. Permite la gestión de productos, usuarios, compras y pagos mediante la integración de MercadoPago. También incluye la generación de facturas electrónicas en Argentina utilizando el SDK de AFIP. El sistema de autenticación incluye roles (admin y user), manejo de carrito de compras, wishlist, órdenes y categorías de productos.

# Requisitos Previos
 Los siguientes programas son necesarios para levantar el proyecto en tu computador:

PHP >= 8.1
Composer >= 2.0
MySQL o MariaDB(o puedes utilizar SQLite-- en Laravel puedes utilizarzo por defecto).
Node.js >= 14.x
Git
Además, necesitarás una cuenta en MercadoPago( https://www.mercadopago.com.ar/developers), AFIP (para facturación electrónica--> https://afipsdk.com/) y Mailtrap(envio de emails--> https://mailtrap.io/).

Aqui es necesario revisar tu archivo .env para configurar las variables de entorno necesarias.

## Guía de Instalación
Paso 1: Clonar el Repositorio
Clona este repositorio en tu máquina local utilizando Git:

git clone https://github.com/ConstanzaCanet/CurnocopiaApp.git
cd Nombre de la carpeta donde clonaste el repositorio
----------------
Paso 2: Instalar Dependencias de PHP y Node.js
Instala las dependencias de PHP y JavaScript necesarias para ejecutar el proyecto:
composer install
npm install
npm run dev
---------------
Paso 3: Configurar el Archivo .env
Copia el archivo .env.example como .env y configura las siguientes variables:

cp .env.example .env

En el archivo .env, configura los detalles de la base de datos y los servicios externos:

APP_NAME=E-commerce
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Configuración de MercadoPago
MERCADOPAGO_ACCESS_TOKEN=tu_token_de_acceso

# Configuración de Mailtrap para verificación de correos electrónicos
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="no-reply@ecommerce.com"
MAIL_FROM_NAME="${APP_NAME}"

# Configuración del SDK de AFIP
AFIP_CUIT=tu_cuit
AFIP_CERT=path_al_certificado.pem
AFIP_KEY=path_a_la_clave_privada.pem

(Acalracion sobre AFIP SDK, en la web puedes encontrar datos de prueba, son publicos, por ende si quieres usarlo sin tener que registrarte por la clave fiscal, son estos datos:
AFIP_CUIT=20409378472
AFIP_CERT_PATH=storage/app/afip/certificates/cert.crt
AFIP_KEY_PATH=storage/app/afip/certificates/private.key
AFIP_ENV=sandbox  )
----------------
Paso 4: Generar Clave de la Aplicación
Genera la clave de la aplicación Laravel:

php artisan key:generate
----------------
Paso 5: Migrar la Base de Datos y correr los Seeders
Migra las tablas necesarias para el proyecto y siembra datos iniciales, incluidos los roles de usuario y admin:

php artisan migrate --seed
--------------------
Paso 6: Crear el enlace para almacenamiento de imagenes y documentos
Crea el enlace simbólico para que las imágenes cargadas puedan ser accedidas públicamente:

php artisan storage:link
--------------------
Paso 7: Iniciar el Servidor
Finalmente, inicia el servidor local:

php artisan serve

Ahora puedes acceder a la aplicación en tu navegador en http://localhost:8000(o el puerto que hayas configurado en tu archivo .env)
---------------------

Roles y Acceso
El proyecto tiene dos roles de usuario:

User(user): Los usuarios pueden navegar por el catálogo, añadir productos al carrito, realizar compras y ver sus órdenes. Ademas pueden contribuir vendiendo sus propios productos, eliminandolos y editandolos.

Admin(admin): Este rol tiene permisos para crear, editar y eliminar productos en general(suyos y de otros usuarios), además de gestionar órdenes, facturación y puede mandar notificacion a usuarios o eliminarlos.
--------------------

Relaciones entre los Modelos

User
Relación: Un usuario puede tener múltiples órdenes,invoices, productos y una lista de deseos (wishlist).
Roles: Un usuario puede tener uno de dos roles: admin o user.
Atributos: id, name, last_name, email, password, email_verified_at, remember_token, profile_photo_path, role

Product
Relación: Un producto puede tener múltiples imágenes, puede estar relacionado con varias órdenes a través de los order_items y pertenecer a varias categorías.
Atributos: id, name, description, price, stock, category_id.

Order
Relación: Una orden pertenece a un usuario y puede tener varios order_items.
Atributos: user_id, total_price, status, shipping_address, zip, uuid, preference, api_response.

OrderItem
Relación: Un order_item pertenece a una orden y está relacionado con un producto.
Atributos: order_id, product_id, quantity, price.

Invoices
Relación: Una invoice se relaciona con un usuario y una orden.
Atributos: id, order_id, cae, cae_vto (estos dos ultimos atributos son necesarios en la facturacion- son codigos de factura)

Image
Relación: Una imagen pertenece a un producto.
Atributos: product_id, path.

Category
Relación: Una categoría tiene muchos productos.
Atributos: id, name, description.

Wishlist
Relación: Un usuario puede tener varios productos en su wishlist.
Atributos: user_id, product_id.
----------------------------

Una vez creado los seeders ya puedes interaccionar libremente. Hasta este punto ya estaria explicado como se despliega y como funcionan los modelos que implementamos aqui.

A continuacion se explica brevemente el funcionamiento de un par de paquetes que integramos como servicios en la app.
----------------------------

Configuración de MercadoPago

Este proyecto usa MercadoPago Checkout Pro para gestionar los pagos. A continuación se describen los pasos para la configuración e integración de MercadoPago.

Instalación del Paquete de MercadoPago

En el proyecto, se utiliza el SDK oficial de MercadoPago para PHP. Para instalarlo, sigue estos pasos:
Ejecuta el siguiente comando para instalar el SDK de MercadoPago:

composer require mercadopago/dx-php:3.0.6

Configura las credenciales de MercadoPago en el archivo .env. Para obtener estas credenciales, ve a MercadoPago Developers.
En el archivo .env, añade lo siguiente:

MERCADOPAGO_ACCESS_TOKEN=tu_token_de_acceso
MERCADOPAGO_PUBLIC_KEY=tu_key_public
-----------------------------
La integración de MercadoPago se realizó dentro del OrderController, donde se configura el checkout de MercadoPago al momento de realizar una compra. El flujo es el siguiente:
Creación del Cliente de Preferencias: El PreferenceClient de MercadoPago se utiliza para configurar los detalles del pago, como los productos en el carrito, el total de la compra, la redirección en caso de éxito o error, entre otros.
Aquí un ejemplo de la implementación en el controlador:

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Configura el token de acceso de MercadoPago
        SDK::setAccessToken(config('services.mercado_pago.token'));

        // Crea una nueva preferencia
        $preference = new Preference();

        // Crea los ítems para la preferencia (productos del carrito)
        foreach ($request->cart_items as $cart_item) {
            $item = new Item();
            $item->title = $cart_item['name'];
            $item->quantity = $cart_item['quantity'];
            $item->unit_price = $cart_item['price'];
            $preference->items[] = $item;
        }

        // Configura las URLs de retorno
        $preference->back_urls = [
            'success' => route('checkout.success'),
            'failure' => route('checkout.failure'),
            'pending' => route('checkout.pending'),
        ];

        // Configura el comportamiento del pago
        $preference->auto_return = 'approved'; // Retorno automático si el pago es exitoso

        // Guarda la preferencia
        $preference->save();

        // Redirige al usuario a la URL de MercadoPago
        return redirect($preference->init_point);
    }
}
Rutas y Controlador de Checkout: Se definen rutas que manejan los diferentes estados de una transacción: éxito, fallo o pendiente.

Route::get('/checkout/success', [OrderController::class, 'paymentSuccess'])->name('checkout.success');
Route::get('/checkout/failure', [OrderController::class, 'paymentFailure'])->name('checkout.failure');
Route::get('/checkout/pending', [OrderController::class, 'paymentPending'])->name('checkout.pending');

En el controlador, puedes manejar las respuestas de MercadoPago:
public function paymentSuccess(Request $request)
{
    // Lógica para actualizar el estado del pedido y confirmar el pago
    $order = Order::where('preference', $request->preference_id)->first();
    $order->status = 'completed';
    $order->save();

    // Redirige a la vista de éxito
    return view('checkout.success', compact('order'));
}

public function paymentFailure(Request $request)
{
    // Lógica para manejar el fallo en el pago
    return view('checkout.failure');
}

public function paymentPending(Request $request)
{
    // Lógica para manejar pagos pendientes
    return view('checkout.pending');
}

Proceso de Orden y Pago
Agregar Productos al Carrito: Los usuarios pueden añadir productos al carrito, los cuales se almacenan en una sesión o en una tabla de carrito según la configuración.
Checkout: Al proceder al checkout, los productos en el carrito se envían a MercadoPago mediante la preferencia configurada, donde se generan los detalles del pago.
Estado del Pedido: Después del pago, el estado de la orden se actualiza según la respuesta de MercadoPago (éxito, pendiente, fallo).
-----------------------------------

Facturación Electrónica con AFIP
El proyecto utiliza el SDK de AFIP para emitir facturas electrónicas en Argentina. A continuación se describe cómo se implementa:

Configuración del SDK de AFIP
Para usar el SDK de AFIP, se deben configurar las siguientes variables en el archivo .env:

env
Copiar código
AFIP_CUIT=tu_cuit
AFIP_CERT=path_al_certificado.pem
AFIP_KEY=path_a_la_clave_privada.pem
Uso del SDK
En el proceso de checkout, cuando una orden se marca como completed, se emite una factura a través de AFIP usando el SDK. Aquí un ejemplo de cómo se hace en el OrderController(en este caso se muestra un breve ejemplo sacado de la documentacion para dejarlo breve- en el archivo app>Services>AfipService podras ver que la factura es mas extensa debido a que quise mostrarlo de esa forma):

use Afip;

public function generateInvoice(Order $order)
{
    $afip = new Afip(array('CUIT' => config('services.afip.cuit')));
    $data = [
        'Concepto' => 1, // Productos
        'DocTipo' => 80, // CUIT
        'DocNro' => $order->user->cuit,
        'CbteDesde' => 1,
        'CbteHasta' => 1,
        'ImpTotal' => $order->total_price,
        // Otros datos de la factura...
    ];
//Aqui llamamos metodos propios del sdk
    $response = $afip->ElectronicBilling->CreateNextVoucher($data);
    $order->invoice_number = $response['CbteDesde'];
    $order->save();
}

Lo mas importante de este codigo es que genera un registro que aunque es de testeo, si se cambian las credenciales, sirve para generar una factura totalmente valida en Argentina.
Este servicio se utiliza en el InvoiceConteoller para el metodo generate--> que factura la orden desde el carrito( puedes verlo en resources>views>invoices>index).
Una vez facturado se puede descargar un pdf, que se genera en base a una plantilla sacada del sdk de afip.


Conclusión
Proporciona una plataforma completa para la venta de productos en línea, integrando pagos con MercadoPago y facturación electrónica con AFIP. Ofrece funcionalidades avanzadas para la administración de productos, wishlist, búsqueda por categorías o descripciones, y un sistema de roles para la gestión de usuarios.
----------------------