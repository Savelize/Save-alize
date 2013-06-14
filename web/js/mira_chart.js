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
    $('#productIp').attr('disabled', true);
    $('#brandIp').attr('disabled');
    $('#categoryIp').attr('disabled', true);
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
    
    
    var filterSelect =  $("#filterSelect");
    filterSelect.on('change', function(){
        if(filterSelect.val() == 1){       
            dateAndCategory();
        }else if(filterSelect.val() == 2){
            dateAndBrand();
        }else if(filterSelect.val() == 3){
            dateAndProduct();
        }else if(filterSelect.val() == 4){
            dateProductAndBrand();
        }
    });
    
     
    function dateAndBrand(){
        $('#productIp').hide();
        $('#brandIp').show();
        $('#categoryIp').hide();
    }
    function dateAndProduct(){
        $('#productIp').show();
        $('#brandIp').hide();
        $('#categoryIp').hide();
         $.ajax({
            type: 'POST',
            url: dateProductpath,
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
                //                var myNewChart = new Chart(ctx).Bar(data);
                graphType(data);
            }
        });
    }
    function dateProductandBrand(){
        $('#productIp').show();
        $('#brandIp').show();
        $('#categoryIp').hide();
    }
    
    
    function reportViaDatesOnly(){
        $('#productIp').hide();
        $('#brandIp').hide();
        $('#categoryIp').hide();
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
                //                var myNewChart = new Chart(ctx).Bar(data);
                graphType(data);
            }
        });
    }
    
    
    function dateAndCategory(){
        $('#productIp').hide();
        $('#brandIp').hide();
        $('#categoryIp').show();
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
                //                var myNewChart = new Chart(ctx).Bar(data);
                graphType(data);
            }
        });
    }
    function graphType(data){
        var ctx = document.getElementById("myChart").getContext("2d");
        var type = $("#graphTypeSelect").val();
        if(type == "Bar"){
            var myNewChart = new Chart(ctx).Bar(data);
        }else if(type == "Pie"){
            var myNewChart = new Chart(ctx).Pie(data);
        }else{
            var myNewChart = new Chart(ctx).Line(data);
        }
    }
    $('#generateReport').on('click', function(){
        dateAndProduct();
    });
    
    
    $('#reportViaDatesOnly').on('click', function(){
        reportViaDatesOnly();
    });
});