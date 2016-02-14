<?php
/**
 * The template for displaying the WP Job Manager start part of the listings list
 *
 * @package Listable
 */
?>

<?php
if ( listable_using_facetwp() ) :

	do_action( 'listify_facetwp_sort' );

	$output = '';
	$output .= facetwp_display( 'template', 'listings' );
	$output .= facetwp_display( 'pager' );

	echo $output;

else : ?>

<div class="grid list job_listings">

<?php endif;
