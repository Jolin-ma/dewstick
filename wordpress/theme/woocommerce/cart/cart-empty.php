<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="page-narrow" data-cart>
	<h1 class="h2"><?php dewstick_e( 'cart.title' ); ?></h1>
	<p class="mt-2 muted"><?php dewstick_e( 'cart.empty' ); ?></p>
	<a class="link-cta mt-3" href="<?php echo esc_url( dewstick_shop_url() ); ?>"><?php dewstick_e( 'cart.continue' ); ?></a>
</div>
