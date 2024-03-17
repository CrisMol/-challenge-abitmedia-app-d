# Despliegue

1. Copiar el archivo .env.example y cambiar el nombre a .env
2. Ejecutar el comando: `composer install`
3. Crear una base de datos en: `http://localhost/phpmyadmin/`
4. Nombre recomendado: `challenge-abitmedia`, la codificación de: `utf8mb4_general_ci`
5. Colocar el nombre de la base, usuario y contraseña en el archivo .env
    DB_DATABASE=challenge-abitmedia
    DB_USERNAME=root
    DB_PASSWORD=
6. Ejecutar migraciones y seeders: `php artisan migrate --seed`

# API
    Importante: los encabezados deben incluir el:
        - `Accept: application/json`

    Si ejecutaste las migraciones junto a los seeders, en la base de datos estara registrado un usuario por defecto con las siguientes credenciales, es necesario loguearse para obtener un token de autenticación para consumir la API:
        nombre: admintest,
        correo: admintest@abitmedia.com,
        contraseña: adminMedia,

    Puedes usar postman para logearte y solicitar un token:
        1. Coloca la dirección: `{tu ip (192.168.1.23)}/-challenge-abitmedia-app-d/public/api/login` con el siguiente cuerpo: 
            {
                "name": "admintest",
                "email": "admintest@abitmedia.com",
                "password": "adminMedia"
            }
        2. Pega el token en cada solicitud que realices en Bearer-token