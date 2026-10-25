<?php
/**
 * Template Name: Full width (page builder)
 *
 * The dewstick header and footer around the page's blocks, with no title or wrapper,
 * for pages laid out in a page builder (/the-idea/ is built with Kadence Blocks).
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="builder-page"><?php the_content(); ?></div>
	<?php
endwhile;

get_footer();
