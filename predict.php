<!DOCTYPE HTML>

<html>
  <head>

    <!--Load the AJAX API-->

    <title>Analysis</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->


    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>




    <script type="text/javascript">
    var default_time=90;
    var BEG_DATE=new Date('2016-02-17');
    var BEG_DAYS_BACK = getDaysBack(BEG_DATE, Date.now());

    //Function to return the diffrenece between two suplied Date Objects
    function getDaysBack(date1,date2){
      var diffDays = parseInt((date2 - date1) / (1000 * 60 * 60 * 24));
      return diffDays;
    }
    //Interesting protptyping
    function formatDate(aDate) {
      var yyyy = aDate.getFullYear().toString();
      var mm = (aDate.getMonth()+1).toString(); // getMonth() is zero-based
      var dd  = aDate.getDate().toString();
      //var hh  = aDate.getHours().toString();
      return yyyy +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ (dd[1]?dd:"0"+dd[0]);//+" "+ (hh[1]?hh:"0"+hh[0]); // padding
   };

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart','table']});
    <!--BEGIN OF DEMOCRAT AREACHART-->
    // // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChartDemo);

      function drawChartDemo() {
        var jsonData = $.ajax({
          url: "predictionDataGetDemocrat.php",//TODO: Change here to test
          dataType: "json",
          data: { 'time' : BEG_DAYS_BACK.toString()},
          success: function(jsonData){
            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);
            // Add some more options
            var options = {
              title: 'Democratic Party Postivie Tweets',
              backgroundColor: '#f2f5f3',
              trendlines: { 0: {type: 'polynomial'},
                            1: {type: 'polynomial'}},
              legend: { position: 'bottom' }
          };
          //Set and draw
          var chart = new google.visualization.AreaChart(document.getElementById('curve_chart_demo'));
          chart.draw(data, options);
        }
      });
    }






  <!--END OF DEMOCRAT AREACHART-->
  <!--BEGIN OF REBLICAN AREACHART -->
  google.charts.setOnLoadCallback(drawChartReb);

  function drawChartReb() {
  var jsonData = $.ajax({
      url: "predictionDataGetRebulican.php",//TODO: Change here to test
      dataType: "json",
      data: { 'time' : BEG_DAYS_BACK.toString()},
      success: function(json){

        var data = new google.visualization.DataTable(json);
        // Create our data table out of JSON data loaded from server.
        // var data = new google.visualization.DataTable(jsonData);
        // Add some more options
        var options = {
          title: 'Rebulican Positive Tweets',
          backgroundColor: '#f2f5f3',
          trendlines: { 0: {type: 'polynomial'},
                        1: {type: 'polynomial'}},
          legend: { position: 'bottom' }
        };
        //Set and draw
        var chart = new google.visualization.AreaChart(document.getElementById('curve_chart_reb'));
        chart.draw(data, options);
      }


      //Provide candidate string for quries
      // data: { candidate: this_candidate},
      // async: false
    });//.responseText;


  }
  <!-- END OF REBULICAN AREACHART-->

  <!-- BEGIN OF ALL AREACHART-->
  google.charts.setOnLoadCallback(drawChart2);

  function drawChart2() {
  var jsonData = $.ajax({
      url: "predictionDataGet.php",//TODO: Change here to test
      dataType: "json",
      data: { 'time' : BEG_DAYS_BACK.toString()},
      success: function(json){

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(json);
      // Add some more options
      var options = {
        title: 'All Parties Positive Tweets',
        backgroundColor: '#f2f5f3',
        trendlines: { 0: {type: 'polynomial'},
                      1: {type: 'polynomial'},
                      2: {type: 'polynomial'},
                      3: {type: 'polynomial'}},
        //curveType: 'function',
        legend: { position: 'bottom' }
      };
      //Set and draw
      var chart = new google.visualization.AreaChart(document.getElementById('curve_chart_all'));
      chart.draw(data, options);
        }
      });


  }
  <!--END OF ALL AREACHART-->
  <!--BEGIN HASHTAG BARCHART-->
   google.charts.setOnLoadCallback(drawTopHashTagChart);

   function drawTopHashTagChart() {

     var jsonData = $.ajax({
         url: "getTopHashTagCount.php",
         dataType: "json",
         data: { 'time' : BEG_DAYS_BACK.toString()},
         success: function(jsonData){
           var data = new google.visualization.DataTable(jsonData);
           var options = {
             title: "Top Hashtags",
             width: 600,
             height: 400,
             bar: {groupWidth: "90%"},
             backgroundColor: '#f2f5f3',
             legend: { position: "none" },
           };
           var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
           chart.draw(data, options);
         }
         });

  }
  <!--END OF HASHTAG BARCHART-->


  <!--BEGIN OF TABLE-->

   google.charts.setOnLoadCallback(drawTable);

   function drawTable() {

     var jsonData = $.ajax({
         url: "getPredictionTableData.php",
         dataType: "json",
         data: { 'time' : BEG_DAYS_BACK.toString()},
         success: function(jsonData){
           var data = new google.visualization.DataTable(jsonData);
           var options = {showRowNumber: false, width: '50%', height: '25%'};
           var table = new google.visualization.Table(document.getElementById('table_div'));
           table.draw(data, options);

         }
       });



  }
  <!--ENF OF TABLE -->


  <!--BEGIN OF DELEGATE COUNT TABLE REB-->

   google.charts.setOnLoadCallback(drawRebResTable);

   function drawRebResTable() {
           var data = new google.visualization.arrayToDataTable([
              ['State','Date','Delegates','Trump','Cruz','Delegate Count Winner'],
              ['Rhode Island',new Date(2016,3,26),19,12,2,'Trump'],
              ['Pennsylvania',new Date(2016,3,26),71,57,4,'Trump'],
              ['Maryland',new Date(2016,3,26),38,38,0,'Trump'],
              ['Delaware',new Date(2016,3,26),16,16,0,'Trump'],
              ['Connecticut',new Date(2016,3,26),28,28,0,'Trump'],
              ['New York',new Date(2016,3,19),95,89,0,'Trump'],
              ['Wisconsin',new Date(2016,3,5),42,6,36,'Cruz'],
              ['North Dakota',new Date(2016,3,1),28,0,10,'Cruz'],
              ['Utah',new Date(2016,2,22),40,0,40,'Cruz'],
              ['Arizona',new Date(2016,2,22),58,58,0,'Trump'],
              ['American Samoa',new Date(2016,2,22),9,1,1,'DRAW'],
              ['Ohio',new Date(2016,2,15),66,0,0,'DRAW'],
              ['Northern Marianas',new Date(2016,2,15),9,9,0,'Trump'],
              ['North Carolina',new Date(2016,2,15),72,29,27,'Trump'],
              ['Missouri',new Date(2016,2,15),52,37,15,'Trump'],
              ['Illinois',new Date(2016,2,15),69,54,9,'Trump'],
              ['Florida',new Date(2016,2,15),99,99,0,'Trump'],
              ['District of Columbia',new Date(2016,2,12),19,0,0,'DRAW'],
              ['Guam',new Date(2016,2,12),9,0,1,'Cruz'  ],
              ['Wyoming1',new Date(2016,2,12),29,1,23,'Cruz'],
              ['Virgin Islands',new Date(2016,2,10),9,0,0,'DRAW'],
              ['Mississippi',new Date(2016,2,8),40,25,15,'Trump'],
              ['Michigan',new Date(2016,2,8),59,25,17,'Trump'],
              ['Idaho',new Date(2016,2,8),32,12,20,'Cruz'],
              ['Hawaii',new Date(2016,2,8),19,11,7,'Trump'],
              ['Puerto Rico',new Date(2016,2,6),23,0,0,'DRAW'],
              ['Maine',new Date(2016,2,5),23,9,12,'Cruz'],
              ['Louisiana',new Date(2016,2,5),46,18,18,'DRAW'],
              ['Kentucky',new Date(2016,2,5),46,17,15,'Trump'],
              ['Kansas',new Date(2016,2,5),40,9,24,'Cruz'],
              ['Colorado1',new Date(2016,2,1),37,0,34,'Cruz'],
              ['Virginia',new Date(2016,2,1),49,17,8,'Trump'],
              ['Vermont',new Date(2016,2,1),16,8,0,'Trump'],
              ['Texas',new Date(2016,2,1),155,48,104,'Cruz'],
              ['Tennessee',new Date(2016,2,1),58,33,16,'Trump'],
              ['Oklahoma',new Date(2016,2,1),43,13,15,'Cruz'],
              ['Minnesota',new Date(2016,2,1),38,8,13,'Cruz'],
              ['Massachusetts',new Date(2016,2,1),42,22,4,'Trump'],
              ['Georgia',new Date(2016,2,1),76,42,18,'Trump'],
              ['Arkansas',new Date(2016,2,1),40,16,15,'Trump'],
              ['Alaska',new Date(2016,2,1),28,11,12,'Cruz'],
              ['Alabama',new Date(2016,2,1),50,36,13,'Trump'],
              ['Nevada',new Date(2016,1,23),30,14,6,'Trump'],
              ['South Carolina',new Date(2016,1,20),50,50,0,'Trump']
            ]);
           var options = {showRowNumber: false, width: '50%', height: '25%'};
           var table = new google.visualization.Table(document.getElementById('table_div_reb_res'));
           table.draw(data, options);

           google.visualization.events.addListener(table, 'select', selectHandler);
           function selectHandler(e) {
            var selection = table.getSelection();
            for (var i = 0; i < selection.length; i++) {
              var item = selection[i];
              if (item.row != null && item.column != null) {
                var selected_date = data.getValue(item.row, item.column);
              } else if (item.row != null) {
                var selected_date = data.getValue(item.row, 1);
              } else if (item.column != null) {
                var selected_date = data.getValue(1, item.column);
              }
            }

            google.charts.setOnLoadCallback(drawTest);
            function drawTest() {
              var two_weeks_back = new Date( );// =  str.add(-(1000 * 60 * 60 * 24));// new Date(str - (1000 * 60 * 60 * 24));
              two_weeks_back.setTime(selected_date.getTime() - (1000 * 60 * 60 * 24*2));
              // alert(formatDate(selected_date).toString() + "  " +formatDate(two_weeks_back).toString() + " "+ getDaysBack(two_weeks_back,selected_date));
              var jsonData = $.ajax({
                  url: "predictionDataGetRebulicanHour.php",//TODO: Change here to test
                  dataType: "json",
                  data: { 'end' : formatDate(selected_date),
                          'start' : formatDate(two_weeks_back),
                          'days' : getDaysBack(two_weeks_back , selected_date)
                          },
                  success: function(json){
                    var data = new google.visualization.DataTable(json);
                    var options = {
                      title: 'Rebulican Positive Tweets',
                      //curveType: 'function',
                      // trendlines: { 0: {type: 'polynomial'},
                      //               1: {type: 'polynomial'}}
                    };
                    //Set and draw
                    var chart = new google.visualization.AreaChart(document.getElementById('test'));
                    chart.draw(data, options);
                  }});//END OF AJAX
          }//END OF DRAW TEST
      }//END OF SELECT HANDLER
  }
  <!--END OF DELEGATE COUNT TABLE REB -->

  <!--BEGIN OF DELEGATE COUNT TABLE DEM-->

   google.charts.setOnLoadCallback(drawDemResTable);

   function drawDemResTable() {
           var data = new google.visualization.arrayToDataTable([

             ['State','Date','Delegates','Clinton','Sanders','Delegate Count Winner'],
             ['Indiana',new Date(2016,4,3),83,0,0,'DRAW'],
             ['Rhode Island',new Date(2016,3,26),24,11,13,'Sanders'],
             ['Pennsylvania',new Date(2016,3,26),189,105,83,'Clinton'],
             ['Delaware',new Date(2016,3,26),21,12,9,'Clinton'],
             ['Connecticut',new Date(2016,3,26),55,28,27,'Clinton'],
             ['Maryland',new Date(2016,3,26),95,61,33,'Clinton'],
             ['New York',new Date(2016,3,19),247,139,108,'Clinton'],
             ['Wyoming',new Date(2016,3,9),14,7,7,'DRAW'],
             ['Wisconsin',new Date(2016,3,5),86,38,48,'Sanders'],
             ['Washington',new Date(2016,2,26),101,9,25,'Sanders'],
             ['Hawaii',new Date(2016,2,26),25,8,17,'Sanders'],
             ['Alaska',new Date(2016,2,26),16,3,13,'Sanders'],
             ['Utah',new Date(2016,2,22),33,6,27,'Sanders'],
             ['Idaho',new Date(2016,2,22),23,5,18,'Sanders'],
             ['Arizona',new Date(2016,2,22),75,42,33,'Clinton'],
             ['Ohio',new Date(2016,2,15),143,81,62,'Clinton'],
             ['North Carolina',new Date(2016,2,15),107,59,45,'Clinton'],
             ['Missouri',new Date(2016,2,15),71,36,35,'Clinton'],
             ['Illinois',new Date(2016,2,15),156,79,77,'Clinton'],
             ['Florida',new Date(2016,2,15),214,141,73,'Clinton'],
             ['Northern Marianas',new Date(2016,2,12),6,4,2,'Clinton'],
             ['Michigan',new Date(2016,2,8),130,63,67,'Sanders'],
             ['Mississippi',new Date(2016,2,8),36,32,4,'Clinton'],
             ['Maine',new Date(2016,2,6),25,9,16,'Sanders'],
             ['Kansas',new Date(2016,2,5),33,10,23,'Sanders'],
             ['Nebraska',new Date(2016,2,5),25,10,15,'Sanders'],
             ['Louisiana',new Date(2016,2,5),51,37,14,'Clinton'],
             ['Virginia',new Date(2016,2,1),95,62,33,'Clinton'],
             ['Vermont',new Date(2016,2,1),16,0,16,'Sanders'],
             ['Texas',new Date(2016,2,1),222,147,75,'Clinton'],
             ['Tennessee',new Date(2016,2,1),67,44,23,'Clinton'],
             ['Oklahoma',new Date(2016,2,1),38,17,21,'Sanders'],
             ['Minnesota',new Date(2016,2,1),77,31,46,'Sanders'],
             ['Massachusetts',new Date(2016,2,1),91,46,45,'Clinton'],
             ['Georgia',new Date(2016,2,1),102,73,29,'Clinton'],
             ['Democrats Abroad',new Date(2016,2,1),13,4,9,'Sanders'],
             ['Colorado',new Date(2016,2,1),66,25,41,'Sanders'],
             ['Arkansas',new Date(2016,2,1),32,22,10,'Clinton'],
             ['American Samoa',new Date(2016,2,1),6,4,2,'Clinton'],
             ['Alabama',new Date(2016,2,1),53,44,9,'Clinton'],
             ['South Carolina',new Date(2016,1,27),53,39,14,'Clinton'],
             ['Nevada',new Date(2016,1,20),35,20,15,'Clinton']

            ]);



           var options = {showRowNumber: false, width: '50%', height: '25%'};
           var table = new google.visualization.Table(document.getElementById('table_div_dem_res'));
           table.draw(data, options);
           google.visualization.events.addListener(table, 'select', selectHandler);
           function selectHandler(e) {
            var selection = table.getSelection();
            for (var i = 0; i < selection.length; i++) {
              var item = selection[i];
              if (item.row != null && item.column != null) {
                var selected_date = data.getValue(item.row, item.column);
              } else if (item.row != null) {
                var selected_date = data.getValue(item.row, 1);
              } else if (item.column != null) {
                var selected_date = data.getValue(1, item.column);
              }
            }

            google.charts.setOnLoadCallback(drawTest2);
            function drawTest2() {
              var two_weeks_back = new Date( );// =  str.add(-(1000 * 60 * 60 * 24));// new Date(str - (1000 * 60 * 60 * 24));
              two_weeks_back.setTime(selected_date.getTime() - (1000 * 60 * 60 * 24*2));
              // alert(formatDate(selected_date).toString() + "  " +formatDate(two_weeks_back).toString() + " "+ getDaysBack(two_weeks_back,selected_date));
              var jsonData = $.ajax({
                  url: "predictionDataGetDemocratHour.php",//TODO: Change here to test
                  dataType: "json",
                  data: { 'end' : formatDate(selected_date),
                          'start' : formatDate(two_weeks_back),
                          'days' : getDaysBack(two_weeks_back , selected_date)
                          },
                  success: function(json){
                    var data = new google.visualization.DataTable(json);
                    var options = {
                      title: 'Democrat Positive Tweets',
                      //curveType: 'function',
                      // trendlines: { 0: {type: 'polynomial'},
                      //               1: {type: 'polynomial'}}
                    };
                    //Set and draw
                    var chart = new google.visualization.AreaChart(document.getElementById('test2'));
                    chart.draw(data, options);
                  }});//END OF AJAX
          }//END OF DRAW TEST
      }//END OF SELECT HANDLER



  }
  <!--END OF DELEGATE COUNT TABLE DEM -->


</script>
  </head>
  <body>

    <div id="page-wrapper">

      <!-- Header -->
        <header id="header">
          <div class="logo container">
            <div>

              <p>Here is the Analysis for US Election 2016</p>
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
            <li><a href="predict.php">Analysis</a></li>
          </ul>
        </nav>



                </div>


  <!-- <body> --><br/>

    <!--Div that will hold the pie chart -->

<div id="main-wrapper">
<div id="main" class="container">
    <!--Div that will hold the pie chart-->
    <h3 align="center">Positive Tweets For Candidates in the Democratic Party</h3>
    <div id="curve_chart_demo" align="center" style="width: 100%; height: 500px">
      <img src="default.svg">
    </div><br/>

    <!--Div that will hold the pie chart-->
    <h3 align="center">Positive Tweets For Candidates in the Republican Party</h3>
    <div id="curve_chart_reb" align="center" style="width: 100%; height: 500px">
      <img src="default.svg">
    </div><br/>
    <!--Div that will hold the line chart-->
    <h3 align="center">Positive Tweets For Candidates in Both Democrat and Republican Parties</h3>
    <div id="curve_chart_all" align="center" style="width: 100%; height: 500px">
      <img src="default.svg">
    </div><br/><br/>
    <h3 align="center">Number of Postive, Neutral, and Negative Tweets for All Candidates.</h3>
    <div id="table_div" align="center">
      <img src="default.svg">
    </div><br/>
<!--- NEW --->
    <h3 align="center">Rebulican Delegate Results.</h3>
    <div id="table_div_reb_res" align="center">
      <img src="default.svg">
    </div><br/>
<!--TEST-->
    <h3 align="center">Rebulican Positive Count For Selected Date.</h3>
    <div id="test" align="center" style="width: 100%; height: 500px">
      <h3 align="center">Click a row to see stats for primary</h3>
    </div><br/>
<!--END TEST-->

    <h3 align="center">Democrat Delegate Results.</h3>
    <div id="table_div_dem_res" align="center">
      <img src="default.svg">
    </div><br/>
    <!-- TEST2 -->
    <h3 align="center">Democrat Positive Count For Selected Date.</h3>
    <div id="test2" align="center" style="width: 100%; height: 500px">
      <h3 align="center">Click a row to see stats for primary</h3>
    </div><br/>

<!--END NEW--->


    <!--Div for the hashtag chart -->
    <h3 align="center">TOP 10 Hash Tags Recorded.</h3>
     <div id="barchart_values" align="center" style="width: 100%; height: 300px;">
       <img src="default.svg">
     </div> <br/>






    </div>
    </div>

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

<!-- previous code -->
