<?php
//ES Conector information
require_once 'app/init.php';
//Get candidate from ajax call
$candidate = $_GET['candidate'];
//For timezone adjustment
date_default_timezone_set('GMT');


//Elasticsearch query
$query_result = $client->search(
  [
    'index' => 'sentiment*',
    'type' => 'tweet',
    'size' => 0,
    'body' => [
      'query' => [
          'filtered' => [
            'filter' => ['range'=>['@timestamp'=>[ 'gt'=>'now-60m']]],
            'query'  => ['match'=>['message'=> $candidate ]]
          ]
      ],
      'aggs' => [
          'hits_over_time' => [
            'date_histogram' => [
              'field' => '@timestamp',
              'interval' => 'minute'
            ]
          ]
        ]
      ]
    ]);
    //Data colums
    $array['cols'][] = array('id'=>'','label'=>'Time','pattern'=>'','type'=>'string');
    $array['cols'][] = array('id'=>'','label'=>'Tweets','pattern'=>'','type'=>'number');
    //Results of query
    $results=$query_result['aggregations']['hits_over_time']['buckets'];
    //Remove last entry, often it is incomplete minute displays funny
    array_pop($results);
    //Formatting for google chart
    foreach ($results as $result){
      //Conver to time stamp, no date from record
      preg_match("/[0-9]{2}:[0-9]{2}:[0-9]{2}/",$result['key_as_string'],$matches);
      //Get GMT from record
      $time = date( $matches[0] );
      //Convert to localtime TODO: should probablly get time zone from user to show thier time
      $datetime = new DateTime($time);
      $la_time = new DateTimeZone('America/Los_Angeles');//<-Change with time from users browser
      $datetime->setTimezone($la_time);
      //Count per time stamp from record
      $doc_count = $result['doc_count'];
      //Create array for json conversion
      $array['rows'][] = array('c' => array( array('v'=>$datetime->format('H:i:s'),'f'=>null),
                                      array('v'=>$doc_count, 'f' =>null)));
    }
    //Return it
    echo json_encode($array);
//    echo '<pre>', print_r($results), '</pre>';
?>
