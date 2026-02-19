<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class StorageCrearDirectorios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:crear-directorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea todos los directorios de almacenamiento';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Lista de carpetas que quieres asegurar
        $folders = [
            'albums/cover',
            'articles/cover',
            'media',
            'users/profiles/photo',
            'users/profiles/cover',
        ];

        foreach ($folders as $folder) {
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder, 0755, true); // el true permite crear carpetas recursivamente
                $this->info("Carpeta creada: {$folder}");
            } else {
                $this->info("Carpeta ya existe: {$folder}");
            }
        }

        $this->info('Todos los directorios están listos.');
        return 0;
    }
}
