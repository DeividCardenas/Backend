# Arquitectura del Sistema - Nova Growth Backend

## Visión General

Nova Growth es una API REST construida con Laravel 12 para gestionar indicadores de desempeño, comités organizacionales y un sistema completo de usuarios con roles y permisos.

## Patrón de Arquitectura

El sistema sigue el patrón **MVC (Model-View-Controller)** de Laravel con algunas adaptaciones para API REST:

- **Models**: Representan las entidades y lógica de datos
- **Controllers**: Manejan las peticiones HTTP y respuestas JSON
- **Routes**: Definen los endpoints de la API (sin vistas tradicionales)

## Estructura de Directorios

```
Backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # Controladores de API
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── UserController.php
│   │   │   │   ├── RolController.php
│   │   │   │   ├── PermisoController.php
│   │   │   │   ├── ComiteController.php
│   │   │   │   ├── ReunionController.php
│   │   │   │   ├── IndicadorController.php
│   │   │   │   └── IndicadorValorController.php
│   │   │   └── Controller.php    # Controlador base
│   │   └── Middleware/
│   │       ├── CheckRole.php     # Verificación de roles
│   │       └── CheckPermission.php # Verificación de permisos
│   ├── Models/
│   │   ├── User.php              # Usuario del sistema
│   │   ├── Rol.php               # Roles (Admin, Usuario, etc)
│   │   ├── Permiso.php           # Permisos granulares
│   │   ├── Comite.php            # Comités organizacionales
│   │   ├── Reunion.php           # Reuniones de comités
│   │   ├── Indicador.php         # Indicadores de gestión
│   │   └── IndicadorValor.php    # Valores históricos
│   └── Providers/
│       └── AppServiceProvider.php
├── bootstrap/
│   └── app.php                   # Configuración de middleware
├── config/
│   ├── sanctum.php               # Configuración de autenticación
│   ├── database.php
│   └── ...
├── database/
│   ├── migrations/               # 14 migraciones totales
│   ├── seeders/
│   └── factories/
├── routes/
│   ├── api.php                   # Todas las rutas de API
│   └── web.php
├── docs/                         # Documentación del proyecto
└── tests/
    ├── Feature/
    └── Unit/
```

## Flujo de Datos

```
Cliente (Frontend/Postman)
    ↓
[HTTP Request + Bearer Token]
    ↓
Routes (api.php)
    ↓
Middleware (throttle, auth:sanctum, role, permission)
    ↓
Controller (valida, procesa lógica)
    ↓
Model (consulta/actualiza base de datos)
    ↓
Controller (formatea respuesta)
    ↓
[JSON Response]
    ↓
Cliente
```

## Capas del Sistema

### 1. Capa de Autenticación (Laravel Sanctum)

- Gestiona tokens de API
- Expiraci��n configurada: 24 horas
- Rate limiting: 10 intentos/minuto en login/register

**Flujo de autenticación:**
```
1. Usuario envía correo/password
2. AuthController valida credenciales
3. Si válido, genera token con Sanctum
4. Cliente guarda token
5. Cliente incluye token en header: "Authorization: Bearer {token}"
6. Sanctum valida token en cada request
```

### 2. Capa de Autorización (RBAC)

Sistema de Roles y Permisos basado en relaciones muchos-a-muchos:

```
Usuario ←→ Rol ←→ Permiso
```

**Middleware de autorización:**
- `CheckRole`: Verifica si el usuario tiene un rol específico
- `CheckPermission`: Verifica si el usuario tiene un permiso específico

**Ejemplo de uso:**
```php
Route::middleware(['auth:sanctum', 'role:Administrador'])->group(function () {
    // Solo administradores
});

Route::middleware(['auth:sanctum', 'permission:crear_usuarios'])->group(function () {
    // Solo usuarios con permiso específico
});
```

### 3. Capa de Modelos

**Relaciones implementadas:**

1. **User**
   - belongsToMany → Rol (tabla: usuario_rol)
   - hasMany → Comite (como responsable)
   - belongsToMany → Comite (como miembro)
   - hasMany → Indicador (como responsable)

2. **Rol**
   - belongsToMany → User
   - belongsToMany → Permiso (tabla: rol_permiso)

3. **Permiso**
   - belongsToMany → Rol

4. **Comite**
   - belongsTo → User (responsable)
   - belongsToMany → User (miembros, tabla: comite_miembros)
   - hasMany → Reunion

5. **Reunion**
   - belongsTo → Comite

6. **Indicador**
   - belongsTo → User (responsable)
   - hasMany → IndicadorValor

7. **IndicadorValor**
   - belongsTo → Indicador
   - belongsTo → User (registrado_por)

### 4. Capa de Controladores

Todos los controladores siguen el patrón **Resource Controller** con métodos estándar:

- `index()` - GET - Listar todos los recursos
- `store()` - POST - Crear nuevo recurso
- `show($id)` - GET - Ver recurso específico
- `update($id)` - PUT/PATCH - Actualizar recurso
- `destroy($id)` - DELETE - Eliminar/desactivar recurso

**Características especiales:**

- **UserController**: Desactivación soft (campo `activo = false`)
- **IndicadorController**: Desactivación soft
- **ReunionController**: Manejo de archivos (actas PDF/DOC)
- **IndicadorValorController**: Auto-registro del usuario que crea el valor

### 5. Capa de Validación

Validaciones implementadas directamente en controladores usando `$request->validate()`:

**Ejemplos:**
```php
// AuthController
'nombre' => 'required|string|max:60',
'correo' => 'required|email|unique:usuarios,correo',
'password' => 'required|min:8|confirmed',

// ComiteController
'nombre' => 'required|string|max:255',
'objetivo' => 'required|string',
'miembros' => 'array',
'miembros.*' => 'exists:usuarios,id_usuario',
```

### 6. Capa de Base de Datos

**Motor:** SQLite (desarrollo) / MySQL o PostgreSQL (producción)

**Estrategia de IDs:**
- Todas las tablas usan `bigIncrements` para IDs personalizados
- Nomenclatura: `id_usuario`, `id_rol`, `id_comite`, etc.

**Tablas Pivot (muchos-a-muchos):**
- `usuario_rol` - Primary key compuesta: [id_usuario, id_rol]
- `rol_permiso` - Primary key compuesta: [id_rol, id_permiso]
- `comite_miembros` - Primary key compuesta + timestamps

**Integridad Referencial:**
- Todas las FKs usan `onDelete('cascade')` o `onDelete('set null')`
- Garantiza limpieza automática al eliminar registros padre

## Seguridad Implementada

### Nivel 1: Autenticación
- Tokens con expiración
- Contraseñas hasheadas con bcrypt (rounds: 12)
- Validación de usuarios activos

### Nivel 2: Rate Limiting
- 10 requests/minuto en rutas de autenticación
- Previene ataques de fuerza bruta

### Nivel 3: Autorización
- Middleware de roles
- Middleware de permisos
- Todas las rutas protegidas excepto login/register/ping

### Nivel 4: Validación de Datos
- Validación de tipos
- Validación de longitudes
- Validación de unicidad
- Validación de relaciones (exists)

### Nivel 5: Soft Delete
- Usuarios e Indicadores usan campo `activo`
- No se eliminan físicamente de la BD

## Escalabilidad

### Actual
- API REST stateless (sin sesiones)
- Tokens en caché (Redis compatible)
- Consultas con Eager Loading (`with()`)

### Recomendaciones Futuras
1. Implementar capa de Servicios para lógica compleja
2. Usar Repository Pattern para abstracción de datos
3. Implementar cache de consultas frecuentes
4. Agregar paginación en endpoints de listado
5. Implementar Jobs para procesos pesados
6. Agregar logging estructurado

## Mantenibilidad

### Convenciones Seguidas
- PSR-12: Estilo de código PHP
- RESTful: Diseño de API
- Eloquent ORM: Queries legibles
- Nombres descriptivos en español (dominio del negocio)

### Testing (Pendiente)
- Feature tests para endpoints
- Unit tests para modelos
- Tests de autorización

## Diagrama Entidad-Relación

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│   Usuario   │───────│ usuario_rol │───────│     Rol     │
│             │  N:M  └─────────────┘  N:M  │             │
│ id_usuario  │                             │   id_rol    │
│ nombre      │                             │   nombre    │
│ correo      │                             └──────┬──────┘
│ password    │                                    │
│ activo      │                                    │ N:M
└──────┬──────┘                             ┌──────┴──────┐
       │                                    │ rol_permiso │
       │ 1:N                                └──────┬──────┘
       │                                           │
┌──────┴──────┐                             ┌──────┴──────┐
│   Comite    │                             │   Permiso   │
│             │                             │             │
│ id_comite   │                             │ id_permiso  │
│ nombre      │                             │ nombre      │
│ objetivo    │                             └─────────────┘
│responsable_id│
└──────┬──────┘
       │ 1:N
       │
┌──────┴──────┐
│   Reunion   │
│             │
│ id_reunion  │
│ fecha       │
│ tema        │
│ acuerdos    │
│ archivo_acta│
└─────────────┘

┌─────────────┐
│  Indicador  │
│             │
│id_indicador │
│ nombre      │
│ formula     │
│ meta        │
│responsable_id│
└──────┬──────┘
       │ 1:N
       │
┌──────┴──────────┐
│ IndicadorValor  │
│                 │
│ id_valor        │
│ id_indicador    │
│ valor           │
│ fecha           │
│ registrado_por  │
└─────────────────┘
```

## Próximos Pasos Recomendados

1. Implementar seeders con datos de ejemplo
2. Crear tests automatizados
3. Implementar logging con Monolog
4. Agregar documentación OpenAPI/Swagger
5. Implementar versionado de API (v1, v2)
6. Agregar observables de Eloquent para auditoría
7. Implementar notificaciones por email
8. Agregar exportación de reportes (PDF, Excel)
