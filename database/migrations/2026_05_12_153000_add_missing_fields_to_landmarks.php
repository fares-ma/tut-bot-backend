<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->string('city')->nullable()->after('region');
            $table->string('area')->nullable()->after('city');
            $table->string('raw_category')->nullable()->after('category');
            $table->string('fallback_image')->nullable()->after('image');
            $table->string('panorama_url')->nullable()->after('fallback_image');
            $table->string('opening_hours')->nullable()->after('description');
            $table->string('closing_hours')->nullable()->after('opening_hours');
            $table->unsignedInteger('avg_visit_duration')->nullable()->after('closing_hours');
            $table->boolean('accessibility_wheelchair')->default(false)->after('avg_visit_duration');
            $table->boolean('is_outdoor')->default(false)->after('accessibility_wheelchair');
            $table->string('best_day_visit')->nullable()->after('is_outdoor');
            $table->string('best_season')->nullable()->after('best_day_visit');
            $table->string('cost_level')->nullable()->after('best_season');
            $table->unsignedInteger('entrance_fee_egyptian')->default(0)->after('cost_level');
            $table->unsignedInteger('entrance_fee_egyptian_student')->default(0)->after('entrance_fee_egyptian');
            $table->unsignedInteger('entrance_fee_foreigner')->default(0)->after('entrance_fee_egyptian_student');
            $table->unsignedInteger('entrance_fee_foreigner_student')->default(0)->after('entrance_fee_foreigner');
            $table->unsignedInteger('reviews')->default(0)->after('rating');
        });
    }

    public function down(): void
    {
        Schema::table('landmarks', function (Blueprint $table) {
            $table->dropColumn([
                'city', 'area', 'raw_category', 'fallback_image', 'panorama_url',
                'opening_hours', 'closing_hours', 'avg_visit_duration',
                'accessibility_wheelchair', 'is_outdoor', 'best_day_visit',
                'best_season', 'cost_level',
                'entrance_fee_egyptian', 'entrance_fee_egyptian_student',
                'entrance_fee_foreigner', 'entrance_fee_foreigner_student',
                'reviews',
            ]);
        });
    }
};
