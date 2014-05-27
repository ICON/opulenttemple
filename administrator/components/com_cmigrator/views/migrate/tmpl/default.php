<?php
/**

 * CMigrator - Adminstrator
 * @Copyright (C) 2010 - Daniel Dimitrov - http://compojoom.com
 * @All rights reserved
 * @license under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html

**/

defined('_JEXEC') or die('Restricted access');


?>
<div id="width-80 fltlft">
    <div id="fail-message">
        <p><strong>
            You can safely ignore "Failed! - Duplicate Key!" errors. Please verify first that the contents you imported
            are present in your Joomla site or not.
        </strong></p>
    </div>
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
                <?php echo JText::_('COM_CMIGRATOR_IMPORT_CATEGORIES'); ?>
            </td>
            <td>
                <?php echo $this->errors['categories']; ?>
            </td>
        </tr>

        <tr class="row0">
            <td align="right">
                <?php echo JText::_('COM_CMIGRATOR_IMPORT_CONTENT'); ?>
            </td>
            <td>
                <?php echo $this->errors['content']; ?>
            </td>
        </tr>

        <tr class="row1">
            <td align="right">
                <?php echo JText::_('COM_CMIGRATOR_IMPORT_TAGS'); ?>
            </td>
            <td>
                <?php echo $this->errors['tags']; ?>
            </td>
        </tr>
        
        <tr class="row0">
            <td align="right">
                <?php echo JText::_('COM_CMIGRATOR_IMPORT_USERS'); ?>
            </td>
            <td>
                <?php echo $this->errors['users']; ?>
            </td>
        </tr>
        
        <tr class="row1">
            <td align="right">
                <?php echo JText::_('COM_CMIGRATOR_IMPORT_COMMENTS'); ?>
            </td>
            <td>
                <?php echo $this->errors['comments']; ?>
            </td>
        </tr>
        
    </table>

</div>


