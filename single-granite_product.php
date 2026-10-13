<?php
/**
 * Single Granite Product.
 *
 * Three rendering tiers (checked in order):
 *   1. ACF-driven  — when stone_origin, stone_finishes, or stone_color_group are set.
 *   2. Seeded HTML — when post content contains a <main> block (seed import).
 *   3. Basic       — plain dashboard post, no ACF, no seeded HTML.
 *
 * @package Granite_Gateway
 */

get_header();

while (have_posts()) :
    the_post();

    $acf_active = function_exists('get_field') &&
            (get_field('stone_origin') || get_field('stone_finishes') || get_field('stone_color_group'));

    /* =========================================================================
     * TIER 1 — ACF-driven template (matches Lovable design exactly)
     * ====================================================================== */
    if ($acf_active) :

        // ── Fields ──────────────────────────────────────────────────────────
        $color_group = get_field('stone_color_group') ?: '';
        $color_label = ucfirst($color_group);
        $short_description = get_the_excerpt();
        $description = get_the_content();
        $origin = get_field('stone_origin');
        $density = get_field('stone_density');
        $water_abs = get_field('stone_water_absorption');
        $hardness = get_field('stone_hardness');
        $comp_str = get_field('stone_compressive_strength');
        $flex_str = get_field('stone_flexural_strength');
        $certs = get_field('stone_certifications');
        $min_order = get_field('stone_min_order');
        $finishes = (array)(get_field('stone_finishes') ?: array());
        $forms = (array)(get_field('stone_forms') ?: array());
        $thickness = (array)(get_field('stone_thickness') ?: array());
        $sizes = get_field('stone_sizes');
        $applications = (array)(get_field('stone_applications') ?: array());
        $gallery = (array)(get_field('stone_gallery') ?: array());
        $faqs = (array)(get_field('stone_faqs') ?: array());

        // ── Gradient map (hero background + related card fallbacks) ──────────
        $gradients = array(
                'black' => 'linear-gradient(135deg,#1a1a1a,#3a3a3a,#0a0a0a)',
                'red' => 'linear-gradient(135deg,#8b2a1a,#c54a2a,#5a1812)',
                'grey' => 'linear-gradient(135deg,#5a5a5a,#8a8a8a,#3a3a3a)',
                'rosa' => 'linear-gradient(135deg,#d48a8a,#c06060,#a04040)',
                'white' => 'linear-gradient(135deg,#e8e4e0,#f0ece8,#d8d4d0)',
                'green' => 'linear-gradient(135deg,#2a5a3a,#4a8a5a,#1a3a2a)',
                'yellow' => 'linear-gradient(135deg,#8a7a2a,#c4b04a,#6a5a1a)',
                'gold' => 'linear-gradient(135deg,#8a6a2a,#c4a04a,#6a4a1a)',
        );
        $hero_gradient = isset($gradients[strtolower($color_group)])
                ? $gradients[strtolower($color_group)]
                : 'linear-gradient(135deg,#4a4a4a,#7a7a7a,#2a2a2a)';

        // ── Auto-derived key features ────────────────────────────────────────
        $features = array();
        if ($origin) {
            $features[] = 'Origin: ' . $origin;
        }
        if ($color_label) {
            $features[] = 'Color family: ' . $color_label;
        }
        if ($water_abs) {
            $features[] = 'Water absorption: ' . $water_abs;
        }
        if ($hardness) {
            $features[] = 'Hardness: ' . $hardness;
        }
        $features[] = 'Suitable for indoor and outdoor applications';
        if (!empty($finishes)) {
            $features[] = 'Full range of finishes (' . reset($finishes) . ' to ' . end($finishes) . ')';
        }
        if (!empty($forms)) {
            $features[] = ucwords(implode(', ', $forms)) . ' available';
        }
        $features[] = 'Export-ready ISPM-15 packaging';
        if ($certs) {
            $features[] = 'Certifications: ' . $certs;
        }

        // ── Related products (shared for sidebar list + bottom grid) ─────────
        $related_q = new WP_Query(array(
                'post_type' => 'granite_product',
                'post_status' => 'publish',
                'posts_per_page' => 8,
                'post__not_in' => array(get_the_ID()),
                'orderby' => 'rand',
        ));
        $related_posts = $related_q->posts;
        wp_reset_postdata();

        // Tile defaults (used in Sizes section)
        $tile_sizes = $sizes ?: '30×30, 30×60, 40×40, 60×60, 80×80 cm';
        $tile_thick = !empty($thickness) ? implode(', ', $thickness) : '1, 1.5 or 2 cm';

        ?>
        <main class="flex-1">

            <!-- ══════════════════════════════════════════════
                 HERO — image left / title + CTAs right
                 ══════════════════════════════════════════════ -->
            <section class="relative hero-bg stone-grain border-b border-border">
                <div class="absolute inset-0 grid-glow opacity-50"></div>
                <div class="relative mx-auto max-w-7xl px-4 md:px-6 py-12 md:py-20">

                    <!-- Breadcrumb -->
                    <nav aria-label="Breadcrumb" class="text-xs text-muted-foreground">
                        <ol class="flex flex-wrap items-center gap-1.5">
                            <li class="flex items-center gap-1.5">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-gold">Home</a>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-chevron-right h-3 w-3"
                                     aria-hidden="true">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <a class="hover:text-gold" href="<?php echo esc_url(home_url('/egyptian-granite')); ?>">Egyptian
                                    Granite</a>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-chevron-right h-3 w-3"
                                     aria-hidden="true">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </li>
                            <li class="flex items-center gap-1.5">
                                <span class="text-foreground"><?php the_title(); ?></span>
                            </li>
                        </ol>
                    </nav>

                    <!-- 2-column: square image left — details right -->
                    <div class="mt-8 grid lg:grid-cols-2 gap-10 items-start">

                        <!-- Product image -->
                        <div class="aspect-square rounded-3xl overflow-hidden border border-border shadow-luxe relative"
                             style="background:<?php echo esc_attr($hero_gradient); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', array('class' => 'absolute inset-0 h-full w-full object-cover', 'loading' => 'eager')); ?>
                            <?php elseif (!empty($gallery)) : ?>
                                <img
                                        src="<?php echo esc_url($gallery[0]['sizes']['large'] ?? $gallery[0]['url']); ?>"
                                        alt="<?php echo esc_attr($gallery[0]['alt']); ?>"
                                        class="absolute inset-0 h-full w-full object-cover"
                                        loading="eager"
                                />
                            <?php endif; ?>
                        </div>

                        <!-- Title, description, CTAs -->
                        <div>
                            <?php if ($color_label) : ?>
                                <div class="text-[11px] uppercase tracking-[0.25em] text-gold">
                                    Egyptian Granite · <?php echo esc_html($color_label); ?>
                                </div>
                            <?php endif; ?>
                            <h1 class="mt-3 font-display text-4xl md:text-6xl leading-[1.05]"><?php the_title(); ?></h1>
                            <?php if ($short_description) : ?>
                                <p class="mt-5 text-lg text-muted-foreground"><?php echo esc_html($short_description); ?></p>
                            <?php endif; ?>
                            <?php if ($origin || $color_label) : ?>
                                <p class="mt-3 text-sm text-muted-foreground">
                                    <?php if ($origin) : ?>Origin: <?php echo esc_html($origin); ?> · <?php endif; ?>
                                    Natural
                                    granite<?php if ($color_label) : ?> · Color: <?php echo esc_html($color_label); ?><?php endif; ?>
                                    · Worldwide export from Egypt
                                </p>
                            <?php endif; ?>
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="<?php echo esc_url(home_url('/contact')); ?>"
                                   class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">
                                    Request Quote for <?php the_title(); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4"
                                         aria-hidden="true">
                                        <path d="M5 12h14"/>
                                        <path d="m12 5 7 7-7 7"/>
                                    </svg>
                                </a>
                                <a href="mailto:info@egyptiangranite.com"
                                   class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition border border-foreground/20 hover:border-gold hover:text-gold">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-camera h-4 w-4"
                                         aria-hidden="true">
                                        <path d="M13.997 4a2 2 0 0 1 1.76 1.05l.486.9A2 2 0 0 0 18.003 7H20a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h1.997a2 2 0 0 0 1.759-1.048l.489-.904A2 2 0 0 1 10.004 4z"/>
                                        <circle cx="12" cy="13" r="3"/>
                                    </svg>
                                    Ask for Slab Photos
                                </a>
                                <a href="#tech"
                                   class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition hover:bg-secondary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-download h-4 w-4"
                                         aria-hidden="true">
                                        <path d="M12 15V3"/>
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <path d="m7 10 5 5 5-5"/>
                                    </svg>
                                    Technical Sheet
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            <!-- ══════════════════════════════════════════════
                 BODY — 3-column: content (2/3) + sidebar (1/3)
                 ══════════════════════════════════════════════ -->
            <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 ">
                <div class="grid lg:grid-cols-3 gap-12">

                    <!-- ── MAIN CONTENT ── -->
                    <div class="lg:col-span-2 space-y-10">

                        <!-- Overview -->
                        <?php if ($description) : ?>
                            <div>
                                <h2 class="font-display text-3xl mb-4">Overview of <?php the_title(); ?></h2>
                                <div class="space-y-4 text-muted-foreground leading-relaxed">
                                    <p><?php echo nl2br(esc_html($description)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Key Features -->
                        <?php if (!empty($features)) : ?>
                            <div>
                                <h2 class="font-display text-3xl mb-4">Key Features</h2>
                                <ul class="grid sm:grid-cols-2 gap-3">
                                    <?php foreach ($features as $feat) : ?>
                                        <li class="flex items-start gap-2 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-shield-check h-4 w-4 text-gold mt-0.5 shrink-0"
                                                 aria-hidden="true">
                                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                                                <path d="m9 12 2 2 4-4"/>
                                            </svg>
                                            <?php echo esc_html($feat); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Applications -->
                        <?php if (!empty($applications)) : ?>
                            <div>
                                <h2 class="font-display text-3xl mb-4">Applications</h2>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($applications as $app) : ?>
                                        <span class="px-3 py-1.5 rounded-full bg-secondary text-sm"><?php echo esc_html(ucwords(str_replace('-', ' ', $app))); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Available Forms -->
                        <?php if (!empty($forms)) : ?>
                            <div>
                                <h2 class="font-display text-3xl mb-4">Available Forms</h2>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($forms as $form) : ?>
                                        <span class="px-3 py-1.5 rounded-full border border-border text-sm"><?php echo esc_html(ucwords(str_replace('-', ' ', $form))); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Available Sizes & Thickness -->
                        <div id="sizes">
                            <h2 class="font-display text-3xl mb-4">Available Sizes &amp; Thickness</h2>
                            <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                                <div class="p-4 rounded-xl bg-card border border-border">
                                    <dt class="text-[10px] uppercase tracking-widest text-gold mb-1">Blocks</dt>
                                    <dd class="text-muted-foreground">Quarry blocks typically 2.4–3.0 m × 1.4–1.8 m ×
                                        1.2–1.8 m, weight 18–25 tons per block.
                                    </dd>
                                </div>
                                <div class="p-4 rounded-xl bg-card border border-border">
                                    <dt class="text-[10px] uppercase tracking-widest text-gold mb-1">Slabs</dt>
                                    <dd class="text-muted-foreground">Gangsaw slabs ~2.6–3.0 m × 1.6–2.0 m. Cutter slabs
                                        ~2.4 × 1.2 m. Standard thicknesses 2 cm and 3 cm; 4 cm and 5 cm on request.
                                    </dd>
                                </div>
                                <div class="p-4 rounded-xl bg-card border border-border">
                                    <dt class="text-[10px] uppercase tracking-widest text-gold mb-1">Tiles</dt>
                                    <dd class="text-muted-foreground">Standard tile
                                        sizes: <?php echo esc_html($tile_sizes); ?>.
                                        Thickness <?php echo esc_html($tile_thick); ?>.
                                    </dd>
                                </div>
                                <div class="p-4 rounded-xl bg-card border border-border">
                                    <dt class="text-[10px] uppercase tracking-widest text-gold mb-1">Cut-to-size</dt>
                                    <dd class="text-muted-foreground">Cut-to-size production to your project drawings:
                                        countertops, vanities, stairs, risers, treads, skirting, window sills, copings,
                                        pool surrounds, façade panels.
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Finish Options -->
                        <?php if (!empty($finishes)) : ?>
                            <div>
                                <h2 class="font-display text-3xl mb-4">Finish Options</h2>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    <?php foreach ($finishes as $finish) : ?>
                                        <div class="px-3 py-2 rounded-lg bg-card border border-border text-sm"><?php echo esc_html(ucwords(str_replace('-', ' ', $finish))); ?></div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="mt-3 text-sm text-muted-foreground">Choose a polished or honed finish for
                                    interior surfaces, or flamed, bush-hammered, or sandblasted for slip-resistant
                                    outdoor flooring. See our <a href="<?php echo esc_url(home_url('/finishes')); ?>"
                                                                 class="text-gold hover:underline">granite finishes
                                        guide</a>.</p>
                            </div>
                        <?php endif; ?>

                        <!-- Packing & Export -->
                        <div id="tech">
                            <h2 class="font-display text-3xl mb-4">Packing &amp; Export</h2>
                            <p class="text-muted-foreground leading-relaxed">Slabs ship in heavy-duty A-frame wooden
                                bundles with foam interleaves and steel banding. Tiles pack into fumigated wooden crates
                                that comply with ISPM-15 for international shipment. Blocks are loose-loaded in 20-ft
                                containers with hardwood dunnage. Each piece is numbered and matched to the packing
                                list, and mixed-material containers can be combined across granite types, finishes, and
                                thicknesses.</p>
                            <p class="mt-3 text-sm text-muted-foreground">Quotations available FOB (Egyptian port) or
                                CIF (your destination). Full export documentation issued per shipment. See our <a
                                        href="<?php echo esc_url(home_url('/export')); ?>"
                                        class="text-gold hover:underline">export and shipping details</a> for incoterms,
                                packing, and document scope.</p>
                        </div>

                        <?php if (!empty($faqs)) : ?>
                            <div>
                                <h2 class="font-display text-3xl mb-4">Frequently Asked Questions</h2>
                                <div class="space-y-3">
                                    <?php foreach ($faqs as $faq_post) :
                                        $q = get_the_title($faq_post);
                                        $a = get_field('faq_answer', $faq_post->ID) ?: wp_strip_all_tags($faq_post->post_content);
                                        ?>
                                        <details class="group p-5 rounded-xl bg-card border border-border">
                                            <summary
                                                    class="cursor-pointer font-medium list-none flex items-center justify-between gap-4">
                                                <?php echo esc_html($q); ?>
                                                <span class="text-gold group-open:rotate-45 transition shrink-0">+</span>
                                            </summary>
                                            <p class="mt-3 text-sm text-muted-foreground leading-relaxed"><?php echo esc_html($a); ?></p>
                                        </details>
                                    <?php endforeach; ?>
                                </div>
                                <p class="mt-4 text-sm text-muted-foreground">Have a question we have not covered? <a
                                            href="<?php echo esc_url(home_url('/contact')); ?>"
                                            class="text-gold hover:underline">Send us your inquiry</a> and our export
                                    team will reply within one business day.</p>
                            </div>
                        <?php endif; ?>

                    </div><!-- /.lg:col-span-2 -->

                    <!-- ── SIDEBAR ── -->
                    <aside class="space-y-4 lg:sticky lg:top-24 h-fit">

                        <!-- Quote request card -->
                        <div class="p-6 rounded-2xl bg-card border border-border shadow-luxe">
                            <div class="text-[10px] uppercase tracking-widest text-gold mb-2">Quote Request</div>
                            <h3 class="font-display text-2xl">Get an Export Price for <?php the_title(); ?></h3>
                            <p class="text-sm text-muted-foreground mt-2">Send your sizes, finish, quantity, and
                                destination port. We'll reply with availability and a professional quotation.</p>
                            <div class="mt-5 space-y-3">
                                <a href="<?php echo esc_url(home_url('/contact')); ?>"
                                   class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium transition bg-foreground text-background hover:opacity-90 shadow-sm">
                                    Request Quote
                                </a>
                                <a href="mailto:info@egyptiangranite.com"
                                   class="text-sm text-muted-foreground hover:text-gold transition flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="lucide lucide-mail h-4 w-4" aria-hidden="true">
                                        <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/>
                                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    </svg>
                                    info@egyptiangranite.com
                                </a>
                            </div>
                        </div>

                        <!-- Related granite (sidebar list) -->
                        <?php $sidebar_related = array_slice($related_posts, 0, 4); ?>
                        <?php if (!empty($sidebar_related)) : ?>
                            <div class="p-6 rounded-2xl bg-card border border-border">
                                <div class="text-[10px] uppercase tracking-widest text-gold mb-3">Related Egyptian
                                    Granite
                                </div>
                                <ul class="space-y-2 text-sm">
                                    <?php foreach ($sidebar_related as $rel) : ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_permalink($rel->ID)); ?>"
                                               class="flex items-center justify-between gap-3 hover:text-gold transition">
                                                <span><?php echo esc_html(get_the_title($rel->ID)); ?></span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="lucide lucide-arrow-right h-3 w-3 shrink-0"
                                                     aria-hidden="true">
                                                    <path d="M5 12h14"/>
                                                    <path d="m12 5 7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                    </aside>

                </div>
            </section>

            <!-- ══════════════════════════════════════════════
                 BOTTOM — Related Granite grid (4 columns)
                 ══════════════════════════════════════════════ -->
            <?php $bottom_related = array_slice($related_posts, 0, 4); ?>
            <?php if (!empty($bottom_related)) : ?>
                <section class="border-t border-border bg-secondary/30">
                    <section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24 !py-20">
                        <h2 class="font-display text-3xl mb-8">Related Egyptian Granite</h2>
                        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <?php foreach ($bottom_related as $rel) :
                                $rid = $rel->ID;
                                $rel_color = function_exists('get_field') ? (get_field('stone_color_group', $rid) ?: '') : '';
                                $rel_bg = isset($gradients[strtolower($rel_color)])
                                        ? $gradients[strtolower($rel_color)]
                                        : 'linear-gradient(135deg,#4a4a4a,#7a7a7a,#2a2a2a)';
                                $rel_thumb = get_the_post_thumbnail_url($rid, 'medium');
                                $rel_apps = function_exists('get_field') ? (array)(get_field('stone_applications', $rid) ?: array()) : array();
                                $rel_apps = array_slice($rel_apps ?: array('flooring', 'wall-cladding', 'facades'), 0, 3);
                                ?>
                                <a href="<?php echo esc_url(get_permalink($rid)); ?>"
                                   class="group relative flex flex-col rounded-2xl overflow-hidden bg-card border border-border hover:border-gold/50 transition-all duration-500 hover:-translate-y-1 hover:shadow-luxe">
                                    <div class="relative aspect-[4/3] overflow-hidden"
                                         style="background:<?php echo esc_attr($rel_bg); ?>">
                                        <?php if ($rel_thumb) : ?>
                                            <img src="<?php echo esc_url($rel_thumb); ?>"
                                                 alt="<?php echo esc_attr(get_the_title($rid)); ?>"
                                                 loading="lazy"
                                                 class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"/>
                                        <?php endif; ?>
                                        <?php if ($rel_color) : ?>
                                            <div class="absolute top-3 left-3">
                                                <span class="glass text-[10px] uppercase tracking-widest px-2.5 py-1 rounded-full"><?php echo esc_html(ucfirst($rel_color)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="absolute bottom-3 right-3 h-9 w-9 rounded-full glass flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-arrow-up-right h-4 w-4" aria-hidden="true">
                                                <path d="M7 7h10v10"/>
                                                <path d="M7 17 17 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="p-5">
                                        <h3 class="font-display text-xl"><?php echo esc_html(get_the_title($rid)); ?></h3>
                                        <p class="text-sm text-muted-foreground mt-1 line-clamp-2"><?php echo esc_html(get_the_excerpt($rid)); ?></p>
                                        <div class="mt-3 flex flex-wrap gap-1.5">
                                            <?php foreach ($rel_apps as $app) : ?>
                                                <span class="text-[10px] uppercase tracking-wider text-muted-foreground border border-border rounded-full px-2 py-0.5"><?php echo esc_html(ucwords(str_replace('-', ' ', $app))); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="mt-4 text-sm font-medium text-gold inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                                            View Details
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-arrow-up-right h-3.5 w-3.5" aria-hidden="true">
                                                <path d="M7 7h10v10"/>
                                                <path d="M7 17 17 7"/>
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-8">
                            <a class="text-sm text-gold inline-flex items-center gap-1 hover:gap-2 transition-all"
                               href="<?php echo esc_url(home_url('/egyptian-granite')); ?>">
                                View all Egyptian granite
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="lucide lucide-arrow-right h-4 w-4"
                                     aria-hidden="true">
                                    <path d="M5 12h14"/>
                                    <path d="m12 5 7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </section>
                </section>
            <?php endif; ?>

        </main>

    <?php
    /* =========================================================================
     * TIER 2 — Seeded HTML
     * ====================================================================== */
    elseif (false !== strpos(get_the_content(), '<main')) :

        ob_start();
        the_content();
        $html = ob_get_clean();

        echo $html; // phpcs:ignore WordPress.Security.EscapeOutput

    else :
        /* =========================================================================
         * TIER 3 — Basic fallback for plain dashboard posts
         * ====================================================================== */
        ?>
        <main class="flex-1">
            <div class="mx-auto max-w-4xl px-4 md:px-6 py-16">
                <nav class="text-sm text-muted-foreground mb-6">
                    <a class="hover:text-gold transition" href="<?php echo esc_url(home_url('/egyptian-granite')); ?>">Egyptian
                        Granite</a>
                    <span> / </span><?php the_title(); ?>
                </nav>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="rounded-2xl overflow-hidden mb-8 border border-border">
                        <?php the_post_thumbnail('large', array('class' => 'w-full h-auto object-cover')); ?>
                    </div>
                <?php endif; ?>
                <h1 class="font-display text-4xl md:text-5xl mb-4"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="text-lg text-muted-foreground mb-8"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <div class="space-y-4 leading-relaxed"><?php the_content(); ?></div>
                <div class="mt-10">
                    <a class="inline-flex items-center rounded-full bg-foreground text-background px-6 py-3.5 text-sm font-medium hover:opacity-90 transition"
                       href="<?php echo esc_url(home_url('/contact')); ?>">Request a Quote</a>
                </div>
            </div>
        </main>
    <?php
    endif;
endwhile;

get_footer();
