<?php
/**
 * Copyright (C) 2018 Kunal Mehta <legoktm@debian.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\UnCaptcha;

use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthManager;

class Hooks {

	/**
	 * Manipulate the form UI based on the robot authentication request
	 *
	 * @param AuthenticationRequest[] $reqs All requests making up the field
	 * @param array $fieldInfo union of all AuthenticationRequest::getFieldInfo() responses
	 * @param array &$formDescriptor HTMLForm descriptor
	 * @param string $action ACTION_* constant
	 */
	public static function onAuthChangeFormFields( $reqs, $fieldInfo, &$formDescriptor, $action ) {
		if ( $action !== AuthManager::ACTION_CREATE ) {
			return;
		}

		/** @var RobotAuthenticationRequest $req */
		$req = AuthenticationRequest::getRequestByClass(
			$reqs, RobotAuthenticationRequest::class
		);
		if ( !$req ) {
			// Shouldn't be possible?
			return;
		}

		// We want to invert the checkbox so that no matter what
		// you need to click it.
		$formDescriptor['robot']['invert'] = !$req->not;
	}

}
