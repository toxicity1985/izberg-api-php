<?php
namespace Izberg;

class Helper
{
  /**
	* Camelize a string
  * @param string $value Text to camelize
	*/
  public function camelize($value)
  {
    return strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
  }

  /**
	* Uncamelize a string
  * @param string $value Text to uncamelize
  * @param string $splitter Char to use to split
	*/
  public function uncamelize($value,$splitter="_") {
    $value=preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $value));
    return strtolower($value);
  }
}
