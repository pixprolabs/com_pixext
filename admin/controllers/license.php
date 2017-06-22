<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Pixext
 * @author     Johan Sundell <johan@pixpro.net>
 * @copyright  2017 Johan Sundell
 * @license    GNU General Public License version 2 eller senare; se LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * License controller class.
 *
 * @since  1.6
 */
class PixextControllerLicense extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'licenses';
		parent::__construct();
	}
}
