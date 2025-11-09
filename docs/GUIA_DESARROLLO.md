# Guía de Desarrollo - Nova Growth Backend

## Inicio Rápido

### Requisitos Previos

- PHP 8.2 o superior
- Composer
- SQLite (desarrollo) o MySQL/PostgreSQL (producción)
- Git

### Instalación

```bash
# 1. Clonar repositorio
git clone <url-repositorio>
cd Backend

# 2. Instalar dependencias
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Ejecutar migraciones
php artisan migrate

# 5. Iniciar servidor
php artisan serve
```

La API estará disponible en: `http://localhost:8000/api`

## Estructura de Datos

### Tablas de la Base de Datos

```sql
-- Gestión de Usuarios y Permisos
usuarios (id_usuario, nombre, correo, password, activo)
roles (id_rol, nombre, descripcion)
permisos (id_permiso, nombre, descripcion)
usuario_rol (id_usuario, id_rol)              -- Pivot
rol_permiso (id_rol, id_permiso)              -- Pivot

-- Gestión de Comités
comites (id_comite, nombre, objetivo, responsable_id)
comite_miembros (id_comite, id_usuario)       -- Pivot
reuniones (id_reunion, id_comite, fecha, tema, acuerdos, archivo_acta)

-- Gestión de Indicadores
indicadores (id_indicador, nombre, descripcion, formula, meta, unidad, responsable_id, activo)
indicador_valores (id_valor, id_indicador, valor, fecha, observaciones, registrado_por)

-- Sistema
personal_access_tokens                         -- Tokens de Sanctum
cache, jobs, sessions                          -- Caché y tareas
```

### Campos Importantes

**Usuarios:**
- `correo` - Email único para login
- `activo` - Boolean, permite desactivar sin eliminar
- `password` - Hasheado con bcrypt

**Indicadores:**
- `formula` - String, fórmula de cálculo
- `meta` - String, objetivo del indicador
- `unidad` - String, unidad de medida (%, €, unidades, etc)

**Reuniones:**
- `archivo_acta` - String, ruta al archivo en storage/app/public/actas

## Cómo Trabajar con la API

### 1. Autenticación

#### Registrar Usuario

```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan Pérez",
    "correo": "juan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**Respuesta:**
```json
{
  "user": {
    "id_usuario": 1,
    "nombre": "Juan Pérez",
    "correo": "juan@example.com",
    "activo": true
  },
  "token": "1|abcdef123456..."
}
```

#### Login

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "correo": "juan@example.com",
    "password": "password123"
  }'
```

**Guardar el token recibido para usarlo en siguientes peticiones.**

### 2. Uso del Token

Incluir en cada petición:
```
Authorization: Bearer {token}
```

Ejemplo:
```bash
curl -X GET http://localhost:8000/api/usuarios \
  -H "Authorization: Bearer 1|abcdef123456..."
```

### 3. Operaciones CRUD

#### Crear Recurso (POST)

```bash
# Crear rol
curl -X POST http://localhost:8000/api/roles \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Administrador",
    "descripcion": "Acceso completo al sistema",
    "permisos": [1, 2, 3]
  }'
```

#### Listar Recursos (GET)

```bash
# Listar usuarios
curl -X GET http://localhost:8000/api/usuarios \
  -H "Authorization: Bearer {token}"
```

#### Ver Recurso (GET)

```bash
# Ver usuario específico
curl -X GET http://localhost:8000/api/usuarios/1 \
  -H "Authorization: Bearer {token}"
```

#### Actualizar Recurso (PUT)

```bash
# Actualizar usuario
curl -X PUT http://localhost:8000/api/usuarios/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Juan Pérez Actualizado",
    "activo": true
  }'
```

#### Eliminar Recurso (DELETE)

```bash
# Desactivar usuario
curl -X DELETE http://localhost:8000/api/usuarios/1 \
  -H "Authorization: Bearer {token}"
```

**Nota:** DELETE en usuarios e indicadores hace soft delete (activo=false)

### 4. Trabajar con Relaciones

#### Crear Usuario con Roles

```bash
curl -X POST http://localhost:8000/api/usuarios \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "María López",
    "correo": "maria@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "roles": [1, 2]
  }'
```

#### Crear Comité con Miembros

```bash
curl -X POST http://localhost:8000/api/comites \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Comité de Calidad",
    "objetivo": "Supervisar procesos de calidad",
    "responsable_id": 1,
    "miembros": [1, 2, 3, 4]
  }'
```

#### Actualizar Miembros de Comité

```bash
curl -X PUT http://localhost:8000/api/comites/1 \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "miembros": [1, 2, 5, 6]
  }'
```

**Nota:** El array de miembros reemplaza completamente los existentes (sync).

### 5. Filtrado de Datos

```bash
# Reuniones de un comité específico
curl -X GET "http://localhost:8000/api/reuniones?id_comite=1" \
  -H "Authorization: Bearer {token}"

# Valores de un indicador específico
curl -X GET "http://localhost:8000/api/indicador-valores?id_indicador=2" \
  -H "Authorization: Bearer {token}"
```

### 6. Subida de Archivos

```bash
# Crear reunión con archivo de acta
curl -X POST http://localhost:8000/api/reuniones \
  -H "Authorization: Bearer {token}" \
  -F "id_comite=1" \
  -F "fecha=2025-11-15" \
  -F "tema=Revisión trimestral" \
  -F "acuerdos=Se aprobó el presupuesto" \
  -F "archivo_acta=@/ruta/al/archivo.pdf"
```

**Formatos permitidos:** PDF, DOC, DOCX
**Tamaño máximo:** 10MB

## Desarrollo de Nuevas Funcionalidades

### Agregar Nuevo Endpoint

#### 1. Crear Migración

```bash
php artisan make:migration create_proyectos_table
```

Editar archivo en `database/migrations/`:
```php
public function up(): void {
    Schema::create('proyectos', function (Blueprint $table) {
        $table->bigIncrements('id_proyecto');
        $table->string('nombre');
        $table->text('descripcion')->nullable();
        $table->unsignedBigInteger('responsable_id')->nullable();
        $table->timestamps();

        $table->foreign('responsable_id')
              ->references('id_usuario')
              ->on('usuarios')
              ->onDelete('set null');
    });
}
```

Ejecutar:
```bash
php artisan migrate
```

#### 2. Crear Modelo

```bash
php artisan make:model Proyecto
```

Editar `app/Models/Proyecto.php`:
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';
    protected $primaryKey = 'id_proyecto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'responsable_id',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id', 'id_usuario');
    }
}
```

#### 3. Crear Controlador

```bash
php artisan make:controller Api/ProyectoController --api
```

Editar `app/Http/Controllers/Api/ProyectoController.php`:
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function index()
    {
        $proyectos = Proyecto::with('responsable')->get();
        return response()->json($proyectos);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
        ]);

        $proyecto = Proyecto::create($validated);
        return response()->json($proyecto->load('responsable'), 201);
    }

    public function show($id)
    {
        $proyecto = Proyecto::with('responsable')->findOrFail($id);
        return response()->json($proyecto);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'responsable_id' => 'nullable|exists:usuarios,id_usuario',
        ]);

        $proyecto = Proyecto::findOrFail($id);
        $proyecto->update($validated);
        return response()->json($proyecto->load('responsable'));
    }

    public function destroy($id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();
        return response()->json(['message' => 'Proyecto eliminado correctamente']);
    }
}
```

#### 4. Registrar Rutas

Editar `routes/api.php`:
```php
use App\Http\Controllers\Api\ProyectoController;

Route::middleware('auth:sanctum')->group(function () {
    // ... rutas existentes ...
    Route::apiResource('proyectos', ProyectoController::class);
});
```

#### 5. Probar

```bash
# Listar
curl -X GET http://localhost:8000/api/proyectos \
  -H "Authorization: Bearer {token}"

# Crear
curl -X POST http://localhost:8000/api/proyectos \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "nombre": "Proyecto Alpha",
    "descripcion": "Descripción del proyecto",
    "responsable_id": 1
  }'
```

### Usar Middleware de Permisos

```php
// En routes/api.php
Route::middleware(['auth:sanctum', 'role:Administrador'])->group(function () {
    Route::apiResource('proyectos', ProyectoController::class);
});

// O solo ciertos métodos
Route::middleware('auth:sanctum')->group(function () {
    Route::get('proyectos', [ProyectoController::class, 'index']);
    Route::get('proyectos/{id}', [ProyectoController::class, 'show']);

    Route::middleware('permission:crear_proyectos')->group(function () {
        Route::post('proyectos', [ProyectoController::class, 'store']);
    });
});
```

## Debugging y Errores Comunes

### 1. Error: "Unauthenticated"

**Problema:** Token inválido o ausente.

**Solución:**
- Verificar que el header tenga: `Authorization: Bearer {token}`
- Verificar que el token no haya expirado (24h)
- Hacer login nuevamente para obtener nuevo token

### 2. Error: "404 Not Found"

**Problema:** Ruta no existe.

**Solución:**
- Verificar que la ruta esté en `routes/api.php`
- Recordar que todas las rutas de API tienen prefijo `/api`
- Listar rutas: `php artisan route:list`

### 3. Error: "Validation Error"

**Problema:** Datos enviados no cumplen validación.

**Solución:**
- Leer el mensaje de error, indica qué campo falla
- Verificar tipos de datos
- Verificar campos requeridos

### 4. Error: "SQLSTATE[23000]: Integrity constraint violation"

**Problema:** Violación de restricción de BD (FK, unique, etc).

**Solución:**
- Verificar que los IDs de relaciones existan
- Verificar unicidad de emails
- Verificar que no se elimine un registro con dependencias

### 5. Logging

Ver logs en tiempo real:
```bash
tail -f storage/logs/laravel.log
```

O usar Laravel Pail:
```bash
php artisan pail
```

### 6. Limpiar Caché

Si los cambios no se reflejan:
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

## Testing

### Crear Test

```bash
# Feature test (prueba endpoints)
php artisan make:test ProyectoTest

# Unit test (prueba clases/métodos)
php artisan make:test ProyectoModelTest --unit
```

### Ejemplo de Feature Test

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProyectoTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_proyectos()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->get('/api/proyectos');

        $response->assertStatus(200);
    }

    public function test_can_create_proyecto()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->post('/api/proyectos', [
                             'nombre' => 'Test Proyecto',
                             'descripcion' => 'Descripción de prueba',
                         ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('proyectos', [
            'nombre' => 'Test Proyecto',
        ]);
    }
}
```

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test tests/Feature/ProyectoTest.php

# Con coverage
php artisan test --coverage
```

## Buenas Prácticas

### 1. Validación

✅ Siempre validar datos de entrada
✅ Usar `sometimes` para campos opcionales en updates
✅ Validar relaciones con `exists:tabla,columna`

```php
$request->validate([
    'email' => 'required|email|unique:usuarios,correo,' . $id . ',id_usuario',
    'roles' => 'array',
    'roles.*' => 'exists:roles,id_rol',
]);
```

### 2. Respuestas HTTP

✅ 200 OK - Operación exitosa
✅ 201 Created - Recurso creado
✅ 401 Unauthorized - Sin autenticación
✅ 403 Forbidden - Sin permisos
✅ 404 Not Found - Recurso no existe
✅ 422 Unprocessable Entity - Error de validación

### 3. Eager Loading

❌ Malo (N+1 queries):
```php
$comites = Comite::all();
foreach ($comites as $comite) {
    echo $comite->responsable->nombre; // Query por cada comité
}
```

✅ Bueno:
```php
$comites = Comite::with('responsable')->get(); // 2 queries totales
```

### 4. Seguridad

✅ Nunca confiar en datos del cliente
✅ Validar SIEMPRE
✅ Usar fillable o guarded en modelos
✅ Hashear contraseñas con `Hash::make()`
✅ Verificar permisos antes de operaciones sensibles

### 5. Nombres Descriptivos

✅ Variables: `$usuarioActivo` no `$u`
✅ Métodos: `obtenerUsuariosActivos()` no `get()`
✅ Rutas: `/api/indicador-valores` no `/api/iv`

## Comandos Útiles

```bash
# Generar clases
php artisan make:model Modelo
php artisan make:controller Api/ModeloController --api
php artisan make:migration create_modelos_table
php artisan make:seeder ModeloSeeder
php artisan make:middleware CheckModelo
php artisan make:request ModeloRequest

# Base de datos
php artisan migrate
php artisan migrate:fresh  # Borra todo y recrea
php artisan migrate:rollback
php artisan db:seed

# Ver información
php artisan route:list
php artisan model:show User
php artisan tinker  # REPL de Laravel

# Optimización
php artisan config:cache
php artisan route:cache
php artisan optimize
php artisan optimize:clear

# Logs
php artisan pail
tail -f storage/logs/laravel.log
```

## Recursos Adicionales

- [Documentación Laravel 11](https://laravel.com/docs/11.x)
- [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)
- [Eloquent ORM](https://laravel.com/docs/11.x/eloquent)
- [Validation Rules](https://laravel.com/docs/11.x/validation#available-validation-rules)
