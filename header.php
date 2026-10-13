<?php
/**
 * Header.
 *
 * @package Granite_Gateway
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#F8F7F3"/>
    <?php // Prevent dark-mode flash: apply saved theme before paint. ?>
    <script>
        (function () {
            try {
                var t = localStorage.getItem('gg-theme');
                if (t === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {
            }
        })();
    </script>
    <style>
        .gg-nav-desktop, .gg-nav-mobile, .gg-footer-menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .custom-logo-link {
            display: inline-flex;
            align-items: center;
        }

        .custom-logo {
            height: 56px;
            width: auto;
            max-width: 260px;
            display: block;
        }

        .gg-nav-desktop {
            display: flex;
            align-items: center;
            gap: .25rem;
        }

        .gg-nav-mobile {
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .gg-footer-menu {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        #mobile-menu {
            background: var(--background);
            background: color-mix(in oklab, var(--background) 86%, transparent);
            -webkit-backdrop-filter: blur(30px) saturate(155%);
            backdrop-filter: blur(30px) saturate(155%);
            box-shadow: 0 18px 40px -28px rgba(15, 23, 31, .5);
        }

        #gg-theme-toggle .gg-sun {
            display: none;
        }

        html.dark #gg-theme-toggle .gg-sun {
            display: inline;
        }

        html.dark #gg-theme-toggle .gg-moon {
            display: none;
        }

        /* ── Desktop dropdown submenu ─────────────────────────────── */
        .gg-nav-desktop .menu-item-has-children {
            position: relative;
        }

        .gg-nav-desktop .sub-menu {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            min-width: 230px;
            list-style: none;
            margin: 0;
            padding: 8px;
            border-radius: 12px;
            border: 1px solid var(--border);
            border-color: color-mix(in oklab, var(--border) 86%, transparent);
            background: var(--card);
            background: color-mix(in oklab, var(--card) 92%, transparent);
            -webkit-backdrop-filter: blur(26px) saturate(150%);
            backdrop-filter: blur(26px) saturate(150%);
            box-shadow: 0 24px 60px -32px rgba(15, 23, 31, .52), 0 8px 24px -20px rgba(15, 23, 31, .34);
            z-index: 60;
            /* hidden state */
            visibility: hidden;
            opacity: 0;
            transform: translateY(6px) scale(.98);
            transform-origin: top left;
            pointer-events: none;
            transition: opacity .16s ease, transform .16s ease, visibility 0s linear .16s;
        }

        .gg-nav-desktop .menu-item-has-children:hover > .sub-menu,
        .gg-nav-desktop .menu-item-has-children:focus-within > .sub-menu {
            visibility: visible;
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
            transition: opacity .16s ease, transform .16s ease;
        }

        /* keep dropdown open when mouse moves from link to dropdown */
        .gg-nav-desktop .sub-menu::before {
            content: '';
            position: absolute;
            top: -12px;
            left: 0;
            right: 0;
            height: 12px;
        }

        .gg-nav-desktop .sub-menu a {
            display: block;
            padding: 9px 11px;
            border-radius: 8px;
            white-space: nowrap;
            font-size: .8125rem;
            color: var(--foreground);
            transition: background-color .12s ease, color .12s ease, transform .12s ease;
        }

        .gg-nav-desktop .sub-menu a:hover,
        .gg-nav-desktop .sub-menu a:focus {
            background-color: var(--secondary);
            color: var(--foreground);
            transform: translateX(2px);
        }

        /* Chevron on parent items */
        .gg-nav-desktop li.menu-item-has-children > a {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .gg-nav-chevron {
            width: 14px;
            height: 14px;
            opacity: .6;
            flex-shrink: 0;
            transition: transform .15s ease, opacity .15s ease;
        }

        .gg-nav-desktop li.menu-item-has-children:hover > a .gg-nav-chevron,
        .gg-nav-desktop li.menu-item-has-children:focus-within > a .gg-nav-chevron {
            transform: rotate(180deg);
            opacity: .9;
        }

        /* ── Mobile submenu ──────────────────────────────────────── */
        .gg-nav-mobile .menu-item-has-children {
            position: relative;
        }

        .gg-nav-mobile .menu-item-has-children > a {
            padding-right: 3rem;
        }

        .gg-mobile-submenu-toggle {
            position: absolute;
            top: 2px;
            right: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--background);
            box-shadow: 0 1px 2px rgba(15, 23, 31, .08);
            color: var(--muted-foreground);
            transition: background-color .15s ease, border-color .15s ease, color .15s ease, box-shadow .15s ease;
        }

        .gg-mobile-submenu-toggle:hover,
        .gg-mobile-submenu-toggle:focus {
            background: var(--secondary);
            border-color: var(--gold);
            color: var(--foreground);
            outline: none;
            box-shadow: 0 3px 10px -8px rgba(15, 23, 31, .45);
        }

        .gg-mobile-submenu-toggle[aria-expanded="true"] {
            background: var(--secondary);
            border-color: var(--gold);
            color: var(--foreground);
        }

        .gg-mobile-submenu-toggle svg {
            width: 18px;
            height: 18px;
            opacity: .78;
            transition: transform .2s ease, opacity .2s ease;
        }

        .gg-mobile-submenu-toggle[aria-expanded="true"] svg {
            transform: rotate(180deg);
            opacity: 1;
        }

        .gg-nav-mobile .sub-menu {
            list-style: none;
            margin: 2px 0 4px 12px;
            padding: 0 0 0 12px;
            border-left: 1px solid var(--border);
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height .24s ease, opacity .18s ease;
        }

        .gg-nav-mobile .sub-menu.is-open {
            max-height: 600px;
            opacity: 1;
        }

        .gg-nav-mobile .sub-menu a {
            color: var(--muted-foreground);
            padding-top: .5rem;
            padding-bottom: .5rem;
        }

        .gg-nav-mobile .sub-menu a:hover {
            color: var(--foreground);
        }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="flex min-h-screen flex-col">
    <header id="site-header" class="sticky top-0 z-50 glass border-b border-border/60">
        <div class="mx-auto max-w-7xl px-4 md:px-6">
            <div class="flex h-16 items-center justify-between gap-4">

                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a class="flex items-center gap-2 group" href="<?php echo esc_url(home_url('/')); ?>">
                        <span class="flex h-9 w-9 items-center justify-center rounded-md bg-foreground text-background font-display text-lg font-semibold tracking-tighter shadow-sm">EG</span>
                        <span class="flex flex-col leading-none">
							<span class="font-display text-base font-medium tracking-tight"><?php bloginfo('name'); ?></span>
							<span class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">by EG</span>
						</span>
                    </a>
                <?php endif; ?>

                <nav class="hidden lg:flex items-center text-sm"
                     aria-label="<?php esc_attr_e('Primary', 'granite-gateway'); ?>">
                    <?php gg_nav_menu('primary', array('menu_class' => 'gg-nav-desktop')); ?>
                </nav>

                <div class="hidden lg:flex items-center gap-2">
                    <a href="mailto:<?php echo esc_attr('info@egyptiangranite.com'); ?>"
                       class="p-2 rounded-md hover:bg-secondary text-muted-foreground" aria-label="Email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="h-4 w-4" aria-hidden="true">
                            <path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/>
                            <rect x="2" y="4" width="20" height="16" rx="2"/>
                        </svg>
                    </a>

                    <a href="https://wa.me/201001649770" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"
                       class="p-2 rounded-md hover:bg-secondary text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="h-4 w-4" aria-hidden="true">
                            <path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/>
                        </svg>
                    </a>
                    <button id="gg-theme-toggle" type="button"
                            class="p-2 rounded-md hover:bg-secondary text-muted-foreground" aria-label="Toggle theme">
                        <svg class="gg-moon h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/>
                        </svg>
                        <svg class="gg-sun h-4 w-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="4"/>
                            <path d="M12 2v2"/>
                            <path d="M12 20v2"/>
                            <path d="m4.93 4.93 1.41 1.41"/>
                            <path d="m17.66 17.66 1.41 1.41"/>
                            <path d="M2 12h2"/>
                            <path d="M20 12h2"/>
                            <path d="m6.34 17.66-1.41 1.41"/>
                            <path d="m19.07 4.93-1.41 1.41"/>
                        </svg>
                    </button>
                    <a href="<?php echo esc_url(home_url('/contact')); ?>"
                       class="ml-2 inline-flex items-center rounded-full bg-foreground text-background px-4 py-2 text-sm font-medium hover:opacity-90 transition shadow-sm">Request
                        a Quote</a>
                </div>

                <button id="mobile-menu-btn" type="button" class="lg:hidden p-2 rounded-md hover:bg-secondary"
                        aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="menu-icon h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 5h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 19h16"/>
                    </svg>
                    <svg class="close-icon hidden h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="lg:hidden hidden border-t border-border/60">
            <div class="mx-auto max-w-7xl px-4 md:px-6 py-4">
                <nav aria-label="<?php esc_attr_e('Mobile', 'granite-gateway'); ?>">
                    <?php gg_nav_menu('primary', array('menu_class' => 'gg-nav-mobile', 'menu_id' => 'mobile-menu-list')); ?>
                </nav>
                <div class="mt-4 flex items-center gap-3">
                    <a href="<?php echo esc_url(home_url('/contact')); ?>"
                       class="inline-flex items-center rounded-full bg-foreground text-background px-4 py-2 text-sm font-medium">Request
                        a Quote</a>
                    <button id="gg-theme-toggle-m" type="button"
                            class="p-2 rounded-md hover:bg-secondary text-muted-foreground" aria-label="Toggle theme">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>
