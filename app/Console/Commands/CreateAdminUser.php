<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {--email=aslly@lectorix.com : Email del administrador} {--password=aslly123 : Contraseña del administrador}{--num_documento=1010116656 : Numero de documento del administrador} {--name=Aslly : Nombre del administrador} {--apellido=Zuñiga : Apellido del administrado} {--edad=24 : Edad del administrador}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un usuario administrador inicial para la API';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');
        $apellido = $this->option('apellido');
        $edad = $this->option('edad');
        $num_documento = $this->option('num_documento');

        // Verificar si ya existe un usuario con ese email
        if (Usuario::where('correo', $email)->exists()) {
            $this->error("Ya existe un usuario con el email: {$email}");
            return 1;
        }

        try {
            $admin = Usuario::create([
                'num_documento' => $num_documento,
                'nombre' => $name,
                'apellido' => $apellido,
                'edad' => $edad,
                'correo' => $email,
                'contrasena_hash' => Hash::make($password),
                'rol_id' => 1, // Administrador
                'institucion_id' => 1, // Institución por defecto
                'fecha_creacion' => Carbon::now(),
                'estado' => true,
            ]);

            $this->info("✅ Usuario administrador creado exitosamente!");
            $this->info("📧 Email: {$email}");
            $this->info("🔑 Contraseña: {$password}");
            $this->info("👤 Nombre: {$name}");
            $this->info("🆔 ID: {$admin->id}");
            $this->info("🔐 Rol: Administrador (ID: 1)");

            $this->newLine();
            $this->info("🚀 Ahora puedes probar la API con:");
            $this->info("   curl -X POST http://localhost:8000/api/auth/login \\");
            $this->info("     -H \"Content-Type: application/json\" \\");
            $this->info("     -d '{\"correo\":\"{$email}\",\"contrasena\":\"{$password}\"}'");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error al crear el usuario administrador: " . $e->getMessage());
            return 1;
        }
    }
} 