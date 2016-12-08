<?php
class houndAdmin extends hound {
    function write_param($arrayvalue, $file) {
        $current = '';
        foreach ($arrayvalue as $value => $key) {
            $current.="$value: $key\n";
        }

        if(file_put_contents($file, $current))return 1;
        else return 0;
    }

    function read_file($file) {
        return file_get_contents($file);
    }	

    function write_file($content, $file) {
        return file_put_contents($file, $content);
    }	

    function read_param_from_string($string) {
        $headers = array(
            'order' => 'Order',
            'item' => 'Item',
            'link' => 'Link'
        );

        $content=$string;

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

    function makeUrlFriendly($output) {
        $output = trim($output);
        /**
        $output = preg_replace("/\s/e" , "_" , $output);         // Replace spaces with underscores
        $output = str_replace("--", "-", $output);
        $output = str_replace("- ", "", $output);
        $output = str_replace("/", "", $output);
        $output = str_replace("\\", "", $output);
        $output = str_replace("'", "", $output);
        $output = str_replace(",", "", $output);
        $output = str_replace(";", "", $output);
        $output = str_replace(":", "", $output);
        $output = str_replace(".", "-", $output);
        $output = str_replace("?", "", $output);
        $output = str_replace("=", "-", $output);
        $output = str_replace("+", "", $output);
        $output = str_replace("$", "", $output);
        $output = str_replace("&", "", $output);
        $output = str_replace("!", "", $output);
        $output = str_replace(">>", "-", $output);
        $output = str_replace(">", "-", $output);
        $output = str_replace("<<", "-", $output);
        $output = str_replace("<", "-", $output);
        $output = str_replace("*", "", $output);
        $output = str_replace(")", "", $output);
        $output = str_replace("(", "", $output);
        $output = str_replace("[", "", $output);
        $output = str_replace("]", "", $output);
        $output = str_replace("^", "", $output);
        $output = str_replace("%", "", $output);
        $output = str_replace("ª", "-", $output);
        $output = str_replace("|", "", $output);
        $output = str_replace("#", "", $output);
        $output = str_replace("@", "", $output);
        $output = str_replace(" ", "-", $output);
        $output = str_replace("`", "", $output);
        $output = str_replace("î", "", $output);
        $output = str_replace("ì", "", $output);
        $output = str_replace("\"", "", $output);
        //$output = str_replace("_", "-", $output);
        $output = strtolower($output);
        /**/

        return $output;
    }
}