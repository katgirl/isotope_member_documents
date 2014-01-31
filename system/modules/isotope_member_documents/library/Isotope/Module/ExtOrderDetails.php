<?php

/**
 * PHP version 5
 * @copyright  Copyright (C) 2013 Kirsten Roschanski
 * @author     Kirsten Roschanski <git@kirsten-roschanski.de>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @package    IsotopeMemberDocuments 
 * @filesource https://github.com/katgirl/isotope-member-documents
 */

namespace Isotope\Module;

use Haste\Util\Format;
use Isotope\Isotope;
use Isotope\Model\ProductCollection\Order;
use Isotope\Model\Document;

/**
 * Extented the OrderDetails with Documents
 *
 * @package    IsotopeMemberDocuments
 * @author     Kirsten Roschanski <git@kirsten-roschanski.de>
 */
class ExtOrderDetails extends OrderDetails
{ 
  /**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate($blnBackend = false)
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new \BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### ISOTOPE ECOMMERCE: ORDER DETAILS WITH DOCUMENTS###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

    if ($blnBackend) {
        $this->backend = true;
        $this->jumpTo  = 0;
    }

    return parent::generate();
	}
  
  /**
   * Generate the module
   */
  protected function compile()
  {
    // Also check owner (see #126)
    if (($objOrder = Order::findOneBy('uniqid', (string) \Input::get('uid'))) === null || (FE_USER_LOGGED_IN === true && $objOrder->member > 0 && \FrontendUser::getInstance()->id != $objOrder->member)) {
      $this->Template          = new \Isotope\Template('mod_message');
      $this->Template->type    = 'error';
      $this->Template->message = $GLOBALS['TL_LANG']['ERR']['orderNotFound'];

      return;
    }

    Isotope::setConfig($objOrder->getRelated('config_id'));
    
    $formID = 'iso_member_documents';
    
    if( \Input::post('FORM_SUBMIT') == $formID && $this->iso_downloadDocument > 0 && (($objDocument = Document::findByPk($this->iso_downloadDocument)) !== null))
    {
      $objDocument->outputToBrowser($objOrder);
    }
    

    $objTemplate               = new \Isotope\Template($this->iso_collectionTpl);
    $objTemplate->linkProducts = true;

    $objOrder->addToTemplate(
      $objTemplate,
      array(
           'gallery' => $this->iso_gallery,
           'sorting' => $objOrder->getItemsSortingCallable($this->iso_orderCollectionBy),
      )
    );

    $this->Template->collection           = $objOrder;
    $this->Template->products             = $objTemplate->parse();
    $this->Template->info                 = deserialize($objOrder->checkout_info, true);
    $this->Template->date                 = Format::date($objOrder->locked);
    $this->Template->time                 = Format::time($objOrder->locked);
    $this->Template->datim                = Format::datim($objOrder->locked);
    $this->Template->orderDetailsHeadline = sprintf($GLOBALS['TL_LANG']['MSC']['orderDetailsHeadline'], $objOrder->document_number, $this->Template->datim);
    $this->Template->orderStatus          = sprintf($GLOBALS['TL_LANG']['MSC']['orderStatusHeadline'], $objOrder->getStatusLabel());
    $this->Template->orderStatusKey       = $objOrder->getStatusAlias();
    
    if($this->iso_downloadDocument)
    {
      $this->Template->submit             = $GLOBALS['MSC']['iso_member_documents']['button'];
      $this->Template->action             = ampersand(\Environment::get('request'));
      $this->Template->formId             = $formID;
    }
  }
}
