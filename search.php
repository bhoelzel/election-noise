<!DOCTYPE HTML>
<?php
require_once 'app/init.php';

if(isset($_GET['query_set'])){

  $query_data = $_GET['query_set'];
  $query_result = $client->search([
    'index' => 'sentiment*',
    'type' => 'tweet',
    'body' => [
      'size' => 10,
      'query' => [
        'bool' => [
          'should' => [
            'match' => ['message' => $query_data],
          //  'match' => ['hashtags' => $query_data]
          ]
        ]
      ]
    ]
  ]);
   
  if($query_result['hits']['total'] >= 1){
    $hits = $query_result['hits']['hits'];
    // echo $query_result['hits']['total'];
  }
   //echo '<pre>', print_r($query_result), '</pre>';
}
?>
<html>
  <head>

    <!--Load the AJAX API-->
   
    <title>Search</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
     <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    
    // <!-- Candidate -->
    var this_candidate = "<?php echo $_GET['query_set']; ?>";
    var time_frame =60;

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var jsonData = $.ajax({
          url: "getData.php",
          dataType: "json",
          //Provide candidate string for quries
          data: { candidate: this_candidate,
                  time: time_frame
                },
          async: false
          }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
      // Instantiate and draw our chart, passing in some options.

      var pie_options = {
                width: 400,
                height: 240,
                title: 'All Time Sentiment: '+this_candidate,
                backgroundColor: '#f2f5f3',
                colors: ['#0000FF', '#FFA500', '#FF0000'],
                is3D:  true,
              };

      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data,  pie_options);
    }


<!-- -->
google.charts.setOnLoadCallback(drawChart2);


function drawChart2() {
  var jsonData = $.ajax({
      url: "getDataLineChart3Line.php",
      dataType: "json",
      //Provide candidate string for quries
      data: { candidate: this_candidate,
              time: time_frame
      },
      async: false
      }).responseText;

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
      // Add some more options
      var options = {
        title: 'Sentiment past '+time_frame+ ' Minutes: '+this_candidate,
        //curveType: 'function',
        backgroundColor: '#f2f5f3',
        legend: { position: 'bottom' }
      };
      //Set and draw
      var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
      chart.draw(data, options);
}

    </script>

  </head>
  <body>
    
    <div id="page-wrapper">

      <!-- Header -->
        <header id="header">
          <div class="logo container">
            <div>
              <p>Search for any keyword, you will get the sentiment and top 10 tweets for that keyword.</p>
            </div>
          </div>
        </header>

      <!-- Nav -->
        <nav id="nav">
          <ul>
            <li class="current"><a href="index.html">Home</a></li>
            <li>
              <a href="#">CANDIDATES</a>
              <ul>
                <li><a href="trump.html">Donald Trump</a></li>
                <li><a href="clinton.html">Hilary Clinton</a></li>
                <li>
                  <a href="ted.html">Ted Cruz</a>
                  
                </li>
                <li><a href="barne.html">Barnie Sandres</a></li>
              </ul>
            </li>
            <li><a href="debates.html">DEBATES</a></li>
            <li><a href="search.php">SEARCH</a></li>
            <li><a href="predict.php">PREDICTION</a></li>
          </ul>
          
        </nav>

      <!-- Main -->
      
                      <section>
                        <!-- <h3>More intriguing information</h3>
                         --><p>
  <form id="fm" action="search.php" method="get" autocomplete="off">
    <label>
      Search for something
      <input type="text" name="query_set" id="a" placeholder="Search for sentiment" >
    </label>
    
      <input type="submit" value="Search" >
  </form>
<div id="search">



<!-- <div class=""> -->


<?php

//I know, $hits , thats funny!
if(isset($hits)){
//
  echo "<mark>This query returns the top ".
              sizeof($hits).
              " results out of ".
              $query_result['hits']['total'].
              " total hits."
              ." NOTE: This is only the message.</mark>";
  $count = 0;
//
  foreach($hits as $hit){
?>
  <div class="result">
    <?php echo ++$count.":\t\t".$hit['_source']['message']?>
  </div>

<?php
  }
}
?>                          
</p>
</section>
                    </article>

                </div>
              </div>
            </div>
          
  <!-- <body> -->
    <!--Div that will hold the pie chart-->
    <div id="chart_div" align="center" ></div>
    <!--Div that will hold the line chart-->
    <div id="curve_chart" style="width: 100%; height: 500px"></div>
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


