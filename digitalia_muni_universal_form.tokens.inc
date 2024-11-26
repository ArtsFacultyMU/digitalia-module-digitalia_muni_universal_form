<?php

use Drupal\group\Entity\GroupRelationship;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Utility\Token;
use Drupal\Component\Utility\Html;

/**
 * Implements hook_token_info().
 */
function digitalia_muni_universal_form_token_info()
{
	$type = [
		"name" => t("Digitalia group tokens"),
		"description" => t("Tokens for Digitalia"),
	];

	$node["group_ids"] = [
		"name" => t("Group IDs"),
		"description" => t("IDs of related groups"),
	];

	return [
		"types" => ["digitalia_muni_groups" => $type],
		"tokens" => ["digitalia_muni_groups" => $node],
	];
}

function digitalia_muni_universal_form_tokens($type, $tokens, array $data, array $options, BubbleableMetadata $bubbleable_metadata)
{
	$replacements = [];

	if ($type != "digitalia_muni_groups") {
		return $replacements;
	}

	foreach ($tokens as $name => $original) {
		if ($name == "group_ids") {
			$replacements[$original] = " " . digitalia_muni_group_ids($data["node"]);
		}
	}

	\Drupal::logger("TEST_TOKEN")->debug(print_r($replacements, TRUE));
	return $replacements;
}

/**
 * Gets group IDs of a given node.
 *
 * @param object $node
 *   A core drupal node object.
 *
 * @return string
 *   The tokenized value for the given data. Multiple entries are separated with '+'
 */
function digitalia_muni_group_ids($node)
{
        $gids = [];
        if (is_null($node)) {
                $group = \Drupal::routeMatch()->getParameter("group");

		if (is_null($group)) {
			\Drupal::logger("TEST_TOKEN")->debug(print_r(\Drupal::routeMatch()->getParameters(), TRUE));
			return "";
		}

		return $group->id();
        }

        $group_contents = GroupRelationship::loadByEntity($node);

        foreach ($group_contents as $group_content) {
                $gids[] = $group_content->getGroup()->id();
        }

        return implode("+", $gids);
}
