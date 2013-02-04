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
	global $context, $settings, $options, $scripturl, $modSettings, $txt;
    echo '<div class="container">';
    if (!empty($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0))
	{
        
        echo '<h2>', $txt['parent_boards'], '</h2>';
        
        
    } 
    
   	if (!empty($options['show_board_desc']) && $context['description'] != '')
	{
		echo '
	<div id="description" class="tborder">
		<div class="titlebg2 largepadding smalltext">', $context['description'], '</div>
	</div>';
	}

	// Create the button set...
	$normal_buttons = array(
		'new_topic' => array('test' => 'can_post_new', 'text' => 'new_topic', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0'),
		'post_poll' => array('test' => 'can_post_poll', 'text' => 'new_poll', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll'),
		'notify' => array('test' => 'can_mark_notify', 'text' => $context['is_marked_notify'] ? 'unnotify' : 'notify', 'image' => ($context['is_marked_notify'] ? 'un' : '') . 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');"', 'url' => $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id']),
		'markread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id']),
	); 
    
    	// They can only mark read if they are logged in and it's enabled!
	if (!$context['user']['is_logged'] || !$settings['show_mark_read'])
		unset($normal_buttons['markread']);

	// Allow adding new buttons easily.
	call_integration_hook('integrate_messageindex_buttons', array(&$normal_buttons));
    
    // topic listing start!
    if (!$context['no_topic_listing'])
	{
        		echo '
		<div id="" class="row-fluid">
			<div class="span4">', $txt['pages'], ': ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a href="#bot"><strong>' . $txt['go_down'] . '</strong></a>' : '', '</div>
			<div class="span8">', template_button_strip($normal_buttons, 'bottom'), '</div>
		</div>';
    }
    

		echo '
			<div class="row-fluid forum-header" id="messageindex">';

		// Are there actually any topics to show?
		if (!empty($context['topics']))
		{
		  //header
			echo '<div class="span2"></div>
                  <div class="span4">', $txt['subject'],'/',$txt['started_by'],'</div>
                  <div class="span3 visible-desktop">',$txt['replies'],'/', $txt['views'],'</div>
                  <div class="span3 hidden-phone">',$txt['last_post'],'</div>';
        }
        echo '</div>';
        
        	// No topics.... just say, "sorry bub".
		if (empty($context['topics']))
			echo '<h3> sorry bub </h3>';

		foreach ($context['topics'] as $topic)
		{
            
            echo '<div class="row-fluid forum-row">';
            //print_r($topic);
            echo '<div class="span2"></div>
                  <div class="span4">', $topic['first_post']['link'],'</br>', $txt['started_by'], ' ', $topic['first_post']['member']['link'], '</div>
                  <div class="span3 visible-desktop">', $topic['replies'],' ',$txt['replies'],' ' , $topic['views'],' ',$txt['views'],'</div>
                  <div class="span3 hidden-phone">
                  <a href="', $topic['last_post']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt['last_post'], '" title="', $txt['last_post'], '" /></a>
								<span class="smalltext">
									', $topic['last_post']['time'], '<br />
									', $txt['by'], ' ', $topic['last_post']['member']['link'], '
								</span>
                  </div>
                  </div>';
        }

}

function theme_show_buttons()
{
	global $context, $settings, $options, $txt, $scripturl;

	$buttonArray = array();

	// If they are logged in, and the mark read buttons are enabled..
	if ($context['user']['is_logged'] && $settings['show_mark_read'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;' . $context['session_var'] . '=' . $context['session_id'] . '">' . $txt['mark_read_short'] . '</a>';

	// If the user has permission to show the notification button... ask them if they're sure, though.
	if ($context['can_mark_notify'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';' . $context['session_var'] . '=' . $context['session_id'] . '" onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');">' . $txt[$context['is_marked_notify'] ? 'unnotify' : 'notify'] . '</a>';

	// Are they allowed to post new topics?
	if ($context['can_post_new'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0">' . $txt['new_topic'] . '</a>';

	// How about new polls, can the user post those?
	if ($context['can_post_poll'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll">' . $txt['new_poll'] . '</a>';

	// Right to left menu should be in reverse order.
	if ($context['right_to_left'])
		$buttonArray = array_reverse($buttonArray, true);

	return implode(' &nbsp;|&nbsp; ', $buttonArray);
}

?>