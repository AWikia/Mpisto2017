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
	 * @inheritDoc
	 * @return bool
	 */
	public function isResponsive() {
		return $this->getUser()->getOption( 'mpisto-responsive' );
	}

	/**
	 * @inheritDoc
	 * @param OutputPage $out
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );

		if ( $this->isResponsive() ) {
			$styleModule = 'skins.mpisto.responsive';
			$out->addModules( [
				'skins.mpisto.mobile'
			] );
		} else {
			$styleModule = 'skins.mpisto.styles';
		}

		$out->addModuleStyles( [
			$styleModule
		] );
	}

	/**
	 * @param User $user
	 * @param array &$preferences
	 */
	public static function onGetPreferences( User $user, array &$preferences ) {
		$preferences['mpisto-responsive'] = [
			'type' => 'toggle',
			'label-message' => 'mpisto-responsive-label',
			'section' => 'rendering/skin/skin-prefs',
			// Only show this section when the Monobook skin is checked. The JavaScript client also uses
			// this state to determine whether to show or hide the whole section.
			'hide-if' => [ '!==', 'wpskin', 'mpisto' ],
		];
	}
}
