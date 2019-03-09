jQuery(document).ready(function() {
	/**
		Skipping a profile progress
	**/
	jQuery(document).on('click', '.um-completeness-save a.skip',function(e){
		e.preventDefault();
		var key = jQuery('.um-completeness-editwrap').attr('data-key');
		var next_step = jQuery('.um-completeness-step[data-key='+key+']').next('.um-completeness-step:not(.completed,.is-core)').attr('data-key');

		if ( next_step ) {
		jQuery.ajax({
			url: wp.ajax.settings.url,
			type: 'post',
			data: {
				action:'um_profile_completeness_edit_popup',
				key: next_step,
				nonce: um_scripts.nonce
			},
			success: function(data){
				if ( data.success ) {
					show_Modal( data.data );
					responsive_Modal();

					jQuery(".um-popup .um-s1").select2({
						allowClear: true,
						minimumResultsForSearch: 10
					});

					jQuery('.um-datepicker').each(function(){
						elem = jQuery(this);

						if ( elem.attr('data-disabled_weekdays') != '' ) {
							var disable = JSON.parse( elem.attr('data-disabled_weekdays') );
						} else {
							var disable = false;
						}

						var years_n = elem.attr('data-years');

						var min = elem.attr('data-date_min');
						var max = elem.attr('data-date_max');

						var min = min.split(",");
						var max = max.split(",");

						elem.pickadate({
							selectYears: years_n,
							min: min,
							max: max,
							disable: disable,
							format: elem.attr('data-format'),
							formatSubmit: 'yyyy/mm/dd',
							hiddenName: true,
							onOpen: function() { elem.blur(); },
							onClose: function() { elem.blur(); }
						});
					});

					jQuery('.um-timepicker').each(function(){
						elem = jQuery(this);

						elem.pickatime({
							format: elem.attr('data-format'),
							interval: parseInt( elem.attr('data-intervals') ),
							formatSubmit: 'HH:i',
							hiddenName: true,
							onOpen: function() { elem.blur(); },
							onClose: function() { elem.blur(); }
						});
					});

				} else {
					remove_Modal();
				}
			}
		});
		} else {
			remove_Modal();
		}

		return false;
	});

	/**
		Saving profile progress
	**/
	jQuery(document).on('click', '.um-completeness-save a.save',function(e){
		e.preventDefault();
		var no_value = true;

		var type = jQuery(this).parents('.um-completeness-editwrap').find('input[type=text],input[type=radio],input[type=checkbox],textarea,select').attr('type');

		var key = jQuery(this).parents('.um-completeness-editwrap').find('input[type=text],input[type=radio],input[type=checkbox],textarea,select').attr('name');

		if ( !key ) {
			var key = jQuery(this).parents('.um-completeness-editwrap').attr('data-key');
		}

		if ( jQuery(this).parents('.um-completeness-editwrap').find('select').length && jQuery(this).parents('.um-completeness-editwrap').find('.picker').length == 0 ) {
			type = 'select';
			key = jQuery(this).parents('.um-completeness-editwrap').find('select').attr('id');
		}

		if ( type == 'radio' ) {

			var value = jQuery('input[name="'+key+'"]:checked').val();
			if ( value ) {
				no_value = false;
			}

		} else if ( type == 'checkbox' ) {

			var value = [];
			jQuery('input[name="'+key+'"]:checked').each(function(i){
				value.push( jQuery(this).val() );
			});

			if ( value ) {
				no_value = false;
				value = value.join(", ");
			}

		} else if ( type == 'select' ) {

			var value = jQuery('#'+key).val();

			if ( value ) {
				no_value = false;
			}

			if ( jQuery('.um-popup select[multiple]').length && value ) {
				no_value = false;
				value = value.join(", ");
			}

		} else {

			if ( jQuery(this).parents('.um-completeness-editwrap').find('textarea').length == 1 ) {
				key = jQuery(this).parents('.um-completeness-editwrap').find('textarea').attr('id');
			}

			var value = jQuery('.um-completeness-field #'+key).val();

			if ( jQuery(this).parents('.um-popup').find('.picker').length ) {
				var value = jQuery('#' + key + '_hidden').val()
							|| jQuery('#' + key).parents('.um-field-area').find('[type=hidden][name=' + key + ']').val();
			}

			if ( value.trim().length > 0 ) {
				no_value = false;
			}

		
		}

		key = key.replace('[]','');
		key = key.replace( 'um_completeness_widget_', '' );

		if ( no_value || !value ) {
			jQuery('input[name="'+key+'"]').focus();
		} else {

			jQuery.ajax({
				url: wp.ajax.settings.url,
				type: 'post',
				dataType: 'json',
				data: {
					action:'um_profile_completeness_save_popup',
					key: key,
					value: value,
					nonce: um_scripts.nonce
				},
				success: function(data){

					var next_step = jQuery('.um-completeness-step[data-key='+key+']').next('.um-completeness-step:not(.completed,.is-core)').attr('data-key');

					jQuery('.um-completeness-done').animate({width: data.percent + '%'});
					jQuery('div[data-key='+key+']').addClass('completed');
					jQuery('.um-completeness-jx').html( data.percent );

					var next_step = jQuery('.um-completeness-step[data-key='+key+']').next('.um-completeness-step:not(.completed,.is-core)').attr('data-key');

					if ( next_step ) {
						jQuery('.um-completeness-editwrap').css({'opacity':0.5});
						jQuery.ajax({
							url: wp.ajax.settings.url,
							type: 'post',
							data: {
								action:'um_profile_completeness_edit_popup',
								key: next_step,
								nonce: um_scripts.nonce
							},
							success: function(data){
								if ( data.success ) {
									show_Modal( data.data );
									responsive_Modal();

									jQuery('.um-completeness-editwrap').css({'opacity':1});

									jQuery(".um-popup .um-s1").select2({
										allowClear: true,
										minimumResultsForSearch: 10
									});

									jQuery('.um-datepicker').each(function(){
										elem = jQuery(this);

										if ( elem.attr('data-disabled_weekdays') != '' ) {
											var disable = JSON.parse( elem.attr('data-disabled_weekdays') );
										} else {
											var disable = false;
										}

										var years_n = elem.attr('data-years');

										var min = elem.attr('data-date_min');
										var max = elem.attr('data-date_max');

										var min = min.split(",");
										var max = max.split(",");

										elem.pickadate({
											selectYears: years_n,
											min: min,
											max: max,
											disable: disable,
											format: elem.attr('data-format'),
											formatSubmit: 'yyyy/mm/dd',
											hiddenName: true,
											onOpen: function() { elem.blur(); },
											onClose: function() { elem.blur(); }
										});
									});

									jQuery('.um-timepicker').each(function(){
										elem = jQuery(this);

										elem.pickatime({
											format: elem.attr('data-format'),
											interval: parseInt( elem.attr('data-intervals') ),
											formatSubmit: 'HH:i',
											hiddenName: true,
											onOpen: function() { elem.blur(); },
											onClose: function() { elem.blur(); }
										});
									});

								} else {
									remove_Modal();
									location.reload();
								}
							}
						});
					} else {
						remove_Modal();
						location.reload();
					}

				}
			});

		}
		return false;
	});
	
	// Append user id in modal for image uploads
   jQuery(document).on('click','body.um-own-profile a[data-modal="um_upload_single"]',function(e){
   		var user_id = jQuery('.um-completeness-widget').attr('data-user_id');
   		if( user_id ){
			jQuery("#um_upload_single").attr('data-user_id', user_id );
		}

   });

	/**
		Editing a profile progress
	**/
	jQuery(document).on('click', '.um-completeness-edit',function(e) {
		if ( jQuery(this).attr('data-key') && ! jQuery(this).parents('.um-completeness-step').hasClass('completed') ) {
			var key = jQuery(this).attr('data-key');
			prepare_Modal();

			jQuery.ajax({
				url: wp.ajax.settings.url,
				type: 'post',
				data: {
					action:'um_profile_completeness_edit_popup',
					key: key,
					nonce: um_scripts.nonce
				},
				success: function( data ) {
					if ( data.success ) {
						show_Modal( data.data );
						responsive_Modal();

						jQuery(".um-popup .um-s1").select2({
							allowClear: true,
							minimumResultsForSearch: 10
						});

						jQuery('.um-datepicker').each(function(){
							elem = jQuery(this);

							if ( elem.attr('data-disabled_weekdays') != '' ) {
								var disable = JSON.parse( elem.attr('data-disabled_weekdays') );
							} else {
								var disable = false;
							}

							var years_n = elem.attr('data-years');

							var min = elem.attr('data-date_min');
							var max = elem.attr('data-date_max');

							var min = min.split(",");
							var max = max.split(",");

							elem.pickadate({
								selectYears: years_n,
								min: min,
								max: max,
								disable: disable,
								format: elem.attr('data-format'),
								formatSubmit: 'yyyy/mm/dd',
								hiddenName: true,
								onOpen: function() { elem.blur(); },
								onClose: function() { elem.blur(); }
							});
						});

						jQuery('.um-timepicker').each(function(){
							elem = jQuery(this);

							elem.pickatime({
								format: elem.attr('data-format'),
								interval: parseInt( elem.attr('data-intervals') ),
								formatSubmit: 'HH:i',
								hiddenName: true,
								onOpen: function() { elem.blur(); },
								onClose: function() { elem.blur(); }
							});
						});

					} else {
						remove_Modal();
					}
				}
			});
			return false;
		} else {
			if ( ! jQuery(this).parents('.um-completeness-step').hasClass('is-core') ) {
				//e.preventDefault();
				return false;
			}
		}
	});

});
