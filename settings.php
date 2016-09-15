<?php

/**
 * ownCloud - user_saml
 *
 * @author Sixto Martin <smartin@yaco.es>
 * @copyright 2012 Yaco Sistemas // CONFIA
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

OC_Util::checkAdminUser();

$params = array('saml_ssp_path', 'saml_sp_source', 'saml_force_saml_login', 'saml_autocreate', 'saml_update_user_data', 'saml_protected_groups', 'saml_default_group', 'saml_username_mapping', 'saml_email_mapping', 'saml_quota_mapping', 'saml_default_quota', 'saml_displayname_mapping', 'saml_group_mapping');

OCP\Util::addScript('user_saml', 'settings');

if ($_POST) {
	// CSRF check
	OCP\JSON::callCheck();

	foreach ($params as $param) {
		if (isset($_POST[$param])) {
			\OC::$server->getConfig()->setAppValue('user_saml', $param, $_POST[$param]);
		} elseif ('saml_force_saml_login' == $param) {
			\OC::$server->getConfig()->setAppValue('user_saml', $param, 0);
		} elseif ('saml_autocreate' == $param) {
			// unchecked checkboxes are not included in the post paramters
			\OC::$server->getConfig()->setAppValue('user_saml', $param, 0);
		} elseif ('saml_update_user_data' == $param) {
			\OC::$server->getConfig()->setAppValue('user_saml', $param, 0);
		}
	}
}

// fill template
$tmpl = new OCP\Template('user_saml', 'settings');
foreach ($params as $param) {
	$value = htmlentities(\OC::$server->getConfig()->getAppValue('user_saml', $param, ''));
	$tmpl->assign($param, $value);
}

// settings with default values
$tmpl->assign('saml_ssp_path', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_ssp_path', '/var/www/sp/simplesamlphp'));
$tmpl->assign('saml_sp_source', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_sp_source', 'default-sp'));
$tmpl->assign('saml_force_saml_login', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_force_saml_login', 0));
$tmpl->assign('saml_autocreate', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_autocreate', 0));
$tmpl->assign('saml_update_user_data', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_update_user_data', 0));
$tmpl->assign('saml_protected_groups', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_protected_groups', ''));
$tmpl->assign('saml_default_group', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_default_group', ''));
$tmpl->assign('saml_username_mapping', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_username_mapping', 'uid'));
$tmpl->assign('saml_email_mapping', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_email_mapping', 'mail'));
$tmpl->assign('saml_displayname_mapping', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_displayname_mapping', 'displayName'));
$tmpl->assign('saml_quota_mapping', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_quota_mapping', ''));
$tmpl->assign('saml_default_quota', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_default_quota', ''));
$tmpl->assign('saml_group_mapping', \OC::$server->getConfig()->getAppValue('user_saml', 'saml_group_mapping', ''));

return $tmpl->fetchPage();
