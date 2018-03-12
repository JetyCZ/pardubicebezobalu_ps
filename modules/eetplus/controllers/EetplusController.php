<?php
/**
 * eetplus module for Prestashop
 *
 * PHP version 5
 *
 * LICENSE: The buyer can free use/edit/modify this software in anyway
 * The buyer is NOT allowed to redistribute this module in anyway or resell it
 * or redistribute it to third party
 *
 * @package    eetplus
 * @author    Vaclav Mach <info@prestahost.cz>
 * @copyright 2017 Vaclav Mach
 * @license   EULA
 * @link       http://www.prestahost.cz
 */
class EetplusController
{
    public function postProcess()
    {
        $output = '';
        for ($i = 0; $i < count($this->settings); $i++) {
            Configuration::updateValue($this->settings[$i], Tools::getValue($this->settings[$i]));
        }
        $output .= $this->instance->l('Settings updated');
        return $output;
    }
    
    public function getSettings()
    {
        return $this->settings;
    }
    
    protected function generateTextBox($name, $label, $legend = null, $size = 12, $required = true)
    {
        if($required)
        $retval = '<div class="form-group"><label class="control-label col-lg-3 required">' . $label . ' </label>';
        else
        $retval = '<div class="form-group"><label class="control-label col-lg-3">' . $label . ' </label>';
        $retval .= '<div class="col-lg-3 ">';
        $retval .= '<input id="' . $name . '" class="" type="text"   size="' . $size . '" value="' . Configuration::get($name) . '" name="' . $name . '">
        </div> ' . $legend . ' </div>';
        return $retval;
    }
    
    protected function generatePassword($name, $label, $legend = null, $size = 12)
    {
        $retval = '<div class="form-group"><label class="control-label col-lg-3 required">' . $label . ' </label>';
        $retval .= '<div class="col-lg-3 ">';
        $retval .= '<input id="' . $name . '" class="" type="password"   size="' . $size . '" value="' . Configuration::get($name) . '" name="' . $name . '">
        </div> ' . $legend . ' </div>';
        return $retval;
    }
    
    protected function generateRadio($name, $label, $legend = null, $options)
    {
        $retval = '<div class="form-group"> <label class="control-label col-lg-3"> ' . $label . ' </label>';
        $retval .= ' <div class="col-lg-9 ">';
        $test = (int) Configuration::get($name);
        while (list($key, $val) = each($options)) {
            $retval .= "<input id='$key' type='radio' value='$key' name='$name'";
            if ($test == $key)
                $retval .= " checked ='checked'";
            $retval .= "/> $val<br />";
        }
        $retval .= '</div></div>';
        return $retval;
    }
    
    protected function generateSubmit($name, $label, $legend = null)
    {
        $retval = ' <div class="panel-footer">';
        $retval .= '<button  class="button" name="' . $name . '" value="1" type="submit">';
        $retval .= '<i class="process-icon-save"></i>' . $label . '</button></div>';
        return $retval;
    }
    
    protected function generateCheckBox($name, $label, $legend = null, $checked = '', $sizelabel=3, $size = 3)
    {
        $retval = '<div class="form-group"><label class="control-label col-lg-'.$sizelabel.'">' . $label . ' </label>';
        $retval .= '<div class="col-lg-'.$size.'">';
        $retval .= '<input id="' . $name . '" class="" type="checkbox"   name="' . $name . '" ' . $checked . ' value="1">
        </div> ' . $legend . ' </div>';
        return $retval;
    }
    
}