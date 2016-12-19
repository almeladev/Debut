# Debut
Debut es un micro framework PHP súper simple pensado para ofrecer una estructura mínima para trabajar con el patrón arquitectónico Modelo–Vista–Controlador.

Debut dispone de un <i>core</i> con las clases y métodos necesarios para empezar a trabajar en tu próxima aplicación web. Puedes crear rutas para tu aplicación y hacer cualquier CRUD de una forma rápida y sencilla. ¡Además su instalación es muy fácil con Vagrant!

## Características
<ul>
<li>Sencillo, estructurado, con ejemplos y fácil de entender</li>
<li>Instalación 100% automática con Vagrant</li>
<li>Integración con Bootstrap CSS y JS</li>
<li>Autocarga de clases automática</li>
<li>Puedes definir rutas cortas y claras</li>
<li>DEMO login y logout</li>
<li>DEMO CRUD (Create, Read, Update, Delete) de usuarios y posts</li>
<li>Uso de motor de plantillas <i>Twig</i> para las vistas</li>
<li>Compatibilidad con distintas bases de datos gracias a la integración de <i>DBAL</i> de Doctrine</li>
<li>Manejador de dependencias <i>Composer</i> integrado</li>
<li>Código claro y comentado</li>
<li>Control de errores para modo desarrollo y modo producción</li>
<li>Cache propio para las vistas</li>
<li>Debut no se ha creado a partir de otro framework</li>
</ul>

## ¿Necesito? (La instalación con Vagrant se encarga de todo)
<ul>
<li>PHP 5.3.0+</li>
<li>Módulo reescritura para urls amigables (mod_rewrite) activado</li>
<li>Composer</li>
</ul>

## Instalación
Puedes instalar un servidor con todo lo necesario para que Debut funcione con <a href="https://www.vagrantup.com/downloads.html">Vagrant</a> y los archivos <b>Vagrantfile</b> y <b>config.sh</b> que se encuentran en la carpeta "_install". Añadiendo estos dos archivos a cualquier directorio de tu equipo, sitúandote en él y ejecutando un <b>vagrant up</b>. ¡Así de simple!

Los datos por defecto del servidor son los siguientes:
* Box: <code>Ubuntu 16.04</code>
* Memoria: <code>1024 MB</code>
* CPUs: <code>1</code>
* Ip: <code>192.168.56.101</code>

Si no usas Vagrant, puedes instalar Debut y su base de datos manualmente en tu servidor si cumple con los requerimientos de la aplicación.

## Post-Instalación
Una vez tengamos Debut corriendo en nuestro servidor, debemos establecer la configuración que tendrá nuestro framework. El archivo de configuración que usa Debut se encuentra en <b>app/config.php</b>, en él debemos establecer nuestra configuración propia.

Es necesario dar permisos a la carpeta "storage" y a sus subcarpetas si vamos a trabajar en modo producción: <code>$ chmod 777 -R storage/</code>

## Licencia
Debut se encuentra bajo una licencia MIT. Eres libre de modificar y adaptar el código para uso personal o comercial.
