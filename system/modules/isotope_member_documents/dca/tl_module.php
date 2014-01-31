<?php

/**
 * PHP version 5
 * @copyright  Copyright (C) 2013 Kirsten Roschanski
 * @author     Kirsten Roschanski <git@kirsten-roschanski.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @package    IsotopeMemberDocuments 
 * @filesource https://github.com/katgirl/isotope-member-documents
 */
 
/**
 * Table tl_module
 */

// Palettes 
$GLOBALS['TL_DCA']['tl_module']['palettes']['iso_orderdetails'] = str_replace
(
    'iso_includeMessages',
    'iso_downloadDocument,iso_includeMessages',
    $GLOBALS['TL_DCA']['tl_module']['palettes']['iso_orderdetails']
);

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['iso_downloadDocument'] = array
(
    'name'       => 'document',
    'label'      => &$GLOBALS['TL_LANG']['tl_module']['iso_downloadDocument'],
    'inputType'  => 'select',
    'foreignKey' => 'tl_iso_document.name',
    'eval'       => array('includeBlankOption'=>true, 'tl_class'=>'w50'),
    'sql'        => "varchar(255) NOT NULL default ''",
);
