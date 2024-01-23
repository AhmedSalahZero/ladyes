function getYesOrNoForBoolean(boolean , lang){
	const result = boolean ? {en:"Yes",ar:"نعم"} : {en:"No" , ar:"لا"} 
	console.log(boolean,lang,result[lang])
	return  result[lang]
}
$(document).on('click', '.confirm_delete', function () {
	const modal = $(this).closest('.modal');
	
	const deleteRoute = $(this).attr('data-delete-route')
	const token = $('body').attr('data-token')
	var id = $(this).attr('data-id')
	var ids = [id]
	
	$.ajax({
		type: 'DELETE',
		url: deleteRoute,
		data: {
			'_token': token,
			ids
		},
		success: function (data) {
			if (data.status == true) {
				window.location.reload();
			}else{
				$('.error-msg-div').show()
				$('#fail-msg-id').html(data.msg);
			}
		}, error: function (reject) {

		}
	})
})

$(document).on('change', '.switch-trigger-js', function () {
	var id = $(this).attr('data-id')
	const lang = $('body').attr('data-lang')
	var checked = $(this).is(':checked')
	const token = $('body').attr('data-token')
	const url = $(this).attr('data-toggle-route')
	$.ajax({
		type: 'put',
		url,
		data: {
			'_token': token,
			'id': id,
			'is_active': checked,
		},
		success: function (data) {
			if (data.status == true) {
				$('#type-success').click();
				if($('.js-is-verified[data-model-id="'+id+'"]').length){ // for driver modal banned check box 
					let checkedFormatted = getYesOrNoForBoolean(checked,lang)
					$('.js-is-verified[data-model-id="'+ id +'"').html(checkedFormatted)
					if(checked){
						$('.send-verification-code-message-js[data-model-id="'+id+'"]').fadeOut(500);
					}else{
						$('.send-verification-code-message-js[data-model-id="'+id+'"]').fadeIn(500);
					}
				}
			}
		}
	})
})

$(document).on('change','select.country-updates-cities-js',function(){
	const token =$('body').attr('data-token');
	const countryId = $(this).val();
	if(countryId){
		$.ajax({
			url:route('update.cities.based.on.country',{country_id:countryId}),
			data:{
				"_token":token,
			},
			success:function(res){
				let options = '';
				let cities = res.data ;
				for(var index in cities){
					options+='<option value="'+ cities[index].id +'">'+ cities[index].name +'</option>'
				}
				$('select#city_id').empty().append(options).selectpicker('refresh').trigger('change');
			}
		})
	}
})



$(document).on('change','select.city-updates-areas-js',function(){
	const token =$('body').attr('data-token');
	const cityId = $(this).val();
	if(cityId){
		$.ajax({
			url:route('update.areas.based.on.city',{city_id:cityId}),
			data:{
				"_token":token,
			},
			success:function(res){
				let options = '';
				let areas = res.data ;
				for(var index in areas){
					options+='<option value="'+ areas[index].id +'">'+ areas[index].name +'</option>'
				}
				$('select#area_id').empty().append(options).selectpicker('refresh');
			}
		})
	}
})



$(document).on('change','select.update-models-based-on-make-js',function(){
	const token =$('body').attr('data-token');
	const makeId = $(this).val();
	if(makeId){
		$.ajax({
			url:route('update.models.based.on.make',{make_id:makeId}),
			data:{
				"_token":token,
			},
			success:function(res){
				let options = '';
				let models = res.data ;
				for(var index in models){
					options+='<option value="'+ models[index].id +'">'+ models[index].name +'</option>'
				}
				$('select#model_id').empty().append(options).selectpicker('refresh').trigger('change');
			}
		})
	}
})





$(document).on('click', '.js-save-by-ajax', function(e) {
	e.preventDefault();

	// Validate form before submit
	form = $(this).closest('form')[0]

	var formData = new FormData(form);

	this.disabled = true;
	$.ajax({
		type: "POST"
		, url: $(form).attr('action')
		, data: formData
		, cache: false
		, contentType: false
		, processData: false
		, success: function(res) {
			console.log(res);
			if (res.status) {
					Swal.fire({
						icon: 'success'
						, title: res.message
						, buttonsStyling: false,
						timer: 2000,
						showCancelButton: false,
    					showConfirmButton: false
						, customClass: {
							confirmButton: "btn btn-primary"
						}
						// text: successMessage,
					}).then(function(){
						if(res.redirectTo){
							window.location.href=res.redirectTo
						}
					})

			} else {
				$('.js-save-by-ajax').prop('disabled', false)
			console.log(res)
				
				Swal.fire({
					icon: 'error'
					, title: res.message,
				})
			}
		}
		, error: function(res) {

			$('.js-save-by-ajax').prop('disabled', false)

			Swal.fire({
				icon: 'error'
				, title: res.responseJSON.message,

			})


		}
	})



});
