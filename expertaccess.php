<?php

require_once 'expertaccess.civix.php';

function expertaccess_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Case_Form_Case' || $formName == 'CRM_Case_Form_EditClient') {
   //deny access to the expert application case when the logged in user is the client of the case
    CRM_Expertaccess_DenyAccessNewCase::buildForm($formName, $form);
  }
}

function expertaccess_civicrm_aclWhereClause( $type, &$tables, &$whereTables, &$contactID, &$where ) {
  if ( ! $contactID ) {
    return;
  }
  
  if ($type == CRM_Core_Permission::VIEW) {  
    $config = CRM_Expertaccess_Config::singleton();
    $relation_table = 'civicrm_relationship';
    $tables[$relation_table] = $whereTables[$relation_table] = "LEFT JOIN `civicrm_relationship` `expert_rel` ON `contact_a`.`id` = `expert_rel`.`contact_id_a`";
    $clause = "(
        `expert_rel`.`case_id` IS NOT NULL 
        AND `expert_rel`.`relationship_type_id` = '".$config->getExpertRelationshipType('id')."'
        AND `expert_rel`.`is_active` = '1' 
        AND (`expert_rel`.`start_date` IS NULL OR `expert_rel`.`start_date` <= NOW()) 
        AND (`expert_rel`.`end_date` IS NULL OR `expert_rel`.`end_date` >= NOW()) 
        AND `expert_rel`.`contact_id_b` = '".$contactID."') OR (`contact_a`.`id` = '".$contactID."' AND `contact_a`.`contact_sub_type` LIKE '%".CRM_Core_DAO::VALUE_SEPARATOR."Expert".CRM_Core_DAO::VALUE_SEPARATOR."%')";
    if (!empty($where)) {
      $where = "(".$where . " OR ".$clause.")";
    } else {
      $where = $clause;
    }
  }
}

/** 
 * Place a link to edit  portal profile for expert
 * 
 * Implementation of hook_civicrm_summary
 * 
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_summary
 */
function expertaccess_civicrm_pageRun(&$page) {
  if ($page instanceof CRM_Contact_Page_View_Summary) {
    $portalProfileLink = new CRM_Expertaccess_PortalProfileLink($page->getVar('_contactId'));
    if ($portalProfileLink->userIsExpert()) {
      CRM_Core_Region::instance('page-body')->add(array(
        'template' => "CRM/Contact/Page/View/Summary/portal_profile.tpl"
      ));
      $smarty = CRM_Core_Smarty::singleton();
      $smarty->assign('link_to_portal_profile', $portalProfileLink->getLink());
    }
  }
}

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function expertaccess_civicrm_config(&$config) {
  _expertaccess_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function expertaccess_civicrm_xmlMenu(&$files) {
  _expertaccess_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function expertaccess_civicrm_install() {
  return _expertaccess_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function expertaccess_civicrm_uninstall() {
  return _expertaccess_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function expertaccess_civicrm_enable() {
  return _expertaccess_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function expertaccess_civicrm_disable() {
  return _expertaccess_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function expertaccess_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _expertaccess_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function expertaccess_civicrm_managed(&$entities) {
  return _expertaccess_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function expertaccess_civicrm_caseTypes(&$caseTypes) {
  _expertaccess_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function expertaccess_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _expertaccess_civix_civicrm_alterSettingsFolders($metaDataFolders);
}
