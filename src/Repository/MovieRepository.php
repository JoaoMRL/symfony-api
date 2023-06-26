<?php

namespace App\Repository;
use App\Entity\Movie;
use DateTime;

class MovieRepository{

    /**
     * Récupère tous les films
     * @return Movie[]
     */
    public function findAll():array{
        $list = [];

        $connect = Database::getConnection();
        $req = "SELECT * FROM movie";

        $query =$connect->prepare($req);
        $query->execute();

        foreach ($query->fetchAll() as $film) {
            $list[]=new Movie($film['title'],$film['resume'], new DateTime($film['released']),$film['duree'],$film['id']);
        }
        return $list;
    }

     /**
     * Récupère tous les films
     * @return Movie[]
     */
    public function findById(int $id):?Movie{
        $connect = Database::getConnection();

        $req = "SELECT * FROM movie WHERE id = :id";
        $query =$connect->prepare($req);
        $query->bindValue(":id",$id);
        $query->execute();

        foreach ($query->fetchAll() as $film) {
            return new Movie($film['title'],$film['resume'], new DateTime($film['released']),$film['duree'],$film['id']);
        }
        return null;
    }

    /**
     * Insère un new Film dans la BDD
     * @return void
     */
    public function persist(Movie $film):void{
        $connect = Database::getConnection();

        $req = "INSERT INTO movie (title,resume,released,duree) VALUES (:tit,:res,:rel,:dur)";
        $query =$connect->prepare($req);
        $query->bindValue(":tit",$film->getTitle());
        $query->bindValue(":res",$film->getResume());
        $query->bindValue(":rel",$film->getReleased()->format('Y-m-d'));
        $query->bindValue(":dur",$film->getDuree());
        $query->execute();

        $film->setId($connect->lastInsertId());
    }

    /**
     * Met à jour un Film dans la BDD
     * @return void
     */
    public function update(Movie $film):void{
        $connect = Database::getConnection();

        $req = "UPDATE movie SET title=:tit,resume=:res,released=:rel,duree=:dur WHERE id = :id";
        $query =$connect->prepare($req);
        $query->bindValue(":tit",$film->getTitle());
        $query->bindValue(":res",$film->getResume());
        $query->bindValue(":rel",$film->getReleased()->format('Y-m-d'));
        $query->bindValue(":dur",$film->getDuree());
        $query->bindValue(":id",$film->getId());
        $query->execute();
    }

    /**
     * Supprime un Film dans la BDD
     * @return void
     */
    public function delete(int $id):void{
        $connect = Database::getConnection();

        $req = "DELETE FROM movie WHERE id = :id";
        $query =$connect->prepare($req);
        $query->bindValue(":id",$id);
        $query->execute();
    }
}