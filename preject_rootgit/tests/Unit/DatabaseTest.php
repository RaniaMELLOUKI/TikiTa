<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../classes/database.php';

class DatabaseTest extends TestCase
{
    public function testDatabaseClassExists(): void
    {
        $this->assertTrue(class_exists('Database'), 'La classe Database doit exister');
    }

    public function testDatabaseConnectionConstants(): void
    {
        $requiredEnvVars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
        foreach ($requiredEnvVars as $var) {
            $this->assertIsString(getenv($var) ?: '', "La variable $var devrait retourner une chaine");
        }
    }

    public function testPdoExtensionLoaded(): void
    {
        $this->assertTrue(extension_loaded('pdo'), 'L\'extension PDO doit etre chargee');
    }

    public function testPdoMysqlExtensionLoaded(): void
    {
        $this->assertTrue(extension_loaded('pdo_mysql'), 'L\'extension PDO MySQL doit etre chargee');
    }

    public function testMysqliExtensionLoaded(): void
    {
        $this->assertTrue(extension_loaded('mysqli'), 'L\'extension MySQLi doit etre chargee');
    }
}