<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MigrateJobImages extends Command
{
    protected $signature = 'trofi:migrate-job-images';
    protected $description = 'Convierte job_images de strings a objetos con id y url';

    public function handle()
    {
        $users = User::whereNotNull('job_images')->get();
        $this->info("Migrando job_images para {$users->count()} usuarios...");

        foreach ($users as $user) {
            $images = $user->job_images;

            if (is_string($images)) {
                $images = json_decode($images, true);
            }

            if (!is_array($images)) {
                $this->warn("Usuario {$user->id} tiene job_images no válidas.");
                continue;
            }

            // Si ya está migrado, lo salteamos
            if (isset($images[0]['id']) && isset($images[0]['url'])) {
                $this->line("Usuario {$user->id} ya tiene formato correcto. Saltando...");
                continue;
            }

            // Generar objetos con ID
            $newImages = [];
            $id = 1;

            foreach ($images as $img) {
                if (is_string($img)) {
                    $newImages[] = [
                        'id' => $id++,
                        'url' => $img
                    ];
                }
            }

            $user->job_images = $newImages;
            $user->save();

            $this->info("Usuario {$user->id} migrado con éxito.");
        }

        $this->info('✔ Migración finalizada.');
    }
}
