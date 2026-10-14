<?php
/**
 * Single blog post with Article schema + author + related.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post(); ?>
	<article class="container section post narrow">
		<?php vtech_breadcrumbs(); ?>
		<header class="post__header">
			<h1 class="post__title"><?php the_title(); ?></h1>
			<p class="post__meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( function_exists( 'vtech_contact' ) ? vtech_contact( 'company' ) . ' engineering team' : get_the_author() ); ?></p>
		</header>
		<?php if ( has_post_thumbnail() ) : ?><figure class="post__hero"><?php the_post_thumbnail( 'vtech-hero' ); ?></figure><?php endif; ?>
		<div class="post__content"><?php the_content(); ?></div>
		<?php if ( function_exists( 'vtech_render_post_footer' ) ) { vtech_render_post_footer( get_the_ID() ); } ?>
	</article>
	<?php
	// Rank Math outputs the Article schema when active (see inc/seo-boost.php); this is the fallback.
	if ( ! ( function_exists( 'vtech_seo_plugin_active' ) && vtech_seo_plugin_active() ) ) vtech_json_ld( array(
		'@context' => 'https://schema.org', '@type' => 'Article',
		'headline' => get_the_title(), 'datePublished' => get_the_date( 'c' ), 'dateModified' => get_the_modified_date( 'c' ),
		'author' => array( '@type' => 'Organization', 'name' => 'VTECH Audio Visual Solutions' ),
		'publisher' => array( '@type' => 'Organization', 'name' => 'VTECH Audio Visual Solutions', '@id' => home_url( '/' ) . '#business' ),
		'mainEntityOfPage' => get_permalink(),
	) );
endwhile; get_footer();
