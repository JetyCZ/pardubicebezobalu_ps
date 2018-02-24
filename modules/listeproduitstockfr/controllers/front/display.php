<?php
if (!defined('_PS_VERSION_'))
 exit; 

class listeproduitstockfrdisplayModuleFrontController extends ModuleFrontController
{

  
  public function initContent()
  {
   $this->display_column_left = false;
   $this->display_column_right = false;
    parent::initContent();		
	
	$this->getSendAttributeGroup();
	$id_lang=(int)$this->context->language->id;
	$this->context->smarty->assign(array(
    'url_submit'=>'index.php?fc=module&module=listeproduitstockfr&controller=display&id_lang='.$id_lang, 
    'all_products' =>$this->getAllProducts(),
    'message'=>$this->addToCart() 	
	
    
));
    $this->setTemplate('display.tpl');
  } 
  
  
  
  
  
  function nka_filter(){
  
  $sql = 'SELECT * FROM '._DB_PREFIX_.'product';
   if ($results = Db::getInstance()->ExecuteS($sql))
    foreach ($results as $row)
        echo $row['id_product'].' :: '.$row['id_category'].'::'.$row['active'].'<br />';  
  
 }
  
  
  
  public function getSendAttributeGroup(){
   $id_lang=$this->context->language->id;
   //$id_attribute_group=1;
  $AttributeGroup=new AttributeGroup();
  //$attributes=$AttributeGroup->getAttributes($id_lang, $id_attribute_group);
  $attribute_groups=$AttributeGroup->getAttributesGroups($id_lang);
  
  $html='';
  foreach($attribute_groups as $group){
  $html.='<p></p> 
  <select  name="'.$group['id_attribute_group'].'"  size="1" id="row-5-office" name="row-5-office"  >
  <option value="'.$group['id_attribute_group'].'" selected="selected">'.$group['name'].'</option>';  
    $attributes=$AttributeGroup->getAttributes($id_lang,$group['id_attribute_group']);
   foreach ($attributes as $attribute){
   $html.='<option value="'.$attribute['id_attribute'].'">'.$attribute['name'].'</option>';
      }
   $html.='</select>';
  }
  
  $this->context->smarty->assign(array(
  
    'html' =>$html,
	'attribute_groups'=>$attribute_groups
	
    
));
  
  }
   
  
  public function addToCart(){
  
  $context=Context::getContext();
  $message='';
  $id_cart=(int)$context->cookie->id_cart;
  if(Tools::isSubmit('submitAddThierryProduct')){
  
  $cart=new Cart($id_cart);
  $id_product=(int)Tools::getValue('id_product');
  $quantity=(int)Tools::getValue('ordered_qty');
  
  $cart->updateQty($quantity, $id_product, null, false);
  $message='Product Added Successfully';
  }
  
  return $message;
  
  }
  
  
  public function getAllProducts(){
  
    $id_lang=$this->context->language->id;
	
	$productObj = new Product();
    $products = $productObj -> getProducts($id_lang, 0, 0, 'id_product', 'DESC' );
	
	
	return $products;  
  
  }
  
  
  
  
  
  
 
}