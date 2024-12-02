<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      if (!app()->environment('local', 'development')) {
        $this->error('To polecenie może być używane tylko w środowisku deweloperskim!');
        return Command::FAILURE;
      }
      
      $this->info('Czyszczenie bazy danych...');
      $this->call('db:wipe');
  
      $this->info('Uruchamianie migracji...');
      $this->call('migrate');

      $this->info('Uruchamianie seederów...');
      $this->call('db:seed', ['--class' => 'DatabaseSeeder']);

      $this->info('Generowanie klucza aplikacji...');
      $this->call('key:generate');
  
      $this->info('Inicjalizacja zakończona pomyślnie!');
      return Command::SUCCESS;
    }
}
