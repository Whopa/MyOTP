MyOTP
=====

Integra OTP en tus aplicaciones web con Laravel. Funciona con Google Authenticator y otras aplicaciones que soporten OATH (HOTP / TOTP).

### Requisitos
   - Laravel 4.1.x

Instalación
-----------
Para instalar este paquete, agrega lo siguiente en tu archivo composer.json

```sh
require {
    "whopa/myotp" : "dev-master"
}
```

Luego ejecuta

`composer install` o `composer update` según sea el caso.

Luego abre el archivo `config/app.php` y agregar en el array `providers`

```sh
'Whopa\Myotp\MyotpServiceProvider'
```

Uso
---
El paquete define automáticamente `Myotp` como alias.

Para generar un key secreto.
```sh
$key = Myotp::userRandomKey();
```

Para generar el OTP en base a la Key
```sh
$otp = Myotp::generate($key, 30);
```