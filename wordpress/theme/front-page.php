<?php
/**
 * Homepage: hero video → USP strip → "Find your shade" → "Juicy. Glossy. Balmy." → story band →
 * "Start here" tiles → "Why it works" cards → "No reviews yet." founder note. Newsletter + wall are in footer.php.
 */
defined( 'ABSPATH' ) || exit;
get_header();

$dewstick_shop = dewstick_shop_url();
$dewstick_set  = dewstick_product_url( 'the-set-of-five' );
?>
<section class="hero">
	<div class="hero__media">
		<video autoplay loop muted playsinline preload="metadata" poster="<?php echo esc_url( dewstick_asset( 'video/hero-poster.jpg' ) ); ?>" aria-hidden="true">
			<source src="<?php echo esc_url( dewstick_asset( 'video/hero.mp4' ) ); ?>" type="video/mp4">
		</video>
	</div>
	<div class="hero__content">
		<h1 class="h1"><?php echo str_replace( '<br>', '<br> ', dewstick_t( 'hero.title_html' ) ); ?></h1>
		<div class="hero__copy">
			<p><?php dewstick_e( 'home.hero_p1' ); ?></p>
			<p><?php dewstick_e( 'home.hero_p2' ); ?></p>
			<div class="btn-stack">
				<a class="btn btn--light" href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'hero.cta' ); ?></a>
				<a class="btn btn--outline" href="#why"><?php dewstick_e( 'hero.cta_secondary' ); ?></a>
			</div>
		</div>
	</div>
</section>

<ul class="usp">
	<?php foreach ( array( 'usp_formula', 'usp_shades', 'usp_set' ) as $dewstick_k ) : ?>
		<li><?php echo DEWSTICK_CHECK; ?><?php dewstick_e( "home.$dewstick_k" ); ?></li>
	<?php endforeach; ?>
</ul>

<section id="shades" class="section">
	<div class="sec-head">
		<h2 class="h2"><?php dewstick_e( 'home.shop_title' ); ?></h2>
		<a class="link-u" href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'shades.see_all' ); ?></a>
	</div>
	<div class="g12 pgrid">
		<?php
		foreach ( array( 'nectar', 'poppy', 'blush' ) as $dewstick_shade ) {
			echo dewstick_shade_card( $dewstick_shade );
		}
		echo dewstick_set_card();
		?>
	</div>
</section>

<section id="feel" class="section g12 gallery">
	<div class="gallery__text">
		<h2 class="t-xl"><?php dewstick_e( 'feel.title' ); ?></h2>
		<div class="prose measure" style="margin-top: 1.3rem;">
			<p><?php dewstick_e( 'home.feel_lead' ); ?></p>
			<p><?php dewstick_e( 'home.feel_p1' ); ?></p>
			<p><?php dewstick_e( 'home.feel_p2' ); ?></p>
		</div>
		<a class="btn btn--outline" href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'home.feel_cta' ); ?></a>
	</div>
	<div class="gallery__img gallery__img--a"><?php echo dewstick_img( '09', null, '(min-width: 768px) 50vw, 100vw' ); ?></div>
</section>

<section class="band">
	<?php echo dewstick_img( 'band-lips-square.jpg', DEWSTICK_ALT['12'], '100vw', 'band__img' ); ?>
	<div class="g12">
		<div class="band__copy">
			<p class="t-3xl"><?php dewstick_e( 'home.story' ); ?></p>
			<a class="btn btn--outline" href="#why"><?php dewstick_e( 'hero.cta_secondary' ); ?></a>
		</div>
	</div>
</section>

<section class="section">
	<div class="sec-head"><h2 class="h2"><?php dewstick_e( 'home.tiles_title' ); ?></h2></div>
	<div class="tiles">
		<?php
		$dewstick_tiles = array(
			array( $dewstick_shop, '11', 'home.tile_shades' ),
			array( dewstick_home_url( '#why' ), '03', 'nav.ingredients' ),
			array( $dewstick_set, '10', 'set.title' ),
			array( dewstick_home_url( '#note' ), '08', 'nav.idea' ),
		);
		foreach ( $dewstick_tiles as [ $dewstick_href, $dewstick_img, $dewstick_cap ] ) :
			?>
			<a class="tile" href="<?php echo esc_url( $dewstick_href ); ?>"><div class="tile__media"><?php echo dewstick_img( $dewstick_img, '', '(min-width: 768px) 25vw, 70vw' ); ?></div>
				<p class="tile__caption"><?php dewstick_e( $dewstick_cap ); ?></p></a>
		<?php endforeach; ?>
	</div>
</section>

<section id="why" class="section" aria-labelledby="why-title">
	<div class="sec-head">
		<h2 id="why-title" class="h2"><?php dewstick_e( 'why.title' ); ?></h2>
		<div class="scroller__nav">
			<button type="button" class="link-u" data-scroll="-1" aria-controls="whyTrack"><?php dewstick_e( 'home.why_prev' ); ?></button>
			<button type="button" class="link-u" data-scroll="1" aria-controls="whyTrack"><?php dewstick_e( 'home.why_next' ); ?></button>
		</div>
	</div>
	<div id="whyTrack" class="scroller" tabindex="0" aria-label="<?php echo esc_attr( dewstick_t_raw( 'why.title' ) ); ?>">
		<?php
		$dewstick_why = array(
			array( 'squalane', 'hydrate', 'ing-squalane.jpg', 'Squalane' ),
			array( 'shea', 'nourish', 'ing-shea.jpg', 'Shea butter' ),
			array( 'ceramides', 'protect', 'ing-ceramide.jpg', 'Ceramide cream' ),
			array( 'colour', 'glow', 'ing-swatches.jpg', 'Tinted lip balm swatches' ),
		);
		foreach ( $dewstick_why as [ $dewstick_k, $dewstick_benefit, $dewstick_src, $dewstick_alt ] ) :
			?>
			<article class="scard">
				<h3 class="t-xl"><?php dewstick_e( "home.why_$dewstick_k" ); ?></h3>
				<p class="scard__label"><?php dewstick_e( "why.$dewstick_benefit.title" ); ?></p>
				<p class="scard__body"><?php dewstick_e( "home.why_{$dewstick_k}_body" ); ?></p>
				<?php echo dewstick_img( $dewstick_src, $dewstick_alt, '(min-width: 768px) 15vw, 50vw', 'scard__img' ); ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>

<section id="note" class="section g12 founder">
	<h2 class="h2 founder__head"><?php dewstick_e( 'note.title' ); ?></h2>
	<div class="founder__body">
		<div class="prose measure">
			<p><?php dewstick_e( 'note.sub' ); ?></p>
			<p><?php dewstick_e( 'note.body' ); ?></p>
			<p><?php dewstick_e( 'home.note_sign' ); ?></p>
		</div>
		<a class="btn btn--outline" href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'hero.cta' ); ?></a>
	</div>
	<div class="founder__img"><?php echo dewstick_img( '07', null, '(min-width: 768px) 33vw, 100vw' ); ?></div>
</section>
<?php
get_footer();
