<?php
/**
 * "Stay in the dew" signups. Stored in the dewstick_subscribers option and listed under Tools → Newsletter.
 */
defined( 'ABSPATH' ) || exit;

function dewstick_newsletter_subscribe() {
	check_admin_referer( 'dewstick_newsletter' );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$back  = wp_get_referer() ?: home_url( '/' );
	if ( ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'newsletter', 'invalid', $back ) . '#news-title' );
		exit;
	}
	$subs = get_option( 'dewstick_subscribers', array() );
	if ( ! isset( $subs[ $email ] ) ) {
		$subs[ $email ] = array(
			'date' => current_time( 'mysql' ),
			'lang' => dewstick_lang(),
		);
		update_option( 'dewstick_subscribers', $subs, false );
	}
	wp_safe_redirect( add_query_arg( 'newsletter', 'ok', $back ) . '#news-title' );
	exit;
}
add_action( 'admin_post_nopriv_dewstick_newsletter', 'dewstick_newsletter_subscribe' );
add_action( 'admin_post_dewstick_newsletter', 'dewstick_newsletter_subscribe' );

add_action(
	'admin_menu',
	function () {
		add_management_page(
			'Newsletter',
			'Newsletter',
			'manage_options',
			'dewstick-newsletter',
			function () {
				$subs = get_option( 'dewstick_subscribers', array() );
				echo '<div class="wrap"><h1>Newsletter signups</h1>';
				if ( ! $subs ) {
					echo '<p>No signups yet.</p></div>';
					return;
				}
				echo '<table class="widefat striped"><thead><tr><th>Email</th><th>Language</th><th>Date</th></tr></thead><tbody>';
				foreach ( $subs as $email => $sub ) {
					printf( '<tr><td>%s</td><td>%s</td><td>%s</td></tr>', esc_html( $email ), esc_html( $sub['lang'] ), esc_html( $sub['date'] ) );
				}
				echo '</tbody></table></div>';
			}
		);
	}
);

function dewstick_newsletter_form() {
	$state = sanitize_key( $_GET['newsletter'] ?? '' );
	if ( 'ok' === $state ) {
		return '<p class="small medium mt-3" role="status">' . dewstick_t( 'newsletter.success' ) . '</p>';
	}
	ob_start();
	?>
	<form class="js-newsletter" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
		<input type="hidden" name="action" value="dewstick_newsletter">
		<?php wp_nonce_field( 'dewstick_newsletter' ); ?>
		<div class="field-row<?php echo 'invalid' === $state ? ' is-error' : ''; ?>">
			<label class="visually-hidden" for="Newsletter-email"><?php dewstick_e( 'newsletter.label' ); ?></label>
			<input id="Newsletter-email" type="email" name="email" inputmode="email" autocomplete="email" required placeholder="<?php echo esc_attr( dewstick_t_raw( 'newsletter.placeholder' ) ); ?>">
			<button type="submit" class="btn"><?php dewstick_e( 'newsletter.submit' ); ?></button>
		</div>
		<p class="form-note" data-note aria-live="polite"><?php echo 'invalid' === $state ? dewstick_t( 'newsletter.invalid' ) : ''; ?></p>
	</form>
	<?php
	return ob_get_clean();
}
