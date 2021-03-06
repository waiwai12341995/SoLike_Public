var UM_Conv_Ajax = false;

//used for delete cookie on login form
jQuery(document.body).on( 'click', '.um-login #um-submit-btn', function () {
	document.cookie = 'um_messaging_invite_login=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
});

function um_Hide_Emobox() {
	var emolist = jQuery('span.um-message-emolist');
	if ( emolist.is(':visible') ) {
		emolist.hide();
	}
}

function um_Chatbox( chatbox ) {
	var Length = chatbox.val().length;
	var maxLen = chatbox.attr('data-maxchar');
	var AmountLeft = maxLen - Length;

	jQuery('.um-message-limit:visible').html(AmountLeft);
	if ( chatbox.val() != '' ) {
		jQuery('.um-message-send:visible').removeClass('disabled');
	} else {
		jQuery('.um-message-send:visible').addClass('disabled');
	}
	if ( Length > maxLen ) {
		jQuery('.um-message-limit:visible').addClass('exceed');
		jQuery('.um-message-send:visible').addClass('disabled');
	} else {
		jQuery('.um-message-limit:visible').removeClass('exceed');
		if ( chatbox.val() != '' ) {
			jQuery('.um-message-send:visible').removeClass('disabled');
		}
	}
}

function Init_BodyConv() {
	jQuery('.um-profile-body.messages .um-message-autoheight').css({
			'max-height': '500px'
	}).mCustomScrollbar({ theme:"dark-3", mouseWheelPixels:500 }).mCustomScrollbar("scrollTo", "bottom",{ scrollInertia:0} );
	
	if ( jQuery('.um-message-conv').length ) {
		jQuery('.um-message-conv').css({
			'max-height': '500px'
		}).mCustomScrollbar({
			theme:"dark-3",
			mouseWheelPixels:500,
			callbacks:{
				onScrollStart: function () {
					jQuery('.um-message-conv-load-more').show();
				},
				onTotalScroll: function(){
					var user_id = jQuery(this).data('user');
					var offset = jQuery(this).find('.um-message-conv-item').length;

					wp.ajax.send( 'um_conversations_load', {
						data: {
							user_id: user_id,
							offset: offset,
							nonce: um_scripts.nonce
						},
						success: function(data){
							if( data !== '' ){
								var load = jQuery('.um-message-conv-load-more').detach();
								jQuery('.um-message-conv .mCSB_container').append(data).append(load);
							} else {
								jQuery('.um-message-conv-load-more').remove();
							}
						},
						error: function(e){
							console.log(e);
						}
					});
				},
				onTotalScrollOffset: 10,
				alwaysTriggerOffsets: false
			}
		});
	}
}

function UM_Refresh_Conv() {
	var ajax_wrapper = jQuery('.um-message-ajax');
	if ( ajax_wrapper.length && ajax_wrapper.is(':visible') && ! UM_Conv_Ajax ) {
		var message_to = ajax_wrapper.data('message_to');
		var conversation_id = ajax_wrapper.data('conversation_id');
		var last_updated = ajax_wrapper.data('last_updated');

		if ( conversation_id === 'new' ) {
			return;
		}

		wp.ajax.send( 'um_messaging_update', {
			data: {
				message_to: message_to,
				conversation_id: conversation_id,
				last_updated: last_updated,
				nonce: um_scripts.nonce
			},
			success: function( data ) {
				if ( data ) {
					UM_Update_UTC_to_LocalTime();

					if ( ajax_wrapper.find('.um-message-item[data-message_id="'+data.message_id+'"]').length == 0 ) {
						if ( data.response !== 'nothing_new' ) {
							jQuery('.um-message-ajax').append( data.response );
							jQuery('.um-popup-autogrow').mCustomScrollbar('update').mCustomScrollbar("scrollTo", "bottom",{ scrollInertia:0});

							ajax_wrapper.data( 'last_updated', data.last_updated );
						}
					}
				}
			},
			error: function(e) {
				console.log(e);
			}
		});
	}
}

function UM_Update_UTC_to_LocalTime() {
	jQuery('.um-message-item-time').each( function() {
		if ( jQuery(this).attr("title") == "" || jQuery(this).attr("original-title") == "" ) {
			var utctime = jQuery(this).attr("um-message-utc-time");
			var timestamp = jQuery(this).attr("um-messsage-timestamp");
			var m = moment(timestamp,"X").tz( moment.tz.guess() );
			var title =  m.format('LLLL');
			jQuery(this).attr("title", title);
			jQuery(this).attr("original-title",  utctime  );
		}
	});
}

/* End of custom functions */

jQuery(document).ready(function() {

	jQuery(document.body).on('mouseenter','.um-message-item', function() {
		jQuery(this).find('.um-message-item-show-on-hover').fadeIn('fast');
	});

	jQuery(document.body).on('mouseleave','.um-message-item', function() {
		jQuery(this).find('.um-message-item-show-on-hover').fadeOut('fast');
	});

	if ( um_messages.can_read ) {
		setInterval( UM_Refresh_Conv, um_messages.interval );
	}

	/* Height of conversation */
	Init_BodyConv();
	UM_Update_UTC_to_LocalTime();

	/* unblocking a user */
	jQuery(document.body).on('click', '.um-message-unblock',function(e) {
		e.preventDefault();
		var user_id = jQuery(this).attr('data-user_id');
		jQuery(this).parents('.um-message-blocked').fadeOut('fast');
		wp.ajax.send( 'um_messaging_unblock_user', {
			data: {
				user_id: user_id,
				nonce: um_scripts.nonce
			},
			success: function( data ) {
				UM_Update_UTC_to_LocalTime();
			},
			error: function(e){
				console.log(e);
			}
		});
		return false;
	});
	
	/* blocking a user */
	jQuery(document.body).on('click', '.um-message-blocku:not(.um-message-blocked-true)',function(e){
		e.preventDefault();
		jQuery(this).addClass('um-message-blocked-true');
		var conversation_id = jQuery(this).attr('data-conversation_id');
		var other_user = jQuery(this).attr('data-other_user');

		jQuery('.tipsy').remove();
		
		jQuery('.um-message-body,.um-message-footer,.um-message-header-left').css({'opacity': 0.5});
		jQuery('.um-message-conv-item[data-conversation_id="'+conversation_id+'"]').remove();
		jQuery('.um-message-footer').empty();
		jQuery('.um-message-item-remove').remove();
		jQuery('a.um-message-delconv').addClass('disabled');
		wp.ajax.send( 'um_messaging_block_user', {
			data: {
				other_user: other_user,
				nonce: um_scripts.nonce
			},
			success: function(data) {

			},
			error: function(e) {
				console.log(e);
			}
		});
		return false;
	});
	
	/* Delete conversation (disabled) */
	jQuery(document.body).on('click', '.um-message-delconv.disabled',function(e){
		e.preventDefault();
		return false;
	});
	
	/* Delete conversation */
	jQuery(document.body).on('click', '.um-message-delconv',function(e){
		e.preventDefault();
		var conversation_id = jQuery(this).attr('data-conversation_id');
		var other_user = jQuery(this).attr('data-other_user');
		
		jQuery('.tipsy').remove();
		
		if ( jQuery('.um-message-conv-view').length ) {
			
			jQuery('.um-message-conv-item[data-conversation_id='+conversation_id+']').remove();
			if ( jQuery('.um-message-conv-item').length && jQuery('.um-message-conv-view').is(':visible') ) {
				jQuery('.um-message-conv-item:first').trigger('click');
			}
			
			jQuery('.um-message-conv-view').empty();
			
		} else {
			
			remove_Modal();
			
		}
		
		// No more chats?
		if ( jQuery('.um-message-conv').find('.um-message-conv-item').length == 0 ) {
			jQuery('.um-profile-body.messages').append('<div class="um-message-noconv"><i class="um-icon-android-chat"></i>'+um_message_i18n.no_chats_found+'</div>');
			jQuery('.um-profile-body.messages').find('.um-message-conv, .um-message-conv-view').hide();
		}

		// Ajax request to delete conversation
		wp.ajax.send( 'um_messaging_delete_conversation', {
			data: {
				conversation_id: conversation_id,
				other_user: other_user,
				nonce: um_scripts.nonce
			},
			success: function(data){
			},
			error: function(e){
				console.log(e);
			}
		});
		return false;
	});
	
	/* Close modal */
	jQuery(document.body).on('click', '.um-message-hide',function(e){
		e.preventDefault();
		remove_Modal();
		return false;
	});
	
	jQuery(document.body).on('click', '.um-login-to-msg-btn', function(e) {
		e.preventDefault();
		prepare_Modal();
		
		var message_to = jQuery(this).attr('data-message_to');

		if ( jQuery('body').hasClass('um-page-members') ) {
			var page_id, matches = document.body.className.match(/(^|\s)page-id-(\d+)(\s|$)/);
			if ( matches ) {
			    page_id = matches[2];
			}

			var members_current_page = jQuery('.um-members-pagi .current').text();
		}

		wp.ajax.send( 'um_messaging_login_modal', {
			data: {
				message_to: message_to,
				page_id: page_id,
				current_page: members_current_page,
				nonce: um_scripts.nonce
			},
			success: function( data ) {
				if ( data.content ) {
					show_Modal( data.content );

					//insert login form
					var login_form = jQuery('.um_messaging_hidden_login').html();
					jQuery('.um_messaging_hidden_login').html('');
					jQuery('.um-message-modal .um-message-body').append( login_form );

					jQuery( document ).trigger( "um_messaging_open_login_form" );

					jQuery('.um-message-modal #redirect_to').val( data.redirect_url );

					responsive_Modal();
					um_responsive();
				} else {
					remove_Modal();
				}
			},
			error: function(e){
				console.log(e);
			}
		});

		return false;
	});


	jQuery( document ).on( "um_before_modal_removed", function(e) {
		if ( jQuery('.um_messaging_hidden_login').length ) {
			var login_form = jQuery('.um-message-modal .um-message-body').html();
			jQuery('.um-message-modal .um-message-body').html('');
			jQuery('.um_messaging_hidden_login').html( login_form );

			jQuery( document ).trigger( "um_messaging_close_login_form" );
		}
	});


	/* Display a conversation */
	jQuery( document.body ).on('click', '.um-message-conv-item', function(e){
		e.preventDefault();
		
		if ( jQuery(this).attr('data-trigger_modal') && jQuery('.um-message-conv-view').is(':hidden') )
			return false;
		
		if ( jQuery(this).hasClass('active') || UM_Conv_Ajax )
			return false;
		
		UM_Conv_Ajax = true;
		
		var link = jQuery(this);
		var savehtml = jQuery(this).html();
		jQuery(this).find('img').replaceWith('<span class="um-message-cssload"><i class="um-faicon-circle-o-notch"></i></span>');
		
		var message_to = jQuery(this).attr('data-message_to');
		
		window.history.pushState("string", "Conversation",  jQuery(this).attr('href') );

		wp.ajax.send( 'um_messaging_start', {
			data: {
				message_to: message_to,
				nonce: um_scripts.nonce
			},
			success: function(data){
				if ( data ) {
					UM_Update_UTC_to_LocalTime();
					jQuery('.um-message-conv-view').html( data );
					link.html( savehtml );
					jQuery('.um-message-conv-item').removeClass('active');
					link.addClass('active');

					Init_BodyConv();

					jQuery('.um-tip-n').tipsy({gravity: 'n', opacity: 1, offset: 3, delayIn: 500 });
					jQuery('.um-tip-w').tipsy({gravity: 'w', opacity: 1, offset: 3, delayIn: 500 });
					jQuery('.um-tip-e').tipsy({gravity: 'e', opacity: 1, offset: 3, delayIn: 500 });
					jQuery('.um-tip-s').tipsy({gravity: 's', opacity: 1, offset: 3, delayIn: 500 });

					UM_Conv_Ajax = false;
				}
			},
			error: function(e) {
				console.log(e);
			}
		});
		return false;
	});
	
	/* Remove a message */
	jQuery(document.body).on('click', '.um-message-item-remove',function(e){
		e.preventDefault();
		var message_id = jQuery(this).parents('.um-message-item').attr('data-message_id');
		var conversation_id = jQuery(this).parents('.um-message-item').attr('data-conversation_id');
		jQuery(this).parents('.um-message-item').fadeOut('fast');
		wp.ajax.send( 'um_messaging_remove', {
			data: {
				message_id: message_id,
				conversation_id: conversation_id,
				nonce: um_scripts.nonce
			},
			success: function(data){
			},
			error: function(e){
				console.log(e);
			}
		});
		return false;
	});
	
	/* Show emoji list */
	jQuery(document.body).on('click', '.um-message-emo',function() {
		if ( jQuery('span.um-message-emolist').is(':visible') ) {
			um_Hide_Emobox();
		} else {
			jQuery('span.um-message-emolist').show();
		}
	});
	
	/* Insert a smiley */
	jQuery(document.body).on('click', '.um-message-emolist span.um-message-insert-emo',function(e){
		var code = jQuery(this).attr('data-emo');
		var chatbox = jQuery('.um-message-textarea textarea');
		chatbox.val( chatbox.val() + ' ' + code );
		um_Hide_Emobox();
		um_Chatbox( chatbox );
		chatbox.focus();
	});

	/* Show message modal */
	jQuery(document.body).on('click', '.um-message-btn:not(.um-login-to-msg-btn), *[data-trigger_modal="conversation"]',function(e){

		e.preventDefault();

        if ( jQuery(this).attr('data-trigger_modal') && jQuery('.um-message-conv-view').is(':visible') ) return false;


		var message_to = jQuery(this).attr('data-message_to');
		
		jQuery('.um-message-conv-item').removeClass('active');
		jQuery(this).addClass('active');
		
		prepare_Modal();
		wp.ajax.send( 'um_messaging_start', {
			data: {
				message_to: message_to,
				nonce: um_scripts.nonce
			},
			success: function( data ) {
				if ( data ) {
					show_Modal( data );
					responsive_Modal();
					autosize( jQuery('.um-message-textarea textarea:visible') );
				} else {
					remove_Modal();
				}
			},
			error: function(e) {
				console.log(e);
			}
		});
		return false;
	});
	
	/* Send message */
	jQuery(document.body).on('click', '.um-message-send:not(.disabled)',function() {
		var obj = jQuery(this);
		obj.addClass('disabled');

		var message_to = obj.parents('.um-message-footer').siblings('.um-message-body').data('message_to');
		var content = obj.parents('.um-message-footer').find('.um_message_text').val();
		wp.ajax.send( 'um_messaging_send', {
			data: {
				message_to: message_to,
				content: content,
				nonce: um_scripts.nonce
			},
			success: function(data) {
				var textarea = jQuery('.um-message-textarea textarea:visible');
				UM_Update_UTC_to_LocalTime();
				textarea.val('');
				jQuery('.um-message-body:visible').find('.um-message-ajax:visible').html( data.messages ).data( 'last_updated', data.last_updated );

				jQuery('[data-conversation_id="new"]').data('conversation_id', data.conversation_id ).show();
				jQuery('.um-gdpr-donwload-link:visible').attr('href', data.chat_history_download );

				if ( data.limit_hit ) {
					jQuery('.um-message-footer:visible').html( jQuery('.um-message-footer:visible').data('limit_hit') );
				}

				jQuery('.um-message-footer:visible').find('.um-message-limit').html( textarea.data('maxchar') );

				jQuery('.um-popup-autogrow:visible').mCustomScrollbar('update').mCustomScrollbar("scrollTo", "bottom",{scrollInertia:0});
			},
			error: function(e){
				jQuery('.um-message-textarea textarea:visible').val('');
				console.log(e);
			}
		});
		return false;
	});
	
	/* Disabled send button */
	jQuery(document.body).on('click', '.um-message-send.disabled',function(e){
		e.preventDefault();
		return false;
	});
	
	/* Way to hide emo box */
	jQuery(document.body).on('click', 'textarea.um_message_text',function(event) {
		um_Hide_Emobox();
	});
	
	/* Message char limit */
	jQuery(document.body).on('keyup keydown keypress', 'textarea.um_message_text',function(event) {
		um_Hide_Emobox();
		um_Chatbox( jQuery( this ) );
	});

	jQuery(document.body).on('paste', 'textarea.um_message_text',function(e) {
		var chatbox = jQuery(this);
		var pastedData = e.originalEvent.clipboardData.getData('text');
		um_Hide_Emobox();

		var Length = chatbox.val().length + pastedData.length;
		var maxLen = chatbox.attr('data-maxchar');
		var AmountLeft = maxLen - Length;

		jQuery('.um-message-limit:visible').html(AmountLeft);
		if ( chatbox.val() + pastedData != '' ) {
			jQuery('.um-message-send:visible').removeClass('disabled');
		} else {
			jQuery('.um-message-send:visible').addClass('disabled');
		}
		if ( Length > maxLen ) {
			jQuery('.um-message-limit:visible').addClass('exceed');
			jQuery('.um-message-send:visible').addClass('disabled');
		} else {
			jQuery('.um-message-limit:visible').removeClass('exceed');
			if ( chatbox.val() + pastedData != '' ) {
				jQuery('.um-message-send:visible').removeClass('disabled');
			}
		}
	});


	if( jQuery('.um-profile-nav-item.um-profile-nav-messages.active').length > 0 ){

		if( typeof um_messages_get_url_vars()['conversation_id'] !== 'undefined' ){
			var conversation_id = um_messages_get_url_vars()['conversation_id'];
			jQuery('.um-message-conv-item[data-conversation_id='+conversation_id+']').click();
		}
    }

  	
  	/*
  	 * Get URL variables
  	 */
  	function um_messages_get_url_vars(){
	    var vars = [], hash;
	    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
	    for(var i = 0; i < hashes.length; i++)
	    {
	        hash = hashes[i].split('=');
	        vars.push(hash[0]);
	        vars[hash[0]] = hash[1];
	    }
	    return vars;
	}

});