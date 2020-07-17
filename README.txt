Descomprimir la carpeta en la carpeta raíz de la instalación de Moodle

Debe llamarse interfaz_angeles/ 

=========================

Ejecutar los Scrips en la base de datos, para crear las tablas auxiliares

angeles_bd.sql

==========================

Se debe editar el Archivo ../course/view.php

include("../interfaz_angeles/angeles.php");

En la parte final del Archivo, Antes de 

echo $OUTPUT->footer();


===================

Se debe editar el Archivo ../my/index.php

include("../interfaz_angeles/index_sms.php");

Despues de echo $OUTPUT->header();

