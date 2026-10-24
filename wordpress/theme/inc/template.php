<?php
/**
 * Template helpers — the same building blocks as scripts/build_shop.py (img, well, swatch, product cards).
 */
defined( 'ABSPATH' ) || exit;

const DEWSTICK_SWATCH = array(
	'nectar' => '#f3b52f',
	'poppy'  => '#e2392f',
	'peony'  => '#f4a9bd',
	'blush'  => '#b6c935',
	'orchid' => '#8dbde6',
);

/* Store photo per shade (shop/img/dewstick-<shade>-*.jpg). */
const DEWSTICK_SHADES = array( 'nectar', 'poppy', 'peony', 'blush', 'orchid' );

const DEWSTICK_ALT = array(
	'01'     => 'dewstick in poppy — red tube beside its box, with squalane, shea butter and ceramides',
	'02'     => 'dewstick in peony — pink tube over a swatch: half lipstick, half lip balm',
	'03'     => 'dewstick in orchid — blue tube beside squalane, shea butter and ceramide swatches',
	'04'     => 'The set of five in its red gift box: happy shades, happy days',
	'05'     => 'dewstick in blush — green tube beside squalane, shea butter and ceramide swatches',
	'06'     => 'A red dewstick over balm swatches: juicy, glossy, balmy',
	'07'     => 'Get ready with me: applying dewstick — hydrating, tinted, effortless',
	'08'     => 'Unboxing a dewstick from its shipping box',
	'09'     => 'Macro of a dewy red bullet: the sound, the texture, the dew',
	'10'     => 'A pile of dewsticks in every colourway',
	'11'     => 'All five dewstick shades lined up with their swatches',
	'12'     => 'Close-up of glossy tinted lips with a dewstick',
	'13'     => 'dewstick in nectar — a luxury lip mask in a stick: hydrate, nourish, protect, glow',
	'nectar' => 'dewstick in nectar — yellow tube with a red bullet',
	'poppy'  => 'dewstick in poppy — red tube with a red bullet',
	'peony'  => 'dewstick in peony — pink tube with a berry bullet',
	'blush'  => 'dewstick in blush — green tube with a lime bullet',
	'orchid' => 'dewstick in orchid — blue tube with a blue bullet',
);

const DEWSTICK_CHECK = '<svg viewBox="0 0 10 10" aria-hidden="true"><path d="M1.5 5.2 4 7.6 8.6 2.4"/></svg>';

function dewstick_asset( $path ) {
	return get_template_directory_uri() . '/assets/' . $path;
}

/** A store photo. $name is "01"…"13", a shade, or another file in assets/img (with extension). Store photos get a 500/1000 srcset. */
function dewstick_img( $name, $alt = null, $sizes = '100vw', $cls = '', $loading = 'lazy' ) {
	$class = $cls ? ' class="' . esc_attr( $cls ) . '"' : '';
	if ( str_contains( $name, '.' ) ) {
		$src    = dewstick_asset( "img/$name" );
		$srcset = '';
	} else {
		$src    = dewstick_asset( "img/dewstick-$name-1000.jpg" );
		$srcset = ' srcset="' . esc_url( dewstick_asset( "img/dewstick-$name-500.jpg" ) ) . ' 500w, ' . esc_url( $src ) . ' 1000w" sizes="' . esc_attr( $sizes ) . '"';
	}
	$alt = $alt ?? ( DEWSTICK_ALT[ $name ] ?? '' );
	return '<img' . $class . ' src="' . esc_url( $src ) . '"' . $srcset . ' loading="' . esc_attr( $loading ) . '" alt="' . esc_attr( $alt ) . '">';
}

function dewstick_swatch( $shade ) {
	return '<span class="swatch-dot" style="background: ' . esc_attr( DEWSTICK_SWATCH[ $shade ] ?? '#e4e2dd' ) . '" aria-hidden="true"></span>';
}

function dewstick_money( $amount ) {
	return wc_price( $amount );
}

/* ---------- links ---------- */
function dewstick_product( $slug ) {
	$post = get_page_by_path( $slug, OBJECT, 'product' );
	return $post ? wc_get_product( $post ) : null;
}

function dewstick_product_url( $slug, $shade = null ) {
	$product = dewstick_product( $slug );
	$url     = $product ? get_permalink( $product->get_id() ) : home_url( '/' );
	return $shade ? add_query_arg( 'attribute_shade', $shade, $url ) : $url;
}

function dewstick_shop_url() {
	$link = get_term_link( 'shades', 'product_cat' );
	return is_wp_error( $link ) ? wc_get_page_permalink( 'shop' ) : $link;
}

function dewstick_home_url( $hash = '' ) {
	return home_url( '/' ) . $hash;
}

/* ---------- product cards ---------- */
function dewstick_product_card( $title_html, $href, $image, $price_html, $desc = '', $badge = '', $alt = '' ) {
	ob_start();
	?>
	<a class="pcard" href="<?php echo esc_url( $href ); ?>">
		<div class="pcard__media"><?php echo dewstick_img( $image, $alt, '(min-width: 768px) 25vw, 50vw' ); ?><?php echo $badge ? '<span class="pcard__badge">' . esc_html( $badge ) . '</span>' : ''; ?></div>
		<h3 class="pcard__title t-xl"><?php echo $title_html; ?></h3>
		<p class="pcard__price"><?php echo $price_html; ?></p>
		<?php if ( $desc ) : ?>
			<p class="pcard__desc"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
	</a>
	<?php
	return ob_get_clean();
}

function dewstick_shade_card( $shade ) {
	$balm  = dewstick_product( 'dewstick-tinted-lip-balm' );
	$price = $balm ? $balm->get_variation_price( 'min' ) : 22;
	return dewstick_product_card(
		dewstick_swatch( $shade ) . esc_html( $shade ) . ' <span class="faint">/ ' . dewstick_t( "shades.$shade.tone" ) . '</span>',
		dewstick_product_url( 'dewstick-tinted-lip-balm', $shade ),
		$shade,
		dewstick_money( $price ),
		dewstick_t_raw( "shades.$shade.line" ),
		'',
		DEWSTICK_ALT[ $shade ]
	);
}

function dewstick_set_card() {
	$set = dewstick_product( 'the-set-of-five' );
	if ( ! $set ) {
		return '';
	}
	$regular = (float) $set->get_regular_price();
	$price   = (float) $set->get_price();
	$compare = $regular > $price ? ' <span class="faint pcard__compare">' . dewstick_money( $regular ) . '</span>' : '';
	$badge   = $regular > $price ? dewstick_t_raw( 'home.badge_save', array( 'amount' => '$' . ( $regular - $price ) ) ) : '';
	return dewstick_product_card(
		esc_html( dewstick_product_title( $set ) ),
		get_permalink( $set->get_id() ),
		'04',
		dewstick_money( $price ) . $compare,
		dewstick_t_raw( 'set.body' ),
		$badge,
		DEWSTICK_ALT['04']
	);
}

function dewstick_lang_switch() {
	$out = '<div class="lang-switch u-mini">';
	foreach ( array( 'en' => 'EN', 'zh' => '中文' ) as $lang => $label ) {
		if ( 'zh' === $lang ) {
			$out .= '<span class="sep" aria-hidden="true">/</span>';
		}
		$out .= sprintf(
			'<a href="%s" lang="%s" aria-current="%s">%s</a>',
			esc_url( dewstick_lang_url( $lang ) ),
			'zh' === $lang ? 'zh-CN' : 'en',
			dewstick_lang() === $lang ? 'true' : 'false',
			esc_html( $label )
		);
	}
	return $out . '</div>';
}

/* "Why it works" split block — product page. */
function dewstick_why_split( $image ) {
	$icons = array(
		'hydrate' => '<path d="M12 3c3 4 5 6.5 5 9.5a5 5 0 0 1-10 0C7 9.5 9 7 12 3Z"/>',
		'nourish' => '<path d="M5 19c0-8 5-13 14-14-1 9-6 14-14 14Z"/><path d="M5 19 13 11"/>',
		'protect' => '<path d="M12 3 5 6v5c0 4.5 3 8.5 7 10 4-1.5 7-5.5 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/>',
		'glow'    => '<path d="M12 21s-7-4.35-7-10a4 4 0 0 1 7-2.65A4 4 0 0 1 19 11c0 5.65-7 10-7 10Z"/>',
	);
	ob_start();
	?>
	<section id="why" class="border-top">
		<div class="split split--reverse">
			<div class="split__media"><div class="well well--square"><?php echo dewstick_img( $image, null, '(min-width: 768px) 50vw, 100vw' ); ?></div></div>
			<div class="split__copy">
				<h2 class="h2"><?php dewstick_e( 'why.title' ); ?></h2>
				<p class="lede mt-2" style="max-width: 28rem;"><?php dewstick_e( 'why.body' ); ?></p>
				<div class="benefits">
					<?php foreach ( $icons as $k => $svg ) : ?>
						<div class="benefit"><svg viewBox="0 0 24 24" aria-hidden="true"><?php echo $svg; ?></svg>
							<h3><?php dewstick_e( "why.$k.title" ); ?></h3><p class="small muted"><?php dewstick_e( "why.$k.body" ); ?></p></div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
