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

    function processQrData(event) {
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
                        $.get("/vaha.php?t="+Date.now(), function (data) {
                            if (data != -1) {
                                var input = productQuantityJQueryObj(productId).val(100);
                                input.val(data);
                                updateTotalPrice(productId);
                                toSay += " " + data + " gramů";
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


            if (activeElement != null && activeId.startsWith('productQuantity_')) {
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
        Object.keys(cart).forEach(function(productId) {
            totalPrice+=cart[productId];
        });
        document.getElementById('cartTotalPrice').innerHTML =  Math.round(totalPrice * 100) / 100 + ',- Kč'
    }

    function updateTotalPriceQuantityElement(productId, quantityJQueryObj) {
        var productPriceHiddenId = "productPrice" + productId;
        var totalPriceId = "totalPrice" + productId;
        var productPriceHidden = document.getElementById(productPriceHiddenId);
        var totalPriceSpan = document.getElementById(totalPriceId);
        var pricePerUnit = productPriceHidden.value;
        let priceForQuantity = pricePerUnit * quantityJQueryObj.val();
        totalPriceSpan.innerText = Math.round(priceForQuantity * 100) / 100 + ',- Kč';
        cart[productId] = priceForQuantity;
        refreshTotalPrice();
    }


    function updateTotalPrice(productId) {
        var quantityJQueryObj =  $('[name="productQuantity' + productId + '"]');
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
