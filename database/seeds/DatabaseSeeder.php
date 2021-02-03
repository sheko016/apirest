<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

    	//quitar validaciones de llaves foeranas
    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        //truncaremos la tablas para eliminar datos existentes

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();

        DB::table('category_product')->truncate();

        //Ejecutamos factoris

        $CantidadUsuarios = 200;
        $CantidadCategorias = 70;
        $CantidadProductos = 2500;
        $CantidadTransaciones = 1650;


        factory(User::class, $CantidadUsuarios)->create();

        factory(Category::class, $CantidadCategorias)->create();

        factory(Product::class, $CantidadProductos)->create()->each(

        	    function ($producto)
        	{
        		$categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
        		$producto->categories()->attach($categorias);
        	}
        );

        factory(Transaction::class, $CantidadTransaciones)->create();
    }
}
