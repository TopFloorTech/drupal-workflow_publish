<?php

namespace Drupal\workflow_publish\Plugin\Action;


use Drupal\aluminum_storage\Aluminum\Config\ConfigManager;
use Drupal\Core\Action\ActionBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ChangeWorkflowStateBase extends ActionBase implements ContainerFactoryPluginInterface {
  /**
   * @var string The workflow state to set to.
   */
  protected $workflowState = 'default_workflow_published';

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var ContentEntityInterface $object */

    $result = $object->access('update', $account, TRUE);
    $fieldName = $this->getWorkflowField();

    if ($object->hasField($fieldName)) {
      $result->andIf($object->get($fieldName)->access('edit', $account, TRUE));
    }

    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    /** @var ContentEntityInterface $entity */

    $fieldName = $this->getWorkflowField();
    $newValue = $this->getWorkflowState();

    if ($entity->hasField($fieldName)) {
      $entity->get($fieldName)->setValue($newValue);
      $entity->save();
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Returns the ID of the workflow state to set the field to.
   *
   * @return string The ID of the workflow state to set
   */
  protected function getWorkflowState() {
    return $this->workflowState;
  }

  /**
   * Returns the field name of the Workflow field to perform actions on.
   *
   * @return string The field name of the Workflow field
   */
  protected function getWorkflowField() {
    $config = ConfigManager::getConfig('admin');
    return $config->getValue('workflow_publish_field', 'workflow');
  }
}
