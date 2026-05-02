<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../classes/database.php';
require_once __DIR__ . '/../../classes/artist.php';
require_once __DIR__ . '/../../classes/categorie.php';
require_once __DIR__ . '/../../classes/evenement.php';
require_once __DIR__ . '/../../classes/inscription.php';
require_once __DIR__ . '/../../classes/utilisateur.php';
require_once __DIR__ . '/../../classes/validator.php';
require_once __DIR__ . '/../../classes/notification.php';

class ClassesIntegrationTest extends TestCase
{
    public function testAllClassesExist(): void
    {
        $expectedClasses = [
            'Database', 'Artist', 'Categorie', 'Evenement',
            'Inscription', 'Utilisateur', 'Validator', 'Notification',
        ];
        foreach ($expectedClasses as $class) {
            $this->assertTrue(class_exists($class), "La classe $class doit exister");
        }
    }

    public function testAllClassesSyntax(): void
    {
        $classFiles = glob(__DIR__ . '/../../classes/*.php');
        $this->assertNotEmpty($classFiles);
        foreach ($classFiles as $file) {
            $output = [];
            $returnCode = 0;
            exec("php -l $file 2>&1", $output, $returnCode);
            $this->assertEquals(0, $returnCode, "Le fichier $file doit avoir une syntaxe valide");
        }
    }

    public function testFrontendAssetsExist(): void
    {
        $this->assertFileExists(__DIR__ . '/../../index.css');
        $this->assertFileExists(__DIR__ . '/../../main.js');
    }

    public function testProjectStructure(): void
    {
        $this->assertDirectoryExists(__DIR__ . '/../../api');
        $this->assertDirectoryExists(__DIR__ . '/../../classes');
    }
}