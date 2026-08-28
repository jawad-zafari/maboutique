<?php
// SÉCURITÉ : Récupération des données passées par le contrôleur
$csrfToken    = $csrf_token ?? '';
$oldInput     = $old_input ?? [];
$currentError = $error_msg ?? ($_GET['error'] ?? null);

// Récupération de l'URL de retour (Intended URL)
$backUrl = $oldInput['back_url'] ?? ($_GET['back'] ?? '');
?>
