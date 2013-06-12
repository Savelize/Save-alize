$(document).ready(function(){
    
    //    alert($('#hiddenMonth').val());
    var price = new Array();
    var products = new Array();
    var boughtAt = new Date(new Array());
    
    var monthlyData = JSON.parse($('#monthlyData').val());
    for(var i in monthlyData){
        price[i] = monthlyData[i].productPrice;
        products[i] = monthlyData[i].productName;
        boughtAt[i] = monthlyData[i].boughtAt.toString();
    }

    var dateClicked;
    //  $('#calendar').fullCalendar({
    //  dayClick: function(date){
    //    dateClicked = $.fullCalendar.formatDate(date, "yyyy-MM-dd");
    //var t = Date.parse(dateClicked);
    //            var d = new Date(t);
    //            alert(d.getDay());
    //            alert(d.getMonth());
    // alert(dateClicked);
    //  alert(dateClicked);
    //},

    var data = {
        events: []
    };
    monthlyData.forEach(function(element,i){
        if(i <= monthlyData.length){
            data.events.push({
                title: products[i],
                start: boughtAt[i]
            });
        }
    });

    $('#calendar').fullCalendar('option', 'aspectRatio', 13);
    $('#calendar').fullCalendar(data);
    
});