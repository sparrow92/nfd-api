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

      $this->info('Generowanie klucza API...');
      $this->installApiKey();
  
      $this->info('Inicjalizacja zakończona pomyślnie!');
      return Command::SUCCESS;
    }

    protected function installApiKey()
    {
      $apiKey = bin2hex(random_bytes(32));
      $envFile = base_path('.env');

      if (file_exists($envFile)) {
        $envContent = file_get_contents($envFile);
        $pattern = '/^API_KEY=.*$/m';

        if (preg_match($pattern, $envContent)) {
          $envContent = preg_replace($pattern, "API_KEY={$apiKey}", $envContent);
        } else {
          $envContent .= "\nAPI_KEY={$apiKey}";
        }

        file_put_contents($envFile, $envContent);

        $this->info('Klucz API został zapisany w pliku .env.');
      } else {
        $this->error('Nie znaleziono pliku .env!');
      }

      $this->info("Wygenerowany klucz API: {$apiKey}");
    }
}
