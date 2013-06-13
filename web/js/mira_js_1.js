$(document).ready(function(){
    
    var viewFlag = true;
    
    $("#switchView").click(function(){
        viewFlag = !viewFlag;
    });
    
    if(viewFlag){
        $("#historyList").hide();
        $("#calendar").show();
    }else{
        $("#historyList").show();
        $("#calendar").hide();
    }
    
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
    };
    
    
    
   
    
    $('#calendar').fullCalendar({
        editable: true,
        
        events: calendarpath,
			
        eventDrop: function(event, delta) {
            alert(event.title + ' was moved ' + delta + ' days\n' +
                '(should probably update your database)');
        },
			
        loading: function(bool) {
            if (bool) $('#loading').show();
            else $('#loading').hide();
        },
        
        eventClick: function(calEvent) {

            alert('Product: ' + calEvent.data['product'] + '\n' + 'Price: ' +calEvent.data['price']);
          
           
            $(this).css('border-color', 'red');

        }
    });
    
});