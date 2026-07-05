<?php

/**
 * Contact form handling (native, no plugin).
 *
 * The form POSTs to admin-post.php with the `mikroloty_contact` action.
 * Protection: nonce + honeypot. The message is emailed to the address from
 * the "Kontakt" options page (contact_email) or the site admin address.
 */

namespace App;

add_action('admin_post_nopriv_mikroloty_contact', __NAMESPACE__.'\\handle_contact');
add_action('admin_post_mikroloty_contact', __NAMESPACE__.'\\handle_contact');

function handle_contact(): void
{
    $referer = wp_get_referer() ?: home_url('/');

    // Honeypot — a hidden field filled in only by bots.
    if (! empty($_POST['website'])) {
        wp_safe_redirect(add_query_arg('contact', 'sent', $referer));
        exit;
    }

    if (! isset($_POST['_contact_nonce']) || ! wp_verify_nonce($_POST['_contact_nonce'], 'mikroloty_contact')) {
        wp_safe_redirect(add_query_arg('contact', 'error', $referer));
        exit;
    }

    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    $consent = ! empty($_POST['consent']);

    if (! $name || ! is_email($email) || ! $message || ! $consent) {
        wp_safe_redirect(add_query_arg('contact', 'error', $referer));
        exit;
    }

    $to = get_field('contact_email', 'option') ?: get_option('admin_email');

    $body = sprintf(
        "Nowa wiadomość z formularza kontaktowego mikroloty.com\n\n".
        "Imię i nazwisko: %s\nE-mail: %s\nTemat: %s\n\nWiadomość:\n%s\n",
        $name, $email, $subject ?: '—', $message
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: '.$name.' <'.$email.'>',
    ];

    $sent = wp_mail($to, '[mikroloty.com] '.($subject ?: 'Wiadomość z formularza'), $body, $headers);

    wp_safe_redirect(add_query_arg('contact', $sent ? 'sent' : 'error', $referer));
    exit;
}
