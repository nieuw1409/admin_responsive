<?php
/*
  $Id: install.php,v 1.3 2004/11/07 21:02:11 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2004 osCommerce

  Released under the GNU General Public License
*/

  define('PAGE_TITLE_INSTALLATION', 'Instalaci&oacute;n Nueva');
  define('TEXT_BOX_DB', 'Base de Datos del Servidor');
  define('TEXT_BOX_WS', 'Servidor Web');
  define('TEXT_BOX_ON', 'Configuraci&oacute;n de Tienda Online');
  define('TEXT_BOX_FINISH', 'Finalizado!');

  define('TEXT_DESCRIPTION', 'Esta rutina de instalaci&oacute;n basada en la web va a instalar y configurar correctamente osCommerce para correr en este servidor.<br /><br />
  Por favor, siga las instrucciones en pantalla que le llevar&aacute; a trav&eacute;s del servidor de bases de datos, servidor web, y las opciones de almacenar la configuraci&oacute;n. Si necesita ayuda en cualquier momento, por favor consulte la documentaci&oacute;n o pedir ayuda en los foros de apoyo de la comunidad.');
  define('TEXT_STEP1', 'Paso 1: Base de Datos del Servidor');
  define('TEXT_STEP1_TEXT1', 'El servidor de base de datos almacena el contenido de la tienda en l&iacute;nea, tales como informaci&oacute;n sobre productos, informaci&oacute;n de clientes y los pedidos que se han hecho.');
  define('TEXT_STEP1_TEXT2', 'Por favor consulte a su administrador del servidor si los par&aacute;metros del Servidor de Base de Datos a&uacute;n no se conocen.');

  define('TEXT_DATABASE_TEXT', 'La direcci&oacute;n del servidor de base de datos en la forma de un nombre de host o IP.');
  define('TEXT_DATABASE_USERNAME', 'Nombre de Usuario');
  define('TEXT_DATABASE_USERNAME_TEXT', 'El nombre de usuario utilizado para conectar con el servidor de base de datos.');
  define('TEXT_DATABASE_PASSWORD', 'Contrase&ntilde;a');
  define('TEXT_DATABASE_PASSWORD_TEXT', 'La contrase&ntilde;a usada en conjunto con el nombre de usuario para conectar con el servidor de base de datos.');
  define('TEXT_DATABASE_NAME', 'Nombre de Base de Datos');
  define('TEXT_DATABASE_NAME_TEXT', 'El nombre de la base de datos que albergar&aacute; los datos..');

  define('CONECT_TESTING', 'Prueba de conexi&oacute;n con la base de datos..');
  define('CONECT_PROBLEM', 'Hemos encontrado un error al importar la base de datos. Ha ocurido el siguiente error:<p><b>%s</b></p>Por favor verifica los parametros de coneccion y intenta e nuevo.');
  define('CONECT_IMPORTED', 'La estructura de base de datos est&aacute; siendo importado ahora. Por favor, sea paciente durante este procedimiento.');
  define('CONECT_SUCCESSFULLY', 'La base de datos ha sido importado correctamente.!');

  define('TEXT_STEP2', 'Step 2:  Servidor Web');
  define('TEXT_STEP2_TEXT', 'El servidor web se encarga de servir las p&aacute;ginas de su tienda en l&iacute;nea a sus visitantes y clientes. El servidor web se asegura de que los enlaces a las paginas apuntan a la ubicaci&oacute;n correcta.');
  define('TEXT_WWW_ADDRESS', 'Direcci&oacute;n WWW');
  define('TEXT_WWW_ADDRESS_TEXT', 'La direcci&oacute;n web de la tienda en l&iacute;nea.');
  define('TEXT_WWW_ROOT_DIRECTORY', 'Directorio Ra&iacute;z del Servidor Web');
  define('TEXT_WWW_ROOT_DIRECTORY_TEXT', 'El directorio donde la tienda en l&iacute;nea est&aacute; instalada en el servidor.');

  define('TEXT_STEP3', 'Step 3: Configuraci&oacute;n de la Tienda En L&iacute;nea');
  define('TEXT_STEP3_TEXT', 'Aqu&iacute; usted puede definir el nombre de su tienda en l&iacute;nea y la informaci&oacute;n de contacto del propietario de la tienda.<br /><br />
      El nombre de usuario del administrador y la contrase&ntilde;a que usar&aacute; para acceder a  la secci&oacute;n protegida de las herramientas de administraci&oacute;n.');
  define('TEXT_STORE_NAME', 'Nombre de la Tienda Online<br ');
  define('TEXT_STORE_NAME_TEXT', 'El nombre de la tienda mostrado al p&uacute;blico.');
  define('TEXT_OWNER_NAME', 'Nombre del Due&ntilde;o de la Tienda');
  define('TEXT_OWNER_NAME_TEXT', 'El nombre de administrador utilizado para usar las herramientas de administraci&oacute;n.');
  define('TEXT_OWNER_MAIL', 'Correo Electr&oacute;nico del Due&ntilde;o de la Tienda');
  define('TEXT_OWNER_MAIL_TEXT', 'La direcci&oacute;n de correo electr&oacute;nico del propietario de la tienda mostrado al p&uacute;blico.');
  define('TEXT_AU', 'Nombre de usuario del Administrador');
  define('TEXT_AU_TEXT', 'El nombre de usuario de administrador para el uso de la herramienta de administraci&oacute;n.');
  define('TEXT_AP', 'la Contrase&ntilde;a del Administrador');
  define('TEXT_AP_TEXT', 'La contrase&ntilde;a que utilizar&aacute; para la cuenta de administrador.');
  define('TEXT_ADMIN_DN', 'Nombre del Directorio de Administraci&oacute;n');
  define('TEXT_ADMIN_DN_TEXT', 'Este es el directorio donde se instal&oacute; la secci&oacute;n de administraci&oacute;n. Deber&aacute; cambiar el directorio por razones de seguridad.');

  define('TEXT_STEP4', 'Step 4: Finalizado!');
  define('TEXT_STEP4_TEXT', 'Enhorabuena por  instalar y configurar osCommerce Online Merchant como su soluci&oacute;n de tienda en l&iacute;nea!<br /><br />
   Le deseamos el mejor de los &eacute;xitos con su tienda en l&iacute;nea y le damos la bienvenida por unirse y participar en nuestra comunidad.');
  define('TEXT_OSC_TEAM', '- El Equipo de osCommerce');
  define('TEXT_SUCCESSFUL', 'La instalaci&oacute;n y la configuraci&oacute;n han sido un &eacute;xito.');
  define('TEXT_RENAME', 'Cambie el nombre del directorio de la Herramienta de Aministraci&oacute;n ubicado en');
  
  define('TEXT_ADMINISTRATION', 'Los detalles de acceso del Admin:<br />
  E-Mail: <i><b>admin@localhost.com</b></i><br />
  Password: <i><b>admin</b></i><br /><font color=red>
  <b>Despu&eacute;s de acceder en la à administraci&oacute;n necesariamente cambiar de e-mail la direcci&oacute;n y la contrase&ntilde;a de una entrada.</b></font>');

  define('TEXT_CONTINUE', 'Continuar');
  define('TEXT_CENCEL', 'Cancelar');
  define('TEXT_CATALOG', 'Catalog');
  define('TEXT_ADMIN_TOOL', 'Herramienta de Administraci&oacute;n');
  define('TEXT_POST_INSTALL', 'Notas posteriores a la instalaci&oacute;n');
  define('TEXT1_POST_INSTALL', 'Se recomienda seguir los siguientes pasos posteriores a la instalaci&oacute;n para asegurar su tienda osCommerce Online Merchant');
  define('TEXT_Rename', 'Cambie el nombre del directorio de la Herramienta de Administraci&oacute;n ubicado en');
  define('TEXT_SET', 'Establecer los permisos de');
  define('TEXT_TO', 'a 644 (o 444 si el archivo sigue siendo modificable).');
  define('TEXT_DEL', 'Eliminar el');
  define('TEXT_D', 'directorio');
  define('TEXT_REW', 'Revisi&oacute;n de los permisos del directorio de la Herramienta de Administraci&oacute;n -> Herramientas -> Seguridad Directorio de permisos de la p&aacute;gina.');
  define('TEXT_REW2', 'La Herramienta de Administraci&oacute;n debe estar m&aacute;s protegido con htaccess / htpasswd y puede ser puesta en marcha dentro de la Configuraci&oacute;n -> P&aacute;gina de Administradores.');
  define('IMAGE_CONTINUE', 'CONTINUAR') ;
  define('IMAGE_CANCEL', 'PANTALLA DE INICIO') ;  
  define('IMAGE_TOWEBSHOP', 'CATALOG') ;  
  define('IMAGE_TOADMIN', 'HERRAMIENTA DE ADMINISTRACION') ;  
?>
