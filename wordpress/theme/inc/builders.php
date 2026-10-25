<?php
/**
 * Two pages rebuilt in page builders, next to the hand-written PHP templates:
 * /ingredients/ in Elementor and /the-idea/ in Kadence Blocks. dewstick_seed() creates each one when its plugin is
 * active and skips it when the page already exists, so edits made in the builders survive later seeds.
 * Both pages are English only: builder content sits outside the theme's lang/*.json switch.
 */
defined( 'ABSPATH' ) || exit;

function dewstick_seed_builder_pages() {
	$done = array();
	if ( did_action( 'elementor/loaded' ) && ! get_page_by_path( 'ingredients' ) ) {
		dewstick_seed_elementor_kit();
		dewstick_seed_elementor_page();
		$done[] = 'Elementor';
	}
	if ( defined( 'KADENCE_BLOCKS_VERSION' ) && ! get_page_by_path( 'the-idea' ) ) {
		dewstick_seed_kadence_page();
		$done[] = 'Kadence';
	}
	return $done;
}

/* ---------- Elementor: /ingredients/ ---------- */

/** Site Settings → Global Colors and Global Fonts, so every widget on the page points at the brand tokens. */
function dewstick_seed_elementor_kit() {
	$kit = \Elementor\Plugin::$instance->kits_manager->get_active_kit();
	if ( ! $kit->get_id() ) {
		return;
	}
	$color = fn( $id, $title, $hex ) => array( '_id' => $id, 'title' => $title, 'color' => $hex );
	$font  = function ( $id, $title, $family, $rem, $rem_mobile, $line, $extra = array() ) {
		return array_merge(
			array(
				'_id'                           => $id,
				'title'                         => $title,
				'typography_typography'         => 'custom',
				'typography_font_family'        => $family,
				'typography_font_weight'        => '400',
				'typography_font_size'          => array( 'unit' => 'rem', 'size' => $rem ),
				'typography_font_size_mobile'   => array( 'unit' => 'rem', 'size' => $rem_mobile ),
				'typography_line_height'        => array( 'unit' => 'em', 'size' => $line ),
				'typography_letter_spacing'     => array( 'unit' => 'em', 'size' => 0 ),
			),
			$extra
		);
	};
	$sans_spacing = array( 'typography_letter_spacing' => array( 'unit' => 'em', 'size' => 0.02 ) );
	$kit->update_settings(
		array(
			'system_colors'      => array(
				$color( 'primary', 'Ink', '#1E1A17' ),
				$color( 'secondary', 'Linen', '#F4EFE9' ),
				$color( 'text', 'Ink', '#1E1A17' ),
				$color( 'accent', 'Dew red', '#E94B3C' ),
			),
			'custom_colors'      => array(
				$color( 'dw_paper', 'Paper', '#FFFFFF' ),
				$color( 'dw_studio', 'Studio', '#F6F5F3' ),
				$color( 'dw_muted', 'Ink 60%', '#1E1A1799' ),
			),
			'system_typography'  => array(
				$font( 'primary', 'Display (Newsreader)', 'Newsreader', 2, 1.625, 1.16 ),
				$font( 'secondary', 'Title (Newsreader)', 'Newsreader', 1.125, 1.125, 1.2 ),
				$font( 'text', 'Body (Hanken Grotesk)', 'Hanken Grotesk', 0.9375, 0.9375, 1.35, $sans_spacing ),
				$font( 'accent', 'Button (Newsreader)', 'Newsreader', 0.9375, 0.9375, 1 ),
			),
			'custom_typography'  => array(
				$font( 'dw_label', 'Label (caps)', 'Hanken Grotesk', 0.75, 0.75, 1.3, $sans_spacing + array( 'typography_text_transform' => 'uppercase' ) ),
				$font( 'dw_statement', 'Statement (Newsreader)', 'Newsreader', 2, 1.625, 1.16 ),
			),
			'container_width'    => array( 'unit' => '%', 'size' => 100 ),
			'container_padding'  => array( 'unit' => 'rem', 'top' => '0', 'right' => '1.25', 'bottom' => '0', 'left' => '1.25', 'isLinked' => false ),
		)
	);
	// The theme already loads Newsreader and Hanken Grotesk, so Elementor doesn't load them a second time.
	update_option( 'elementor_google_font', '0' );
	// Global colours/fonts only, no Elementor defaults (Roboto, its blue) on unset widgets.
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
}

/** An Elementor element; ids are stable so reseeding a fresh site gives the same data. */
function dewstick_el( $type, $settings = array(), $children = array() ) {
	static $n = 0;
	$el = array(
		'id'       => substr( md5( 'dewstick-el-' . ++$n ), 0, 7 ),
		'elType'   => 'container' === $type ? 'container' : 'widget',
		'settings' => $settings,
		'elements' => $children,
		'isInner'  => false,
	);
	if ( 'container' !== $type ) {
		$el['widgetType'] = $type;
	}
	return $el;
}

/** Spacing value for Elementor's dimension controls, in rem. */
function dewstick_el_box( $top, $right, $bottom, $left ) {
	return array( 'unit' => 'rem', 'top' => "$top", 'right' => "$right", 'bottom' => "$bottom", 'left' => "$left", 'isLinked' => false );
}

function dewstick_el_heading( $text, $tag, $font, $extra = array() ) {
	return dewstick_el(
		'heading',
		array_merge(
			array(
				'title'       => $text,
				'header_size' => $tag,
				'__globals__' => array( 'typography_typography' => "globals/typography?id=$font", 'title_color' => 'globals/colors?id=primary' ),
			),
			$extra
		)
	);
}

function dewstick_el_text( $html, $font = 'text', $extra = array() ) {
	return dewstick_el(
		'text-editor',
		array_merge(
			array(
				'editor'            => $html,
				'paragraph_spacing' => array( 'unit' => 'em', 'size' => 1.3 ),
				'__globals__' => array( 'typography_typography' => "globals/typography?id=$font", 'text_color' => 'globals/colors?id=text' ),
			),
			$extra
		)
	);
}

/** Square, flat, serif button: filled ink (ink → dew red on hover) or outlined. */
function dewstick_el_button( $text, $url, $style = 'fill', $extra = array() ) {
	$on_dark  = 'light' === $style;
	$settings = array(
		'text'                           => $text,
		'link'                           => array( 'url' => $url ),
		'border_border'                  => 'solid',
		'border_width'                   => array( 'unit' => 'px', 'top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'isLinked' => true ),
		'border_radius'                  => array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true ),
		'text_padding'                   => array( 'unit' => 'rem', 'top' => '0.75', 'right' => '0.7', 'bottom' => '0.75', 'left' => '0.7', 'isLinked' => false ),
		'background_background'          => 'classic',
		'button_background_hover_background' => 'classic',
		'button_hover_transition_duration' => array( 'unit' => 's', 'size' => 0.15 ),
		'__globals__'                    => array(
			'typography_typography'               => 'globals/typography?id=accent',
			'button_text_color'                   => 'outline' === $style ? 'globals/colors?id=primary' : ( $on_dark ? 'globals/colors?id=primary' : 'globals/colors?id=secondary' ),
			'background_color'                    => 'outline' === $style ? '' : ( $on_dark ? 'globals/colors?id=secondary' : 'globals/colors?id=primary' ),
			'border_color'                        => $on_dark ? 'globals/colors?id=secondary' : 'globals/colors?id=primary',
			'hover_color'                         => $on_dark ? 'globals/colors?id=primary' : 'globals/colors?id=secondary',
			'button_background_hover_color'       => $on_dark ? 'globals/colors?id=dw_paper' : ( 'outline' === $style ? 'globals/colors?id=primary' : 'globals/colors?id=accent' ),
			'button_hover_border_color'           => $on_dark ? 'globals/colors?id=dw_paper' : ( 'outline' === $style ? 'globals/colors?id=primary' : 'globals/colors?id=accent' ),
		),
	);
	if ( 'outline' === $style ) {
		$settings['background_color'] = 'transparent';
	}
	return dewstick_el( 'button', array_merge( $settings, $extra ) );
}

function dewstick_el_image( $name, $extra = array() ) {
	$id = dewstick_seed_image( $name );
	return dewstick_el(
		'image',
		array_merge(
			array(
				'image'      => array( 'id' => $id, 'url' => wp_get_attachment_url( $id ) ),
				'image_size' => 'full',
				'width'      => array( 'unit' => '%', 'size' => 100 ),
			),
			$extra
		)
	);
}

/** A flex container. $width is a % of the row on desktop; it goes full width on phones. */
function dewstick_el_col( $children, $width = null, $extra = array() ) {
	$settings = array(
		'content_width'  => 'full',
		'flex_direction' => 'column',
		'padding'        => dewstick_el_box( 0, 0, 0, 0 ),
		// Spacing comes from each widget's own top margin, as in the theme's CSS.
		'flex_gap'       => array( 'unit' => 'rem', 'size' => 0, 'column' => '0', 'row' => '0', 'isLinked' => true ),
	);
	if ( $width ) {
		$settings['width']        = array( 'unit' => '%', 'size' => $width );
		$settings['width_mobile'] = array( 'unit' => '%', 'size' => 100 );
	}
	return dewstick_el( 'container', array_merge( $settings, $extra ), $children );
}

function dewstick_el_row( $children, $extra = array() ) {
	return dewstick_el(
		'container',
		array_merge(
			array(
				'content_width'         => 'full',
				'flex_direction'        => 'row',
				'flex_direction_mobile' => 'column',
				'flex_wrap'             => 'nowrap',
				'flex_gap'              => array( 'unit' => 'rem', 'size' => 1.25, 'column' => '1.25', 'row' => '1.25', 'isLinked' => true ),
				'padding'               => dewstick_el_box( 0, 1.25, 0, 1.25 ),
				'margin'                => dewstick_el_box( 8, 0, 0, 0 ),
				'margin_mobile'         => dewstick_el_box( 4, 0, 0, 0 ),
			),
			$extra
		),
		$children
	);
}

function dewstick_el_ingredient_card( $name, $benefit, $body, $image ) {
	return dewstick_el_col(
		array(
			dewstick_el_col(
				array(
					dewstick_el_heading( $name, 'h3', 'secondary' ),
					dewstick_el_heading( $benefit, 'p', 'dw_label', array( '_margin' => dewstick_el_box( 0.9, 0, 0, 0 ) ) ),
					dewstick_el_text( "<p>$body</p>", 'text', array( '_margin' => dewstick_el_box( 0.9, 0, 0, 0 ) ) ),
				)
			),
			dewstick_el_image(
				$image,
				array(
					'width'   => array( 'unit' => '%', 'size' => 55 ),
					'align'   => 'left',
					'_margin' => dewstick_el_box( 2.6, 0, 0, 0 ),
				)
			),
		),
		25,
		array(
			'background_background'    => 'classic',
			'__globals__'              => array( 'background_color' => 'globals/colors?id=dw_paper' ),
			'padding'                  => dewstick_el_box( 2.6, 1.3, 1.3, 1.3 ),
			'padding_mobile'           => dewstick_el_box( 1.3, 1.3, 1.3, 1.3 ),
			// Copy at the top, photo docked to the bottom of a 3:4-ish card.
			'flex_justify_content'     => 'space-between',
			'min_height'               => array( 'unit' => 'rem', 'size' => 30 ),
			'min_height_mobile'        => array( 'unit' => 'rem', 'size' => 0 ),
		)
	);
}

function dewstick_seed_elementor_page() {
	$shop = dewstick_shop_url();
	$set  = dewstick_product_url( 'the-set-of-five' );

	$elements = array(
		// Intro: label, headline, lede.
		dewstick_el_row(
			array(
				dewstick_el_col(
					array(
						dewstick_el_heading( 'Ingredients', 'p', 'dw_label' ),
						dewstick_el_heading( 'Why it works', 'h1', 'primary', array( '_margin' => dewstick_el_box( 1, 0, 0, 0 ) ) ),
						dewstick_el_text( '<p>Half the tube is a lip treatment — three skincare ingredients doing the work of a lip mask, under real colour.</p>', 'text', array( '_margin' => dewstick_el_box( 1.3, 0, 0, 0 ) ) ),
					),
					42
				),
			),
			array( '_title' => 'Intro', 'margin' => dewstick_el_box( 4, 0, 0, 0 ), 'margin_mobile' => dewstick_el_box( 3, 0, 0, 0 ) )
		),
		// The four ingredient cards.
		dewstick_el_row(
			array(
				dewstick_el_ingredient_card( 'Squalane', 'Hydrate', 'A skin-identical oil that sinks in fast and holds moisture where lips lose it first.', 'ing-squalane.jpg' ),
				dewstick_el_ingredient_card( 'Shea butter', 'Nourish', 'Rich fatty acids that soften dry patches and keep lips smooth for hours.', 'ing-shea.jpg' ),
				dewstick_el_ingredient_card( 'Ceramides', 'Protect', 'The lipids your skin barrier is built from. They help lips hold on to their own moisture.', 'ing-ceramide.jpg' ),
				dewstick_el_ingredient_card( 'Real colour', 'Glow', 'A sheer-to-buildable tint in five shades, with a juicy, balmy finish.', 'ing-swatches.jpg' ),
			),
			array( '_title' => 'Ingredient cards', 'margin' => dewstick_el_box( 3.6, 0, 0, 0 ), 'margin_mobile' => dewstick_el_box( 2.4, 0, 0, 0 ) )
		),
		// Texture: photo left, copy right.
		dewstick_el_row(
			array(
				dewstick_el_col( array( dewstick_el_image( '09' ) ), 50 ),
				dewstick_el_col(
					array(
						dewstick_el_heading( 'Juicy. Glossy. Balmy.', 'h2', 'primary' ),
						dewstick_el_text( '<p>One swipe, juicy glow.</p><p>Real colour that melts in like a balm and keeps working like a mask.</p><p>Underneath it: squalane, shea butter and ceramides, so every swipe is also a treatment.</p>', 'text', array( '_margin' => dewstick_el_box( 1.3, 0, 0, 0 ) ) ),
						dewstick_el_button( 'Find your shade', $shop, 'outline', array( '_margin' => dewstick_el_box( 2.6, 0, 0, 0 ) ) ),
					),
					33,
					array( 'margin' => dewstick_el_box( 0, 0, 0, 8 ), 'margin_mobile' => dewstick_el_box( 1.3, 0, 0, 0 ) )
				),
			),
			array( '_title' => 'Texture' )
		),
		// Closing band on ink.
		dewstick_el_row(
			array(
				dewstick_el_col(
					array(
						dewstick_el_heading( 'Half the tube is lipstick. The other half does the work of a $30 lip mask.', 'h2', 'dw_statement', array( '__globals__' => array( 'typography_typography' => 'globals/typography?id=dw_statement', 'title_color' => 'globals/colors?id=secondary' ) ) ),
						dewstick_el_button( 'Shop the shades', $shop, 'light', array( '_margin' => dewstick_el_box( 2.6, 0, 0, 0 ) ) ),
						dewstick_el_text( '<p><a href="' . esc_url( $set ) . '" style="color:inherit;border-bottom:1px solid currentColor">Or take all five — the set saves $12.</a></p>', 'text', array( '_margin' => dewstick_el_box( 1.3, 0, 0, 0 ), '__globals__' => array( 'typography_typography' => 'globals/typography?id=text', 'text_color' => 'globals/colors?id=secondary' ) ) ),
					),
					50
				),
			),
			array(
				'_title'                => 'Closing band',
				'background_background' => 'classic',
				'__globals__'           => array( 'background_color' => 'globals/colors?id=primary' ),
				'padding'               => dewstick_el_box( 5, 1.25, 5, 1.25 ),
				'padding_mobile'        => dewstick_el_box( 3, 1.25, 3, 1.25 ),
			)
		),
	);

	$id = wp_insert_post(
		array(
			'post_type'   => 'page',
			'post_status' => 'publish',
			'post_title'   => 'Ingredients',
			'post_name'    => 'ingredients',
			'post_excerpt' => 'Squalane, shea butter and ceramides under real colour: what each ingredient in dewstick does for your lips.',
		)
	);
	$doc = \Elementor\Plugin::$instance->documents->get( $id, false );
	$doc->set_is_built_with_elementor( true );
	$doc->save(
		array(
			'elements' => $elements,
			'settings' => array( 'template' => 'elementor_header_footer' ),
		)
	);
	return $id;
}

/* ---------- Kadence Blocks: /the-idea/ ---------- */

/**
 * The page was laid out in the block editor with Kadence Blocks and its saved markup exported to
 * kadence-the-idea.html. Image ids/URLs and internal links differ per site, so the file carries
 * {{img:NAME}}, {{id:NAME}} and {{home}} placeholders that are filled in here.
 */
function dewstick_seed_kadence_page() {
	$markup = file_get_contents( __DIR__ . '/kadence-the-idea.html' );
	$markup = preg_replace_callback(
		'/\{\{(img|id):([^}]+)\}\}/',
		function ( $m ) {
			$id = dewstick_seed_image( $m[2] );
			return 'id' === $m[1] ? (string) $id : wp_get_attachment_url( $id );
		},
		$markup
	);
	$markup = str_replace( '{{home}}', untrailingslashit( home_url() ), $markup );

	return wp_insert_post(
		array(
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_title'    => 'The Idea',
			'post_name'     => 'the-idea',
			'post_excerpt'  => 'Why we made dewstick, a lip mask you can wear out the door. No reviews yet, and we won’t make any up.',
			// wp_insert_post() unslashes, and the block attributes are JSON with escaped characters.
			'post_content'  => wp_slash( $markup ),
			'page_template' => 'template-builder.php',
		)
	);
}
