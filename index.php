
<html>
  <head>



    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">

    <!-- Candidate -->
    var this_candidate ='trump';

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var jsonData = $.ajax({
          url: "getData.php",
          dataType: "json",
          //Provide candidate string for quries
          data: { candidate: this_candidate},
          async: false
          }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
      // Instantiate and draw our chart, passing in some options.

      var pie_options = {
                width: 400,
                height: 240,
                title: 'All Time Sentiment: '+this_candidate
              };

      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data,  pie_options);
    }


<!-- -->
google.charts.setOnLoadCallback(drawChart2);


function drawChart2() {
  var jsonData = $.ajax({
      url: "getDataLineChart0.php",
      dataType: "json",
      //Provide candidate string for quries
      data: { candidate: this_candidate},
      async: false
      }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
      // Add some more options
      var options = {
        title: 'Sentiment past 30 Minutes: '+this_candidate,
        //curveType: 'function',
        legend: { position: 'bottom' }
      };
      //Set and draw
      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
      chart.draw(data, options);
}
<!-- -->





    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
    <!--Div that will hold the line chart-->
    <div id="curve_chart" style="width: 900px; height: 500px"></div>

  </body>
</html>
