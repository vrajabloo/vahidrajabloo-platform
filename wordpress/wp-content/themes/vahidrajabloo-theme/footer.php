</main><!-- #main -->

<footer class="site-footer" id="site-footer">
    <!-- Newsletter Section -->
    <section class="newsletter-section section">
        <div class="container text-center">
            <h2 class="newsletter-title">
                <?php echo esc_html( get_theme_mod( 'newsletter_title', 'Stay in the loop' ) ); ?>
            </h2>
            <p class="newsletter-description">
                <?php echo esc_html( get_theme_mod( 'newsletter_description', 'Subscribe to our newsletter for the latest updates.' ) ); ?>
            </p>
            <form class="newsletter-form" action="#" method="post">
                <input type="email" class="form-input" placeholder="Enter your email" required>
                <button type="submit" class="btn btn--primary">
                    <?php echo esc_html( get_theme_mod( 'newsletter_btn_text', 'Subscribe' ) ); ?>
                </button>
            </form>
        </div>
    </section>

    <!-- Footer Main -->
    <div class="footer-main section--gray">
        <div class="container">
            <div class="footer-grid grid grid--4">
                <!-- Footer Column 1 - Logo & Info -->
                <div class="footer-col footer-col--brand">
                    <div class="footer-logo">
                        <?php vahidrajabloo_custom_logo(); ?>
                    </div>
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    <?php endif; ?>
                </div>

                <!-- Footer Column 2 -->
                <div class="footer-col">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <h4 class="widget-title">Company</h4>
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer',
                            'menu_class'     => 'footer-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ));
                        ?>
                    <?php endif; ?>
                </div>

                <!-- Footer Column 3 -->
                <div class="footer-col">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    <?php else : ?>
                        <h4 class="widget-title">Resources</h4>
                    <?php endif; ?>
                </div>

                <!-- Footer Column 4 -->
                <div class="footer-col">
                    <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-4' ); ?>
                    <?php else : ?>
                        <h4 class="widget-title">Connect</h4>
                        <div class="social-links flex flex--gap-md">
                            <?php
                            $social_networks = array(
                                'facebook'  => 'Facebook',
                                'twitter'   => 'Twitter',
                                'instagram' => 'Instagram',
                                'linkedin'  => 'LinkedIn',
                                'youtube'   => 'YouTube',
                            );
                            foreach ( $social_networks as $network => $label ) :
                                $url = get_theme_mod( "footer_social_{$network}", '' );
                                if ( $url ) :
                            ?>
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php echo esc_attr( $label ); ?>">
                                    <span class="social-icon social-icon--<?php echo esc_attr( $network ); ?>"></span>
                                </a>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container flex flex--between">
            <p class="copyright">
                <?php echo esc_html( get_theme_mod( 'footer_copyright', '© ' . date('Y') . ' VahidRajabloo. All rights reserved.' ) ); ?>
            </p>
            <div class="footer-links flex flex--gap-lg">
                <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Cookies</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
