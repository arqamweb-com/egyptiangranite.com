<?php
/**
 * Footer.
 *
 * @package Granite_Gateway
 */
?>
	<footer class="mt-24 border-t border-border bg-secondary/40">
		<div class="mx-auto max-w-7xl px-4 md:px-6 py-16 grid gap-10 md:grid-cols-2 lg:grid-cols-4">

			<div>
				<div class="flex items-center gap-2 mb-4">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<span class="flex h-9 w-9 items-center justify-center rounded-md bg-foreground text-background font-display font-semibold">EG</span>
						<div class="leading-tight">
							<div class="font-display text-base"><?php bloginfo( 'name' ); ?></div>
							<div class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">by EG</div>
						</div>
					<?php endif; ?>
				</div>
				<p class="text-sm text-muted-foreground mb-4">The Global Source for Premium Egyptian Granite</p>
				<p class="text-sm">egyptiangranite.com<br />
					<a class="hover:text-gold transition" href="mailto:info@egyptiangranite.com">info@egyptiangranite.com</a>
				</p>
			</div>

			<div>
				<h4 class="text-sm font-semibold uppercase tracking-wider mb-4 text-muted-foreground">Featured Granite</h4>
				<?php gg_nav_menu( 'footer_products', array( 'menu_class' => 'gg-footer-menu text-sm' ) ); ?>
			</div>

			<div>
				<h4 class="text-sm font-semibold uppercase tracking-wider mb-4 text-muted-foreground">Company</h4>
				<?php gg_nav_menu( 'footer_company', array( 'menu_class' => 'gg-footer-menu text-sm' ) ); ?>
			</div>

			<div>
				<h4 class="text-sm font-semibold uppercase tracking-wider mb-4 text-muted-foreground">Resources</h4>
				<?php gg_nav_menu( 'footer_resources', array( 'menu_class' => 'gg-footer-menu text-sm' ) ); ?>
			</div>
		</div>

		<div class="border-t border-border">
		    <p class="site-credit mx-auto max-w-7xl px-4 md:px-6 py-6 text-xs text-muted-foreground text-center">
                &copy;
                <span id="y"><?php echo esc_html(wp_date('Y')); ?></span> <?php bloginfo( 'name' ); ?><?php echo esc_html(__('. All rights reserved.', 'arqamweb')) ?>
                <span aria-hidden="true"> · </span>
                <?php echo esc_html(__('Designed and Developed by', 'arqamweb')) ?>
                <a href="https://www.arqamweb.com"
                   class="arqam-credit hover:text-gold"><?php echo esc_html(__('Arqam Web', 'arqamweb')) ?></a>

                <style>
                    .arqam-credit:focus-visible {
                        outline: 2px solid gold;
                        outline-offset: 3px;
                    }
                </style>
            </p>
		</div>
	</footer>
</div><!-- /.flex.min-h-screen -->

<?php wp_footer(); ?>
</body>
</html>
