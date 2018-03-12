<?php

class HTMLTemplateEetCore extends HTMLTemplate 
{
     protected $data;
     public function __construct($object, $smarty) {
         $this->smarty = $smarty;
         $this->object = $object;
         $this->data = $this->object->getPdfVars();
     }
     
    public function getFilename()
    {      
       return  'EET_'.$this->data['poradove_cislo'].'.pdf';
    }

    public function getFooter()
    {
        $shop_address = $this->getShopAddress();

        $id_shop = (int)$this->shop->id;

        $this->smarty->assign(array(
            'available_in_your_account' => $this->available_in_your_account,
            'shop_address' => $shop_address,
            'shop_fax' => Configuration::get('PS_SHOP_FAX', null, null, $id_shop),
            'shop_phone' => Configuration::get('PS_SHOP_PHONE', null, null, $id_shop),
            'shop_email' => Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop),
            'free_text' => Configuration::get('PS_INVOICE_FREE_TEXT', (int)Context::getContext()->language->id, null, $id_shop)
        ));

        return $this->smarty->fetch(_PS_MODULE_DIR_.'eetplus/views/templates/eetpdffooter.tpl');
    }
    /**
     * Returns the template filename when using bulk rendering
     *
     * @return string filename
     */
      public function getBulkFilename() {
          
      }
  public function getContent()
    {
       
        $this->smarty->assign(array(
            'data' => $this->data,
            
        ));
       
        return $this->smarty->fetch(_PS_MODULE_DIR_.'eetplus/views/templates/eetpdf.tpl');
       
      
    }
}


