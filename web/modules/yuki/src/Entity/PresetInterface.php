<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 11/6/18
 * Time: 12:30 PM
 */

namespace Drupal\yuki\Entity;


interface PresetInterface {

  /**
   * The values to add into the template normally input and output paths
   *
   * @param array $values
   *
   * @return mixed
   */
  public function setConfigurationValues(array $values);

  /**
   * Returns the command in array form ready to be executed by a symfony process
   *
   * @return array
   */
  public function getCommand();

}