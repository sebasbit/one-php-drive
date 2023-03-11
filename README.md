# Probando una conexión con OneDrive usando Microsoft Graph y onedrive-php-sdk

## Como usarlo

1. [Registra una aplicación](https://docs.microsoft.com/en-us/graph/tutorials/php?tutorial-step=2) en [Azure Active Directory admin center](https://aad.portal.azure.com/).
   - En la sección **Permisos de API** agregar *Files.Read*, *Files.ReadWrite* y *offline_access*.
   - En la ruta de callback configura la dirección al archivo **/oauth/callback.login.php**.

2. Copia el archivo **configuracion.ejemplo.php** como **configuracion.php** con la información obtenida al registrar la aplicación.

3. Ejecutar el archivo en **/oauth/login.php** esto creara un archivo **/var/oauth/state** para guardar una instancia de *Krizalys\Onedrive\ClientState* serializada, esta instancia incluye el token de acceso y la dirección de callback.

4. En **/index.php** aparecerán los archivos y carpetas de la cuenta de OneDrive desde la que se aceptaron los permisos. Entre las opciones están: crear y abrir una carpeta, subir, descargar y borrar un archivo.
