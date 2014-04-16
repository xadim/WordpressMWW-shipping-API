//How to store images in your server where creating view of generated images.

$('#fpd-create-image, #fpd-create-view-image').click(function(evt) {

						evt.preventDefault();

						if(_checkAPI()) {

							var stage = fancyProductDesigner.getStage(),
								objects = stage.getObjects(),
								$this = $(this),
								tempViewIndex = fancyProductDesigner.getViewIndex(),
								onlyCurrentView = $this.attr('id') == 'fpd-create-view-image';

							_scaleStage(onlyCurrentView ? tempViewIndex : false, 'image', function(dataUrls) {

								var image = new Image();


								$.ajax({
									url: "<?php echo admin_url('admin-ajax.php'); ?>",
									data: {
										action: 'fpd_imagefromdataurl',
										_ajax_nonce: "<?php echo $this->ajax_nonce; ?>",
										order_id: <?php echo $thepostid; ?>,
										item_id: currentItemId,
										data_url: dataUrls[0],
										title: currentItemId+'_order_item_generated'
									},
									type: 'post',
									dataType: 'json',
									complete: function(data) {

										var json = data.responseJSON;
										if(data.status != 200 || json.code == 500) {
											alert("<?php _e( 'Could not create image. Please try again', 'radykal' ); ?>");
										}
										else if( json.code == 201 ) {
											$orderImageList.append('<li><a href="'+json.url+'" title="'+json.url+'" target="_blank">'+json.title+'.png</a></li>');
										}
										else {
											//prevent caching
											$orderImageList.find('a[title="'+json.url+'"]').attr('href', json.url+'?t='+new Date().getTime());
										}
										$('#fpd-ajax-loader-image').hide();
										$this.prop('disabled', false);

									}

								});
								_resetObjects(tempViewIndex);
							});

						}

					});
