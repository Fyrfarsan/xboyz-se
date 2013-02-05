<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

function template_main()
{
	global $context, $settings, $options, $txt, $scripturl, $modSettings;

    	// Show the page index... "Pages: [1]".
    echo '<div class="container">';
	echo '
        <div class="row" id="postbuttons">
	        <div class="next">', $context['previous_next'], '</div>
	        <div class="margintop middletext floatleft">', $txt['pages'], ': ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . ' &nbsp;&nbsp;<a href="#lastPost"><strong>' . $txt['go_down'] . '</strong></a>' : '', '</div>
	        <div class="nav floatright">', template_button_strip($normal_buttons, 'bottom'), '</div>
        </div>';

//header
	echo '<div class="row">
            <div class="span12 forum-header">
            <div class="row">
            <div class="span2"><h6>',$txt['author'],'</h6></div>
            <div class="span5"></div>
            <div class="span5"><h6>', $txt['topic'], ': ', $context['subject'], ' &nbsp;(', $txt['read'], ' ', $context['num_views'], ' ', $txt['times'],')</h6></div>
            ';
    echo '</div></div></div></div>';

    echo '<div class="container">';

                 // Get all the messages...
	while ($message = $context['get_message']())
	{
        $is_first_post = !isset($is_first_post) ? true : false;
		$ignoring = false;
		if ($message['can_remove'])
			$removableMessageIDs[] = $message['id'];

        echo '<div class="row">';
            echo '<div class="span12 post-wrapper">
                       <div class="row">
                       <div class="span3 poster">
                       <h4>', $message['member']['link'], '</h4>
					<ul class="reset smalltext" id="msg_', $message['id'], '_extra_info">';

		// Show the member's custom title, if they have one.
		if (isset($message['member']['title']) && $message['member']['title'] != '')
			echo '
						<li>', $message['member']['title'], '</li>';

		// Show the member's primary group (like 'Administrator') if they have one.
		if (isset($message['member']['group']) && $message['member']['group'] != '')
			echo '
						<li>', $message['member']['group'], '</li>';

		// Don't show these things for guests.
		if (!$message['member']['is_guest'])
		{
			// Show the post group if and only if they have no other group or the option is on, and they are in a post group.
			if ((empty($settings['hide_post_group']) || $message['member']['group'] == '') && $message['member']['post_group'] != '')
				echo '
						<li>', $message['member']['post_group'], '</li>';
			echo '
						<li>', $message['member']['group_stars'], '</li>';

			// Is karma display enabled?  Total or +/-?
			if ($modSettings['karmaMode'] == '1')
				echo '
						<li class="margintop">', $modSettings['karmaLabel'], ' ', $message['member']['karma']['good'] - $message['member']['karma']['bad'], '</li>';
			elseif ($modSettings['karmaMode'] == '2')
				echo '
						<li class="margintop">', $modSettings['karmaLabel'], ' +', $message['member']['karma']['good'], '/-', $message['member']['karma']['bad'], '</li>';

			// Is this user allowed to modify this member's karma?
			if ($message['member']['karma']['allow'])
				echo '
						<li>
							<a href="', $scripturl, '?action=modifykarma;sa=applaud;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.' . $context['start'], ';m=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $modSettings['karmaApplaudLabel'], '</a>
							<a href="', $scripturl, '?action=modifykarma;sa=smite;uid=', $message['member']['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';m=', $message['id'], ';', $context['session_var'], '=', $context['session_id'], '">', $modSettings['karmaSmiteLabel'], '</a>
						</li>';

			// Show online and offline buttons?
			if (!empty($modSettings['onlineEnable']))
				echo '
						<li>', $context['can_send_pm'] ? '<a href="' . $message['member']['online']['href'] . '" title="' . $message['member']['online']['label'] . '">' : '', $settings['use_image_buttons'] ? '<img src="' . $message['member']['online']['image_href'] . '" alt="' . $message['member']['online']['text'] . '" border="0" style="margin-top: 2px;" />' : $message['member']['online']['text'], $context['can_send_pm'] ? '</a>' : '', $settings['use_image_buttons'] ? '<span class="smalltext"> ' . $message['member']['online']['text'] . '</span>' : '', '</li>';

			// Show the member's gender icon?
			if (!empty($settings['show_gender']) && $message['member']['gender']['image'] != '' && !isset($context['disabled_fields']['gender']))
				echo '
						<li>', $txt['gender'], ': ', $message['member']['gender']['image'], '</li>';

			// Show how many posts they have made.
			if (!isset($context['disabled_fields']['posts']))
				echo '
						<li>', $txt['member_postcount'], ': ', $message['member']['posts'], '</li>';

			// Any custom fields for standard placement?
			if (!empty($message['member']['custom_fields']))
			{
				foreach ($message['member']['custom_fields'] as $custom)
					if (empty($custom['placement']) && !empty($custom['value']))
						echo '
						<li>', $custom['title'], ': ', $custom['value'], '</li>';
			}

			// Show avatars, images, etc.?
			if (!empty($settings['show_user_images']) && empty($options['show_no_avatars']) && !empty($message['member']['avatar']['image']))
				echo '
						<li class="margintop" style="overflow: auto;">', $message['member']['avatar']['image'], '</li>';

			// Show their personal text?
			if (!empty($settings['show_blurb']) && $message['member']['blurb'] != '')
				echo '
						<li class="margintop">', $message['member']['blurb'], '</li>';

			// Any custom fields to show as icons?
			if (!empty($message['member']['custom_fields']))
			{
				$shown = false;
				foreach ($message['member']['custom_fields'] as $custom)
				{
					if ($custom['placement'] != 1 || empty($custom['value']))
						continue;
					if (empty($shown))
					{
						$shown = true;
						echo '
						<li class="margintop">
							<ul class="reset nolist">';
					}
					echo '
								<li>', $custom['value'], '</li>';
				}
				if ($shown)
					echo '
							</ul>
						</li>';
			}

			// This shows the popular messaging icons.
			if ($message['member']['has_messenger'] && $message['member']['can_view_profile'])
				echo '
						<li class="margintop">
							<ul class="reset nolist">
								', !isset($context['disabled_fields']['icq']) && !empty($message['member']['icq']['link']) ? '<li>' . $message['member']['icq']['link'] . '</li>' : '', '
								', !isset($context['disabled_fields']['msn']) && !empty($message['member']['msn']['link']) ? '<li>' . $message['member']['msn']['link'] . '</li>' : '', '
								', !isset($context['disabled_fields']['aim']) && !empty($message['member']['aim']['link']) ? '<li>' . $message['member']['aim']['link'] . '</li>' : '', '
								', !isset($context['disabled_fields']['yim']) && !empty($message['member']['yim']['link']) ? '<li>' . $message['member']['yim']['link'] . '</li>' : '', '
							</ul>
						</li>';

			// Show the profile, website, email address, and personal message buttons.
			if ($settings['show_profile_buttons'])
			{
				echo '
						<li class="margintop">
							<ul class="reset nolist">';
				// Don't show the profile button if you're not allowed to view the profile.
				if ($message['member']['can_view_profile'])
					echo '
								<li><a href="', $message['member']['href'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt['view_profile'] . '" title="' . $txt['view_profile'] . '" border="0" />' : $txt['view_profile']), '</a></li>';

				// Don't show an icon if they haven't specified a website.
				if ($message['member']['website']['url'] != '' && !isset($context['disabled_fields']['website']))
					echo '
								<li><a href="', $message['member']['website']['url'], '" title="' . $message['member']['website']['title'] . '" target="_blank" class="new_win">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/www_sm.gif" alt="' . $message['member']['website']['title'] . '" border="0" />' : $txt['www']), '</a></li>';

				// Don't show the email address if they want it hidden.
				if (in_array($message['member']['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
					echo '
								<li><a href="', $scripturl, '?action=emailuser;sa=email;msg=', $message['id'], '" rel="nofollow">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . '" />' : $txt['email']), '</a></li>';

				// Since we know this person isn't a guest, you *can* message them.
				if ($context['can_send_pm'])
					echo '
								<li><a href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '" title="', $message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline'], '">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/im_' . ($message['member']['online']['is_online'] ? 'on' : 'off') . '.gif" alt="' . ($message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline']) . '" border="0" />' : ($message['member']['online']['is_online'] ? $txt['pm_online'] : $txt['pm_offline']), '</a></li>';

				echo '
							</ul>
						</li>';
			}

			// Are we showing the warning status?
			if ($message['member']['can_see_warning'])
				echo '
						<li>', $context['can_issue_warning'] ? '<a href="' . $scripturl . '?action=profile;area=issuewarning;u=' . $message['member']['id'] . '">' : '', '<img src="', $settings['images_url'], '/warning_', $message['member']['warning_status'], '.gif" alt="', $txt['user_warn_' . $message['member']['warning_status']], '" />', $context['can_issue_warning'] ? '</a>' : '', '<span class="warn_', $message['member']['warning_status'], '">', $txt['warn_' . $message['member']['warning_status']], '</span></li>';
		}
		// Otherwise, show the guest's email.
		elseif (!empty($message['member']['email']) && in_array($message['member']['show_email'], array('yes', 'yes_permission_override', 'no_through_forum')))
			echo '
						<li><a href="', $scripturl, '?action=emailuser;sa=email;msg=', $message['id'], '" rel="nofollow">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt['email'] . '" title="' . $txt['email'] . '" border="0" />' : $txt['email']), '</a></li>';

		// Done with the information about the poster... on to the post itself.
		echo '
					</ul>
                       </div>
                       <div class="span9">
                       <div class="flow_hidden">
                       <div class="keyinfo">
									<div class="messageicon">
										<img src="', $message['icon_url'] . '" alt=""', $message['can_modify'] ? ' id="msg_icon_' . $message['id'] . '"' : '', ' />
									</div>
									<h5 id="subject_', $message['id'], '">
										<a href="', $message['href'], '" rel="nofollow">', $message['subject'], '</a>
									</h5>
									<div class="smalltext">&#171; <strong>', !empty($message['counter']) ? $txt['reply_noun'] . ' #' . $message['counter'] : '', ' ', $txt['on'], ':</strong> ', $message['time'], ' &#187;</div>
									<div id="msg_', $message['id'], '_quick_mod"></div>
						</div>
                        </div>

                       <div class="inner" id="msg_', $message['id'], '"', '>', $message['body'], '</div>
                       </div>
                  </div></div>';
                  
        echo '</div>';
        
        
    }

    echo '</div>';
}

?>