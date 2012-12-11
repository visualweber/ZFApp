<?php
/**
 * http://riteshsblog.blogspot.com/2012/01/zend-flash-message.html
 * Enter description here ...
 * @author TOAN
 *
 */
class App_Controller_Action_Helper_Messenger extends Zend_Controller_Action_Helper_FlashMessenger
{
    public function showmessage($messages, $type = 'message')
    {
    	return $messages;
        $html = '';
        if ($messages)
        {
            $html = '<div class="clr"></div>';
            $html .= '<dl id="system-message">';
            $html .= '<dd class="' . $type . ' message fade">';
            $html .= "	<ul>
		                      <li>" . $messages . "</li>
	                       </ul>";
            $html .= '</dd>';
            $html .= '</dl>';
        }
        return $html;
    }
}
?>