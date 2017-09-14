<?php
/**
 * Scan the plugins path, recursively including all PHP extensions
 *
 * @param string  $dir
 *
 */
function getExtensions($target) {
    if (is_dir($target)) {
        $files = glob($target . '*', GLOB_MARK);

        foreach ($files as $file) {
            getExtensions($file);

            if (strpos($file, 'ext-') !== false) {
                include_once $file;
            }
        }
    } 
}
getExtensions('../plugins');

function get_variable($item) {
    $headers = array(
        'title' => 'Title',
        'content' => 'Content',
        'template' => 'Template',
        'menu' => 'Menu',
        'url' => 'Url',
        'slug' => 'Slug',
        'order' => 'Order',
        'link' => 'Link',
        'item' => 'Item',
        'slogan' => 'Slogan',
        'include' => '',
    );

        if (!function_exists('file_get_contents')) {
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
    $templatePath = $websiteurl . '/site/templates/' . $template . '/' . $partial;

    return $templatePath;
}

function houndCountContent($type) {
    if ($type === 'page' || $type === 'menu') {
        $dir = '../site/pages/';
        $list = glob($dir . $type . '-*.txt');
    } else if ($type === 'post') {
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

        $listofpage = array();

        // Read template
        $config = $this->read_param('site/config.txt');
        $titleofsite = $config['title'];

        // Retrieve a page
        $currentUrl = explode('?', $_SERVER['REQUEST_URI']);
        $curpath = str_replace($this->path, '', $currentUrl[0]);

        $listofword = explode('/', $curpath);
        $curpage = $listofword[count($listofword) - 1];

        // Load files in /pages/
        $i = 0;
        $fileindir = $this->get_files('site/pages/');
        foreach ($fileindir as $file) {
            if (preg_match("/\bpage\b/i", $file)) {
                $listofpage[] = str_replace('site/pages/', '', $file);
            }
            if (preg_match("/\bpost\b/i", $file)) {
                $listofpage[] = str_replace('site/pages/', '', $file);
            }

            if (preg_match("/\bmenu\b/i", $file)) {
                $menuparam = $this->read_param($file);
                $arrayofmenu[$i]['order'] = $menuparam['order'];
                $arrayofmenu[$i]['link'] = $menuparam['link'];
                $arrayofmenu[$i]['item'] = $menuparam['item'];
                $i++;
            }
        }

        // Read file content
        if (in_array('page-' . $curpage . '.txt', $listofpage)) {
            $pageparam = $this->read_param('site/pages/page-' . $curpage . '.txt');
        } else if (in_array('post-' . $curpage . '.txt', $listofpage)) {
            $pageparam = $this->read_param('site/pages/post-' . $curpage . '.txt');
        } else {
            $pageparam = $this->read_param('site/pages/page-index.txt');
        }

        // Build menu
        $menuitems = '';
        if (!empty($arrayofmenu) && is_array($arrayofmenu)) {
            array_multisort($arrayofmenu);
            foreach ($arrayofmenu as $itemmenu) {
                $menuitems .= '<li><a href="' . $itemmenu['link'] . '">' . $itemmenu['item'] . '</a></li>';
            }
        }

        $this->run_hooks('after_read_param');

        //$this->run_hooks('before_render');

        //RENDER LAYOUT
        $layout = new Template("site/templates/".$config['template']."/".$pageparam['template']);
        $layout->set("title", $pageparam['title']);

        // Turn this into a function
        // [gallery "/images/gallery-2014/"]
        $pattern = '/\[gallery(.*?)?\](?:(.+?)?\[\/gallery\])?/';
        preg_match($pattern, $pageparam['content'], $matches, PREG_OFFSET_CAPTURE, 3);

        if (!empty($matches)) {
            $files = glob('site/templates/' . $config['template'] . '/' . str_replace('"', '', trim($matches[1][0])) . '*.*');
            $gstring = '<div class="grid">';
                for ($i=1; $i<count($files); $i++) {
                    $image = $files[$i];
                    $supportedFile = array(
                        'gif',
                        'jpg',
                        'jpeg',
                        'png'
                    );
                    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                    if (in_array($ext, $supportedFile)) {
                        $gtitle = ucwords(str_replace(array('-', '_'), ' ', pathinfo($image, PATHINFO_FILENAME)));
                        $gstring .= '<div class="grid-item"><img src="' . $image . '" width="' . $width . '" height="' . $height. '" title="' . $gtitle . '" data-title="' . $gtitle . '" alt=""></div>';
                    } else {
                        continue;
                    }
                }
            $gstring .= '</div>';
            $pageparam['content'] = preg_replace($pattern, $gstring, $pageparam['content']);
        }
        //

        $pageparam['content'] = pluginHoundSlider(
            '/\[slider(.*?)?\](?:(.+?)?\[\/slider\])?/',
            $pageparam['content'],
            $config['template']
        );

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

        $arrayItems = array();
        if ($files = scandir($directory)) {
            foreach ($files as $file) {
                if (in_array(substr($file, -1), array('~', '#'))) {
                    continue;
                }
                if (preg_match("/^(^\.)/", $file) === 0) {
                    if (is_dir($directory . "/" . $file)) {
                        $arrayItems = array_merge($arrayItems, $this->get_files($directory . "/" . $file, $ext));
                    } else {
                        $file = $directory . "/" . $file;
                        if (!$ext || strstr($file, $ext)) {
                            $arrayItems[] = preg_replace("/\/\//si", "/", $file);
                        }
                    }
                }
            }
        }

        return $arrayItems;
    }

    function read_param($file) {
        if (!file_exists($file)) {
            include 'admin/templates/install.php';

            return;
        }

        $headers = array(
            'title' => 'Title',
            'content' => 'Content',
            'template' => 'Template',
            'menu' => 'Menu',
            'url' => 'Url',
            'slug' => 'Slug',
            'order' => 'Order',
            'link' => 'Link',
            'item' => 'Item',
            'slogan' => 'Slogan',
            'include' => '',
            'version' => 'Version',
        );

        if (!function_exists('file_get_contents')) {
            $content = $this->url_get_contents($file);
        } else {
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

    public function url_get_contents($url) {
        if (function_exists('curl_exec')){ 
                $conn = curl_init($url);
                curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($conn, CURLOPT_FRESH_CONNECT,  true);
                curl_setopt($conn, CURLOPT_RETURNTRANSFER, 1);
                $urlGetContentsData = (curl_exec($conn));
                curl_close($conn);
            }elseif(function_exists('file_get_contents')){
                $urlGetContentsData = file_get_contents($url);
            }elseif(function_exists('fopen') && function_exists('stream_get_contents')){
                $handle = fopen ($url, "r");
                $urlGetContentsData = stream_get_contents($handle);
            }else{
                $urlGetContentsData = false;
            }
        return $urlGetContentsData;
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
                $pluginName = preg_replace("/\\.[^.\\s]{3}$/", '', basename($plugin));
                if (class_exists($pluginName)) {
                    $obj = new $pluginName;
                    $this->plugins[] = $obj;
                }
            }
        }
    }


    /**
     * Processes any hooks and runs them
     *
     * @param string $hookId the ID of the hook
     * @param array $args optional arguments
     */
    public function run_hooks($hookId, $args = array())
    {
        if (!empty($this->plugins)) {
            foreach ($this->plugins as $plugin) {
                if (is_callable(array($plugin, $hookId))) {
                    call_user_func_array(array($plugin, $hookId), $args);
                }
            }
        }
    }

    public static function getBlog() {
        $i = 0;
        $arrayOfPosts = array();

        $getPosts = glob('site/pages/post-*.txt');
        usort($getPosts, create_function('$a,$b', 'return filemtime($b) - filemtime($a);'));

        foreach($getPosts as $file) {
            $headers = array(
                'title' => 'Title',
                'content' => 'Content',
                'template' => 'Template',
                'slug' => 'Slug',
            );

            if (!function_exists('file_get_contents')) {
                $content = $this->url_get_contents($file);
            } else {
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

            $postParam = $headers;

            $arrayOfPosts[$i]['slug'] = $postParam['slug'];
            $arrayOfPosts[$i]['title'] = $postParam['title'];
            $arrayOfPosts[$i]['content'] = $postParam['content'];

            $arrayOfPosts[$i]['date'] = date('F d Y H:i:s', filemtime($file));

            $i++;
        }

        // Build blog
        $blogPosts = '';
        if (!empty($arrayOfPosts) && is_array($arrayOfPosts)) {
            foreach ($arrayOfPosts as $blogPost) {
                $blogPosts .= '<div class="post">
                    <h3><a href="' . $blogPost['slug'] . '">' . $blogPost['title'] . '</a></h3>
                    <div class"post-meta">' . $blogPost['date'] . '</div>
                    <div class="post-content">' . $blogPost['content'] . '</div>
                </div>';
            }
        }

        return $blogPosts;
    }
}
