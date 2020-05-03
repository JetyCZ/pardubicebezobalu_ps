    // https://babylon.tierraverde.cz/index.php/s/HKYpUwoIg6au36t#pdfviewer
    var gramPerLitrMapping = {

        '52-praci-gel-z-mydlovych-orechu-bez-vune-staceny':1065,
        '54-praci-gel-z-mydlovych-orechu-se-silici-vavrinu-kubeboveho-staceny':1080,
        '58-gel-do-mycky-na-nadobi-z-mydlovych-orechu-staceny':1099,
        '59-gel-na-nadobi-staceny':1011,
        '60-wc-cistic-staceny':1025,
        '85-bio-olej-slunecnicovy-na-smazeni-a-peceni-5-l-staceny-produkt':896,
        '438-bio-olej-slunecnicovy-na-smazeni-a-peceni-5-l-staceny-produkt':878,
        '392-bzenecky-ocet-kvasny-staceny-produkt':1004,
        '62-avivaz-staceny-produkt':1000,
        '504-olej-repkovy-staceny-produkt':916

    };

    document.addEventListener("keydown", handleKeyDown);
    var readingPreffix = false;
    var readingData = false;
    var qrBufferPreffix='';
    var qrBufferData='';
    var lastInventoryProductId=-1;

    var ctrl

    function handleKeyDown(event) {
        var code = event.keyCode;
        var prevent = true;
        if (code==221) {
            readingPreffix = true;
            readingData = false;
            qrBufferPreffix='221_';
            qrBufferData='';
        } else if (
            !readingPreffix &&
            !readingData){
            prevent = false;
        } else if (code==13){
            readingPreffix = false;
            readingData = false;

            processQrData(event);

            qrBufferPreffix='';
            qrBufferData='';
        } else if (code==16 || code==17){ // Shift
            // Do nothing
        } else {
            if (readingPreffix) {
                qrBufferPreffix += event.keyCode+'_';
                if (
                    qrBufferPreffix=='221_67_48_' // CODE128
                    || qrBufferPreffix=='221_48_' // CODE128 with CTRL
                    || qrBufferPreffix=='221_69_48_' // Other format - milk
                    || qrBufferPreffix=='221_48_' // Other format - milk - with CTRL
                ) {
                    console.log('Preffix FOUND');
                    qrBufferPreffix = '';
                    readingPreffix = false;
                    readingData = true;
                } else {
                    console.log('Preffix NOT FOUND: ' + qrBufferPreffix);
                }
            } else if (readingData) {
                var key = event.key;
                if (event.keyCode>=49 && event.keyCode<=57)
                    key = (event.keyCode - 48)+'';
                else if (event.keyCode==48)
                    key = '0';
                else if (event.keyCode == 187|| event.keyCode == 189)
                    key = '_';
                qrBufferData += key;
                console.log('DATA APPEND: ' + qrBufferData);
            } else {
                prevent=false;
            }
        }
        if (prevent) event.preventDefault();
        console.log(event.key + ' = ' + event.keyCode);
    }

    let preffix = 'productQuantity';

    function activeElementIsProductQuantity() {
        return document.activeElement != null && document.activeElement.id.startsWith('productQuantity_');
    }


    function getIdInventory() {
        let query = window.location.search.substring(1);
        let urlParams = parse_query_string(query);
        let idInventory = urlParams['id_inventory'];
        return idInventory;
    }

    function processQrData(event) {

        let idInventory = getIdInventory();
        let inventory = (idInventory != null);
        if (inventory && event.ctrlKey) {
            setInventoryQuantity(true);
        }

        let activeElement = document.activeElement;
        console.log("processQrData: " + qrBufferData);
        var productId = map[qrBufferData];
        console.log('productId:' + productId);
        var activeName = null;
        var activeId = null;
        if (activeElement != null) {
            activeName = activeElement.name;
            activeId = activeElement.id;
        }

        console.log('activeName:' + activeName);
        if (productId != null) {
            let productLabelElem = document.getElementById('productLabel'+productId);
            let productNotVisibleOnPage = productLabelElem==null;

            if ((activeName != null && activeName == 's') ||
                productNotVisibleOnPage) {
                let message;

                let productLabel = '';
                if (!productNotVisibleOnPage) {
                    productLabel = productLabelElem.text;
                }
                if (productNotVisibleOnPage) {
                    message = 'Čárový kód je namapován na produkt s id ' +productId + ', který již na Zrychlené objednávce není zobrazen. Chcete zrušit toto mapování čárového kódu ' + qrBufferData + ' ?';
                } else {

                    message = 'Chcete zrušit mapování čárového kódu ' + qrBufferData + ' na produkt ' + productLabel + ' ?';
                }

                if (confirm(message)) {
                    delete map[qrBufferData];
                    let deleteUrl = "/admin313uriemy/mapping.php?qrcode=" + qrBufferData + "&delete=true";
                    $.get(deleteUrl, function (data) {
                    });
                }
            } else if (event.ctrlKey) {

                    delete map[qrBufferData];
                    let deleteUrl = "/admin313uriemy/mapping.php?qrcode=" + qrBufferData + "&delete=true";
                    $.get(deleteUrl, function (data) {});
                    let remapMessage='Mapování zrušeno';
                    if (activeElement != null && activeId.startsWith('productQuantity_')) {

                        var productId = activeName.substring(preffix.length);
                        let productLabelElem = document.getElementById('productLabel'+productId);
                        map[qrBufferData] = productId;
                        let addUrl = "/admin313uriemy/mapping.php?qrcode=" + qrBufferData + "&idproduct=" + productId;
                        $.get(addUrl, function (data) {});
                        remapMessage = 'Přemapováno na ' + productLabelElem.text;
                    }
                    responsiveVoice.speak(remapMessage);


                /*
                Open product in admin
                let productLink = document.getElementById('productLink_' + productId);
                if (productLink != null) {
                    window.open(productLink.getAttribute('href'), '_blank');
                }
                 */
            } else {
                console.log('Saying id:' + '#productLabel' + productId);
                console.log('Saying text:' + $('#productLabel' + productId).text());
                var labelToSay = $('#productLabel' + productId).text().replace(' - na váhu', '').replace(' - stáčený produkt')
                var toSay = labelToSay;

                let shouldIncreaseByOne = isKsProduct(productId);
                focusQuantity(productId, shouldIncreaseByOne);

                try {
                    if (!shouldIncreaseByOne) {
                        $.get("/vaha.php", function (data) {
                            if (data != -1) {
                                toSay += " " + data + " gramů";
                                var input = productQuantityJQueryObj(productId);

                                if (!inventory) {
                                    input.val(data);
                                    updateTotalPrice(productId);
                                } else {
                                    callInventoryPHPUrl(productId, idInventory, data, input, false);
                                    toSay += ' přidáno do inventury';
                                }

                            }
                            responsiveVoice.speak(toSay);
                        });
                    } else {
                        responsiveVoice.speak(toSay);
                    }
                } catch (e) {
                    console.debug("Error trying to output weight " + e);
                    responsiveVoice.speak("Chyba váhy.");
                }

            }
        } else {

            var msg = 'Neznámý produkt (čár. kód:' + qrBufferData + '). ';


            if (activeElementIsProductQuantity()) {
                var productId = activeName.substring(preffix.length);
                let productLabelElem = document.getElementById('productLabel'+productId);
                if (confirm(msg + ' Chcete ho namapovat na ' + productLabelElem.text + '?')) {
                    map[qrBufferData] = productId;
                    let addUrl = "/admin313uriemy/mapping.php?qrcode=" + qrBufferData + "&idproduct=" + productId;
                    $.get(addUrl, function (data) {});
                    if (isKsProduct(productId)) {
                        increaseByOne(productId);
                    }

                }
            } else {
                alert(msg + 'Pro namapování na produkt, prosím vlezte do obj. množství a pípněte znovu...')
            }


            // console.log(productId);
        }
    }

    function isKsProduct(productId) {
        let input = productQuantityJQueryObj(productId);
        if (input!=null) {
            return input.hasClass("kusove-zbozi");
        } else {
            alert('Produkt neexistuje na stránce: ' + productId);
            return false;
        }

    }


    function increaseByOne(productId) {
        let input = productQuantityJQueryObj(productId);
        if (input.hasClass('kusove-zbozi')) {
            input.val(parseInt(input.val()) + 1);
            updateTotalPrice(productId);
        }
    }

    function productQuantityJQueryObj(productId) {
        return $('[name="productQuantity' + productId + '"]')
    }

    function focusQuantity(productId, shouldIncreaseByOne = false) {
        let input = productQuantityJQueryObj(productId);
        if (input != null) {
            input.focus();
            if (shouldIncreaseByOne) {
                increaseByOne(productId);
            }
            input.select();
        }
    }

    document.addEventListener("DOMContentLoaded", function(event) {

        responsiveVoice.setDefaultVoice("Czech Female");

        $("#btnAddAllTopLeft").click(function(){
            document.getElementById('bulkAddToCartButton').click()
        });


        $('.quantity').focus(function () {
            var parentTr = $(this).closest('tr');
            parentTr.siblings().css('background-color', 'white');
            parentTr.css('background-color', 'yellow');
        });



    });

    var cart = {};

    function refreshTotalPrice() {
        var totalPrice = 0;
        let bottleCounts = new Object();
        for (var bottledProductId in bottledProducts) {
            var bottledProductQuantity = parseInt(quantityObj(bottledProductId).val());
            var bottledId = bottledProducts[bottledProductId];
            if (!(bottledId in bottleCounts)) {
                bottleCounts[bottledId] = 0;
            }
            bottleCounts[bottledId] += bottledProductQuantity;
        }
        updateTotalPriceDisabled = true;
        var totalReturnedBottlesPrice = 0;
        for (var bottleId in bottleCounts) {
            quantityObj(bottleId).val(bottleCounts[bottledId]);
            updateProductQuantity(bottledId, quantityObj(bottledId))
            let returnedCount = parseInt($('#returnedBottles' + bottledId).val());
            let returnedCost = returnedCount*pricePerUnit(bottledId);
            totalReturnedBottlesPrice += returnedCost;
        }
        updateTotalPriceDisabled = false;


        Object.keys(cart).forEach(function(productId) {
            totalPrice+=cart[productId];
        });
        totalPrice -= totalReturnedBottlesPrice;
        document.getElementById('cartTotalPrice').innerHTML =  Math.round(totalPrice * 100) / 100 + ',- Kč'
    }

    function pricePerUnit(productId) {
        var productPriceHiddenId = "productPrice" + productId;
        var productPriceHidden = document.getElementById(productPriceHiddenId);
        var result = productPriceHidden.value;
        return result;
    }

    function updateProductQuantity(productId, quantityJQueryObj) {
        var totalPriceId = "totalPrice" + productId;
        var pricePerUnitVal = pricePerUnit(productId);
        var totalPriceSpan = document.getElementById(totalPriceId);
        let priceForQuantity = pricePerUnitVal * quantityJQueryObj.val();
        totalPriceSpan.innerText = Math.round(priceForQuantity * 100) / 100 + ',- Kč';
        cart[productId] = priceForQuantity;
    }

    function updateTotalPriceQuantityElement(productId, quantityJQueryObj) {
        updateProductQuantity(productId, quantityJQueryObj);

        refreshTotalPrice();
    }


    var updateTotalPriceDisabled = false;

    function quantityObj(productId) {
        return $('[name="productQuantity' + productId + '"]');
    }

    function updateTotalPrice(productId) {
        if (updateTotalPriceDisabled) return;
        var quantityJQueryObj =  quantityObj(productId);
        updateTotalPriceQuantityElement(productId, quantityJQueryObj);
    }
    function updateTotalPriceFruitKs(productId, gramPerKs, shortUrl) {
        var quantityElem = document.getElementById("productQuantity_" + shortUrl);
        var quantityKs = document.getElementById("productQuantityKs_" + shortUrl).value;
        var productPriceHiddenId = "productPrice" + productId;
        var totalPriceId = "totalPrice" + productId;
        var productPriceHidden = document.getElementById(productPriceHiddenId);
        var pricePerGram = productPriceHidden.value;
        var totalWeight = gramPerKs * quantityKs;
        quantityElem.value = totalWeight;

        var totalPriceSpan = document.getElementById(totalPriceId);
        // totalPriceSpan.innerText = Math.round(pricePerGram*totalWeight * 100) / 100 + ',- Kč';
        var labelKc = Math.round(pricePerGram*totalWeight * 100) / 100 + ',- Kč';


        totalPriceSpan.innerText = labelKc + '; ' + Math.round(totalWeight*100) / (100*1000) + ' Kg';
        updateTotalPrice(productId);
    }


    function updateMililitersInput(productId, shortUrl) {
        var gramPerLitr = gramPerLitrMapping[shortUrl];
        if (gramPerLitr==null) gramPerLitr = 1000;
        if (gramPerLitr > 0) {
            var quantityElem = document.getElementById("productQuantity_" + shortUrl);
            var pouredGramElem = document.getElementById("productPouredGram_" + shortUrl);
            quantityElem.value = Math.round(pouredGramElem.value / (gramPerLitr/1000));
        }
        updateTotalPrice(productId);

    }
    function updateProductPouredGramInput(productId, shortUrl) {
        var tierreGramPerLitr = gramPerLitrMapping[shortUrl];
        if (tierreGramPerLitr > 0) {
            var quantityElem = document.getElementById("productQuantity_" + shortUrl);
            var pouredGramElem = document.getElementById("productPouredGram_" + shortUrl);
            pouredGramElem.value = Math.round(quantityElem.value * (tierreGramPerLitr/1000));
        }
    }


    function parse_query_string(query) {
        var vars = query.split("&");
        var query_string = {};
        for (var i = 0; i < vars.length; i++) {
            var pair = vars[i].split("=");
            var key = decodeURIComponent(pair[0]);
            var value = decodeURIComponent(pair[1]);
            // If first entry with this name
            if (typeof query_string[key] === "undefined") {
                query_string[key] = decodeURIComponent(value);
                // If second entry with this name
            } else if (typeof query_string[key] === "string") {
                var arr = [query_string[key], decodeURIComponent(value)];
                query_string[key] = arr;
                // If third or later entry with this name
            } else {
                query_string[key].push(decodeURIComponent(value));
            }
        }
        return query_string;
    }

    function setInventoryQuantity(reset=false) {
        if (activeElementIsProductQuantity()) {
            var productId = document.activeElement.name.substring(preffix.length);
            let productLabelElem = document.getElementById('productLabel' + productId);
            callInventoryPHPUrl(productId, getIdInventory(), document.activeElement.value, document.activeElement, reset);
        }else {
            alert('Prosím klepněte do množství produktu, jehož inventorní množství chcete nastavit');
        }
    }

    function callInventoryPHPUrlFromLink(shortUrl, productId, reset = true) {
        let elementId = preffix + '_' + shortUrl;
        let quantityElem = document.getElementById(elementId);
        callInventoryPHPUrl(productId, getIdInventory(), quantityElem.value, $('#' + elementId), reset);
    }

    function callInventoryPHPUrlReset(productId, shortUrl) {
        callInventoryPHPUrlFromLink(shortUrl, productId, true);
    }
    function callInventoryPHPUrlUpdate(productId, shortUrl) {
        callInventoryPHPUrlFromLink(shortUrl, productId, false);
    }
    function callInventoryPHPUrl(productId, idInventory, quantity, productQuantityElement,reset) {
        let dmt = $('#inventoryDmt').val();
        if (lastInventoryProductId != productId || dmt == '') {
            dmt = prompt('Prosím zadej DMT zboží:', '1/21');
            $('#inventoryDmt').val(dmt);
            lastInventoryProductId = productId;
        }
        if (dmt.split('/').length == 2) {
            dmt = '1/' + dmt;
        }
        $.get("/admin313uriemy/inventory.php?" +
            "id_inventory=" + idInventory + "&" +
            "id_product=" + productId + "&" +
            "quantity=" + quantity + "&" +
            "reset=" + reset + "&" +
            "dmt=" + dmt + "&", function (inventoryResponse) {
            productQuantityElement.val(inventoryResponse);
            updateTotalPrice(productId);
        });
    }

