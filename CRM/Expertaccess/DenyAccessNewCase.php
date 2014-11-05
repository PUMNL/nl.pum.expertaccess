<?php

class CRM_Expertaccess_DenyAccessNewCase {
  
  public static function buildForm($formName, &$form) {    
    $hasAccess = false;
    if ($formName != 'CRM_Case_Form_Case' && $formName != 'CRM_Case_Form_EditClient') {
      return;
    }
    
    if (CRM_Core_Permission::check('add cases')) {
      $hasAccess = true;
    }
    
    $session = CRM_Core_Session::singleton();
    if (!$hasAccess) {
      $session->setStatus('You do not have access to add a new case', 'Access denied', 'Alert');
      $userContext = $session->popUserContext();
      CRM_Utils_System::redirect($userContext);
    }
    
    
  }
  
}

