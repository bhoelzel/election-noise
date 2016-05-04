
<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart','table']});

<!--BEGIN OF DEMOCRAT AREACHART-->
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChartDemo);

    function drawChartDemo() {
      var jsonData = $.ajax({
          url: "predictionDataGetDemocrat.php",//TODO: Change here to test
          dataType: "json",
          //Provide candidate string for quries
          // data: { candidate: this_candidate},
          async: false
          }).responseText;

          // Create our data table out of JSON data loaded from server.
          var data = new google.visualization.DataTable(jsonData);
          // Add some more options
          var options = {
            title: 'Democratic Party Postivie Tweets',
            //curveType: 'function',
            legend: { position: 'bottom' }
          };
          //Set and draw
          var chart = new google.visualization.AreaChart(document.getElementById('curve_chart_demo'));
          chart.draw(data, options);
    }
<!--END OF DEMOCRAT AREACHART-->
<!--BEGIN OF REBLICAN AREACHART -->
google.charts.setOnLoadCallback(drawChartReb);

function drawChartReb() {
  var jsonData = $.ajax({
      url: "predictionDataGetRebulican.php",//TODO: Change here to test
      dataType: "json",
      //Provide candidate string for quries
      // data: { candidate: this_candidate},
      async: false
      }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
      // Add some more options
      var options = {
        title: 'Rebulican Positive Tweets',
        //curveType: 'function',
        legend: { position: 'bottom' }
      };
      //Set and draw
      var chart = new google.visualization.AreaChart(document.getElementById('curve_chart_reb'));
      chart.draw(data, options);
}
<!-- END OF REBULICAN AREACHART-->

<!-- BEGIN OF ALL AREACHART-->
google.charts.setOnLoadCallback(drawChart2);

function drawChart2() {
  var jsonData = $.ajax({
      url: "predictionDataGet.php",//TODO: Change here to test
      dataType: "json",
      //Provide candidate string for quries
      // data: { candidate: this_candidate},
      async: false
      }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
      // Add some more options
      var options = {
        title: 'All Parties Positive Tweets',
        //curveType: 'function',
        legend: { position: 'bottom' }
      };
      //Set and draw
      var chart = new google.visualization.AreaChart(document.getElementById('curve_chart_all'));
      chart.draw(data, options);
}
<!--END OF ALL AREACHART-->
<!--BEGIN HASHTAG BARCHART-->
   google.charts.setOnLoadCallback(drawTopHashTagChart);

   function drawTopHashTagChart() {

     var jsonData = $.ajax({
         url: "getTopHashTagCount.php",
         dataType: "json",
         async: false
         }).responseText;
     var data = new google.visualization.DataTable(jsonData);
     var options = {
       title: "Top Hashtags",
       width: 600,
       height: 400,
       bar: {groupWidth: "90%"},
       legend: { position: "none" },
     };
     var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
     chart.draw(data, options);
 }
<!--END OF HASHTAG BARCHART-->


<!--BEGIN OF TABLE-->

   google.charts.setOnLoadCallback(drawTable);

   function drawTable() {

     var jsonData = $.ajax({
         url: "getPredictionTableData.php",
         dataType: "json",
         async: false
         }).responseText;

     var data = new google.visualization.DataTable(jsonData);
     var options = {showRowNumber: false, width: '50%', height: '25%'};
     var table = new google.visualization.Table(document.getElementById('table_div'));
     table.draw(data, options);

 }
<!--ENF OF TABLE -->



    </script>
  </head>

  <body>
    <!--Div for the hashtag chart -->
    <div id="barchart_values" style="width: 900px; height: 300px;"></div>
    <!--Div that will hold the pie chart-->
    <div id="curve_chart_demo" style="width: 900px; height: 500px"></div>
    <!--Div that will hold the pie chart-->
    <div id="curve_chart_reb" style="width: 900px; height: 500px"></div>
    <!--Div that will hold the line chart-->
    <div id="curve_chart_all" style="width: 900px; height: 500px"></div>

    <div id="table_div"></div>
<footer id="footer" class="container">
          <div class="row 200%">
            <div class="12u">

              <!-- About -->
                <section>
                  <h2 class="major"><span>What's this about?</span></h2>
                  <p style="font-size:20px">
                    This web site is about US Election 2016, based on Twitter Data Sentiment analysis.  
                </p>
                </section>

            </div>
          </div>
          
            <div id="copyright">
              <ul class="menu">
                <li>&copy; Untitled. All rights reserved</li><li>Design: <a href="#">DRS</a></li>
              </ul>
            </div>

        </footer>

  </body>
</html>
