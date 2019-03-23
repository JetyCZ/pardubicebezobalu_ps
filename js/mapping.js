    // https://babylon.tierraverde.cz/index.php/s/HKYpUwoIg6au36t#pdfviewer
    var tierreGramPerLitrMapping = {

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

    function isKsProduct(productId) {
        let input = productQuantityJQueryObj(productId);
        if (input!=null) {
            return input.hasClass("kusove-zbozi");
        } else {
            alert('Produkt neexistuje na stránce: ' + productId);
            return false;
        }

    }

    function handleKeyDown(event) {
        var code = event.keyCode;
        var prevent = true;
        if (code==221) {
            console.log('START');
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
            console.log('ENTER DATA:' + qrBufferData);


            var productId = map[qrBufferData];

            if (productId!=null) {
                focusQuantity(productId, isKsProduct(productId));
            } else {
                var activeId = document.activeElement.id;
                var msg = 'Neznámý produkt (čár. kód:' + qrBufferData + '). ';
                let preffix = 'productQuantity_';


                if (activeId.indexOf(preffix)==0) {
                    var shortUrl = activeId.substring(preffix.length);
                    if (confirm(msg +' Chcete ho namapovat na ' + shortUrl + '?')) {
                        map[qrBufferData] = shortUrl;
                        $.get( "/admin313uriemy/mapping.php?qrcode=" + qrBufferData +
                            "&idproduct=" + shortUrl.substring(0, shortUrl.indexOf("-")), function( data ) {
                            alert( "Mapping saved to database." );
                        });
                        if (isKsProduct(shortUrl)) {
                            increaseByOne(shortUrl);
                        }

                    }
                } else {
                    alert(msg + 'Prosím vlezte do obj. množství produktu a pípněte znovu...')
                }


                console.log(productId);
            }

            qrBufferPreffix='';
            qrBufferData='';
        } else if (code==16){ // Shift
            // Do nothing
        } else {
            if (readingPreffix) {
                qrBufferPreffix += event.keyCode+'_';
                if (qrBufferPreffix=='221_67_48_' // CODE128
                    || qrBufferPreffix=='221_69_48_' // Other format - milk
                ) {
                    console.log('Preffix FOUND');
                    qrBufferPreffix = '';
                    readingPreffix = false;
                    readingData = true;
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
        var tierreGramPerLitr = tierreGramPerLitrMapping[shortUrl];
        if (tierreGramPerLitr > 0) {
            var quantityElem = document.getElementById("productQuantity_" + shortUrl);
            var pouredGramElem = document.getElementById("productPouredGram_" + shortUrl);
            quantityElem.value = Math.round(pouredGramElem.value / (tierreGramPerLitr/1000));
        }
        updateTotalPrice(productId);

    }
    function updateProductPouredGramInput(productId, shortUrl) {
        var tierreGramPerLitr = tierreGramPerLitrMapping[shortUrl];
        if (tierreGramPerLitr > 0) {
            var quantityElem = document.getElementById("productQuantity_" + shortUrl);
            var pouredGramElem = document.getElementById("productPouredGram_" + shortUrl);
            pouredGramElem.value = Math.round(quantityElem.value * (tierreGramPerLitr/1000));
        }
    }
