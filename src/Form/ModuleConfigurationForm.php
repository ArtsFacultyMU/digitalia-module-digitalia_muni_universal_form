<?php

namespace Drupal\digitalia_muni_universal_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\group\Entity\GroupRelationship;
use Drupal\group\Entity\GroupMembership;
use Drupal\group\Entity\Group;

/**
 * Defines configuration form for universal form module
 */
class ModuleConfigurationForm extends ConfigFormBase
{
	/**
	 * {@inheritdoc}
	 */
	public function getFormId()
	{
		return "digitalia_muni_universal_form";
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getEditableConfigNames()
	{
		return [
			"digitalia_muni_universal_form.settings",
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state)
	{
		$config = $this->config("digitalia_muni_universal_form.settings");
		$gids = \Drupal::entityQuery("group")->accessCheck(FALSE)->execute();
		$groups = Group::loadMultiple($gids);

		// TODO: make field selection easier (checkboxes)
		$form["group_field_config"] = [
			"#type" => "textarea",
			"#title" => $this->t("Group configuration"),
			"#description" => $this->t("Configures enabled fields for each group. Format is gid::list_of_fields, fields are separated by ',' (comma)."),
			"#default_value" => $config->get("group_field_config"),
		];

		$form["enabled_form_ids"] = [
			"#type" => "textfield",
			"#title" => $this->t("Enabled form IDs"),
			"#description" => $this->t("Selects which forms should be altered. Use ',' (comma) to separate multiple values."),
			"#default_value" => $config->get("enabled_form_ids"),
		];

		return parent::buildForm($form, $form_state);
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state)
	{
		$this->config("digitalia_muni_universal_form.settings")->set("group_field_config", $form_state->getValue("group_field_config"));
		$this->config("digitalia_muni_universal_form.settings")->set("enabled_form_ids", $form_state->getValue("enabled_form_ids"));
		$this->config("digitalia_muni_universal_form.settings")->save();

		parent::submitForm($form, $form_state);
	}
}
