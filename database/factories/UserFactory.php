<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Seller;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('123456'), // password

        'remember_token' => Str::random(10),
        
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::GenerarVerificacionToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
        

    ];
});


$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),    

    ];
});

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 50),
        'status' => $faker->randomElement([Product::PRODUCT_DISPONIBLE, Product::PRODUCT_NO_DISPONIBLE]),
        'image' => $faker->randomElement(['producto_1','producto_2','producto_3','producto_4','producto_5','producto_6','producto_7','producto_8','producto_9']),
        //'seller_id' => User::inRandomOrder()->first()->id,
        'seller_id' => User::all()->random()->id,

    ];
});


$factory->define(Transaction::class, function (Faker $faker) {

	$vendedor = Seller::has('products')->get()->random();
	$comprador = User::all()->except($vendedor->id)->random();
    return [
    	'quantity' => $faker->numberBetween(1, 5),
    	'buyer_id' => $comprador->id,
        'product_id' => $vendedor->products->random()->id,    

    ];
});
