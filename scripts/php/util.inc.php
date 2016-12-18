<?php
/**
 * Generate a random string
 * 
 * @param int $length      How many characters do we want ?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length = 10, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
  $str = '';
  $max = mb_strlen($keyspace, '8bit') - 1;
  for ($i = 0; $i < $length; ++$i) {
    $str .= $keyspace[rand(0, $max)];
  }
  return $str;
}
?>