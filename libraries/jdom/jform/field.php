<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.2   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
* @added by		Girolamo Tomaselli - http://bygiro.com
* @version		0.0.1
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// JDOM html validator
JLoader::register('JdomHtmlValidator', JPATH_SITE . DS . 'libraries' . DS . 'jdom' . DS . 'jform' . DS . 'html' . DS . 'validator.php');

/**
* Form field for Jdom.
*
* @package	Jdom
* @subpackage	Form
*/
class JdomClassFormField extends JFormField
{
	/**
	* The literal input HTML.
	*
	* @var string
	*/
	protected $input = null;

	/**
	* Cache for ACL actions
	*
	* @var object
	*/
	protected static $acl = null;

	/**
	* The literal label HTML.
	*
	* @var string
	*/
	protected $label = null;
	
	/**
	* The literal label HTML MARKUP.
	*
	* @var string
	*/
	public $markup = null;

	/**
	* The form field dynamic options (JDom legacy).
	*
	* @var array
	*/
	public $jdomOptions = array();

	/**
	* Method to get extended properties of the field.
	*
	* @access	public
	* @param	string	$name	Name of the property.
	*
	* @return	mixed	Field property value.
	*/
	public function __get($name)
	{
		switch ($name)
		{
			case 'format':
			case 'position':
			case 'align':
			case 'responsive':
				return $this->element[$name];
				break;
		}
		return parent::__get($name);
	}

	/**
	* Method to check the authorisations.
	*
	* @access	public
	*
	* @return	boolean	True if user is allowed to see the field.
	*/
	public function canView()
	{
		if (!isset($this->element['access']))
			return true;

		$access = (string)$this->element['access'];

		$acl = $this->getActions();
		foreach(explode(",", $access) as $action)
			if ($acl->get($action))
				return true;

		return false;
	}

	/**
	* Method to get the field input markup.
	*
	* @access	public
	*
	* @return	string	The field input markup.
	*
	* @since	11.1
	*/
	public function getInput()
	{
		if (!$this->canView())
			return;

		//Loads the front javascript and HTML validator (inspirated by JDom.)
		//Hidden messages strings are created in order to fill the javascript message alert.
		//When an error occurs, the messages appears under each field.
		//On loading, the informations messages for each field are shown when values are empties.
		//Each validation occurs on the 'change' event, and merged together in alert box on form submit.
		//If the field is required without validation rule, the helper is called only for the required message implementation.

		$input = $this->input;		
		$input .= JdomHtmlValidator::loadValidators($this->element, $this->id);

		return $input;
	}
	
	
	/**
	* Method to read an option parameter.
	*
	* @access	protected
	* @param	string	$name	The parameter name.
	* @param	string	$default	Default value.
	* @param	string	$type	Var type for this parameter.
	*
	* @return	mixed	Parameter value.
	*/
	public function getOption($name, $default = null, $type = null)
	{
		if (!isset($this->element[$name]))
			return $default;

		$elemValue = $this->element[$name];

		switch($type)
		{
			case 'bool':
				$elemValue = (string)$elemValue;
				if (($elemValue == 'true') || ($elemValue == '1'))
					return true;

				if (($elemValue == 'false') || ($elemValue == '0'))
					return false;

				if (!empty($elemValue))
					return true;

				return false;
				break;

			case 'int':
				return (int)$elemValue;
				break;

			default:
				return (string)$elemValue;
				break;
		}
	}

	/**
	* Method to dynamic config the control.
	*
	* @access	public
	* @param	array	$options	An array of options.
	*
	* @return	void	
	* @return	void
	*/
	public function setJdomOptions($options)
	{
		$this->jdomOptions = $options;
	}


	public function getAllLabels()
	{
		$values = array();
		$type = $this->getOption('type');
	
		switch($type){		
			case 'accesslevel':
			case 'author':
			case 'checkbox':
			case 'checkboxes':
			case 'chromestyle':
			case 'ckaccesslevel':
			case 'ckcheckbox':
			case 'ckcheckboxes':
			case 'ckcombo':
			case 'ckcontentlanguage':
			case 'cklist':
			case 'ckradio':
			case 'ckrules':
			case 'combo':
			case 'contentlanguage':
			case 'contenttype':
			case 'databaseconnection':
			case 'imagelist':
			case 'language':
			case 'list':
			case 'predefinedlist':
			case 'radio':
			case 'rules':
			case 'sql':
			case 'status':
			
				// array values				
				if(($type == 'ckcheckbox' OR $type == 'checkbox') AND (string)$this->element['value'] <= 1){
					$values[0] = JText::_('JNO');
					$values[1] = JText::_('JYES');
				}				
				
				$options = $this->getOptions();
				foreach($options as $opt){
					$listKey = $this->getOption('listKey') ? $this->getOption('listKey') : 'id';
					$labelKey = $this->getOption('labelKey') ? $this->getOption('labelKey') : 'text';
					$values[$opt->$listKey] = $opt->$labelKey;
				}
			break;
		}

		return $values;
	}

	public function getOutput($tmplEngine = null)
	{
		$html = '';
		if(!isset($this->value)){
			return $html;
		}
		
		$values = $this->getAllLabels();
		$type = $this->getOption('type');

		switch($type){				
			case 'ckfile':
				// TO DO: find a better place to create the jdomOptions
				$this->jdomOptions = array_merge(array(
						'dataKey' => $this->getOption('name'),
						'formGroup' => $this->group,
						'formControl' => $this->formControl,
						'domClass' => $this->getOption('class'),
						'dataValue' => $this->value,
						'height' => $this->getOption('height'),
						'indirect' => $this->getOption('indirect', null, 'bool'),
						'actions' => explode(',',$this->getOption('actions', null)),
						'responsive' => $this->getOption('responsive'),
						'preview' => $this->getOption('preview'),
						'root' => $this->getOption('root'),
						'ruleInstance' => $this->getOption('ruleInstance'),
						'view' => $this->getOption('view'),
						'width' => $this->getOption('width')
					), $this->jdomOptions);

				$preview = false;
				if ($this->jdomOptions['preview'] == 'true'){
					$preview = true;
				}

				switch($tmplEngine){
					case 'doT':
					default:						
						if($preview){			
							$html .= JDom::_("html.fly.file.image", $this->jdomOptions);
						}
						
						$html .= $this->value;
						
					break;
				}
				break;
				
			default:
				if(count($values)){
					$fieldName = $this->getOption('name');
					switch($tmplEngine){
						case 'doT':
							$values = str_replace("'","\'",$this->escapeJsonString(json_encode($values)));
							$html .= 
								'{{ var value,tmp_val,vals = JSON.parse(\''. $values .'\');
										tmp_val = it.'. $fieldName .';
										}}
										{{ if(typeof it.'. $fieldName .' == "boolean"){ 
											tmp_val = 0;
											if(it.'. $fieldName .'){
												tmp_val = 1; }}
											{{ } }}
										{{ } 
										value = vals[tmp_val]; }}
									{{=value || ""}}';
							break;
							
						default:
							if(isset($values[$this->value])){
								$html .= $values[$this->value];
							} else {
								$html .= $this->value;
							}
							break;
					}				
				} else {			
					$html .= $this->value;
				}
		}

		return $html;
	}
	

	protected function getOptions(){
		if (isset($this->jdomOptions['list'])){
			$options = $this->jdomOptions['list'];
		}
		//Get the options from XML
		foreach ($this->element->children() as $option)
		{
			$opt = new stdClass();
			foreach($option->attributes() as $attr => $value)
				$opt->$attr = (string)$value;
	
			$opt->text = JText::_(trim((string) $option));
			$options[] = $opt;
		}
		
		return $options;
	}
	
	/**
	* Method to get the field label markup.
	*
	* @access	public
	*
	* @return	string	The field label markup.
	*
	* @since	11.1
	*/
	public function getLabel()
	{
		if (!$this->canView())
			return;
		
		if($this->markup == null){
			$this->markup == 'label';
		}
	
		$this->label = JDom::_('html.form.label', array_merge(array(
				'dataKey' => $this->getOption('name'),
				'required' => $this->getOption('required'),
				'formGroup' => $this->group,
				'formControl' => $this->formControl,
				'label' => $this->getOption('label'),
				'description' => $this->getOption('description'),
				'markup' => $this->markup
			), $this->jdomOptions));

		return $this->label;
	}	

	public function getXML(){				
		return $this->element;
	}
		
	function escapeJsonString($value) {
		# list from www.json.org: (\b backspace, \f formfeed)    
		$escapers =     array("\\",     "/",   "\"",  "\n",  "\r",  "\t", "\x08", "\x0c");
		$replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t",  "\\f",  "\\b");
		$result = str_replace($escapers, $replacements, $value);
		return $result;
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
	
}
