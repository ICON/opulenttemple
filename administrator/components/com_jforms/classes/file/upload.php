<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <    Generated with Cook Self Service  V2.6.3   |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		0.2.9
* @package		jForms
* @subpackage	
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

defined('JFORMS_UPLOAD_RANDOM_CHARS') or define("JFORMS_UPLOAD_RANDOM_CHARS", 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');
defined('JFORMS_UPLOAD_CHMOD_FOLDER') or define("JFORMS_UPLOAD_CHMOD_FOLDER", 0744);
defined('JFORMS_UPLOAD_CHMOD_FILE') or define("JFORMS_UPLOAD_CHMOD_FILE", 0644);


/**
* Uploader Class for Jforms.
*
* @package	Jforms
* @subpackage	Class
*/
class JformsCkClassFileUpload extends JformsClassFile
{
	/**
	* Allowed Files types
	*
	* @var array
	*/
	protected $allowedTypes;

	/**
	* File informations
	*
	* @var stdClass
	*/
	protected $file;

	/**
	* Max uploadable file size
	*
	* @var integer
	*/
	protected $maxSize;

	/**
	* Upload Options
	*
	* @var array
	*/
	protected $options;

	/**
	* Upload Folder
	*
	* @var string
	*/
	public $uploadFolder;

	/**
	* Constructor
	*
	* @access	public
	* @param	string	$uploadFolder	Upload folder.
	* @return	void
	*/
	public function __construct($uploadFolder)
	{
		$this->setUploadFolder($uploadFolder);
		$this->maxSize = $this->getMaxSize();

		$this->mime_types = $this->getMimeTypes();
	}

	/**
	* Return a safe file name
	*
	* @access	protected
	* @param	string	$str	file name to alias.
	* @param	boolean	$toCase	Change case.
	*
	* @return	string	Aliased string.
	*/
	protected function alias($str, $toCase = 'lower')
	{
		//ACCENTS
		$accents = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
		$replacements = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
		$str = str_replace($accents, $replacements, $str);

		//SPACES
		$str = preg_replace("/\s+/", "-", $str);

		//OTHER CHARACTERS
		$strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
					   "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
					   "—", "–", ",", "<", ".", ">", "/", "?");
		$str = trim(str_replace($strip, "", strip_tags($str)));
		
		switch($toCase)
		{
			case 'lower':
				$str = strtolower($str);
				break;

			case 'upper':
				$str = strtoupper($str);
				break;

			case 'ucfirst':
				$str = ucfirst($str);
				break;

			case 'ucwords':
				$str = ucwords($str);
				break;

			default:
				break;

		}


		$str = JFile::makeSafe($str);

		return $str;
	}

	/**
	* Check a file extension
	*
	* @access	protected
	* @param	string	$fileExt	File extension.
	*
	* @return	boolean	True if allowed, False otherwise.
	*/
	protected function checkExtension($fileExt)
	{
		$valid = false;

		foreach($this->allowedTypes as $mime => $ext)
			if (in_array($fileExt, explode(",", $ext)))
				$valid = true;

		return $valid;
	}

	/**
	* Check if the file is already present.
	*
	* @access	protected
	*
	* @return	boolean	True is already present, False otherwise.
	*/
	protected function checkFilePresence()
	{
		if ($this->fileExists())
		{
			switch($this->options["overwrite"])
			{
				case 'no':		// Error file already present
					return false;
					break;

				case 'yes':
					return true; //Override
					break;

				default:
				case 'suffix':
								// Add a file suffix
					$this->renameIfExists();
					break;
			}
		}

		return true;
	}

	/**
	* Check a mime type
	*
	* @access	protected
	* @param	string	$fileMime	Mime type.
	*
	* @return	boolean	True if allowed, false otherwise.
	*/
	protected function checkMime($fileMime)
	{
		$valid = false;
		if (isset($this->allowedTypes) && count($this->allowedTypes))
		foreach($this->allowedTypes as $mime => $ext)
		{
			$mime = preg_replace("#\/#", "\\\/", $mime);
			if (preg_match("/" . $mime . "/", $fileMime))
				$valid = true;
		}
		return $valid;
	}

	/**
	* Get the extension from the mime type
	*
	* @access	protected
	*
	* @return	string	file extension, null if not found.
	*/
	protected function extensionFromMime()
	{
		foreach($this->allowedTypes as $mime => $ext)
			if ($mime == $this->file->mime)
			{
				$exts = explode(",", $ext);
				return $exts[0];
			}
	}

	/**
	* Check presence of a file
	*
	* @access	public
	* @param	string	$suffix	File suffix.
	*
	* @return	boolean	True if exists, False otherwise.
	*/
	public function fileExists($suffix = null)
	{
		$s = (isset($suffix)?"-" . $suffix:"");

		return file_exists($this->uploadFolder .DS. $this->file->base . $s . '.' . $this->file->extension);
	}

	/**
	* Get allowed files extensions
	*
	* @access	public
	*
	* @return	string	List of allowed extensions.
	*/
	public function getAllowedExtensions()
	{
		return implode(",", $this->allowedTypes);
	}

	/**
	* Get allowed files mimes types
	*
	* @access	public
	*
	* @return	string	List of allowed mimes.
	*/
	public function getAllowedMimes()
	{
		return implode(" - ", array_keys($this->allowedTypes));
	}

	/**
	* Return the authorized max upload size.
	*
	* @access	public static
	* @param	boolean	$string	Add the final unit.
	* @param	integer	$maxSizeCustom	Restrict the max file size upload.
	*
	* @return	mixed	max file size.
	*/
	public static function getMaxSize($string = false, $maxSizeCustom = null)
	{
		$maxSize = intval(ini_get('upload_max_filesize')) * 1024 * 1024;
		$config	= JComponentHelper::getParams( 'com_jforms' );
		$maxSizeConfig = (int)$config->get('upload_maxsize') * 1024 * 1024;

		if ($maxSizeConfig)
			$maxSize = min($maxSize, $maxSizeConfig);

		if ($maxSizeCustom)
			$maxSize = min($maxSize, $maxSizeCustom);

		
		if ($string)
			$maxSize = JText::sprintf("JFORMS_UPLOAD_MAX_B", self::bytesToString($maxSize));

		return $maxSize;
	}

	/**
	* Get the maximum upload size
	*
	* @access	protected
	*
	* @return	integer	Max file upload size in bytes.
	*/
	protected function getMaxUpload()
	{
		$max = $this->maxSize;


		//PHP.INI (upload_max_filesize)
		$iniMaxUpload = self::bytes(ini_get('upload_max_filesize'));
		if ((int)$iniMaxUpload && ($iniMaxUpload < $max))
			$max = $iniMaxUpload;



		//PHP.INI (post_max_size)
		$iniMaxPost = self::bytes(ini_get('post_max_size'));
		if ((int)$iniMaxPost && ($iniMaxPost < $max))
			$max = $iniMaxPost;

		return $max;
	}

	/**
	* Parse the renaming patterns
	*
	* @access	protected
	* @param	string	&$pattern	File name pattern to override.
	* @param	string	$name	Name of the pattern tag.
	* @param	string	$value	Value.
	* @return	void
	*
	* @since	Cook 1.1
	*/
	protected function parsePattern(&$pattern, $name, $value)
	{
		$name = strtoupper($name);

		if (preg_match("/{" . $name . "(\(.+\))?(\#?[0-9]+)?}/", $pattern))
		{
			//Trim to length
			if (preg_match("/{" . $name . "(\(.+\))?\#?[0-9]+}/", $pattern))
			{
				$length = $this->patternLength($name, $pattern);

				$value = substr($value, 0, $length);
			}

			$pattern = preg_replace("/{" . $name . "(\(.+\))?(\#?[0-9]+)?}/", $value, $pattern);

		}
	}

	/**
	* Limit the length if the length modifier is defined
	*
	* @access	protected
	* @param	string	$name	Tag name.
	* @param	string	$pattern	Pattern.
	*
	* @return	integer	Length.
	*
	* @since	Cook 1.1
	*/
	protected function patternLength($name, $pattern)
	{
		$name = strtoupper($name);

		if (!preg_match("/{" . $name . "\#[0-9]+}/", $pattern))
			return;

		$length = preg_replace("/^(.+)?{" . $name . "(\(.+\))?\#?([0-9]+)(}(.+)?)$/", '$'.'3', $pattern);

		return $length;
	}

	/**
	* Get the params of a tag pattern
	*
	* @access	protected
	* @param	string	$name	Name of the tag.
	* @param	string	$pattern	Pattern.
	*
	* @return	string	Tag params.
	*/
	protected function patternParam($name, $pattern)
	{
		$name = strtoupper($name);

		if (!preg_match("/{" . $name . "\(.+\)(\#[0-9]+)?}/", $pattern))
			return null;

		$param = preg_replace("/^(.+)?{" . $name . "\((.+)?\)\#?([0-9]+)?(}(.+)?)$/", '$'.'2', $pattern);

		return $param;
	}

	/**
	* Process the upload
	*
	* @access	public
	*
	* @return	boolean	True on success, False otherwise.
	*/
	public function process()
	{
		//Clean the (eventually renamed) path
		$this->file->filename = JPath::clean($this->file->filename);


		//Check if upload autocreate directory exists + Create index.html
		$dir = dirname($this->file->filename);
		$blankContent = '<html><body bgcolor="#FFFFFF"></body></html>';

		//Create the directories and protect with index.html empty file
		self::blankFiles($this->uploadFolder, $dir);
	
		//Upload file
		$fileDest = $this->uploadFolder . $this->file->filename;
		if (!move_uploaded_file($this->file->tmp, $fileDest))
			if(!JFile::upload($this->file->tmp, $fileDest))
				return false;

		//Protect file against execution
		@chmod($fileDest, JFORMS_UPLOAD_CHMOD_FILE);

		return true;
	}

	/**
	* Return a random alias from composed from a list of chars.
	*
	* @access	protected
	* @param	integer	$length	Length of the random.
	*
	* @return	string	Random string.
	*/
	protected function randomAlias($length)
	{
		$lenChars = strlen(JFORMS_UPLOAD_RANDOM_CHARS);
		$random = "";

		if ((int)$length == 0)
			$length = 8;

		for($i = 0 ; $i < $length ; $i++)
		{
			$pos = rand(0, $lenChars);
			$random .= substr(JFORMS_UPLOAD_RANDOM_CHARS, $pos, 1);
		}

		return $random;
	}

	/**
	* Rewrite the file name before upload
	* PATTERNS :
	* 	{EXT}				: Original extension
	* 	{MIMEXT} 			: Corrected extension from Mime-header
	* 	{BASE}				: Original file name without extension
	* 	{ALIAS}				: Safe aliased original file name
	* 	{RAND}				: Randomized value
	* 	{DATE(Y-m-d)} 		: formated date
	* 	{ID}				: Current item id
	* 
	* MODIFIERS :
	* 	{[PATTERN]#6} 		: Limit to 6 chars
	*
	* @access	protected
	* @return	void
	*
	* @since	Cook 1.1
	*/
	protected function renameFile()
	{
		$file = $this->file;
		if ($this->options["rename"])
			$pattern = $this->options["rename"];
		else
			$pattern = "{ALIAS}.{MIMEXT}";

		if (isset($this->options['id']))
		{
			//Original extension
			$this->parsePattern($pattern, "ID", $this->options['id']);
		}

		//Original extension
		$this->parsePattern($pattern, "EXT", $file->extension);

		//Corrected extension from Mime-header
		$this->parsePattern($pattern, "MIMEXT", $this->extensionFromMime());

		//Original file name without extension
		$this->parsePattern($pattern, "BASE", $file->base);


		//Safe aliased original file name
		$this->parsePattern($pattern, "ALIAS", $this->alias($file->base, 'lower'));


		//Randomized value
		$length = $this->patternLength("RAND", $pattern);
		$this->parsePattern($pattern, "RAND", $this->randomAlias($length));

		//formated date
		$format = $this->patternParam("DATE", $pattern);
		if (!$format)
			$format = "Y-m-d";
		$this->parsePattern($pattern, "DATE", JFactory::getDate()->format($format));


		//remove spaces
		$pattern = preg_replace("/\s+/", "", $pattern);

		//remove backdir
		$pattern = preg_replace("/\.\./", "", $pattern);

		//Non empty string
		if (trim($pattern) == "")
			$pattern = $this->randomAlias(8);

		$file->filename = $pattern;
		$file->base = $this->fileBase($file->filename);
		$file->extension = $this->fileExtension($file->filename);


		$this->file = $file;
	}

	/**
	* Rename the file if it already exists
	*
	* @access	protected
	* @return	void
	*
	* @since	Cook 1.1
	*/
	protected function renameIfExists()
	{
		$file = $this->file;

		if ($this->fileExists())
		{
			$suffix = 1;
			while($this->fileExists($suffix))
				$suffix++;

			$file->base = $file->base . "-" . $suffix;
			$file->filename = $file->base . "." . $file->extension;

		}
	}

	/**
	* Set the allowed files types
	*
	* @access	public
	* @param	array	$allowedTypes	Allowed types.
	* @return	void
	*/
	public function setAllowed($allowedTypes)
	{
		$this->allowedTypes = $allowedTypes;
	}

	/**
	* Set the upload folder
	*
	* @access	public
	* @param	string	$uploadFolder	Upload folder.
	* @return	void
	*/
	public function setUploadFolder($uploadFolder)
	{
		$uploadFolder = $this->getPhysical($uploadFolder);
		$app = JFactory::getApplication();

		jimport('joomla.filesystem.folder');

		//Clean upload path
		$uploadFolder = JPath::clean(html_entity_decode($uploadFolder . DS));
		$uploadPath = JPath::clean($uploadFolder);




		//Check if upload directory exists
		if(!is_dir($uploadPath))
			JFolder::create($uploadPath);

		if (!is_dir($uploadPath))
			return false;

		$blankContent = '<html><body bgcolor="#FFFFFF"></body></html>';
		if (!self::exists($uploadPath.'index.html'))
			self::write($uploadPath.'index.html', $blankContent);


		//Protect against execution and set writable
		@chmod($uploadPath, JFORMS_UPLOAD_CHMOD_FOLDER);
		if(!is_writable($uploadPath))
		{
			$app->enqueueMessage(JText::sprintf( "JFORMS_UPLOAD_PLEASE_MAKE_SURE_THE_FOLDER_IS_WRITABLE",$uploadPath), 'notice');
			return false;
		}

		$this->uploadFolder = $uploadFolder;
	}

	/**
	* Upload a file. Main process.
	*
	* @access	public
	* @param	array	$uploadFile	Array of informations of the file (From $_FILES).
	* @param	array	$options	Upload options.
	*
	* @return	mixed	file informations on success, False otherwise.
	*
	* @since	Cook 1.1
	*/
	public function uploadFile($uploadFile, $options = array())
	{
		$this->options = $options;

		if (isset($this->options["maxSize"]))  //Overwrite maxSize
			$this->maxSize = $this->options["maxSize"];

		$uploadFolder = $this->uploadFolder;
		$app = JFactory::getApplication();

		//Check file name
		if(empty($uploadFile['name'])){
			$app->enqueueMessage(JText::_("JFORMS_UPLOAD_PLEASE_BROWSE_A_FILE"),'notice');
			return false;
		}

		$file = null;
		$file->filename = $uploadFile['name'];
		$file->tmp = $uploadFile['tmp_name'];
		$file->size = $uploadFile['size'];

		$file->extension = $this->fileExtension($file->filename);
		$file->base = $this->fileBase($file->filename);

		$this->file = $file;

		//CHECK EXTENSION
		if (!$this->checkExtension($file->extension))
		{
			$app->enqueueMessage(JText::sprintf( "JFORMS_UPLOAD_THIS_FILE_EXTENSION_IS_NOT_ACCEPTED_THE_ACCEPTED_FILES_ARE",
												$file->extension,
												$this->getAllowedExtensions()
												), 'notice');
			return false;
		}


		//CHECK MIME HEADER
		$this->file->mime = $this->getMime($this->file->tmp);
		if (!$this->checkMime($this->file->mime))
		{
			$app->enqueueMessage(JText::sprintf( "JFORMS_UPLOAD_MIME_TYPE_NOT_VALID_ALLOWED_MIMES_ARE",
												$this->file->mime,
												$this->getAllowedMimes()), 'error');
			return false;
		}

		//CHECK SIZE
		$maxSize = self::getMaxUpload();
		if ($this->file->size > $maxSize)
		{
			$app->enqueueMessage(JText::sprintf( "JFORMS_UPLOAD_TOO_BIG_FILE_BYTES_MAX_ALLOWED_SIZE_BYTES",
											self::bytesToString($this->file->size),
											self::bytesToString($maxSize)), 'error');
			return false;
		}



		//CHECK PHP INJECTION
		$contents = JFile::read($file->tmp);
		if (preg_match("/\<\?php\s/", $contents) AND !$app->isAdmin()) 
		{
			$app->enqueueMessage(JText::_( "JFORMS_UPLOAD_THE_FILE_CONTAINS_ERRORS"), 'error');
			return false;
		}

		//CORRECT FILENAME
		$this->renameFile();

		//CHECK FILE PRESENCE
		if (!$this->checkFilePresence())  //And rename if allowed
		{
			$app->enqueueMessage(JText::sprintf( "JFORMS_UPLOAD_THIS_FILE_ALREADY_EXIST",$file->filename), 'notice');
			return false;
		}

		//PROCESS UPLOAD
		if (!$this->process())
		{
			if ($app->isSite())
				$msg = JText::sprintf( "JFORMS_UPLOAD_COULD_NOT_UPLOAD_THE_FILE", $file->filename);	// Don't show the complete directory in front-end
			else if ($app->isAdmin())
				$msg = JText::sprintf( "JFORMS_UPLOAD_COULD_NOT_UPLOAD_THE_FILE_TO",$file->tmp,$this->uploadFolder . $file->filename);
	
			$app->enqueueMessage($msg, 'error');
			return false;
		}

		return $file;
	}

	public function getMimeTypes(){
		$mime_types = array(
			"323" => "text/h323",
			"7z" => "application/x-7z-compressed",
			"acx" => "application/internet-property-stream",
			"ai" => "application/postscript",
			"aif" => "audio/x-aiff",
			"aifc" => "audio/x-aiff",
			"aiff" => "audio/x-aiff",
			"asf" => "video/x-ms-asf",
			"asr" => "video/x-ms-asf",
			"asx" => "video/x-ms-asf",
			"au" => "audio/basic",
			"avi" => "video/x-msvideo",
			"axs" => "application/olescript",
			"bas" => "text/plain",
			"bcpio" => "application/x-bcpio",
			"bin" => "application/octet-stream",
			"blend" => "application/x-blender",
			"bmp" => "image/bmp",
			"c" => "text/plain",
			"cat" => "application/vnd.ms-pkiseccat",
			"cdf" => "application/x-cdf",
			"cdr" => "application/coreldraw",
			"cer" => "application/x-x509-ca-cert",
			"class" => "application/octet-stream",
			"clp" => "application/x-msclip",
			"cmx" => "image/x-cmx",
			"cod" => "image/cis-cod",
			"cpio" => "application/x-cpio",
			"crd" => "application/x-mscardfile",
			"crl" => "application/pkix-crl",
			"crt" => "application/x-x509-ca-cert",
			"csh" => "application/x-csh",
			"css" => "text/css",
			"dcr" => "application/x-director",
			"der" => "application/x-x509-ca-cert",
			"dir" => "application/x-director",
			"dll" => "application/x-msdownload",
			"dms" => "application/octet-stream",
			"doc" => "application/msword",
			"docx" => "application/msword",
			"dot" => "application/msword",
			"dv" => "video/dv",
			"dvi" => "application/x-dvi",
			"dxr" => "application/x-director",
			"eps" => "application/postscript",
			"etx" => "text/x-setext",
			"evy" => "application/envoy",
			"exe" => "application/octet-stream",
			"exe" => "application/x-ms-dos-executable",
			"fif" => "application/fractals",
			"flac" => "audio/flac",
			"flr" => "x-world/x-vrml",
			"gif" => "image/gif",
			"gtar" => "application/x-gtar",
			"gz" => "application/x-gzip",
			"gzip" => "application/x-gzip",
			"h" => "text/plain",
			"hdf" => "application/x-hdf",
			"hlp" => "application/winhlp",
			"hqx" => "application/mac-binhex40",
			"hta" => "application/hta",
			"htc" => "text/x-component",
			"htm" => "text/html",
			"html" => "text/html",
			"htt" => "text/webviewhtml",
			"ical" => "text/calendar",
			"ico" => "image/x-icon",
			"ics" => "text/calendar",
			"ief" => "image/ief",
			"iii" => "application/x-iphone",
			"impress" => "text/impress",
			"ini" => "text/plain",
			"ins" => "application/x-internet-signup",
			"isp" => "application/x-internet-signup",
			"jfif" => "image/pipeg",
			"jpe" => "image/jpeg",
			"jpeg" => "image/jpeg",
			"jpg" => "image/jpeg",
			"js" => "application/javascript",
			"js" => "application/x-javascript",
			"keynote" => "application/x-iwork-keynote-sffkey",
			"kra" => "application/x-krita",
			"latex" => "application/x-latex",
			"lha" => "application/octet-stream",
			"lsf" => "video/x-la-asf",
			"lsx" => "video/x-la-asf",
			"lzh" => "application/octet-stream",
			"m13" => "application/x-msmediaview",
			"m14" => "application/x-msmediaview",
			"m2t" => "video/mp2t",
			"m3u" => "audio/x-mpegurl",
			"m4v" => "video/mp4",
			"man" => "application/x-troff-man",
			"mdb" => "application/x-msaccess",
			"me" => "application/x-troff-me",
			"mht" => "message/rfc822",
			"mhtml" => "message/rfc822",
			"mid" => "audio/mid",
			"mny" => "application/x-msmoney",
			"mov" => "video/quicktime",
			"movie" => "video/x-sgi-movie",
			"mp2" => "video/mpeg",
			"mp3" => "audio/mpeg",
			"mp4" => "video/mp4",
			"mpa" => "video/mpeg",
			"mpe" => "video/mpeg",
			"mpeg" => "video/mpeg",
			"mpg" => "video/mpeg",
			"mpp" => "application/vnd.ms-project",
			"mpv2" => "video/mpeg",
			"ms" => "application/x-troff-ms",
			"mvb" => "application/x-msmediaview",
			"numbers" => "application/x-iwork-numbers-sffnumbers",
			"nws" => "message/rfc822",
			"oda" => "application/oda",
			"odg" => "application/vnd.oasis.opendocument.graphics",
			"odp" => "application/vnd.oasis.opendocument.presentation",
			"ods" => "application/vnd.oasis.opendocument.spreadsheet",
			"odt" => "application/vnd.oasis.opendocument.text",
			"oga" => "audio/ogg",
			"ogg" => "audio/ogg",
			"ogv" => "video/ogg",
			"p10" => "application/pkcs10",
			"p12" => "application/x-pkcs12",
			"p7b" => "application/x-pkcs7-certificates",
			"p7c" => "application/x-pkcs7-mime",
			"p7m" => "application/x-pkcs7-mime",
			"p7r" => "application/x-pkcs7-certreqresp",
			"p7s" => "application/x-pkcs7-signature",
			"pages" => "application/x-iwork-pages-sffpages",
			"pbm" => "image/x-portable-bitmap",
			"pdf" => "application/pdf",
			"pfx" => "application/x-pkcs12",
			"pgm" => "image/x-portable-graymap",
			"php" => "application/x-php",
			"php" => "text/x-php",
			"pko" => "application/ynd.ms-pkipko",
			"pl" => "application/x-pearl",
			"pma" => "application/x-perfmon",
			"pmc" => "application/x-perfmon",
			"pml" => "application/x-perfmon",
			"pmr" => "application/x-perfmon",
			"pmw" => "application/x-perfmon",
			"png" => "image/png",
			"pnm" => "image/x-portable-anymap",
			"pot" => "application/vnd.ms-powerpoint",
			"ppm" => "image/x-portable-pixmap",
			"pps" => "application/vnd.ms-powerpoint",
			"ppt" => "application/mspowerpoint",
			"ppt" => "application/vnd.ms-powerpoint",
			"pptx" => "application/mspowerpoint",
			"prf" => "application/pics-rules",
			"ps" => "application/postscript",
			"psd" => "application/x-photoshop",
			"pub" => "application/x-mspublisher",
			"py" => "application/x-python",
			"py" => "text/x-script.phyton",
			"qt" => "video/quicktime",
			"ra" => "audio/x-pn-realaudio",
			"ram" => "audio/x-pn-realaudio",
			"rar" => "application/x-rar-compressed",
			"ras" => "image/x-cmu-raster",
			"rgb" => "image/x-rgb",
			"rmi" => "audio/mid",
			"roff" => "application/x-troff",
			"rtf" => "application/rtf",
			"rtx" => "text/richtext",
			"scd" => "application/x-msschedule",
			"sct" => "text/scriptlet",
			"setpay" => "application/set-payment-initiation",
			"setreg" => "application/set-registration-initiation",
			"sgf" => "application/sgf",
			"sh" => "application/x-sh",
			"shar" => "application/x-shar",
			"sit" => "application/x-stuffit",
			"snd" => "audio/basic",
			"spc" => "application/x-pkcs7-certificates",
			"spl" => "application/futuresplash",
			"src" => "application/x-wais-source",
			"sst" => "application/vnd.ms-pkicertstore",
			"stl" => "application/vnd.ms-pkistl",
			"stm" => "text/html",
			"sv4cpio" => "application/x-sv4cpio",
			"sv4crc" => "application/x-sv4crc",
			"svg" => "image/svg+xml",
			"t" => "application/x-troff",
			"tar" => "application/x-tar",
			"tar.gz" => "application/x-compressed",
			"tcl" => "application/x-tcl",
			"tex" => "application/x-tex",
			"texi" => "application/x-texinfo",
			"texinfo" => "application/x-texinfo",
			"tgz" => "application/x-compressed",
			"tif" => "image/tiff",
			"tiff" => "image/tiff",
			"tr" => "application/x-troff",
			"trm" => "application/x-msterminal",
			"tsv" => "text/tab-separated-values",
			"txt" => "text/plain",
			"uls" => "text/iuls",
			"ustar" => "application/x-ustar",
			"vcard" => "text/vcard",
			"vcf" => "text/vcard",
			"vcf" => "text/x-vcard",
			"vrml" => "x-world/x-vrml",
			"wav" => "audio/wav",
			"wav" => "audio/x-wav",
			"wcm" => "application/vnd.ms-works",
			"wdb" => "application/vnd.ms-works",
			"webm" => "video/webm",
			"wks" => "application/vnd.ms-works",
			"wmf" => "application/x-msmetafile",
			"wmv" => "video/x-ms-asf",
			"wps" => "application/vnd.ms-works",
			"wri" => "application/x-mswrite",
			"wrl" => "x-world/x-vrml",
			"wrz" => "x-world/x-vrml",
			"xaf" => "x-world/x-vrml",
			"xbm" => "image/x-xbitmap",
			"xcf" => "application/x-gimp",
			"xla" => "application/vnd.ms-excel",
			"xlc" => "application/vnd.ms-excel",
			"xlm" => "application/vnd.ms-excel",
			"xls" => "application/msexcel",
			"xls" => "application/vnd.ms-excel",
			"xlsx" => "application/msexcel",
			"xlt" => "application/vnd.ms-excel",
			"xlw" => "application/vnd.ms-excel",
			"xml" => "application/xml",
			"xof" => "x-world/x-vrml",
			"xpm" => "image/x-xpixmap",
			"xwd" => "image/x-xwindowdump",
			"z" => "application/x-compress",
			"zip" => "application/zip"
		);
		
		return $mime_types;
	}
}

// Load the fork
JformsHelper::loadFork(__FILE__);

// Fallback if no fork has been found
if (!class_exists('JformsClassFileUpload')){ class JformsClassFileUpload extends JformsCkClassFileUpload{} }

