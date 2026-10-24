<?php
defined( 'ABSPATH' ) || exit;
$dewstick_shop = dewstick_shop_url();
$dewstick_set  = dewstick_product_url( 'the-set-of-five' );
$dewstick_cart = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
?>
</main>
<footer class="site-footer">
	<section class="g12 news" aria-labelledby="news-title">
		<div class="news__form">
			<h2 id="news-title" class="h2"><?php dewstick_e( 'newsletter.heading' ); ?></h2>
			<p class="mt-3 measure"><?php dewstick_e( 'newsletter.privacy' ); ?></p>
			<?php echo dewstick_newsletter_form(); ?>
		</div>
		<div class="wall" aria-label="@dewstick">
			<?php foreach ( array( '06', '02', '01', '13' ) as $dewstick_n ) : ?>
				<div class="wall__tile"><?php echo dewstick_img( $dewstick_n, null, '(min-width: 768px) 20vw, 33vw' ); ?></div>
			<?php endforeach; ?>
		</div>
	</section>
	<div class="site-footer__rule" style="margin-top: var(--section);"></div>
	<div class="g12 site-footer__grid">
		<div class="site-footer__col">
			<p class="site-footer__label"><?php dewstick_e( 'home.footer_shop' ); ?></p>
			<ul>
				<li><a href="<?php echo esc_url( $dewstick_shop ); ?>"><?php dewstick_e( 'home.tile_shades' ); ?></a></li>
				<li><a href="<?php echo esc_url( $dewstick_set ); ?>"><?php dewstick_e( 'set.title' ); ?></a></li>
				<li><a href="<?php echo esc_url( $dewstick_cart ); ?>"><?php dewstick_e( 'general.cart' ); ?></a></li>
			</ul>
		</div>
		<div class="site-footer__col">
			<p class="site-footer__label"><?php dewstick_e( 'footer.about' ); ?></p>
			<ul>
				<li><a href="<?php echo esc_url( dewstick_home_url( '#note' ) ); ?>"><?php dewstick_e( 'nav.idea' ); ?></a></li>
				<li><a href="<?php echo esc_url( dewstick_home_url( '#why' ) ); ?>"><?php dewstick_e( 'nav.ingredients' ); ?></a></li>
			</ul>
		</div>
		<div class="site-footer__col">
			<p class="site-footer__label"><?php dewstick_e( 'footer.project' ); ?></p>
			<ul>
				<li><a href="<?php echo esc_url( DEWSTICK_SHOPIFY_URL ); ?>" target="_blank" rel="noopener"><?php dewstick_e( 'footer.store' ); ?></a></li>
				<li><a href="<?php echo esc_url( DEWSTICK_STATIC_URL ); ?>" target="_blank" rel="noopener"><?php dewstick_e( 'footer.static' ); ?></a></li>
				<li><a href="<?php echo esc_url( DEWSTICK_GITHUB_URL ); ?>" target="_blank" rel="noopener"><?php dewstick_e( 'footer.source' ); ?></a></li>
			</ul>
		</div>
		<div class="site-footer__switch">
			<p><?php dewstick_e( 'home.lang_q' ); ?></p>
			<?php echo dewstick_lang_switch(); ?>
		</div>
	</div>
	<div class="site-footer__base">
		<p>© <?php echo esc_html( gmdate( 'Y' ) ); ?> dewstick</p>
		<p class="muted"><?php dewstick_e( 'footer.copy_note' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
