<?php

/**
 * PHP connector for Pygments
 * Version: 0.1
 * Author: Marcelo Iván Tosco (Capy)
 * Author URI: http://ecapy.com/
 * License: GPL v3
 */
class PHPygments
{

    /**
     * Sends code to highlight to pygments across using "bind.py" and returns the result
     * @param $code
     * @param $language
     * @param string $style
     * @param string $linenumbers
     * @return array "code" the processed code "styles" a reference of css needed files.
     */
    static function render($code, $language, $style = "default", $linenumbers = "False")
    {

        $pygments_bind_app = "python " . dirname(__FILE__) . "/bind.py";

        // Create a temporary file as bridge for code...
        $temp_name = tempnam("/tmp", "pygmentize_");
        $file_handle = fopen($temp_name, "w");
        fwrite($file_handle, $code);
        fclose($file_handle);
        chmod($temp_name, 0777);

        //Settings
        $pygments_bind_params = array(
            "--sourcefile" => $temp_name,
            "--style" => $style,
            "--lang" => $language,
            "--linenumbers" => $linenumbers
        );

        $params = " ";
        foreach ($pygments_bind_params as $k => $v) {
            $params .= $k . "=" . $v . " ";
        }

        $command = $pygments_bind_app . " " . rtrim($params);
        $output = array();
        $retval = -1;

        exec($command, $output, $retval);
        unlink($temp_name);

        return array(
            "code" => utf8_decode(implode("\n", $output)),
            "styles" => "styles/" . $style . ".css"
        );

    }


}
