<?php
//ES Conector information
require_once 'app/init.php';
//Get candidate from ajax call
//$candidate = $_GET['candidate'];
//For timezone adjustment
date_default_timezone_set('GMT');
$time_start =  $_GET['start'];
$time_end = $_GET['end'];
$days = $_GET['days'];
$interval = "hour";//Change .d to something else if you go out of dailly
//
$days = $days * 24;


//Elasticsearch query
$query_result = $client->search(
  [
    'index' => 'sentiment*',
    'type' => 'tweet',
    'size' => 0,
    'body' => [
      "query" => [
        "filtered" => [
          "filter" => ["range"=>["@timestamp"=>["gte"=>$time_start,
                                                "lte"=>$time_end,
                                                "format"=>"yyyy-MM-dd"   ]]]
        ]
    ],

    "aggs" => [
        "candidate_sentiment"=> [
            "filters" => [
                "filters" => [

                    "clinton_results"   => [ "term" => [ "message" => "clinton"]],
                    "sanders_results"    => [ "term" => [ "message"=> "sanders"]]
                ]
            ],

            "aggs" => [
                "sentiment_overdate" => [
                    "date_histogram" => [
                        "field"    => "@timestamp",
                        "interval" => $interval,
                        "min_doc_count" => 0,
                        "extended_bounds" => [ "min" => $time_start,
                                               "max" => $time_end]
                    ],

                    "aggs" => [
                        "sentiment" => [
                            "terms" => ["field" => "sentiment"]
                        ]
                    ]
                ]
            ]
        ]
    ]
  ]
]);
    //Data colums
    $array['cols'][] = array('id'=>'','label'=>'Time','pattern'=>'','type'=>'datetime');


    $array['cols'][] = array('id'=>'','label'=>'Clinton PositiveTweets','pattern'=>'','type'=>'number');
    $array['cols'][] = array('id'=>'','label'=>'Sanders PositiveTweets','pattern'=>'','type'=>'number');
    //Results of query
    // $pos_results = array();
    // $neg_results = array();
  // echo '<pre>', print_r($query_result), '</pre>';
//

$clinton_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['clinton_results']['sentiment_overdate']['buckets'];
$sanders_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['sanders_results']['sentiment_overdate']['buckets'];





    for ($i = 0; $i < $days; $i++){
      //Conver to time stamp, no date from record.
      // ONLY NEED ONE, SET THE ES Query to have min_num_doc to 0, and extended_bounds,
      // so we will get all dates, even if they have no recs, because the qeury from es will add 0 for empty query


      preg_match("/([0-9]{4}-[0-9]{2}-[0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})/",$clinton_sent[$i]['key_as_string'],$matches);//TODO: regez date and time
      //Get GMT from record
      //  echo '<pre>', print_r($matches), '</pre>';

      //$time = Date( $matches[1]);

      //Convert to localtime TODO: should probablly get time zone from user to show thier time
      $datetime = new DateTime($matches[1]);
      $datetime->setTime($matches[2],$matches[3],$matches[4]);
      $la_time = new DateTimeZone('America/Los_Angeles');//<-Change with time from users browser
      $datetime->setTimezone($la_time);
      $datetime->modify('-1 month');
      //Get sentiment count
      if($clinton_sent[$i]['doc_count']==0||$sanders_sent[$i]['doc_count']==0){
        // $array['rows'][] = array('c' => array( array('v'=>'Date('.$datetime->format('Y,m,d,H').')','f'=>null),
        //                                        array('v'=>0, 'f' =>null),
        //                                        array('v'=>0, 'f' =>null)));
      }
      else {
        $clinton_tot         = $clinton_sent[$i]['doc_count'];
        $clinton_pos_count   = getSentiment( $clinton_sent[$i]['sentiment']['buckets'], 'positive' );
        $sanders_tot          = $sanders_sent[$i]['doc_count'];
        $sanders_pos_count    = getSentiment( $sanders_sent[$i]['sentiment']['buckets'], 'positive' );
        $all_candidate_tweets = $clinton_tot + $sanders_tot ;
      if ($all_candidate_tweets != 0) {
        // echo '<pre>', $datetime->format('Y,m,d,H'), '</pre>';

      $array['rows'][] = array('c' => array( array('v'=>'Date('. $datetime->format('Y,m,d,H') .')','f'=>  null ),
                                             array('v'=>$sanders_pos_count/*$all_candidate_tweets*/, 'f' =>null),
                                             array('v'=>$clinton_pos_count/*$all_candidate_tweets*/, 'f' =>null)));

          }
        }
    }
    //Return it
    // usort($array['rows'],'sortByDate');
  // echo '<pre>', print_r($array), '</pre>';

    echo json_encode($array);

    //Array accessor helper function
    function getSentiment($array, $sentiment){
        $result = -1;
        foreach ($array as $sub_array){
          if ($sub_array['key']==$sentiment){
            $result = $sub_array['doc_count'];
          }
        }
        return $result;
      }

      function sortByDate($a, $b){
        return $b['c'][0]['f'] - $a['c'][0]['f'];
      }

?>
