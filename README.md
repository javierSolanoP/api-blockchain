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
    "file" : file
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
    "key_previous_block" : int,
    "hash_previous_block" : string,
    "file" : file
 }
```

- Obtener todos los bloques: 

Obtiene todos los bloques de la base de datos:   
 ```
GET : http://localhost/blocks/v1
 ```

- Obtener una cadena de bloques: 

Obtiene el historial de toda la cadena del último bloque. Lo que se encuentra entre '{ }', son los parametros que se requieren: 
 ```
GET : http://localhost/blocks-chain/v1/{public_key}/{hash}
 ```



