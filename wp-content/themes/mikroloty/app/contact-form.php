<?php

/**
 * Obsługa formularza kontaktowego (natywnie, bez wtyczki).
 *
 * Formularz POST-uje do admin-post.php z akcją `mikroloty_kontakt`.
 * Zabezpieczenia: nonce + honeypot. Wiadomość trafia mailem na adres
 * z opcji „Kontakt" (kontakt_email) lub adres administratora.
 */

namespace App;

add_action('admin_post_nopriv_mikroloty_kontakt', __NAMESPACE__.'\\handle_kontakt');
add_action('admin_post_mikroloty_kontakt', __NAMESPACE__.'\\handle_kontakt');

function handle_kontakt(): void
{
    $referer = wp_get_referer() ?: home_url('/');

    // Honeypot — ukryte pole wypełniane przez boty.
    if (! empty($_POST['strona_www'])) {
        wp_safe_redirect(add_query_arg('kontakt', 'ok', $referer));
        exit;
    }

    if (! isset($_POST['_kontakt_nonce']) || ! wp_verify_nonce($_POST['_kontakt_nonce'], 'mikroloty_kontakt')) {
        wp_safe_redirect(add_query_arg('kontakt', 'blad', $referer));
        exit;
    }

    $imie = sanitize_text_field($_POST['imie'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $temat = sanitize_text_field($_POST['temat'] ?? '');
    $wiadomosc = sanitize_textarea_field($_POST['wiadomosc'] ?? '');
    $zgoda = ! empty($_POST['zgoda']);

    if (! $imie || ! is_email($email) || ! $wiadomosc || ! $zgoda) {
        wp_safe_redirect(add_query_arg('kontakt', 'blad', $referer));
        exit;
    }

    $do = get_field('kontakt_email', 'option') ?: get_option('admin_email');

    $tresc = sprintf(
        "Nowa wiadomość z formularza kontaktowego mikroloty.com\n\n".
        "Imię i nazwisko: %s\nE-mail: %s\nTemat: %s\n\nWiadomość:\n%s\n",
        $imie, $email, $temat ?: '—', $wiadomosc
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: '.$imie.' <'.$email.'>',
    ];

    $wyslano = wp_mail($do, '[mikroloty.com] '.($temat ?: 'Wiadomość z formularza'), $tresc, $headers);

    wp_safe_redirect(add_query_arg('kontakt', $wyslano ? 'ok' : 'blad', $referer));
    exit;
}
