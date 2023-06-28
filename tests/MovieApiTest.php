<?php

namespace App\Tests;

use App\Repository\Database;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieApiTest extends WebTestCase
{
    /**
     * La méthode setUp sera déclenchée avant l'exécution de chacun des tests de la classe actuelle.
     * Ici on lui dit de remettre à zéro la bdd en se basant sur le contenu du database.sql
     */
    public function setUp():void {
        Database::getConnection()->query(file_get_contents(__DIR__.'/../database.sql'));
    }

    public function testGetAllSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/movie');
        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();

        $this->assertNotEmpty($json);
        $this->assertIsString($json[0]['title']);
        $this->assertIsInt($json[0]['id']);
       
    }


    public function testGetOneSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/movie/1');
        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();

        $this->assertIsString($json['title']);
        $this->assertIsString($json['resume']);
        $this->assertIsInt($json['duration']);
        $this->assertIsString($json['released']);
        $this->assertIsInt($json['id']);
       
    }
    /**
     * En général c'est bien de faire différents tests dédiés aux différents scénarios prévu. Ici on vérifie
     * qu'on a bien l'erreur attendue quand on requête une ressource inexistante
     */
    public function testGetOneNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/movie/100');
        
        $this->assertResponseStatusCodeSame(404);

    }
    
    public function testPostSuccess(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/movie', content: json_encode([
            'title' => 'From Test',
            'resume' => 'Resume Test',
            'released' => '2020-10-01',
            'duration' => 100
        ]));
        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();

        $this->assertIsInt($json['id']);
    }
    public function testPostValidationFailed(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/movie', content: json_encode([
            'title' => '',
            'resume' => 'Resume Test',
            'released' => '2020-10-01',
            'duration' => -100
        ]));
        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseStatusCodeSame(400);

        $this->assertStringContainsString('title', $json['errors']['detail']);
        $this->assertStringContainsString('duration', $json['errors']['detail']);

    }


    public function testPatchSuccess(): void
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/movie/2', content: json_encode([
            'title' => 'From Test'
        ]));
        $json = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();

        //On vérifie que le champ à modifier l'a bien été
        $this->assertEquals($json['title'], 'From Test');
        //Et pourquoi pas que les autres champs sont restés inchangés
        $this->assertEquals($json['resume'], 'a mafia movie sequel');
        $this->assertEquals($json['released'], '1974-12-20T00:00:00+00:00');
    }
    
    public function testPatchNotFound(): void
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/movie/100');
        
        $this->assertResponseStatusCodeSame(404);

    }
    
    public function testDeleteSuccess(): void
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/movie/1');
        
        $this->assertResponseIsSuccessful();

    }
}
