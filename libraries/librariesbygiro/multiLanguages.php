<?php
/*
* @version		0.1.0
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class multiLanguages {
    
    var $lang = "";
    var $db = "";
    var $extension = "";
    var $multilanguage_fields = array();
	var $installedLanguages = array();

	function __construct() {
		$this->db = JFactory::getDBO();
		
		$this->setLang();
	}
	
    function setLang($joomlaLangTag = 'en-GB'){
		$this->lang = strtolower(str_replace('-','', $joomlaLangTag));
    }
	
    function setExtension($extension){
		$this->extension = $extension;
    }
    
	function getInstalledLanguages(){
		$sql = 'SELECT lang_code FROM #__languages WHERE published = 1 ORDER BY ordering';
		$this->db->setQuery(  $sql );
		$this->installedLanguages = $this->db->loadColumn();	
	}	
	
	function buildLangFieldsQuery($alias = null){		
		$fields = $this->multilanguage_fields;
		
		$tableAlias = '';
		if($alias){
			$tableAlias = $alias.'.';			
		}
		
		$mFields = array();
		foreach($fields as $f){
			$field = $f[0] .'_'. $this->lang;
			$field = $tableAlias . $field .' AS '. $alias . $field;
			
			$mFields[] = $field;
		}
		
		return implode(', ',$mFields);
	}
        
	function checkTables($tables){
		if(count($this->installedLanguages) <= 0){
			$this->getInstalledLanguages();
		}

		$newFields = array();
		
		$db = $this->db;
		foreach($tables as $name => $tbl_fields){
			$tbl_name = '#__'. $this->extension .'_'. $name;
			
			$query = 'SHOW COLUMNS FROM `'. $tbl_name .'`';   
            $db->setQuery( $query );
            $fields = $db->loadObjectList('Field');			
			$fields_names = array_keys($fields);
			
			foreach($tbl_fields as $fi => $va){
					// let's check the field exist in the table
				if(!in_array($fi, $fields_names)){
					continue;
				}
				
				foreach($this->installedLanguages as $lang){
					$this->setLang($lang);
					
					$ml_field = $fi .'_'. $this->lang;
					$test[] = $ml_field;
					// now let's check the ml_field exist in the table
					if(!in_array($ml_field, $fields_names)){
						// let's clone this field BUT with another name
						$newFi = new stdClass;
						$newFi->Field = $ml_field;
						$newFi->Type = $fields[$fi]->Type;
						$newFi->Null = $fields[$fi]->Null;
						$newFi->Default = $fields[$fi]->Default;
						$newFields[$tbl_name][$fi][] = $newFi;
					}
				}
			}	
		}

		foreach($newFields as $tableName => $tableFields){

			$sql_array_update = array();
			$sql_array_add = array();
			foreach($tableFields as $fiName => $fields){
				
				foreach($fields as $field){
					$notNull = '';
					if($field->Null == 'NO'){
						$notNull = 'NOT NULL ';
					}

					$default = '';
					if($field->Default != ''){
						$default = "DEFAULT '". $field->Default ."'";
					}
					
					$sql_array_add[] = "ADD `". $field->Field ."` ". $field->Type . $notNull . $default;					
					$sql_array_update[] = "`". $field->Field ."` = `". $fiName ."`";
				}
			}
			
			if (count($sql_array_add) > 0){
				
				// add columns
				$query = "ALTER TABLE `". $tableName ."` ".implode(", ",$sql_array_add);
				$db->setQuery($query);
				if (!$db->query()){
					JError::raiseWarning(500, "Error install new language:<br>".$db->getErrorMsg());
				}
				
				// add default data
				$query = "UPDATE `". $tableName ."` SET ". implode(", ",$sql_array_update);
				$db->setQuery($query);
				if (!$db->query()){
					JError::raiseWarning(500, "Error copying default data for new language:<br>".$db->getErrorMsg());
				}				
			}			
		}		
	}
	
	function preprocessForm(&$form,$view_item,$data){		
		$fieldsets = $form->getFieldsets();
	
		// get multilanguage fieldsets
		$ml_fsets = array();
		foreach($fieldsets as $fieldset){			
			if(isset($fieldset->multilanguage) AND $fieldset->multilanguage == 'true'){
				$ml_fsets[] = $fieldset;
			}			
		}
	
		if(count($ml_fsets) <= 0){
			return;
		}
		
		// load installed languaged if not loaded yet
		if(count($this->installedLanguages) <= 0){
			$this->getInstalledLanguages();
		}

		// load original XML form
		$folderPath = JPATH_SITE . DS .'administrator'. DS .'components'. DS . 'com_'. $this->extension . DS .'models'. DS .'forms'. DS ;
		$formfile = $folderPath . $view_item . '.xml';
		
		if(!file_exists($formfile)){
			return;
		}
		
		$xmlFileContent = file_get_contents($formfile);
		$xml = new SimpleXmlElement($xmlFileContent);
		
		$fields_multilanguage = array();
		// duplicate multilanguage fieldsets
		$ml_fieldsets = $xml->xpath("//fieldset[@multilanguage='true']");
		$newForm = new SimpleXmlElement('<form></form>');
		// get fields
		foreach($ml_fieldsets as $fset){
			$xmlString = $fset->asXML();
			
			foreach($this->installedLanguages as $lang){
				$this->setLang($lang);
				
				$new = new SimpleXmlElement($xmlString);
				self::setMlAttrName($new);
				
				// find fields and fields group ONLY FIRST LEVEL
				$fields = $new->xpath("//field | //fields");		
				foreach($fields as $fi){				
					self::setMlAttrName($fi, 'name');
					self::setMlAttrName($fi, 'alias');
					

					if((string)$fi->attributes()->type == 'ckfile'){
					
						$attributes = array('root','rename');						
						foreach($attributes as $att){
							$att_val = (string)$fi->attributes()->$att;
							if(strpos($att_val,'{LANG}') !== false){
								$att_val = str_replace('{LANG}',$lang, $att_val);
								unset($fi->attributes()->$att);
								$fi->addAttribute($att,$att_val);
							}						
						}
					}
					
					if($fi->getName() == 'fields'){
						$fields_multilanguage[] = (string)$fi->attributes()->name;
						// set a different name on each sub fieldset
						$subfsets = $fi->xpath('child::fieldset');
						foreach($subfsets as $st){
							self::setMlAttrName($st);
						}
					}
					
					// remove the required attribute
					unset($fi->attributes()->required);
					$class = $fi->attributes()->class;
					if($class != ''){
						$class = str_replace('validate[required]','',$class);
						$class = str_replace(',required','',$class);
						$class = str_replace(', required','',$class);
						$class = str_replace('required,','',$class);
						$class = str_replace('required ,','',$class);
						unset($fi->attributes()->class);
						$fi->addAttribute('class',$class);
					}					
				}			

				self::addsubtree($newForm,$new);
				unset($new);				
			}
		}
		$form->load($newForm);

		// set fields value in groups
		foreach($fields_multilanguage as $group){				
			$fields = $form->getGroup($group);

			foreach($fields as $fi){
				$fieldname = $fi->fieldname;
				// get data value considering the groups tree
				if($fi->group != ''){
					$value = self::array_path_value($data, $fi->group .'.'. $fieldname);						
				} else if(isset($data->$fieldname)){
					$value = $data->$fieldname;
				}
				
				if(isset($value)){
					$form->setValue($fieldname, null, $value);
				}
			}
		}
		
		
		// set fields values in fieldsets
		foreach($ml_fsets as $fset){
			foreach($this->installedLanguages as $lang){
				$this->setLang($lang);
				
				$fields = $form->getFieldset($fset->name .'_'. $this->lang);
				
				foreach($fields as $fi){
					$fieldname = $fi->fieldname;
					// get data value considering the groups tree
					if($fi->group != ''){
						$value = self::array_path_value($data, $fi->group .'.'. $fieldname);						
					} else if(isset($data->$fieldname)){
						$value = $data->$fieldname;
					}
					
					if(isset($value)){
						$form->setValue($fieldname, null, $value);
					}
				}
			}
		}	
	}
	
	
	/* UTILITIES */
	function addsubtree(&$xml1,&$xml2)
    {// Create new DOMElements from the two SimpleXMLElements
        $dom1 = dom_import_simplexml($xml1);
        $dom2 = dom_import_simplexml($xml2);
        // Import the  into the  document
        $dom2 = $dom1->ownerDocument->importNode($dom2, TRUE);
        // Append the  to 
        $dom1->appendChild($dom2);
    }

	function setMlAttrName(&$xml, $fieldname = 'name'){
		$name = $xml->attributes()->$fieldname;
		if((string)$name == ''){
			return;
		}
		$name = array_diff(explode('_',$name),$this->installedLanguages);
		array_push($name,$this->lang);
		$name = implode('_',$name);
		unset($xml->attributes()->$fieldname);
		$xml->addAttribute($fieldname,$name);
	}
	
	/**
	 * Set value of an array by using "root/branch/leaf" notation
	 *
	 * @param array $array Array to affect
	 * @param string $path Path to set
	 * @param mixed $value Value to set the target cell to
	 * @return void
	 */
	public static function set_array_path_value(&$array, $path, $value)
	{
		// fail if the path is empty
		if (empty($path)) {
			throw new Exception('Path cannot be empty');
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
			throw new Exception('Path cannot be empty');
		}
	 
		// remove all leading and trailing slashes
		$path = trim($path, $delimiter);
	 
		// use current array as the initial value
		$value = $array;
	 
		// extract parts of the path
		$parts = explode($delimiter, $path);
	 
		// loop through each part and extract its value
		foreach ($parts as $part) {
			if(is_array($part) AND isset($value[$part])){
				// replace current value with the child
				$value = $value[$part];
			} else if(is_object($part) AND isset($value->$part)){
				// replace current value with the child
				$value = $value->$part;
			} else {
				// key doesn't exist, fail
				return $default;
			}
		}
	 
		return $value;
	}	
}
?>
