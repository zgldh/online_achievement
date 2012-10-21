<?php
class JavascriptCssManager
{
	private $_javascripts = array ();
	private $_auto_javascript_codes = array ();
	private $_styles = array ();
	private $_style_codes = array ();
	function __construct()
	{
	}
	
	/**
	 * 添加一个Javascript文件
	 * 
	 * @param String $path        	
	 * @return boolean true成功添加 false已经存在
	 */
	public function addJavascriptFile($path)
	{
		if (! array_key_exists ( $path, $this->_javascripts ))
		{
			$this->_javascripts [] = $path;
			return true;
		}
		return false;
	}
	/**
	 * 添加一段自动执行的Javascript代码
	 * 
	 * @param String $path        	
	 */
	public function addAutoRunJavascriptCode($code)
	{
		$this->_auto_javascript_codes [] = $code;
	}
	/**
	 * 添加一个css文件
	 * 
	 * @param String $path        	
	 * @return boolean true成功添加 false已经存在
	 */
	public function addStyleFile($path)
	{
		if (! array_key_exists ( $path, $this->_styles ))
		{
			$this->_styles [] = $path;
			return true;
		}
		return false;
	}
	/**
	 * 添加一段css代码
	 * 
	 * @param
	 *        	String css code
	 */
	public function addStyleCode($code)
	{
		$this->_style_codes [] = $code;
	}
	
	/**
	 * 得到javascripts文件数组
	 * 
	 * @return array
	 */
	public function getJavascripts()
	{
		return $this->_javascripts;
	}
	/**
	 * 得到javascripts执行代码数组
	 * 
	 * @return array
	 */
	public function getJavascriptCodes()
	{
		return $this->_auto_javascript_codes;
	}
	/**
	 * 得到 css 文件数组
	 * 
	 * @return array
	 */
	public function getStyles()
	{
		return $this->_styles;
	}
	/**
	 * 得到 css 代码数组
	 * 
	 * @return array
	 */
	public function getStyleCodes()
	{
		return $this->_style_codes;
	}
	
	/**
	 * 将全部javascript和style以html标签形式输出
	 */
	public function outputAll()
	{
		$this->outputJavascripts ();
		$this->outputStyles ();
	}
	/**
	 * 输出javascript的全部html标签形式的代码
	 */
	public function outputJavascripts()
	{
		if ($this->_javascripts)
		{
			foreach ( $this->_javascripts as $index => $javascript_file )
			{
				echo '<script src="' . $javascript_file . '"></script>';
			}
		}
		
		if ($this->_auto_javascript_codes)
		{
			echo '<script type="text/javascript">';
			echo '$(function(){';
			foreach ( $this->_auto_javascript_codes as $index => $code )
			{
				echo $code;
			}
			echo '});';
			echo '</script>';
		}
	}
	/**
	 * 输出style的全部html标签形式的代码
	 */
	public function outputStyles()
	{
		if ($this->_styles)
		{
			foreach ( $this->_styles as $index => $style_file )
			{
				echo '<link rel="stylesheet" href="' . $style_file . '" />';
			}
		}
		
		if ($this->_style_codes)
		{
			echo '<style>';
			foreach ( $this->_style_codes as $index => $css_code )
			{
				echo $css_code;
			}
			echo '</style>';
		}
	}
}