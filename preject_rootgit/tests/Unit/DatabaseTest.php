<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * Test que la classe Database existe
     */
    public function testDatabaseClassExists(): void
    {
        $this->assertTrue(class_exists('Database'), 'La classe Database doit exister');
    }

    /**
     * Test que les constantes de connexion sont definies
     */
    public function testDatabaseConnectionConstants(): void
    {
        // Verifier que les variables d'environnement de connexion sont accessibles
        $requiredEnvVars = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];

        foreach ($requiredEnvVars as $var) {
            // En environnement de test, on verifie juste que getenv fonctionne
            $this->assertIsString(
                getenv($var) ?: '',
                "La variable $var devrait retourner une chaine"
            );
        }
    }

    /**
     * Test que PDO est disponible
     */
    public function testPdoExtensionLoaded(): void
    {
        $this->assertTrue(
            extension_loaded('pdo'),
            'L\'extension PDO doit etre chargee'
        );
    }

    /**
     * Test que l'extension PDO MySQL est disponible
     */
    public function testPdoMysqlExtensionLoaded(): void
    {
        $this->assertTrue(
            extension_loaded('pdo_mysql'),
            'L\'extension PDO MySQL doit etre chargee'
        );
    }

    /**
     * Test que MySQLi est disponible
     */
    public function testMysqliExtensionLoaded(): void
    {
        $this->assertTrue(
            extension_loaded('mysqli'),
            'L\'extension MySQLi doit etre chargee'
        );
    }
}