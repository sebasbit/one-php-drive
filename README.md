# Probando una conexi贸n con OneDrive usando Microsoft Graph y onedrive-php-sdk

Este proyecto lo hice como un ejemplo para probar la conexi贸n con Microsoft Graph usando el SDK para PHP [onedrive-php-sdk](https://github.com/krizalys/onedrive-php-sdk). Decid铆 publicarlo en GitHub luego de haber "terminado" por esto solo hay uno o dos commits. 馃槄

## Como usarlo

1. [Registra una aplicaci贸n](https://docs.microsoft.com/en-us/graph/tutorials/php?tutorial-step=2) en [Azure Active Directory admin center](https://aad.portal.azure.com/).
   - En la secci贸n **Permisos de API** agregar *Files.Read*, *Files.ReadWrite* y *offline_access*.
   - En la ruta de callback configura la direcci贸n al archivo **/oauth/callback.login.php**

2. Copia el archivo **configuracion.ejemplo.php** como **configuracion.php** con la informaci贸n obtenida al registrar la aplicaci贸n

3. Ejecutar el archivo en **/oauth/login.php** esto creara un archivo **/var/oauth/state** para guardar una instancia de *Krizalys\Onedrive\ClientState* serializada, esta instancia incluye el token de acceso y la direcci贸n de callback.

4. En **/index.php** aparecer谩 los archivos y carpetas de la cuenta de OneDrive desde la que se aceptaron los permisos. Entre las opciones est谩n: crear y abrir una carpeta, subir, descargar y borrar un archivo.
