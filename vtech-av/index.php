<?php
/**
 * Fallback index. Most output is block/template-driven; this covers the blog.
 *
 * @package VTECH_AV
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
// HOST-PROOF: some hosts misroute custom pages to index.php. If this request
// is really one of our template-driven pages, load its template and stop.
$vtech_force_map = array(
	'equipment-hire'         => 'page-equipment-hire.php',
	'equipment-hire-request' => 'page-hire-request.php',
	'consultation'           => 'page-consultation.php',
	'book-a-consultation'    => 'page-consultation.php',
	'about'                  => 'page-about.php',
	'contact'                => 'page-contact.php',
);
$vtech_req_slug = '';
$vtech_obj = get_queried_object();
if ( $vtech_obj instanceof WP_Post ) {
	$vtech_req_slug = $vtech_obj->post_name;
} else {
	$vtech_pn = trim( (string) get_query_var( 'pagename' ), '/' );
	if ( '' === $vtech_pn ) { $vtech_pn = trim( (string) get_query_var( 'name' ), '/' ); }
	if ( '' === $vtech_pn ) { $vtech_pn = trim( parse_url( isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH ), '/' ); }
	if ( $vtech_pn ) { $vtech_parts = explode( '/', $vtech_pn ); $vtech_req_slug = end( $vtech_parts ); }
}
if ( $vtech_req_slug && isset( $vtech_force_map[ $vtech_req_slug ] ) && locate_template( $vtech_force_map[ $vtech_req_slug ] ) ) {
	include locate_template( $vtech_force_map[ $vtech_req_slug ] );
	return;
}
get_header();
$vtc_paged = max( 1, (int) get_query_var( 'paged' ) );
$vtc_cats  = is_home() ? get_categories( array( 'hide_empty' => true ) ) : array();
?>
<section class="blog-hero">
	<div class="container">
		<?php vtech_breadcrumbs(); ?>
		<p class="ab-kicker"><?php esc_html_e( 'Insights & guides', 'vtech-av' ); ?></p>
		<h1 class="blog-hero__title"><?php echo is_home() ? esc_html__( 'AV insights for Kenyan organisations', 'vtech-av' ) : wp_kses_post( get_the_archive_title() ); ?></h1>
		<p class="blog-hero__lead"><?php esc_html_e( 'Practical advice on sound, LED, conferencing and event AV: buying guides, costs, checklists and case studies.', 'vtech-av' ); ?></p>
		<?php if ( count( $vtc_cats ) > 1 ) : ?>
			<nav class="blog-cats" aria-label="<?php esc_attr_e( 'Topics', 'vtech-av' ); ?>">
				<a class="is-active" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'All', 'vtech-av' ); ?></a>
				<?php foreach ( $vtc_cats as $c ) : ?><a href="<?php echo esc_url( get_category_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a><?php endforeach; ?>
			</nav>
		<?php endif; ?>
	</div>
</section>

<section class="section blog-list">
	<div class="container">
	<?php if ( have_posts() ) : $vtc_i = 0; ?>
		<div class="blog-grid">
		<?php while ( have_posts() ) : the_post(); $vtc_i++;
			$lead = ( 1 === $vtc_i && 1 === $vtc_paged );
			$mins = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) );
			$cat  = get_the_category();
		?>
			<article <?php post_class( 'blog-card' . ( $lead ? ' blog-card--lead' : '' ) ); ?>>
				<a class="blog-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
					<?php if ( has_post_thumbnail() ) { the_post_thumbnail( $lead ? 'vtech-hero' : 'vtech-card', array( 'loading' => $vtc_i > 2 ? 'lazy' : 'eager', 'alt' => get_the_title() ) ); } else { echo '<img src="' . esc_url( VTECH_URI . '/assets/img/og-default.webp' ) . '" alt="" loading="lazy">'; } ?>
				</a>
				<div class="blog-card__body">
					<p class="blog-card__meta">
						<?php if ( $cat ) : ?><span class="blog-card__cat"><?php echo esc_html( $cat[0]->name ); ?></span><?php endif; ?>
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></time>
						<span><?php echo esc_html( sprintf( _n( '%d min read', '%d min read', $mins, 'vtech-av' ), $mins ) ); ?></span>
					</p>
					<h2 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="blog-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), $lead ? 34 : 20 ) ); ?></p>
					<span class="svx-link" aria-hidden="true"><?php esc_html_e( 'Read article', 'vtech-av' ); ?> &rarr;</span>
				</div>
			</article>
		<?php endwhile; ?>
		</div>
		<div class="blog-pager"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
	<?php else : ?>
		<p><?php esc_html_e( 'No articles yet. Check back soon.', 'vtech-av' ); ?></p>
	<?php endif; ?>
	</div>
</section>

<section class="section svx-final">
	<div class="container">
		<div class="svx-final__box">
			<div class="svx-final__copy">
				<h2><?php esc_html_e( 'Planning an AV project or event?', 'vtech-av' ); ?></h2>
				<p><?php esc_html_e( 'Get advice from an engineer and a fixed quote within 24 hours.', 'vtech-av' ); ?></p>
			</div>
			<div class="svx-final__actions">
				<a class="btn btn--accent btn--lg" href="<?php echo esc_url( home_url( '/book-a-consultation/' ) ); ?>"><?php esc_html_e( 'Book a free consultation', 'vtech-av' ); ?></a>
				<a class="svx-final__alt" href="<?php echo esc_url( home_url( '/hire-packages/' ) ); ?>"><?php esc_html_e( 'See hire packages', 'vtech-av' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php get_footer();
