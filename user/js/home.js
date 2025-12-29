// script.js - Enhanced for XAMPP localhost
document.addEventListener('DOMContentLoaded', function() {
    console.log('SlipKu website loaded on XAMPP');
    
    // ========== MOBILE NAVIGATION ==========
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-links a');
    
    // Debug: Check if elements exist
    console.log('Mobile Menu Button:', mobileMenuBtn);
    console.log('Mobile Nav Overlay:', mobileNavOverlay);
    
    // Open mobile menu
    if (mobileMenuBtn && mobileNavOverlay) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Opening mobile menu');
            mobileNavOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close mobile menu
    if (mobileCloseBtn && mobileNavOverlay) {
        mobileCloseBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Closing mobile menu');
            mobileNavOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    }
    
    // Close menu when clicking on links
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            console.log('Mobile link clicked, closing menu');
            mobileNavOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        });
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(event) {
        if (mobileNavOverlay && mobileNavOverlay.classList.contains('active')) {
            if (!mobileNavOverlay.querySelector('.mobile-nav-content').contains(event.target) && 
                !mobileMenuBtn.contains(event.target)) {
                console.log('Click outside, closing menu');
                mobileNavOverlay.classList.remove('active');
                document.body.style.overflow = 'auto';
            }
        }
    });
    
    // ========== 3D SPLINE LOADING OPTIMIZATION ==========
    const loadingOverlay = document.getElementById('loadingOverlay');
    const skipLoadingBtn = document.getElementById('skipLoadingBtn');
    const splineViewer = document.getElementById('splineViewer');
    const splineFallback = document.getElementById('splineFallback');
    const loadSplineBtn = document.getElementById('loadSplineBtn');
    
    // Debug: Check loading elements
    console.log('Loading Overlay:', loadingOverlay);
    console.log('Spline Viewer:', splineViewer);
    
    // Set a maximum loading time (8 seconds for XAMPP localhost)
    const MAX_LOADING_TIME = 8000; // 8 seconds
    let loadingTimer = null;
    let splineLoaded = false;
    
    // Start loading timer
    if (loadingOverlay) {
        loadingTimer = setTimeout(function() {
            if (!splineLoaded && loadingOverlay.style.display !== 'none') {
                console.log('Loading timeout reached, showing fallback');
                hideLoadingShowFallback();
            }
        }, MAX_LOADING_TIME);
    }
    
    // Skip loading button
    if (skipLoadingBtn) {
        skipLoadingBtn.addEventListener('click', function() {
            console.log('Skip loading clicked');
            clearTimeout(loadingTimer);
            hideLoadingShowFallback();
        });
    }
    
    // Load Spline manually
    if (loadSplineBtn) {
        loadSplineBtn.addEventListener('click', function() {
            console.log('Manual load Spline clicked');
            splineFallback.classList.remove('active');
            loadingOverlay.style.display = 'flex';
            loadingOverlay.style.opacity = '1';
            
            // Force Spline to load
            if (splineViewer) {
                splineViewer.setAttribute('loading', 'eager');
                splineViewer.load();
            }
            
            // Set new timeout
            loadingTimer = setTimeout(function() {
                if (!splineLoaded) {
                    hideLoadingShowFallback();
                }
            }, MAX_LOADING_TIME);
        });
    }
    
    // Function to hide loading and show fallback
    function hideLoadingShowFallback() {
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
                if (splineFallback) {
                    splineFallback.classList.add('active');
                }
            }, 500);
        }
    }
    
    // // Force transparent background for Spline
    // if (splineViewer) {
    //     // Apply styles immediately
    //     splineViewer.style.setProperty('--background', 'transparent', 'important');
    //     splineViewer.style.setProperty('background', 'transparent', 'important');
        
    //     // Set initial attributes for better loading
    //     splineViewer.setAttribute('loading', 'lazy');
        
    //     // Listen for Spline load event
    //     splineViewer.addEventListener('load', () => {
    //         console.log('Spline loaded successfully');
    //         splineLoaded = true;
    //         clearTimeout(loadingTimer);
            
    //         if (loadingOverlay) {
    //             loadingOverlay.style.opacity = '0';
    //             setTimeout(() => {
    //                 loadingOverlay.style.display = 'none';
    //                 if (splineFallback) {
    //                     splineFallback.classList.remove('active');
    //                 }
    //             }, 500);
    //         }
            
    //         // Remove any background color
    //         setTimeout(() => {
    //             const canvas = splineViewer.shadowRoot?.querySelector('canvas');
    //             if (canvas) {
    //                 canvas.style.background = 'transparent';
    //                 canvas.style.borderRadius = '20px';
    //             }
    //         }, 1000);
    //     });
        
    //     // Listen for Spline error
    //     splineViewer.addEventListener('error', (e) => {
    //         console.error('Spline loading error:', e);
    //         clearTimeout(loadingTimer);
    //         hideLoadingShowFallback();
    //     });
    // }
    
    // // Auto-hide loading after minimum time (1.5 seconds)
    // setTimeout(() => {
    //     if (loadingOverlay && loadingOverlay.style.display !== 'none' && !splineLoaded) {
    //         console.log('Minimum loading time reached');
    //         // Keep loading visible but allow skip
    //     }
    // }, 1500);
    
    // ========== SMOOTH SCROLL ==========
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                // Close mobile menu if open
                if (mobileNavOverlay && mobileNavOverlay.classList.contains('active')) {
                    mobileNavOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
                
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // ========== NAVBAR SCROLL EFFECT ==========
    const nav = document.getElementById('mainNav');
    if (nav) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
                nav.style.padding = '15px 0';
            } else {
                nav.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
                nav.style.padding = '20px 0';
            }
        });
    }
    
    // ========== INTERACTIVE ELEMENTS ==========
    const interactiveElements = document.querySelectorAll('.btn, .feature-card, .stat-item, .social-icon, .footer-links li, .contact-item');
    interactiveElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // ========== RESIZE HANDLER ==========
    window.addEventListener('resize', function() {
        // Auto-close mobile menu on resize to desktop
        if (window.innerWidth > 992 && mobileNavOverlay && mobileNavOverlay.classList.contains('active')) {
            mobileNavOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
            console.log('Resized to desktop, closing mobile menu');
        }
    });
    
    // ========== INITIALIZE SPLINE ==========
    // Force Spline to start loading after page is ready
    setTimeout(() => {
        if (splineViewer && !splineLoaded) {
            console.log('Starting Spline load...');
            splineViewer.load();
        }
    }, 1000);
    
    // Debug info
    console.log('Window width:', window.innerWidth);
    console.log('Device:', navigator.userAgent);
});