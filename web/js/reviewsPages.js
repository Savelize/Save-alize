//completeItems = ["aaaa","ssss"];

var brandArr = [];	

$("#categorySelect").on('change',function(){
	$.ajax({
		type: 'POST',
		url: path,
		data: {
			catId: $("#categorySelect").val()
		},
		success: function(resp)
		{
			alert(resp);
			completeItems = resp[0];
			
			$( "#autocomplete" ).autocomplete({
		source: completeItems,
	});
		}
	});
});

//$( "#autocomplete" ).autocomplete({
//		source: completeItems,
//	});	

