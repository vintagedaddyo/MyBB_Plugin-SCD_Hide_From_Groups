<?php
/*
 * MyBB: SCD Hide From Groups
 *
 * File: scd_hide_fg.php
 * 
 * Authors: Dylan Myers, Vintagedaddyo
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 2.6
 *
 * License: LGPL v2.1 
 * 
 */ 

// Disallow direct access to this file for security reasons

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// add hooks

$plugins->add_hook("parse_message_start", "scd_hide_fg_parse_message");

$plugins->add_hook("parse_message_end", "scd_hide_fg_parse_append");

$plugins->add_hook("parse_quoted_message", "scd_hide_fg_parse_reply");

// plugin information

function scd_hide_fg_info()
{
    global $lang;

    // load language
    
    $lang->load("scd_hide_fg");
    
    $lang->scd_hide_fg_description = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:right;">' .
        '<input type="hidden" name="cmd" value="_s-xclick">' . 
        '<input type="hidden" name="hosted_button_id" value="AZE6ZNZPBPVUL">' .
        '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' .
        '<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">' .
        '</form>' . $lang->scd_hide_fg_description;

    return array(
        'name' => $lang->scd_hide_fg_name,
        'description' => $lang->scd_hide_fg_description,
        'website' => $lang->scd_hide_fg_website,
        'author' => $lang->scd_hide_fg_author,
        'authorsite' => $lang->scd_hide_fg_authorsite,
        'version' => $lang->scd_hide_fg_version,
        'guid' => $lang->scd_hide_fg_guid,
        'compatibility' => $lang->scd_hide_fg_compatibility
    );
}

// plugin installation

function scd_hide_fg_install()
{
	
    global $settings, $db, $mybb,$lang;

    // load language

    $lang->load("scd_hide_fg");

    // setting group
    
    $scd_hide_fg_group = array(
        'gid' => '0',
        'name' => 'scd_hide_fg',
        'title' => $lang->scd_hide_fg_setting_group_title,
        'description' => $lang->scd_hide_fg_setting_group_description,
        'disporder' => '500',
        'isdefault' => '0'
    );
        $db->insert_query('settinggroups', $scd_hide_fg_group);
    
    $gid = $db->insert_id();

    // setting 1
        
    $scd_hide_fg_setting_1 = array(
        'sid' => '0',
        'name' => 'scd_hide_fg_groups',
        'title' => $lang->scd_hide_fg_setting_1_title,
        'description' => $lang->scd_hide_fg_setting_1_description,
        'optionscode' => 'text',
        'value' => '1',
        'disporder' => '1',
        'gid' => intval($gid)
    );
    
    // setting 2
    
    $scd_hide_fg_setting_2 = array(
        'sid' => '0',
        'name' => 'scd_hide_fg_forums',
        'title' => $lang->scd_hide_fg_setting_2_title,
        'description' => $lang->scd_hide_fg_setting_2_description,
        'optionscode' => 'text',
        'value' => '',
        'disporder' => '2',
        'gid' => intval($gid)
    );
    
    // setting 3
    
    $scd_hide_fg_setting_3 = array(
        'sid' => '0',
        'name' => 'scd_hide_fg_text',
        'title' => $lang->scd_hide_fg_setting_3_title,
        'description' => $lang->scd_hide_fg_setting_3_description,
        'optionscode' => 'textarea',
        'value' => $lang->scd_hide_fg_setting_3_value,
        'disporder' => '3',
        'gid' => intval($gid)
    );
    
    // setting 4
    
    $scd_hide_fg_setting_4 = array(
        'sid' => '0',
        'name' => 'scd_hide_fg_append',
        'title' => $lang->scd_hide_fg_setting_4_title,
        'description' => $lang->scd_hide_fg_setting_4_description,
        'optionscode' => 'textarea',
        'value' => $db->escape_string(''.$lang->scd_hide_fg_setting_4_value.''),
        'disporder' => '4',
        'gid' => intval($gid)
    );
    
    // setting 5
    
    $scd_hide_fg_setting_5 = array(
        'sid' => '0',
        'name' => 'scd_hide_fg_tohide',
        'title' => $lang->scd_hide_fg_setting_5_title,
        'description' => $lang->scd_hide_fg_setting_5_description,
        'optionscode' => 'text',
        'value' => 'PHP,CODE,QUOTE,URL,IMG,EMAIL',
        'disporder' => '5',
        'gid' => intval($gid)
    );
    
    $db->insert_query('settings', $scd_hide_fg_setting_1);
    
    $db->insert_query('settings', $scd_hide_fg_setting_2);
        
    $db->insert_query('settings', $scd_hide_fg_setting_3);
    
    $db->insert_query('settings', $scd_hide_fg_setting_4);
    
    $db->insert_query('settings', $scd_hide_fg_setting_5);
  
    rebuild_settings();
    
}

//  plugin uninstallation

function scd_hide_fg_uninstall()
{
	
    global $db, $mybb;	
    
    // setting 1
    
    $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('scd_hide_fg_groups')");
    
    // setting 2
    
    $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('scd_hide_fg_forums')");
    
    // setting 3
    
    $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('scd_hide_fg_text')");
    
    // setting 4
    
    $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('scd_hide_fg_append')");
    
    // setting 5
    
    $db->query("DELETE FROM " . TABLE_PREFIX . "settings WHERE name IN ('scd_hide_fg_tohide')");
    
    // setting group
    
    $db->query("DELETE FROM " . TABLE_PREFIX . "settinggroups WHERE name='scd_hide_fg'");
    

    rebuild_settings();
    
}
   
function scd_hide_fg_is_installed()
{
	
	global $db;
	
	$query = $db->simple_select("settinggroups", "COUNT(*) as `rows`", "name='scd_hide_fg'");
	
	$zrows = $db->fetch_field($query, "rows");
	
	if($zrows > 0)
	{
		
		return true;
		
	}
	
	return false;
	
}

function scd_hide_fg_activate()
{
}

function scd_hide_fg_deactivate()
{
}

function scd_hide_fg_parse_message($message)
{

	global $db, $mybb, $fid;

	$append = '';	
	
	if(!empty($mybb->settings['scd_hide_fg_append']))
	{
		
		$append = '  {scd_hide_fg_append}';
		
	}

	// Parse the message for code tags, and replace the contents with a little message.
	if(in_array($mybb->user['usergroup'], explode(',', $mybb->settings['scd_hide_fg_groups'])))
	{
		
		if(!empty($mybb->settings['scd_hide_fg_forums']) && !in_array($fid, explode(',', $mybb->settings['scd_hide_fg_forums'])))
		{
			
			return $message;
			
		}
		
		foreach(explode(',', $mybb->settings['scd_hide_fg_tohide']) as $tag)
		{
			
			if(preg_match('#url#i', $tag))
			{
				
				$message = preg_replace('#\['.$tag.'=.*?\].*?\[/'.$tag.'\]#si', '['.$tag.'='.$mybb->settings['bburl'].'/member.php?action=register]'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $message);
				
				$message = preg_replace('#\['.$tag.'\].*?\[/'.$tag.'\]#si', '['.$tag.'='.$mybb->settings['bburl'].'/member.php?action=register]'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $message);
				
			}
			
			else if(!preg_match('#quote#i', $tag))
			{
				
				$message = preg_replace('#\['.$tag.'\].*?\[/'.$tag.'\]#si', '['.$tag.']'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']'.$append, $message);
				
				$message = preg_replace('#\['.$tag.'=.*?\].*?\[/'.$tag.'\]#si', '['.$tag.'='.$mybb->settings['scd_hide_fg_text'].']'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']'.$append, $message);
				
				if(preg_match('#\['.$tag.'\]#si', $message) && !preg_match('#\[/'.$tag.'\]#si', $message))
				{
					
					$message = preg_replace('#\['.$tag.'.*?\]#si', $mybb->settings['scd_hide_fg_text'], $message);
					
				}
			}
			
			else if (preg_match('#quote#i', $tag))
			{
				
				$message = preg_replace("#\[quote=(?:&quot;|\"|')?(.*?)[\"']?(?:&quot;|\"|')?\](.*)\[\/quote\](\r\n?|\n?)#si", "[quote=$1]".$mybb->settings['scd_hide_fg_text']."[/quote]\n".$append, $message);
				
				$message = preg_replace('#\['.$tag.'\].*\[/'.$tag.'\]#si', '['.$tag.']'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']'.$append, $message);
				
			}
			
			else
			{
				$message .= '\n\nThis message is displayed in error. Please inform the Admin that there is a bug in the SCD Hide Things Plugin!';
			}
		}
	}
	
	return $message;
	
}

function scd_hide_fg_parse_reply($page)
{
	
	global $db, $mybb, $fid;
	
	if((!isset($fid) || empty($fid)) && is_array($page))
	{
		
		$query = $db->simple_select("threads", "fid", "tid='{$page['tid']}'");
		$fid = $db->fetch_field($query, "fid");
		
		$db->free_result($query);
		
	}

	// Parse the message for code tags, and replace the contents with a little message.
	if(in_array($mybb->user['usergroup'], explode(',', $mybb->settings['scd_hide_fg_groups'])))
	{
		
		if(!empty($mybb->settings['scd_hide_fg_forums']) && !in_array($fid, explode(',', $mybb->settings['scd_hide_fg_forums'])))
		{
			
			return $page;
			
		}
		
		foreach(explode(',', $mybb->settings['scd_hide_fg_tohide']) as $tag)
		{
			
			if(preg_match('#url#i', $tag))
			{
				
				$page['message'] = preg_replace('#\['.$tag.'=.*?\].*?\[/'.$tag.'\]#si', '['.$tag.'='.$mybb->settings['bburl'].'/member.php?action=register]'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $page['message']);
				
				$page['message'] = preg_replace('#\['.$tag.'\].*?\[/'.$tag.'\]#si', '['.$tag.'='.$mybb->settings['bburl'].'/member.php?action=register]'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $page['message']);
				
			}
			
			else if(!preg_match('#quote#i', $tag))
			{
				
				$page['message'] = preg_replace('#\['.$tag.'\].*?\[/'.$tag.'\]#si', '['.$tag.']'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $page['message']);
				
				$page['message'] = preg_replace('#\['.$tag.'=.*?\].*?\[/'.$tag.'\]#si', '['.$tag.'='.$mybb->settings['scd_hide_fg_text'].']'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $page['message']);
				
				if(preg_match('#\['.$tag.'\]#si', $page['message']) && !preg_match('#\[/'.$tag.'\]#si', $page['message']))
				{
					
					$page['message'] = preg_replace('#\['.$tag.'.*?\]#si', $mybb->settings['scd_hide_fg_text'], $page['message']);
					
				}
			}
			
			else if (preg_match('#quote#i', $tag))
			{
				
				$page['message'] = preg_replace("#\[quote=?(?:&quot;|\"|')?(.*?)[\"']?(?:&quot;|\"|')?\](.*)\[\/quote\](\r\n?|\n?)#si", "[quote=$1]".$mybb->settings['scd_hide_fg_text']."[/quote]\n", $page['message']);
				$page['message'] = preg_replace('#\['.$tag.'\].*\[/'.$tag.'\]#si', '['.$tag.']'.$mybb->settings['scd_hide_fg_text'].'[/'.$tag.']', $page['message']);
				
			}
			
			else
			{
				
				$page['message'] .= '\n\nThis message is displayed in error. Please inform the Admin that there is a bug in the SCD Hide Things Plugin!';
				
			}
		}
	}
	
	return;
	
}

function scd_hide_fg_parse_append($message)
{
	
	global $db, $mybb;
	
	$message = str_replace('{scd_hide_fg_append}', $mybb->settings['scd_hide_fg_append'], $message);
	
	return $message;
	
}

?>