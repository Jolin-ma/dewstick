<?php
/**
 * Shop and collection pages (/shop/, /collections/shades/): the five shades and the set, as on the static site.
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="section" style="padding-top: 3.6rem; margin-top: 0;">
	<div class="sec-head">
		<h1 class="h2"><?php dewstick_e( 'collection.title' ); ?></h1>
		<p class="muted"><?php dewstick_e( 'collection.desc' ); ?></p>
	</div>
	<?php woocommerce_output_all_notices(); ?>
	<div class="g12 pgrid">
		<?php
		foreach ( DEWSTICK_SHADES as $dewstick_shade ) {
			echo dewstick_shade_card( $dewstick_shade );
		}
		echo dewstick_set_card();
		?>
	</div>
</section>
<?php
get_footer();
