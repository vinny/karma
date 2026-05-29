(function($) {
	'use strict';

	// Move karma profile field below posts count on page load
	$(function() {
		$('.profile-karma').each(function() {
			var $profile = $(this).closest('.postprofile');
			var $posts = $profile.find('.profile-posts');
			if ($posts.length) {
				$(this).insertAfter($posts);
			}
		});
	});

	// Block clicks on disabled vote buttons
	$(document).on('click', '.karma-vote-btn.karma-btn-blocked', function(e) {
		e.preventDefault();
		e.stopPropagation();
		return false;
	});

	// Register phpBB AJAX callback for voting
	phpbb.addAjaxCallback('vinny_karma_vote', function(res) {
		if (res.status === 'success') {
			var $panel = $(this).closest('.karma-panel');
			
			// Update karma score text
			$panel.find('.karma-score').text(res.post_karma);

			// Reset voted classes and apply the new state
			$panel.removeClass('voted-up voted-down');

			var $upvoteBtn = $panel.find('.karma-upvote');
			var $downvoteBtn = $panel.find('.karma-downvote');
			var upvoteUrl = $panel.attr('data-upvote-url');
			var downvoteUrl = $panel.attr('data-downvote-url');
			var langUp = $panel.attr('data-lang-upvote');
			var langDown = $panel.attr('data-lang-downvote');
			var langAlreadyUp = $panel.attr('data-lang-already-up');
			var langAlreadyDown = $panel.attr('data-lang-already-down');

			if (res.vote_direction === 1) {
				$panel.addClass('voted-up');
				// Disable upvote
				$upvoteBtn.addClass('karma-btn-blocked')
					.attr('href', 'javascript:void(0);')
					.attr('title', langAlreadyUp)
					.removeAttr('data-ajax')
					.off('click');
				// Enable downvote
				$downvoteBtn.removeClass('karma-btn-blocked')
					.attr('href', downvoteUrl)
					.attr('title', langDown)
					.attr('data-ajax', 'vinny_karma_vote')
					.off('click');
				phpbb.ajaxify({
					selector: $downvoteBtn,
					callback: 'vinny_karma_vote'
				});
			} else if (res.vote_direction === -1) {
				$panel.addClass('voted-down');
				// Enable upvote
				$upvoteBtn.removeClass('karma-btn-blocked')
					.attr('href', upvoteUrl)
					.attr('title', langUp)
					.attr('data-ajax', 'vinny_karma_vote')
					.off('click');
				phpbb.ajaxify({
					selector: $upvoteBtn,
					callback: 'vinny_karma_vote'
				});
				// Disable downvote
				$downvoteBtn.addClass('karma-btn-blocked')
					.attr('href', 'javascript:void(0);')
					.attr('title', langAlreadyDown)
					.removeAttr('data-ajax')
					.off('click');
			} else {
				// Reset both buttons to active (retracted state)
				$upvoteBtn.removeClass('karma-btn-blocked')
					.attr('href', upvoteUrl)
					.attr('title', langUp)
					.attr('data-ajax', 'vinny_karma_vote')
					.off('click');
				phpbb.ajaxify({
					selector: $upvoteBtn,
					callback: 'vinny_karma_vote'
				});

				$downvoteBtn.removeClass('karma-btn-blocked')
					.attr('href', downvoteUrl)
					.attr('title', langDown)
					.attr('data-ajax', 'vinny_karma_vote')
					.off('click');
				phpbb.ajaxify({
					selector: $downvoteBtn,
					callback: 'vinny_karma_vote'
				});
			}
		} else {
			// Get fallback language strings from data attributes
			var $panel = $(this).closest('.karma-panel');
			var errorLang = $(this).attr('data-lang-error');
			var errorMsg = res.message || errorLang || $panel.attr('data-lang-error-message') || '';
			var errorTitle = res.title || $panel.attr('data-lang-error-title') || '';
			phpbb.alert(errorTitle, errorMsg);
		}
	});
})(jQuery);
