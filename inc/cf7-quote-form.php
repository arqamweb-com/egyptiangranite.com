<?php
/**
 * Quote request form (Contact Form 7).
 *
 * The form is created once in CF7 by the theme setup; after that it is edited
 * from Contact → Contact Forms. Field styling lives in src/input.css under
 * .gg-quote-form, so the markup below only carries layout classes.
 *
 * @package GraniteGateway
 */

defined( 'ABSPATH' ) || exit;

const GG_QUOTE_FORM_TITLE  = 'Quote Request';
const GG_QUOTE_FORM_OPTION = 'gg_quote_form_id';

/**
 * CF7 form template (same fields as the original theme form).
 */
function gg_quote_form_template() {
	$products = array(
		'Black Aswan Granite', 'Red Aswan Granite', 'Light Red Aswan Granite', 'Medium Red Aswan Granite',
		'Dark Red Aswan Granite', 'Red Forsan Granite', 'Red Horus Granite', 'Red Nefertiti Granite',
		'New Halayeb Granite', 'Halayeb Granite', 'White Halayeb Granite', 'Bianco Halayeb Granite',
		'Rosa El Nasr Granite', 'Rosa Hody Granite', 'Medium Rosa Hody Granite', 'Dark Rosa Hody Granite',
		'Light Rosa Granite', 'Dark Rosa Granite', 'Rosa Katrina Granite', 'Rose Borino Granite',
		'Gandola Aswan Granite', 'Gandolla Granite', 'Grey Shirka Granite', 'Sherka Grey Granite',
		'Karnak Grey Granite', 'Grey Sholman Granite', 'Yellow Verdi Granite', 'Green Verdi Granite',
		'Verdi Ghazal Granite', 'Royal Granite',
	);
	$forms    = array( 'Blocks', 'Slabs', 'Tiles', 'Stairs', 'Countertops', 'Monuments', 'Cubes/Pavers', 'Cut-to-size' );
	$finishes = array( 'Polished', 'Honed', 'Flamed', 'Brushed', 'Sandblasted', 'Bush-Hammered', 'Tumbled', 'Split Face', 'Leathered', 'Acid Washed', 'Not Sure' );

	$opts = function ( $list ) {
		return '"Select…" "' . implode( '" "', $list ) . '"';
	};

	$products = $opts( $products );
	$forms    = $opts( $forms );
	$finishes = $opts( $finishes );

	$label = 'block text-sm font-medium mb-1.5';

	return <<<CF7
<div class="space-y-5">
<div class="grid sm:grid-cols-2 gap-4">
<div><label for="fullName" class="{$label}">Full Name *</label>[text* fullName id:fullName maxlength:200 autocomplete:name]</div>
<div><label for="company" class="{$label}">Company Name *</label>[text* company id:company maxlength:200 autocomplete:organization]</div>
<div><label for="country" class="{$label}">Country *</label>[text* country id:country maxlength:200 autocomplete:country-name]</div>
<div><label for="email" class="{$label}">Email *</label>[email* email id:email maxlength:200 autocomplete:email]</div>
<div><label for="whatsapp" class="{$label}">WhatsApp Number</label>[text whatsapp id:whatsapp maxlength:200 autocomplete:tel]</div>
<div><label for="product" class="{$label}">Product Interested In</label>[select product id:product first_as_label {$products}]</div>
<div><label for="form" class="{$label}">Required Form</label>[select form id:form first_as_label {$forms}]</div>
<div><label for="finish" class="{$label}">Finish</label>[select finish id:finish first_as_label {$finishes}]</div>
<div><label for="thickness" class="{$label}">Thickness (cm)</label>[text thickness id:thickness maxlength:200 placeholder "e.g. 2, 3, custom"]</div>
<div><label for="quantity" class="{$label}">Quantity</label>[text quantity id:quantity maxlength:200 placeholder "e.g. 1 container, 500 sqm"]</div>
<div><label for="port" class="{$label}">Destination Port</label>[text port id:port maxlength:200 placeholder "e.g. Jebel Ali, Hamburg"]</div>
<div><label for="projectType" class="{$label}">Project Type</label>[text projectType id:projectType maxlength:200 placeholder "Commercial, hotel, residential…"]</div>
</div>
<div><label for="drawings" class="{$label}">Upload drawings / BOQ</label>[file drawings id:drawings limit:10mb filetypes:pdf|jpg|jpeg|png|dwg|dxf|xls|xlsx|doc|docx|zip]<p class="mt-1.5 text-xs text-muted-foreground">PDF, images, DWG/DXF, Excel, Word or ZIP — up to 10 MB.</p></div>
<div><label for="message" class="{$label}">Message</label>[textarea message id:message x4 maxlength:2000 placeholder "Material, finish, sizes, quantity, port, and any other requirements."]</div>
[submit "Request Export Quote"]
<p class="text-xs text-muted-foreground text-center">Our team will review your request and reply with availability, recommended material, production options, packing method, and export quotation.</p>
</div>
CF7;
}

/**
 * Mail sent to the export team.
 */
function gg_quote_form_mail() {
	return array(
		'active'             => true,
		'subject'            => '[Quote Request] [fullName] — [_site_title]',
		'sender'             => '[_site_title] <wordpress@egyptiangranite.com>',
		'recipient'          => 'info@egyptiangranite.com',
		'body'               => "Full Name: [fullName]\nCompany: [company]\nCountry: [country]\nEmail: [email]\nWhatsApp: [whatsapp]\nProduct: [product]\nRequired Form: [form]\nFinish: [finish]\nThickness (cm): [thickness]\nQuantity: [quantity]\nDestination Port: [port]\nProject Type: [projectType]\n\nMessage:\n[message]\n\n-- \nSent from [_url]",
		'additional_headers' => 'Reply-To: [fullName] <[email]>',
		'attachments'        => '[drawings]',
		'use_html'           => false,
		'exclude_blank'      => true,
	);
}

/**
 * Create the CF7 form if it does not exist yet. Never overwrites admin edits.
 *
 * @return int Form ID, or 0 when CF7 is not active.
 */
function gg_ensure_quote_form() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
		return 0;
	}

	$id = gg_quote_form_id();
	if ( $id ) {
		return $id;
	}

	$form = WPCF7_ContactForm::get_template( array( 'title' => GG_QUOTE_FORM_TITLE ) );
	$form->set_properties(
		array(
			'form'     => gg_quote_form_template(),
			'mail'     => gg_quote_form_mail(),
			'messages' => array_merge(
				$form->prop( 'messages' ),
				array(
					'mail_sent_ok'     => 'Thank you — your quote request has been sent. Our export team will reply shortly.',
					'mail_sent_ng'     => 'Sorry, your request could not be sent. Please try again or email info@egyptiangranite.com.',
					'validation_error' => 'Please check the required fields and try again.',
				)
			),
		)
	);
	$id = (int) $form->save();

	if ( $id ) {
		update_option( GG_QUOTE_FORM_OPTION, $id );
	}
	return $id;
}
// Covers sites where the theme is already active (e.g. after a deploy).
add_action( 'admin_init', 'gg_ensure_quote_form' );

/**
 * ID of the quote form if it still exists in CF7.
 */
function gg_quote_form_id() {
	$id = (int) get_option( GG_QUOTE_FORM_OPTION );
	if ( $id && 'wpcf7_contact_form' === get_post_type( $id ) ) {
		return $id;
	}
	return 0;
}

/**
 * Print the quote form.
 */
function gg_quote_form() {
	$id = function_exists( 'wpcf7_contact_form' ) ? gg_quote_form_id() : 0;

	if ( ! $id ) {
		echo '<p class="text-sm text-muted-foreground">To request a quote, email <a href="mailto:info@egyptiangranite.com" class="text-gold">info@egyptiangranite.com</a>.</p>';
		return;
	}

	echo do_shortcode( '[contact-form-7 id="' . $id . '" title="' . esc_attr( GG_QUOTE_FORM_TITLE ) . '"]' );
}

/**
 * The template has its own layout, so skip CF7's auto <p>/<br> for this form only.
 */
function gg_quote_form_autop( $autop ) {
	$form = class_exists( 'WPCF7_ContactForm' ) ? WPCF7_ContactForm::get_current() : null;
	if ( $form && $form->id() === gg_quote_form_id() ) {
		return false;
	}
	return $autop;
}
add_filter( 'wpcf7_autop_or_not', 'gg_quote_form_autop' );
