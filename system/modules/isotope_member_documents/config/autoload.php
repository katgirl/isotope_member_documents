<?php

/**
 * PHP version 5
 * @copyright  Copyright (C) 2013 Kirsten Roschanski
 * @author     Kirsten Roschanski <git@kirsten-roschanski.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @package    IsotopeMemberDocuments 
 * @filesource https://github.com/katgirl/isotope_member_documents
 */


/**
 * Register PSR-0 namespace
 */
NamespaceClassLoader::add('Isotope', 'system/modules/isotope_member_documents/library');


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
  'mod_iso_orderdetails' => 'system/modules/isotope_member_documents/templates/modules',
));
