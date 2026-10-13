<?php
/**
 * Granite Gateway — install / setup: custom post types, auto pages,
 * editable menus, seeded content, and the Appearance → Setup screen.
 *
 * @package Granite_Gateway
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Custom post types.
 * ---------------------------------------------------------------------- */

function gg_register_post_types() {

	register_post_type(
		'gg_product_faq',
		array(
			'labels'        => array(
				'name'          => __( 'Product FAQs', 'granite-gateway' ),
				'singular_name' => __( 'Product FAQ', 'granite-gateway' ),
				'add_new_item'  => __( 'Add New FAQ', 'granite-gateway' ),
				'edit_item'     => __( 'Edit FAQ', 'granite-gateway' ),
				'menu_name'     => __( 'Product FAQs', 'granite-gateway' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => 'edit.php?post_type=granite_product',
			'menu_icon'     => 'dashicons-editor-help',
			'supports'      => array( 'title' ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'granite_product',
		array(
			'labels'       => array(
				'name'          => __( 'Granite Products', 'granite-gateway' ),
				'singular_name' => __( 'Granite Product', 'granite-gateway' ),
				'add_new_item'  => __( 'Add New Granite Product', 'granite-gateway' ),
				'edit_item'     => __( 'Edit Granite Product', 'granite-gateway' ),
				'menu_name'     => __( 'Granite Products', 'granite-gateway' ),
			),
			'public'       => true,
			'has_archive'  => false, // The "Products" page (slug egyptian-granite) is the listing.
			'menu_icon'    => 'dashicons-screenoptions',
			'menu_position' => 5,
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'rewrite'      => array(
				'slug'       => 'egyptian-granite',
				'with_front' => false,
			),
			'show_in_rest' => true,
		)
	);

}
add_action( 'init', 'gg_register_post_types' );

/* -------------------------------------------------------------------------
 * Page definitions (slug => title). Order = menu order.
 * ---------------------------------------------------------------------- */

function gg_pages() {
	return array(
		'home'             => 'Home',
		'about'            => 'About',
		'egyptian-granite' => 'Egyptian Granite Products',
		'applications'     => 'Applications',
		'finishes'         => 'Finishes',
		'factory'          => 'Factory & Production',
		'export'           => 'Export & Shipping',
		'quality'          => 'Quality & Certificates',
		'projects'         => 'Projects',
		'blog'             => 'Blog',
		'contact'          => 'Contact',
	);
}

/* -------------------------------------------------------------------------
 * One-shot setup on theme activation.
 * ---------------------------------------------------------------------- */

function gg_after_switch_theme() {
	gg_register_post_types();

	$page_ids = gg_create_pages();
	gg_seed_cpt( 'granite_product', 'products' );
	gg_migrate_gg_articles();
	gg_seed_articles();
	gg_create_menus( $page_ids );

	// Front page.
	if ( ! empty( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
	}

	// Pretty permalinks (required for CPT single URLs).
	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}

	flush_rewrite_rules();
	update_option( 'gg_setup_done', GG_VERSION );
}
add_action( 'after_switch_theme', 'gg_after_switch_theme' );

/**
 * Create the WP pages. Returns slug => ID map.
 */
function gg_create_pages() {
	$ids   = array();
	$order = 0;
	foreach ( gg_pages() as $slug => $title ) {
		$order += 10;
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$ids[ $slug ] = $existing->ID;
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
				'menu_order'   => $order,
			)
		);
		if ( ! is_wp_error( $id ) ) {
			$ids[ $slug ] = $id;
		}
	}
	return $ids;
}

/**
 * Seed a CPT from /inc/seed/{group}.json + matching HTML files. Idempotent.
 */
function gg_seed_cpt( $post_type, $group ) {
	$dir  = get_template_directory() . '/inc/seed/';
	$json = $dir . $group . '.json';
	if ( ! file_exists( $json ) ) {
		return;
	}
	$items = json_decode( file_get_contents( $json ), true ); // phpcs:ignore
	if ( ! is_array( $items ) ) {
		return;
	}
	foreach ( $items as $item ) {
		$slug = sanitize_title( $item['slug'] );
		if ( get_page_by_path( $slug, OBJECT, $post_type ) ) {
			continue;
		}
		$html_file = $dir . $group . '/' . $slug . '.html';
		$content   = file_exists( $html_file ) ? file_get_contents( $html_file ) : ''; // phpcs:ignore
		wp_insert_post(
			array(
				'post_title'   => $item['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => $post_type,
				'post_excerpt' => isset( $item['excerpt'] ) ? $item['excerpt'] : '',
				'post_content' => $content,
			)
		);
	}
}

/**
 * Seed blog articles as native WordPress posts. Idempotent.
 */
function gg_seed_articles() {
	$dir  = get_template_directory() . '/inc/seed/';
	$json = $dir . 'articles.json';
	if ( ! file_exists( $json ) ) {
		return;
	}
	$items = json_decode( file_get_contents( $json ), true ); // phpcs:ignore
	if ( ! is_array( $items ) ) {
		return;
	}
	foreach ( $items as $item ) {
		$slug = sanitize_title( $item['slug'] );
		if ( get_page_by_path( $slug, OBJECT, 'post' ) ) {
			continue;
		}
		$html_file = $dir . 'articles/' . $slug . '.html';
		$content   = file_exists( $html_file ) ? file_get_contents( $html_file ) : ''; // phpcs:ignore

		$cat_name = isset( $item['category'] ) ? $item['category'] : 'Egyptian Granite';
		$cat_id   = gg_ensure_category( $cat_name );

		$post_id = wp_insert_post(
			array(
				'post_title'   => $item['title'],
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'post',
				'post_excerpt' => isset( $item['excerpt'] ) ? $item['excerpt'] : '',
				'post_content' => $content,
			)
		);
		if ( ! is_wp_error( $post_id ) && $cat_id ) {
			wp_set_post_categories( $post_id, array( $cat_id ) );
		}
	}
}

/**
 * Migrate any existing gg_article posts to native WP posts. Idempotent.
 */
function gg_migrate_gg_articles() {
	global $wpdb;

	$ids = $wpdb->get_col( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		"SELECT ID FROM {$wpdb->posts} WHERE post_type = 'gg_article' AND post_status = 'publish'"
	);
	if ( empty( $ids ) ) {
		return;
	}

	$cat_map = array(
		'best-egyptian-granite-outdoor-flooring'  => 'Granite Applications',
		'black-aswan-granite-export-guide'         => 'Granite Types',
		'egyptian-granite-complete-buyer-guide'    => 'Egyptian Granite Guide',
		'egyptian-granite-finishes-explained'      => 'Granite Finishes',
		'egyptian-granite-slabs-vs-tiles'          => 'Egyptian Granite Guide',
		'how-to-import-granite-from-egypt'         => 'Import from Egypt',
		'new-halayeb-granite-modern-grey'          => 'Granite Types',
		'red-aswan-granite-history-uses'           => 'Granite Types',
		'top-egyptian-granite-types'               => 'Granite Types',
		'why-architects-choose-granite-from-egypt' => 'Project Inspiration',
	);

	foreach ( $ids as $post_id ) {
		$post_id = (int) $post_id;
		$wpdb->update( $wpdb->posts, array( 'post_type' => 'post' ), array( 'ID' => $post_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		clean_post_cache( $post_id );

		$post = get_post( $post_id );
		if ( $post && isset( $cat_map[ $post->post_name ] ) ) {
			$cat_id = gg_ensure_category( $cat_map[ $post->post_name ] );
			if ( $cat_id ) {
				wp_set_post_categories( $post_id, array( $cat_id ) );
			}
		}
	}
}

/**
 * Get or create a category by name and return its term ID.
 */
function gg_ensure_category( $name ) {
	$slug = sanitize_title( $name );
	$cat  = get_category_by_slug( $slug );
	if ( $cat ) {
		return $cat->term_id;
	}
	$result = wp_insert_term( $name, 'category' );
	return is_wp_error( $result ) ? 0 : $result['term_id'];
}

/**
 * Build a single menu from a spec and assign it to a location.
 *
 * @param string $name     Menu name.
 * @param string $location Theme location.
 * @param array  $items    List of [type,'object'=>..,'object_id'=>..,'title'=>..,'url'=>..].
 */
function gg_build_menu( $name, $location, $items ) {
	$menu = wp_get_nav_menu_object( $name );
	if ( ! $menu ) {
		$menu_id = wp_create_nav_menu( $name );
	} else {
		$menu_id = $menu->term_id;
		// Don't duplicate items if it already has some.
		$existing = wp_get_nav_menu_items( $menu_id );
		if ( ! empty( $existing ) ) {
			gg_assign_menu_location( $location, $menu_id );
			return;
		}
	}
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	foreach ( $items as $i => $it ) {
		$args = array(
			'menu-item-title'   => $it['title'],
			'menu-item-status'  => 'publish',
			'menu-item-position' => ( $i + 1 ) * 10,
		);
		if ( 'custom' === $it['type'] ) {
			$args['menu-item-type'] = 'custom';
			$args['menu-item-url']  = $it['url'];
		} else {
			$args['menu-item-type']      = 'post_type';
			$args['menu-item-object']    = $it['object'];
			$args['menu-item-object-id'] = $it['object_id'];
		}
		wp_update_nav_menu_item( $menu_id, 0, $args );
	}
	gg_assign_menu_location( $location, $menu_id );
}

function gg_assign_menu_location( $location, $menu_id ) {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Helper: get a CPT post ID by slug.
 */
function gg_cpt_id( $slug, $type ) {
	$p = get_page_by_path( $slug, OBJECT, $type );
	return $p ? $p->ID : 0;
}

/**
 * Create the header + 3 footer menus and assign them.
 */
function gg_create_menus( $page_ids ) {
	$pi = function ( $slug ) use ( $page_ids ) {
		return isset( $page_ids[ $slug ] ) ? $page_ids[ $slug ] : 0;
	};

	// --- Header ---
	$header = array();
	foreach ( array(
		'home'             => 'Home',
		'about'            => 'About',
		'egyptian-granite' => 'Products',
		'applications'     => 'Applications',
		'finishes'         => 'Finishes',
		'factory'          => 'Factory',
		'export'           => 'Export',
		'blog'             => 'Blog',
		'contact'          => 'Contact',
	) as $slug => $label ) {
		if ( $pi( $slug ) ) {
			$header[] = array( 'type' => 'post_type', 'object' => 'page', 'object_id' => $pi( $slug ), 'title' => $label );
		}
	}
	gg_build_menu( 'Header Menu', 'primary', $header );

	// --- Footer: Featured Granite (products) ---
	$prod_slugs = array(
		'black-aswan-granite'  => 'Black Aswan Granite',
		'red-aswan-granite'    => 'Red Aswan Granite',
		'new-halayeb-granite'  => 'New Halayeb Granite',
		'rosa-el-nasr-granite' => 'Rosa El Nasr Granite',
		'rosa-hody-granite'    => 'Rosa Hody Granite',
		'gandola-aswan-granite' => 'Gandola Aswan Granite',
	);
	$fp = array();
	foreach ( $prod_slugs as $slug => $label ) {
		$id = gg_cpt_id( $slug, 'granite_product' );
		if ( $id ) {
			$fp[] = array( 'type' => 'post_type', 'object' => 'granite_product', 'object_id' => $id, 'title' => $label );
		}
	}
	gg_build_menu( 'Footer — Featured Granite', 'footer_products', $fp );

	// --- Footer: Company ---
	$company = array();
	foreach ( array(
		'about'    => 'About',
		'factory'  => 'Factory',
		'quality'  => 'Quality',
		'export'   => 'Export',
		'projects' => 'Projects',
		'contact'  => 'Contact',
	) as $slug => $label ) {
		if ( $pi( $slug ) ) {
			$company[] = array( 'type' => 'post_type', 'object' => 'page', 'object_id' => $pi( $slug ), 'title' => $label );
		}
	}
	gg_build_menu( 'Footer — Company', 'footer_company', $company );

	// --- Footer: Resources ---
	$resources = array();
	foreach ( array(
		'blog'         => 'Blog',
		'finishes'     => 'Finishes',
		'applications' => 'Applications',
		'contact'      => 'Request Quote',
	) as $slug => $label ) {
		if ( $pi( $slug ) ) {
			$resources[] = array( 'type' => 'post_type', 'object' => 'page', 'object_id' => $pi( $slug ), 'title' => $label );
		}
	}
	// Granite Guide + Import Guide — link to seeded native posts.
	$granite_guide_id = gg_cpt_id( 'egyptian-granite-complete-buyer-guide', 'post' );
	if ( $granite_guide_id ) {
		$resources[] = array( 'type' => 'post_type', 'object' => 'post', 'object_id' => $granite_guide_id, 'title' => 'Granite Guide' );
	}
	$import_guide_id = gg_cpt_id( 'how-to-import-granite-from-egypt', 'post' );
	if ( $import_guide_id ) {
		$resources[] = array( 'type' => 'post_type', 'object' => 'post', 'object_id' => $import_guide_id, 'title' => 'Import Guide' );
	}
	gg_build_menu( 'Footer — Resources', 'footer_resources', $resources );
}

/* -------------------------------------------------------------------------
 * Admin notice + Appearance → Setup screen.
 * ---------------------------------------------------------------------- */

function gg_admin_notice() {
	if ( get_option( 'gg_setup_done' ) === GG_VERSION ) {
		return;
	}
	$url = admin_url( 'themes.php?page=gg-setup' );
	echo '<div class="notice notice-info is-dismissible"><p><strong>Granite Gateway:</strong> Finish setup — <a href="' . esc_url( $url ) . '">run the theme setup</a> to create pages, menus, and seed products &amp; articles.</p></div>';
}
add_action( 'admin_notices', 'gg_admin_notice' );

function gg_register_setup_page() {
	add_theme_page(
		__( 'Granite Gateway Setup', 'granite-gateway' ),
		__( 'Granite Gateway Setup', 'granite-gateway' ),
		'manage_options',
		'gg-setup',
		'gg_render_setup_page'
	);
}
add_action( 'admin_menu', 'gg_register_setup_page' );

function gg_render_setup_page() {
	if ( isset( $_POST['gg_run_setup'] ) && check_admin_referer( 'gg_run_setup' ) ) {
		gg_after_switch_theme();
		echo '<div class="notice notice-success"><p>Setup complete. Pages, menus, products and articles are ready.</p></div>';
	}

	$front_ok = ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0 );
	$perma_ok = ( '' !== get_option( 'permalink_structure' ) );
	?>
	<div class="wrap">
		<h1>Granite Gateway — Setup</h1>
		<p>This theme auto-creates its pages, editable menus, and seeds the Products &amp; Blog content. Re-run any time (it is safe and idempotent).</p>

		<form method="post">
			<?php wp_nonce_field( 'gg_run_setup' ); ?>
			<p><button type="submit" name="gg_run_setup" value="1" class="button button-primary">Run / Re-run Setup</button></p>
		</form>

		<h2>Pages</h2>
		<table class="widefat striped" style="max-width:640px">
			<thead><tr><th>Page</th><th>Slug</th><th>Status</th></tr></thead>
			<tbody>
			<?php foreach ( gg_pages() as $slug => $title ) :
				$p = get_page_by_path( $slug ); ?>
				<tr>
					<td><?php echo esc_html( $title ); ?></td>
					<td><code><?php echo esc_html( $slug ); ?></code></td>
					<td><?php echo $p ? '✅ Exists' : '❌ Missing'; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<h2>Content</h2>
		<ul>
			<li>Granite Products: <strong><?php echo (int) wp_count_posts( 'granite_product' )->publish; ?></strong> published</li>
			<li>Blog Articles: <strong><?php echo (int) wp_count_posts( 'post' )->publish; ?></strong> native posts published</li>
		</ul>

		<h2>Menus</h2>
		<p>Edit these under <a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>">Appearance → Menus</a>:</p>
		<ul>
			<li>Header Menu → <em>Header Menu</em> location</li>
			<li>Footer — Featured Granite → <em>Footer — Featured Granite</em></li>
			<li>Footer — Company → <em>Footer — Company</em></li>
			<li>Footer — Resources → <em>Footer — Resources</em></li>
		</ul>

		<h2>Settings status</h2>
		<ul>
			<li>Front page set to "Home": <?php echo $front_ok ? '✅' : '❌ — set it in Settings → Reading'; ?></li>
			<li>Pretty permalinks: <?php echo $perma_ok ? '✅' : '❌ — open Settings → Permalinks and Save'; ?></li>
		</ul>

		<h2>After setup</h2>
		<ol>
			<li>Go to <strong>Settings → Permalinks</strong> and click <strong>Save</strong> (flushes rewrite rules for product/article URLs).</li>
			<li>Customize the header &amp; footer menus under <strong>Appearance → Menus</strong>.</li>
		</ol>
	</div>
	<?php
}
