<?php
	include 'connect.php';
    $array = [
        [" id " => 1 , 'name' => 'Ali'     , 'gender'=>'male' , 'price' => 2000] ,
        [" id " => 2 , 'name' => 'Omer'    , 'gender'=>'male' , 'price' => 3000] ,
        [" id " => 3 , 'name' => 'Ahmed'   , 'gender'=>'male' , 'price' => 3000] ,
        [" id " => 4 , 'name' => 'babiker' , 'gender'=>'male' , 'price' => 3500] ,
        [" id " => 5 , 'name' => 'KHalid'  , 'gender'=>'male' , 'price' => 4000]
    ];
    $result = array_sum(array_map(function($item) {
        if($item['price'] >= 3000)
        return $item['price']; 
    }, $array));
    echo $result."<br>";
    echo array_sum(array_column($array, 'price'));
?>