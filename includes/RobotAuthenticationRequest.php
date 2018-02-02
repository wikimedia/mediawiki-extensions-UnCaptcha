<?php
/**
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

class RobotAuthenticationRequest extends AuthenticationRequest {

	/**
	 * If true, the question will read "I am *not* a robot", otherwise
	 * it will read "I am a robot".
	 *
	 * @var bool
	 */
	public $not;

	/**
	 * If true, they answered the question correctly (the 'invert'
	 * logic takes care of this)
	 *
	 * @var bool
	 */
	public $robot;

	/**
	 * @param bool $not
	 */
	public function __construct( $not ) {
		$this->not = $not;
	}

	public function getFieldInfo() {
		if ( $this->not ) {
			$key = 'uncaptcha-auth-label-not';
		} else {
			$key = 'uncaptcha-auth-label-am';
		}
		return [
			'robot' => [
				'type' => 'checkbox',
				'label' => wfMessage( $key ),
				'optional' => true,
			],
		];
	}
}
