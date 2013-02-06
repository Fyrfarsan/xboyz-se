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
		<div id="" class="row">
			<div class="span4">', $txt['pages'], ': ', $context['page_index'], !empty($modSettings['topbottomEnable']) ? $context['menu_separator'] . '&nbsp;&nbsp;<a href="#bot"><strong>' . $txt['go_down'] . '</strong></a>' : '', '</div>
			<div class="span8 toolbar">', template_button_strip($normal_buttons, 'bottom'), '</div>
		</div>';
    }
    

		echo '
			<div class="row forum-header" id="messageindex">';

		// Are there actually any topics to show?
		if (!empty($context['topics']))
		{
		  //header
			echo '<div class="span1 visible-desktop"></div>
                  <div class="span5"><h6>', $txt['subject'],'/',$txt['started_by'],'</h6></div>
                  <div class="span3 visible-desktop"><h6>',$txt['replies'],'/', $txt['views'],'</h6></div>
                  <div class="span3 hidden-phone"><h6>',$txt['last_post'],'</h6></div>';
        }
        echo '</div>';
        
        	// No topics.... just say, "sorry bub".
		if (empty($context['topics']))
			echo '<h3> sorry bub </h3>';

         //Alternation 
        $oddeven = true;
        $listclass;

		foreach ($context['topics'] as $topic)
		{
            $listclass = '';
            $listclass .= (($oddeven = !$oddeven) ? ' odd':' even');
            
            // Do we want to separate the sticky and lock status out?
			if (!empty($settings['separate_sticky_lock']) && strpos($topic['class'], 'sticky') !== false)
				$topic['class'] = substr($topic['class'], 0, strrpos($topic['class'], '_sticky'));
			if (!empty($settings['separate_sticky_lock']) && strpos($topic['class'], 'locked') !== false)
				$topic['class'] = substr($topic['class'], 0, strrpos($topic['class'], '_locked'));


            echo '<div class="row forum-row', $listclass ,'">';
            //print_r($topic)<div class="span1">
            //        <img src="', $settings['images_url'], '/topic/', $topic['class'], '.gif" alt="" />
            //       </div>;
            //print_r($topic['first_post']);
            echo '
                   <div class="span1 visible-desktop">
                   <img src="', $topic['first_post']['icon_url'], '" alt="" />
                   </div>
                  <div class="span5"><a class="heading" href="', $topic['first_post']['href'],'">', $topic['first_post']['subject'],'</a></br>', $txt['started_by'], ' ', $topic['first_post']['member']['link'], '</div>
                  <div class="span3 visible-desktop"><span class="statistics">', $topic['replies'],'</span> ',$txt['replies'],' / ' , $topic['views'],' ',$txt['views'],'</div>
                  <div class="span3 hidden-phone">
                  <ul class="last-post" > 
                <li> ', $txt['by'], ' ', $topic['last_post']['member']['link'], ' </li>
                <li> ', $topic['last_post']['time'],' </li>
                </ul>
                  
								
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