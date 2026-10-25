<?php
/**
 * Sets up the store the theme expects — WooCommerce settings, the catalog, cart/checkout pages, payment and shipping.
 * Same catalog as the Shopify store: dewstick tinted lip balm (five shades, $22 CAD) and the set of five ($98, was $110).
 * Safe to run again: it skips anything that already exists. The blueprint runs it on every start.
 */
defined( 'ABSPATH' ) || exit;

const DEWSTICK_DESC = array(
	'dewstick-tinted-lip-balm' => array(
		'en' => '<p><strong>half lipstick. half lip balm.</strong></p><p>A luxury lip mask, in a stick — real colour over squalane, shea butter and ceramides.</p><ul><li><strong>Hydrate</strong> — squalane for deep hydration</li><li><strong>Nourish</strong> — shea butter, soft &amp; smooth</li><li><strong>Protect</strong> — ceramides to strengthen &amp; protect</li><li><strong>Glow</strong> — one swipe, juicy glow</li></ul><p>Twist-up bullet, 4 g / 0.14 oz.</p>',
		'zh' => '<p><strong>半支口红，半支润唇膏。</strong></p><p>一支棒状的奢华唇膜 —— 真实色彩，融合角鲨烷、乳木果油与神经酰胺。</p><ul><li><strong>保湿</strong> —— 角鲨烷深层保湿</li><li><strong>滋养</strong> —— 乳木果油，柔滑顺润</li><li><strong>修护</strong> —— 神经酰胺强韧修护</li><li><strong>光泽</strong> —— 轻轻一抹，水润光泽</li></ul><p>旋转式唇膏管，4 克 / 0.14 盎司。</p>',
	),
	'the-set-of-five'          => array(
		'en' => '<p><strong>All five shades in one box</strong> — one formula, every mood. Happy shades, happy days.</p><p>nectar (coral) · poppy (true red) · peony (rosy nude) · blush (soft pink) · orchid (berry rose).</p><p>$98 CAD — save $12 vs. singles.</p>',
		'zh' => '<p><strong>五个色号，一盒收齐</strong> —— 同一配方，适配每一种心情。快乐色号，快乐每一天。</p><p>nectar（珊瑚色）· poppy（正红色）· peony（玫瑰裸色）· blush（柔粉色）· orchid（莓果玫瑰）。</p><p>$98 加元 —— 比单支省 $12。</p>',
	),
);

/** Copy a theme photo into the media library once; returns the attachment ID. */
function dewstick_seed_image( $name ) {
	$found = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'inherit',
			'meta_key'    => '_dewstick_src',
			'meta_value'  => $name,
			'fields'      => 'ids',
			'numberposts' => 1,
		)
	);
	if ( $found ) {
		return $found[0];
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	// A product photo by its number or shade ("09", "poppy"), or any other theme photo by file name.
	$file   = str_contains( $name, '.' ) ? $name : "dewstick-$name-1000.jpg";
	$upload = wp_upload_bits( $file, null, file_get_contents( get_template_directory() . "/assets/img/$file" ) );
	if ( $upload['error'] ) {
		return 0;
	}
	$id = wp_insert_attachment(
		array(
			'post_title'     => "dewstick $name",
			'post_mime_type' => 'image/jpeg',
			'post_status'    => 'inherit',
		),
		$upload['file']
	);
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	update_post_meta( $id, '_wp_attachment_image_alt', DEWSTICK_ALT[ $name ] ?? '' );
	update_post_meta( $id, '_dewstick_src', $name );
	return $id;
}

function dewstick_seed_settings() {
	update_option( 'blogname', 'dewstick' );
	update_option( 'blogdescription', 'half lipstick. half lip balm.' );
	update_option( 'woocommerce_currency', 'CAD' );
	update_option( 'woocommerce_default_country', 'CA:ON' );
	update_option( 'woocommerce_price_num_decimals', 0 );
	update_option( 'woocommerce_calc_taxes', 'no' );
	update_option( 'woocommerce_cart_redirect_after_add', 'yes' );
	update_option( 'woocommerce_enable_ajax_add_to_cart', 'no' );
	update_option( 'woocommerce_coming_soon', 'no' );
	update_option( 'woocommerce_store_pages_only', 'no' );
	update_option( 'woocommerce_onboarding_profile', array( 'skipped' => true ) );
	update_option(
		'woocommerce_permalinks',
		array(
			'product_base'           => 'products',
			'category_base'          => 'collections',
			'tag_base'               => 'product-tag',
			'attribute_base'         => '',
			'use_verbose_page_rules' => false,
		)
	);

	// Test payment: cash on delivery stands in for Shopify's test gateway, so orders go through without money moving.
	update_option(
		'woocommerce_cod_settings',
		array(
			'enabled'            => 'yes',
			'title'              => 'Test payment (no charge)',
			'description'        => 'This is a demo store — placing an order does not charge anything.',
			'instructions'       => 'Demo order — nothing was charged.',
			'enable_for_methods' => array(),
			'enable_for_virtual' => 'yes',
		)
	);

	// Free shipping everywhere ("Locations not covered by your other zones").
	$rest = new WC_Shipping_Zone( 0 );
	if ( ! $rest->get_shipping_methods() ) {
		$rest->add_shipping_method( 'free_shipping' );
	}

	// Classic cart and checkout: the theme overrides the cart template, and styles the checkout form.
	if ( wc_get_page_id( 'cart' ) < 1 || wc_get_page_id( 'checkout' ) < 1 ) {
		WC_Install::create_pages();
	}
	foreach ( array( 'cart' => 'woocommerce_cart', 'checkout' => 'woocommerce_checkout' ) as $page => $shortcode ) {
		$id = wc_get_page_id( $page );
		if ( $id > 0 && ! has_shortcode( get_post_field( 'post_content', $id ), $shortcode ) ) {
			wp_update_post(
				array(
					'ID'           => $id,
					'post_content' => "<!-- wp:shortcode -->[$shortcode]<!-- /wp:shortcode -->",
				)
			);
		}
	}

	// Drop the sample post and page WordPress installs with.
	foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
		$post = get_page_by_path( $slug, OBJECT, $type );
		if ( $post ) {
			wp_delete_post( $post->ID, true );
		}
	}
}

function dewstick_seed_catalog() {
	$term = term_exists( 'shades', 'product_cat' ) ?: wp_insert_term( 'shades', 'product_cat', array( 'slug' => 'shades' ) );
	$cat  = (int) ( is_array( $term ) ? $term['term_id'] : $term );

	if ( ! get_page_by_path( 'dewstick-tinted-lip-balm', OBJECT, 'product' ) ) {
		$balm = new WC_Product_Variable();
		$balm->set_name( 'dewstick tinted lip balm' );
		$balm->set_slug( 'dewstick-tinted-lip-balm' );
		$balm->set_description( DEWSTICK_DESC['dewstick-tinted-lip-balm']['en'] );
		$balm->set_short_description( 'A luxury lip mask, in a stick — real colour over squalane, shea butter and ceramides.' );
		$balm->set_category_ids( array( $cat ) );
		$balm->set_image_id( dewstick_seed_image( 'nectar' ) );
		$balm->set_gallery_image_ids( array_map( 'dewstick_seed_image', array( 'poppy', 'peony', 'blush', 'orchid', '11', '06', '12' ) ) );
		$shade = new WC_Product_Attribute();
		$shade->set_name( 'Shade' );
		$shade->set_options( DEWSTICK_SHADES );
		$shade->set_visible( true );
		$shade->set_variation( true );
		$balm->set_attributes( array( $shade ) );
		$balm_id = $balm->save();

		foreach ( DEWSTICK_SHADES as $name ) {
			$v = new WC_Product_Variation();
			$v->set_parent_id( $balm_id );
			$v->set_attributes( array( 'shade' => $name ) );
			$v->set_regular_price( '22' );
			$v->set_sku( "DEW-$name" );
			$v->set_image_id( dewstick_seed_image( $name ) );
			$v->save();
		}
		WC_Product_Variable::sync( $balm_id );
		update_post_meta( $balm_id, '_dewstick_title_zh', 'dewstick 有色润唇膏' );
		update_post_meta( $balm_id, '_dewstick_desc_zh', DEWSTICK_DESC['dewstick-tinted-lip-balm']['zh'] );
	}

	if ( ! get_page_by_path( 'the-set-of-five', OBJECT, 'product' ) ) {
		$set = new WC_Product_Simple();
		$set->set_name( 'the set of five' );
		$set->set_slug( 'the-set-of-five' );
		$set->set_description( DEWSTICK_DESC['the-set-of-five']['en'] );
		$set->set_short_description( 'All five shades in one box — one formula, every mood.' );
		$set->set_regular_price( '110' );
		$set->set_sale_price( '98' );
		$set->set_sku( 'DEW-SET5' );
		$set->set_category_ids( array( $cat ) );
		$set->set_image_id( dewstick_seed_image( '04' ) );
		$set->set_gallery_image_ids( array_map( 'dewstick_seed_image', array( '08', '10' ) ) );
		$set_id = $set->save();
		update_post_meta( $set_id, '_dewstick_title_zh', '五色套装' );
		update_post_meta( $set_id, '_dewstick_desc_zh', DEWSTICK_DESC['the-set-of-five']['zh'] );
	}
}

function dewstick_seed() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return 'WooCommerce is not active.';
	}
	dewstick_seed_settings();
	dewstick_seed_catalog();
	$builders = dewstick_seed_builder_pages();
	// WooCommerce registered its post types with the old bases this request, so let the next request rebuild the rules.
	global $wp_rewrite;
	$wp_rewrite->set_permalink_structure( '/%postname%/' );
	delete_option( 'rewrite_rules' );
	return 'dewstick store ready.' . ( $builders ? ' Builder pages: ' . implode( ', ', $builders ) . '.' : '' );
}
