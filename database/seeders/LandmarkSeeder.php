<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;

class LandmarkSeeder extends Seeder
{
    public function run(): void
    {
        if (Landmark::query()->count() > 20) {
            $this->command->info('Landmarks table already populated, skipping.');
            return;
        }

        $jsonPath = __DIR__ . '/data/landmarks.json';

        if (!file_exists($jsonPath)) {
            $this->command->error('landmarks.json not found at ' . $jsonPath);
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        if (empty($data)) {
            $this->command->error('landmarks.json is empty or invalid');
            return;
        }

        $bar = $this->command->getOutput()->createProgressBar(count($data));
        $bar->start();

        foreach ($data as $row) {
            Landmark::query()->create($row);
            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine();
        $this->command->info('Seeded ' . count($data) . ' landmarks.');
    }
}
