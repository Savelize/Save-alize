

$(document).ready(function(){
    var price = new Array();
    var products = new Array();
    
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
               // alert(JSON.parse(response));
                plots = JSON.parse(response);
                console.log(plots);
                for(var i in plots){
                    
                    price[i] = plots[i].productPrice;
                    products[i] = plots[i].productName;
                    console.log(price[i]);
                }
                //                    price[i] = plots[i].price;
                //                    prodcuts[i] = plots[i].product;
              //  alert(plots[3].productPrice);
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
                
        
    //        $.post("{{ path('site_savalize_ajaxtoshowchart') }}",               
    //        {
    //            startDate: 2013-06-01, 
    //            endDate: 2013-06-30
    //        },
    //        function(response){
    //            plots = jQuery.parseJSON(response);
    //            for($i = 0; $i <= plots.length ; $i++){
    //                price[$i] = plots[$i].price;
    //                prodcuts[$i] = plots[$i].product.name;
    //            }
    //            var ctx = document.getElementById("myChart").getContext("2d");
    //
    //            var data = {
    //                labels : products,
    //                datasets : [
    //                {
    //                    fillColor : "rgba(220,220,220,0.5)",
    //                    strokeColor : "rgba(220,220,220,1)",
    //                    pointColor : "rgba(220,220,220,1)",
    //                    pointStrokeColor : "#fff",
    //                    data : price
    //                }
    //                ]
    //            }
    //
    //            var myNewChart = new Chart(ctx).Line(data);
    //
    //        }, 'json');
    //        


    $('#generateReport').on('click', function(){
        aButtonPressed();
    });
    
});