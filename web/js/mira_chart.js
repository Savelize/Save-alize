$(document).ready(function(){
    var price = new Array();
    var products = new Array();
    price[0] = 0;
    var categorySources = new Array();
    var hiddenCategory = JSON.parse($("#hiddenCategory").val());
    var categoryHash={};
    var categoryID;
 
    for(i = 0; i < hiddenCategory.length; i++){
        categorySources[i] = hiddenCategory[i].name;
    }
    
    
    hiddenCategory.forEach(function(element,i){
        if(i <= hiddenCategory.length){
            categoryHash[hiddenCategory[i].name]= (hiddenCategory[i].id)              
                
        }
    });
   
    
    
    $( "#categoryIP" ).autocomplete({
        source: categorySources
    });
    $("#categoryIP").on('change', function(){
        categoryID = (categoryHash[$("#categoryIP").val()]);
        $.ajax({
            type: 'POST',
            url: fromCategoryPath,
            data: { 
                categoryID: categoryID
            },
            success: function(response) {
                
            }
        });
    });
     
    
    function aButtonPressed(){

        $.ajax({
            type: 'POST',
            url: path,
            datatype: 'json',
            data: {
                startDate: 2013-06-01, 
                endDate: 2013-06-30
            },
            success: function(response) {
                alert(JSON.parse(response));
                plots = JSON.parse(response);
                console.log(plots);
                for(var i in plots){    
                    price[i] = parseInt(plots[i].price);
                    products[i] = plots[i].name;
                    console.log(price[i]);
                }
                price.push(2000);
                
                var ctx = document.getElementById("myChart").getContext("2d");

                var data = {
                    labels : products,
                    datasets : [
                    {
                        fillColor : "rgba(220,220,220,0.5)",
                        strokeColor : "rgba(220,220,220,1)",
                        pointColor : "rgba(220,220,220,1)",
                        pointStrokeColor : "#fff",
                        data : price
                    }
                    ]
                }

                var myNewChart = new Chart(ctx).Line(data);

            
            }
            
        });
    }
                
       
    $('#generateReport').on('click', function(){
        aButtonPressed();
    });
    
});