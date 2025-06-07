<?php
    $siteName ??='ShareNami';
    $pageName ??= 'Home Page';
    $ownerName ??= 'DabeTech';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            /**
                * Header file for the landing page
                */
            require_once 'layout/header.php';
        ?>
    </head>
    <body>
        <header class="landing-header">
            <div class="container">
                <div class="header-content">
                    <div class="logo">
                        <img src="<?= $helpers::asset('logo.png') ?>" alt="<?= htmlspecialchars($siteName) ?> Logo" loading="lazy">
                        <span><?= htmlspecialchars($siteName) ?></span>
                    </div>
                    <div class="main-nav">
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#how-it-works">How It Works</a></li>
                            <li><a href="#pricing">Pricing</a></li>
                            <li><a href="<?= $helpers::route('/login') ?>">Login</a></li>
                        </ul>
                    </div>
                    <a href="<?= $helpers::route('/register') ?>" class="btn-primary">Sign Up Free</a>
                    <button class="mobile-menu-btn" aria-label="Toggle mobile menu">
                        <img src="<?= $helpers::asset('menu-icon.png') ?>" class="mobile-menu-img" alt="Menu Icon">
                    </button>
                </div>

                <!-- mobile menu nav -->
                <div class="mobile-nav">
                    <div class="mobile-container"> 
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#how-it-works">How It Works</a></li>
                            <li><a href="#pricing">Pricing</a></li>
                            <li><a href="<?= $helpers::route('/login') ?>" class="login-link">Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section class="hero">
                <div class="container">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h2>Share Files Securely with Your Team</h2>
                            <p><?= htmlspecialchars($siteName) ?> makes it simple to send and receive files with end-to-end encryption and real-time chat.</p>
                            <div class="hero-buttons">
                                <a href="<?= $helpers::route('/register') ?>" class="btn-primary">Get Started</a>
                            </div>
                        </div>
                        <div class="hero-image">
                            <img src="/assets/hero-image.png" alt="<?= htmlspecialchars($siteName) ?> app screenshot" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="features" id="features">
                <div class="container">
                    <h2 class="section-title">Powerful Features</h2>
                    <p class="section-subtitle">Everything you need for secure file sharing</p>
                    
                    <div class="features-grid">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                            </div>
                            <h3>End-to-End Encryption</h3>
                            <p>Your files are encrypted before they leave your device and can only be decrypted by the intended recipient.</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-comments" aria-hidden="true"></i>
                            </div>
                            <h3>Integrated Chat</h3>
                            <p>Discuss your files in real-time with built-in chat functionality.</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
                            </div>
                            <h3>Large File Support</h3>
                            <p>Send files up to 10GB with no compression or quality loss.</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-hourglass-half" aria-hidden="true"></i>
                            </div>
                            <h3>Expiring Links</h3>
                            <p>Set expiration dates for shared files to maintain control over your content.</p>
                        </div>
                        
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-mobile-alt" aria-hidden="true"></i>
                            </div>
                            <h3>Cross-Platform</h3>
                            <p>Access your files from any device with our responsive web interface.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="how-it-works" id="how-it-works">
                <div class="container">
                    <h2 class="section-title">How <?= htmlspecialchars($siteName) ?> Works</h2>
                    <p class="section-subtitle">Send files in just a few simple steps</p>
                    
                    <div class="steps">
                        <div class="step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Sign Up</h3>
                                <p>Create your free account in less than a minute.</p>
                            </div>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Start a Conversation</h3>
                                <p>Find the person you want to share files with or invite them to join.</p>
                            </div>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Upload & Share</h3>
                                <p>Drag and drop your files or select them from your device.</p>
                            </div>
                        </div>
                        
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h3>Collaborate</h3>
                                <p>Discuss the files in real-time with built-in chat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Pricing Section -->
            <section class="pricing" id="pricing">
                <div class="container">
                    <h2 class="section-title">Simple, Transparent Pricing</h2>
                    <p class="section-subtitle">Choose the plan that's right for you</p>
                    
                    <div class="pricing-cards">
                        <div class="pricing-card">
                            <h3>Free</h3>
                            <div class="price">$0<span>/month</span></div>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> 5GB Storage</li>
                                <li><i class="fas fa-check"></i> 2GB File Size Limit</li>
                                <li><i class="fas fa-check"></i> Basic Encryption</li>
                                <li><i class="fas fa-check"></i> 1 Month File Retention</li>
                            </ul>
                            <a href="<?= $helpers::route('/register') ?>" class="btn-primary">Get Started</a>
                        </div>
                        
                        <div class="pricing-card featured">
                            <div class="popular-badge">Most Popular</div>
                            <h3>Pro</h3>
                            <div class="price">$9<span>/month</span></div>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> 100GB Storage</li>
                                <li><i class="fas fa-check"></i> 10GB File Size Limit</li>
                                <li><i class="fas fa-check"></i> Advanced Encryption</li>
                                <li><i class="fas fa-check"></i> 6 Months File Retention</li>
                                <li><i class="fas fa-check"></i> Priority Support</li>
                            </ul>
                            <a href="<?= $helpers::route('/register') ?>" class="btn-primary">Upgrade Now</a>
                        </div>
                        
                        <div class="pricing-card">
                            <h3>Enterprise</h3>
                            <div class="price">Custom</div>
                            <ul class="features-list">
                                <li><i class="fas fa-check"></i> Unlimited Storage</li>
                                <li><i class="fas fa-check"></i> No File Size Limit</li>
                                <li><i class="fas fa-check"></i> Military-Grade Encryption</li>
                                <li><i class="fas fa-check"></i> Unlimited Retention</li>
                                <li><i class="fas fa-check"></i> 24/7 Support</li>
                                <li><i class="fas fa-check"></i> Custom Branding</li>
                            </ul>
                            <a href="#contact" class="btn-secondary">Contact Sales</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta">
                <div class="container">
                    <h2>Ready to Share Files Securely?</h2>
                    <p>Join others who trust <?= htmlspecialchars($siteName) ?> for their file sharing needs.</p>
                    <a href="<?= $helpers::route('/register') ?>" class="btn-primary">Get Started for Free</a>
                </div>
            </section>
        </main>

        <footer class="main-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-about">
                        <div class="logo">
                            <img src="/assets/logo-white.png" alt="<?= htmlspecialchars($siteName) ?> Logo" loading="lazy">
                            <span><?= htmlspecialchars($siteName) ?></span>
                        </div>
                        <p>Secure file sharing made simple for teams and individuals.</p>
                        <div class="social-links">
                            <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                            <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                    
                    <div class="footer-links">
                        <h4>Product</h4>
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#pricing">Pricing</a></li>
                            <li><a href="#how-it-works">How It Works</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-links">
                        <h4>Support</h4>
                        <ul>
                            <li>
                                <a href="#" class="whatsapp-link">
                                    <img src="/assets/whatsapp-icon-white.png" alt="WhatsApp Icon" loading="lazy">
                                    <span>Click to chat with us</span>
                                </a>
                            </li>
                            <li>
                                <div class="email-div">
                                    <img src="/assets/email-icon.png" alt="Email Icon">
                                    <span>sharenamisupport@gmail.com</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; 2025 <?= htmlspecialchars($siteName) ?>. All rights reserved. By <?= htmlspecialchars($ownerName) ?></p>
                    <div class="footer-legal">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </footer>

        <script src="/assets/js/landing.js?v=<?= time(); ?>"></script>
    </body>
</html>