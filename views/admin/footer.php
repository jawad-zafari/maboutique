</main> 
</div> 

    <script src="<?= URL ?>public/assets/js/admin.js" defer></script>
    <script src="<?= URL ?>public/assets/js/admin_layout.js" defer></script>
    
    <?php if (!empty($activeMenu)): ?>
        <?php 
            // Assainissement strict du nom de fichier pour empêcher les attaques de type Path Traversal
            $safeMenu = basename($activeMenu);
            
            // Chemin relatif utilisé pour générer l'URL côté client
            $scriptUrlPath = 'public/assets/js/admin_' . $safeMenu . '.js';
            
            // Calcul du chemin physique absolu sur le serveur
            $physicalPath = dirname(__DIR__, 2) . '/' . $scriptUrlPath;
            
            // Inclusion dynamique du script JS
            if (file_exists($physicalPath)): 
        ?>
            
    <?php endif; ?>
    
</body>
</html>