# Organización de Carpetas - Nova Growth Backend

## Estructura Completa del Proyecto

```
Backend/
├── app/                          # Código fuente de la aplicación
│   ├── Http/                     # Capa HTTP
│   │   ├── Controllers/          # Controladores
│   │   │   ├── Api/              # Controladores de API REST
│   │   │   │   ├── AuthController.php       (216 líneas)
│   │   │   │   ├── ComiteController.php     (67 líneas)
│   │   │   │   ├── IndicadorController.php  (64 líneas)
│   │   │   │   ├── IndicadorValorController.php (61 líneas)
│   │   │   │   ├── PermisoController.php    (51 líneas)
│   │   │   │   ├── ReunionController.php    (77 líneas)
│   │   │   │   ├── RolController.php        (70 líneas)
│   │   │   │   └── UserController.php       (82 líneas)
│   │   │   └── Controller.php    # Controlador base abstracto
│   │   └── Middleware/           # Middleware personalizado
│   │       ├── CheckPermission.php  # Verifica permisos
│   │       └── CheckRole.php        # Verifica roles
│   ├── Models/                   # Modelos Eloquent ORM
│   │   ├── User.php              # Usuario (67 líneas)
│   │   ├── Rol.php               # Rol (26 líneas)
│   │   ├── Permiso.php           # Permiso (22 líneas)
│   │   ├── Comite.php            # Comité (38 líneas)
│   │   ├── Reunion.php           # Reunión (30 líneas)
│   │   ├── Indicador.php         # Indicador (43 líneas)
│   │   └── IndicadorValor.php    # Valor de indicador (39 líneas)
│   └── Providers/                # Service Providers de Laravel
│       └── AppServiceProvider.php
├── bootstrap/                    # Archivos de arranque
│   ├── app.php                   # Configuración de aplicación
│   ├── cache/                    # Cache de configuración
│   └── providers.php             # Providers del sistema
├── config/                       # Archivos de configuración
│   ├── app.php                   # Configuración general
│   ├── auth.php                  # Configuración de autenticación
│   ├── cache.php                 # Configuración de cache
│   ├── database.php              # Configuración de BD
│   ├── sanctum.php               # Configuración de tokens API
│   ├── session.php               # Configuración de sesiones
│   └── ...                       # Más configuraciones
├── database/                     # Base de datos
│   ├── migrations/               # Migraciones de BD (14 archivos)
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── 2025_08_21_043656_create_roles_table.php
│   │   ├── 2025_08_21_043656_create_permisos_table.php
│   │   ├── 2025_08_21_043656_create_rol_permiso_table.php
│   │   ├── 2025_08_21_043657_create_usuarios_table.php
│   │   ├── 2025_08_21_043657_create_usuario_rol_table.php
│   │   ├── 2025_08_21_043657_create_comites_table.php
│   │   ├── 2025_08_21_043657_create_comite_miembros_table.php
│   │   ├── 2025_08_21_043657_create_reuniones_table.php
│   │   ├── 2025_08_21_043657_create_indicadores_table.php
│   │   ├── 2025_08_21_043657_create_indicador_valores_table.php
│   │   └── 2025_08_23_023738_create_personal_access_tokens_table.php
│   ├── seeders/                  # Seeders para datos iniciales
│   │   └── DatabaseSeeder.php
│   └── factories/                # Factories para testing
│       └── UserFactory.php
├── docs/                         # Documentación del proyecto
│   ├── ARQUITECTURA.md           # Arquitectura del sistema
│   ├── ORGANIZACION.md           # Este archivo
│   └── GUIA_DESARROLLO.md        # Guía para desarrolladores
├── public/                       # Carpeta pública (punto de entrada web)
│   └── index.php                 # Archivo de entrada
├── resources/                    # Recursos del proyecto
│   ├── css/                      # Estilos CSS
│   ├── js/                       # JavaScript
│   └── views/                    # Vistas blade (no usado en API)
├── routes/                       # Definición de rutas
│   ├── api.php                   # Rutas de API REST (/api/*)
│   ├── web.php                   # Rutas web
│   └── console.php               # Comandos de consola
├── storage/                      # Almacenamiento temporal
│   ├── app/                      # Archivos de aplicación
│   │   └── public/               # Archivos públicos (actas de reuniones)
│   ├── framework/                # Cache, sesiones, vistas compiladas
│   └── logs/                     # Logs de la aplicación
├── tests/                        # Tests automatizados
│   ├── Feature/                  # Tests de características
│   └── Unit/                     # Tests unitarios
├── vendor/                       # Dependencias de Composer (no en git)
├── .env                          # Variables de entorno (no en git)
├── .env.example                  # Ejemplo de variables de entorno
├── .gitignore                    # Archivos ignorados por git
├── artisan                       # CLI de Laravel
├── composer.json                 # Dependencias PHP
├── composer.lock                 # Lock de dependencias
├── package.json                  # Dependencias NPM
├── phpunit.xml                   # Configuración de PHPUnit
├── README.md                     # Documentación principal
└── vite.config.js                # Configuración de Vite
```

## Detalles por Carpeta

### 📁 app/

Contiene toda la lógica de la aplicación.

#### app/Http/Controllers/

**Propósito:** Manejar las peticiones HTTP y devolver respuestas.

**Archivos:**
- `Controller.php` - Clase base de la que heredan todos los controladores
- `Api/` - Subcarpeta con 8 controladores REST
  - Cada controlador maneja un recurso específico
  - Implementan métodos CRUD estándar
  - Validación de datos en cada método

**Convención de nombres:**
- `{Recurso}Controller.php` (singular, PascalCase)
- Ejemplo: `UserController.php`, `ComiteController.php`

#### app/Http/Middleware/

**Propósito:** Interceptar y procesar requests antes de llegar al controlador.

**Archivos:**
- `CheckRole.php` - Valida que el usuario tenga un rol específico
- `CheckPermission.php` - Valida que el usuario tenga un permiso específico

**Uso:**
```php
// En routes/api.php
Route::middleware(['auth:sanctum', 'role:Admin'])->group(...);
```

#### app/Models/

**Propósito:** Representar las tablas de la base de datos y sus relaciones.

**Características:**
- Usan Eloquent ORM
- Definen relaciones (hasMany, belongsTo, belongsToMany)
- Configuran fillable, casts, hidden
- Total: 7 modelos

**Convención de nombres:**
- Nombre en singular, PascalCase
- Ejemplo: `User.php` representa tabla `usuarios`

### 📁 config/

Archivos de configuración del sistema.

**Archivos importantes:**
- `sanctum.php` - Configuración de autenticación API
  - `expiration: 1440` (24 horas)
  - Dominios permitidos para cookies
- `database.php` - Configuración de conexiones a BD
  - Por defecto usa SQLite
- `app.php` - Configuración general
  - Timezone, locale, debug mode

### 📁 database/migrations/

**Propósito:** Versionamiento del esquema de base de datos.

**Orden de ejecución:**
```
1. 0001_01_01_000000 - Tablas de sistema (cache, jobs)
2. 2025_08_21_043656 - Roles y permisos
3. 2025_08_21_043657 - Usuarios
4. 2025_08_21_043657 - Tablas pivot (relaciones)
5. 2025_08_21_043657 - Comités, reuniones
6. 2025_08_21_043657 - Indicadores y valores
7. 2025_08_23_023738 - Tokens de API
```

**Comando para ejecutar:**
```bash
php artisan migrate
```

**Rollback:**
```bash
php artisan migrate:rollback
```

### 📁 routes/

**Archivos:**

#### routes/api.php
Define todos los endpoints de la API.

**Estructura:**
```php
// Autenticación (sin protección)
/api/auth/register
/api/auth/login

// Autenticación (con token)
/api/auth/profile
/api/auth/logout

// Recursos CRUD (todos protegidos)
/api/usuarios
/api/roles
/api/permisos
/api/comites
/api/reuniones
/api/indicadores
/api/indicador-valores
```

**Prefijo automático:** Todas las rutas en este archivo tienen prefijo `/api`

#### routes/web.php
Rutas web tradicionales (no usado en este proyecto).

#### routes/console.php
Comandos de consola personalizados.

### 📁 storage/

**Estructura:**

```
storage/
├── app/
│   ├── public/          # Archivos públicos accesibles vía web
│   │   └── actas/       # Actas de reuniones subidas
│   └── private/         # Archivos privados
├── framework/
│   ├── cache/           # Cache de aplicación
│   ├── sessions/        # Sesiones de usuarios
│   └── views/           # Vistas compiladas
└── logs/
    └── laravel.log      # Log de errores y eventos
```

**Importante:** Crear enlace simbólico para archivos públicos:
```bash
php artisan storage:link
```

### 📁 tests/

**Estructura:**

```
tests/
├── Feature/             # Tests de funcionalidades completas
│   └── ExampleTest.php
├── Unit/                # Tests unitarios de clases individuales
│   └── ExampleTest.php
└── TestCase.php         # Clase base para tests
```

**Ejecutar tests:**
```bash
php artisan test
```

### 📁 docs/

Documentación del proyecto.

**Archivos:**
- `ARQUITECTURA.md` - Arquitectura técnica del sistema
- `ORGANIZACION.md` - Organización de carpetas (este archivo)
- `GUIA_DESARROLLO.md` - Guía para desarrolladores

## Archivos en la Raíz

### Archivos de Configuración

- **composer.json** - Dependencias PHP
  - Laravel 12
  - Sanctum 4.2
  - PHPUnit 11.5

- **package.json** - Dependencias JavaScript
  - Vite para build de assets

- **.env.example** - Plantilla de variables de entorno
  - Copiar a `.env` para configuración local

- **phpunit.xml** - Configuración de tests

### Archivos de Control

- **.gitignore** - Archivos excluidos de git
  - vendor/
  - node_modules/
  - .env
  - storage/

- **artisan** - CLI de Laravel
  - Comandos útiles: `php artisan list`

## Flujo de Archivos en una Petición

```
1. public/index.php
   ↓
2. bootstrap/app.php (carga la app)
   ↓
3. routes/api.php (encuentra la ruta)
   ↓
4. Middleware (auth:sanctum, throttle, role, permission)
   ↓
5. app/Http/Controllers/Api/{Recurso}Controller.php
   ↓
6. app/Models/{Modelo}.php (consulta BD)
   ↓
7. database/ (SQLite o MySQL)
   ↓
8. Respuesta JSON al cliente
```

## Convenciones de Nombres

### Archivos
- **Controladores:** `{Recurso}Controller.php` (singular)
- **Modelos:** `{Entidad}.php` (singular)
- **Migraciones:** `{fecha}_{acción}_{tabla}_table.php` (plural)
- **Middleware:** `Check{Condición}.php`

### Código
- **Clases:** PascalCase (`UserController`)
- **Métodos:** camelCase (`getUserById`)
- **Variables:** snake_case (`$user_id`) o camelCase (`$userId`)
- **Tablas BD:** snake_case plural (`usuarios`, `comites`)
- **Columnas BD:** snake_case (`id_usuario`, `created_at`)

## Mejores Prácticas Implementadas

✅ Separación de responsabilidades (Controllers, Models, Routes)
✅ Nomenclatura consistente en español (dominio del negocio)
✅ Validación en controladores
✅ Relaciones Eloquent bien definidas
✅ Middleware para seguridad
✅ Migraciones versionadas
✅ Configuración centralizada en /config
✅ Documentación en /docs

## Tareas Pendientes

⏳ Implementar Seeders con datos de ejemplo
⏳ Crear Tests automatizados (Feature + Unit)
⏳ Implementar Repository Pattern (capa de abstracción)
⏳ Agregar Service Layer para lógica compleja
⏳ Implementar Request Classes para validación
⏳ Documentación OpenAPI/Swagger
⏳ Logging estructurado
⏳ Observadores de Eloquent para auditoría

## Comandos Útiles

```bash
# Ver estructura de archivos
tree -L 3 -I 'vendor|node_modules'

# Contar líneas de código
cloc app/

# Listar rutas
php artisan route:list

# Listar modelos
php artisan model:show User

# Generar clases
php artisan make:controller Api/NuevoController --api
php artisan make:model Nuevo
php artisan make:migration create_nuevos_table
php artisan make:middleware CheckNuevo
php artisan make:seeder NuevoSeeder

# Limpiar caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```
