// Karma System extension for the phpBB Forum Software package.

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

			// Show/hide reset button based on the updated score
			var $resetBtn = $panel.find('.karma-reset-btn');
			if ($resetBtn.length) {
				if (res.post_karma === 0) {
					$resetBtn.hide();
				} else {
					$resetBtn.show();
				}
			}

			// Update author's karma score in all profiles on the page
			if (res.poster_id) {
				var $config = $('#karma-config');
				var langKarma = $config.attr('data-lang-karma') || '';

				$('.postprofile').each(function() {
					var $profile = $(this);
					var found = false;
					$profile.find('a').each(function() {
						var href = $(this).attr('href') || '';
						var match = href.match(/[?&]u=(\d+)/);
						if (match && parseInt(match[1], 10) === res.poster_id) {
							found = true;
							return false; // break loop
						}
					});

					if (found) {
						var $karmaLine = $profile.find('.profile-karma');
						if (res.user_karma === 0) {
							$karmaLine.remove();
						} else {
							if ($karmaLine.length) {
								$karmaLine.find('.profile-karma-value').text(res.user_karma);
							} else {
								var $newKarmaLine = $('<dd class="profile-custom-field profile-karma"><strong>' + langKarma + ' </strong><span class="profile-karma-value">' + res.user_karma + '</span></dd>');
								var $posts = $profile.find('.profile-posts');
								if ($posts.length) {
									$newKarmaLine.insertAfter($posts);
								} else {
									$profile.append($newKarmaLine);
								}
							}
						}
					}
				});
			}

			// Reset voted classes and apply the new state
			$panel.removeClass('voted-up voted-down');

			var $config = $('#karma-config');
			var $upvoteBtn = $panel.find('.karma-upvote');
			var $downvoteBtn = $panel.find('.karma-downvote');
			var upvoteUrl = $upvoteBtn.attr('data-vote-url');
			var downvoteUrl = $downvoteBtn.attr('data-vote-url');
			var langUp = $config.attr('data-lang-upvote');
			var langDown = $config.attr('data-lang-downvote');
			var langAlreadyUp = $config.attr('data-lang-already-up');
			var langAlreadyDown = $config.attr('data-lang-already-down');

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
			var $config = $('#karma-config');
			var errorLang = $(this).attr('data-lang-error');
			var errorMsg = res.message || errorLang || $config.attr('data-lang-error-message') || '';
			var errorTitle = res.title || $config.attr('data-lang-error-title') || '';
			phpbb.alert(errorTitle, errorMsg);
		}
	});
})(jQuery);
