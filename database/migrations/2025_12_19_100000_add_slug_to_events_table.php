<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Event;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add slug column if it doesn't exist
        if (!Schema::hasColumn('events', 'slug')) {
            Schema::table('events', function (Blueprint $table) {
                $table->string('slug', 100)->nullable()->after('name');
            });
        }

        // Generate slugs for existing events
        Event::withTrashed()->whereNull('slug')->orWhere('slug', '')->each(function ($event) {
            $slug = Str::slug($event->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Event::withTrashed()->where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $event->slug = $slug;
            $event->saveQuietly();
        });

        // Add unique index if not exists (check by trying to query)
        try {
            Schema::table('events', function (Blueprint $table) {
                $table->unique('slug', 'events_slug_unique');
            });
        } catch (\Exception $e) {
            // Index already exists, ignore
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('events', 'slug')) {
            Schema::table('events', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
