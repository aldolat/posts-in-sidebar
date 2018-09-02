<?php
/**
 * This file contains the functions used in the widget's forms.
 *
 * @package PostsInSidebar
 * @since 1.12
 */

/**
 * Prevent direct access to this file.
 *
 * @since 2.0
 */
if ( ! defined( 'WPINC' ) ) {
	exit( 'No script kiddies please!' );
}

/**
 * Create a form label to be used in the widget panel.
 *
 * @since 1.12
 * @param string $label The label to display.
 * @param string $id The id of the label.
 */
function pis_form_label( $label, $id ) {
	echo '<label for="' . esc_attr( $id ) . '">' . wp_kses_post( $label ) . '</label>';
}

/**
 * Create a form text input to be used in the widget panel.
 *
 * @since 1.12
 * @param string $label The label to display.
 * @param string $id The id of the label.
 * @param string $name The name of the input form.
 * @param string $value The values of the input form.
 * @param string $placeholder The HTML placeholder for the input form.
 * @param string $comment An optional comment to display. It is displayed below the input form.
 * @param string $style An optional inline style.
 * @uses pis_form_label
 */
function pis_form_input_text( $label, $id, $name, $value, $placeholder = '', $comment = '', $style = '' ) {
	if ( $style ) {
		echo '<p style="' . esc_attr( $style ) . '">';
	} else {
		echo '<p>';
	}
	pis_form_label( $label, $id );
	echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '" class="widefat" />';
	if ( $comment ) {
		echo '<br /><em>' . wp_kses_post( $comment ) . '</em>';
	}
	echo '</p>';
}

/**
 * Create a form textarea to be used in the widget panel.
 *
 * @param string $label The label to display.
 * @param string $id The id of the label.
 * @param string $name The name of the textarea form.
 * @param string $text The text to display.
 * @param string $placeholder The HTML placeholder for the input form.
 * @param string $style An optional inline style.
 * @param string $comment An optional comment to display. It is displayed below the textarea form.
 * @since 1.12
 */
function pis_form_textarea( $label, $id, $name, $text, $placeholder = '', $style = '', $comment = '' ) {
	echo '<p>';
	pis_form_label( $label, $id );
	echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="2" cols="10" placeholder="' . esc_attr( $placeholder ) . '" class="widefat" style="' . esc_attr( $style ) . '">' . esc_textarea( $text ) . '</textarea>';
	if ( $comment ) {
		echo '<br /><em>' . wp_kses_post( $comment ) . '</em>';
	}
	echo '</p>';
}


/**
 * Create a form checkbox to be used in the widget panel.
 *
 * @param string $label The label to display.
 * @param string $id The id of the label.
 * @param string $name The name of the checkbox form.
 * @param string $checked If the option is checked.
 * @param string $comment An optional comment to display. It is displayed below the checkbox form.
 * @param string $class The custom class for the p element.
 * @since 1.12
 */
function pis_form_checkbox( $label, $id, $name, $checked, $comment = '', $class = '' ) {
	$class = rtrim( 'pis-checkbox ' . $class );
	echo '<p class="' . esc_attr( $class ) . '">';
	echo '<input class="checkbox" type="checkbox" ' . checked( $checked, true, false ) . ' id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" />&nbsp;';
	pis_form_label( $label, $id );
	if ( $comment ) {
		echo '<br /><em>' . wp_kses_post( $comment ) . '</em>';
	}
	echo '</p>';
}


/**
 * Create a form select to be used in the widget panel.
 *
 * @param string $label The label to display.
 * @param string $id The id of the label.
 * @param string $name The name of the select form.
 * @param string $options The options to display.
 * @param string $value The values of the select form.
 * @param string $comment An optional comment to display. It is displayed below the select form.
 * @param string $class The custom class for the select element.
 * @since 1.12
 */
function pis_form_select( $label, $id, $name, $options, $value, $comment = '', $class = '' ) {
	echo '<p>';
	pis_form_label( $label, $id );
	$class = rtrim( 'pis-select ' . $class );
	echo '&nbsp;<select name="' . esc_attr( $name ) . '" class="' . esc_attr( $class ) . '">';
	foreach ( $options as $option ) {
		echo '<option ' . selected( $option['value'], $value, false ) . ' value="' . esc_attr( $option['value'] ) . '">' . esc_html( $option['desc'] ) . '</option>';
	}
	echo '</select>';
	if ( $comment ) {
		echo '<br /><em>' . wp_kses_post( $comment ) . '</em>';
	}
	echo '</p>';
}
