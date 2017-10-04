<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Pixext
 * @author     Johan Sundell <johan@pixpro.net>
 * @copyright  2017 Johan Sundell
 * @license    GNU General Public License version 2 eller senare; se LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Cpanels list controller class.
 *
 * @since  1.6
 */
class PixextControllerCpanels extends JControllerAdmin
{
	public function upload()
	{
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$file = $this->input->files->get('file_upload', null, 'raw');
		//print_r( $file ); die();
		if( function_exists( 'curl_file_create' ) ) // php 5.6+
			$cFile = curl_file_create( $file['tmp_name']);
		else
			$cFile = '@'.realpath($file['tmp_name']);
		$params = JComponentHelper::getParams( 'com_pixext' );
		$post = array('extra_info' => $file['size'],'file_contents'=> $cFile, 'joomla_default' => $params->get( 'joomla_default' ), 'user_id' => JFactory::getUser()->id );
		$ch = curl_init();
		$url = $params->get('url');
		curl_setopt( $ch, CURLOPT_URL, $url.'/pixext/admin/upload' );
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER , 1 );
		$result = curl_exec( $ch );
		$httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$curl_error = curl_error( $ch );
		curl_close( $ch );
		$msg = '';
		if( $httpCode == 200 )
		{
			$msg = 'Worked';
		}
		else
		{
			$msg = $curl_error;
		}
			
		//print_r( $result ); die();
		$this->setRedirect( 'index.php?option=com_pixext&view=cpanels', $msg );
	}
	
	public function ping()
	{
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		if( $this->getModel()->ping() )
			JFactory::getApplication()->enqueueMessage( 'Works' );
		else
			JFactory::getApplication()->enqueueMessage( 'Could not connect', 'error' );
		
		$this->setRedirect('index.php?option=com_pixext&view=cpanels');
	}
	
	public function getInstallations()
	{
		$pixext_extension_id = (int)JFactory::getApplication()->input->getInt( 'pixext_extension_id' );
		$data = PixextHelpersPixext::getComponentInstallations( $pixext_extension_id, 0, 0 );
		echo json_encode( $data ); die();
		return;
	}
	/**
	 * Method to clone existing Cpanels
	 *
	 * @return void
	 */
	public function duplicate()
	{
		// Check for request forgeries
		Jsession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Get id(s)
		$pks = $this->input->post->get('cid', array(), 'array');

		try
		{
			if (empty($pks))
			{
				throw new Exception(JText::_('COM_PIXEXT_NO_ELEMENT_SELECTED'));
			}

			ArrayHelper::toInteger($pks);
			$model = $this->getModel();
			$model->duplicate($pks);
			$this->setMessage(Jtext::_('COM_PIXEXT_ITEMS_SUCCESS_DUPLICATED'));
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
		}

		$this->setRedirect('index.php?option=com_pixext&view=cpanels');
	}

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    Optional. Model name
	 * @param   string  $prefix  Optional. Class prefix
	 * @param   array   $config  Optional. Configuration array for model
	 *
	 * @return  object	The Model
	 *
	 * @since    1.6
	 */
	public function getModel($name = 'cpanels', $prefix = 'PixextModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks   = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		ArrayHelper::toInteger($pks);
		ArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
}
