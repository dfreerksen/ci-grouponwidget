<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Groupon Widget Class
 *
 * This class allows you to create a Groupon Widget for your website.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Library
 * @author      David Freerksen
 * @link        https://github.com/dfreerksen/ci-grouponwidget
 */
class Grouponwidget {

	protected $_config = array(
		'referral_code' => NULL,
		'rounded' => NULL,
		'header_color' => NULL,
		'shell_background_color' => NULL,
		'shell_text_color' => NULL,
		'deal_link_color' => NULL,
		'pricetag_background_color' => NULL,
		'get_it_background_color' => NULL,
		'city' => NULL
	);

	/**
	 * Constructor
	 *
	 * @param   array   $config
	 */
	public function __construct($config = array())
	{
		if ( ! empty($config))
		{
			$this->initialize($config);
		}

		log_message('debug', 'Groupon Widget Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Initialize config values
	 *
	 * @param   array
	 * @return  Grouponwidget
	 */
	public function initialize($config = array())
	{
		foreach ($config as $name => $value)
		{
			$this->__set($name, $value);
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * get magic method
	 *
	 * @param   string
	 * @return  mixed
	 */
	public function __get($name)
	{
		return array_key_exists($name, $this->_config) ? $this->_config[$name] : NULL;
	}

	// --------------------------------------------------------------------

	/**
	 * set magic method
	 *
	 * @param   string
	 * @return  void
	 */
	public function __set($name, $value)
	{
		if (array_key_exists($name, $this->_config))
		{
			$this->_config[$name] = $value;
		}
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the form
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return $this->generate();
	}

	// --------------------------------------------------------------------

	/**
	 * Referral code
	 *
	 * @param   string  $code
	 * @return  Grouponwidget
	 */
	public function referral_code($code = NULL)
	{
		if ($code)
		{
			$this->_config['referral_code'] = $code;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Rounded corners
	 *
	 * @param   bool    $rounded
	 * @return  Grouponwidget
	 */
	public function rounded($rounded = TRUE)
	{
		$this->_config['rounded'] = $rounded;

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Header color
	 *
	 * @param   string  $color
	 * @return  Grouponwidget
	 */
	public function header_color($color = NULL)
	{
		if ($this->_valid_hex($color))
		{
			$this->_config['header_color'] = $color;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Shell background color
	 *
	 * @param   string  $color
	 * @return  Grouponwidget
	 */
	public function shell_background_color($color = NULL)
	{
		if ($this->_valid_hex($color))
		{
			$this->_config['shell_background_color'] = $color;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Shell text color
	 *
	 * @param   string  $color
	 * @return  Grouponwidget
	 */
	public function shell_text_color($color = NULL)
	{
		if ($this->_valid_hex($color))
		{
			$this->_config['shell_text_color'] = $color;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Deal link color
	 *
	 * @param   string  $color
	 * @return  Grouponwidget
	 */
	public function deal_link_color($color = NULL)
	{
		if ($this->_valid_hex($color))
		{
			$this->_config['deal_link_color'] = $color;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Pricetag background color
	 *
	 * @param   string  $color
	 * @return  Grouponwidget
	 */
	public function pricetag_background_color($color = NULL)
	{
		if ($this->_valid_hex($color))
		{
			$this->_config['pricetag_background_color'] = $color;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Get t background color
	 *
	 * @param   string  $color
	 * @return  Grouponwidget
	 */
	public function get_it_background_color($color = NULL)
	{
		if ($this->_valid_hex($color))
		{
			$this->_config['get_it_background_color'] = $color;
		}

		return $this;
	}

	// --------------------------------------------------------------------

	/**
	 * Alias for generate()
	 *
	 * @return  string
	 */
	public function render($config = array())
	{
		return $this->generate();
	}

	// --------------------------------------------------------------------

	/**
	 * Generate Groupon Widget code
	 *
	 * @param   array   $config
	 * @return  string
	 */
	public function generate($config = array())
	{
		// Settings were passed
		if ( ! empty($config))
		{
			$this->initialize($config);
		}

		// If the page is HTTPS we need to account for that when loading in the page
		$http = 'http';

		if (isset($_SERVER['HTTPS']))
		{
			$http .= 's';
		}

		// Start with loading the Groupon Javascript
		$result = '<script type="text/javascript" src="'.$http.'://groupon.com/javascripts/common/widget.js"></script>';

		$json = array(
			'theme' => array(
				'header' => array(
					'color' => $this->_config['header_color']
				),
				'shell' => array(
					'background' => $this->_config['shell_background_color'],
					'color' => $this->_config['shell_text_color']
				),
				'rounded' => $this->_config['rounded'],
				'deal' => array(
					'link_color' => $this->_config['deal_link_color']
				),
				'buttons' => array(
					'get_it_btn' => array(
						'background' => $this->_config['pricetag_background_color']
					),
					'price_tag_btn' => array(
						'background' => $this->_config['get_it_background_color']
					)
				)
			),
			'referral_code' => $this->_config['referral_code'],
			'city' => $this->_config['city']
		);

		// Remove NULL values
		$json = array_filter($json, array($this, '_remove_null_items'));

		// Add the Javascript code to generate the widget
		$result .= '<script type="text/javascript">new GRPN.Widget('.json_encode($json).').render();</script>';

		return $result;
	}

	// --------------------------------------------------------------------

	/**
	 * Test if string is a valid HEX code
	 *
	 * @param   string  $hex
	 * @return  bool
	 */
	private function _valid_hex($hex = '')
	{
		return (preg_match('/^#(?:(?:[a-fd]{3}){1,2})$/i', $hex)) ? TRUE : FALSE;
	}

	// --------------------------------------------------------------------

	/**
	 * Recursive array_filter
	 *
	 * @param   array   $input
	 * @param   string  $callback
	 * @return  array
	 */
	private function _remove_null_items($item = array())
	{
		if (is_array($item))
		{
			return array_filter($item, array($this, '_remove_null_items'));
		}

		if ( ! empty($item))
		{
			return TRUE;
		}
	}

}
// END Grouponwidget class

/* End of file Grouponwidget.php */
/* Location: ./application/libraries/Grouponwidget.php */