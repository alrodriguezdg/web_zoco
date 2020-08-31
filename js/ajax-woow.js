(function( $ ){

	/*
	==============================================
	Scripts Ajax to send mail "Contact"
	==============================================
	*/

	SendForm = function ( idFom ){

		copyHtmlOk = $( '#alertSuccess span' ).html();

		$( ".input_submit" ).attr( 'disabled', 'disabled' );

		if ( $('.printErrors').is(':visible') ) {

			$('.printErrors').fadeOut(0);

		}
		
		var options = {
				type: "POST"
			,	url: 'registrar-usuario.php'
			,	dataType: "json"
			,	resetForm: true
			,	beforeSubmit: validate
			,	beforeSend: function(){
					$( '#loader_special' ).fadeIn( 200 );
					$( '#loader_special .expecial_txt_loader' ).html( 'Enviando...' );
				}
			,	success: function( msn ){

					console.log( msn );
					
					if( msn.validate == true ){
						// Objetio de envio Analytics
						gtag('event', 'envio-formulario', {
						  'event_category' : 'registro',
						  'event_label' : 'enviado'
						});

						$( '#loader_special' ).fadeOut( 200 );
						$( '.alertOk' ).fadeIn( 20 );

						$( '.alertOk' ).fadeOut( 0 );
							$( '.alertOk.alertContacto' ).fadeIn( 200 );

					}else{

						if( msn.htmlErrors ) {
							// Print errors of validation
							$( '.printErrors' ).fadeIn().html( msn.htmlErrors );
						}

						$( '#alertFailSend' ).fadeIn( 200 );
						$( '#loader_special' ).fadeOut( 200 );
					}

			}
			, 	error: function( msn ){

					console.log( msn );

				}

		}

		$( idFom ).ajaxSubmit( options );

		setTimeout( function(){
			$( '.alertFail' ).fadeOut( 2000, 'swing' );
			$( '.alertOk' ).fadeOut( 4000 );
			$( ".input_submit" ).removeAttr( 'disabled' );

		}, 8000 );

	}
	// Publicamos la funcion paraque sea visible desde afuera
	this.SendForm;

	/*
	==============================================
	Scripts to validate input requires in form
	==============================================
	*/

	function validate(formData, jqForm, options) {


		var inputValidate = true;
		$( jqForm.selector + ' .woowRequireFail' ).removeClass( 'woowRequireFail' );

		// Validate inputs type [text]
		for (var i=0; i < formData.length; i++) {
			var inputName = formData[i].name;
			// $( jqForm.selector ).serializeArray();

			// Validate inputs type [text]
			if ( formData[i].required == true && !formData[i].value ) {
				inputValidate = false;
				$( jqForm.selector + ' [name="' + inputName + '"]' ).addClass( 'woowRequireFail' );

			}

			// Validate inputs type [email]
			if( formData[i].type == 'email' &&  formData[i].value ){
				inputValidEmail = validateEmail( formData[i].value );
				if( !inputValidEmail ){
					inputValidate = false;
					$( jqForm + ' input[name="' + inputName + '"]' ).addClass( 'woowRequireFail' );
				}

			}
		}
		
		// Validate inputs type [radio]
		$( jqForm ).find( ":radio, :checkbox" ).each( function(){
			
			// get name of input
			name = $( this ).attr( 'name' );
			// get attribute required of input
			requiredVal = $( this ).attr( 'required' )
			// get attribute checked of input
			checkedVal = $( '[name="'+ name +'"]:checked' ).length



			// validate attributes of input
			if( requiredVal && checkedVal == 0 ){
				inputValidate = false;
				$( this ).closest( '.parentValidate' ).addClass( 'woowRequireFail' );

				if(name=='term_cond'){
					$('#termCondiciones').addClass('errorTerm');
				}
			}

		} );


		if( !inputValidate ){

			$( '#alertFailValidation' ).fadeIn( 200 );
			$( '#div-loader' ).fadeOut( 300 );

			return false;
		}
	}


	/*
	==============================================
	Scripts to validate input Email
	==============================================
	*/

	function validateEmail( email ) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

		if( !emailReg.test( email ) ) {
			return false; //No es E-mail

		} else {
			return true; //Si es E-mail

		}
	}


	/*
	==============================================
	Scripts to Validate numbers
	==============================================
	*/

	function ValidNumber( e ){
		tecla = (document.all) ? e.keyCode : e.which;
		//Tecla de retroceso para borrar, siempre la permite
		if ((tecla==8)||(tecla==0)){
			return true;
		}
		// Patron de entrada, en este caso solo acepta numeros
		patron =/[0-9]/;
		tecla_final = String.fromCharCode(tecla);
		return patron.test(tecla_final);
	}


})( jQuery );
