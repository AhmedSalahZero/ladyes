<?php 
function split(array &$arr){
	$length = count($arr) ;
	if($length <= 1) return ;
	$middle = floor($length/2);
	$left = []; $right = [];
	for($i = 0 ; $i< $middle ; $i++){
		$left[$i] = $arr[$i];
	}
	for($j = $middle ; $j< $length ; $j++){
		$right[$j] = $arr[$j];
	}
	$left = array_values($left); // to reset indexes
	$right = array_values($right); // to reset indexes
	split($left);
	split($right);
	return merge($left,$right,$arr);
}
function merge($left , $right , &$arr)
{
	$i = 0  ; $j = 0 ; $k = 0 ;
	while($i < count($left) && $j < count($right)){
		if($right[$j] < $left[$i]){
			$arr[$k++] = $right[$j++]; 
		}else{
			$arr[$k++] = $left[$i++]; 
		}
	}
	while($j < count($right)){
		$arr[$k++] = $right[$j++];
	}
	while($i < count($left)){
		$arr[$k++] = $left[$i++];
	}
	return $arr ;
}
$origin = [1,6,7,3];
$result = split($origin);
