<?php
//ES Conector information
require_once 'app/init.php';
//Get candidate from ajax call
//$candidate = $_GET['candidate'];
//For timezone adjustment
date_default_timezone_set('GMT');
$time_back = $_GET['time'];
$interval = "day";//Change .d to something else if you go out of dailly
$number_top_tags = 11;


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
    "size"=> 0,
    "aggs"=> [
      "top_hashtags"=>[
        "terms"=> [
          "field"=> "hashtags.text",
          "size"=> $number_top_tags,
          "order"=> [
            "1"=> "desc"
          ]
        ],
        "aggs"=> [
          "1"=> [
            "cardinality"=> [
              "field"=> "hashtags.text"
            ]
          ]
        ]
      ]
    ]
  ]
]);
//     //Data colums
     $array['cols'][] = array('id'=>'','label'=>'HashTag','pattern'=>'','type'=>'string');
     $array['cols'][] = array('id'=>'','label'=>'HashTagCount','pattern'=>'','type'=>'number');
//echo '<pre>', print_r($query_result), '</pre>';
// //
$hashtag_results=$query_result['aggregations']['top_hashtags']['buckets'];
//usort($hashtag_results, 'sortByKey');
//echo '<pre>', print_r($hashtag_results), '</pre>';
//
     for ($i = 0; $i < $number_top_tags; $i++){
//       //Conver to time stamp, no date from record.
//       // ONLY NEED ONE, SET THE ES Query to have min_num_doc to 0, and extended_bounds,
//       // so we will get all dates, even if they have no recs, because the qeury from es will add 0 for empty query
//       preg_match("/[0-9]{4}-[0-9]{2}-[0-9]{2}/",$clinton_sent[$i]['key_as_string'],$matches);//TODO: regez date and time
//       //Get GMT from record
//       $time = date( $matches[0] );
//       //Convert to localtime TODO: should probablly get time zone from user to show thier time
//       $datetime = new DateTime($time);
//       $la_time = new DateTimeZone('America/Los_Angeles');//<-Change with time from users browser
//       $datetime->setTimezone($la_time);
//       //Get sentiment count
//       $clinton_tot       = $clinton_sent[$i]['doc_count'];
//       $clinton_pos_count = getSentiment( $clinton_sent[$i]['sentiment']['buckets'], 'positive' );
//
//
//       $bernie_tot        = $bernie_sent[$i]['doc_count'];
//       $bernie_pos_count  = getSentiment( $bernie_sent[$i]['sentiment']['buckets'], 'positive' );
//
//       $all_candidate_tweets = $clinton_tot +  $bernie_tot;
//   //    echo "Clinton: ".$clinton_pos_count."\n";
//   //    echo "Trump: ".$trump_pos_count."\n";
//   //    echo "Cruz: ".$cruz_pos_count."\n";
//   //    echo "Bernie: ".$bernie_pos_count."\n";
//       //echo array_search('positive', array_column( $clinton_pos_count, 'key'));
//
// //TODO: chart for each party
// //TODO: how to normilize and get value???
//       if ($all_candidate_tweets != 0) {
      $array['rows'][] = array('c' => array( array('v'=>$hashtag_results[$i]['key'],'f'=>null), array('v'=>$hashtag_results[$i]['doc_count'], 'f' =>null)
                                            ));
                                            // $array['rows'][] =  array( array('v'=>$hashtag_results[$i]['key'],'f'=>null),
                                            //                                        array('v'=>$hashtag_results[$i]['doc_count'], 'f' =>null)
                                            //                                       );

          // }
     }
usort($array['rows'], 'sortByTagCount');
  // echo '<pre>', print_r($array), '</pre>';
//
//   //  echo '<pre>', print_r($array), '</pre>';
//
//     //Return it
     echo json_encode($array);
// //    echo '<pre>', print_r($results), '</pre>';
//


    function sortByTagCount($a, $b){
      return $b['c'][1]['v'] - $a['c'][1]['v'];
    }
?>
