$(document).ready(function(){
var allBrands = JSON.parse($("#allBrands").val());
var completeItems = new Array();
for(i=0;i<allBrands.length;i++){
//alert (allBrands[i]);
	completeItems[i] = allBrands[i]; 
}
$("#autocomplete").autocomplete({
		source: completeItems
	});
var brandArr = [];	

$("#categorySelect").on('change',function(){
	$.ajax({
		type: 'POST',
		url: path,
		datatype: 'json',
		data: {
			catId: $("#categorySelect").val()
		},
		success: function(resp)
		{
			var allBrands = JSON.parse(resp);
			
			completeItems=[];
			
			for(i=0;i<allBrands.length;i++)
			{
				completeItems[i] = allBrands[i]; 
			}
			$("#autocomplete").autocomplete({
				source: completeItems
			}); 
		}
	});
});
});
	

