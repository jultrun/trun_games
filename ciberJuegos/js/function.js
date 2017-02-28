

$(function() { 
    $( "#buscador-juego" ).autocomplete({
      minLength: 0,
      source:  function( request, response ) {
			$.ajax({
				url: "http://localhost/ciberJuegos/php/json/juegoslike.php",
				type: "post",
				dataType: "json",
				data: {
				term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
      focus: function( event, ui ) {
        $( "#buscador-juego" ).attr("value",ui.item.IDJUEGO);
        return false;
      },
      select: function( event, ui ) {
        $( "#buscador-juego" ).attr("value",ui.item.IDJUEGO);
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<a href='index.php?cat=games&id="+item.IDJUEGO+"'>" + "<div class='busqueda-nombre'>"+item.NOMBREJUEGO + "</div><div class='busqueda-precio'>" + item.PRECIOJUEGO + "</div></a>" )
        .appendTo( ul );
    };
    $( "#tabs-games" ).tabs();
    




    $( "#buscador-genero" ).autocomplete({
      minLength: 0,
      source:  function( request, response ) {
			$.ajax({
				url: "../php/json/generos.php",
				type: "post",
				dataType: "json",
				data: {
				term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
      focus: function( event, ui ) {
        $( "#buscador-genero" ).val(ui.item.IDGENERO);
        
        return false;
      },
      select: function( event, ui ) {
          $( "#buscador-genero" ).val(ui.item.IDGENERO);;
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $( "<li>" )
        .append( "<div class='busqueda-nombre'>"+item.NOMBREGENERO +"("+item.IDGENERO+")</div>" )
        .appendTo( ul );
    };
    
    
    
    
    $( "#buscador-dessarrolladoras" ).autocomplete({
        minLength: 0,
        source:  function( request, response ) {
  			$.ajax({
  				url: "../php/json/desarrolladora.php",
  				type: "post",
  				dataType: "json",
  				data: {
  				term: request.term
  				},
  				success: function(data) {
  					response(data);
  				}
  			});
  		},
        focus: function( event, ui ) {
          $( "#buscador-dessarrolladoras" ).val(ui.item.IDDESARROLLADOR);
   
          
          return false;
        },
        select: function( event, ui ) {
      	  $( "#buscador-dessarrolladoras" ).val(ui.item.IDDESARROLLADOR);
          return false;
        }
      })
      .autocomplete( "instance" )._renderItem = function( ul, item ) {
        return $( "<li>" )
          .append( "<div class='busqueda-nombre'>"+item.NOMBREDESAROLLADOR +"("+item.IDDESARROLLADOR+")</div>" )
          .appendTo( ul );
      };
   
      $( "#buscador-juegou" ).autocomplete({
          minLength: 0,
          source:  function( request, response ) {
    			$.ajax({
    				url: "http://localhost/ciberJuegos/php/json/juegoslike.php",
    				type: "post",
    				dataType: "json",
    				data: {
    				term: request.term
    				},
    				success: function(data) {
    					response(data);
    				}
    			});
    		},
          focus: function( event, ui ) {
            $( "#buscador-juegou" ).val(ui.item.NOMBREJUEGO);
            $( "#buscador-juegou" ).attr("value",ui.item.IDJUEGO);
            return false;
          },
          select: function( event, ui ) {
            $( "#buscador-juegou" ).val(ui.item.NOMBREJUEGO);
            $( "#buscador-juegou" ).attr("value",ui.item.IDJUEGO);
            return false;
          }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<div class='busqueda-nombre'>"+item.NOMBREJUEGO + "</div>" )
            .appendTo( ul );
        };
        
        
        $( "#precio" ).spinner({
			min: 0,
			step:1000});
		$( "#descuento" ).spinner({
			min: 0,
			max:100});
			$( "#datepicker" ).datepicker( $.datepicker.regional[ "es" ] );
			$( "#locale" ).change(function() {
				$( "#datepicker" ).datepicker( "option",
						$.datepicker.regional[ $( this ).val() ] );
			});
			if($("#acerca").length>0){
			CKEDITOR.replace( 'acerca',{
				filebrowserBrowseUrl: 'http://localhost/ciberJuegos/php/ckfinder/ckfinder.html',
				filebrowserUploadUrl: 'http://localhost/ciberJuegos/php/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
			});
			}
			if($("#comentar").length>0){
				CKEDITOR.replace( 'comentar',{
				
				});
				}
			
			
			
			
			$( "#etiquetas-buscador" ).autocomplete({
			      minLength: 0,
			      source:  function( request, response ) {
						$.ajax({
							url: "http://localhost/ciberJuegos/php/json/etiequetas.php",
							type: "post",
							dataType: "json",
							data: {
							term: request.term
							},
							success: function(data) {
								response(data);
							}
						});
					},
			      focus: function( event, ui ) {
			        $( "#etiquetas-buscador" ).val(ui.item.NOMBREETIQUETA);
			        $( "#currentEtiqueta" ).val(ui.item.IDETIQUETA)
			        return false;
			      },
			      select: function( event, ui ) {
			        $( "#etiquetas-buscador" ).val(ui.item.NOMBREETIQUETA);
			        $( "#currentEtiqueta" ).val(ui.item.IDETIQUETA)
			        return false;
			      }
			    })
			    .autocomplete( "instance" )._renderItem = function( ul, item ) {
			      return $( "<li>" )
			        .append( "<div class='busqueda-nombre'>"+item.NOMBREETIQUETA+"</div>" )
			        .appendTo( ul );
			    };
			
			$("#agregarcat").click(function() {
				var $inputs = $("#etiquetas div input")
				var ids=[]
				for(i=0;i<$inputs.length ;i++){
					ids[i]=$($inputs[i]).attr("name")
				}
				if($.inArray($("#currentEtiqueta").val(),ids)==-1){
				if($("#etiquetas-buscador").val()!="" &&  ($("#currentEtiqueta").val()>0 || $("#currentEtiqueta").val()>0) ){
					$("#etiquetas").append('<div><input type="hidden" name="etiquetas[]" value ="'+$("#currentEtiqueta").val()+'">'+$("#etiquetas-buscador").val()+'<span class="removedor" >X</span></div>')
					$( "#etiquetas-buscador").val("")
					$("#currentEtiqueta").val(-1)
					$( ".removedor" ).click(function() {
						  $(this).parent().remove()
						});
					}
				}
			});
			
			
			
			
			
		    $( "#buscador-generou" ).autocomplete({
		        minLength: 0,
		        source:  function( request, response ) {
		  			$.ajax({
		  				url: "../php/json/generos.php",
		  				type: "post",
		  				dataType: "json",
		  				data: {
		  				term: request.term
		  				},
		  				success: function(data) {
		  					response(data);
		  				}
		  			});
		  		},
		        focus: function( event, ui ) {
		          $( "#buscador-generou" ).val(ui.item.NOMBREGENERO);
		          $( "#buscador-generou" ).attr("value",ui.item.IDGENERO);
		          
		          return false;
		        },
		        select: function( event, ui ) {
		        	 $( "#buscador-generou" ).val(ui.item.NOMBREGENERO);
			          $( "#buscador-generou" ).attr("value",ui.item.IDGENERO);
		          return false;
		        }
		      })
		      .autocomplete( "instance" )._renderItem = function( ul, item ) {
		        return $( "<li>" )
		          .append( "<div class='busqueda-nombre'>"+item.NOMBREGENERO +"</div>" )
		          .appendTo( ul );
		      };
		      
		      
		      
		      
		      
		      
		      
		      $( "#buscador-desarroladoru" ).autocomplete({
			        minLength: 0,
			        source:  function( request, response ) {
			  			$.ajax({
			  				url: "../php/json/desarrolladora.php",
			  				type: "post",
			  				dataType: "json",
			  				data: {
			  				term: request.term
			  				},
			  				success: function(data) {
			  					response(data);
			  				}
			  			});
			  		},
			        focus: function( event, ui ) {
			          $( "#buscador-desarroladoru" ).val(ui.item.NOMBREDESAROLLADOR);
			          $( "#buscador-desarroladoru" ).attr("value",ui.item.IDDESARROLLADOR);
			          
			          return false;
			        },
			        select: function( event, ui ) {
			        	 $( "#buscador-desarroladoru" ).val(ui.item.NOMBREDESAROLLADOR);
				          $( "#buscador-desarroladoru" ).attr("value",ui.item.IDDESARROLLADOR);
			          return false;
			        }
			      })
			      .autocomplete( "instance" )._renderItem = function( ul, item ) {
			        return $( "<li>" )
			          .append( "<div class='busqueda-nombre'>"+item.NOMBREDESAROLLADOR +"</div>" )
			          .appendTo( ul );
			      };
			      
			      
			      
			      
			      
			      
			      
			      
			      
			      
			      
			      $( "#buscador-etiquetau" ).autocomplete({
			          minLength: 0,
			          source:  function( request, response ) {
			    			$.ajax({
			    				url: "http://localhost/ciberJuegos/php/json/etiequetas.php",
			    				type: "post",
			    				dataType: "json",
			    				data: {
			    				term: request.term
			    				},
			    				success: function(data) {
			    					response(data);
			    				}
			    			});
			    		},
			          focus: function( event, ui ) {
			            $( "#buscador-etiquetau" ).val(ui.item.NOMBREETIQUETA);
			            $( "#buscador-etiquetau" ).attr("value",ui.item.IDETIQUETA);
			            return false;
			          },
			          select: function( event, ui ) {
			            $( "#buscador-etiquetau" ).val(ui.item.NOMBREETIQUETA);
			            $( "#buscador-etiquetau" ).attr("value",ui.item.IDETIQUETA);
			            return false;
			          }
			        })
			        .autocomplete( "instance" )._renderItem = function( ul, item ) {
			          return $( "<li>" )
			            .append( "<div class='busqueda-nombre'>"+item.NOMBREETIQUETA + "</div>" )
			            .appendTo( ul );
			        };

		      	
			
      
 });

function updatejuego(id){
	$.ajax({
		url: "http://localhost/ciberJuegos/php/json/juegoone.php",
		type: "post",
		dataType: "json",
		data: {
		id: id
		}
	}).done(function(respuestas){
		datos=respuestas[0];
		$("#juegoIDd").html(datos.IDJUEGO)
		$("#juegoIDi").val(datos.IDJUEGO)
		$("#descripcion").val(datos.DESCJUEGO)
		$("#datepicker").val(datos.LANZAMIENTOJUEGO.replace("-","/").replace("-","/"));
		$("#nombrej").val(datos.NOMBREJUEGO)
		$("#precio").val(datos.PRECIOJUEGO)
		$("#descuento").val(datos.DESCUENTOJUEGO)
		$("#buscador-genero").val(datos.IDGENERO)
		$("#buscador-dessarrolladoras").val(datos.IDDESAROLLADOR)
		CKEDITOR.instances.acerca.setData(datos.ACERCAJUEGO)
	});
	
	$.ajax({
		url: "http://localhost/ciberJuegos/php/json/requerimientosjuego.php",
		type: "post",
		dataType: "json",
		data: {
		id: id 		
		}
	}).done(function(respuestas){
		for (var i = 0; i < respuestas.length; i++) {
			var dato = respuestas[i];
			$("#ram-"+dato.NOMBRE).val(dato.RAM)
			$("#disco-"+dato.NOMBRE).val(dato.DISCO)
			$("#grafica-"+dato.NOMBRE).val(dato.GRAFICA)
			$("#procesador-"+dato.NOMBRE).val(dato.PROCESADOR)
		}
	});
	
	$.ajax({
		url: "http://localhost/ciberJuegos/php/json/etiequetasJuego.php",
		type: "post",
		dataType: "json",
		data: {
		id: id 		
		}
	}).done(function(respuestas){
		$("#etiquetas").html("")
		for (var i = 0; i < respuestas.length; i++) {
			console.log(respuestas[i].NOMBREETIQUETA)
			$("#etiquetas").append('<div><input type="hidden" name="etiquetas[]" value ="'+respuestas[i].IDETIQUETA+'">'+respuestas[i].NOMBREETIQUETA+'<span class="removedor" >X</span></div>')
			$( ".removedor" ).click(function() {
				  $(this).parent().remove()
				});
		
		
		}
	});
	
}
function updategenero(id){
	
	$.ajax({
		url: "http://localhost/ciberJuegos/php/json/generosone.php",
		type: "post",
		dataType: "json",
		data: {
		id: id
		}
	}).done(function(respuestas){
		datos=respuestas[0];
		$("#generoIDd").html(datos.IDGENERO)
		$("#generoIDi").val(datos.IDGENERO)
		$("#nombreg").val(datos.NOMBREGENERO)
	});
	
	
	
}



function updatedesarroradora(id){
	
	$.ajax({
		url: "http://localhost/ciberJuegos/php/json/desarrolladoraone.php",
		type: "post",
		dataType: "json",
		data: {
		id: id
		}
	}).done(function(respuestas){
		datos=respuestas[0];
		$("#desarroladorIDd").html(datos.IDDESARROLLADOR)
		$("#desarroladorIDi").val(datos.IDDESARROLLADOR)
		$("#nombred").val(datos.NOMBREDESAROLLADOR)
	});
	
	
	
}


function updateetiqueta(id){
	
	$.ajax({
		url: "http://localhost/ciberJuegos/php/json/etiquetaone.php",
		type: "post",
		dataType: "json",
		data: {
		id: id
		}
	}).done(function(respuestas){
		datos=respuestas[0];
		$("#etiquetaIDd").html(datos.IDETIQUETA)
		$("#etiquetaIDi").val(datos.IDETIQUETA)
		$("#nombree").val(datos.NOMBREETIQUETA)
	});
	
	
	
}


