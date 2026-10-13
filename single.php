<?php
/**
 * Single native post (blog articles).
 *
 * Seeded posts embed their own full layout (incl. <main>). Posts created via
 * the dashboard fall back to a styled wrapper.
 *
 * @package Granite_Gateway
 */

get_header();

while ( have_posts() ) :
	the_post();
	$raw = get_the_content();

	if ( false !== strpos( $raw, '<main' ) ) :
		the_content();
	else :
		$cats     = get_the_category();
		$cat_name = ! empty( $cats ) ? $cats[0]->name : 'Blog';
		?>
		<main class="flex-1">
			<div class="mx-auto max-w-4xl px-4 md:px-6 py-16">
				<nav class="text-sm text-muted-foreground mb-6">
					<a class="hover:text-gold transition" href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Blog</a>
					<span> / </span><?php the_title(); ?>
				</nav>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="rounded-2xl overflow-hidden mb-8 border border-border">
						<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
					</div>
				<?php endif; ?>
				<div class="text-[11px] uppercase tracking-[0.25em] text-gold mb-3"><?php echo esc_html( $cat_name ); ?></div>
				<h1 class="font-display text-4xl md:text-5xl mb-4"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="text-lg text-muted-foreground mb-8"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<div class="space-y-4 leading-relaxed"><?php the_content(); ?></div>
				<div class="mt-10">
					<a class="inline-flex items-center rounded-full border border-border hover:border-gold px-5 py-2.5 text-sm transition" href="<?php echo esc_url( home_url( '/blog' ) ); ?>">&#8592; Back to Blog</a>
				</div>
			</div>
		</main>
		<?php
	endif;
endwhile;

get_footer();
