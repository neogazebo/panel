$(function() {
	// this function to hide flash message in 3 second
	function timeout() {
		setTimeout(function() {
			$(".alert-dismissable").hide();
		}, 5000);
	};
	timeout();

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
			$.get(Url,function(results) {
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

	$('.deleteBtn').on('click',function() {
		var Url = $(this).attr('value');
			name = Url.match(/\w+$/)[0];
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
			$.get(Url, function(results) {
				// data = JSON.parse(results);
				// if (data.status = 'success') {
					// element.remove();
					// location.reload();
					// swal.close();
					// message = "Delete " + name + " role successed!";
					// customAlert(data.status,message);
					// element.remove();
				// } else {
					// swal.close();
					// element.remove();
					// message = "Delete " + name + " role failed!";
					// customAlert('error', message);
				// }
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

	$(".select2").select2();

	//Date range picker
    $('#the_daterange').daterangepicker(
		{
			separator : ' to ',
			format: 'YYYY-MM-DD',
	      	ranges: {
	            'Today': [moment(), moment()],
	            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	            'This Month': [moment().startOf('month'), moment().endOf('month')],
	            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	      	},
	      	startDate: moment().subtract(29, 'days'),
	      	endDate: moment()
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
  function startChart(selector = null){
	  var div = $('#pieChart');
		 url = $(div).data('url');
		 id = $(div).data('key');
		 $.ajax({
			 type: 'POST',
			 url: url,
			 data: {
				 id : id,
				 data : selector
			 },success: function(results) {
			 var data = jQuery.parseJSON(results);
			 var html = "<ul class='nav nav-stacked'>";
			 for (var i = 0; i < data.length; i++) {

				 html += "<li><a href='#'>"+data[i].label+"<span class='pull-right badge ' style='background-color:"+data[i].color+"'>"+data[i].value+"</span></a></li>";
			 }
			 html += "</ul>";
			 $('.chart-notes').html(html);
			 topFour(data);
			 $(".overlay").css('display','none');
		   }
	   });
  }
  	if ($('#pieChart').length > 0) {
		startChart()
	}
$("#filter_chart").change(function(){
	$(".overlay").css('display','block');
	var selector = $(this).val();
	startChart(selector);
});
