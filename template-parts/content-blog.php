<?php
/**
 * Blog listing — native WordPress posts via WP_Query.
 *
 * @package Granite_Gateway
 */
?>
<main class="flex-1">
<section class="relative hero-bg stone-grain pt-16 pb-20 md:pt-24 md:pb-28 border-b border-border overflow-hidden">
	<div class="absolute inset-0 grid-glow opacity-60"></div>
	<div class="relative mx-auto max-w-7xl px-4 md:px-6 text-center">
		<div class="inline-block text-[11px] uppercase tracking-[0.25em] text-gold mb-4">Knowledge Center</div>
		<h1 class="font-display text-4xl md:text-6xl leading-[1.05] max-w-4xl mx-auto">Egyptian Granite Blog</h1>
		<p class="mt-5 text-base md:text-lg text-muted-foreground max-w-2xl mx-auto">Guides and reference content for architects, contractors, and international importers sourcing granite from Egypt.</p>
	</div>
</section>

<section class="mx-auto max-w-7xl px-4 md:px-6 py-16 md:py-24">
	<nav aria-label="Breadcrumb" class="text-xs text-muted-foreground">
		<ol class="flex flex-wrap items-center gap-1.5">
			<li class="flex items-center gap-1.5">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-gold">Home</a>
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right h-3 w-3" aria-hidden="true"><path d="m9 18 6-6-6-6" /></svg>
			</li>
			<li class="flex items-center gap-1.5"><span class="text-foreground">Blog</span></li>
		</ol>
	</nav>

	<div id="gg-blog-filter" class="mt-8 flex flex-wrap gap-2">
		<button class="px-3 py-1.5 rounded-full text-xs transition border bg-foreground text-background border-foreground">All</button>
		<button class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">Egyptian Granite Guide</button>
		<button class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">Granite Types</button>
		<button class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">Import from Egypt</button>
		<button class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">Granite Finishes</button>
		<button class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">Granite Applications</button>
		<button class="px-3 py-1.5 rounded-full text-xs transition border border-border hover:border-gold">Project Inspiration</button>
	</div>

	<div id="gg-blog-grid" class="mt-8 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
		<?php
		$blog_query = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 12,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		while ( $blog_query->have_posts() ) :
			$blog_query->the_post();

			$cats     = get_the_category();
			$cat_name = ! empty( $cats ) ? $cats[0]->name : '';
			$cat_slug = strtolower( $cat_name );

			$word_count = str_word_count( wp_strip_all_tags( get_the_content() ) );
			$read_time  = max( 1, (int) ceil( $word_count / 200 ) );
			?>
			<a href="<?php the_permalink(); ?>" data-category="<?php echo esc_attr( $cat_slug ); ?>" class="group flex flex-col p-6 rounded-2xl bg-card border border-border hover:border-gold/50 hover:shadow-luxe transition-all">
				<?php if ( $cat_name ) : ?>
					<div class="text-[10px] uppercase tracking-widest text-gold mb-3"><?php echo esc_html( $cat_name ); ?></div>
				<?php endif; ?>
				<h3 class="font-display text-xl group-hover:text-gold transition"><?php the_title(); ?></h3>
				<p class="text-sm text-muted-foreground mt-2 line-clamp-3 flex-1"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<div class="mt-4 text-xs text-muted-foreground"><?php echo esc_html( $read_time ); ?> min read</div>
			</a>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
	</div>
</section>
</main>
