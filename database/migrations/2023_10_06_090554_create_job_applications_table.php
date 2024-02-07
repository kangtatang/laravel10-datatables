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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_title_id');
            $table->unsignedBigInteger('applicant_id');
            $table->date('application_date');
            $table->string('status');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('job_title_id')
                ->references('id')
                ->on('job_titles')
                ->onDelete('cascade'); // Jika judul pekerjaan terhapus, hapus juga aplikasi terkait

            $table->foreign('applicant_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade'); // Jika pelamar terhapus, hapus juga aplikasi terkait
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
