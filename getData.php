<?php
//!! Now we getting somewhere!
$src_token = $_GET['candidate'];;

//ES Conector information
require_once 'app/init.php';

//Query ES
$query_result = $client->search(
  [
    'index' => 'sentiment*',
    'type' => 'tweet',
    'size' => 0,
    'body' => [
//
      'query' => [
          'match' => [
            'message' => $src_token
          ]
      ],

//
      'aggs' => [
          'by_sentiment' => [
            'terms' => [
              'field' => 'sentiment',
              'size' => 3
            ]
          ]
        ]
      ]
    ]);


    $key_one = $query_result['aggregations']['by_sentiment']['buckets']['0']['key'];
    $doc_count_one = $query_result['aggregations']['by_sentiment']['buckets']['0']['doc_count'];

    $key_two = $query_result['aggregations']['by_sentiment']['buckets']['1']['key'];
    $doc_count_two = $query_result['aggregations']['by_sentiment']['buckets']['1']['doc_count'];

    $key_three = $query_result['aggregations']['by_sentiment']['buckets']['2']['key'];
    $doc_count_three = $query_result['aggregations']['by_sentiment']['buckets']['2']['doc_count'];

      $array['cols'][] = array('id'=>'','label'=>'Sentiment','pattern'=>'','type'=>'string');
      $array['cols'][] = array('id'=>'','label'=>'Tweets','pattern'=>'','type'=>'number');

      $array['rows'][] = array('c' => array( array('v'=>$key_one,'f'=>null), array('v'=>$doc_count_one, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_two,'f'=>null), array('v'=>$doc_count_two, 'f' =>null)) );
      $array['rows'][] = array('c' => array( array('v'=>$key_three,'f'=>null), array('v'=>$doc_count_three, 'f' =>null)) );
      echo json_encode($array);
//echo '<pre>', print_r($query_result), '</pre>';ONLY WORKS WHEN MAIN PAGE SERVER SIDE THING???


// This is just an example of reading server side data and sending it to the client.
// It reads a json formatted text file and outputs it.

// $string = file_get_contents("sampleData.json");
// echo $string;





?>
