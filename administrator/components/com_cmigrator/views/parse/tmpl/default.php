<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

defined('_JEXEC') or die('Restricted access');

if(count($this->err_list) > 0 && $this->err_list[0]) {?>
<div id="system-message-container">
    <dl id="system-message">
        <dt class="message">Message</dt>
        <dd class="message notice">
            <ul>
                <?php foreach($this->err_list as $err) {?>
                <li><?php if($err) echo $err; ?></li>
                <?php } ?>
            </ul>
        </dd>
    </dl>
</div>
<?php } ?>
<div id="width-80 fltlft">    
    <table class="adminform">
        <tr>
            <th class="title">
                <h3><?php echo JText::_('COM_CMIGRATOR_INFORMATION'); ?></h3>
            </th>
            <th class="title">
                <h3><?php echo JText::_('COM_CMIGRATOR_STATUS'); ?></h3>
            </th>
        </tr>
        <tr class="row1">
            <td align="right">
                <?php echo JText::_('COM_CMIGRATOR_PARSING_IMAGES'); ?>
            </td>
            <td>
                <?php echo $this->result['images']; ?>
            </td>
        </tr>
        <tr class="row0">
            <td align="right">
                <?php echo JText::_('COM_CMIGRATOR_PARSING_CONTENT'); ?>
            </td>
            <td>
                <?php echo $this->result['content']; ?>
            </td>
        </tr>
    </table>
</div>