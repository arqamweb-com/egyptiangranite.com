<?php
$args = array(
        'post_type' => 'granite_product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
);

$products_query = new WP_Query($args);

$gg_get_catalog_field = static function ($field, $post_id) {
    return function_exists('get_field') ? get_field($field, $post_id) : null;
};

$gg_catalog_label = static function ($value) {
    if (is_array($value)) {
        $value = $value['label'] ?? $value['value'] ?? $value['name'] ?? $value['application_name'] ?? '';
    }

    return trim(wp_strip_all_tags((string) $value));
};

$gg_catalog_terms = static function ($values) use ($gg_catalog_label) {
    if (empty($values)) {
        return array();
    }

    if (!is_array($values)) {
        $values = array($values);
    }

    $terms = array();
    foreach ($values as $value) {
        $label = $gg_catalog_label($value);
        $slug = sanitize_title($label);

        if (!$slug) {
            continue;
        }

        $terms[$slug] = ucwords(str_replace(array('-', '_'), ' ', $label));
    }

    return $terms;
};

$catalog_colors = array();
$catalog_forms = array();
$catalog_finishes = array();

if ($products_query->have_posts()) {
    while ($products_query->have_posts()) {
        $products_query->the_post();
        $post_id = get_the_ID();

        $color = $gg_catalog_label($gg_get_catalog_field('stone_color_group', $post_id) ?: $gg_get_catalog_field('color', $post_id));
        $color_slug = sanitize_title($color);
        if ($color_slug) {
            $catalog_colors[$color_slug] = ucwords(str_replace(array('-', '_'), ' ', $color));
        }

        $catalog_forms += $gg_catalog_terms($gg_get_catalog_field('stone_forms', $post_id));
        $catalog_finishes += $gg_catalog_terms($gg_get_catalog_field('stone_finishes', $post_id));
    }

    $products_query->rewind_posts();
}
?>

<main class="flex-1"><!--$-->
    <section class="relative hero-bg stone-grain pt-16 pb-20 md:pt-24 md:pb-28 border-b border-border overflow-hidden">
        <div class="absolute inset-0 grid-glow opacity-60"></div>
        <div class="relative mx-auto max-w-7xl px-4 md:px-6 text-center">
            <div class="inline-block text-[11px] uppercase tracking-[0.25em] text-gold mb-4">Egyptian Granite Catalog
            </div>
            <h1 class="font-display text-4xl md:text-6xl leading-[1.05] max-w-4xl mx-auto">Egyptian Granite
                Products</h1>
            <p class="mt-5 text-base md:text-lg text-muted-foreground max-w-2xl mx-auto">Explore our complete Egyptian
                granite collection, including Black Aswan, Red Aswan, New Halayeb, Halayeb, Rosa El Nasr, Rosa Hody,
                Gandola Aswan, Grey Shirka, Yellow Verdi, Green Verdi, Red Forsan, Red Horus, and other premium granite
                types from Egypt.</p></div>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <nav aria-label="Breadcrumb" class="text-xs text-muted-foreground">
            <ol class="flex flex-wrap items-center gap-1.5">
                <li class="flex items-center gap-1.5"><a href="<?php echo esc_url(home_url('/')); ?>"
                                                         class="hover:text-gold">Home</a>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-right h-3 w-3" aria-hidden="true">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </li>
                <li class="flex items-center gap-1.5"><span class="text-foreground">Egyptian Granite</span></li>
            </ol>
        </nav>
        <div class="mt-8 p-6 rounded-2xl bg-card border border-border space-y-5">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground"
                     aria-hidden="true">
                    <path d="m21 21-4.34-4.34"/>
                    <circle cx="11" cy="11" r="8"/>
                </svg>
                <input id="gg-catalog-search" placeholder="Search Egyptian granite by name, color, or application…"
                       class="w-full pl-10 pr-11 py-3 rounded-xl bg-background border border-border focus:border-gold focus:outline-none text-sm"
                       value=""/>
                <button id="gg-catalog-search-clear" type="button"
                        class="hidden absolute right-2 top-1/2 -translate-y-1/2 h-8 w-8 items-center justify-center rounded-md text-muted-foreground hover:text-foreground hover:bg-secondary transition"
                        aria-label="Clear search">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="h-4 w-4" aria-hidden="true">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
	            <div>
	                <div class="text-[10px] uppercase tracking-widest text-muted-foreground mb-2">Color</div>
	                <div data-filter-group="color" class="flex flex-wrap gap-2">
	                    <button data-filter-value="all" class="px-3 py-1.5 rounded-full text-xs transition border bg-foreground text-background border-foreground">
	                        All
	                    </button>
	                    <?php foreach ($catalog_colors as $color_value => $color_label) : ?>
	                        <button data-filter-value="<?php echo esc_attr($color_value); ?>" class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">
	                            <?php echo esc_html($color_label); ?>
	                        </button>
	                    <?php endforeach; ?>
	                </div>
	            </div>
	            <div class="grid md:grid-cols-2 gap-5">
	                <div>
	                    <div class="text-[10px] uppercase tracking-widest text-muted-foreground mb-2">Form</div>
	                    <div data-filter-group="form" class="flex flex-wrap gap-2">
	                        <button data-filter-value="all" class="px-3 py-1.5 rounded-full text-xs transition border bg-foreground text-background border-foreground">
	                            All
	                        </button>
	                        <?php foreach ($catalog_forms as $form_value => $form_label) : ?>
	                            <button data-filter-value="<?php echo esc_attr($form_value); ?>" class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">
	                                <?php echo esc_html($form_label); ?>
	                            </button>
	                        <?php endforeach; ?>
	                    </div>
	                </div>
	                <div>
	                    <div class="text-[10px] uppercase tracking-widest text-muted-foreground mb-2">Finish</div>
	                    <div data-filter-group="finish" class="flex flex-wrap gap-2">
	                        <button data-filter-value="all" class="px-3 py-1.5 rounded-full text-xs transition border bg-foreground text-background border-foreground">
	                            All
	                        </button>
	                        <?php foreach ($catalog_finishes as $finish_value => $finish_label) : ?>
	                            <button data-filter-value="<?php echo esc_attr($finish_value); ?>" class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">
	                                <?php echo esc_html($finish_label); ?>
	                            </button>
	                        <?php endforeach; ?>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="mt-6 text-sm text-muted-foreground"><span
	                    id="gg-count"><?php echo esc_html($products_query->post_count); ?> Egyptian granite types · Export-ready</span></div>
	        <div id="gg-no-results" class="mt-8 text-center text-muted-foreground py-12" style="display:none">No granite
	            types match your filters.
	            <button id="gg-clear-filters" type="button"
	                    class="text-gold underline ml-1">Clear filters
	            </button>
	        </div>

	        <?php if ($products_query->have_posts()) : ?>
            <div id="gg-catalog-grid" class="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                <?php while ($products_query->have_posts()) : $products_query->the_post(); ?>

                    <?php
	                    $post_id = get_the_ID();
	                    $name = get_the_title();
	                    $description = get_the_excerpt();
	                    $color = $gg_catalog_label($gg_get_catalog_field('stone_color_group', $post_id) ?: $gg_get_catalog_field('color', $post_id));
	                    $color_value = sanitize_title($color);
	                    $color_label = $color_value ? ucwords(str_replace(array('-', '_'), ' ', $color)) : '';
	                    $applications = $gg_get_catalog_field('stone_applications', $post_id) ?: $gg_get_catalog_field('applications', $post_id);
	                    $application_items = $gg_catalog_terms($applications);
	                    $form_items = $gg_catalog_terms($gg_get_catalog_field('stone_forms', $post_id));
	                    $finish_items = $gg_catalog_terms($gg_get_catalog_field('stone_finishes', $post_id));
	                    $search_terms = array_merge(
	                            array($name, $description, $color_label),
	                            array_values($application_items),
	                            array_values($form_items),
	                            array_values($finish_items)
	                    );

                    $image_id = get_post_thumbnail_id(get_the_ID());
                    $image_url = '';
                    $image_alt = $name;

                    if ($image_id) {
                        $image_url = wp_get_attachment_image_url($image_id, 'large');
                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $name;
                    }

                    // fallback gradient
                    $gradients = array(
                            'black' => 'linear-gradient(135deg,#1a1a1a,#3a3a3a,#0a0a0a)',
                            'red' => 'linear-gradient(135deg,#8b2a1a,#c54a2a,#5a1812)',
                            'grey' => 'linear-gradient(135deg,#7a7a7a,#a5a5a5,#5a5a5a)',
                            'white' => 'linear-gradient(135deg,#d8d6d0,#efeee8,#b8b6b0)',
                            'rosa' => 'linear-gradient(135deg,#d09a8a,#e8baa8,#a87060)',
                            'yellow' => 'linear-gradient(135deg,#b0a060,#d2c082,#807040)',
                            'green' => 'linear-gradient(135deg,#6a7a5a,#8a9a72,#4a5a3a)',
                            'gold' => 'linear-gradient(135deg,#a8884a,#d2b06a,#785828)',
                    );

	                    $background = $gradients[$color_value] ?? 'linear-gradient(135deg,#7a7a7a,#a5a5a5,#5a5a5a)';
	                    ?>

	                    <a href="<?php the_permalink(); ?>"
	                       data-color="<?php echo esc_attr($color_value); ?>"
	                       data-form="<?php echo esc_attr(implode(' ', array_keys($form_items))); ?>"
	                       data-finish="<?php echo esc_attr(implode(' ', array_keys($finish_items))); ?>"
	                       data-search="<?php echo esc_attr(strtolower(implode(' ', array_filter($search_terms)))); ?>"
	                       class="group relative flex flex-col rounded-2xl overflow-hidden bg-card border border-border hover:border-gold/50 transition-all duration-500 hover:-translate-y-1 hover:shadow-luxe">

                        <div class="relative aspect-[4/3] overflow-hidden"
                             style="background:<?php echo esc_attr($background); ?>">

                            <?php if ($image_url) : ?>
                                <img src="<?php echo esc_url($image_url); ?>"
                                     alt="<?php echo esc_attr($image_alt); ?>"
                                     loading="lazy"
                                     decoding="async"
                                     class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"/>
                            <?php endif; ?>

	                            <?php if ($color_label) : ?>
	                                <div class="absolute top-3 left-3">
	                                    <span class="glass text-[10px] uppercase tracking-widest px-2.5 py-1 rounded-full">
	                                        <?php echo esc_html($color_label); ?>
	                                    </span>
	                                </div>
	                            <?php endif; ?>

                            <div class="absolute bottom-3 right-3 h-9 w-9 rounded-full glass flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="lucide lucide-arrow-up-right h-4 w-4"
                                     aria-hidden="true">
                                    <path d="M7 7h10v10"/>
                                    <path d="M7 17 17 7"/>
                                </svg>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-display text-xl">
                                <?php echo esc_html($name); ?>
                            </h3>

                            <?php if ($description) : ?>
                                <p class="text-sm text-muted-foreground mt-1 line-clamp-2">
                                    <?php echo esc_html($description); ?>
                                </p>
                            <?php endif; ?>

	                            <?php if ($application_items) : ?>
	                                <div class="mt-3 flex flex-wrap gap-1.5">

	                                    <?php foreach ($application_items as $application_text) : ?>
	                                        <span class="text-[10px] uppercase tracking-wider text-muted-foreground border border-border rounded-full px-2 py-0.5">
	                                            <?php echo esc_html($application_text); ?>
	                                        </span>
	                                    <?php endforeach; ?>

	                                </div>
	                            <?php endif; ?>

                            <div class="mt-4 text-sm font-medium text-gold inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     width="24"
                                     height="24"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2"
                                     stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="lucide lucide-arrow-up-right h-3.5 w-3.5"
                                     aria-hidden="true">
                                    <path d="M7 7h10v10"/>
                                    <path d="M7 17 17 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>

                <?php endwhile; ?>

            </div>

            <?php wp_reset_postdata(); ?>

        <?php endif; ?>
    </section><!--/$--></main>
