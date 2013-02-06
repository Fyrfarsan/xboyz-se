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


    /* Each category in categories is made up of:
    id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
    new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
    and boards. (see below.) */
    foreach ($context['categories'] as $category) {
        // If theres no parent boards we can see, avoid showing an empty category (unless its collapsed)
        if (empty($category['boards']) && !$category['is_collapsed'])
            continue;
        //Alternation 
        $oddeven = true;
        $listclass;

        echo '
            <div class="container">
                
                <h2 class="category">', $category['name'], '</h2>
                
				<div class="row">
                    <div class="span12 forum-header">
                        <div class="row">
					        <div class="span6">
						        <h6>Forum</h6>
					        </div>
					        <div class="span2 visible-desktop">
						        <h6>Statistik</h6>
					        </div>
					        <div class="span4 hidden-phone">
						        <h6>Info om senaste tråd</h6>
					        </div>
                        </div>
                    </div>
				</div>
                <div class="container-boards">';
        foreach ($category['boards'] as $board) {
            $listclass = '';
            $listclass .= (($oddeven = !$oddeven) ? ' odd':' even');

            echo '	<div class="row">
                    <div class="span12  forum-row', $listclass ,'">
                    <div class="row">
					<div class="span6 content-post">
						<div class="icon">';    
            if ($board['new'] || $board['children_new'])
                echo '
						<img src="', $settings['images_url'], '/xbz/folder_green.png" alt="', $txt['new_posts'],
                    '" title="', $txt['new_posts'], '" border="0" />';
            else
                echo '<img src="', $settings['images_url'], '/xbz/folder_green_unread.png" alt="', $txt['old_posts'],
                    '" title="', $txt['old_posts'], '" />';


            echo '</div>
						<div class="rowContent"><a class="heading" href = "', $board['href'], '"
                name = "b', $board['id'], '" > ', $board['name'], ' </a> ';

            echo '							
							<br>
							<span class="desc">
								', $board['description'], '
								<br />
								<span class="number visible-phone">
									', $board['time'], '
								</span>
							</span>
						</div>
					</div>
					<div class="span1 visible-desktop statistic-post">
						<span class="statistics">', $board['topics'], '</span><span> ämnen</span>
					</div>
                    <div class="span1 visible-desktop statistic-post">
						<span class="statistics">',$board['posts'], '</span><span> inlägg</span>
					</div>
					<div class="span4 hidden-phone">';

            if (!empty($board['last_post']['id']))
                echo '<ul class="last-post" > <li> ', $board['last_post']['link'], ' </li >
                <li> ', $txt['by'], ' ', $board['last_post']['member']['link'], ' </li>
                <li> ', $board['last_post']['time'],' </li>
                </ul>';


            echo '</div></div></div>
				</div>';
        }
        echo '</div></div>';

        }
        template_info_center();
    }

    function template_info_center()
    {
        global $context, $settings, $options, $txt, $scripturl, $modSettings;

        echo ' <div class="container infocenter">
        <div class="row">
        <div class="span12">
        <h4 class="widget-header"> ', $txt['forum_stats'], ' </h4>
        <p> ', $context['common_stats']['total_posts'],' ', $txt['posts_made'],' ', $txt['in'], ' ', $context['common_stats']['total_topics'],
            ' ', $txt['topics'], ' ', $txt['by'], ' ', $context['common_stats']['total_members'],
            ' ', $txt['members'], ' . ', !empty($settings['show_latest_member']) ?
            $txt['latest_member'] . ' : <strong>' . $context['common_stats']['latest_member']['link'] . ' </strong>' : '', ' <br/>', (!empty($context['latest_post']) ?
            $txt['latest_post'] . ' : <strong>' . $context['latest_post']['link'] . '</strong> (' . $context['latest_post']['time'] . ') <br/>' :
            ''), ' <a href="', $scripturl, '?action=recent" >
                ', $txt['recent_view'], ' </a> ', $context['show_stats'] ?
            ' <br/> <a href="' . $scripturl . '?action=stats" > ' . $txt['
                more_stats'] . ' </a>' : ' ';

        //Show statistical style information...
        if ($settings['show_stats_index']) {
            
        }
        
        /* Show statistical style information...
        if ($settings['show_stats_index']) {
        echo ' < div class = "infocenter_section" > < h4 class = "titlebg" >
        ', $txt['forum_stats'], ' < / h4 > < div class = "windowbg" > < p class =
        "section" > < a href = "', $scripturl, '?action=stats" > < img src = "', $settings['images_url'],
        '/icons/info.gif"alt = "', $txt['forum_stats'], '" / > < / a > < / p > <
        div class = "windowbg2 sectionbody middletext" > ', $context['common_stats']['
        total_posts'], '', $txt['posts_made'], '',
        $txt['in'], '', $context['common_stats']['total_topics'], '
        ', $txt['topics'],
        '', $txt['by'], '', $context['common_stats']['total_members'], '
        ', $txt['members'],
        ' . ', !empty($settings['show_latest_member']) ? $txt['
        latest_member'] .
        ' : < strong > ' . $context['common_stats']['latest_member']['link
        '] . ' < / strong > ' :
        '', ' < br / > ', (!empty($context['latest_post']) ? $txt['
        latest_post'] .
        ' : < strong > &quot;
        ' . $context['latest_post']['link'] . ' & quot;
        < / strong > (' . $context['latest_post']['time'] .
        ') < br / > ' : ''), ' < a href = "', $scripturl, '?action=recent" >
        ', $txt['recent_view'], ' < / a > ', $context['show_stats'] ?
        ' < br / > < a href = "' . $scripturl . '?action=stats" > ' . $txt['
        more_stats'] . ' < / a > ' :
        '', ' < / div > < / div > < / div > ';
        }*/
        echo '</div>
            </div>
        <div class="row">';
        // "Users online" - in order of activity.
        echo ' <div class="infocenter_section span12"><h4 class="titlebg">
                ', $txt['online_users'],
            ' </h4><div class="windowbg"><p class="section"> ', $context['show_who'] ? ' <a href ="' . $scripturl .
            '?action=who' . '">
                ' : '', ' <img src="', $settings['images_url'],
            '/icons/online.gif', '"
                alt="', $txt['online_users'], '" /> ', $context['show_who'] ?
            '</a>' : '', '</p><div class="windowbg2 sectionbody">', $context['show_who'] ?
            ' <a href="' . $scripturl . '?action=who">
                ' : '', comma_format($context['num_guests']), '', $context['num_guests
                '] == 1 ? $txt['guest'] : $txt['guests'], ', ' . comma_format($context['num_users_online']),
            '
                ', $context['num_users_online'] == 1 ? $txt['user'] : $txt['users'];

        // Handle hidden users and buddies.
        $bracketList = array();
        if ($context['show_buddies'])
            $bracketList[] = comma_format($context['num_buddies']) . '
                ' . ($context['num_buddies'] == 1 ? $txt['buddy'] : $txt['buddies']);
        if (!empty($context['num_spiders']))
            $bracketList[] = comma_format($context['num_spiders']) . '
                ' . ($context['num_spiders'] == 1 ? $txt['spider'] : $txt['spiders']);
        if (!empty($context['num_users_hidden']))
            $bracketList[] = comma_format($context['num_users_hidden']) . '
                ' . $txt['hidden'];

        if (!empty($bracketList))
            echo '(' . implode(', ', $bracketList) . ')';

        echo $context['show_who'] ? ' </a>' : '', ' <div class="smalltext">
                ';

        // Assuming there ARE users online... each user in users_online has an id, username, name, group, href, and link.
        if (!empty($context['users_online'])) {
            echo '', sprintf($txt['users_active'], $modSettings['lastActive']), ' : <br/> ', implode(', ', $context['list_users_online']);

            // Showing membergroups?
            if (!empty($settings['show_group_key']) && !empty($context['
                membergroups']))
                echo ' <br/> [' . implode('] & nbsp;
            &nbsp;
            [', $context['membergroups']) . ']';
        }

        echo ' </div><hr class="hrcolor"/><div class="smalltext">
                ', $txt['most_online_today'], ' : <strong> ', comma_format($modSettings['
                mostOnlineToday']), ' </strong> . ', $txt['most_online_ever'],
            ' :
                ', comma_format($modSettings['mostOnline']), '(', timeformat($modSettings['mostDate']),
            ') </div></div></div></div></div>';

        // If they are logged in, but statistical information is off... show a personal message bar.
        if ($context['user']['is_logged'] && !$settings['show_stats_index']) {
            echo ' <div class="infocenter_section"> <h4 class="titlebg">
                ', $txt['personal_message'],
                ' </h4><div class="windowbg"><p class="section"> ', $context['allow_pm'] ? ' < a href="' . $scripturl .
                '?action=pm" >
                ' : '', ' <img src="', $settings['images_url'],
                '/message_sm.gif"alt =
                "', $txt['personal_message'], '" / > ', $context['allow_pm'] ?
                ' </a> ' : '', ' </p> <div class="windowbg2 sectionbody"><strong> < a href="', $scripturl,
                '?action=pm" >
                ', $txt['personal_message'],
                ' </a></strong> <div class="smalltext">', $txt['
                you_have'], '', comma_format($context['user']['messages']), '',
                $context['user']['messages'] == 1 ? $txt['message_lowercase
                '] : $txt['msg_alert_messages'], ' . . . . ', $txt['click'],
                ' < a href="', $scripturl, '?action=pm" >
                ', $txt['here'], ' < / a > ', $txt['to_view'],
                ' </div></div></div></div> ';
        }

        // Show the login bar. (it's only true if they are logged out anyway . )
        if ($context['show_login_bar']) {
        echo '
			<div class="infocenter_section">
				<h4 class="titlebg">', $txt['login'], ' <a href="', $scripturl,
            '?action=reminder" class="smalltext">', $txt['forgot_your_password'], '</a></h4>
				<div class="windowbg">
					<p class="section">
						<a href="', $scripturl, '?action=login"><img src="', $settings['images_url'],
            '/icons/login.gif', '" alt="', $txt['login'], '" /></a>
					</p>
					<div class="windowbg2 sectionbody">
						<form id="infocenter_login" action="', $scripturl,
            '?action=login2" method="post" accept-charset="', $context['character_set'], '">
							<ul class="reset horizlist clearfix">
								<li>
									<label for="user">', $txt['username'], ':<br />
									<input type="text" name="user" id="user" size="15" class="input_text" /></label>
								</li>
								<li>
									<label for="passwrd">', $txt['password'], ':<br />
									<input type="password" name="passwrd" id="passwrd" size="15" class="input_password" /></label>
								</li>
								<li>
									<label for="cookielength">', $txt['mins_logged_in'], ':<br />
									<input type="text" name="cookielength" id="cookielength" size="4" maxlength="4" value="',
            $modSettings['cookieTime'], '" class="input_text" /></label>
								</li>
								<li>
									<label for="cookieneverexp">', $txt['always_logged_in'], ':<br />
									<input type="checkbox" name="cookieneverexp" id="cookieneverexp" checked="checked" class="input_check" /></label>
								</li>
								<li>
									<input type="submit" value="', $txt['login'],
            '" class="button_submit" />
								</li>
							</ul>
						</form>
					</div>
				</div>
			</div>';
    }


    echo '
		</div>
	</div>
      </div> ';
      }

?>