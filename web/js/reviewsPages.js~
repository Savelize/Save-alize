
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
		url: path1,
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
			console.log(completeItems);
			$("#autocomplete").autocomplete({
				source: completeItems
			}); 
		}
	});
});

$("#searchBrand").on('click',function(){
	if($("#autocomplete").val() != "")
	{
		$.ajax({
			type: 'POST',
			url: path2,
			datatype: 'json',
			data: {
				brandSearched : $("#autocomplete").val()
			},
			success: function(resp)
			{
				//alert(JSON.parse(resp)[0]);
				var picName = JSON.parse(resp)[0];
				var picOnePath = basePathOfPictures + picName;
				//alert(picOnePath);
				$("#pictures").html("<a href='#'><img width='200px' height='200px'/></a>");
				$("#pictures :last-child img").attr('src', picOnePath);
				$("#pictures :last-child img").attr('id', picName);
				$("#pictures :last-child").attr('href', newPath+"/"+picName);
				for(var i=1; i<JSON.parse(resp).length; i++)
				{
					picName = JSON.parse(resp)[i];
					picOnePath = basePathOfPictures + picName;
					//alert(picOnePath);
					$("#pictures").append("<a href='#'><img width='200px' height='200px' /></a>");
					$("#pictures :last-child img").attr('src', picOnePath);
					$("#pictures :last-child img").attr('id', picName);
					$("#pictures :last-child").attr('href', newPath+"/"+picName);
				}
				//$("$pictures").append('<img src= />');
				//var x = JSON.parse(resp);
				//$("#pictures").html(x);
			
			}
		});
	}
	//else
	//{}
});	

$("#likeImg").on('click',function(){
	var likeImgPath = baseLikeImg + 'liked.jpeg'
	$("#likeImg").attr('src', likeImgPath);
});

