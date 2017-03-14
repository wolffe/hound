<?php
function get_variable($item) {
    $headers = array(
        'title' => 'Title',
        'description' => 'Description',
        'meta.title' => 'Meta.title',
        'meta.description' => 'Meta.description',
        'meta.keywords' => 'Meta.keywords',
        'content' => 'Content',
        'template' => 'Template',
        'menu' => 'Menu',
        'url' => 'Url',
        'slug' => 'Slug',
        'order' => 'Order',
        'link' => 'Link',
        'item' => 'Item',
        'featuredimage' => 'Featuredimage',
        'slogan' => 'Slogan',
        'include' => '',
    );

    if(!function_exists('file_get_contents')) {
        $content = $this->url_get_contents('site/config.txt');
    } else {
        $content = file_get_contents('site/config.txt');
    }

    // Add support for custom headers by hooking into the headers array
    foreach ($headers as $field => $regex) {
        if (preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $content, $match) && $match[1]) {
            $headers[$field] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
        } else {
            $headers[$field] = '';
        }
    }

    $item = $headers[$item];

    return $item;
}

function get_theme_directory($partial) {
    $websiteurl = getcwd();

    $template = get_variable('template');
    $template_path = $websiteurl . '/site/templates/' . $template . '/' . $partial;

    return $template_path;
}

function houndCountContent($type) {
    if ($type === 'page' || $type === 'menu') {
        $dir = '../site/pages/';
        $list = glob($dir . $type . '-*.txt');
    } else if ($type === 'backup') {
        $dir = '../backup/';
        $list = glob($dir . $type . '-*.zip');
    } else if ($type === 'asset') {
        $dir = '../files/images/';
        $list = glob($dir . '*.*');
    }

    return (int) count($list);
}

class hound {
    var $config;
    var $path;
    var $websiteurl;
    var $plugins;

    public function __construct($path, $websiteurl) {
        $this->path = $path;
        $this->websiteurl = $websiteurl;
    }

    public function start() {
        // Load plugins
        $this->load_plugins();
        $this->run_hooks('plugins_loaded');

        // Read template
        $config = $this->read_param('site/config.txt');
        $titleofsite = $config['title'];

        // If name of page is in format: page.html
        //.htaccess
        //RewriteRule ^(.*).html$ /index.php
        //$curpage=str_replace(".html","",$curpath);
        //$curpage=str_replace("/","",$curpage);

        // Retrieve a page
        $current_url = explode('?', $_SERVER['REQUEST_URI']);
        $curpath = str_replace($this->path, '', $current_url[0]);

        //PATH WITHOUT FINAL SLASH  www.site.it/page
        //$curpage=substr($curpath, strrpos($curpath, '/') + 1);

        //PATH WIT FINAL SLASH  www.site.it/page/
        $listofword = explode('/', $curpath);
        $curpage = $listofword[count($listofword) - 1];

        //LOAD FILE IN PAGES FOLDER
        $i=0;
        $fileindir=$this->get_files('site/pages/');
        foreach ($fileindir as $file) {
            //THE PAGES
            if (preg_match("/\bpage\b/i", $file)) {
                $listofpage[]=str_replace("site/pages/","",$file);
            } 

            //THE MENU
            if (preg_match("/\bmenu\b/i", $file)) {
                $menuparam=$this->read_param($file);
                $arrayofmenu[$i]['order']=$menuparam['order'];
                $arrayofmenu[$i]['link']=$menuparam['link'];
                $arrayofmenu[$i]['item']=$menuparam['item'];
                $i++;
            } 
        }

        // READ FILE CONTENT
        if(in_array("page-".$curpage.".txt", $listofpage)){
            $pageparam=$this->read_param("site/pages/page-".$curpage.".txt");
        }else{
            $pageparam=$this->read_param("site/pages/page-index.txt");
        }

        //BUILD MENU
        array_multisort($arrayofmenu);
        $menuitems="";
        foreach ($arrayofmenu as $itemmenu) {
            $menuitems.="<li>
            <a href=\"".$itemmenu['link']."\">".$itemmenu['item']."
            </a>
            </li>";
        }

        $this->run_hooks('after_read_param');

        //$this->run_hooks('before_render');

        //RENDER LAYOUT
        $layout = new Template("site/templates/".$config['template']."/".$pageparam['template']);
        $layout->set("meta.title", $pageparam['meta.title']." | ".$titleofsite);
        $layout->set("meta.description", $pageparam['meta.description']);
        $layout->set("title", $pageparam['title']);
        $layout->set("featuredimage", $pageparam['featuredimage']);

        // Turn this into a function
        $pattern = '/\[gallery(.*?)?\](?:(.+?)?\[\/gallery\])?/';
        preg_match($pattern, $pageparam['content'], $matches, PREG_OFFSET_CAPTURE, 3);

        if(!empty($matches)) {
            $files = glob('site/templates/' . $config['template'] . '/' . str_replace('"', '', trim($matches[1][0])) . '*.*');
            $gstring = '<div class="gallery">';
                for($i=1; $i<count($files); $i++) {
                    $image = $files[$i];
                    $supported_file = array(
                        'gif',
                        'jpg',
                        'jpeg',
                        'png'
                    );
                    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                    if(in_array($ext, $supported_file)) {
                        //print $image ."<br />";
                        $gstring .= '<div><img src="' . $image . '" alt=""></div>';
                    } else {
                        continue;
                    }
                }
            $gstring .= '</div>';
            $pageparam['content'] = preg_replace($pattern, $gstring, $pageparam['content']);
        }
        //

        $layout->set("content", $pageparam['content']);
        $layout->set("menu", $menuitems);  
        $layout->set("urlwebsite", $this->websiteurl); 
        $layout->set("site.title", $config['title']);
        $layout->set("slogan", $config['slogan']);

        $layout->set("slug", $pageparam['slug']);
        $layout->set("excerpt", substr(strip_tags(trim($pageparam['content'])), 0, 300));
            
        //$this->run_hooks('after_render');
        echo $layout->output();
    }

    function get_files($directory, $ext = ''){

        $array_items = array();
        if ($files = scandir($directory)) {
            foreach ($files as $file) {
                if (in_array(substr($file, -1), array('~', '#'))) {
                    continue;
                }
                if (preg_match("/^(^\.)/", $file) === 0) {
                    if (is_dir($directory . "/" . $file)) {
                        $array_items = array_merge($array_items, $this->get_files($directory . "/" . $file, $ext));
                    } else {
                        $file = $directory . "/" . $file;
                        if (!$ext || strstr($file, $ext)) {
                            $array_items[] = preg_replace("/\/\//si", "/", $file);
                        }
                    }
                }
            }
        }

        return $array_items;
    }

    function read_param($file){

        $headers = array(
            'title' => 'Title',
            'description' => 'Description',
            'meta.title' => 'Meta.title',
            'meta.description' => 'Meta.description',
            'meta.keywords' => 'Meta.keywords',
            'content' => 'Content',
            'template' => 'Template',
            'menu' => 'Menu',
            'url' => 'Url',
            'slug' => 'Slug',
            'order' => 'Order',
            'link' => 'Link',
            'item' => 'Item',
            'featuredimage' => 'Featuredimage',
            'slogan' => 'Slogan',
            'include' => '',
        'version' => 'Version',

        );

        if (!function_exists('file_get_contents')){ 
            $content=$this->url_get_contents($file);
        }else {
            $content = file_get_contents($file);
        }

    
        // Add support for custom headers by hooking into the headers array
        foreach ($headers as $field => $regex) {
            if (preg_match('/^[ \t\/*#@]*' . preg_quote($regex, '/') . ':(.*)$/mi', $content, $match) && $match[1]) {
                $headers[$field] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
            } else {
                $headers[$field] = '';
            }
        }


        return $headers;
    }

    public function url_get_contents ($Url) {
        if (function_exists('curl_exec')){ 
                $conn = curl_init($url);
                curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
                curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
                $url_get_contents_data = (curl_exec($conn));
                curl_close($conn);
            }elseif(function_exists('file_get_contents')){
                $url_get_contents_data = file_get_contents($url);
            }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
                $handle = fopen ($url, "r");
                $url_get_contents_data = stream_get_contents($handle);
            }else{
                $url_get_contents_data = false;
            }
        return $url_get_contents_data;
    }


     /**
     * Load any plugins
     */
    public function load_plugins()
    {
        $this->plugins = array();
        $plugins = $this->get_files("plugins", '.php');
        if (!empty($plugins)) {
            foreach ($plugins as $plugin) {
                include_once($plugin);
                $plugin_name = preg_replace("/\\.[^.\\s]{3}$/", '', basename($plugin));
                if (class_exists($plugin_name)) {
                    $obj = new $plugin_name;
                    $this->plugins[] = $obj;
                }
            }
        }
    }


    /**
     * Processes any hooks and runs them
     *
     * @param string $hook_id the ID of the hook
     * @param array $args optional arguments
     */
    public function run_hooks($hook_id, $args = array())
    {
        if (!empty($this->plugins)) {
            foreach ($this->plugins as $plugin) {
                if (is_callable(array($plugin, $hook_id))) {
                    call_user_func_array(array($plugin, $hook_id), $args);
                }
            }
        }
    }
}
