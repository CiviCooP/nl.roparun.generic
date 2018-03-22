<?php

require_once 'generic.civix.php';


/**
 * Implements hook_civicrm_tokens().
 *
 * @link https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_tokens
 *
 * Provide the tokens defined in this extensions
 */
function generic_civicrm_tokens(&$tokens) {
	$contributionTokenClass = CRM_Generic_Tokens_Contribution::singleton();
	$contributionTokenClass->tokens($tokens);
}
/**
 * Implements hook__civicrm_tokenValues().
 *
 * @link https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_tokenValues
 *
 * Provide the implementation of the tokens of this extension
 */
function generic_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null) {
  $contributionTokenClass = CRM_Generic_Tokens_Contribution::singleton();
  $contributionTokenClass->tokenValues($values, $cids, $tokens);
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function generic_civicrm_config(&$config) {
  _generic_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function generic_civicrm_xmlMenu(&$files) {
  _generic_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function generic_civicrm_install() {
	_generic_required_extensions_installed();
	_generic_load_config_items();
  _generic_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function generic_civicrm_postInstall() {
  _generic_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function generic_civicrm_uninstall() {
  _generic_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function generic_civicrm_enable() {
	_generic_required_extensions_installed();
	_generic_load_config_items();
  _generic_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function generic_civicrm_disable() {
  _generic_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function generic_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _generic_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function generic_civicrm_managed(&$entities) {
	_generic_load_config_items();
  _generic_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function generic_civicrm_caseTypes(&$caseTypes) {
  _generic_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function generic_civicrm_angularModules(&$angularModules) {
  _generic_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function generic_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _generic_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

function _generic_load_config_items() {
	$path = realpath(__DIR__);
	return civicrm_api3('Civiconfig', 'load_json', array(
		'path' => $path . '/resources/', 
	));
}

/**
 * Function to check if the required extensions are installed
 *
 * @throws Exception
 */
function _generic_required_extensions_installed() {
  $required = array(
    'org.civicoop.configitems' => FALSE,
    'org.civicoop.civirules' => FALSE,
    'org.civicoop.emailapi' => FALSE,
  );
  $installedExtensions = civicrm_api3('Extension', 'get', array(
    'option' => array('limit' => 0,),));
  foreach ($installedExtensions['values'] as $installedExtension) {
    if (isset($required[$installedExtension['key']]) && $installedExtension['status'] == 'installed') {
      $required[$installedExtension['key']] = TRUE;
    }
  }
  foreach ($required as $requiredExtension => $installed) {
    if (!$installed) {
      throw new Exception('Required extension '.$requiredExtension.' is not installed, can not install or enable 
      nl.roparun.generic. Please install the extension and then retry installing or enabling 
      nl.roparun.generic');
    }
  }
}

function _generic_is_civirules_installed() {
	$civiRulesInstalled = FALSE;
	$emailApiInstalled = FALSE;
  try {
    $extensions = civicrm_api3('Extension', 'get', array('options' => array('limit' => 0)));
    foreach($extensions['values'] as $ext) {
      if ($ext['key'] == 'org.civicoop.civirules' && ($ext['status'] == 'installed' || $ext['status'] == 'disabled')) {
        $civiRulesInstalled = TRUE;
      }
			if ($ext['key'] == 'org.civicoop.emailapi' && ($ext['status'] == 'installed' || $ext['status'] == 'disabled')) {
        $emailApiInstalled = TRUE;
      }
    }
		if ($emailApiInstalled && $civiRulesInstalled) {
			return TRUE;
		}
  }
  catch (Exception $e) {
    return FALSE;
  }
  return FALSE;
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function generic_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function generic_civicrm_navigationMenu(&$menu) {
  _generic_civix_insert_navigation_menu($menu, NULL, array(
    'label' => ts('The Page', array('domain' => 'nl.roparun.generic')),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _generic_civix_navigationMenu($menu);
} // */
