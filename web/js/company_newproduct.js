$(document).ready(function(){
	
	var categorySources = new Array();
    var categoryID;
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
			
				// alert(categoryID);
			if(categoryID){
				$.ajax({
			            type: 'POST',
			            url: catValue,
			            data: {category_id: categoryID
			            },
			            success: function(response) {
                          alert(response);

			            	var pb = JSON.parse(response);
			            	for(var b in pb.brands){ 
								brands[b] = pb.brands[b];
							}
								for(var b in pb.products){ 
								products[b] = pb.products[b];
							}
								alert(brands);
								$("#brandIp").autocomplete({
									source: brands
								});
								$("#productIp").autocomplete({
									source: products
								});
			            	}
			        	});
				/*	$.ajax({
							type: 'POST',
							url: path,
							data: { brand: $("#brandIp").val(),
								    product: $().val("#productIp")},
							success: function(response){

							}

					});*/

			}else{
				alert("please enter a value");
		}
    });

});