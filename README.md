# Nova Growth Backend

API REST para gestión de indicadores, comités y usuarios con sistema de roles y permisos.

## Stack Tecnológico

- Laravel 12
- PHP 8.2+
- SQLite (desarrollo) / MySQL/PostgreSQL (producción)
- Laravel Sanctum (autenticación)

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## Estructura de la Base de Datos

**Tablas principales:**
- `usuarios` - Gestión de usuarios con control de estado activo/inactivo
- `roles` - Roles del sistema con permisos asociados
- `permisos` - Permisos granulares para acciones específicas
- `comites` - Comités con responsables y miembros
- `reuniones` - Reuniones de comités con actas
- `indicadores` - Indicadores de gestión con metas y fórmulas
- `indicador_valores` - Valores históricos de indicadores

## Autenticación

Todos los endpoints requieren autenticación mediante Bearer Token, excepto register y login.

**Rate Limiting:** 10 intentos por minuto en rutas de autenticación.

**Expiración de tokens:** 24 horas (1440 minutos).

### Registro

```http
POST /api/auth/register
Content-Type: application/json

{
  "nombre": "Juan Pérez",
  "correo": "juan@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login

```http
POST /api/auth/login
Content-Type: application/json

{
  "correo": "juan@example.com",
  "password": "password123"
}
```

**Respuesta:**
```json
{
  "user": {...},
  "token": "1|abc123..."
}
```

### Perfil

```http
GET /api/auth/profile
Authorization: Bearer {token}
```

### Logout

```http
POST /api/auth/logout
Authorization: Bearer {token}
```

## Endpoints API

Todos los endpoints CRUD siguen el estándar REST. Usar header:
```
Authorization: Bearer {token}
```

### Usuarios

```http
GET    /api/usuarios           # Listar usuarios activos
POST   /api/usuarios           # Crear usuario
GET    /api/usuarios/{id}      # Ver usuario
PUT    /api/usuarios/{id}      # Actualizar usuario
DELETE /api/usuarios/{id}      # Desactivar usuario
```

**Crear/Actualizar usuario:**
```json
{
  "nombre": "María López",
  "correo": "maria@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "activo": true,
  "roles": [1, 2]
}
```

### Roles

```http
GET    /api/roles              # Listar roles
POST   /api/roles              # Crear rol
GET    /api/roles/{id}         # Ver rol
PUT    /api/roles/{id}         # Actualizar rol
DELETE /api/roles/{id}         # Eliminar rol
```

**Crear rol:**
```json
{
  "nombre": "Administrador",
  "descripcion": "Acceso completo al sistema",
  "permisos": [1, 2, 3]
}
```

### Permisos

```http
GET    /api/permisos           # Listar permisos
POST   /api/permisos           # Crear permiso
GET    /api/permisos/{id}      # Ver permiso
PUT    /api/permisos/{id}      # Actualizar permiso
DELETE /api/permisos/{id}      # Eliminar permiso
```

### Comités

```http
GET    /api/comites            # Listar comités
POST   /api/comites            # Crear comité
GET    /api/comites/{id}       # Ver comité con reuniones
PUT    /api/comites/{id}       # Actualizar comité
DELETE /api/comites/{id}       # Eliminar comité
```

**Crear comité:**
```json
{
  "nombre": "Comité de Calidad",
  "objetivo": "Supervisar procesos de calidad",
  "responsable_id": 1,
  "miembros": [1, 2, 3]
}
```

### Reuniones

```http
GET    /api/reuniones                    # Listar reuniones
GET    /api/reuniones?id_comite=1        # Filtrar por comité
POST   /api/reuniones                    # Crear reunión
GET    /api/reuniones/{id}               # Ver reunión
PUT    /api/reuniones/{id}               # Actualizar reunión
DELETE /api/reuniones/{id}               # Eliminar reunión
```

**Crear reunión:**
```json
{
  "id_comite": 1,
  "fecha": "2025-11-15",
  "tema": "Revisión trimestral",
  "acuerdos": "Se aprobó el presupuesto",
  "archivo_acta": "file upload (opcional)"
}
```

### Indicadores

```http
GET    /api/indicadores        # Listar indicadores activos
POST   /api/indicadores        # Crear indicador
GET    /api/indicadores/{id}   # Ver indicador con valores históricos
PUT    /api/indicadores/{id}   # Actualizar indicador
DELETE /api/indicadores/{id}   # Desactivar indicador
```

**Crear indicador:**
```json
{
  "nombre": "Satisfacción del cliente",
  "descripcion": "Medición de satisfacción",
  "formula": "(Clientes satisfechos / Total) * 100",
  "meta": ">=90%",
  "unidad": "%",
  "responsable_id": 1,
  "activo": true
}
```

### Valores de Indicadores

```http
GET    /api/indicador-valores                      # Listar valores
GET    /api/indicador-valores?id_indicador=1       # Filtrar por indicador
POST   /api/indicador-valores                      # Registrar valor
GET    /api/indicador-valores/{id}                 # Ver valor
PUT    /api/indicador-valores/{id}                 # Actualizar valor
DELETE /api/indicador-valores/{id}                 # Eliminar valor
```

**Registrar valor:**
```json
{
  "id_indicador": 1,
  "valor": 92.5,
  "fecha": "2025-11-01",
  "observaciones": "Resultado del mes de octubre"
}
```

## Middleware de Permisos

**Verificar rol:**
```php
Route::middleware(['auth:sanctum', 'role:Administrador'])->group(...);
```

**Verificar permiso:**
```php
Route::middleware(['auth:sanctum', 'permission:crear_usuarios'])->group(...);
```

## Relaciones entre Modelos

- **Usuario** ↔ **Roles** (muchos a muchos)
- **Rol** ↔ **Permisos** (muchos a muchos)
- **Usuario** → **Comités** (responsable, uno a muchos)
- **Usuario** ↔ **Comités** (miembros, muchos a muchos)
- **Usuario** → **Indicadores** (responsable, uno a muchos)
- **Comité** → **Reuniones** (uno a muchos)
- **Indicador** → **IndicadorValores** (uno a muchos)

## Seguridad Implementada

- Contraseñas hasheadas con bcrypt
- Mínimo 8 caracteres en contraseñas
- Tokens con expiración de 24 horas
- Rate limiting en autenticación (10 intentos/minuto)
- Validación de usuarios activos en login
- Soft delete (desactivación) de usuarios e indicadores
- Middleware de roles y permisos

## Variables de Entorno Importantes

```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite

SANCTUM_STATEFUL_DOMAINS=localhost:3000
```

## Testing

```bash
php artisan test
```

## Producción

1. Configurar base de datos MySQL/PostgreSQL en `.env`
2. `APP_DEBUG=false`
3. `APP_ENV=production`
4. Configurar dominios en `SANCTUM_STATEFUL_DOMAINS`
5. `php artisan config:cache`
6. `php artisan route:cache`
