/**
 * University of Aberdeen Theme - Main Scripts
 * Includes Hero Slider, Lead Modal, Interactivity API Filter, and Task 8 Click Tracking
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initHeroSlider();
		initLeadModal();
		initProgrammeFilter();
		initClickTracking();
	});

	/* ==========================================================================
	   1. Hero Slider (Desktop P1)
	   ========================================================================== */
	function initHeroSlider() {
		var slides = document.querySelectorAll('.hero-slider-section .slide-item');
		var dots = document.querySelectorAll('#sliderDots .slider-dot');
		var prevBtn = document.getElementById('sliderPrevBtn');
		var nextBtn = document.getElementById('sliderNextBtn');

		if (!slides.length) return;

		var currentIndex = 0;
		var timer = null;

		function showSlide(index) {
			if (index < 0) index = slides.length - 1;
			if (index >= slides.length) index = 0;

			slides.forEach(function (slide, i) {
				if (i === index) {
					slide.classList.add('active');
				} else {
					slide.classList.remove('active');
				}
			});

			dots.forEach(function (dot, i) {
				if (i === index) {
					dot.classList.add('active');
				} else {
					dot.classList.remove('active');
				}
			});

			currentIndex = index;
		}

		function startAutoPlay() {
			stopAutoPlay();
			timer = setInterval(function () {
				showSlide(currentIndex + 1);
			}, 6000);
		}

		function stopAutoPlay() {
			if (timer) clearInterval(timer);
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				showSlide(currentIndex + 1);
				startAutoPlay();
			});
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				showSlide(currentIndex - 1);
				startAutoPlay();
			});
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				var idx = parseInt(this.getAttribute('data-slide-index'), 10);
				showSlide(idx);
				startAutoPlay();
			});
		});

		var sliderWrap = document.querySelector('.hero-slider-section');
		if (sliderWrap) {
			sliderWrap.addEventListener('mouseenter', stopAutoPlay);
			sliderWrap.addEventListener('mouseleave', startAutoPlay);
		}

		startAutoPlay();
	}

	/* ==========================================================================
	   2. Lead Form Popup & Thank You Modal (Figma Lead Form)
	   ========================================================================== */
	function initLeadModal() {
		var modal = document.getElementById('leadModalOverlay');
		var closeBtn = document.getElementById('leadModalClose');
		var formContainer = document.getElementById('leadModalFormContainer');
		var thankYouContainer = document.getElementById('leadModalThankYou');
		var form = document.getElementById('leadCaptureForm');
		var openButtons = document.querySelectorAll('.js-open-lead-modal');
		var closeThankYouBtn = document.querySelector('.js-close-thankyou');

		if (!modal) return;

		function openModal() {
			modal.classList.add('active');
			modal.setAttribute('aria-hidden', 'false');
			document.body.style.overflow = 'hidden';
		}

		function closeModal() {
			modal.classList.remove('active');
			modal.setAttribute('aria-hidden', 'true');
			document.body.style.overflow = '';
			// Reset view after transition
			setTimeout(function () {
				if (formContainer && thankYouContainer) {
					formContainer.style.display = 'block';
					thankYouContainer.style.display = 'none';
				}
			}, 300);
		}

		openButtons.forEach(function (btn) {
			btn.addEventListener('click', function (e) {
				e.preventDefault();
				openModal();
			});
		});

		if (closeBtn) {
			closeBtn.addEventListener('click', closeModal);
		}

		if (closeThankYouBtn) {
			closeThankYouBtn.addEventListener('click', closeModal);
		}

		modal.addEventListener('click', function (e) {
			if (e.target === modal) {
				closeModal();
			}
		});

		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && modal.classList.contains('active')) {
				closeModal();
			}
		});

		if (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				// Basic client validation
				if (!form.checkValidity()) {
					form.reportValidity();
					return;
				}

				// Switch to Thank You Alert screen from Figma design
				if (formContainer && thankYouContainer) {
					formContainer.style.display = 'none';
					thankYouContainer.style.display = 'block';
				}
				form.reset();
			});
		}
	}

	/* ==========================================================================
	   3. Programme Filter & Routing (Task 4 & Task 7)
	   ========================================================================== */
	function initProgrammeFilter() {
		var filterPills = document.querySelectorAll('.program-filter-bar .filter-pill');
		var cardsGrid = document.getElementById('programsGrid');
		var spinner = document.getElementById('filterLoadingSpinner');
		var pageHeading = document.getElementById('programmesPageHeading');

		if (!filterPills.length || !cardsGrid) return;

		filterPills.forEach(function (pill) {
			pill.addEventListener('click', function (e) {
				e.preventDefault();
				var filterSlug = this.getAttribute('data-filter-slug');
				var targetUrl = this.getAttribute('href');

				// Update active tab style
				filterPills.forEach(function (p) { p.classList.remove('active'); });
				this.classList.add('active');

				// Update page title
				if (pageHeading) {
					if (filterSlug === 'undergraduate') {
						pageHeading.textContent = 'Undergraduate Programmes';
					} else if (filterSlug === 'postgraduate') {
						pageHeading.textContent = 'Postgraduate Programmes';
					} else {
						pageHeading.textContent = 'Our Programmes';
					}
				}

				// Update browser history URL (Task 7 requirement)
				if (window.history && window.history.pushState) {
					window.history.pushState({ filter: filterSlug }, '', targetUrl);
				}

				// Show loading state briefly (Task 7 requirement)
				if (spinner) {
					spinner.style.display = 'flex';
					cardsGrid.style.opacity = '0.3';
				}

				setTimeout(function () {
					var cards = cardsGrid.querySelectorAll('.program-card');
					var visibleCount = 0;

					cards.forEach(function (card) {
						var cardType = card.getAttribute('data-program-type');
						if (filterSlug === 'all' || cardType === filterSlug) {
							card.style.display = 'flex';
							visibleCount++;
						} else {
							card.style.display = 'none';
						}
					});

					// Empty state handling (Task 7 requirement)
					var existingEmpty = document.getElementById('filterEmptyNotice');
					if (visibleCount === 0) {
						if (!existingEmpty) {
							var emptyDiv = document.createElement('div');
							emptyDiv.id = 'filterEmptyNotice';
							emptyDiv.className = 'filter-empty-state';
							emptyDiv.innerHTML = '<h3>No programmes found</h3><p>There are currently no programmes listed in this category.</p>';
							cardsGrid.appendChild(emptyDiv);
						}
					} else if (existingEmpty) {
						existingEmpty.remove();
					}

					if (spinner) {
						spinner.style.display = 'none';
						cardsGrid.style.opacity = '1';
					}
				}, 180);
			});
		});

		// Handle browser back/forward buttons
		window.addEventListener('popstate', function () {
			var currentPath = window.location.pathname;
			filterPills.forEach(function (pill) {
				if (pill.getAttribute('href') === currentPath) {
					pill.click();
				}
			});
		});
	}

	/* ==========================================================================
	   4. Click Tracking (Task 8: Data Layer + Log File)
	   ========================================================================== */
	function initClickTracking() {
		var restEndpoint = (window.AberdeenConfig && window.AberdeenConfig.restUrl)
			? window.AberdeenConfig.restUrl
			: '/wp-json/programs/v1/track';

		// Delegated click listener on document (Task 8 requirement: event delegation)
		document.addEventListener('click', function (e) {
			var trackTarget = e.target.closest('[data-track]');
			if (!trackTarget) return;

			var rawData = trackTarget.getAttribute('data-track');
			if (!rawData) return;

			var payload;
			try {
				payload = JSON.parse(rawData);
			} catch (err) {
				// Task 8: ignore invalid JSON without throwing errors
				return;
			}

			if (!payload || typeof payload !== 'object') return;

			var jsonPayload = JSON.stringify(payload);

			// Send with navigator.sendBeacon or fetch keepalive (Task 8)
			try {
				if (navigator.sendBeacon) {
					var blob = new Blob([jsonPayload], { type: 'application/json' });
					navigator.sendBeacon(restEndpoint, blob);
				} else if (window.fetch) {
					fetch(restEndpoint, {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: jsonPayload,
						keepalive: true
					}).catch(function () {
						// Fail gracefully, never block navigation
					});
				}
			} catch (err) {
				// Tracking must never block or delay navigation
			}
		});
	}
})();
