{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<table id="payment-tab" width="100%">
    <tr>
        <td class="payment center small grey bold" width="44%">{l s='Payment Method' d='Shop.Pdf' pdf='true'}</td>
        <td class="payment left white" width="56%">
            <table width="100%" border="0">
                {foreach from=$order_invoice->getOrderPaymentCollection() item=payment}

                    {if $payment->payment_method eq 'Platba v hotovosti (dobírka)'}
                        <tr>
                            <td class="right small">{$payment->payment_method}</td>
                            <td class="right small">{displayPrice currency=$payment->id_currency price=$payment->amount}</td>
                        </tr>
                    {else}
                        <tr>
                            <td class="right small">Platba převodem</td>
                            <td class="right small">{displayPrice currency=$payment->id_currency price=$payment->amount}</td>
                        </tr>
                        <tr>
                            <td class="right small">Na účet</td>
                            <td class="right small">2241315002/5500</td>
                        </tr>
                        <tr>
                            <td class="right small">Variabilní symbol</td>
                            <td class="right small">{$title|escape:'html':'UTF-8'|replace:'#IN':''}</td>
                        </tr>
                    {/if}

                {/foreach}
            </table>
        </td>
    </tr>
</table>
