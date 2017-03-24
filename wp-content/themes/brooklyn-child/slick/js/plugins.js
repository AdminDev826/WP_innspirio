/**
 * Corsa plugins initialization and main theme JavaScript code
 *
 * @requires jQuery
 */
jQuery(document).ready(function(){
	"use strict";

	// The commonly used DOM elements
	var $window = jQuery(window),
		$html = jQuery('html'),
		$body = jQuery('.l-body'),
		$canvas = jQuery('.l-canvas'),
		$header = jQuery('.l-header'),
		$logoImg = jQuery('.w-logo-img'),
		$headerNav = jQuery('.l-header .w-nav'),
		$firstSection = jQuery('.l-section').first(),
		$firstSubsectionH = $firstSection.find('.l-subsection-h').first(),
		$supersized = jQuery('.us_supersized'),
		$main = jQuery('.l-main'),
		$footer = jQuery('.l-footer'),
		$topLink = jQuery('.w-toplink'),
		resizeTimer = null,
		videoInit = false,
		scrollInit = false;

	if (jQuery.magnificPopup){
		jQuery('a[ref=magnificPopup][class!=direct-link]').magnificPopup({
			type: 'image',
			fixedContentPos: false
		});
	}

	if (jQuery().waypoint){

		jQuery('.animate_fade, .animate_afc, .animate_afl, .animate_afr, .animate_aft, .animate_afb, .animate_wfc, ' +
		'.animate_hfc, .animate_rfc, .animate_rfl, .animate_rfr').waypoint(function(){
			if ( ! jQuery(this).hasClass('animate_start')){
				var elm = jQuery(this);
				setTimeout(function() {
					elm.addClass('animate_start');
				}, 20);
			}
		}, {offset:'85%', triggerOnce: true});

		jQuery('.w-counter').each(function(){
			var $this = jQuery(this),
				counter = $this.find('.w-counter-number'),
				count = parseInt($this.data('count') || 10),
				prefix = $this.data('prefix') || '',
				suffix = $this.data('suffix') || '',
				number = parseInt($this.data('number') || 0);

			counter.html(prefix+number+suffix);

			$this.waypoint(function(){
				var	step = Math.ceil((count-number)/25),
					stepCount = Math.floor((count-number) / step),
					handler = setInterval(function(){
						number += step;
						stepCount--;
						counter.html(prefix+number+suffix);
						if (stepCount <= 0) {
							counter.html(prefix+count+suffix);
							window.clearInterval(handler);
						}
					}, 40);
			}, {offset:'85%', triggerOnce: true});
		});
	}

	var headerDisableStickyHeaderWidth = parseInt(window.headerDisableStickyHeaderWidth || 0),
		mobileNavWidth = parseInt(window.mobileNavWidth || 1000),
		defaultLogoHeight = parseInt(window.defaultLogoHeight || 50),
		mobileLogoHeight = parseInt(window.mobileLogoHeight || 30),

		// Body modificators
		headerType = $body.mod('headertype'),
		homeType = $body.mod('hometype'),
		headerPos = $body.mod('headerpos'),

		// Window dimensions
		winHeight = parseInt($window.height()),
		winWidth = parseInt($window.width()),
		headerHeight = $header.outerHeight(),
		footerHeight = $footer.outerHeight(),
		headerTop = 0,
		scrollOffsetTolerance = 0;

	// Removing fullscreen subsections from the home section (in case someone adds them)
	$firstSection.find('.l-subsection.full_screen').removeClass('full_screen').removeClass('valign_center');

	var fullscreenSubsections = jQuery('.l-subsection.full_screen');

	var handleScroll = function(){
		var scrollTop = parseInt($window.scrollTop(), 10);

		$topLink.toggleClass('active', (scrollTop >= winHeight));

		if (headerType == 'sticky' && (headerPos == 'bottom' || headerPos == 'outside')){
			$header.toggleClass('fixed', (scrollTop > headerTop));
			$header.css('top', (scrollTop > headerTop)?0:headerTop);
		}

		// Move trough each menu and check its position with scroll position then add current class
		// TODO Rewrite this block
		jQuery('.w-nav-item a[href^="#"]').each(function(){
			var thisHref = jQuery(this).attr('href');

			if (jQuery(thisHref).length){
				var thisTruePosition = parseInt(jQuery(thisHref).offset().top, 10),
					thisPosition = thisTruePosition - headerHeight;

				if (scrollTop >= thisPosition){
					jQuery('.w-nav-item a[href^="#"]').parent().removeClass('active');
					jQuery('.w-nav-item a[href="'+ thisHref +'"]').parent().addClass('active');

					jQuery('.w-cart').each(function(){
						if (jQuery(this).hasClass('status_empty')){
							jQuery(this).css('display', '');
						}
					});
					jQuery(thisHref).find('.w-cart').css('display', 'block');
				}
			}
		});
		//If we're at the bottom of the page, move pointer to the lal-section-hst section
		var bottomPage	= parseInt(jQuery(document).height(), 10) - parseInt(jQuery(window).height(), 10);

		if(scrollTop === bottomPage || scrollTop >= bottomPage){
			var thisHref = jQuery('.w-nav-item a[href^="#"]:last').attr('href');
			if (jQuery(thisHref).length) {
				jQuery('.w-nav-item a[href^="#"]').parent().parent().removeClass('active');
				jQuery('.w-nav-item a[href^="#"]:last').parent().parent().addClass('active');
			}
		}
		// TODO / Rewrite this block
	};

	var renderMenu = function(){},
		setFixedMobileMaxHeight = function(){};
	if ($headerNav.length > 0){
		var touchMenuInited = $headerNav.hasClass('touch_enabled'),
			touchMenuOpened = false,
			navControl = $headerNav.find('.w-nav-control'),
			navItems = $headerNav.find('.w-nav-item'),
			navList = $headerNav.find('.w-nav-list.level_1'),
			navSubItems = navList.find('.w-nav-item.has_sublevel'),
			navSubAnchors = navList.find('.w-nav-item.has_sublevel > .w-nav-anchor'),
			navSubLists = navList.find('.w-nav-item.has_sublevel > .w-nav-list'),
			navAnchors = $headerNav.find('.w-nav-anchor'),
			togglable = window.headerMenuTogglable || false;
		// Count proper dimensions
		setFixedMobileMaxHeight = function(){
			if (winWidth > headerDisableStickyHeaderWidth){
				var headerOuterHeight = $header.outerHeight(),
					navListOuterHeight = Math.min(navList.outerHeight(), headerOuterHeight),
					menuOffset = headerOuterHeight - navListOuterHeight;
				navList.css('max-height', winHeight-menuOffset+'px');
			}
			else{
				navList.css('max-height', 'auto');
			}
		};
		if ( ! touchMenuInited){
			$headerNav.addClass('touch_disabled');
			navList.css('display', 'block');
		}
		// Mobile menu toggler
		navControl.on('click', function(){
			touchMenuOpened = ! touchMenuOpened;
			if (touchMenuOpened){
				// Closing opened sublists
				navItems.filter('.opened').removeClass('opened');
				navSubLists.css('height', 0);

				navList.slideDownCSS();
			}
			else{
				navList.slideUpCSS();
			}
			if (headerType == 'sticky') setFixedMobileMaxHeight();
		});
		// Mobile submenu togglers
		var toggleEvent = function(e){
			if ( ! touchMenuInited) return;
			e.stopPropagation();
			e.preventDefault();
			var $item = jQuery(this).closest('.w-nav-item'),
				$sublist = $item.children('.w-nav-list');
			if ($item.hasClass('opened')){
				$item.removeClass('opened');
				$sublist.slideUpCSS();
			}
			else {
				$item.addClass('opened');
				$sublist.slideDownCSS();
			}
		};
		// Toggle on item clicks
		if (togglable){
			navSubAnchors.on('click', toggleEvent);
		}
		// Toggle on arrows
		else {
			navList.find('.w-nav-item.has_sublevel > .w-nav-anchor > .w-nav-arrow').on('click', toggleEvent);
		}
		// Mark all the togglable items
		navSubItems.each(function(){
			var $this = jQuery(this),
				$parentItem = $this.parent().closest('.w-nav-item');
			if ($parentItem.length == 0 || $parentItem.mod('columns') === false) $this.addClass('togglable');
		});
		// Touch device handling in default (notouch) layout
		if ( ! $html.hasClass('no-touch')){
			navList.find('.w-nav-item.has_sublevel.togglable > .w-nav-anchor').on('click', function(e){
				if (touchMenuInited) return;
				e.preventDefault();
				var $this = jQuery(this),
					$item = $this.parent(),
					$list = $item.children('.w-nav-list');

				// Second tap: going to the URL
				if ($item.hasClass('opened')) return location.assign($this.attr('href'));

				$list.fadeInCSS();
				$item.addClass('opened');
				var outsideClickEvent = function(e){
					if (jQuery.contains($item[0], e.target)) return;
					$item.removeClass('opened');
					$list.fadeOutCSS();
					$body.off('touchstart', outsideClickEvent);
				};

				$body.on('touchstart', outsideClickEvent);
			});
		}
		// Desktop device hovers
		else {
			navSubItems
				.filter('.togglable')
				.on('mouseenter', function(){
					if (touchMenuInited) return;
					var $list = jQuery(this).children('.w-nav-list');
					$list.fadeInCSS();
				})
				.on('mouseleave', function(){
					if (touchMenuInited) return;
					var $list = jQuery(this).children('.w-nav-list');
					$list.fadeOutCSS();
				});
		}
		// Close menu on anchor clicks
		navAnchors.on('click', function(){
			if (winWidth > mobileNavWidth) return;
			// Toggled the item
			if (togglable && jQuery(this).closest('.w-nav-item').hasClass('has_sublevel')) return;
			navList.slideUpCSS();
			touchMenuOpened = false;
		});
		renderMenu = function(){
			// Mobile layout
			if (winWidth <= mobileNavWidth){

				// Switching from desktop to mobile layout
				if ( ! touchMenuInited){
					touchMenuInited = true;
					touchMenuOpened = false;
					navList.css('height', 0);

					// Closing opened sublists
					navItems.filter('.opened').removeClass('opened');
					navSubLists.css('height', 0);

					$headerNav.removeClass('touch_disabled').addClass('touch_enabled');
				}

				// Max-height limitation for fixed header layouts
				if (headerType == 'sticky') setFixedMobileMaxHeight();
			}

			// Switching from mobile to desktop layout
			else if (touchMenuInited){
				$headerNav.removeClass('touch_enabled').addClass('touch_disabled');

				// Clearing height-hiders
				navList.css({height: '', 'max-height': '', display: 'block', opacity: 1});

				// Closing opened sublists
				navItems.filter('.opened').removeClass('opened');
				navSubLists.css('height', '');
				navItems.filter('.togglable').children('.w-nav-list').css('display', 'none');

				touchMenuInited = false;
				touchMenuOpened = false;
			}

		};
	}

	var updateVideosSizes = function(){
		jQuery('.video-background').each(function(){
			var container = jQuery(this);
			if (winWidth <= 1024) return jQuery(this).hide();
			var mejsContainer = container.find('.mejs-container'),
				poster = container.find('.mejs-mediaelement img'),
				video = container.find('video'),
				videoWidth = video.attr('width'),
				videoHeight = video.attr('height'),
				videoProportion = videoWidth / videoHeight,
				parent = container.parent(),
				parentWidth = parent.outerWidth(),
				parentHeight = parent.outerHeight(),
				proportion,
				centerX, centerY;
			if (mejsContainer.length == 0) return;
			// Proper sizing
			if (video.length > 0 && video[0].player && video[0].player.media) videoWidth = video[0].player.media.videoWidth;
			if (video.length > 0 && video[0].player && video[0].player.media) videoHeight = video[0].player.media.videoHeight;

			container.show();

			parent.find('span.mejs-offscreen').hide();

			proportion = (parentWidth/parentHeight > videoWidth/videoHeight)?parentWidth/videoWidth:parentHeight/videoHeight;

			container.width(proportion*videoWidth);
			container.height(proportion*videoHeight);

			poster.width(proportion*videoWidth);
			poster.height(proportion*videoHeight);

			centerX = (parentWidth < videoWidth*proportion)?(parentWidth - videoWidth*proportion)/2:0;
			centerY = (parentHeight < videoHeight*proportion)?(parentHeight - videoHeight*proportion)/2:0;

			container.css({ 'left': centerX, 'top': centerY });

			mejsContainer.css({width: '100%', height: '100%'});
			video.css({'object-fit': 'cover'});
		});
	};

	var handleResize = function(){
		winHeight = parseInt($window.height());
		winWidth = parseInt($window.width());
		headerTop = 0;
		scrollOffsetTolerance = 0;

		// Resetting fullscreen sliders
		$supersized.css('height', '0px');
		$firstSection.css('min-height', '');
		$firstSubsectionH.css('margin-top', '');

		var firstSectionHeight = parseInt($firstSection.height());

		headerHeight = $header.outerHeight();
		footerHeight = $footer.outerHeight();

		$main.css('margin-bottom', footerHeight+'px');

		if (headerPos == 'top'){
			if (homeType == 'fullscreen'){
				firstSectionHeight = Math.max((winHeight-headerHeight), firstSectionHeight);
				$firstSection.css('min-height', firstSectionHeight+'px');
			}
			if (headerType == 'sticky'){
				$firstSection.css('margin-top', headerHeight+'px');
			}
		}
		else if (headerPos == 'bottom'){
			if (homeType == 'fullscreen'){
				firstSectionHeight = Math.max((winHeight-headerHeight), firstSectionHeight);
				$firstSection.css({
					'min-height': firstSectionHeight+'px',
					'margin-bottom': headerHeight+'px'
				});
				headerTop = firstSectionHeight;
			}
			else{
				$firstSection.css('margin-bottom', headerHeight+'px');
				headerTop = firstSectionHeight;
			}
		}
		else if (headerPos == 'outside'){
			if (homeType == 'fullscreen'){
				firstSectionHeight = Math.max(winHeight, firstSectionHeight);
				$firstSection.css({
					'min-height': firstSectionHeight+'px',
					'margin-bottom': headerHeight+'px'
				});
				headerTop = firstSectionHeight;
			}
			else {
				$firstSection.css('margin-bottom', headerHeight+'px');
				headerTop = firstSectionHeight;
			}
		}

		// Updating fullscreen secondary page sections
		fullscreenSubsections.each(function(){
			var subsection = jQuery(this),
				spaceHeight = ((headerType == 'sticky') ? (winHeight-headerHeight) : winHeight) + 1;
			subsection.css('min-height', spaceHeight);
			if (subsection.hasClass('valign_center')){
				var subsectionH = subsection.find('.l-subsection-h'),
					contentHeight;
				subsectionH.css('margin-top', '');
				contentHeight = subsectionH.outerHeight();
				var heightDifference = parseInt(subsection.height()) - contentHeight;
				subsectionH.css('margin-top', (heightDifference > 0) ? (heightDifference/2) : '');
			}
		});

		// Updating fullscreen sliders
		$supersized.each(function(){
			var $this = jQuery(this),
				$parentSection = $this.closest('.l-section');
			if ($parentSection.length) $this.css('height', $parentSection.height());
		});

		if (homeType == 'fullscreen'){
			var subsectionsHeight = 0,
				firstSubsectionMargin;
			$firstSection.find('.l-subsection-h').each(function(){
				subsectionsHeight += jQuery(this).height();
			});
			firstSubsectionMargin = Math.max((firstSectionHeight - subsectionsHeight)/2, 0);
			$firstSubsectionH.css('margin-top', firstSubsectionMargin);
		}
		
		if (typeof(api) != 'undefined') {
			api.resizeNow();
		}

		$header.css('top', headerTop+'px');

		if (headerType == 'sticky'){
			scrollOffsetTolerance = headerHeight-1;
		}

		$logoImg.css('height', ((winWidth < 1024)?mobileLogoHeight:defaultLogoHeight)+'px');

		// Resizing video properly
		if (window.MediaElementPlayer){
			updateVideosSizes();
		}

		handleScroll();
		renderMenu();
	};

	if (window.MediaElementPlayer){
		jQuery('.video-background video').mediaelementplayer({
			enableKeyboard: false,
			iPadUseNativeControls: false,
			pauseOtherPlayers: false,
			iPhoneUseNativeControls: false,
			AndroidUseNativeControls: false,
			videoWidth: '100%',
			videoHeight: '100%',
			success: function(mediaElement, domObject){
				updateVideosSizes();
				jQuery(domObject).css('display', 'block');
			}
		});
	}

	$body.on('click', 'a.w-toplink[href*="#"], a.w-logo-link[href*="#"], a.w-nav-anchor[href*="#"], a.g-btn[href*="#"], ' +
		'a.smooth-scroll[href*="#"], a.w-icon-link[href*="#"], a.w-iconbox-link[href*="#"], a.bbp-reply-permalink[href*="#"], ' +
		'.menu-item > a[href*="#"], a.w-blogpost-meta-comments-h[href*="#"], .w-comments-title a[href*="#"]',
		function(event){

			var href = this.href,
				hash = this.hash;

			// Handling to other URLs or pages
			if ( ! (
					href.charAt(0) == '#' ||
					(href.charAt(0) == '/' && href.test('^'+location.pathname+'#')) ||
					href.indexOf(location.host+location.pathname+'#') > -1
				)) return;

			event.preventDefault();
			event.stopPropagation();

			var scrollTop = 0;
			if (this && hash != '#'){
				var $target = jQuery(this.hash);
				if ($target.length){
					scrollTop = $target.offset().top - scrollOffsetTolerance;
				}
			}

			jQuery("html, body").animate({
				scrollTop: scrollTop+"px"
			}, {
				duration: 1200,
				easing: "easeInOutQuint"
			});
		});

	if (scrollInit == false && document.location.hash && jQuery(document.location.hash).length){
		jQuery(window).load(function(){
			scrollInit = true;

			jQuery("html, body").animate({
				scrollTop: jQuery(document.location.hash).offset().top-scrollOffsetTolerance+"px"
			}, {
				duration: 1200,
				easing: "easeInOutQuint"
			});
		});
	}

	handleResize();

	$window.scroll(handleScroll);

	// Recounting objects' positions when the resize is finished
	var resizeTimer = null;
	$window.resize(function(){
		clearTimeout(resizeTimer);
		resizeTimer = setTimeout(handleResize, 100);
	});

	$window.load(handleResize);

	jQuery('.contact_form').each(function(){

		jQuery(this).submit(function(){
			var form = jQuery(this),
				name, email, phone, message,
				nameField = form.find('input[name=name]'),
				emailField = form.find('input[name=email]'),
				phoneField = form.find('input[name=phone]'),
				messageField = form.find('textarea[name=message]'),
				button = form.find('.g-btn'),
				errors = 0;

			button.addClass('loading');
			jQuery('.w-form-field-success').html('');

			if (nameField.length) {
				name = nameField.val();

				if (name === '' && nameField.data('required') === 1){
					jQuery('#name_row').addClass('check_wrong');
					jQuery('#name_state').html(window.nameFieldError);

					errors++;
				} else {
					jQuery('#name_row').removeClass('check_wrong');
					jQuery('#name_state').html('');
				}
			}

			if (emailField.length) {
				email = emailField.val();

				if (email === '' && emailField.data('required') === 1){
					jQuery('#email_row').addClass('check_wrong');
					jQuery('#email_state').html(window.emailFieldError);
					errors++;
				} else {
					jQuery('#email_row').removeClass('check_wrong');
					jQuery('#email_state').html('');
				}
			}

			if (phoneField.length) {
				phone = phoneField.val();

				if (phone === '' && phoneField.data('required') === 1){
					jQuery('#phone_row').addClass('check_wrong');
					jQuery('#phone_state').html(window.phoneFieldError);
					errors++;
				} else {
					jQuery('#phone_row').removeClass('check_wrong');
					jQuery('#phone_state').html('');
				}
			}

			if (messageField.length) {
				message = messageField.val();

				if (message === '' && messageField.data('required') === 1){
					jQuery('#message_row').addClass('check_wrong');
					jQuery('#message_state').html(window.messageFieldError);
					errors++;
				} else {
					jQuery('#message_row').removeClass('check_wrong');
					jQuery('#message_state').html('');
				}
			}

			if (errors === 0){
				jQuery.ajax({
					type: 'POST',
					url: window.ajaxURL,
					dataType: 'json',
					data: {
						action: 'sendContact',
						name: name,
						email: email,
						phone: phone,
						message: message
					},
					success: function(data){
						if (data.success){
							jQuery('.w-form-field-success').html(window.messageFormSuccess);

							if (nameField.length) {
								nameField.val('');
							}
							if (emailField.length) {
								emailField.val('');
							}
							if (phoneField.length) {
								phoneField.val('');
							}
							if (messageField.length) {
								messageField.val('');
							}
						}

						button.removeClass('loading');
					},
					error: function(){
					}
				});
			} else {
				button.removeClass('loading');
			}

			return false;
		});

	});

	function preloaderProgress() {
		preloaderLoadedImageCount++;
		var percent = Math.round(preloaderLoadedImageCount/preloaderTotalImageCount*100);
		jQuery('.l-preloader-counter').text(percent+"%");
		jQuery('.l-preloader-bar').stop().animate({
			"height": percent+"%"
		}, 200);
	}

	function preloaderFinished() {
		jQuery('.l-preloader-counter').text("100%");
		jQuery('.l-preloader-bar').stop().animate({
			"height": "100%"
		}, 200);
		window.setTimeout(function(){
			jQuery('.l-preloader' ).animate({height: 0}, 300, function () {
				jQuery('.l-preloader').remove();
				handleResize();
			});
		}, 200);
	}

	if (jQuery('.l-preloader').length){
		var images = jQuery('.l-section').first().find('.l-subsection-hh > img'),
			preloaderLoadedImageCount = 0,
			preloaderTotalImageCount = 0,
			preloaderContainer = jQuery('.l-preloader');

		jQuery('.l-subsection').each(function(){
			var el = jQuery(this)
				, image = el.css('background-image').match(/url\((['"])?(.*?)\1\)/);
			if(image)
				images = images.add(jQuery('<img>').attr('src', image.pop()));
		});

		if (preloaderContainer.hasClass('with_spinner')){
			images.imagesLoaded().always(function(){
				window.setTimeout(function(){
					jQuery('.l-preloader').animate({height: 0}, 300, function () {
						jQuery('.l-preloader').remove();
						handleResize();
					});
				}, 200);
			});
		} else {
			preloaderTotalImageCount = images.length;
			jQuery("<div class='l-preloader-bar'></div>").appendTo(preloaderContainer);
			jQuery("<div class='l-preloader-counter'></div>").text("0%").appendTo(preloaderContainer);

			images.imagesLoaded().progress(preloaderProgress).always(preloaderFinished);
		}
	}


	jQuery('.no-touch .l-subsection.with_parallax').each(function(){
		jQuery(this).parallax('50%', '0.3');
	});

	jQuery(".w-clients-list").each(function() {
		var clients = jQuery(this),
			autoPlay = clients.attr('data-autoPlay'),
			autoPlaySpeed = clients.attr('data-autoPlaySpeed'),
			columns = clients.attr('data-columns'),
			columns1300 = (columns < 4)?columns:4,
			columns1024 = (columns < 3)?columns:3,
			columns768 = (columns < 2)?columns:2,
			infinite = false;
		if (autoPlay == 1) {
			autoPlay = infinite = true;
		} else {
			autoPlay = infinite = false;
		}
		clients.slick({
			infinite: infinite,
			autoplay: autoPlay,
			lazyLoad: 'progressive',
			autoplaySpeed: autoPlaySpeed,
			accessibility: false,
			slidesToShow: columns,
			responsive: [{
				breakpoint: 1300,
				settings: {
					slidesToShow: columns1300
				}
			},{
				breakpoint: 1024,
				settings: {
					slidesToShow: columns1024
				}
			},{
				breakpoint: 768,
				settings: {
					slidesToShow: columns768
				}
			},{
				breakpoint: 480,
				settings: {
					slidesToShow: 1
				}
			}]
		});
	});

	if (jQuery().fotorama){
		jQuery('.fotorama').fotorama({
			spinner: {
				lines: 13,
				color: 'rgba(0, 0, 0, .75)'
			}
		});
	}
});

// Disable FotoRama statistics usage
window.blockFotoramaData = true;

/**
 * CSS-analog of jQuery slideDown/slideUp/fadeIn/fadeOut functions (for better rendering)
 */
!function(){
	/**
	 *
	 * @param {Object} css key-value pairs of animated css
	 * @param {Number} duration
	 * @param {Function} onFinish
	 */
	jQuery.fn.performCSSTransition = function(css, duration, onFinish){
		duration = duration || 250;
		var $this = this,
			transition = [];
		for (var attr in css){
			if ( ! css.hasOwnProperty(attr)) continue;
			transition.push(attr+' '+(duration/1000)+'s ease-in-out');
		}
		transition = transition.join(', ');
		$this.css({
			transition: transition,
			'-webkit-transition': transition
		});

		// Stopping previous events, if there were any
		var prevTimers = (this.data('animation-timers') || '').split(',');
		if (prevTimers.length == 2){
			clearTimeout(prevTimers[0]);
			clearTimeout(prevTimers[1]);
		}

		// Starting the transition with a slight delay for the proper application of CSS transition properties
		var timer1 = setTimeout(function(){
			$this.css(css);
		}, 25);

		var timer2 = setTimeout(function(){
			if (typeof onFinish == 'function') onFinish();
			$this.css({
				transition: '',
				'-webkit-transition': ''
			});
		}, duration + 25);

		this.data('animation-timers', timer1+','+timer2);
	};
	// Height animations
	jQuery.fn.slideDownCSS = function(){
		if (this.length == 0) return;
		// Grabbing the "auto" height in px
		this.css({
			visibility: 'hidden',
			position: 'absolute',
			height: 'auto',
			display: 'block'
		});
		var height = this.outerHeight();
		this.css({
			overflow: 'hidden',
			height: '0px',
			visibility: '',
			position: '',
			opacity: 0
		});
		var $this = this;
		this.performCSSTransition({
			height: height,
			opacity: 1
		}, arguments[0] || 250, function(){
			$this.css({
				overflow: '',
				height: 'auto'
			});
		});
	};
	jQuery.fn.slideUpCSS = function(){
		if (this.length == 0) return;
		this.css({
			height: this.outerHeight(),
			overflow: 'hidden',
			opacity: 1
		});
		var $this = this;
		this.performCSSTransition({
			height: 0,
			opacity: 0
		}, arguments[0] || 250, function(){
			$this.css({overflow: '', display: 'none'});
		});
	};
	// Opacity animations
	jQuery.fn.fadeInCSS = function(){
		if (this.length == 0) return;
		this.css({
			opacity: 0,
			display: 'block'
		});
		this.performCSSTransition({
			opacity: 1
		}, arguments[0] || 250);
	};
	jQuery.fn.fadeOutCSS = function(){
		if (this.length == 0) return;
		var $this = this;
		this.performCSSTransition({
			opacity: 0
		}, arguments[0] || 250, function(){
			$this.css('display', 'none');
		});
	};
	// Material design animations
	jQuery.fn.showMD = function(){
		if (this.length == 0) return;
		// Grabbing the "auto" height in px
		this.css({
			visibility: 'hidden',
			position: 'absolute',
			height: 'auto',
			display: 'block'
		});
		var height = this.outerHeight();
		this.css({
			transform: 'translateY(-600px)',
			'-webkit-transform': 'translateX(-600px)',
			overflow: 'hidden',
			height: '0px',
			visibility: '',
			position: '',
			opacity: 0
		});
		var $this = this;
		this.performCSSTransition({
			height: height,
			transform: 'translateY(0px)',
			'-webkit-transform': 'translateY(0px)',
			opacity: 1
		}, arguments[0] || 250, function(){
			$this.css({
				overflow: '',
				height: 'auto',
				transform: '',
				'-webkit-transform': ''
			});
		});
	};
	jQuery.fn.hideMD = function(){
		if (this.length == 0) return;
		var $this = this;
		this.css({
			transform: '',
			'-webkit-transform': ''
		});
		this.performCSSTransition({
			opacity: 0
		}, arguments[0] || 250, function(){
			$this.css('display', 'none');
		});
	};
}();

/**
 * Retrieve/set/erase modificator class <mod>_<value>
 * @param {String} mod Modificator namespace
 * @param {String} [value] Value
 * @returns {string|jQuery}
 */
jQuery.fn.mod = function(mod, value){
	if (this.length == 0) return this;
	// Remove class modificator
	if (value === false){
		this.get(0).className = this.get(0).className.replace(new RegExp('(^| )'+mod+'\_[a-z0-9]+( |$)'), '$2');
		return this;
	}
	var pcre = new RegExp('^.*?'+mod+'\_([a-z0-9]+).*?$'),
		arr;
	// Retrieve modificator
	if (value === undefined){
		return (arr = pcre.exec(this.get(0).className)) ? arr[1] : false;
	}
	// Set modificator
	else {
		this.mod(mod, false).get(0).className += ' '+mod+'_'+value;
		return this;
	}
};

// Fixing hovers for devices with both mouse and touch screen
jQuery.isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
jQuery('html').toggleClass('no-touch',  ! jQuery.isMobile);