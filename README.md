# UpTask - Sistema de Gestión de Proyectos y Tareas

Sistema web para la gestión de proyectos y tareas personales, desarrollado con PHP 8 siguiendo el patrón MVC.

## Características

- **Autenticación de usuarios**: Registro, login, logout y recuperación de contraseña
- **Gestión de proyectos**: Creación y organización de proyectos personales
- **Sistema de tareas**: Creación, edición, eliminación y cambio de estado de tareas
- **Filtros de tareas**: Visualización por estado (todas, completadas, pendientes)
- **Confirmación por email**: Verificación de cuenta y recuperación de contraseña via SMTP
- **Perfil de usuario**: Edición de datos personales y cambio de contraseña
- **API REST**: Endpoints para operaciones CRUD de tareas
- **Interfaz responsiva**: Diseño adaptable con SASS y soporte para Dark Mode

## Requisitos

- PHP 8.0 o superior
- MySQL 5.7 o superior
- Composer
- Node.js y npm
- Servidor web (Apache/Nginx)

## Instalación

1. Clonar el repositorio
```bash
git clone https://github.com/mateopavoni/UpTask.git
cd UpTask
```

2. Instalar dependencias PHP
```bash
composer install
```

3. Instalar dependencias Node
```bash
npm install
```

4. Configurar variables de entorno
```bash
cp .env.example .env
```

5. Editar `.env` con los datos de tu entorno:
```
DB_HOST=localhost
DB_USER=tu_usuario
DB_PASS=tu_contraseña
DB_NAME=uptask

MAIL_HOST=smtp.ejemplo.com
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_smtp
MAIL_PASSWORD=tu_contraseña_smtp
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=correo@tudominio.com
MAIL_FROM_NAME=UpTask
```

6. Crear la base de datos con las tablas necesarias (usuarios, proyectos, tareas)

7. Compilar assets
```bash
npm run dev
```

8. Configurar el servidor web para apuntar a `/public`

## Estructura del Proyecto

```
├── classes/          # Clases auxiliares (Email)
├── controllers/      # Controladores MVC
├── includes/         # Archivos de configuración y funciones
├── models/           # Modelos (ActiveRecord, Usuario, Proyecto, Tarea)
├── public/           # Punto de entrada y assets compilados
├── src/              # Código fuente (SASS, JS)
│   ├── scss/         # Estilos SASS organizados por módulos
│   └── js/           # JavaScript vanilla
├── views/            # Vistas PHP
│   ├── auth/         # Vistas de autenticación
│   ├── dashboard/    # Vistas del panel principal
│   └── templates/    # Plantillas reutilizables
├── Router.php        # Sistema de enrutamiento
└── composer.json     # Dependencias PHP
```

## Rutas Principales

- `/` - Login
- `/crear` - Registro de usuarios
- `/olvide` - Recuperación de contraseña
- `/reestablecer` - Reestablecer contraseña
- `/confirmar` - Confirmación de cuenta
- `/dashboard` - Panel de proyectos
- `/crear-proyecto` - Crear nuevo proyecto
- `/proyecto` - Vista de tareas del proyecto
- `/perfil` - Edición de perfil
- `/cambiar-password` - Cambio de contraseña

## API REST

- `GET /api/tareas` - Obtener tareas de un proyecto
- `POST /api/tarea` - Crear nueva tarea
- `POST /api/tarea/actualizar` - Actualizar tarea existente
- `POST /api/tarea/eliminar` - Eliminar tarea

## Tecnologías

- **Backend**: PHP 8, MVC Pattern, PSR-4 Autoloading
- **Base de datos**: MySQL con ActiveRecord personalizado
- **Frontend**: HTML5, SASS, JavaScript vanilla
- **Build Tools**: Gulp (compilación SASS, minificación JS)
- **Librerías**: PHPMailer, vlucas/phpdotenv, SweetAlert2

## Seguridad

- Contraseñas hasheadas con bcrypt (PASSWORD_BCRYPT)
- Tokens únicos para confirmación de cuenta y recuperación de contraseña
- Sanitización de datos con `htmlspecialchars`
- Escape de consultas SQL con `mysqli_real_escape_string`
- Sesiones PHP para autenticación
- Validación de formularios en backend
- Verificación de propiedad de proyectos por usuario

## Autor

Mateo Pavoni - mateopavoni905@gmail.com
