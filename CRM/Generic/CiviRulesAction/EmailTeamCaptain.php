<?php

class CRM_Generic_CiviRulesAction_EmailTeamCaptain extends CRM_Civirules_Action {
	
	/**
   * Process the action
   *
   * @param CRM_Civirules_TriggerData_TriggerData $triggerData
   * @access public
   */
  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
    $params = $this->getActionParameters();

		$config = CRM_Generic_Config::singleton();
		$contribution = $triggerData->getEntityData('Contribution');
		$params['contribution_id'] = $contribution['id'];
		$team_contact_id = false;
		if (isset($contribution['custom_'.$config->getTowardsTeamCustomFieldId()]) && !empty($contribution['custom_'.$config->getTowardsTeamCustomFieldId()])) {
			$team_contact_id = $contribution['custom_'.$config->getTowardsTeamCustomFieldId()];
		}
		if (!$team_contact_id) {
			return; // this contribution is not linked to a team. So return
		}

		$team_captain_contact_ids = CRM_Generic_Team::getTeamCaptainContacts($team_contact_id);
		if (!count($team_captain_contact_ids)) {
			return; // there is no team captain found.
		}
		foreach($team_captain_contact_ids as $team_captain_contact_id) {
			if ($team_captain_contact_id) {
    		$params['contact_id'] = $team_captain_contact_id;
    		//execute the action
    		$this->executeApiAction('Email', 'send', $params);
			}
		}
  }
	
	/**
   * Executes the action
   *
   * This method could be overridden if needed
   *
   * @param $entity
   * @param $action
   * @param $parameters
   * @access protected
   * @throws Exception on api error
   */
  protected function executeApiAction($entity, $action, $parameters) {
    try {
      civicrm_api3($entity, $action, $parameters);
    } catch (Exception $e) {
      $formattedParams = '';
      foreach($parameters as $key => $param) {
        if (strlen($formattedParams)) {
          $formattedParams .= ', ';
        }
        $formattedParams .= "{$key}=\"$param\"";
      }
      $message = "Civirules api action exception: {$e->getMessage()}. API call: {$entity}.{$action} with params: {$formattedParams}";
      CRM_Core_Error::debug_log_message($message);
      throw new Exception($message);
    }
  }

  /**
   * Returns a redirect url to extra data input from the user after adding a action
   *
   * Return false if you do not need extra data input
   *
   * @param int $ruleActionId
   * @return bool|string
   * $access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return CRM_Utils_System::url('civicrm/civirules/actions/emailteamcaptain', 'rule_action_id='.$ruleActionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    $template = 'unknown template';
    $params = $this->getActionParameters();
    $version = CRM_Core_BAO_Domain::version();
    // Compatibility with CiviCRM > 4.3
    if($version >= 4.4) {
      $messageTemplates = new CRM_Core_DAO_MessageTemplate();
    } else {
      $messageTemplates = new CRM_Core_DAO_MessageTemplates();
    }
    $messageTemplates->id = $params['template_id'];
    $messageTemplates->is_active = true;
    if ($messageTemplates->find(TRUE)) {
      $template = $messageTemplates->msg_title;
    }

    $to = ts('Team Captain');
		
		$cc = '';
		if (isset($params['cc']) && !empty($params['cc'])) {
      $cc = ts(' and cc to %1', array(1=>$params['cc']));
    }
		$bcc = '';
		if (isset($params['bcc']) && !empty($params['bcc'])) {
      $bcc = ts(' and bcc to %1', array(1=>$params['bcc']));
    }

    return ts('Send e-mail from "%1 (%2)" with template "%3" to %4 %5 %6', array(
        1=>$params['from_name'],
        2=>$params['from_email'],
        3=>$template,
        4 => $to,
        5 => $cc,
        6 => $bcc
    ));
  }
	
	/**
   * This function validates whether this action works with the selected trigger.
   *
   * This function could be overriden in child classes to provide additional validation
   * whether an action is possible in the current setup. 
   *
   * @param CRM_Civirules_Trigger $trigger
   * @param CRM_Civirules_BAO_Rule $rule
   * @return bool
   */
  public function doesWorkWithTrigger(CRM_Civirules_Trigger $trigger, CRM_Civirules_BAO_Rule $rule) {
  	if ($trigger instanceof CRM_CivirulesPostTrigger_Contribution) {
  		return true;
  	}
    return false;
  }
}
