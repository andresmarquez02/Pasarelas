
# Pasarelas de Pago

Proyecto de pasarelas de pago implementando paypal y stripe en laravel.

## Instalación

Para instalar el sistema debes seguir los siguientes pasos

Paso 1: Clonar proyecto.

```bash
  git clone https://github.com/andresmarquez02/pasarelas-de-pago.git
```

Paso 3: Instalar paquetes con composer

```bash
  composer install
```

Paso 2: Instalar modulos de node (Si quieres editar algo en el sistema cumple este paso
)

```bash
  npm i
```

o usa

```bash
  npm install
```

Paso 4: Renombrar el archivo .env.example a .env

Paso 5: Agrega los valores de las apikey de tu cuenta paypal, stripe y mercadopago

`PAYPAL_CLIENT_ID`

`PAYPAL_CLIENT_SECRET`

`STRIPE_KEY`

`STRIPE_SECRET`

`MERCADOPAGO_KEY`

`MERCADOPAGO_SECRET`

Paso 6: para que mercado pago funcione debes crear una cuenta en https://www.currencyconverterapi.com/ para poder convertir las monedas despues de crear una cuenta y obtener un api key agregalo a las variables de entorno

`CURRENCY_CONVERSION_API_KEY`

Paso 7: Ejecutar en la linea de comando para generar la clave de encriptacion

```bash
  php artisan key:generate --show
```

Paso 8: Crea una base de datos y coloca el nombre en las variables de entorno

`DB_DATABASE`

Paso 8: Ejecutar en la linea de comando para generar las migraciones en conjunto a los seeder

```bash
  php artisan migrate:fresh --seed
```

Si quieres solo usar dos de estas plataformas solo debes comentar la insercion en los seeders.

## Authors

- [@andresmarquez](https://www.github.com/andresmarquez02)

## 🔗 Links
[![portafolio](https://img.shields.io/badge/my_portfolio-000?style=for-the-badge&logo=ko-fi&logoColor=white)](https://andresmarquez02.github.io/andres/)
[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/andres-marquez-02/)


