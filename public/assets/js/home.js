
document.addEventListener("DOMContentLoaded", () => {
    
    // Récupération de l'URL de base pour garantir un routing correct
    const baseTag = document.querySelector('base');
    const baseUrl = baseTag ? baseTag.getAttribute('href') : '/';

    // Récupération du jeton CSRF injecté dans la vue
    const homeWrapper = document.getElementById('homeMainWrapper');
    const csrfToken = homeWrapper ? homeWrapper.getAttribute('data-csrf') : '';

    // GESTION DU SLIDER PRINCIPAL
    const sliderTrack = document.getElementById('sliderTrack');
    const slides = document.querySelectorAll('.slide');
    const btnNext = document.getElementById('btnNext');
    const btnPrev = document.getElementById('btnPrev');
    const dotsContainer = document.getElementById('sliderDots');

    if (sliderTrack && slides.length > 0) {
        const totalSlides = slides.length;
        let currentIndex = 0;
        let autoPlayInterval;

        if (dotsContainer) {
            slides.forEach((_, index) => {
                const dot = document.createElement('button');
                dot.classList.add('dot');
                dot.setAttribute('role', 'tab');
                dot.setAttribute('aria-label', `Slide ${index + 1}`);
                if (index === 0) dot.classList.add('active');
                
                dot.addEventListener('click', () => {
                    currentIndex = index;
                    updateSliderPosition();
                    resetAutoPlay();
                });
                dotsContainer.appendChild(dot);
            });
        }

        const updateSliderPosition = () => {
            sliderTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
            if (dotsContainer) {
                document.querySelectorAll('.slider-dots .dot').forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }
        };

        const moveToNextSlide = () => {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSliderPosition();
        };

        const moveToPrevSlide = () => {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSliderPosition();
        };

        if (btnNext) btnNext.addEventListener('click', () => { moveToNextSlide(); resetAutoPlay(); });
        if (btnPrev) btnPrev.addEventListener('click', () => { moveToPrevSlide(); resetAutoPlay(); });

        const startAutoPlay = () => { autoPlayInterval = setInterval(moveToNextSlide, 6000); };
        const resetAutoPlay = () => { clearInterval(autoPlayInterval); startAutoPlay(); };

        if (totalSlides > 1) startAutoPlay();
    }

    //  GESTION DYNAMIQUE DU CAROUSEL DES MARQUES
    const brandsCarouselTrack = document.getElementById('brandsCarouselTrack');
    const brandsBtnNext = document.getElementById('brandsBtnNext');
    const brandsBtnPrev = document.getElementById('brandsBtnPrev');

    if (brandsCarouselTrack) {
        const getBrandScrollStep = () => {
            const firstBrandItem = brandsCarouselTrack.querySelector('.brands-carousel-item');
            return firstBrandItem ? firstBrandItem.clientWidth + 15 : 120;
        };

        if (brandsBtnNext) {
            brandsBtnNext.addEventListener('click', () => {
                brandsCarouselTrack.scrollBy({ left: getBrandScrollStep(), behavior: 'smooth' });
            });
        }

        if (brandsBtnPrev) {
            brandsBtnPrev.addEventListener('click', () => {
                brandsCarouselTrack.scrollBy({ left: -getBrandScrollStep(), behavior: 'smooth' });
            });
        }
    }

    //gestion dynamique du carousel boutique tv
    const tvCarouselTrack = document.getElementById('tvCarouselTrack');
    const tvBtnNext = document.getElementById('tvBtnNext');
    const tvBtnPrev = document.getElementById('tvBtnPrev');

    if (tvCarouselTrack) {
        const getTvScrollStep = () => {
            const firstTvItem = tvCarouselTrack.querySelector('.tv-carousel-item');
            return firstTvItem ? firstTvItem.clientWidth + 20 : 300;
        };

        if (tvBtnNext) {
            tvBtnNext.addEventListener('click', () => {
                tvCarouselTrack.scrollBy({ left: getTvScrollStep(), behavior: 'smooth' });
            });
        }

        if (tvBtnPrev) {
            tvBtnPrev.addEventListener('click', () => {
                tvCarouselTrack.scrollBy({ left: -getTvScrollStep(), behavior: 'smooth' });
            });
        }
    }

    //  LECTURE VIDÉO INLINE (BOUTIQUE TV) - SÉCURISÉ (Création DOM)
    document.addEventListener('click', (e) => {
        const tvTrigger = e.target.closest('.tv-image-container');
        if (tvTrigger) {
            const parentItem = tvTrigger.closest('.tv-carousel-item');
            let videoSrc = parentItem.getAttribute('data-video-src');
            
            if (videoSrc) {
                if (videoSrc.includes('youtube.com') && !videoSrc.includes('autoplay=')) {
                    videoSrc += (videoSrc.includes('?') ? '&' : '?') + 'autoplay=1';
                }
                
                // Vider l'élément parent en toute sécurité
                parentItem.innerHTML = '';
                
                // SÉCURITÉ : Création manuelle de l'iframe pour éviter toute injection HTML/JS
                const iframe = document.createElement('iframe');
                iframe.src = videoSrc;
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('allowfullscreen', 'true');
                iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                iframe.style.width = '100%';
                iframe.style.aspectRatio = '16/9';
                iframe.style.borderRadius = '8px';
                iframe.style.border = '1px solid #e2e8f0';
                iframe.style.display = 'block';
                
                parentItem.appendChild(iframe);
            }
        }
    });

    //  GESTION DU CAROUSEL DES ACTUALITÉS (NEWS)
    
    const newsCarouselTrack = document.getElementById('newsCarouselTrack');
    const newsBtnNext = document.getElementById('newsBtnNext');
    const newsBtnPrev = document.getElementById('newsBtnPrev');

    if (newsCarouselTrack) {
        const getNewsScrollStep = () => {
            const firstNewsItem = newsCarouselTrack.querySelector('.news-card');
            return firstNewsItem ? firstNewsItem.clientWidth + 20 : 350;
        };

        if (newsBtnNext) {
            newsBtnNext.addEventListener('click', () => {
                newsCarouselTrack.scrollBy({ left: getNewsScrollStep(), behavior: 'smooth' });
            });
        }

        if (newsBtnPrev) {
            newsBtnPrev.addEventListener('click', () => {
                newsCarouselTrack.scrollBy({ left: -getNewsScrollStep(), behavior: 'smooth' });
            });
        }
    }

   