$(document).ready(function(){
    var price = new Array();
    var chartproducts = new Array();
    price[0] = 0;
    var categorySources = new Array();
    var hiddenCategory = JSON.parse($("#hiddenCategory").val());
    var categoryHash={};
    var categoryID;
    var userChartStartDate;
    var userChartEndDate;
//    var pbID;
    for(i = 0; i < hiddenCategory.length; i++){
        categorySources[i] = hiddenCategory[i].name;
    }
    hiddenCategory.forEach(function(element,i){
        if(i <= hiddenCategory.length){
            categoryHash[hiddenCategory[i].name]= (hiddenCategory[i].id)
        }
    });
   
    //   .setDefaults({
    //        showOn: "both",
    //        buttonImageOnly: true,
    //        buttonImage: "calendar.gif",
    //        buttonText: "Calendar"
    //    });
   
   
    $('#startDate').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#endDate').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#startDate').on('change', function(){
        userChartStartDate = $('#startDate').val();
    });
    $('#endDate').on('change', function(){
        userChartEndDate = $('#endDate').val();
    });
    
    $("#categoryIP").autocomplete({
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
                var products = new Array();
                var brands = new Array();
                var pb = JSON.parse(response);
//                for(var b in pb.pbID){    
//                    pbID = (pb.pbID[b].id);
//                }
                //                alert(pb.pbID['id']);
                for(var b in pb.brands){    
                    brands[b] = pb.brands[b];
                }
                for(var b in pb.products){    
                    products[b] = pb.products[b];
                }
//                alert(brands);
                $("#brandIP").autocomplete({
                    source: brands
                });
                $("#productIP").autocomplete({
                    source: products
                });
            }
        });
    });
     
    
    function reportViaDatesOnly(){
        $.ajax({
            type: 'POST',
            url: dateOnlypath,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate
            },
            success: function(response) {
                plots = JSON.parse(response);
                console.log(plots);
                for(var i in plots){    
                   price[i] = parseInt(plots[i].price);
                    chartproducts[i] = plots[i].name;
                }
                
                var ctx = document.getElementById("myChart").getContext("2d");

                var data = {
                    labels : chartproducts,
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
                var myNewChart = new Chart(ctx).Bar(data);
            }
        });
    }
    
    
    function aButtonPressed(){

        $.ajax({
            type: 'POST',
            url: path,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
//                pbID: pbID,
                categoryID: categoryID
            },
            success: function(response) {
              //  alert(JSON.parse(response));
                plots = JSON.parse(response);
                console.log(plots);
                for(var i in plots){    
                    
                    price[i] = parseInt(plots[i].price);
                    chartproducts[i] = plots[i].products;
                  //  console.log(price[i]);
                }
//                alert(parseInt(plots.price));
//                price = parseInt(plots.price);
//                chartproducts = plots.products;
                //                price.push(2000);
                
                var ctx = document.getElementById("myChart").getContext("2d");

                var data = {
                    labels : chartproducts,
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
                var myNewChart = new Chart(ctx).Bar(data);
            }
        });
    }
                
    $('#generateReport').on('click', function(){
        aButtonPressed();
    });
    
    
    $('#reportViaDatesOnly').on('click', function(){
        reportViaDatesOnly();
    });
});