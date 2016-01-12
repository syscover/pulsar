<?php

/*
|--------------------------------------------------------------------------
| Idioma: spanish
| web: PULSAR
|
| *Instrucciones de traducción*
| Solo traducir la palabras de la columna derecha, ejemplo:
| 'aceptar' => 'texto aceptar'
|
| Respetar las mayúsculas y minúsculas
|
| En esta caso habría que traducir el texto 'texto aceptar' respetando las comillas simples.
| Hay etiquetas que son específicas para la maquetación del texto, IMPORTANTE!! respetar las etiquetas.
| Las etiquetas mas comunes a encontrar en los textos son:
| <br> <span> <p> <a> <strong> <div>
|
| Ejemplo:
| 'aceptar' => 'hola <strong>mundo</strong>'
|
| La traducción de este testo sería:
| 'aceptar' => 'hello <strong>world</strong>'
|
| IMPORTANTE!!
| Si hiciera falta poner una comilla simple en el texto, por ejemplo 'don't save' las comillas deben de estar precedicas del caracter \ para que no se tengan en cuenta a la hora de cerrar el texto, ejemplo:
|
| 'no_salvar'   =>  'Don't save'     *** ERROR *** la web daría un error.
| 'no_salvar'   =>  'Don\'t save'    *** OK *** el resultado en pantalla sería, Don't save.
|--------------------------------------------------------------------------
*/

return [
    
    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the paginator library to build
    | the pagination links. You're free to change them to anything you want.
    | If you come up with something more exciting, let us know.
    |
    */
    'access'                                => 'Acceso',
    'accept'                                => 'Aceptar',
    'access_your_account'                   => 'Accede a tu cuenta',
    'action'                                => 'Acción|Acciones',
    'active'                                => 'Activo',
    'address'                               => 'Dirección|Direcciones',
    'administration'                        => 'Administración',
    'cancel'                                => 'Cancelar',
    'change_password_successful'            => 'Estupendo, Le hemos enviado un email',
    'create'                                => 'Crear',
    'custom_field'                          => 'Campo personalizado|Campos personalizados',
    'confirmed'                             => 'Confirmado',
    'data_type'                             => 'Tipo de dato|Tipo de datos',
    'delete'                                => 'Borrar',
    'divorced'                              => 'Divorciado/a',
    'edit'                                  => 'Editar',
    'email'                                 => 'Email',
    'enter_email'                           => 'Indique su dirección de email',
    'error'                                 => 'Error|Errores',
    'error_reset_password'                  => 'Por favor introduzca su email',
    'error2_reset_password'                 => 'Error, su usuario no existe',
    'feature'                               => 'Característica|Características',
    'featured'                              => 'Destacado',
    'female'                                => 'Mujer',
    'field'                                 => 'Campo|Campos',
    'field_group'                           => 'Grupo de campos|Grupos de campos',
    'field_size'                            => 'Tamaño campo',
    'field_type'                            => 'Tipo de campo|Tipo de campos',
    'gender'                                => 'Género',
    'group'                                 => 'Grupo|Grupos',
    'int_value'                             => 'Valor entero',
    'instructions'                          => 'Instrucciones',
    'ip'                                    => 'IP',
    'label_size'                            => 'Tamaño etiqueta',
    'link'                                  => 'Enlace',
    'login'                                 => 'Iniciar sesión',
    'logout'                                => 'Cerrar sesión',
    'male'                                  => 'Varón',
    'married'                               => 'Casado/a',
    'maximum_length'                        => 'Longitus máxima',
    'mr'                                    => 'Sr.',
    'mrs'                                   => 'Sra.',
    'ms'                                    => 'Srta.',
    'new'                                   => 'Nuevo',
    'new2'                                  => 'Nueva',
    'new_window'                            => 'Nueva ventana',
    'objects_list'                          => 'Lista de objetos',
    'package'                               => 'Paquete|Paquetes',
    'package_name'                          => 'Administración',
    'password'                              => 'Contraseña',
    'pattern'                               => 'Patrón',
    'price'                                 => 'Precio|Precios',
    'remember_password'                     => '¿Olvidó su contraseña?',
    'required'                              => 'Necesario',
    'reset_password'                        => 'Cambie su contraseña',
    'reset_password_successful'             => 'Perfecto, Le hemos enviado un email',
    'row'                                   => 'Fila|Filas',
    'run'                                   => 'Ejecutar',
    'save'                                  => 'Guardar',
    'select_category'                       => 'Seleccione una categoría',
    'selected_objects'                      => 'Objetos seleccionados',
    'setting'                               => 'Ajuste|Ajustes',
    'single'                                => 'Soltero/a',
    'state'                                 => 'Estado|Estados',
    'treatment'                             => 'Tratamiento|Tramientos',
    'type_something'                        => 'Escribe algo',
    'value'                                 => 'Valor|Valores',
    'weight'                                => 'Peso|Pesos',
    'widower'                               => 'Viudo/a',


    'file_not_select'                       => 'Fichero no seleccionado',
    'select'                                => 'Seleccionar',
    'sorting'                               => 'Orden',
    'dashboard'                             => 'Escritorio',
    'prefix'                                => 'Prefijo',
    'edit_record'                           => 'Editar registro',
    'view_record'                           => 'Ver registro',
    'related_record'                        => 'Relacionar registro',
    'delete_record'                         => 'Borrar registro',
    'delete_translation'                    => 'Borrar traducción',
    'edit_permissions'                      => 'Editar permisos',
    'set_all_permissions'                   => 'Establecer todos los permisos',
    'territorial_area'                      => 'Area territorial|Areas territoriales',
    'message_set_all_permissions'           => '¿Desea establecer todos los permisos para el perfil seleccionado?',
    'message_create_all_permissions'        => 'Los permisos para el perfil <strong>:profile</strong> han sido creados',
    'message_create_record_successful'      => 'Registration <strong>:name</strong> has been properly recorded',
    'message_delete_record'                 => '¿Desea borrar el registro con <strong>ID \' + $(this).data("id") + \' </strong>?',
    'message_delete_translation_record'     => '¿Desea borrar el registro traducido con <strong>ID :id </strong>?',
    'message_delete_records'                => '¿Desea borrar los registros seleccionados?',
    'message_delete_record_successful'      => 'El registro <strong>:nombre</strong> ha sido borrado correctamente',
    'message_delete_records_successful'     => 'Los registros seleccionados han sido borrados correctamente',
    'message_record_no_select'              => 'No hay ningún registro seleccionado para realizar esta acción',
    'message_update_record'                 => 'El registro <strong>:name</strong> ha sido actualizado correctamente',
    'message_delete_image'                  => '¿Desea borrar la imagen del elemento seleccionado?',
    'message_user_pass'                     => 'Indique su usuario y contraseña',
    'message_user'                          => 'Indique su usuario',
    'message_pass'                          => 'Indique su contraseña',
    'message_error_reset_password'          => 'Indique su email y nueva contraseña',
    'message_create_permission_successful'  => 'El permiso <strong>:action</strong> para el recurso <strong>:resource</strong> ha sido creado correctamente',
    'message_create_permission_error'       => 'El permiso <strong>:action</strong> para el recurso <strong>:resource</strong> no ha sido creado correctamente debido a un problema de permisos.<br><strong>Póngase en contacto con el administrador.</strong>',
    'message_delete_permission_successful'  => 'El permiso <strong>:action</strong> para el recurso <strong>:resource</strong> ha sido borrado correctamente',
    'message_delete_permission_error'       => 'El permiso <strong>:action</strong> para el recurso <strong>:resource</strong> no ha sido borrado correctamente debido a un problema de permisos.<br><strong>Póngase en contacto con el administrador.</strong>',
    'message_error_reset_password'          => 'Introduzca su email y nueva contraseña.',
    'message_error_login'                   => 'Acceso denegado',
    'message_error_login_msg_1'             => 'El usuario o la contraseña no coinciden con un usuario válido',
    'message_error_login_msg_2'             => 'El usuario indicado no tiene permisos de acceso a la aplicación, póngase en contacto con el administrador',
    'message_error_login_msg_3'             => 'El usuario indicado no está activado, póngase en contacto con el administrador',
    'resource'                              => 'Recurso|Recursos',
    'profile'                               => 'Perfil|Perfiles',
    'permission'                            => 'Permiso|Permisos',
    'user'                                  => 'Usuario|Usuarios',
    'family'                                => 'Familia|Familias',
    'cronjob'                               => 'Cron Job|Cron Jobs',
    'cron_expression'                       => 'Expression Cron',
    'key'                                   => 'Key',
    'country'                               => 'País|Paises',
    'name'                                  => 'Nombre',
    'surname'                               => 'Apellido',
    'language'                              => 'Idioma|Idiomas',
    'base_language'                         => 'Idioma base',
    'image'                                 => 'Imagen|Imágenes',
    'preference'                            => 'Preferencia|Preferencias',
    'repeat_password'                       => 'Repita contraseña',
    'action_successful'                     => '¡Acción realizada!',
    'action_error'                          => '¡Error!',
    'select_a'                              => 'Elija un',
    'data_access'                           => 'Datos de acceso',
    'last_run'                              => 'Última ejecución',
    'next_run'                              => 'Siguiente ejecución',
    'show_file'                             => 'Ver fichero',
    'message_error_403'                     => 'No dispone de permisos para acceder a esta sección',
    'message_error_404'                     => '¡Ups! página no encontrada',
    'go_back'                               => 'Volvar atrás',
    'category'                              => 'Categoría|Categorías',
    'contact'                               => 'Contacto|Contactos',
    'group'                                 => 'Grupo|Grupos',
    'business'                              => 'Negocio|Negocios',
    'phone'                                 => 'Teléfono',
    'company_name'                          => 'Nombre empresa',
    'company'                               => 'Empresa',
    'birth_date'                            => 'Fecha de nacimiento',
    'tin'                                   => 'CIF',
    'web'                                   => 'Web',
    'cp'                                    => 'CP',
    'locality'                              => 'Localidad',
    'master_tables'                         => 'Tablas maestras',
    'section'                               => 'Sección|Secciones',
    'article'                               => 'Articulo|Articulos',
    'customer'                              => 'Cliente|Clientes',
    'mobile'                                => 'Móvil',
    'prefix'                                => 'Prefijo',
    'suffix'                                => 'Sufijo',
    'code'                                  => 'Código',
    'alias'                                 => 'Alias',
    'favorite'                              => 'Favorito',
    'photo'                                 => 'Foto|Fotos',
    'observations'                          => 'Observaciones',
    'comment'                               => 'Comentario|Comentarios',
    'date'                                  => 'Fecha',
    'units'                                 => 'Unidades',
    'attached'                              => 'Adjunto',
    'subject_password_reset'                => 'Restablecer contraseña',
    'account'                               => 'Cuenta|Cuentas',
    'incoming_server'                       => 'Servidor entrante',
    'outgoing_server'                       => 'Servidor saliente',
    'reply_to'                              => 'Responder a',
    'incoming_type'                         => 'Tipo de cuenta',
    'incoming_secure'                       => 'Seguridad de entrada',
    'port'                                  => 'Puerto',
    'no_security'                           => 'Sin seguridad',
    'state'                                 => 'Estado|Estados',
    'color'                                 => 'Color|Colores',
    'form'                                  => 'Formulario|Formularios',
    'forward'                               => 'Reenvio|Reenvios',
    'push'                                  => 'Push',
    'add'                                   => 'Añadir',
    'update'                                => 'Actualizar',
    'opened'                                => 'Abierto',
    'record'                                => 'Registro|Registros',
    'subject'                               => 'Asunto',
    'message'                               => 'Mensaje',
    'all'                                   => 'Todos',
    'width'                                 => 'Ancho',
    'height'                                => 'Alto',
    'editor'                                => 'Editor',
    'title'                                 => 'Título',
    'add_tag'                               => 'Añadir etiqueta',
    'attachment'                            => 'Adjunto|Adjuntos',
    'file'                                  => 'Fichero|Ficheros',
    'video'                                 => 'Video|Videos',
    'library'                               => 'Librería|Librerías',
    'size'                                  => 'Tamaño|Tamaños',
    'type'                                  => 'Tipo',
    'geolocation'                           => 'Geolocalización|Geolocalizaciones',
    'latitude'                              => 'Latitud',
    'longitude'                             => 'Longitud',
    'fax'                                   => 'Fax',
    'description'                           => 'Descripción|Descripciones',
    'label'                                 => 'Etiqueta|Etiquetas',
    'iban'                                  => 'IBAN',
    'bic'                                   => 'SWIFT/BIC',
    'attachment_family'                     => 'Familia de adjunto|Familias de adjunto',
    'image_name'                            => 'Nombre de la imagen',
    'drag_files'                            => 'Arrastre aquí sus archivos',
    'select_family'                         => 'Seleccione una familia',
    'folder'                                => 'Carpeta|Carpetas',
    'slug'                                  => 'Slug',
    'template'                              => 'Plantilla|Plantillas',
    'theme'                                 => 'Theme|Themes',
    'content'                               => 'Contenido|Contenidos',
    'filter_records'                        => 'Filtrar registros',
    'icon'                                  => 'Icono|Iconos',
    'import'                                => 'Importación|Importaciones',
    'of'                                    => 'de',
    'showing'                               => 'Mostrando',
    'wildcard'                              => 'Comodín|Comodines',
    'preview'                               => 'Vista previa'
];