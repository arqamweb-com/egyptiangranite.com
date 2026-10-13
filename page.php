<?php
/**
 * Generic page fallback (used for pages without a dedicated page-{slug}.php).
 *
 * @package Granite_Gateway
 */

get_header();
?>
<main class="flex-1">
	<div class="mx-auto max-w-4xl px-4 md:px-6 py-16">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<h1 class="font-display text-4xl md:text-5xl mb-8"><?php the_title(); ?></h1>
			<div class="space-y-4 leading-relaxed"><?php the_content(); ?></div>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
