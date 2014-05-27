<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	Contents
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


$file = JPATH_SITE .DS.'libraries'.DS.'librariesbygiro'.DS.'multiLanguages.php';
if(file_exists($file) AND !class_exists('multiLanguages')){
	require_once($file);
}

$file = JPATH_SITE .DS.'libraries'.DS.'librariesbygiro'.DS.'HtmlTxt.php';
if(file_exists($file) AND !class_exists('HtmlText')){
	require_once($file);
}



/**
* Jforms Helper functions.
*
* @package	Jforms
* @subpackage	Helper
*/
class JformsCkHelper
{
	/**
	* Cache for ACL actions
	*
	* @var object
	*/
	protected static $acl = null;

	/**
	* Directories aliases.
	*
	* @var array
	*/
	protected static $directories;

	/**
	* Determines when requirements have been loaded.
	*
	* @var boolean
	*/
	protected static $loaded = null;

	/**
	* Call a JS file. Manage fork files.
	*
	* @access	protected static
	* @param	JDocument	$doc	Document.
	* @param	string	$base	Component base from site root.
	* @param	string	$file	Component file.
	* @param	boolean	$replace	Replace the file or override. (Default : Replace)
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected static function addScript($doc, $base, $file, $replace = true)
	{
		$url = JURI::root(true) . '/' . $base . '/' . $file;
		$url = str_replace(DS, '/', $url);
		
		$urlFork = null;
		if (file_exists(JPATH_SITE .DS. $base .DS. 'fork' .DS. $file))
		{
			$urlFork = JURI::root(true) . '/' . $base . '/fork/' . $file;
			$urlFork = str_replace(DS, '/', $urlFork);
		}

		if ($replace && $urlFork)
			$url = $urlFork;

		$doc->addScript($url);

		if (!$replace && $urlFork)
			$doc->addScript($urlFork);
	}

	/**
	* Call a CSS file. Manage fork files.
	*
	* @access	protected static
	* @param	JDocument	$doc	Document.
	* @param	string	$base	Component base from site root.
	* @param	string	$file	Component file.
	* @param	boolean	$replace	Replace the file or override. (Default : Override)
	* @return	void
	*
	* @since	Cook 2.0
	*/
	protected static function addStyleSheet($doc, $base, $file, $replace = false)
	{
		$url = JURI::root(true) . '/' . $base . '/' . $file;
		$url = str_replace(DS, '/', $url);

		$urlFork = null;
		if (file_exists(JPATH_SITE .DS. $base .DS. 'fork' .DS. $file))
		{
			$urlFork = JURI::root(true) . '/' . $base . '/fork/' . $file;
			$urlFork = str_replace(DS, '/', $urlFork);
		}

		if ($replace && $urlFork)
			$url = $urlFork;

		$doc->addStyleSheet($url);

		if (!$replace && $urlFork)
			$doc->addStyleSheet($urlFork);
	}

	/**
	* Configure the Linkbar.
	*
	* @access	public static
	* @param	varchar	$view	The name of the active view.
	* @param	varchar	$layout	The name of the active layout.
	* @param	varchar	$alias	The name of the menu. Default : 'menu'
	* @return	void
	*
	* @since	1.6
	*/
	public static function addSubmenu($view, $layout, $alias = 'menu')
	{
		$items = self::getMenuItems();

		// Will be handled in XML in future (or/and with the Joomla native menus)
		// -> give your opinion on j-cook.pro/forum

		
		$client = 'admin';
		if (JFactory::getApplication()->isSite())
			$client = 'site';
	
		$links = array();
		switch($client)
		{
			case 'admin':
				switch($alias)
				{
					case 'cpanel':
					case 'menu':
					default:
						$links = array(
							'admin.forms.default',
							'admin.submissions.default'
						);
								
						if ($alias != 'cpanel')
							array_unshift($links, 'admin.cpanel');
					
						break;
				}
				break;
		
			case 'site':
				switch($alias)
				{
					case 'cpanel':
					case 'menu':
					default:
						$links = array(
							'site.forms',
							'site.submissions'
						);
								
						if ($alias != 'cpanel')
							array_unshift($links, 'site.cpanel');
					
						break;
				}
				break;
		}


		//Compile with selected items in the right order
		$menu = array();
		foreach($links as $link)
		{
			if (!isset($items[$link]))
				continue;	// Not found
		
			$item = $items[$link];
	
			// Menu link
			$extension = 'com_jforms';
			if (isset($item['extension']))
				$extension = $item['extension'];
	
			$url = 'index.php?option=' . $extension;
			if (isset($item['view']))
				$url .= '&view=' . $item['view'];
			if (isset($item['layout']))
				$url .= '&layout=' . $item['layout'];
	
			// Is active
			$active = ($item['view'] == $view);
			if (isset($item['layout']))
				$active = $active && ($item['layout'] == $layout);
	
			// Reconstruct it the Joomla format
			$menu[] = array(JText::_($item['label']), $url, $active, $item['icon']);

		}

		$version = new JVersion();
		//Create the submenu in the old fashion way
		if (version_compare($version->RELEASE, '3.0', '<'))
		{
			$html = "";	
			// Prepare the submenu module
			foreach ($menu as $entry )
				JSubMenuHelper::addEntry($entry[0], $entry[1], $entry[2]);
		}

		return $menu;
	}

	/**
	* Prepare the enumeration static lists.
	*
	* @access	public static
	* @param	string	$ctrl	The model in wich to find the list.
	* @param	string	$fieldName	The field reference for this list.
	*
	* @return	array	Prepared arrays to fill lists.
	*/
	public static function enumList($ctrl, $fieldName)
	{
		$lists = array();

		$lists["forms"]["layout_type"] = array();
		$lists["forms"]["layout_type"]["single_form"] = array("value" => "single_form", "text" => JText::_("JFORMS_ENUM_FORMS_LAYOUT_TYPE_SINGLE_FORM"));
		$lists["forms"]["layout_type"]["wizard"] = array("value" => "wizard", "text" => JText::_("JFORMS_ENUM_FORMS_LAYOUT_TYPE_WIZARD"));




		return $lists[$ctrl][$fieldName];
	}

	/**
	* Gets a list of the actions that can be performed.
	*
	* @access	public static
	*
	* @return	JObject	An ACL object containing authorizations
	*
	* @deprecated	Cook 2.0
	*/
	public static function getAcl()
	{
		return self::getActions();
	}

	/**
	* Gets a list of the actions that can be performed.
	*
	* @access	public static
	* @param	integer	$itemId	The item ID.
	*
	* @return	JObject	An ACL object containing authorizations
	*
	* @since	1.6
	*/
	public static function getActions($itemId = 0)
	{
		if (isset(self::$acl))
			return self::$acl;

		$user	= JFactory::getUser();
		$result	= new JObject;

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.edit.state',
			'core.edit.own',
			'core.delete',
			'core.delete.own',
			'core.view.own',
		);

		foreach ($actions as $action)
			$result->set($action, $user->authorise($action, COM_JFORMS));

		self::$acl = $result;

		return $result;
	}

	/**
	* Return the directories aliases full paths
	*
	* @access	public static
	*
	* @return	array	Arrays of aliases relative path from site root.
	*
	* @since	2.6.2
	*/
	public static function getDirectories()
	{
		if (!empty(self::$directories))
			return self::$directories;

		$configMedias = JComponentHelper::getParams('com_media');
		$config = JComponentHelper::getParams('com_jforms');

		$comAlias = "com_jforms";
		$directories = array(
			'DIR_FILES' => "[COM_SITE]" .DS. "files",
			'DIR_FORMS_LANGUAGE_FILE' => $config->get("upload_dir_forms_language_file", "[COM_SITE]" .DS. "files" .DS. "forms_language_file"),
			'DIR_FORMS_FIELDSETS' => $config->get("upload_dir_forms_fieldsets", "[COM_SITE]" .DS. "files" .DS. "forms_fieldsets"),
			'DIR_FORMS_EMAILS' => $config->get("upload_dir_forms_emails", "[COM_SITE]" .DS. "files" .DS. "forms_emails"),
			'DIR_FORMS_EVENTS' => $config->get("upload_dir_forms_events", "[COM_SITE]" .DS. "files" .DS. "forms_events"),
			'DIR_SUBMISSIONS_PDF' => $config->get("upload_dir_submissions_pdf", "[COM_SITE]" .DS. "files" .DS. "submissions_pdf"),
			'DIR_SUBMISSIONS_ATTACHED_FILES' => $config->get("upload_dir_submissions_attached_files", "[COM_SITE]" .DS. "files" .DS. "submissions_attached_files"),
			'DIR_TRASH' => $config->get("trash_dir", 'images' . DS . "trash"),

			'COM_ADMIN' => "administrator" .DS. 'components' .DS. $comAlias,
			'ADMIN' => "administrator",
			'COM_SITE' => 'components' .DS. $comAlias,
			'IMAGES' => $config->get('image_path', 'images'),
			'MEDIAS' => $configMedias->get('file_path', 'images'),
			'ROOT' => '',

		);



		self::$directories = $directories;
		return self::$directories;
	}

	/**
	* Get a file path or url depending of the method
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	string	$indirect	Method to access the file : [direct,indirect,physical]
	* @param	array	$options	File parameters.
	*
	* @return	string	File path or url
	*
	* @since	Cook 2.6.1
	*/
	public static function getFile($path, $indirect = 'physical', $options = null)
	{
		switch ($indirect)
		{
			case 'physical':	// Physical file on the drive (url is a path here)
				return JformsClassFile::getPhysical($path, $options);
	
			case 'direct':		// Direct url
				return JformsClassFile::getUrl($path, $options);
	
			case 'indirect':	// Indirect file access (through controller)
			default:
				return JformsClassFile::getIndirectUrl($path, $options);
		}
	}

	/**
	* Extract usefull informations from the thumb creator.
	*
	* @access	public static
	* @param	string	$path	File path. Can contain directories aliases.
	* @param	array	$options	File parameters.
	*
	* @return	mixed	Array of various informations
	*
	* @since	Cook 2.6.1
	*/
	public static function getImageInfos($path, $options = null)
	{
		include_once(JPATH_ADMIN_JFORMS .DS. 'classes' .DS. 'images.php');

		$filename = self::getFile($path, 'physical', null);

		$mime = JformsClassFile::getMime($filename);
		$thumb = new JformsClassImage($filename, $mime);

		$attrs = isset($options['attrs'])?$options['attrs']:null;
		$w = isset($options['width'])?(int)$options['width']:0;
		$h = isset($options['height'])?(int)$options['height']:0;

		if ($attrs)
			$thumb->attrs($attrs);

		$thumb->width($w);
		$thumb->height($h);
		$info = $thumb->info();
		
		return $info;
	}

	/**
	* Get an indirect url to find image through model restrictions.
	*
	* @access	public static
	* @param	string	$view	List model name
	* @param	string	$key	Field name where is stored the filename
	* @param	string	$id	Item id
	* @param	array	$options	File parameters.
	*
	* @return	string	Indirect url
	*
	* @since	Cook 2.6.1
	*/
	public static function getIndexedFile($view, $key, $id, $options = null)
	{
		return JformsClassFile::getIndexUrl($view, $key, $id, $options);
	}

	/**
	* Load all menu items.
	*
	* @access	public static
	* @return	void
	*
	* @since	Cook 2.0
	*/
	public static function getMenuItems()
	{
		// Will be handled in XML in future (or/and with the Joomla native menus)
		// -> give your opinion on j-cook.pro/forum

		$items = array();

		$items['admin.forms.default'] = array(
			'label' => 'JFORMS_LAYOUT_FORMS',
			'view' => 'forms',
			'layout' => 'default',
			'icon' => 'jforms_forms'
		);

		$items['admin.submissions.default'] = array(
			'label' => 'JFORMS_LAYOUT_SUBMISSIONS',
			'view' => 'submissions',
			'layout' => 'default',
			'icon' => 'jforms_submissions'
		);

		$items['admin.cpanel'] = array(
			'label' => 'JFORMS_LAYOUT_JFORMS',
			'view' => 'cpanel',
			'icon' => 'jforms_cpanel'
		);

		$items['site.forms'] = array(
			'label' => 'JFORMS_LAYOUT_FORMS',
			'view' => 'forms',
			'icon' => 'jforms_forms'
		);

		$items['site.submissions'] = array(
			'label' => 'JFORMS_LAYOUT_SUBMISSIONS',
			'view' => 'submissions',
			'icon' => 'jforms_submissions'
		);

		$items['site.cpanel'] = array(
			'label' => 'JFORMS_LAYOUT_JFORMS',
			'view' => 'cpanel',
			'icon' => 'jforms_cpanel'
		);

		return $items;
	}

	/**
	* Defines the headers of your template.
	*
	* @access	public static
	*
	* @return	void	
	* @return	void
	*/
	public static function headerDeclarations()
	{
		if (self::$loaded)
			return;
	
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();

		$siteUrl = JURI::root(true);

		$baseSite = 'components' .DS. COM_JFORMS;
		$baseAdmin = 'administrator' .DS. 'components' .DS. COM_JFORMS;

		$componentUrl = $siteUrl . '/' . str_replace(DS, '/', $baseSite);
		$componentUrlAdmin = $siteUrl . '/' . str_replace(DS, '/', $baseAdmin);

		//Required libraries
		//jQuery Loading : Abstraction to handle cross versions of Joomla
		JDom::_('framework.jquery');
		JDom::_('framework.jquery.chosen');
		JDom::_('framework.bootstrap');
		JDom::_('html.icon.glyphicon');
		JDom::_('html.icon.icomoon');


		//Load the jQuery-Validation-Engine (MIT License, Copyright(c) 2011 Cedric Dugas http://www.position-absolute.com)
		self::addScript($doc, $baseAdmin, 'js' .DS. 'jquery.validationEngine.js');
		self::addStyleSheet($doc, $baseAdmin, 'css' .DS. 'validationEngine.jquery.css');
		JdomHtmlValidator::loadLanguageScript();



		//CSS
		if ($app->isAdmin())
		{


			self::addStyleSheet($doc, $baseAdmin, 'css' .DS. 'jforms.css');
			self::addStyleSheet($doc, $baseAdmin, 'css' .DS. 'toolbar.css');

		}
		else if ($app->isSite())
		{
			self::addStyleSheet($doc, $baseSite, 'css' .DS. 'jforms.css');
			self::addStyleSheet($doc, $baseSite, 'css' .DS. 'toolbar.css');

			// js
			$doc->addScript($componentUrl . '/js/extra.js'); 
		}



		self::$loaded = true;
	}

	/**
	* Load the fork file. (Cook Self Service concept)
	*
	* @access	public static
	* @param	string	$file	Current file to fork.
	* @return	void
	*
	* @since	2.6.3
	*/
	public static function loadFork($file)
	{
		//Transform the file path to reach the fork directory
		$file = preg_replace("#com_jforms#", 'com_jforms' .DS. 'fork', $file);

		// Load the fork file.
		if (!empty($file) && file_exists($file))
			include_once($file);
	}

	/**
	* Recreate the URL with a redirect in order to : -> keep an good SEF ->
	* always kill the post -> precisely control the request
	*
	* @access	public static
	* @param	array	$vars	The array to override the current request.
	*
	* @return	string	Routed URL.
	*/
	public static function urlRequest($vars = array())
	{
		$parts = array();

		// Authorisated followers
		$authorizedInUrl = array(
					'option' => null, 
					'view' => null, 
					'layout' => null, 
					'Itemid' => null, 
					'tmpl' => null,
					'object' => null,
					'lang' => null);

		$jinput = JFactory::getApplication()->input;

		$request = $jinput->getArray($authorizedInUrl);

		foreach($request as $key => $value)
			if (!empty($value))
				$parts[] = $key . '=' . $value;

		$cid = $jinput->get('cid', array(), 'ARRAY');
		if (!empty($cid))
		{
			$cidVals = implode(",", $cid);
			if ($cidVals != '0')
				$parts[] = 'cid[]=' . $cidVals;
		}

		if (count($vars))
		foreach($vars as $key => $value)
			$parts[] = $key . '=' . $value;

		return JRoute::_("index.php?" . implode("&", $parts), false);
	}


	public static function getMultilangTables(){
		$lang = JFactory::getLanguage();
		$lang_tag = strtolower(str_replace('-','', $lang->getTag()));
		
		if($lang_tag != ''){
			$lang_tag = '_'.$lang_tag;
		}
		
		$tables = array();		
		$tables['forms'] = array(
			'name' => 'name'.$lang_tag,
			'description' => 'description'.$lang_tag,
			'message_after_submit' => 'message_after_submit'.$lang_tag,
			'language_file' => 'language_file'.$lang_tag
		);

		return $tables;
	}

	public static function getMlFields($item,$fields){
		
		foreach($fields as $key => $val){
			$newKey = $key .'_ml';
			if(isset($item->$val) AND is_string($item->$val)){
				$item->$newKey = ($item->$val != '') ? $item->$val : $item->$key;
			}
		}
		
		return $item;
	}
	
	public static function getInstalledLanguages(){
		$db = JFactory::getDBO();
		$sql = 'SELECT * FROM #__languages WHERE published = 1 ORDER BY ordering';
		$db->setQuery(  $sql );
		return $db->loadObjectList();	
	}
	
	public static function get_ip_address(){
		foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						return $ip;
					}
				}
			}
		}
	}
	
	/**
	 * Set value of an array by using "root.branch.leaf" notation
	 *
	 * @param array $array Array to affect
	 * @param string $path Path to set
	 * @param mixed $value Value to set the target cell to
	 * @return void
	 */
	public static function set_array_path_value($array, $path, $value)
	{
		// fail if the path is empty
		if (empty($path)) {
			return;
			// throw new Exception('Path cannot be empty');
		}
	 
		// fail if path is not a string
		if (!is_string($path)) {
			throw new Exception('Path must be a string');
		}
	 
		// specify the delimiter
		$delimiter = '.';
	 
		// remove all leading and trailing slashes
		$path = trim($path, $delimiter);
	 
		// split the path in into separate parts
		$parts = explode($delimiter, $path);
	 
		// initially point to the root of the array
		$pointer =& $array;
	 
		// loop through each part and ensure that the cell is there
		foreach ($parts as $part) {
			// fail if the part is empty
			if ($part == '') {
				throw new Exception('Invalid path specified: ' . $path);
			}
	 
			// create the cell if it doesn't exist
			if(is_object($pointer) AND !isset($pointer->$part)){
				$pointer->$part = new stdClass;
			} else if (!isset($pointer[$part])) {
				$pointer[$part] = array();
			}
	 
			// redirect the pointer to the new cell
			if(is_object($pointer)){
				$pointer =& $pointer->$part;
			} else if (is_array($pointer)) {
				$pointer =& $pointer[$part];
			}
			
		}
	 
		// set value of the target cell
		$pointer = $value;
		
		return $array;
	}
	
	/**
	 * Get value of an array by using "root/branch/leaf" notation
	 *
	 * @param array $array   Array to traverse
	 * @param string $path   Path to a specific option to extract
	 * @param mixed $default Value to use if the path was not found
	 * @return mixed
	 */
	public static function array_path_value($array, $path, $default = null)
	{
		// specify the delimiter
		$delimiter = '.';
	 
		// fail if the path is empty
		if (empty($path)) {
			return;
			// throw new Exception('Path cannot be empty');
		}
	 
		// remove all leading and trailing slashes
		$path = trim($path, $delimiter);
	 
		// use current array as the initial value
		$value = $array;
	 
		// extract parts of the path
		$parts = explode($delimiter, $path);
	 
		// loop through each part and extract its value
		foreach ($parts as $part) {
			if(is_array($value) AND isset($value[$part])){
				// replace current value with the child
				$value = $value[$part];
			} else if(is_object($value) AND isset($value->$part)){
				// replace current value with the child
				$value = $value->$part;
			} else {
				// key doesn't exist, fail
				return $default;
			}
		}
	 
		return $value;
	}
	
	public static function sendEmails($emails){		
		$result = array();
		foreach($emails as $email){
			// get Mailer
			$mailer = JFactory::getMailer();		
			$mailer->setSender($email->sender);
			$mailer->addReplyTo($email->reply_to[0],$email->reply_to[1]);
			$mailer->addRecipient($email->recipients[0],$email->recipients[1]);
			$mailer->addCC($email->recipients_cc[0],$email->recipients_cc[1]);
			$mailer->addBCC($email->recipients_bcc[0],$email->recipients_bcc[1]);
			$mailer->addAttachment($email->attachment);
			$mailer->setSubject($email->subject);
			
			$body = $email->body;
			
			$mailer->isHTML(true);
			$isHTML = $email->html;
			if($isHTML > 0){
				$mailer->isHTML(true);
			} else {
				$mailer->isHTML(false);
				
				// convert HTML body to plain text body
				$HtmlText = new HtmlText($body);
				$body = $HtmlText->get_text();
			}

			$mailer->Encoding = 'base64';
			$mailer->setBody($body);
			
			// send email
			$send = $mailer->Send();
			if ( $send !== true ) {
				$result[] = 'ERROR: '. $send->get('message');
			} else {
				$result[] = 'OK';
			}
		}		
	}
	
	public static function objectToArray($object, $recursive = false)
	{
		$array=array();
		if(!is_object($object) AND !is_array($object)){
			return $object;
		}		
		
		foreach($object as $key => $value)
		{
			if($recursive AND (is_object($value) OR is_array($value))){
				$value = self::objectToArray($value, true);
			}			
			$array[$key] = $value;
		}
		return $array;
	}

	public static function arrayToObject($array, $recursive = false)
	{
		$object = new stdClass;
		if(!is_array($array) AND !is_object($array)){
			return $array;
		}
		
		foreach($array as $key => $value)
		{
			if($recursive AND (is_object($value) OR is_array($value))){
				$value = self::ArrayToObject($value, true);
			}
			$object->$key = $value;
		}
		return $object;
	}

	public static function generatePdf($html, $download = false){
		$mpdf_library = JPATH_SITE .DS.'libraries'.DS.'librariesbygiro'.DS.'mpdf'.DS.'mpdf.php';

		if(file_exists($mpdf_library)){
			require_once($mpdf_library);
		}
		
		$mpdf=new mPDF();

		// add CSS files for better rendering
		$css_files = array();
		// jshop css
		$css_files[] = JPATH_SITE .DS.'libraries'.DS.'jdom'.DS.'assets'.DS.'bootstrap'.DS.'css'.DS.'bootstrap.css';
		$css_files[] = JPATH_SITE .DS.'libraries'.DS.'jdom'.DS.'assets'.DS.'bootstrap'.DS.'css'.DS.'bootstrap-legacy.css';
		$css_files[] = JPATH_SITE .DS.'libraries'.DS.'jdom'.DS.'assets'.DS.'bootstrap'.DS.'css'.DS.'bootstrap-icons.css';
		$css_files[] = JPATH_SITE .DS.'components'.DS.'com_jforms'.DS.'css'.DS.'jforms.css';
		$css_files[] = JPATH_SITE .DS.'components'.DS.'com_jforms'.DS.'css'.DS.'pdf.css';

		foreach($css_files as $css){
			if(file_exists($css)){
				$stylesheet = '<style>'. file_get_contents($css) .'</style>';
				$mpdf->WriteHTML($stylesheet,1);
			}
		}
		
		$mpdf->setFooter('{PAGENO} / {nb}');
		$mpdf->WriteHTML($html);

		if($download){
			$mpdf->Output(self::generateRandomString(15) .'.pdf', 'D');
			return;
		}
		
		$content = $mpdf->Output('', 'S');
		return $content;
	}
	
	public static function getLabels($data){
		$labels = array();
		$jForm = $data['jforms_snapshot'];
		
		foreach($jForm->fieldsets as $mainFieldset){			
			if($mainFieldset->form instanceof JForm){
				$fieldsets = $mainFieldset->form->getFieldsets();
				
				foreach($fieldsets as $fset){
					$fields = $mainFieldset->form->getFieldset($fset->name);
					
					foreach($fields as $fi){
						if(method_exists($fi,'getOutput')){
							$arr = $fi->getAllLabels();
							if(count($arr) > 0){
								$labels[$fi->fieldname] = $arr;
							}
						}
					}
				}
			}
		}
	
		return $labels;
	}
	
	public static function replacer($str, $data){
		static $labels;
		
		if(!isset($labels)){
			$labels = self::getLabels($data);
		}
		
		foreach($data as $key => $details){
			if(is_string($details)){
				$regex_simple = '[['. $key .']]';
				$str = str_replace($regex_simple,$details,$str);
			} else {
				$regex_simple = '[['. $key .':';
				$regex = '#\[\['. $key .'\:([^\]]+)\]\]#';
			
				if (!(strpos($str, $regex_simple) === false)) {
					if (preg_match_all($regex, $str, $matches, PREG_SET_ORDER) > 0) {				
						$str = self::replacerHelper($matches,$str, $key, $details, $labels);
					}
				}
			}	
		}

		if (preg_match_all('#\{([^\}]+)}#', $str, $matches, PREG_SET_ORDER) > 0) {	
			foreach ($matches as $match) {				
				$str = str_replace($match['0'], JText::_(strtoupper($match['1'])), $str);
			}
		}
		
		return $str;
	}	

	protected function replacerHelper($matches,$str, $key, $details, $labels){
		foreach ($matches as $match) {
			$found = true;
			$value = $details;
			if(is_object($value)){
				$value = (array)$value;
			}
			$var = $match['1'];
			
			$variables = explode(':',$match['1']);		
			// nested variables
			if(count($variables) > 1){
				foreach($variables as $var){
					if(is_object($value)){
						$value = (array)$value;
					}
					
					if(!isset($value[$var])){
						break;
						$found = false;
						$value = '';
					}
					$value = $value[$var];
				}							
			} else {
				if(isset($value[$var])){
					$value = $value[$var];
				} else {
					$value = '';
				}
			}

			if (!$found){
				continue;
			}

			if(is_array($value) OR is_object($value)){
				$html = '<ul>';
				foreach($value as $v){
					$v = (string)$v;
					if(isset($labels[$var])){
						$v = JText::_($labels[$var][$v]);
					}
				
					$html .= '<li>'. $v .'</li>';
				}
				$html .= '</ul>';							
				$value = $html;
			} else {
				if(isset($labels[$var])){
					$value = JText::_($labels[$var][$value]);
				}
			}						
			
			$str = str_replace($match['0'], $value, $str);
		}

		return $str;
	}
	
	public static function generateRandomString($length = 5) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function escapeJsonString($value) {
		# list from www.json.org: (\b backspace, \f formfeed)    
		$escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
	}
	
    /**
     * Checks whether a string is valid json.
     *
     * @param string $string
     * @return boolean
     */
    function is_json($string)
    {
		$result = false;
        try
        {
            // try to decode string
            $result = json_decode($string);
        }
        catch (ErrorException $e)
        {
            // exception has been caught which means argument wasn't a string and thus is definitely no json.
            return false;
        }

        // check if error occured
        return (json_last_error() == JSON_ERROR_NONE) ? false : $result;
    }
	
	// inverse will convert from arrays to objects
	function objectToArrayRecursive($obj, $inverse = false, $result = array()){
		$arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($arrObj as $key => $val) {
			
			if(is_array($val) || is_object($val)){				
				$val = self::objectToArrayRecursive($val,$inverse);
			}
			
			if($inverse){ // all -> objects	
				$result = (object)$arr;
				$result->$key = $val;
			} else { // all -> arrays
				$result[$key] = $val;
			}
		}
		
		return $result;
	}
	
	public static function groupArrayByValue($array, $keyName, $multiple = true){
		if(!is_array($array)){
			(array)$array;
		}
		
		$newArray = array();
		foreach($array as $key => $it){
			if(is_array($it)){
				if($multiple){
					$newArray[$it[$keyName]][$key] = $it;
				} else {
					$newArray[$it[$keyName]] = $it;
				}
			} elseif(is_object($it)){
				if($multiple){
					$newArray[$it->$keyName][$key] = $it;
				} else {
					$newArray[$it->$keyName] = $it;
				}
			}
		}
		
		return $newArray;
	}

	public function sort_on_field($objects, $on, $order = 'ASC') { 
		$comparer = ($order === 'DESC') 
			? "return -strcmp(\$a->{$on},\$b->{$on});" 
			: "return strcmp(\$a->{$on},\$b->{$on});"; 
		usort($objects, create_function('$a,$b', $comparer));

		return $objects;
	}

	// get all fieldset data and simgle forms instances
	public function getjFieldsets($jForm, $live = true, $load = true){
		if(is_array($jForm)){
			$jForm = (object)$jForm;
		} else if(!is_object($jForm)){
			return;
		}
	
		$app = JFactory::getApplication();
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$files_dir = $config->get('upload_dir_forms_fieldsets', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_fieldsets');

		if($live){
			// sorting fieldsets
			$jForm->fieldsets = self::sort_on_field($jForm->fieldsets, 'ordering', 'ASC');	
		}		

		$k = 0;
		
		foreach($jForm->fieldsets as $fset){
			$k++;
			
			$formFile = $files_dir .DS. $fset->form_file;
			
			if($live AND $fset->form_file != '' AND is_file($formFile)){
				$xmlString = file_get_contents($formFile);
				$fset->form_file_content = $xmlString;
			}

			if(!$load OR !isset($fset->form_file_content) OR $fset->form_file_content == ''){
				continue;
			}

			// test the XML
			libxml_use_internal_errors(true);
			$doc_test = simplexml_load_string($fset->form_file_content);
			$xml_test = explode("\n", $fset->form_file_content);

			/*
			if (!$doc_test AND 1 == 0) {
				$errors = libxml_get_errors();

				foreach ($errors as $error) {
					echo self::display_xml_error($error, $xml_test);
				}

				libxml_clear_errors();				
				continue;
			}
			*/
			
			// create the single form
			$fset_form = JForm::getInstance('com_jforms.fset'. $k, $fset->form_file_content,array('control'=>'jform'));	
			
			if($fset_form instanceof JForm){
				$fset->form = $fset_form;
			}			
		}
		
		return $jForm;
	}

	public function getMainForm($jForm){
		if(is_array($jForm)){
			$jForm = (object)$jForm;
		} else if(!is_object($jForm)){
			return;
		}
		$form = null;
		foreach($jForm->fieldsets as $fset){
			if($fset->enabled != 'true' OR !isset($fset->form)){
				continue;
			}
			
			// integrate forms
			if(!($form instanceof JForm)){
				$form = JForm::getInstance('com_jforms.main', $fset->form_file_content, array('control'=>'jform'));
				$form->addFieldPath(JPATH_SITE .DS. 'libraries/jdom/jform/fields');
				$form->addRulePath(JPATH_SITE .DS. 'libraries/jdom/jform/rules');				
			} else {
				// merge this form with the main form
				$xml = simplexml_load_string($fset->form_file_content);
				$form->load($xml, true);
			}
		}
			
		$jForm->form = $form;
		
		return $jForm;
	}
	
	// get the language files and load the current in memory
	public function getjFormLanguageFiles($jForm, $live = true, $load = true){
		if(is_array($jForm)){
			$jForm = (object)$jForm;
		} else if(!is_object($jForm)){
			return;
		}
		
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$jForm_lang_dir = $config->get('upload_dir_forms_language_file', JPATH_SITE .DS. 'components' .DS. 'com_jforms' .DS. 'files' .DS. 'forms_language_file');
		$lang = JFactory::getLanguage();
		
		if($live){
			$installedLanguages = JformsHelper::getInstalledLanguages();
			$lang_prefix = array('default'=>'');
			foreach($installedLanguages as $lg){
				$lang_prefix[$lg->lang_code] = '_'. strtolower(str_replace('-','', $lg->lang_code));
			}
		
			foreach($lang_prefix as $key => $pref){
				$lang_var = 'language_file'. $pref;
				$lang_var_content = 'language_file_content'. $pref;				
				
				$file = $jForm_lang_dir .DS. $jForm->$lang_var;
				if($jForm->$lang_var != '' AND is_file($formFile)){
					$jForm->$lang_var_content = file_get_contents($jForm_lang_dir .DS. $jForm->$lang_var);
				}
			}
		}

		if($load){
			$languages_to_load = array(
				'default' => '',
				$lang->getTag() => '_'. strtolower(str_replace('-','', $lang->getTag()))
			);
			
			$temp_files = array();
			foreach($languages_to_load as $joomlaTag => $jFormsTag){
				$langFileVar = 'language_file'. $jFormsTag;
				$lang_var_content = 'language_file_content'. $jFormsTag;
				
				if(!$live){
					if($jForm->$lang_var_content == ''){
						continue;
					}
					
					// create a temp filename
					do{
						$filename = 'language/'. $joomlaTag .'/'. $joomlaTag .'.'. self::generateRandomString(15) .'.ini';
					} while(file_exists($jForm_lang_dir .DS. $filename));
					
					$jForm->$langFileVar = $filename;
					file_put_contents($jForm_lang_dir .DS. $jForm->$langFileVar, $jForm->$lang_var_content);
					
					$temp_files[] = $jForm_lang_dir .DS. $jForm->$langFileVar;
				}
		
				self::loadCustomLangFile($jForm->$langFileVar,$jForm_lang_dir,$joomlaTag);
			}
		}
		
		// remove the temp language files
		foreach($temp_files as $tmpFile){
			unlink($tmpFile);
		}
		
		return $jForm;
	}

function display_xml_error($error, $xml)
{
    $return  = $xml[$error->line - 1] . "\n";
    $return .= str_repeat('-', $error->column) . "^\n";

    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "Warning $error->code: ";
            break;
         case LIBXML_ERR_ERROR:
            $return .= "Error $error->code: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "Fatal Error $error->code: ";
            break;
    }

    $return .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";

    if ($error->file) {
        $return .= "\n  File: $error->file";
    }

    return "$return\n\n--------------------------------------------\n\n";
}

	public static function loadCustomLangFile($filename,$dir,$language){
		if($filename == ''){
			return;
		}
		
		$lang = JFactory::getLanguage();

		$fake_extension = basename($filename);
		$fake_extension = explode('.',$fake_extension);
		$fake_extension = $fake_extension[1];
		$lang->load($fake_extension , JPath::clean($dir), $language, true);	
	}
	
	public static function triggerEvents($type,&$data){
		static $config;
		static $version;
		
		if(empty($version)){
			$version = new JVersion();
		}
		
		if(empty($config)){
			$config = JComponentHelper::getParams( 'com_jforms' );
		}

		$files_dir = $config->get('upload_dir_forms_events', JPATH_COMPONENT .DS. 'files' .DS. 'forms_events');
		
		foreach($data['jForm']->events as $event){
			$file = $files_dir . DS . $event->file;
			if($event->enabled != 'true' OR $event->event != $type){
				continue;
			}
		
			if(is_file($file)){
				// include the file
				try {
					include $file;
				} catch (RuntimeException $e) {

				}
			}

			if($event->script != ''){
				$script = $event->script;
				try {
					eval("?> $script <?php ");
				} catch (RuntimeException $e) {

				}
			}
			
		}

		// trigger joomla jForms plugins
		JPluginHelper::importPlugin( 'jForms' );
		
		// Joomla! 1.6 - 1.7 - 2.5
		if (version_compare($version->RELEASE, '2.5', '<='))
		{	
			$dispatcher = JDispatcher::getInstance();
		} else {
			$dispatcher = JEventDispatcher::getInstance();
		}
		
		$dispatcher->trigger( $type, $data );
	}
	
	public static function getUniquePath(&$filePath){
		$fileName = basename($filePath);
		$dir = dirname($filePath);
		while(file_exists($filePath)){
			$rand = JformsHelper::generateRandomString(5);
			$fileNameParts = explode('.',$fileName);						
			$fileNameParts[(count($fileNameParts) -2)] = $fileNameParts[(count($fileNameParts) -2)] .'_'. $rand;
			$filePath = $dir .DS. implode('.',$fileNameParts);
		}
	}
	
	public function loginFirstly(){

		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		if($user->guest){
			$u = JURI::getInstance();
			$currentURL = $u->toString();		

			$redirectUrl = urlencode(base64_encode($currentURL));
			$redirectUrl = '&return='.$redirectUrl;
			$joomlaLoginUrl = 'index.php?option=com_users&view=login';
	
			$app->redirect(JRoute::_($joomlaLoginUrl . $redirectUrl, false), JText::_('JGLOBAL_YOU_MUST_LOGIN_FIRST'), 'warning');			
			return false;
		}

		return true;
	}

/*
*
*	function by AKEEBA BACKUP
*
*/
	
	public static function colorise($file, $onlyLast = false)
	{
		$ret = '';
		
		$lines = @file($file);
		if(empty($lines)) return $ret;
		
		array_shift($lines);
		
		foreach($lines as $line) {
			$line = trim($line);
			if(empty($line)) continue;
			$type = substr($line,0,1);
			switch($type) {
				case '=':
					continue;
					break;
					
				case '+':
					$ret .= "\t".'<li class="jForms-changelog-added"><i class="changelog-icon-added"></i>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '-':
					$ret .= "\t".'<li class="jForms-changelog-removed"><i class="changelog-icon-removed"></i>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '~':
					$ret .= "\t".'<li class="jForms-changelog-changed"><i class="changelog-icon-changed"></i>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '!':
					$ret .= "\t".'<li class="jForms-changelog-important"><i class="changelog-icon-important"></i>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				case '#':
					$ret .= "\t".'<li class="jForms-changelog-fixed"><i class="changelog-icon-fixed"></i>'.htmlentities(trim(substr($line,2)))."</li>\n";
					break;
				
				default:
					if(!empty($ret)) {
						$ret .= "</ul>";
						if($onlyLast) return $ret;
					}
					if(!$onlyLast) $ret .= "<h3 class=\"jForms-changelog\">$line</h3>\n";
					$ret .= "<ul class=\"jForms-changelog\">\n";
					break;
			}
		}
		
		return $ret;
	}	

}

// Load the fork
JformsCkHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsHelper')){ class JformsHelper extends JformsCkHelper{} }

