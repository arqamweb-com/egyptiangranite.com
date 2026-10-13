<?php
$home_products_query = new WP_Query(
        array(
                'post_type' => 'granite_product',
                'posts_per_page' => 12,
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
        )
);

$gg_home_get_field = static function ($field, $post_id) {
    return function_exists('get_field') ? get_field($field, $post_id) : null;
};

$gg_home_label = static function ($value) {
    if (is_array($value)) {
        $value = $value['label'] ?? $value['value'] ?? $value['name'] ?? $value['application_name'] ?? '';
    }

    return trim(wp_strip_all_tags((string) $value));
};

$gg_home_terms = static function ($values) use ($gg_home_label) {
    if (empty($values)) {
        return array();
    }

    if (!is_array($values)) {
        $values = array($values);
    }

    $terms = array();
    foreach ($values as $value) {
        $label = $gg_home_label($value);
        $slug = sanitize_title($label);

        if (!$slug) {
            continue;
        }

        $terms[$slug] = ucwords(str_replace(array('-', '_'), ' ', $label));
    }

    return $terms;
};

$home_filter_terms = array();

if ($home_products_query->have_posts()) {
    while ($home_products_query->have_posts()) {
        $home_products_query->the_post();
        $post_id = get_the_ID();

        $color = $gg_home_label($gg_home_get_field('stone_color_group', $post_id) ?: $gg_home_get_field('color', $post_id));
        $color_slug = sanitize_title($color);
        if ($color_slug) {
            $home_filter_terms[$color_slug] = ucwords(str_replace(array('-', '_'), ' ', $color));
        }

        $applications = $gg_home_get_field('stone_applications', $post_id) ?: $gg_home_get_field('applications', $post_id);
        $home_filter_terms += $gg_home_terms($applications);
    }

    $home_products_query->rewind_posts();
    wp_reset_postdata();
}
?>

<main class="flex-1"><!--$-->
    <section class="relative hero-bg stone-grain overflow-hidden">
        <div class="absolute inset-0 grid-glow"></div>
        <div class="absolute top-1/2 right-[-10%] -translate-y-1/2 w-[600px] h-[600px] rounded-full opacity-30 blur-3xl"
             style="background:var(--gradient-gold)"></div>
        <div class="relative mx-auto max-w-7xl px-4 md:px-6 pt-20 pb-24 md:pt-28 md:pb-32 grid lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 animate-fade-up">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full glass text-xs uppercase tracking-[0.2em]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-sparkles h-3 w-3 text-gold" aria-hidden="true">
                        <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/>
                        <path d="M20 2v4"/>
                        <path d="M22 4h-4"/>
                        <circle cx="4" cy="20" r="2"/>
                    </svg>
                    Egyptian Granite · Direct from the Quarry
                </div>
                <h1 class="mt-6 font-display text-5xl md:text-7xl leading-[0.95] tracking-tight">Premium Egyptian
                    Granite<br/><span class="gradient-gold-text">Exported Worldwide</span></h1>
                <p class="mt-6 text-lg md:text-xl text-muted-foreground max-w-xl font-display italic">Direct from Egypt&#x27;s
                    legendary quarries to global architectural, commercial, and monumental projects.</p>
                <p class="mt-5 text-base text-muted-foreground max-w-2xl">Egyptian Granite by EG supplies premium
                    Egyptian granite blocks, slabs, tiles, stairs, countertops, monuments, and cut-to-size projects with
                    certified quality, professional packing, and reliable export logistics.</p>
                <div class="mt-8 flex flex-wrap gap-3"><a href="<?php echo esc_url(home_url('/egyptian-granite')); ?>"
                                                          class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">Explore
                        Egyptian Granite
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a><a href="<?php echo esc_url(home_url('/contact')); ?>"
                           class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition border border-foreground/20 hover:border-gold hover:text-gold">Request
                        Export Quote</a><a href="#catalog"
                                           class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition hover:bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-download h-4 w-4" aria-hidden="true">
                            <path d="M12 15V3"/>
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <path d="m7 10 5 5 5-5"/>
                        </svg>
                        Download Catalog</a></div>
                <div class="mt-10 flex flex-wrap gap-x-6 gap-y-2 text-xs uppercase tracking-wider text-muted-foreground">
                    <span class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold"></span>Direct Factory Supply</span><span
                            class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold"></span>Export-Ready Packing</span><span
                            class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold"></span>Certified Quality</span><span
                            class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold"></span>Worldwide Shipping</span><span
                            class="flex items-center gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold"></span>Custom Sizes &amp; Finishes</span>
                </div>
            </div>
            <div class="lg:col-span-5 relative">
                <div class="relative aspect-[4/5] rounded-3xl overflow-hidden shadow-luxe border border-border"
                     style="background:linear-gradient(135deg,#1a1a1a,#3a3a3a,#0a0a0a)"><img
                            src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-slab-Dw7fT9Kk.jpg"
                            alt="Premium polished Black Aswan Egyptian granite slab — featured export-ready stone"
                            width="1024" height="1280" fetchPriority="high" decoding="async"
                            class="absolute inset-0 h-full w-full object-cover"/>
                    <div class="absolute bottom-5 left-5 right-5 glass rounded-xl p-4">
                        <div class="text-[10px] uppercase tracking-widest text-gold">Featured Slab</div>
                        <div class="font-display text-lg">Black Aswan Granite</div>
                        <div class="text-xs text-muted-foreground">Polished · 2/3 cm · Export Ready</div>
                    </div>
                </div>
                <div class="absolute -left-6 top-8 w-44 glass rounded-xl p-3 shadow-luxe animate-float hidden md:block">
                    <div class="aspect-square rounded-lg mb-2 overflow-hidden"
                         style="background:linear-gradient(135deg,#8b2a1a,#c54a2a,#5a1812)"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/red-aswan-granite-CZCN9VmC.jpg"
                                alt="Red Aswan Egyptian granite slab close-up" loading="lazy" decoding="async"
                                width="400" height="400" class="h-full w-full object-cover"/></div>
                    <div class="text-xs font-medium">Red Aswan</div>
                    <div class="text-[10px] text-muted-foreground">Heritage Stone</div>
                </div>
                <div class="absolute -right-4 bottom-12 w-44 glass rounded-xl p-3 shadow-luxe animate-float hidden md:block"
                     style="animation-delay:1.5s">
                    <div class="aspect-square rounded-lg mb-2 overflow-hidden"
                         style="background:linear-gradient(135deg,#d09a8a,#e8baa8,#a87060)"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/rosa-el-nasr-granite-CWvpuR0K.jpg"
                                alt="Rosa El Nasr Egyptian pink granite slab close-up" loading="lazy" decoding="async"
                                width="400" height="400" class="h-full w-full object-cover"/></div>
                    <div class="text-xs font-medium">Rosa El Nasr</div>
                    <div class="text-[10px] text-muted-foreground">Pink Granite</div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-xs text-muted-foreground flex flex-col items-center gap-1">
            <span class="uppercase tracking-widest text-[10px]">Scroll</span>
            <div class="h-8 w-px bg-gradient-to-b from-gold to-transparent"></div>
        </div>
    </section>
    <section aria-label="Egyptian Granite by EG — key statistics" class="border-y border-border bg-card">
        <div class="mx-auto max-w-7xl px-4 md:px-6 py-12 md:py-16 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="font-display text-4xl md:text-5xl gradient-gold-text tabular-nums">25+</div>
                <div class="mt-2 text-xs md:text-sm uppercase tracking-[0.18em] text-muted-foreground">Years of Experience</div>
            </div>
            <div class="text-center">
                <div class="font-display text-4xl md:text-5xl gradient-gold-text tabular-nums">35+</div>
                <div class="mt-2 text-xs md:text-sm uppercase tracking-[0.18em] text-muted-foreground">Export Countries</div>
            </div>
            <div class="text-center">
                <div class="font-display text-4xl md:text-5xl gradient-gold-text tabular-nums">15,000+ m²/mo</div>
                <div class="mt-2 text-xs md:text-sm uppercase tracking-[0.18em] text-muted-foreground">Production Capacity</div>
            </div>
            <div class="text-center">
                <div class="font-display text-4xl md:text-5xl gradient-gold-text tabular-nums">750+</div>
                <div class="mt-2 text-xs md:text-sm uppercase tracking-[0.18em] text-muted-foreground">Projects Completed</div>
            </div>
        </div>
    </section>
    <div class="border-y border-border bg-secondary/40">
        <div class="mx-auto max-w-7xl px-4 md:px-6 py-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="flex items-center gap-2 text-xs md:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-award h-4 w-4 text-gold shrink-0" aria-hidden="true">
                    <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/>
                    <circle cx="12" cy="8" r="6"/>
                </svg>
                <span class="text-muted-foreground">Premium Egyptian Granite</span></div>
            <div class="flex items-center gap-2 text-xs md:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-layers h-4 w-4 text-gold shrink-0" aria-hidden="true">
                    <path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"/>
                    <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"/>
                    <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"/>
                </svg>
                <span class="text-muted-foreground">Blocks, Slabs, Tiles &amp; Cut-to-Size</span></div>
            <div class="flex items-center gap-2 text-xs md:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-earth h-4 w-4 text-gold shrink-0" aria-hidden="true">
                    <path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"/>
                    <path d="M7 3.34V5a3 3 0 0 0 3 3a2 2 0 0 1 2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2c0-1.1.9-2 2-2h3.17"/>
                    <path d="M11 21.95V18a2 2 0 0 0-2-2a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"/>
                    <circle cx="12" cy="12" r="10"/>
                </svg>
                <span class="text-muted-foreground">Global Export</span></div>
            <div class="flex items-center gap-2 text-xs md:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-shield-check h-4 w-4 text-gold shrink-0" aria-hidden="true">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                <span class="text-muted-foreground">Quality Inspection</span></div>
            <div class="flex items-center gap-2 text-xs md:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-package h-4 w-4 text-gold shrink-0" aria-hidden="true">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                    <path d="M12 22V12"/>
                    <polyline points="3.29 7 12 12 20.71 7"/>
                    <path d="m7.5 4.27 9 5.15"/>
                </svg>
                <span class="text-muted-foreground">Secure Packing</span></div>
            <div class="flex items-center gap-2 text-xs md:text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-sparkles h-4 w-4 text-gold shrink-0" aria-hidden="true">
                    <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/>
                    <path d="M20 2v4"/>
                    <path d="M22 4h-4"/>
                    <circle cx="4" cy="20" r="2"/>
                </svg>
                <span class="text-muted-foreground">Fast Quotation</span></div>
        </div>
    </div>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-14">
        <div class="max-w-4xl mx-auto text-center">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Egyptian Granite at a Glance</div>
            <h2 class="font-display text-2xl md:text-3xl leading-snug">What is Egyptian granite?</h2>
            <p class="mt-5 text-base md:text-lg text-muted-foreground leading-relaxed">Egyptian granite is natural
                igneous stone quarried in Egypt — primarily in the Aswan, Halayeb, Sharka, Forsan, and South Sinai
                regions. It is exported worldwide as blocks, slabs, tiles, and cut-to-size in colors including Black
                Aswan, Red Aswan, New Halayeb grey, Rosa El Nasr, Gandola Aswan, and Verdi green — prized for
                durability, low water absorption, and a heritage palette unique to Egypt.</p></div>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Featured Collection</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">The Egyptian Granite Collection</h2>
            <p class="mt-4 text-base text-muted-foreground">From iconic Aswan blacks and reds to modern Halayeb greys
                and refined Rosa pinks — explore Egypt&#x27;s most internationally specified granites.</p></div>
        <?php if ($home_products_query->have_posts()) : ?>
            <div id="gg-home-filter" class="flex flex-wrap gap-2 mb-8">
                <button data-filter-value="all" class="px-4 py-2 rounded-full text-sm transition border bg-foreground text-background border-foreground">
                    All
                </button>
                <?php foreach ($home_filter_terms as $filter_value => $filter_label) : ?>
                    <button data-filter-value="<?php echo esc_attr($filter_value); ?>" class="px-4 py-2 rounded-full text-sm transition border border-border hover:border-gold hover:text-gold">
                        <?php echo esc_html($filter_label); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div id="gg-home-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php while ($home_products_query->have_posts()) : $home_products_query->the_post(); ?>
                    <?php
                    $post_id = get_the_ID();
                    $name = get_the_title();
                    $description = get_the_excerpt();
                    $color = $gg_home_label($gg_home_get_field('stone_color_group', $post_id) ?: $gg_home_get_field('color', $post_id));
                    $color_value = sanitize_title($color);
                    $color_label = $color_value ? ucwords(str_replace(array('-', '_'), ' ', $color)) : '';
                    $applications = $gg_home_get_field('stone_applications', $post_id) ?: $gg_home_get_field('applications', $post_id);
                    $application_items = $gg_home_terms($applications);
                    $image_id = get_post_thumbnail_id($post_id);
                    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
                    $image_alt = $image_id ? (get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $name) : $name;
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
                       data-app="<?php echo esc_attr(implode(' ', array_keys($application_items))); ?>"
                       class="group relative flex flex-col rounded-2xl overflow-hidden bg-card border border-border hover:border-gold/50 transition-all duration-500 hover:-translate-y-1 hover:shadow-luxe">
                        <div class="relative aspect-[4/3] overflow-hidden" style="background:<?php echo esc_attr($background); ?>">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right h-4 w-4" aria-hidden="true">
                                    <path d="M7 7h10v10"/>
                                    <path d="M7 17 17 7"/>
                                </svg>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-display text-xl"><?php echo esc_html($name); ?></h3>
                            <?php if ($description) : ?>
                                <p class="text-sm text-muted-foreground mt-1 line-clamp-2"><?php echo esc_html($description); ?></p>
                            <?php endif; ?>
                            <?php if ($application_items) : ?>
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    <?php foreach (array_slice($application_items, 0, 3) as $application_text) : ?>
                                        <span class="text-[10px] uppercase tracking-wider text-muted-foreground border border-border rounded-full px-2 py-0.5">
                                            <?php echo esc_html($application_text); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="mt-4 text-sm font-medium text-gold inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-up-right h-3.5 w-3.5" aria-hidden="true">
                                    <path d="M7 7h10v10"/>
                                    <path d="M7 17 17 7"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <?php wp_reset_postdata(); ?>

            <div class="mt-10 text-center"><a href="<?php echo esc_url(home_url('/egyptian-granite')); ?>"
                                              class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">View
                    All <?php echo esc_html($home_products_query->found_posts); ?> Egyptian Granite Types
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </a></div>
        <?php endif; ?>
    </section>
    <section class="border-y border-border bg-secondary/30 stone-grain">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="grid lg:grid-cols-2 gap-16 items-start">
                <div>
                    <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Why Egyptian Granite</div>
                    <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Why Egyptian Granite Is Chosen for
                        Global Projects</h2>
                    <div class="mt-6 space-y-4 text-muted-foreground leading-relaxed"><p>For over four thousand years,
                            Egyptian granite has shaped landmark architecture — from the obelisks and temples of
                            pharaonic Egypt to the airports, civic plazas, and luxury residences of today. The same
                            Aswan quarries that supplied the ancient world continue to produce premium granite blocks,
                            slabs, tiles, and cut-to-size for international markets.</p>
                        <p>As an Egyptian granite supplier, exporter, and manufacturer, Egyptian Granite by EG works
                            direct from the factory floor — quarrying, cutting, polishing, and packing all granite types
                            in-country before shipment. Architects and contractors choose granite from Egypt because no
                            other origin offers the same combination of color palette, technical durability, heritage
                            value, and factory-direct export pricing.</p>
                        <p>The Egyptian granite catalog spans the full color spectrum: Black Aswan and Red Aswan from
                            Upper Egypt, New Halayeb and Halayeb greys from the Red Sea coast, Rosa El Nasr, Rosa Hody
                            and Rosa Katrina pinks, Gandola Aswan gold-reds, Grey Shirka and Karnak greys, and the Verdi
                            family of earth-toned yellows and greens — all quarried, processed, and exported from
                            Egypt.</p></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Pharaonic Heritage</div>
                        <p class="text-sm text-muted-foreground mt-1">Aswan granite has built monuments for over four
                            millennia — the original heritage stone.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Natural Color Range</div>
                        <p class="text-sm text-muted-foreground mt-1">Black, red, grey, rosa, white, yellow, green —
                            every Egyptian granite color from one origin.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Indoor &amp; Outdoor</div>
                        <p class="text-sm text-muted-foreground mt-1">Very low water absorption performs in every
                            climate, from desert sun to coastal humidity to freeze–thaw.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">High-Traffic Durability</div>
                        <p class="text-sm text-muted-foreground mt-1">Specified for plazas, airports, hotels, transit
                            hubs, and civic squares worldwide.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Competitive Export Pricing</div>
                        <p class="text-sm text-muted-foreground mt-1">Factory-direct from Egyptian quarries — no
                            intermediaries, mixed containers welcome.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Full Product Range</div>
                        <p class="text-sm text-muted-foreground mt-1">Blocks, gangsaw and cutter slabs, tiles, stairs,
                            countertops, monuments, and cut-to-size.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Multiple Finishes</div>
                        <p class="text-sm text-muted-foreground mt-1">Polished, honed, flamed, brushed, sandblasted,
                            bush-hammered, leathered, and more.</p></div>
                    <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/40 transition">
                        <div class="font-display text-lg">Reliable Shipping</div>
                        <p class="text-sm text-muted-foreground mt-1">ISPM-15 wooden packing, container loading, and
                            worldwide shipping with full export documentation.</p></div>
                </div>
            </div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Product Categories</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Every Form of Egyptian Granite</h2>
            <p class="mt-4 text-base text-muted-foreground">Production-ready output for any architectural or monumental
                specification.</p></div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-boxes h-5 w-5 text-gold" aria-hidden="true">
                        <path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z"/>
                        <path d="m7 16.5-4.74-2.85"/>
                        <path d="m7 16.5 5-3"/>
                        <path d="M7 16.5v5.17"/>
                        <path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z"/>
                        <path d="m17 16.5-5-3"/>
                        <path d="m17 16.5 4.74-2.85"/>
                        <path d="M17 16.5v5.17"/>
                        <path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z"/>
                        <path d="M12 8 7.26 5.15"/>
                        <path d="m12 8 4.74-2.85"/>
                        <path d="M12 13.5V8"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Blocks</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-layers h-5 w-5 text-gold" aria-hidden="true">
                        <path d="M12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83z"/>
                        <path d="M2 12a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 12"/>
                        <path d="M2 17a1 1 0 0 0 .58.91l8.6 3.91a2 2 0 0 0 1.65 0l8.58-3.9A1 1 0 0 0 22 17"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Slabs</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-scissors h-5 w-5 text-gold" aria-hidden="true">
                        <circle cx="6" cy="6" r="3"/>
                        <path d="M8.12 8.12 12 12"/>
                        <path d="M20 4 8.12 15.88"/>
                        <circle cx="6" cy="18" r="3"/>
                        <path d="M14.8 14.8 20 20"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Tiles</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-mountain h-5 w-5 text-gold" aria-hidden="true">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Stairs</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-crown h-5 w-5 text-gold" aria-hidden="true">
                        <path d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z"/>
                        <path d="M5 21h14"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Countertops</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-award h-5 w-5 text-gold" aria-hidden="true">
                        <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/>
                        <circle cx="12" cy="8" r="6"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Monuments</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-package h-5 w-5 text-gold" aria-hidden="true">
                        <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                        <path d="M12 22V12"/>
                        <polyline points="3.29 7 12 12 20.71 7"/>
                        <path d="m7.5 4.27 9 5.15"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Cubes &amp; Pavers</div>
            </div>
            <div class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary group-hover:bg-gold/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-wrench h-5 w-5 text-gold" aria-hidden="true">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z"/>
                    </svg>
                </div>
                <div class="font-display text-lg">Egyptian Granite Cut-to-Size</div>
            </div>
        </div>
    </section>
    <section class="border-y border-border bg-secondary/30">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="mb-10 md:mb-14 max-w-3xl">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Applications</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Where Egyptian Granite Performs</h2>
                <p class="mt-4 text-base text-muted-foreground">From exterior façades to monumental memorials —
                    specified across every premium project type.</p></div>
            <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Exterior Facades</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Flooring</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Wall Cladding</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Staircases</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Kitchen Countertops</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Bathroom Vanities</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Monuments &amp; Memorials</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Landscaping</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Public Squares</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Commercial Projects</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Hotels &amp; Resorts</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border hover:border-gold/50 transition group">
                    <div class="font-medium">Residential Villas</div>
                    <div class="text-xs text-muted-foreground mt-1 group-hover:text-gold transition">Egyptian granite
                        specified worldwide
                    </div>
                </div>
            </div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Factory &amp; Production</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">From Quarry to Container</h2>
            <p class="mt-4 text-base text-muted-foreground">A continuous, inspected process — every block traced from
                selection to shipment.</p></div>
        <div class="relative grid sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->01</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-mountain h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                </svg>
                <div class="font-display text-base">Block Selection</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->02</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-scissors h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <circle cx="6" cy="6" r="3"/>
                    <path d="M8.12 8.12 12 12"/>
                    <path d="M20 4 8.12 15.88"/>
                    <circle cx="6" cy="18" r="3"/>
                    <path d="M14.8 14.8 20 20"/>
                </svg>
                <div class="font-display text-base">Cutting</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->03</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-sparkles h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/>
                    <path d="M20 2v4"/>
                    <path d="M22 4h-4"/>
                    <circle cx="4" cy="20" r="2"/>
                </svg>
                <div class="font-display text-base">Resin / Treatment</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->04</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-sparkles h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z"/>
                    <path d="M20 2v4"/>
                    <path d="M22 4h-4"/>
                    <circle cx="4" cy="20" r="2"/>
                </svg>
                <div class="font-display text-base">Polishing / Finishing</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->05</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-shield-check h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                <div class="font-display text-base">Quality Inspection</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->06</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-tag h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/>
                    <circle cx="7.5" cy="7.5" r=".5" fill="currentColor"/>
                </svg>
                <div class="font-display text-base">Numbering &amp; Labeling</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->07</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-package h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/>
                    <path d="M12 22V12"/>
                    <polyline points="3.29 7 12 12 20.71 7"/>
                    <path d="m7.5 4.27 9 5.15"/>
                </svg>
                <div class="font-display text-base">Wooden Packing</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->08</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-truck h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/>
                    <path d="M15 18H9"/>
                    <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/>
                    <circle cx="17" cy="18" r="2"/>
                    <circle cx="7" cy="18" r="2"/>
                </svg>
                <div class="font-display text-base">Container Loading</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->09</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-file-check h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/>
                    <path d="M14 2v5a1 1 0 0 0 1 1h5"/>
                    <path d="m9 15 2 2 4-4"/>
                </svg>
                <div class="font-display text-base">Export Documents</div>
            </div>
            <div class="relative p-5 rounded-xl bg-card border border-border">
                <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Step <!-- -->10</div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-ship h-5 w-5 text-foreground mb-2" aria-hidden="true">
                    <path d="M12 10.189V14"/>
                    <path d="M12 2v3"/>
                    <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                    <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-8.188-3.639a2 2 0 0 0-1.624 0L3 14a11.6 11.6 0 0 0 2.81 7.76"/>
                    <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                </svg>
                <div class="font-display text-base">Worldwide Shipping</div>
            </div>
        </div>
    </section>
    <section class="border-y border-border bg-secondary/30">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="mb-10 md:mb-14 max-w-3xl">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Finishes</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Specify Your Surface</h2>
                <p class="mt-4 text-base text-muted-foreground">All standard granite finishes available — choose by
                    application, aesthetic, and slip-resistance requirements.</p></div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/polished-BgHUn7U1.jpg"
                                alt="Polished granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Polished</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/honed-l3981MC8.jpg"
                                alt="Honed granite finish close-up texture" loading="lazy" decoding="async" width="120"
                                height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Honed</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/flamed-VUV4FL0g.jpg"
                                alt="Flamed granite finish close-up texture" loading="lazy" decoding="async" width="120"
                                height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Flamed</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/brushed-DcEoXbwe.jpg"
                                alt="Brushed granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Brushed</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/sandblasted-CG3eTxzk.jpg"
                                alt="Sandblasted granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Sandblasted</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/bush-hammered-Bum18cGH.jpg"
                                alt="Bush-Hammered granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Bush-Hammered</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/tumbled-CA1YUZdh.jpg"
                                alt="Tumbled granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Tumbled</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/split-face-DBjtYGiM.jpg"
                                alt="Split Face granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Split Face</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/leathered-BX6eydJM.jpg"
                                alt="Leathered granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Leathered</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border text-center hover:border-gold transition">
                    <div class="h-14 w-14 mx-auto mb-3 rounded-full overflow-hidden border border-border"><img
                                src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/acid-washed-C-GcFJSO.jpg"
                                alt="Acid Washed granite finish close-up texture" loading="lazy" decoding="async"
                                width="120" height="120" class="h-full w-full object-cover"/></div>
                    <div class="text-sm font-medium">Acid Washed</div>
                </div>
            </div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1 relative">
                <div class="aspect-square rounded-3xl overflow-hidden border border-border shadow-luxe relative">
                    <div class="absolute inset-0" style="background:var(--gradient-luxe)"></div>
                    <div class="absolute inset-0 grid-glow opacity-80"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="0.6" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-earth h-48 w-48 text-gold/30" aria-hidden="true">
                            <path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"/>
                            <path d="M7 3.34V5a3 3 0 0 0 3 3a2 2 0 0 1 2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2c0-1.1.9-2 2-2h3.17"/>
                            <path d="M11 21.95V18a2 2 0 0 0-2-2a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"/>
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                    </div>
                    <div class="absolute top-6 left-6 glass rounded-xl px-3 py-2 text-xs">FOB / CIF Quotation Support
                    </div>
                    <div class="absolute bottom-6 right-6 glass rounded-xl px-3 py-2 text-xs">Mixed Container Loading
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Export Capabilities</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Export-Ready Egyptian Granite for
                    International Buyers</h2>
                <ul class="mt-6 space-y-3 text-muted-foreground">
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>FOB and CIF quotation support</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Worldwide shipping from Egyptian ports</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Container loading with secure wooden bundles and crates</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Full export documentation</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Inspection before shipment</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Custom sizes and thicknesses</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Mixed container options across materials and finishes</span>
                    </li>
                    <li class="flex items-start gap-3"><span class="mt-2 h-1.5 w-1.5 rounded-full bg-gold"></span><span>Factory-direct pricing</span>
                    </li>
                </ul>
                <div class="mt-8"><a href="<?php echo esc_url(home_url('/export')); ?>"
                                     class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">View
                        Export Capabilities
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a></div>
            </div>
        </div>
    </section>
    <section class="border-y border-border bg-secondary/30">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="mb-10 md:mb-14 text-center mx-auto max-w-2xl">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Quality &amp; Certificates</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Quality Controlled at Every Stage</h2>
                <p class="mt-4 text-base text-muted-foreground">Every shipment is inspected against your specification —
                    measurements, finish, thickness, and packing.</p></div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Factory Quality Control</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Product Inspection</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Measurement Accuracy</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Thickness Tolerance</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Surface Finish Inspection</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Packing Inspection</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Export Documentation</div>
                </div>
                <div class="p-5 rounded-xl bg-card border border-border">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-check h-5 w-5 text-gold mb-3" aria-hidden="true">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="m9 14 2 2 4-4"/>
                    </svg>
                    <div class="font-medium text-sm">Certificates Available on Request</div>
                </div>
            </div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Compliance &amp; Standards</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Certifications &amp; Compliance for Global
                Importers</h2>
            <p class="mt-4 text-base text-muted-foreground">We test and certify our natural Egyptian granite against
                international performance, safety, and quality standards.</p></div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
            <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-shield-check h-5 w-5 text-gold"
                                 aria-hidden="true">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                <path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">Europe &amp; United Kingdom</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>CE Marking (Construction Products Regulation)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>UKCA Marking (United Kingdom)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>EN 1469 – cladding slabs</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>EN 12058 – floor &amp; stair tiles</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>EN 1341 / EN 1342 / EN 1343 – paving slabs, setts &amp; kerbs</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>EPD / GWP declaration (CPR 2024/3110)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>REACH compliance</span></li>
                    </ul>
                </div>
            </div>
            <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-award h-5 w-5 text-gold"
                                 aria-hidden="true">
                                <path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/>
                                <circle cx="12" cy="8" r="6"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">United States</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ASTM C615 – Granite Dimension Stone</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ASTM C97 – absorption &amp; density</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ASTM C170 – compressive strength</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ASTM C99 / C880 – modulus of rupture &amp; flexural strength</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>NSF/ANSI 51 – food-contact countertops</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>Natural Stone Institute standards</span></li>
                    </ul>
                </div>
            </div>
            <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-earth h-5 w-5 text-gold"
                                 aria-hidden="true">
                                <path d="M21.54 15H17a2 2 0 0 0-2 2v4.54"/>
                                <path d="M7 3.34V5a3 3 0 0 0 3 3a2 2 0 0 1 2 2c0 1.1.9 2 2 2a2 2 0 0 0 2-2c0-1.1.9-2 2-2h3.17"/>
                                <path d="M11 21.95V18a2 2 0 0 0-2-2a2 2 0 0 1-2-2v-1a2 2 0 0 0-2-2H2.05"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">Australia &amp; New Zealand</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>AS/NZS 4586 – slip resistance (P/R ratings)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>AS/NZS 4456 – material test methods</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>NCC / ABCB compliance</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>Australian Biosecurity – ISPM-15 timber packing &amp; container cleanliness</span></li>
                    </ul>
                </div>
            </div>
            <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-file-check h-5 w-5 text-gold"
                                 aria-hidden="true">
                                <path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z"/>
                                <path d="M14 2v5a1 1 0 0 0 1 1h5"/>
                                <path d="m9 15 2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">Gulf &amp; Middle East</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>SASO / SABER (Saudi Arabia)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ESMA / Emirates Quality Mark (UAE)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>GSO – GCC Standardization</span></li>
                    </ul>
                </div>
            </div>
            <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-10 w-10 rounded-xl flex items-center justify-center bg-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="lucide lucide-clipboard-check h-5 w-5 text-gold"
                                 aria-hidden="true">
                                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                <path d="m9 14 2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">International (every shipment)</h3>
                    </div>
                    <ul class="space-y-2">
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ISO 9001 – Quality Management System</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>ISPM-15 – heat-treated wooden packing</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>Certificate of Origin (Egyptian Chamber of Commerce / GOEIC)</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>Pre-shipment / third-party inspection</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>Material Test Certificate (MTC) per shipment</span></li>
                        <li class="text-sm text-muted-foreground flex items-start gap-2"><span class="h-1.5 w-1.5 rounded-full bg-gold shrink-0 mt-2"></span><span>EN 13755, EN 1926, EN 12372, EN 12371 test reports (water absorption, compressive &amp; flexural strength, frost resistance)</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <p class="mt-8 text-center text-xs md:text-sm text-muted-foreground max-w-3xl mx-auto italic border-t border-border/60 pt-6">
            * Material test certificates, CE/UKCA documentation, and country-specific compliance reports are issued per
            order and available on request.</p>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Global Markets</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Egyptian Granite, Shipped Worldwide</h2>
            <p class="mt-4 text-base text-muted-foreground">From the Middle East and Europe to North America and Asia —
                Egyptian Granite by EG supplies premium granite to international architectural projects.</p></div>
        <div class="relative rounded-3xl border border-border bg-card p-8 md:p-12 overflow-hidden">
            <div class="absolute inset-0 grid-glow opacity-50"></div>
            <div class="relative">
                <div class="text-sm text-muted-foreground mb-4">Active export markets across <span
                            class="text-gold font-medium">Middle East · Europe · Africa · Asia · North America</span>
                </div>
                <div class="flex flex-wrap gap-2"><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Saudi Arabia</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>UAE</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Qatar</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Kuwait</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Oman</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Jordan</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Iraq</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Libya</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Morocco</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Italy</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Spain</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>France</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Germany</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>United Kingdom</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>United States</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>Canada</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>India</span><span
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full glass text-sm"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-map-pin h-3 w-3 text-gold"
                                aria-hidden="true"><path
                                    d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle
                                    cx="12" cy="10" r="3"/></svg>China</span></div>
            </div>
        </div>
    </section>
    <section class="border-y border-border bg-secondary/30">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="mb-10 md:mb-14 max-w-3xl">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Knowledge Center</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">From the Egyptian Granite Blog</h2>
                <p class="mt-4 text-base text-muted-foreground">Guides and reference content for architects,
                    contractors, and international importers.</p></div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $home_blog_query = new WP_Query(
                    array(
                        'post_type'           => 'post',
                        'post_status'         => 'publish',
                        'category_name'       => 'blog',
                        'posts_per_page'      => 6,
                        'orderby'             => 'date',
                        'order'               => 'DESC',
                        'ignore_sticky_posts' => true,
                    )
                );
                if ( $home_blog_query->have_posts() ) :
                    while ( $home_blog_query->have_posts() ) :
                        $home_blog_query->the_post();
                        $gg_home_post_label = '';
                        foreach ( get_the_category() as $gg_home_cat ) {
                            if ( 'blog' !== $gg_home_cat->slug ) {
                                $gg_home_post_label = $gg_home_cat->name;
                                break;
                            }
                        }
                        $gg_home_read_time = max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) );
                        ?>
                        <a href="<?php the_permalink(); ?>"
                           class="group p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                            <?php if ( $gg_home_post_label ) : ?>
                                <div class="text-[10px] uppercase tracking-widest text-gold mb-3"><?php echo esc_html( $gg_home_post_label ); ?></div>
                            <?php endif; ?>
                            <h3 class="font-display text-xl group-hover:text-gold transition"><?php the_title(); ?></h3>
                            <?php if ( get_the_excerpt() ) : ?>
                                <p class="text-sm text-muted-foreground mt-2 line-clamp-3"><?php echo esc_html( get_the_excerpt() ); ?></p>
                            <?php endif; ?>
                            <div class="mt-4 text-xs text-muted-foreground"><?php echo esc_html( $gg_home_read_time ); ?> min read</div>
                        </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="text-muted-foreground col-span-full">No blog posts have been published yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Technical Data</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Egyptian Granite Technical Specifications</h2>
            <p class="mt-4 text-base text-muted-foreground">Typical industry values for Egyptian granite. Final
                specifications are confirmed per color and order with material test reports on request.</p></div>
        <div class="mx-auto max-w-3xl rounded-2xl border border-border bg-card overflow-hidden shadow-luxe">
            <table class="w-full text-sm">
                <thead class="bg-secondary/60">
                <tr>
                    <th scope="col" class="text-left font-medium uppercase tracking-wider text-xs text-muted-foreground px-5 py-3">Property</th>
                    <th scope="col" class="text-left font-medium uppercase tracking-wider text-xs text-muted-foreground px-5 py-3">Typical Value</th>
                </tr>
                </thead>
                <tbody>
                <tr class="bg-background/40">
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Water Absorption</th>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">0.1–0.4%</td>
                </tr>
                <tr>
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Bulk Density</th>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">2,600–2,700 kg/m³</td>
                </tr>
                <tr class="bg-background/40">
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Compressive Strength</th>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">120–200 MPa</td>
                </tr>
                <tr>
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Flexural (Bending) Strength</th>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">8–15 MPa</td>
                </tr>
                <tr class="bg-background/40">
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Mohs Hardness</th>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">6–7</td>
                </tr>
                <tr>
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Frost / Abrasion Resistance</th>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">High</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
    <section class="border-y border-border bg-secondary/30">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="mb-10 md:mb-14 max-w-3xl">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Pricing</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">What Determines Egyptian Granite Price</h2>
                <p class="mt-4 text-base text-muted-foreground">Egyptian granite is priced per project. The factors below
                    shape every quotation — share your specification for an accurate, factory-direct price.</p></div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-palette h-5 w-5 text-gold" aria-hidden="true">
                            <path d="M12 22a1 1 0 0 1 0-20 10 9 0 0 1 10 9 5 5 0 0 1-5 5h-2.25a1.75 1.75 0 0 0-1.4 2.8l.3.4a1.75 1.75 0 0 1-1.4 2.8z"/>
                            <circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/>
                            <circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/>
                            <circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/>
                            <circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/>
                        </svg>
                    </div>
                    <div class="font-display text-lg">Color &amp; Rarity</div>
                    <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Common greys and reds price lower than
                        rare colors such as deep Verdi greens or premium Rosa selections.</p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-ruler h-5 w-5 text-gold" aria-hidden="true">
                            <path d="M21.3 15.3a2.4 2.4 0 0 1 0 3.4l-2.6 2.6a2.4 2.4 0 0 1-3.4 0L2.7 8.7a2.41 2.41 0 0 1 0-3.4l2.6-2.6a2.41 2.41 0 0 1 3.4 0Z"/>
                            <path d="m14.5 12.5 2-2"/>
                            <path d="m11.5 9.5 2-2"/>
                            <path d="m8.5 6.5 2-2"/>
                            <path d="m17.5 15.5 2-2"/>
                        </svg>
                    </div>
                    <div class="font-display text-lg">Thickness</div>
                    <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Standard 2 cm and 3 cm slabs are the
                        most economical; custom thicknesses and oversized blocks add to the unit price.</p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-hammer h-5 w-5 text-gold" aria-hidden="true">
                            <path d="m15 12-8.373 8.373a1 1 0 1 1-3-3L12 9"/>
                            <path d="m18 15 4-4"/>
                            <path d="m21.5 11.5-1.914-1.914A2 2 0 0 1 19 8.172V7l-2.26-2.26a6 6 0 0 0-4.202-1.756L9 2.96l.92.82A6.18 6.18 0 0 1 12 8.4V10l2 2h1.172a2 2 0 0 1 1.414.586L18.5 14.5"/>
                        </svg>
                    </div>
                    <div class="font-display text-lg">Finish</div>
                    <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Polished and honed are standard;
                        flamed, brushed, leathered, and bush-hammered finishes add processing time.</p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-boxes h-5 w-5 text-gold" aria-hidden="true">
                            <path d="M2.97 12.92A2 2 0 0 0 2 14.63v3.24a2 2 0 0 0 .97 1.71l3 1.8a2 2 0 0 0 2.06 0L12 19v-5.5l-5-3-4.03 2.42Z"/>
                            <path d="m7 16.5-4.74-2.85"/>
                            <path d="m7 16.5 5-3"/>
                            <path d="M7 16.5v5.17"/>
                            <path d="M12 13.5V19l3.97 2.38a2 2 0 0 0 2.06 0l3-1.8a2 2 0 0 0 .97-1.71v-3.24a2 2 0 0 0-.97-1.71L17 10.5l-5 3Z"/>
                            <path d="m17 16.5-5-3"/>
                            <path d="m17 16.5 4.74-2.85"/>
                            <path d="M17 16.5v5.17"/>
                            <path d="M7.97 4.42A2 2 0 0 0 7 6.13v4.37l5 3 5-3V6.13a2 2 0 0 0-.97-1.71l-3-1.8a2 2 0 0 0-2.06 0l-3 1.8Z"/>
                            <path d="M12 8 7.26 5.15"/>
                            <path d="m12 8 4.74-2.85"/>
                            <path d="M12 13.5V8"/>
                        </svg>
                    </div>
                    <div class="font-display text-lg">Order Quantity</div>
                    <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Full-container loads of a single color
                        earn the best unit price; mixed containers are welcomed at a small premium.</p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-ship h-5 w-5 text-gold" aria-hidden="true">
                            <path d="M12 10.189V14"/>
                            <path d="M12 2v3"/>
                            <path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"/>
                            <path d="M19.38 20A11.6 11.6 0 0 0 21 14l-8.188-3.639a2 2 0 0 0-1.624 0L3 14a11.6 11.6 0 0 0 2.81 7.76"/>
                            <path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1s1.2 1 2.5 1c2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"/>
                        </svg>
                    </div>
                    <div class="font-display text-lg">Shipping Terms</div>
                    <p class="text-sm text-muted-foreground mt-2 leading-relaxed">FOB from Egyptian ports is leanest; CIF
                        to your destination port includes ocean freight and insurance.</p>
                </div>
                <div class="p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
                    <div class="h-11 w-11 rounded-xl flex items-center justify-center mb-4 bg-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-droplets h-5 w-5 text-gold" aria-hidden="true">
                            <path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 4.7 7 2.2C6.71 4.7 5.57 7.16 4.71 9.06S3 11.09 3 12.25c0 2.22 1.8 4.05 4 4.05z"/>
                            <path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"/>
                        </svg>
                    </div>
                    <div class="font-display text-lg">Material Treatment</div>
                    <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Resin treatment, mesh backing, edge
                        profiling, and special packing are quoted as optional add-ons.</p>
                </div>
            </div>
            <div class="mt-10 text-center"><a href="<?php echo esc_url(home_url('/contact')); ?>"
                                              class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">Request
                    a Quote
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                        <path d="M5 12h14"/>
                        <path d="m12 5 7 7-7 7"/>
                    </svg>
                </a></div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Comparison</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Egyptian Granite vs Indian &amp; Chinese
                Granite</h2>
            <p class="mt-4 text-base text-muted-foreground">A quick reference for specifiers comparing the three largest
                natural-granite origins on the global market.</p></div>
        <div class="rounded-2xl border border-border bg-card overflow-hidden shadow-luxe overflow-x-auto">
            <table class="w-full text-sm min-w-[640px]">
                <thead class="bg-secondary/60">
                <tr>
                    <th scope="col" class="text-left font-medium uppercase tracking-wider text-xs text-muted-foreground px-5 py-3">Criterion</th>
                    <th scope="col" class="text-left font-medium uppercase tracking-wider text-xs text-gold px-5 py-3">Egyptian Granite</th>
                    <th scope="col" class="text-left font-medium uppercase tracking-wider text-xs text-muted-foreground px-5 py-3">Indian Granite</th>
                    <th scope="col" class="text-left font-medium uppercase tracking-wider text-xs text-muted-foreground px-5 py-3">Chinese Granite</th>
                </tr>
                </thead>
                <tbody>
                <tr class="bg-background/40">
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Heritage Color Palette</th>
                    <td class="px-5 py-3.5 border-t border-border">Unique Aswan blacks, reds &amp; Rosa pinks</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Strong in blacks &amp; greens</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Mostly greys &amp; beiges</td>
                </tr>
                <tr>
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Water Absorption</th>
                    <td class="px-5 py-3.5 border-t border-border">Very low (0.1–0.4%)</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Low–moderate</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Variable by quarry</td>
                </tr>
                <tr class="bg-background/40">
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Proven Durability</th>
                    <td class="px-5 py-3.5 border-t border-border">4,000+ years of monumental use</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Well-established</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Newer to global premium projects</td>
                </tr>
                <tr>
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Sourcing</th>
                    <td class="px-5 py-3.5 border-t border-border">Factory-direct, no intermediaries</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Often via traders</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Often via traders</td>
                </tr>
                <tr class="bg-background/40">
                    <th scope="row" class="text-left font-medium px-5 py-3.5 border-t border-border">Export Logistics</th>
                    <td class="px-5 py-3.5 border-t border-border">Short, well-documented routes</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Long-haul shipping</td>
                    <td class="px-5 py-3.5 border-t border-border text-muted-foreground">Long-haul shipping</td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>
    <section class="border-y border-border bg-secondary/30">
        <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-24">
            <div class="mb-10 md:mb-14 max-w-3xl">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">Testimonials</div>
                <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Trusted by International Buyers</h2>
                <p class="mt-4 text-base text-muted-foreground">Sample testimonials below — placeholder copy to be
                    replaced with real client feedback as approvals are received.</p></div>
            <div class="grid md:grid-cols-3 gap-6">
                <figure class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-quote h-6 w-6 text-gold mb-4" aria-hidden="true">
                        <path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>
                        <path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>
                    </svg>
                    <blockquote class="text-sm text-muted-foreground leading-relaxed flex-1">"Sample testimonial — replace
                        with real client feedback. Egyptian granite supplied for a major hospitality project; consistent
                        color, on-time delivery, professional packing."</blockquote>
                    <figcaption class="mt-5 pt-5 border-t border-border">
                        <div class="font-display text-base">Placeholder Client Name</div>
                        <div class="text-xs text-muted-foreground mt-0.5">Procurement Manager · Sample Hospitality Group</div>
                    </figcaption>
                </figure>
                <figure class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-quote h-6 w-6 text-gold mb-4" aria-hidden="true">
                        <path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>
                        <path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>
                    </svg>
                    <blockquote class="text-sm text-muted-foreground leading-relaxed flex-1">"Sample testimonial — replace
                        with real client feedback. Mixed-container shipment of Aswan and Halayeb slabs arrived in
                        excellent condition with full export documentation."</blockquote>
                    <figcaption class="mt-5 pt-5 border-t border-border">
                        <div class="font-display text-base">Placeholder Importer Name</div>
                        <div class="text-xs text-muted-foreground mt-0.5">Director · Sample Stone Imports</div>
                    </figcaption>
                </figure>
                <figure class="p-6 rounded-2xl bg-card border border-border hover:border-gold/40 hover:shadow-luxe transition-all flex flex-col">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-quote h-6 w-6 text-gold mb-4" aria-hidden="true">
                        <path d="M16 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>
                        <path d="M5 3a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2 1 1 0 0 1 1 1v1a2 2 0 0 1-2 2 1 1 0 0 0-1 1v2a1 1 0 0 0 1 1 6 6 0 0 0 6-6V5a2 2 0 0 0-2-2z"/>
                    </svg>
                    <blockquote class="text-sm text-muted-foreground leading-relaxed flex-1">"Sample testimonial — replace
                        with real client feedback. Factory-direct pricing and clear technical specifications made
                        specification simple for our architectural team."</blockquote>
                    <figcaption class="mt-5 pt-5 border-t border-border">
                        <div class="font-display text-base">Placeholder Architect Name</div>
                        <div class="text-xs text-muted-foreground mt-0.5">Principal · Sample Architecture Studio</div>
                    </figcaption>
                </figure>
            </div>
        </section>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="mb-10 md:mb-14 max-w-3xl">
            <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3">FAQ</div>
            <h2 class="font-display text-3xl md:text-5xl leading-[1.1]">Egyptian Granite — Frequently Asked
                Questions</h2>
            <p class="mt-4 text-base text-muted-foreground">Answers to the most common questions from architects,
                contractors, and international importers.</p></div>
        <div class="max-w-3xl mx-auto rounded-2xl border border-border bg-card px-6 md:px-8 py-2 shadow-luxe">
            <details class="border-b border-border group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>Is Egyptian granite good quality?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">Yes. Egyptian granite is a dense igneous
                    stone with very low water absorption (around 0.1–0.4%) and high compressive strength, making it
                    durable, scratch- and frost-resistant. Quarried mainly in Aswan, it has been used in monuments for
                    over 4,000 years and performs well both indoors and outdoors.</div>
            </details>
            <details class="border-b border-border group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>What colors does Egyptian granite come in?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">Egyptian granite spans 30+ colors,
                    including Black Aswan, Red Aswan, Halayeb and New Halayeb greys, Rosa El Nasr and Rosa Hody pinks,
                    Gandola Aswan gold-reds, Grey Shirka, Karnak Grey, and the Verdi green family. Each is quarried in
                    Egypt and available as blocks, slabs, tiles, and cut-to-size.</div>
            </details>
            <details class="border-b border-border group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>How much does Egyptian granite cost?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">Price depends on the color, thickness (2
                    or 3 cm), finish, order quantity, and shipping terms (FOB or CIF). Rarer colors and special finishes
                    cost more, while factory-direct sourcing and full-container loads lower the unit price. Contact us
                    with your specification and destination port for an accurate quote.</div>
            </details>
            <details class="border-b border-border group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>What forms and finishes are available?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">Egyptian granite is supplied as blocks,
                    gangsaw and cutter slabs, tiles, stairs, countertops, monuments, cubes, pavers, and cut-to-size.
                    Finishes include polished, honed, flamed, brushed, sandblasted, bush-hammered, leathered, tumbled,
                    split-face, and acid-washed — chosen by application and slip-resistance needs.</div>
            </details>
            <details class="border-b border-border group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>Can you export Egyptian granite worldwide?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">Yes. We ship factory-direct from
                    Egyptian ports to 35+ countries across the Middle East, Europe, Africa, Asia, and North America.
                    Every order includes quality inspection, ISPM-15 wooden packing, secure container loading, and full
                    export documentation, with FOB and CIF quotation support and mixed-container options.</div>
            </details>
            <details class="border-b border-border group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>What is the difference between Egyptian and Indian or Chinese granite?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">Egyptian granite offers a unique
                    heritage palette — especially Aswan blacks and reds — with very low water absorption and durability
                    proven over millennia. Sourcing factory-direct from Egypt removes intermediaries, giving competitive
                    pricing, consistent quality control, and shorter, well-documented export logistics for international
                    projects.</div>
            </details>
            <details class="group">
                <summary class="font-display text-base md:text-lg text-left py-4 cursor-pointer list-none flex items-center justify-between gap-4">
                    <span>What is the minimum order and lead time?</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-chevron-down h-4 w-4 shrink-0 transition-transform group-open:rotate-180"
                         aria-hidden="true">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </summary>
                <div class="text-sm text-muted-foreground leading-relaxed pb-4">We welcome mixed containers and
                    full-container loads, so buyers can combine colors, sizes, and finishes in one shipment. Lead time
                    depends on material availability, processing, and finish, and is confirmed with your quotation. Share
                    your sizes, thickness, quantity, and destination port to get started.</div>
            </details>
        </div>
        <div class="mt-10 text-center"><a href="<?php echo esc_url(home_url('/contact')); ?>"
                                          class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">Request
                a Quote
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                    <path d="M5 12h14"/>
                    <path d="m12 5 7 7-7 7"/>
                </svg>
            </a></div>
    </section>
    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
        <div class="relative rounded-3xl overflow-hidden p-10 md:p-20 text-center border border-border shadow-luxe"
             style="background:var(--gradient-luxe)">
            <div class="absolute inset-0 grid-glow opacity-60"></div>
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full blur-3xl opacity-40"
                 style="background:var(--gradient-gold)"></div>
            <div class="relative">
                <div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-4">Start Your Order</div>
                <h2 class="font-display text-4xl md:text-6xl leading-[1.05] max-w-3xl mx-auto">Source Premium Egyptian
                    Granite Directly from Egypt</h2>
                <p class="mt-6 text-muted-foreground max-w-2xl mx-auto">Send your required material, sizes, thickness,
                    finish, quantity, and destination port. Our export team will prepare a professional quotation.</p>
                <div class="mt-8 flex flex-wrap gap-3 justify-center"><a
                            href="<?php echo esc_url(home_url('/contact')); ?>"
                            class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">Request
                        a Quote
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-arrow-right h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a><a href="mailto:info@egyptiangranite.com"
                           class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition border border-foreground/20 hover:border-gold hover:text-gold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-mail h-4 w-4" aria-hidden="true">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/>
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                        </svg>
                        Email Us</a><a href="<?php echo esc_url(home_url('/egyptian-granite')); ?>"
                                       class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition hover:bg-secondary">Browse
                        Products</a></div>
            </div>
        </div>
    </section><!--/$--></main>
