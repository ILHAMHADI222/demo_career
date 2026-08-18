<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('candidate_profiles', function (Blueprint $table): void {
            $table->string('rt_rw')->nullable()->after('address');
            $table->string('village')->nullable()->after('rt_rw');
            $table->string('district')->nullable()->after('village');
            $table->string('driving_license')->nullable()->after('portfolio_url');
        });

        Schema::table('candidate_educations', function (Blueprint $table): void {
            $table->string('ipk')->nullable()->after('major');
            $table->text('certification')->nullable()->after('ipk');
        });

        Schema::table('candidate_experiences', function (Blueprint $table): void {
            $table->string('work_years')->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_experiences', function (Blueprint $table): void {
            $table->dropColumn('work_years');
        });

        Schema::table('candidate_educations', function (Blueprint $table): void {
            $table->dropColumn(['ipk', 'certification']);
        });

        Schema::table('candidate_profiles', function (Blueprint $table): void {
            $table->dropColumn(['rt_rw', 'village', 'district', 'driving_license']);
        });
    }
};
