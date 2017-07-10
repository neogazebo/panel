$(function() {
	// this function to hide flash message in 3 second
	function timeout() {
		setTimeout(function() {
			$(".alert-dismissable").hide();
		}, 10000);
	};
	timeout();
        
        $('[data-toggle="tooltip"]').tooltip();
        
	var customAlert = function(status,message) {
		var html = "<div class='row'><div class='col-xs-12 col-sm-12 col-lg-12'>";
		if(status == 'success') {
			html += "<div class='alert alert-dismissable alert-success'>";
			html += message;
			html += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			html += "</div>";
		} else if(status == 'error') {
			html += "<div class='alert alert-dismissable alert-danger'>";
			html += message;
			html += "<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>";
			html += "</div>";
		}
		html += "</div></div>";
		$('.content-wrapper').prepend(html);
		timeout();
	}

	// this function to show render modal
	$('.modalButton').on('click',function() {
		$('#modal').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
	});

	// this function to generate role auto maticaly
	$('.ajaxRequest').on('click',function() {
		var Url = $(this).attr('value');
			reload = $(this).data('key');
			dom = $('body');
		swal({
			  title: "Synchronize role",
			  text: "",
			  type: "info",
			  showCancelButton: true,
			  closeOnCancel: true,
			  closeOnConfirm: false,
			  showLoaderOnConfirm: true,
		}, function() {
			$.get(Url, function(results) {
				// data = JSON.parse(results);
				// if (data.status = 'success') {
				// 	dom.load(reload);
				// 	swal("Syncronized", "Successed", "success");
				// }else{
				// 	swal("Syncronized","Failed!", "error");
				// }
			})
		});
	});

	$('.deleteBtn').on('click', function() {
		var Url = $(this).attr('value'),
			name = Url.match(/\w+$/)[0],
			element = $(this).closest('tr');

		swal({
			title: "Delete " + name + " role",
			text: "Are you sure want to delete this?",
			type: "info",
			showCancelButton: true,
			closeOnCancel: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
			$.get(Url, function(results) {})
		});
	});

	$('.deleteThis').on('click', function() {
		var Url = $(this).attr('value'),
			name = Url.match(/\w+$/)[0],
			element = $(this).closest('tr'),
			title = $(this).attr('data-title');

		swal({
			title: title,
			text: "Are you sure want to delete this?",
			type: "info",
			showCancelButton: true,
			closeOnCancel: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
			$.get(Url, function(results) {})
		});
	});

	$('.gotohell').on('click',function(e) {
		e.preventDefault();
		var Url = $(this).attr('value');
			element = $(this).closest('tr');
			confirm = $(this).data('text');
			title = $(this).data('title');
		swal({
			title: title,
			text: confirm,
			type: "info",
			showCancelButton: true,
			closeOnCancel: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
			$.post(Url, function(data) {
				if(!data.error) {
	                $('.modal').modal('hide');
	                swal({
	                    title: 'Success',   
	                    timer: 1000,
	                    text: 'Data is successfully saved',
	                    type: "success",
	                    showConfirmButton: false
	                },
	                function() {   
	                    window.location.reload();
	                });
	            } else {
	                if(data.error == 1000) {
	                    swal({
	                        title: 'System Error',   
	                        html: true,
	                        text: data.message,
	                        type: "error",
	                    });
	                }
	            }
			})
		});
	});


    $('#search').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
        }
    });

    $('#addChild').on('submit', function(event) {
		// var permission = $('#search_to').val();
		thisRole = $(this).data('key');
    	swal({
			title: "Update Permission "+thisRole,
			text: "Are you sure want to do this?",
			type: "info",
			showCancelButton: true,
			closeOnCancel: true,
			closeOnConfirm: false,
			showLoaderOnConfirm: true,
		}, function() {
	    	$.ajax({
    			type: 'POST',
    			url: $('#addChild').attr('action'),
    			data: $('#addChild').serialize(),
    			success: function(results) {
    	// 			data = JSON.parse(results);
					// if (data.status == 'success') {
					// 	$('body').load('detail?name='+thisRole);
					// 	swal.close();
					// 	timeout();
					// 	// message = "Add new Item successed!";
					// 	// customAlert(data.status,message);
					// }else{
					// 	swal.close();
					// 	timeout();
					// 	// message = "Add new Item failed!";
					// 	// customAlert(data.status,message);
					// }
    			}
    		});
		});
    	event.preventDefault();
    });

    // css for parent role on page role
	$('#search>option , #search_to>option').each(function(){
		var id = $(this).text();
		if (id.substr(id.length - 1) === '*') {
			$(this).css({'font-weight':'bold'});
		}
	});

	// action for blocking page modal
	$('#tester').on('click',function(){
		alert('oke');
	})

	$('.refreshParent').on('click',function(){
		window.opener.location.reload(true);
		window.close();
	});

	// $(".select2").select2();

	//Date range picker
	var start = moment();
	var end = moment();
    $('#the_daterange').daterangepicker(
		{

	      	startDate: start,
	      	endDate: end,
			separator : ' to ',
			format: 'YYYY-MM-DD',
	      	ranges: {
	            'Today': [moment(), moment()],
	            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	            'This Month': [moment().startOf('month'), moment().endOf('month')],
	            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	      	}
        },
	    function (start, end) {
	      $('#reportrange span').html(start.format('YYYY D, MMMM') + ' to ' + end.format('YYYY D, MMMM'));
	    }
	);

	// disabled button enter on form submit
    function stopRKey(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
        if ((evt.keyCode == 13) && (node.type=='text'))  {return false;}
    }
    document.onkeypress = stopRKey;

});

function topFour(data) {
	var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
	var pieChart = new Chart(pieChartCanvas);
	var PieData = data;
	var pieOptions = {
      //Boolean - Whether we should show a stroke on each segment
      segmentShowStroke: true,
      //String - The colour of each segment stroke
      segmentStrokeColor: '#fff',
      //Number - The width of each segment stroke
      segmentStrokeWidth: 2,
      //Number - The percentage of the chart that we cut out of the middle
      percentageInnerCutout: 50, // This is 0 for Pie charts
      //Number - Amount of animation steps
      animationSteps: 100,
      //String - Animation easing effect
      animationEasing: 'easeOutBounce',
      //Boolean - Whether we animate the rotation of the Doughnut
      animateRotate: true,
      //Boolean - Whether we animate scaling the Doughnut from the centre
      animateScale: false,
      //Boolean - whether to make the chart responsive to window resizing
      responsive: true,
      // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
      maintainAspectRatio: true,
      //String - A legend template
      legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>'
    };
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    pieChart.Doughnut(PieData, pieOptions);
  };

  function startChart(selector){
	  var div = $('#pieChart');
		 url = $(div).data('url');
		 id = $(div).data('key');
		 $.ajax({
			 type: 'POST',
			 url: url,
			 data: {
				 id : id,
				 data : selector ? selector : ''
			 },success: function(results) {
			 var data = jQuery.parseJSON(results);
			 var html = "<ul class='nav nav-stacked'>";
			 for (var i = 0; i < data.length; i++) {
				 var total = data[i].currency+'. '+(data[i].total)+data[i].k;
				 if (data[i].total == 0 ) {
					 total = 'N/A';
				 }
				 $("#testing").text(total);
				 html += "<li><a href='#'>"+data[i].label+"<span class='pull-right badge ' style='background-color:"+data[i].color+"'>"+data[i].currency+'. '+data[i].amount+data[i].k+"</span></a></li>";
			 }
			 html += "</ul>";
			 $('.chart-notes').html(html);
			 topFour(data);
			 $(".overlay").hide();
		   }
	   });
  }
  	if ($('#pieChart').length > 0) {
		startChart()
	}
$("#filter_chart").change(function(){
	$(".overlay").show();
	var selector = $(this).val();
	startChart(selector);
});

function confirmBlocked(){
    $(".confirmBlocked").on('click',function(){
        var user = $('.profile-username').text();
        var urlVal = $(this).val();
        var text = $(this).text();
        var param = $(this).data('key');
        swal({
            title: "Are you sure?",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        },
        function(){
            blocked(user,urlVal,text,param);
        });
    });
}
confirmBlocked();
function blocked(user,urlVal,text,param){
    $.get(urlVal,{param : param}).done(function(results){
        var data = jQuery.parseJSON(results);
        var str = data.action;
        str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
            return letter.toUpperCase();
        });
        if (data.status = 'success') {
            swal("Changed!", user+" status changed", "success");
        } else {
            swal("Changed!", "Process Error", "error");
        }
    });
}



$('#existing_merchant_form').on('submit',function(e){
	e.preventDefault();
	var form_data = new FormData(this);
		_url = $(this).attr('action');
		$.ajax({
			type : 'POST',
			url: _url,
			data: form_data,
	        processData: false,
	        contentType: false,
	        beforeSend: function() {
	            $('.modal-dialog').waitMe({
	                effect : 'stretch',
	                text : 'Saving...',
	                bg : 'rgba(255,255,255,0.7)',
	                color : '#000',
	                sizeW : '',
	                sizeH : ''
	            });
	        },
	        complete: function() {
	            $('.modal-dialog').waitMe('hide');
	        },
	        success: function(data) {
	            if(!data.error) {
	                $('#existing_merchant_form').modal('hide');

	                swal({
	                    title: 'Success',   
	                    html: true,
	                    text: 'Data is successfully saved',
	                    type: "success",
	                },
	                function() {   
	                    window.location.reload();
	                });
	            } else {
	                var msg = '';

	                if(data.error == 1000) {
	                    swal({
	                        title: 'System Error',   
	                        html: true,
	                        text: data.message,
	                        type: "error",
	                    });
	                }
	            }
	        }
	    });
});