function setUpload(data) {
    var lab = [];
    var id = [];
    var my = [];
    var appId = [];
    var appMy = [];
    var rjtId = [];
    var rjtMy = [];
        for (var i = 0; i < data.length; i++) {
            lab[i] = (data[i].labels);
            id[i] = data[i].id;
            my[i] = data[i].my;
            appId[i] = data[i].appId;
            appMy[i] = data[i].appId;
            rjtId[i] = data[i].rjtId;
            rjtMy[i] = data[i].rjtMy;
        }
        lab = lab.filter(function( element ) {
           return element !== undefined;
        });
        id = id.filter(function( element ) {
           return element !== undefined;
        });
        my = my.filter(function( element ) {
           return element !== undefined;
        });
        appId = appId.filter(function( element ) {
           return element !== undefined;
        });
        appMy = appMy.filter(function( element ) {
           return element !== undefined;
        });
        rjtId = rjtId.filter(function( element ) {
           return element !== undefined;
        });
        rjtMy = rjtMy.filter(function( element ) {
           return element !== undefined;
        });
        var areaChartData = {
            labels: lab,

          datasets: [
            {
              label: "Indonesia",
              fillColor: "rgb(245, 105, 84)",
              strokeColor: "rgb(245, 105, 84)",
              pointColor: "rgb(245, 105, 84)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: id
            },
            {
              label: "Malaysia",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: my
            }
          ]
        };
        var areaAppChartData = {
            labels: lab,

          datasets: [
            {
              label: "Indonesia",
              fillColor: "rgb(245, 105, 84)",
              strokeColor: "rgb(245, 105, 84)",
              pointColor: "rgb(245, 105, 84)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: appId
            },
            {
              label: "Malaysia",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: appMy
            }
          ]
        };
        var areaRjtChartData = {
            labels: lab,

          datasets: [
            {
              label: "Indonesia",
              fillColor: "rgb(245, 105, 84)",
              strokeColor: "rgb(245, 105, 84)",
              pointColor: "rgb(245, 105, 84)",
              pointStrokeColor: "#c1c7d1",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: rjtId
            },
            {
              label: "Malaysia",
              fillColor: "rgba(60,141,188,0.9)",
              strokeColor: "rgba(60,141,188,0.8)",
              pointColor: "#3b8bba",
              pointStrokeColor: "rgba(60,141,188,1)",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(60,141,188,1)",
              data: rjtMy
            }
          ]
        };

        var areaChartOptions = {
          //Boolean - If we should show the scale at all
          showScale: true,
          //Boolean - Whether grid lines are shown across the chart
          scaleShowGridLines: false,
          //String - Colour of the grid lines
          scaleGridLineColor: "rgba(0,0,0,.05)",
          //Number - Width of the grid lines
          scaleGridLineWidth: 1,
          //Boolean - Whether to show horizontal lines (except X axis)
          scaleShowHorizontalLines: true,
          //Boolean - Whether to show vertical lines (except Y axis)
          scaleShowVerticalLines: true,
          //Boolean - Whether the line is curved between points
          bezierCurve: true,
          //Number - Tension of the bezier curve between points
          bezierCurveTension: 0.3,
          //Boolean - Whether to show a dot for each point
          pointDot: false,
          //Number - Radius of each point dot in pixels
          pointDotRadius: 4,
          //Number - Pixel width of point dot stroke
          pointDotStrokeWidth: 1,
          //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
          pointHitDetectionRadius: 20,
          //Boolean - Whether to show a stroke for datasets
          datasetStroke: true,
          //Number - Pixel width of dataset stroke
          datasetStrokeWidth: 2,
          //Boolean - Whether to fill the dataset with a color
          datasetFill: true,
          //String - A legend template
          legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
          //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
          maintainAspectRatio: true,
          //Boolean - whether to make the chart responsive to window resizing
          responsive: true
        };

        //-------------
        //- LINE CHART -
        //--------------
        // upload receipt chart
        var lineChartCanvas = $("#lineChart").get(0).getContext("2d");
        var lineChart = new Chart(lineChartCanvas);
        var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineChart.Line(areaChartData, lineChartOptions);
        // approved
        var lineAppChartCanvas = $("#lineApproveChart").get(0).getContext("2d");
        var lineAppChart = new Chart(lineAppChartCanvas);
        // var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineAppChart.Line(areaAppChartData, lineChartOptions);
        // rjected
        var lineRjtChartCanvas = $("#lineRejectChart").get(0).getContext("2d");
        var lineRjtChart = new Chart(lineRjtChartCanvas);
        // var lineChartOptions = areaChartOptions;
        lineChartOptions.datasetFill = false;
        lineRjtChart.Line(areaRjtChartData, lineChartOptions);
};
function startChart(selector){
    var div = $("#lineChart");
        url = $(div).data('url');
        $.ajax({
            type: 'GET',
            url : url,
            success: function(results){
                var data = jQuery.parseJSON(results);
                setUpload(data);
            }
        });
}

startChart();
