<?php
/**
 * Simple template engine class (use [@tag] tags in your templates).
 * 
 * @link http://www.broculos.net/ Broculos.net Programming Tutorials
 * @author Nuno Freitas <nunofreitas@gmail.com>
 * @version 1.0
 */
class Template {
	/**
	 * The filename of the template to load.
	 *
	 * @access protected
	 * @var string
	 */
    protected $file;
        
    /**
     * An array of values for replacing each tag on the template (the key for each value is its corresponding tag).
     *
     * @access protected
     * @var array
     */
    protected $values = array();
        
    /**
     * Creates a new Template object and sets its associated file.
     *
     * @param string $file the filename of the template to load
     */
    public function __construct($file) {
        $this->file = $file;
    }
        
    /**
     * Sets a value for replacing a specific tag.
     *
     * @param string $key the name of the tag to replace
     * @param string $value the value to replace
     */
    public function set($key, $value) {
        $this->values[$key] = $value;
    }

    
    
    
    
    
    
      public function process_php( $str ) {
    $php_start = 0;

    $tag = '<?php';
    $endtag = '?>';

    while(is_long($php_start = strpos($str, $tag, $php_start)))
    {
        $start_pos = $php_start + strlen($tag);
        $end_pos = strpos($str, $endtag, $start_pos); //the 3rd param is to start searching from the starting tag - not to mix the ending tag of the 1st block if we want for the 2nd

        if (!$end_pos) { echo "template: php code has no ending tag!", exit; }
        $php_end = $end_pos + strlen($endtag);

        $php_code = substr($str, $start_pos, $end_pos - $start_pos);
        if( strtolower(substr($php_code, 0, 3)) == 'php' )
            $php_code = substr($php_code, 3);

        // before php code
        $part1 = substr($str, 0, $php_start);

        // here set the php output
        ob_start();
        eval($php_code);
        $output = ob_get_contents();
        ob_end_clean();

        // after php code
        $part2 = substr($str, $php_end, strlen($str));

        $str = $part1 . $output . $part2;
    }
    return $str;
}

    
    
    /**
     * Outputs the content of the template, replacing the keys for its respective values.
     *
     * @return string
     */
    public function output() {
    	/**
    	 * Tries to verify if the file exists.
    	 * If it doesn't return with an error message.
    	 * Anything else loads the file contents and loops through the array replacing every key for its value.
    	 */
        if (!file_exists($this->file)) {
        	return "Error loading template file ($this->file).<br>";
        }
        $output = Template::process_php(file_get_contents($this->file));

        foreach ($this->values as $key => $value) {
        	$tagToReplace = "[@$key]";

        	$output = str_replace($tagToReplace, $value, $output);
        }

        return $output;
    }
}