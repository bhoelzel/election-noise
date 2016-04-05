<?php
//ES Conector information
require_once 'app/init.php';

/*Query ES
{
    "size": 0,
    "query": {
        "filtered":{
          "filter":{"range":{"@timestamp":{ "gt":"now-15m"}}},
          "query":  { "match": { "message": "trump" }}
        }
    },
    "aggs" : {
       "articles_over_time" : {
            "date_histogram" : {
                "field" : "@timestamp",
                "interval" : "minute"
            }
        }
    }
}
*/
$query_result = $client->search(
  [
    'index' => 'sentiment*',
    'type' => 'tweet',
    'size' => 0,
    'body' => [
      'query' => [
          'filtered' => [
            'filter' => ['range'=>['@timestamp'=>[ 'gt'=>'now-15m']]],
            'query'  => ['match'=>['message'=> 'trump' ]]
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


    $key_one = $query_result['aggregations']['hits_over_time']['buckets']['0']['key_as_string'];
    $doc_count_one = $query_result['aggregations']['hits_over_time']['buckets']['0']['doc_count'];

    $key_two = $query_result['aggregations']['hits_over_time']['buckets']['1']['key_as_string'];
    $doc_count_two = $query_result['aggregations']['hits_over_time']['buckets']['1']['doc_count'];

    $key_three = $query_result['aggregations']['hits_over_time']['buckets']['2']['key_as_string'];
    $doc_count_three = $query_result['aggregations']['hits_over_time']['buckets']['2']['doc_count'];

    $key_four = $query_result['aggregations']['hits_over_time']['buckets']['3']['key_as_string'];
    $doc_count_four = $query_result['aggregations']['hits_over_time']['buckets']['3']['doc_count'];

    $key_five = $query_result['aggregations']['hits_over_time']['buckets']['4']['key_as_string'];
    $doc_count_five = $query_result['aggregations']['hits_over_time']['buckets']['4']['doc_count'];

    $key_six = $query_result['aggregations']['hits_over_time']['buckets']['5']['key_as_string'];
    $doc_count_six = $query_result['aggregations']['hits_over_time']['buckets']['5']['doc_count'];

    $key_seven = $query_result['aggregations']['hits_over_time']['buckets']['6']['key_as_string'];
    $doc_count_seven = $query_result['aggregations']['hits_over_time']['buckets']['6']['doc_count'];

    $key_eight = $query_result['aggregations']['hits_over_time']['buckets']['7']['key_as_string'];
    $doc_count_eight = $query_result['aggregations']['hits_over_time']['buckets']['7']['doc_count'];

    $key_nine = $query_result['aggregations']['hits_over_time']['buckets']['8']['key_as_string'];
    $doc_count_nine = $query_result['aggregations']['hits_over_time']['buckets']['8']['doc_count'];

    $key_ten = $query_result['aggregations']['hits_over_time']['buckets']['9']['key_as_string'];
    $doc_count_ten = $query_result['aggregations']['hits_over_time']['buckets']['9']['doc_count'];
/*
    ['Time', 'Hits'],
    ['04:43:00',  40],
    ['4:44:00',  106],
    ['04:45:00', 124],
    ['04:46:00',  110],
    ['04:47:00',  148],
    ['04:48:00',  145],
    ['04:49:00',  111],
    ['04:50:00',  122],
*/
      $array['cols'][] = array('id'=>'','label'=>'Time','pattern'=>'','type'=>'string');
      $array['cols'][] = array('id'=>'','label'=>'Tweets','pattern'=>'','type'=>'number');

      $array['rows'][] = array('c' => array( array('v'=>$key_one,'f'=>null), array('v'=>$doc_count_one, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_two,'f'=>null), array('v'=>$doc_count_two, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_three,'f'=>null), array('v'=>$doc_count_three, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_four,'f'=>null), array('v'=>$doc_count_four, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_five,'f'=>null), array('v'=>$doc_count_five, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_six,'f'=>null), array('v'=>$doc_count_six, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_seven,'f'=>null), array('v'=>$doc_count_seven, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_eight,'f'=>null), array('v'=>$doc_count_eight, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_nine,'f'=>null), array('v'=>$doc_count_nine, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_ten,'f'=>null), array('v'=>$doc_count_ten, 'f' =>null)) );
//Return it
    //  echo json_encode($array);
echo '<pre>', print_r($array), '</pre>';







?>
