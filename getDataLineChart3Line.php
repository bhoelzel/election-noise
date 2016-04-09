<?php
//ES Conector information
require_once 'app/init.php';
//Get candidate from ajax call
$candidate = $_GET['candidate'];
//For timezone adjustment
date_default_timezone_set('GMT');
$minutes_back = $_GET['time'];//30;


//Elasticsearch query
$query_result = $client->search(
  [
    'index' => 'sentiment*',
    'type' => 'tweet',
    'size' => 0,
    'body' => [
      'query' => [
          'filtered' => [
            'filter' => ['range'=>['@timestamp'=>[ 'gt'=>'now-'.$minutes_back.'m']]],
            'query'  => ['match'=>['message'=> $candidate ]]
          ]
      ],
      'aggs' => [
          'positive_hits' => [
            'terms' => [ 'field' => 'sentiment', 'include'=>'positive'],
            'aggs' => [
              'positive_over_time' => [
                'date_histogram' => [
                  'field' => '@timestamp',
                  'interval' => 'minute',
                  'min_doc_count' => 0,
                  'extended_bounds' => ["min" => 'now-'.$minutes_back.'m',
                                        "max" => 'now']
                ]
              ]
            ]
          ],
          'negative_hits' => [
            'terms' => [ 'field' => 'sentiment', 'include'=>'negative'],
            'aggs' => [
              'negative_over_time' => [
                'date_histogram' => [
                  'field' => '@timestamp',
                  'interval' => 'minute',
                  'min_doc_count' => 0,
                  'extended_bounds' => ["min" => 'now-'.$minutes_back.'m',
                                        "max" => 'now']

                ]
              ]
            ]
          ]
          ,
          'neutral_hits' => [
            'terms' => [ 'field' => 'sentiment', 'include'=>'neutral'],
            'aggs' => [
              'neutral_over_time' => [
                'date_histogram' => [
                  'field' => '@timestamp',
                  'interval' => 'minute',
                  'min_doc_count' => 0,
                  'extended_bounds' => ["min" => 'now-'.$minutes_back.'m',
                                        "max" => 'now']
                ]
              ]
            ]
          ]


        ]
      ]
    ]);
    //Data colums
    $array['cols'][] = array('id'=>'','label'=>'Time','pattern'=>'','type'=>'string');
    $array['cols'][] = array('id'=>'','label'=>'Positive Tweets','pattern'=>'','type'=>'number');
    $array['cols'][] = array('id'=>'','label'=>'Negative Tweets','pattern'=>'','type'=>'number');
    $array['cols'][] = array('id'=>'','label'=>'Neutral Tweets','pattern'=>'','type'=>'number');
    //Results of query
    // $pos_results = array();
    // $neg_results = array();
//echo '<pre>', print_r($query_result), '</pre>';

    $pos_results=$query_result['aggregations']['positive_hits']['buckets'][0]['positive_over_time']['buckets'];
    $neg_results=$query_result['aggregations']['negative_hits']['buckets'][0]['negative_over_time']['buckets'];
    $neu_results=$query_result['aggregations']['neutral_hits']['buckets'][0]['neutral_over_time']['buckets'];
    //Remove last entry, often it is incomplete minute displays funny
    //TODO: stopped pop, to insure we get the for loop right
    // array_pop($pos_results);
    //array_pop($neg_results);
    //TODO:array_pop($neu_results);



    for ($i = 0; $i < $minutes_back; $i++){
      //Conver to time stamp, no date from record.
      // ONLY NEED ONE, SET THE ES Query to have min_num_doc to 0, and extended_bounds,
      // so we will get all dates, even if they have no recs
      preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{2}/",$pos_results[$i]['key_as_string'],$matches);
      //Get GMT from record
      $time = date( $matches[0] );
      //Convert to localtime TODO: should probablly get time zone from user to show thier time
      $datetime = new DateTime($time);
      $la_time = new DateTimeZone('America/Los_Angeles');//<-Change with time from users browser
      $datetime->setTimezone($la_time);
      //Count per time stamp from record
      $doc_count = $pos_results[$i]['doc_count'];
///////////
      $neg_doc_count = $neg_results[$i]['doc_count'];

      $neu_doc_count = $neu_results[$i]['doc_count'];
      $array['rows'][] = array('c' => array( array('v'=>$datetime->format('H:i:s'),'f'=>null),array('v'=>$doc_count, 'f' =>null),array('v'=>$neg_doc_count, 'f' =>null),array('v'=>$neu_doc_count, 'f' =>null)));

    }
  //  echo '<pre>', print_r($array), '</pre>';

    // //Formatting for google chart
    // foreach ($pos_results as $result){
    //   //Conver to time stamp, no date from record
    //   preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{2}/",$result['key_as_string'],$matches);
    //   //Get GMT from record
    //   $time = date( $matches[0] );
    //   //Convert to localtime TODO: should probablly get time zone from user to show thier time
    //   $datetime = new DateTime($time);
    //   $la_time = new DateTimeZone('America/Los_Angeles');//<-Change with time from users browser
    //   $datetime->setTimezone($la_time);
    //   //Count per time stamp from record
    //   $doc_count = $result['doc_count'];
    //   //Create array for json conversion
    //   $array['rows'][] = array('c' => array( array('v'=>$datetime->format('H:i:s'),'f'=>null),
    //                                   array('v'=>$doc_count, 'f' =>null)));
    // }
    //
    // //Formatting for google chart
    // foreach ($neg_results as $result){
    //   //Conver to time stamp, no date from record
    //   preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{2}/",$result['key_as_string'],$matches);
    //   //Get GMT from record
    //   $time = date( $matches[0] );
    //   //Convert to localtime TODO: should probablly get time zone from user to show thier time
    //   $datetime = new DateTime($time);
    //   $la_time = new DateTimeZone('America/Los_Angeles');//<-Change with time from users browser
    //   $datetime->setTimezone($la_time);
    //   //Count per time stamp from record
    //   $doc_count = $result['doc_count'];
    //   //Create array for json conversion
    //   $array['rows'][] = array('c' => array( array('v'=>$datetime->format('H:i:s'),'f'=>null),
    //                                   array('v'=>$doc_count, 'f' =>null)));
    // }
  //  echo '<pre>', print_r($array), '</pre>';

    //Return it
    echo json_encode($array);
//    echo '<pre>', print_r($results), '</pre>';
?>
