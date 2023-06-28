<?php

namespace App\Repository;

use App\Entity\Genre;
use DateTime;

class GenreRepository
{

    /**
     * Méthode qui va faire une requête pour récupérer tous les genres de la base de données puis qui va boucler
     * sur les résultat de la requête pour transformer chaque ligne de résultat en instance de la classe Genre
     * @return Genre[] La liste des genres contenus dans la base de données;
     */
    public function findAll(): array
    {
        $list = [];
        $connection = Database::getConnection();

        $query = $connection->prepare("SELECT * FROM genre");

        $query->execute();

        foreach ($query->fetchAll() as $line) {
            $list[] = new Genre($line["label"], $line["id"]);
        }

        return $list;
    }
    /**
     * @return Genre[]
     */
    public function findByMovie(int $id): array
    {
        $list = [];
        $connection = Database::getConnection();

        $query = $connection->prepare("SELECT * FROM genre 
        LEFT JOIN genre_movie ON genre.id=genre_movie.id_genre 
        WHERE genre_movie.id_movie=:id");
        $query->bindValue(':id', $id);
        $query->execute();

        foreach ($query->fetchAll() as $line) {
            $list[] = new Genre($line["label"], $line["id"]);
        }

        return $list;
    }

    /**
     * Méthode permettant de récupérer un genre spécifique en se basant sur son id
     * Si aucun genre n'existe pour cet id dans la base de données, on renvoie null
     * 
     * @param $id l'id du genre que l'on souhaite récupérer
     */
    public function findById(int $id):?Genre {

        $connection = Database::getConnection();

        $query = $connection->prepare("SELECT * FROM genre WHERE id=:id ");
        $query->bindValue(":id", $id);
        $query->execute();

        foreach ($query->fetchAll() as $line) {
            return new Genre($line["label"], $line["id"]);
        }
        return null;

    }

    /**
     * Méthode qui va prendre une instance de Genre en argument et va la transformer en requête INSERT INTO pour 
     * la faire persister en base de données
     * @param $genre Le genre que l'on souhaite faire persister (qui n'aura donc pas d'id au début de la méthode, car pas encore dans la bdd)
     */
    public function persist(Genre $genre) {
        $connection = Database::getConnection();

        $query = $connection->prepare("INSERT INTO genre (label) VALUES (:label)");
        $query->bindValue(':label', $genre->getLabel());
        

        $query->execute();

        //On assigne l'id auto incrémenté à l'instance de genre afin que l'objet soit complet après le persist
        $genre->setId($connection->lastInsertId());
    }

    /**
     * Méthode qui permet de supprimer un genre de la base de données en se basant sur son id
     * 
     * @param $id l'id du genre à supprimer
     */
    public function delete(int $id) {

        $connection = Database::getConnection();

        $query = $connection->prepare("DELETE FROM genre WHERE id=:id");
        $query->bindValue(":id", $id);
        $query->execute();
    }

    /**
     * Méthode pour mettre un jour un genre existant en base de données
     * 
     * @param Genre $genre Le genre à mettre à jour. Il doit avoir un id correspondant à une ligne de la bdd
     */
    public function update(Genre $genre) {
        
        $connection = Database::getConnection();

        $query = $connection->prepare("UPDATE genre SET label=:label WHERE id=:id");
        $query->bindValue(':label', $genre->getLabel());
        $query->bindValue(":id", $genre->getId());

        $query->execute();
    }
}