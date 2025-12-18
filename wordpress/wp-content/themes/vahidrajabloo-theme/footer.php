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
                        <?php
                        wp_nav_menu( array(
                            'theme_location' => 'footer-2',
                            'menu_class'     => 'footer-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'depth'          => 1,
                        ));
                        ?>
                    <?php endif; ?>
                </div>

                <!-- Footer Column 4 -->
                <div class="footer-col">
                    <h4 class="widget-title">Connect</h4>
                    <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-4' ); ?>
                    <?php endif; ?>
                    <div class="social-links flex flex--gap-md">
                        <?php
                        $social_icons = array(
                            'facebook'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                            'twitter'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                            'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
                            'linkedin'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                            'youtube'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                            'wikipedia' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12.09 13.119c-.936 1.932-2.217 4.548-2.853 5.728-.616 1.074-1.127.931-1.532.029-1.406-3.321-4.293-9.144-5.651-12.409-.251-.601-.441-.987-.619-1.139-.181-.15-.554-.24-1.122-.271C.103 5.033 0 4.982 0 4.898v-.455l.052-.045c.924-.005 5.401 0 5.401 0l.051.045v.434c0 .119-.075.176-.225.176l-.564.031c-.485.029-.727.164-.727.436 0 .135.053.33.166.601 1.082 2.646 4.818 10.521 4.818 10.521l.136.046 2.411-4.81-.482-1.067-1.658-3.264s-.318-.654-.428-.872c-.728-1.443-.712-1.518-1.447-1.617-.207-.023-.313-.05-.313-.149v-.468l.06-.045h4.292l.113.037v.451c0 .105-.076.15-.227.15l-.308.047c-.792.061-.661.381-.136 1.422l1.582 3.252 1.758-3.504c.293-.64.233-.801.111-.947-.07-.084-.305-.22-.812-.24l-.201-.021c-.052 0-.098-.015-.145-.051-.045-.03-.066-.07-.066-.105v-.494l.066-.051h4.695l.054.045v.494c0 .105-.057.156-.165.156l-.463.036c-.428.036-.838.174-1.227.405-.346.199-.725.675-1.082 1.346l-2.194 4.307.99 2.045 2.741 5.552s.164.074.202.074c.045 0 .181-.06.181-.06s3.198-6.659 4.023-8.391c.646-1.353.596-1.516.092-1.616l-.529-.084c-.166-.015-.249-.075-.249-.179v-.457l.045-.045h4.455l.045.045v.457c0 .135-.09.195-.27.195-.781.06-1.32.18-1.605.315-.314.15-.603.454-.878.915-.229.369-3.63 7.036-3.63 7.036l-1.054 2.196-.045.015c-.63-1.2-2.49-5.041-2.49-5.041l-2.062 4.035-.045.015c-.87-1.665-2.773-5.506-2.773-5.506l-.045.015z"/></svg>',
                        );
                        foreach ( $social_icons as $network => $icon ) :
                            $url = get_theme_mod( "footer_social_{$network}", '' );
                            if ( $url ) :
                        ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="<?php echo esc_attr( ucfirst($network) ); ?>">
                                <?php echo $icon; ?>
                            </a>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
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
