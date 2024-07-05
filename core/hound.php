<?php
function get_theme_directory($partial) {
    $themeSlug = hound_get_option('site_theme');;
    $themeDirectory = HOUND_DIR . '/content/site/templates/' . $themeSlug . '/' . $partial;

    return $themeDirectory;
}

function get_theme_url($partial) {
    $themeSlug = hound_get_option('site_theme');;
    $themeDirectory = HOUND_URL . '/content/site/templates/' . $themeSlug . '/' . $partial;

    return $themeDirectory;
}

function hound_count_content($type) {
    $db = hound_db();

    if ($type === 'page' || $type === 'menu' || $type === 'post') {
        $stmt = $db->prepare('SELECT COUNT(*) AS post_count FROM posts WHERE post_type = :post_type');
        $stmt->bindParam(':post_type', $type, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['post_count'])) {
            return (int) $result['post_count'];
        } else {
            return 0;
        }
    } elseif ($type === 'asset') {
        $dir = '../../content/files/images/';
        $list = glob($dir . '*.*');

        return (int) count($list);
    }

    return 0;
}

function hound_compare($a, $b) {
    return filemtime($b) - filemtime($a);
}

function hound_render_blog($echo = true) {
    $found_posts = hound_get_posts('post');
    $blogPosts = '';

    foreach ($found_posts as $post) {
        $post_url = rtrim( HOUND_URL, '/\\' ) . '/' . $post['post_slug'];

        $blogPosts .= '<div class="post">
            <h3><a href="' . $post_url . '">' . $post['post_title'] . '</a></h3>
            <div class"post-meta">' . $post['post_date'] . '</div>
            <div class="post-content">' . $post['post_content'] . '</div>
        </div>';
    }

    if ($echo === true) {
        echo $blogPosts;
    } else {
        return $blogPosts;
    }
}



function hound_get_files($directory, $ext = '') {
    $arrayItems = array();

    if ($files = scandir($directory)) {
        foreach ($files as $file) {
            if (in_array(substr($file, -1), array('~', '#'))) {
                continue;
            }
            if (preg_match("/^(^\.)/", $file) === 0) {
                if (is_dir($directory . "/" . $file)) {
                    $arrayItems = array_merge($arrayItems, hound_get_files($directory . "/" . $file, $ext));
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

function hound_init_plugins() {
    $plugins = hound_get_files('content/plugins', '.php');

    if (!empty($plugins)) {
        foreach ($plugins as $plugin) {
            include_once($plugin);
            $pluginName = preg_replace("/\\.[^.\\s]{3}$/", '', basename($plugin));
            if (class_exists($pluginName)) {
                $obj = new $pluginName;
                $plugins[] = $obj;
            }
        }
    }
}

function hound_init() {
    hound_init_plugins();

    /**
     * Read template
     */
    $titleofsite = hound_get_option('site_title');

    /**
     * Get current page
     */
    $currentPageSlug = strtok(basename($_SERVER['REQUEST_URI']), '?');
    $currentPageSlug = str_replace(HOUND_DIR_SINGLE, '', $currentPageSlug);

    /**
     * Build menu
     */
    $arrayofmenu = hound_get_menu();

    $menuitems = '';
    if (!empty($arrayofmenu) && is_array($arrayofmenu)) {
        usort($arrayofmenu, function ($a, $b) {
            return $a['node_order'] <=> $b['node_order'];
        });

        foreach ($arrayofmenu as $itemmenu) {
            $menuitems .= '<li><a href="' . $itemmenu['node_url'] . '">' . $itemmenu['node_title'] . '</a></li>';
        }
    }

    /**
     * Render layout (SQLite)
     */
    if ($currentPageSlug === '') {
        //echo '<p>Slug is empty. ' . $currentPageSlug . '</p>';
        $post = hound_get_post(0, '', $currentPageSlug, 'home');
        $post = hound_get_post(0, '', 'index');
    } else {
        $post = hound_get_post(0, '', $currentPageSlug);
    }

    if ($post) {
        // Display the post details
        $db_slug = $post['post_slug'];
        $db_title = $post['post_title'];
        $db_type = $post['post_type'];
        $db_content = $post['post_content'];
        $db_template = $post['post_template'];
        $db_date = $post['post_date'];
        $db_author = $post['post_author'];
    } else {
        echo 'Post not found.';
    }
    //





    /**
     * Render layout
     */
    $layout = new Template('content/site/templates/' . hound_get_option('site_theme') . '/' . $db_template);
    $layout->set('title', $db_title);

    /**
     * Parse content hook(s)
     */
    $pageparam['content'] = hook('content', $db_content);

    $layout->set('content', $pageparam['content']);
    $layout->set('menu', $menuitems);  
    $layout->set('urlwebsite', HOUND_URL); 
    $layout->set('site.title', hound_get_option('site_title'));

    $layout->set('slug', $db_slug);
    $layout->set('excerpt', substr(strip_tags(trim($pageparam['content'])), 0, 300));

    echo $layout->output();
}
