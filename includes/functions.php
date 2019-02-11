<?php
function period_diff($in_dateLow, $in_dateHigh) {
   // swap dates if they are backwards
   if ($in_dateLow > $in_dateHigh) {
    	$tmp = $in_dateLow;
	    $in_dateLow = $in_dateHigh;
       	$in_dateHigh = $tmp;
   	}
	$dateLow = $in_dateLow;
   	$dateHigh = strftime('%m/%Y', $in_dateHigh);
	$periodDiff = 0;
   	while (strftime('%m/%Y', $dateLow) != $dateHigh) {
       	$periodDiff++;
       	$dateLow = strtotime('+1 month', $dateLow);
   	}
	return $periodDiff;
}
function IsPhysicalAddress($address){
//   $address=strtoupper($address);
  // $address=trim($address); 
   If(strlen($address) > 17) return 0;
   If($address == "") return 0;

   If (!eregi("^[0-9A-Z]+(\-[0-9A-Z]+)+(\-[0-9A-Z]+)+(\-[0-9A-Z]+)+(\-[0-9A-Z]+)+(\-[0-9A-Z]+)",$address)) return 0;
   echo $address;
   $Array=explode("-",$address);
   If(strlen($Array[0]) != 2) return 0;
   If(strlen($Array[1]) != 2) return 0;
   If(strlen($Array[2]) != 2) return 0;
   If(strlen($Array[3]) != 2) return 0;
   If(strlen($Array[4]) != 2) return 0;
   If(strlen($Array[5]) != 2) return 0;
   return 1;

} 
function GenMOD11( $base_val )
{
   $result = "";
   $weight = array( 2, 3, 4, 5, 6, 7,
                    2, 3, 4, 5, 6, 7,
                    2, 3, 4, 5, 6, 7,
                    2, 3, 4, 5, 6, 7 );   /* For convenience, reverse the string and work left to right. */
   $reversed_base_val = strrev( $base_val );
   for ( $i = 0, $sum = 0; $i < strlen( $reversed_base_val ); $i++ )
   {  /* Calculate product and accumulate. */
      $sum += substr( $reversed_base_val, $i, 1 ) * $weight[ $i ];
   }   /* Determine check digit, and concatenate to base value. */
   $remainder = $sum % 11;
   switch ( $remainder )
   {
   case 0:
      $result = $base_val . 0;
      break;
   case 1:
      $result = "n/a";
      break;
   default:
      $check_digit = 11 - $remainder;
      $result = $base_val . $check_digit;
      break;
   }
   return $result;
}
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    // Uncomment one of the following alternatives
    //$bytes /= pow(1024, $pow);
     $bytes /= (1 << (10 * $pow)); 
    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function validate_UPCABarcode($barcode){// check to see if barcode is 12 digits long
  if(!preg_match("/^[0-9]{12}$/",$barcode))     return false;
  $digits = $barcode;
  // 1. sum each of the odd numbered digits
  $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];  
  // 2. multiply result by three
  $odd_sum_three = $odd_sum * 3;
  // 3. add the result to the sum of each of the even numbered digits
  $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9];
	$total_sum = $odd_sum_three + $even_sum;
  	// 4. subtract the result from the next highest power of 10
	$next_ten = (ceil($total_sum/10))*10;
	$check_digit = $next_ten - $total_sum;
	// if the check digit and the last digit of the barcode are OK return true;
	if($check_digit == $digits[11])      return true;
	return false;
}

function validate_EAN13Barcode($barcode){
  // check to see if barcode is 13 digits long
  if(!preg_match("/^[0-9]{13}$/",$barcode))      return false;
  $digits = $barcode;
  // 1. Add the values of the digits in the even-numbered positions: 2, 4, 6, etc.
  $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
  // 2. Multiply this result by 3.
  $even_sum_three = $even_sum * 3;
  // 3. Add the values of the digits in the odd-numbered positions: 1, 3, 5, etc.
  $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
  // 4. Sum the results of steps 2 and 3.
  $total_sum = $even_sum_three + $odd_sum;
  // 5. The check character is the smallest number which, when added to the result in step 4,  produces a multiple of 10.
  $next_ten = (ceil($total_sum/10))*10;
  $check_digit = $next_ten - $total_sum;
  // if the check digit and the last digit of the barcode are OK return true;
  if($check_digit == $digits[12])     return true;
  return false;
}

function validateGtin($val){
	$digits=strlen($val)-1;		
	if($digits<7||!eregi("(([0-9])*)",$val))return false;
 	if ($digits%2) $n=1;
	for ($i=0; $i <$digits ; $i++) { 
  		$sum[$n%2]+=substr($val, $i,1);
		$n++;
  	}
	$total_sum=$sum[0]+($sum[1]*3);
	$next_ten = (ceil($total_sum/10))*10;
	$check_digit = $next_ten - $total_sum;
	// if the check digit and the last digit of the barcode are OK return true;
	if($check_digit == $val[$digits])     return true;
	return false;
}

function recursive_array_diff($a1, $a2) { 
    $r = array(); 
    foreach ($a1 as $k => $v) {
        if (array_key_exists($k, $a2)) { 
            if (is_array($v)) { 
                $rad = recursive_array_diff($v, $a2[$k]); 
                if (count($rad)) { $r[$k] = $rad; } 
            } else { 
                if ($v != $a2[$k]) { 
                    $r[$k] = $v; 
                }
            }
        } else { 
            $r[$k] = $v; 
        } 
    } 
    return $r; 
}

?>