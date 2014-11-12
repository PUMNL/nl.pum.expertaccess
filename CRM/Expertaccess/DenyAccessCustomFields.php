<?php

class CRM_Expertaccess_DenyAccessCustomFields {
  
  public static function pageRun(&$page) {    
    if (!($page instanceof CRM_Contact_Page_View_CustomData)) {
      return;
    }
    
    if (CRM_Core_Permission::check('access all custom data')) {
      return;
    }
    
    $contactId = $page->getVar('_contactId');    
    $session = CRM_Core_Session::singleton();
    
    if (!$session->get('userID')) {
      return;
    }
    
    if ($session->get('userID') != $contactId) {
      return;
    }
    
    if ($page->get_template_vars('editCustomData')) {
      return;
    }
    
    $page->assign('editOwnCustomData', false);
       
  }
  
}

