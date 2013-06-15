
$(document).ready(function(){
	
	var categorySources = new Array();
    var categoryID;
    var categoryID2;
    // var pb = JSON.parse(response);
 
    // for(i = 0; i < hiddenCategory.length; i++){
    //     categorySources[i] = hiddenCategory[i].name;
    // }
    
    
    // hiddenCategory.forEach(function(element,i){
    //     if(i <= hiddenCategory.length){
    //         categoryHash[hiddenCategory[i].name]= (hiddenCategory[i].id)              
    //     	 	alert(categoryHash[i]);        
    //     }

    // });
   
    // $( "#catIp" ).autocomplete({
    //     source: categorySources
    // });

    $('#catIp').change(function() {
			categoryID = $("#catIp").val();
			var products = new Array();
			var brands = new Array();
			if(categoryID){
				$.ajax({
			            type: 'POST',
			            url: catValue,
			            data: {category_id: categoryID
			            },
			            success: function(response) {
			            	var pb = JSON.parse(response);
			            	for(var b in pb.brands){ 
								brands[b] = pb.brands[b];
							}
								for(var b in pb.products){ 
								products[b] = pb.products[b];
							}
								// alert(brands);
								$("#brandIp").autocomplete({
									source: brands
								});
			            	}
			        	});
				}else{
				alert("please enter a value");
		}
    });
    
    $('#addBrandButton').on('click', function(){
    	var brand = $("#brandIp").val();
			if(brand){
				$.ajax({

						type: 'POST',
						url: path,
						data: { brand: brand },
						success: function(response){
							$('#msg').html(response);
						}
				});
			}else{
				alert("please choose a correct value ..")
			}
	});

 //    $('#addBrandButton').on('click', function(){
 //    	var brand = $("#brandIp").val();
	// 		if(brand){
	// 			$.ajax({

	// 					type: 'POST',
	// 					url: path,
	// 					data: { brand: brand },
	// 								    // product: $().val("#productIp")},
	// 					success: function(response){
	// 						$('#msg').html(response);
	// 					}
	// 			});
	// 		}else{
	// 			alert("please choose a correct value ..")
	// 		}
	// });
	
	// var categoryID2 = $("#catInput").val();

	$('#catInput').change(function() {
			
			 categoryID2 = $("#catInput").find(":selected").val();
			 // alert(categoryID2);
			var product = new Array();
			var brand = new Array();
			if(categoryID2){
				$.ajax({
			            type: 'POST',
			            url: catValue,
			            data: {category_id: categoryID2
			            },
			            success: function(response) {
			            	var pb = JSON.parse(response);
			            	for(var b in pb.brands){ 
								brand[b] = pb.brands[b];
							}
								for(var b in pb.products){ 
								product[b] = pb.products[b];
							}

								$("#brandInput").autocomplete({
									source: brand
								});
								$("#productInput").autocomplete({
									source: product
								});
			            	}
			        	});
				}else{
				alert("please enter a value");
		}
    });

    // $('#addProductButton').on('click', function(){
    	
			if(brand && product){


			}else{
				// alert("please choose a correct value ..")
			}
	// });

			var brand = $("#brandInput").val();
    		var product = $("#productInput").val();
    		
				$('#submitForm').ajaxForm({ 
	   						    type: 'POST', 
	    						url: path2,
	    						success: function(response){
	    								$('#anothermsg').html(response);
	    							} 
								});

});