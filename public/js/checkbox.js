// JavaScript Document
// 

$(document).ready(function(){
    $(".selectall").click(function(){
       var selectopt = $(this).attr('name');
       var options = selectopt.split("_");
       var checked_status = this.checked;
     
       $(".module_"+options[1]).each(function() {
            this.checked = checked_status;
       })
   
  
       
   });
    
});