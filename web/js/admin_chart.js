$(document).ready(function(){
    var price = new Array();
    var chartproducts = new Array();
    var categorySources = new Array();
    var brandSources = new Array();
    var productSources = new Array();
    var productsCategory = new Array();
    var brandsCategory = new Array();
    var hiddenCategory = JSON.parse($("#hiddenCategory").val());
    var hiddenBrand = JSON.parse($("#hiddenBrand").val());
    var hiddenProduct = JSON.parse($("#hiddenProduct").val());
    var categoryHash={};
    var productHash={};
    var brandHash={};
    var categoryID;
    var userChartStartDate;
    var userChartEndDate;

    for(i = 0; i < hiddenCategory.length; i++){
        categorySources[i] = hiddenCategory[i].name;
    }
    
    for(i = 0; i < hiddenBrand.length; i++){
        brandSources[i] = hiddenBrand[i].name;
    }
    
    for(i = 0; i < hiddenProduct.length; i++){
        productSources[i] = hiddenProduct[i].name;
    }
    
    hiddenCategory.forEach(function(element,i){
        if(i <= hiddenCategory.length){
            categoryHash[hiddenCategory[i].name]= (hiddenCategory[i].id)
        }
    });
    
    hiddenProduct.forEach(function(element,i){
        if(i <= hiddenProduct.length){
            productHash[hiddenProduct[i].name]= (hiddenProduct[i].id)
        }
    });
    
    hiddenBrand.forEach(function(element,i){
        if(i <= hiddenBrand.length){
            brandHash[hiddenBrand[i].name]= (hiddenBrand[i].id)
        }
    });
   
    function getRandomColour() {
        var color = '';
        while (!color.match(/(#[c-e].)([e-f][a-f])([9-c].)/)) {
            color = '#' + Math.floor(Math.random() * (Math.pow(16,6))).toString(16);
        }
        return color;
    }
   
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
    
   
    
    var filterSelect =  $("#filterSelect");
    filterSelect.on('change', function(){
        if(filterSelect.val() == 1){    
            $('#filters').empty().append('<li>Category: <input type="text" id="categoryIP"></li>');
            $("#categoryIP").autocomplete({
                source: categorySources
            });
        }else if(filterSelect.val() == 2){
            $('#filters').empty().append('<li>Brand: <input type="text" id="brandIP"></li>');
            $("#brandIP").autocomplete({
                source: brandSources
            });
        }else if(filterSelect.val() == 3){
            $('#filters').empty().append('<li>Product: <input type="text" id="productIP"></li>');
           
            $("#productIP").autocomplete({
                source: productSources
            });
           
        }else if(filterSelect.val() == 4){
            $('#filters').empty().append('<li>Brand: <input type="text" id="brandIP"></li><li>Product: <input type="text" id="productIP"></li>');
           
            for(i = 0; i < hiddenBrand.length; i++){
                brandSources[i] = hiddenBrand[i].name;
            }
    
            for(i = 0; i < hiddenProduct.length; i++){
                productSources[i] = hiddenProduct[i].name;
            }
            
            $("#brandIP").autocomplete({
                source: brandSources
            });
            $("#productIP").autocomplete({
                source: productSources
            });
            
        }else if(filterSelect.val() == 5){
            $('#filters').empty().append('<li>Category: <input type="text" id="categoryIP"></li><li>Brand: <input type="text" id="brandIP"></li><li>Product: <input type="text" id="productIP"></li>');
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
                        var pb = JSON.parse(response);
                        for(var b in pb.brands){    
                            brandsCategory[b] = pb.brands[b];
                        }
                        for(var b in pb.products){    
                            productsCategory[b] = pb.products[b];
                        }
                        $("#brandIP").autocomplete({
                            source: brandsCategory
                        });
                        $("#productIP").autocomplete({
                            source: productsCategory
                        });
                     
                    }
                });
            });
            
            
           
        }
    });
    
     
    function dateAndBrand(){
        $.ajax({
            type: 'POST',
            url: dateBrandpath,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                brandID: (brandHash[$("#brandIP").val()])
            },
            success: function(response) {
                plots = JSON.parse(response);
                console.log(plots);
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
                
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
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        labels: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    function dateAndProduct(){
        $.ajax({
            type: 'POST',
            url: dateProductpath,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                productID: (productHash[$("#productIP").val()])
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
                plots = JSON.parse(response);
                
                console.log(plots);
                for(var i in plots){    
                    price[i] = parseInt(plots[i].price);
                    chartproducts[i] = plots[i].name.date;
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
                
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        labels: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    function dateProductandBrand(){
        $.ajax({
            type: 'POST',
            url: dateProductpath,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                productID: (productHash[$("#productIP").val()]),
                brandID: (brandHash[$("#brandIP").val()])
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
                chartproducts[0] = " ";
                plots = JSON.parse(response);
                console.log(plots);
                var j=1;
                for(var i = 0; i<plots.length;  i++){
                    //                for(var i in plots){    
                    price[j] = parseInt(plots[i].price);
                    chartproducts[j] = plots[i].name.date;
                    j++;
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
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        label: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    function CategoryBrandProduct(){
        $.ajax({
            type: 'POST',
            url: CategoryBrandProductpath,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                productID: (productHash[$("#productIP").val()]),
                categoryID: (categoryHash[$("#categoryIP").val()]),
                brandID: (brandHash[$("#brandIP").val()])
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
                plots = JSON.parse(response);
                
                console.log(plots);
                for(var i in plots){    
                    price[i] = parseInt(plots[i].price);
                    chartproducts[i] = plots[i].name.date;
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
                
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        labels: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    function Region(r){
        var urlr;
        if(r == 1) {
            urlr = CategoryRegionpath;
        }else if(r == 2) {
            urlr = BrandRegionpath;
        }else if(r == 3) {
            urlr = ProductRegionpath;
        }else if(r == 4) {
            urlr = BrandProductRegionpath;
        }else if(r == 5) {
            urlr = CategoryBrandProductRegionpath;
        }   
        $.ajax({
            type: 'POST',
            url: urlr,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                productID: (productHash[$("#productIP").val()]),
                categoryID: (categoryHash[$("#categoryIP").val()]),
                brandID: (brandHash[$("#brandIP").val()])
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
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
                
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        labels: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    function Country(r){
        var urlr;
        if(r == 1) {
            urlr = CategoryCountrypath;
        }else if(r == 2) {
            urlr = BrandCountrypath;
        }else if(r == 3) {
            urlr = ProductCountrypath;
        }else if(r == 4) {
            urlr = BrandProductCountrypath;
        }else if(r == 5) {
            urlr = CategoryBrandProductCountrynpath;
        }   
        $.ajax({
            type: 'POST',
            url: urlr,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                productID: (productHash[$("#productIP").val()]),
                categoryID: (categoryHash[$("#categoryIP").val()]),
                brandID: (brandHash[$("#brandIP").val()])
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
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
                
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        label: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    function City(r){
        var urlr;
        if(r == 1) {
            urlr = CategoryCitypath;
        }else if(r == 2) {
            urlr = BrandCitypath;
        }else if(r == 3) {
            urlr = ProductCitypath;
        }else if(r == 4) {
            urlr = BrandProductCitypath;
        }else if(r == 5) {
            urlr = CategoryBrandProductCitynpath;
        }   
        $.ajax({
            type: 'POST',
            url: urlr,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,
                productID: (productHash[$("#productIP").val()]),
                categoryID: (categoryHash[$("#categoryIP").val()]),
                brandID: (brandHash[$("#brandIP").val()])
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
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
                
                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        labels: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
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
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
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

                var dataCurve = []
                for(i=0; i<price.length; i++){
                    dataCurve.push( {
                        value: price[i],
                        label: chartproducts[i],
                        color: getRandomColour()
                    });		
                }
                graphType(data, dataCurve);
            }
        });
    }
    
    
    function dateAndCategory(){
        $.ajax({
            type: 'POST',
            url: dateCategorypath,
            datatype: 'json',
            data: {
                startDate: userChartStartDate, 
                endDate: userChartEndDate,              
                categoryID: categoryHash[$("#categoryIP").val()]
            },
            success: function(response) {
                price.length = 0;
                chartproducts.length = 0;
                price[0] = 0;
                plots = JSON.parse(response);
                console.log(plots);
                for(var i in plots){    
                    price[i] = parseInt(plots[i].price);
                    chartproducts[i] = plots[i].products;
                }
                var data = {
                    labels : chartproducts,
                    datasets : [
                    {
                        fillColor : "rgba(220,220,220,0.5)",
                        strokeColor : "rgba(220,55,220,4)",
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
    function graphType(data, dataCurve){
        var ctx = document.getElementById("myChart").getContext("2d");
        var type = $("#graphTypeSelect").val();
        if(type == "Bar"){
            var myNewChart = new Chart(ctx).Bar(data);
        }else if(type == "Pie"){
            var myNewChart = new Chart(ctx).Pie(dataCurve);
        }else if(type == "Doughnut"){
            var myNewChart = new Chart(ctx).Doughnut(dataCurve);   
        }else{
            var myNewChart = new Chart(ctx).Line(data);
        }
    }
    
    $('#generateReport').on('click', function(){
        if(filterSelect.val() == 1){ 
            dateAndCategory();
        }else if(filterSelect.val() == 2){
            dateAndBrand();
        }else if(filterSelect.val() == 3){
            dateAndProduct();
        }else if(filterSelect.val() == 4){
            dateProductandBrand();
        }else if(filterSelect.val() == 5){
            CategoryBrandProduct();
        }
    });
    
    $('#region').on('click', function(){
        if(filterSelect.val() == 1){ 
            Region(1);
        }else if(filterSelect.val() == 2){
            Region(2);
        }else if(filterSelect.val() == 3){
            Region(3);
        }else if(filterSelect.val() == 4){
            Region(4);
        }else if(filterSelect.val() == 5){
            Region(5);
        }
       
    });   
    
    $('#country').on('click', function(){
        if(filterSelect.val() == 1){ 
            Country(1);
        }else if(filterSelect.val() == 2){
            Country(2);
        }else if(filterSelect.val() == 3){
            Country(3);
        }else if(filterSelect.val() == 4){
            Country(4);
        }else if(filterSelect.val() == 5){
            Country(5);
        }
       
    });
    
    $('#city').on('click', function(){
        if(filterSelect.val() == 1){ 
            City(1);
        }else if(filterSelect.val() == 2){
            City(2);
        }else if(filterSelect.val() == 3){
            City(3);
        }else if(filterSelect.val() == 4){
            City(4);
        }else if(filterSelect.val() == 5){
            City(5);
        }
       
    }); 
     
    $('#reportViaDatesOnly').on('click', function(){
        reportViaDatesOnly();
    });
});