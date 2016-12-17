function updateClock (){
		var now = moment().locale('th').format('D MMMM YYYY HH:mm:ss');
	   	$("#clock").html(now);
}
function TimeUpdate(){
	updateClock();
	remaining();
	setTimeout(TimeUpdate,1000)
}
function preloading(){
	$.each($('#UserQueue tr'),function(i,row){
		$(row).find('#within').html('<div class="preloader-wrapper small active">'
	    	+'<div class="spinner-layer spinner-blue-only">'
			      +'<div class="circle-clipper left">'
			       +' <div class="circle"></div>'
			      +'</div><div class="gap-patch">'
			       +' <div class="circle"></div>'
			      +'</div><div class="circle-clipper right">'
			       +' <div class="circle"></div>'
			     +' </div>'
			    +'</div>'
			 +' </div>');
	});

	$.each($('#AllQueue tr'),function(i,row){
		$(row).find('#remaining').html('<div class="preloader-wrapper small active">'
	    	+'<div class="spinner-layer spinner-blue-only">'
			      +'<div class="circle-clipper left">'
			       +' <div class="circle"></div>'
			      +'</div><div class="gap-patch">'
			       +' <div class="circle"></div>'
			      +'</div><div class="circle-clipper right">'
			       +' <div class="circle"></div>'
			     +' </div>'
			    +'</div>'
			 +' </div>');

	});

}

function remaining(){
   var now = new Date().getTime();
   $.each($('#UserQueue tr'),function(i,row){
      var end = $(row).find('td:eq(4)').attr('id');
      $(row).find('#within').countdown(end)
      .on('update.countdown', function(event) {
        var format = '%H:%M:%S';
        if(event.offset.totalDays > 0) {
          format = '%-d day%!d ' + format;
        }
        if(event.offset.weeks > 0) {
          format = '%-w week%!w ' + format;
        }
        $(this).html(event.strftime(format));
      })
      .on('finish.countdown', function(event) {
        $(this).html('Ended').parent().addClass('color orange lighten-4');
      });
   })
   $.each($('#AllQueue tr'),function(i,row){
      var end = $(row).find('td:eq(5)').attr('id');
      var flag = 0;
      if(new Date(end).getTime() < now){ flag = 1; end = $(row).find('td:eq(6)').attr('id'); }
      $(row).find('#remaining').countdown(end)
      .on('update.countdown', function(event) {
        var format = '%H:%M:%S';
        if(event.offset.totalDays > 0) {
          format = '%-d day%!d ' + format;
        }
        if(event.offset.weeks > 0) {
          format = '%-w week%!w ' + format;
        }
        $(this).html(event.strftime(format));
      })
      .on('finish.countdown', function(event) {
      	if(flag){
      		var status = $(this).closest('td').next();
      		status.html("<p class='red-text'>Closed</p>");
      		$(this).html('Ended').parent().addClass('color orange lighten-4');
      	}else{
      		var status = $(this).closest('td').next();
      		status.html("<p class='green-text'>Begin</p>");
      	}
      });
   })
}
$(document).ready(function(){
	preloading();

	$(".button-collapse").sideNav();
	$('.datepicker').pickadate({
		selectMonths: true, // Creates a dropdown to control month
	    selectYears: 100 // Creates a dropdown of 15 years to control year
	});
	TimeUpdate();
});