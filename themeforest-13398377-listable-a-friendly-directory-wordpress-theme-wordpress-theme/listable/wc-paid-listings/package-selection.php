<?php if ( $packages || $user_packages ) :
	$checked = 1;
	?>
	<?php if ( $user_packages ) : ?>
		<h2 class="package-list__title"><?php _e( "Your packages", "listable" ); ?></h2>
		<div class="package-list  package-list--user">
			<?php foreach ( $user_packages as $key => $package ) :
				$package = wc_paid_listings_get_package( $package );
				?>
				<div class="package  package--featured">
					<h2 class="package__title"><?php echo $package->get_title(); ?></h2>
					<div class="package__content">
					<?php
						if ( $package->get_limit() ) {
							printf( _n( '%s listing posted out of %d', '%s listings posted out of %d', $package->get_count(), 'wp-job-manager-wc-paid-listings' ) . ', ', $package->get_count(), $package->get_limit() );
						} else {
							printf( _n( '%s listing posted', '%s listings posted', $package->get_count(), 'wp-job-manager-wc-paid-listings' ) . ', ', $package->get_count() );
						}

						if ( $package->get_duration() ) {
							printf( _n( 'listed for %s day', 'listed for %s days', $package->get_duration(), 'wp-job-manager-wc-paid-listings' ), $package->get_duration() );
						}

						$checked = 0;
					?>
					</div>
					<button class="btn package__btn" type="submit" name="job_package" value="user-<?php echo $key; ?>" id="package-<?php echo $product->id; ?>">
						<?php _e('Get Started', 'listable') ?>
					</button>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( $packages ) : ?>
		<?php if ( $user_packages ) : ?>
			<h2 class="package-list__title"><?php _e( "Purchase packages", "listable" ); ?></h2>
		<?php endif; ?>
		<div class="package-list">
			<?php foreach ( $packages as $key => $package ) :
				$product = wc_get_product( $package );
				if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) || ! $product->is_purchasable() ) {
					continue;
				} ?>
				<div class="package  <?php echo $product->is_featured()  == 'yes' ? 'package--featured' : ''; ?>">
					<?php if ( $product->is_featured()  == 'yes' ): ?>
						<?php $tags = get_the_terms($product->id, 'product_tag');
							if ( !empty($tags) ) {
								$tag = $tags[0]; ?>
								<div class="featured-label"><?php _e('Most Popular', 'listable'); ?></div>
							<?php }
						?>
					<?php endif; ?>
					<h2 class="package__title">
						<?php echo $product->get_title(); ?>
					</h2>
					<div class="package__price">
						<?php if ( $product->price ): ?>
							<sup class="package__currency"><?php echo get_woocommerce_currency_symbol(); ?></sup><?php echo $product->price; ?>
						<?php else: ?>
							<?php _e('Free', 'listable'); ?>
						<?php endif; ?>
					</div>
					<div class="package__description">
						<?php echo apply_filters( 'woocommerce_short_description', $product->post->post_excerpt ) ?>
					</div>
					<div class="package__content">
						<?php echo apply_filters( 'the_content', $product->post->post_content ) ?>
					</div>
					<button class="btn package__btn" type="submit" name="job_package" value="<?php echo $product->id; ?>" id="package-<?php echo $product->id; ?>">
						<?php _e('Get Started', 'listable') ?>
					</button>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php else : ?>

	<p class="no-packages"><?php _e( 'No packages found', 'wp-job-manager-wc-paid-listings' ); ?></p>

<?php endif; ?>
