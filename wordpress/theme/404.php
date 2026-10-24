<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="section">
	<div class="container page-narrow center">
		<p class="u-mini muted">404</p>
		<h1 class="h2 mt-1"><?php dewstick_e( 'not_found.title' ); ?></h1>
		<a class="link-cta mt-3" href="<?php echo esc_url( dewstick_shop_url() ); ?>"><?php dewstick_e( 'cart.continue' ); ?></a>
	</div>
</section>
<?php
get_footer();
