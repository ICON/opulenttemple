<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class JDomHtmlFormInputDatetimepicker extends JDomHtmlFormInput
{
	protected $dateFormat;
	protected $uiFormat;
	protected $filter;
	protected $startDate;
	protected $endDate;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 *
	 *	@dateFormat	: Date Format
	 */
	function __construct($args)
	{

		parent::__construct($args);

		$this->arg('dateFormat'	, null, $args, "Y-m-d");
		$this->arg('required'	, null, $args, false);
		$this->arg('uiFormat'	, null, $args, "dd-mm-yyyy");
		$this->arg('filter'		, null, $args);
		$this->arg('todayBtn'	, null, $args, 'true');
		$this->arg('autoclose'	, null, $args, 'true');
		$this->arg('startView'	, null, $args, 2);
		$this->arg('minView'	, null, $args, 0);
		$this->arg('maxView'	, null, $args, 4);
		$this->arg('startDate'	, null, $args);
		$this->arg('endDate'	, null, $args);

		
		$numeric = array(0,1,2,3,4);
		$textual = array('hour','day','month','year','decade');

		if(in_array($this->startView,$textual)){
			$this->startView = '"'. $this->startView .'"';
		} else if(!in_array($this->startView,$numeric)){
			$this->startView = 2;
		}

		if(in_array($this->minView,$textual)){
			$this->minView = '"'. $this->minView .'"';
		} else if(!in_array($this->minView,$numeric)){
			$this->minView = 0;
		}

		if(in_array($this->maxView,$textual)){
			$this->maxView = '"'. $this->maxView .'"';
		} else if(!in_array($this->maxView,$numeric)){
			$this->maxView = 4;
		}		
	}

	function build()
	{
		$dateFormat = $this->dateFormat;

		//JDate::toFormat() is deprecated. CONVERT Legacy Joomla Format
		//Minutes : ‰M > i
		$dateFormat = str_replace("%M", "i", $dateFormat);
		//remove the %
		$dateFormat = str_replace("%", "", $dateFormat);
	
	
		$formatedDate = $this->dataValue;

		if ($this->dataValue
		&& ($this->dataValue != "0000-00-00")
		&& ($this->dataValue != "00:00:00")
		&& ($this->dataValue != "0000-00-00 00:00:00"))
		{
			jimport("joomla.utilities.date");
			$date = JFactory::getDate($this->dataValue);
			$formatedDate = $date->format($dateFormat);

			$config = JFactory::getConfig();
			// If a known filter is given use it.
			switch (strtoupper(($this->filter)))
			{
				case 'SERVER_UTC':
					// Convert a date to UTC based on the server timezone.
					if (intval($this->dataValue))
					{
						// Get a date object based on the correct timezone.
						$date = JFactory::getDate($this->dataValue, 'UTC');
						$date->setTimezone(new DateTimeZone($config->get('offset')));

						// Format the date string.
						$formatedDate = $date->format($dateFormat, true);
					}
					break;

				case 'USER_UTC':
					// Convert a date to UTC based on the user timezone.
					if (intval($this->dataValue))
					{
						// Get a date object based on the correct timezone.
						$date = JFactory::getDate($this->dataValue, 'UTC');
						$user = JFactory::getUser();
						$date->setTimezone(new DateTimeZone($user->getParam('timezone', $config->get('offset'))));

						// Format the date string.
						$formatedDate = $date->format($dateFormat, true);
					}
					break;
			}
		}
		else {
			$formatedDate = "";
		}

		static $jsLoaded;
		$doc = JFactory::getDocument();
		
		if(!$jsLoaded){
			$this->addScript('assets/datetimepicker/js/datetimepicker.js');
			$this->addStyleSheet('assets/datetimepicker/css/datetimepicker.css');
			
			$lang = JFactory::getLanguage();
			$currentLang = $lang->getName();
			
			$currentLang = explode('-', $currentLang);
			if($currentLang[0] != 'en'){
				$this->addScript('assets/datetimepicker/js/locale/datetimepicker.'. $currentLang[0] .'.js');
			}
		
			$jsLoaded++;
		}
		
		$inputId = $this->getInputId();
		
		$startDate = $endDate = '';
		if($this->startDate != ''){
			if(strtolower($this->startDate) == 'now'){
				$this->startDate = date ("d-m-Y", time());
			}
			$startDate = 'startDate: "'. $this->startDate .'",';
		}
		
		if($this->endDate != ''){
			if(strtolower($this->endDate) == 'now'){
				$this->endDate = date ("d-m-Y", time());
			}
			$endDate = 'endDate: "'. $this->endDate .'",';
		}
		
		$rand = $this->generateRandomString(15);
		
		$script = '
		jQuery(document).ready(function(){			
			jQuery("#'. $inputId .'_picker'. $rand .'").datetimepicker({
				autoclose: '. $this->autoclose .',
				todayBtn: '. $this->todayBtn .',
				startView: '. $this->startView .',
				minView: '. $this->minView .',
				maxView: '. $this->maxView .',
				'. $startDate .'
				'. $endDate .'
				minuteStep: 10
			});
			
		});';
		
		$doc->addScriptDeclaration($script);
		$required = '';
		if($this->required){
			$required = 'validate[required]';
		}
				
		$html =	'<div class="input-append date" id="<%DOM_ID%>_picker'. $rand .'" data-date-format="'. $this->uiFormat .'">'
			.	'<input id="<%DOM_ID%>_val'. $rand .'" size="16" type="text" value="'. $formatedDate .'" class="'. $required .'" readonly="readonly" <%STYLE%><%SELECTORS%>>'
			.	'<input id="<%DOM_ID%>" type="hidden" value="'. $formatedDate .'" name="<%INPUT_NAME%>"<%STYLE%><%CLASS%><%SELECTORS%>>'
			.	'<span class="add-on"><i class="icon-calendar"></i></span>'
			.	'<%VALIDOR_ICON%>'.LN
			.	'<%MESSAGE%>'
			.	'</div>';
		return $html;		
	}

	
	// fix for BAD HTML with duplicated IDs
	public static function generateRandomString($length = 5) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
}