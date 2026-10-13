<?php
/**
 * Fallback template (archives, search, and any view without a more specific template).
 *
 * @package Granite_Gateway
 */

get_header();
?>
<main class="flex-1">
	<div class="mx-auto max-w-6xl px-4 md:px-6 py-16">
		<?php if ( is_search() ) : ?>
			<h1 class="font-display text-3xl md:text-4xl mb-8">
				<?php printf( esc_html__( 'Search results for: %s', 'granite-gateway' ), '<span class="text-gold">' . esc_html( get_search_query() ) . '</span>' ); ?>
			</h1>
		<?php elseif ( is_archive() ) : ?>
			<h1 class="font-display text-3xl md:text-4xl mb-8"><?php the_archive_title(); ?></h1>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="group flex flex-col rounded-2xl overflow-hidden bg-card border border-border hover:border-gold/50 transition-all duration-500 hover:-translate-y-1 hover:shadow-luxe">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="aspect-[4/3] overflow-hidden">
								<?php the_post_thumbnail( 'medium_large', array( 'class' => 'h-full w-full object-cover transition-transform duration-700 group-hover:scale-105' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="p-5">
							<h2 class="font-display text-xl mb-2"><?php the_title(); ?></h2>
							<p class="text-sm text-muted-foreground line-clamp-3"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						</div>
					</a>
					<?php
				endwhile;
				?>
			</div>
			<div class="mt-10"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
		<?php else : ?>
			<p class="text-muted-foreground"><?php esc_html_e( 'Nothing found.', 'granite-gateway' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
