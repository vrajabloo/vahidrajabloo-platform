<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * Contact page template with built-in contact form
 *
 * @package VahidRajabloo_Theme
 */

get_header();

// Handle form submission
$form_submitted = false;
$form_success = false;
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vr_contact_nonce'])) {
    if (wp_verify_nonce($_POST['vr_contact_nonce'], 'vr_contact_form')) {
        $name = sanitize_text_field($_POST['contact_name'] ?? '');
        $email = sanitize_email($_POST['contact_email'] ?? '');
        $subject = sanitize_text_field($_POST['contact_subject'] ?? '');
        $message = sanitize_textarea_field($_POST['contact_message'] ?? '');
        
        if (empty($name) || empty($email) || empty($message)) {
            $form_error = 'Please fill in all required fields.';
        } elseif (!is_email($email)) {
            $form_error = 'Please enter a valid email address.';
        } else {
            // Get admin email
            $to = get_option('admin_email');
            $email_subject = '[Contact Form] ' . ($subject ?: 'New Message from ' . $name);
            $email_body = "Name: $name\n";
            $email_body .= "Email: $email\n";
            $email_body .= "Subject: $subject\n\n";
            $email_body .= "Message:\n$message";
            
            $headers = array(
                'Content-Type: text/plain; charset=UTF-8',
                'Reply-To: ' . $name . ' <' . $email . '>',
            );
            
            $sent = wp_mail($to, $email_subject, $email_body, $headers);
            
            if ($sent) {
                $form_success = true;
            } else {
                $form_error = 'Failed to send message. Please try again later.';
            }
        }
        $form_submitted = true;
    }
}
?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Page Header -->
    <section class="page-header page-header--contact">
        <div class="container">
            <h1 class="page-header__title"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <p class="page-header__subtitle"><?php the_excerpt(); ?></p>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Contact Content -->
    <section class="contact-section section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Info -->
                <div class="contact-info">
                    <h2 class="contact-info__title"><?php echo esc_html(get_theme_mod('contact_info_title', 'Get in Touch')); ?></h2>
                    
                    <?php if (get_theme_mod('contact_email')) : ?>
                    <div class="contact-info__item">
                        <span class="contact-info__icon">📧</span>
                        <div>
                            <strong>Email</strong>
                            <a href="mailto:<?php echo esc_attr(get_theme_mod('contact_email')); ?>">
                                <?php echo esc_html(get_theme_mod('contact_email')); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('contact_phone')) : ?>
                    <div class="contact-info__item">
                        <span class="contact-info__icon">📱</span>
                        <div>
                            <strong>Phone</strong>
                            <a href="tel:<?php echo esc_attr(get_theme_mod('contact_phone')); ?>">
                                <?php echo esc_html(get_theme_mod('contact_phone')); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('contact_address')) : ?>
                    <div class="contact-info__item">
                        <span class="contact-info__icon">📍</span>
                        <div>
                            <strong>Address</strong>
                            <span><?php echo esc_html(get_theme_mod('contact_address')); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Social Links -->
                    <?php if (get_theme_mod('footer_social_linkedin') || get_theme_mod('footer_social_twitter') || get_theme_mod('footer_social_instagram')) : ?>
                    <div class="contact-info__social">
                        <?php if (get_theme_mod('footer_social_linkedin')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('footer_social_linkedin')); ?>" target="_blank" rel="noopener" class="social-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('footer_social_twitter')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('footer_social_twitter')); ?>" target="_blank" rel="noopener" class="social-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        <?php endif; ?>
                        <?php if (get_theme_mod('footer_social_instagram')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('footer_social_instagram')); ?>" target="_blank" rel="noopener" class="social-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Contact Form -->
                <div class="contact-form">
                    <h3 class="contact-form__title">Send us a message</h3>
                    
                    <?php if ($form_submitted && $form_success) : ?>
                        <div class="contact-form__success">
                            <span class="success-icon">✓</span>
                            <h4>Message Sent!</h4>
                            <p>Thank you for contacting us. We'll get back to you soon.</p>
                        </div>
                    <?php else : ?>
                        
                        <?php if ($form_error) : ?>
                            <div class="contact-form__error">
                                <?php echo esc_html($form_error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="contact-form__form" novalidate>
                            <?php wp_nonce_field('vr_contact_form', 'vr_contact_nonce'); ?>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="contact_name">Name <span class="required">*</span></label>
                                    <input type="text" id="contact_name" name="contact_name" required 
                                           value="<?php echo esc_attr($_POST['contact_name'] ?? ''); ?>"
                                           placeholder="Your name">
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_email">Email <span class="required">*</span></label>
                                    <input type="email" id="contact_email" name="contact_email" required
                                           value="<?php echo esc_attr($_POST['contact_email'] ?? ''); ?>"
                                           placeholder="your@email.com">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_subject">Subject</label>
                                <input type="text" id="contact_subject" name="contact_subject"
                                       value="<?php echo esc_attr($_POST['contact_subject'] ?? ''); ?>"
                                       placeholder="What is this about?">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_message">Message <span class="required">*</span></label>
                                <textarea id="contact_message" name="contact_message" rows="5" required
                                          placeholder="Your message..."><?php echo esc_textarea($_POST['contact_message'] ?? ''); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn--primary btn--large">
                                <span>Send Message</span>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
                                </svg>
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <!-- Extra content from page editor -->
                    <?php 
                    $content = get_the_content();
                    if (!empty(trim($content))) : ?>
                        <div class="contact-form__extra">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    
    <?php endwhile; ?>
</main>

<style>
/* Contact Page Styles */
.page-header--contact {
    padding: 4rem 0 3rem;
    background: var(--color-background-alt, #F8FAFC);
    text-align: center;
}

.page-header__title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--color-text-primary, #0F172A);
    margin-bottom: 0.5rem;
}

.page-header__subtitle {
    color: var(--color-text-body, #475569);
    font-size: 1.125rem;
    max-width: 600px;
    margin: 0 auto;
}

.contact-section {
    padding: 4rem 0 5rem;
}

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 4rem;
    max-width: 1100px;
    margin: 0 auto;
}

@media (max-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
}

/* Contact Info */
.contact-info__title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--color-text-primary, #0F172A);
}

.contact-info__item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.25rem;
    padding: 1rem;
    background: var(--color-background-alt, #F8FAFC);
    border-radius: 12px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.contact-info__item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.contact-info__icon {
    font-size: 1.5rem;
    line-height: 1;
}

.contact-info__item strong {
    display: block;
    font-size: 0.875rem;
    color: var(--color-text-muted, #94A3B8);
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.contact-info__item a,
.contact-info__item span {
    color: var(--color-text-primary, #0F172A);
    text-decoration: none;
    font-weight: 500;
}

.contact-info__item a:hover {
    color: var(--color-primary, #4361EE);
}

.contact-info__social {
    display: flex;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--color-border, #E2E8F0);
}

.social-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: var(--color-background-alt, #F8FAFC);
    color: var(--color-text-body, #475569);
    transition: all 0.2s ease;
}

.social-link:hover {
    background: var(--color-primary, #4361EE);
    color: #fff;
    transform: translateY(-2px);
}

/* Contact Form */
.contact-form {
    background: #fff;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--color-border, #E2E8F0);
}

[data-theme="dark"] .contact-form {
    background: var(--color-background-alt, #1E293B);
}

.contact-form__title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    color: var(--color-text-primary, #0F172A);
}

.contact-form__success {
    text-align: center;
    padding: 3rem 2rem;
}

.contact-form__success .success-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    color: #fff;
    font-size: 2rem;
    margin-bottom: 1rem;
}

.contact-form__success h4 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--color-text-primary, #0F172A);
}

.contact-form__success p {
    color: var(--color-text-body, #475569);
}

.contact-form__error {
    background: #FEF2F2;
    color: #DC2626;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
    border: 1px solid #FECACA;
}

[data-theme="dark"] .contact-form__error {
    background: rgba(220, 38, 38, 0.1);
    border-color: rgba(220, 38, 38, 0.3);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 500px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

.form-group {
    margin-bottom: 1.25rem;
}

.form-group label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text-primary, #0F172A);
    margin-bottom: 0.5rem;
}

.form-group .required {
    color: #DC2626;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1.5px solid var(--color-border, #E2E8F0);
    border-radius: 10px;
    font-size: 1rem;
    font-family: inherit;
    background: var(--color-secondary, #fff);
    color: var(--color-text-primary, #0F172A);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--color-primary, #4361EE);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: var(--color-text-muted, #94A3B8);
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

.contact-form .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 1rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.contact-form .btn--primary {
    background: var(--gradient-primary, linear-gradient(135deg, #4361EE 0%, #7C3AED 100%));
    color: #fff;
}

.contact-form .btn--primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
}

.contact-form .btn--primary:active {
    transform: translateY(0);
}

.contact-form__extra {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--color-border, #E2E8F0);
}
</style>

<?php
get_footer();
