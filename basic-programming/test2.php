<?php

function pluMinus($arr)
{
   $n = count($arr);

   $positiveCount = 0;
   $negativeCount = 0;
   $zeroCount = 0;

   foreach ($arr as $num) {
      if ($num > 0) {
         $positiveCount++;
      } elseif ($num < 0) {
         $negativeCount++;
      } else {
         $zeroCount++;
      }
   }

   $positiveRatio = $positiveCount / $n;
   $negativeRatio = $negativeCount / $n;
   $zeroRatio = $zeroCount / $n;

   printf("%.6f\n", $positiveRatio);
   printf("%.6f\n", $negativeRatio);
   printf("%.6f\n", $zeroRatio);
}


$n = 6;
$arr = [-4, 3, -9, 0, 4, 1];

pluMinus($arr);
