<?php
namespace Izberg;
/**
 * Helper
 */
class Helper
{
  public function camelize($value)
  {
    return strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
  }
  
  public function uncamelize($camel,$splitter="_") {
    $camel=preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $camel));
    return strtolower($camel);
  }
}
