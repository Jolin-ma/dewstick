<?php
defined( 'ABSPATH' ) || exit;
$dewstick_shop  = dewstick_shop_url();
$dewstick_set   = dewstick_product_url( 'the-set-of-five' );
$dewstick_cart  = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
$dewstick_count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$dewstick_label = dewstick_t_raw( 1 === $dewstick_count ? 'general.cart_count.one' : 'general.cart_count.other', array( 'count' => $dewstick_count ) );
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php dewstick_e( 'general.skip' ); ?></a>
<div class="announcement"><p><a href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'announcement.text' ); ?></a></p></div>
<header class="site-header">
	<div class="g12 site-header__bar">
		<a href="<?php echo esc_url( dewstick_home_url() ); ?>" class="wordmark" aria-label="<?php echo esc_attr( dewstick_t_raw( 'general.home' ) ); ?>">dewstick</a>
		<nav class="site-header__nav u-caps" aria-label="<?php echo esc_attr( dewstick_t_raw( 'general.nav' ) ); ?>">
			<a href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'nav.shop' ); ?></a>
			<a href="<?php echo esc_url( $dewstick_set ); ?>"><?php dewstick_e( 'set.title' ); ?></a>
			<a href="<?php echo esc_url( dewstick_home_url( '#why' ) ); ?>"><?php dewstick_e( 'nav.ingredients' ); ?></a>
			<a href="<?php echo esc_url( dewstick_home_url( '#note' ) ); ?>"><?php dewstick_e( 'nav.idea' ); ?></a>
		</nav>
		<div class="site-header__tools u-caps">
			<span class="hide-mobile"><?php echo dewstick_lang_switch(); ?></span>
			<a href="<?php echo esc_url( $dewstick_cart ); ?>" class="cart-link" aria-label="<?php echo esc_attr( $dewstick_label ); ?>">
				<span class="hide-mobile"><?php dewstick_e( 'general.cart' ); ?></span>
				<svg class="only-mobile" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><path d="M5 8h14l-1 12H6L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/></svg>
				<span class="cart-count"<?php echo $dewstick_count ? '' : ' hidden'; ?>><?php echo (int) $dewstick_count; ?></span>
			</a>
			<button id="menuBtn" class="menu-toggle" aria-label="<?php echo esc_attr( dewstick_t_raw( 'general.menu' ) ); ?>" aria-expanded="false" aria-controls="menuPanel">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3" aria-hidden="true"><path d="M3 7h18M3 12h18M3 17h18"/></svg>
			</button>
		</div>
	</div>
	<nav id="menuPanel" class="mobile-nav" hidden aria-label="<?php echo esc_attr( dewstick_t_raw( 'general.nav' ) ); ?>">
		<a href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'nav.shop' ); ?></a>
		<a href="<?php echo esc_url( $dewstick_set ); ?>"><?php dewstick_e( 'set.title' ); ?></a>
		<a href="<?php echo esc_url( dewstick_home_url( '#why' ) ); ?>"><?php dewstick_e( 'nav.ingredients' ); ?></a>
		<a href="<?php echo esc_url( dewstick_home_url( '#note' ) ); ?>"><?php dewstick_e( 'nav.idea' ); ?></a>
		<a href="<?php echo esc_url( $dewstick_cart ); ?>"><?php dewstick_e( 'general.cart' ); ?> (<?php echo (int) $dewstick_count; ?>)</a>
		<div class="u-caps"><?php echo dewstick_lang_switch(); ?></div>
	</nav>
</header>
<main id="main" tabindex="-1">
