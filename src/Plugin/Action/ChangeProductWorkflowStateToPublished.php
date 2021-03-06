<?php
/**
 * Created by PhpStorm.
 * User: ben
 * Date: 12/2/16
 * Time: 12:28 PM
 */

namespace Drupal\workflow_publish\Plugin\Action;

use Drupal\Core\Annotation\Action;
use Drupal\Core\Annotation\Translation;

/**
 * Sets a product's workflow state to Published.
 *
 * @Action(
 *   id = "change_product_workflow_state_to_published_action",
 *   label = @Translation("Change a product's workflow state to Published"),
 *   type = "commerce_product"
 * )
 */
class ChangeProductWorkflowStateToPublished extends ChangeWorkflowStateBase {
  protected $workflowState = 'default_workflow_published';
}
