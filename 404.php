<?php
/**
 * 404.
 *
 * @package Granite_Gateway
 */

get_header();
?>
<main class="flex-1">
	<div class="mx-auto max-w-2xl px-4 md:px-6 py-28 text-center">
		<p class="text-sm uppercase tracking-[0.2em] text-gold mb-4">Error 404</p>
		<h1 class="font-display text-5xl md:text-6xl mb-6">Page not found</h1>
		<p class="text-muted-foreground mb-10">The page you are looking for may have been moved or no longer exists. Explore our Egyptian granite collection or get in touch with our export team.</p>
		<div class="flex flex-wrap items-center justify-center gap-4">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center rounded-full bg-foreground text-background px-6 py-3.5 text-sm font-medium hover:opacity-90 transition">Back to Home</a>
			<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="inline-flex items-center rounded-full border border-border px-6 py-3.5 text-sm font-medium hover:border-gold transition">Contact Us</a>
		</div>
	</div>
</main>
<?php
get_footer();
