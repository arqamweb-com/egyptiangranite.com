/* Granite Gateway — vanilla theme interactions (no framework). */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
	}

	/* ---- 1. Dark / light theme toggle ---- */
	function applyTheme(mode) {
		var root = document.documentElement;
		if (mode === 'dark') { root.classList.add('dark'); }
		else { root.classList.remove('dark'); }
		try { localStorage.setItem('gg-theme', mode); } catch (e) {}
	}
	function toggleTheme() {
		var isDark = document.documentElement.classList.contains('dark');
		applyTheme(isDark ? 'light' : 'dark');
	}
	function initTheme() {
		['gg-theme-toggle', 'gg-theme-toggle-m'].forEach(function (id) {
			var btn = document.getElementById(id);
			if (btn) { btn.addEventListener('click', toggleTheme); }
		});
	}

	/* ---- 2. Scroll-aware header ---- */
	function initScrollHeader() {
		var header = document.getElementById('site-header');
		if (!header) { return; }
		var inner = header.querySelector(':scope > div > div') || header.firstElementChild;
		function onScroll() {
			if (window.scrollY > 20) {
				header.classList.add('scrolled', 'shadow-elegant');
			} else {
				header.classList.remove('scrolled', 'shadow-elegant');
			}
		}
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });
	}

	/* ---- 3. Mobile menu ---- */
	function initMobileMenu() {
		var btn = document.getElementById('mobile-menu-btn');
		var menu = document.getElementById('mobile-menu');
		if (!btn || !menu) { return; }
		var menuIcon = btn.querySelector('.menu-icon');
		var closeIcon = btn.querySelector('.close-icon');

		function closeMenu() {
			menu.classList.add('hidden');
			btn.setAttribute('aria-expanded', 'false');
			if (menuIcon) { menuIcon.classList.remove('hidden'); }
			if (closeIcon) { closeIcon.classList.add('hidden'); }
			menu.querySelectorAll('.sub-menu.is-open').forEach(function (submenu) {
				submenu.classList.remove('is-open');
				submenu.setAttribute('aria-hidden', 'true');
			});
			menu.querySelectorAll('.gg-mobile-submenu-toggle[aria-expanded="true"]').forEach(function (toggle) {
				toggle.setAttribute('aria-expanded', 'false');
			});
		}

		btn.addEventListener('click', function () {
			var open = menu.classList.toggle('hidden') === false;
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
			if (menuIcon) { menuIcon.classList.toggle('hidden', open); }
			if (closeIcon) { closeIcon.classList.toggle('hidden', !open); }
		});

		// Close after navigating — skip parent items (handled by submenu toggle).
		menu.querySelectorAll('a').forEach(function (a) {
			var parentLi = a.parentElement;
			if (parentLi && parentLi.classList.contains('menu-item-has-children')) { return; }
			a.addEventListener('click', closeMenu);
		});
	}

	/* ---- 3b. Mobile submenu toggle ---- */
	function initMobileSubmenu() {
		var mobileNav = document.querySelector('.gg-nav-mobile');
		if (!mobileNav) { return; }

		mobileNav.querySelectorAll('li.menu-item-has-children > a').forEach(function (link) {
			var item = link.parentElement;
			var submenu = link.nextElementSibling;
			while (submenu && !submenu.classList.contains('sub-menu')) {
				submenu = submenu.nextElementSibling;
			}
			if (!submenu) { return; }

			var toggle = document.createElement('button');
			var label = link.textContent.trim() || 'submenu';
			var submenuId = submenu.id || 'gg-submenu-' + Math.random().toString(36).slice(2, 9);

			submenu.id = submenuId;
			submenu.setAttribute('aria-hidden', 'true');
			toggle.type = 'button';
			toggle.className = 'gg-mobile-submenu-toggle';
			toggle.setAttribute('aria-expanded', 'false');
			toggle.setAttribute('aria-controls', submenuId);
			toggle.setAttribute('aria-label', 'Toggle ' + label + ' submenu');
			toggle.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';

			if (item) { item.insertBefore(toggle, submenu); }

			if (item && (
				item.classList.contains('current-menu-parent') ||
				item.classList.contains('current-menu-ancestor') ||
				item.classList.contains('current_page_parent') ||
				item.classList.contains('current_page_ancestor')
			)) {
				submenu.classList.add('is-open');
				submenu.setAttribute('aria-hidden', 'false');
				toggle.setAttribute('aria-expanded', 'true');
			}

			toggle.addEventListener('click', function () {
				var isOpen = submenu.classList.toggle('is-open');
				toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
				submenu.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
			});
		});
	}

	/* ---- 4. Scroll reveal ---- */
	function initReveal() {
		var els = document.querySelectorAll('.opacity-0.translate-y-8');
		if (!els.length || !('IntersectionObserver' in window)) {
			els.forEach(function (el) { el.classList.remove('opacity-0', 'translate-y-8'); });
			return;
		}
		var io = new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					var el = entry.target;
					el.classList.remove('opacity-0', 'translate-y-8', 'blur-[2px]');
					el.classList.add('opacity-100', 'translate-y-0', 'blur-0');
					obs.unobserve(el);
				}
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
		els.forEach(function (el) { io.observe(el); });
	}

	/* ---- 5. Animated counters ---- */
	function easeOutQuart(t) { return 1 - Math.pow(1 - t, 4); }
	function animateCount(el) {
		var target = parseFloat(el.getAttribute('data-count')) || 0;
		var suffix = el.getAttribute('data-suffix') || '';
		var decimals = (String(target).split('.')[1] || '').length;
		var dur = 1800, start = null;
		function step(ts) {
			if (start === null) { start = ts; }
			var p = Math.min((ts - start) / dur, 1);
			var val = target * easeOutQuart(p);
			el.textContent = (decimals ? val.toFixed(decimals) : Math.round(val).toLocaleString()) + suffix;
			if (p < 1) { requestAnimationFrame(step); }
		}
		requestAnimationFrame(step);
	}
	function initCounters() {
		var els = document.querySelectorAll('[data-count]');
		if (!els.length) { return; }
		if (!('IntersectionObserver' in window)) { els.forEach(animateCount); return; }
		var io = new IntersectionObserver(function (entries, obs) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) { animateCount(entry.target); obs.unobserve(entry.target); }
			});
		}, { threshold: 0.4 });
		els.forEach(function (el) { io.observe(el); });
	}

	/* ---- 6. Contact form feedback ---- */
	function initContactFeedback() {
		var params = new URLSearchParams(window.location.search);
		var status = params.get('contact');
		if (!status) { return; }
		var host = document.getElementById('gg-form-status');
		var form = document.querySelector('form[action*="admin-post.php"]');
		var box = document.createElement('div');
		var ok = status === 'success';
		box.className = 'mb-2 rounded-xl px-4 py-3 text-sm ' +
			(ok ? 'bg-gold/15 text-foreground border border-gold/40'
			    : 'bg-destructive/10 text-foreground border border-destructive/40');
		box.textContent = ok
			? 'Thank you — your quote request has been sent. Our export team will reply shortly.'
			: 'Sorry, your request could not be sent. Please check the required fields or email info@egyptiangranite.com.';
		if (host) { host.appendChild(box); }
		else if (form) { form.parentNode.insertBefore(box, form); }
		if (ok && form) { form.reset(); }
		params.delete('contact');
		var url = window.location.pathname + (params.toString() ? '?' + params.toString() : '') + window.location.hash;
		window.history.replaceState({}, '', url);
		if (host) { host.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
	}

	/* ---- 7. Accordion ---- */
	function initAccordion() {
		document.querySelectorAll('[data-accordion-trigger]').forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				var id = trigger.getAttribute('data-accordion-trigger');
				var content = document.querySelector('[data-accordion-content="' + id + '"]');
				if (!content) { return; }
				var open = content.style.maxHeight && content.style.maxHeight !== '0px';
				content.style.maxHeight = open ? '0px' : content.scrollHeight + 'px';
				trigger.setAttribute('aria-expanded', open ? 'false' : 'true');
			});
		});
	}

	/* ---- 8. Cursor glow (fine pointers only) ---- */
	function initCursorGlow() {
		var glow = document.getElementById('cursor-glow');
		if (!glow || !window.matchMedia('(pointer: fine)').matches) { return; }
		document.addEventListener('mousemove', function (e) {
			glow.style.setProperty('--cx', e.clientX + 'px');
			glow.style.setProperty('--cy', e.clientY + 'px');
		});
		document.addEventListener('mouseenter', function () { glow.style.opacity = '1'; });
		document.addEventListener('mouseleave', function () { glow.style.opacity = '0'; });
	}

	/* ---- 9. Home-page product filter ---- */
	function initHomeFilter() {
		var filter = document.getElementById('gg-home-filter');
		var grid   = document.getElementById('gg-home-grid');
		if (!filter || !grid) { return; }

		var btns  = filter.querySelectorAll('button');
		var cards = grid.querySelectorAll('[data-color]');
		var active = 'all';

		function applyFilter() {
			cards.forEach(function (card) {
				var color = (card.getAttribute('data-color') || '').toLowerCase();
				var app   = (card.getAttribute('data-app')   || '').toLowerCase();
				var show  = active === 'all' || color === active || app.indexOf(active) !== -1;
				card.style.display = show ? '' : 'none';
			});
		}

		btns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				active = (btn.getAttribute('data-filter-value') || btn.textContent.trim()).toLowerCase();
				btns.forEach(function (b) {
					var isActive = b === btn;
					b.classList.toggle('bg-foreground',   isActive);
					b.classList.toggle('text-background', isActive);
					b.classList.toggle('border-foreground', isActive);
					b.classList.toggle('border-border',   !isActive);
					b.classList.toggle('hover:border-gold', !isActive);
					b.classList.toggle('hover:text-gold', !isActive);
				});
				applyFilter();
			});
		});
	}

	/* ---- 10. Blog category filter ---- */
	function initBlogFilter() {
		var filter = document.getElementById('gg-blog-filter');
		var grid   = document.getElementById('gg-blog-grid');
		if (!filter || !grid) { return; }

		var btns   = filter.querySelectorAll('button');
		var cards  = grid.querySelectorAll('[data-category]');
		var active = 'all';

		function applyFilter() {
			cards.forEach(function (card) {
				var cat  = (card.getAttribute('data-category') || '').toLowerCase();
				var show = active === 'all' || cat === active;
				card.style.display = show ? '' : 'none';
			});
		}

		btns.forEach(function (btn) {
			btn.addEventListener('click', function () {
				active = btn.textContent.trim().toLowerCase();
				btns.forEach(function (b) {
					var isActive = b === btn;
					b.classList.toggle('bg-foreground',     isActive);
					b.classList.toggle('text-background',   isActive);
					b.classList.toggle('border-foreground', isActive);
					b.classList.toggle('border-border',     !isActive);
					b.classList.toggle('hover:border-gold', !isActive);
				});
				applyFilter();
			});
		});
	}

	/* ---- 11. Product catalog filter + live search ---- */
	function initCatalogFilter() {
		var grid = document.getElementById('gg-catalog-grid');
		if (!grid) { return; }

		var searchInput = document.getElementById('gg-catalog-search');
		var searchClear = document.getElementById('gg-catalog-search-clear');
		var filterGroups = document.querySelectorAll('[data-filter-group]');
		var clearBtn = document.getElementById('gg-clear-filters');
		var activeFilters = {};
		var searchVal   = '';

		function setButtonState(btn, isActive) {
			btn.classList.toggle('bg-foreground',     isActive);
			btn.classList.toggle('text-background',   isActive);
			btn.classList.toggle('border-foreground', isActive);
			btn.classList.toggle('border-border',     !isActive);
			btn.classList.toggle('hover:border-gold', !isActive);
		}

		function cardMatches(card, group, value) {
			if (value === 'all') { return true; }

			var attr = group === 'color' ? 'data-color' : 'data-' + group;
			var values = ' ' + (card.getAttribute(attr) || '').toLowerCase() + ' ';
			return values.indexOf(' ' + value + ' ') !== -1;
		}

		function applyFilters() {
			var cards   = grid.querySelectorAll('[data-color]');
			var visible = 0;
			if (searchClear) {
				searchClear.classList.toggle('hidden', searchVal === '');
				searchClear.classList.toggle('inline-flex', searchVal !== '');
			}
			cards.forEach(function (card) {
				var heading    = card.querySelector('h3');
				var name       = heading ? heading.textContent.toLowerCase() : '';
				var searchData = (card.getAttribute('data-search') || name).toLowerCase();
				var matchFilters = Object.keys(activeFilters).every(function (group) {
					return cardMatches(card, group, activeFilters[group]);
				});
				var matchSearch = searchVal === '' || searchData.indexOf(searchVal) !== -1;
				var show = matchFilters && matchSearch;
				card.style.display = show ? '' : 'none';
				if (show) { visible++; }
			});
			var noResults = document.getElementById('gg-no-results');
			if (noResults) { noResults.style.display = visible === 0 ? '' : 'none'; }
			var countEl = document.getElementById('gg-count');
			if (countEl) { countEl.textContent = visible + ' Egyptian granite types · Export-ready'; }
		}

		filterGroups.forEach(function (groupEl) {
			var group = groupEl.getAttribute('data-filter-group');
			var btns = groupEl.querySelectorAll('button');
			activeFilters[group] = 'all';

			btns.forEach(function (btn) {
				btn.addEventListener('click', function () {
					activeFilters[group] = (btn.getAttribute('data-filter-value') || btn.textContent.trim()).toLowerCase();
					btns.forEach(function (b) {
						setButtonState(b, b === btn);
					});
					applyFilters();
				});
			});
		});

		if (searchInput) {
			searchInput.addEventListener('input', function () {
				searchVal = this.value.trim().toLowerCase();
				applyFilters();
			});
		}

		if (searchClear && searchInput) {
			searchClear.addEventListener('click', function () {
				searchVal = '';
				searchInput.value = '';
				searchInput.focus();
				applyFilters();
			});
		}

		if (clearBtn) {
			clearBtn.addEventListener('click', function () {
				searchVal = '';
				if (searchInput) { searchInput.value = ''; }
				filterGroups.forEach(function (groupEl) {
					var group = groupEl.getAttribute('data-filter-group');
					var btns = groupEl.querySelectorAll('button');
					activeFilters[group] = 'all';
					btns.forEach(function (btn, index) {
						setButtonState(btn, index === 0);
					});
				});
				applyFilters();
			});
		}

		applyFilters();
	}

	ready(function () {
		initTheme();
		initScrollHeader();
		initMobileMenu();
		initMobileSubmenu();
		initReveal();
		initCounters();
		initContactFeedback();
		initAccordion();
		initCursorGlow();
		initHomeFilter();
		initBlogFilter();
		initCatalogFilter();
	});
})();
