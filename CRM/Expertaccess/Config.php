<?php

class CRM_Expertaccess_Config {
  
  protected static $_singleton;
  
  protected $expert_relationship_type;
  
  private function __construct() {
    $this->expert_relationship_type = civicrm_api3('RelationshipType', 'getsingle', array('name_a_b' => 'Expert'));
  }
  
  /**
   * 
   * @return CRM_Expertaccess_Config
   */
  public static function singleton() {
    if (!self::$_singleton) {
      self::$_singleton = new CRM_Expertaccess_Config();
    } 
    return self::$_singleton;
  }
  
  public function getExpertRelationshipType($key='id') {
    return $this->expert_relationship_type[$key];
  }
}

