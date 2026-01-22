<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\UserService;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "🚀 Iniciando verificación (Strict Type Architecture & PostgreSQL)...\n";

// 1. Database Setup
try {
    // Verify connection to Postgres (as per environment configuration)
    DB::connection()->getPdo();
    echo "✅ Conexión a Base de Datos (PostgreSQL) exitosa.\n";
} catch (\Throwable $e) {
    echo "❌ Error de conexión a Base de Datos: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Happy Path
echo "\n🔍 1. Prueba de Creación de Usuario (Happy Path)...\n";
try {
    $user = User::factory()->create([
        'nombre' => 'Deivid Test',
        'correo' => 'test@backend.com',
        'password' => bcrypt('password123')
    ]);
    echo "✅ Usuario creado con ID: " . $user->id_usuario . "\n";
} catch (\Throwable $e) {
    echo "❌ Error creando usuario: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Strict Type Test
echo "\n🔍 2. Prueba de Tipado Estricto (The Jules Test)...\n";
try {
    $service = app(UserService::class);
    // @phpstan-ignore-next-line
    $service->getUserById("5"); // Passing string, expecting int
    echo "❌ Error: Se esperaba TypeError pero no ocurrió. El modo estricto podría no estar funcionando.\n";
} catch (\TypeError $e) {
    echo "✅ TypeError capturado correctamente: " . $e->getMessage() . "\n";
} catch (\Throwable $e) {
    echo "❌ Error inesperado (no TypeError): " . get_class($e) . " - " . $e->getMessage() . "\n";
}

// 4. Relationships
echo "\n🔍 3. Verificando Relaciones...\n";
try {
    $roles = $user->roles;
    echo "✅ Relación roles accedida. Cantidad: " . $roles->count() . "\n";
} catch (\Throwable $e) {
    echo "❌ Error accediendo relaciones: " . $e->getMessage() . "\n";
}

echo "\n🏁 Verificación completada.\n";
