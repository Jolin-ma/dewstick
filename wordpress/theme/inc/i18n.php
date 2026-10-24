<?php
/**
 * English / 中文. The copy is the Shopify theme's own language files (lang/en.json, lang/zh.json);
 * the language is picked with ?lang=en|zh and remembered in a cookie.
 */
defined( 'ABSPATH' ) || exit;

const DEWSTICK_LANGS = array( 'en', 'zh' );

/* Strings Shopify supplies itself, or that only this build needs. */
const DEWSTICK_EXTRA = array(
	'en' => array(
		'collection.title'   => 'all five shades',
		'collection.desc'    => 'Every shade $22 CAD. One formula, every mood.',
		'footer.project'     => 'This project',
		'footer.store'       => 'Live Shopify store',
		'footer.static'      => 'Static copy (Vercel)',
		'footer.source'      => 'Source on GitHub',
		'footer.copy_note'   => 'The WordPress + WooCommerce build of the dewstick store.',
		'newsletter.invalid' => 'Please enter a valid email.',
		'checkout.title'     => 'Check out',
		'product.type_balm'  => 'Tinted lip balm',
		'product.type_set'   => 'Gift set',
	),
	'zh' => array(
		'collection.title'   => '全部五色',
		'collection.desc'    => '每支 $22 加元。同一配方，适配每一种心情。',
		'footer.project'     => '关于本项目',
		'footer.store'       => 'Shopify 线上店铺',
		'footer.static'      => '静态副本（Vercel）',
		'footer.source'      => 'GitHub 源代码',
		'footer.copy_note'   => 'dewstick 店铺的 WordPress + WooCommerce 版本。',
		'newsletter.invalid' => '请输入有效的邮箱地址。',
		'checkout.title'     => '结账',
		'product.type_balm'  => '有色润唇膏',
		'product.type_set'   => '礼盒套装',
	),
);

/* Read ?lang= once per request and keep it in a cookie for the next pages. */
add_action(
	'init',
	function () {
		if ( isset( $_GET['lang'] ) && in_array( $_GET['lang'], DEWSTICK_LANGS, true ) ) {
			$lang = sanitize_key( $_GET['lang'] );
			setcookie( 'dewstick_lang', $lang, time() + YEAR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true );
			$_COOKIE['dewstick_lang'] = $lang;
		}
	},
	1
);

function dewstick_lang() {
	$lang = $_GET['lang'] ?? $_COOKIE['dewstick_lang'] ?? 'en';
	return in_array( $lang, DEWSTICK_LANGS, true ) ? $lang : 'en';
}

function dewstick_strings( $lang ) {
	static $cache = array();
	if ( ! isset( $cache[ $lang ] ) ) {
		$cache[ $lang ] = json_decode( file_get_contents( get_template_directory() . "/lang/$lang.json" ), true );
	}
	return $cache[ $lang ];
}

/** A string from the language files, unescaped. Keys are dotted ("home.shop_title"); {{ name }} fills from $vars. */
function dewstick_t_raw( $key, $vars = array(), $lang = null ) {
	$lang = $lang ?: dewstick_lang();
	if ( isset( DEWSTICK_EXTRA[ $lang ][ $key ] ) ) {
		$s = DEWSTICK_EXTRA[ $lang ][ $key ];
	} else {
		$s = dewstick_strings( $lang );
		foreach ( explode( '.', $key ) as $part ) {
			if ( ! is_array( $s ) || ! isset( $s[ $part ] ) ) {
				return $key;
			}
			$s = $s[ $part ];
		}
	}
	foreach ( $vars as $k => $v ) {
		$s = str_replace( '{{ ' . $k . ' }}', $v, $s );
	}
	return $s;
}

/** Escaped for HTML, except *_html keys, which carry their own markup. */
function dewstick_t( $key, $vars = array() ) {
	$s = dewstick_t_raw( $key, $vars );
	return str_ends_with( $key, '_html' ) ? wp_kses_post( $s ) : esc_html( $s );
}

function dewstick_e( $key, $vars = array() ) {
	echo dewstick_t( $key, $vars ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in dewstick_t()
}

/** The current URL in another language. */
function dewstick_lang_url( $lang ) {
	return add_query_arg( 'lang', $lang );
}

add_filter(
	'language_attributes',
	function ( $output ) {
		return preg_replace( '/lang="[^"]*"/', 'lang="' . ( 'zh' === dewstick_lang() ? 'zh-CN' : 'en' ) . '"', $output );
	}
);

/* Product names and descriptions in 中文 come from post meta set when the catalog is created (inc/seed.php). */
function dewstick_product_title( $product ) {
	$id = $product->get_parent_id() ?: $product->get_id();
	if ( 'zh' === dewstick_lang() && ( $zh = get_post_meta( $id, '_dewstick_title_zh', true ) ) ) {
		return $zh;
	}
	return get_the_title( $id );
}

function dewstick_product_description( $product ) {
	if ( 'zh' === dewstick_lang() && ( $zh = get_post_meta( $product->get_id(), '_dewstick_desc_zh', true ) ) ) {
		return $zh;
	}
	return $product->get_description();
}

if ( ! is_admin() ) {
	add_filter(
		'woocommerce_product_get_name',
		function ( $name, $product ) {
			return 'zh' === dewstick_lang() ? ( get_post_meta( $product->get_id(), '_dewstick_title_zh', true ) ?: $name ) : $name;
		},
		10,
		2
	);
	add_filter(
		'woocommerce_product_variation_get_name',
		function ( $name, $variation ) {
			if ( 'zh' !== dewstick_lang() ) {
				return $name;
			}
			$zh = get_post_meta( $variation->get_parent_id(), '_dewstick_title_zh', true );
			return $zh ? $zh . ' - ' . $variation->get_attribute( 'shade' ) : $name;
		},
		10,
		2
	);
}
