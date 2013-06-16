
$(document).ready(function(){
	
	var categorySources = new Array();
    var categoryID;
    var categoryID2;

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
			            	// alert(pb);
			            	for(var b in pb.brands){ 
								brands[b] = pb.brands[b];
							}
								for(var b in pb.products){ 
								products[b] = pb.products[b];
							}
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


	$('#catInput').change(function() {
			categoryID2 = $("#catInput").find(":selected").val();
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

				$('#submitForm').ajaxForm({ 
	   						    type: 'POST', 
	    						url: path2,
	    						success: function(response){
	    								$('#anothermsg').html(response);
	    							}
	    			}); 				
});