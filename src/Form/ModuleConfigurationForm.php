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

		$node_types = \Drupal::service("entity_type.bundle.info")->getAllBundleInfo()["node"];
		$types_with_label = array();

		foreach ($node_types as $node_type => $value) {
			$types_with_label[$node_type] = $value["label"];
		}

		// admin user should be member and admin of all used groups
		$all_gids = \Drupal::entityQuery("group")->accessCheck(TRUE)->execute();

		$groups = Group::loadMultiple($all_gids);

		$groups_with_label = array();

		foreach ($groups as $group_type) {
			$groups_with_label[$group_type->id()] = $group_type->label();
		}

		$form["vertical_tabs"] = [
			"#type" => "vertical_tabs",
			"#title" => $this->t("Settings for node types"),
		];

		foreach ($types_with_label as $node_type => $node_label) {
			$form[$node_type] = [
				"#type" => "details",
				"#title" => $node_label,
				"#tree" => TRUE,
				"#group" => "vertical_tabs",
			];

			$form[$node_type]["enabled"] = [
				"#type" => "checkbox",
				"#title" => $this->t("Enabled"),
				"#description" => $this->t("Enables form alteration for content type."),
				"#default_value" => $config->get($node_type . ".enabled"),
			];

			$fields = \Drupal::service("entity_field.manager")->getFieldDefinitions("node", $node_type);
			$fields_with_label = array();

			foreach ($fields as $field_id => $field) {
				// control only user defined fields TODO: deal with 'body' field in 'Article' content type
				if (!str_starts_with($field_id, "field_")) {
					continue;
				}

				$fields_with_label[$field_id] = $field->label();
			}

			foreach ($groups_with_label as $group_id => $label) {
				$form[$node_type]["groups"][$group_id] = [
					"#type" => "details",
					"#title" => $label,
					"#description" => $this->t("Select enabled fields."),
				];

				$config_group = $config->get($node_type . ".groups." . $group_id);

				foreach ($fields_with_label as $field_id => $label) {
					$form[$node_type]["groups"][$group_id][$field_id] = [
						"#type" => "checkbox",
						"#title" => $label,
						"#default_value" => $config_group[$field_id],
					];
				}
			}
		}

		return parent::buildForm($form, $form_state);
	}

	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state)
	{
		$node_types = array_keys(\Drupal::service("entity_type.bundle.info")->getAllBundleInfo()["node"]);
		foreach ($node_types as $type) {
			$this->config("digitalia_muni_universal_form.settings")->set($type, $form_state->getValue($type));
		}

		$this->config("digitalia_muni_universal_form.settings")->save();

		parent::submitForm($form, $form_state);
	}
}
