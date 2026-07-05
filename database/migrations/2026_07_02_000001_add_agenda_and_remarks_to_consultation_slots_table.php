<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_slots', function (Blueprint $table) {
            $table->string('agenda')->nullable()->after('venue_or_link');
            $table->text('remarks')->nullable()->after('agenda');
        });
    }

    public function down(): void
    {
        Schema::table('consultation_slots', function (Blueprint $table) {
            $table->dropColumn(['agenda', 'remarks']);
        });
    }
};