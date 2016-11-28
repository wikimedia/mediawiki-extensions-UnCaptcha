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

use MediaWiki\Auth\AbstractPreAuthenticationProvider;
use MediaWiki\Auth\AuthenticationRequest;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Logger\LoggerFactory;
use Status;

class RobotPreAuthenticationProvider extends AbstractPreAuthenticationProvider {

	const SESSION_KEY = 'RobotPreAuth';

	public function getAuthenticationRequests( $action, array $options ) {
		if ( $action == AuthManager::ACTION_CREATE ) {
			$session = $this->manager->getRequest()->getSession();
			if ( $session->exists( self::SESSION_KEY ) ) {
				$not = (int)$session->get( self::SESSION_KEY );
			} else {
				$not = mt_rand( 0, 1 );
				$session->set( self::SESSION_KEY, $not );
			}
			return [ new RobotAuthenticationRequest( (bool)$not ) ];
		} else {
			return [];
		}
	}

	public function testForAccountCreation( $user, $creator, array $reqs ) {
		/** @var RobotAuthenticationRequest $request */
		$request = AuthenticationRequest::getRequestByClass( $reqs, RobotAuthenticationRequest::class );
		if ( !$request ) {
			throw new \RuntimeException( 'Account creation request without a RobotAuthenticationRequest' );
		}

		$logger = LoggerFactory::getInstance( 'UnCaptcha' );
		$logger->info( 'Got account creation request which passed: {robot} (had not: {not})', [
			'robot' => $request->robot ? 'true' : 'false',
			'not' => $request->not ? 'true' : 'false',
		] );

		if ( $request->robot ) {
			return Status::newGood();
		} else {
			return Status::newFatal( 'uncaptcha-auth-failed' );
		}
	}
}
