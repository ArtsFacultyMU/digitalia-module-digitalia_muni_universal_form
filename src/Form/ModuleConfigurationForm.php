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
	 * {@ingeritdoc}
	 */
	public function getFormId()
	{
		return 'digitalia_muni_universal_form';
	}

	/**
	 * {@ingeritdoc}
	 */
	protected function getEditableConfigNames()
	{
		return [
			'digitalia_muni_universal_form.settings',
		];
	}

	/**
	 * {@ingeritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state)
	{
		$config = $this->config('digitalia_muni_universal_form.settings');


		//$groups = GroupMembership::loadBy
		//$relationships = GroupRelationShip::loadByPluginId('');

		$gids = \Drupal::entityQuery('group')->accessCheck(FALSE)->execute();
		//dpm(print_r($gids, TRUE));

		$groups = Group::loadMultiple($gids);

		foreach ($groups as $group) {
			dpm($group->id() . ": " . $group->label());
		}

		//$group = Group::load('9');
		//dpm(print_r($group, TRUE));

		$form['group_field_config'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Group configuration'),
			'#description' => $this->t('Configures enabled fields for each group. Format is gid::list_of_fields'),
			'#default_value' => $config->get('group_field_config'),
		];

		$form['group_field_required'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Required fields'),
			'#description' => $this->t('Configures required fields for each group. Format is gid::list_of_fields'),
			'#default_value' => $config->get('group_field_required'),
		];

		$form['enabled_form_ids'] = [
			'#type' => 'textarea',
			'#title' => $this->t('Enabled form IDs'),
			'#description' => $this->t('Selects which forms should be altered'),
			'#default_value' => $config->get('enabled_form_ids'),
		];


		return parent::buildForm($form, $form_state);
	}

	/**
	 * {@ingeritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state)
	{
		$this->config('digitalia_muni_universal_form.settings')->set('group_field_config', $form_state->getValue('group_field_config'));
		$this->config('digitalia_muni_universal_form.settings')->set('group_field_required', $form_state->getValue('group_field_required'));
		$this->config('digitalia_muni_universal_form.settings')->set('enabled_form_ids', $form_state->getValue('enabled_form_ids'));
		$this->config('digitalia_muni_universal_form.settings')->save();

		parent::submitForm($form, $form_state);
	}
}
