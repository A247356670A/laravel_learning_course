<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     * @var bool
     */

    // You can define properties, methods, and other class members here

    // Example method where you can put the code
    public function exampleMethod()
    {
        $user = User::create([
            'first_name' => 'Taylor',
            'last_name' => 'Otwell',
            'title' => 'Developer',
        ]);
         
        $user->title = 'Painter';
         
        $user->isDirty(); // true
        $user->isDirty('title'); // true
        $user->isDirty('first_name'); // false
        $user->isDirty(['first_name', 'title']); // true
         
        $user->isClean(); // false
        $user->isClean('title'); // false
        $user->isClean('first_name'); // true
        $user->isClean(['first_name', 'title']); // false
         
        $user->save();
         
        $user->isDirty(); // false
        $user->isClean(); // true
        $user->wasChanged(); // true
$user->wasChanged('title'); // true
$user->wasChanged(['title', 'slug']); // true
$user->wasChanged('first_name'); // false
$user->wasChanged(['first_name', 'title']); // true
    }
}
