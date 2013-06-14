$(document).ready(function(){
    
    //    alert($('#hiddenMonth').val());
    var price = new Array();
    var products = new Array();
    var boughtAt = new Date(new Array());
    var month;
    var monthlyData;
    
    var months = {
        January:"1", 
        February:"2", 
        March:"3", 
        April:"4", 
        May:"5", 
        June:"6", 
        July:"7", 
        August:"8", 
        September:"9", 
        October:"10", 
        November:"11", 
        December:"12"
    }
    
    monthlyData = JSON.parse($('#monthlyData').val());
        for(var i in monthlyData){
            price[i] = monthlyData[i].productPrice;
            products[i] = monthlyData[i].productName;
            boughtAt[i] = monthlyData[i].boughtAt.toString();
        }
        //alert(monthlyData);

//    var dateClicked;
//      $('#calendar').fullCalendar({
//      dayClick: function(date){
//        dateClicked = $.fullCalendar.formatDate(date, "yyyy-MM-dd");
//    var t = Date.parse(dateClicked);
//                var d = new Date(t);
//                alert(d.getDay());
//                alert(d.getMonth());
//     alert(dateClicked);
//      alert(dateClicked);
//    },

//        var data = {
            var events= new Array();
//        };
        monthlyData.forEach(function(element,i){
            if(i <= monthlyData.length){
                events.push({
                    title: price[i].toString(),
                    start: boughtAt[i]
                });
            }
        });
    var calendar = $('#calendar');
   // calendar.fullCalendar('option', 'aspectRatio', 13);
   //  calendar.fullCalendar(data);
    calendar.fullCalendar({
        events: events,
        viewDisplay: function(view) {
            alert("d");
            month = (view.title).split(" ");
            month = month[0];
            var dat = new Date('1 ' + month.toString() + ' 1999');
            var selectedMonth = (dat.getMonth()+1);
            $.ajax({
                type: 'POST',
                url: calendarpath,
                data: {
                    startDate: selectedMonth
                       
                },
                success: function(response){                
                    monthlyData =  JSON.parse(response);
                    for(var i in monthlyData){
                        price[i] = monthlyData[i].productPrice;
                        products[i] = monthlyData[i].productName;
                        boughtAt[i] = monthlyData[i].boughtAt.toString();
                    }

                  // calendar.empty();
                    //calendar.fullCalendar(data);
                    
                }
            })
        }
    });
});