<?php

namespace Civi\Roparun\ActionProvider\Action;

use \Civi\ActionProvider\Action\AbstractAction;
use \Civi\ActionProvider\Parameter\ParameterBagInterface;
use \Civi\ActionProvider\Parameter\SpecificationBag;
use \Civi\ActionProvider\Parameter\Specification;

use CRM_Generic_ExtensionUtil as E;

class CurrentEvent extends AbstractAction {
  
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
    $output->setParameter('event_id', \CRM_Generic_CurrentEvent::getCurrentRoparunEventId());
  }
  
  /**
   * Returns the specification of the configuration options for the actual action.
   * 
   * @return SpecificationBag
   */
  public function getConfigurationSpecification() {
    return new SpecificationBag();
  }
  
  /**
   * Returns the specification of the parameters of the actual action.
   * 
   * @return SpecificationBag
   */
  public function getParameterSpecification() {
    return new SpecificationBag();
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
      new Specification('event_id', 'Integer', E::ts('Event ID'))
    ));
  }
  
  /**
   * Returns the human readable title of this action
   */
  public function getTitle() {
    return E::ts('Get event id of the current roparun event'); 
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
