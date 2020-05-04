<?php
/**
 * Gets CSS units.
 *
 * @return array
 */
function wsb_get_css_units() {
	$units = apply_filters( 'wsb_dimensions_units', [
		'px'  => __( 'px', 'buy-now-woo' ),
		'em'  => __( 'em', 'buy-now-woo' ),
		'rem' => __( 'rem', 'buy-now-woo' ),
	] );

	return is_array( $units ) && ! empty( $units ) ? $units : [];
}

