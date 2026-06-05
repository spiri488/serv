<?php

declare(strict_types=1);

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $dataDir = dirname(__DIR__, 2) . '/data';

            if (!is_dir($dataDir)) {
                mkdir($dataDir, 0777, true);
            }

            $dbPath = $dataDir . '/blog.sqlite';

            self::$connection = new PDO('sqlite:' . $dbPath);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            self::createTables();
            self::insertDemoData();
        }

        return self::$connection;
    }

    private static function createTables(): void
    {
        $db = self::$connection;

        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nickname VARCHAR(128) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                is_confirmed INTEGER NOT NULL DEFAULT 0,
                role VARCHAR(20) NOT NULL,
                password_hash VARCHAR(255) NOT NULL,
                auth_token VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS articles (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                author_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                text TEXT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    private static function insertDemoData(): void
    {
        $db = self::$connection;

        $usersCount = (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        if ($usersCount === 0) {
            $db->exec("
                INSERT INTO users 
                    (nickname, email, is_confirmed, role, password_hash, auth_token, created_at)
                VALUES
                    ('admin', 'admin@gmail.com', 1, 'admin', 'hash1', 'token1', CURRENT_TIMESTAMP),
                    ('user', 'user@gmail.com', 1, 'user', 'hash2', 'token2', CURRENT_TIMESTAMP)
            ");
        }

        $articlesCount = (int) $db->query("SELECT COUNT(*) FROM articles")->fetchColumn();

        if ($articlesCount === 0) {
            $db->exec("
                INSERT INTO articles
                    (author_id, name, text, created_at)
                VALUES
                    (1, 'Статья №1', 'Текст первой статьи. Далеко-далеко, за словесными горами в стране гласных и согласных живут рыбные тексты. Злых, над себя решила использовало свой правилами букв пояс живет дал одна, текста имени?', CURRENT_TIMESTAMP),
                    (1, 'Статья №2', 'Текст второй статьи. Далеко-далеко за словесными горами в стране гласных и согласных живут рыбные тексты. Одна встретил большой домах повстречался однажды образ несколько точках ведущими, первую свой, подпоясал рукопись!', CURRENT_TIMESTAMP)
            ");
        }
    }
}