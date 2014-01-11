<?php
/**
 * The forms for the widget panel
 *
 * @package PostsInSidebar
 */

/**
 * Create a form label to be used in the widget panel.
 *
 * @since 1.12
 * @param string $label The label to display.
 * @param string $id The id of the label.
 */
function pis_form_label( $label, $id ) {
	echo '<label for="' . esc_attr( $id ) . '">' . $label . '</label>';
}

/**
 * Create a form text input to be used in the widget panel.
 *
 * @since 1.12
 * @param string $label The label to display.
 * @param string $id The id of the label.
 * @param string $name The name of the input form.
 * @param string $value The values of the input form.
 * @param string $comment An optional comment to display. It is displayed below the input form.
 * @param string $style An optional inline style.
 * @uses pis_form_label
 */
function pis_form_input_text( $label, $id, $name, $value, $comment = '', $style = '' ) {
	if ( $style ) $style = ' style="' . $style . '" ';
	echo '<p' . $style . '>';
	pis_form_label( $label, $id );
	echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
	if ( $comment ) echo '<br /><em>' . $comment . '</em>';
	echo '</p>';
}

/**
 * Create a form textarea to be used in the widget panel.
 *
 * @param string $label The label to display.
 * @param string $id The id of the label.
 * @param string $name The name of the textarea form.
 * @param string $text The text to display.
 * @param string $style An optional inline style.
 * @param string $comment An optional comment to display. It is displayed below the textarea form.
 * @since 1.12
 */
function pis_form_textarea( $label, $id, $name, $text,  $style = '', $comment = '' ) {
	echo '<p>';
	pis_form_label( $label, $id );
	if ( $style ) $style = ' style="' . $style . '"';
	echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="2" cols="10" class="widefat"' . $style . '>' . esc_html( $text ) . '</textarea>'; ?>
	<?php if ( $comment ) echo '<br /><em>' . $comment . '</em>';
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
 * @since 1.12
 */
function pis_form_checkbox( $label, $id, $name, $checked, $comment = '' ) {
	echo '<p>';
	echo '<input class="checkbox" type="checkbox" ' . $checked . ' id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" />&nbsp;';
	pis_form_label( $label, $id );
	if ( $comment ) echo '<br /><em>' . $comment . '</em>';
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
 * @since 1.12
 */
function pis_form_select( $label, $id, $name, $options, $value, $comment = '' ) {
	echo '<p>';
	pis_form_label( $label, $id );
	echo '<select name="' . $name . '">';
		foreach ( $options as $option ) {
			$selected = selected( $option['value'], $value, false );
			echo '<option ' . $selected . ' value="' . esc_attr( $option['value'] ) . '">' . esc_html( $option['desc'] ) . '</option>';
		}
	echo '</select>';
	if ( $comment ) echo '<br /><em>' . $comment . '</em>';
	echo '</p>';
}