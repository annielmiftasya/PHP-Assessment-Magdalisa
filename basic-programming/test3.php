<?php

function timeConversion($s)
{

   $period = substr($s, -2);

   $hour = (int)substr($s, 0, 2);
   $minutes = substr($s, 3, 2);
   $seconds = substr($s, 6, 2);

   if ($period == 'AM') {
      if ($hour == 12) {
         $hour = '00';
      } else {
         $hour = str_pad($hour, 2, '0', STR_PAD_LEFT);
      }
   } else {

      if ($hour != 12) {
         $hour += 12;
      }
      $hour = str_pad($hour, 2, '0', STR_PAD_LEFT);


      return $hour . ':' . $minutes . ':' . $seconds;
   }
}


$s = '07:05:45PM';

echo timeConversion($s) . "\n";
