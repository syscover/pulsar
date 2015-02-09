# GetFile - jQuery Upload Plugin
# By Jose Carlos Rodriguez & Octavio Molano
# (c) 2014 Syscover S.L. - http://www.syscover.com/
# All rights reserved

FEATURES

English:
- Lets you upload any type of file to your chosen folder via AJAX
- Mobile ready
- Bootstrap compatible
- Lets you specify MIME file types allowed during file upload
- Callback support to perform any any needed operations after a successful file upload
- Deletes temporary files after a set time
- Output file name can be a predefined or encrypted name, or the original file name
- If the uploaded file is an image, it allows the user to:
    · Crop the image with a friendly user interface
    · Resize the image to a predetermined size, optionally keeping the aspect ratio
    · Determine the output file format (jpg, gif or png) independently of the source image file format
    · Get several copies of the uploaded image in different sizes, allowing you to specify which folder each file must be saved to, and whether it must have any file name prefix

Spanish:
- Permite subir cualquier tipo de fichero a la carpeta que determines vía AJAX
- Compatible con dispositivos móviles
- Compatible con Bootstrap
- Permite especificar los tipos de archivos MIME permitidos en la carga
- Callback para realizar las operaciones necesarias tras una carga de fichero correcta
- Borra archivos temporales pasado un tiempo
- El nombre del fichero de salida puede ser un nombre predefinido, cifrado o el nombre original del fichero
- Si el fichero subido es una imágen, permite al usuario:
    · Recortar la imagen mediante un interfaz de usuario amigable
    · Redimensionar la imagen a un tamaño predeterminado, manteniendo o no la proporción
    · Determinar el formato de imagen de salida (jpg, gif o png) independientemente del formato de la imagen original
    · Obtener a partir de una imagen varias imágenes de tamaños diferentes, permitiendo especificar en que carpeta de debe guardar cada fichero y si debe de tener algún prefijo en particular

___


Error code reference
1: File field is not defined
2: MIME type is not allowed
3: JCrop library not loaded
4: Width and height or ratio must be defined
5: Destination directory does not exist. Check folder setting:
6: Folder setting must be defined
7: Destination folder is not writeable. Please check permissions for:
8: Temp directory does not exist. Check tmpFolder setting:
9: The tmpFolder setting must be defined
10: Destination folder is not writeable. Please check permissions for:
11: The mimesAccept setting must be an array or false. Setting it to false might be dangerous, since it means accepting all file types

????
- En el caso de ser una imagen permite rotarla VER. 2
- Permile convertir PDFs a imágenes VER. 2
- Permite convertir imágenes a PDF VER. 2
- Permite completar una imagen con un fondo de color plano en el caso de no cumplir las proporciones adecuadas??
- Instanciar prefix para la opción de crop
- Cambiar variable isImage por isManipulableImage para determinar si la imagen subida se puede redimensionar o hacer crop
