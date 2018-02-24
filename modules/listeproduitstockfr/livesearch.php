<?php
/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/
// @TODO Find the reason why the blockcart.php is includ multiple time

include_once('./../../config/config.inc.php');
include_once('./../../init.php');
//include_once('./../../classes/AttributeGroup.php');
    $id_lang=(int)Configuration::get('PS_LANG_DEFAULT');
	

if($_POST)
{
$q=$_POST['search'];
//$sql_res=mysql_query("select *from ps_product_lang where name like '%$q%' and id_lang=$id_lang order by id_product LIMIT 5");


$sql='SELECT *FROM `'._DB_PREFIX_.'product` p
		LEFT JOIN  `'._DB_PREFIX_.'product_lang` pl ON pl.`id_product` = p.`id_product`	AND pl.`id_lang`='.$id_lang.'	
		WHERE pl.`name` LIKE \'%'.pSQL($q).'%\'
        ';
if (Context::getContext()->cookie->shopContext)
			$sql.= ' AND pl.`id_shop` = '.(int)Context::getContext()->shop->id;
            $results = Db::getInstance()->ExecuteS($sql);

            
        

foreach ($results as $product)
{

?>


    <tr>
      <th scope="row"><?php$product['id_product'];?></th>
      <td><?php echo $product['name'];?></td>
      <td><?php echo $product['id_product'];?></td>
      <td><?php echo $product['condition'];?></td>
	  <td><?php StockAvailable::getQuantityAvailableByProduct($product['id_product'], 0);?></td>
	  <td><?php echo $product['price'];?></td>
	  <td>	  
	  <input type="text"  name="ordered_qty"  value="<?php echo $product['minimal_quantity'];?>">
	  </td>
    </tr>
<?php 

}
}
?>  
  

