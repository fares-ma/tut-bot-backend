<?php

use App\Models\Landmark;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (Landmark::query()->count() > 20) {
            return;
        }

        $jsonPath = __DIR__ . '/../seeders/data/landmarks.json';

        if (!file_exists($jsonPath)) {
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        if (empty($data)) {
            return;
        }

        Landmark::query()->truncate();

        foreach ($data as $row) {
            Landmark::query()->create($row);
        }
    }

    public function down(): void
    {
    }
};
