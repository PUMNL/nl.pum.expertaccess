<?php

class CRM_Expertaccess_PortalProfileLink {
  
  protected $contact_id;
  
  public function __construct($contact_id) {
    $this->contact_id = $contact_id;
  }
  
  public function userIsExpert() {
    $session = CRM_Core_Session::singleton();
    if ($this->contact_id != $session->get('userID')) {
      return false;
    }
    
    //check if current contact is an expert
    try {
      $contact = civicrm_api3('Contact', 'getsingle', array('id' => $this->contact_id));
      foreach($contact['contact_sub_type'] as $subtype) {
        if ($subtype == 'Expert') {
          return true;
        }
      }
    } catch (Exception $e) {
      return false;
    }
  }
  
  public function getLink() {
    try {
      $uf = civicrm_api3('UFMatch', 'getsingle', array('contact_id' => $this->contact_id));
      $url = url('user/'.$uf['uf_id'].'');
      return $url;
    } catch (Exception $e) {
      return false;
    }
  }
}

