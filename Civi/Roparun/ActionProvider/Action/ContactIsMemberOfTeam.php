<?php

namespace Civi\Roparun\ActionProvider\Action;

use \Civi\ActionProvider\Action\AbstractAction;
use \Civi\ActionProvider\Parameter\ParameterBagInterface;
use \Civi\ActionProvider\Parameter\SpecificationBag;
use \Civi\ActionProvider\Parameter\Specification;

use CRM_Generic_ExtensionUtil as E;

class ContactIsMemberOfTeam extends AbstractAction {
  
  /**
   * Run the action
   * 
   * @param ParameterInterface $parameters
   *   The parameters to this action. 
   * @param ParameterBagInterface $output
   *   The parameters this action can send back
   * @return void
   */
  protected function doAction(ParameterBagInterface $parameters, ParameterBagInterface $output) {
    $config = \CRM_Generic_Config::singleton();
    
    $sql = "SELECT COUNT(*) as total
      FROM civicrm_participant
      INNER JOIN {$config->getTeamMemberDataCustomGroupTableName()} team_member_data ON team_member_data.entity_id = civicrm_participant.id
      WHERE event_id = %1
      AND contact_id = %2
      AND team_member_data.{$config->getMemberOfTeamCustomFieldColumnName()} = %3 
    ";
    $sqlParams[1] = array($parameters->getParameter('event_id'), 'Integer');
    $sqlParams[2] = array($parameters->getParameter('contact_id'), 'Integer');
    $sqlParams[3] = array($parameters->getParameter('team_id'), 'Integer');
    
    $count = \CRM_Core_DAO::singleValueQuery($sql, $sqlParams);
    $output->setParameter('is_member_of_team', false);
    if (!$count && $this->configuration->getParameter('throw_error')) {
      throw new \Civi\ActionProvider\Exception\ExecutionException(E::ts('Contact is not a member of the team'));
    } elseif ($count) {
      $output->setParameter('is_member_of_team', true);
    }
  }
  
  /**
   * Returns the human readable title of this action
   */
  public function getTitle() {
    return E::ts('Is contact member of team'); 
  }
  
  /**
   * Returns the specification of the configuration options for the actual action.
   * 
   * @return SpecificationBag
   */
  public function getConfigurationSpecification() {
    $createError = new Specification('throw_error', 'Boolean', E::ts('Create an error'));
    $createError->setDescription(E::ts('When the contact is not a member of the team should we create an error?'));
    return new SpecificationBag(array(
      $createError,
    ));
  }
  
  /**
   * Returns the specification of the parameters of the actual action.
   * 
   * @return SpecificationBag
   */
  public function getParameterSpecification() {
    return new SpecificationBag(array(
      new Specification('event_id', 'Integer', E::ts('Event ID'), true),
      new Specification('contact_id', 'Integer', E::ts('Contact ID'), true),
      new Specification('team_id', 'Integer', E::ts('Team ID'), true)
    ));
  }
  
  /**
   * Returns the specification of the output parameters of this action.
   * 
   * This function could be overriden by child classes.
   * 
   * @return SpecificationBag
   */
  public function getOutputSpecification() {
    return new SpecificationBag(array(
      new Specification('is_member_of_team', 'Boolean', E::ts('Is member of team')),
    ));
  }
  
  /**
   * Returns the tags for this action.
   */
  public function getTags() {
    return array(
      AbstractAction::DATA_RETRIEVAL_TAG,
    );
  }
  
}