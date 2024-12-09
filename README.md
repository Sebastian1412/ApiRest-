# API REST en PHP para Gestión de Usuarios

Esta es una API REST sencilla desarrollada en PHP para gestionar usuarios, diseñada con el objetivo de aprender y aplicar conceptos básicos de programación de APIs, validación de datos y seguridad.

## Características

- **CRUD Completo**: Crear, Leer, Actualizar y Eliminar usuarios.
- **Validaciones**: Validación básica para entradas como nombre, RUT, correo y fecha de nacimiento.
- **Log de actividades**: Registro de operaciones en un archivo de log.
- **JSON Encoding Seguro**: Escapado de caracteres especiales en las respuestas JSON para evitar vulnerabilidades XSS.
- **Estructura Simple y Escalable**: Organización de funciones y manejo de errores.

## Tecnologías Utilizadas

- PHP 7.4+ 
- MySQL como base de datos
- PDO para conexiones a la base de datos
- JSON como formato de intercambio de datos

## Requisitos Previos

Antes de instalar y ejecutar esta API, asegúrate de tener lo siguiente configurado:

1. **Servidor Web** con soporte para PHP (como Apache o Nginx).
2. **PHP 7.4 o superior**.
3. **Base de datos MySQL**.
4. **Postman o cualquier cliente de API REST** para probar los endpoints.

## Instalación

1. **Clona este repositorio**:

   ```bash
   git clone [https://github.com/tu_usuario/tu_repositorio.git](https://github.com/Sebastian1412/ApiRest-Php.git)
   cd tu_repositorio
