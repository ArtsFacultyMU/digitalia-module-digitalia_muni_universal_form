<?php

namespace Drupal\digitalia_muni_universal_form\Plugin\FormAlter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\pluginformalter\Annotation\InlineEntityFormAlter;
use Drupal\pluginformalter\Plugin\FormAlterBase;

/**
 * Class IEFAlter.
 *
 * @InlineEntityFormAlter(
 *	 id = "ief_form_alter",
 *	 label = @Translation("IEF form alter."),
 *	 type = "entity_form",
 *	 entity_type = "node",
 *	 bundle = "*"
 * )
 *
 * @package Drupal\sen_core\Plugin\FormAlter
 */
class IEFAlter extends FormAlterBase {

	/**
	 * @inheritDoc
	 */
	public function formAlter(array &$form, FormStateInterface $form_state, $form_id)
	{
		//\Drupal::logger("TEST_ALTER")->debug("FORM ALTERED");
		//\Drupal::logger("TEST_ALTER")->debug($form_id);
		//\Drupal::logger("TEST_ALTER")->debug(print_r(array_keys($form), TRUE));
		//\Drupal::logger("TEST_ALTER")->debug(print_r($form["#id"], TRUE));
		//\Drupal::logger("TEST_ALTER")->debug(print_r($form["#type"], TRUE));
		//\Drupal::logger("TEST_ALTER")->debug(print_r($form["#entity_type"], TRUE));
		//\Drupal::logger("TEST_ALTER_ID")->debug(print_r($form["#id"], TRUE));
		//\Drupal::logger("TEST_ALTER_ENTITY")->debug(print_r($form["#entity"]->id(), TRUE));
		//\Drupal::logger("TEST_ALTER_PARENTS")->debug(print_r($form["#parents"], TRUE));
		//\Drupal::logger("TEST_ALTER_ARRAY_PARENTS")->debug(print_r($form["#array_parents"], TRUE));
		alterNodeForm($form, $form_state, $form_id, $form["#entity"]);
	}

}
