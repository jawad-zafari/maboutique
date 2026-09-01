<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <base href="<?= URL ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= URL ?>public/assets/css/main.css">
</head>
<body class="admin-login-body">

    <div class="admin-login-container">
        
        <div class="login-card">
            
            <div class="login-header">
                <i class="fa-solid fa-user-shield login-icon" aria-hidden="true"></i>
                <h2>Connexion à l'administration</h2>
                <p>Veuillez saisir vos identifiants pour accéder au panneau de contrôle.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'locked'): ?>
                    <div class="alert-message alert-danger text-center" role="alert" aria-live="assertive">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i> Trop de tentatives échouées. Veuillez réessayer dans 5 minutes.
                    </div>
                <?php elseif ($_GET['error'] == 1): ?>
                    <div class="alert-message alert-danger text-center" role="alert" aria-live="assertive">
                        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Identifiants incorrects ou accès non autorisé.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div id="jsLoginErrorMessage" class="alert-message alert-danger display-none-box" role="alert" aria-live="assertive"></div>

            
</body>
</html>