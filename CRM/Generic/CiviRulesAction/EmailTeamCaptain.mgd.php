<?php

if (_generic_is_civirules_installed()) {
  return array (
    0 =>
      array (
        'name' => 'Civirules:Action.EmailTeamCaptain',
        'entity' => 'CiviRuleAction',
        'params' =>
          array (
            'version' => 3,
            'name' => 'emailteamcaptain',
            'label' => 'Send e-mail to Team Captain about donation',
            'class_name' => 'CRM_Generic_CiviRulesAction_EmailTeamCaptain',
            'is_active' => 1
          ),
      ),
  );
}
else { return array(); }