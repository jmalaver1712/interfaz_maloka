Descomprimir la carpeta en la carpeta raíz de la instalación de Moodle

Debe llamarse interfaz_maloka/

==========================

Se debe editar el Archivo ../course/view.php

include("../interfaz_maloka/maloka.php");

En la parte final del Archivo, Antes de 



echo $OUTPUT->footer();


