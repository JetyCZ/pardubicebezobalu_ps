    var tierreGramPerLitrMapping = {

        '52-praci-gel-z-mydlovych-orechu-bez-vune-staceny':1065,
        '54-praci-gel-z-mydlovych-orechu-se-silici-vavrinu-kubeboveho-staceny':1080,
        '58-gel-do-mycky-na-nadobi-z-mydlovych-orechu-staceny':1099,
        '59-gel-na-nadobi-staceny':1011,
        '60-wc-cistic-staceny':1025,
        '85-bio-olej-slunecnicovy-na-smazeni-a-peceni-5-l-staceny-produkt':896,
        '438-bio-olej-slunecnicovy-na-smazeni-a-peceni-5-l-staceny-produkt':878

    };

    var map = {
        1: '133-merunky-cele-na-vahu',
        2: '135-cokoladove-kaminky-na-vahu',
        3: '136-rajcata',
        4: '139-svestky-ashlock-na-vahu',
        5: '164-vlasska-jadra-na-vahu',
        6: '165-pistacie-raw-neloupane-na-vahu',
        7: '167-raw-kesu-jumbo-na-vahu',
        8: '168-raw-mandle-valencia',
        9: '169-mandle-loupane-na-vahu',
        10: '173-kesu-na-vahu',
        11: '170-kustovnice-cinska-na-vahu',
        12: '218-rozinky-sirene-na-vahu',
        13: '219-zazvor-kostky-sireny-s-cukrem-na-vahu',
        14: '8-konopny-vegansky-protein',
        15: '10-sacek-na-chleb-25-x-37-cm',
        16: '84-bio-olej-olivovy-5-l-staceny-produkt',
        17: '85-bio-olej-slunecnicovy-na-smazeni-a-peceni-5-l-staceny-produkt',
        18: '211-zalohovany-5l-kyblik-na-mleko',
        19: '226-sklenice-na-jogurt',
        20: '25-bio-bazalka',
        21: '28-bio-citronela-rezana',
        22: '179-bio-majoranka-na-vahu',
        23: '180-bio-oregano-na-vahu',
        24: '183-bio-pepr-bily-cely-na-vahu',
        25: '186-bio-skorice-mleta-na-vahu',
        26: '203-bio-trtinova-stava-panela-na-vahu',
        27: '34-jogurt-rakytnik',
        28: '35-jogurt-boruvkovy',
        29: '36-jogurt-jahodovy',
        30: '37-jogurt-cokoladovy',
        31: '174-farmarske-maslo-na-vahu',
        32: '175-farmarske-mleko-5l',
        33: '191-farmarske-mleko-3l',
        34: '198-jogurt-merunkovy-20kc-sklo',
        35: '199-jogurt-visnovy-20kc-sklo',
        36: '200-jogurt-lesni-plody-20kc-sklo',
        37: '201-jogurt-malinovy-20kc-sklo',
        38: '202-jogurt-brusinkovy-20kc-sklo',
        39: '204-farmarske-mleko-1l-20kc-sklo-',
        40: '208-farmarsky-jogurt-bily-v-cene-20-kc-kyblik',
        41: '220-cesnek-na-vahu',
        42: '221-cibule-zluta-na-vahu',
        43: '49-bio-zito',
        44: '69-bio-psenice-na-vahu',
        45: '70-bio-amarant-na-vahu',
        46: '72-jahly-na-vahu',
        47: '74-bio-pohanka-loupana-kroupy-na-vahu',
        48: '75-bio-quinoa-na-vahu',
        49: '76-bio-ryze-kulatozrnna-natural-na-vahu',
        50: '77-bio-ryze-dlouhozrnna-natural-na-vahu',
        51: '78-bio-ryze-jasminova-na-vahu',
        52: '79-bio-ryze-jasminova-natural-na-vahu',
        53: '80-bio-ryze-basmati-natural-na-vahu',
        54: '95-bio-kuskus-celozrnny-na-vahu',
        55: '96-bio-bulgur-psenicny-na-vahu',
        56: '158-bio-hrach-zluty-puleny-na-vahu',
        57: '188-bio-oves-bezpluchy-na-vahu',
        58: '207-bio-cocka-cervena-pulena',
        59: '52-praci-gel-z-mydlovych-orechu-bez-vune-staceny',
        60: '54-praci-gel-z-mydlovych-orechu-se-silici-vavrinu-kubeboveho-staceny',
        61: '58-gel-do-mycky-na-nadobi-z-mydlovych-orechu-staceny',
        62: '59-gel-na-nadobi-staceny',
        63: '60-wc-cistic-staceny',
        64: '64-belici-prasek-a-odstranovac-skvrn-na-bazi-kysliku-na-vahu',
        65: '67-praci-prasek-z-mydlovych-orechu-na-bile-pradlo-a-latkove-pleny-na-vahu',
        66: '86-frolikova-zrnkova-kava-na-vahu',
        67: '103-cajova-smes-k-uvolneni-na-vahu',
        68: '115-mata-peprna-na-vahu',
        69: '119-medunka-lekarska-na-vahu',
        70: '121-echinacea-na-vahu',
        71: '122-materidouska',
        72: '123-jitrocel-kopinaty',
        73: '215-petrzelovo-celerova-nat-na-vahu',
        74: '224-matovy-sirup-v-cene-3-kc-sklo',
        75: '225-slezovy-sirup-v-cene-3-kc-sklo',
        76: '92-srchovsky-med-kvetovy-',
        77: '94-srchovsky-med-kvetovy-pastovy',
        78: '193-srchovsky-med-dvoubarevny-10kc-sklo-',
        79: '98-teleci-parky-na-vahu',
        80: '209-jurova-klobasa-na-vahu',
        81: '212-netinsky-vostrak-na-vahu',
        82: '213-netinske-uzene-maso-na-vahu',
        83: '216-japonska-smes-na-vahu',
};


    function focusQuantity(shortUrl) {
        let input = document.getElementById('productQuantity_' + shortUrl);
        if (input != null) {
            input.focus();
            input.value = null;
        }
    }

    function checkQrCode(timeoutCall) {
        let qrcodeInput = document.getElementById('qrcode');
        var pref = "https://www.pardubicebezobalu.cz/s.php?id=";
        var text = qrcodeInput.value;
        if (text.startsWith(pref)) {
            var idSklenice = text.replace(pref,"");
            if (idSklenice.length>0) {
                if (idSklenice<10 && !timeoutCall) {
                    setTimeout(
                        function(){
                            checkQrCode(true);
                            }, 500);
                } else {
                    var shortUrl = map[idSklenice];
                    focusQuantity(shortUrl);
                }

            }
        }
    }


    document.addEventListener("DOMContentLoaded", function(event) {

        $( ".quantity" ).keypress(function( event ) {
            if ( event.which == 104 ) {
                event.preventDefault();
                let qrcodeInput = document.getElementById('qrcode');
                qrcodeInput.value = 'h';
                qrcodeInput.focus();
            }
        });

        $("#btnAddAllTopLeft").click(function(){
            document.getElementById('bulkAddToCartButton').click()
        });

        /*
        $('.quantity').on('key',function(e) {

            e.preventDefault();
            var text = (e.originalEvent || e).clipboardData.getData('text/plain');
            // https://pardubicebezobalu.cz/s.php?id=2
            var pref = "https://pardubicebezobalu.cz/s.php?id=";
            if (text.startsWith(pref)) {
                var idSklenice = text.replace(pref,"");
                if (idSklenice.length>0) {
                    var shortUrl = map[idSklenice];
                    let input = document.getElementById('productQuantity_' + shortUrl);
                    if (input!=null) {
                        input.focus();
                        input.value = null;
                    }
                }
            } else {
                document.execCommand("insertText", false, text);
            }
        });
        */
    });

    var cart = {};


    function refreshTotalPrice() {
        var totalPrice = 0;
        Object.keys(cart).forEach(function(productId) {
            totalPrice+=cart[productId];
        });
        document.getElementById('cartTotalPrice').innerHTML =  Math.round(totalPrice * 100) / 100 + ',- Kč'
    }

    function updateTotalPriceQuantityElement(productId, quantityElem) {
        var productPriceHiddenId = "productPrice" + productId;
        var totalPriceId = "totalPrice" + productId;
        var productPriceHidden = document.getElementById(productPriceHiddenId);
        var totalPriceSpan = document.getElementById(totalPriceId);
        var pricePerUnit = productPriceHidden.value;
        let priceForQuantity = pricePerUnit * quantityElem.value;
        totalPriceSpan.innerText = Math.round(priceForQuantity * 100) / 100 + ',- Kč';
        cart[productId] = priceForQuantity;
        refreshTotalPrice();
    }



    function updateTotalPrice(productId, shortUrl) {
        var quantityElem = document.getElementById("productQuantity_" + shortUrl);
        updateTotalPriceQuantityElement(productId, quantityElem);
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
    }


    function updateMililitersInput(productId, shortUrl) {
        var tierreGramPerLitr = tierreGramPerLitrMapping[shortUrl];
        if (tierreGramPerLitr > 0) {
            var quantityElem = document.getElementById("productQuantity_" + shortUrl);
            var pouredGramElem = document.getElementById("productPouredGram_" + shortUrl);
            quantityElem.value = Math.round(pouredGramElem.value / (tierreGramPerLitr/1000));
        }
        updateTotalPrice(productId, shortUrl);

    }
    function updateProductPouredGramInput(productId, shortUrl) {
        var tierreGramPerLitr = tierreGramPerLitrMapping[shortUrl];
        if (tierreGramPerLitr > 0) {
            var quantityElem = document.getElementById("productQuantity_" + shortUrl);
            var pouredGramElem = document.getElementById("productPouredGram_" + shortUrl);
            pouredGramElem.value = Math.round(quantityElem.value * (tierreGramPerLitr/1000));
        }
    }
