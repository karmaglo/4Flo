<?php

// storing users who have hashed password
class AuthController {
    public function register($password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Further implementation here if i needed it
    }
}