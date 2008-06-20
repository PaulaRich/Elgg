<?php

	/**
	 * Elgg profile plugin edit action
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
		
	// Load configuration
		global $CONFIG;

	// Get profile fields
		$input = array();
		foreach($CONFIG->profile as $shortname => $valuetype) {
			$input[$shortname] = get_input($shortname);
			if ($valuetype == 'tags')
				$input[$shortname] = string_to_tag_array($input[$shortname]);
		}
		
	// Save stuff if we can, and forward to the user's profile
		
		if ($user = page_owner()) {
			$user = page_owner_entity();			
		} else {
			$user = $_SESSION['user'];
			set_page_owner($user->getGUID());
		}
		if ($user->canEdit()) {
			
			// Save stuff
			if (sizeof($input) > 0)
				foreach($input as $shortname => $value) {
					$user->$shortname = $value;
				}
			$user->save();

			system_message(elgg_echo("profile:saved"));
			
			// Forward to the user's profile
			forward($user->getUrl());

		} else {
	// If we can't, display an error
			
			system_message(elgg_echo("profile:cantedit"));
		}

?>