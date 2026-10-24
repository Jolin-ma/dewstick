<?php
/**
 * Pages, including WooCommerce's cart, checkout and account pages (their shortcodes render inside).
 */
defined( 'ABSPATH' ) || exit;
get_header();

$dewstick_is_cart     = function_exists( 'is_cart' ) && is_cart();
$dewstick_is_checkout = function_exists( 'is_checkout' ) && is_checkout();
?>
<section class="section" style="margin-top: 3rem;">
	<div class="container<?php echo $dewstick_is_cart ? '' : ' page-wide'; ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			if ( ! $dewstick_is_cart ) :
				?>
				<h1 class="h2"><?php echo $dewstick_is_checkout ? dewstick_t( 'checkout.title' ) : esc_html( get_the_title() ); ?></h1>
				<?php
			endif;
			?>
			<div class="<?php echo $dewstick_is_cart ? '' : 'rte page-content mt-3'; ?>"><?php the_content(); ?></div>
		<?php endwhile; ?>
	</div>
</section>
<?php
get_footer();
