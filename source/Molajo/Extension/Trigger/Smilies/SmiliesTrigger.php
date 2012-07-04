<?php
/**
 * @package    Molajo
 * @copyright  2012 Amy Stephen. All rights reserved.
 * @license    GNU GPL v 2, or later and MIT, see License folder
 */
namespace Molajo\Extension\Trigger\Smilies;

use Molajo\Extension\Trigger\Content\ContentTrigger;
use Molajo\Service\Services;

defined('MOLAJO') or die;

/**
 * @package     Molajo
 * @subpackage  Trigger
 * @since       1.0
 */
class SmiliesTrigger extends ContentTrigger
{

	/**
	 * Replaces text with emotion images
	 *
	 * @return boolean
	 * @since   1.0
	 */
	public function onAfterRead()
	{
		$fields = $this->retrieveFieldsByType('text');

		if (is_array($fields) && count($fields) > 0) {

			foreach ($fields as $field) {

				$name = $field->name;

				$fieldValue = $this->getFieldValue($field);

				if ($fieldValue == false) {
				} else {

					$value = $this->smilies($fieldValue);

					if ($value == false) {
					} else {

						$this->saveField($field, $name, $value);
					}
				}

			}
		}

		return true;
	}

	/**
	 * smilies - change text smiley values into icons
	 *
	 * @param  string $text
	 * @return string
	 * @since  1.0
	 */
	public function smilies($text)
	{
		$smile = array(
			':mrgreen:' => 'icon_mrgreen.gif',
			':neutral:' => 'icon_neutral.gif',
			':twisted:' => 'icon_twisted.gif',
			':arrow:' => 'icon_arrow.gif',
			':shock:' => 'icon_eek.gif',
			':smile:' => 'icon_smile.gif',
			':???:' => 'icon_confused.gif',
			':cool:' => 'icon_cool.gif',
			':evil:' => 'icon_evil.gif',
			':grin:' => 'icon_biggrin.gif',
			':idea:' => 'icon_idea.gif',
			':oops:' => 'icon_redface.gif',
			':razz:' => 'icon_razz.gif',
			':roll:' => 'icon_rolleyes.gif',
			':wink:' => 'icon_wink.gif',
			':cry:' => 'icon_cry.gif',
			':eek:' => 'icon_surprised.gif',
			':lol:' => 'icon_lol.gif',
			':mad:' => 'icon_mad.gif',
			':sad:' => 'icon_sad.gif',
			'8-)' => 'icon_cool.gif',
			'8-O' => 'icon_eek.gif',
			':-(' => 'icon_sad.gif',
			':-)' => 'icon_smile.gif',
			':-?' => 'icon_confused.gif',
			':-D' => 'icon_biggrin.gif',
			':-P' => 'icon_razz.gif',
			':-o' => 'icon_surprised.gif',
			':-x' => 'icon_mad.gif',
			':-|' => 'icon_neutral.gif',
			';-)' => 'icon_wink.gif',
			'8)' => 'icon_cool.gif',
			'8O' => 'icon_eek.gif',
			':(' => 'icon_sad.gif',
			':)' => 'icon_smile.gif',
			':?' => 'icon_confused.gif',
			':D' => 'icon_biggrin.gif',
			':P' => 'icon_razz.gif',
			':o' => 'icon_surprised.gif',
			':x' => 'icon_mad.gif',
			':|' => 'icon_neutral.gif',
			';)' => 'icon_wink.gif',
			':!:' => 'icon_exclaim.gif',
			':?:' => 'icon_question.gif',
		);

		if (count($smile) > 0) {
			foreach ($smile as $key => $val) {
				$text = str_ireplace($key,
					'<span><img src="' . SITES_MEDIA_URL . '/images/smilies/'
						. $val
						. '" alt="smiley" class="smiley-class" /></span>',
					$text);
			}
		}

		return $text;
	}
}
