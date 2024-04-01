<?php

/**
 * Replace variables in a string
 */
function populate_variables(string $text, array $variables): string {
	$pattern = '/\{\{\s*\$(\w+)\s*\}\}/';

	$replacedText = preg_replace_callback($pattern, function ($matches) use ($variables) {
		$variableName = $matches[1];

		return isset($variables[$variableName]) ? $variables[$variableName] : $matches[0];
	}, $text);

	return $replacedText;
}
