<?php
/**
 * Fallback for anything without its own template (posts, search).
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<section class="section" style="margin-top: 3rem;">
	<div class="container page-narrow">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article class="mt-4">
					<h2 class="h2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="rte mt-2"><?php is_singular() ? the_content() : the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="muted"><?php dewstick_e( 'collection.empty' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
