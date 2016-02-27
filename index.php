<?php
//TESTING NEW PHP-JENKINS TEMPLATE
require_once 'app/init.php';

if(isset($_GET['query_set'])){

  $query_data = $_GET['query_set'];
  $query_result = $client->search([
    'index' => 'sentiment',
    'type' => 'test-type',
    'body' => [
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



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Election Noise Webapp</title>
</head>
<body>
  <form action="index.php" methof="get" autocomplete="off">
    <label>
      Search for something
      <input type="text" name="query_set">
    </label>
    <input type="submit" value="Search">
  </form>

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
</body>
</html>
