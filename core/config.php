<?php


// Configuration stricte des cookies de session pour contrer les attaques XSS et CSRF
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Lax');

