<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {

        // 1. créér un faux client (navigateur) , de pointer vers une URL
        // 2. Remplir les champs de mon formulaire d'inscription
        // 3. Est ce que tu peux regarder si dans ma page j'ai le message d'alerte suivant : Votre compte est bien créé , veuillez vous connecter.

       
       //1.
        $client = static::createClient();
        $client->request('GET', '/inscription');

       //2.(firstname, lastname, email, password, confirmation du password )
       $client->submitForm('Valider', [
        'register_user[email]' => 'julie@exemple.fr',
        'register_user[plainPassword][first]' => '12345678',
        'register_user[plainPassword][second]' => '12345678',
        'register_user[firstname]' => 'julie',
        'register_user[lastname]' => 'Doe'
        ]) ;

        //3. 

        //teste et suit les redirections
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();


        $this->assertSelectorExists('div:contains("Votre compte est bien créé , veuillez vous connecter.")');

    }
}
