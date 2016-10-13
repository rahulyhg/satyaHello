<?php
/*
 * Fuse
 *
 * A simple open source ticket management system. 
 *
 * @package		Fuse
 * @author		Vivek. V
 * @link		http://getvivekv.bitbucket.org/Fuse
 * @link		http://www.vivekv.com
 */

 
/**
 * Template loader class. This custom loader class overrides CI_Loader
 */

class MY_Loader extends CI_Loader
{

    function __construct()
    {
        parent::__construct();
    }

    public function template($template_name, $vars = array(), $return = FALSE)
    {

        $template = 'default';

        $content = $this -> view($template . '/header', $vars, $return);
        $content .= $this -> view($template . '/' . $template_name, $vars, $return);
        $content .= $this -> view($template . '/footer', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }

    public function admin_template($template_name, $vars = array(), $return = FALSE)
    {

        $template = 'default';

        $content = $this -> view($template . '/admin/header', $vars, $return);
        
        if( isset($vars['sidebar'])) // If sidebar is present, then we need to load it after header
        $content .= $this -> view($template . '/admin/sidebars/' . $vars['sidebar'], $vars, $return);
        
        $content .= $this -> view($template . '/admin/' . $template_name, $vars, $return);
        $content .= $this -> view($template . '/admin/footer', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }

   
}
