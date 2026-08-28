<?php
// SÉCURITÉ : Récupération du paramètre de retour (Intended URL) de manière propre et protégée contre XSS
$backUrl = isset($_GET['back']) ? htmlspecialchars($_GET['back'], ENT_QUOTES, 'UTF-8') : '';
?>
