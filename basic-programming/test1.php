<?php
function miniMaxSum($arr)
{
   sort($arr);
   $totalSum = array_sum($arr);

   $minSum = $totalSum - $arr[count($arr) - 1];
   $maxSum = $totalSum - $arr[0];

   echo $minSum . " " . $maxSum . "\n";
}

$input = "1 2 3 4 5";
$arr = explode(" ", $input);
$arr = array_map('intval', $arr);

miniMaxSum($arr);
