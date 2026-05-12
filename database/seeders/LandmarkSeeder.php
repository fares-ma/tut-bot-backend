<?php

namespace Database\Seeders;

use App\Models\Landmark;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandmarkSeeder extends Seeder
{
    public function run(): void
    {
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

        $count = Landmark::query()->count();

        if ($count === count($data)) {
            $this->command->info('Landmarks already seeded (' . $count . ' records), skipping.');
            return;
        }

        $this->command->info('Clearing ' . $count . ' old landmarks and seeding ' . count($data) . ' new ones...');

        DB::statement('PRAGMA foreign_keys = OFF');

        Landmark::query()->truncate();

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
