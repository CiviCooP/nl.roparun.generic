<?php

namespace Civi\Roparun\ActionProvider\CompilerPass;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Actions implements CompilerPassInterface {
  
  public function process(ContainerBuilder $container) {
    if (!$container->hasDefinition('action_provider')) {
      return;
    }
    $actionProviderDefinition = $container->getDefinition('action_provider');
    $actionProviderDefinition->addMethodCall('addAction', array(new Definition('Civi\Roparun\ActionProvider\Action\CurrentEvent')));
    $actionProviderDefinition->addMethodCall('addAction', array(new Definition('Civi\Roparun\ActionProvider\Action\GetCampaignFromEvent')));
    $actionProviderDefinition->addMethodCall('addAction', array(new Definition('Civi\Roparun\ActionProvider\Action\ContactIsMemberOfTeam')));
  }
  
}
