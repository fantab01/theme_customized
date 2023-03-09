jQuery(document).ready(function($) {
	
	
	// Toggle blog-menu
	$(".nav-toggle").on("click", function(){	
		$(this).toggleClass("active");
		$(".mobile-menu").slideToggle();
		$( 'body' ).toggleClass("enable-click");
		return false;
	});

	$(document).ready(function () {
		$(document).click(function (event) {
			var clickover = $(event.target);
			var _opened = $(".nav-toggle").hasClass("nav-toggle hidden active");
			if (_opened === true && !clickover.hasClass("navbar-toggle")) {
				$(".nav-toggle").click();
			}
		});
	});
	
	var mainHeader = $('.sidebar'),
		headerHeight = mainHeader.height();
	
	var scrolling = false,
		previousTop = 0,
		currentTop = 0,
		scrollDelta = 2,
		scrollOffset = 150;
	
	$(window).on('scroll', function(){
		if( !scrolling ) {
			scrolling = true;
			(!window.requestAnimationFrame)
				? setTimeout(autoHideHeader, 250)
				: requestAnimationFrame(autoHideHeader);
		}
	});
	
	$(window).on('resize', function(){
		headerHeight = mainHeader.height();
	});
	
	function autoHideHeader() {
		var currentTop = $(window).scrollTop();
		if (previousTop - currentTop > scrollDelta) {
			//if scrolling up...
			mainHeader.removeClass('is-hidden');
		} else if( currentTop - previousTop > scrollDelta && currentTop > scrollOffset) {
			//if scrolling down...
			mainHeader.addClass('is-hidden');
		}
		previousTop = currentTop;
		scrolling = false;
	}

	$(function() {
		$('a[href*=#]:not([href=#])').click(function() {
			if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				if (target.length) {
					$('html,body').animate({
						scrollTop: target.offset().top
					}, 500);
					return false;
				}
			}
		});
	});
	
	
	// Hide mobile-menu > 960
	$(window).resize(function() {
		if ($(window).width() > 960) {
			$(".nav-toggle").removeClass("active");
			$(".mobile-menu").hide();
		}
	});
	
	// Toggle post-meta
	$(".post-meta-toggle").on("click", function(){	
		$(this).toggleClass("active");
		$('.post-meta').toggleClass("active");
		$(".post-meta-inner").slideToggle();
		return false;
	});

    // Post meta tabs
    $('.tab-selector a').click(function() {
    	$('.tab-selector a').removeClass('active');
		$('.post-meta-tabs .tab').hide(); 
		return false;
    });
    
    $('.tab-selector .tab-comments').click(function() {
    	$(this).addClass('active');
		$('.post-meta-tabs .tab-comments').show(); 
    });
    
    $('.tab-selector .tab-post-meta').click(function() {
    	$(this).addClass('active');
		$('.post-meta-tabs .tab-post-meta').show(); 
    });
    
    $('.tab-selector .tab-author-meta').click(function() {
    	$(this).addClass('active');
		$('.post-meta-tabs .tab-author-meta').show(); 
    });
	
	
	// Resize videos after container
	var vidSelector = "iframe, object, video";	
	var resizeVideo = function(sSel) {
		$( sSel ).each(function() {
			var $video = $(this),
				$container = $video.parent(),
				iTargetWidth = $container.width();

			if ( !$video.attr("data-origwidth") ) {
				$video.attr("data-origwidth", $video.attr("width"));
				$video.attr("data-origheight", $video.attr("height"));
			}

			var ratio = iTargetWidth / $video.attr("data-origwidth");

			$video.css("width", iTargetWidth + "px");
			$video.css("height", ( $video.attr("data-origheight") * ratio ) + "px");
		});
	};

	resizeVideo(vidSelector);

	$(window).resize(function() {
		resizeVideo(vidSelector);
	});
	
	
});