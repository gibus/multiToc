<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of multiToc, a plugin for Dotclear.
# 
# Copyright (c) 2009-2015 Tomtom and contributors
# 
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$page_title = __('Tables of content');

if (!empty($_POST['save']))
{
	$settings	= unserialize(dcCore::app()->blog->settings->multiToc->multitoc_settings);
	
	$types = array('cat','tag','alpha','post');
	
	foreach ($types as $type) {
		foreach ($settings[$type] as $k => $v) {
			$settings[$type][$k] = '';
		}
	}
	
	foreach ($_POST as $k => $v) {
		if (preg_match('#^('.implode('|',$types).')_(.*)$#',$k,$match)) {
			$settings[$match[1]][$match[2]] = $v;
		}
	}
	
	dcCore::app()->blog->settings->multiToc->put('multitoc_settings',serialize($settings));
	http::redirect(dcCore::app()->admin->getPageURL().'&upd=1');
}

echo
'<html>'.
'<head>'.
	'<title>'.$page_title.'</title>'.
'</head>'.
'<body>'.
	dcPage::breadcrumb(
    array(
    html::escapeHTML(dcCore::app()->blog->name) => '',
    '<span class="page-title">'.$page_title.'</span>' => ''
    ));

# Information message
if (!empty($_GET['upd'])) {
  dcPage::success(__('Configuration has been saved successfully'));
}

echo
'<form method="post" action="'.dcCore::app()->admin->getPageURL().'">'.
	multiTocUi::form('post').
	multiTocUi::form('cat').
	multiTocUi::form('tag').
	multiTocUi::form('alpha').
	dcCore::app()->formNonce().
'<p><input name="save" value="'.__('Save').'" type="submit" /></p>'.
'</form>'.
 dcPage::helpBlock('multiToc').
'</body>'.
'</html>';