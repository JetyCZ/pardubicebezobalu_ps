<?php
/*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Customer;

require_once _PS_MODULE_DIR_ . 'ps_customtext/classes/CustomText.php';

class Ps_Customtext extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'ps_customtext';
        $this->author = 'PrestaShop';
        $this->version = '2.0.0';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Custom text blocks', array(), 'Modules.Customtext.Admin');
        $this->description = $this->trans('Integrates custom text blocks anywhere in your store front', array(), 'Modules.Customtext.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:ps_customtext/ps_customtext.tpl';
    }

    public function install()
    {
        return parent::install() &&
            $this->installDB() &&
            $this->registerHook('displayHome') &&
            $this->registerHook('customCMS') &&
            $this->installFixtures();
    }

    public function getJavascript()
    {
        $javascript = <<<'EOD'
<script type='text/javascript'>
    function updateTotalPrice(productId) {
        var quantity = document.getElementById("productQuantity" + productId).value;
        var productPriceHiddenId = "productPrice" + productId;
        var totalPriceId = "totalPrice" + productId;
        var productPriceHidden = document.getElementById(productPriceHiddenId);
        var totalPriceSpan = document.getElementById(totalPriceId);
        var price = productPriceHidden.value;
        totalPriceSpan.innerText = Math.round(price*quantity * 100) / 100 + ',- Kč';
    }
    function updateTotalPriceFruitKs(productId, gramPerKs) {
        var quantity = document.getElementById("productQuantity" + productId + 'Ks').value;
        var productPriceHiddenId = "productPrice" + productId;
        var totalPriceId = "totalPrice" + productId;
        var productPriceHidden = document.getElementById(productPriceHiddenId);
        var pricePerGram = productPriceHidden.value;
        var totalWeight = gramPerKs * quantity;
        
        var totalPriceSpan = document.getElementById(totalPriceId);
        // totalPriceSpan.innerText = Math.round(pricePerGram*totalWeight * 100) / 100 + ',- Kč';
        var labelKc = Math.round(pricePerGram*totalWeight * 100) / 100 + ',- Kč';
        
        document.getElementById("productQuantity" + productId).value = totalWeight;
        totalPriceSpan.innerText = labelKc + '; ' + Math.round(totalWeight*100) / (100*1000) + ' Kg';
    }
</script>
EOD;
        return $javascript;
    }

    public function hookcustomCMS($params)
    {
        if (strpos($this->smarty->getTemplateVars("request_uri"), 'zrychlena-objednavka-zbozi') !== false) {

            $result = "";

            $javascript = $this->getJavascript();
            $result .= $javascript;


            // try {
            $context = Context::getContext();
            $lang = (int)$context->language->id;


            $rootCat = Category::getRootCategory();

            $children = Category::getChildren($rootCat->id_category, $lang);
            $result .= "<form method='POST'>";
            $formPosted = !empty($_POST);
            $result .= "<table style='background-color:#FEFEFE;' border='1'><tr style='background-color:#D0FFD0;'>
                    <th>Zboží</th>
                    <th>Cena za jednotku <br>vč. DPH</th>
                    <th>Objednané množství</th>
                    <th>Cena za objednáno</th>
                    <th>Info</th>
                    </tr>";


            $productIds = array();
            foreach ($children as $childCat) {

                $catName = $childCat["name"];

                $idCategory = $childCat["id_category"];
                $cat = new Category($idCategory);
                $products = $cat->getProducts($lang, 0, 1000);

                $isFruit = ($idCategory == 18);
                if (!($catName === "BIO") && count($products) > 0) {
                    $result .= "<tr>";
                    $catLink = "/" . $idCategory . "-" . $childCat["link_rewrite"];

                    $result .= "<td colspan='6' style='background-color:#F0FFF0;'><b>" .
                        "<a href='" . $catLink . "' target='_new'>" .
                        $catName .
                        "</a>" .
                        "</b></td>";
                    $result .= "</tr>";
                    foreach ($products as $product) {
                        $idProduct = $product["id_product"];

                        if (!array_key_exists($idProduct, $productIds)) {
                            $result .= "<tr>";
                            $productName = $product["name"];
                            $price = $product["price"];
                            $link = $product["link"];
                            $result .= "<td style='padding-left:20pt'>" .
                                "<a href='" . $link . "' target='_new'>" .
                                $productName .
                                "</a>" .
                                "</td>";

                            $unitX = "";
                            $help = "";
                            $zaLabelUnit = "";
                            $zaLabelPrice = "";
                            $isWeightedKs = false;
                            $vaziZhrubaLabel = "váží zhruba";
                            $vaziZhrubaPos = strpos($productName, $vaziZhrubaLabel);
                            if (strpos($productName, 'stáčený produkt') != false) {
                                $unitX = "ml ";
                                $help = "(1000 = 1 litr)";
                                $zaLabelUnit = "za litr";
                                $zaLabelPrice = $price*1000;
                            } elseif (strpos($productName, 'na váhu') != false) {
                                $unitX = "g ";
                                $help = "(1000 = 1 kg)";

                                $pricePer100g = $price * 100;
                                if ($pricePer100g<10) {
                                        $zaLabelPrice = $price*1000;
                                        $zaLabelUnit = "za Kg";
                                } else {
                                    $zaLabelPrice = $pricePer100g;
                                    $zaLabelUnit = "za 100 gramů";
                                }

                            } else if ($vaziZhrubaPos != false) {
                                $unitX = "ks ";
                                $help = "(kusové zboží)";
                                $zaLabelUnit = "za kus";
                                $zaLabelPrice = $price;

                                $isWeightedKs = true;
                            } else {
                                $unitX = "ks ";
                                $help = "(kusové zboží)";
                                $zaLabelUnit = "za&nbsp;kus";
                                $zaLabelPrice = $price;
                            }

                            $result .= "<td nowrap='nowrap'>";
                            if (!$isFruit && !$isWeightedKs) {
                                $result .= $zaLabelPrice . ",- Kč&nbsp;<span style='color: #A0A0A0;'>" . $zaLabelUnit . "</span>";
                            } else {
                                $result .= ($price * 1000) . ",- Kč&nbsp;<span style='color: #A0A0A0;'>za Kg</span>";
                            }
                            $result .= "</td>";

                            $result .= "<input type='hidden' id='productPrice" . $idProduct . "' value='" . $price . "'></input>";

                            $fieldName = "productQuantity" . $idProduct;
                            $result .= "<td nowrap='nowrap'>";

                            $updateFunction = "updateTotalPrice(" . $idProduct . ")";

                            if ($isWeightedKs) {
                                $gramPerKs = substr($productName, $vaziZhrubaPos + strlen($vaziZhrubaLabel), strlen($productName) - strlen($vaziZhrubaLabel) - $vaziZhrubaPos - 1);

                                $updateFunctionFruitKs = "updateTotalPriceFruitKs(" . $idProduct . "," . $gramPerKs . ")";
                                $result .= "<input style='width:100px' oninput='" . $updateFunctionFruitKs . "' onchange='" . $updateFunctionFruitKs . ")' type='number' value='0' name='" . $fieldName . "Ks' id='" . $fieldName . "Ks' min=0>";
                                $result .= "<input type='hidden' value='0' name='" . $fieldName . "' id='" . $fieldName . "'>";
                                $result .= " " . $unitX;
                                $result .= "<span style='color: #C0C0C0;'>";
                                $result .= $help;
                                $result .= "</span>";
                            } else if (!$isFruit) {
                                $result .= "<input style='width:100px' oninput='" . $updateFunction . "' onchange='" . $updateFunction . ")' type='number' value='0' name='" . $fieldName . "' id='" . $fieldName . "' min=0>";

                                $result .= " " . $unitX;
                                $result .= "<span style='color: #C0C0C0;'>";
                                $result .= $help;
                                $result .= "</span>";
                            } else {
                                        $result .= "<select style='width:150px' oninput='updateTotalPrice(" . $idProduct . ")' onchange='updateTotalPrice(" . $idProduct . ")' name='" . $fieldName . "' id='" . $fieldName . "'>";

                                        $result .= "<option value='0'>Vybrat hmotnost</option>";
                                        $result .= "<option value='100'>100 g</option>";
                                        $result .= "<option value='200'>200 g</option>";
                                        $result .= "<option value='300'>300 g</option>";
                                        $result .= "<option value='400'>400 g</option>";
                                        $result .= "<option value='500'>0.5 Kg</option>";
                                        $result .= "<option value='1000'>1 Kg</option>";
                                        $result .= "<option value='2000'>2 Kg</option>";
                                        $result .= "<option value='3000'>3 Kg</option>";
                                        $result .= "<option value='4000'>4 Kg</option>";
                                        $result .= "<option value='5000'>5 Kg</option>";
                                        $result .= "<option value='5000'>7 Kg</option>";
                                        $result .= "<option value='5000'>10 Kg</option>";
                                        $result .= "<option value='5000'>15 Kg</option>";
                                        $result .= "<option value='5000'>20 Kg</option>";
                                        $result .= "<option value='2500'>25 Kg</option>";
                                        $result .= "<option value='50000'>50 Kg</option>";
                                        $result .= "</select>";
                            }

                            $result .= "</td>";
                            $result .= "<td><span id='totalPrice" . $idProduct . "'></span></td>";
                            $info = "&nbsp;";
                            if ($formPosted) {
                                $quantity = $_POST[$fieldName];
                                if (isset($quantity) && $quantity > 0) {
                                    $info = "Do košíku přidáno: " . $quantity;
                                }
                            }
                            $result .= "<td>" . $info . "</td>";


                            $productIds[$idProduct] = 1;
                            $result .= "</tr>";
                        }

                    }
                }


            }
            $result .= "<tr ><td style='padding-top: 30pt;' colspan='6' align='center'>" .
                "<input name='bulkAddToCartButton' style='font-size: 150%;padding:20pt;background-color:#F0FFF0;' value='Vložit zboží všechno najednou do košíku' type='submit'/>" .
                "</td> </tr>";
            $result .= "</table>";


            $result .= "</form>";

            return $result;

            /*
            } catch (Exception $e) {
                var_dump($e);
                return "Došlo k chybě při zobrazení stránky pro zrychlenou objednávku zboží, prosím přidejte zboží po jednom.";
            }*/

        } else {
            return "";
        }
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallDB();
    }

    public function installDB()
    {
        $return = true;
        $return &= Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'info` (
                `id_info` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned DEFAULT NULL,
                PRIMARY KEY (`id_info`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;'
        );

        $return &= Db::getInstance()->execute('
                CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'info_lang` (
                `id_info` INT UNSIGNED NOT NULL,
                `id_lang` int(10) unsigned NOT NULL ,
                `text` text NOT NULL,
                PRIMARY KEY (`id_info`, `id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8 ;'
        );

        return $return;
    }

    public function uninstallDB($drop_table = true)
    {
        $ret = true;
        if ($drop_table) {
            $ret &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'info`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'info_lang`');
        }

        return $ret;
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('saveps_customtext')) {
            if (!Tools::getValue('text_' . (int)Configuration::get('PS_LANG_DEFAULT'), false)) {
                $output = $this->displayError($this->trans('Please fill out all fields.', array(), 'Admin.Notifications.Error')) . $this->renderForm();
            } else {
                $update = $this->processSaveCustomText();

                if (!$update) {
                    $output = '<div class="alert alert-danger conf error">'
                        . $this->trans('An error occurred on saving.', array(), 'Admin.Notifications.Error')
                        . '</div>';
                }

                $this->_clearCache($this->templateFile);
            }
        }

        return $output . $this->renderForm();
    }

    public function processSaveCustomText()
    {
        $info = new CustomText(Tools::getValue('id_info', 1));

        $text = array();
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $text[$lang['id_lang']] = Tools::getValue('text_' . $lang['id_lang']);
        }

        $info->text = $text;

        if (Shop::isFeatureActive() && !$info->id_shop) {
            $saved = true;
            $shop_ids = Shop::getShops();
            foreach ($shop_ids as $id_shop) {
                $info->id_shop = $id_shop;
                $saved &= $info->add();
            }
        } else {
            $saved = $info->save();
        }

        return $saved;
    }

    protected function renderForm()
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->trans('CMS block', array(), 'Modules.Customtext.Admin'),
            ),
            'input' => array(
                'id_info' => array(
                    'type' => 'hidden',
                    'name' => 'id_info'
                ),
                'content' => array(
                    'type' => 'textarea',
                    'label' => $this->trans('Text block', array(), 'Modules.Customtext.Admin'),
                    'lang' => true,
                    'name' => 'text',
                    'cols' => 40,
                    'rows' => 10,
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            ),
            'buttons' => array(
                array(
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                    'title' => $this->trans('Back to list', array(), 'Admin.Actions'),
                    'icon' => 'process-icon-back'
                )
            )
        );

        if (Shop::isFeatureActive() && Tools::getValue('id_info') == false) {
            $fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso_theme'
            );
        }


        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = 'ps_customtext';
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }

        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'saveps_customtext';

        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm(array(array('form' => $fields_form)));
    }

    public function getFormValues()
    {
        $fields_value = array();
        $id_info = 1;

        foreach (Language::getLanguages(false) as $lang) {
            $info = new CustomText((int)$id_info);
            $fields_value['text'][(int)$lang['id_lang']] = $info->text[(int)$lang['id_lang']];
        }

        $fields_value['id_info'] = $id_info;

        return $fields_value;
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('ps_customtext'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('ps_customtext'));
    }

    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $sql = 'SELECT r.`id_info`, r.`id_shop`, rl.`text`
            FROM `' . _DB_PREFIX_ . 'info` r
            LEFT JOIN `' . _DB_PREFIX_ . 'info_lang` rl ON (r.`id_info` = rl.`id_info`)
            WHERE `id_lang` = ' . (int)$this->context->language->id . ' AND  `id_shop` = ' . (int)$this->context->shop->id;

        return array(
            'cms_infos' => Db::getInstance()->getRow($sql),
        );
    }

    public function installFixtures()
    {
        $return = true;
        $tab_texts = array(
            array(
                'text' => '<h3>Custom Text Block</h3>
<p><strong class="dark">Lorem ipsum dolor sit amet conse ctetu</strong></p>
<p>Sit amet conse ctetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit.</p>'
            ),
        );

        $shops_ids = Shop::getShops(true, null, true);

        foreach ($tab_texts as $tab) {
            $info = new CustomText();
            foreach (Language::getLanguages(false) as $lang) {
                $info->text[$lang['id_lang']] = $tab['text'];
            }
            foreach ($shops_ids as $id_shop) {
                $info->id_shop = $id_shop;
                $return &= $info->add();
            }
        }

        return $return;
    }


    public function fetch($templatePath, $cache_id = null, $compile_id = null)
    {
        return parent::fetch($templatePath, $cache_id, $compile_id); // TODO: Change the autogenerated stub
    }
}
