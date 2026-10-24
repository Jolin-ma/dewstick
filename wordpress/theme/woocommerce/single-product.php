<?php
/**
 * Product page: gallery + thumbs, shade picker, quantity, add to cart. The form posts to WooCommerce's own
 * add-to-cart handler (add-to-cart / variation_id / attribute_shade), which then sends the shopper to the cart.
 */
defined( 'ABSPATH' ) || exit;
get_header();

global $product;
$product = wc_get_product( get_queried_object_id() );
if ( ! $product ) {
	get_footer();
	return;
}

$dewstick_has_shades = $product->is_type( 'variable' );
$dewstick_media      = array_values( array_filter( array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() ) ) );
$dewstick_variants   = array();
$dewstick_wanted     = sanitize_key( $_GET['attribute_shade'] ?? '' );
$dewstick_first      = null;

if ( $dewstick_has_shades ) {
	foreach ( $product->get_children() as $dewstick_vid ) {
		$dewstick_v     = wc_get_product( $dewstick_vid );
		$dewstick_shade = $dewstick_v->get_attribute( 'shade' );
		$dewstick_variants[ $dewstick_shade ] = array(
			'id'    => $dewstick_vid,
			'media' => (string) $dewstick_v->get_image_id(),
			'stock' => $dewstick_v->is_in_stock(),
		);
	}
	$dewstick_first = isset( $dewstick_variants[ $dewstick_wanted ] ) ? $dewstick_wanted : array_key_first( $dewstick_variants );
}
$dewstick_shown = $dewstick_first ? $dewstick_variants[ $dewstick_first ]['media'] : (string) ( $dewstick_media[0] ?? '' );
$dewstick_type  = $dewstick_has_shades ? 'product.type_balm' : 'product.type_set';
$dewstick_reg   = (float) $product->get_regular_price();
$dewstick_price = $dewstick_has_shades ? (float) $product->get_variation_price( 'min' ) : (float) $product->get_price();
?>
<section class="section" data-product style="margin-top: 3rem;">
	<div class="container">
		<?php woocommerce_output_all_notices(); ?>
	</div>
	<div class="container product">
		<div class="product__gallery">
			<?php foreach ( $dewstick_media as $dewstick_id ) : ?>
				<div data-media-id="<?php echo esc_attr( $dewstick_id ); ?>"<?php echo (string) $dewstick_id === $dewstick_shown ? '' : ' hidden'; ?>>
					<div class="well well--square"><?php echo wp_get_attachment_image( $dewstick_id, 'large', false, array( 'sizes' => '(min-width: 900px) 55vw, 100vw', 'loading' => 'eager' ) ); ?></div>
				</div>
			<?php endforeach; ?>
			<div class="product__thumbs">
				<?php foreach ( $dewstick_media as $dewstick_id ) : ?>
					<button type="button" data-thumb="<?php echo esc_attr( $dewstick_id ); ?>" aria-label="<?php echo esc_attr( get_post_meta( $dewstick_id, '_wp_attachment_image_alt', true ) ); ?>" aria-current="<?php echo (string) $dewstick_id === $dewstick_shown ? 'true' : 'false'; ?>">
						<?php echo wp_get_attachment_image( $dewstick_id, 'woocommerce_thumbnail', false, array( 'alt' => '' ) ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="product__info">
			<p class="u-mini muted"><?php dewstick_e( $dewstick_type ); ?></p>
			<h1 class="h2 mt-1" style="text-transform: none;"><?php echo esc_html( dewstick_product_title( $product ) ); ?></h1>
			<p class="product__price"><span><?php echo dewstick_money( $dewstick_price ); ?></span>
				<?php if ( ! $dewstick_has_shades && $dewstick_reg > $dewstick_price ) : ?>
					<span class="faint pcard__compare"><?php echo dewstick_money( $dewstick_reg ); ?></span>
				<?php endif; ?>
			</p>
			<form id="ProductForm" method="post" action="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
				<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
				<?php if ( $dewstick_has_shades ) : ?>
					<input type="hidden" name="variation_id" value="<?php echo esc_attr( $dewstick_variants[ $dewstick_first ]['id'] ); ?>" data-variation-id>
					<fieldset class="shade-picker">
						<legend class="u-mini"><?php dewstick_e( 'product.shade' ); ?></legend>
						<div class="shade-picker__options">
							<?php
							$dewstick_i = 0;
							foreach ( $dewstick_variants as $dewstick_shade => $dewstick_v ) :
								++$dewstick_i;
								?>
								<input type="radio" id="shade-<?php echo (int) $dewstick_i; ?>" name="attribute_shade" value="<?php echo esc_attr( $dewstick_shade ); ?>" data-option data-variation="<?php echo esc_attr( $dewstick_v['id'] ); ?>" data-media="<?php echo esc_attr( $dewstick_v['media'] ); ?>"<?php checked( $dewstick_shade, $dewstick_first ); ?>>
								<label for="shade-<?php echo (int) $dewstick_i; ?>"><?php echo dewstick_swatch( $dewstick_shade ); ?><?php echo esc_html( $dewstick_shade ); ?><span class="faint">&nbsp;/ <?php dewstick_e( "shades.$dewstick_shade.tone" ); ?></span></label>
							<?php endforeach; ?>
						</div>
					</fieldset>
				<?php endif; ?>
				<div class="buy-row">
					<div class="qty" data-qty>
						<button type="button" data-step="-1" aria-label="<?php echo esc_attr( dewstick_t_raw( 'product.qty_down' ) ); ?>">−</button>
						<label class="visually-hidden" for="Quantity"><?php dewstick_e( 'product.quantity' ); ?></label>
						<input id="Quantity" type="number" name="quantity" value="1" min="1" inputmode="numeric">
						<button type="button" data-step="1" aria-label="<?php echo esc_attr( dewstick_t_raw( 'product.qty_up' ) ); ?>">+</button>
					</div>
					<button type="submit" class="btn"<?php disabled( ! $product->is_in_stock() ); ?>><span><?php dewstick_e( $product->is_in_stock() ? 'product.add_to_cart' : 'product.sold_out' ); ?></span></button>
				</div>
			</form>
			<div class="product__desc rte"><?php echo wp_kses_post( dewstick_product_description( $product ) ); ?></div>
		</div>
	</div>
</section>

<?php if ( $dewstick_has_shades ) : ?>
	<section class="border-top">
		<div class="split split--mist">
			<div class="split__media"><div class="well well--square"><?php echo dewstick_img( '04', null, '(min-width: 768px) 50vw, 100vw' ); ?></div></div>
			<div class="split__copy">
				<h2 class="h2"><?php dewstick_e( 'set.title' ); ?></h2>
				<p class="lede mt-2" style="max-width: 26rem;"><?php dewstick_e( 'set.body' ); ?></p>
				<a class="link-cta mt-3" style="align-self: flex-start;" href="<?php echo esc_url( dewstick_product_url( 'the-set-of-five' ) ); ?>"><?php dewstick_e( 'set.details' ); ?></a>
			</div>
		</div>
	</section>
	<?php echo dewstick_why_split( '05' ); ?>
<?php endif; ?>
<?php
get_footer();
