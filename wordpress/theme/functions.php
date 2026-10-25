<?php
/**
 * dewstick — the store as a classic WordPress theme on WooCommerce.
 * Pages: home (front-page.php), shop / collection (woocommerce/archive-product.php),
 * product (woocommerce/single-product.php), cart (woocommerce/cart/*), checkout (page.php + WooCommerce).
 */
defined( 'ABSPATH' ) || exit;

const DEWSTICK_VERSION = '1.0.0';
const DEWSTICK_SHOPIFY_URL = 'https://dewstick.myshopify.com';
const DEWSTICK_STATIC_URL = 'https://dewstick.vercel.app';
const DEWSTICK_GITHUB_URL = 'https://github.com/Jolin-ma/dewstick';

require get_template_directory() . '/inc/i18n.php';
require get_template_directory() . '/inc/template.php';
require get_template_directory() . '/inc/newsletter.php';
require get_template_directory() . '/inc/seed.php';
require get_template_directory() . '/inc/builders.php';

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( 'https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,500&family=Hanken+Grotesk:wght@400;500&display=swap', 'assets/css/editor.css' ) );
		// The brand palette in the block editor's colour pickers, Kadence Blocks' included.
		add_theme_support(
			'editor-color-palette',
			array(
				array( 'name' => 'Ink', 'slug' => 'ink', 'color' => '#1e1a17' ),
				array( 'name' => 'Linen', 'slug' => 'linen', 'color' => '#f4efe9' ),
				array( 'name' => 'Paper', 'slug' => 'paper', 'color' => '#ffffff' ),
				array( 'name' => 'Dew red', 'slug' => 'dew', 'color' => '#e94b3c' ),
				array( 'name' => 'Studio', 'slug' => 'studio', 'color' => '#f6f5f3' ),
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri = get_template_directory_uri();
		wp_enqueue_style( 'dewstick-fonts', 'https://fonts.googleapis.com/css2?family=Newsreader:opsz,wght@6..72,400;6..72,500&family=Hanken+Grotesk:wght@400;500&family=Noto+Sans+SC:wght@400;500&family=Bodoni+Moda:opsz,wght@6..96,700&family=Noto+Serif+SC:wght@400;700&display=swap', array(), null );
		wp_enqueue_style( 'dewstick', "$uri/assets/css/dewstick.css", array(), DEWSTICK_VERSION );
		wp_enqueue_style( 'dewstick-wp', "$uri/assets/css/wp.css", array( 'dewstick' ), DEWSTICK_VERSION );
		wp_enqueue_script( 'dewstick', "$uri/assets/js/dewstick.js", array(), DEWSTICK_VERSION, array( 'strategy' => 'defer' ) );
		wp_localize_script(
			'dewstick',
			'DEWSTICK',
			array( 'invalidEmail' => dewstick_t_raw( 'newsletter.invalid' ) )
		);

		// The theme draws its own shop, product and cart pages; WooCommerce's stylesheets are only kept for checkout and the account pages.
		if ( function_exists( 'is_checkout' ) && ! is_checkout() && ! is_account_page() ) {
			wp_dequeue_style( 'woocommerce-general' );
			wp_dequeue_style( 'woocommerce-layout' );
			wp_dequeue_style( 'woocommerce-smallscreen' );
		}
		// The cart form posts normally; WooCommerce's ajax cart script expects its own markup.
		wp_dequeue_script( 'wc-cart' );
		// Core block styles only where a page is laid out in blocks.
		if ( ! is_page_template( 'template-builder.php' ) ) {
			wp_dequeue_style( 'wp-block-library' );
		}
	},
	20
);

add_action(
	'wp_head',
	function () {
		echo '<meta name="theme-color" content="#f4efe9">' . "\n";
		echo '<meta name="description" content="' . esc_attr( dewstick_meta_description() ) . '">' . "\n";
		echo '<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 32 32\'%3E%3Crect width=\'32\' height=\'32\' rx=\'9\' fill=\'%23E94B3C\'/%3E%3Cpath d=\'M16 7c3 4 5 6.5 5 9.5a5 5 0 0 1-10 0C11 13.5 13 11 16 7Z\' fill=\'%23fff\'/%3E%3C/svg%3E">' . "\n";
	},
	1
);

/*
 * Meta description per page: a product's own copy (led by the shade's line when one is preselected),
 * the collection footnote on the shop and collection pages, the hero copy everywhere else.
 */
function dewstick_meta_description() {
	if ( function_exists( 'is_product' ) && is_product() ) {
		$zh      = 'zh' === dewstick_lang();
		$stop    = $zh ? '。' : '. ';
		$product = wc_get_product( get_queried_object_id() );
		$text    = wp_strip_all_tags( str_replace( array( '</p>', '</li>' ), $stop, dewstick_product_description( $product ) ) );
		$shade   = sanitize_key( $_GET['attribute_shade'] ?? '' );
		if ( in_array( $shade, DEWSTICK_SHADES, true ) ) {
			$text = dewstick_t_raw( "shades.$shade.line" ) . $stop . $text;
		}
		// Collapse doubled full stops ("days.." / "。。") and whitespace, then cut to what search results show.
		$text = trim( preg_replace( array( '/([.。])\s*[.。]/u', '/\s+/u' ), array( '$1', ' ' ), $text ) );
		$max  = $zh ? 80 : 160;
		return mb_strlen( $text ) > $max ? rtrim( mb_substr( $text, 0, $max - 1 ) ) . '…' : $text;
	}
	if ( function_exists( 'is_product_category' ) && ( is_product_category() || is_shop() ) ) {
		return dewstick_t_raw( 'shades.all_title' ) . ' — ' . dewstick_t_raw( 'shades.footnote' );
	}
	// Pages with their own excerpt (the page-builder pages) use it.
	if ( is_page() && has_excerpt() ) {
		return wp_strip_all_tags( get_the_excerpt() );
	}
	return dewstick_t_raw( 'hero.body' );
}

add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( is_front_page() ) {
			$parts = array( 'title' => 'dewstick — ' . wp_strip_all_tags( str_replace( '<br>', ' ', dewstick_t_raw( 'hero.title_html' ) ) ) );
		} elseif ( function_exists( 'is_product_category' ) && ( is_product_category() || is_shop() ) ) {
			$parts['title'] = dewstick_t_raw( 'collection.title' );
		} elseif ( function_exists( 'is_cart' ) && is_cart() ) {
			$parts['title'] = dewstick_t_raw( 'cart.title' );
		} elseif ( function_exists( 'is_product' ) && is_product() ) {
			$parts['title'] = dewstick_product_title( wc_get_product( get_queried_object_id() ) );
		}
		unset( $parts['tagline'] );
		return $parts;
	}
);
add_filter( 'document_title_separator', fn() => '—' );

/* Prices read "$22 CAD", like the other two builds. */
add_filter( 'woocommerce_price_format', fn() => '%1$s%2$s&nbsp;CAD' );

/* No WooCommerce breadcrumbs or sidebar; the templates lay out the page themselves. */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/* Adding to cart already lands on the cart, so skip WooCommerce's "added to your cart" notice. */
add_filter( 'wc_add_to_cart_message_html', '__return_empty_string' );
