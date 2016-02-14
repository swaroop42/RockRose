<?php
/**
 * The template for displaying the WP Job Manager listing on archives
 *
 * @package Listable
 */

global $post;

$taxonomies  = array();
$terms       = get_the_terms( get_the_ID(), 'job_listing_category' );
$termString  = '';
$data_output = '';
if ( is_array( $terms ) || is_object( $terms ) ) {
	$firstTerm = $terms[0];
	if ( ! $firstTerm == null ) {
		$term_id = $firstTerm->term_id;
		$data_output .= ' data-icon="' . listable_get_term_icon_url( $term_id ) . '"';
		$count = 1;
		foreach ( $terms as $term ) {
			$termString .= $term->name;
			if ( $count != count( $terms ) ) {
				$termString .= ', ';
			}
			$count ++;
		}
	}
}

$listing_classes = 'card card--listing ';
$listing_is_claimed = false;
if ( class_exists( 'WP_Job_Manager_Claim_Listing' ) ) {
	$classes = WP_Job_Manager_Claim_Listing()->listing->add_post_class( array() );

	if ( isset( $classes[0] ) && ! empty( $classes[0] ) ) {
		$listing_classes .= $classes[0];

		if( $classes[0] == 'claimed' )
			$listing_is_claimed = true;
	}
} ?>
<a class="grid__item" href="<?php the_job_permalink(); ?>">
	<article class="<?php echo $listing_classes; ?>" itemscope itemtype="http://schema.org/LocalBusiness"
	         data-latitude="<?php echo get_post_meta( $post->ID, 'geolocation_lat', true ); ?>"
	         data-longitude="<?php echo get_post_meta( $post->ID, 'geolocation_long', true ); ?>"
	         data-img="<?php echo listable_get_post_image_src( $post->ID, 'listable-card-image' ); ?>"
	         data-permalink="<?php the_job_permalink(); ?>"
	         data-categories="<?php echo $termString; ?>"
		<?php echo $data_output; ?> >
		<aside class="card__image" style="background-image: url(<?php echo listable_get_post_image_src( $post->ID, 'listable-card-image' ); ?>);">
			<?php
			global $job_manager_bookmarks;

			if ( $job_manager_bookmarks !== null && method_exists( $job_manager_bookmarks, 'is_bookmarked' ) ) {
				$bookmark_state = '';

				if (  $job_manager_bookmarks->is_bookmarked( $post->ID ) ) {
					$bookmark_state = 'is--bookmarked';
				} ?>
				<div class="heart <?php echo $bookmark_state; ?>">
					<?php get_template_part( 'assets/svg/heart-svg' ); ?>
				</div>
			<?php } ?>
		</aside>
		<div class="card__content">
			<h2 class="card__title" itemprop="name"><?php
				echo get_the_title();

				if( $listing_is_claimed ) :
					echo '<span class="listing-claimed-icon">';
					get_template_part('assets/svg/checked-icon-small');
					echo '<span>';
				endif;
			?></h2>
			<div class="card__tagline" itemprop="description"><?php the_company_tagline(); ?></div>
			<footer class="card__footer">
				<?php
				$rating = get_average_listing_rating( $post->ID, 1 );
				$geolocation_street = get_post_meta( $post->ID, 'geolocation_street', true );
				if ( ! empty( $rating ) ) { ?>
					<div class="rating  card__rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
						<meta itemprop="ratingValue" content = "<?php echo get_average_listing_rating( $post->ID, 1 ); ?>">
						<meta itemprop="reviewCount" content = "<?php echo get_comments_number( $post->ID ) ?>; ?>">
						<span class="js-average-rating"><?php echo get_average_listing_rating( $post->ID, 1 ); ?></span>
					</div>
				<?php } elseif ( ! empty( $geolocation_street ) ) { ?>
					<div class="card__rating  card__pin">
						<?php get_template_part( 'assets/svg/pin-simple-svg' ) ?>
					</div>
				<?php }

				if ( is_array( $terms ) || is_object( $terms ) ) { ?>
					<ul class="card__tags">
						<?php foreach ( $terms as $term ) {
							$icon_url = listable_get_term_icon_url( $term->term_id );
							$attachment_id = listable_get_term_icon_id( $term->term_id );
							if ( empty( $icon_url ) ) {
								continue;
							} ?>
							<li>
								<div class="card__tag">
									<div class="pin__icon">
										<?php listable_display_image( $icon_url, '', true, $attachment_id ); ?>
									</div>
								</div>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
				<div class="address  card__address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<div itemprop="streetAddress">
						<span class="address__street"><?php echo trim( get_post_meta( $post->ID, 'geolocation_street', true ), '' ); ?></span>
						<span class="address__street-no"><?php echo trim( get_post_meta( $post->ID, 'geolocation_street_number', true ), '' ); ?></span>
					</div>
					<span class="address__city" itemprop="addressLocality"><?php echo trim( get_post_meta( $post->ID, 'geolocation_city', true ), '' ); ?></span>
					<span class="address__postcode" itemprop="postalCode"><?php echo trim( get_post_meta( $post->ID, 'geolocation_postcode', true ), '' ); ?></span>
					<span class="address__state-short" itemprop="addressRegion"><?php echo trim( get_post_meta( $post->ID, 'geolocation_state_short', true ), '' ); ?></span>
					<span class="address__country-short" itemprop="addressCountry"><?php echo trim( get_post_meta( $post->ID, 'geolocation_country_short', true ), '' ); ?></span>
				</div>
			</footer>
		</div>
	</article>
</a>
