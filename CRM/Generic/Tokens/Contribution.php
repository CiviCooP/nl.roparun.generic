<?php

class CRM_Generic_Tokens_Contribution {
	
	private $token_name = 'donation';
	
	private $contributions = array();
	
	private static $instance;
	
	private function __construct() {
		
	}
	
	public static function singleton() {
		if (!self::$instance) {
			self::$instance = new CRM_Generic_Tokens_Contribution();
		}
		return self::$instance;
	}
	
	public function tokens(&$tokens) {
		$tokens[$this->token_name][$this->token_name.'.amount'] = ts('Amount donated');	
		$tokens[$this->token_name][$this->token_name.'.donor_name'] = ts('Donor name');
		$tokens[$this->token_name][$this->token_name.'.donor_city'] = ts('City of donor');
		$tokens[$this->token_name][$this->token_name.'.anonym'] = ts('Donor anonym on website'); 
	}
	
	public function tokenValues(&$values, $cids, $tokens = array()) {
    if ($this->isTokenInTokens($tokens, 'amount')) {
      $this->amountToken($values, $cids, 'amount');
    }
		if ($this->isTokenInTokens($tokens, 'donor_name')) {
      $this->nameToken($values, $cids, 'donor_name');
    }
		if ($this->isTokenInTokens($tokens, 'donor_city')) {
      $this->cityToken($values, $cids, 'donor_city');
    }
		if ($this->isTokenInTokens($tokens, 'anonym')) {
      $this->anonymToken($values, $cids, 'anonym');
    }
	}
	
	private function anonymToken(&$values, $cids, $token) {
		$config = CRM_Generic_Config::singleton();
    $contact_ids = $cids;
    if (!is_array($contact_ids)) {
      $contact_ids = array($contact_ids);
    }
    $tokenValues = array();
		
    foreach($contact_ids as $contact_id) {
    	$contribution = $this->getContribution($values, $contact_id);
			if ($contribution) {
				if (isset($contribution['custom_'.$config->getDonateAnonymousCustomFieldId()]) && $contribution['custom_'.$config->getDonateAnonymousCustomFieldId()] == $config->getDonateAnonymousOptionValue()) {					
					$tokenValues[$contact_id] = ts('anoniem');
				} 
			}
    }
    $this->setTokenValue($values, $cids, $token, $tokenValues);
  }
	
	private function amountToken(&$values, $cids, $token) {
    $contact_ids = $cids;
    if (!is_array($contact_ids)) {
      $contact_ids = array($contact_ids);
    }
    $tokenValues = array();
		
    foreach($contact_ids as $contact_id) {
    	$contribution = $this->getContribution($values, $contact_id);
			if ($contribution) {
				$tokenValues[$contact_id] = CRM_Utils_Money::format($contribution['total_amount'], $contribution['currency']);
			}
    }
    $this->setTokenValue($values, $cids, $token, $tokenValues);
  }

	private function nameToken(&$values, $cids, $token) {
    $contact_ids = $cids;
    if (!is_array($contact_ids)) {
      $contact_ids = array($contact_ids);
    }
    $tokenValues = array();
		
    foreach($contact_ids as $contact_id) {
    	$contribution = $this->getContribution($values, $contact_id);
			if ($contribution) {
				$tokenValues[$contact_id] = $contribution['display_name'];
			}
    }
    $this->setTokenValue($values, $cids, $token, $tokenValues);
  }
	
	private function cityToken(&$values, $cids, $token) {
    $contact_ids = $cids;
    if (!is_array($contact_ids)) {
      $contact_ids = array($contact_ids);
    }
    $tokenValues = array();
		
    foreach($contact_ids as $contact_id) {
    	$contribution = $this->getContribution($values, $contact_id);
			if ($contribution) {
				try {
					$tokenValues[$contact_id] = civicrm_api3('Address', 'getvalue', array(
						'contact_id' => $contribution['contact_id'],
						'is_primary' => true,
						'return' => 'city'
					));	
				} catch (Exception $e) {
					// Do nothing
				}
				
			}
    }
    $this->setTokenValue($values, $cids, $token, $tokenValues);
  }
	
	private function getContribution($values, $cid) {
		$contribution_id = false;
		if (isset($values[$cid]) && is_array($values[$cid]) && isset($values[$cid]['contribution_id'])) {
			$contribution_id = $values[$cid]['contribution_id'];
		} elseif ($values['contribution_id']) {
			$contribution_id = $values['contribution_id'];
		}
		
		if (!$contribution_id) {
			return false;
		}
		
		if (!isset($this->contributions[$contribution_id])) {
			$this->contributions[$contribution_id] = civicrm_api3('Contribution', 'getsingle', array('id' => $contribution_id));
		}
		return $this->contributions[$contribution_id];
	}
	
  /**
   * Check whether a token is present in the set of tokens.
   *
   * @param $tokens
   * @param $token
   * @return bool
   */
  protected function isTokenInTokens($tokens, $token) {
    if (in_array($token, $tokens)) {
      return true;
    } elseif (isset($tokens[$token])) {
      return true;
    } elseif (isset($tokens[$this->token_name]) && in_array($token, $tokens[$this->token_name])) {
      return true;
    } elseif (isset($tokens[$this->token_name][$token])) {
      return true;
    }
    return FALSE;
  }
  /**
   * Set the value for a token and checks whether cids is an array or not.
   *
   * @param $values
   * @param $cids
   * @param $token
   * @param $tokenValues
   */
  protected function setTokenValue(&$values, $cids, $token, $tokenValues) {
    if (is_array($cids)) {
      foreach ($cids as $cid) {
        $values[$cid][$this->token_name . '.' . $token] = $tokenValues[$cid];
      }
    }
    else {
      $values[$this->token_name . '.' . $token] = $tokenValues[$cids];
    }
  }
}
