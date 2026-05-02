<?php

use PHPUnit\Framework\TestCase;

class ClassesIntegrationTest extends TestCase
{
    /**
     * Test que toutes les classes metier existent
     */
    public function testAllClassesExist(): void
    {
        $expectedClasses = [
            'Database',
            'Artist',
            'Categorie',
            'Evenement',
            'Inscription',
            'Utilisateur',
            'Validator',
            'Notification',
        ];

        foreach ($expectedClasses as $class) {
            $this->assertTrue(
                class_exists($class),
                "La classe $class doit exister dans le dossier classes/"
            );
        }
    }

    /**
     * Test que tous les fichiers de classes ont une syntaxe valide
     */
    public function testAllClassesSyntax(): void
    {
        $classFiles = glob(__DIR__ . '/../../classes/*.php');

        $this->assertNotEmpty($classFiles, 'Le dossier classes/ doit contenir des fichiers PHP');

        foreach ($classFiles as $file) {
            $output = [];
            $returnCode = 0;
            exec("php -l $file 2>&1", $output, $returnCode);
            $this->assertEquals(
                0,
                $returnCode,
                "Le fichier $file doit avoir une syntaxe PHP valide"
            );
        }
    }

    /**
     * Test que les fichiers CSS et JS existent
     */
    public function testFrontendAssetsExist(): void
    {
        $this->assertFileExists(
            __DIR__ . '/../../index.css',
            'Le fichier index.css doit exister'
        );
        $this->assertFileExists(
            __DIR__ . '/../../main.js',
            'Le fichier main.js doit exister'
        );
    }

    /**
     * Test la structure du projet
     */
    public function testProjectStructure(): void
    {
        $requiredDirs = [
            __DIR__ . '/../../api',
            __DIR__ . '/../../classes',
        ];

        foreach ($requiredDirs as $dir) {
            $this->assertDirectoryExists($dir, "Le dossier $dir doit exister");
        }
    }
}