<?php
namespace Ice;
/**
 * Helper
 */
class Helper
{
  public function camelize($value)
  {
    return strtr(ucwords(strtr($value, array('_' => ' ', '.' => '_ ', '\\' => '_ '))), array(' ' => ''));
  }
}
