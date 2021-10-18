# API DE BLOCKCHAIN DE ARCHIVOS

## Configuración: 

Para poder utilizar este servicio web, deberá instalar los paquetes que requiere con el siguiente comando: 

```
./vendor/bin/sail composer install
```

Para utilizar las entidades que el servicio requiere, deberá crearlas en la base de datos con el siguiente comando: 

```
./vendor/bin/sail php artisan migrate
```

## CONSUMIR SERVICIO: 

Para poder utilizar este servicio, utilice los siguientes 'endpoint': 

- Generar bloque 'Genesis': 

Genera el primer bloque de una cadena: 
 ```
 POST : http://localhost/blocksGenesis/v1
 ```

 La petición debe contener la siguiente información en formato JSON: 

```
 {
    "data_user" : json,
    "file" : string
 }
```

- Generar cadena de bloques: 

Genera una cadena de un bloque 'Genesis' o una subcadena de una cadena existente: 
 ```
 POST : http://localhost/blocks/v1
 ```

 La petición debe contener la siguiente información en formato JSON: 

```
 {
    "data_user" : json,
    "public_key_previous_block" : int,
    "private_key_previous_block" : string,
    "file" : string
 }
```

- Obtener todos los bloques: 

Obtiene todos los bloques de la base de datos:   
 ```
GET : http://localhost/blocks/v1
 ```

 - Obtener un bloque específico: 

Obtiene el bloque solicitado. Lo que se encuentra entre '{ }', es el parametro  que recibirá el argumento correspondiente:   
 ```
GET : http://localhost/blocks/v1/{public_key}
 ```

 - Obtener los datos de un bloque específico: 

Obtiene el bloque solicitado. Lo que se encuentra entre '{ }', es el parametro  que recibirá el argumento correspondiente:   
 ```
GET : http://localhost/data-blocks/v1/{private_key}
 ```

- Obtener una cadena de bloques: 

Obtiene el historial de toda la cadena del último bloque. Lo que se encuentra entre '{ }', son los parametros  que recibirán los argumentos correspondientes:   
 ```
GET : http://localhost/blocks-chain/v1/{public_key}/{private_key}
 ```



