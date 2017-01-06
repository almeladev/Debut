<?php

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

use app\Models\User;
use app\Models\Post;
use Faker\Factory as Faker;

class Version20161221134035 extends AbstractMigration
{
    /**
     * Ejecuta las migraciones
     * 
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        //
    }

    /**
     * Revierte las migraciones
     * 
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        //
    }
    
    /**
     * Añade datos de prueba tras hacer las migraciones
     * 
     * @param Schema $schema
     */
    public function postUp(Schema $schema) 
    {
        $faker = Faker::create();
        
        // Administrador
        User::create([
            'email'    => 'admin@debut.app',
            'name'     => 'admin',
            'password' => encrypt('secret')
        ]);
        
        // Posts ...
        $num_posts = 50;
        
        for($i = 0; $i <= $num_posts; $i++) {
            Post::create([
                'title'   => 'title ' . $i,
                'content' => $faker->text(500),
                'user_id' => NULL
            ]);
        }
        
    }
}
