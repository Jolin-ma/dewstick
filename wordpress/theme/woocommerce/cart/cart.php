<?php
/**
 * Cart — the static site's cart layout on WooCommerce's cart. Quantity changes post the form (update_cart);
 * "Check out" goes to WooCommerce checkout.
 */
defined( 'ABSPATH' ) || exit;
?>
<div class="page-narrow" data-cart>
	<h1 class="h2"><?php dewstick_e( 'cart.title' ); ?></h1>
	<?php do_action( 'woocommerce_before_cart' ); ?>
	<form class="mt-3" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" data-cart-form>
		<?php
		$dewstick_i = 0;
		foreach ( WC()->cart->get_cart() as $dewstick_key => $dewstick_item ) :
			$dewstick_p = $dewstick_item['data'];
			if ( ! $dewstick_p || ! $dewstick_p->exists() || $dewstick_item['quantity'] < 1 ) {
				continue;
			}
			++$dewstick_i;
			$dewstick_url   = $dewstick_p->get_permalink( $dewstick_item );
			$dewstick_shade = $dewstick_p->is_type( 'variation' ) ? $dewstick_p->get_attribute( 'shade' ) : '';
			?>
			<div class="cart-line">
				<a href="<?php echo esc_url( $dewstick_url ); ?>"><div class="well well--square"><?php echo $dewstick_p->get_image( 'woocommerce_thumbnail', array( 'alt' => '' ) ); ?></div></a>
				<div>
					<a href="<?php echo esc_url( $dewstick_url ); ?>" class="medium"><?php echo esc_html( dewstick_product_title( $dewstick_p ) ); ?></a>
					<?php if ( $dewstick_shade ) : ?>
						<p class="small muted"><?php echo dewstick_swatch( $dewstick_shade ); ?><?php echo esc_html( $dewstick_shade ); ?></p>
					<?php endif; ?>
					<div class="qty mt-1" data-qty>
						<button type="button" data-step="-1" aria-label="<?php echo esc_attr( dewstick_t_raw( 'product.qty_down' ) ); ?>">−</button>
						<label class="visually-hidden" for="Qty-<?php echo (int) $dewstick_i; ?>"><?php dewstick_e( 'product.quantity' ); ?></label>
						<input id="Qty-<?php echo (int) $dewstick_i; ?>" type="number" name="cart[<?php echo esc_attr( $dewstick_key ); ?>][qty]" value="<?php echo (int) $dewstick_item['quantity']; ?>" min="0" inputmode="numeric" data-cart-qty>
						<button type="button" data-step="1" aria-label="<?php echo esc_attr( dewstick_t_raw( 'product.qty_up' ) ); ?>">+</button>
					</div>
					<div><a class="cart-line__remove muted" href="<?php echo esc_url( wc_get_cart_remove_url( $dewstick_key ) ); ?>"><?php dewstick_e( 'cart.remove' ); ?></a></div>
				</div>
				<p class="small"><?php echo WC()->cart->get_product_subtotal( $dewstick_p, $dewstick_item['quantity'] ); ?></p>
			</div>
		<?php endforeach; ?>
		<button type="submit" name="update_cart" value="1" class="link-u mt-2" data-update-cart><?php dewstick_e( 'cart.update' ); ?></button>
		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
	</form>
	<div class="cart-footer">
		<p><span class="u-mini muted"><?php dewstick_e( 'cart.subtotal' ); ?></span>&nbsp; <span class="medium"><?php echo WC()->cart->get_cart_subtotal(); ?></span></p>
		<p class="small muted"><?php dewstick_e( 'cart.taxes' ); ?></p>
		<a class="btn" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php dewstick_e( 'cart.checkout' ); ?></a>
	</div>
	<?php do_action( 'woocommerce_after_cart' ); ?>
</div>
