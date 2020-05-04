<?php

namespace Buy_Now_Woo\Admin;

/**
 * Settings
 */
class Settings extends \WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'wc_simple_buy_settings';
		$this->label = esc_html__( 'Buy Now', 'buy-now-woo' );

		new Dimensions_Field();
		new Size_Field();

		add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_settings_page' ], 20 );
		add_filter( 'woocommerce_sections_' . $this->id, [ $this, 'output_sections' ] );
		add_filter( 'woocommerce_settings_' . $this->id, [ $this, 'output_settings' ] );
		add_action( 'woocommerce_settings_save_' . $this->id, [ $this, 'save' ] );
	}

	/**
	 * Gets sections
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = [
			''          => esc_html__( 'General', 'buy-now-woo' ),
			'customize' => esc_html__( 'Customize', 'buy-now-woo' ),
		];

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Gets button positions.
	 *
	 * @return array
	 */
	public function get_positions() {
		return apply_filters( 'buy_now_woo_get_postitions', [
			'before'          => esc_html__( 'Before Add To Cart Button', 'buy-now-woo' ),
			'after'           => esc_html__( 'After Add To Cart Button', 'buy-now-woo' ),
			'replace'         => esc_html__( 'Replace Add To Cart Button', 'buy-now-woo' ),
			'before_quantity' => esc_html__( 'Before Quantity Input', 'buy-now-woo' ),
			'after_quantity'  => esc_html__( 'After Quantity Input', 'buy-now-woo' ),
			'shortcode'       => esc_html__( 'Use a Shortcode (for developer)', 'buy-now-woo' ),
		] );
	}

	/**
	 * Gets redirects.
	 *
	 * @return array
	 */
	public function get_redirects() {
		return apply_filters( 'buy_now_woo_get_redirects', [
			'popup'    => esc_html__( 'Use pop-up', 'buy-now-woo' ),
			'checkout' => esc_html__( 'Redirect to the checkout page (skip the cart page)',
				'buy-now-woo' ),
		] );
	}

	/**
	 * Output settings.
	 */
	public function output_settings() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		\WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Gets settings.
	 *
	 * @param  array $current_section Current section.
	 *
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {
		if ( 'customize' === $current_section ) {
			$settings = $this->get_customize();
		} else {
			$settings = $this->get_general();
		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}

	/**
	 * Gets general settings.
	 *
	 * @return array
	 */
	public function get_general() {
		$settings = [];

		$settings[] = [
			'name' => esc_html__( 'General Settings', 'buy-now-woo' ),
			'type' => 'title',
			'desc' => esc_html__( 'The following options are used to configure Buy Now button actions.',
				'buy-now-woo' ),
			'id'   => 'buy_now_woo_settings_start',
		];

		$settings[] = [
			'name'    => esc_html__( 'Enable Buy Now button', 'buy-now-woo' ),
			'id'      => 'buy_now_woo_single_product_enable',
			'type'    => 'checkbox',
			'default' => 'yes',
		];

		$settings[] = [
			'name'     => esc_html__( 'Redirect', 'buy-now-woo' ),
			'desc_tip' => esc_html__( 'Use pop-up or redirect to the checkout page', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_redirect',
			'type'     => 'radio',
			'default'  => 'popup',
			'options'  => $this->get_redirects(),
		];

		$settings[] = [
			'name'     => esc_html__( 'Button Position', 'buy-now-woo' ),
			'desc_tip' => esc_html__( 'Where the button need to be added in single page .. before / after / replace',
				'buy-now-woo' ),
			'id'       => 'buy_now_woo_single_product_position',
			'type'     => 'select',
			'class'    => 'chosen_select',
			'default'  => 'before',
			'options'  => $this->get_positions(),
		];

		$settings[] = [
			'name'     => esc_html__( 'Button Title', 'buy-now-woo' ),
			'desc_tip' => esc_html__( 'Button Title', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_single_product_button',
			'type'     => 'text',
			'default'  => esc_html__( 'Buy Now', 'buy-now-woo' ),
		];

		$settings[] = [
			'name' => esc_html__( 'Reset Cart before Buy Now', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_single_product_reset_cart',
			'type' => 'checkbox',
		];

		$settings[] = [
			'name' => esc_html__( 'Remove Quantity input', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_single_product_remove_quantity',
			'type' => 'checkbox',
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_settings_end',
		];

		return apply_filters( 'buy_now_woo_general_settings', $settings );
	}

	/**
	 * Gets customize settings.
	 *
	 * @return array
	 */
	public function get_customize() {
		$settings = [];

		$settings[] = [
			'name' => esc_html__( 'Customize Settings', 'buy-now-woo' ),
			'type' => 'title',
			'desc' => esc_html__( 'The following options are used to configure Buy Now button style.',
				'buy-now-woo' ),
			'id'   => 'buy_now_woo_settings_start',
		];

		$settings[] = [
			'name'     => esc_html__( 'Button style', 'buy-now-woo' ),
			'desc_tip' => esc_html__( 'Use theme style or customize', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_customize',
			'type'     => 'radio',
			'default'  => 'theme',
			'options'  => [
				'theme'     => esc_html__( 'Theme style (default)', 'buy-now-woo' ),
				'customize' => esc_html__( 'Customize', 'buy-now-woo' ),
			],
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_customize_end',
		];

		// Normal colors.
		$settings[] = [
			'name' => esc_html__( 'Normal colors', 'buy-now-woo' ),
			'type' => 'title',
			'id'   => 'buy_now_woo_normal_colors',
		];

		$settings[] = [
			'name'     => esc_html__( 'Text color', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_button_color',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'autoload' => false,
			'desc_tip' => true,
		];

		$settings[] = [
			'name'     => esc_html__( 'Background color', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_button_bgcolor',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'autoload' => false,
			'desc_tip' => true,
		];

		$settings[] = [
			'name'     => esc_html__( 'Border color', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_button_border_color',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'autoload' => false,
			'desc_tip' => true,
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_colors_end',
		];

		// Hover colors.
		$settings[] = [
			'name' => esc_html__( 'Hover colors', 'buy-now-woo' ),
			'type' => 'title',
			'id'   => 'buy_now_woo_hover_colors',
		];

		$settings[] = [
			'name'     => esc_html__( 'Text color', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_button_hover_color',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'autoload' => false,
			'desc_tip' => true,
		];

		$settings[] = [
			'name'     => esc_html__( 'Background color', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_button_hover_bgcolor',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'autoload' => false,
			'desc_tip' => true,
		];

		$settings[] = [
			'name'     => esc_html__( 'Border color', 'buy-now-woo' ),
			'id'       => 'buy_now_woo_button_hover_border_color',
			'type'     => 'color',
			'css'      => 'width:6em;',
			'autoload' => false,
			'desc_tip' => true,
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_hover_colors_end',
		];

		// Dimensions.
		$settings[] = [
			'name' => esc_html__( 'Dimensions', 'buy-now-woo' ),
			'type' => 'title',
			'id'   => 'buy_now_woo_dimensions',
		];

		$settings[] = [
			'name' => esc_html__( 'Padding', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_button_padding',
			'type' => 'wsb_dimensions',
		];

		$settings[] = [
			'name' => esc_html__( 'Margin', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_button_margin',
			'type' => 'wsb_dimensions',
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_dimensions_end',
		];

		// Size.
		$settings[] = [
			'name' => esc_html__( 'Size', 'buy-now-woo' ),
			'type' => 'title',
			'id'   => 'buy_now_woo_sizes',
		];

		$settings[] = [
			'name' => esc_html__( 'Width', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_button_width',
			'type' => 'wsb_size',
		];

		$settings[] = [
			'name' => esc_html__( 'Height', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_button_height',
			'type' => 'wsb_size',
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_sizes_end',
		];

		// Additional CSS.
		$settings[] = [
			'name' => esc_html__( 'Additional CSS', 'buy-now-woo' ),
			'type' => 'title',
			'id'   => 'buy_now_woo_additional_css',
		];

		$settings[] = [
			'name' => esc_html__( 'CSS code', 'buy-now-woo' ),
			'id'   => 'buy_now_woo_button_additional_css',
			'type' => 'textarea',
			'css'  => 'height: 160px;',
		];

		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'buy_now_woo_additional_css_end',
		];

		return apply_filters( 'buy_now_woo_customize_settings', $settings );
	}

	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;
		$settings = $this->get_settings( $current_section );
		\WC_Admin_Settings::save_fields( $settings );
	}
}
