<?php

/**
 * Validation Helper Class
 * 
 * Provides utility functions for validating user input
 * Helps prevent SQL injection, XSS, and other security issues
 */
class Validator {
    
    /**
     * Validate that a string is not empty
     * 
     * @param string $value - Value to check
     * @param string $fieldName - Field name for error messages
     * @return bool|string - True if valid, error message if invalid
     */
    public static function validateRequired($value, $fieldName = 'Field') {
        if (empty(trim($value))) {
            return "$fieldName is required";
        }
        return true;
    }

    /**
     * Validate email format
     * 
     * @param string $email - Email to validate
     * @return bool|string - True if valid, error message if invalid
     */
    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email format';
        }
        return true;
    }

    /**
     * Validate date is in YYYY-MM-DD format and is valid
     * 
     * @param string $date - Date string to validate
     * @return bool|string - True if valid, error message if invalid
     */
    public static function validateDate($date) {
        // Check format YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return 'Date must be in YYYY-MM-DD format';
        }
        
        // Validate it's an actual date
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) {
            return 'Invalid date';
        }
        
        // Check date is not in the past
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            return 'Event date cannot be in the past';
        }
        
        return true;
    }

    /**
     * Validate number is positive and within range
     * 
     * @param int|float $value - Number to validate
     * @param int $min - Minimum value
     * @param int $max - Maximum value (0 = no max)
     * @param string $fieldName - Field name for error messages
     * @return bool|string - True if valid, error message if invalid
     */
    public static function validateNumber($value, $min = 0, $max = 0, $fieldName = 'Number') {
        if (!is_numeric($value)) {
            return "$fieldName must be a number";
        }
        
        $num = (int)$value;
        
        if ($num < $min) {
            return "$fieldName must be at least $min";
        }
        
        if ($max > 0 && $num > $max) {
            return "$fieldName must not exceed $max";
        }
        
        return true;
    }

    /**
     * Validate string length
     * 
     * @param string $value - String to validate
     * @param int $maxLength - Maximum length allowed
     * @param int $minLength - Minimum length allowed
     * @param string $fieldName - Field name for error messages
     * @return bool|string - True if valid, error message if invalid
     */
    public static function validateLength($value, $maxLength, $minLength = 1, $fieldName = 'Field') {
        $length = strlen($value);
        
        if ($length < $minLength) {
            return "$fieldName must be at least $minLength characters";
        }
        
        if ($length > $maxLength) {
            return "$fieldName must not exceed $maxLength characters";
        }
        
        return true;
    }

    /**
     * Sanitize string input to prevent XSS
     * 
     * @param string $value - Value to sanitize
     * @return string - Sanitized value
     */
    public static function sanitizeString($value) {
        return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate multiple fields at once
     * 
     * @param array $data - Associative array of field values
     * @param array $rules - Validation rules array
     *                       Format: ['fieldName' => 'required|email|max:100']
     * @return array - Array of errors, empty if all valid
     */
    public static function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $ruleString) {
            $fieldRules = explode('|', $ruleString);
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule) {
                $result = self::applyRule($field, $value, trim($rule));
                if ($result !== true) {
                    $errors[$field] = $result;
                    break; // Stop checking this field after first error
                }
            }
        }
        
        return $errors;
    }

    /**
     * Apply a single validation rule
     * 
     * @param string $field - Field name
     * @param mixed $value - Value to validate
     * @param string $rule - Rule string (e.g., 'required', 'email', 'max:100')
     * @return bool|string - True if valid, error message if invalid
     */
    private static function applyRule($field, $value, $rule) {
        // Parse rule with parameters
        $parts = explode(':', $rule);
        $ruleName = $parts[0];
        $param = $parts[1] ?? null;
        
        switch ($ruleName) {
            case 'required':
                return self::validateRequired($value, $field);
            case 'email':
                if (empty($value)) return true; // Optional field
                return self::validateEmail($value);
            case 'date':
                if (empty($value)) return true;
                return self::validateDate($value);
            case 'numeric':
                if (empty($value)) return true;
                return is_numeric($value) ? true : "$field must be a number";
            case 'min':
                if (empty($value)) return true;
                return self::validateNumber($value, (int)$param, 0, $field);
            case 'max':
                if (empty($value)) return true;
                return self::validateLength($value, (int)$param, 0, $field);
            case 'minlength':
                if (empty($value)) return true;
                return strlen($value) >= (int)$param ? true : "$field must be at least " . $param . " characters";
            case 'maxlength':
                if (empty($value)) return true;
                return strlen($value) <= (int)$param ? true : "$field must not exceed " . $param . " characters";
            default:
                return true; // Unknown rule, ignore
        }
    }
}

