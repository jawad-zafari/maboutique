<div id="homeMainWrapper" class="home-global-wrapper" data-csrf="<?= htmlspecialchars($data['csrf_token'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <section class="main-slider" aria-roledescription="carousel" aria-label="Bannières principales">
        <div class="slider-track" id="sliderTrack">
            <?php
            $slider1 = $data['slider1'] ?? [];
            if(!empty($slider1)): foreach($slider1 as $slide):
            ?>
            <div class="slide" role="group" aria-roledescription="slide">
                <a href="<?= htmlspecialchars($slide['link'] ?? '#', ENT_QUOTES, 'UTF-8') ?>" class="slide-link-container">
                    <img src="<?= URL . htmlspecialchars($slide['image_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($slide['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="slide-bg">
                    
                    <div class="slide-content">
                        <h2 class="slide-title"><?= htmlspecialchars($slide['title'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
                        
                        <?php if(!empty($slide['description'])): ?>
                            <p class="slide-desc"><?= htmlspecialchars($slide['description'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php endif; ?>
                        
                        <span class="btn-slide-action">
                            <?= htmlspecialchars($slide['button_text'] ?? 'Découvrir', ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                </a>
            </div>
            <?php endforeach; else: ?>
                <div class="slide" role="group">
                    <img src="https://placehold.co/1920x600/3b5bdb/ffffff?text=Bannière+Principale" class="slide-bg" alt="Bannière par défaut">
                    <div class="slide-content">
                        <h2 class="slide-title">Bienvenue sur notre boutique</h2>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <button type="button" class="slider-btn prev cyber-btn" id="btnPrev" aria-label="Bannière précédente"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
        <button type="button" class="slider-btn next cyber-btn" id="btnNext" aria-label="Bannière suivante"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
        
        <div class="slider-dots" id="sliderDots" role="tablist"></div>
    </section>

    <div class="home-container">
        
        <section class="features-row modern-shadow">
            <div class="feature-item">
                <div class="icon-wrapper"><i class="fa-solid fa-truck-fast" aria-hidden="true"></i></div>
                <div class="feature-text">
                    <span class="feature-title">Livraison Gratuite</span>
                    <span class="feature-subtitle">Dès 50€ d'achat</span>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="icon-wrapper"><i class="fa-solid fa-rotate-left" aria-hidden="true"></i></div>
                <div class="feature-text">
                    <span class="feature-title">Retours Faciles</span>
                    <span class="feature-subtitle">Sous 30 jours</span>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="icon-wrapper"><i class="fa-solid fa-lock" aria-hidden="true"></i></div>
                <div class="feature-text">
                    <span class="feature-title">Paiement Sécurisé</span>
                    <span class="feature-subtitle">100% garanti</span>
                </div>
            </div>
            
            <div class="feature-item">
                <div class="icon-wrapper"><i class="fa-solid fa-headset" aria-hidden="true"></i></div>
                <div class="feature-text">
                    <span class="feature-title">Support 24/7</span>
                    <span class="feature-subtitle">À votre écoute</span>
                </div>
            </div>
        </section>

        