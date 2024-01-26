<?php
defined('RUN') or http_response_code(404) and die();

class User
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        $user = 'root';
        $pass = 'root';
        $port = 3306;
        $host = '127.0.0.1';
        $dbname = 'fbr';

        if (self::$pdo === null) {
            self::$pdo = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname, $user, $pass);
        }

        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$pdo;
    }

    public function all(): array
    {
        $stmt = self::pdo()->prepare('SELECT * FROM users');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $id): array
    {
        $stmt = self::pdo()->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store(array $data): bool
    {
        $stmt = self::pdo()->prepare('INSERT INTO users (id, name, email, phone) VALUES (:id, :name, :email, :phone)');
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);

        return $stmt->execute();
    }

    public function delete(string $id): bool
    {
        $stmt = self::pdo()->prepare('DELETE FROM users WHERE id = :id');
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}
