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

                    "trump_results"   => [ "term" => [ "message" => "trump"]],
                    "cruz_results"    => [ "term" => [ "message"=> "cruz"]]
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


    $array['cols'][] = array('id'=>'','label'=>'Cruz PositiveTweets','pattern'=>'','type'=>'number');
    $array['cols'][] = array('id'=>'','label'=>'Trump PositiveTweets','pattern'=>'','type'=>'number');
    //Results of query
    // $pos_results = array();
    // $neg_results = array();
  // echo '<pre>', print_r($query_result), '</pre>';
//

$trump_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['trump_results']['sentiment_overdate']['buckets'];
$cruz_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['cruz_results']['sentiment_overdate']['buckets'];


// echo 'CLINTON:';
//  echo '<pre>', print_r($clinton_sent), '</pre>';
// echo 'TRUMP:';
// echo '<pre>', print_r($trump_sent), '</pre>';
// echo 'CRUZ:';
// echo '<pre>', print_r($cruz_sent), '</pre>';
// echo 'BERNIE:';
// echo '<pre>', print_r($bernie_sent), '</pre>';



    //$pos_results=$query_result['aggregations']['positive_hits']['buckets'][0]['positive_over_time']['buckets'];
    //$neg_results=$query_result['aggregations']['negative_hits']['buckets'][0]['negative_over_time']['buckets'];
    //$neu_results=$query_result['aggregations']['neutral_hits']['buckets'][0]['neutral_over_time']['buckets'];

    //Remove last entry, often it is incomplete minute displays funny
    //TODO:stopped pop, to insure we get the for loop right
    //array_pop($pos_results);
    //array_pop($neg_results);
    //TODO:array_pop($neu_results);



    for ($i = 0; $i < $days; $i++){
      //Conver to time stamp, no date from record.
      // ONLY NEED ONE, SET THE ES Query to have min_num_doc to 0, and extended_bounds,
      // so we will get all dates, even if they have no recs, because the qeury from es will add 0 for empty query


      preg_match("/([0-9]{4}-[0-9]{2}-[0-9]{2})T([0-9]{2}):([0-9]{2}):([0-9]{2})/",$trump_sent[$i]['key_as_string'],$matches);//TODO: regez date and time
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
      if($trump_sent[$i]['doc_count']==0||$cruz_sent[$i]['doc_count']==0){
        // $array['rows'][] = array('c' => array( array('v'=>'Date('.$datetime->format('Y,m,d,H').')','f'=>null),
        //                                        array('v'=>0, 'f' =>null),
        //                                        array('v'=>0, 'f' =>null)));
      }
      else {
        $trump_tot         = $trump_sent[$i]['doc_count'];
        $trump_pos_count   = getSentiment( $trump_sent[$i]['sentiment']['buckets'], 'positive' );
        $cruz_tot          = $cruz_sent[$i]['doc_count'];
        $cruz_pos_count    = getSentiment( $cruz_sent[$i]['sentiment']['buckets'], 'positive' );
        $all_candidate_tweets = $trump_tot + $cruz_tot ;
      if ($all_candidate_tweets != 0) {
        // echo '<pre>', $datetime->format('Y,m,d,H'), '</pre>';

      $array['rows'][] = array('c' => array( array('v'=>'Date('. $datetime->format('Y,m,d,H') .')','f'=>  null ),
                                             array('v'=>$cruz_pos_count/*$all_candidate_tweets*/, 'f' =>null),
                                             array('v'=>$trump_pos_count/*$all_candidate_tweets*/, 'f' =>null)));

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
