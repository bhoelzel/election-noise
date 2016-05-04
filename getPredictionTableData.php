<?php
//ES Conector information
require_once 'app/init.php';
//Get candidate from ajax call
//$candidate = $_GET['candidate'];
//For timezone adjustment
date_default_timezone_set('GMT');
$time_back = $_GET['time'];
$interval = "day";//Change .d to something else if you go out of dailly


//Elasticsearch query
$query_result = $client->search(
  [
    'index' => 'sentiment*',
    'type' => 'tweet',
    'size' => 0,
    'body' => [
      "query" => [
        "filtered" => [
          "filter" => ["range"=>["@timestamp"=>["gt"=>"now-".$time_back."d"]]]
        ]
    ],

    "aggs" => [
        "candidate_sentiment"=> [
            "filters" => [
                "filters" => [
                    "clinton_results" => [ "term" => [ "message" => "clinton"]],
                    "trump_results"   => [ "term" => [ "message" => "trump"]],
                    "cruz_results"    => [ "term" => [ "message"=> "cruz"]],
                    "bernie_results"  => [ "term" => [ "message"=> "bernie"]]
                ]
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
  );

// echo '<pre>', print_r($query_result), '</pre>';


//Data colums
$array['cols'][] = array('id'=>'','label'=>'Candidate','pattern'=>'','type'=>'string');
$array['cols'][] = array('id'=>'','label'=>'Positive Tweets','pattern'=>'','type'=>'number');
$array['cols'][] = array('id'=>'','label'=>'Negative Tweets','pattern'=>'','type'=>'number');
$array['cols'][] = array('id'=>'','label'=>'Neutral Tweets','pattern'=>'','type'=>'number');

$clinton_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['clinton_results']['sentiment']['buckets'];
$trump_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['trump_results']['sentiment']['buckets'];
$cruz_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['cruz_results']['sentiment']['buckets'];
$bernie_sent=$query_result['aggregations']['candidate_sentiment']['buckets']['bernie_results']['sentiment']['buckets'];

    //Get sentiment count
      $clinton_pos_count = getSentiment( $clinton_sent, 'positive' );
      $clinton_neg_count = getSentiment( $clinton_sent, 'negative' );
      $clinton_net_count = getSentiment( $clinton_sent, 'neutral' );

      $trump_pos_count   = getSentiment( $trump_sent, 'positive' );
      $trump_neg_count   = getSentiment( $trump_sent, 'negative' );
      $trump_net_count   = getSentiment( $trump_sent, 'neutral' );

      $cruz_pos_count    = getSentiment( $cruz_sent, 'positive' );
      $cruz_neg_count    = getSentiment( $cruz_sent, 'negative' );
      $cruz_net_count    = getSentiment( $cruz_sent, 'neutral' );

      $bernie_pos_count  = getSentiment( $bernie_sent, 'positive' );
      $bernie_neg_count  = getSentiment( $bernie_sent, 'negative' );
      $bernie_net_count  = getSentiment( $bernie_sent, 'neutral' );


 $array['rows'][] = array('c' => array(
          array('v'=>'Bernie Sanders', 'f' =>null),
          array('v'=>$bernie_pos_count, 'f' =>null),
          array('v'=>$bernie_neg_count, 'f' =>null),
          array('v'=>$bernie_net_count, 'f' =>null)
 ));
 $array['rows'][] = array('c' => array(
          array('v'=>'Hilray Clinton', 'f' =>null),
          array('v'=>$clinton_pos_count, 'f' =>null),
          array('v'=>$clinton_neg_count, 'f' =>null),
          array('v'=>$clinton_net_count, 'f' =>null)
 ));

 $array['rows'][] = array('c' => array(
          array('v'=>'Ted Cruz',       'f' =>null),
          array('v'=>$cruz_pos_count, 'f' =>null),
          array('v'=>$cruz_neg_count, 'f' =>null),
          array('v'=>$cruz_net_count, 'f' =>null)
  ));

  $array['rows'][] = array('c' => array(
          array('v'=>'Donald Trump',   'f' =>null),
          array('v'=>$trump_pos_count,'f' =>null),
          array('v'=>$trump_neg_count, 'f' =>null),
          array('v'=>$trump_net_count, 'f' =>null)
   ));
    //Sort by postive count
    usort($array['rows'], 'sortByTagCount');
    //Return it
    echo json_encode($array);
//    echo '<pre>', print_r($results), '</pre>';

      function getSentiment($array, $sentiment){
        $result = -1;
        foreach ($array as $sub_array){
          if ($sub_array['key']==$sentiment){
            $result = $sub_array['doc_count'];
          }
        }
        return $result;
      }

      function sortByTagCount($a, $b){
        return $b['c'][1]['v'] - $a['c'][1]['v'];
      }



?>
