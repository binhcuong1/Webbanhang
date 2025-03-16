<?php
class SessionHelper {
    public static function isLoggedIn() {
        return isset($_SESSION['username']);
    }
    
    public static function isAdmin() {
        return self::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    public static function isUser() {
        return self::isLoggedIn() && !isset($_SESSION['role']);
    }
}
