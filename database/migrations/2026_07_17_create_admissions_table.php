<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            
            // Student Information
            $table->string('student_name');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('religion')->nullable();
            $table->string('nationality');
            $table->string('former_school')->nullable();
            $table->string('shehia');
            $table->string('ward');
            $table->text('address')->nullable();
            
            // Parent/Guardian Information
            $table->string('parent_full_name');
            $table->string('parent_address');
            $table->string('parent_mobile');
            $table->string('parent_relationship');
            $table->string('parent_email')->nullable();
            
            // Sponsor Information
            $table->string('sponsor_full_name')->nullable();
            $table->string('sponsor_address')->nullable();
            $table->string('sponsor_mobile')->nullable();
            $table->string('sponsor_occupation')->nullable();
            
            // Academic Information
            $table->string('academic_level');
            $table->string('class_applying_for');
            $table->string('academic_year');
            $table->string('previous_school')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('admissions');
    }
};
