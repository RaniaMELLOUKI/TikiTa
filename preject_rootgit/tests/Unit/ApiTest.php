<?php

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test que les fichiers API existent
     */
    public function testApiFilesExist(): void
    {
        $apiFiles = [
            __DIR__ . '/../../api/artiste.php',
            __DIR__ . '/../../api/categorie.php',
            __DIR__ . '/../../api/evenement.php',
        ];

        foreach ($apiFiles as $file) {
            $this->assertFileExists($file, "Le fichier API $file doit exister");
        }
    }

    /**
     * Test que les fichiers API ont une syntaxe PHP valide
     */
    public function testApiFilesSyntax(): void
    {
        $apiFiles = [
            __DIR__ . '/../../api/artiste.php',
            __DIR__ . '/../../api/categorie.php',
            __DIR__ . '/../../api/evenement.php',
        ];

        foreach ($apiFiles as $file) {
            $output = [];
            $returnCode = 0;
            exec("php -l $file 2>&1", $output, $returnCode);
            $this->assertEquals(0, $returnCode, "Le fichier $file doit avoir une syntaxe valide");
        }
    }

    /**
     * Test que le point d'entree index.php existe
     */
    public function testIndexFileExists(): void
    {
        $this->assertFileExists(
            __DIR__ . '/../../index.php',
            'Le fichier index.php doit exister'
        );
    }

    /**
     * Test que les fichiers de pages existent
     */
    public function testPageFilesExist(): void
    {
        $pages = [
            __DIR__ . '/../../buy.php',
            __DIR__ . '/../../sell.php',
            __DIR__ . '/../../setup.php',
        ];

        foreach ($pages as $page) {
            $this->assertFileExists($page, "La page $page doit exister");
        }
    }
}