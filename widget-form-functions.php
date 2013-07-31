<?php
/**
 * Create a form label to be used with the widget.
 *
 * @since 1.12
 */
function pis_form_label( $label, $id ) {
	echo '<label for="' . esc_attr( $id ) . '">' . $label . '</label>';
}

/**
 * Create a form text input to be used with the widget.
 *
 * @since 1.12
 * @uses pis_form_label
 */
function pis_form_input_text( $label, $id, $name, $value, $comment = '' ) {
	echo '<p>';
	pis_form_label( $label, $id );
	echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="widefat" />';
	if ( $comment ) echo '<br /><em>' . $comment . '</em>';
	echo '</p>';
}

/**
 * Create a form textarea to be used with the widget.
 *
 * @since 1.12
 */
function pis_form_textarea( $label, $id, $name, $text,  $style = '', $comment = '' ) {
	echo '<p>';
	pis_form_label( $label, $id );
	if ( $style ) $style = ' style="' . $style . '"';
	echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" rows="2" cols="10" class="widefat"' . esc_attr( $style ) . '>' . esc_html( $text ) . '</textarea>'; ?>
	<?php if ( $comment ) echo '<br /><em>' . $comment . '</em>';
	echo '</p>';
}

/**
 * Create a form checkbox to be used with the widget.
 *
 * @since 1.12
 */
function pis_form_checkbox( $label, $id, $name, $checked, $comment = '' ) {
	echo '<p>';
	echo '<input class="checkbox" type="checkbox" ' . $checked . ' id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" />&nbsp;';
	pis_form_label( $label, $id );
	if ( $comment ) echo '<br /><em>' . $comment . '</em>';
	echo '</p>';
}
