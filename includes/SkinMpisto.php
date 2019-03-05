<?php
/**
 * Mpisto nouveau.
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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
 * @ingroup Skins
 */

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @ingroup Skins
 */
class SkinMpisto extends SkinTemplate {
	/** Using Mpisto. */
	public $skinname = 'mpisto';
	public $stylename = 'Mpisto';
	public $template = 'MpistoTemplate';

	/**
	 * @param OutputPage $out
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		if ( $out->getUser()->getOption( 'mpisto-responsive' ) ) {
			$out->addMeta( 'viewport',
				'width=device-width, initial-scale=1.0, ' .
				'user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0'
			);
			$styleModule = 'skins.mpisto.responsive';
			$out->addModules( [
				'skins.mpisto.mobile'
			] );

			if ( ExtensionRegistry::getInstance()->isLoaded( 'Echo' ) && $out->getUser()->isLoggedIn() ) {
				$out->addModules( [ 'skins.mpisto.mobile.echohack' ] );
			}
			if ( ExtensionRegistry::getInstance()->isLoaded( 'UniversalLanguageSelector' ) ) {
				$out->addModules( [ 'skins.mpisto.mobile.uls' ] );
			}
		} else {
			$styleModule = 'skins.mpisto.styles';
		}

		$out->addModuleStyles( [
			'mediawiki.skinning.interface',
			'mediawiki.skinning.content.externallinks',
			$styleModule
		] );

		// TODO: Migrate all of these (get RL support for conditional IE)
		// Force desktop styles in IE 8-; no support for @media widths
		$out->addStyle( $this->stylename . '/resources/screen-desktop.css', 'screen', 'lt IE 9' );
		// Miscellanious fixes
		$out->addStyle( $this->stylename . '/resources/IE60Fixes.css', 'screen', 'IE 6' );
		$out->addStyle( $this->stylename . '/resources/IE70Fixes.css', 'screen', 'IE 7' );
	}

	/**
	 * @param User $user
	 * @param array &$preferences
	 */
	public static function onGetPreferences( User $user, array &$preferences ) {
		if ( $user->getOption( 'skin' ) === 'mpisto' ) {
			$preferences['mpisto-responsive'] = [
				'type' => 'toggle',
				'label-message' => 'mpisto-responsive-label',
				'section' => 'rendering/skin',
			];
		}
	}

	/**
	 * Handler for ResourceLoaderRegisterModules hook
	 * Check if extensions are loaded
	 *
	 * @param ResourceLoader $resourceLoader
	 */
	static function registerMobileExtensionStyles( ResourceLoader $resourceLoader ) {
		if ( ExtensionRegistry::getInstance()->isLoaded( 'Echo' ) ) {
			$resourceLoader->register( 'skins.mpisto.mobile.echohack', [
				'localBasePath' => __DIR__ . '/..',
				'remoteSkinPath' => 'Mpisto',

				'targets' => [ 'desktop', 'mobile' ],
				'scripts' => [ 'resources/mobile-echo.js' ],
				'styles' => [ 'resources/mobile-echo.less' => [
					'media' => 'screen and (max-width: 550px)'
				] ],
				'dependencies' => [ 'ext.echo.badgeicons', 'mediawiki.util' ],
				'messages' => [ 'mpisto-notifications-link', 'mpisto-notifications-link-none' ]
			] );
		}

		if ( ExtensionRegistry::getInstance()->isLoaded( 'UniversalLanguageSelector' ) ) {
			$resourceLoader->register( 'skins.mpisto.mobile.uls', [
				'localBasePath' => __DIR__ . '/..',
				'remoteSkinPath' => 'Mpisto',

				'targets' => [ 'desktop', 'mobile' ],
				'scripts' => [ 'resources/mobile-uls.js' ],
				'dependencies' => [ 'ext.uls.interface' ],
			] );
		}
	}
}
