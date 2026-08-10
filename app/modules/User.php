<?php
defined('RUN') or http_response_code(404) and die();

class User
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $connection = strtolower(getenv('DB_CONNECTION') ?: 'sqlite');

            if ($connection === 'sqlite') {
                self::$pdo = self::connectSqlite();
            } else {
                self::$pdo = self::connectMysql();
            }

            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$pdo;
    }

    private static function connectSqlite(): PDO
    {
        $database = getenv('DB_DATABASE') ?: (__DIR__ . '/../../database/fbr.sqlite');

        $directory = dirname($database);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdo = new PDO('sqlite:' . $database);

        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS users (
                id TEXT PRIMARY KEY,
                name TEXT NOT NULL DEFAULT \'\',
                email TEXT NOT NULL DEFAULT \'\',
                phone TEXT NOT NULL DEFAULT \'\'
            )'
        );

        return $pdo;
    }

    private static function connectMysql(): PDO
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $database = getenv('DB_DATABASE') ?: 'fbr';
        $username = getenv('DB_USERNAME') ?: 'root';
        $password = getenv('DB_PASSWORD') ?: '';

        return new PDO(
            sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $database),
            $username,
            $password
        );
    }

    public function all(): array
    {
        $stmt = self::pdo()->query('SELECT * FROM users ORDER BY id');

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $id): array
    {
        $stmt = self::pdo()->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            throw new PageNotFoundException(sprintf('User not found: %s', $id));
        }

        return $user;
    }

    public function store(array $data): bool
    {
        $stmt = self::pdo()->prepare('INSERT INTO users (id, name, email, phone) VALUES (:id, :name, :email, :phone)');

        return $stmt->execute([
            ':id' => $data['id'] ?? uniqid(),
            ':name' => $data['name'] ?? '',
            ':email' => $data['email'] ?? '',
            ':phone' => $data['phone'] ?? '',
        ]);
    }

    public function update(string $id, array $data): bool
    {
        $stmt = self::pdo()->prepare('UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id');

        return $stmt->execute([
            ':id' => $id,
            ':name' => $data['name'] ?? '',
            ':email' => $data['email'] ?? '',
            ':phone' => $data['phone'] ?? '',
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = self::pdo()->prepare('DELETE FROM users WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }
}
