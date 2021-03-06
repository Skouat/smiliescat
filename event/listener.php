<?php
/**
*
* @package		Breizh Smilies Categories Extension
* @copyright	(c) 2020 Sylver35  https://breizhcode.com
* @license		http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace sylver35\smiliescat\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use sylver35\smiliescat\core\category;
use phpbb\controller\helper;
use phpbb\template\template;

class listener implements EventSubscriberInterface
{
	/* @var \sylver35\breizh\core\category */
	protected $category;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	public function __construct(category $category, helper $helper, template $template)
	{
		$this->category		= $category;
		$this->helper		= $helper;
		$this->template		= $template;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'				=> 'load_language_on_setup',
			'core.page_header'				=> 'add_page_header',
			'breizhshoutbox.smilies'		=> 'shoutbox_smilies',
			'breizhshoutbox.smilies_popup'	=> 'shoutbox_smilies_popup',
		);
	}

	/**
	 * Load language files during user setup
	 *
	 * @param \phpbb\event\data $event The event object
	 *
	 * @return void
	 * @access public
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'sylver35/smiliescat',
			'lang_set' => array('smilies_category'),
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_page_header()
	{
		$this->template->assign_vars(array(
			'U_CATEGORY_POPUP'		=> $this->helper->route('sylver35_smiliescat_controller_smilies_pop'),
		));
	}

	/**
	 *
	 * @param \phpbb\event\data $event The event object
	 */
	public function shoutbox_smilies($event)
	{
		$this->category->shoutbox_smilies($event);
	}

	/**
	 *
	 * @param \phpbb\event\data $event The event object
	 */
	public function shoutbox_smilies_popup($event)
	{
		$this->category->shoutbox_smilies($event);
		$this->category->shoutbox_smilies_popup($event);
	}
}
