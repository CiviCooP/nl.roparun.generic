<?php

/**
 * Civiconfig.LoadJson API method.
 * Calls the loader to add and update config items from JSON files.
 *
 * @param array $params Parameters
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_civiconfig_load_roparun($params) {
	$path = realpath(__DIR__ . '/../../../');
	return civicrm_api3('Civiconfig', 'load_json', array(
			'path' => $path . '/resources/', 
		));
}