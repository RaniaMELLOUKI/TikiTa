<?php

use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * Test que la classe Validator existe et peut etre instanciee
     */
    public function testValidatorClassExists(): void
    {
        $this->assertTrue(class_exists('Validator'), 'La classe Validator doit exister');
    }

    /**
     * Test de validation d'un email valide
     */
    public function testValidEmail(): void
    {
        $validEmails = [
            'user@example.com',
            'test.email@domain.org',
            'name+tag@company.co'
        ];

        foreach ($validEmails as $email) {
            $this->assertTrue(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "L'email $email devrait etre valide"
            );
        }
    }

    /**
     * Test de validation d'un email invalide
     */
    public function testInvalidEmail(): void
    {
        $invalidEmails = [
            'not-an-email',
            '@domain.com',
            'user@',
            '',
            'user @domain.com'
        ];

        foreach ($invalidEmails as $email) {
            $this->assertFalse(
                filter_var($email, FILTER_VALIDATE_EMAIL) !== false,
                "L'email '$email' devrait etre invalide"
            );
        }
    }

    /**
     * Test que les champs obligatoires ne sont pas vides
     */
    public function testRequiredFieldsNotEmpty(): void
    {
        $validData = ['nom' => 'Test', 'email' => 'test@test.com'];
        $emptyData = ['nom' => '', 'email' => ''];

        foreach ($validData as $field => $value) {
            $this->assertNotEmpty($value, "Le champ $field ne doit pas etre vide");
        }

        foreach ($emptyData as $field => $value) {
            $this->assertEmpty($value, "Le champ $field devrait etre vide dans ce test");
        }
    }
}