<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\User;  // Replace with your user model path

class InsertInitialUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $userId1 = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('useruser'),
            'usertype' => 'user',
        ]);
        
        $user2 = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminadmin'),
        ]);
        
        $user2->usertype = 'admin'; // Explicitly set usertype for admin
        $user2->save(); // Save the updated user model
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // You can leave this function empty as you don't want to reverse this migration
    }
}
