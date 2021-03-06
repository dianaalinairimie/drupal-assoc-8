<?php

namespace Drupal\da_member_subscription\Form;

use Drupal\Core\Entity\EntityDeleteForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SubscriptionTypeDeleteForm.
 *
 * @package Drupal\da_member_subscription\Form
 */
class SubscriptionTypeDeleteForm extends EntityDeleteForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $num_nodes = $this->entityTypeManager->getStorage('da_subscription')->getQuery()
      ->condition('type', $this->entity->id())
      ->count()
      ->execute();
    if ($num_nodes) {
      $caption = '<p>' . $this->formatPlural($num_nodes, '%type is used by 1 piece of content on your site. You can not remove this content type until you have removed all of the %type content.', '%type is used by @count pieces of content on your site. You may not remove %type until you have removed all of the %type content.', ['%type' => $this->entity->getName()]) . '</p>';
      $form['#title'] = $this->getQuestion();
      $form['description'] = ['#markup' => $caption];
      return $form;
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the @entity-type %label?', [
      '@entity-type' => $this->getEntity()->getEntityType()->getLowercaseLabel(),
      '%label' => $this->getEntity()->getName(),
    ]);
  }

}
