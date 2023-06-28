<?php

namespace App\Repository;

/**
 * Petite classe qui sert juste à externaliser la connexion à la base de données pour pouvoir la réutiliser 
 * entre les différentes méthodes et repositories
 */
class Database {
    /**
     * Méthode qui crée une connexion à la base de données et la renvoie.
     * La méthode est définie static, ce qui signifie qu'elle pourra être appelé sur la classe Database directement
     * plutôt que sur instance de Database. L'usage du static est à limité autant que possible pour des cas particuliers comme ici
     */
    public static function getConnection() {
        return new \PDO("mysql:host=localhost;dbname=symfony_rest", "root", "1234");
    }
}