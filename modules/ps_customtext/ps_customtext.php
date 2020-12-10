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
require_once _PS_ROOT_DIR_ . '/classes/custom/CustomUtils.php';
require_once _PS_ROOT_DIR_ . '/classes/custom/CustomInventory.php';
require_once _PS_ROOT_DIR_ . '/classes/jety/Cron/CronExpression.php';

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
<link rel="stylesheet" href="/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"></link>
<script type='text/javascript' src="/js/mapping.js?v=14"></script>
<script type='text/javascript' src="/admin313uriemy/mapping.php?v=6[inventoryUrlParam]"></script>
<script src="//code.responsivevoice.org/responsivevoice.js?key=hLFPNIz1"></script>

EOD;
        $inventoryUrlParam = "";
        if (isset($_GET['id_inventory'])) {
            $inventoryUrlParam = "&id_inventory=".$_GET['id_inventory'];
        }
        $javascript = str_replace( "[inventoryUrlParam]",$inventoryUrlParam,$javascript);
        return $javascript;
    }


    public function hookcustomCMS($params)
    {

        if (strpos($this->smarty->getTemplateVars("request_uri"), 'zrychlena-objednavka-zbozi') !== false) {
            try {

                $deliveryToHome = isset($_GET['deliveryToHome']) && ($_GET['deliveryToHome'] == 'true');
                $result = "";
                $isAdmin = CustomUtils::isAdmin($this->context);
                $javascript = $this->getJavascript();
                $result .= $javascript;


                // try {
                $context = Context::getContext();
                $lang = (int)$context->language->id;


                $rootCat = Category::getRootCategory();

                $children = Category::getChildren($rootCat->id_category, $lang);

                $result .= "<form method='POST'>";
                // $result .= $this->context->customer->email;

                $formPosted = !empty($_POST);

                // http://jsbin.com/xecacojave/edit?html,js,output

                $result .= "<div style='position:fixed;top:0pt;left:0pt;' >";
                $result .= "<table>";
                $result .= "<tr>";
                $result .= "<td>";
                $result .= "<h2 style='background-color:#FFF0F0;color:black;'>Zaplatit: <span id='whatToPayPrice'>0,- Kč</span></h2>";
                $result .= "<h4 style='background-color:#FFF0F0;color:black;'>Za zboží: <span id='cartTotalPrice'>0,- Kč</span></h4>";
                $result .= "<h4 style='background-color:#FFF0F0;color:black;'>Vrácené obaly: -<span id='returnedBottlesPrice'>0,- Kč</span></h4>";
                $result .= "</td>";
                $result .= "</tr>";
                $result .= "</table>";
                if ($isAdmin) {
                    $result .= "<h2 style='background-color:#FFF0FF;' id='btnAddAllTopLeft'>Vložit vše</h2>";
                    $result .= "<a target='_new' href='http://pardubicebezobalu.cz/admin313uriemy/sells.php'><h4 style='background-color:#FFF0FF;' >Co objednat</h4></a>";
                    $result .= "<a target='_new' href='http://pardubicebezobalu.cz/admin313uriemy/supply.php'><h4 style='background-color:#FFF0FF;' >Objednávky dle dodavatelů</h4></a>";
                    $result .= "<a target='_new' href='http://bezobalu.herokuapp.com'><h4 style='background-color:#FFF0FF;' >Ceníky dodavatelů <br>(info@pardubicebezobalu.cz)</h4></a>";
                }
                $result .= "</div>";


                $result = CustomUtils::addDeliveryToHomeNote($result, $deliveryToHome);

                $invTable = new CustomInventory();

                $result .= "<table style='background-color:#FEFEFE;' border='1'><tr style='background-color:#D0FFD0;'>
                    <th>Zboží</th>
                    <th>Cena za jednotku <br>vč. DPH</th>
                    <th>Objednané množství</th>
                    <th>Cena za objednáno</th>
                    <th>Info</th>
                    
                    </tr>";


                $supplierCrons = Db::getInstance()->executeS("select * from " . _DB_PREFIX_ . "jety_supplier_cron");
                $bottles = Db::getInstance()->executeS("select * from " . _DB_PREFIX_ . "jety_bottled_products");
                $bottleMap = array();
                $bottleIds = array();
                $bottleJs = '<script language="JavaScript">let bottledProducts=[];';
                foreach ($bottles as $bottle) {
                    $idBottle = $bottle["id_product_bottle"];
                    $idProductInBottle = $bottle["id_product"];
                    $bottleMap[$idProductInBottle] = $idBottle;
                    $bottleIds[$idBottle] = true;
                    $bottleJs .= "bottledProducts[".$idProductInBottle."]=".$idBottle.";";
                }
                $bottleJs .= "</script>\n";
                $result .= $bottleJs;

                $productIds = array();
                $cats = "var map = {";
                $catProductCounter = 1;
                $resultAllCategories = "";
                $resultBottles = "";
                foreach ($children as $childCat) {


                    $catName = $childCat["name"];

                    $idCategory = $childCat["id_category"];
                    if ($idCategory == 28 && !$isAdmin) {
                        continue;
                    }
                    $cat = new Category($idCategory);
                    $products = $cat->getProducts($lang, 0, 1000);

                    $isNoDeliveryToHomeCat = ($idCategory == 23 || $idCategory == 17);
                    $productsOneCategory = "";

                    $hideBecauseOfDelivery = !$isAdmin && ($deliveryToHome && $isNoDeliveryToHomeCat);

                    if (
                        !$hideBecauseOfDelivery &&
                        !($catName === "BIO") && count($products) > 0
                    ) {
                        $isBottle = ($catName === "Obaly");
                        if ($isBottle && !$isAdmin) continue;
                        foreach ($products as $product) {
                            $oneProduct = $this->displayOneProduct($product, $productIds, $catProductCounter, $cats, $isAdmin, $invTable, $supplierCrons, $formPosted, $productsOneCategory, $isBottle);
                            $idProduct = $product["id_product"];
                            $productIds[$idProduct] = 1;
                            $productsOneCategory .= $oneProduct;
                        }

                        if (strlen($productsOneCategory) > 0) {
                            $resultOneCategory = "<tr>";

                            $catLink = "/" . $idCategory . "-" . $childCat["link_rewrite"];
                            $resultOneCategory .= "<td colspan='6' style='background-color:#F0FFF0;'><b>" .
                                "<a href='" . $catLink . "' target='_new'>" .
                                $catName .
                                "</a>" .
                                "</b>";


                            if ($isNoDeliveryToHomeCat) {
                                $resultOneCategory .=
                                    '<div style="font-size: 120%;">';
                                $resultOneCategory .= '<span class="glyphicon glyphicon-comment"></span>&nbsp;';
                                $resultOneCategory .= '<b>Poznámka: </b>';
                                $resultOneCategory .=
                                    'Produkty v této kategorii nelze přepravovat, vyberte je prosím, jen pokud objednávate pro osobní odběr na prodejně.';
                            }
                            $resultOneCategory .= "</td>";

                            $resultOneCategory .= "</tr>";
                            $resultOneCategory .= $productsOneCategory;
                            if ($isBottle) {
                                $resultBottles = $resultOneCategory;
                            } else {
                                $resultAllCategories .= $resultOneCategory;
                            }
                        }
                    }
                }
                $result .= $resultBottles;
                $result .= $resultAllCategories;

                $result .= "<tr ><td style='padding-top: 30pt;' colspan='6' align='center'>" .
                    "<input id='bulkAddToCartButton'
                    name='bulkAddToCartButton' style='font-size: 150%;padding:20pt;background-color:#F0FFF0;' value='Vložit zboží všechno najednou do košíku' type='submit'/>" .
                    "</td> </tr>";
                $result .= "</table>";


                $result .= "</form>";
                // $result.="<textarea rows=1 cols=5>".$cats."</textarea>";
                $result .= "<input id='qrcode' name='qrcode''>";

                $formPosted = !empty($_POST);
                if ($formPosted && isset($_POST['bulkAddToCartButton'])) {
                    if ($isAdmin) {
                        $redirectUrl = '/objednávku';
                    } else {
                        $redirectUrl = '/kosik?action=show';
                    }
                    $result .= '<a href="http://doc.prestashop.com/display/PS17/Installing+PrestaShop?utm_source=html_installer">You will be redirected to the getting started guide</a>                
                <script type="text/javascript">document.location.href = \'' . $redirectUrl . '\';</script>';
                }

                // return $result.$invTable->outputHtml();
                return $result;


            } catch (Exception $e) {
                var_dump("<!--" . $e . "-->");
                return "Došlo k chybě při zobrazení stránky pro zrychlenou objednávku zboží, prosím přidejte zboží po jednom.";
            }

        } else {
            return "";
        }
    }

    public function toGraySpan($text)
    {
        return "\n<span style='color: #909090;'>" . $text . "</span>";
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

    public function infoLabel($text, $label): string
    {
        return "\n<div title='" . $label . "'>"
            . "\n\t<i class=\"glyphicon glyphicon-info-sign\"></i> "
            . "\n" . $text
            . " \n</div>";
    }


    public function calculateStockLabel($supplierCrons, $product, $stockLabel)
    {
        foreach ($supplierCrons as $supplierCronDbRow) {

            $productIdSupplier = $product["id_supplier"];
            $cronIdSupplier = $supplierCronDbRow["id_supplier"];
            try {
                if ($cronIdSupplier == $productIdSupplier) {
                    $deliveryInfo = CustomUtils::calculateDeliveryInfo($supplierCronDbRow);

                    $orderDateStr = $deliveryInfo->orderDateStr();
                    $deliveryDateStr = $deliveryInfo->deliveryDateStr();
                    $infoLabel = "Zboží lze přidat do košíku, i když ho nemáme skladem. Pravidelně objednáváme u dodavatele.";


                    if ($deliveryDateStr == null) {
                        $infoText = "Zboží už jsme objednali, termín další objednávky zatím dosud není stanoven.";
                    } else {
                        $infoText = "Zboží budeme objednávat "
                            . $orderDateStr
                            . ", k vyzvednutí bude <b>"
                            . $deliveryDateStr
                            . "</b>";
                    }

                    $stockLabel .= $this->infoLabel($infoText, $infoLabel);
                    break;
                }
            } catch (Exception $e) {
                var_dump($e);
            }

        }
        return $stockLabel;
    }

    /**
     * @param $product
     * @param array $productIds
     * @param int $catProductCounter
     * @param string $cats
     * @param bool $isAdmin
     * @param CustomInventory $invTable
     * @param $supplierCrons
     * @param bool $formPosted
     * @param string $resultOneCategory
     * @return string
     */
    public function displayOneProduct($product, array $productIds, int $catProductCounter, string $cats, bool $isAdmin, CustomInventory $invTable, $supplierCrons, bool $formPosted, string $resultOneCategory,$isBottle): string
    {
        $idProduct = $product["id_product"];

        $quantity = $product["quantity"];
        $outOfStock = (int)$product["out_of_stock"];
        $linkRewrite = $product["link_rewrite"];
        $shortUrl = $product["id_product"] . "-" . $linkRewrite;
        $resultOneProduct = "";

        if (
            !array_key_exists($idProduct, $productIds) &&
            (
                ($outOfStock < 2) ||
                ((int)$quantity > 0)
            )
        ) {

            $productQuantityIdAttr = " id='productQuantity_" . $shortUrl . "' ";

            // 1: '133-merunky-cele-na-vahu',
            $cats .= "\n" . ($catProductCounter++) . ": '" . $shortUrl . "',";

            $resultOneProduct .= "\n<tr>";
            $resultOneProduct .= '<a name="' . $linkRewrite . '"></a>';

            $productName = $product["name"];
            $price = $product["price"];
            $link = $product["link"];


            $resultOneProduct .= "\n<td style='padding-left:20pt'>" .
                "<a id='productLabel" . $idProduct . "' href='" . $link . "' target='_new'>" .
                $productName .
                "</a>";

                        if ($isAdmin) {
                            $resultOneProduct .= ' |&nbsp;' .
                                CustomUtils::ordersWithProductLink($idProduct);
                            $resultOneProduct .= ' |&nbsp;' .
                                CustomUtils::productLink($idProduct);
                            $resultOneProduct .= ' |&nbsp;' .
                                CustomUtils::supplyLink($productName);
                            $resultOneProduct .= $invTable->links($idProduct, $shortUrl);
                        }
                        $resultOneProduct .= "</td>";


                        $priceInfo = null;
                        $priceInfo = CustomUtils::priceInfo($productName, $price);

                        $resultOneProduct .= "\n<td nowrap='nowrap'>";
                        $resultOneProduct .= $priceInfo->pricePerUnitLabel();
                        $resultOneProduct .= "</td>";
                        $resultOneProduct .= "<input type='hidden' id='productPrice" . $idProduct . "' value='" . $price . "'></input>";

                        $fieldName = "productQuantity" . $idProduct;
                        $resultOneProduct .= "\n<td nowrap='nowrap'>";
                        $maxAttribute = " max=" . $quantity;
                        $stockLabel = "";
                        $inStoreLabel = $this->infoLabel("Skladem: " . $priceInfo->quantityToAmountAndUnit($quantity, 1),
                            "Množství zboží, které máme fyzicky v prodejně v Brozanech k volnému prodeju. Objednáním přes e-shop si zboží rezervujete pro sebe.");

                        $invTable->invRow($quantity, $price, $idProduct, $productName, $shortUrl);

                        if (!$isBottle) {
                            if ($outOfStock == 1) {
                                $maxAttribute = "";
                                if ($quantity > 0) {
                                    $stockLabel .= $inStoreLabel;
                                } else {
                                    $stockLabel .= $this->infoLabel("Není skladem, lze objednat", "Toto zboží nemáme fyzicky v prodejně v Brozanech na skladě, můžeme jej ale objednat od dodavatele.");
                                    if ($quantity < 0) {

                                        $quantityToOrder = $priceInfo->quantityToAmountAndUnit($quantity, -1);
                                        $stockLabel .= $this->infoLabel("Budeme objednávat: " . $quantityToOrder,
                                            "Informace o tom, kolik budeme objednávat v příští objednávce. Jde o množství, co zákazníci objednali mínus kolik máme na skladě.");
                                    }
                                }

                                $stockLabel = $this->calculateStockLabel($supplierCrons, $product, $stockLabel);

                            } else {
                                $stockLabel = $inStoreLabel;
                            }
                        }

                        $quote = "'";


                        $updateProductPouredGramInputFunction = "";
                        if ($priceInfo->isPoured) {
                            $updateProductPouredGramInputFunction = '; updateProductPouredGramInput(' . $idProduct . ', ' . $quote . $shortUrl . $quote . ');"';
                        }

                        $updateTotalPriceFunction = '"updateTotalPrice(' . $idProduct . ');' . $updateProductPouredGramInputFunction . '"';
                        $oninput = " oninput=" . $updateTotalPriceFunction;
                        $onchange = " onchange=" . $updateTotalPriceFunction;
                        $onchange = "";

                        if ($isBottle) {
                            $resultOneProduct.= "Vráceno kusů: "."<input class='returnedBottle' oninput='refreshTotalPrice()' type='number' value='0' id='returnedBottle".$idProduct."'>";
                        } else if ($priceInfo->isWeightedKs) {
                            $updateFunctionFruitKs = '"updateTotalPriceFruitKs(' . $idProduct . ',' . $priceInfo->gramPerKs . ', ' . $quote . $shortUrl . $quote . ')"';
                            $productQuantityKsIdAttr = " id='productQuantityKs_" . $shortUrl . "' ";
                            $resultOneProduct .= "<input " . $productQuantityKsIdAttr
                                . " class='quantity"
                                . "' style='width:100px' oninput=" . $updateFunctionFruitKs
                                . " onchange=" . $updateFunctionFruitKs . " type='number' value='0' name='"
                                . $fieldName . "Ks' min=0 " . $maxAttribute . ">";

                            if ($isAdmin) {
                                $type = "type='text' " . $oninput . $onchange;
                                $resultOneProduct .= " " . $priceInfo->unitX;
                                $resultOneProduct .= "&nbsp;<input " . $type . " value='0' name='" . $fieldName . "' " . $productQuantityIdAttr . ">";
                                $resultOneProduct .= " gramů";
                            } else {
                                $type = "type='hidden' ";
                                $resultOneProduct .= "<input " . $type . " value='0' name='" . $fieldName . "' " . $productQuantityIdAttr . ">";
                                $resultOneProduct .= " " . $priceInfo->unitX;
                            }


                        } else {

                            $appendKsClass = ($priceInfo->unitX == "ks ") ? " kusove-zbozi" : "";
                            $resultOneProduct .= "<input " . $productQuantityIdAttr . $oninput . $onchange
                                . " class='quantity"
                                . $appendKsClass
                                . "' style='width:100px' type='number' value='0' name='" . $fieldName
                                . "' min=0 " . $maxAttribute . ">";
                            $resultOneProduct .= " " . $priceInfo->unitX;


                            if ($priceInfo->isPoured && $isAdmin) {
                                $fieldNameWeightPoured = "productWeightPoured" . $idProduct;
                                $updateMlFunction = '"updateMililitersInput(' . $idProduct . ', ' . $quote . $shortUrl . $quote . ');"';
                                $oninputMl = " oninput=" . $updateMlFunction;
                                $onchangeMl = " onchange=" . $updateMlFunction;
                                $productPouredGramIdAttr = " id='productPouredGram_" . $shortUrl . "' ";

                                $resultOneProduct .= "<input " . $productPouredGramIdAttr . $oninputMl . $onchangeMl . " class='quantity' style='width:100px' type='number' value='0' name='" . $fieldNameWeightPoured . "' min=0 " . $maxAttribute . ">";
                                $resultOneProduct .= "&nbsp;g&nbsp;";
                            }

                        }
                        $resultOneProduct .= $this->toGraySpan($priceInfo->help);


                        $resultOneProduct .= "<br>" . $this->toGraySpan($stockLabel);
                        $resultOneProduct .= "</td>";
                        $resultOneProduct .= "\n<td><span id='totalPrice" . $idProduct . "'></span></td>";
                        $info = "&nbsp;";
                        if ($formPosted) {
                            $quantity = $_POST[$fieldName];
                            if (isset($quantity) && $quantity > 0) {
                                $info = "Do košíku přidáno: " . $quantity;
                            }
                        }
                        $resultOneProduct .= "\n<td>";
                        $resultOneProduct .= $info . "</td>";

            $resultOneProduct .= "</tr>";
        }
        return $resultOneProduct;
    }


}
