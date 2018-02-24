(function (modules) {
    var installedModules = {};

    function __webpack_require__(moduleId) {
        if (installedModules[moduleId])
            return installedModules[moduleId].exports;
        var module = installedModules[moduleId] = {exports: {}, id: moduleId, loaded: !1};
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        module.loaded = !0;
        return module.exports;
        /******/
    }

    __webpack_require__.m = modules;
    __webpack_require__.c = installedModules;
    __webpack_require__.p = "";
    return __webpack_require__(0);
    /******/
})([(function (module, exports, __webpack_require__) {
    module.exports = __webpack_require__(1);
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    __webpack_require__(3);
    __webpack_require__(5);
    __webpack_require__(9);
    __webpack_require__(10);
    __webpack_require__(11);
    __webpack_require__(13);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    var _events = __webpack_require__(14);
    var _events2 = _interopRequireDefault(_events);
    var _common = __webpack_require__(12);
    window.$ = _jquery2['default'];
    window.jQuery = _jquery2['default'];
    for (var i in _events2['default'].prototype) {
        _prestashop2['default'][i] = _events2['default'].prototype[i]
    }
    (0, _jquery2['default'])(document).ready(function () {
        (0, _common.psShowHide)()
    });
    /***/
}), (function (module, exports, __webpack_require__) {
    var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;
    "use strict";
    (function (global, factory) {
        if (typeof module === "object" && typeof module.exports === "object") {
            module.exports = global.document ? factory(global, !0) : function (w) {
                if (!w.document) {
                    throw new Error("jQuery requires a window with a document")
                }
                return factory(w)
            }
        } else {
            factory(global)
        }
    })(typeof window !== "undefined" ? window : undefined, function (window, noGlobal) {
        var arr = [];
        var document = window.document;
        var _slice = arr.slice;
        var concat = arr.concat;
        var push = arr.push;
        var indexOf = arr.indexOf;
        var class2type = {};
        var toString = class2type.toString;
        var hasOwn = class2type.hasOwnProperty;
        var support = {};
        var version = "2.2.4", jQuery = function jQuery(selector, context) {
                return new jQuery.fn.init(selector, context)
            }, rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, rmsPrefix = /^-ms-/, rdashAlpha = /-([\da-z])/gi,
            fcamelCase = function fcamelCase(all, letter) {
                return letter.toUpperCase()
            };
        jQuery.fn = jQuery.prototype = {
            jquery: version,
            constructor: jQuery,
            selector: "",
            length: 0,
            toArray: function toArray() {
                return _slice.call(this)
            },
            get: function get(num) {
                return num != null ? num < 0 ? this[num + this.length] : this[num] : _slice.call(this)
            },
            pushStack: function pushStack(elems) {
                var ret = jQuery.merge(this.constructor(), elems);
                ret.prevObject = this;
                ret.context = this.context;
                return ret
            },
            each: function each(callback) {
                return jQuery.each(this, callback)
            },
            map: function map(callback) {
                return this.pushStack(jQuery.map(this, function (elem, i) {
                    return callback.call(elem, i, elem)
                }))
            },
            slice: function slice() {
                return this.pushStack(_slice.apply(this, arguments))
            },
            first: function first() {
                return this.eq(0)
            },
            last: function last() {
                return this.eq(-1)
            },
            eq: function eq(i) {
                var len = this.length, j = +i + (i < 0 ? len : 0);
                return this.pushStack(j >= 0 && j < len ? [this[j]] : [])
            },
            end: function end() {
                return this.prevObject || this.constructor()
            },
            push: push,
            sort: arr.sort,
            splice: arr.splice
        };
        jQuery.extend = jQuery.fn.extend = function () {
            var options, name, src, copy, copyIsArray, clone, target = arguments[0] || {}, i = 1,
                length = arguments.length, deep = !1;
            if (typeof target === "boolean") {
                deep = target;
                target = arguments[i] || {};
                i++
            }
            if (typeof target !== "object" && !jQuery.isFunction(target)) {
                target = {}
            }
            if (i === length) {
                target = this;
                i--
            }
            for (; i < length; i++) {
                if ((options = arguments[i]) != null) {
                    for (name in options) {
                        src = target[name];
                        copy = options[name];
                        if (target === copy) {
                            continue
                        }
                        if (deep && copy && (jQuery.isPlainObject(copy) || (copyIsArray = jQuery.isArray(copy)))) {
                            if (copyIsArray) {
                                copyIsArray = !1;
                                clone = src && jQuery.isArray(src) ? src : []
                            } else {
                                clone = src && jQuery.isPlainObject(src) ? src : {}
                            }
                            target[name] = jQuery.extend(deep, clone, copy)
                        } else if (copy !== undefined) {
                            target[name] = copy
                        }
                    }
                }
            }
            return target
        };
        jQuery.extend({
            expando: "jQuery" + (version + Math.random()).replace(/\D/g, ""), isReady: !0, error: function error(msg) {
                throw new Error(msg)
            }, noop: function noop() {
            }, isFunction: function isFunction(obj) {
                return jQuery.type(obj) === "function"
            }, isArray: Array.isArray, isWindow: function isWindow(obj) {
                return obj != null && obj === obj.window
            }, isNumeric: function isNumeric(obj) {
                var realStringObj = obj && obj.toString();
                return !jQuery.isArray(obj) && realStringObj - parseFloat(realStringObj) + 1 >= 0
            }, isPlainObject: function isPlainObject(obj) {
                var key;
                if (jQuery.type(obj) !== "object" || obj.nodeType || jQuery.isWindow(obj)) {
                    return !1
                }
                if (obj.constructor && !hasOwn.call(obj, "constructor") && !hasOwn.call(obj.constructor.prototype || {}, "isPrototypeOf")) {
                    return !1
                }
                for (key in obj) {
                }
                return key === undefined || hasOwn.call(obj, key)
            }, isEmptyObject: function isEmptyObject(obj) {
                var name;
                for (name in obj) {
                    return !1
                }
                return !0
            }, type: function type(obj) {
                if (obj == null) {
                    return obj + ""
                }
                return typeof obj === "object" || typeof obj === "function" ? class2type[toString.call(obj)] || "object" : typeof obj
            }, globalEval: function globalEval(code) {
                var script, indirect = eval;
                code = jQuery.trim(code);
                if (code) {
                    if (code.indexOf("use strict") === 1) {
                        script = document.createElement("script");
                        script.text = code;
                        document.head.appendChild(script).parentNode.removeChild(script)
                    } else {
                        indirect(code)
                    }
                }
            }, camelCase: function camelCase(string) {
                return string.replace(rmsPrefix, "ms-").replace(rdashAlpha, fcamelCase)
            }, nodeName: function nodeName(elem, name) {
                return elem.nodeName && elem.nodeName.toLowerCase() === name.toLowerCase()
            }, each: function each(obj, callback) {
                var length, i = 0;
                if (isArrayLike(obj)) {
                    length = obj.length;
                    for (; i < length; i++) {
                        if (callback.call(obj[i], i, obj[i]) === !1) {
                            break
                        }
                    }
                } else {
                    for (i in obj) {
                        if (callback.call(obj[i], i, obj[i]) === !1) {
                            break
                        }
                    }
                }
                return obj
            }, trim: function trim(text) {
                return text == null ? "" : (text + "").replace(rtrim, "")
            }, makeArray: function makeArray(arr, results) {
                var ret = results || [];
                if (arr != null) {
                    if (isArrayLike(Object(arr))) {
                        jQuery.merge(ret, typeof arr === "string" ? [arr] : arr)
                    } else {
                        push.call(ret, arr)
                    }
                }
                return ret
            }, inArray: function inArray(elem, arr, i) {
                return arr == null ? -1 : indexOf.call(arr, elem, i)
            }, merge: function merge(first, second) {
                var len = +second.length, j = 0, i = first.length;
                for (; j < len; j++) {
                    first[i++] = second[j]
                }
                first.length = i;
                return first
            }, grep: function grep(elems, callback, invert) {
                var callbackInverse, matches = [], i = 0, length = elems.length, callbackExpect = !invert;
                for (; i < length; i++) {
                    callbackInverse = !callback(elems[i], i);
                    if (callbackInverse !== callbackExpect) {
                        matches.push(elems[i])
                    }
                }
                return matches
            }, map: function map(elems, callback, arg) {
                var length, value, i = 0, ret = [];
                if (isArrayLike(elems)) {
                    length = elems.length;
                    for (; i < length; i++) {
                        value = callback(elems[i], i, arg);
                        if (value != null) {
                            ret.push(value)
                        }
                    }
                } else {
                    for (i in elems) {
                        value = callback(elems[i], i, arg);
                        if (value != null) {
                            ret.push(value)
                        }
                    }
                }
                return concat.apply([], ret)
            }, guid: 1, proxy: function proxy(fn, context) {
                var tmp, args, proxy;
                if (typeof context === "string") {
                    tmp = fn[context];
                    context = fn;
                    fn = tmp
                }
                if (!jQuery.isFunction(fn)) {
                    return undefined
                }
                args = _slice.call(arguments, 2);
                proxy = function () {
                    return fn.apply(context || this, args.concat(_slice.call(arguments)))
                };
                proxy.guid = fn.guid = fn.guid || jQuery.guid++;
                return proxy
            }, now: Date.now, support: support
        });
        if (typeof Symbol === "function") {
            jQuery.fn[Symbol.iterator] = arr[Symbol.iterator]
        }
        jQuery.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "), function (i, name) {
            class2type["[object " + name + "]"] = name.toLowerCase()
        });

        function isArrayLike(obj) {
            var length = !!obj && "length" in obj && obj.length, type = jQuery.type(obj);
            if (type === "function" || jQuery.isWindow(obj)) {
                return !1
            }
            return type === "array" || length === 0 || typeof length === "number" && length > 0 && length - 1 in obj
        }

        var Sizzle = (function (window) {
            var i, support, Expr, getText, isXML, tokenize, compile, select, outermostContext, sortInput, hasDuplicate,
                setDocument, document, docElem, documentIsHTML, rbuggyQSA, rbuggyMatches, matches, contains,
                expando = "sizzle" + 1 * new Date(), preferredDoc = window.document, dirruns = 0, done = 0,
                classCache = createCache(), tokenCache = createCache(), compilerCache = createCache(),
                sortOrder = function sortOrder(a, b) {
                    if (a === b) {
                        hasDuplicate = !0
                    }
                    return 0
                }, MAX_NEGATIVE = 1 << 31, hasOwn = ({}).hasOwnProperty, arr = [], pop = arr.pop,
                push_native = arr.push, push = arr.push, slice = arr.slice, indexOf = function indexOf(list, elem) {
                    var i = 0, len = list.length;
                    for (; i < len; i++) {
                        if (list[i] === elem) {
                            return i
                        }
                    }
                    return -1
                },
                booleans = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
                whitespace = "[\\x20\\t\\r\\n\\f]", identifier = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
                attributes = "\\[" + whitespace + "*(" + identifier + ")(?:" + whitespace + "*([*^$|!~]?=)" + whitespace + "*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|(" + identifier + "))|)" + whitespace + "*\\]",
                pseudos = ":(" + identifier + ")(?:\\((" + "('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|" + "((?:\\\\.|[^\\\\()[\\]]|" + attributes + ")*)|" + ".*" + ")\\)|)",
                rwhitespace = new RegExp(whitespace + "+", "g"),
                rtrim = new RegExp("^" + whitespace + "+|((?:^|[^\\\\])(?:\\\\.)*)" + whitespace + "+$", "g"),
                rcomma = new RegExp("^" + whitespace + "*," + whitespace + "*"),
                rcombinators = new RegExp("^" + whitespace + "*([>+~]|" + whitespace + ")" + whitespace + "*"),
                rattributeQuotes = new RegExp("=" + whitespace + "*([^\\]'\"]*?)" + whitespace + "*\\]", "g"),
                rpseudo = new RegExp(pseudos), ridentifier = new RegExp("^" + identifier + "$"), matchExpr = {
                    "ID": new RegExp("^#(" + identifier + ")"),
                    "CLASS": new RegExp("^\\.(" + identifier + ")"),
                    "TAG": new RegExp("^(" + identifier + "|[*])"),
                    "ATTR": new RegExp("^" + attributes),
                    "PSEUDO": new RegExp("^" + pseudos),
                    "CHILD": new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + whitespace + "*(even|odd|(([+-]|)(\\d*)n|)" + whitespace + "*(?:([+-]|)" + whitespace + "*(\\d+)|))" + whitespace + "*\\)|)", "i"),
                    "bool": new RegExp("^(?:" + booleans + ")$", "i"),
                    "needsContext": new RegExp("^" + whitespace + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + whitespace + "*((?:-\\d)?\\d*)" + whitespace + "*\\)|)(?=[^-]|$)", "i")
                }, rinputs = /^(?:input|select|textarea|button)$/i, rheader = /^h\d$/i, rnative = /^[^{]+\{\s*\[native \w/,
                rquickExpr = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/, rsibling = /[+~]/, rescape = /'|\\/g,
                runescape = new RegExp("\\\\([\\da-f]{1,6}" + whitespace + "?|(" + whitespace + ")|.)", "ig"),
                funescape = function funescape(_, escaped, escapedWhitespace) {
                    var high = "0x" + escaped - 0x10000;
                    return high !== high || escapedWhitespace ? escaped : high < 0 ? String.fromCharCode(high + 0x10000) : String.fromCharCode(high >> 10 | 0xD800, high & 0x3FF | 0xDC00)
                }, unloadHandler = function unloadHandler() {
                    setDocument()
                };
            try {
                push.apply(arr = slice.call(preferredDoc.childNodes), preferredDoc.childNodes);
                arr[preferredDoc.childNodes.length].nodeType
            } catch (e) {
                push = {
                    apply: arr.length ? function (target, els) {
                        push_native.apply(target, slice.call(els))
                    } : function (target, els) {
                        var j = target.length, i = 0;
                        while (target[j++] = els[i++]) {
                        }
                        target.length = j - 1
                    }
                }
            }

            function Sizzle(selector, context, results, seed) {
                var m, i, elem, nid, nidselect, match, groups, newSelector,
                    newContext = context && context.ownerDocument, nodeType = context ? context.nodeType : 9;
                results = results || [];
                if (typeof selector !== "string" || !selector || nodeType !== 1 && nodeType !== 9 && nodeType !== 11) {
                    return results
                }
                if (!seed) {
                    if ((context ? context.ownerDocument || context : preferredDoc) !== document) {
                        setDocument(context)
                    }
                    context = context || document;
                    if (documentIsHTML) {
                        if (nodeType !== 11 && (match = rquickExpr.exec(selector))) {
                            if (m = match[1]) {
                                if (nodeType === 9) {
                                    if (elem = context.getElementById(m)) {
                                        if (elem.id === m) {
                                            results.push(elem);
                                            return results
                                        }
                                    } else {
                                        return results
                                    }
                                } else {
                                    if (newContext && (elem = newContext.getElementById(m)) && contains(context, elem) && elem.id === m) {
                                        results.push(elem);
                                        return results
                                    }
                                }
                            } else if (match[2]) {
                                push.apply(results, context.getElementsByTagName(selector));
                                return results
                            } else if ((m = match[3]) && support.getElementsByClassName && context.getElementsByClassName) {
                                push.apply(results, context.getElementsByClassName(m));
                                return results
                            }
                        }
                        if (support.qsa && !compilerCache[selector + " "] && (!rbuggyQSA || !rbuggyQSA.test(selector))) {
                            if (nodeType !== 1) {
                                newContext = context;
                                newSelector = selector
                            } else if (context.nodeName.toLowerCase() !== "object") {
                                if (nid = context.getAttribute("id")) {
                                    nid = nid.replace(rescape, "\\$&")
                                } else {
                                    context.setAttribute("id", nid = expando)
                                }
                                groups = tokenize(selector);
                                i = groups.length;
                                nidselect = ridentifier.test(nid) ? "#" + nid : "[id='" + nid + "']";
                                while (i--) {
                                    groups[i] = nidselect + " " + toSelector(groups[i])
                                }
                                newSelector = groups.join(",");
                                newContext = rsibling.test(selector) && testContext(context.parentNode) || context
                            }
                            if (newSelector) {
                                try {
                                    push.apply(results, newContext.querySelectorAll(newSelector));
                                    return results
                                } catch (qsaError) {
                                } finally {
                                    if (nid === expando) {
                                        context.removeAttribute("id")
                                    }
                                }
                            }
                        }
                    }
                }
                return select(selector.replace(rtrim, "$1"), context, results, seed)
            }

            function createCache() {
                var keys = [];

                function cache(key, value) {
                    if (keys.push(key + " ") > Expr.cacheLength) {
                        delete cache[keys.shift()]
                    }
                    return cache[key + " "] = value
                }

                return cache
            }

            function markFunction(fn) {
                fn[expando] = !0;
                return fn
            }

            function assert(fn) {
                var div = document.createElement("div");
                try {
                    return !!fn(div)
                } catch (e) {
                    return !1
                } finally {
                    if (div.parentNode) {
                        div.parentNode.removeChild(div)
                    }
                    div = null
                }
            }

            function addHandle(attrs, handler) {
                var arr = attrs.split("|"), i = arr.length;
                while (i--) {
                    Expr.attrHandle[arr[i]] = handler
                }
            }

            function siblingCheck(a, b) {
                var cur = b && a,
                    diff = cur && a.nodeType === 1 && b.nodeType === 1 && (~b.sourceIndex || MAX_NEGATIVE) - (~a.sourceIndex || MAX_NEGATIVE);
                if (diff) {
                    return diff
                }
                if (cur) {
                    while (cur = cur.nextSibling) {
                        if (cur === b) {
                            return -1
                        }
                    }
                }
                return a ? 1 : -1
            }

            function createInputPseudo(type) {
                return function (elem) {
                    var name = elem.nodeName.toLowerCase();
                    return name === "input" && elem.type === type
                }
            }

            function createButtonPseudo(type) {
                return function (elem) {
                    var name = elem.nodeName.toLowerCase();
                    return (name === "input" || name === "button") && elem.type === type
                }
            }

            function createPositionalPseudo(fn) {
                return markFunction(function (argument) {
                    argument = +argument;
                    return markFunction(function (seed, matches) {
                        var j, matchIndexes = fn([], seed.length, argument), i = matchIndexes.length;
                        while (i--) {
                            if (seed[j = matchIndexes[i]]) {
                                seed[j] = !(matches[j] = seed[j])
                            }
                        }
                    })
                })
            }

            function testContext(context) {
                return context && typeof context.getElementsByTagName !== "undefined" && context
            }

            support = Sizzle.support = {};
            isXML = Sizzle.isXML = function (elem) {
                var documentElement = elem && (elem.ownerDocument || elem).documentElement;
                return documentElement ? documentElement.nodeName !== "HTML" : !1
            };
            setDocument = Sizzle.setDocument = function (node) {
                var hasCompare, parent, doc = node ? node.ownerDocument || node : preferredDoc;
                if (doc === document || doc.nodeType !== 9 || !doc.documentElement) {
                    return document
                }
                document = doc;
                docElem = document.documentElement;
                documentIsHTML = !isXML(document);
                if ((parent = document.defaultView) && parent.top !== parent) {
                    if (parent.addEventListener) {
                        parent.addEventListener("unload", unloadHandler, !1)
                    } else if (parent.attachEvent) {
                        parent.attachEvent("onunload", unloadHandler)
                    }
                }
                support.attributes = assert(function (div) {
                    div.className = "i";
                    return !div.getAttribute("className")
                });
                support.getElementsByTagName = assert(function (div) {
                    div.appendChild(document.createComment(""));
                    return !div.getElementsByTagName("*").length
                });
                support.getElementsByClassName = rnative.test(document.getElementsByClassName);
                support.getById = assert(function (div) {
                    docElem.appendChild(div).id = expando;
                    return !document.getElementsByName || !document.getElementsByName(expando).length
                });
                if (support.getById) {
                    Expr.find.ID = function (id, context) {
                        if (typeof context.getElementById !== "undefined" && documentIsHTML) {
                            var m = context.getElementById(id);
                            return m ? [m] : []
                        }
                    };
                    Expr.filter.ID = function (id) {
                        var attrId = id.replace(runescape, funescape);
                        return function (elem) {
                            return elem.getAttribute("id") === attrId
                        }
                    }
                } else {
                    delete Expr.find.ID;
                    Expr.filter.ID = function (id) {
                        var attrId = id.replace(runescape, funescape);
                        return function (elem) {
                            var node = typeof elem.getAttributeNode !== "undefined" && elem.getAttributeNode("id");
                            return node && node.value === attrId
                        }
                    }
                }
                Expr.find.TAG = support.getElementsByTagName ? function (tag, context) {
                    if (typeof context.getElementsByTagName !== "undefined") {
                        return context.getElementsByTagName(tag)
                    } else if (support.qsa) {
                        return context.querySelectorAll(tag)
                    }
                } : function (tag, context) {
                    var elem, tmp = [], i = 0, results = context.getElementsByTagName(tag);
                    if (tag === "*") {
                        while (elem = results[i++]) {
                            if (elem.nodeType === 1) {
                                tmp.push(elem)
                            }
                        }
                        return tmp
                    }
                    return results
                };
                Expr.find.CLASS = support.getElementsByClassName && function (className, context) {
                    if (typeof context.getElementsByClassName !== "undefined" && documentIsHTML) {
                        return context.getElementsByClassName(className)
                    }
                };
                rbuggyMatches = [];
                rbuggyQSA = [];
                if (support.qsa = rnative.test(document.querySelectorAll)) {
                    assert(function (div) {
                        docElem.appendChild(div).innerHTML = "<a id='" + expando + "'></a>" + "<select id='" + expando + "-\r\\' msallowcapture=''>" + "<option selected=''></option></select>";
                        if (div.querySelectorAll("[msallowcapture^='']").length) {
                            rbuggyQSA.push("[*^$]=" + whitespace + "*(?:''|\"\")")
                        }
                        if (!div.querySelectorAll("[selected]").length) {
                            rbuggyQSA.push("\\[" + whitespace + "*(?:value|" + booleans + ")")
                        }
                        if (!div.querySelectorAll("[id~=" + expando + "-]").length) {
                            rbuggyQSA.push("~=")
                        }
                        if (!div.querySelectorAll(":checked").length) {
                            rbuggyQSA.push(":checked")
                        }
                        if (!div.querySelectorAll("a#" + expando + "+*").length) {
                            rbuggyQSA.push(".#.+[+~]")
                        }
                    });
                    assert(function (div) {
                        var input = document.createElement("input");
                        input.setAttribute("type", "hidden");
                        div.appendChild(input).setAttribute("name", "D");
                        if (div.querySelectorAll("[name=d]").length) {
                            rbuggyQSA.push("name" + whitespace + "*[*^$|!~]?=")
                        }
                        if (!div.querySelectorAll(":enabled").length) {
                            rbuggyQSA.push(":enabled", ":disabled")
                        }
                        div.querySelectorAll("*,:x");
                        rbuggyQSA.push(",.*:")
                    })
                }
                if (support.matchesSelector = rnative.test(matches = docElem.matches || docElem.webkitMatchesSelector || docElem.mozMatchesSelector || docElem.oMatchesSelector || docElem.msMatchesSelector)) {
                    assert(function (div) {
                        support.disconnectedMatch = matches.call(div, "div");
                        matches.call(div, "[s!='']:x");
                        rbuggyMatches.push("!=", pseudos)
                    })
                }
                rbuggyQSA = rbuggyQSA.length && new RegExp(rbuggyQSA.join("|"));
                rbuggyMatches = rbuggyMatches.length && new RegExp(rbuggyMatches.join("|"));
                hasCompare = rnative.test(docElem.compareDocumentPosition);
                contains = hasCompare || rnative.test(docElem.contains) ? function (a, b) {
                    var adown = a.nodeType === 9 ? a.documentElement : a, bup = b && b.parentNode;
                    return a === bup || !!(bup && bup.nodeType === 1 && (adown.contains ? adown.contains(bup) : a.compareDocumentPosition && a.compareDocumentPosition(bup) & 16))
                } : function (a, b) {
                    if (b) {
                        while (b = b.parentNode) {
                            if (b === a) {
                                return !0
                            }
                        }
                    }
                    return !1
                };
                sortOrder = hasCompare ? function (a, b) {
                    if (a === b) {
                        hasDuplicate = !0;
                        return 0
                    }
                    var compare = !a.compareDocumentPosition - !b.compareDocumentPosition;
                    if (compare) {
                        return compare
                    }
                    compare = (a.ownerDocument || a) === (b.ownerDocument || b) ? a.compareDocumentPosition(b) : 1;
                    if (compare & 1 || !support.sortDetached && b.compareDocumentPosition(a) === compare) {
                        if (a === document || a.ownerDocument === preferredDoc && contains(preferredDoc, a)) {
                            return -1
                        }
                        if (b === document || b.ownerDocument === preferredDoc && contains(preferredDoc, b)) {
                            return 1
                        }
                        return sortInput ? indexOf(sortInput, a) - indexOf(sortInput, b) : 0
                    }
                    return compare & 4 ? -1 : 1
                } : function (a, b) {
                    if (a === b) {
                        hasDuplicate = !0;
                        return 0
                    }
                    var cur, i = 0, aup = a.parentNode, bup = b.parentNode, ap = [a], bp = [b];
                    if (!aup || !bup) {
                        return a === document ? -1 : b === document ? 1 : aup ? -1 : bup ? 1 : sortInput ? indexOf(sortInput, a) - indexOf(sortInput, b) : 0
                    } else if (aup === bup) {
                        return siblingCheck(a, b)
                    }
                    cur = a;
                    while (cur = cur.parentNode) {
                        ap.unshift(cur)
                    }
                    cur = b;
                    while (cur = cur.parentNode) {
                        bp.unshift(cur)
                    }
                    while (ap[i] === bp[i]) {
                        i++
                    }
                    return i ? siblingCheck(ap[i], bp[i]) : ap[i] === preferredDoc ? -1 : bp[i] === preferredDoc ? 1 : 0
                };
                return document
            };
            Sizzle.matches = function (expr, elements) {
                return Sizzle(expr, null, null, elements)
            };
            Sizzle.matchesSelector = function (elem, expr) {
                if ((elem.ownerDocument || elem) !== document) {
                    setDocument(elem)
                }
                expr = expr.replace(rattributeQuotes, "='$1']");
                if (support.matchesSelector && documentIsHTML && !compilerCache[expr + " "] && (!rbuggyMatches || !rbuggyMatches.test(expr)) && (!rbuggyQSA || !rbuggyQSA.test(expr))) {
                    try {
                        var ret = matches.call(elem, expr);
                        if (ret || support.disconnectedMatch || elem.document && elem.document.nodeType !== 11) {
                            return ret
                        }
                    } catch (e) {
                    }
                }
                return Sizzle(expr, document, null, [elem]).length > 0
            };
            Sizzle.contains = function (context, elem) {
                if ((context.ownerDocument || context) !== document) {
                    setDocument(context)
                }
                return contains(context, elem)
            };
            Sizzle.attr = function (elem, name) {
                if ((elem.ownerDocument || elem) !== document) {
                    setDocument(elem)
                }
                var fn = Expr.attrHandle[name.toLowerCase()],
                    val = fn && hasOwn.call(Expr.attrHandle, name.toLowerCase()) ? fn(elem, name, !documentIsHTML) : undefined;
                return val !== undefined ? val : support.attributes || !documentIsHTML ? elem.getAttribute(name) : (val = elem.getAttributeNode(name)) && val.specified ? val.value : null
            };
            Sizzle.error = function (msg) {
                throw new Error("Syntax error, unrecognized expression: " + msg)
            };
            Sizzle.uniqueSort = function (results) {
                var elem, duplicates = [], j = 0, i = 0;
                hasDuplicate = !support.detectDuplicates;
                sortInput = !support.sortStable && results.slice(0);
                results.sort(sortOrder);
                if (hasDuplicate) {
                    while (elem = results[i++]) {
                        if (elem === results[i]) {
                            j = duplicates.push(i)
                        }
                    }
                    while (j--) {
                        results.splice(duplicates[j], 1)
                    }
                }
                sortInput = null;
                return results
            };
            getText = Sizzle.getText = function (elem) {
                var node, ret = "", i = 0, nodeType = elem.nodeType;
                if (!nodeType) {
                    while (node = elem[i++]) {
                        ret += getText(node)
                    }
                } else if (nodeType === 1 || nodeType === 9 || nodeType === 11) {
                    if (typeof elem.textContent === "string") {
                        return elem.textContent
                    } else {
                        for (elem = elem.firstChild; elem; elem = elem.nextSibling) {
                            ret += getText(elem)
                        }
                    }
                } else if (nodeType === 3 || nodeType === 4) {
                    return elem.nodeValue
                }
                return ret
            };
            Expr = Sizzle.selectors = {
                cacheLength: 50,
                createPseudo: markFunction,
                match: matchExpr,
                attrHandle: {},
                find: {},
                relative: {
                    ">": {dir: "parentNode", first: !0},
                    " ": {dir: "parentNode"},
                    "+": {dir: "previousSibling", first: !0},
                    "~": {dir: "previousSibling"}
                },
                preFilter: {
                    "ATTR": function ATTR(match) {
                        match[1] = match[1].replace(runescape, funescape);
                        match[3] = (match[3] || match[4] || match[5] || "").replace(runescape, funescape);
                        if (match[2] === "~=") {
                            match[3] = " " + match[3] + " "
                        }
                        return match.slice(0, 4)
                    }, "CHILD": function CHILD(match) {
                        match[1] = match[1].toLowerCase();
                        if (match[1].slice(0, 3) === "nth") {
                            if (!match[3]) {
                                Sizzle.error(match[0])
                            }
                            match[4] = +(match[4] ? match[5] + (match[6] || 1) : 2 * (match[3] === "even" || match[3] === "odd"));
                            match[5] = +(match[7] + match[8] || match[3] === "odd")
                        } else if (match[3]) {
                            Sizzle.error(match[0])
                        }
                        return match
                    }, "PSEUDO": function PSEUDO(match) {
                        var excess, unquoted = !match[6] && match[2];
                        if (matchExpr.CHILD.test(match[0])) {
                            return null
                        }
                        if (match[3]) {
                            match[2] = match[4] || match[5] || ""
                        } else if (unquoted && rpseudo.test(unquoted) && (excess = tokenize(unquoted, !0)) && (excess = unquoted.indexOf(")", unquoted.length - excess) - unquoted.length)) {
                            match[0] = match[0].slice(0, excess);
                            match[2] = unquoted.slice(0, excess)
                        }
                        return match.slice(0, 3)
                    }
                },
                filter: {
                    "TAG": function TAG(nodeNameSelector) {
                        var nodeName = nodeNameSelector.replace(runescape, funescape).toLowerCase();
                        return nodeNameSelector === "*" ? function () {
                            return !0
                        } : function (elem) {
                            return elem.nodeName && elem.nodeName.toLowerCase() === nodeName
                        }
                    }, "CLASS": function CLASS(className) {
                        var pattern = classCache[className + " "];
                        return pattern || (pattern = new RegExp("(^|" + whitespace + ")" + className + "(" + whitespace + "|$)")) && classCache(className, function (elem) {
                            return pattern.test(typeof elem.className === "string" && elem.className || typeof elem.getAttribute !== "undefined" && elem.getAttribute("class") || "")
                        })
                    }, "ATTR": function ATTR(name, operator, check) {
                        return function (elem) {
                            var result = Sizzle.attr(elem, name);
                            if (result == null) {
                                return operator === "!="
                            }
                            if (!operator) {
                                return !0
                            }
                            result += "";
                            return operator === "=" ? result === check : operator === "!=" ? result !== check : operator === "^=" ? check && result.indexOf(check) === 0 : operator === "*=" ? check && result.indexOf(check) > -1 : operator === "$=" ? check && result.slice(-check.length) === check : operator === "~=" ? (" " + result.replace(rwhitespace, " ") + " ").indexOf(check) > -1 : operator === "|=" ? result === check || result.slice(0, check.length + 1) === check + "-" : !1
                        }
                    }, "CHILD": function CHILD(type, what, argument, first, last) {
                        var simple = type.slice(0, 3) !== "nth", forward = type.slice(-4) !== "last",
                            ofType = what === "of-type";
                        return first === 1 && last === 0 ? function (elem) {
                            return !!elem.parentNode
                        } : function (elem, context, xml) {
                            var cache, uniqueCache, outerCache, node, nodeIndex, start,
                                dir = simple !== forward ? "nextSibling" : "previousSibling", parent = elem.parentNode,
                                name = ofType && elem.nodeName.toLowerCase(), useCache = !xml && !ofType, diff = !1;
                            if (parent) {
                                if (simple) {
                                    while (dir) {
                                        node = elem;
                                        while (node = node[dir]) {
                                            if (ofType ? node.nodeName.toLowerCase() === name : node.nodeType === 1) {
                                                return !1
                                            }
                                        }
                                        start = dir = type === "only" && !start && "nextSibling"
                                    }
                                    return !0
                                }
                                start = [forward ? parent.firstChild : parent.lastChild];
                                if (forward && useCache) {
                                    node = parent;
                                    outerCache = node[expando] || (node[expando] = {});
                                    uniqueCache = outerCache[node.uniqueID] || (outerCache[node.uniqueID] = {});
                                    cache = uniqueCache[type] || [];
                                    nodeIndex = cache[0] === dirruns && cache[1];
                                    diff = nodeIndex && cache[2];
                                    node = nodeIndex && parent.childNodes[nodeIndex];
                                    while (node = ++nodeIndex && node && node[dir] || (diff = nodeIndex = 0) || start.pop()) {
                                        if (node.nodeType === 1 && ++diff && node === elem) {
                                            uniqueCache[type] = [dirruns, nodeIndex, diff];
                                            break
                                        }
                                    }
                                } else {
                                    if (useCache) {
                                        node = elem;
                                        outerCache = node[expando] || (node[expando] = {});
                                        uniqueCache = outerCache[node.uniqueID] || (outerCache[node.uniqueID] = {});
                                        cache = uniqueCache[type] || [];
                                        nodeIndex = cache[0] === dirruns && cache[1];
                                        diff = nodeIndex
                                    }
                                    if (diff === !1) {
                                        while (node = ++nodeIndex && node && node[dir] || (diff = nodeIndex = 0) || start.pop()) {
                                            if ((ofType ? node.nodeName.toLowerCase() === name : node.nodeType === 1) && ++diff) {
                                                if (useCache) {
                                                    outerCache = node[expando] || (node[expando] = {});
                                                    uniqueCache = outerCache[node.uniqueID] || (outerCache[node.uniqueID] = {});
                                                    uniqueCache[type] = [dirruns, diff]
                                                }
                                                if (node === elem) {
                                                    break
                                                }
                                            }
                                        }
                                    }
                                }
                                diff -= last;
                                return diff === first || diff % first === 0 && diff / first >= 0
                            }
                        }
                    }, "PSEUDO": function PSEUDO(pseudo, argument) {
                        var args,
                            fn = Expr.pseudos[pseudo] || Expr.setFilters[pseudo.toLowerCase()] || Sizzle.error("unsupported pseudo: " + pseudo);
                        if (fn[expando]) {
                            return fn(argument)
                        }
                        if (fn.length > 1) {
                            args = [pseudo, pseudo, "", argument];
                            return Expr.setFilters.hasOwnProperty(pseudo.toLowerCase()) ? markFunction(function (seed, matches) {
                                var idx, matched = fn(seed, argument), i = matched.length;
                                while (i--) {
                                    idx = indexOf(seed, matched[i]);
                                    seed[idx] = !(matches[idx] = matched[i])
                                }
                            }) : function (elem) {
                                return fn(elem, 0, args)
                            }
                        }
                        return fn
                    }
                },
                pseudos: {
                    "not": markFunction(function (selector) {
                        var input = [], results = [], matcher = compile(selector.replace(rtrim, "$1"));
                        return matcher[expando] ? markFunction(function (seed, matches, context, xml) {
                            var elem, unmatched = matcher(seed, null, xml, []), i = seed.length;
                            while (i--) {
                                if (elem = unmatched[i]) {
                                    seed[i] = !(matches[i] = elem)
                                }
                            }
                        }) : function (elem, context, xml) {
                            input[0] = elem;
                            matcher(input, null, xml, results);
                            input[0] = null;
                            return !results.pop()
                        }
                    }), "has": markFunction(function (selector) {
                        return function (elem) {
                            return Sizzle(selector, elem).length > 0
                        }
                    }), "contains": markFunction(function (text) {
                        text = text.replace(runescape, funescape);
                        return function (elem) {
                            return (elem.textContent || elem.innerText || getText(elem)).indexOf(text) > -1
                        }
                    }), "lang": markFunction(function (lang) {
                        if (!ridentifier.test(lang || "")) {
                            Sizzle.error("unsupported lang: " + lang)
                        }
                        lang = lang.replace(runescape, funescape).toLowerCase();
                        return function (elem) {
                            var elemLang;
                            do {
                                if (elemLang = documentIsHTML ? elem.lang : elem.getAttribute("xml:lang") || elem.getAttribute("lang")) {
                                    elemLang = elemLang.toLowerCase();
                                    return elemLang === lang || elemLang.indexOf(lang + "-") === 0
                                }
                            } while ((elem = elem.parentNode) && elem.nodeType === 1);
                            return !1
                        }
                    }), "target": function target(elem) {
                        var hash = window.location && window.location.hash;
                        return hash && hash.slice(1) === elem.id
                    }, "root": function root(elem) {
                        return elem === docElem
                    }, "focus": function focus(elem) {
                        return elem === document.activeElement && (!document.hasFocus || document.hasFocus()) && !!(elem.type || elem.href || ~elem.tabIndex)
                    }, "enabled": function enabled(elem) {
                        return elem.disabled === !1
                    }, "disabled": function disabled(elem) {
                        return elem.disabled === !0
                    }, "checked": function checked(elem) {
                        var nodeName = elem.nodeName.toLowerCase();
                        return nodeName === "input" && !!elem.checked || nodeName === "option" && !!elem.selected
                    }, "selected": function selected(elem) {
                        if (elem.parentNode) {
                            elem.parentNode.selectedIndex
                        }
                        return elem.selected === !0
                    }, "empty": function empty(elem) {
                        for (elem = elem.firstChild; elem; elem = elem.nextSibling) {
                            if (elem.nodeType < 6) {
                                return !1
                            }
                        }
                        return !0
                    }, "parent": function parent(elem) {
                        return !Expr.pseudos.empty(elem)
                    }, "header": function header(elem) {
                        return rheader.test(elem.nodeName)
                    }, "input": function input(elem) {
                        return rinputs.test(elem.nodeName)
                    }, "button": function button(elem) {
                        var name = elem.nodeName.toLowerCase();
                        return name === "input" && elem.type === "button" || name === "button"
                    }, "text": function text(elem) {
                        var attr;
                        return elem.nodeName.toLowerCase() === "input" && elem.type === "text" && ((attr = elem.getAttribute("type")) == null || attr.toLowerCase() === "text")
                    }, "first": createPositionalPseudo(function () {
                        return [0]
                    }), "last": createPositionalPseudo(function (matchIndexes, length) {
                        return [length - 1]
                    }), "eq": createPositionalPseudo(function (matchIndexes, length, argument) {
                        return [argument < 0 ? argument + length : argument]
                    }), "even": createPositionalPseudo(function (matchIndexes, length) {
                        var i = 0;
                        for (; i < length; i += 2) {
                            matchIndexes.push(i)
                        }
                        return matchIndexes
                    }), "odd": createPositionalPseudo(function (matchIndexes, length) {
                        var i = 1;
                        for (; i < length; i += 2) {
                            matchIndexes.push(i)
                        }
                        return matchIndexes
                    }), "lt": createPositionalPseudo(function (matchIndexes, length, argument) {
                        var i = argument < 0 ? argument + length : argument;
                        for (; --i >= 0;) {
                            matchIndexes.push(i)
                        }
                        return matchIndexes
                    }), "gt": createPositionalPseudo(function (matchIndexes, length, argument) {
                        var i = argument < 0 ? argument + length : argument;
                        for (; ++i < length;) {
                            matchIndexes.push(i)
                        }
                        return matchIndexes
                    })
                }
            };
            Expr.pseudos.nth = Expr.pseudos.eq;
            for (i in{radio: !0, checkbox: !0, file: !0, password: !0, image: !0}) {
                Expr.pseudos[i] = createInputPseudo(i)
            }
            for (i in{submit: !0, reset: !0}) {
                Expr.pseudos[i] = createButtonPseudo(i)
            }

            function setFilters() {
            }

            setFilters.prototype = Expr.filters = Expr.pseudos;
            Expr.setFilters = new setFilters();
            tokenize = Sizzle.tokenize = function (selector, parseOnly) {
                var matched, match, tokens, type, soFar, groups, preFilters, cached = tokenCache[selector + " "];
                if (cached) {
                    return parseOnly ? 0 : cached.slice(0)
                }
                soFar = selector;
                groups = [];
                preFilters = Expr.preFilter;
                while (soFar) {
                    if (!matched || (match = rcomma.exec(soFar))) {
                        if (match) {
                            soFar = soFar.slice(match[0].length) || soFar
                        }
                        groups.push(tokens = [])
                    }
                    matched = !1;
                    if (match = rcombinators.exec(soFar)) {
                        matched = match.shift();
                        tokens.push({value: matched, type: match[0].replace(rtrim, " ")});
                        soFar = soFar.slice(matched.length)
                    }
                    for (type in Expr.filter) {
                        if ((match = matchExpr[type].exec(soFar)) && (!preFilters[type] || (match = preFilters[type](match)))) {
                            matched = match.shift();
                            tokens.push({value: matched, type: type, matches: match});
                            soFar = soFar.slice(matched.length)
                        }
                    }
                    if (!matched) {
                        break
                    }
                }
                return parseOnly ? soFar.length : soFar ? Sizzle.error(selector) : tokenCache(selector, groups).slice(0)
            };

            function toSelector(tokens) {
                var i = 0, len = tokens.length, selector = "";
                for (; i < len; i++) {
                    selector += tokens[i].value
                }
                return selector
            }

            function addCombinator(matcher, combinator, base) {
                var dir = combinator.dir, checkNonElements = base && dir === "parentNode", doneName = done++;
                return combinator.first ? function (elem, context, xml) {
                    while (elem = elem[dir]) {
                        if (elem.nodeType === 1 || checkNonElements) {
                            return matcher(elem, context, xml)
                        }
                    }
                } : function (elem, context, xml) {
                    var oldCache, uniqueCache, outerCache, newCache = [dirruns, doneName];
                    if (xml) {
                        while (elem = elem[dir]) {
                            if (elem.nodeType === 1 || checkNonElements) {
                                if (matcher(elem, context, xml)) {
                                    return !0
                                }
                            }
                        }
                    } else {
                        while (elem = elem[dir]) {
                            if (elem.nodeType === 1 || checkNonElements) {
                                outerCache = elem[expando] || (elem[expando] = {});
                                uniqueCache = outerCache[elem.uniqueID] || (outerCache[elem.uniqueID] = {});
                                if ((oldCache = uniqueCache[dir]) && oldCache[0] === dirruns && oldCache[1] === doneName) {
                                    return newCache[2] = oldCache[2]
                                } else {
                                    uniqueCache[dir] = newCache;
                                    if (newCache[2] = matcher(elem, context, xml)) {
                                        return !0
                                    }
                                }
                            }
                        }
                    }
                }
            }

            function elementMatcher(matchers) {
                return matchers.length > 1 ? function (elem, context, xml) {
                    var i = matchers.length;
                    while (i--) {
                        if (!matchers[i](elem, context, xml)) {
                            return !1
                        }
                    }
                    return !0
                } : matchers[0]
            }

            function multipleContexts(selector, contexts, results) {
                var i = 0, len = contexts.length;
                for (; i < len; i++) {
                    Sizzle(selector, contexts[i], results)
                }
                return results
            }

            function condense(unmatched, map, filter, context, xml) {
                var elem, newUnmatched = [], i = 0, len = unmatched.length, mapped = map != null;
                for (; i < len; i++) {
                    if (elem = unmatched[i]) {
                        if (!filter || filter(elem, context, xml)) {
                            newUnmatched.push(elem);
                            if (mapped) {
                                map.push(i)
                            }
                        }
                    }
                }
                return newUnmatched
            }

            function setMatcher(preFilter, selector, matcher, postFilter, postFinder, postSelector) {
                if (postFilter && !postFilter[expando]) {
                    postFilter = setMatcher(postFilter)
                }
                if (postFinder && !postFinder[expando]) {
                    postFinder = setMatcher(postFinder, postSelector)
                }
                return markFunction(function (seed, results, context, xml) {
                    var temp, i, elem, preMap = [], postMap = [], preexisting = results.length,
                        elems = seed || multipleContexts(selector || "*", context.nodeType ? [context] : context, []),
                        matcherIn = preFilter && (seed || !selector) ? condense(elems, preMap, preFilter, context, xml) : elems,
                        matcherOut = matcher ? postFinder || (seed ? preFilter : preexisting || postFilter) ? [] : results : matcherIn;
                    if (matcher) {
                        matcher(matcherIn, matcherOut, context, xml)
                    }
                    if (postFilter) {
                        temp = condense(matcherOut, postMap);
                        postFilter(temp, [], context, xml);
                        i = temp.length;
                        while (i--) {
                            if (elem = temp[i]) {
                                matcherOut[postMap[i]] = !(matcherIn[postMap[i]] = elem)
                            }
                        }
                    }
                    if (seed) {
                        if (postFinder || preFilter) {
                            if (postFinder) {
                                temp = [];
                                i = matcherOut.length;
                                while (i--) {
                                    if (elem = matcherOut[i]) {
                                        temp.push(matcherIn[i] = elem)
                                    }
                                }
                                postFinder(null, matcherOut = [], temp, xml)
                            }
                            i = matcherOut.length;
                            while (i--) {
                                if ((elem = matcherOut[i]) && (temp = postFinder ? indexOf(seed, elem) : preMap[i]) > -1) {
                                    seed[temp] = !(results[temp] = elem)
                                }
                            }
                        }
                    } else {
                        matcherOut = condense(matcherOut === results ? matcherOut.splice(preexisting, matcherOut.length) : matcherOut);
                        if (postFinder) {
                            postFinder(null, results, matcherOut, xml)
                        } else {
                            push.apply(results, matcherOut)
                        }
                    }
                })
            }

            function matcherFromTokens(tokens) {
                var checkContext, matcher, j, len = tokens.length, leadingRelative = Expr.relative[tokens[0].type],
                    implicitRelative = leadingRelative || Expr.relative[" "], i = leadingRelative ? 1 : 0,
                    matchContext = addCombinator(function (elem) {
                        return elem === checkContext
                    }, implicitRelative, !0), matchAnyContext = addCombinator(function (elem) {
                        return indexOf(checkContext, elem) > -1
                    }, implicitRelative, !0), matchers = [function (elem, context, xml) {
                        var ret = !leadingRelative && (xml || context !== outermostContext) || ((checkContext = context).nodeType ? matchContext(elem, context, xml) : matchAnyContext(elem, context, xml));
                        checkContext = null;
                        return ret
                    }];
                for (; i < len; i++) {
                    if (matcher = Expr.relative[tokens[i].type]) {
                        matchers = [addCombinator(elementMatcher(matchers), matcher)]
                    } else {
                        matcher = Expr.filter[tokens[i].type].apply(null, tokens[i].matches);
                        if (matcher[expando]) {
                            j = ++i;
                            for (; j < len; j++) {
                                if (Expr.relative[tokens[j].type]) {
                                    break
                                }
                            }
                            return setMatcher(i > 1 && elementMatcher(matchers), i > 1 && toSelector(tokens.slice(0, i - 1).concat({value: tokens[i - 2].type === " " ? "*" : ""})).replace(rtrim, "$1"), matcher, i < j && matcherFromTokens(tokens.slice(i, j)), j < len && matcherFromTokens(tokens = tokens.slice(j)), j < len && toSelector(tokens))
                        }
                        matchers.push(matcher)
                    }
                }
                return elementMatcher(matchers)
            }

            function matcherFromGroupMatchers(elementMatchers, setMatchers) {
                var bySet = setMatchers.length > 0, byElement = elementMatchers.length > 0,
                    superMatcher = function superMatcher(seed, context, xml, results, outermost) {
                        var elem, j, matcher, matchedCount = 0, i = "0", unmatched = seed && [], setMatched = [],
                            contextBackup = outermostContext,
                            elems = seed || byElement && Expr.find.TAG("*", outermost),
                            dirrunsUnique = dirruns += contextBackup == null ? 1 : Math.random() || 0.1,
                            len = elems.length;
                        if (outermost) {
                            outermostContext = context === document || context || outermost
                        }
                        for (; i !== len && (elem = elems[i]) != null; i++) {
                            if (byElement && elem) {
                                j = 0;
                                if (!context && elem.ownerDocument !== document) {
                                    setDocument(elem);
                                    xml = !documentIsHTML
                                }
                                while (matcher = elementMatchers[j++]) {
                                    if (matcher(elem, context || document, xml)) {
                                        results.push(elem);
                                        break
                                    }
                                }
                                if (outermost) {
                                    dirruns = dirrunsUnique
                                }
                            }
                            if (bySet) {
                                if (elem = !matcher && elem) {
                                    matchedCount--
                                }
                                if (seed) {
                                    unmatched.push(elem)
                                }
                            }
                        }
                        matchedCount += i;
                        if (bySet && i !== matchedCount) {
                            j = 0;
                            while (matcher = setMatchers[j++]) {
                                matcher(unmatched, setMatched, context, xml)
                            }
                            if (seed) {
                                if (matchedCount > 0) {
                                    while (i--) {
                                        if (!(unmatched[i] || setMatched[i])) {
                                            setMatched[i] = pop.call(results)
                                        }
                                    }
                                }
                                setMatched = condense(setMatched)
                            }
                            push.apply(results, setMatched);
                            if (outermost && !seed && setMatched.length > 0 && matchedCount + setMatchers.length > 1) {
                                Sizzle.uniqueSort(results)
                            }
                        }
                        if (outermost) {
                            dirruns = dirrunsUnique;
                            outermostContext = contextBackup
                        }
                        return unmatched
                    };
                return bySet ? markFunction(superMatcher) : superMatcher
            }

            compile = Sizzle.compile = function (selector, match) {
                var i, setMatchers = [], elementMatchers = [], cached = compilerCache[selector + " "];
                if (!cached) {
                    if (!match) {
                        match = tokenize(selector)
                    }
                    i = match.length;
                    while (i--) {
                        cached = matcherFromTokens(match[i]);
                        if (cached[expando]) {
                            setMatchers.push(cached)
                        } else {
                            elementMatchers.push(cached)
                        }
                    }
                    cached = compilerCache(selector, matcherFromGroupMatchers(elementMatchers, setMatchers));
                    cached.selector = selector
                }
                return cached
            };
            select = Sizzle.select = function (selector, context, results, seed) {
                var i, tokens, token, type, find, compiled = typeof selector === "function" && selector,
                    match = !seed && tokenize(selector = compiled.selector || selector);
                results = results || [];
                if (match.length === 1) {
                    tokens = match[0] = match[0].slice(0);
                    if (tokens.length > 2 && (token = tokens[0]).type === "ID" && support.getById && context.nodeType === 9 && documentIsHTML && Expr.relative[tokens[1].type]) {
                        context = (Expr.find.ID(token.matches[0].replace(runescape, funescape), context) || [])[0];
                        if (!context) {
                            return results
                        } else if (compiled) {
                            context = context.parentNode
                        }
                        selector = selector.slice(tokens.shift().value.length)
                    }
                    i = matchExpr.needsContext.test(selector) ? 0 : tokens.length;
                    while (i--) {
                        token = tokens[i];
                        if (Expr.relative[type = token.type]) {
                            break
                        }
                        if (find = Expr.find[type]) {
                            if (seed = find(token.matches[0].replace(runescape, funescape), rsibling.test(tokens[0].type) && testContext(context.parentNode) || context)) {
                                tokens.splice(i, 1);
                                selector = seed.length && toSelector(tokens);
                                if (!selector) {
                                    push.apply(results, seed);
                                    return results
                                }
                                break
                            }
                        }
                    }
                }
                (compiled || compile(selector, match))(seed, context, !documentIsHTML, results, !context || rsibling.test(selector) && testContext(context.parentNode) || context);
                return results
            };
            support.sortStable = expando.split("").sort(sortOrder).join("") === expando;
            support.detectDuplicates = !!hasDuplicate;
            setDocument();
            support.sortDetached = assert(function (div1) {
                return div1.compareDocumentPosition(document.createElement("div")) & 1
            });
            if (!assert(function (div) {
                    div.innerHTML = "<a href='#'></a>";
                    return div.firstChild.getAttribute("href") === "#"
                })) {
                addHandle("type|href|height|width", function (elem, name, isXML) {
                    if (!isXML) {
                        return elem.getAttribute(name, name.toLowerCase() === "type" ? 1 : 2)
                    }
                })
            }
            if (!support.attributes || !assert(function (div) {
                    div.innerHTML = "<input/>";
                    div.firstChild.setAttribute("value", "");
                    return div.firstChild.getAttribute("value") === ""
                })) {
                addHandle("value", function (elem, name, isXML) {
                    if (!isXML && elem.nodeName.toLowerCase() === "input") {
                        return elem.defaultValue
                    }
                })
            }
            if (!assert(function (div) {
                    return div.getAttribute("disabled") == null
                })) {
                addHandle(booleans, function (elem, name, isXML) {
                    var val;
                    if (!isXML) {
                        return elem[name] === !0 ? name.toLowerCase() : (val = elem.getAttributeNode(name)) && val.specified ? val.value : null
                    }
                })
            }
            return Sizzle
        })(window);
        jQuery.find = Sizzle;
        jQuery.expr = Sizzle.selectors;
        jQuery.expr[":"] = jQuery.expr.pseudos;
        jQuery.uniqueSort = jQuery.unique = Sizzle.uniqueSort;
        jQuery.text = Sizzle.getText;
        jQuery.isXMLDoc = Sizzle.isXML;
        jQuery.contains = Sizzle.contains;
        var dir = function dir(elem, _dir, until) {
            var matched = [], truncate = until !== undefined;
            while ((elem = elem[_dir]) && elem.nodeType !== 9) {
                if (elem.nodeType === 1) {
                    if (truncate && jQuery(elem).is(until)) {
                        break
                    }
                    matched.push(elem)
                }
            }
            return matched
        };
        var _siblings = function _siblings(n, elem) {
            var matched = [];
            for (; n; n = n.nextSibling) {
                if (n.nodeType === 1 && n !== elem) {
                    matched.push(n)
                }
            }
            return matched
        };
        var rneedsContext = jQuery.expr.match.needsContext;
        var rsingleTag = /^<([\w-]+)\s*\/?>(?:<\/\1>|)$/;
        var risSimple = /^.[^:#\[\.,]*$/;

        function winnow(elements, qualifier, not) {
            if (jQuery.isFunction(qualifier)) {
                return jQuery.grep(elements, function (elem, i) {
                    return !!qualifier.call(elem, i, elem) !== not
                })
            }
            if (qualifier.nodeType) {
                return jQuery.grep(elements, function (elem) {
                    return elem === qualifier !== not
                })
            }
            if (typeof qualifier === "string") {
                if (risSimple.test(qualifier)) {
                    return jQuery.filter(qualifier, elements, not)
                }
                qualifier = jQuery.filter(qualifier, elements)
            }
            return jQuery.grep(elements, function (elem) {
                return indexOf.call(qualifier, elem) > -1 !== not
            })
        }

        jQuery.filter = function (expr, elems, not) {
            var elem = elems[0];
            if (not) {
                expr = ":not(" + expr + ")"
            }
            return elems.length === 1 && elem.nodeType === 1 ? jQuery.find.matchesSelector(elem, expr) ? [elem] : [] : jQuery.find.matches(expr, jQuery.grep(elems, function (elem) {
                return elem.nodeType === 1
            }))
        };
        jQuery.fn.extend({
            find: function find(selector) {
                var i, len = this.length, ret = [], self = this;
                if (typeof selector !== "string") {
                    return this.pushStack(jQuery(selector).filter(function () {
                        for (i = 0; i < len; i++) {
                            if (jQuery.contains(self[i], this)) {
                                return !0
                            }
                        }
                    }))
                }
                for (i = 0; i < len; i++) {
                    jQuery.find(selector, self[i], ret)
                }
                ret = this.pushStack(len > 1 ? jQuery.unique(ret) : ret);
                ret.selector = this.selector ? this.selector + " " + selector : selector;
                return ret
            }, filter: function filter(selector) {
                return this.pushStack(winnow(this, selector || [], !1))
            }, not: function not(selector) {
                return this.pushStack(winnow(this, selector || [], !0))
            }, is: function is(selector) {
                return !!winnow(this, typeof selector === "string" && rneedsContext.test(selector) ? jQuery(selector) : selector || [], !1).length
            }
        });
        var rootjQuery, rquickExpr = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,
            init = jQuery.fn.init = function (selector, context, root) {
                var match, elem;
                if (!selector) {
                    return this
                }
                root = root || rootjQuery;
                if (typeof selector === "string") {
                    if (selector[0] === "<" && selector[selector.length - 1] === ">" && selector.length >= 3) {
                        match = [null, selector, null]
                    } else {
                        match = rquickExpr.exec(selector)
                    }
                    if (match && (match[1] || !context)) {
                        if (match[1]) {
                            context = context instanceof jQuery ? context[0] : context;
                            jQuery.merge(this, jQuery.parseHTML(match[1], context && context.nodeType ? context.ownerDocument || context : document, !0));
                            if (rsingleTag.test(match[1]) && jQuery.isPlainObject(context)) {
                                for (match in context) {
                                    if (jQuery.isFunction(this[match])) {
                                        this[match](context[match])
                                    } else {
                                        this.attr(match, context[match])
                                    }
                                }
                            }
                            return this
                        } else {
                            elem = document.getElementById(match[2]);
                            if (elem && elem.parentNode) {
                                this.length = 1;
                                this[0] = elem
                            }
                            this.context = document;
                            this.selector = selector;
                            return this
                        }
                    } else if (!context || context.jquery) {
                        return (context || root).find(selector)
                    } else {
                        return this.constructor(context).find(selector)
                    }
                } else if (selector.nodeType) {
                    this.context = this[0] = selector;
                    this.length = 1;
                    return this
                } else if (jQuery.isFunction(selector)) {
                    return root.ready !== undefined ? root.ready(selector) : selector(jQuery)
                }
                if (selector.selector !== undefined) {
                    this.selector = selector.selector;
                    this.context = selector.context
                }
                return jQuery.makeArray(selector, this)
            };
        init.prototype = jQuery.fn;
        rootjQuery = jQuery(document);
        var rparentsprev = /^(?:parents|prev(?:Until|All))/,
            guaranteedUnique = {children: !0, contents: !0, next: !0, prev: !0};
        jQuery.fn.extend({
            has: function has(target) {
                var targets = jQuery(target, this), l = targets.length;
                return this.filter(function () {
                    var i = 0;
                    for (; i < l; i++) {
                        if (jQuery.contains(this, targets[i])) {
                            return !0
                        }
                    }
                })
            }, closest: function closest(selectors, context) {
                var cur, i = 0, l = this.length, matched = [],
                    pos = rneedsContext.test(selectors) || typeof selectors !== "string" ? jQuery(selectors, context || this.context) : 0;
                for (; i < l; i++) {
                    for (cur = this[i]; cur && cur !== context; cur = cur.parentNode) {
                        if (cur.nodeType < 11 && (pos ? pos.index(cur) > -1 : cur.nodeType === 1 && jQuery.find.matchesSelector(cur, selectors))) {
                            matched.push(cur);
                            break
                        }
                    }
                }
                return this.pushStack(matched.length > 1 ? jQuery.uniqueSort(matched) : matched)
            }, index: function index(elem) {
                if (!elem) {
                    return this[0] && this[0].parentNode ? this.first().prevAll().length : -1
                }
                if (typeof elem === "string") {
                    return indexOf.call(jQuery(elem), this[0])
                }
                return indexOf.call(this, elem.jquery ? elem[0] : elem)
            }, add: function add(selector, context) {
                return this.pushStack(jQuery.uniqueSort(jQuery.merge(this.get(), jQuery(selector, context))))
            }, addBack: function addBack(selector) {
                return this.add(selector == null ? this.prevObject : this.prevObject.filter(selector))
            }
        });

        function sibling(cur, dir) {
            while ((cur = cur[dir]) && cur.nodeType !== 1) {
            }
            return cur
        }

        jQuery.each({
            parent: function parent(elem) {
                var parent = elem.parentNode;
                return parent && parent.nodeType !== 11 ? parent : null
            }, parents: function parents(elem) {
                return dir(elem, "parentNode")
            }, parentsUntil: function parentsUntil(elem, i, until) {
                return dir(elem, "parentNode", until)
            }, next: function next(elem) {
                return sibling(elem, "nextSibling")
            }, prev: function prev(elem) {
                return sibling(elem, "previousSibling")
            }, nextAll: function nextAll(elem) {
                return dir(elem, "nextSibling")
            }, prevAll: function prevAll(elem) {
                return dir(elem, "previousSibling")
            }, nextUntil: function nextUntil(elem, i, until) {
                return dir(elem, "nextSibling", until)
            }, prevUntil: function prevUntil(elem, i, until) {
                return dir(elem, "previousSibling", until)
            }, siblings: function siblings(elem) {
                return _siblings((elem.parentNode || {}).firstChild, elem)
            }, children: function children(elem) {
                return _siblings(elem.firstChild)
            }, contents: function contents(elem) {
                return elem.contentDocument || jQuery.merge([], elem.childNodes)
            }
        }, function (name, fn) {
            jQuery.fn[name] = function (until, selector) {
                var matched = jQuery.map(this, fn, until);
                if (name.slice(-5) !== "Until") {
                    selector = until
                }
                if (selector && typeof selector === "string") {
                    matched = jQuery.filter(selector, matched)
                }
                if (this.length > 1) {
                    if (!guaranteedUnique[name]) {
                        jQuery.uniqueSort(matched)
                    }
                    if (rparentsprev.test(name)) {
                        matched.reverse()
                    }
                }
                return this.pushStack(matched)
            }
        });
        var rnotwhite = /\S+/g;

        function createOptions(options) {
            var object = {};
            jQuery.each(options.match(rnotwhite) || [], function (_, flag) {
                object[flag] = !0
            });
            return object
        }

        jQuery.Callbacks = function (options) {
            options = typeof options === "string" ? createOptions(options) : jQuery.extend({}, options);
            var firing, memory, _fired, _locked, list = [], queue = [], firingIndex = -1, fire = function fire() {
                _locked = options.once;
                _fired = firing = !0;
                for (; queue.length; firingIndex = -1) {
                    memory = queue.shift();
                    while (++firingIndex < list.length) {
                        if (list[firingIndex].apply(memory[0], memory[1]) === !1 && options.stopOnFalse) {
                            firingIndex = list.length;
                            memory = !1
                        }
                    }
                }
                if (!options.memory) {
                    memory = !1
                }
                firing = !1;
                if (_locked) {
                    if (memory) {
                        list = []
                    } else {
                        list = ""
                    }
                }
            }, self = {
                add: function add() {
                    if (list) {
                        if (memory && !firing) {
                            firingIndex = list.length - 1;
                            queue.push(memory)
                        }
                        (function add(args) {
                            jQuery.each(args, function (_, arg) {
                                if (jQuery.isFunction(arg)) {
                                    if (!options.unique || !self.has(arg)) {
                                        list.push(arg)
                                    }
                                } else if (arg && arg.length && jQuery.type(arg) !== "string") {
                                    add(arg)
                                }
                            })
                        })(arguments);
                        if (memory && !firing) {
                            fire()
                        }
                    }
                    return this
                }, remove: function remove() {
                    jQuery.each(arguments, function (_, arg) {
                        var index;
                        while ((index = jQuery.inArray(arg, list, index)) > -1) {
                            list.splice(index, 1);
                            if (index <= firingIndex) {
                                firingIndex--
                            }
                        }
                    });
                    return this
                }, has: function has(fn) {
                    return fn ? jQuery.inArray(fn, list) > -1 : list.length > 0
                }, empty: function empty() {
                    if (list) {
                        list = []
                    }
                    return this
                }, disable: function disable() {
                    _locked = queue = [];
                    list = memory = "";
                    return this
                }, disabled: function disabled() {
                    return !list
                }, lock: function lock() {
                    _locked = queue = [];
                    if (!memory) {
                        list = memory = ""
                    }
                    return this
                }, locked: function locked() {
                    return !!_locked
                }, fireWith: function fireWith(context, args) {
                    if (!_locked) {
                        args = args || [];
                        args = [context, args.slice ? args.slice() : args];
                        queue.push(args);
                        if (!firing) {
                            fire()
                        }
                    }
                    return this
                }, fire: function fire() {
                    self.fireWith(this, arguments);
                    return this
                }, fired: function fired() {
                    return !!_fired
                }
            };
            return self
        };
        jQuery.extend({
            Deferred: function Deferred(func) {
                var tuples = [["resolve", "done", jQuery.Callbacks("once memory"), "resolved"], ["reject", "fail", jQuery.Callbacks("once memory"), "rejected"], ["notify", "progress", jQuery.Callbacks("memory")]],
                    _state = "pending", _promise = {
                        state: function state() {
                            return _state
                        }, always: function always() {
                            deferred.done(arguments).fail(arguments);
                            return this
                        }, then: function then() {
                            var fns = arguments;
                            return jQuery.Deferred(function (newDefer) {
                                jQuery.each(tuples, function (i, tuple) {
                                    var fn = jQuery.isFunction(fns[i]) && fns[i];
                                    deferred[tuple[1]](function () {
                                        var returned = fn && fn.apply(this, arguments);
                                        if (returned && jQuery.isFunction(returned.promise)) {
                                            returned.promise().progress(newDefer.notify).done(newDefer.resolve).fail(newDefer.reject)
                                        } else {
                                            newDefer[tuple[0] + "With"](this === _promise ? newDefer.promise() : this, fn ? [returned] : arguments)
                                        }
                                    })
                                });
                                fns = null
                            }).promise()
                        }, promise: function promise(obj) {
                            return obj != null ? jQuery.extend(obj, _promise) : _promise
                        }
                    }, deferred = {};
                _promise.pipe = _promise.then;
                jQuery.each(tuples, function (i, tuple) {
                    var list = tuple[2], stateString = tuple[3];
                    _promise[tuple[1]] = list.add;
                    if (stateString) {
                        list.add(function () {
                            _state = stateString
                        }, tuples[i ^ 1][2].disable, tuples[2][2].lock)
                    }
                    deferred[tuple[0]] = function () {
                        deferred[tuple[0] + "With"](this === deferred ? _promise : this, arguments);
                        return this
                    };
                    deferred[tuple[0] + "With"] = list.fireWith
                });
                _promise.promise(deferred);
                if (func) {
                    func.call(deferred, deferred)
                }
                return deferred
            }, when: function when(subordinate) {
                var i = 0, resolveValues = _slice.call(arguments), length = resolveValues.length,
                    remaining = length !== 1 || subordinate && jQuery.isFunction(subordinate.promise) ? length : 0,
                    deferred = remaining === 1 ? subordinate : jQuery.Deferred(),
                    updateFunc = function updateFunc(i, contexts, values) {
                        return function (value) {
                            contexts[i] = this;
                            values[i] = arguments.length > 1 ? _slice.call(arguments) : value;
                            if (values === progressValues) {
                                deferred.notifyWith(contexts, values)
                            } else if (!--remaining) {
                                deferred.resolveWith(contexts, values)
                            }
                        }
                    }, progressValues, progressContexts, resolveContexts;
                if (length > 1) {
                    progressValues = new Array(length);
                    progressContexts = new Array(length);
                    resolveContexts = new Array(length);
                    for (; i < length; i++) {
                        if (resolveValues[i] && jQuery.isFunction(resolveValues[i].promise)) {
                            resolveValues[i].promise().progress(updateFunc(i, progressContexts, progressValues)).done(updateFunc(i, resolveContexts, resolveValues)).fail(deferred.reject)
                        } else {
                            --remaining
                        }
                    }
                }
                if (!remaining) {
                    deferred.resolveWith(resolveContexts, resolveValues)
                }
                return deferred.promise()
            }
        });
        var readyList;
        jQuery.fn.ready = function (fn) {
            jQuery.ready.promise().done(fn);
            return this
        };
        jQuery.extend({
            isReady: !1, readyWait: 1, holdReady: function holdReady(hold) {
                if (hold) {
                    jQuery.readyWait++
                } else {
                    jQuery.ready(!0)
                }
            }, ready: function ready(wait) {
                if (wait === !0 ? --jQuery.readyWait : jQuery.isReady) {
                    return
                }
                jQuery.isReady = !0;
                if (wait !== !0 && --jQuery.readyWait > 0) {
                    return
                }
                readyList.resolveWith(document, [jQuery]);
                if (jQuery.fn.triggerHandler) {
                    jQuery(document).triggerHandler("ready");
                    jQuery(document).off("ready")
                }
            }
        });

        function completed() {
            document.removeEventListener("DOMContentLoaded", completed);
            window.removeEventListener("load", completed);
            jQuery.ready()
        }

        jQuery.ready.promise = function (obj) {
            if (!readyList) {
                readyList = jQuery.Deferred();
                if (document.readyState === "complete" || document.readyState !== "loading" && !document.documentElement.doScroll) {
                    window.setTimeout(jQuery.ready)
                } else {
                    document.addEventListener("DOMContentLoaded", completed);
                    window.addEventListener("load", completed)
                }
            }
            return readyList.promise(obj)
        };
        jQuery.ready.promise();
        var access = function access(elems, fn, key, value, chainable, emptyGet, raw) {
            var i = 0, len = elems.length, bulk = key == null;
            if (jQuery.type(key) === "object") {
                chainable = !0;
                for (i in key) {
                    access(elems, fn, i, key[i], !0, emptyGet, raw)
                }
            } else if (value !== undefined) {
                chainable = !0;
                if (!jQuery.isFunction(value)) {
                    raw = !0
                }
                if (bulk) {
                    if (raw) {
                        fn.call(elems, value);
                        fn = null
                    } else {
                        bulk = fn;
                        fn = function (elem, key, value) {
                            return bulk.call(jQuery(elem), value)
                        }
                    }
                }
                if (fn) {
                    for (; i < len; i++) {
                        fn(elems[i], key, raw ? value : value.call(elems[i], i, fn(elems[i], key)))
                    }
                }
            }
            return chainable ? elems : bulk ? fn.call(elems) : len ? fn(elems[0], key) : emptyGet
        };
        var acceptData = function acceptData(owner) {
            return owner.nodeType === 1 || owner.nodeType === 9 || !+owner.nodeType
        };

        function Data() {
            this.expando = jQuery.expando + Data.uid++
        }

        Data.uid = 1;
        Data.prototype = {
            register: function register(owner, initial) {
                var value = initial || {};
                if (owner.nodeType) {
                    owner[this.expando] = value
                } else {
                    Object.defineProperty(owner, this.expando, {value: value, writable: !0, configurable: !0})
                }
                return owner[this.expando]
            }, cache: function cache(owner) {
                if (!acceptData(owner)) {
                    return {}
                }
                var value = owner[this.expando];
                if (!value) {
                    value = {};
                    if (acceptData(owner)) {
                        if (owner.nodeType) {
                            owner[this.expando] = value
                        } else {
                            Object.defineProperty(owner, this.expando, {value: value, configurable: !0})
                        }
                    }
                }
                return value
            }, set: function set(owner, data, value) {
                var prop, cache = this.cache(owner);
                if (typeof data === "string") {
                    cache[data] = value
                } else {
                    for (prop in data) {
                        cache[prop] = data[prop]
                    }
                }
                return cache
            }, get: function get(owner, key) {
                return key === undefined ? this.cache(owner) : owner[this.expando] && owner[this.expando][key]
            }, access: function access(owner, key, value) {
                var stored;
                if (key === undefined || key && typeof key === "string" && value === undefined) {
                    stored = this.get(owner, key);
                    return stored !== undefined ? stored : this.get(owner, jQuery.camelCase(key))
                }
                this.set(owner, key, value);
                return value !== undefined ? value : key
            }, remove: function remove(owner, key) {
                var i, name, camel, cache = owner[this.expando];
                if (cache === undefined) {
                    return
                }
                if (key === undefined) {
                    this.register(owner)
                } else {
                    if (jQuery.isArray(key)) {
                        name = key.concat(key.map(jQuery.camelCase))
                    } else {
                        camel = jQuery.camelCase(key);
                        if (key in cache) {
                            name = [key, camel]
                        } else {
                            name = camel;
                            name = name in cache ? [name] : name.match(rnotwhite) || []
                        }
                    }
                    i = name.length;
                    while (i--) {
                        delete cache[name[i]]
                    }
                }
                if (key === undefined || jQuery.isEmptyObject(cache)) {
                    if (owner.nodeType) {
                        owner[this.expando] = undefined
                    } else {
                        delete owner[this.expando]
                    }
                }
            }, hasData: function hasData(owner) {
                var cache = owner[this.expando];
                return cache !== undefined && !jQuery.isEmptyObject(cache)
            }
        };
        var dataPriv = new Data();
        var dataUser = new Data();
        var rbrace = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/, rmultiDash = /[A-Z]/g;

        function dataAttr(elem, key, data) {
            var name;
            if (data === undefined && elem.nodeType === 1) {
                name = "data-" + key.replace(rmultiDash, "-$&").toLowerCase();
                data = elem.getAttribute(name);
                if (typeof data === "string") {
                    try {
                        data = data === "true" ? true : data === "false" ? false : data === "null" ? null : +data + "" === data ? +data : rbrace.test(data) ? jQuery.parseJSON(data) : data
                    } catch (e) {
                    }
                    dataUser.set(elem, key, data)
                } else {
                    data = undefined
                }
            }
            return data
        }

        jQuery.extend({
            hasData: function hasData(elem) {
                return dataUser.hasData(elem) || dataPriv.hasData(elem)
            }, data: function data(elem, name, _data) {
                return dataUser.access(elem, name, _data)
            }, removeData: function removeData(elem, name) {
                dataUser.remove(elem, name)
            }, _data: function _data(elem, name, data) {
                return dataPriv.access(elem, name, data)
            }, _removeData: function _removeData(elem, name) {
                dataPriv.remove(elem, name)
            }
        });
        jQuery.fn.extend({
            data: function data(key, value) {
                var i, name, data, elem = this[0], attrs = elem && elem.attributes;
                if (key === undefined) {
                    if (this.length) {
                        data = dataUser.get(elem);
                        if (elem.nodeType === 1 && !dataPriv.get(elem, "hasDataAttrs")) {
                            i = attrs.length;
                            while (i--) {
                                if (attrs[i]) {
                                    name = attrs[i].name;
                                    if (name.indexOf("data-") === 0) {
                                        name = jQuery.camelCase(name.slice(5));
                                        dataAttr(elem, name, data[name])
                                    }
                                }
                            }
                            dataPriv.set(elem, "hasDataAttrs", !0)
                        }
                    }
                    return data
                }
                if (typeof key === "object") {
                    return this.each(function () {
                        dataUser.set(this, key)
                    })
                }
                return access(this, function (value) {
                    var data, camelKey;
                    if (elem && value === undefined) {
                        data = dataUser.get(elem, key) || dataUser.get(elem, key.replace(rmultiDash, "-$&").toLowerCase());
                        if (data !== undefined) {
                            return data
                        }
                        camelKey = jQuery.camelCase(key);
                        data = dataUser.get(elem, camelKey);
                        if (data !== undefined) {
                            return data
                        }
                        data = dataAttr(elem, camelKey, undefined);
                        if (data !== undefined) {
                            return data
                        }
                        return
                    }
                    camelKey = jQuery.camelCase(key);
                    this.each(function () {
                        var data = dataUser.get(this, camelKey);
                        dataUser.set(this, camelKey, value);
                        if (key.indexOf("-") > -1 && data !== undefined) {
                            dataUser.set(this, key, value)
                        }
                    })
                }, null, value, arguments.length > 1, null, !0)
            }, removeData: function removeData(key) {
                return this.each(function () {
                    dataUser.remove(this, key)
                })
            }
        });
        jQuery.extend({
            queue: function queue(elem, type, data) {
                var queue;
                if (elem) {
                    type = (type || "fx") + "queue";
                    queue = dataPriv.get(elem, type);
                    if (data) {
                        if (!queue || jQuery.isArray(data)) {
                            queue = dataPriv.access(elem, type, jQuery.makeArray(data))
                        } else {
                            queue.push(data)
                        }
                    }
                    return queue || []
                }
            }, dequeue: function dequeue(elem, type) {
                type = type || "fx";
                var queue = jQuery.queue(elem, type), startLength = queue.length, fn = queue.shift(),
                    hooks = jQuery._queueHooks(elem, type), next = function next() {
                        jQuery.dequeue(elem, type)
                    };
                if (fn === "inprogress") {
                    fn = queue.shift();
                    startLength--
                }
                if (fn) {
                    if (type === "fx") {
                        queue.unshift("inprogress")
                    }
                    delete hooks.stop;
                    fn.call(elem, next, hooks)
                }
                if (!startLength && hooks) {
                    hooks.empty.fire()
                }
            }, _queueHooks: function _queueHooks(elem, type) {
                var key = type + "queueHooks";
                return dataPriv.get(elem, key) || dataPriv.access(elem, key, {
                    empty: jQuery.Callbacks("once memory").add(function () {
                        dataPriv.remove(elem, [type + "queue", key])
                    })
                })
            }
        });
        jQuery.fn.extend({
            queue: function queue(type, data) {
                var setter = 2;
                if (typeof type !== "string") {
                    data = type;
                    type = "fx";
                    setter--
                }
                if (arguments.length < setter) {
                    return jQuery.queue(this[0], type)
                }
                return data === undefined ? this : this.each(function () {
                    var queue = jQuery.queue(this, type, data);
                    jQuery._queueHooks(this, type);
                    if (type === "fx" && queue[0] !== "inprogress") {
                        jQuery.dequeue(this, type)
                    }
                })
            }, dequeue: function dequeue(type) {
                return this.each(function () {
                    jQuery.dequeue(this, type)
                })
            }, clearQueue: function clearQueue(type) {
                return this.queue(type || "fx", [])
            }, promise: function promise(type, obj) {
                var tmp, count = 1, defer = jQuery.Deferred(), elements = this, i = this.length,
                    resolve = function resolve() {
                        if (!--count) {
                            defer.resolveWith(elements, [elements])
                        }
                    };
                if (typeof type !== "string") {
                    obj = type;
                    type = undefined
                }
                type = type || "fx";
                while (i--) {
                    tmp = dataPriv.get(elements[i], type + "queueHooks");
                    if (tmp && tmp.empty) {
                        count++;
                        tmp.empty.add(resolve)
                    }
                }
                resolve();
                return defer.promise(obj)
            }
        });
        var pnum = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source;
        var rcssNum = new RegExp("^(?:([+-])=|)(" + pnum + ")([a-z%]*)$", "i");
        var cssExpand = ["Top", "Right", "Bottom", "Left"];
        var isHidden = function isHidden(elem, el) {
            elem = el || elem;
            return jQuery.css(elem, "display") === "none" || !jQuery.contains(elem.ownerDocument, elem)
        };

        function adjustCSS(elem, prop, valueParts, tween) {
            var adjusted, scale = 1, maxIterations = 20, currentValue = tween ? function () {
                    return tween.cur()
                } : function () {
                    return jQuery.css(elem, prop, "")
                }, initial = currentValue(), unit = valueParts && valueParts[3] || (jQuery.cssNumber[prop] ? "" : "px"),
                initialInUnit = (jQuery.cssNumber[prop] || unit !== "px" && +initial) && rcssNum.exec(jQuery.css(elem, prop));
            if (initialInUnit && initialInUnit[3] !== unit) {
                unit = unit || initialInUnit[3];
                valueParts = valueParts || [];
                initialInUnit = +initial || 1;
                do {
                    scale = scale || ".5";
                    initialInUnit = initialInUnit / scale;
                    jQuery.style(elem, prop, initialInUnit + unit)
                } while (scale !== (scale = currentValue() / initial) && scale !== 1 && --maxIterations)
            }
            if (valueParts) {
                initialInUnit = +initialInUnit || +initial || 0;
                adjusted = valueParts[1] ? initialInUnit + (valueParts[1] + 1) * valueParts[2] : +valueParts[2];
                if (tween) {
                    tween.unit = unit;
                    tween.start = initialInUnit;
                    tween.end = adjusted
                }
            }
            return adjusted
        }

        var rcheckableType = /^(?:checkbox|radio)$/i;
        var rtagName = /<([\w:-]+)/;
        var rscriptType = /^$|\/(?:java|ecma)script/i;
        var wrapMap = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            thead: [1, "<table>", "</table>"],
            col: [2, "<table><colgroup>", "</colgroup></table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: [0, "", ""]
        };
        wrapMap.optgroup = wrapMap.option;
        wrapMap.tbody = wrapMap.tfoot = wrapMap.colgroup = wrapMap.caption = wrapMap.thead;
        wrapMap.th = wrapMap.td;

        function getAll(context, tag) {
            var ret = typeof context.getElementsByTagName !== "undefined" ? context.getElementsByTagName(tag || "*") : typeof context.querySelectorAll !== "undefined" ? context.querySelectorAll(tag || "*") : [];
            return tag === undefined || tag && jQuery.nodeName(context, tag) ? jQuery.merge([context], ret) : ret
        }

        function setGlobalEval(elems, refElements) {
            var i = 0, l = elems.length;
            for (; i < l; i++) {
                dataPriv.set(elems[i], "globalEval", !refElements || dataPriv.get(refElements[i], "globalEval"))
            }
        }

        var rhtml = /<|&#?\w+;/;

        function buildFragment(elems, context, scripts, selection, ignored) {
            var elem, tmp, tag, wrap, contains, j, fragment = context.createDocumentFragment(), nodes = [], i = 0,
                l = elems.length;
            for (; i < l; i++) {
                elem = elems[i];
                if (elem || elem === 0) {
                    if (jQuery.type(elem) === "object") {
                        jQuery.merge(nodes, elem.nodeType ? [elem] : elem)
                    } else if (!rhtml.test(elem)) {
                        nodes.push(context.createTextNode(elem))
                    } else {
                        tmp = tmp || fragment.appendChild(context.createElement("div"));
                        tag = (rtagName.exec(elem) || ["", ""])[1].toLowerCase();
                        wrap = wrapMap[tag] || wrapMap._default;
                        tmp.innerHTML = wrap[1] + jQuery.htmlPrefilter(elem) + wrap[2];
                        j = wrap[0];
                        while (j--) {
                            tmp = tmp.lastChild
                        }
                        jQuery.merge(nodes, tmp.childNodes);
                        tmp = fragment.firstChild;
                        tmp.textContent = ""
                    }
                }
            }
            fragment.textContent = "";
            i = 0;
            while (elem = nodes[i++]) {
                if (selection && jQuery.inArray(elem, selection) > -1) {
                    if (ignored) {
                        ignored.push(elem)
                    }
                    continue
                }
                contains = jQuery.contains(elem.ownerDocument, elem);
                tmp = getAll(fragment.appendChild(elem), "script");
                if (contains) {
                    setGlobalEval(tmp)
                }
                if (scripts) {
                    j = 0;
                    while (elem = tmp[j++]) {
                        if (rscriptType.test(elem.type || "")) {
                            scripts.push(elem)
                        }
                    }
                }
            }
            return fragment
        }

        (function () {
            var fragment = document.createDocumentFragment(), div = fragment.appendChild(document.createElement("div")),
                input = document.createElement("input");
            input.setAttribute("type", "radio");
            input.setAttribute("checked", "checked");
            input.setAttribute("name", "t");
            div.appendChild(input);
            support.checkClone = div.cloneNode(!0).cloneNode(!0).lastChild.checked;
            div.innerHTML = "<textarea>x</textarea>";
            support.noCloneChecked = !!div.cloneNode(!0).lastChild.defaultValue
        })();
        var rkeyEvent = /^key/, rmouseEvent = /^(?:mouse|pointer|contextmenu|drag|drop)|click/,
            rtypenamespace = /^([^.]*)(?:\.(.+)|)/;

        function returnTrue() {
            return !0
        }

        function returnFalse() {
            return !1
        }

        function safeActiveElement() {
            try {
                return document.activeElement
            } catch (err) {
            }
        }

        function _on(elem, types, selector, data, fn, one) {
            var origFn, type;
            if (typeof types === "object") {
                if (typeof selector !== "string") {
                    data = data || selector;
                    selector = undefined
                }
                for (type in types) {
                    _on(elem, type, selector, data, types[type], one)
                }
                return elem
            }
            if (data == null && fn == null) {
                fn = selector;
                data = selector = undefined
            } else if (fn == null) {
                if (typeof selector === "string") {
                    fn = data;
                    data = undefined
                } else {
                    fn = data;
                    data = selector;
                    selector = undefined
                }
            }
            if (fn === !1) {
                fn = returnFalse
            } else if (!fn) {
                return elem
            }
            if (one === 1) {
                origFn = fn;
                fn = function (event) {
                    jQuery().off(event);
                    return origFn.apply(this, arguments)
                };
                fn.guid = origFn.guid || (origFn.guid = jQuery.guid++)
            }
            return elem.each(function () {
                jQuery.event.add(this, types, fn, data, selector)
            })
        }

        jQuery.event = {
            global: {},
            add: function add(elem, types, handler, data, selector) {
                var handleObjIn, eventHandle, tmp, events, t, handleObj, special, handlers, type, namespaces, origType,
                    elemData = dataPriv.get(elem);
                if (!elemData) {
                    return
                }
                if (handler.handler) {
                    handleObjIn = handler;
                    handler = handleObjIn.handler;
                    selector = handleObjIn.selector
                }
                if (!handler.guid) {
                    handler.guid = jQuery.guid++
                }
                if (!(events = elemData.events)) {
                    events = elemData.events = {}
                }
                if (!(eventHandle = elemData.handle)) {
                    eventHandle = elemData.handle = function (e) {
                        return typeof jQuery !== "undefined" && jQuery.event.triggered !== e.type ? jQuery.event.dispatch.apply(elem, arguments) : undefined
                    }
                }
                types = (types || "").match(rnotwhite) || [""];
                t = types.length;
                while (t--) {
                    tmp = rtypenamespace.exec(types[t]) || [];
                    type = origType = tmp[1];
                    namespaces = (tmp[2] || "").split(".").sort();
                    if (!type) {
                        continue
                    }
                    special = jQuery.event.special[type] || {};
                    type = (selector ? special.delegateType : special.bindType) || type;
                    special = jQuery.event.special[type] || {};
                    handleObj = jQuery.extend({
                        type: type,
                        origType: origType,
                        data: data,
                        handler: handler,
                        guid: handler.guid,
                        selector: selector,
                        needsContext: selector && jQuery.expr.match.needsContext.test(selector),
                        namespace: namespaces.join(".")
                    }, handleObjIn);
                    if (!(handlers = events[type])) {
                        handlers = events[type] = [];
                        handlers.delegateCount = 0;
                        if (!special.setup || special.setup.call(elem, data, namespaces, eventHandle) === !1) {
                            if (elem.addEventListener) {
                                elem.addEventListener(type, eventHandle)
                            }
                        }
                    }
                    if (special.add) {
                        special.add.call(elem, handleObj);
                        if (!handleObj.handler.guid) {
                            handleObj.handler.guid = handler.guid
                        }
                    }
                    if (selector) {
                        handlers.splice(handlers.delegateCount++, 0, handleObj)
                    } else {
                        handlers.push(handleObj)
                    }
                    jQuery.event.global[type] = !0
                }
            },
            remove: function remove(elem, types, handler, selector, mappedTypes) {
                var j, origCount, tmp, events, t, handleObj, special, handlers, type, namespaces, origType,
                    elemData = dataPriv.hasData(elem) && dataPriv.get(elem);
                if (!elemData || !(events = elemData.events)) {
                    return
                }
                types = (types || "").match(rnotwhite) || [""];
                t = types.length;
                while (t--) {
                    tmp = rtypenamespace.exec(types[t]) || [];
                    type = origType = tmp[1];
                    namespaces = (tmp[2] || "").split(".").sort();
                    if (!type) {
                        for (type in events) {
                            jQuery.event.remove(elem, type + types[t], handler, selector, !0)
                        }
                        continue
                    }
                    special = jQuery.event.special[type] || {};
                    type = (selector ? special.delegateType : special.bindType) || type;
                    handlers = events[type] || [];
                    tmp = tmp[2] && new RegExp("(^|\\.)" + namespaces.join("\\.(?:.*\\.|)") + "(\\.|$)");
                    origCount = j = handlers.length;
                    while (j--) {
                        handleObj = handlers[j];
                        if ((mappedTypes || origType === handleObj.origType) && (!handler || handler.guid === handleObj.guid) && (!tmp || tmp.test(handleObj.namespace)) && (!selector || selector === handleObj.selector || selector === "**" && handleObj.selector)) {
                            handlers.splice(j, 1);
                            if (handleObj.selector) {
                                handlers.delegateCount--
                            }
                            if (special.remove) {
                                special.remove.call(elem, handleObj)
                            }
                        }
                    }
                    if (origCount && !handlers.length) {
                        if (!special.teardown || special.teardown.call(elem, namespaces, elemData.handle) === !1) {
                            jQuery.removeEvent(elem, type, elemData.handle)
                        }
                        delete events[type]
                    }
                }
                if (jQuery.isEmptyObject(events)) {
                    dataPriv.remove(elem, "handle events")
                }
            },
            dispatch: function dispatch(event) {
                event = jQuery.event.fix(event);
                var i, j, ret, matched, handleObj, handlerQueue = [], args = _slice.call(arguments),
                    handlers = (dataPriv.get(this, "events") || {})[event.type] || [],
                    special = jQuery.event.special[event.type] || {};
                args[0] = event;
                event.delegateTarget = this;
                if (special.preDispatch && special.preDispatch.call(this, event) === !1) {
                    return
                }
                handlerQueue = jQuery.event.handlers.call(this, event, handlers);
                i = 0;
                while ((matched = handlerQueue[i++]) && !event.isPropagationStopped()) {
                    event.currentTarget = matched.elem;
                    j = 0;
                    while ((handleObj = matched.handlers[j++]) && !event.isImmediatePropagationStopped()) {
                        if (!event.rnamespace || event.rnamespace.test(handleObj.namespace)) {
                            event.handleObj = handleObj;
                            event.data = handleObj.data;
                            ret = ((jQuery.event.special[handleObj.origType] || {}).handle || handleObj.handler).apply(matched.elem, args);
                            if (ret !== undefined) {
                                if ((event.result = ret) === !1) {
                                    event.preventDefault();
                                    event.stopPropagation()
                                }
                            }
                        }
                    }
                }
                if (special.postDispatch) {
                    special.postDispatch.call(this, event)
                }
                return event.result
            },
            handlers: function handlers(event, _handlers) {
                var i, matches, sel, handleObj, handlerQueue = [], delegateCount = _handlers.delegateCount,
                    cur = event.target;
                if (delegateCount && cur.nodeType && (event.type !== "click" || isNaN(event.button) || event.button < 1)) {
                    for (; cur !== this; cur = cur.parentNode || this) {
                        if (cur.nodeType === 1 && (cur.disabled !== !0 || event.type !== "click")) {
                            matches = [];
                            for (i = 0; i < delegateCount; i++) {
                                handleObj = _handlers[i];
                                sel = handleObj.selector + " ";
                                if (matches[sel] === undefined) {
                                    matches[sel] = handleObj.needsContext ? jQuery(sel, this).index(cur) > -1 : jQuery.find(sel, this, null, [cur]).length
                                }
                                if (matches[sel]) {
                                    matches.push(handleObj)
                                }
                            }
                            if (matches.length) {
                                handlerQueue.push({elem: cur, handlers: matches})
                            }
                        }
                    }
                }
                if (delegateCount < _handlers.length) {
                    handlerQueue.push({elem: this, handlers: _handlers.slice(delegateCount)})
                }
                return handlerQueue
            },
            props: ("altKey bubbles cancelable ctrlKey currentTarget detail eventPhase " + "metaKey relatedTarget shiftKey target timeStamp view which").split(" "),
            fixHooks: {},
            keyHooks: {
                props: "char charCode key keyCode".split(" "), filter: function filter(event, original) {
                    if (event.which == null) {
                        event.which = original.charCode != null ? original.charCode : original.keyCode
                    }
                    return event
                }
            },
            mouseHooks: {
                props: ("button buttons clientX clientY offsetX offsetY pageX pageY " + "screenX screenY toElement").split(" "),
                filter: function filter(event, original) {
                    var eventDoc, doc, body, button = original.button;
                    if (event.pageX == null && original.clientX != null) {
                        eventDoc = event.target.ownerDocument || document;
                        doc = eventDoc.documentElement;
                        body = eventDoc.body;
                        event.pageX = original.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
                        event.pageY = original.clientY + (doc && doc.scrollTop || body && body.scrollTop || 0) - (doc && doc.clientTop || body && body.clientTop || 0)
                    }
                    if (!event.which && button !== undefined) {
                        event.which = button & 1 ? 1 : button & 2 ? 3 : button & 4 ? 2 : 0
                    }
                    return event
                }
            },
            fix: function fix(event) {
                if (event[jQuery.expando]) {
                    return event
                }
                var i, prop, copy, type = event.type, originalEvent = event, fixHook = this.fixHooks[type];
                if (!fixHook) {
                    this.fixHooks[type] = fixHook = rmouseEvent.test(type) ? this.mouseHooks : rkeyEvent.test(type) ? this.keyHooks : {}
                }
                copy = fixHook.props ? this.props.concat(fixHook.props) : this.props;
                event = new jQuery.Event(originalEvent);
                i = copy.length;
                while (i--) {
                    prop = copy[i];
                    event[prop] = originalEvent[prop]
                }
                if (!event.target) {
                    event.target = document
                }
                if (event.target.nodeType === 3) {
                    event.target = event.target.parentNode
                }
                return fixHook.filter ? fixHook.filter(event, originalEvent) : event
            },
            special: {
                load: {noBubble: !0}, focus: {
                    trigger: function trigger() {
                        if (this !== safeActiveElement() && this.focus) {
                            this.focus();
                            return !1
                        }
                    }, delegateType: "focusin"
                }, blur: {
                    trigger: function trigger() {
                        if (this === safeActiveElement() && this.blur) {
                            this.blur();
                            return !1
                        }
                    }, delegateType: "focusout"
                }, click: {
                    trigger: function trigger() {
                        if (this.type === "checkbox" && this.click && jQuery.nodeName(this, "input")) {
                            this.click();
                            return !1
                        }
                    }, _default: function _default(event) {
                        return jQuery.nodeName(event.target, "a")
                    }
                }, beforeunload: {
                    postDispatch: function postDispatch(event) {
                        if (event.result !== undefined && event.originalEvent) {
                            event.originalEvent.returnValue = event.result
                        }
                    }
                }
            }
        };
        jQuery.removeEvent = function (elem, type, handle) {
            if (elem.removeEventListener) {
                elem.removeEventListener(type, handle)
            }
        };
        jQuery.Event = function (src, props) {
            if (!(this instanceof jQuery.Event)) {
                return new jQuery.Event(src, props)
            }
            if (src && src.type) {
                this.originalEvent = src;
                this.type = src.type;
                this.isDefaultPrevented = src.defaultPrevented || src.defaultPrevented === undefined && src.returnValue === !1 ? returnTrue : returnFalse
            } else {
                this.type = src
            }
            if (props) {
                jQuery.extend(this, props)
            }
            this.timeStamp = src && src.timeStamp || jQuery.now();
            this[jQuery.expando] = !0
        };
        jQuery.Event.prototype = {
            constructor: jQuery.Event,
            isDefaultPrevented: returnFalse,
            isPropagationStopped: returnFalse,
            isImmediatePropagationStopped: returnFalse,
            isSimulated: !1,
            preventDefault: function preventDefault() {
                var e = this.originalEvent;
                this.isDefaultPrevented = returnTrue;
                if (e && !this.isSimulated) {
                    e.preventDefault()
                }
            },
            stopPropagation: function stopPropagation() {
                var e = this.originalEvent;
                this.isPropagationStopped = returnTrue;
                if (e && !this.isSimulated) {
                    e.stopPropagation()
                }
            },
            stopImmediatePropagation: function stopImmediatePropagation() {
                var e = this.originalEvent;
                this.isImmediatePropagationStopped = returnTrue;
                if (e && !this.isSimulated) {
                    e.stopImmediatePropagation()
                }
                this.stopPropagation()
            }
        };
        jQuery.each({
            mouseenter: "mouseover",
            mouseleave: "mouseout",
            pointerenter: "pointerover",
            pointerleave: "pointerout"
        }, function (orig, fix) {
            jQuery.event.special[orig] = {
                delegateType: fix, bindType: fix, handle: function handle(event) {
                    var ret, target = this, related = event.relatedTarget, handleObj = event.handleObj;
                    if (!related || related !== target && !jQuery.contains(target, related)) {
                        event.type = handleObj.origType;
                        ret = handleObj.handler.apply(this, arguments);
                        event.type = fix
                    }
                    return ret
                }
            }
        });
        jQuery.fn.extend({
            on: function on(types, selector, data, fn) {
                return _on(this, types, selector, data, fn)
            }, one: function one(types, selector, data, fn) {
                return _on(this, types, selector, data, fn, 1)
            }, off: function off(types, selector, fn) {
                var handleObj, type;
                if (types && types.preventDefault && types.handleObj) {
                    handleObj = types.handleObj;
                    jQuery(types.delegateTarget).off(handleObj.namespace ? handleObj.origType + "." + handleObj.namespace : handleObj.origType, handleObj.selector, handleObj.handler);
                    return this
                }
                if (typeof types === "object") {
                    for (type in types) {
                        this.off(type, selector, types[type])
                    }
                    return this
                }
                if (selector === !1 || typeof selector === "function") {
                    fn = selector;
                    selector = undefined
                }
                if (fn === !1) {
                    fn = returnFalse
                }
                return this.each(function () {
                    jQuery.event.remove(this, types, fn, selector)
                })
            }
        });
        var rxhtmlTag = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:-]+)[^>]*)\/>/gi,
            rnoInnerhtml = /<script|<style|<link/i, rchecked = /checked\s*(?:[^=]|=\s*.checked.)/i,
            rscriptTypeMasked = /^true\/(.*)/, rcleanScript = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;

        function manipulationTarget(elem, content) {
            return jQuery.nodeName(elem, "table") && jQuery.nodeName(content.nodeType !== 11 ? content : content.firstChild, "tr") ? elem.getElementsByTagName("tbody")[0] || elem.appendChild(elem.ownerDocument.createElement("tbody")) : elem
        }

        function disableScript(elem) {
            elem.type = (elem.getAttribute("type") !== null) + "/" + elem.type;
            return elem
        }

        function restoreScript(elem) {
            var match = rscriptTypeMasked.exec(elem.type);
            if (match) {
                elem.type = match[1]
            } else {
                elem.removeAttribute("type")
            }
            return elem
        }

        function cloneCopyEvent(src, dest) {
            var i, l, type, pdataOld, pdataCur, udataOld, udataCur, events;
            if (dest.nodeType !== 1) {
                return
            }
            if (dataPriv.hasData(src)) {
                pdataOld = dataPriv.access(src);
                pdataCur = dataPriv.set(dest, pdataOld);
                events = pdataOld.events;
                if (events) {
                    delete pdataCur.handle;
                    pdataCur.events = {};
                    for (type in events) {
                        for (i = 0, l = events[type].length; i < l; i++) {
                            jQuery.event.add(dest, type, events[type][i])
                        }
                    }
                }
            }
            if (dataUser.hasData(src)) {
                udataOld = dataUser.access(src);
                udataCur = jQuery.extend({}, udataOld);
                dataUser.set(dest, udataCur)
            }
        }

        function fixInput(src, dest) {
            var nodeName = dest.nodeName.toLowerCase();
            if (nodeName === "input" && rcheckableType.test(src.type)) {
                dest.checked = src.checked
            } else if (nodeName === "input" || nodeName === "textarea") {
                dest.defaultValue = src.defaultValue
            }
        }

        function domManip(collection, args, callback, ignored) {
            args = concat.apply([], args);
            var fragment, first, scripts, hasScripts, node, doc, i = 0, l = collection.length, iNoClone = l - 1,
                value = args[0], isFunction = jQuery.isFunction(value);
            if (isFunction || l > 1 && typeof value === "string" && !support.checkClone && rchecked.test(value)) {
                return collection.each(function (index) {
                    var self = collection.eq(index);
                    if (isFunction) {
                        args[0] = value.call(this, index, self.html())
                    }
                    domManip(self, args, callback, ignored)
                })
            }
            if (l) {
                fragment = buildFragment(args, collection[0].ownerDocument, !1, collection, ignored);
                first = fragment.firstChild;
                if (fragment.childNodes.length === 1) {
                    fragment = first
                }
                if (first || ignored) {
                    scripts = jQuery.map(getAll(fragment, "script"), disableScript);
                    hasScripts = scripts.length;
                    for (; i < l; i++) {
                        node = fragment;
                        if (i !== iNoClone) {
                            node = jQuery.clone(node, !0, !0);
                            if (hasScripts) {
                                jQuery.merge(scripts, getAll(node, "script"))
                            }
                        }
                        callback.call(collection[i], node, i)
                    }
                    if (hasScripts) {
                        doc = scripts[scripts.length - 1].ownerDocument;
                        jQuery.map(scripts, restoreScript);
                        for (i = 0; i < hasScripts; i++) {
                            node = scripts[i];
                            if (rscriptType.test(node.type || "") && !dataPriv.access(node, "globalEval") && jQuery.contains(doc, node)) {
                                if (node.src) {
                                    if (jQuery._evalUrl) {
                                        jQuery._evalUrl(node.src)
                                    }
                                } else {
                                    jQuery.globalEval(node.textContent.replace(rcleanScript, ""))
                                }
                            }
                        }
                    }
                }
            }
            return collection
        }

        function _remove(elem, selector, keepData) {
            var node, nodes = selector ? jQuery.filter(selector, elem) : elem, i = 0;
            for (; (node = nodes[i]) != null; i++) {
                if (!keepData && node.nodeType === 1) {
                    jQuery.cleanData(getAll(node))
                }
                if (node.parentNode) {
                    if (keepData && jQuery.contains(node.ownerDocument, node)) {
                        setGlobalEval(getAll(node, "script"))
                    }
                    node.parentNode.removeChild(node)
                }
            }
            return elem
        }

        jQuery.extend({
            htmlPrefilter: function htmlPrefilter(html) {
                return html.replace(rxhtmlTag, "<$1></$2>")
            }, clone: function clone(elem, dataAndEvents, deepDataAndEvents) {
                var i, l, srcElements, destElements, clone = elem.cloneNode(!0),
                    inPage = jQuery.contains(elem.ownerDocument, elem);
                if (!support.noCloneChecked && (elem.nodeType === 1 || elem.nodeType === 11) && !jQuery.isXMLDoc(elem)) {
                    destElements = getAll(clone);
                    srcElements = getAll(elem);
                    for (i = 0, l = srcElements.length; i < l; i++) {
                        fixInput(srcElements[i], destElements[i])
                    }
                }
                if (dataAndEvents) {
                    if (deepDataAndEvents) {
                        srcElements = srcElements || getAll(elem);
                        destElements = destElements || getAll(clone);
                        for (i = 0, l = srcElements.length; i < l; i++) {
                            cloneCopyEvent(srcElements[i], destElements[i])
                        }
                    } else {
                        cloneCopyEvent(elem, clone)
                    }
                }
                destElements = getAll(clone, "script");
                if (destElements.length > 0) {
                    setGlobalEval(destElements, !inPage && getAll(elem, "script"))
                }
                return clone
            }, cleanData: function cleanData(elems) {
                var data, elem, type, special = jQuery.event.special, i = 0;
                for (; (elem = elems[i]) !== undefined; i++) {
                    if (acceptData(elem)) {
                        if (data = elem[dataPriv.expando]) {
                            if (data.events) {
                                for (type in data.events) {
                                    if (special[type]) {
                                        jQuery.event.remove(elem, type)
                                    } else {
                                        jQuery.removeEvent(elem, type, data.handle)
                                    }
                                }
                            }
                            elem[dataPriv.expando] = undefined
                        }
                        if (elem[dataUser.expando]) {
                            elem[dataUser.expando] = undefined
                        }
                    }
                }
            }
        });
        jQuery.fn.extend({
            domManip: domManip, detach: function detach(selector) {
                return _remove(this, selector, !0)
            }, remove: function remove(selector) {
                return _remove(this, selector)
            }, text: function text(value) {
                return access(this, function (value) {
                    return value === undefined ? jQuery.text(this) : this.empty().each(function () {
                        if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                            this.textContent = value
                        }
                    })
                }, null, value, arguments.length)
            }, append: function append() {
                return domManip(this, arguments, function (elem) {
                    if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                        var target = manipulationTarget(this, elem);
                        target.appendChild(elem)
                    }
                })
            }, prepend: function prepend() {
                return domManip(this, arguments, function (elem) {
                    if (this.nodeType === 1 || this.nodeType === 11 || this.nodeType === 9) {
                        var target = manipulationTarget(this, elem);
                        target.insertBefore(elem, target.firstChild)
                    }
                })
            }, before: function before() {
                return domManip(this, arguments, function (elem) {
                    if (this.parentNode) {
                        this.parentNode.insertBefore(elem, this)
                    }
                })
            }, after: function after() {
                return domManip(this, arguments, function (elem) {
                    if (this.parentNode) {
                        this.parentNode.insertBefore(elem, this.nextSibling)
                    }
                })
            }, empty: function empty() {
                var elem, i = 0;
                for (; (elem = this[i]) != null; i++) {
                    if (elem.nodeType === 1) {
                        jQuery.cleanData(getAll(elem, !1));
                        elem.textContent = ""
                    }
                }
                return this
            }, clone: function clone(dataAndEvents, deepDataAndEvents) {
                dataAndEvents = dataAndEvents == null ? false : dataAndEvents;
                deepDataAndEvents = deepDataAndEvents == null ? dataAndEvents : deepDataAndEvents;
                return this.map(function () {
                    return jQuery.clone(this, dataAndEvents, deepDataAndEvents)
                })
            }, html: function html(value) {
                return access(this, function (value) {
                    var elem = this[0] || {}, i = 0, l = this.length;
                    if (value === undefined && elem.nodeType === 1) {
                        return elem.innerHTML
                    }
                    if (typeof value === "string" && !rnoInnerhtml.test(value) && !wrapMap[(rtagName.exec(value) || ["", ""])[1].toLowerCase()]) {
                        value = jQuery.htmlPrefilter(value);
                        try {
                            for (; i < l; i++) {
                                elem = this[i] || {};
                                if (elem.nodeType === 1) {
                                    jQuery.cleanData(getAll(elem, !1));
                                    elem.innerHTML = value
                                }
                            }
                            elem = 0
                        } catch (e) {
                        }
                    }
                    if (elem) {
                        this.empty().append(value)
                    }
                }, null, value, arguments.length)
            }, replaceWith: function replaceWith() {
                var ignored = [];
                return domManip(this, arguments, function (elem) {
                    var parent = this.parentNode;
                    if (jQuery.inArray(this, ignored) < 0) {
                        jQuery.cleanData(getAll(this));
                        if (parent) {
                            parent.replaceChild(elem, this)
                        }
                    }
                }, ignored)
            }
        });
        jQuery.each({
            appendTo: "append",
            prependTo: "prepend",
            insertBefore: "before",
            insertAfter: "after",
            replaceAll: "replaceWith"
        }, function (name, original) {
            jQuery.fn[name] = function (selector) {
                var elems, ret = [], insert = jQuery(selector), last = insert.length - 1, i = 0;
                for (; i <= last; i++) {
                    elems = i === last ? this : this.clone(!0);
                    jQuery(insert[i])[original](elems);
                    push.apply(ret, elems.get())
                }
                return this.pushStack(ret)
            }
        });
        var iframe, elemdisplay = {HTML: "block", BODY: "block"};

        function actualDisplay(name, doc) {
            var elem = jQuery(doc.createElement(name)).appendTo(doc.body), display = jQuery.css(elem[0], "display");
            elem.detach();
            return display
        }

        function defaultDisplay(nodeName) {
            var doc = document, display = elemdisplay[nodeName];
            if (!display) {
                display = actualDisplay(nodeName, doc);
                if (display === "none" || !display) {
                    iframe = (iframe || jQuery("<iframe frameborder='0' width='0' height='0'/>")).appendTo(doc.documentElement);
                    doc = iframe[0].contentDocument;
                    doc.write();
                    doc.close();
                    display = actualDisplay(nodeName, doc);
                    iframe.detach()
                }
                elemdisplay[nodeName] = display
            }
            return display
        }

        var rmargin = /^margin/;
        var rnumnonpx = new RegExp("^(" + pnum + ")(?!px)[a-z%]+$", "i");
        var getStyles = function getStyles(elem) {
            var view = elem.ownerDocument.defaultView;
            if (!view || !view.opener) {
                view = window
            }
            return view.getComputedStyle(elem)
        };
        var swap = function swap(elem, options, callback, args) {
            var ret, name, old = {};
            for (name in options) {
                old[name] = elem.style[name];
                elem.style[name] = options[name]
            }
            ret = callback.apply(elem, args || []);
            for (name in options) {
                elem.style[name] = old[name]
            }
            return ret
        };
        var documentElement = document.documentElement;
        (function () {
            var pixelPositionVal, boxSizingReliableVal, pixelMarginRightVal, reliableMarginLeftVal,
                container = document.createElement("div"), div = document.createElement("div");
            if (!div.style) {
                return
            }
            div.style.backgroundClip = "content-box";
            div.cloneNode(!0).style.backgroundClip = "";
            support.clearCloneStyle = div.style.backgroundClip === "content-box";
            container.style.cssText = "border:0;width:8px;height:0;top:0;left:-9999px;" + "padding:0;margin-top:1px;position:absolute";
            container.appendChild(div);

            function computeStyleTests() {
                div.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;" + "position:relative;display:block;" + "margin:auto;border:1px;padding:1px;" + "top:1%;width:50%";
                div.innerHTML = "";
                documentElement.appendChild(container);
                var divStyle = window.getComputedStyle(div);
                pixelPositionVal = divStyle.top !== "1%";
                reliableMarginLeftVal = divStyle.marginLeft === "2px";
                boxSizingReliableVal = divStyle.width === "4px";
                div.style.marginRight = "50%";
                pixelMarginRightVal = divStyle.marginRight === "4px";
                documentElement.removeChild(container)
            }

            jQuery.extend(support, {
                pixelPosition: function pixelPosition() {
                    computeStyleTests();
                    return pixelPositionVal
                }, boxSizingReliable: function boxSizingReliable() {
                    if (boxSizingReliableVal == null) {
                        computeStyleTests()
                    }
                    return boxSizingReliableVal
                }, pixelMarginRight: function pixelMarginRight() {
                    if (boxSizingReliableVal == null) {
                        computeStyleTests()
                    }
                    return pixelMarginRightVal
                }, reliableMarginLeft: function reliableMarginLeft() {
                    if (boxSizingReliableVal == null) {
                        computeStyleTests()
                    }
                    return reliableMarginLeftVal
                }, reliableMarginRight: function reliableMarginRight() {
                    var ret, marginDiv = div.appendChild(document.createElement("div"));
                    marginDiv.style.cssText = div.style.cssText = "-webkit-box-sizing:content-box;box-sizing:content-box;" + "display:block;margin:0;border:0;padding:0";
                    marginDiv.style.marginRight = marginDiv.style.width = "0";
                    div.style.width = "1px";
                    documentElement.appendChild(container);
                    ret = !parseFloat(window.getComputedStyle(marginDiv).marginRight);
                    documentElement.removeChild(container);
                    div.removeChild(marginDiv);
                    return ret
                }
            })
        })();

        function curCSS(elem, name, computed) {
            var width, minWidth, maxWidth, ret, style = elem.style;
            computed = computed || getStyles(elem);
            ret = computed ? computed.getPropertyValue(name) || computed[name] : undefined;
            if ((ret === "" || ret === undefined) && !jQuery.contains(elem.ownerDocument, elem)) {
                ret = jQuery.style(elem, name)
            }
            if (computed) {
                if (!support.pixelMarginRight() && rnumnonpx.test(ret) && rmargin.test(name)) {
                    width = style.width;
                    minWidth = style.minWidth;
                    maxWidth = style.maxWidth;
                    style.minWidth = style.maxWidth = style.width = ret;
                    ret = computed.width;
                    style.width = width;
                    style.minWidth = minWidth;
                    style.maxWidth = maxWidth
                }
            }
            return ret !== undefined ? ret + "" : ret
        }

        function addGetHookIf(conditionFn, hookFn) {
            return {
                get: function get() {
                    if (conditionFn()) {
                        delete this.get;
                        return
                    }
                    return (this.get = hookFn).apply(this, arguments)
                }
            }
        }

        var rdisplayswap = /^(none|table(?!-c[ea]).+)/,
            cssShow = {position: "absolute", visibility: "hidden", display: "block"},
            cssNormalTransform = {letterSpacing: "0", fontWeight: "400"}, cssPrefixes = ["Webkit", "O", "Moz", "ms"],
            emptyStyle = document.createElement("div").style;

        function vendorPropName(name) {
            if (name in emptyStyle) {
                return name
            }
            var capName = name[0].toUpperCase() + name.slice(1), i = cssPrefixes.length;
            while (i--) {
                name = cssPrefixes[i] + capName;
                if (name in emptyStyle) {
                    return name
                }
            }
        }

        function setPositiveNumber(elem, value, subtract) {
            var matches = rcssNum.exec(value);
            return matches ? Math.max(0, matches[2] - (subtract || 0)) + (matches[3] || "px") : value
        }

        function augmentWidthOrHeight(elem, name, extra, isBorderBox, styles) {
            var i = extra === (isBorderBox ? "border" : "content") ? 4 : name === "width" ? 1 : 0, val = 0;
            for (; i < 4; i += 2) {
                if (extra === "margin") {
                    val += jQuery.css(elem, extra + cssExpand[i], !0, styles)
                }
                if (isBorderBox) {
                    if (extra === "content") {
                        val -= jQuery.css(elem, "padding" + cssExpand[i], !0, styles)
                    }
                    if (extra !== "margin") {
                        val -= jQuery.css(elem, "border" + cssExpand[i] + "Width", !0, styles)
                    }
                } else {
                    val += jQuery.css(elem, "padding" + cssExpand[i], !0, styles);
                    if (extra !== "padding") {
                        val += jQuery.css(elem, "border" + cssExpand[i] + "Width", !0, styles)
                    }
                }
            }
            return val
        }

        function getWidthOrHeight(elem, name, extra) {
            var valueIsBorderBox = !0, val = name === "width" ? elem.offsetWidth : elem.offsetHeight,
                styles = getStyles(elem), isBorderBox = jQuery.css(elem, "boxSizing", !1, styles) === "border-box";
            if (val <= 0 || val == null) {
                val = curCSS(elem, name, styles);
                if (val < 0 || val == null) {
                    val = elem.style[name]
                }
                if (rnumnonpx.test(val)) {
                    return val
                }
                valueIsBorderBox = isBorderBox && (support.boxSizingReliable() || val === elem.style[name]);
                val = parseFloat(val) || 0
            }
            return val + augmentWidthOrHeight(elem, name, extra || (isBorderBox ? "border" : "content"), valueIsBorderBox, styles) + "px"
        }

        function showHide(elements, show) {
            var display, elem, hidden, values = [], index = 0, length = elements.length;
            for (; index < length; index++) {
                elem = elements[index];
                if (!elem.style) {
                    continue
                }
                values[index] = dataPriv.get(elem, "olddisplay");
                display = elem.style.display;
                if (show) {
                    if (!values[index] && display === "none") {
                        elem.style.display = ""
                    }
                    if (elem.style.display === "" && isHidden(elem)) {
                        values[index] = dataPriv.access(elem, "olddisplay", defaultDisplay(elem.nodeName))
                    }
                } else {
                    hidden = isHidden(elem);
                    if (display !== "none" || !hidden) {
                        dataPriv.set(elem, "olddisplay", hidden ? display : jQuery.css(elem, "display"))
                    }
                }
            }
            for (index = 0; index < length; index++) {
                elem = elements[index];
                if (!elem.style) {
                    continue
                }
                if (!show || elem.style.display === "none" || elem.style.display === "") {
                    elem.style.display = show ? values[index] || "" : "none"
                }
            }
            return elements
        }

        jQuery.extend({
            cssHooks: {
                opacity: {
                    get: function get(elem, computed) {
                        if (computed) {
                            var ret = curCSS(elem, "opacity");
                            return ret === "" ? "1" : ret
                        }
                    }
                }
            },
            cssNumber: {
                "animationIterationCount": !0,
                "columnCount": !0,
                "fillOpacity": !0,
                "flexGrow": !0,
                "flexShrink": !0,
                "fontWeight": !0,
                "lineHeight": !0,
                "opacity": !0,
                "order": !0,
                "orphans": !0,
                "widows": !0,
                "zIndex": !0,
                "zoom": !0
            },
            cssProps: {"float": "cssFloat"},
            style: function style(elem, name, value, extra) {
                if (!elem || elem.nodeType === 3 || elem.nodeType === 8 || !elem.style) {
                    return
                }
                var ret, type, hooks, origName = jQuery.camelCase(name), style = elem.style;
                name = jQuery.cssProps[origName] || (jQuery.cssProps[origName] = vendorPropName(origName) || origName);
                hooks = jQuery.cssHooks[name] || jQuery.cssHooks[origName];
                if (value !== undefined) {
                    type = typeof value;
                    if (type === "string" && (ret = rcssNum.exec(value)) && ret[1]) {
                        value = adjustCSS(elem, name, ret);
                        type = "number"
                    }
                    if (value == null || value !== value) {
                        return
                    }
                    if (type === "number") {
                        value += ret && ret[3] || (jQuery.cssNumber[origName] ? "" : "px")
                    }
                    if (!support.clearCloneStyle && value === "" && name.indexOf("background") === 0) {
                        style[name] = "inherit"
                    }
                    if (!hooks || !("set" in hooks) || (value = hooks.set(elem, value, extra)) !== undefined) {
                        style[name] = value
                    }
                } else {
                    if (hooks && "get" in hooks && (ret = hooks.get(elem, !1, extra)) !== undefined) {
                        return ret
                    }
                    return style[name]
                }
            },
            css: function css(elem, name, extra, styles) {
                var val, num, hooks, origName = jQuery.camelCase(name);
                name = jQuery.cssProps[origName] || (jQuery.cssProps[origName] = vendorPropName(origName) || origName);
                hooks = jQuery.cssHooks[name] || jQuery.cssHooks[origName];
                if (hooks && "get" in hooks) {
                    val = hooks.get(elem, !0, extra)
                }
                if (val === undefined) {
                    val = curCSS(elem, name, styles)
                }
                if (val === "normal" && name in cssNormalTransform) {
                    val = cssNormalTransform[name]
                }
                if (extra === "" || extra) {
                    num = parseFloat(val);
                    return extra === !0 || isFinite(num) ? num || 0 : val
                }
                return val
            }
        });
        jQuery.each(["height", "width"], function (i, name) {
            jQuery.cssHooks[name] = {
                get: function get(elem, computed, extra) {
                    if (computed) {
                        return rdisplayswap.test(jQuery.css(elem, "display")) && elem.offsetWidth === 0 ? swap(elem, cssShow, function () {
                            return getWidthOrHeight(elem, name, extra)
                        }) : getWidthOrHeight(elem, name, extra)
                    }
                }, set: function set(elem, value, extra) {
                    var matches, styles = extra && getStyles(elem),
                        subtract = extra && augmentWidthOrHeight(elem, name, extra, jQuery.css(elem, "boxSizing", !1, styles) === "border-box", styles);
                    if (subtract && (matches = rcssNum.exec(value)) && (matches[3] || "px") !== "px") {
                        elem.style[name] = value;
                        value = jQuery.css(elem, name)
                    }
                    return setPositiveNumber(elem, value, subtract)
                }
            }
        });
        jQuery.cssHooks.marginLeft = addGetHookIf(support.reliableMarginLeft, function (elem, computed) {
            if (computed) {
                return (parseFloat(curCSS(elem, "marginLeft")) || elem.getBoundingClientRect().left - swap(elem, {marginLeft: 0}, function () {
                    return elem.getBoundingClientRect().left
                })) + "px"
            }
        });
        jQuery.cssHooks.marginRight = addGetHookIf(support.reliableMarginRight, function (elem, computed) {
            if (computed) {
                return swap(elem, {"display": "inline-block"}, curCSS, [elem, "marginRight"])
            }
        });
        jQuery.each({margin: "", padding: "", border: "Width"}, function (prefix, suffix) {
            jQuery.cssHooks[prefix + suffix] = {
                expand: function expand(value) {
                    var i = 0, expanded = {}, parts = typeof value === "string" ? value.split(" ") : [value];
                    for (; i < 4; i++) {
                        expanded[prefix + cssExpand[i] + suffix] = parts[i] || parts[i - 2] || parts[0]
                    }
                    return expanded
                }
            };
            if (!rmargin.test(prefix)) {
                jQuery.cssHooks[prefix + suffix].set = setPositiveNumber
            }
        });
        jQuery.fn.extend({
            css: function css(name, value) {
                return access(this, function (elem, name, value) {
                    var styles, len, map = {}, i = 0;
                    if (jQuery.isArray(name)) {
                        styles = getStyles(elem);
                        len = name.length;
                        for (; i < len; i++) {
                            map[name[i]] = jQuery.css(elem, name[i], !1, styles)
                        }
                        return map
                    }
                    return value !== undefined ? jQuery.style(elem, name, value) : jQuery.css(elem, name)
                }, name, value, arguments.length > 1)
            }, show: function show() {
                return showHide(this, !0)
            }, hide: function hide() {
                return showHide(this)
            }, toggle: function toggle(state) {
                if (typeof state === "boolean") {
                    return state ? this.show() : this.hide()
                }
                return this.each(function () {
                    if (isHidden(this)) {
                        jQuery(this).show()
                    } else {
                        jQuery(this).hide()
                    }
                })
            }
        });

        function Tween(elem, options, prop, end, easing) {
            return new Tween.prototype.init(elem, options, prop, end, easing)
        }

        jQuery.Tween = Tween;
        Tween.prototype = {
            constructor: Tween, init: function init(elem, options, prop, end, easing, unit) {
                this.elem = elem;
                this.prop = prop;
                this.easing = easing || jQuery.easing._default;
                this.options = options;
                this.start = this.now = this.cur();
                this.end = end;
                this.unit = unit || (jQuery.cssNumber[prop] ? "" : "px")
            }, cur: function cur() {
                var hooks = Tween.propHooks[this.prop];
                return hooks && hooks.get ? hooks.get(this) : Tween.propHooks._default.get(this)
            }, run: function run(percent) {
                var eased, hooks = Tween.propHooks[this.prop];
                if (this.options.duration) {
                    this.pos = eased = jQuery.easing[this.easing](percent, this.options.duration * percent, 0, 1, this.options.duration)
                } else {
                    this.pos = eased = percent
                }
                this.now = (this.end - this.start) * eased + this.start;
                if (this.options.step) {
                    this.options.step.call(this.elem, this.now, this)
                }
                if (hooks && hooks.set) {
                    hooks.set(this)
                } else {
                    Tween.propHooks._default.set(this)
                }
                return this
            }
        };
        Tween.prototype.init.prototype = Tween.prototype;
        Tween.propHooks = {
            _default: {
                get: function get(tween) {
                    var result;
                    if (tween.elem.nodeType !== 1 || tween.elem[tween.prop] != null && tween.elem.style[tween.prop] == null) {
                        return tween.elem[tween.prop]
                    }
                    result = jQuery.css(tween.elem, tween.prop, "");
                    return !result || result === "auto" ? 0 : result
                }, set: function set(tween) {
                    if (jQuery.fx.step[tween.prop]) {
                        jQuery.fx.step[tween.prop](tween)
                    } else if (tween.elem.nodeType === 1 && (tween.elem.style[jQuery.cssProps[tween.prop]] != null || jQuery.cssHooks[tween.prop])) {
                        jQuery.style(tween.elem, tween.prop, tween.now + tween.unit)
                    } else {
                        tween.elem[tween.prop] = tween.now
                    }
                }
            }
        };
        Tween.propHooks.scrollTop = Tween.propHooks.scrollLeft = {
            set: function set(tween) {
                if (tween.elem.nodeType && tween.elem.parentNode) {
                    tween.elem[tween.prop] = tween.now
                }
            }
        };
        jQuery.easing = {
            linear: function linear(p) {
                return p
            }, swing: function swing(p) {
                return 0.5 - Math.cos(p * Math.PI) / 2
            }, _default: "swing"
        };
        jQuery.fx = Tween.prototype.init;
        jQuery.fx.step = {};
        var fxNow, timerId, rfxtypes = /^(?:toggle|show|hide)$/, rrun = /queueHooks$/;

        function createFxNow() {
            window.setTimeout(function () {
                fxNow = undefined
            });
            return fxNow = jQuery.now()
        }

        function genFx(type, includeWidth) {
            var which, i = 0, attrs = {height: type};
            includeWidth = includeWidth ? 1 : 0;
            for (; i < 4; i += 2 - includeWidth) {
                which = cssExpand[i];
                attrs["margin" + which] = attrs["padding" + which] = type
            }
            if (includeWidth) {
                attrs.opacity = attrs.width = type
            }
            return attrs
        }

        function createTween(value, prop, animation) {
            var tween, collection = (Animation.tweeners[prop] || []).concat(Animation.tweeners["*"]), index = 0,
                length = collection.length;
            for (; index < length; index++) {
                if (tween = collection[index].call(animation, prop, value)) {
                    return tween
                }
            }
        }

        function defaultPrefilter(elem, props, opts) {
            var prop, value, toggle, tween, hooks, oldfire, display, checkDisplay, anim = this, orig = {},
                style = elem.style, hidden = elem.nodeType && isHidden(elem), dataShow = dataPriv.get(elem, "fxshow");
            if (!opts.queue) {
                hooks = jQuery._queueHooks(elem, "fx");
                if (hooks.unqueued == null) {
                    hooks.unqueued = 0;
                    oldfire = hooks.empty.fire;
                    hooks.empty.fire = function () {
                        if (!hooks.unqueued) {
                            oldfire()
                        }
                    }
                }
                hooks.unqueued++;
                anim.always(function () {
                    anim.always(function () {
                        hooks.unqueued--;
                        if (!jQuery.queue(elem, "fx").length) {
                            hooks.empty.fire()
                        }
                    })
                })
            }
            if (elem.nodeType === 1 && ("height" in props || "width" in props)) {
                opts.overflow = [style.overflow, style.overflowX, style.overflowY];
                display = jQuery.css(elem, "display");
                checkDisplay = display === "none" ? dataPriv.get(elem, "olddisplay") || defaultDisplay(elem.nodeName) : display;
                if (checkDisplay === "inline" && jQuery.css(elem, "float") === "none") {
                    style.display = "inline-block"
                }
            }
            if (opts.overflow) {
                style.overflow = "hidden";
                anim.always(function () {
                    style.overflow = opts.overflow[0];
                    style.overflowX = opts.overflow[1];
                    style.overflowY = opts.overflow[2]
                })
            }
            for (prop in props) {
                value = props[prop];
                if (rfxtypes.exec(value)) {
                    delete props[prop];
                    toggle = toggle || value === "toggle";
                    if (value === (hidden ? "hide" : "show")) {
                        if (value === "show" && dataShow && dataShow[prop] !== undefined) {
                            hidden = !0
                        } else {
                            continue
                        }
                    }
                    orig[prop] = dataShow && dataShow[prop] || jQuery.style(elem, prop)
                } else {
                    display = undefined
                }
            }
            if (!jQuery.isEmptyObject(orig)) {
                if (dataShow) {
                    if ("hidden" in dataShow) {
                        hidden = dataShow.hidden
                    }
                } else {
                    dataShow = dataPriv.access(elem, "fxshow", {})
                }
                if (toggle) {
                    dataShow.hidden = !hidden
                }
                if (hidden) {
                    jQuery(elem).show()
                } else {
                    anim.done(function () {
                        jQuery(elem).hide()
                    })
                }
                anim.done(function () {
                    var prop;
                    dataPriv.remove(elem, "fxshow");
                    for (prop in orig) {
                        jQuery.style(elem, prop, orig[prop])
                    }
                });
                for (prop in orig) {
                    tween = createTween(hidden ? dataShow[prop] : 0, prop, anim);
                    if (!(prop in dataShow)) {
                        dataShow[prop] = tween.start;
                        if (hidden) {
                            tween.end = tween.start;
                            tween.start = prop === "width" || prop === "height" ? 1 : 0
                        }
                    }
                }
            } else if ((display === "none" ? defaultDisplay(elem.nodeName) : display) === "inline") {
                style.display = display
            }
        }

        function propFilter(props, specialEasing) {
            var index, name, easing, value, hooks;
            for (index in props) {
                name = jQuery.camelCase(index);
                easing = specialEasing[name];
                value = props[index];
                if (jQuery.isArray(value)) {
                    easing = value[1];
                    value = props[index] = value[0]
                }
                if (index !== name) {
                    props[name] = value;
                    delete props[index]
                }
                hooks = jQuery.cssHooks[name];
                if (hooks && "expand" in hooks) {
                    value = hooks.expand(value);
                    delete props[name];
                    for (index in value) {
                        if (!(index in props)) {
                            props[index] = value[index];
                            specialEasing[index] = easing
                        }
                    }
                } else {
                    specialEasing[name] = easing
                }
            }
        }

        function Animation(elem, properties, options) {
            var result, stopped, index = 0, length = Animation.prefilters.length,
                deferred = jQuery.Deferred().always(function () {
                    delete tick.elem
                }), tick = function tick() {
                    if (stopped) {
                        return !1
                    }
                    var currentTime = fxNow || createFxNow(),
                        remaining = Math.max(0, animation.startTime + animation.duration - currentTime),
                        temp = remaining / animation.duration || 0, percent = 1 - temp, index = 0,
                        length = animation.tweens.length;
                    for (; index < length; index++) {
                        animation.tweens[index].run(percent)
                    }
                    deferred.notifyWith(elem, [animation, percent, remaining]);
                    if (percent < 1 && length) {
                        return remaining
                    } else {
                        deferred.resolveWith(elem, [animation]);
                        return !1
                    }
                }, animation = deferred.promise({
                    elem: elem,
                    props: jQuery.extend({}, properties),
                    opts: jQuery.extend(!0, {specialEasing: {}, easing: jQuery.easing._default}, options),
                    originalProperties: properties,
                    originalOptions: options,
                    startTime: fxNow || createFxNow(),
                    duration: options.duration,
                    tweens: [],
                    createTween: function createTween(prop, end) {
                        var tween = jQuery.Tween(elem, animation.opts, prop, end, animation.opts.specialEasing[prop] || animation.opts.easing);
                        animation.tweens.push(tween);
                        return tween
                    },
                    stop: function stop(gotoEnd) {
                        var index = 0, length = gotoEnd ? animation.tweens.length : 0;
                        if (stopped) {
                            return this
                        }
                        stopped = !0;
                        for (; index < length; index++) {
                            animation.tweens[index].run(1)
                        }
                        if (gotoEnd) {
                            deferred.notifyWith(elem, [animation, 1, 0]);
                            deferred.resolveWith(elem, [animation, gotoEnd])
                        } else {
                            deferred.rejectWith(elem, [animation, gotoEnd])
                        }
                        return this
                    }
                }), props = animation.props;
            propFilter(props, animation.opts.specialEasing);
            for (; index < length; index++) {
                result = Animation.prefilters[index].call(animation, elem, props, animation.opts);
                if (result) {
                    if (jQuery.isFunction(result.stop)) {
                        jQuery._queueHooks(animation.elem, animation.opts.queue).stop = jQuery.proxy(result.stop, result)
                    }
                    return result
                }
            }
            jQuery.map(props, createTween, animation);
            if (jQuery.isFunction(animation.opts.start)) {
                animation.opts.start.call(elem, animation)
            }
            jQuery.fx.timer(jQuery.extend(tick, {elem: elem, anim: animation, queue: animation.opts.queue}));
            return animation.progress(animation.opts.progress).done(animation.opts.done, animation.opts.complete).fail(animation.opts.fail).always(animation.opts.always)
        }

        jQuery.Animation = jQuery.extend(Animation, {
            tweeners: {
                "*": [function (prop, value) {
                    var tween = this.createTween(prop, value);
                    adjustCSS(tween.elem, prop, rcssNum.exec(value), tween);
                    return tween
                }]
            }, tweener: function tweener(props, callback) {
                if (jQuery.isFunction(props)) {
                    callback = props;
                    props = ["*"]
                } else {
                    props = props.match(rnotwhite)
                }
                var prop, index = 0, length = props.length;
                for (; index < length; index++) {
                    prop = props[index];
                    Animation.tweeners[prop] = Animation.tweeners[prop] || [];
                    Animation.tweeners[prop].unshift(callback)
                }
            }, prefilters: [defaultPrefilter], prefilter: function prefilter(callback, prepend) {
                if (prepend) {
                    Animation.prefilters.unshift(callback)
                } else {
                    Animation.prefilters.push(callback)
                }
            }
        });
        jQuery.speed = function (speed, easing, fn) {
            var opt = speed && typeof speed === "object" ? jQuery.extend({}, speed) : {
                complete: fn || !fn && easing || jQuery.isFunction(speed) && speed,
                duration: speed,
                easing: fn && easing || easing && !jQuery.isFunction(easing) && easing
            };
            opt.duration = jQuery.fx.off ? 0 : typeof opt.duration === "number" ? opt.duration : opt.duration in jQuery.fx.speeds ? jQuery.fx.speeds[opt.duration] : jQuery.fx.speeds._default;
            if (opt.queue == null || opt.queue === !0) {
                opt.queue = "fx"
            }
            opt.old = opt.complete;
            opt.complete = function () {
                if (jQuery.isFunction(opt.old)) {
                    opt.old.call(this)
                }
                if (opt.queue) {
                    jQuery.dequeue(this, opt.queue)
                }
            };
            return opt
        };
        jQuery.fn.extend({
            fadeTo: function fadeTo(speed, to, easing, callback) {
                return this.filter(isHidden).css("opacity", 0).show().end().animate({opacity: to}, speed, easing, callback)
            }, animate: function animate(prop, speed, easing, callback) {
                var empty = jQuery.isEmptyObject(prop), optall = jQuery.speed(speed, easing, callback),
                    doAnimation = function doAnimation() {
                        var anim = Animation(this, jQuery.extend({}, prop), optall);
                        if (empty || dataPriv.get(this, "finish")) {
                            anim.stop(!0)
                        }
                    };
                doAnimation.finish = doAnimation;
                return empty || optall.queue === !1 ? this.each(doAnimation) : this.queue(optall.queue, doAnimation)
            }, stop: function stop(type, clearQueue, gotoEnd) {
                var stopQueue = function stopQueue(hooks) {
                    var stop = hooks.stop;
                    delete hooks.stop;
                    stop(gotoEnd)
                };
                if (typeof type !== "string") {
                    gotoEnd = clearQueue;
                    clearQueue = type;
                    type = undefined
                }
                if (clearQueue && type !== !1) {
                    this.queue(type || "fx", [])
                }
                return this.each(function () {
                    var dequeue = !0, index = type != null && type + "queueHooks", timers = jQuery.timers,
                        data = dataPriv.get(this);
                    if (index) {
                        if (data[index] && data[index].stop) {
                            stopQueue(data[index])
                        }
                    } else {
                        for (index in data) {
                            if (data[index] && data[index].stop && rrun.test(index)) {
                                stopQueue(data[index])
                            }
                        }
                    }
                    for (index = timers.length; index--;) {
                        if (timers[index].elem === this && (type == null || timers[index].queue === type)) {
                            timers[index].anim.stop(gotoEnd);
                            dequeue = !1;
                            timers.splice(index, 1)
                        }
                    }
                    if (dequeue || !gotoEnd) {
                        jQuery.dequeue(this, type)
                    }
                })
            }, finish: function finish(type) {
                if (type !== !1) {
                    type = type || "fx"
                }
                return this.each(function () {
                    var index, data = dataPriv.get(this), queue = data[type + "queue"],
                        hooks = data[type + "queueHooks"], timers = jQuery.timers, length = queue ? queue.length : 0;
                    data.finish = !0;
                    jQuery.queue(this, type, []);
                    if (hooks && hooks.stop) {
                        hooks.stop.call(this, !0)
                    }
                    for (index = timers.length; index--;) {
                        if (timers[index].elem === this && timers[index].queue === type) {
                            timers[index].anim.stop(!0);
                            timers.splice(index, 1)
                        }
                    }
                    for (index = 0; index < length; index++) {
                        if (queue[index] && queue[index].finish) {
                            queue[index].finish.call(this)
                        }
                    }
                    delete data.finish
                })
            }
        });
        jQuery.each(["toggle", "show", "hide"], function (i, name) {
            var cssFn = jQuery.fn[name];
            jQuery.fn[name] = function (speed, easing, callback) {
                return speed == null || typeof speed === "boolean" ? cssFn.apply(this, arguments) : this.animate(genFx(name, !0), speed, easing, callback)
            }
        });
        jQuery.each({
            slideDown: genFx("show"),
            slideUp: genFx("hide"),
            slideToggle: genFx("toggle"),
            fadeIn: {opacity: "show"},
            fadeOut: {opacity: "hide"},
            fadeToggle: {opacity: "toggle"}
        }, function (name, props) {
            jQuery.fn[name] = function (speed, easing, callback) {
                return this.animate(props, speed, easing, callback)
            }
        });
        jQuery.timers = [];
        jQuery.fx.tick = function () {
            var timer, i = 0, timers = jQuery.timers;
            fxNow = jQuery.now();
            for (; i < timers.length; i++) {
                timer = timers[i];
                if (!timer() && timers[i] === timer) {
                    timers.splice(i--, 1)
                }
            }
            if (!timers.length) {
                jQuery.fx.stop()
            }
            fxNow = undefined
        };
        jQuery.fx.timer = function (timer) {
            jQuery.timers.push(timer);
            if (timer()) {
                jQuery.fx.start()
            } else {
                jQuery.timers.pop()
            }
        };
        jQuery.fx.interval = 13;
        jQuery.fx.start = function () {
            if (!timerId) {
                timerId = window.setInterval(jQuery.fx.tick, jQuery.fx.interval)
            }
        };
        jQuery.fx.stop = function () {
            window.clearInterval(timerId);
            timerId = null
        };
        jQuery.fx.speeds = {slow: 600, fast: 200, _default: 400};
        jQuery.fn.delay = function (time, type) {
            time = jQuery.fx ? jQuery.fx.speeds[time] || time : time;
            type = type || "fx";
            return this.queue(type, function (next, hooks) {
                var timeout = window.setTimeout(next, time);
                hooks.stop = function () {
                    window.clearTimeout(timeout)
                }
            })
        };
        (function () {
            var input = document.createElement("input"), select = document.createElement("select"),
                opt = select.appendChild(document.createElement("option"));
            input.type = "checkbox";
            support.checkOn = input.value !== "";
            support.optSelected = opt.selected;
            select.disabled = !0;
            support.optDisabled = !opt.disabled;
            input = document.createElement("input");
            input.value = "t";
            input.type = "radio";
            support.radioValue = input.value === "t"
        })();
        var boolHook, attrHandle = jQuery.expr.attrHandle;
        jQuery.fn.extend({
            attr: function attr(name, value) {
                return access(this, jQuery.attr, name, value, arguments.length > 1)
            }, removeAttr: function removeAttr(name) {
                return this.each(function () {
                    jQuery.removeAttr(this, name)
                })
            }
        });
        jQuery.extend({
            attr: function attr(elem, name, value) {
                var ret, hooks, nType = elem.nodeType;
                if (nType === 3 || nType === 8 || nType === 2) {
                    return
                }
                if (typeof elem.getAttribute === "undefined") {
                    return jQuery.prop(elem, name, value)
                }
                if (nType !== 1 || !jQuery.isXMLDoc(elem)) {
                    name = name.toLowerCase();
                    hooks = jQuery.attrHooks[name] || (jQuery.expr.match.bool.test(name) ? boolHook : undefined)
                }
                if (value !== undefined) {
                    if (value === null) {
                        jQuery.removeAttr(elem, name);
                        return
                    }
                    if (hooks && "set" in hooks && (ret = hooks.set(elem, value, name)) !== undefined) {
                        return ret
                    }
                    elem.setAttribute(name, value + "");
                    return value
                }
                if (hooks && "get" in hooks && (ret = hooks.get(elem, name)) !== null) {
                    return ret
                }
                ret = jQuery.find.attr(elem, name);
                return ret == null ? undefined : ret
            }, attrHooks: {
                type: {
                    set: function set(elem, value) {
                        if (!support.radioValue && value === "radio" && jQuery.nodeName(elem, "input")) {
                            var val = elem.value;
                            elem.setAttribute("type", value);
                            if (val) {
                                elem.value = val
                            }
                            return value
                        }
                    }
                }
            }, removeAttr: function removeAttr(elem, value) {
                var name, propName, i = 0, attrNames = value && value.match(rnotwhite);
                if (attrNames && elem.nodeType === 1) {
                    while (name = attrNames[i++]) {
                        propName = jQuery.propFix[name] || name;
                        if (jQuery.expr.match.bool.test(name)) {
                            elem[propName] = !1
                        }
                        elem.removeAttribute(name)
                    }
                }
            }
        });
        boolHook = {
            set: function set(elem, value, name) {
                if (value === !1) {
                    jQuery.removeAttr(elem, name)
                } else {
                    elem.setAttribute(name, name)
                }
                return name
            }
        };
        jQuery.each(jQuery.expr.match.bool.source.match(/\w+/g), function (i, name) {
            var getter = attrHandle[name] || jQuery.find.attr;
            attrHandle[name] = function (elem, name, isXML) {
                var ret, handle;
                if (!isXML) {
                    handle = attrHandle[name];
                    attrHandle[name] = ret;
                    ret = getter(elem, name, isXML) != null ? name.toLowerCase() : null;
                    attrHandle[name] = handle
                }
                return ret
            }
        });
        var rfocusable = /^(?:input|select|textarea|button)$/i, rclickable = /^(?:a|area)$/i;
        jQuery.fn.extend({
            prop: function prop(name, value) {
                return access(this, jQuery.prop, name, value, arguments.length > 1)
            }, removeProp: function removeProp(name) {
                return this.each(function () {
                    delete this[jQuery.propFix[name] || name]
                })
            }
        });
        jQuery.extend({
            prop: function prop(elem, name, value) {
                var ret, hooks, nType = elem.nodeType;
                if (nType === 3 || nType === 8 || nType === 2) {
                    return
                }
                if (nType !== 1 || !jQuery.isXMLDoc(elem)) {
                    name = jQuery.propFix[name] || name;
                    hooks = jQuery.propHooks[name]
                }
                if (value !== undefined) {
                    if (hooks && "set" in hooks && (ret = hooks.set(elem, value, name)) !== undefined) {
                        return ret
                    }
                    return elem[name] = value
                }
                if (hooks && "get" in hooks && (ret = hooks.get(elem, name)) !== null) {
                    return ret
                }
                return elem[name]
            }, propHooks: {
                tabIndex: {
                    get: function get(elem) {
                        var tabindex = jQuery.find.attr(elem, "tabindex");
                        return tabindex ? parseInt(tabindex, 10) : rfocusable.test(elem.nodeName) || rclickable.test(elem.nodeName) && elem.href ? 0 : -1
                    }
                }
            }, propFix: {"for": "htmlFor", "class": "className"}
        });
        if (!support.optSelected) {
            jQuery.propHooks.selected = {
                get: function get(elem) {
                    var parent = elem.parentNode;
                    if (parent && parent.parentNode) {
                        parent.parentNode.selectedIndex
                    }
                    return null
                }, set: function set(elem) {
                    var parent = elem.parentNode;
                    if (parent) {
                        parent.selectedIndex;
                        if (parent.parentNode) {
                            parent.parentNode.selectedIndex
                        }
                    }
                }
            }
        }
        jQuery.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function () {
            jQuery.propFix[this.toLowerCase()] = this
        });
        var rclass = /[\t\r\n\f]/g;

        function getClass(elem) {
            return elem.getAttribute && elem.getAttribute("class") || ""
        }

        jQuery.fn.extend({
            addClass: function addClass(value) {
                var classes, elem, cur, curValue, clazz, j, finalValue, i = 0;
                if (jQuery.isFunction(value)) {
                    return this.each(function (j) {
                        jQuery(this).addClass(value.call(this, j, getClass(this)))
                    })
                }
                if (typeof value === "string" && value) {
                    classes = value.match(rnotwhite) || [];
                    while (elem = this[i++]) {
                        curValue = getClass(elem);
                        cur = elem.nodeType === 1 && (" " + curValue + " ").replace(rclass, " ");
                        if (cur) {
                            j = 0;
                            while (clazz = classes[j++]) {
                                if (cur.indexOf(" " + clazz + " ") < 0) {
                                    cur += clazz + " "
                                }
                            }
                            finalValue = jQuery.trim(cur);
                            if (curValue !== finalValue) {
                                elem.setAttribute("class", finalValue)
                            }
                        }
                    }
                }
                return this
            }, removeClass: function removeClass(value) {
                var classes, elem, cur, curValue, clazz, j, finalValue, i = 0;
                if (jQuery.isFunction(value)) {
                    return this.each(function (j) {
                        jQuery(this).removeClass(value.call(this, j, getClass(this)))
                    })
                }
                if (!arguments.length) {
                    return this.attr("class", "")
                }
                if (typeof value === "string" && value) {
                    classes = value.match(rnotwhite) || [];
                    while (elem = this[i++]) {
                        curValue = getClass(elem);
                        cur = elem.nodeType === 1 && (" " + curValue + " ").replace(rclass, " ");
                        if (cur) {
                            j = 0;
                            while (clazz = classes[j++]) {
                                while (cur.indexOf(" " + clazz + " ") > -1) {
                                    cur = cur.replace(" " + clazz + " ", " ")
                                }
                            }
                            finalValue = jQuery.trim(cur);
                            if (curValue !== finalValue) {
                                elem.setAttribute("class", finalValue)
                            }
                        }
                    }
                }
                return this
            }, toggleClass: function toggleClass(value, stateVal) {
                var type = typeof value;
                if (typeof stateVal === "boolean" && type === "string") {
                    return stateVal ? this.addClass(value) : this.removeClass(value)
                }
                if (jQuery.isFunction(value)) {
                    return this.each(function (i) {
                        jQuery(this).toggleClass(value.call(this, i, getClass(this), stateVal), stateVal)
                    })
                }
                return this.each(function () {
                    var className, i, self, classNames;
                    if (type === "string") {
                        i = 0;
                        self = jQuery(this);
                        classNames = value.match(rnotwhite) || [];
                        while (className = classNames[i++]) {
                            if (self.hasClass(className)) {
                                self.removeClass(className)
                            } else {
                                self.addClass(className)
                            }
                        }
                    } else if (value === undefined || type === "boolean") {
                        className = getClass(this);
                        if (className) {
                            dataPriv.set(this, "__className__", className)
                        }
                        if (this.setAttribute) {
                            this.setAttribute("class", className || value === !1 ? "" : dataPriv.get(this, "__className__") || "")
                        }
                    }
                })
            }, hasClass: function hasClass(selector) {
                var className, elem, i = 0;
                className = " " + selector + " ";
                while (elem = this[i++]) {
                    if (elem.nodeType === 1 && (" " + getClass(elem) + " ").replace(rclass, " ").indexOf(className) > -1) {
                        return !0
                    }
                }
                return !1
            }
        });
        var rreturn = /\r/g, rspaces = /[\x20\t\r\n\f]+/g;
        jQuery.fn.extend({
            val: function val(value) {
                var hooks, ret, isFunction, elem = this[0];
                if (!arguments.length) {
                    if (elem) {
                        hooks = jQuery.valHooks[elem.type] || jQuery.valHooks[elem.nodeName.toLowerCase()];
                        if (hooks && "get" in hooks && (ret = hooks.get(elem, "value")) !== undefined) {
                            return ret
                        }
                        ret = elem.value;
                        return typeof ret === "string" ? ret.replace(rreturn, "") : ret == null ? "" : ret
                    }
                    return
                }
                isFunction = jQuery.isFunction(value);
                return this.each(function (i) {
                    var val;
                    if (this.nodeType !== 1) {
                        return
                    }
                    if (isFunction) {
                        val = value.call(this, i, jQuery(this).val())
                    } else {
                        val = value
                    }
                    if (val == null) {
                        val = ""
                    } else if (typeof val === "number") {
                        val += ""
                    } else if (jQuery.isArray(val)) {
                        val = jQuery.map(val, function (value) {
                            return value == null ? "" : value + ""
                        })
                    }
                    hooks = jQuery.valHooks[this.type] || jQuery.valHooks[this.nodeName.toLowerCase()];
                    if (!hooks || !("set" in hooks) || hooks.set(this, val, "value") === undefined) {
                        this.value = val
                    }
                })
            }
        });
        jQuery.extend({
            valHooks: {
                option: {
                    get: function get(elem) {
                        var val = jQuery.find.attr(elem, "value");
                        return val != null ? val : jQuery.trim(jQuery.text(elem)).replace(rspaces, " ")
                    }
                }, select: {
                    get: function get(elem) {
                        var value, option, options = elem.options, index = elem.selectedIndex,
                            one = elem.type === "select-one" || index < 0, values = one ? null : [],
                            max = one ? index + 1 : options.length, i = index < 0 ? max : one ? index : 0;
                        for (; i < max; i++) {
                            option = options[i];
                            if ((option.selected || i === index) && (support.optDisabled ? !option.disabled : option.getAttribute("disabled") === null) && (!option.parentNode.disabled || !jQuery.nodeName(option.parentNode, "optgroup"))) {
                                value = jQuery(option).val();
                                if (one) {
                                    return value
                                }
                                values.push(value)
                            }
                        }
                        return values
                    }, set: function set(elem, value) {
                        var optionSet, option, options = elem.options, values = jQuery.makeArray(value),
                            i = options.length;
                        while (i--) {
                            option = options[i];
                            if (option.selected = jQuery.inArray(jQuery.valHooks.option.get(option), values) > -1) {
                                optionSet = !0
                            }
                        }
                        if (!optionSet) {
                            elem.selectedIndex = -1
                        }
                        return values
                    }
                }
            }
        });
        jQuery.each(["radio", "checkbox"], function () {
            jQuery.valHooks[this] = {
                set: function set(elem, value) {
                    if (jQuery.isArray(value)) {
                        return elem.checked = jQuery.inArray(jQuery(elem).val(), value) > -1
                    }
                }
            };
            if (!support.checkOn) {
                jQuery.valHooks[this].get = function (elem) {
                    return elem.getAttribute("value") === null ? "on" : elem.value
                }
            }
        });
        var rfocusMorph = /^(?:focusinfocus|focusoutblur)$/;
        jQuery.extend(jQuery.event, {
            trigger: function trigger(event, data, elem, onlyHandlers) {
                var i, cur, tmp, bubbleType, ontype, handle, special, eventPath = [elem || document],
                    type = hasOwn.call(event, "type") ? event.type : event,
                    namespaces = hasOwn.call(event, "namespace") ? event.namespace.split(".") : [];
                cur = tmp = elem = elem || document;
                if (elem.nodeType === 3 || elem.nodeType === 8) {
                    return
                }
                if (rfocusMorph.test(type + jQuery.event.triggered)) {
                    return
                }
                if (type.indexOf(".") > -1) {
                    namespaces = type.split(".");
                    type = namespaces.shift();
                    namespaces.sort()
                }
                ontype = type.indexOf(":") < 0 && "on" + type;
                event = event[jQuery.expando] ? event : new jQuery.Event(type, typeof event === "object" && event);
                event.isTrigger = onlyHandlers ? 2 : 3;
                event.namespace = namespaces.join(".");
                event.rnamespace = event.namespace ? new RegExp("(^|\\.)" + namespaces.join("\\.(?:.*\\.|)") + "(\\.|$)") : null;
                event.result = undefined;
                if (!event.target) {
                    event.target = elem
                }
                data = data == null ? [event] : jQuery.makeArray(data, [event]);
                special = jQuery.event.special[type] || {};
                if (!onlyHandlers && special.trigger && special.trigger.apply(elem, data) === !1) {
                    return
                }
                if (!onlyHandlers && !special.noBubble && !jQuery.isWindow(elem)) {
                    bubbleType = special.delegateType || type;
                    if (!rfocusMorph.test(bubbleType + type)) {
                        cur = cur.parentNode
                    }
                    for (; cur; cur = cur.parentNode) {
                        eventPath.push(cur);
                        tmp = cur
                    }
                    if (tmp === (elem.ownerDocument || document)) {
                        eventPath.push(tmp.defaultView || tmp.parentWindow || window)
                    }
                }
                i = 0;
                while ((cur = eventPath[i++]) && !event.isPropagationStopped()) {
                    event.type = i > 1 ? bubbleType : special.bindType || type;
                    handle = (dataPriv.get(cur, "events") || {})[event.type] && dataPriv.get(cur, "handle");
                    if (handle) {
                        handle.apply(cur, data)
                    }
                    handle = ontype && cur[ontype];
                    if (handle && handle.apply && acceptData(cur)) {
                        event.result = handle.apply(cur, data);
                        if (event.result === !1) {
                            event.preventDefault()
                        }
                    }
                }
                event.type = type;
                if (!onlyHandlers && !event.isDefaultPrevented()) {
                    if ((!special._default || special._default.apply(eventPath.pop(), data) === !1) && acceptData(elem)) {
                        if (ontype && jQuery.isFunction(elem[type]) && !jQuery.isWindow(elem)) {
                            tmp = elem[ontype];
                            if (tmp) {
                                elem[ontype] = null
                            }
                            jQuery.event.triggered = type;
                            elem[type]();
                            jQuery.event.triggered = undefined;
                            if (tmp) {
                                elem[ontype] = tmp
                            }
                        }
                    }
                }
                return event.result
            }, simulate: function simulate(type, elem, event) {
                var e = jQuery.extend(new jQuery.Event(), event, {type: type, isSimulated: !0});
                jQuery.event.trigger(e, null, elem)
            }
        });
        jQuery.fn.extend({
            trigger: function trigger(type, data) {
                return this.each(function () {
                    jQuery.event.trigger(type, data, this)
                })
            }, triggerHandler: function triggerHandler(type, data) {
                var elem = this[0];
                if (elem) {
                    return jQuery.event.trigger(type, data, elem, !0)
                }
            }
        });
        jQuery.each(("blur focus focusin focusout load resize scroll unload click dblclick " + "mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " + "change select submit keydown keypress keyup error contextmenu").split(" "), function (i, name) {
            jQuery.fn[name] = function (data, fn) {
                return arguments.length > 0 ? this.on(name, null, data, fn) : this.trigger(name)
            }
        });
        jQuery.fn.extend({
            hover: function hover(fnOver, fnOut) {
                return this.mouseenter(fnOver).mouseleave(fnOut || fnOver)
            }
        });
        support.focusin = "onfocusin" in window;
        if (!support.focusin) {
            jQuery.each({focus: "focusin", blur: "focusout"}, function (orig, fix) {
                var handler = function handler(event) {
                    jQuery.event.simulate(fix, event.target, jQuery.event.fix(event))
                };
                jQuery.event.special[fix] = {
                    setup: function setup() {
                        var doc = this.ownerDocument || this, attaches = dataPriv.access(doc, fix);
                        if (!attaches) {
                            doc.addEventListener(orig, handler, !0)
                        }
                        dataPriv.access(doc, fix, (attaches || 0) + 1)
                    }, teardown: function teardown() {
                        var doc = this.ownerDocument || this, attaches = dataPriv.access(doc, fix) - 1;
                        if (!attaches) {
                            doc.removeEventListener(orig, handler, !0);
                            dataPriv.remove(doc, fix)
                        } else {
                            dataPriv.access(doc, fix, attaches)
                        }
                    }
                }
            })
        }
        var location = window.location;
        var nonce = jQuery.now();
        var rquery = /\?/;
        jQuery.parseJSON = function (data) {
            return JSON.parse(data + "")
        };
        jQuery.parseXML = function (data) {
            var xml;
            if (!data || typeof data !== "string") {
                return null
            }
            try {
                xml = new window.DOMParser().parseFromString(data, "text/xml")
            } catch (e) {
                xml = undefined
            }
            if (!xml || xml.getElementsByTagName("parsererror").length) {
                jQuery.error("Invalid XML: " + data)
            }
            return xml
        };
        var rhash = /#.*$/, rts = /([?&])_=[^&]*/, rheaders = /^(.*?):[ \t]*([^\r\n]*)$/mg,
            rlocalProtocol = /^(?:about|app|app-storage|.+-extension|file|res|widget):$/, rnoContent = /^(?:GET|HEAD)$/,
            rprotocol = /^\/\//, prefilters = {}, transports = {}, allTypes = "*/".concat("*"),
            originAnchor = document.createElement("a");
        originAnchor.href = location.href;

        function addToPrefiltersOrTransports(structure) {
            return function (dataTypeExpression, func) {
                if (typeof dataTypeExpression !== "string") {
                    func = dataTypeExpression;
                    dataTypeExpression = "*"
                }
                var dataType, i = 0, dataTypes = dataTypeExpression.toLowerCase().match(rnotwhite) || [];
                if (jQuery.isFunction(func)) {
                    while (dataType = dataTypes[i++]) {
                        if (dataType[0] === "+") {
                            dataType = dataType.slice(1) || "*";
                            (structure[dataType] = structure[dataType] || []).unshift(func)
                        } else {
                            (structure[dataType] = structure[dataType] || []).push(func)
                        }
                    }
                }
            }
        }

        function inspectPrefiltersOrTransports(structure, options, originalOptions, jqXHR) {
            var inspected = {}, seekingTransport = structure === transports;

            function inspect(dataType) {
                var selected;
                inspected[dataType] = !0;
                jQuery.each(structure[dataType] || [], function (_, prefilterOrFactory) {
                    var dataTypeOrTransport = prefilterOrFactory(options, originalOptions, jqXHR);
                    if (typeof dataTypeOrTransport === "string" && !seekingTransport && !inspected[dataTypeOrTransport]) {
                        options.dataTypes.unshift(dataTypeOrTransport);
                        inspect(dataTypeOrTransport);
                        return !1
                    } else if (seekingTransport) {
                        return !(selected = dataTypeOrTransport)
                    }
                });
                return selected
            }

            return inspect(options.dataTypes[0]) || !inspected["*"] && inspect("*")
        }

        function ajaxExtend(target, src) {
            var key, deep, flatOptions = jQuery.ajaxSettings.flatOptions || {};
            for (key in src) {
                if (src[key] !== undefined) {
                    (flatOptions[key] ? target : deep || (deep = {}))[key] = src[key]
                }
            }
            if (deep) {
                jQuery.extend(!0, target, deep)
            }
            return target
        }

        function ajaxHandleResponses(s, jqXHR, responses) {
            var ct, type, finalDataType, firstDataType, contents = s.contents, dataTypes = s.dataTypes;
            while (dataTypes[0] === "*") {
                dataTypes.shift();
                if (ct === undefined) {
                    ct = s.mimeType || jqXHR.getResponseHeader("Content-Type")
                }
            }
            if (ct) {
                for (type in contents) {
                    if (contents[type] && contents[type].test(ct)) {
                        dataTypes.unshift(type);
                        break
                    }
                }
            }
            if (dataTypes[0] in responses) {
                finalDataType = dataTypes[0]
            } else {
                for (type in responses) {
                    if (!dataTypes[0] || s.converters[type + " " + dataTypes[0]]) {
                        finalDataType = type;
                        break
                    }
                    if (!firstDataType) {
                        firstDataType = type
                    }
                }
                finalDataType = finalDataType || firstDataType
            }
            if (finalDataType) {
                if (finalDataType !== dataTypes[0]) {
                    dataTypes.unshift(finalDataType)
                }
                return responses[finalDataType]
            }
        }

        function ajaxConvert(s, response, jqXHR, isSuccess) {
            var conv2, current, conv, tmp, prev, converters = {}, dataTypes = s.dataTypes.slice();
            if (dataTypes[1]) {
                for (conv in s.converters) {
                    converters[conv.toLowerCase()] = s.converters[conv]
                }
            }
            current = dataTypes.shift();
            while (current) {
                if (s.responseFields[current]) {
                    jqXHR[s.responseFields[current]] = response
                }
                if (!prev && isSuccess && s.dataFilter) {
                    response = s.dataFilter(response, s.dataType)
                }
                prev = current;
                current = dataTypes.shift();
                if (current) {
                    if (current === "*") {
                        current = prev
                    } else if (prev !== "*" && prev !== current) {
                        conv = converters[prev + " " + current] || converters["* " + current];
                        if (!conv) {
                            for (conv2 in converters) {
                                tmp = conv2.split(" ");
                                if (tmp[1] === current) {
                                    conv = converters[prev + " " + tmp[0]] || converters["* " + tmp[0]];
                                    if (conv) {
                                        if (conv === !0) {
                                            conv = converters[conv2]
                                        } else if (converters[conv2] !== !0) {
                                            current = tmp[0];
                                            dataTypes.unshift(tmp[1])
                                        }
                                        break
                                    }
                                }
                            }
                        }
                        if (conv !== !0) {
                            if (conv && s.throws) {
                                response = conv(response)
                            } else {
                                try {
                                    response = conv(response)
                                } catch (e) {
                                    return {
                                        state: "parsererror",
                                        error: conv ? e : "No conversion from " + prev + " to " + current
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return {state: "success", data: response}
        }

        jQuery.extend({
            active: 0,
            lastModified: {},
            etag: {},
            ajaxSettings: {
                url: location.href,
                type: "GET",
                isLocal: rlocalProtocol.test(location.protocol),
                global: !0,
                processData: !0,
                async: !0,
                contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                accepts: {
                    "*": allTypes,
                    text: "text/plain",
                    html: "text/html",
                    xml: "application/xml, text/xml",
                    json: "application/json, text/javascript"
                },
                contents: {xml: /\bxml\b/, html: /\bhtml/, json: /\bjson\b/},
                responseFields: {xml: "responseXML", text: "responseText", json: "responseJSON"},
                converters: {
                    "* text": String,
                    "text html": !0,
                    "text json": jQuery.parseJSON,
                    "text xml": jQuery.parseXML
                },
                flatOptions: {url: !0, context: !0}
            },
            ajaxSetup: function ajaxSetup(target, settings) {
                return settings ? ajaxExtend(ajaxExtend(target, jQuery.ajaxSettings), settings) : ajaxExtend(jQuery.ajaxSettings, target)
            },
            ajaxPrefilter: addToPrefiltersOrTransports(prefilters),
            ajaxTransport: addToPrefiltersOrTransports(transports),
            ajax: function ajax(url, options) {
                if (typeof url === "object") {
                    options = url;
                    url = undefined
                }
                options = options || {};
                var transport, cacheURL, responseHeadersString, responseHeaders, timeoutTimer, urlAnchor, fireGlobals,
                    i, s = jQuery.ajaxSetup({}, options), callbackContext = s.context || s,
                    globalEventContext = s.context && (callbackContext.nodeType || callbackContext.jquery) ? jQuery(callbackContext) : jQuery.event,
                    deferred = jQuery.Deferred(), completeDeferred = jQuery.Callbacks("once memory"),
                    _statusCode = s.statusCode || {}, requestHeaders = {}, requestHeadersNames = {}, state = 0,
                    strAbort = "canceled", jqXHR = {
                        readyState: 0, getResponseHeader: function getResponseHeader(key) {
                            var match;
                            if (state === 2) {
                                if (!responseHeaders) {
                                    responseHeaders = {};
                                    while (match = rheaders.exec(responseHeadersString)) {
                                        responseHeaders[match[1].toLowerCase()] = match[2]
                                    }
                                }
                                match = responseHeaders[key.toLowerCase()]
                            }
                            return match == null ? null : match
                        }, getAllResponseHeaders: function getAllResponseHeaders() {
                            return state === 2 ? responseHeadersString : null
                        }, setRequestHeader: function setRequestHeader(name, value) {
                            var lname = name.toLowerCase();
                            if (!state) {
                                name = requestHeadersNames[lname] = requestHeadersNames[lname] || name;
                                requestHeaders[name] = value
                            }
                            return this
                        }, overrideMimeType: function overrideMimeType(type) {
                            if (!state) {
                                s.mimeType = type
                            }
                            return this
                        }, statusCode: function statusCode(map) {
                            var code;
                            if (map) {
                                if (state < 2) {
                                    for (code in map) {
                                        _statusCode[code] = [_statusCode[code], map[code]]
                                    }
                                } else {
                                    jqXHR.always(map[jqXHR.status])
                                }
                            }
                            return this
                        }, abort: function abort(statusText) {
                            var finalText = statusText || strAbort;
                            if (transport) {
                                transport.abort(finalText)
                            }
                            done(0, finalText);
                            return this
                        }
                    };
                deferred.promise(jqXHR).complete = completeDeferred.add;
                jqXHR.success = jqXHR.done;
                jqXHR.error = jqXHR.fail;
                s.url = ((url || s.url || location.href) + "").replace(rhash, "").replace(rprotocol, location.protocol + "//");
                s.type = options.method || options.type || s.method || s.type;
                s.dataTypes = jQuery.trim(s.dataType || "*").toLowerCase().match(rnotwhite) || [""];
                if (s.crossDomain == null) {
                    urlAnchor = document.createElement("a");
                    try {
                        urlAnchor.href = s.url;
                        urlAnchor.href = urlAnchor.href;
                        s.crossDomain = originAnchor.protocol + "//" + originAnchor.host !== urlAnchor.protocol + "//" + urlAnchor.host
                    } catch (e) {
                        s.crossDomain = !0
                    }
                }
                if (s.data && s.processData && typeof s.data !== "string") {
                    s.data = jQuery.param(s.data, s.traditional)
                }
                inspectPrefiltersOrTransports(prefilters, s, options, jqXHR);
                if (state === 2) {
                    return jqXHR
                }
                fireGlobals = jQuery.event && s.global;
                if (fireGlobals && jQuery.active++ === 0) {
                    jQuery.event.trigger("ajaxStart")
                }
                s.type = s.type.toUpperCase();
                s.hasContent = !rnoContent.test(s.type);
                cacheURL = s.url;
                if (!s.hasContent) {
                    if (s.data) {
                        cacheURL = s.url += (rquery.test(cacheURL) ? "&" : "?") + s.data;
                        delete s.data
                    }
                    if (s.cache === !1) {
                        s.url = rts.test(cacheURL) ? cacheURL.replace(rts, "$1_=" + nonce++) : cacheURL + (rquery.test(cacheURL) ? "&" : "?") + "_=" + nonce++
                    }
                }
                if (s.ifModified) {
                    if (jQuery.lastModified[cacheURL]) {
                        jqXHR.setRequestHeader("If-Modified-Since", jQuery.lastModified[cacheURL])
                    }
                    if (jQuery.etag[cacheURL]) {
                        jqXHR.setRequestHeader("If-None-Match", jQuery.etag[cacheURL])
                    }
                }
                if (s.data && s.hasContent && s.contentType !== !1 || options.contentType) {
                    jqXHR.setRequestHeader("Content-Type", s.contentType)
                }
                jqXHR.setRequestHeader("Accept", s.dataTypes[0] && s.accepts[s.dataTypes[0]] ? s.accepts[s.dataTypes[0]] + (s.dataTypes[0] !== "*" ? ", " + allTypes + "; q=0.01" : "") : s.accepts["*"]);
                for (i in s.headers) {
                    jqXHR.setRequestHeader(i, s.headers[i])
                }
                if (s.beforeSend && (s.beforeSend.call(callbackContext, jqXHR, s) === !1 || state === 2)) {
                    return jqXHR.abort()
                }
                strAbort = "abort";
                for (i in{success: 1, error: 1, complete: 1}) {
                    jqXHR[i](s[i])
                }
                transport = inspectPrefiltersOrTransports(transports, s, options, jqXHR);
                if (!transport) {
                    done(-1, "No Transport")
                } else {
                    jqXHR.readyState = 1;
                    if (fireGlobals) {
                        globalEventContext.trigger("ajaxSend", [jqXHR, s])
                    }
                    if (state === 2) {
                        return jqXHR
                    }
                    if (s.async && s.timeout > 0) {
                        timeoutTimer = window.setTimeout(function () {
                            jqXHR.abort("timeout")
                        }, s.timeout)
                    }
                    try {
                        state = 1;
                        transport.send(requestHeaders, done)
                    } catch (e) {
                        if (state < 2) {
                            done(-1, e)
                        } else {
                            throw e
                        }
                    }
                }

                function done(status, nativeStatusText, responses, headers) {
                    var isSuccess, success, error, response, modified, statusText = nativeStatusText;
                    if (state === 2) {
                        return
                    }
                    state = 2;
                    if (timeoutTimer) {
                        window.clearTimeout(timeoutTimer)
                    }
                    transport = undefined;
                    responseHeadersString = headers || "";
                    jqXHR.readyState = status > 0 ? 4 : 0;
                    isSuccess = status >= 200 && status < 300 || status === 304;
                    if (responses) {
                        response = ajaxHandleResponses(s, jqXHR, responses)
                    }
                    response = ajaxConvert(s, response, jqXHR, isSuccess);
                    if (isSuccess) {
                        if (s.ifModified) {
                            modified = jqXHR.getResponseHeader("Last-Modified");
                            if (modified) {
                                jQuery.lastModified[cacheURL] = modified
                            }
                            modified = jqXHR.getResponseHeader("etag");
                            if (modified) {
                                jQuery.etag[cacheURL] = modified
                            }
                        }
                        if (status === 204 || s.type === "HEAD") {
                            statusText = "nocontent"
                        } else if (status === 304) {
                            statusText = "notmodified"
                        } else {
                            statusText = response.state;
                            success = response.data;
                            error = response.error;
                            isSuccess = !error
                        }
                    } else {
                        error = statusText;
                        if (status || !statusText) {
                            statusText = "error";
                            if (status < 0) {
                                status = 0
                            }
                        }
                    }
                    jqXHR.status = status;
                    jqXHR.statusText = (nativeStatusText || statusText) + "";
                    if (isSuccess) {
                        deferred.resolveWith(callbackContext, [success, statusText, jqXHR])
                    } else {
                        deferred.rejectWith(callbackContext, [jqXHR, statusText, error])
                    }
                    jqXHR.statusCode(_statusCode);
                    _statusCode = undefined;
                    if (fireGlobals) {
                        globalEventContext.trigger(isSuccess ? "ajaxSuccess" : "ajaxError", [jqXHR, s, isSuccess ? success : error])
                    }
                    completeDeferred.fireWith(callbackContext, [jqXHR, statusText]);
                    if (fireGlobals) {
                        globalEventContext.trigger("ajaxComplete", [jqXHR, s]);
                        if (!--jQuery.active) {
                            jQuery.event.trigger("ajaxStop")
                        }
                    }
                }

                return jqXHR
            },
            getJSON: function getJSON(url, data, callback) {
                return jQuery.get(url, data, callback, "json")
            },
            getScript: function getScript(url, callback) {
                return jQuery.get(url, undefined, callback, "script")
            }
        });
        jQuery.each(["get", "post"], function (i, method) {
            jQuery[method] = function (url, data, callback, type) {
                if (jQuery.isFunction(data)) {
                    type = type || callback;
                    callback = data;
                    data = undefined
                }
                return jQuery.ajax(jQuery.extend({
                    url: url,
                    type: method,
                    dataType: type,
                    data: data,
                    success: callback
                }, jQuery.isPlainObject(url) && url))
            }
        });
        jQuery._evalUrl = function (url) {
            return jQuery.ajax({url: url, type: "GET", dataType: "script", async: !1, global: !1, "throws": !0})
        };
        jQuery.fn.extend({
            wrapAll: function wrapAll(html) {
                var wrap;
                if (jQuery.isFunction(html)) {
                    return this.each(function (i) {
                        jQuery(this).wrapAll(html.call(this, i))
                    })
                }
                if (this[0]) {
                    wrap = jQuery(html, this[0].ownerDocument).eq(0).clone(!0);
                    if (this[0].parentNode) {
                        wrap.insertBefore(this[0])
                    }
                    wrap.map(function () {
                        var elem = this;
                        while (elem.firstElementChild) {
                            elem = elem.firstElementChild
                        }
                        return elem
                    }).append(this)
                }
                return this
            }, wrapInner: function wrapInner(html) {
                if (jQuery.isFunction(html)) {
                    return this.each(function (i) {
                        jQuery(this).wrapInner(html.call(this, i))
                    })
                }
                return this.each(function () {
                    var self = jQuery(this), contents = self.contents();
                    if (contents.length) {
                        contents.wrapAll(html)
                    } else {
                        self.append(html)
                    }
                })
            }, wrap: function wrap(html) {
                var isFunction = jQuery.isFunction(html);
                return this.each(function (i) {
                    jQuery(this).wrapAll(isFunction ? html.call(this, i) : html)
                })
            }, unwrap: function unwrap() {
                return this.parent().each(function () {
                    if (!jQuery.nodeName(this, "body")) {
                        jQuery(this).replaceWith(this.childNodes)
                    }
                }).end()
            }
        });
        jQuery.expr.filters.hidden = function (elem) {
            return !jQuery.expr.filters.visible(elem)
        };
        jQuery.expr.filters.visible = function (elem) {
            return elem.offsetWidth > 0 || elem.offsetHeight > 0 || elem.getClientRects().length > 0
        };
        var r20 = /%20/g, rbracket = /\[\]$/, rCRLF = /\r?\n/g,
            rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i,
            rsubmittable = /^(?:input|select|textarea|keygen)/i;

        function buildParams(prefix, obj, traditional, add) {
            var name;
            if (jQuery.isArray(obj)) {
                jQuery.each(obj, function (i, v) {
                    if (traditional || rbracket.test(prefix)) {
                        add(prefix, v)
                    } else {
                        buildParams(prefix + "[" + (typeof v === "object" && v != null ? i : "") + "]", v, traditional, add)
                    }
                })
            } else if (!traditional && jQuery.type(obj) === "object") {
                for (name in obj) {
                    buildParams(prefix + "[" + name + "]", obj[name], traditional, add)
                }
            } else {
                add(prefix, obj)
            }
        }

        jQuery.param = function (a, traditional) {
            var prefix, s = [], add = function add(key, value) {
                value = jQuery.isFunction(value) ? value() : value == null ? "" : value;
                s[s.length] = encodeURIComponent(key) + "=" + encodeURIComponent(value)
            };
            if (traditional === undefined) {
                traditional = jQuery.ajaxSettings && jQuery.ajaxSettings.traditional
            }
            if (jQuery.isArray(a) || a.jquery && !jQuery.isPlainObject(a)) {
                jQuery.each(a, function () {
                    add(this.name, this.value)
                })
            } else {
                for (prefix in a) {
                    buildParams(prefix, a[prefix], traditional, add)
                }
            }
            return s.join("&").replace(r20, "+")
        };
        jQuery.fn.extend({
            serialize: function serialize() {
                return jQuery.param(this.serializeArray())
            }, serializeArray: function serializeArray() {
                return this.map(function () {
                    var elements = jQuery.prop(this, "elements");
                    return elements ? jQuery.makeArray(elements) : this
                }).filter(function () {
                    var type = this.type;
                    return this.name && !jQuery(this).is(":disabled") && rsubmittable.test(this.nodeName) && !rsubmitterTypes.test(type) && (this.checked || !rcheckableType.test(type))
                }).map(function (i, elem) {
                    var val = jQuery(this).val();
                    return val == null ? null : jQuery.isArray(val) ? jQuery.map(val, function (val) {
                        return {name: elem.name, value: val.replace(rCRLF, "\r\n")}
                    }) : {name: elem.name, value: val.replace(rCRLF, "\r\n")}
                }).get()
            }
        });
        jQuery.ajaxSettings.xhr = function () {
            try {
                return new window.XMLHttpRequest()
            } catch (e) {
            }
        };
        var xhrSuccessStatus = {0: 200, 1223: 204}, xhrSupported = jQuery.ajaxSettings.xhr();
        support.cors = !!xhrSupported && "withCredentials" in xhrSupported;
        support.ajax = xhrSupported = !!xhrSupported;
        jQuery.ajaxTransport(function (options) {
            var callback, errorCallback;
            if (support.cors || xhrSupported && !options.crossDomain) {
                return {
                    send: function send(headers, complete) {
                        var i, xhr = options.xhr();
                        xhr.open(options.type, options.url, options.async, options.username, options.password);
                        if (options.xhrFields) {
                            for (i in options.xhrFields) {
                                xhr[i] = options.xhrFields[i]
                            }
                        }
                        if (options.mimeType && xhr.overrideMimeType) {
                            xhr.overrideMimeType(options.mimeType)
                        }
                        if (!options.crossDomain && !headers["X-Requested-With"]) {
                            headers["X-Requested-With"] = "XMLHttpRequest"
                        }
                        for (i in headers) {
                            xhr.setRequestHeader(i, headers[i])
                        }
                        callback = function (type) {
                            return function () {
                                if (callback) {
                                    callback = errorCallback = xhr.onload = xhr.onerror = xhr.onabort = xhr.onreadystatechange = null;
                                    if (type === "abort") {
                                        xhr.abort()
                                    } else if (type === "error") {
                                        if (typeof xhr.status !== "number") {
                                            complete(0, "error")
                                        } else {
                                            complete(xhr.status, xhr.statusText)
                                        }
                                    } else {
                                        complete(xhrSuccessStatus[xhr.status] || xhr.status, xhr.statusText, (xhr.responseType || "text") !== "text" || typeof xhr.responseText !== "string" ? {binary: xhr.response} : {text: xhr.responseText}, xhr.getAllResponseHeaders())
                                    }
                                }
                            }
                        };
                        xhr.onload = callback();
                        errorCallback = xhr.onerror = callback("error");
                        if (xhr.onabort !== undefined) {
                            xhr.onabort = errorCallback
                        } else {
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4) {
                                    window.setTimeout(function () {
                                        if (callback) {
                                            errorCallback()
                                        }
                                    })
                                }
                            }
                        }
                        callback = callback("abort");
                        try {
                            xhr.send(options.hasContent && options.data || null)
                        } catch (e) {
                            if (callback) {
                                throw e
                            }
                        }
                    }, abort: function abort() {
                        if (callback) {
                            callback()
                        }
                    }
                }
            }
        });
        jQuery.ajaxSetup({
            accepts: {script: "text/javascript, application/javascript, " + "application/ecmascript, application/x-ecmascript"},
            contents: {script: /\b(?:java|ecma)script\b/},
            converters: {
                "text script": function textScript(text) {
                    jQuery.globalEval(text);
                    return text
                }
            }
        });
        jQuery.ajaxPrefilter("script", function (s) {
            if (s.cache === undefined) {
                s.cache = !1
            }
            if (s.crossDomain) {
                s.type = "GET"
            }
        });
        jQuery.ajaxTransport("script", function (s) {
            if (s.crossDomain) {
                var script, callback;
                return {
                    send: function send(_, complete) {
                        script = jQuery("<script>").prop({
                            charset: s.scriptCharset,
                            src: s.url
                        }).on("load error", callback = function (evt) {
                            script.remove();
                            callback = null;
                            if (evt) {
                                complete(evt.type === "error" ? 404 : 200, evt.type)
                            }
                        });
                        document.head.appendChild(script[0])
                    }, abort: function abort() {
                        if (callback) {
                            callback()
                        }
                    }
                }
            }
        });
        var oldCallbacks = [], rjsonp = /(=)\?(?=&|$)|\?\?/;
        jQuery.ajaxSetup({
            jsonp: "callback", jsonpCallback: function jsonpCallback() {
                var callback = oldCallbacks.pop() || jQuery.expando + "_" + nonce++;
                this[callback] = !0;
                return callback
            }
        });
        jQuery.ajaxPrefilter("json jsonp", function (s, originalSettings, jqXHR) {
            var callbackName, overwritten, responseContainer,
                jsonProp = s.jsonp !== !1 && (rjsonp.test(s.url) ? "url" : typeof s.data === "string" && (s.contentType || "").indexOf("application/x-www-form-urlencoded") === 0 && rjsonp.test(s.data) && "data");
            if (jsonProp || s.dataTypes[0] === "jsonp") {
                callbackName = s.jsonpCallback = jQuery.isFunction(s.jsonpCallback) ? s.jsonpCallback() : s.jsonpCallback;
                if (jsonProp) {
                    s[jsonProp] = s[jsonProp].replace(rjsonp, "$1" + callbackName)
                } else if (s.jsonp !== !1) {
                    s.url += (rquery.test(s.url) ? "&" : "?") + s.jsonp + "=" + callbackName
                }
                s.converters["script json"] = function () {
                    if (!responseContainer) {
                        jQuery.error(callbackName + " was not called")
                    }
                    return responseContainer[0]
                };
                s.dataTypes[0] = "json";
                overwritten = window[callbackName];
                window[callbackName] = function () {
                    responseContainer = arguments
                };
                jqXHR.always(function () {
                    if (overwritten === undefined) {
                        jQuery(window).removeProp(callbackName)
                    } else {
                        window[callbackName] = overwritten
                    }
                    if (s[callbackName]) {
                        s.jsonpCallback = originalSettings.jsonpCallback;
                        oldCallbacks.push(callbackName)
                    }
                    if (responseContainer && jQuery.isFunction(overwritten)) {
                        overwritten(responseContainer[0])
                    }
                    responseContainer = overwritten = undefined
                });
                return "script"
            }
        });
        jQuery.parseHTML = function (data, context, keepScripts) {
            if (!data || typeof data !== "string") {
                return null
            }
            if (typeof context === "boolean") {
                keepScripts = context;
                context = !1
            }
            context = context || document;
            var parsed = rsingleTag.exec(data), scripts = !keepScripts && [];
            if (parsed) {
                return [context.createElement(parsed[1])]
            }
            parsed = buildFragment([data], context, scripts);
            if (scripts && scripts.length) {
                jQuery(scripts).remove()
            }
            return jQuery.merge([], parsed.childNodes)
        };
        var _load = jQuery.fn.load;
        jQuery.fn.load = function (url, params, callback) {
            if (typeof url !== "string" && _load) {
                return _load.apply(this, arguments)
            }
            var selector, type, response, self = this, off = url.indexOf(" ");
            if (off > -1) {
                selector = jQuery.trim(url.slice(off));
                url = url.slice(0, off)
            }
            if (jQuery.isFunction(params)) {
                callback = params;
                params = undefined
            } else if (params && typeof params === "object") {
                type = "POST"
            }
            if (self.length > 0) {
                jQuery.ajax({
                    url: url,
                    type: type || "GET",
                    dataType: "html",
                    data: params
                }).done(function (responseText) {
                    response = arguments;
                    self.html(selector ? jQuery("<div>").append(jQuery.parseHTML(responseText)).find(selector) : responseText)
                }).always(callback && function (jqXHR, status) {
                    self.each(function () {
                        callback.apply(this, response || [jqXHR.responseText, status, jqXHR])
                    })
                })
            }
            return this
        };
        jQuery.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function (i, type) {
            jQuery.fn[type] = function (fn) {
                return this.on(type, fn)
            }
        });
        jQuery.expr.filters.animated = function (elem) {
            return jQuery.grep(jQuery.timers, function (fn) {
                return elem === fn.elem
            }).length
        };

        function getWindow(elem) {
            return jQuery.isWindow(elem) ? elem : elem.nodeType === 9 && elem.defaultView
        }

        jQuery.offset = {
            setOffset: function setOffset(elem, options, i) {
                var curPosition, curLeft, curCSSTop, curTop, curOffset, curCSSLeft, calculatePosition,
                    position = jQuery.css(elem, "position"), curElem = jQuery(elem), props = {};
                if (position === "static") {
                    elem.style.position = "relative"
                }
                curOffset = curElem.offset();
                curCSSTop = jQuery.css(elem, "top");
                curCSSLeft = jQuery.css(elem, "left");
                calculatePosition = (position === "absolute" || position === "fixed") && (curCSSTop + curCSSLeft).indexOf("auto") > -1;
                if (calculatePosition) {
                    curPosition = curElem.position();
                    curTop = curPosition.top;
                    curLeft = curPosition.left
                } else {
                    curTop = parseFloat(curCSSTop) || 0;
                    curLeft = parseFloat(curCSSLeft) || 0
                }
                if (jQuery.isFunction(options)) {
                    options = options.call(elem, i, jQuery.extend({}, curOffset))
                }
                if (options.top != null) {
                    props.top = options.top - curOffset.top + curTop
                }
                if (options.left != null) {
                    props.left = options.left - curOffset.left + curLeft
                }
                if ("using" in options) {
                    options.using.call(elem, props)
                } else {
                    curElem.css(props)
                }
            }
        };
        jQuery.fn.extend({
            offset: function offset(options) {
                if (arguments.length) {
                    return options === undefined ? this : this.each(function (i) {
                        jQuery.offset.setOffset(this, options, i)
                    })
                }
                var docElem, win, elem = this[0], box = {top: 0, left: 0}, doc = elem && elem.ownerDocument;
                if (!doc) {
                    return
                }
                docElem = doc.documentElement;
                if (!jQuery.contains(docElem, elem)) {
                    return box
                }
                box = elem.getBoundingClientRect();
                win = getWindow(doc);
                return {
                    top: box.top + win.pageYOffset - docElem.clientTop,
                    left: box.left + win.pageXOffset - docElem.clientLeft
                }
            }, position: function position() {
                if (!this[0]) {
                    return
                }
                var offsetParent, offset, elem = this[0], parentOffset = {top: 0, left: 0};
                if (jQuery.css(elem, "position") === "fixed") {
                    offset = elem.getBoundingClientRect()
                } else {
                    offsetParent = this.offsetParent();
                    offset = this.offset();
                    if (!jQuery.nodeName(offsetParent[0], "html")) {
                        parentOffset = offsetParent.offset()
                    }
                    parentOffset.top += jQuery.css(offsetParent[0], "borderTopWidth", !0);
                    parentOffset.left += jQuery.css(offsetParent[0], "borderLeftWidth", !0)
                }
                return {
                    top: offset.top - parentOffset.top - jQuery.css(elem, "marginTop", !0),
                    left: offset.left - parentOffset.left - jQuery.css(elem, "marginLeft", !0)
                }
            }, offsetParent: function offsetParent() {
                return this.map(function () {
                    var offsetParent = this.offsetParent;
                    while (offsetParent && jQuery.css(offsetParent, "position") === "static") {
                        offsetParent = offsetParent.offsetParent
                    }
                    return offsetParent || documentElement
                })
            }
        });
        jQuery.each({scrollLeft: "pageXOffset", scrollTop: "pageYOffset"}, function (method, prop) {
            var top = "pageYOffset" === prop;
            jQuery.fn[method] = function (val) {
                return access(this, function (elem, method, val) {
                    var win = getWindow(elem);
                    if (val === undefined) {
                        return win ? win[prop] : elem[method]
                    }
                    if (win) {
                        win.scrollTo(!top ? val : win.pageXOffset, top ? val : win.pageYOffset)
                    } else {
                        elem[method] = val
                    }
                }, method, val, arguments.length)
            }
        });
        jQuery.each(["top", "left"], function (i, prop) {
            jQuery.cssHooks[prop] = addGetHookIf(support.pixelPosition, function (elem, computed) {
                if (computed) {
                    computed = curCSS(elem, prop);
                    return rnumnonpx.test(computed) ? jQuery(elem).position()[prop] + "px" : computed
                }
            })
        });
        jQuery.each({Height: "height", Width: "width"}, function (name, type) {
            jQuery.each({
                padding: "inner" + name,
                content: type,
                "": "outer" + name
            }, function (defaultExtra, funcName) {
                jQuery.fn[funcName] = function (margin, value) {
                    var chainable = arguments.length && (defaultExtra || typeof margin !== "boolean"),
                        extra = defaultExtra || (margin === !0 || value === !0 ? "margin" : "border");
                    return access(this, function (elem, type, value) {
                        var doc;
                        if (jQuery.isWindow(elem)) {
                            return elem.document.documentElement["client" + name]
                        }
                        if (elem.nodeType === 9) {
                            doc = elem.documentElement;
                            return Math.max(elem.body["scroll" + name], doc["scroll" + name], elem.body["offset" + name], doc["offset" + name], doc["client" + name])
                        }
                        return value === undefined ? jQuery.css(elem, type, extra) : jQuery.style(elem, type, value, extra)
                    }, type, chainable ? margin : undefined, chainable, null)
                }
            })
        });
        jQuery.fn.extend({
            bind: function bind(types, data, fn) {
                return this.on(types, null, data, fn)
            }, unbind: function unbind(types, fn) {
                return this.off(types, null, fn)
            }, delegate: function delegate(selector, types, data, fn) {
                return this.on(types, selector, data, fn)
            }, undelegate: function undelegate(selector, types, fn) {
                return arguments.length === 1 ? this.off(selector, "**") : this.off(types, selector || "**", fn)
            }, size: function size() {
                return this.length
            }
        });
        jQuery.fn.andSelf = jQuery.fn.addBack;
        if (!0) {
            !(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = function () {
                return jQuery
            }.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__), __WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__))
        }
        var _jQuery = window.jQuery, _$ = window.$;
        jQuery.noConflict = function (deep) {
            if (window.$ === jQuery) {
                window.$ = _$
            }
            if (deep && window.jQuery === jQuery) {
                window.jQuery = _jQuery
            }
            return jQuery
        };
        if (!noGlobal) {
            window.jQuery = window.$ = jQuery
        }
        return jQuery
    })
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    (0, _jquery2['default'])(document).ready(function () {
        _prestashop2['default'].on('updateCart', function (event) {
            _prestashop2['default'].cart = event.reason.cart;
            var getCartViewUrl = (0, _jquery2['default'])('.js-cart').data('refresh-url');
            var requestData = {};
            if (event && event.reason) {
                requestData = {
                    id_product_attribute: event.reason.idProductAttribute,
                    id_product: event.reason.idProduct
                }
            }
            _jquery2['default'].post(getCartViewUrl, requestData).then(function (resp) {
                (0, _jquery2['default'])('.cart-detailed-totals').replaceWith(resp.cart_detailed_totals);
                (0, _jquery2['default'])('.cart-summary-items-subtotal').replaceWith(resp.cart_summary_items_subtotal);
                (0, _jquery2['default'])('.cart-summary-totals').replaceWith(resp.cart_summary_totals);
                (0, _jquery2['default'])('.cart-detailed-actions').replaceWith(resp.cart_detailed_actions);
                (0, _jquery2['default'])('.cart-voucher').replaceWith(resp.cart_voucher);
                (0, _jquery2['default'])('.cart-overview').replaceWith(resp.cart_detailed);
                (0, _jquery2['default'])('#product_customization_id').val(0);
                (0, _jquery2['default'])('.js-cart-line-product-quantity').each(function (index, input) {
                    var $input = (0, _jquery2['default'])(input);
                    $input.attr('value', $input.val())
                });
                _prestashop2['default'].emit('updatedCart', {eventType: 'updateCart', resp: resp})
            }).fail(function (resp) {
                _prestashop2['default'].emit('handleError', {eventType: 'updateCart', resp: resp})
            })
        });
        var $body = (0, _jquery2['default'])('body');
        $body.on('click', '[data-button-action="add-to-cart"]', function (event) {
            event.preventDefault();
            if ((0, _jquery2['default'])('#quantity_wanted').val() > (0, _jquery2['default'])('[data-stock]').data('stock') && (0, _jquery2['default'])('[data-allow-oosp]').data('allow-oosp').length === 0) {
                (0, _jquery2['default'])('[data-button-action="add-to-cart"]').attr('disabled', 'disabled')
            } else {
                var _ret = (function () {
                    var $form = (0, _jquery2['default'])(event.target).closest('form');
                    var query = $form.serialize() + '&add=1&action=update';
                    var actionURL = $form.attr('action');
                    var isQuantityInputValid = function isQuantityInputValid($input) {
                        var validInput = !0;
                        $input.each(function (index, input) {
                            var $input = (0, _jquery2['default'])(input);
                            var minimalValue = parseInt($input.attr('min'), 10);
                            if (minimalValue && $input.val() < minimalValue) {
                                onInvalidQuantity($input);
                                validInput = !1
                            }
                        });
                        return validInput
                    };
                    var onInvalidQuantity = function onInvalidQuantity($input) {
                        $input.parents('.product-add-to-cart').first().find('.product-minimal-quantity').addClass('error');
                        $input.parent().find('label').addClass('error')
                    };
                    var $quantityInput = $form.find('input[min]');
                    if (!isQuantityInputValid($quantityInput)) {
                        onInvalidQuantity($quantityInput);
                        return {v: undefined}
                    }
                    _jquery2['default'].post(actionURL, query, null, 'json').then(function (resp) {
                        _prestashop2['default'].emit('updateCart', {
                            reason: {
                                idProduct: resp.id_product,
                                idProductAttribute: resp.id_product_attribute,
                                linkAction: 'add-to-cart',
                                cart: resp.cart
                            }, resp: resp
                        })
                    }).fail(function (resp) {
                        _prestashop2['default'].emit('handleError', {eventType: 'addProductToCart', resp: resp})
                    })
                })();
                if (typeof _ret === 'object') return _ret.v
            }
        });
        $body.on('submit', '[data-link-action="add-voucher"]', function (event) {
            event.preventDefault();
            var $addVoucherForm = (0, _jquery2['default'])(event.currentTarget);
            var getCartViewUrl = $addVoucherForm.attr('action');
            if (0 === $addVoucherForm.find('[name=action]').length) {
                $addVoucherForm.append((0, _jquery2['default'])('<input>', {
                    'type': 'hidden',
                    'name': 'ajax',
                    "value": 1
                }))
            }
            if (0 === $addVoucherForm.find('[name=action]').length) {
                $addVoucherForm.append((0, _jquery2['default'])('<input>', {
                    'type': 'hidden',
                    'name': 'action',
                    "value": "update"
                }))
            }
            _jquery2['default'].post(getCartViewUrl, $addVoucherForm.serialize(), null, 'json').then(function (resp) {
                if (resp.hasError) {
                    (0, _jquery2['default'])('.js-error').show().find('.js-error-text').text(resp.errors[0]);
                    return
                }
                _prestashop2['default'].emit('updateCart', {reason: event.target.dataset, resp: resp})
            }).fail(function (resp) {
                _prestashop2['default'].emit('handleError', {eventType: 'updateCart', resp: resp})
            })
        })
    });
    /***/
}), (function (module, exports) {
    module.exports = prestashop;
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    var _checkoutAddress = __webpack_require__(6);
    var _checkoutAddress2 = _interopRequireDefault(_checkoutAddress);
    var _checkoutDelivery = __webpack_require__(7);
    var _checkoutDelivery2 = _interopRequireDefault(_checkoutDelivery);
    var _checkoutPayment = __webpack_require__(8);
    var _checkoutPayment2 = _interopRequireDefault(_checkoutPayment);

    function setUpCheckout() {
        (0, _checkoutAddress2['default'])();
        (0, _checkoutDelivery2['default'])();
        (0, _checkoutPayment2['default'])();
        handleCheckoutStepChange()
    }

    function handleCheckoutStepChange() {
        (0, _jquery2['default'])('.checkout-step').off('click');
        var currentStepClass = 'js-current-step';
        var currentStepSelector = '.' + currentStepClass;
        var stepsAfterPersonalInformation = (0, _jquery2['default'])('#checkout-personal-information-step').nextAll();
        (0, _jquery2['default'])(currentStepSelector).prevAll().add(stepsAfterPersonalInformation).on('click', function (event) {
            var $nextStep = (0, _jquery2['default'])(event.target).closest('.checkout-step');
            if (!$nextStep.hasClass('-unreachable')) {
                (0, _jquery2['default'])(currentStepSelector + ', .-current').removeClass(currentStepClass + ' -current');
                $nextStep.toggleClass('-current');
                $nextStep.toggleClass(currentStepClass)
            }
            _prestashop2['default'].emit('changedCheckoutStep', {event: event})
        });
        (0, _jquery2['default'])(currentStepSelector + ':not(#checkout-personal-information-step)').nextAll().on('click', function (event) {
            (0, _jquery2['default'])(currentStepSelector + ' button.continue').click();
            _prestashop2['default'].emit('changedCheckoutStep', {event: event})
        })
    }

    (0, _jquery2['default'])(document).ready(function () {
        if ((0, _jquery2['default'])('#checkout').length === 1) {
            setUpCheckout()
        }
    });
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';
    Object.defineProperty(exports, '__esModule', {value: !0});

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    exports['default'] = function () {
        (0, _jquery2['default'])('.js-edit-addresses').on('click', function (event) {
            event.stopPropagation();
            (0, _jquery2['default'])('#checkout-addresses-step').trigger('click');
            _prestashop2['default'].emit('editAddress')
        });
        (0, _jquery2['default'])('#delivery-addresses, #invoice-addresses input[type=radio]').on('click', function () {
            (0, _jquery2['default'])('.address-item').removeClass('selected');
            (0, _jquery2['default'])('.address-item:has(input[type=radio]:checked)').addClass('selected')
        })
    };
    module.exports = exports['default'];
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';
    Object.defineProperty(exports, '__esModule', {value: !0});

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    exports['default'] = function () {
        var $body = (0, _jquery2['default'])('body');
        var deliveryFormSelector = '#js-delivery';
        var summarySelector = '#js-checkout-summary';
        var deliveryStepSelector = '#checkout-delivery-step';
        var editDeliveryButtonSelector = '.js-edit-delivery';
        var updateDeliveryForm = function updateDeliveryForm(event) {
            var $deliveryMethodForm = (0, _jquery2['default'])(deliveryFormSelector);
            var requestData = $deliveryMethodForm.serialize();
            var $inputChecked = (0, _jquery2['default'])(event.currentTarget);
            var $newDeliveryOption = $inputChecked.parents("div.delivery-option");
            _jquery2['default'].post($deliveryMethodForm.data('url-update'), requestData).then(function (resp) {
                (0, _jquery2['default'])(summarySelector).replaceWith(resp.preview);
                _prestashop2['default'].emit('updatedDeliveryForm', {
                    dataForm: $deliveryMethodForm.serializeArray(),
                    deliveryOption: $newDeliveryOption,
                    resp: resp
                })
            }).fail(function (resp) {
                _prestashop2['default'].trigger('handleError', {eventType: 'updateDeliveryOptions', resp: resp})
            })
        };
        $body.on('change', deliveryFormSelector + ' input', updateDeliveryForm);
        $body.on('click', editDeliveryButtonSelector, function (event) {
            event.stopPropagation();
            (0, _jquery2['default'])(deliveryStepSelector).trigger('click');
            _prestashop2['default'].emit('editDelivery')
        })
    };
    module.exports = exports['default'];
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';
    Object.defineProperty(exports, '__esModule', {value: !0});
    var _createClass = (function () {
        function defineProperties(target, props) {
            for (var i = 0; i < props.length; i++) {
                var descriptor = props[i];
                descriptor.enumerable = descriptor.enumerable || !1;
                descriptor.configurable = !0;
                if ('value' in descriptor) descriptor.writable = !0;
                Object.defineProperty(target, descriptor.key, descriptor)
            }
        }

        return function (Constructor, protoProps, staticProps) {
            if (protoProps) defineProperties(Constructor.prototype, protoProps);
            if (staticProps) defineProperties(Constructor, staticProps);
            return Constructor
        }
    })();

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    function _classCallCheck(instance, Constructor) {
        if (!(instance instanceof Constructor)) {
            throw new TypeError('Cannot call a class as a function')
        }
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var Payment = (function () {
        function Payment() {
            _classCallCheck(this, Payment);
            this.confirmationSelector = '#payment-confirmation';
            this.paymentSelector = '#payment-section';
            this.conditionsSelector = '#conditions-to-approve';
            this.conditionAlertSelector = '.js-alert-payment-conditions';
            this.additionalInformatonSelector = '.js-additional-information';
            this.optionsForm = '.js-payment-option-form'
        }

        _createClass(Payment, [{
            key: 'init', value: function init() {
                (0, _jquery2['default'])(this.paymentSelector + ' input[type="checkbox"][disabled]').attr('disabled', !1);
                var $body = (0, _jquery2['default'])('body');
                $body.on('change', this.conditionsSelector + ' input[type="checkbox"]', _jquery2['default'].proxy(this.toggleOrderButton, this));
                $body.on('change', 'input[name="payment-option"]', _jquery2['default'].proxy(this.toggleOrderButton, this));
                $body.on('click', this.confirmationSelector + ' button', _jquery2['default'].proxy(this.confirm, this));
                this.collapseOptions()
            }
        }, {
            key: 'collapseOptions', value: function collapseOptions() {
                (0, _jquery2['default'])(this.additionalInformatonSelector + ', ' + this.optionsForm).hide()
            }
        }, {
            key: 'getSelectedOption', value: function getSelectedOption() {
                return (0, _jquery2['default'])('input[name="payment-option"]:checked').attr('id')
            }
        }, {
            key: 'hideConfirmation', value: function hideConfirmation() {
                (0, _jquery2['default'])(this.confirmationSelector).hide()
            }
        }, {
            key: 'showConfirmation', value: function showConfirmation() {
                (0, _jquery2['default'])(this.confirmationSelector).show()
            }
        }, {
            key: 'toggleOrderButton', value: function toggleOrderButton() {
                var show = !0;
                (0, _jquery2['default'])(this.conditionsSelector + ' input[type="checkbox"]').each(function (_, checkbox) {
                    if (!checkbox.checked) {
                        show = !1
                    }
                });
                this.collapseOptions();
                var selectedOption = this.getSelectedOption();
                if (!selectedOption) {
                    show = !1
                }
                (0, _jquery2['default'])('#' + selectedOption + '-additional-information').show();
                (0, _jquery2['default'])('#pay-with-' + selectedOption + '-form').show();
                (0, _jquery2['default'])('.js-payment-binary').hide();
                if ((0, _jquery2['default'])('#' + selectedOption).hasClass('binary')) {
                    var paymentOption = this.getPaymentOptionSelector(selectedOption);
                    this.hideConfirmation();
                    (0, _jquery2['default'])(paymentOption).show();
                    if (show) {
                        (0, _jquery2['default'])(paymentOption).removeClass('disabled')
                    } else {
                        (0, _jquery2['default'])(paymentOption).addClass('disabled')
                    }
                } else {
                    this.showConfirmation();
                    (0, _jquery2['default'])(this.confirmationSelector + ' button').attr('disabled', !show);
                    if (show) {
                        (0, _jquery2['default'])(this.conditionAlertSelector).hide()
                    } else {
                        (0, _jquery2['default'])(this.conditionAlertSelector).show()
                    }
                }
            }
        }, {
            key: 'getPaymentOptionSelector', value: function getPaymentOptionSelector(option) {
                var moduleName = (0, _jquery2['default'])('#' + option).data('module-name');
                return '.js-payment-' + moduleName
            }
        }, {
            key: 'confirm', value: function confirm() {
                var option = this.getSelectedOption();
                if (option) {
                    (0, _jquery2['default'])('#pay-with-' + option + '-form form').submit()
                }
            }
        }]);
        return Payment
    })();
    exports['default'] = function () {
        var payment = new Payment();
        payment.init();
        return payment
    };
    module.exports = exports['default'];
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var pendingQuery = !1;

    function updateResults(data) {
        pendingQuery = !1;
        prestashop.emit('updateProductList', data);
        window.history.pushState(data, undefined, data.current_url);
        window.scrollTo(0, 0)
    }

    function handleError() {
        pendingQuery = !1
    }

    function makeQuery(url) {
        if (pendingQuery) {
        } else {
            var slightlyDifferentURL = [url, url.indexOf('?') >= 0 ? '&' : '?', 'from-xhr'].join('');
            _jquery2['default'].get(slightlyDifferentURL, null, null, 'json').then(updateResults).fail(handleError)
        }
    }

    (0, _jquery2['default'])(document).ready(function () {
        prestashop.on('updateFacets', function (param) {
            makeQuery(param)
        })
    });
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    (0, _jquery2['default'])(document).ready(function () {
        (0, _jquery2['default'])('body').on('click', '.quick-view', function (event) {
            _prestashop2['default'].emit('clickQuickView', {dataset: (0, _jquery2['default'])(event.target).closest('.js-product-miniature').data()});
            event.preventDefault()
        })
    });
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);
    var _common = __webpack_require__(12);
    (0, _jquery2['default'])(document).ready(function () {
        (0, _jquery2['default'])('body').on('change', '.product-variants [data-product-attribute]', function () {
            (0, _jquery2['default'])("input[name$='refresh']").click()
        });
        (0, _jquery2['default'])('body').on('click', '.product-refresh', function (event, extraParameters) {
            var $productRefresh = (0, _jquery2['default'])(this);
            event.preventDefault();
            var eventType = 'updatedProductCombination';
            if (typeof extraParameters !== 'undefined' && extraParameters.eventType) {
                eventType = extraParameters.eventType
            }
            var preview = (0, _common.psGetRequestParameter)('preview');
            if (preview !== null) {
                preview = '&preview=' + preview
            } else {
                preview = ''
            }
            var query = (0, _jquery2['default'])(event.target.form).serialize() + '&ajax=1&action=productrefresh' + preview;
            var actionURL = (0, _jquery2['default'])(event.target.form).attr('action');
            _jquery2['default'].post(actionURL, query, null, 'json').then(function (resp) {
                _prestashop2['default'].emit('updateProduct', {
                    reason: {productUrl: resp.productUrl},
                    refreshUrl: $productRefresh.data('url-update'),
                    eventType: eventType,
                    resp: resp
                })
            })
        });
        _prestashop2['default'].on('updateProduct', function (event) {
            if (typeof event.refreshUrl == "undefined") {
                event.refreshUrl = !0
            }
            var eventType = event.eventType;
            var replaceAddToCartSections = function replaceAddToCartSections(addCartHtml) {
                var $addToCartSnippet = (0, _jquery2['default'])(addCartHtml);
                var $addProductToCart = (0, _jquery2['default'])('.product-add-to-cart');

                function replaceAddToCartSection(replacement) {
                    var replace = replacement.$addToCartSnippet.find(replacement.targetSelector);
                    if ((0, _jquery2['default'])(replacement.$targetParent.find(replacement.targetSelector)).length > 0) {
                        if (replace.length > 0) {
                            (0, _jquery2['default'])(replacement.$targetParent.find(replacement.targetSelector)).replaceWith(replace[0].outerHTML)
                        } else {
                            (0, _jquery2['default'])(replacement.$targetParent.find(replacement.targetSelector)).html('')
                        }
                    }
                }

                var productAvailabilitySelector = '.add';
                replaceAddToCartSection({
                    $addToCartSnippet: $addToCartSnippet,
                    $targetParent: $addProductToCart,
                    targetSelector: productAvailabilitySelector
                });
                var productAvailabilityMessageSelector = '#product-availability';
                replaceAddToCartSection({
                    $addToCartSnippet: $addToCartSnippet,
                    $targetParent: $addProductToCart,
                    targetSelector: productAvailabilityMessageSelector
                });
                var productMinimalQuantitySelector = '.product-minimal-quantity';
                replaceAddToCartSection({
                    $addToCartSnippet: $addToCartSnippet,
                    $targetParent: $addProductToCart,
                    targetSelector: productMinimalQuantitySelector
                })
            };
            _jquery2['default'].post(event.reason.productUrl, {
                ajax: '1',
                action: 'refresh'
            }, null, 'json').then(function (resp) {
                (0, _jquery2['default'])('.product-prices').replaceWith(resp.product_prices);
                (0, _jquery2['default'])('.product-customization').replaceWith(resp.product_customization);
                (0, _jquery2['default'])('.product-variants').replaceWith(resp.product_variants);
                (0, _jquery2['default'])('.product-discounts').replaceWith(resp.product_discounts);
                (0, _jquery2['default'])('.images-container').replaceWith(resp.product_cover_thumbnails);
                (0, _jquery2['default'])('.product-additional-info').replaceWith(resp.product_additional_info);
                (0, _jquery2['default'])('#product-details').replaceWith(resp.product_details);
                var $productAddToCart = undefined;
                (0, _jquery2['default'])(resp.product_add_to_cart).each(function (index, value) {
                    if ((0, _jquery2['default'])(value).hasClass('product-add-to-cart')) {
                        $productAddToCart = (0, _jquery2['default'])(value)
                    }
                });
                replaceAddToCartSections($productAddToCart);
                var minimalProductQuantity = parseInt(resp.product_minimal_quantity, 10);
                var quantityInputSelector = '#quantity_wanted';
                var quantityInput = (0, _jquery2['default'])(quantityInputSelector);
                var quantity_wanted = quantityInput.val();
                if (!isNaN(minimalProductQuantity) && quantity_wanted < minimalProductQuantity && eventType !== 'updatedProductQuantity') {
                    quantityInput.attr('min', minimalProductQuantity);
                    quantityInput.val(minimalProductQuantity)
                }
                if (event.refreshUrl) {
                    window.history.pushState({id_product_attribute: resp.id_product_attribute}, undefined, resp.product_url)
                }
                _prestashop2['default'].emit('updatedProduct', resp)
            })
        })
    });
    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';
    Object.defineProperty(exports, '__esModule', {value: !0});
    exports.psShowHide = psShowHide;
    exports.psGetRequestParameter = psGetRequestParameter;

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);

    function psShowHide() {
        (0, _jquery2['default'])('.ps-shown-by-js').show();
        (0, _jquery2['default'])('.ps-hidden-by-js').hide()
    }

    function psGetRequestParameter(paramName) {
        var vars = {};
        window.location.href.replace(location.hash, '').replace(/[?&]+([^=&]+)=?([^&]*)?/gi, function (m, key, value) {
            vars[key] = value !== undefined ? value : ''
        });
        if (paramName) {
            return vars[paramName] ? vars[paramName] : null
        }
        return vars
    }

    /***/
}), (function (module, exports, __webpack_require__) {
    'use strict';

    function _interopRequireDefault(obj) {
        return obj && obj.__esModule ? obj : {'default': obj}
    }

    var _jquery = __webpack_require__(2);
    var _jquery2 = _interopRequireDefault(_jquery);
    var _prestashop = __webpack_require__(4);
    var _prestashop2 = _interopRequireDefault(_prestashop);

    function handleCountryChange(selectors) {
        (0, _jquery2['default'])('body').on('change', selectors.country, function () {
            var requestData = {
                id_country: (0, _jquery2['default'])(selectors.country).val(),
                id_address: (0, _jquery2['default'])(selectors.address + ' form').data('id-address')
            };
            var getFormViewUrl = (0, _jquery2['default'])(selectors.address + ' form').data('refresh-url');
            var formFieldsSelector = selectors.address + ' input';
            _jquery2['default'].post(getFormViewUrl, requestData).then(function (resp) {
                var inputs = [];
                (0, _jquery2['default'])(formFieldsSelector).each(function () {
                    inputs[(0, _jquery2['default'])(this).prop('name')] = (0, _jquery2['default'])(this).val()
                });
                (0, _jquery2['default'])(selectors.address).replaceWith(resp.address_form);
                (0, _jquery2['default'])(formFieldsSelector).each(function () {
                    (0, _jquery2['default'])(this).val(inputs[(0, _jquery2['default'])(this).prop('name')])
                });
                _prestashop2['default'].emit('updatedAddressForm', {
                    target: (0, _jquery2['default'])(selectors.address),
                    resp: resp
                })
            }).fail(function (resp) {
                _prestashop2['default'].emit('handleError', {eventType: 'updateAddressForm', resp: resp})
            })
        })
    }

    (0, _jquery2['default'])(document).ready(function () {
        handleCountryChange({'country': '.js-country', 'address': '.js-address-form'})
    });
    /***/
}), (function (module, exports) {
    'use strict';

    function EventEmitter() {
        this._events = this._events || {};
        this._maxListeners = this._maxListeners || undefined
    }

    module.exports = EventEmitter;
    EventEmitter.EventEmitter = EventEmitter;
    EventEmitter.prototype._events = undefined;
    EventEmitter.prototype._maxListeners = undefined;
    EventEmitter.defaultMaxListeners = 10;
    EventEmitter.prototype.setMaxListeners = function (n) {
        if (!isNumber(n) || n < 0 || isNaN(n)) throw TypeError('n must be a positive number');
        this._maxListeners = n;
        return this
    };
    EventEmitter.prototype.emit = function (type) {
        var er, handler, len, args, i, listeners;
        if (!this._events) this._events = {};
        if (type === 'error') {
            if (!this._events.error || isObject(this._events.error) && !this._events.error.length) {
                er = arguments[1];
                if (er instanceof Error) {
                    throw er
                } else {
                    var err = new Error('Uncaught, unspecified "error" event. (' + er + ')');
                    err.context = er;
                    throw err
                }
            }
        }
        handler = this._events[type];
        if (isUndefined(handler)) return !1;
        if (isFunction(handler)) {
            switch (arguments.length) {
                case 1:
                    handler.call(this);
                    break;
                case 2:
                    handler.call(this, arguments[1]);
                    break;
                case 3:
                    handler.call(this, arguments[1], arguments[2]);
                    break;
                default:
                    args = Array.prototype.slice.call(arguments, 1);
                    handler.apply(this, args)
            }
        } else if (isObject(handler)) {
            args = Array.prototype.slice.call(arguments, 1);
            listeners = handler.slice();
            len = listeners.length;
            for (i = 0; i < len; i++) listeners[i].apply(this, args);
        }
        return !0
    };
    EventEmitter.prototype.addListener = function (type, listener) {
        var m;
        if (!isFunction(listener)) throw TypeError('listener must be a function');
        if (!this._events) this._events = {};
        if (this._events.newListener) this.emit('newListener', type, isFunction(listener.listener) ? listener.listener : listener);
        if (!this._events[type])
            this._events[type] = listener; else if (isObject(this._events[type]))
            this._events[type].push(listener); else this._events[type] = [this._events[type], listener];
        if (isObject(this._events[type]) && !this._events[type].warned) {
            if (!isUndefined(this._maxListeners)) {
                m = this._maxListeners
            } else {
                m = EventEmitter.defaultMaxListeners
            }
            if (m && m > 0 && this._events[type].length > m) {
                this._events[type].warned = !0;
                console.error('(node) warning: possible EventEmitter memory ' + 'leak detected. %d listeners added. ' + 'Use emitter.setMaxListeners() to increase limit.', this._events[type].length);
                if (typeof console.trace === 'function') {
                    console.trace()
                }
            }
        }
        return this
    };
    EventEmitter.prototype.on = EventEmitter.prototype.addListener;
    EventEmitter.prototype.once = function (type, listener) {
        if (!isFunction(listener)) throw TypeError('listener must be a function');
        var fired = !1;

        function g() {
            this.removeListener(type, g);
            if (!fired) {
                fired = !0;
                listener.apply(this, arguments)
            }
        }

        g.listener = listener;
        this.on(type, g);
        return this
    };
    EventEmitter.prototype.removeListener = function (type, listener) {
        var list, position, length, i;
        if (!isFunction(listener)) throw TypeError('listener must be a function');
        if (!this._events || !this._events[type]) return this;
        list = this._events[type];
        length = list.length;
        position = -1;
        if (list === listener || isFunction(list.listener) && list.listener === listener) {
            delete this._events[type];
            if (this._events.removeListener) this.emit('removeListener', type, listener)
        } else if (isObject(list)) {
            for (i = length; i-- > 0;) {
                if (list[i] === listener || list[i].listener && list[i].listener === listener) {
                    position = i;
                    break
                }
            }
            if (position < 0) return this;
            if (list.length === 1) {
                list.length = 0;
                delete this._events[type]
            } else {
                list.splice(position, 1)
            }
            if (this._events.removeListener) this.emit('removeListener', type, listener)
        }
        return this
    };
    EventEmitter.prototype.removeAllListeners = function (type) {
        var key, listeners;
        if (!this._events) return this;
        if (!this._events.removeListener) {
            if (arguments.length === 0) this._events = {}; else if (this._events[type]) delete this._events[type];
            return this
        }
        if (arguments.length === 0) {
            for (key in this._events) {
                if (key === 'removeListener') continue;
                this.removeAllListeners(key)
            }
            this.removeAllListeners('removeListener');
            this._events = {};
            return this
        }
        listeners = this._events[type];
        if (isFunction(listeners)) {
            this.removeListener(type, listeners)
        } else if (listeners) {
            while (listeners.length) this.removeListener(type, listeners[listeners.length - 1])
        }
        delete this._events[type];
        return this
    };
    EventEmitter.prototype.listeners = function (type) {
        var ret;
        if (!this._events || !this._events[type]) ret = []; else if (isFunction(this._events[type])) ret = [this._events[type]]; else ret = this._events[type].slice();
        return ret
    };
    EventEmitter.prototype.listenerCount = function (type) {
        if (this._events) {
            var evlistener = this._events[type];
            if (isFunction(evlistener)) return 1; else if (evlistener) return evlistener.length
        }
        return 0
    };
    EventEmitter.listenerCount = function (emitter, type) {
        return emitter.listenerCount(type)
    };

    function isFunction(arg) {
        return typeof arg === 'function'
    }

    function isNumber(arg) {
        return typeof arg === 'number'
    }

    function isObject(arg) {
        return typeof arg === 'object' && arg !== null
    }

    function isUndefined(arg) {
        return arg === void 0
    }

    /***/
})]);
!function (t) {
    function e(i) {
        if (n[i]) return n[i].exports;
        var r = n[i] = {i: i, l: !1, exports: {}};
        return t[i].call(r.exports, r, r.exports, e), r.l = !0, r.exports
    }

    var n = {};
    e.m = t, e.c = n, e.i = function (t) {
        return t
    }, e.d = function (t, n, i) {
        e.o(t, n) || Object.defineProperty(t, n, {configurable: !1, enumerable: !0, get: i})
    }, e.n = function (t) {
        var n = t && t.__esModule ? function () {
            return t.default
        } : function () {
            return t
        };
        return e.d(n, "a", n), n
    }, e.o = function (t, e) {
        return Object.prototype.hasOwnProperty.call(t, e)
    }, e.p = "", e(e.s = 26)
}([function (t, e) {
    t.exports = jQuery
}, function (t, e) {
    t.exports = prestashop
}, function (t, e, n) {
    "use strict";

    function i(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    Object.defineProperty(e, "__esModule", {value: !0});
    var r = function () {
        function t(t, e) {
            for (var n = 0; n < e.length; n++) {
                var i = e[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
            }
        }

        return function (e, n, i) {
            return n && t(e.prototype, n), i && t(e, i), e
        }
    }(), o = n(0), s = function (t) {
        return t && t.__esModule ? t : {default: t}
    }(o), a = function () {
        function t(e) {
            i(this, t), this.el = e
        }

        return r(t, [{
            key: "init", value: function () {
                this.el.on("show.bs.dropdown", function (t, e) {
                    e ? (0, s.default)("#" + e).find(".dropdown-menu").first().stop(!0, !0).slideDown() : (0, s.default)(t.target).find(".dropdown-menu").first().stop(!0, !0).slideDown()
                }), this.el.on("hide.bs.dropdown", function (t, e) {
                    e ? (0, s.default)("#" + e).find(".dropdown-menu").first().stop(!0, !0).slideUp() : (0, s.default)(t.target).find(".dropdown-menu").first().stop(!0, !0).slideUp()
                }), this.el.find("select.link").each(function (t, e) {
                    (0, s.default)(e).on("change", function (t) {
                        window.location = (0, s.default)(this).val()
                    })
                })
            }
        }]), t
    }();
    e.default = a, t.exports = e.default
}, function (t, e, n) {
    "use strict";

    function i(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    Object.defineProperty(e, "__esModule", {value: !0});
    var r = function () {
        function t(t, e) {
            for (var n = 0; n < e.length; n++) {
                var i = e[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
            }
        }

        return function (e, n, i) {
            return n && t(e.prototype, n), i && t(e, i), e
        }
    }(), o = n(0), s = function (t) {
        return t && t.__esModule ? t : {default: t}
    }(o), a = function () {
        function t() {
            i(this, t)
        }

        return r(t, [{
            key: "init", value: function () {
                (0, s.default)(".js-product-miniature").each(function (t, e) {
                    var n = (0, s.default)(e).find(".discount-percentage"), i = (0, s.default)(e).find(".on-sale"),
                        r = (0, s.default)(e).find(".new");
                    n.length && (r.css("top", 2 * n.height() + 10), n.css("top", -(0, s.default)(e).find(".thumbnail-container").height() + (0, s.default)(e).find(".product-description").height() + 10)), i.length && (n.css("top", parseFloat(n.css("top")) + i.height() + 10), r.css("top", 2 * n.height() + i.height() + 20)), (0, s.default)(e).find(".color").length > 5 && function () {
                        var t = 0;
                        (0, s.default)(e).find(".color").each(function (e, n) {
                            e > 4 && ((0, s.default)(n).hide(), t++)
                        }), (0, s.default)(e).find(".js-count").append("+" + t)
                    }()
                })
            }
        }]), t
    }();
    e.default = a, t.exports = e.default
}, function (t, e, n) {
    "use strict";
    var i, r;
    !function (t) {
        function e(t) {
            var e = t.length, i = n.type(t);
            return "function" !== i && !n.isWindow(t) && (!(1 !== t.nodeType || !e) || ("array" === i || 0 === e || "number" == typeof e && e > 0 && e - 1 in t))
        }

        if (!t.jQuery) {
            var n = function t(e, n) {
                return new t.fn.init(e, n)
            };
            n.isWindow = function (t) {
                return t && t === t.window
            }, n.type = function (t) {
                return t ? "object" == typeof t || "function" == typeof t ? r[s.call(t)] || "object" : typeof t : t + ""
            }, n.isArray = Array.isArray || function (t) {
                return "array" === n.type(t)
            }, n.isPlainObject = function (t) {
                var e;
                if (!t || "object" !== n.type(t) || t.nodeType || n.isWindow(t)) return !1;
                try {
                    if (t.constructor && !o.call(t, "constructor") && !o.call(t.constructor.prototype, "isPrototypeOf")) return !1
                } catch (t) {
                    return !1
                }
                for (e in t) ;
                return void 0 === e || o.call(t, e)
            }, n.each = function (t, n, i) {
                var r = 0, o = t.length, s = e(t);
                if (i) {
                    if (s) for (; r < o && !1 !== n.apply(t[r], i); r++) ; else for (r in t) if (t.hasOwnProperty(r) && !1 === n.apply(t[r], i)) break
                } else if (s) for (; r < o && !1 !== n.call(t[r], r, t[r]); r++) ; else for (r in t) if (t.hasOwnProperty(r) && !1 === n.call(t[r], r, t[r])) break;
                return t
            }, n.data = function (t, e, r) {
                if (void 0 === r) {
                    var o = t[n.expando], s = o && i[o];
                    if (void 0 === e) return s;
                    if (s && e in s) return s[e]
                } else if (void 0 !== e) {
                    var a = t[n.expando] || (t[n.expando] = ++n.uuid);
                    return i[a] = i[a] || {}, i[a][e] = r, r
                }
            }, n.removeData = function (t, e) {
                var r = t[n.expando], o = r && i[r];
                o && (e ? n.each(e, function (t, e) {
                    delete o[e]
                }) : delete i[r])
            }, n.extend = function () {
                var t, e, i, r, o, s, a = arguments[0] || {}, l = 1, u = arguments.length, c = !1;
                for ("boolean" == typeof a && (c = a, a = arguments[l] || {}, l++), "object" != typeof a && "function" !== n.type(a) && (a = {}), l === u && (a = this, l--); l < u; l++) if (o = arguments[l]) for (r in o) o.hasOwnProperty(r) && (t = a[r], i = o[r], a !== i && (c && i && (n.isPlainObject(i) || (e = n.isArray(i))) ? (e ? (e = !1, s = t && n.isArray(t) ? t : []) : s = t && n.isPlainObject(t) ? t : {}, a[r] = n.extend(c, s, i)) : void 0 !== i && (a[r] = i)));
                return a
            }, n.queue = function (t, i, r) {
                if (t) {
                    i = (i || "fx") + "queue";
                    var o = n.data(t, i);
                    return r ? (!o || n.isArray(r) ? o = n.data(t, i, function (t, n) {
                        var i = n || [];
                        return t && (e(Object(t)) ? function (t, e) {
                            for (var n = +e.length, i = 0, r = t.length; i < n;) t[r++] = e[i++];
                            if (n !== n) for (; void 0 !== e[i];) t[r++] = e[i++];
                            t.length = r
                        }(i, "string" == typeof t ? [t] : t) : [].push.call(i, t)), i
                    }(r)) : o.push(r), o) : o || []
                }
            }, n.dequeue = function (t, e) {
                n.each(t.nodeType ? [t] : t, function (t, i) {
                    e = e || "fx";
                    var r = n.queue(i, e), o = r.shift();
                    "inprogress" === o && (o = r.shift()), o && ("fx" === e && r.unshift("inprogress"), o.call(i, function () {
                        n.dequeue(i, e)
                    }))
                })
            }, n.fn = n.prototype = {
                init: function (t) {
                    if (t.nodeType) return this[0] = t, this;
                    throw new Error("Not a DOM node.")
                }, offset: function () {
                    var e = this[0].getBoundingClientRect ? this[0].getBoundingClientRect() : {top: 0, left: 0};
                    return {
                        top: e.top + (t.pageYOffset || document.scrollTop || 0) - (document.clientTop || 0),
                        left: e.left + (t.pageXOffset || document.scrollLeft || 0) - (document.clientLeft || 0)
                    }
                }, position: function () {
                    var t = this[0], e = function (t) {
                            for (var e = t.offsetParent; e && "html" !== e.nodeName.toLowerCase() && e.style && "static" === e.style.position;) e = e.offsetParent;
                            return e || document
                        }(t), i = this.offset(),
                        r = /^(?:body|html)$/i.test(e.nodeName) ? {top: 0, left: 0} : n(e).offset();
                    return i.top -= parseFloat(t.style.marginTop) || 0, i.left -= parseFloat(t.style.marginLeft) || 0, e.style && (r.top += parseFloat(e.style.borderTopWidth) || 0, r.left += parseFloat(e.style.borderLeftWidth) || 0), {
                        top: i.top - r.top,
                        left: i.left - r.left
                    }
                }
            };
            var i = {};
            n.expando = "velocity" + (new Date).getTime(), n.uuid = 0;
            for (var r = {}, o = r.hasOwnProperty, s = r.toString, a = "Boolean Number String Function Array Date RegExp Object Error".split(" "), l = 0; l < a.length; l++) r["[object " + a[l] + "]"] = a[l].toLowerCase();
            n.fn.init.prototype = n.fn, t.Velocity = {Utilities: n}
        }
    }(window), function (o) {
        "object" == typeof t && "object" == typeof t.exports ? t.exports = o() : (i = o, void 0 !== (r = "function" == typeof i ? i.call(e, n, e, t) : i) && (t.exports = r))
    }(function () {
        return function (t, e, n, i) {
            function r(t) {
                for (var e = -1, n = t ? t.length : 0, i = []; ++e < n;) {
                    var r = t[e];
                    r && i.push(r)
                }
                return i
            }

            function o(t) {
                return _.isWrapped(t) ? t = y.call(t) : _.isNode(t) && (t = [t]), t
            }

            function s(t) {
                var e = h.data(t, "velocity");
                return null === e ? i : e
            }

            function a(t, e) {
                var n = s(t);
                n && n.delayTimer && !n.delayPaused && (n.delayRemaining = n.delay - e + n.delayBegin, n.delayPaused = !0, clearTimeout(n.delayTimer.setTimeout))
            }

            function l(t, e) {
                var n = s(t);
                n && n.delayTimer && n.delayPaused && (n.delayPaused = !1, n.delayTimer.setTimeout = setTimeout(n.delayTimer.next, n.delayRemaining))
            }

            function u(t) {
                return function (e) {
                    return Math.round(e * t) * (1 / t)
                }
            }

            function c(t, n, i, r) {
                function o(t, e) {
                    return 1 - 3 * e + 3 * t
                }

                function s(t, e) {
                    return 3 * e - 6 * t
                }

                function a(t) {
                    return 3 * t
                }

                function l(t, e, n) {
                    return ((o(e, n) * t + s(e, n)) * t + a(e)) * t
                }

                function u(t, e, n) {
                    return 3 * o(e, n) * t * t + 2 * s(e, n) * t + a(e)
                }

                function c(e, n) {
                    for (var r = 0; r < m; ++r) {
                        var o = u(n, t, i);
                        if (0 === o) return n;
                        n -= (l(n, t, i) - e) / o
                    }
                    return n
                }

                function f() {
                    for (var e = 0; e < b; ++e) S[e] = l(e * _, t, i)
                }

                function d(e, n, r) {
                    var o, s, a = 0;
                    do {
                        s = n + (r - n) / 2, o = l(s, t, i) - e, o > 0 ? r = s : n = s
                    } while (Math.abs(o) > v && ++a < y);
                    return s
                }

                function p(e) {
                    for (var n = 0, r = 1, o = b - 1; r !== o && S[r] <= e; ++r) n += _;
                    --r;
                    var s = (e - S[r]) / (S[r + 1] - S[r]), a = n + s * _, l = u(a, t, i);
                    return l >= g ? c(e, a) : 0 === l ? a : d(e, n, n + _)
                }

                function h() {
                    E = !0, t === n && i === r || f()
                }

                var m = 4, g = .001, v = 1e-7, y = 10, b = 11, _ = 1 / (b - 1), x = "Float32Array" in e;
                if (4 !== arguments.length) return !1;
                for (var w = 0; w < 4; ++w) if ("number" != typeof arguments[w] || isNaN(arguments[w]) || !isFinite(arguments[w])) return !1;
                t = Math.min(t, 1), i = Math.min(i, 1), t = Math.max(t, 0), i = Math.max(i, 0);
                var S = x ? new Float32Array(b) : new Array(b), E = !1, C = function (e) {
                    return E || h(), t === n && i === r ? e : 0 === e ? 0 : 1 === e ? 1 : l(p(e), n, r)
                };
                C.getControlPoints = function () {
                    return [{x: t, y: n}, {x: i, y: r}]
                };
                var T = "generateBezier(" + [t, n, i, r] + ")";
                return C.toString = function () {
                    return T
                }, C
            }

            function f(t, e) {
                var n = t;
                return _.isString(t) ? E.Easings[t] || (n = !1) : n = _.isArray(t) && 1 === t.length ? u.apply(null, t) : _.isArray(t) && 2 === t.length ? C.apply(null, t.concat([e])) : !(!_.isArray(t) || 4 !== t.length) && c.apply(null, t), !1 === n && (n = E.Easings[E.defaults.easing] ? E.defaults.easing : S), n
            }

            function d(t) {
                if (t) {
                    var e = E.timestamp && !0 !== t ? t : v.now(), n = E.State.calls.length;
                    n > 1e4 && (E.State.calls = r(E.State.calls), n = E.State.calls.length);
                    for (var o = 0; o < n; o++) if (E.State.calls[o]) {
                        var a = E.State.calls[o], l = a[0], u = a[2], c = a[3], f = !!c, g = null, y = a[5], b = a[6];
                        if (c || (c = E.State.calls[o][3] = e - 16), y) {
                            if (!0 !== y.resume) continue;
                            c = a[3] = Math.round(e - b - 16), a[5] = null
                        }
                        b = a[6] = e - c;
                        for (var x = Math.min(b / u.duration, 1), w = 0, S = l.length; w < S; w++) {
                            var C = l[w], A = C.element;
                            if (s(A)) {
                                var O = !1;
                                if (u.display !== i && null !== u.display && "none" !== u.display) {
                                    if ("flex" === u.display) {
                                        var k = ["-webkit-box", "-moz-box", "-ms-flexbox", "-webkit-flex"];
                                        h.each(k, function (t, e) {
                                            T.setPropertyValue(A, "display", e)
                                        })
                                    }
                                    T.setPropertyValue(A, "display", u.display)
                                }
                                u.visibility !== i && "hidden" !== u.visibility && T.setPropertyValue(A, "visibility", u.visibility);
                                for (var D in C) if (C.hasOwnProperty(D) && "element" !== D) {
                                    var N, P = C[D], L = _.isString(P.easing) ? E.Easings[P.easing] : P.easing;
                                    if (_.isString(P.pattern)) {
                                        var j = 1 === x ? function (t, e, n) {
                                            var i = P.endValue[e];
                                            return n ? Math.round(i) : i
                                        } : function (t, e, n) {
                                            var i = P.startValue[e], r = P.endValue[e] - i, o = i + r * L(x, u, r);
                                            return n ? Math.round(o) : o
                                        };
                                        N = P.pattern.replace(/{(\d+)(!)?}/g, j)
                                    } else if (1 === x) N = P.endValue; else {
                                        var B = P.endValue - P.startValue;
                                        N = P.startValue + B * L(x, u, B)
                                    }
                                    if (!f && N === P.currentValue) continue;
                                    if (P.currentValue = N, "tween" === D) g = N; else {
                                        var V;
                                        if (T.Hooks.registered[D]) {
                                            V = T.Hooks.getRoot(D);
                                            var F = s(A).rootPropertyValueCache[V];
                                            F && (P.rootPropertyValue = F)
                                        }
                                        var R = T.setPropertyValue(A, D, P.currentValue + (m < 9 && 0 === parseFloat(N) ? "" : P.unitType), P.rootPropertyValue, P.scrollData);
                                        T.Hooks.registered[D] && (T.Normalizations.registered[V] ? s(A).rootPropertyValueCache[V] = T.Normalizations.registered[V]("extract", null, R[1]) : s(A).rootPropertyValueCache[V] = R[1]), "transform" === R[0] && (O = !0)
                                    }
                                }
                                u.mobileHA && s(A).transformCache.translate3d === i && (s(A).transformCache.translate3d = "(0px, 0px, 0px)", O = !0), O && T.flushTransformCache(A)
                            }
                        }
                        u.display !== i && "none" !== u.display && (E.State.calls[o][2].display = !1), u.visibility !== i && "hidden" !== u.visibility && (E.State.calls[o][2].visibility = !1), u.progress && u.progress.call(a[1], a[1], x, Math.max(0, c + u.duration - e), c, g), 1 === x && p(o)
                    }
                }
                E.State.isTicking && I(d)
            }

            function p(t, e) {
                if (!E.State.calls[t]) return !1;
                for (var n = E.State.calls[t][0], r = E.State.calls[t][1], o = E.State.calls[t][2], a = E.State.calls[t][4], l = !1, u = 0, c = n.length; u < c; u++) {
                    var f = n[u].element;
                    e || o.loop || ("none" === o.display && T.setPropertyValue(f, "display", o.display), "hidden" === o.visibility && T.setPropertyValue(f, "visibility", o.visibility));
                    var d = s(f);
                    if (!0 !== o.loop && (h.queue(f)[1] === i || !/\.velocityQueueEntryFlag/i.test(h.queue(f)[1])) && d) {
                        d.isAnimating = !1, d.rootPropertyValueCache = {};
                        var p = !1;
                        h.each(T.Lists.transforms3D, function (t, e) {
                            var n = /^scale/.test(e) ? 1 : 0, r = d.transformCache[e];
                            d.transformCache[e] !== i && new RegExp("^\\(" + n + "[^.]").test(r) && (p = !0, delete d.transformCache[e])
                        }), o.mobileHA && (p = !0, delete d.transformCache.translate3d), p && T.flushTransformCache(f), T.Values.removeClass(f, "velocity-animating")
                    }
                    if (!e && o.complete && !o.loop && u === c - 1) try {
                        o.complete.call(r, r)
                    } catch (t) {
                        setTimeout(function () {
                            throw t
                        }, 1)
                    }
                    a && !0 !== o.loop && a(r), d && !0 === o.loop && !e && (h.each(d.tweensContainer, function (t, e) {
                        if (/^rotate/.test(t) && (parseFloat(e.startValue) - parseFloat(e.endValue)) % 360 == 0) {
                            var n = e.startValue;
                            e.startValue = e.endValue, e.endValue = n
                        }
                        /^backgroundPosition/.test(t) && 100 === parseFloat(e.endValue) && "%" === e.unitType && (e.endValue = 0, e.startValue = 100)
                    }), E(f, "reverse", {loop: !0, delay: o.delay})), !1 !== o.queue && h.dequeue(f, o.queue)
                }
                E.State.calls[t] = !1;
                for (var m = 0, g = E.State.calls.length; m < g; m++) if (!1 !== E.State.calls[m]) {
                    l = !0;
                    break
                }
                !1 === l && (E.State.isTicking = !1, delete E.State.calls, E.State.calls = [])
            }

            var h, m = function () {
                if (n.documentMode) return n.documentMode;
                for (var t = 7; t > 4; t--) {
                    var e = n.createElement("div");
                    if (e.innerHTML = "\x3c!--[if IE " + t + "]><span></span><![endif]--\x3e", e.getElementsByTagName("span").length) return e = null, t
                }
                return i
            }(), g = function () {
                var t = 0;
                return e.webkitRequestAnimationFrame || e.mozRequestAnimationFrame || function (e) {
                    var n, i = (new Date).getTime();
                    return n = Math.max(0, 16 - (i - t)), t = i + n, setTimeout(function () {
                        e(i + n)
                    }, n)
                }
            }(), v = function () {
                var t = e.performance || {};
                if ("function" != typeof t.now) {
                    var n = t.timing && t.timing.navigationStart ? t.timing.navigationStart : (new Date).getTime();
                    t.now = function () {
                        return (new Date).getTime() - n
                    }
                }
                return t
            }(), y = function () {
                var t = Array.prototype.slice;
                try {
                    return t.call(n.documentElement), t
                } catch (e) {
                    return function (e, n) {
                        var i = this.length;
                        if ("number" != typeof e && (e = 0), "number" != typeof n && (n = i), this.slice) return t.call(this, e, n);
                        var r, o = [], s = e >= 0 ? e : Math.max(0, i + e), a = n < 0 ? i + n : Math.min(n, i),
                            l = a - s;
                        if (l > 0) if (o = new Array(l), this.charAt) for (r = 0; r < l; r++) o[r] = this.charAt(s + r); else for (r = 0; r < l; r++) o[r] = this[s + r];
                        return o
                    }
                }
            }(), b = function () {
                return Array.prototype.includes ? function (t, e) {
                    return t.includes(e)
                } : Array.prototype.indexOf ? function (t, e) {
                    return t.indexOf(e) >= 0
                } : function (t, e) {
                    for (var n = 0; n < t.length; n++) if (t[n] === e) return !0;
                    return !1
                }
            }, _ = {
                isNumber: function (t) {
                    return "number" == typeof t
                }, isString: function (t) {
                    return "string" == typeof t
                }, isArray: Array.isArray || function (t) {
                    return "[object Array]" === Object.prototype.toString.call(t)
                }, isFunction: function (t) {
                    return "[object Function]" === Object.prototype.toString.call(t)
                }, isNode: function (t) {
                    return t && t.nodeType
                }, isWrapped: function (t) {
                    return t && t !== e && _.isNumber(t.length) && !_.isString(t) && !_.isFunction(t) && !_.isNode(t) && (0 === t.length || _.isNode(t[0]))
                }, isSVG: function (t) {
                    return e.SVGElement && t instanceof e.SVGElement
                }, isEmptyObject: function (t) {
                    for (var e in t) if (t.hasOwnProperty(e)) return !1;
                    return !0
                }
            }, x = !1;
            if (t.fn && t.fn.jquery ? (h = t, x = !0) : h = e.Velocity.Utilities, m <= 8 && !x) throw new Error("Velocity: IE8 and below require jQuery to be loaded before Velocity.");
            if (m <= 7) return void(jQuery.fn.velocity = jQuery.fn.animate);
            var w = 400, S = "swing", E = {
                State: {
                    isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
                    isAndroid: /Android/i.test(navigator.userAgent),
                    isGingerbread: /Android 2\.3\.[3-7]/i.test(navigator.userAgent),
                    isChrome: e.chrome,
                    isFirefox: /Firefox/i.test(navigator.userAgent),
                    prefixElement: n.createElement("div"),
                    prefixMatches: {},
                    scrollAnchor: null,
                    scrollPropertyLeft: null,
                    scrollPropertyTop: null,
                    isTicking: !1,
                    calls: [],
                    delayedElements: {count: 0}
                },
                CSS: {},
                Utilities: h,
                Redirects: {},
                Easings: {},
                Promise: e.Promise,
                defaults: {
                    queue: "",
                    duration: w,
                    easing: S,
                    begin: i,
                    complete: i,
                    progress: i,
                    display: i,
                    visibility: i,
                    loop: !1,
                    delay: !1,
                    mobileHA: !0,
                    _cacheValues: !0,
                    promiseRejectEmpty: !0
                },
                init: function (t) {
                    h.data(t, "velocity", {
                        isSVG: _.isSVG(t),
                        isAnimating: !1,
                        computedStyle: null,
                        tweensContainer: null,
                        rootPropertyValueCache: {},
                        transformCache: {}
                    })
                },
                hook: null,
                mock: !1,
                version: {major: 1, minor: 5, patch: 0},
                debug: !1,
                timestamp: !0,
                pauseAll: function (t) {
                    var e = (new Date).getTime();
                    h.each(E.State.calls, function (e, n) {
                        if (n) {
                            if (t !== i && (n[2].queue !== t || !1 === n[2].queue)) return !0;
                            n[5] = {resume: !1}
                        }
                    }), h.each(E.State.delayedElements, function (t, n) {
                        n && a(n, e)
                    })
                },
                resumeAll: function (t) {
                    var e = (new Date).getTime();
                    h.each(E.State.calls, function (e, n) {
                        if (n) {
                            if (t !== i && (n[2].queue !== t || !1 === n[2].queue)) return !0;
                            n[5] && (n[5].resume = !0)
                        }
                    }), h.each(E.State.delayedElements, function (t, n) {
                        n && l(n, e)
                    })
                }
            };
            e.pageYOffset !== i ? (E.State.scrollAnchor = e, E.State.scrollPropertyLeft = "pageXOffset", E.State.scrollPropertyTop = "pageYOffset") : (E.State.scrollAnchor = n.documentElement || n.body.parentNode || n.body, E.State.scrollPropertyLeft = "scrollLeft", E.State.scrollPropertyTop = "scrollTop");
            var C = function () {
                function t(t) {
                    return -t.tension * t.x - t.friction * t.v
                }

                function e(e, n, i) {
                    var r = {x: e.x + i.dx * n, v: e.v + i.dv * n, tension: e.tension, friction: e.friction};
                    return {dx: r.v, dv: t(r)}
                }

                function n(n, i) {
                    var r = {dx: n.v, dv: t(n)}, o = e(n, .5 * i, r), s = e(n, .5 * i, o), a = e(n, i, s),
                        l = 1 / 6 * (r.dx + 2 * (o.dx + s.dx) + a.dx), u = 1 / 6 * (r.dv + 2 * (o.dv + s.dv) + a.dv);
                    return n.x = n.x + l * i, n.v = n.v + u * i, n
                }

                return function t(e, i, r) {
                    var o, s, a, l = {x: -1, v: 0, tension: null, friction: null}, u = [0], c = 0;
                    for (e = parseFloat(e) || 500, i = parseFloat(i) || 20, r = r || null, l.tension = e, l.friction = i, o = null !== r, o ? (c = t(e, i), s = c / r * .016) : s = .016; ;) if (a = n(a || l, s), u.push(1 + a.x), c += 16, !(Math.abs(a.x) > 1e-4 && Math.abs(a.v) > 1e-4)) break;
                    return o ? function (t) {
                        return u[t * (u.length - 1) | 0]
                    } : c
                }
            }();
            E.Easings = {
                linear: function (t) {
                    return t
                }, swing: function (t) {
                    return .5 - Math.cos(t * Math.PI) / 2
                }, spring: function (t) {
                    return 1 - Math.cos(4.5 * t * Math.PI) * Math.exp(6 * -t)
                }
            }, h.each([["ease", [.25, .1, .25, 1]], ["ease-in", [.42, 0, 1, 1]], ["ease-out", [0, 0, .58, 1]], ["ease-in-out", [.42, 0, .58, 1]], ["easeInSine", [.47, 0, .745, .715]], ["easeOutSine", [.39, .575, .565, 1]], ["easeInOutSine", [.445, .05, .55, .95]], ["easeInQuad", [.55, .085, .68, .53]], ["easeOutQuad", [.25, .46, .45, .94]], ["easeInOutQuad", [.455, .03, .515, .955]], ["easeInCubic", [.55, .055, .675, .19]], ["easeOutCubic", [.215, .61, .355, 1]], ["easeInOutCubic", [.645, .045, .355, 1]], ["easeInQuart", [.895, .03, .685, .22]], ["easeOutQuart", [.165, .84, .44, 1]], ["easeInOutQuart", [.77, 0, .175, 1]], ["easeInQuint", [.755, .05, .855, .06]], ["easeOutQuint", [.23, 1, .32, 1]], ["easeInOutQuint", [.86, 0, .07, 1]], ["easeInExpo", [.95, .05, .795, .035]], ["easeOutExpo", [.19, 1, .22, 1]], ["easeInOutExpo", [1, 0, 0, 1]], ["easeInCirc", [.6, .04, .98, .335]], ["easeOutCirc", [.075, .82, .165, 1]], ["easeInOutCirc", [.785, .135, .15, .86]]], function (t, e) {
                E.Easings[e[0]] = c.apply(null, e[1])
            });
            var T = E.CSS = {
                RegEx: {
                    isHex: /^#([A-f\d]{3}){1,2}$/i,
                    valueUnwrap: /^[A-z]+\((.*)\)$/i,
                    wrappedValueAlreadyExtracted: /[0-9.]+ [0-9.]+ [0-9.]+( [0-9.]+)?/,
                    valueSplit: /([A-z]+\(.+\))|(([A-z0-9#-.]+?)(?=\s|$))/gi
                },
                Lists: {
                    colors: ["fill", "stroke", "stopColor", "color", "backgroundColor", "borderColor", "borderTopColor", "borderRightColor", "borderBottomColor", "borderLeftColor", "outlineColor"],
                    transformsBase: ["translateX", "translateY", "scale", "scaleX", "scaleY", "skewX", "skewY", "rotateZ"],
                    transforms3D: ["transformPerspective", "translateZ", "scaleZ", "rotateX", "rotateY"],
                    units: ["%", "em", "ex", "ch", "rem", "vw", "vh", "vmin", "vmax", "cm", "mm", "Q", "in", "pc", "pt", "px", "deg", "grad", "rad", "turn", "s", "ms"],
                    colorNames: {
                        aliceblue: "240,248,255",
                        antiquewhite: "250,235,215",
                        aquamarine: "127,255,212",
                        aqua: "0,255,255",
                        azure: "240,255,255",
                        beige: "245,245,220",
                        bisque: "255,228,196",
                        black: "0,0,0",
                        blanchedalmond: "255,235,205",
                        blueviolet: "138,43,226",
                        blue: "0,0,255",
                        brown: "165,42,42",
                        burlywood: "222,184,135",
                        cadetblue: "95,158,160",
                        chartreuse: "127,255,0",
                        chocolate: "210,105,30",
                        coral: "255,127,80",
                        cornflowerblue: "100,149,237",
                        cornsilk: "255,248,220",
                        crimson: "220,20,60",
                        cyan: "0,255,255",
                        darkblue: "0,0,139",
                        darkcyan: "0,139,139",
                        darkgoldenrod: "184,134,11",
                        darkgray: "169,169,169",
                        darkgrey: "169,169,169",
                        darkgreen: "0,100,0",
                        darkkhaki: "189,183,107",
                        darkmagenta: "139,0,139",
                        darkolivegreen: "85,107,47",
                        darkorange: "255,140,0",
                        darkorchid: "153,50,204",
                        darkred: "139,0,0",
                        darksalmon: "233,150,122",
                        darkseagreen: "143,188,143",
                        darkslateblue: "72,61,139",
                        darkslategray: "47,79,79",
                        darkturquoise: "0,206,209",
                        darkviolet: "148,0,211",
                        deeppink: "255,20,147",
                        deepskyblue: "0,191,255",
                        dimgray: "105,105,105",
                        dimgrey: "105,105,105",
                        dodgerblue: "30,144,255",
                        firebrick: "178,34,34",
                        floralwhite: "255,250,240",
                        forestgreen: "34,139,34",
                        fuchsia: "255,0,255",
                        gainsboro: "220,220,220",
                        ghostwhite: "248,248,255",
                        gold: "255,215,0",
                        goldenrod: "218,165,32",
                        gray: "128,128,128",
                        grey: "128,128,128",
                        greenyellow: "173,255,47",
                        green: "0,128,0",
                        honeydew: "240,255,240",
                        hotpink: "255,105,180",
                        indianred: "205,92,92",
                        indigo: "75,0,130",
                        ivory: "255,255,240",
                        khaki: "240,230,140",
                        lavenderblush: "255,240,245",
                        lavender: "230,230,250",
                        lawngreen: "124,252,0",
                        lemonchiffon: "255,250,205",
                        lightblue: "173,216,230",
                        lightcoral: "240,128,128",
                        lightcyan: "224,255,255",
                        lightgoldenrodyellow: "250,250,210",
                        lightgray: "211,211,211",
                        lightgrey: "211,211,211",
                        lightgreen: "144,238,144",
                        lightpink: "255,182,193",
                        lightsalmon: "255,160,122",
                        lightseagreen: "32,178,170",
                        lightskyblue: "135,206,250",
                        lightslategray: "119,136,153",
                        lightsteelblue: "176,196,222",
                        lightyellow: "255,255,224",
                        limegreen: "50,205,50",
                        lime: "0,255,0",
                        linen: "250,240,230",
                        magenta: "255,0,255",
                        maroon: "128,0,0",
                        mediumaquamarine: "102,205,170",
                        mediumblue: "0,0,205",
                        mediumorchid: "186,85,211",
                        mediumpurple: "147,112,219",
                        mediumseagreen: "60,179,113",
                        mediumslateblue: "123,104,238",
                        mediumspringgreen: "0,250,154",
                        mediumturquoise: "72,209,204",
                        mediumvioletred: "199,21,133",
                        midnightblue: "25,25,112",
                        mintcream: "245,255,250",
                        mistyrose: "255,228,225",
                        moccasin: "255,228,181",
                        navajowhite: "255,222,173",
                        navy: "0,0,128",
                        oldlace: "253,245,230",
                        olivedrab: "107,142,35",
                        olive: "128,128,0",
                        orangered: "255,69,0",
                        orange: "255,165,0",
                        orchid: "218,112,214",
                        palegoldenrod: "238,232,170",
                        palegreen: "152,251,152",
                        paleturquoise: "175,238,238",
                        palevioletred: "219,112,147",
                        papayawhip: "255,239,213",
                        peachpuff: "255,218,185",
                        peru: "205,133,63",
                        pink: "255,192,203",
                        plum: "221,160,221",
                        powderblue: "176,224,230",
                        purple: "128,0,128",
                        red: "255,0,0",
                        rosybrown: "188,143,143",
                        royalblue: "65,105,225",
                        saddlebrown: "139,69,19",
                        salmon: "250,128,114",
                        sandybrown: "244,164,96",
                        seagreen: "46,139,87",
                        seashell: "255,245,238",
                        sienna: "160,82,45",
                        silver: "192,192,192",
                        skyblue: "135,206,235",
                        slateblue: "106,90,205",
                        slategray: "112,128,144",
                        snow: "255,250,250",
                        springgreen: "0,255,127",
                        steelblue: "70,130,180",
                        tan: "210,180,140",
                        teal: "0,128,128",
                        thistle: "216,191,216",
                        tomato: "255,99,71",
                        turquoise: "64,224,208",
                        violet: "238,130,238",
                        wheat: "245,222,179",
                        whitesmoke: "245,245,245",
                        white: "255,255,255",
                        yellowgreen: "154,205,50",
                        yellow: "255,255,0"
                    }
                },
                Hooks: {
                    templates: {
                        textShadow: ["Color X Y Blur", "black 0px 0px 0px"],
                        boxShadow: ["Color X Y Blur Spread", "black 0px 0px 0px 0px"],
                        clip: ["Top Right Bottom Left", "0px 0px 0px 0px"],
                        backgroundPosition: ["X Y", "0% 0%"],
                        transformOrigin: ["X Y Z", "50% 50% 0px"],
                        perspectiveOrigin: ["X Y", "50% 50%"]
                    }, registered: {}, register: function () {
                        for (var t = 0; t < T.Lists.colors.length; t++) {
                            var e = "color" === T.Lists.colors[t] ? "0 0 0 1" : "255 255 255 1";
                            T.Hooks.templates[T.Lists.colors[t]] = ["Red Green Blue Alpha", e]
                        }
                        var n, i, r;
                        if (m) for (n in T.Hooks.templates) if (T.Hooks.templates.hasOwnProperty(n)) {
                            i = T.Hooks.templates[n], r = i[0].split(" ");
                            var o = i[1].match(T.RegEx.valueSplit);
                            "Color" === r[0] && (r.push(r.shift()), o.push(o.shift()), T.Hooks.templates[n] = [r.join(" "), o.join(" ")])
                        }
                        for (n in T.Hooks.templates) if (T.Hooks.templates.hasOwnProperty(n)) {
                            i = T.Hooks.templates[n], r = i[0].split(" ");
                            for (var s in r) if (r.hasOwnProperty(s)) {
                                var a = n + r[s], l = s;
                                T.Hooks.registered[a] = [n, l]
                            }
                        }
                    }, getRoot: function (t) {
                        var e = T.Hooks.registered[t];
                        return e ? e[0] : t
                    }, getUnit: function (t, e) {
                        var n = (t.substr(e || 0, 5).match(/^[a-z%]+/) || [])[0] || "";
                        return n && b(T.Lists.units, n) ? n : ""
                    }, fixColors: function (t) {
                        return t.replace(/(rgba?\(\s*)?(\b[a-z]+\b)/g, function (t, e, n) {
                            return T.Lists.colorNames.hasOwnProperty(n) ? (e || "rgba(") + T.Lists.colorNames[n] + (e ? "" : ",1)") : e + n
                        })
                    }, cleanRootPropertyValue: function (t, e) {
                        return T.RegEx.valueUnwrap.test(e) && (e = e.match(T.RegEx.valueUnwrap)[1]), T.Values.isCSSNullValue(e) && (e = T.Hooks.templates[t][1]), e
                    }, extractValue: function (t, e) {
                        var n = T.Hooks.registered[t];
                        if (n) {
                            var i = n[0], r = n[1];
                            return e = T.Hooks.cleanRootPropertyValue(i, e), e.toString().match(T.RegEx.valueSplit)[r]
                        }
                        return e
                    }, injectValue: function (t, e, n) {
                        var i = T.Hooks.registered[t];
                        if (i) {
                            var r, o = i[0], s = i[1];
                            return n = T.Hooks.cleanRootPropertyValue(o, n), r = n.toString().match(T.RegEx.valueSplit), r[s] = e, r.join(" ")
                        }
                        return n
                    }
                },
                Normalizations: {
                    registered: {
                        clip: function (t, e, n) {
                            switch (t) {
                                case"name":
                                    return "clip";
                                case"extract":
                                    var i;
                                    return T.RegEx.wrappedValueAlreadyExtracted.test(n) ? i = n : (i = n.toString().match(T.RegEx.valueUnwrap), i = i ? i[1].replace(/,(\s+)?/g, " ") : n), i;
                                case"inject":
                                    return "rect(" + n + ")"
                            }
                        }, blur: function (t, e, n) {
                            switch (t) {
                                case"name":
                                    return E.State.isFirefox ? "filter" : "-webkit-filter";
                                case"extract":
                                    var i = parseFloat(n);
                                    if (!i && 0 !== i) {
                                        var r = n.toString().match(/blur\(([0-9]+[A-z]+)\)/i);
                                        i = r ? r[1] : 0
                                    }
                                    return i;
                                case"inject":
                                    return parseFloat(n) ? "blur(" + n + ")" : "none"
                            }
                        }, opacity: function (t, e, n) {
                            if (m <= 8) switch (t) {
                                case"name":
                                    return "filter";
                                case"extract":
                                    var i = n.toString().match(/alpha\(opacity=(.*)\)/i);
                                    return n = i ? i[1] / 100 : 1;
                                case"inject":
                                    return e.style.zoom = 1, parseFloat(n) >= 1 ? "" : "alpha(opacity=" + parseInt(100 * parseFloat(n), 10) + ")"
                            } else switch (t) {
                                case"name":
                                    return "opacity";
                                case"extract":
                                case"inject":
                                    return n
                            }
                        }
                    }, register: function () {
                        function t(t, e, n) {
                            if ("border-box" === T.getPropertyValue(e, "boxSizing").toString().toLowerCase() === (n || !1)) {
                                var i, r, o = 0, s = "width" === t ? ["Left", "Right"] : ["Top", "Bottom"],
                                    a = ["padding" + s[0], "padding" + s[1], "border" + s[0] + "Width", "border" + s[1] + "Width"];
                                for (i = 0; i < a.length; i++) r = parseFloat(T.getPropertyValue(e, a[i])), isNaN(r) || (o += r);
                                return n ? -o : o
                            }
                            return 0
                        }

                        function e(e, n) {
                            return function (i, r, o) {
                                switch (i) {
                                    case"name":
                                        return e;
                                    case"extract":
                                        return parseFloat(o) + t(e, r, n);
                                    case"inject":
                                        return parseFloat(o) - t(e, r, n) + "px"
                                }
                            }
                        }

                        m && !(m > 9) || E.State.isGingerbread || (T.Lists.transformsBase = T.Lists.transformsBase.concat(T.Lists.transforms3D));
                        for (var n = 0; n < T.Lists.transformsBase.length; n++) !function () {
                            var t = T.Lists.transformsBase[n];
                            T.Normalizations.registered[t] = function (e, n, r) {
                                switch (e) {
                                    case"name":
                                        return "transform";
                                    case"extract":
                                        return s(n) === i || s(n).transformCache[t] === i ? /^scale/i.test(t) ? 1 : 0 : s(n).transformCache[t].replace(/[()]/g, "");
                                    case"inject":
                                        var o = !1;
                                        switch (t.substr(0, t.length - 1)) {
                                            case"translate":
                                                o = !/(%|px|em|rem|vw|vh|\d)$/i.test(r);
                                                break;
                                            case"scal":
                                            case"scale":
                                                E.State.isAndroid && s(n).transformCache[t] === i && r < 1 && (r = 1), o = !/(\d)$/i.test(r);
                                                break;
                                            case"skew":
                                            case"rotate":
                                                o = !/(deg|\d)$/i.test(r)
                                        }
                                        return o || (s(n).transformCache[t] = "(" + r + ")"), s(n).transformCache[t]
                                }
                            }
                        }();
                        for (var r = 0; r < T.Lists.colors.length; r++) !function () {
                            var t = T.Lists.colors[r];
                            T.Normalizations.registered[t] = function (e, n, r) {
                                switch (e) {
                                    case"name":
                                        return t;
                                    case"extract":
                                        var o;
                                        if (T.RegEx.wrappedValueAlreadyExtracted.test(r)) o = r; else {
                                            var s, a = {
                                                black: "rgb(0, 0, 0)",
                                                blue: "rgb(0, 0, 255)",
                                                gray: "rgb(128, 128, 128)",
                                                green: "rgb(0, 128, 0)",
                                                red: "rgb(255, 0, 0)",
                                                white: "rgb(255, 255, 255)"
                                            };
                                            /^[A-z]+$/i.test(r) ? s = a[r] !== i ? a[r] : a.black : T.RegEx.isHex.test(r) ? s = "rgb(" + T.Values.hexToRgb(r).join(" ") + ")" : /^rgba?\(/i.test(r) || (s = a.black), o = (s || r).toString().match(T.RegEx.valueUnwrap)[1].replace(/,(\s+)?/g, " ")
                                        }
                                        return (!m || m > 8) && 3 === o.split(" ").length && (o += " 1"), o;
                                    case"inject":
                                        return /^rgb/.test(r) ? r : (m <= 8 ? 4 === r.split(" ").length && (r = r.split(/\s+/).slice(0, 3).join(" ")) : 3 === r.split(" ").length && (r += " 1"), (m <= 8 ? "rgb" : "rgba") + "(" + r.replace(/\s+/g, ",").replace(/\.(\d)+(?=,)/g, "") + ")")
                                }
                            }
                        }();
                        T.Normalizations.registered.innerWidth = e("width", !0), T.Normalizations.registered.innerHeight = e("height", !0), T.Normalizations.registered.outerWidth = e("width"), T.Normalizations.registered.outerHeight = e("height")
                    }
                },
                Names: {
                    camelCase: function (t) {
                        return t.replace(/-(\w)/g, function (t, e) {
                            return e.toUpperCase()
                        })
                    }, SVGAttribute: function (t) {
                        var e = "width|height|x|y|cx|cy|r|rx|ry|x1|x2|y1|y2";
                        return (m || E.State.isAndroid && !E.State.isChrome) && (e += "|transform"), new RegExp("^(" + e + ")$", "i").test(t)
                    }, prefixCheck: function (t) {
                        if (E.State.prefixMatches[t]) return [E.State.prefixMatches[t], !0];
                        for (var e = ["", "Webkit", "Moz", "ms", "O"], n = 0, i = e.length; n < i; n++) {
                            var r;
                            if (r = 0 === n ? t : e[n] + t.replace(/^\w/, function (t) {
                                    return t.toUpperCase()
                                }), _.isString(E.State.prefixElement.style[r])) return E.State.prefixMatches[t] = r, [r, !0]
                        }
                        return [t, !1]
                    }
                },
                Values: {
                    hexToRgb: function (t) {
                        var e, n = /^#?([a-f\d])([a-f\d])([a-f\d])$/i, i = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i;
                        return t = t.replace(n, function (t, e, n, i) {
                            return e + e + n + n + i + i
                        }), e = i.exec(t), e ? [parseInt(e[1], 16), parseInt(e[2], 16), parseInt(e[3], 16)] : [0, 0, 0]
                    }, isCSSNullValue: function (t) {
                        return !t || /^(none|auto|transparent|(rgba\(0, ?0, ?0, ?0\)))$/i.test(t)
                    }, getUnitType: function (t) {
                        return /^(rotate|skew)/i.test(t) ? "deg" : /(^(scale|scaleX|scaleY|scaleZ|alpha|flexGrow|flexHeight|zIndex|fontWeight)$)|((opacity|red|green|blue|alpha)$)/i.test(t) ? "" : "px"
                    }, getDisplayType: function (t) {
                        var e = t && t.tagName.toString().toLowerCase();
                        return /^(b|big|i|small|tt|abbr|acronym|cite|code|dfn|em|kbd|strong|samp|var|a|bdo|br|img|map|object|q|script|span|sub|sup|button|input|label|select|textarea)$/i.test(e) ? "inline" : /^(li)$/i.test(e) ? "list-item" : /^(tr)$/i.test(e) ? "table-row" : /^(table)$/i.test(e) ? "table" : /^(tbody)$/i.test(e) ? "table-row-group" : "block"
                    }, addClass: function (t, e) {
                        if (t) if (t.classList) t.classList.add(e); else if (_.isString(t.className)) t.className += (t.className.length ? " " : "") + e; else {
                            var n = t.getAttribute(m <= 7 ? "className" : "class") || "";
                            t.setAttribute("class", n + (n ? " " : "") + e)
                        }
                    }, removeClass: function (t, e) {
                        if (t) if (t.classList) t.classList.remove(e); else if (_.isString(t.className)) t.className = t.className.toString().replace(new RegExp("(^|\\s)" + e.split(" ").join("|") + "(\\s|$)", "gi"), " "); else {
                            var n = t.getAttribute(m <= 7 ? "className" : "class") || "";
                            t.setAttribute("class", n.replace(new RegExp("(^|s)" + e.split(" ").join("|") + "(s|$)", "gi"), " "))
                        }
                    }
                },
                getPropertyValue: function (t, n, r, o) {
                    function a(t, n) {
                        var r = 0;
                        if (m <= 8) r = h.css(t, n); else {
                            var l = !1;
                            /^(width|height)$/.test(n) && 0 === T.getPropertyValue(t, "display") && (l = !0, T.setPropertyValue(t, "display", T.Values.getDisplayType(t)));
                            var u = function () {
                                l && T.setPropertyValue(t, "display", "none")
                            };
                            if (!o) {
                                if ("height" === n && "border-box" !== T.getPropertyValue(t, "boxSizing").toString().toLowerCase()) {
                                    var c = t.offsetHeight - (parseFloat(T.getPropertyValue(t, "borderTopWidth")) || 0) - (parseFloat(T.getPropertyValue(t, "borderBottomWidth")) || 0) - (parseFloat(T.getPropertyValue(t, "paddingTop")) || 0) - (parseFloat(T.getPropertyValue(t, "paddingBottom")) || 0);
                                    return u(), c
                                }
                                if ("width" === n && "border-box" !== T.getPropertyValue(t, "boxSizing").toString().toLowerCase()) {
                                    var f = t.offsetWidth - (parseFloat(T.getPropertyValue(t, "borderLeftWidth")) || 0) - (parseFloat(T.getPropertyValue(t, "borderRightWidth")) || 0) - (parseFloat(T.getPropertyValue(t, "paddingLeft")) || 0) - (parseFloat(T.getPropertyValue(t, "paddingRight")) || 0);
                                    return u(), f
                                }
                            }
                            var d;
                            d = s(t) === i ? e.getComputedStyle(t, null) : s(t).computedStyle ? s(t).computedStyle : s(t).computedStyle = e.getComputedStyle(t, null), "borderColor" === n && (n = "borderTopColor"), r = 9 === m && "filter" === n ? d.getPropertyValue(n) : d[n], "" !== r && null !== r || (r = t.style[n]), u()
                        }
                        if ("auto" === r && /^(top|right|bottom|left)$/i.test(n)) {
                            var p = a(t, "position");
                            ("fixed" === p || "absolute" === p && /top|left/i.test(n)) && (r = h(t).position()[n] + "px")
                        }
                        return r
                    }

                    var l;
                    if (T.Hooks.registered[n]) {
                        var u = n, c = T.Hooks.getRoot(u);
                        r === i && (r = T.getPropertyValue(t, T.Names.prefixCheck(c)[0])), T.Normalizations.registered[c] && (r = T.Normalizations.registered[c]("extract", t, r)), l = T.Hooks.extractValue(u, r)
                    } else if (T.Normalizations.registered[n]) {
                        var f, d;
                        f = T.Normalizations.registered[n]("name", t), "transform" !== f && (d = a(t, T.Names.prefixCheck(f)[0]), T.Values.isCSSNullValue(d) && T.Hooks.templates[n] && (d = T.Hooks.templates[n][1])), l = T.Normalizations.registered[n]("extract", t, d)
                    }
                    if (!/^[\d-]/.test(l)) {
                        var p = s(t);
                        if (p && p.isSVG && T.Names.SVGAttribute(n)) if (/^(height|width)$/i.test(n)) try {
                            l = t.getBBox()[n]
                        } catch (t) {
                            l = 0
                        } else l = t.getAttribute(n); else l = a(t, T.Names.prefixCheck(n)[0])
                    }
                    return T.Values.isCSSNullValue(l) && (l = 0), E.debug, l
                },
                setPropertyValue: function (t, n, i, r, o) {
                    var a = n;
                    if ("scroll" === n) o.container ? o.container["scroll" + o.direction] = i : "Left" === o.direction ? e.scrollTo(i, o.alternateValue) : e.scrollTo(o.alternateValue, i); else if (T.Normalizations.registered[n] && "transform" === T.Normalizations.registered[n]("name", t)) T.Normalizations.registered[n]("inject", t, i), a = "transform", i = s(t).transformCache[n]; else {
                        if (T.Hooks.registered[n]) {
                            var l = n, u = T.Hooks.getRoot(n);
                            r = r || T.getPropertyValue(t, u), i = T.Hooks.injectValue(l, i, r), n = u
                        }
                        if (T.Normalizations.registered[n] && (i = T.Normalizations.registered[n]("inject", t, i), n = T.Normalizations.registered[n]("name", t)), a = T.Names.prefixCheck(n)[0], m <= 8) try {
                            t.style[a] = i
                        } catch (t) {
                            E.debug
                        } else {
                            var c = s(t);
                            c && c.isSVG && T.Names.SVGAttribute(n) ? t.setAttribute(n, i) : t.style[a] = i
                        }
                        E.debug
                    }
                    return [a, i]
                },
                flushTransformCache: function (t) {
                    var e = "", n = s(t);
                    if ((m || E.State.isAndroid && !E.State.isChrome) && n && n.isSVG) {
                        var i = function (e) {
                            return parseFloat(T.getPropertyValue(t, e))
                        }, r = {
                            translate: [i("translateX"), i("translateY")],
                            skewX: [i("skewX")],
                            skewY: [i("skewY")],
                            scale: 1 !== i("scale") ? [i("scale"), i("scale")] : [i("scaleX"), i("scaleY")],
                            rotate: [i("rotateZ"), 0, 0]
                        };
                        h.each(s(t).transformCache, function (t) {
                            /^translate/i.test(t) ? t = "translate" : /^scale/i.test(t) ? t = "scale" : /^rotate/i.test(t) && (t = "rotate"), r[t] && (e += t + "(" + r[t].join(" ") + ") ", delete r[t])
                        })
                    } else {
                        var o, a;
                        h.each(s(t).transformCache, function (n) {
                            if (o = s(t).transformCache[n], "transformPerspective" === n) return a = o, !0;
                            9 === m && "rotateZ" === n && (n = "rotate"), e += n + o + " "
                        }), a && (e = "perspective" + a + " " + e)
                    }
                    T.setPropertyValue(t, "transform", e)
                }
            };
            T.Hooks.register(), T.Normalizations.register(), E.hook = function (t, e, n) {
                var r;
                return t = o(t), h.each(t, function (t, o) {
                    if (s(o) === i && E.init(o), n === i) r === i && (r = T.getPropertyValue(o, e)); else {
                        var a = T.setPropertyValue(o, e, n);
                        "transform" === a[0] && E.CSS.flushTransformCache(o), r = a
                    }
                }), r
            };
            var A = function t() {
                function r() {
                    return m ? A.promise || null : g
                }

                function u(t, r) {
                    function o(o) {
                        var c, p;
                        if (l.begin && 0 === O) try {
                            l.begin.call(y, y)
                        } catch (t) {
                            setTimeout(function () {
                                throw t
                            }, 1)
                        }
                        if ("scroll" === N) {
                            var m, g, v, w = /^x$/i.test(l.axis) ? "Left" : "Top", C = parseFloat(l.offset) || 0;
                            l.container ? _.isWrapped(l.container) || _.isNode(l.container) ? (l.container = l.container[0] || l.container, m = l.container["scroll" + w], v = m + h(t).position()[w.toLowerCase()] + C) : l.container = null : (m = E.State.scrollAnchor[E.State["scrollProperty" + w]], g = E.State.scrollAnchor[E.State["scrollProperty" + ("Left" === w ? "Top" : "Left")]], v = h(t).offset()[w.toLowerCase()] + C), u = {
                                scroll: {
                                    rootPropertyValue: !1,
                                    startValue: m,
                                    currentValue: m,
                                    endValue: v,
                                    unitType: "",
                                    easing: l.easing,
                                    scrollData: {container: l.container, direction: w, alternateValue: g}
                                }, element: t
                            }, E.debug
                        } else if ("reverse" === N) {
                            if (!(c = s(t))) return;
                            if (!c.tweensContainer) return void h.dequeue(t, l.queue);
                            "none" === c.opts.display && (c.opts.display = "auto"), "hidden" === c.opts.visibility && (c.opts.visibility = "visible"), c.opts.loop = !1, c.opts.begin = null, c.opts.complete = null, S.easing || delete l.easing, S.duration || delete l.duration, l = h.extend({}, c.opts, l), p = h.extend(!0, {}, c ? c.tweensContainer : null);
                            for (var k in p) if (p.hasOwnProperty(k) && "element" !== k) {
                                var D = p[k].startValue;
                                p[k].startValue = p[k].currentValue = p[k].endValue, p[k].endValue = D, _.isEmptyObject(S) || (p[k].easing = l.easing), E.debug
                            }
                            u = p
                        } else if ("start" === N) {
                            c = s(t), c && c.tweensContainer && !0 === c.isAnimating && (p = c.tweensContainer);
                            var P = function (r, o) {
                                var s, f = T.Hooks.getRoot(r), d = !1, m = o[0], g = o[1], v = o[2];
                                if (!(c && c.isSVG || "tween" === f || !1 !== T.Names.prefixCheck(f)[1] || T.Normalizations.registered[f] !== i)) return void E.debug;
                                (l.display !== i && null !== l.display && "none" !== l.display || l.visibility !== i && "hidden" !== l.visibility) && /opacity|filter/.test(r) && !v && 0 !== m && (v = 0), l._cacheValues && p && p[r] ? (v === i && (v = p[r].endValue + p[r].unitType), d = c.rootPropertyValueCache[f]) : T.Hooks.registered[r] ? v === i ? (d = T.getPropertyValue(t, f), v = T.getPropertyValue(t, r, d)) : d = T.Hooks.templates[f][1] : v === i && (v = T.getPropertyValue(t, r));
                                var y, b, x, w = !1, S = function (t, e) {
                                    var n, i;
                                    return i = (e || "0").toString().toLowerCase().replace(/[%A-z]+$/, function (t) {
                                        return n = t, ""
                                    }), n || (n = T.Values.getUnitType(t)), [i, n]
                                };
                                if (v !== m && _.isString(v) && _.isString(m)) {
                                    s = "";
                                    var C = 0, A = 0, I = [], O = [], k = 0, D = 0, N = 0;
                                    for (v = T.Hooks.fixColors(v), m = T.Hooks.fixColors(m); C < v.length && A < m.length;) {
                                        var P = v[C], L = m[A];
                                        if (/[\d\.-]/.test(P) && /[\d\.-]/.test(L)) {
                                            for (var j = P, B = L, V = ".", R = "."; ++C < v.length;) {
                                                if ((P = v[C]) === V) V = ".."; else if (!/\d/.test(P)) break;
                                                j += P
                                            }
                                            for (; ++A < m.length;) {
                                                if ((L = m[A]) === R) R = ".."; else if (!/\d/.test(L)) break;
                                                B += L
                                            }
                                            var M = T.Hooks.getUnit(v, C), H = T.Hooks.getUnit(m, A);
                                            if (C += M.length, A += H.length, M === H) j === B ? s += j + M : (s += "{" + I.length + (D ? "!" : "") + "}" + M, I.push(parseFloat(j)), O.push(parseFloat(B))); else {
                                                var W = parseFloat(j), U = parseFloat(B);
                                                s += (k < 5 ? "calc" : "") + "(" + (W ? "{" + I.length + (D ? "!" : "") + "}" : "0") + M + " + " + (U ? "{" + (I.length + (W ? 1 : 0)) + (D ? "!" : "") + "}" : "0") + H + ")", W && (I.push(W), O.push(0)), U && (I.push(0), O.push(U))
                                            }
                                        } else {
                                            if (P !== L) {
                                                k = 0;
                                                break
                                            }
                                            s += P, C++, A++, 0 === k && "c" === P || 1 === k && "a" === P || 2 === k && "l" === P || 3 === k && "c" === P || k >= 4 && "(" === P ? k++ : (k && k < 5 || k >= 4 && ")" === P && --k < 5) && (k = 0), 0 === D && "r" === P || 1 === D && "g" === P || 2 === D && "b" === P || 3 === D && "a" === P || D >= 3 && "(" === P ? (3 === D && "a" === P && (N = 1), D++) : N && "," === P ? ++N > 3 && (D = N = 0) : (N && D < (N ? 5 : 4) || D >= (N ? 4 : 3) && ")" === P && --D < (N ? 5 : 4)) && (D = N = 0)
                                        }
                                    }
                                    C === v.length && A === m.length || (E.debug, s = i), s && (I.length ? (E.debug, v = I, m = O, b = x = "") : s = i)
                                }
                                s || (y = S(r, v), v = y[0], x = y[1], y = S(r, m), m = y[0].replace(/^([+-\/*])=/, function (t, e) {
                                    return w = e, ""
                                }), b = y[1], v = parseFloat(v) || 0, m = parseFloat(m) || 0, "%" === b && (/^(fontSize|lineHeight)$/.test(r) ? (m /= 100, b = "em") : /^scale/.test(r) ? (m /= 100, b = "") : /(Red|Green|Blue)$/i.test(r) && (m = m / 100 * 255, b = "")));
                                if (/[\/*]/.test(w)) b = x; else if (x !== b && 0 !== v) if (0 === m) b = x; else {
                                    a = a || function () {
                                        var i = {
                                                myParent: t.parentNode || n.body,
                                                position: T.getPropertyValue(t, "position"),
                                                fontSize: T.getPropertyValue(t, "fontSize")
                                            }, r = i.position === F.lastPosition && i.myParent === F.lastParent,
                                            o = i.fontSize === F.lastFontSize;
                                        F.lastParent = i.myParent, F.lastPosition = i.position, F.lastFontSize = i.fontSize;
                                        var s = {};
                                        if (o && r) s.emToPx = F.lastEmToPx, s.percentToPxWidth = F.lastPercentToPxWidth, s.percentToPxHeight = F.lastPercentToPxHeight; else {
                                            var a = c && c.isSVG ? n.createElementNS("http://www.w3.org/2000/svg", "rect") : n.createElement("div");
                                            E.init(a), i.myParent.appendChild(a), h.each(["overflow", "overflowX", "overflowY"], function (t, e) {
                                                E.CSS.setPropertyValue(a, e, "hidden")
                                            }), E.CSS.setPropertyValue(a, "position", i.position), E.CSS.setPropertyValue(a, "fontSize", i.fontSize), E.CSS.setPropertyValue(a, "boxSizing", "content-box"), h.each(["minWidth", "maxWidth", "width", "minHeight", "maxHeight", "height"], function (t, e) {
                                                E.CSS.setPropertyValue(a, e, "100%")
                                            }), E.CSS.setPropertyValue(a, "paddingLeft", "100em"), s.percentToPxWidth = F.lastPercentToPxWidth = (parseFloat(T.getPropertyValue(a, "width", null, !0)) || 1) / 100, s.percentToPxHeight = F.lastPercentToPxHeight = (parseFloat(T.getPropertyValue(a, "height", null, !0)) || 1) / 100, s.emToPx = F.lastEmToPx = (parseFloat(T.getPropertyValue(a, "paddingLeft")) || 1) / 100, i.myParent.removeChild(a)
                                        }
                                        return null === F.remToPx && (F.remToPx = parseFloat(T.getPropertyValue(n.body, "fontSize")) || 16), null === F.vwToPx && (F.vwToPx = parseFloat(e.innerWidth) / 100, F.vhToPx = parseFloat(e.innerHeight) / 100), s.remToPx = F.remToPx, s.vwToPx = F.vwToPx, s.vhToPx = F.vhToPx, E.debug, s
                                    }();
                                    var q = /margin|padding|left|right|width|text|word|letter/i.test(r) || /X$/.test(r) || "x" === r ? "x" : "y";
                                    switch (x) {
                                        case"%":
                                            v *= "x" === q ? a.percentToPxWidth : a.percentToPxHeight;
                                            break;
                                        case"px":
                                            break;
                                        default:
                                            v *= a[x + "ToPx"]
                                    }
                                    switch (b) {
                                        case"%":
                                            v *= 1 / ("x" === q ? a.percentToPxWidth : a.percentToPxHeight);
                                            break;
                                        case"px":
                                            break;
                                        default:
                                            v *= 1 / a[b + "ToPx"]
                                    }
                                }
                                switch (w) {
                                    case"+":
                                        m = v + m;
                                        break;
                                    case"-":
                                        m = v - m;
                                        break;
                                    case"*":
                                        m *= v;
                                        break;
                                    case"/":
                                        m = v / m
                                }
                                u[r] = {
                                    rootPropertyValue: d,
                                    startValue: v,
                                    currentValue: v,
                                    endValue: m,
                                    unitType: b,
                                    easing: g
                                }, s && (u[r].pattern = s), E.debug
                            };
                            for (var L in x) if (x.hasOwnProperty(L)) {
                                var j = T.Names.camelCase(L), B = function (e, n) {
                                    var i, o, s;
                                    return _.isFunction(e) && (e = e.call(t, r, I)), _.isArray(e) ? (i = e[0], !_.isArray(e[1]) && /^[\d-]/.test(e[1]) || _.isFunction(e[1]) || T.RegEx.isHex.test(e[1]) ? s = e[1] : _.isString(e[1]) && !T.RegEx.isHex.test(e[1]) && E.Easings[e[1]] || _.isArray(e[1]) ? (o = n ? e[1] : f(e[1], l.duration), s = e[2]) : s = e[1] || e[2]) : i = e, n || (o = o || l.easing), _.isFunction(i) && (i = i.call(t, r, I)), _.isFunction(s) && (s = s.call(t, r, I)), [i || 0, o, s]
                                }(x[L]);
                                if (b(T.Lists.colors, j)) {
                                    var V = B[0], M = B[1], H = B[2];
                                    if (T.RegEx.isHex.test(V)) {
                                        for (var W = ["Red", "Green", "Blue"], U = T.Values.hexToRgb(V), q = H ? T.Values.hexToRgb(H) : i, z = 0; z < W.length; z++) {
                                            var $ = [U[z]];
                                            M && $.push(M), q !== i && $.push(q[z]), P(j + W[z], $)
                                        }
                                        continue
                                    }
                                }
                                P(j, B)
                            }
                            u.element = t
                        }
                        u.element && (T.Values.addClass(t, "velocity-animating"), R.push(u), c = s(t), c && ("" === l.queue && (c.tweensContainer = u, c.opts = l), c.isAnimating = !0), O === I - 1 ? (E.State.calls.push([R, y, l, null, A.resolver, null, 0]), !1 === E.State.isTicking && (E.State.isTicking = !0, d())) : O++)
                    }

                    var a, l = h.extend({}, E.defaults, S), u = {};
                    switch (s(t) === i && E.init(t), parseFloat(l.delay) && !1 !== l.queue && h.queue(t, l.queue, function (e) {
                        E.velocityQueueEntryFlag = !0;
                        var n = E.State.delayedElements.count++;
                        E.State.delayedElements[n] = t;
                        var i = function (t) {
                            return function () {
                                E.State.delayedElements[t] = !1, e()
                            }
                        }(n);
                        s(t).delayBegin = (new Date).getTime(), s(t).delay = parseFloat(l.delay), s(t).delayTimer = {
                            setTimeout: setTimeout(e, parseFloat(l.delay)),
                            next: i
                        }
                    }), l.duration.toString().toLowerCase()) {
                        case"fast":
                            l.duration = 200;
                            break;
                        case"normal":
                            l.duration = w;
                            break;
                        case"slow":
                            l.duration = 600;
                            break;
                        default:
                            l.duration = parseFloat(l.duration) || 1
                    }
                    if (!1 !== E.mock && (!0 === E.mock ? l.duration = l.delay = 1 : (l.duration *= parseFloat(E.mock) || 1, l.delay *= parseFloat(E.mock) || 1)), l.easing = f(l.easing, l.duration), l.begin && !_.isFunction(l.begin) && (l.begin = null), l.progress && !_.isFunction(l.progress) && (l.progress = null), l.complete && !_.isFunction(l.complete) && (l.complete = null), l.display !== i && null !== l.display && (l.display = l.display.toString().toLowerCase(), "auto" === l.display && (l.display = E.CSS.Values.getDisplayType(t))), l.visibility !== i && null !== l.visibility && (l.visibility = l.visibility.toString().toLowerCase()), l.mobileHA = l.mobileHA && E.State.isMobile && !E.State.isGingerbread, !1 === l.queue) if (l.delay) {
                        var c = E.State.delayedElements.count++;
                        E.State.delayedElements[c] = t;
                        var p = function (t) {
                            return function () {
                                E.State.delayedElements[t] = !1, o()
                            }
                        }(c);
                        s(t).delayBegin = (new Date).getTime(), s(t).delay = parseFloat(l.delay), s(t).delayTimer = {
                            setTimeout: setTimeout(o, parseFloat(l.delay)),
                            next: p
                        }
                    } else o(); else h.queue(t, l.queue, function (t, e) {
                        if (!0 === e) return A.promise && A.resolver(y), !0;
                        E.velocityQueueEntryFlag = !0, o(t)
                    });
                    "" !== l.queue && "fx" !== l.queue || "inprogress" === h.queue(t)[0] || h.dequeue(t)
                }

                var c, m, g, v, y, x, S,
                    C = arguments[0] && (arguments[0].p || h.isPlainObject(arguments[0].properties) && !arguments[0].properties.names || _.isString(arguments[0].properties));
                _.isWrapped(this) ? (m = !1, v = 0, y = this, g = this) : (m = !0, v = 1, y = C ? arguments[0].elements || arguments[0].e : arguments[0]);
                var A = {promise: null, resolver: null, rejecter: null};
                if (m && E.Promise && (A.promise = new E.Promise(function (t, e) {
                        A.resolver = t, A.rejecter = e
                    })), C ? (x = arguments[0].properties || arguments[0].p, S = arguments[0].options || arguments[0].o) : (x = arguments[v], S = arguments[v + 1]), !(y = o(y))) return void(A.promise && (x && S && !1 === S.promiseRejectEmpty ? A.resolver() : A.rejecter()));
                var I = y.length, O = 0;
                if (!/^(stop|finish|finishAll|pause|resume)$/i.test(x) && !h.isPlainObject(S)) {
                    var k = v + 1;
                    S = {};
                    for (var D = k; D < arguments.length; D++) _.isArray(arguments[D]) || !/^(fast|normal|slow)$/i.test(arguments[D]) && !/^\d/.test(arguments[D]) ? _.isString(arguments[D]) || _.isArray(arguments[D]) ? S.easing = arguments[D] : _.isFunction(arguments[D]) && (S.complete = arguments[D]) : S.duration = arguments[D]
                }
                var N;
                switch (x) {
                    case"scroll":
                        N = "scroll";
                        break;
                    case"reverse":
                        N = "reverse";
                        break;
                    case"pause":
                        var P = (new Date).getTime();
                        return h.each(y, function (t, e) {
                            a(e, P)
                        }), h.each(E.State.calls, function (t, e) {
                            var n = !1;
                            e && h.each(e[1], function (t, r) {
                                var o = S === i ? "" : S;
                                return !0 !== o && e[2].queue !== o && (S !== i || !1 !== e[2].queue) || (h.each(y, function (t, i) {
                                    if (i === r) return e[5] = {resume: !1}, n = !0, !1
                                }), !n && void 0)
                            })
                        }), r();
                    case"resume":
                        return h.each(y, function (t, e) {
                            l(e, P)
                        }), h.each(E.State.calls, function (t, e) {
                            var n = !1;
                            e && h.each(e[1], function (t, r) {
                                var o = S === i ? "" : S;
                                return !0 !== o && e[2].queue !== o && (S !== i || !1 !== e[2].queue) || (!e[5] || (h.each(y, function (t, i) {
                                    if (i === r) return e[5].resume = !0, n = !0, !1
                                }), !n && void 0))
                            })
                        }), r();
                    case"finish":
                    case"finishAll":
                    case"stop":
                        h.each(y, function (t, e) {
                            s(e) && s(e).delayTimer && (clearTimeout(s(e).delayTimer.setTimeout), s(e).delayTimer.next && s(e).delayTimer.next(), delete s(e).delayTimer), "finishAll" !== x || !0 !== S && !_.isString(S) || (h.each(h.queue(e, _.isString(S) ? S : ""), function (t, e) {
                                _.isFunction(e) && e()
                            }), h.queue(e, _.isString(S) ? S : "", []))
                        });
                        var L = [];
                        return h.each(E.State.calls, function (t, e) {
                            e && h.each(e[1], function (n, r) {
                                var o = S === i ? "" : S;
                                if (!0 !== o && e[2].queue !== o && (S !== i || !1 !== e[2].queue)) return !0;
                                h.each(y, function (n, i) {
                                    if (i === r) if ((!0 === S || _.isString(S)) && (h.each(h.queue(i, _.isString(S) ? S : ""), function (t, e) {
                                            _.isFunction(e) && e(null, !0)
                                        }), h.queue(i, _.isString(S) ? S : "", [])), "stop" === x) {
                                        var a = s(i);
                                        a && a.tweensContainer && !1 !== o && h.each(a.tweensContainer, function (t, e) {
                                            e.endValue = e.currentValue
                                        }), L.push(t)
                                    } else "finish" !== x && "finishAll" !== x || (e[2].duration = 1)
                                })
                            })
                        }), "stop" === x && (h.each(L, function (t, e) {
                            p(e, !0)
                        }), A.promise && A.resolver(y)), r();
                    default:
                        if (!h.isPlainObject(x) || _.isEmptyObject(x)) {
                            if (_.isString(x) && E.Redirects[x]) {
                                c = h.extend({}, S);
                                var j = c.duration, B = c.delay || 0;
                                return !0 === c.backwards && (y = h.extend(!0, [], y).reverse()), h.each(y, function (t, e) {
                                    parseFloat(c.stagger) ? c.delay = B + parseFloat(c.stagger) * t : _.isFunction(c.stagger) && (c.delay = B + c.stagger.call(e, t, I)), c.drag && (c.duration = parseFloat(j) || (/^(callout|transition)/.test(x) ? 1e3 : w), c.duration = Math.max(c.duration * (c.backwards ? 1 - t / I : (t + 1) / I), .75 * c.duration, 200)), E.Redirects[x].call(e, e, c || {}, t, I, y, A.promise ? A : i)
                                }), r()
                            }
                            var V = "Velocity: First argument (" + x + ") was not a property map, a known action, or a registered redirect. Aborting.";
                            return A.promise ? A.rejecter(new Error(V)) : e.console, r()
                        }
                        N = "start"
                }
                var F = {
                    lastParent: null,
                    lastPosition: null,
                    lastFontSize: null,
                    lastPercentToPxWidth: null,
                    lastPercentToPxHeight: null,
                    lastEmToPx: null,
                    remToPx: null,
                    vwToPx: null,
                    vhToPx: null
                }, R = [];
                h.each(y, function (t, e) {
                    _.isNode(e) && u(e, t)
                }), c = h.extend({}, E.defaults, S), c.loop = parseInt(c.loop, 10);
                var M = 2 * c.loop - 1;
                if (c.loop) for (var H = 0; H < M; H++) {
                    var W = {delay: c.delay, progress: c.progress};
                    H === M - 1 && (W.display = c.display, W.visibility = c.visibility, W.complete = c.complete), t(y, "reverse", W)
                }
                return r()
            };
            E = h.extend(A, E), E.animate = A;
            var I = e.requestAnimationFrame || g;
            if (!E.State.isMobile && n.hidden !== i) {
                var O = function () {
                    n.hidden ? (I = function (t) {
                        return setTimeout(function () {
                            t(!0)
                        }, 16)
                    }, d()) : I = e.requestAnimationFrame || g
                };
                O(), n.addEventListener("visibilitychange", O)
            }
            return t.Velocity = E, t !== e && (t.fn.velocity = A, t.fn.velocity.defaults = E.defaults), h.each(["Down", "Up"], function (t, e) {
                E.Redirects["slide" + e] = function (t, n, r, o, s, a) {
                    var l = h.extend({}, n), u = l.begin, c = l.complete, f = {},
                        d = {height: "", marginTop: "", marginBottom: "", paddingTop: "", paddingBottom: ""};
                    l.display === i && (l.display = "Down" === e ? "inline" === E.CSS.Values.getDisplayType(t) ? "inline-block" : "block" : "none"), l.begin = function () {
                        0 === r && u && u.call(s, s);
                        for (var n in d) if (d.hasOwnProperty(n)) {
                            f[n] = t.style[n];
                            var i = T.getPropertyValue(t, n);
                            d[n] = "Down" === e ? [i, 0] : [0, i]
                        }
                        f.overflow = t.style.overflow, t.style.overflow = "hidden"
                    }, l.complete = function () {
                        for (var e in f) f.hasOwnProperty(e) && (t.style[e] = f[e]);
                        r === o - 1 && (c && c.call(s, s), a && a.resolver(s))
                    }, E(t, d, l)
                }
            }), h.each(["In", "Out"], function (t, e) {
                E.Redirects["fade" + e] = function (t, n, r, o, s, a) {
                    var l = h.extend({}, n), u = l.complete, c = {opacity: "In" === e ? 1 : 0};
                    0 !== r && (l.begin = null), l.complete = r !== o - 1 ? null : function () {
                        u && u.call(s, s), a && a.resolver(s)
                    }, l.display === i && (l.display = "In" === e ? "auto" : "none"), E(this, c, l)
                }
            }), E
        }(window.jQuery || window.Zepto || window, window, window ? window.document : void 0)
    })
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    n(25), n(20), n(22), n(19), n(18), n(8), n(13), n(16), n(17), n(7);
    var r = n(2), o = i(r), s = n(10), a = i(s), l = n(3), u = i(l), c = n(11), f = i(c), d = n(12), p = i(d), h = n(1),
        m = i(h), g = n(21), v = i(g);
    n(14), n(15), n(9);
    for (var y in v.default.prototype) m.default[y] = v.default.prototype[y];
    $(document).ready(function () {
        var t = $(".js-dropdown"), e = new a.default, n = $('.js-top-menu ul[data-depth="0"]'), i = new o.default(t),
            r = new p.default(n), s = new u.default, l = new f.default;
        i.init(), e.init(), r.init(), s.init(), l.init()
    })
}, function (t, e) {
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    function r() {
        s.default.each((0, s.default)(u), function (t, e) {
            (0, s.default)(e).TouchSpin({
                verticalbuttons: !0,
                verticalupclass: "material-icons touchspin-up",
                verticaldownclass: "material-icons touchspin-down",
                buttondown_class: "btn btn-touchspin js-touchspin js-increase-product-quantity",
                buttonup_class: "btn btn-touchspin js-touchspin js-decrease-product-quantity",
                min: parseInt((0, s.default)(e).attr("min"), 10),
                max: 1e6
            })
        })
    }

    var o = n(0), s = i(o), a = n(1), l = i(a);
    l.default.cart = l.default.cart || {}, l.default.cart.active_inputs = null;
    var u = 'input[name="product-quantity-spin"]';
    (0, s.default)(document).ready(function () {
        function t(t) {
            return "on.startupspin" === t || "on.startdownspin" === t
        }

        function e(t) {
            return "on.startupspin" === t
        }

        function n(t) {
            var e = t.parents(".bootstrap-touchspin").find(p);
            return e.is(":focus") ? null : e
        }

        function i(t) {
            var e = t.split("-"), n = void 0, i = void 0, r = "";
            for (n = 0; n < e.length; n++) i = e[n], 0 !== n && (i = i.substring(0, 1).toUpperCase() + i.substring(1)), r += i;
            return r
        }

        function o(r, o) {
            if (!t(o)) return {url: r.attr("href"), type: i(r.data("link-action"))};
            var s = n(r);
            if (s) {
                return e(o) ? {url: s.data("up-url"), type: "increaseProductQuantity"} : {
                    url: s.data("down-url"),
                    type: "decreaseProductQuantity"
                }
            }
        }

        function a(t, e, n) {
            return g(), s.default.ajax({
                url: t, method: "POST", data: e, dataType: "json", beforeSend: function (t) {
                    h.push(t)
                }
            }).then(function (t) {
                n.val(t.quantity);
                var e;
                e = n && n.dataset ? n.dataset : t, l.default.emit("updateCart", {reason: e})
            }).fail(function (t) {
                l.default.emit("handleError", {eventType: "updateProductQuantityInCart", resp: t})
            })
        }

        function c(t) {
            return {ajax: "1", qty: Math.abs(t), action: "update", op: f(t)}
        }

        function f(t) {
            return t > 0 ? "up" : "down"
        }

        function d(t) {
            var e = (0, s.default)(t.currentTarget), n = e.data("update-url"), i = e.attr("value"), r = e.val();
            if (r != parseInt(r) || r < 0 || isNaN(r)) return void e.val(i);
            var o = r - i;
            if (0 != o) {
                a(n, c(o), e)
            }
        }

        var p = ".js-cart-line-product-quantity", h = [];
        l.default.on("updateCart", function () {
            (0, s.default)(".quickview").modal("hide")
        }), l.default.on("updatedCart", function () {
            r()
        }), r();
        var m = (0, s.default)("body"), g = function () {
            for (var t; h.length > 0;) t = h.pop(), t.abort()
        }, v = function (t) {
            return (0, s.default)(t.parents(".bootstrap-touchspin").find("input"))
        }, y = function (t) {
            t.preventDefault();
            var e = (0, s.default)(t.currentTarget), n = t.currentTarget.dataset, i = o(e, t.namespace),
                r = {ajax: "1", action: "update"};
            void 0 !== i && (g(), s.default.ajax({
                url: i.url,
                method: "POST",
                data: r,
                dataType: "json",
                beforeSend: function (t) {
                    h.push(t)
                }
            }).then(function (t) {
                v(e).val(t.quantity), l.default.emit("updateCart", {reason: n})
            }).fail(function (t) {
                l.default.emit("handleError", {eventType: "updateProductInCart", resp: t, cartAction: i.type})
            }))
        };
        m.on("click", '[data-link-action="delete-from-cart"], [data-link-action="remove-voucher"]', y), m.on("touchspin.on.startdownspin", u, y), m.on("touchspin.on.startupspin", u, y), m.on("focusout", p, function (t) {
            d(t)
        }), m.on("keyup", p, function (t) {
            13 == t.keyCode && d(t)
        }), m.on("click", ".js-discount .code", function (t) {
            t.stopPropagation();
            var e = (0, s.default)(t.currentTarget);
            return (0, s.default)("[name=discount_name]").val(e.text()), !1
        })
    })
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    function r() {
        0 !== (0, s.default)(".js-cancel-address").length && (0, s.default)(".checkout-step:not(.js-current-step) .step-title").addClass("not-allowed"), (0, s.default)(".js-terms a").on("click", function (t) {
            t.preventDefault();
            var e = (0, s.default)(t.target).attr("href");
            e && (e += "?content_only=1", s.default.get(e, function (t) {
                (0, s.default)("#modal").find(".js-modal-content").html((0, s.default)(t).find(".page-cms").contents())
            }).fail(function (t) {
                l.default.emit("handleError", {eventType: "clickTerms", resp: t})
            })), (0, s.default)("#modal").modal("show")
        }), (0, s.default)(".js-gift-checkbox").on("click", function (t) {
            (0, s.default)("#gift").collapse("toggle")
        })
    }

    var o = n(0), s = i(o), a = n(1), l = i(a);
    (0, s.default)(document).ready(function () {
        1 === (0, s.default)("body#checkout").length && r(), l.default.on("updatedDeliveryForm", function (t) {
            void 0 !== t.deliveryOption && 0 !== t.deliveryOption.length && ((0, s.default)(".carrier-extra-content").hide(), t.deliveryOption.next(".carrier-extra-content").slideDown())
        })
    })
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    var r = n(1), o = i(r), s = n(0), a = i(s);
    o.default.blockcart = o.default.blockcart || {}, o.default.blockcart.showModal = function (t) {
        function e() {
            return (0, a.default)("#blockcart-modal")
        }

        var n = e();
        n.length && n.remove(), (0, a.default)("body").append(t), n = e(), n.modal("show").on("hidden.bs.modal", function (t) {
            o.default.emit("updateProduct", {reason: t.currentTarget.dataset})
        })
    }
}, function (t, e, n) {
    "use strict";

    function i(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    Object.defineProperty(e, "__esModule", {value: !0});
    var r = function () {
        function t(t, e) {
            for (var n = 0; n < e.length; n++) {
                var i = e[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
            }
        }

        return function (e, n, i) {
            return n && t(e.prototype, n), i && t(e, i), e
        }
    }(), o = n(0), s = function (t) {
        return t && t.__esModule ? t : {default: t}
    }(o), a = function () {
        function t() {
            i(this, t)
        }

        return r(t, [{
            key: "init", value: function () {
                this.parentFocus(), this.togglePasswordVisibility()
            }
        }, {
            key: "parentFocus", value: function () {
                (0, s.default)(".js-child-focus").focus(function () {
                    (0, s.default)(this).closest(".js-parent-focus").addClass("focus")
                }), (0, s.default)(".js-child-focus").focusout(function () {
                    (0, s.default)(this).closest(".js-parent-focus").removeClass("focus")
                })
            }
        }, {
            key: "togglePasswordVisibility", value: function () {
                (0, s.default)('button[data-action="show-password"]').on("click", function () {
                    var t = (0, s.default)(this).closest(".input-group").children("input.js-visible-password");
                    "password" === t.attr("type") ? (t.attr("type", "text"), (0, s.default)(this).text((0, s.default)(this).data("textHide"))) : (t.attr("type", "password"), (0, s.default)(this).text((0, s.default)(this).data("textShow")))
                })
            }
        }]), t
    }();
    e.default = a, t.exports = e.default
}, function (t, e, n) {
    "use strict";

    function i(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    Object.defineProperty(e, "__esModule", {value: !0});
    var r = function () {
        function t(t, e) {
            for (var n = 0; n < e.length; n++) {
                var i = e[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
            }
        }

        return function (e, n, i) {
            return n && t(e.prototype, n), i && t(e, i), e
        }
    }(), o = n(0), s = function (t) {
        return t && t.__esModule ? t : {default: t}
    }(o);
    n(4);
    var a = function () {
        function t() {
            i(this, t)
        }

        return r(t, [{
            key: "init", value: function () {
                var t = this, e = (0, s.default)(".js-modal-arrows"), n = (0, s.default)(".js-modal-product-images"),
                    i = (0, s.default)(".on-sale");
                (0, s.default)("body").on("click", ".js-modal-thumb", function (t) {
                    (0, s.default)(".js-modal-thumb").hasClass("selected") && (0, s.default)(".js-modal-thumb").removeClass("selected"), (0, s.default)(t.currentTarget).addClass("selected"), (0, s.default)(".js-modal-product-cover").attr("src", (0, s.default)(t.target).data("image-large-src")), (0, s.default)(".js-modal-product-cover").attr("title", (0, s.default)(t.target).attr("title")), (0, s.default)(".js-modal-product-cover").attr("alt", (0, s.default)(t.target).attr("alt"))
                }).on("click", "aside#thumbnails", function (t) {
                    "thumbnails" == t.target.id && (0, s.default)("#product-modal").modal("hide")
                }), i.length && (0, s.default)("#product").length && (0, s.default)(".new").css("top", i.height() + 10), (0, s.default)(".js-modal-product-images li").length <= 5 ? e.css("opacity", ".2") : e.on("click", function (e) {
                    (0, s.default)(e.target).hasClass("arrow-up") && n.position().top < 0 ? (t.move("up"), (0, s.default)(".js-modal-arrow-down").css("opacity", "1")) : (0, s.default)(e.target).hasClass("arrow-down") && n.position().top + n.height() > (0, s.default)(".js-modal-mask").height() && (t.move("down"), (0, s.default)(".js-modal-arrow-up").css("opacity", "1"))
                })
            }
        }, {
            key: "move", value: function (t) {
                var e = (0, s.default)(".js-modal-product-images"),
                    n = (0, s.default)(".js-modal-product-images li img").height() + 10, i = e.position().top;
                e.velocity({translateY: "up" === t ? i + n : i - n}, function () {
                    e.position().top >= 0 ? (0, s.default)(".js-modal-arrow-up").css("opacity", ".2") : e.position().top + e.height() <= (0, s.default)(".js-modal-mask").height() && (0, s.default)(".js-modal-arrow-down").css("opacity", ".2")
                })
            }
        }]), t
    }();
    e.default = a, t.exports = e.default
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    function r(t, e) {
        if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
    }

    function o(t, e) {
        if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function, not " + typeof e);
        t.prototype = Object.create(e && e.prototype, {
            constructor: {
                value: t,
                enumerable: !1,
                writable: !0,
                configurable: !0
            }
        }), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
    }

    Object.defineProperty(e, "__esModule", {value: !0});
    var s = function () {
        function t(t, e) {
            for (var n = 0; n < e.length; n++) {
                var i = e[n];
                i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
            }
        }

        return function (e, n, i) {
            return n && t(e.prototype, n), i && t(e, i), e
        }
    }(), a = function (t, e, n) {
        for (var i = !0; i;) {
            var r = t, o = e, s = n;
            i = !1, null === r && (r = Function.prototype);
            var a = Object.getOwnPropertyDescriptor(r, o);
            if (void 0 !== a) {
                if ("value" in a) return a.value;
                var l = a.get;
                if (void 0 === l) return;
                return l.call(s)
            }
            var u = Object.getPrototypeOf(r);
            if (null === u) return;
            t = u, e = o, n = s, i = !0, a = u = void 0
        }
    }, l = n(0), u = i(l), c = n(2), f = i(c), d = function (t) {
        function e() {
            r(this, e), a(Object.getPrototypeOf(e.prototype), "constructor", this).apply(this, arguments)
        }

        return o(e, t), s(e, [{
            key: "init", value: function () {
                var t = this, n = void 0, i = this;
                this.el.find("li").hover(function (e) {
                    t.el.parent().hasClass("mobile") || (n !== (0, u.default)(e.currentTarget).attr("id") && (0 === (0, u.default)(e.target).data("depth") && (0, u.default)("#" + n + " .js-sub-menu").hide(), n = (0, u.default)(e.currentTarget).attr("id")), n && 0 === (0, u.default)(e.target).data("depth") && (0, u.default)("#" + n + " .js-sub-menu").show().css({top: (0, u.default)("#" + n).height() + (0, u.default)("#" + n).position().top}))
                }), (0, u.default)("#menu-icon").on("click", function () {
                    (0, u.default)("#mobile_top_menu_wrapper").toggle(), i.toggleMobileMenu()
                }), (0, u.default)(".js-top-menu").mouseleave(function () {
                    t.el.parent().hasClass("mobile") || (0, u.default)("#" + n + " .js-sub-menu").hide()
                }), this.el.on("click", function (e) {
                    t.el.parent().hasClass("mobile") || e.stopPropagation()
                }), prestashop.on("responsive update", function (t) {
                    (0, u.default)(".js-sub-menu").removeAttr("style"), i.toggleMobileMenu()
                }), a(Object.getPrototypeOf(e.prototype), "init", this).call(this)
            }
        }, {
            key: "toggleMobileMenu", value: function () {
                (0, u.default)("#mobile_top_menu_wrapper").is(":visible") ? ((0, u.default)("#notifications").hide(), (0, u.default)("#wrapper").hide(), (0, u.default)("#footer").hide()) : ((0, u.default)("#notifications").show(), (0, u.default)("#wrapper").show(), (0, u.default)("#footer").show())
            }
        }]), e
    }(f.default);
    e.default = d, t.exports = e.default
}, function (t, e, n) {
    "use strict";

    function i() {
        (0, s.default)("#order-return-form table thead input[type=checkbox]").on("click", function () {
            var t = (0, s.default)(this).prop("checked");
            (0, s.default)("#order-return-form table tbody input[type=checkbox]").each(function (e, n) {
                (0, s.default)(n).prop("checked", t)
            })
        })
    }

    function r() {
        (0, s.default)("body#order-detail") && i()
    }

    var o = n(0), s = function (t) {
        return t && t.__esModule ? t : {default: t}
    }(o);
    (0, s.default)(document).ready(r)
}, function (t, e, n) {
    "use strict";
    !function (t) {
        var e = 0, n = function (e, n) {
            this.options = n, this.$elementFilestyle = [], this.$element = t(e)
        };
        n.prototype = {
            clear: function () {
                this.$element.val(""), this.$elementFilestyle.find(":text").val(""), this.$elementFilestyle.find(".badge").remove()
            }, destroy: function () {
                this.$element.removeAttr("style").removeData("filestyle"), this.$elementFilestyle.remove()
            }, disabled: function (t) {
                if (!0 === t) this.options.disabled || (this.$element.attr("disabled", "true"), this.$elementFilestyle.find("label").attr("disabled", "true"), this.options.disabled = !0); else {
                    if (!1 !== t) return this.options.disabled;
                    this.options.disabled && (this.$element.removeAttr("disabled"), this.$elementFilestyle.find("label").removeAttr("disabled"), this.options.disabled = !1)
                }
            }, buttonBefore: function (t) {
                if (!0 === t) this.options.buttonBefore || (this.options.buttonBefore = !0, this.options.input && (this.$elementFilestyle.remove(), this.constructor(), this.pushNameFiles())); else {
                    if (!1 !== t) return this.options.buttonBefore;
                    this.options.buttonBefore && (this.options.buttonBefore = !1, this.options.input && (this.$elementFilestyle.remove(), this.constructor(), this.pushNameFiles()))
                }
            }, icon: function (t) {
                if (!0 === t) this.options.icon || (this.options.icon = !0, this.$elementFilestyle.find("label").prepend(this.htmlIcon())); else {
                    if (!1 !== t) return this.options.icon;
                    this.options.icon && (this.options.icon = !1, this.$elementFilestyle.find(".icon-span-filestyle").remove())
                }
            }, input: function (t) {
                if (!0 === t) this.options.input || (this.options.input = !0, this.options.buttonBefore ? this.$elementFilestyle.append(this.htmlInput()) : this.$elementFilestyle.prepend(this.htmlInput()), this.$elementFilestyle.find(".badge").remove(), this.pushNameFiles(), this.$elementFilestyle.find(".group-span-filestyle").addClass("input-group-btn")); else {
                    if (!1 !== t) return this.options.input;
                    if (this.options.input) {
                        this.options.input = !1, this.$elementFilestyle.find(":text").remove();
                        var e = this.pushNameFiles();
                        e.length > 0 && this.options.badge && this.$elementFilestyle.find("label").append(' <span class="badge">' + e.length + "</span>"), this.$elementFilestyle.find(".group-span-filestyle").removeClass("input-group-btn")
                    }
                }
            }, size: function (t) {
                if (void 0 === t) return this.options.size;
                var e = this.$elementFilestyle.find("label"), n = this.$elementFilestyle.find("input");
                e.removeClass("btn-lg btn-sm"), n.removeClass("input-lg input-sm"), "nr" != t && (e.addClass("btn-" + t), n.addClass("input-" + t))
            }, placeholder: function (t) {
                if (void 0 === t) return this.options.placeholder;
                this.options.placeholder = t, this.$elementFilestyle.find("input").attr("placeholder", t)
            }, buttonText: function (t) {
                if (void 0 === t) return this.options.buttonText;
                this.options.buttonText = t, this.$elementFilestyle.find("label .buttonText").html(this.options.buttonText)
            }, buttonName: function (t) {
                if (void 0 === t) return this.options.buttonName;
                this.options.buttonName = t, this.$elementFilestyle.find("label").attr({class: "btn " + this.options.buttonName})
            }, iconName: function (t) {
                if (void 0 === t) return this.options.iconName;
                this.$elementFilestyle.find(".icon-span-filestyle").attr({class: "icon-span-filestyle " + this.options.iconName})
            }, htmlIcon: function () {
                return this.options.icon ? '<span class="icon-span-filestyle ' + this.options.iconName + '"></span> ' : ""
            }, htmlInput: function () {
                return this.options.input ? '<input type="text" class="form-control ' + ("nr" == this.options.size ? "" : "input-" + this.options.size) + '" placeholder="' + this.options.placeholder + '" disabled> ' : ""
            }, pushNameFiles: function () {
                var t = "", e = [];
                void 0 === this.$element[0].files ? e[0] = {name: this.$element[0] && this.$element[0].value} : e = this.$element[0].files;
                for (var n = 0; n < e.length; n++) t += e[n].name.split("\\").pop() + ", ";
                return "" !== t ? this.$elementFilestyle.find(":text").val(t.replace(/\, $/g, "")) : this.$elementFilestyle.find(":text").val(""), e
            }, constructor: function () {
                var n = this, i = "", r = n.$element.attr("id"), o = "";
                "" !== r && r || (r = "filestyle-" + e, n.$element.attr({id: r}), e++), o = '<span class="group-span-filestyle ' + (n.options.input ? "input-group-btn" : "") + '"><label for="' + r + '" class="btn ' + n.options.buttonName + " " + ("nr" == n.options.size ? "" : "btn-" + n.options.size) + '" ' + (n.options.disabled ? 'disabled="true"' : "") + ">" + n.htmlIcon() + '<span class="buttonText">' + n.options.buttonText + "</span></label></span>", i = n.options.buttonBefore ? o + n.htmlInput() : n.htmlInput() + o, n.$elementFilestyle = t('<div class="bootstrap-filestyle input-group">' + i + "</div>"), n.$elementFilestyle.find(".group-span-filestyle").attr("tabindex", "0").keypress(function (t) {
                    if (13 === t.keyCode || 32 === t.charCode) return n.$elementFilestyle.find("label").click(), !1
                }), n.$element.css({
                    position: "absolute",
                    clip: "rect(0px 0px 0px 0px)"
                }).attr("tabindex", "-1").after(n.$elementFilestyle), n.options.disabled && n.$element.attr("disabled", "true"), n.$element.change(function () {
                    var t = n.pushNameFiles();
                    0 == n.options.input && n.options.badge ? 0 == n.$elementFilestyle.find(".badge").length ? n.$elementFilestyle.find("label").append(' <span class="badge">' + t.length + "</span>") : 0 == t.length ? n.$elementFilestyle.find(".badge").remove() : n.$elementFilestyle.find(".badge").html(t.length) : n.$elementFilestyle.find(".badge").remove()
                }), window.navigator.userAgent.search(/firefox/i) > -1 && n.$elementFilestyle.find("label").click(function () {
                    return n.$element.click(), !1
                })
            }
        };
        var i = t.fn.filestyle;
        t.fn.filestyle = function (e, i) {
            var r = "", o = this.each(function () {
                if ("file" === t(this).attr("type")) {
                    var o = t(this), s = o.data("filestyle"),
                        a = t.extend({}, t.fn.filestyle.defaults, e, "object" == typeof e && e);
                    s || (o.data("filestyle", s = new n(this, a)), s.constructor()), "string" == typeof e && (r = s[e](i))
                }
            });
            return void 0 !== typeof r ? r : o
        }, t.fn.filestyle.defaults = {
            buttonText: "Choose file",
            iconName: "glyphicon glyphicon-folder-open",
            buttonName: "btn-default",
            size: "nr",
            input: !0,
            badge: !0,
            icon: !0,
            buttonBefore: !1,
            disabled: !1,
            placeholder: ""
        }, t.fn.filestyle.noConflict = function () {
            return t.fn.filestyle = i, this
        }, t(function () {
            t(".filestyle").each(function () {
                var e = t(this), n = {
                    input: "false" !== e.attr("data-input"),
                    icon: "false" !== e.attr("data-icon"),
                    buttonBefore: "true" === e.attr("data-buttonBefore"),
                    disabled: "true" === e.attr("data-disabled"),
                    size: e.attr("data-size"),
                    buttonText: e.attr("data-buttonText"),
                    buttonName: e.attr("data-buttonName"),
                    iconName: e.attr("data-iconName"),
                    badge: "false" !== e.attr("data-badge"),
                    placeholder: e.attr("data-placeholder")
                };
                e.filestyle(n)
            })
        })
    }(window.jQuery)
}, function (t, e, n) {
    "use strict";
    !function (t) {
        t.fn.scrollbox = function (e) {
            var n = {
                linear: !1,
                startDelay: 2,
                delay: 3,
                step: 5,
                speed: 32,
                switchItems: 1,
                direction: "vertical",
                distance: "auto",
                autoPlay: !0,
                onMouseOverPause: !0,
                paused: !1,
                queue: null,
                listElement: "ul",
                listItemElement: "li",
                infiniteLoop: !0,
                switchAmount: 0,
                afterForward: null,
                afterBackward: null,
                triggerStackable: !1
            };
            return e = t.extend(n, e), e.scrollOffset = "vertical" === e.direction ? "scrollTop" : "scrollLeft", e.queue && (e.queue = t("#" + e.queue)), this.each(function () {
                var n, i, r, o, s, a, l, u, c, f = t(this), d = null, p = null, h = !1, m = 0, g = 0;
                e.onMouseOverPause && (f.bind("mouseover", function () {
                    h = !0
                }), f.bind("mouseout", function () {
                    h = !1
                })), n = f.children(e.listElement + ":first-child"), !1 === e.infiniteLoop && 0 === e.switchAmount && (e.switchAmount = n.children().length), a = function () {
                    if (!h) {
                        var r, s, a, l, u;
                        if (r = n.children(e.listItemElement + ":first-child"), l = "auto" !== e.distance ? e.distance : "vertical" === e.direction ? r.outerHeight(!0) : r.outerWidth(!0), e.linear ? a = Math.min(f[0][e.scrollOffset] + e.step, l) : (u = Math.max(3, parseInt(.3 * (l - f[0][e.scrollOffset]), 10)), a = Math.min(f[0][e.scrollOffset] + u, l)), f[0][e.scrollOffset] = a, a >= l) {
                            for (s = 0; s < e.switchItems; s++) e.queue && e.queue.find(e.listItemElement).length > 0 ? (n.append(e.queue.find(e.listItemElement)[0]), n.children(e.listItemElement + ":first-child").remove()) : n.append(n.children(e.listItemElement + ":first-child")), ++m;
                            if (f[0][e.scrollOffset] = 0, clearInterval(d), d = null, t.isFunction(e.afterForward) && e.afterForward.call(f, {
                                    switchCount: m,
                                    currentFirstChild: n.children(e.listItemElement + ":first-child")
                                }), e.triggerStackable && 0 !== g) return void i();
                            if (!1 === e.infiniteLoop && m >= e.switchAmount) return;
                            e.autoPlay && (p = setTimeout(o, 1e3 * e.delay))
                        }
                    }
                }, l = function () {
                    if (!h) {
                        var r, s, a, l, u;
                        if (0 === f[0][e.scrollOffset]) {
                            for (s = 0; s < e.switchItems; s++) n.children(e.listItemElement + ":last-child").insertBefore(n.children(e.listItemElement + ":first-child"));
                            r = n.children(e.listItemElement + ":first-child"), l = "auto" !== e.distance ? e.distance : "vertical" === e.direction ? r.height() : r.width(), f[0][e.scrollOffset] = l
                        }
                        if (e.linear ? a = Math.max(f[0][e.scrollOffset] - e.step, 0) : (u = Math.max(3, parseInt(.3 * f[0][e.scrollOffset], 10)), a = Math.max(f[0][e.scrollOffset] - u, 0)), f[0][e.scrollOffset] = a, 0 === a) {
                            if (--m, clearInterval(d), d = null, t.isFunction(e.afterBackward) && e.afterBackward.call(f, {
                                    switchCount: m,
                                    currentFirstChild: n.children(e.listItemElement + ":first-child")
                                }), e.triggerStackable && 0 !== g) return void i();
                            e.autoPlay && (p = setTimeout(o, 1e3 * e.delay))
                        }
                    }
                }, i = function () {
                    0 !== g && (g > 0 ? (g--, p = setTimeout(o, 0)) : (g++, p = setTimeout(r, 0)))
                }, o = function () {
                    clearInterval(d), d = setInterval(a, e.speed)
                }, r = function () {
                    clearInterval(d), d = setInterval(l, e.speed)
                }, u = function () {
                    e.autoPlay = !0, h = !1, clearInterval(d), d = setInterval(a, e.speed)
                }, c = function () {
                    h = !0
                }, s = function (t) {
                    e.delay = t || e.delay, clearTimeout(p), e.autoPlay && (p = setTimeout(o, 1e3 * e.delay))
                }, e.autoPlay && (p = setTimeout(o, 1e3 * e.startDelay)), f.bind("resetClock", function (t) {
                    s(t)
                }), f.bind("forward", function () {
                    e.triggerStackable ? null !== d ? g++ : o() : (clearTimeout(p), o())
                }), f.bind("backward", function () {
                    e.triggerStackable ? null !== d ? g-- : r() : (clearTimeout(p), r())
                }), f.bind("pauseHover", function () {
                    c()
                }), f.bind("forwardHover", function () {
                    u()
                }), f.bind("speedUp", function (t, n) {
                    "undefined" === n && (n = Math.max(1, parseInt(e.speed / 2, 10))), e.speed = n
                }), f.bind("speedDown", function (t, n) {
                    "undefined" === n && (n = 2 * e.speed), e.speed = n
                }), f.bind("updateConfig", function (n, i) {
                    e = t.extend(e, i)
                })
            })
        }
    }(jQuery)
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    function r(t) {
        (0, s.default)("#search_filters").replaceWith(t.rendered_facets), (0, s.default)("#js-active-search-filters").replaceWith(t.rendered_active_filters), (0, s.default)("#js-product-list-top").replaceWith(t.rendered_products_top), (0, s.default)("#js-product-list").replaceWith(t.rendered_products), (0, s.default)("#js-product-list-bottom").replaceWith(t.rendered_products_bottom), (new c.default).init()
    }

    var o = n(0), s = i(o), a = n(1), l = i(a);
    n(4);
    var u = n(3), c = i(u);
    (0, s.default)(document).ready(function () {
        l.default.on("clickQuickView", function (e) {
            var n = {
                action: "quickview",
                id_product: e.dataset.idProduct,
                id_product_attribute: e.dataset.idProductAttribute
            };
            s.default.post(l.default.urls.pages.product, n, null, "json").then(function (e) {
                (0, s.default)("body").append(e.quickview_html);
                var n = (0, s.default)("#quickview-modal-" + e.product.id + "-" + e.product.id_product_attribute);
                n.modal("show"), t(n), n.on("hidden.bs.modal", function () {
                    n.remove()
                })
            }).fail(function (t) {
                l.default.emit("handleError", {eventType: "clickQuickView", resp: t})
            })
        });
        var t = function (t) {
            var n = (0, s.default)(".js-arrows"), i = t.find(".js-qv-product-images");
            (0, s.default)(".js-thumb").on("click", function (t) {
                (0, s.default)(".js-thumb").hasClass("selected") && (0, s.default)(".js-thumb").removeClass("selected"), (0, s.default)(t.currentTarget).addClass("selected"), (0, s.default)(".js-qv-product-cover").attr("src", (0, s.default)(t.target).data("image-large-src"))
            }), i.find("li").length <= 4 ? n.hide() : n.on("click", function (t) {
                (0, s.default)(t.target).hasClass("arrow-up") && (0, s.default)(".js-qv-product-images").position().top < 0 ? (e("up"), (0, s.default)(".arrow-down").css("opacity", "1")) : (0, s.default)(t.target).hasClass("arrow-down") && i.position().top + i.height() > (0, s.default)(".js-qv-mask").height() && (e("down"), (0, s.default)(".arrow-up").css("opacity", "1"))
            }), t.find("#quantity_wanted").TouchSpin({
                verticalbuttons: !0,
                verticalupclass: "material-icons touchspin-up",
                verticaldownclass: "material-icons touchspin-down",
                buttondown_class: "btn btn-touchspin js-touchspin",
                buttonup_class: "btn btn-touchspin js-touchspin",
                min: 1,
                max: 1e6
            })
        }, e = function (t) {
            var e = (0, s.default)(".js-qv-product-images"),
                n = (0, s.default)(".js-qv-product-images li img").height() + 20, i = e.position().top;
            e.velocity({translateY: "up" === t ? i + n : i - n}, function () {
                e.position().top >= 0 ? (0, s.default)(".arrow-up").css("opacity", ".2") : e.position().top + e.height() <= (0, s.default)(".js-qv-mask").height() && (0, s.default)(".arrow-down").css("opacity", ".2")
            })
        };
        (0, s.default)("body").on("click", "#search_filter_toggler", function () {
            (0, s.default)("#search_filters_wrapper").removeClass("hidden-sm-down"), (0, s.default)("#content-wrapper").addClass("hidden-sm-down"), (0, s.default)("#footer").addClass("hidden-sm-down")
        }), (0, s.default)("#search_filter_controls .clear").on("click", function () {
            (0, s.default)("#search_filters_wrapper").addClass("hidden-sm-down"), (0, s.default)("#content-wrapper").removeClass("hidden-sm-down"), (0, s.default)("#footer").removeClass("hidden-sm-down")
        }), (0, s.default)("#search_filter_controls .ok").on("click", function () {
            (0, s.default)("#search_filters_wrapper").addClass("hidden-sm-down"), (0, s.default)("#content-wrapper").removeClass("hidden-sm-down"), (0, s.default)("#footer").removeClass("hidden-sm-down")
        });
        var n = function (t) {
            if (void 0 !== t.target.dataset.searchUrl) return t.target.dataset.searchUrl;
            if (void 0 === (0, s.default)(t.target).parent()[0].dataset.searchUrl) throw new Error("Can not parse search URL");
            return (0, s.default)(t.target).parent()[0].dataset.searchUrl
        };
        (0, s.default)("body").on("change", "#search_filters input[data-search-url]", function (t) {
            l.default.emit("updateFacets", n(t))
        }), (0, s.default)("body").on("click", ".js-search-filters-clear-all", function (t) {
            l.default.emit("updateFacets", n(t))
        }), (0, s.default)("body").on("click", ".js-search-link", function (t) {
            t.preventDefault(), l.default.emit("updateFacets", (0, s.default)(t.target).closest("a").get(0).href)
        }), (0, s.default)("body").on("change", "#search_filters select", function (t) {
            var e = (0, s.default)(t.target).closest("form");
            l.default.emit("updateFacets", "?" + e.serialize())
        }), l.default.on("updateProductList", function (t) {
            r(t)
        })
    })
}, function (t, e, n) {
    "use strict";
    var i = n(0), r = function (t) {
        return t && t.__esModule ? t : {default: t}
    }(i);
    (0, r.default)(document).ready(function () {
        function t() {
            (0, r.default)(".js-thumb").on("click", function (t) {
                (0, r.default)(".js-modal-product-cover").attr("src", (0, r.default)(t.target).data("image-large-src")), (0, r.default)(".selected").removeClass("selected"), (0, r.default)(t.target).addClass("selected"), (0, r.default)(".js-qv-product-cover").prop("src", (0, r.default)(t.currentTarget).data("image-large-src"))
            })
        }

        function e() {
            (0, r.default)("#main .js-qv-product-images li").length > 2 ? ((0, r.default)("#main .js-qv-mask").addClass("scroll"), (0, r.default)(".scroll-box-arrows").addClass("scroll"), (0, r.default)("#main .js-qv-mask").scrollbox({
                direction: "h",
                distance: 113,
                autoPlay: !1
            }), (0, r.default)(".scroll-box-arrows .left").click(function () {
                (0, r.default)("#main .js-qv-mask").trigger("backward")
            }), (0, r.default)(".scroll-box-arrows .right").click(function () {
                (0, r.default)("#main .js-qv-mask").trigger("forward")
            })) : ((0, r.default)("#main .js-qv-mask").removeClass("scroll"), (0, r.default)(".scroll-box-arrows").removeClass("scroll"))
        }

        function n() {
            (0, r.default)(".js-file-input").on("change", function (t) {
                var e = void 0, n = void 0;
                (e = (0, r.default)(t.currentTarget)[0]) && (n = e.files[0]) && (0, r.default)(e).prev().text(n.name)
            })
        }

        !function () {
            var t = (0, r.default)("#quantity_wanted");
            t.TouchSpin({
                verticalbuttons: !0,
                verticalupclass: "material-icons touchspin-up",
                verticaldownclass: "material-icons touchspin-down",
                buttondown_class: "btn btn-touchspin js-touchspin",
                buttonup_class: "btn btn-touchspin js-touchspin",
                min: parseInt(t.attr("min"), 10),
                max: 1e6
            });
            var e = t.val();
            t.on("keyup change", function (t) {
                var n = (0, r.default)(this).val();
                if (n !== e) {
                    e = n;
                    var i = (0, r.default)(".product-refresh");
                    (0, r.default)(t.currentTarget).trigger("touchspin.stopspin"), i.trigger("click", {eventType: "updatedProductQuantity"})
                }
                return t.preventDefault(), !1
            })
        }(), n(), t(), e(), prestashop.on("updatedProduct", function (i) {
            if (n(), t(), i && i.product_minimal_quantity) {
                var o = parseInt(i.product_minimal_quantity, 10);
                (0, r.default)("#quantity_wanted").trigger("touchspin.updatesettings", {min: o})
            }
            e(), (0, r.default)((0, r.default)(".tabs .nav-link.active").attr("href")).addClass("active").removeClass("fade"), (0, r.default)(".js-product-images-modal").replaceWith(i.product_images_modal)
        })
    })
}, function (t, e, n) {
    "use strict";

    function i(t) {
        return t && t.__esModule ? t : {default: t}
    }

    function r(t, e) {
        var n = e.children().detach();
        e.empty().append(t.children().detach()), t.append(n)
    }

    function o() {
        u.default.responsive.mobile ? (0, a.default)("*[id^='_desktop_']").each(function (t, e) {
            var n = (0, a.default)("#" + e.id.replace("_desktop_", "_mobile_"));
            n.length && r((0, a.default)(e), n)
        }) : (0, a.default)("*[id^='_mobile_']").each(function (t, e) {
            var n = (0, a.default)("#" + e.id.replace("_mobile_", "_desktop_"));
            n.length && r((0, a.default)(e), n)
        }), u.default.emit("responsive update", {mobile: u.default.responsive.mobile})
    }

    var s = n(0), a = i(s), l = n(1), u = i(l);
    u.default.responsive = u.default.responsive || {}, u.default.responsive.current_width = window.innerWidth, u.default.responsive.min_width = 768, u.default.responsive.mobile = u.default.responsive.current_width < u.default.responsive.min_width, (0, a.default)(window).on("resize", function () {
        var t = u.default.responsive.current_width, e = u.default.responsive.min_width, n = window.innerWidth,
            i = t >= e && n < e || t < e && n >= e;
        u.default.responsive.current_width = n, u.default.responsive.mobile = u.default.responsive.current_width < u.default.responsive.min_width, i && o()
    }), (0, a.default)(document).ready(function () {
        u.default.responsive.mobile && o()
    })
}, function (t, e, n) {
    "use strict";
    !function (t) {
        function e(t, e) {
            return t + ".touchspin_" + e
        }

        function n(n, i) {
            return t.map(n, function (t) {
                return e(t, i)
            })
        }

        var i = 0;
        t.fn.TouchSpin = function (e) {
            if ("destroy" === e) return void this.each(function () {
                var e = t(this), i = e.data();
                t(document).off(n(["mouseup", "touchend", "touchcancel", "mousemove", "touchmove", "scroll", "scrollstart"], i.spinnerid).join(" "))
            });
            var r = {
                min: 0,
                max: 100,
                initval: "",
                replacementval: "",
                step: 1,
                decimals: 0,
                stepinterval: 100,
                forcestepdivisibility: "round",
                stepintervaldelay: 500,
                verticalbuttons: !1,
                verticalupclass: "glyphicon glyphicon-chevron-up",
                verticaldownclass: "glyphicon glyphicon-chevron-down",
                prefix: "",
                postfix: "",
                prefix_extraclass: "",
                postfix_extraclass: "",
                booster: !0,
                boostat: 10,
                maxboostedstep: !1,
                mousewheel: !0,
                buttondown_class: "btn btn-default",
                buttonup_class: "btn btn-default",
                buttondown_txt: "-",
                buttonup_txt: "+"
            }, o = {
                min: "min",
                max: "max",
                initval: "init-val",
                replacementval: "replacement-val",
                step: "step",
                decimals: "decimals",
                stepinterval: "step-interval",
                verticalbuttons: "vertical-buttons",
                verticalupclass: "vertical-up-class",
                verticaldownclass: "vertical-down-class",
                forcestepdivisibility: "force-step-divisibility",
                stepintervaldelay: "step-interval-delay",
                prefix: "prefix",
                postfix: "postfix",
                prefix_extraclass: "prefix-extra-class",
                postfix_extraclass: "postfix-extra-class",
                booster: "booster",
                boostat: "boostat",
                maxboostedstep: "max-boosted-step",
                mousewheel: "mouse-wheel",
                buttondown_class: "button-down-class",
                buttonup_class: "button-up-class",
                buttondown_txt: "button-down-txt",
                buttonup_txt: "button-up-txt"
            };
            return this.each(function () {
                function s() {
                    "" !== T.initval && "" === L.val() && L.val(T.initval)
                }

                function a(t) {
                    c(t), b();
                    var e = I.input.val();
                    "" !== e && (e = Number(I.input.val()), I.input.val(e.toFixed(T.decimals)))
                }

                function l() {
                    T = t.extend({}, r, j, u(), e)
                }

                function u() {
                    var e = {};
                    return t.each(o, function (t, n) {
                        var i = "bts-" + n;
                        L.is("[data-" + i + "]") && (e[t] = L.data(i))
                    }), e
                }

                function c(e) {
                    T = t.extend({}, T, e)
                }

                function f() {
                    var t = L.val(), e = L.parent();
                    "" !== t && (t = Number(t).toFixed(T.decimals)), L.data("initvalue", t).val(t), L.addClass("form-control"), e.hasClass("input-group") ? d(e) : p()
                }

                function d(e) {
                    e.addClass("bootstrap-touchspin");
                    var n, i, r = L.prev(), o = L.next(),
                        s = '<span class="input-group-addon bootstrap-touchspin-prefix">' + T.prefix + "</span>",
                        a = '<span class="input-group-addon bootstrap-touchspin-postfix">' + T.postfix + "</span>";
                    r.hasClass("input-group-btn") ? (n = '<button class="' + T.buttondown_class + ' bootstrap-touchspin-down" type="button">' + T.buttondown_txt + "</button>", r.append(n)) : (n = '<span class="input-group-btn"><button class="' + T.buttondown_class + ' bootstrap-touchspin-down" type="button">' + T.buttondown_txt + "</button></span>", t(n).insertBefore(L)), o.hasClass("input-group-btn") ? (i = '<button class="' + T.buttonup_class + ' bootstrap-touchspin-up" type="button">' + T.buttonup_txt + "</button>", o.prepend(i)) : (i = '<span class="input-group-btn"><button class="' + T.buttonup_class + ' bootstrap-touchspin-up" type="button">' + T.buttonup_txt + "</button></span>", t(i).insertAfter(L)), t(s).insertBefore(L), t(a).insertAfter(L), A = e
                }

                function p() {
                    var e;
                    e = T.verticalbuttons ? '<div class="input-group bootstrap-touchspin"><span class="input-group-addon bootstrap-touchspin-prefix">' + T.prefix + '</span><span class="input-group-addon bootstrap-touchspin-postfix">' + T.postfix + '</span><span class="input-group-btn-vertical"><button class="' + T.buttondown_class + ' bootstrap-touchspin-up" type="button"><i class="' + T.verticalupclass + '"></i></button><button class="' + T.buttonup_class + ' bootstrap-touchspin-down" type="button"><i class="' + T.verticaldownclass + '"></i></button></span></div>' : '<div class="input-group bootstrap-touchspin"><span class="input-group-btn"><button class="' + T.buttondown_class + ' bootstrap-touchspin-down" type="button">' + T.buttondown_txt + '</button></span><span class="input-group-addon bootstrap-touchspin-prefix">' + T.prefix + '</span><span class="input-group-addon bootstrap-touchspin-postfix">' + T.postfix + '</span><span class="input-group-btn"><button class="' + T.buttonup_class + ' bootstrap-touchspin-up" type="button">' + T.buttonup_txt + "</button></span></div>", A = t(e).insertBefore(L), t(".bootstrap-touchspin-prefix", A).after(L), L.hasClass("input-sm") ? A.addClass("input-group-sm") : L.hasClass("input-lg") && A.addClass("input-group-lg")
                }

                function h() {
                    I = {
                        down: t(".bootstrap-touchspin-down", A),
                        up: t(".bootstrap-touchspin-up", A),
                        input: t("input", A),
                        prefix: t(".bootstrap-touchspin-prefix", A).addClass(T.prefix_extraclass),
                        postfix: t(".bootstrap-touchspin-postfix", A).addClass(T.postfix_extraclass)
                    }
                }

                function m() {
                    "" === T.prefix && I.prefix.hide(), "" === T.postfix && I.postfix.hide()
                }

                function g() {
                    L.on("keydown", function (t) {
                        var e = t.keyCode || t.which;
                        38 === e ? ("up" !== V && (x(), E()), t.preventDefault()) : 40 === e && ("down" !== V && (w(), S()), t.preventDefault())
                    }), L.on("keyup", function (t) {
                        var e = t.keyCode || t.which;
                        38 === e ? C() : 40 === e && C()
                    }), L.on("blur", function () {
                        b()
                    }), I.down.on("keydown", function (t) {
                        var e = t.keyCode || t.which;
                        32 !== e && 13 !== e || ("down" !== V && (w(), S()), t.preventDefault())
                    }), I.down.on("keyup", function (t) {
                        var e = t.keyCode || t.which;
                        32 !== e && 13 !== e || C()
                    }), I.up.on("keydown", function (t) {
                        var e = t.keyCode || t.which;
                        32 !== e && 13 !== e || ("up" !== V && (x(), E()), t.preventDefault())
                    }), I.up.on("keyup", function (t) {
                        var e = t.keyCode || t.which;
                        32 !== e && 13 !== e || C()
                    }), I.down.on("mousedown.touchspin", function (t) {
                        I.down.off("touchstart.touchspin"), L.is(":disabled") || (w(), S(), t.preventDefault(), t.stopPropagation())
                    }), I.down.on("touchstart.touchspin", function (t) {
                        I.down.off("mousedown.touchspin"), L.is(":disabled") || (w(), S(), t.preventDefault(), t.stopPropagation())
                    }), I.up.on("mousedown.touchspin", function (t) {
                        I.up.off("touchstart.touchspin"), L.is(":disabled") || (x(), E(), t.preventDefault(), t.stopPropagation())
                    }), I.up.on("touchstart.touchspin", function (t) {
                        I.up.off("mousedown.touchspin"), L.is(":disabled") || (x(), E(), t.preventDefault(), t.stopPropagation())
                    }), I.up.on("mouseout touchleave touchend touchcancel", function (t) {
                        V && (t.stopPropagation(), C())
                    }), I.down.on("mouseout touchleave touchend touchcancel", function (t) {
                        V && (t.stopPropagation(), C())
                    }), I.down.on("mousemove touchmove", function (t) {
                        V && (t.stopPropagation(), t.preventDefault())
                    }), I.up.on("mousemove touchmove", function (t) {
                        V && (t.stopPropagation(), t.preventDefault())
                    }), t(document).on(n(["mouseup", "touchend", "touchcancel"], i).join(" "), function (t) {
                        V && (t.preventDefault(), C())
                    }), t(document).on(n(["mousemove", "touchmove", "scroll", "scrollstart"], i).join(" "), function (t) {
                        V && (t.preventDefault(), C())
                    }), L.on("mousewheel DOMMouseScroll", function (t) {
                        if (T.mousewheel && L.is(":focus")) {
                            var e = t.originalEvent.wheelDelta || -t.originalEvent.deltaY || -t.originalEvent.detail;
                            t.stopPropagation(), t.preventDefault(), e < 0 ? w() : x()
                        }
                    })
                }

                function v() {
                    L.on("touchspin.uponce", function () {
                        C(), x()
                    }), L.on("touchspin.downonce", function () {
                        C(), w()
                    }), L.on("touchspin.startupspin", function () {
                        E()
                    }), L.on("touchspin.startdownspin", function () {
                        S()
                    }), L.on("touchspin.stopspin", function () {
                        C()
                    }), L.on("touchspin.updatesettings", function (t, e) {
                        a(e)
                    })
                }

                function y(t) {
                    switch (T.forcestepdivisibility) {
                        case"round":
                            return (Math.round(t / T.step) * T.step).toFixed(T.decimals);
                        case"floor":
                            return (Math.floor(t / T.step) * T.step).toFixed(T.decimals);
                        case"ceil":
                            return (Math.ceil(t / T.step) * T.step).toFixed(T.decimals);
                        default:
                            return t
                    }
                }

                function b() {
                    var t, e, n;
                    if ("" === (t = L.val())) return void("" !== T.replacementval && (L.val(T.replacementval), L.trigger("change")));
                    T.decimals > 0 && "." === t || (e = parseFloat(t), isNaN(e) && (e = "" !== T.replacementval ? T.replacementval : 0), n = e, e.toString() !== t && (n = e), e < T.min && (n = T.min), e > T.max && (n = T.max), n = y(n), Number(t).toString() !== n.toString() && (L.val(n), L.trigger("change")))
                }

                function _() {
                    if (T.booster) {
                        var t = Math.pow(2, Math.floor(B / T.boostat)) * T.step;
                        return T.maxboostedstep && t > T.maxboostedstep && (t = T.maxboostedstep, O = Math.round(O / t) * t), Math.max(T.step, t)
                    }
                    return T.step
                }

                function x() {
                    b(), O = parseFloat(I.input.val()), isNaN(O) && (O = 0);
                    var t = O, e = _();
                    O += e, O > T.max && (O = T.max, L.trigger("touchspin.on.max"), C()), I.input.val(Number(O).toFixed(T.decimals)), t !== O && L.trigger("change")
                }

                function w() {
                    b(), O = parseFloat(I.input.val()), isNaN(O) && (O = 0);
                    var t = O, e = _();
                    O -= e, O < T.min && (O = T.min, L.trigger("touchspin.on.min"), C()), I.input.val(O.toFixed(T.decimals)), t !== O && L.trigger("change")
                }

                function S() {
                    C(), B = 0, V = "down", L.trigger("touchspin.on.startspin"), L.trigger("touchspin.on.startdownspin"), N = setTimeout(function () {
                        k = setInterval(function () {
                            B++, w()
                        }, T.stepinterval)
                    }, T.stepintervaldelay)
                }

                function E() {
                    C(), B = 0, V = "up", L.trigger("touchspin.on.startspin"), L.trigger("touchspin.on.startupspin"), P = setTimeout(function () {
                        D = setInterval(function () {
                            B++, x()
                        }, T.stepinterval)
                    }, T.stepintervaldelay)
                }

                function C() {
                    switch (clearTimeout(N), clearTimeout(P), clearInterval(k), clearInterval(D), V) {
                        case"up":
                            L.trigger("touchspin.on.stopupspin"), L.trigger("touchspin.on.stopspin");
                            break;
                        case"down":
                            L.trigger("touchspin.on.stopdownspin"), L.trigger("touchspin.on.stopspin")
                    }
                    B = 0, V = !1
                }

                var T, A, I, O, k, D, N, P, L = t(this), j = L.data(), B = 0, V = !1;
                !function () {
                    L.data("alreadyinitialized") || (L.data("alreadyinitialized", !0), i += 1, L.data("spinnerid", i), L.is("input") && (l(), s(), b(), f(), h(), m(), g(), v(), I.input.css("display", "block")))
                }()
            })
        }
    }(jQuery)
}, function (t, e, n) {
    "use strict";
    if ("undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");
    +function (t) {
        var e = t.fn.jquery.split(" ")[0].split(".");
        if (e[0] < 2 && e[1] < 9 || 1 == e[0] && 9 == e[1] && e[2] < 1 || e[0] >= 4) throw new Error("Bootstrap's JavaScript requires at least jQuery v1.9.1 but less than v4.0.0")
    }(jQuery), function () {
        function t(t, e) {
            if (!t) throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
            return !e || "object" != typeof e && "function" != typeof e ? t : e
        }

        function e(t, e) {
            if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function, not " + typeof e);
            t.prototype = Object.create(e && e.prototype, {
                constructor: {
                    value: t,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
        }

        function n(t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }

        var i = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (t) {
            return typeof t
        } : function (t) {
            return t && "function" == typeof Symbol && t.constructor === Symbol && t !== Symbol.prototype ? "symbol" : typeof t
        }, r = function () {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var i = e[n];
                    i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
                }
            }

            return function (e, n, i) {
                return n && t(e.prototype, n), i && t(e, i), e
            }
        }(), o = function (t) {
            function e(t) {
                return {}.toString.call(t).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
            }

            function n(t) {
                return (t[0] || t).nodeType
            }

            function i() {
                return {
                    bindType: s.end, delegateType: s.end, handle: function (e) {
                        if (t(e.target).is(this)) return e.handleObj.handler.apply(this, arguments)
                    }
                }
            }

            function r() {
                if (window.QUnit) return !1;
                var t = document.createElement("bootstrap");
                for (var e in a) if (void 0 !== t.style[e]) return {end: a[e]};
                return !1
            }

            function o(e) {
                var n = this, i = !1;
                return t(this).one(l.TRANSITION_END, function () {
                    i = !0
                }), setTimeout(function () {
                    i || l.triggerTransitionEnd(n)
                }, e), this
            }

            var s = !1, a = {
                WebkitTransition: "webkitTransitionEnd",
                MozTransition: "transitionend",
                OTransition: "oTransitionEnd otransitionend",
                transition: "transitionend"
            }, l = {
                TRANSITION_END: "bsTransitionEnd", getUID: function (t) {
                    do {
                        t += ~~(1e6 * Math.random())
                    } while (document.getElementById(t));
                    return t
                }, getSelectorFromElement: function (t) {
                    var e = t.getAttribute("data-target");
                    return e || (e = t.getAttribute("href") || "", e = /^#[a-z]/i.test(e) ? e : null), e
                }, reflow: function (t) {
                    new Function("bs", "return bs")(t.offsetHeight)
                }, triggerTransitionEnd: function (e) {
                    t(e).trigger(s.end)
                }, supportsTransitionEnd: function () {
                    return Boolean(s)
                }, typeCheckConfig: function (t, i, r) {
                    for (var o in r) if (r.hasOwnProperty(o)) {
                        var s = r[o], a = i[o], l = void 0;
                        if (l = a && n(a) ? "element" : e(a), !new RegExp(s).test(l)) throw new Error(t.toUpperCase() + ': Option "' + o + '" provided type "' + l + '" but expected type "' + s + '".')
                    }
                }
            };
            return function () {
                s = r(), t.fn.emulateTransitionEnd = o, l.supportsTransitionEnd() && (t.event.special[l.TRANSITION_END] = i())
            }(), l
        }(jQuery), s = (function (t) {
            var e = "alert", i = "bs.alert", s = "." + i, a = t.fn[e], l = {DISMISS: '[data-dismiss="alert"]'},
                u = {CLOSE: "close" + s, CLOSED: "closed" + s, CLICK_DATA_API: "click" + s + ".data-api"},
                c = {ALERT: "alert", FADE: "fade", IN: "in"}, f = function () {
                    function e(t) {
                        n(this, e), this._element = t
                    }

                    return e.prototype.close = function (t) {
                        t = t || this._element;
                        var e = this._getRootElement(t);
                        this._triggerCloseEvent(e).isDefaultPrevented() || this._removeElement(e)
                    }, e.prototype.dispose = function () {
                        t.removeData(this._element, i), this._element = null
                    }, e.prototype._getRootElement = function (e) {
                        var n = o.getSelectorFromElement(e), i = !1;
                        return n && (i = t(n)[0]), i || (i = t(e).closest("." + c.ALERT)[0]), i
                    }, e.prototype._triggerCloseEvent = function (e) {
                        var n = t.Event(u.CLOSE);
                        return t(e).trigger(n), n
                    }, e.prototype._removeElement = function (e) {
                        return t(e).removeClass(c.IN), o.supportsTransitionEnd() && t(e).hasClass(c.FADE) ? void t(e).one(o.TRANSITION_END, t.proxy(this._destroyElement, this, e)).emulateTransitionEnd(150) : void this._destroyElement(e)
                    }, e.prototype._destroyElement = function (e) {
                        t(e).detach().trigger(u.CLOSED).remove()
                    }, e._jQueryInterface = function (n) {
                        return this.each(function () {
                            var r = t(this), o = r.data(i);
                            o || (o = new e(this), r.data(i, o)), "close" === n && o[n](this)
                        })
                    }, e._handleDismiss = function (t) {
                        return function (e) {
                            e && e.preventDefault(), t.close(this)
                        }
                    }, r(e, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }]), e
                }();
            t(document).on(u.CLICK_DATA_API, l.DISMISS, f._handleDismiss(new f)), t.fn[e] = f._jQueryInterface, t.fn[e].Constructor = f, t.fn[e].noConflict = function () {
                return t.fn[e] = a, f._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "button", i = "bs.button", o = "." + i, s = ".data-api", a = t.fn[e],
                l = {ACTIVE: "active", BUTTON: "btn", FOCUS: "focus"}, u = {
                    DATA_TOGGLE_CARROT: '[data-toggle^="button"]',
                    DATA_TOGGLE: '[data-toggle="buttons"]',
                    INPUT: "input",
                    ACTIVE: ".active",
                    BUTTON: ".btn"
                }, c = {CLICK_DATA_API: "click" + o + s, FOCUS_BLUR_DATA_API: "focus" + o + s + " blur" + o + s},
                f = function () {
                    function e(t) {
                        n(this, e), this._element = t
                    }

                    return e.prototype.toggle = function () {
                        var e = !0, n = t(this._element).closest(u.DATA_TOGGLE)[0];
                        if (n) {
                            var i = t(this._element).find(u.INPUT)[0];
                            if (i) {
                                if ("radio" === i.type) if (i.checked && t(this._element).hasClass(l.ACTIVE)) e = !1; else {
                                    var r = t(n).find(u.ACTIVE)[0];
                                    r && t(r).removeClass(l.ACTIVE)
                                }
                                e && (i.checked = !t(this._element).hasClass(l.ACTIVE), t(this._element).trigger("change")), i.focus()
                            }
                        } else this._element.setAttribute("aria-pressed", !t(this._element).hasClass(l.ACTIVE));
                        e && t(this._element).toggleClass(l.ACTIVE)
                    }, e.prototype.dispose = function () {
                        t.removeData(this._element, i), this._element = null
                    }, e._jQueryInterface = function (n) {
                        return this.each(function () {
                            var r = t(this).data(i);
                            r || (r = new e(this), t(this).data(i, r)), "toggle" === n && r[n]()
                        })
                    }, r(e, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }]), e
                }();
            t(document).on(c.CLICK_DATA_API, u.DATA_TOGGLE_CARROT, function (e) {
                e.preventDefault();
                var n = e.target;
                t(n).hasClass(l.BUTTON) || (n = t(n).closest(u.BUTTON)), f._jQueryInterface.call(t(n), "toggle")
            }).on(c.FOCUS_BLUR_DATA_API, u.DATA_TOGGLE_CARROT, function (e) {
                var n = t(e.target).closest(u.BUTTON)[0];
                t(n).toggleClass(l.FOCUS, /^focus(in)?$/.test(e.type))
            }), t.fn[e] = f._jQueryInterface, t.fn[e].Constructor = f, t.fn[e].noConflict = function () {
                return t.fn[e] = a, f._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "carousel", s = "bs.carousel", a = "." + s, l = ".data-api", u = t.fn[e],
                c = {interval: 5e3, keyboard: !0, slide: !1, pause: "hover", wrap: !0}, f = {
                    interval: "(number|boolean)",
                    keyboard: "boolean",
                    slide: "(boolean|string)",
                    pause: "(string|boolean)",
                    wrap: "boolean"
                }, d = {NEXT: "next", PREVIOUS: "prev"}, p = {
                    SLIDE: "slide" + a,
                    SLID: "slid" + a,
                    KEYDOWN: "keydown" + a,
                    MOUSEENTER: "mouseenter" + a,
                    MOUSELEAVE: "mouseleave" + a,
                    LOAD_DATA_API: "load" + a + l,
                    CLICK_DATA_API: "click" + a + l
                }, h = {
                    CAROUSEL: "carousel",
                    ACTIVE: "active",
                    SLIDE: "slide",
                    RIGHT: "right",
                    LEFT: "left",
                    ITEM: "carousel-item"
                }, m = {
                    ACTIVE: ".active",
                    ACTIVE_ITEM: ".active.carousel-item",
                    ITEM: ".carousel-item",
                    NEXT_PREV: ".next, .prev",
                    INDICATORS: ".carousel-indicators",
                    DATA_SLIDE: "[data-slide], [data-slide-to]",
                    DATA_RIDE: '[data-ride="carousel"]'
                }, g = function () {
                    function l(e, i) {
                        n(this, l), this._items = null, this._interval = null, this._activeElement = null, this._isPaused = !1, this._isSliding = !1, this._config = this._getConfig(i), this._element = t(e)[0], this._indicatorsElement = t(this._element).find(m.INDICATORS)[0], this._addEventListeners()
                    }

                    return l.prototype.next = function () {
                        this._isSliding || this._slide(d.NEXT)
                    }, l.prototype.nextWhenVisible = function () {
                        document.hidden || this.next()
                    }, l.prototype.prev = function () {
                        this._isSliding || this._slide(d.PREVIOUS)
                    }, l.prototype.pause = function (e) {
                        e || (this._isPaused = !0), t(this._element).find(m.NEXT_PREV)[0] && o.supportsTransitionEnd() && (o.triggerTransitionEnd(this._element), this.cycle(!0)), clearInterval(this._interval), this._interval = null
                    }, l.prototype.cycle = function (e) {
                        e || (this._isPaused = !1), this._interval && (clearInterval(this._interval), this._interval = null), this._config.interval && !this._isPaused && (this._interval = setInterval(t.proxy(document.visibilityState ? this.nextWhenVisible : this.next, this), this._config.interval))
                    }, l.prototype.to = function (e) {
                        var n = this;
                        this._activeElement = t(this._element).find(m.ACTIVE_ITEM)[0];
                        var i = this._getItemIndex(this._activeElement);
                        if (!(e > this._items.length - 1 || e < 0)) {
                            if (this._isSliding) return void t(this._element).one(p.SLID, function () {
                                return n.to(e)
                            });
                            if (i === e) return this.pause(), void this.cycle();
                            var r = e > i ? d.NEXT : d.PREVIOUS;
                            this._slide(r, this._items[e])
                        }
                    }, l.prototype.dispose = function () {
                        t(this._element).off(a), t.removeData(this._element, s), this._items = null, this._config = null, this._element = null, this._interval = null, this._isPaused = null, this._isSliding = null, this._activeElement = null, this._indicatorsElement = null
                    }, l.prototype._getConfig = function (n) {
                        return n = t.extend({}, c, n), o.typeCheckConfig(e, n, f), n
                    }, l.prototype._addEventListeners = function () {
                        this._config.keyboard && t(this._element).on(p.KEYDOWN, t.proxy(this._keydown, this)), "hover" !== this._config.pause || "ontouchstart" in document.documentElement || t(this._element).on(p.MOUSEENTER, t.proxy(this.pause, this)).on(p.MOUSELEAVE, t.proxy(this.cycle, this))
                    }, l.prototype._keydown = function (t) {
                        if (t.preventDefault(), !/input|textarea/i.test(t.target.tagName)) switch (t.which) {
                            case 37:
                                this.prev();
                                break;
                            case 39:
                                this.next();
                                break;
                            default:
                                return
                        }
                    }, l.prototype._getItemIndex = function (e) {
                        return this._items = t.makeArray(t(e).parent().find(m.ITEM)), this._items.indexOf(e)
                    }, l.prototype._getItemByDirection = function (t, e) {
                        var n = t === d.NEXT, i = t === d.PREVIOUS, r = this._getItemIndex(e), o = this._items.length - 1;
                        if ((i && 0 === r || n && r === o) && !this._config.wrap) return e;
                        var s = t === d.PREVIOUS ? -1 : 1, a = (r + s) % this._items.length;
                        return -1 === a ? this._items[this._items.length - 1] : this._items[a]
                    }, l.prototype._triggerSlideEvent = function (e, n) {
                        var i = t.Event(p.SLIDE, {relatedTarget: e, direction: n});
                        return t(this._element).trigger(i), i
                    }, l.prototype._setActiveIndicatorElement = function (e) {
                        if (this._indicatorsElement) {
                            t(this._indicatorsElement).find(m.ACTIVE).removeClass(h.ACTIVE);
                            var n = this._indicatorsElement.children[this._getItemIndex(e)];
                            n && t(n).addClass(h.ACTIVE)
                        }
                    }, l.prototype._slide = function (e, n) {
                        var i = this, r = t(this._element).find(m.ACTIVE_ITEM)[0],
                            s = n || r && this._getItemByDirection(e, r), a = Boolean(this._interval),
                            l = e === d.NEXT ? h.LEFT : h.RIGHT;
                        if (s && t(s).hasClass(h.ACTIVE)) return void(this._isSliding = !1);
                        if (!this._triggerSlideEvent(s, l).isDefaultPrevented() && r && s) {
                            this._isSliding = !0, a && this.pause(), this._setActiveIndicatorElement(s);
                            var u = t.Event(p.SLID, {relatedTarget: s, direction: l});
                            o.supportsTransitionEnd() && t(this._element).hasClass(h.SLIDE) ? (t(s).addClass(e), o.reflow(s), t(r).addClass(l), t(s).addClass(l), t(r).one(o.TRANSITION_END, function () {
                                t(s).removeClass(l).removeClass(e), t(s).addClass(h.ACTIVE), t(r).removeClass(h.ACTIVE).removeClass(e).removeClass(l), i._isSliding = !1, setTimeout(function () {
                                    return t(i._element).trigger(u)
                                }, 0)
                            }).emulateTransitionEnd(600)) : (t(r).removeClass(h.ACTIVE), t(s).addClass(h.ACTIVE), this._isSliding = !1, t(this._element).trigger(u)), a && this.cycle()
                        }
                    }, l._jQueryInterface = function (e) {
                        return this.each(function () {
                            var n = t(this).data(s), r = t.extend({}, c, t(this).data());
                            "object" === (void 0 === e ? "undefined" : i(e)) && t.extend(r, e);
                            var o = "string" == typeof e ? e : r.slide;
                            if (n || (n = new l(this, r), t(this).data(s, n)), "number" == typeof e) n.to(e); else if ("string" == typeof o) {
                                if (void 0 === n[o]) throw new Error('No method named "' + o + '"');
                                n[o]()
                            } else r.interval && (n.pause(), n.cycle())
                        })
                    }, l._dataApiClickHandler = function (e) {
                        var n = o.getSelectorFromElement(this);
                        if (n) {
                            var i = t(n)[0];
                            if (i && t(i).hasClass(h.CAROUSEL)) {
                                var r = t.extend({}, t(i).data(), t(this).data()), a = this.getAttribute("data-slide-to");
                                a && (r.interval = !1), l._jQueryInterface.call(t(i), r), a && t(i).data(s).to(a), e.preventDefault()
                            }
                        }
                    }, r(l, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }, {
                        key: "Default", get: function () {
                            return c
                        }
                    }]), l
                }();
            t(document).on(p.CLICK_DATA_API, m.DATA_SLIDE, g._dataApiClickHandler), t(window).on(p.LOAD_DATA_API, function () {
                t(m.DATA_RIDE).each(function () {
                    var e = t(this);
                    g._jQueryInterface.call(e, e.data())
                })
            }), t.fn[e] = g._jQueryInterface, t.fn[e].Constructor = g, t.fn[e].noConflict = function () {
                return t.fn[e] = u, g._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "collapse", s = "bs.collapse", a = "." + s, l = t.fn[e], u = {toggle: !0, parent: ""},
                c = {toggle: "boolean", parent: "string"}, f = {
                    SHOW: "show" + a,
                    SHOWN: "shown" + a,
                    HIDE: "hide" + a,
                    HIDDEN: "hidden" + a,
                    CLICK_DATA_API: "click" + a + ".data-api"
                }, d = {IN: "in", COLLAPSE: "collapse", COLLAPSING: "collapsing", COLLAPSED: "collapsed"},
                p = {WIDTH: "width", HEIGHT: "height"},
                h = {ACTIVES: ".card > .in, .card > .collapsing", DATA_TOGGLE: '[data-toggle="collapse"]'},
                m = function () {
                    function a(e, i) {
                        n(this, a), this._isTransitioning = !1, this._element = e, this._config = this._getConfig(i), this._triggerArray = t.makeArray(t('[data-toggle="collapse"][href="#' + e.id + '"],[data-toggle="collapse"][data-target="#' + e.id + '"]')), this._parent = this._config.parent ? this._getParent() : null, this._config.parent || this._addAriaAndCollapsedClass(this._element, this._triggerArray), this._config.toggle && this.toggle()
                    }

                    return a.prototype.toggle = function () {
                        t(this._element).hasClass(d.IN) ? this.hide() : this.show()
                    }, a.prototype.show = function () {
                        var e = this;
                        if (!this._isTransitioning && !t(this._element).hasClass(d.IN)) {
                            var n = void 0, i = void 0;
                            if (this._parent && (n = t.makeArray(t(h.ACTIVES)), n.length || (n = null)), !(n && (i = t(n).data(s)) && i._isTransitioning)) {
                                var r = t.Event(f.SHOW);
                                if (t(this._element).trigger(r), !r.isDefaultPrevented()) {
                                    n && (a._jQueryInterface.call(t(n), "hide"), i || t(n).data(s, null));
                                    var l = this._getDimension();
                                    t(this._element).removeClass(d.COLLAPSE).addClass(d.COLLAPSING), this._element.style[l] = 0, this._element.setAttribute("aria-expanded", !0), this._triggerArray.length && t(this._triggerArray).removeClass(d.COLLAPSED).attr("aria-expanded", !0), this.setTransitioning(!0);
                                    var u = function () {
                                        t(e._element).removeClass(d.COLLAPSING).addClass(d.COLLAPSE).addClass(d.IN), e._element.style[l] = "", e.setTransitioning(!1), t(e._element).trigger(f.SHOWN)
                                    };
                                    if (!o.supportsTransitionEnd()) return void u();
                                    var c = l[0].toUpperCase() + l.slice(1), p = "scroll" + c;
                                    t(this._element).one(o.TRANSITION_END, u).emulateTransitionEnd(600), this._element.style[l] = this._element[p] + "px"
                                }
                            }
                        }
                    }, a.prototype.hide = function () {
                        var e = this;
                        if (!this._isTransitioning && t(this._element).hasClass(d.IN)) {
                            var n = t.Event(f.HIDE);
                            if (t(this._element).trigger(n), !n.isDefaultPrevented()) {
                                var i = this._getDimension(), r = i === p.WIDTH ? "offsetWidth" : "offsetHeight";
                                this._element.style[i] = this._element[r] + "px", o.reflow(this._element), t(this._element).addClass(d.COLLAPSING).removeClass(d.COLLAPSE).removeClass(d.IN), this._element.setAttribute("aria-expanded", !1), this._triggerArray.length && t(this._triggerArray).addClass(d.COLLAPSED).attr("aria-expanded", !1), this.setTransitioning(!0);
                                var s = function () {
                                    e.setTransitioning(!1), t(e._element).removeClass(d.COLLAPSING).addClass(d.COLLAPSE).trigger(f.HIDDEN)
                                };
                                return this._element.style[i] = "", o.supportsTransitionEnd() ? void t(this._element).one(o.TRANSITION_END, s).emulateTransitionEnd(600) : void s()
                            }
                        }
                    }, a.prototype.setTransitioning = function (t) {
                        this._isTransitioning = t
                    }, a.prototype.dispose = function () {
                        t.removeData(this._element, s), this._config = null, this._parent = null, this._element = null, this._triggerArray = null, this._isTransitioning = null
                    }, a.prototype._getConfig = function (n) {
                        return n = t.extend({}, u, n), n.toggle = Boolean(n.toggle), o.typeCheckConfig(e, n, c), n
                    }, a.prototype._getDimension = function () {
                        return t(this._element).hasClass(p.WIDTH) ? p.WIDTH : p.HEIGHT
                    }, a.prototype._getParent = function () {
                        var e = this, n = t(this._config.parent)[0],
                            i = '[data-toggle="collapse"][data-parent="' + this._config.parent + '"]';
                        return t(n).find(i).each(function (t, n) {
                            e._addAriaAndCollapsedClass(a._getTargetFromElement(n), [n])
                        }), n
                    }, a.prototype._addAriaAndCollapsedClass = function (e, n) {
                        if (e) {
                            var i = t(e).hasClass(d.IN);
                            e.setAttribute("aria-expanded", i), n.length && t(n).toggleClass(d.COLLAPSED, !i).attr("aria-expanded", i)
                        }
                    }, a._getTargetFromElement = function (e) {
                        var n = o.getSelectorFromElement(e);
                        return n ? t(n)[0] : null
                    }, a._jQueryInterface = function (e) {
                        return this.each(function () {
                            var n = t(this), r = n.data(s),
                                o = t.extend({}, u, n.data(), "object" === (void 0 === e ? "undefined" : i(e)) && e);
                            if (!r && o.toggle && /show|hide/.test(e) && (o.toggle = !1), r || (r = new a(this, o), n.data(s, r)), "string" == typeof e) {
                                if (void 0 === r[e]) throw new Error('No method named "' + e + '"');
                                r[e]()
                            }
                        })
                    }, r(a, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }, {
                        key: "Default", get: function () {
                            return u
                        }
                    }]), a
                }();
            t(document).on(f.CLICK_DATA_API, h.DATA_TOGGLE, function (e) {
                e.preventDefault();
                var n = m._getTargetFromElement(this), i = t(n).data(s), r = i ? "toggle" : t(this).data();
                m._jQueryInterface.call(t(n), r)
            }), t.fn[e] = m._jQueryInterface, t.fn[e].Constructor = m, t.fn[e].noConflict = function () {
                return t.fn[e] = l, m._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "dropdown", i = "bs.dropdown", s = "." + i, a = ".data-api", l = t.fn[e], u = {
                HIDE: "hide" + s,
                HIDDEN: "hidden" + s,
                SHOW: "show" + s,
                SHOWN: "shown" + s,
                CLICK: "click" + s,
                CLICK_DATA_API: "click" + s + a,
                KEYDOWN_DATA_API: "keydown" + s + a
            }, c = {BACKDROP: "dropdown-backdrop", DISABLED: "disabled", OPEN: "open"}, f = {
                BACKDROP: ".dropdown-backdrop",
                DATA_TOGGLE: '[data-toggle="dropdown"]',
                FORM_CHILD: ".dropdown form",
                ROLE_MENU: '[role="menu"]',
                ROLE_LISTBOX: '[role="listbox"]',
                NAVBAR_NAV: ".navbar-nav",
                VISIBLE_ITEMS: '[role="menu"] li:not(.disabled) a, [role="listbox"] li:not(.disabled) a'
            }, d = function () {
                function e(t) {
                    n(this, e), this._element = t, this._addEventListeners()
                }

                return e.prototype.toggle = function () {
                    if (this.disabled || t(this).hasClass(c.DISABLED)) return !1;
                    var n = e._getParentFromElement(this), i = t(n).hasClass(c.OPEN);
                    if (e._clearMenus(), i) return !1;
                    if ("ontouchstart" in document.documentElement && !t(n).closest(f.NAVBAR_NAV).length) {
                        var r = document.createElement("div");
                        r.className = c.BACKDROP, t(r).insertBefore(this), t(r).on("click", e._clearMenus)
                    }
                    var o = {relatedTarget: this}, s = t.Event(u.SHOW, o);
                    return t(n).trigger(s), !s.isDefaultPrevented() && (this.focus(), this.setAttribute("aria-expanded", "true"), t(n).toggleClass(c.OPEN), t(n).trigger(t.Event(u.SHOWN, o)), !1)
                }, e.prototype.dispose = function () {
                    t.removeData(this._element, i), t(this._element).off(s), this._element = null
                }, e.prototype._addEventListeners = function () {
                    t(this._element).on(u.CLICK, this.toggle)
                }, e._jQueryInterface = function (n) {
                    return this.each(function () {
                        var r = t(this).data(i);
                        if (r || t(this).data(i, r = new e(this)), "string" == typeof n) {
                            if (void 0 === r[n]) throw new Error('No method named "' + n + '"');
                            r[n].call(this)
                        }
                    })
                }, e._clearMenus = function (n) {
                    if (!n || 3 !== n.which) {
                        var i = t(f.BACKDROP)[0];
                        i && i.parentNode.removeChild(i);
                        for (var r = t.makeArray(t(f.DATA_TOGGLE)), o = 0; o < r.length; o++) {
                            var s = e._getParentFromElement(r[o]), a = {relatedTarget: r[o]};
                            if (t(s).hasClass(c.OPEN) && !(n && "click" === n.type && /input|textarea/i.test(n.target.tagName) && t.contains(s, n.target))) {
                                var l = t.Event(u.HIDE, a);
                                t(s).trigger(l), l.isDefaultPrevented() || (r[o].setAttribute("aria-expanded", "false"), t(s).removeClass(c.OPEN).trigger(t.Event(u.HIDDEN, a)))
                            }
                        }
                    }
                }, e._getParentFromElement = function (e) {
                    var n = void 0, i = o.getSelectorFromElement(e);
                    return i && (n = t(i)[0]), n || e.parentNode
                }, e._dataApiKeydownHandler = function (n) {
                    if (/(38|40|27|32)/.test(n.which) && !/input|textarea/i.test(n.target.tagName) && (n.preventDefault(), n.stopPropagation(), !this.disabled && !t(this).hasClass(c.DISABLED))) {
                        var i = e._getParentFromElement(this), r = t(i).hasClass(c.OPEN);
                        if (!r && 27 !== n.which || r && 27 === n.which) {
                            if (27 === n.which) {
                                var o = t(i).find(f.DATA_TOGGLE)[0];
                                t(o).trigger("focus")
                            }
                            return void t(this).trigger("click")
                        }
                        var s = t.makeArray(t(f.VISIBLE_ITEMS));
                        if (s = s.filter(function (t) {
                                return t.offsetWidth || t.offsetHeight
                            }), s.length) {
                            var a = s.indexOf(n.target);
                            38 === n.which && a > 0 && a--, 40 === n.which && a < s.length - 1 && a++, a < 0 && (a = 0), s[a].focus()
                        }
                    }
                }, r(e, null, [{
                    key: "VERSION", get: function () {
                        return "4.0.0-alpha.5"
                    }
                }]), e
            }();
            t(document).on(u.KEYDOWN_DATA_API, f.DATA_TOGGLE, d._dataApiKeydownHandler).on(u.KEYDOWN_DATA_API, f.ROLE_MENU, d._dataApiKeydownHandler).on(u.KEYDOWN_DATA_API, f.ROLE_LISTBOX, d._dataApiKeydownHandler).on(u.CLICK_DATA_API, d._clearMenus).on(u.CLICK_DATA_API, f.DATA_TOGGLE, d.prototype.toggle).on(u.CLICK_DATA_API, f.FORM_CHILD, function (t) {
                t.stopPropagation()
            }), t.fn[e] = d._jQueryInterface, t.fn[e].Constructor = d, t.fn[e].noConflict = function () {
                return t.fn[e] = l, d._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "modal", s = "bs.modal", a = "." + s, l = t.fn[e],
                u = {backdrop: !0, keyboard: !0, focus: !0, show: !0},
                c = {backdrop: "(boolean|string)", keyboard: "boolean", focus: "boolean", show: "boolean"}, f = {
                    HIDE: "hide" + a,
                    HIDDEN: "hidden" + a,
                    SHOW: "show" + a,
                    SHOWN: "shown" + a,
                    FOCUSIN: "focusin" + a,
                    RESIZE: "resize" + a,
                    CLICK_DISMISS: "click.dismiss" + a,
                    KEYDOWN_DISMISS: "keydown.dismiss" + a,
                    MOUSEUP_DISMISS: "mouseup.dismiss" + a,
                    MOUSEDOWN_DISMISS: "mousedown.dismiss" + a,
                    CLICK_DATA_API: "click" + a + ".data-api"
                }, d = {
                    SCROLLBAR_MEASURER: "modal-scrollbar-measure",
                    BACKDROP: "modal-backdrop",
                    OPEN: "modal-open",
                    FADE: "fade",
                    IN: "in"
                }, p = {
                    DIALOG: ".modal-dialog",
                    DATA_TOGGLE: '[data-toggle="modal"]',
                    DATA_DISMISS: '[data-dismiss="modal"]',
                    FIXED_CONTENT: ".navbar-fixed-top, .navbar-fixed-bottom, .is-fixed"
                }, h = function () {
                    function l(e, i) {
                        n(this, l), this._config = this._getConfig(i), this._element = e, this._dialog = t(e).find(p.DIALOG)[0], this._backdrop = null, this._isShown = !1, this._isBodyOverflowing = !1, this._ignoreBackdropClick = !1, this._originalBodyPadding = 0, this._scrollbarWidth = 0
                    }

                    return l.prototype.toggle = function (t) {
                        return this._isShown ? this.hide() : this.show(t)
                    }, l.prototype.show = function (e) {
                        var n = this, i = t.Event(f.SHOW, {relatedTarget: e});
                        t(this._element).trigger(i), this._isShown || i.isDefaultPrevented() || (this._isShown = !0, this._checkScrollbar(), this._setScrollbar(), t(document.body).addClass(d.OPEN), this._setEscapeEvent(), this._setResizeEvent(), t(this._element).on(f.CLICK_DISMISS, p.DATA_DISMISS, t.proxy(this.hide, this)), t(this._dialog).on(f.MOUSEDOWN_DISMISS, function () {
                            t(n._element).one(f.MOUSEUP_DISMISS, function (e) {
                                t(e.target).is(n._element) && (n._ignoreBackdropClick = !0)
                            })
                        }), this._showBackdrop(t.proxy(this._showElement, this, e)))
                    }, l.prototype.hide = function (e) {
                        e && e.preventDefault();
                        var n = t.Event(f.HIDE);
                        t(this._element).trigger(n), this._isShown && !n.isDefaultPrevented() && (this._isShown = !1, this._setEscapeEvent(), this._setResizeEvent(), t(document).off(f.FOCUSIN), t(this._element).removeClass(d.IN), t(this._element).off(f.CLICK_DISMISS), t(this._dialog).off(f.MOUSEDOWN_DISMISS), o.supportsTransitionEnd() && t(this._element).hasClass(d.FADE) ? t(this._element).one(o.TRANSITION_END, t.proxy(this._hideModal, this)).emulateTransitionEnd(300) : this._hideModal())
                    }, l.prototype.dispose = function () {
                        t.removeData(this._element, s), t(window).off(a), t(document).off(a), t(this._element).off(a), t(this._backdrop).off(a), this._config = null, this._element = null, this._dialog = null, this._backdrop = null, this._isShown = null, this._isBodyOverflowing = null, this._ignoreBackdropClick = null, this._originalBodyPadding = null, this._scrollbarWidth = null
                    }, l.prototype._getConfig = function (n) {
                        return n = t.extend({}, u, n), o.typeCheckConfig(e, n, c), n
                    }, l.prototype._showElement = function (e) {
                        var n = this, i = o.supportsTransitionEnd() && t(this._element).hasClass(d.FADE);
                        this._element.parentNode && this._element.parentNode.nodeType === Node.ELEMENT_NODE || document.body.appendChild(this._element), this._element.style.display = "block", this._element.removeAttribute("aria-hidden"), this._element.scrollTop = 0, i && o.reflow(this._element), t(this._element).addClass(d.IN), this._config.focus && this._enforceFocus();
                        var r = t.Event(f.SHOWN, {relatedTarget: e}), s = function () {
                            n._config.focus && n._element.focus(), t(n._element).trigger(r)
                        };
                        i ? t(this._dialog).one(o.TRANSITION_END, s).emulateTransitionEnd(300) : s()
                    }, l.prototype._enforceFocus = function () {
                        var e = this;
                        t(document).off(f.FOCUSIN).on(f.FOCUSIN, function (n) {
                            document === n.target || e._element === n.target || t(e._element).has(n.target).length || e._element.focus()
                        })
                    }, l.prototype._setEscapeEvent = function () {
                        var e = this;
                        this._isShown && this._config.keyboard ? t(this._element).on(f.KEYDOWN_DISMISS, function (t) {
                            27 === t.which && e.hide()
                        }) : this._isShown || t(this._element).off(f.KEYDOWN_DISMISS)
                    }, l.prototype._setResizeEvent = function () {
                        this._isShown ? t(window).on(f.RESIZE, t.proxy(this._handleUpdate, this)) : t(window).off(f.RESIZE)
                    }, l.prototype._hideModal = function () {
                        var e = this;
                        this._element.style.display = "none", this._element.setAttribute("aria-hidden", "true"), this._showBackdrop(function () {
                            t(document.body).removeClass(d.OPEN), e._resetAdjustments(), e._resetScrollbar(), t(e._element).trigger(f.HIDDEN)
                        })
                    }, l.prototype._removeBackdrop = function () {
                        this._backdrop && (t(this._backdrop).remove(), this._backdrop = null)
                    }, l.prototype._showBackdrop = function (e) {
                        var n = this, i = t(this._element).hasClass(d.FADE) ? d.FADE : "";
                        if (this._isShown && this._config.backdrop) {
                            var r = o.supportsTransitionEnd() && i;
                            if (this._backdrop = document.createElement("div"), this._backdrop.className = d.BACKDROP, i && t(this._backdrop).addClass(i), t(this._backdrop).appendTo(document.body), t(this._element).on(f.CLICK_DISMISS, function (t) {
                                    return n._ignoreBackdropClick ? void(n._ignoreBackdropClick = !1) : void(t.target === t.currentTarget && ("static" === n._config.backdrop ? n._element.focus() : n.hide()))
                                }), r && o.reflow(this._backdrop), t(this._backdrop).addClass(d.IN), !e) return;
                            if (!r) return void e();
                            t(this._backdrop).one(o.TRANSITION_END, e).emulateTransitionEnd(150)
                        } else if (!this._isShown && this._backdrop) {
                            t(this._backdrop).removeClass(d.IN);
                            var s = function () {
                                n._removeBackdrop(), e && e()
                            };
                            o.supportsTransitionEnd() && t(this._element).hasClass(d.FADE) ? t(this._backdrop).one(o.TRANSITION_END, s).emulateTransitionEnd(150) : s()
                        } else e && e()
                    }, l.prototype._handleUpdate = function () {
                        this._adjustDialog()
                    }, l.prototype._adjustDialog = function () {
                        var t = this._element.scrollHeight > document.documentElement.clientHeight;
                        !this._isBodyOverflowing && t && (this._element.style.paddingLeft = this._scrollbarWidth + "px"), this._isBodyOverflowing && !t && (this._element.style.paddingRight = this._scrollbarWidth + "px")
                    }, l.prototype._resetAdjustments = function () {
                        this._element.style.paddingLeft = "", this._element.style.paddingRight = ""
                    }, l.prototype._checkScrollbar = function () {
                        this._isBodyOverflowing = document.body.clientWidth < window.innerWidth, this._scrollbarWidth = this._getScrollbarWidth()
                    }, l.prototype._setScrollbar = function () {
                        var e = parseInt(t(p.FIXED_CONTENT).css("padding-right") || 0, 10);
                        this._originalBodyPadding = document.body.style.paddingRight || "", this._isBodyOverflowing && (document.body.style.paddingRight = e + this._scrollbarWidth + "px")
                    }, l.prototype._resetScrollbar = function () {
                        document.body.style.paddingRight = this._originalBodyPadding
                    }, l.prototype._getScrollbarWidth = function () {
                        var t = document.createElement("div");
                        t.className = d.SCROLLBAR_MEASURER, document.body.appendChild(t);
                        var e = t.offsetWidth - t.clientWidth;
                        return document.body.removeChild(t), e
                    }, l._jQueryInterface = function (e, n) {
                        return this.each(function () {
                            var r = t(this).data(s),
                                o = t.extend({}, l.Default, t(this).data(), "object" === (void 0 === e ? "undefined" : i(e)) && e);
                            if (r || (r = new l(this, o), t(this).data(s, r)), "string" == typeof e) {
                                if (void 0 === r[e]) throw new Error('No method named "' + e + '"');
                                r[e](n)
                            } else o.show && r.show(n)
                        })
                    }, r(l, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }, {
                        key: "Default", get: function () {
                            return u
                        }
                    }]), l
                }();
            t(document).on(f.CLICK_DATA_API, p.DATA_TOGGLE, function (e) {
                var n = this, i = void 0, r = o.getSelectorFromElement(this);
                r && (i = t(r)[0]);
                var a = t(i).data(s) ? "toggle" : t.extend({}, t(i).data(), t(this).data());
                "A" === this.tagName && e.preventDefault();
                var l = t(i).one(f.SHOW, function (e) {
                    e.isDefaultPrevented() || l.one(f.HIDDEN, function () {
                        t(n).is(":visible") && n.focus()
                    })
                });
                h._jQueryInterface.call(t(i), a, this)
            }), t.fn[e] = h._jQueryInterface, t.fn[e].Constructor = h, t.fn[e].noConflict = function () {
                return t.fn[e] = l, h._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "scrollspy", s = "bs.scrollspy", a = "." + s, l = t.fn[e],
                u = {offset: 10, method: "auto", target: ""},
                c = {offset: "number", method: "string", target: "(string|element)"},
                f = {ACTIVATE: "activate" + a, SCROLL: "scroll" + a, LOAD_DATA_API: "load" + a + ".data-api"}, d = {
                    DROPDOWN_ITEM: "dropdown-item",
                    DROPDOWN_MENU: "dropdown-menu",
                    NAV_LINK: "nav-link",
                    NAV: "nav",
                    ACTIVE: "active"
                }, p = {
                    DATA_SPY: '[data-spy="scroll"]',
                    ACTIVE: ".active",
                    LIST_ITEM: ".list-item",
                    LI: "li",
                    LI_DROPDOWN: "li.dropdown",
                    NAV_LINKS: ".nav-link",
                    DROPDOWN: ".dropdown",
                    DROPDOWN_ITEMS: ".dropdown-item",
                    DROPDOWN_TOGGLE: ".dropdown-toggle"
                }, h = {OFFSET: "offset", POSITION: "position"}, m = function () {
                    function l(e, i) {
                        n(this, l), this._element = e, this._scrollElement = "BODY" === e.tagName ? window : e, this._config = this._getConfig(i), this._selector = this._config.target + " " + p.NAV_LINKS + "," + this._config.target + " " + p.DROPDOWN_ITEMS, this._offsets = [], this._targets = [], this._activeTarget = null, this._scrollHeight = 0, t(this._scrollElement).on(f.SCROLL, t.proxy(this._process, this)), this.refresh(), this._process()
                    }

                    return l.prototype.refresh = function () {
                        var e = this, n = this._scrollElement !== this._scrollElement.window ? h.POSITION : h.OFFSET,
                            i = "auto" === this._config.method ? n : this._config.method,
                            r = i === h.POSITION ? this._getScrollTop() : 0;
                        this._offsets = [], this._targets = [], this._scrollHeight = this._getScrollHeight(), t.makeArray(t(this._selector)).map(function (e) {
                            var n = void 0, s = o.getSelectorFromElement(e);
                            return s && (n = t(s)[0]), n && (n.offsetWidth || n.offsetHeight) ? [t(n)[i]().top + r, s] : null
                        }).filter(function (t) {
                            return t
                        }).sort(function (t, e) {
                            return t[0] - e[0]
                        }).forEach(function (t) {
                            e._offsets.push(t[0]), e._targets.push(t[1])
                        })
                    }, l.prototype.dispose = function () {
                        t.removeData(this._element, s), t(this._scrollElement).off(a), this._element = null, this._scrollElement = null, this._config = null, this._selector = null, this._offsets = null, this._targets = null, this._activeTarget = null, this._scrollHeight = null
                    }, l.prototype._getConfig = function (n) {
                        if (n = t.extend({}, u, n), "string" != typeof n.target) {
                            var i = t(n.target).attr("id");
                            i || (i = o.getUID(e), t(n.target).attr("id", i)), n.target = "#" + i
                        }
                        return o.typeCheckConfig(e, n, c), n
                    }, l.prototype._getScrollTop = function () {
                        return this._scrollElement === window ? this._scrollElement.scrollY : this._scrollElement.scrollTop
                    }, l.prototype._getScrollHeight = function () {
                        return this._scrollElement.scrollHeight || Math.max(document.body.scrollHeight, document.documentElement.scrollHeight)
                    }, l.prototype._process = function () {
                        var t = this._getScrollTop() + this._config.offset, e = this._getScrollHeight(),
                            n = this._config.offset + e - this._scrollElement.offsetHeight;
                        if (this._scrollHeight !== e && this.refresh(), t >= n) {
                            var i = this._targets[this._targets.length - 1];
                            this._activeTarget !== i && this._activate(i)
                        }
                        if (this._activeTarget && t < this._offsets[0]) return this._activeTarget = null, void this._clear();
                        for (var r = this._offsets.length; r--;) {
                            this._activeTarget !== this._targets[r] && t >= this._offsets[r] && (void 0 === this._offsets[r + 1] || t < this._offsets[r + 1]) && this._activate(this._targets[r])
                        }
                    }, l.prototype._activate = function (e) {
                        this._activeTarget = e, this._clear();
                        var n = this._selector.split(",");
                        n = n.map(function (t) {
                            return t + '[data-target="' + e + '"],' + t + '[href="' + e + '"]'
                        });
                        var i = t(n.join(","));
                        i.hasClass(d.DROPDOWN_ITEM) ? (i.closest(p.DROPDOWN).find(p.DROPDOWN_TOGGLE).addClass(d.ACTIVE), i.addClass(d.ACTIVE)) : i.parents(p.LI).find(p.NAV_LINKS).addClass(d.ACTIVE), t(this._scrollElement).trigger(f.ACTIVATE, {relatedTarget: e})
                    }, l.prototype._clear = function () {
                        t(this._selector).filter(p.ACTIVE).removeClass(d.ACTIVE)
                    }, l._jQueryInterface = function (e) {
                        return this.each(function () {
                            var n = t(this).data(s), r = "object" === (void 0 === e ? "undefined" : i(e)) && e || null;
                            if (n || (n = new l(this, r), t(this).data(s, n)), "string" == typeof e) {
                                if (void 0 === n[e]) throw new Error('No method named "' + e + '"');
                                n[e]()
                            }
                        })
                    }, r(l, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }, {
                        key: "Default", get: function () {
                            return u
                        }
                    }]), l
                }();
            t(window).on(f.LOAD_DATA_API, function () {
                for (var e = t.makeArray(t(p.DATA_SPY)), n = e.length; n--;) {
                    var i = t(e[n]);
                    m._jQueryInterface.call(i, i.data())
                }
            }), t.fn[e] = m._jQueryInterface, t.fn[e].Constructor = m, t.fn[e].noConflict = function () {
                return t.fn[e] = l, m._jQueryInterface
            }
        }(jQuery), function (t) {
            var e = "tab", i = "bs.tab", s = "." + i, a = t.fn[e], l = {
                HIDE: "hide" + s,
                HIDDEN: "hidden" + s,
                SHOW: "show" + s,
                SHOWN: "shown" + s,
                CLICK_DATA_API: "click" + s + ".data-api"
            }, u = {DROPDOWN_MENU: "dropdown-menu", ACTIVE: "active", FADE: "fade", IN: "in"}, c = {
                A: "a",
                LI: "li",
                DROPDOWN: ".dropdown",
                UL: "ul:not(.dropdown-menu)",
                FADE_CHILD: "> .nav-item .fade, > .fade",
                ACTIVE: ".active",
                ACTIVE_CHILD: "> .nav-item > .active, > .active",
                DATA_TOGGLE: '[data-toggle="tab"], [data-toggle="pill"]',
                DROPDOWN_TOGGLE: ".dropdown-toggle",
                DROPDOWN_ACTIVE_CHILD: "> .dropdown-menu .active"
            }, f = function () {
                function e(t) {
                    n(this, e), this._element = t
                }

                return e.prototype.show = function () {
                    var e = this;
                    if (!this._element.parentNode || this._element.parentNode.nodeType !== Node.ELEMENT_NODE || !t(this._element).hasClass(u.ACTIVE)) {
                        var n = void 0, i = void 0, r = t(this._element).closest(c.UL)[0],
                            s = o.getSelectorFromElement(this._element);
                        r && (i = t.makeArray(t(r).find(c.ACTIVE)), i = i[i.length - 1]);
                        var a = t.Event(l.HIDE, {relatedTarget: this._element}),
                            f = t.Event(l.SHOW, {relatedTarget: i});
                        if (i && t(i).trigger(a), t(this._element).trigger(f), !f.isDefaultPrevented() && !a.isDefaultPrevented()) {
                            s && (n = t(s)[0]), this._activate(this._element, r);
                            var d = function () {
                                var n = t.Event(l.HIDDEN, {relatedTarget: e._element}),
                                    r = t.Event(l.SHOWN, {relatedTarget: i});
                                t(i).trigger(n), t(e._element).trigger(r)
                            };
                            n ? this._activate(n, n.parentNode, d) : d()
                        }
                    }
                }, e.prototype.dispose = function () {
                    t.removeClass(this._element, i), this._element = null
                }, e.prototype._activate = function (e, n, i) {
                    var r = t(n).find(c.ACTIVE_CHILD)[0],
                        s = i && o.supportsTransitionEnd() && (r && t(r).hasClass(u.FADE) || Boolean(t(n).find(c.FADE_CHILD)[0])),
                        a = t.proxy(this._transitionComplete, this, e, r, s, i);
                    r && s ? t(r).one(o.TRANSITION_END, a).emulateTransitionEnd(150) : a(), r && t(r).removeClass(u.IN)
                }, e.prototype._transitionComplete = function (e, n, i, r) {
                    if (n) {
                        t(n).removeClass(u.ACTIVE);
                        var s = t(n).find(c.DROPDOWN_ACTIVE_CHILD)[0];
                        s && t(s).removeClass(u.ACTIVE), n.setAttribute("aria-expanded", !1)
                    }
                    if (t(e).addClass(u.ACTIVE), e.setAttribute("aria-expanded", !0), i ? (o.reflow(e), t(e).addClass(u.IN)) : t(e).removeClass(u.FADE), e.parentNode && t(e.parentNode).hasClass(u.DROPDOWN_MENU)) {
                        var a = t(e).closest(c.DROPDOWN)[0];
                        a && t(a).find(c.DROPDOWN_TOGGLE).addClass(u.ACTIVE), e.setAttribute("aria-expanded", !0)
                    }
                    r && r()
                }, e._jQueryInterface = function (n) {
                    return this.each(function () {
                        var r = t(this), o = r.data(i);
                        if (o || (o = o = new e(this), r.data(i, o)), "string" == typeof n) {
                            if (void 0 === o[n]) throw new Error('No method named "' + n + '"');
                            o[n]()
                        }
                    })
                }, r(e, null, [{
                    key: "VERSION", get: function () {
                        return "4.0.0-alpha.5"
                    }
                }]), e
            }();
            t(document).on(l.CLICK_DATA_API, c.DATA_TOGGLE, function (e) {
                e.preventDefault(), f._jQueryInterface.call(t(this), "show")
            }), t.fn[e] = f._jQueryInterface, t.fn[e].Constructor = f, t.fn[e].noConflict = function () {
                return t.fn[e] = a, f._jQueryInterface
            }
        }(jQuery), function (t) {
            if (void 0 === window.Tether) throw new Error("Bootstrap tooltips require Tether (http://tether.io/)");
            var e = "tooltip", s = "bs.tooltip", a = "." + s, l = t.fn[e], u = {
                    animation: !0,
                    template: '<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>',
                    trigger: "hover focus",
                    title: "",
                    delay: 0,
                    html: !1,
                    selector: !1,
                    placement: "top",
                    offset: "0 0",
                    constraints: []
                }, c = {
                    animation: "boolean",
                    template: "string",
                    title: "(string|element|function)",
                    trigger: "string",
                    delay: "(number|object)",
                    html: "boolean",
                    selector: "(string|boolean)",
                    placement: "(string|function)",
                    offset: "string",
                    constraints: "array"
                }, f = {TOP: "bottom center", RIGHT: "middle left", BOTTOM: "top center", LEFT: "middle right"},
                d = {IN: "in", OUT: "out"}, p = {
                    HIDE: "hide" + a,
                    HIDDEN: "hidden" + a,
                    SHOW: "show" + a,
                    SHOWN: "shown" + a,
                    INSERTED: "inserted" + a,
                    CLICK: "click" + a,
                    FOCUSIN: "focusin" + a,
                    FOCUSOUT: "focusout" + a,
                    MOUSEENTER: "mouseenter" + a,
                    MOUSELEAVE: "mouseleave" + a
                }, h = {FADE: "fade", IN: "in"}, m = {TOOLTIP: ".tooltip", TOOLTIP_INNER: ".tooltip-inner"},
                g = {element: !1, enabled: !1}, v = {HOVER: "hover", FOCUS: "focus", CLICK: "click", MANUAL: "manual"},
                y = function () {
                    function l(t, e) {
                        n(this, l), this._isEnabled = !0, this._timeout = 0, this._hoverState = "", this._activeTrigger = {}, this._tether = null, this.element = t, this.config = this._getConfig(e), this.tip = null, this._setListeners()
                    }

                    return l.prototype.enable = function () {
                        this._isEnabled = !0
                    }, l.prototype.disable = function () {
                        this._isEnabled = !1
                    }, l.prototype.toggleEnabled = function () {
                        this._isEnabled = !this._isEnabled
                    }, l.prototype.toggle = function (e) {
                        if (e) {
                            var n = this.constructor.DATA_KEY, i = t(e.currentTarget).data(n);
                            i || (i = new this.constructor(e.currentTarget, this._getDelegateConfig()), t(e.currentTarget).data(n, i)), i._activeTrigger.click = !i._activeTrigger.click, i._isWithActiveTrigger() ? i._enter(null, i) : i._leave(null, i)
                        } else {
                            if (t(this.getTipElement()).hasClass(h.IN)) return void this._leave(null, this);
                            this._enter(null, this)
                        }
                    }, l.prototype.dispose = function () {
                        clearTimeout(this._timeout), this.cleanupTether(), t.removeData(this.element, this.constructor.DATA_KEY), t(this.element).off(this.constructor.EVENT_KEY), this.tip && t(this.tip).remove(), this._isEnabled = null, this._timeout = null, this._hoverState = null, this._activeTrigger = null, this._tether = null, this.element = null, this.config = null, this.tip = null
                    }, l.prototype.show = function () {
                        var e = this, n = t.Event(this.constructor.Event.SHOW);
                        if (this.isWithContent() && this._isEnabled) {
                            t(this.element).trigger(n);
                            var i = t.contains(this.element.ownerDocument.documentElement, this.element);
                            if (n.isDefaultPrevented() || !i) return;
                            var r = this.getTipElement(), s = o.getUID(this.constructor.NAME);
                            r.setAttribute("id", s), this.element.setAttribute("aria-describedby", s), this.setContent(), this.config.animation && t(r).addClass(h.FADE);
                            var a = "function" == typeof this.config.placement ? this.config.placement.call(this, r, this.element) : this.config.placement,
                                u = this._getAttachment(a);
                            t(r).data(this.constructor.DATA_KEY, this).appendTo(document.body), t(this.element).trigger(this.constructor.Event.INSERTED), this._tether = new Tether({
                                attachment: u,
                                element: r,
                                target: this.element,
                                classes: g,
                                classPrefix: "bs-tether",
                                offset: this.config.offset,
                                constraints: this.config.constraints,
                                addTargetClasses: !1
                            }), o.reflow(r), this._tether.position(), t(r).addClass(h.IN);
                            var c = function () {
                                var n = e._hoverState;
                                e._hoverState = null, t(e.element).trigger(e.constructor.Event.SHOWN), n === d.OUT && e._leave(null, e)
                            };
                            if (o.supportsTransitionEnd() && t(this.tip).hasClass(h.FADE)) return void t(this.tip).one(o.TRANSITION_END, c).emulateTransitionEnd(l._TRANSITION_DURATION);
                            c()
                        }
                    }, l.prototype.hide = function (e) {
                        var n = this, i = this.getTipElement(), r = t.Event(this.constructor.Event.HIDE),
                            s = function () {
                                n._hoverState !== d.IN && i.parentNode && i.parentNode.removeChild(i), n.element.removeAttribute("aria-describedby"), t(n.element).trigger(n.constructor.Event.HIDDEN), n.cleanupTether(), e && e()
                            };
                        t(this.element).trigger(r), r.isDefaultPrevented() || (t(i).removeClass(h.IN), o.supportsTransitionEnd() && t(this.tip).hasClass(h.FADE) ? t(i).one(o.TRANSITION_END, s).emulateTransitionEnd(150) : s(), this._hoverState = "")
                    }, l.prototype.isWithContent = function () {
                        return Boolean(this.getTitle())
                    }, l.prototype.getTipElement = function () {
                        return this.tip = this.tip || t(this.config.template)[0]
                    }, l.prototype.setContent = function () {
                        var e = t(this.getTipElement());
                        this.setElementContent(e.find(m.TOOLTIP_INNER), this.getTitle()), e.removeClass(h.FADE).removeClass(h.IN), this.cleanupTether()
                    }, l.prototype.setElementContent = function (e, n) {
                        var r = this.config.html;
                        "object" === (void 0 === n ? "undefined" : i(n)) && (n.nodeType || n.jquery) ? r ? t(n).parent().is(e) || e.empty().append(n) : e.text(t(n).text()) : e[r ? "html" : "text"](n)
                    }, l.prototype.getTitle = function () {
                        var t = this.element.getAttribute("data-original-title");
                        return t || (t = "function" == typeof this.config.title ? this.config.title.call(this.element) : this.config.title), t
                    }, l.prototype.cleanupTether = function () {
                        this._tether && this._tether.destroy()
                    }, l.prototype._getAttachment = function (t) {
                        return f[t.toUpperCase()]
                    }, l.prototype._setListeners = function () {
                        var e = this;
                        this.config.trigger.split(" ").forEach(function (n) {
                            if ("click" === n) t(e.element).on(e.constructor.Event.CLICK, e.config.selector, t.proxy(e.toggle, e)); else if (n !== v.MANUAL) {
                                var i = n === v.HOVER ? e.constructor.Event.MOUSEENTER : e.constructor.Event.FOCUSIN,
                                    r = n === v.HOVER ? e.constructor.Event.MOUSELEAVE : e.constructor.Event.FOCUSOUT;
                                t(e.element).on(i, e.config.selector, t.proxy(e._enter, e)).on(r, e.config.selector, t.proxy(e._leave, e))
                            }
                        }), this.config.selector ? this.config = t.extend({}, this.config, {
                            trigger: "manual",
                            selector: ""
                        }) : this._fixTitle()
                    }, l.prototype._fixTitle = function () {
                        var t = i(this.element.getAttribute("data-original-title"));
                        (this.element.getAttribute("title") || "string" !== t) && (this.element.setAttribute("data-original-title", this.element.getAttribute("title") || ""), this.element.setAttribute("title", ""))
                    }, l.prototype._enter = function (e, n) {
                        var i = this.constructor.DATA_KEY;
                        return n = n || t(e.currentTarget).data(i), n || (n = new this.constructor(e.currentTarget, this._getDelegateConfig()), t(e.currentTarget).data(i, n)), e && (n._activeTrigger["focusin" === e.type ? v.FOCUS : v.HOVER] = !0), t(n.getTipElement()).hasClass(h.IN) || n._hoverState === d.IN ? void(n._hoverState = d.IN) : (clearTimeout(n._timeout), n._hoverState = d.IN, n.config.delay && n.config.delay.show ? void(n._timeout = setTimeout(function () {
                            n._hoverState === d.IN && n.show()
                        }, n.config.delay.show)) : void n.show())
                    }, l.prototype._leave = function (e, n) {
                        var i = this.constructor.DATA_KEY;
                        if (n = n || t(e.currentTarget).data(i), n || (n = new this.constructor(e.currentTarget, this._getDelegateConfig()), t(e.currentTarget).data(i, n)), e && (n._activeTrigger["focusout" === e.type ? v.FOCUS : v.HOVER] = !1), !n._isWithActiveTrigger()) return clearTimeout(n._timeout), n._hoverState = d.OUT, n.config.delay && n.config.delay.hide ? void(n._timeout = setTimeout(function () {
                            n._hoverState === d.OUT && n.hide()
                        }, n.config.delay.hide)) : void n.hide()
                    }, l.prototype._isWithActiveTrigger = function () {
                        for (var t in this._activeTrigger) if (this._activeTrigger[t]) return !0;
                        return !1
                    }, l.prototype._getConfig = function (n) {
                        return n = t.extend({}, this.constructor.Default, t(this.element).data(), n), n.delay && "number" == typeof n.delay && (n.delay = {
                            show: n.delay,
                            hide: n.delay
                        }), o.typeCheckConfig(e, n, this.constructor.DefaultType), n
                    }, l.prototype._getDelegateConfig = function () {
                        var t = {};
                        if (this.config) for (var e in this.config) this.constructor.Default[e] !== this.config[e] && (t[e] = this.config[e]);
                        return t
                    }, l._jQueryInterface = function (e) {
                        return this.each(function () {
                            var n = t(this).data(s), r = "object" === (void 0 === e ? "undefined" : i(e)) ? e : null;
                            if ((n || !/dispose|hide/.test(e)) && (n || (n = new l(this, r), t(this).data(s, n)), "string" == typeof e)) {
                                if (void 0 === n[e]) throw new Error('No method named "' + e + '"');
                                n[e]()
                            }
                        })
                    }, r(l, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }, {
                        key: "Default", get: function () {
                            return u
                        }
                    }, {
                        key: "NAME", get: function () {
                            return e
                        }
                    }, {
                        key: "DATA_KEY", get: function () {
                            return s
                        }
                    }, {
                        key: "Event", get: function () {
                            return p
                        }
                    }, {
                        key: "EVENT_KEY", get: function () {
                            return a
                        }
                    }, {
                        key: "DefaultType", get: function () {
                            return c
                        }
                    }]), l
                }();
            return t.fn[e] = y._jQueryInterface, t.fn[e].Constructor = y, t.fn[e].noConflict = function () {
                return t.fn[e] = l, y._jQueryInterface
            }, y
        }(jQuery));
        !function (o) {
            var a = "popover", l = "bs.popover", u = "." + l, c = o.fn[a], f = o.extend({}, s.Default, {
                    placement: "right",
                    trigger: "click",
                    content: "",
                    template: '<div class="popover" role="tooltip"><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
                }), d = o.extend({}, s.DefaultType, {content: "(string|element|function)"}), p = {FADE: "fade", IN: "in"},
                h = {TITLE: ".popover-title", CONTENT: ".popover-content"}, m = {
                    HIDE: "hide" + u,
                    HIDDEN: "hidden" + u,
                    SHOW: "show" + u,
                    SHOWN: "shown" + u,
                    INSERTED: "inserted" + u,
                    CLICK: "click" + u,
                    FOCUSIN: "focusin" + u,
                    FOCUSOUT: "focusout" + u,
                    MOUSEENTER: "mouseenter" + u,
                    MOUSELEAVE: "mouseleave" + u
                }, g = function (s) {
                    function c() {
                        return n(this, c), t(this, s.apply(this, arguments))
                    }

                    return e(c, s), c.prototype.isWithContent = function () {
                        return this.getTitle() || this._getContent()
                    }, c.prototype.getTipElement = function () {
                        return this.tip = this.tip || o(this.config.template)[0]
                    }, c.prototype.setContent = function () {
                        var t = o(this.getTipElement());
                        this.setElementContent(t.find(h.TITLE), this.getTitle()), this.setElementContent(t.find(h.CONTENT), this._getContent()), t.removeClass(p.FADE).removeClass(p.IN), this.cleanupTether()
                    }, c.prototype._getContent = function () {
                        return this.element.getAttribute("data-content") || ("function" == typeof this.config.content ? this.config.content.call(this.element) : this.config.content)
                    }, c._jQueryInterface = function (t) {
                        return this.each(function () {
                            var e = o(this).data(l), n = "object" === (void 0 === t ? "undefined" : i(t)) ? t : null;
                            if ((e || !/destroy|hide/.test(t)) && (e || (e = new c(this, n), o(this).data(l, e)), "string" == typeof t)) {
                                if (void 0 === e[t]) throw new Error('No method named "' + t + '"');
                                e[t]()
                            }
                        })
                    }, r(c, null, [{
                        key: "VERSION", get: function () {
                            return "4.0.0-alpha.5"
                        }
                    }, {
                        key: "Default", get: function () {
                            return f
                        }
                    }, {
                        key: "NAME", get: function () {
                            return a
                        }
                    }, {
                        key: "DATA_KEY", get: function () {
                            return l
                        }
                    }, {
                        key: "Event", get: function () {
                            return m
                        }
                    }, {
                        key: "EVENT_KEY", get: function () {
                            return u
                        }
                    }, {
                        key: "DefaultType", get: function () {
                            return d
                        }
                    }]), c
                }(s);
            o.fn[a] = g._jQueryInterface, o.fn[a].Constructor = g, o.fn[a].noConflict = function () {
                return o.fn[a] = c, g._jQueryInterface
            }
        }(jQuery)
    }()
}, function (t, e, n) {
    "use strict";

    function i() {
        this._events = this._events || {}, this._maxListeners = this._maxListeners || void 0
    }

    function r(t) {
        return "function" == typeof t
    }

    function o(t) {
        return "number" == typeof t
    }

    function s(t) {
        return "object" == typeof t && null !== t
    }

    function a(t) {
        return void 0 === t
    }

    t.exports = i, i.EventEmitter = i, i.prototype._events = void 0, i.prototype._maxListeners = void 0, i.defaultMaxListeners = 10, i.prototype.setMaxListeners = function (t) {
        if (!o(t) || t < 0 || isNaN(t)) throw TypeError("n must be a positive number");
        return this._maxListeners = t, this
    }, i.prototype.emit = function (t) {
        var e, n, i, o, l, u;
        if (this._events || (this._events = {}), "error" === t && (!this._events.error || s(this._events.error) && !this._events.error.length)) {
            if ((e = arguments[1]) instanceof Error) throw e;
            var c = new Error('Uncaught, unspecified "error" event. (' + e + ")");
            throw c.context = e, c
        }
        if (n = this._events[t], a(n)) return !1;
        if (r(n)) switch (arguments.length) {
            case 1:
                n.call(this);
                break;
            case 2:
                n.call(this, arguments[1]);
                break;
            case 3:
                n.call(this, arguments[1], arguments[2]);
                break;
            default:
                o = Array.prototype.slice.call(arguments, 1), n.apply(this, o)
        } else if (s(n)) for (o = Array.prototype.slice.call(arguments, 1), u = n.slice(), i = u.length, l = 0; l < i; l++) u[l].apply(this, o);
        return !0
    }, i.prototype.addListener = function (t, e) {
        var n;
        if (!r(e)) throw TypeError("listener must be a function");
        return this._events || (this._events = {}), this._events.newListener && this.emit("newListener", t, r(e.listener) ? e.listener : e), this._events[t] ? s(this._events[t]) ? this._events[t].push(e) : this._events[t] = [this._events[t], e] : this._events[t] = e, s(this._events[t]) && !this._events[t].warned && (n = a(this._maxListeners) ? i.defaultMaxListeners : this._maxListeners) && n > 0 && this._events[t].length > n && (this._events[t].warned = !0, console.trace), this
    }, i.prototype.on = i.prototype.addListener, i.prototype.once = function (t, e) {
        function n() {
            this.removeListener(t, n), i || (i = !0, e.apply(this, arguments))
        }

        if (!r(e)) throw TypeError("listener must be a function");
        var i = !1;
        return n.listener = e, this.on(t, n), this
    }, i.prototype.removeListener = function (t, e) {
        var n, i, o, a;
        if (!r(e)) throw TypeError("listener must be a function");
        if (!this._events || !this._events[t]) return this;
        if (n = this._events[t], o = n.length, i = -1, n === e || r(n.listener) && n.listener === e) delete this._events[t], this._events.removeListener && this.emit("removeListener", t, e); else if (s(n)) {
            for (a = o; a-- > 0;) if (n[a] === e || n[a].listener && n[a].listener === e) {
                i = a;
                break
            }
            if (i < 0) return this;
            1 === n.length ? (n.length = 0, delete this._events[t]) : n.splice(i, 1), this._events.removeListener && this.emit("removeListener", t, e)
        }
        return this
    }, i.prototype.removeAllListeners = function (t) {
        var e, n;
        if (!this._events) return this;
        if (!this._events.removeListener) return 0 === arguments.length ? this._events = {} : this._events[t] && delete this._events[t], this;
        if (0 === arguments.length) {
            for (e in this._events) "removeListener" !== e && this.removeAllListeners(e);
            return this.removeAllListeners("removeListener"), this._events = {}, this
        }
        if (n = this._events[t], r(n)) this.removeListener(t, n); else if (n) for (; n.length;) this.removeListener(t, n[n.length - 1]);
        return delete this._events[t], this
    }, i.prototype.listeners = function (t) {
        return this._events && this._events[t] ? r(this._events[t]) ? [this._events[t]] : this._events[t].slice() : []
    }, i.prototype.listenerCount = function (t) {
        if (this._events) {
            var e = this._events[t];
            if (r(e)) return 1;
            if (e) return e.length
        }
        return 0
    }, i.listenerCount = function (t, e) {
        return t.listenerCount(e)
    }
}, function (t, e, n) {
    "use strict";
    var i, i;
    !function (e) {
        t.exports = e()
    }(function () {
        return function t(e, n, r) {
            function o(a, l) {
                if (!n[a]) {
                    if (!e[a]) {
                        var u = "function" == typeof i && i;
                        if (!l && u) return i(a, !0);
                        if (s) return s(a, !0);
                        var c = new Error("Cannot find module '" + a + "'");
                        throw c.code = "MODULE_NOT_FOUND", c
                    }
                    var f = n[a] = {exports: {}};
                    e[a][0].call(f.exports, function (t) {
                        var n = e[a][1][t];
                        return o(n || t)
                    }, f, f.exports, t, e, n, r)
                }
                return n[a].exports
            }

            for (var s = "function" == typeof i && i, a = 0; a < r.length; a++) o(r[a]);
            return o
        }({
            1: [function (t, e, n) {
                e.exports = function (t) {
                    var e, n, i, r = -1;
                    if (t.lines.length > 1 && "flex-start" === t.style.alignContent) for (e = 0; i = t.lines[++r];) i.crossStart = e, e += i.cross; else if (t.lines.length > 1 && "flex-end" === t.style.alignContent) for (e = t.flexStyle.crossSpace; i = t.lines[++r];) i.crossStart = e, e += i.cross; else if (t.lines.length > 1 && "center" === t.style.alignContent) for (e = t.flexStyle.crossSpace / 2; i = t.lines[++r];) i.crossStart = e, e += i.cross; else if (t.lines.length > 1 && "space-between" === t.style.alignContent) for (n = t.flexStyle.crossSpace / (t.lines.length - 1), e = 0; i = t.lines[++r];) i.crossStart = e, e += i.cross + n; else if (t.lines.length > 1 && "space-around" === t.style.alignContent) for (n = 2 * t.flexStyle.crossSpace / (2 * t.lines.length), e = n / 2; i = t.lines[++r];) i.crossStart = e, e += i.cross + n; else for (n = t.flexStyle.crossSpace / t.lines.length, e = t.flexStyle.crossInnerBefore; i = t.lines[++r];) i.crossStart = e, i.cross += n, e += i.cross
                }
            }, {}], 2: [function (t, e, n) {
                e.exports = function (t) {
                    for (var e, n = -1; line = t.lines[++n];) for (e = -1; child = line.children[++e];) {
                        var i = child.style.alignSelf;
                        "auto" === i && (i = t.style.alignItems), "flex-start" === i ? child.flexStyle.crossStart = line.crossStart : "flex-end" === i ? child.flexStyle.crossStart = line.crossStart + line.cross - child.flexStyle.crossOuter : "center" === i ? child.flexStyle.crossStart = line.crossStart + (line.cross - child.flexStyle.crossOuter) / 2 : (child.flexStyle.crossStart = line.crossStart, child.flexStyle.crossOuter = line.cross, child.flexStyle.cross = child.flexStyle.crossOuter - child.flexStyle.crossBefore - child.flexStyle.crossAfter)
                    }
                }
            }, {}], 3: [function (t, e, n) {
                e.exports = function (t, e) {
                    var n = "row" === e || "row-reverse" === e, i = t.mainAxis;
                    if (i) {
                        n && "inline" === i || !n && "block" === i || (t.flexStyle = {
                            main: t.flexStyle.cross,
                            cross: t.flexStyle.main,
                            mainOffset: t.flexStyle.crossOffset,
                            crossOffset: t.flexStyle.mainOffset,
                            mainBefore: t.flexStyle.crossBefore,
                            mainAfter: t.flexStyle.crossAfter,
                            crossBefore: t.flexStyle.mainBefore,
                            crossAfter: t.flexStyle.mainAfter,
                            mainInnerBefore: t.flexStyle.crossInnerBefore,
                            mainInnerAfter: t.flexStyle.crossInnerAfter,
                            crossInnerBefore: t.flexStyle.mainInnerBefore,
                            crossInnerAfter: t.flexStyle.mainInnerAfter,
                            mainBorderBefore: t.flexStyle.crossBorderBefore,
                            mainBorderAfter: t.flexStyle.crossBorderAfter,
                            crossBorderBefore: t.flexStyle.mainBorderBefore,
                            crossBorderAfter: t.flexStyle.mainBorderAfter
                        })
                    } else t.flexStyle = n ? {
                        main: t.style.width,
                        cross: t.style.height,
                        mainOffset: t.style.offsetWidth,
                        crossOffset: t.style.offsetHeight,
                        mainBefore: t.style.marginLeft,
                        mainAfter: t.style.marginRight,
                        crossBefore: t.style.marginTop,
                        crossAfter: t.style.marginBottom,
                        mainInnerBefore: t.style.paddingLeft,
                        mainInnerAfter: t.style.paddingRight,
                        crossInnerBefore: t.style.paddingTop,
                        crossInnerAfter: t.style.paddingBottom,
                        mainBorderBefore: t.style.borderLeftWidth,
                        mainBorderAfter: t.style.borderRightWidth,
                        crossBorderBefore: t.style.borderTopWidth,
                        crossBorderAfter: t.style.borderBottomWidth
                    } : {
                        main: t.style.height,
                        cross: t.style.width,
                        mainOffset: t.style.offsetHeight,
                        crossOffset: t.style.offsetWidth,
                        mainBefore: t.style.marginTop,
                        mainAfter: t.style.marginBottom,
                        crossBefore: t.style.marginLeft,
                        crossAfter: t.style.marginRight,
                        mainInnerBefore: t.style.paddingTop,
                        mainInnerAfter: t.style.paddingBottom,
                        crossInnerBefore: t.style.paddingLeft,
                        crossInnerAfter: t.style.paddingRight,
                        mainBorderBefore: t.style.borderTopWidth,
                        mainBorderAfter: t.style.borderBottomWidth,
                        crossBorderBefore: t.style.borderLeftWidth,
                        crossBorderAfter: t.style.borderRightWidth
                    }, "content-box" === t.style.boxSizing && ("number" == typeof t.flexStyle.main && (t.flexStyle.main += t.flexStyle.mainInnerBefore + t.flexStyle.mainInnerAfter + t.flexStyle.mainBorderBefore + t.flexStyle.mainBorderAfter), "number" == typeof t.flexStyle.cross && (t.flexStyle.cross += t.flexStyle.crossInnerBefore + t.flexStyle.crossInnerAfter + t.flexStyle.crossBorderBefore + t.flexStyle.crossBorderAfter));
                    t.mainAxis = n ? "inline" : "block", t.crossAxis = n ? "block" : "inline", "number" == typeof t.style.flexBasis && (t.flexStyle.main = t.style.flexBasis + t.flexStyle.mainInnerBefore + t.flexStyle.mainInnerAfter + t.flexStyle.mainBorderBefore + t.flexStyle.mainBorderAfter), t.flexStyle.mainOuter = t.flexStyle.main, t.flexStyle.crossOuter = t.flexStyle.cross, "auto" === t.flexStyle.mainOuter && (t.flexStyle.mainOuter = t.flexStyle.mainOffset), "auto" === t.flexStyle.crossOuter && (t.flexStyle.crossOuter = t.flexStyle.crossOffset), "number" == typeof t.flexStyle.mainBefore && (t.flexStyle.mainOuter += t.flexStyle.mainBefore), "number" == typeof t.flexStyle.mainAfter && (t.flexStyle.mainOuter += t.flexStyle.mainAfter), "number" == typeof t.flexStyle.crossBefore && (t.flexStyle.crossOuter += t.flexStyle.crossBefore), "number" == typeof t.flexStyle.crossAfter && (t.flexStyle.crossOuter += t.flexStyle.crossAfter)
                }
            }, {}], 4: [function (t, e, n) {
                var i = t("../reduce");
                e.exports = function (t) {
                    if (t.mainSpace > 0) {
                        var e = i(t.children, function (t, e) {
                            return t + parseFloat(e.style.flexGrow)
                        }, 0);
                        e > 0 && (t.main = i(t.children, function (n, i) {
                            return "auto" === i.flexStyle.main ? i.flexStyle.main = i.flexStyle.mainOffset + parseFloat(i.style.flexGrow) / e * t.mainSpace : i.flexStyle.main += parseFloat(i.style.flexGrow) / e * t.mainSpace, i.flexStyle.mainOuter = i.flexStyle.main + i.flexStyle.mainBefore + i.flexStyle.mainAfter, n + i.flexStyle.mainOuter
                        }, 0), t.mainSpace = 0)
                    }
                }
            }, {"../reduce": 12}], 5: [function (t, e, n) {
                var i = t("../reduce");
                e.exports = function (t) {
                    if (t.mainSpace < 0) {
                        var e = i(t.children, function (t, e) {
                            return t + parseFloat(e.style.flexShrink)
                        }, 0);
                        e > 0 && (t.main = i(t.children, function (n, i) {
                            return i.flexStyle.main += parseFloat(i.style.flexShrink) / e * t.mainSpace, i.flexStyle.mainOuter = i.flexStyle.main + i.flexStyle.mainBefore + i.flexStyle.mainAfter, n + i.flexStyle.mainOuter
                        }, 0), t.mainSpace = 0)
                    }
                }
            }, {"../reduce": 12}], 6: [function (t, e, n) {
                var i = t("../reduce");
                e.exports = function (t) {
                    var e;
                    t.lines = [e = {main: 0, cross: 0, children: []}];
                    for (var n, r = -1; n = t.children[++r];) "nowrap" === t.style.flexWrap || 0 === e.children.length || "auto" === t.flexStyle.main || t.flexStyle.main - t.flexStyle.mainInnerBefore - t.flexStyle.mainInnerAfter - t.flexStyle.mainBorderBefore - t.flexStyle.mainBorderAfter >= e.main + n.flexStyle.mainOuter ? (e.main += n.flexStyle.mainOuter, e.cross = Math.max(e.cross, n.flexStyle.crossOuter)) : t.lines.push(e = {
                        main: n.flexStyle.mainOuter,
                        cross: n.flexStyle.crossOuter,
                        children: []
                    }), e.children.push(n);
                    t.flexStyle.mainLines = i(t.lines, function (t, e) {
                        return Math.max(t, e.main)
                    }, 0), t.flexStyle.crossLines = i(t.lines, function (t, e) {
                        return t + e.cross
                    }, 0), "auto" === t.flexStyle.main && (t.flexStyle.main = Math.max(t.flexStyle.mainOffset, t.flexStyle.mainLines + t.flexStyle.mainInnerBefore + t.flexStyle.mainInnerAfter + t.flexStyle.mainBorderBefore + t.flexStyle.mainBorderAfter)), "auto" === t.flexStyle.cross && (t.flexStyle.cross = Math.max(t.flexStyle.crossOffset, t.flexStyle.crossLines + t.flexStyle.crossInnerBefore + t.flexStyle.crossInnerAfter + t.flexStyle.crossBorderBefore + t.flexStyle.crossBorderAfter)), t.flexStyle.crossSpace = t.flexStyle.cross - t.flexStyle.crossInnerBefore - t.flexStyle.crossInnerAfter - t.flexStyle.crossBorderBefore - t.flexStyle.crossBorderAfter - t.flexStyle.crossLines, t.flexStyle.mainOuter = t.flexStyle.main + t.flexStyle.mainBefore + t.flexStyle.mainAfter, t.flexStyle.crossOuter = t.flexStyle.cross + t.flexStyle.crossBefore + t.flexStyle.crossAfter
                }
            }, {"../reduce": 12}], 7: [function (t, e, n) {
                function i(e) {
                    for (var n, i = -1; n = e.children[++i];) t("./flex-direction")(n, e.style.flexDirection);
                    t("./flex-direction")(e, e.style.flexDirection), t("./order")(e), t("./flexbox-lines")(e), t("./align-content")(e), i = -1;
                    for (var r; r = e.lines[++i];) r.mainSpace = e.flexStyle.main - e.flexStyle.mainInnerBefore - e.flexStyle.mainInnerAfter - e.flexStyle.mainBorderBefore - e.flexStyle.mainBorderAfter - r.main, t("./flex-grow")(r), t("./flex-shrink")(r), t("./margin-main")(r), t("./margin-cross")(r), t("./justify-content")(r, e.style.justifyContent, e);
                    t("./align-items")(e)
                }

                e.exports = i
            }, {
                "./align-content": 1,
                "./align-items": 2,
                "./flex-direction": 3,
                "./flex-grow": 4,
                "./flex-shrink": 5,
                "./flexbox-lines": 6,
                "./justify-content": 8,
                "./margin-cross": 9,
                "./margin-main": 10,
                "./order": 11
            }], 8: [function (t, e, n) {
                e.exports = function (t, e, n) {
                    var i, r, o, s = n.flexStyle.mainInnerBefore, a = -1;
                    if ("flex-end" === e) for (i = t.mainSpace, i += s; o = t.children[++a];) o.flexStyle.mainStart = i, i += o.flexStyle.mainOuter; else if ("center" === e) for (i = t.mainSpace / 2, i += s; o = t.children[++a];) o.flexStyle.mainStart = i, i += o.flexStyle.mainOuter; else if ("space-between" === e) for (r = t.mainSpace / (t.children.length - 1), i = 0, i += s; o = t.children[++a];) o.flexStyle.mainStart = i, i += o.flexStyle.mainOuter + r; else if ("space-around" === e) for (r = 2 * t.mainSpace / (2 * t.children.length), i = r / 2, i += s; o = t.children[++a];) o.flexStyle.mainStart = i, i += o.flexStyle.mainOuter + r; else for (i = 0, i += s; o = t.children[++a];) o.flexStyle.mainStart = i, i += o.flexStyle.mainOuter
                }
            }, {}], 9: [function (t, e, n) {
                e.exports = function (t) {
                    for (var e, n = -1; e = t.children[++n];) {
                        var i = 0;
                        "auto" === e.flexStyle.crossBefore && ++i, "auto" === e.flexStyle.crossAfter && ++i;
                        var r = t.cross - e.flexStyle.crossOuter;
                        "auto" === e.flexStyle.crossBefore && (e.flexStyle.crossBefore = r / i), "auto" === e.flexStyle.crossAfter && (e.flexStyle.crossAfter = r / i), "auto" === e.flexStyle.cross ? e.flexStyle.crossOuter = e.flexStyle.crossOffset + e.flexStyle.crossBefore + e.flexStyle.crossAfter : e.flexStyle.crossOuter = e.flexStyle.cross + e.flexStyle.crossBefore + e.flexStyle.crossAfter
                    }
                }
            }, {}], 10: [function (t, e, n) {
                e.exports = function (t) {
                    for (var e, n = 0, i = -1; e = t.children[++i];) "auto" === e.flexStyle.mainBefore && ++n, "auto" === e.flexStyle.mainAfter && ++n;
                    if (n > 0) {
                        for (i = -1; e = t.children[++i];) "auto" === e.flexStyle.mainBefore && (e.flexStyle.mainBefore = t.mainSpace / n), "auto" === e.flexStyle.mainAfter && (e.flexStyle.mainAfter = t.mainSpace / n), "auto" === e.flexStyle.main ? e.flexStyle.mainOuter = e.flexStyle.mainOffset + e.flexStyle.mainBefore + e.flexStyle.mainAfter : e.flexStyle.mainOuter = e.flexStyle.main + e.flexStyle.mainBefore + e.flexStyle.mainAfter;
                        t.mainSpace = 0
                    }
                }
            }, {}], 11: [function (t, e, n) {
                var i = /^(column|row)-reverse$/;
                e.exports = function (t) {
                    t.children.sort(function (t, e) {
                        return t.style.order - e.style.order || t.index - e.index
                    }), i.test(t.style.flexDirection) && t.children.reverse()
                }
            }, {}], 12: [function (t, e, n) {
                function i(t, e, n) {
                    for (var i = t.length, r = -1; ++r < i;) r in t && (n = e(n, t[r], r));
                    return n
                }

                e.exports = i
            }, {}], 13: [function (t, e, n) {
                function i(t) {
                    a(s(t))
                }

                var r = t("./read"), o = t("./write"), s = t("./readAll"), a = t("./writeAll");
                e.exports = i, e.exports.read = r, e.exports.write = o, e.exports.readAll = s, e.exports.writeAll = a
            }, {"./read": 15, "./readAll": 16, "./write": 17, "./writeAll": 18}], 14: [function (t, e, n) {
                function i(t, e) {
                    var n = String(t).match(o);
                    if (!n) return t;
                    var i = n[1], s = n[2];
                    return "px" === s ? 1 * i : "cm" === s ? .3937 * i * 96 : "in" === s ? 96 * i : "mm" === s ? .3937 * i * 96 / 10 : "pc" === s ? 12 * i * 96 / 72 : "pt" === s ? 96 * i / 72 : "rem" === s ? 16 * i : r(t, e)
                }

                function r(t, e) {
                    s.style.cssText = "border:none!important;clip:rect(0 0 0 0)!important;display:block!important;font-size:1em!important;height:0!important;margin:0!important;padding:0!important;position:relative!important;width:" + t + "!important", e.parentNode.insertBefore(s, e.nextSibling);
                    var n = s.offsetWidth;
                    return e.parentNode.removeChild(s), n
                }

                e.exports = i;
                var o = /^([-+]?\d*\.?\d+)(%|[a-z]+)$/, s = document.createElement("div")
            }, {}], 15: [function (t, e, n) {
                function i(t) {
                    var e = {
                        alignContent: "stretch",
                        alignItems: "stretch",
                        alignSelf: "auto",
                        borderBottomWidth: 0,
                        borderLeftWidth: 0,
                        borderRightWidth: 0,
                        borderTopWidth: 0,
                        boxSizing: "content-box",
                        display: "inline",
                        flexBasis: "auto",
                        flexDirection: "row",
                        flexGrow: 0,
                        flexShrink: 1,
                        flexWrap: "nowrap",
                        justifyContent: "flex-start",
                        height: "auto",
                        marginTop: 0,
                        marginRight: 0,
                        marginLeft: 0,
                        marginBottom: 0,
                        paddingTop: 0,
                        paddingRight: 0,
                        paddingLeft: 0,
                        paddingBottom: 0,
                        maxHeight: "none",
                        maxWidth: "none",
                        minHeight: 0,
                        minWidth: 0,
                        order: 0,
                        position: "static",
                        width: "auto"
                    };
                    if (t instanceof Element) {
                        var n = t.hasAttribute("data-style"),
                            i = n ? t.getAttribute("data-style") : t.getAttribute("style") || "";
                        n || t.setAttribute("data-style", i), s(e, window.getComputedStyle && getComputedStyle(t) || {}), r(e, t.currentStyle || {}), o(e, i);
                        for (var a in e) e[a] = l(e[a], t);
                        var u = t.getBoundingClientRect();
                        e.offsetHeight = u.height || t.offsetHeight, e.offsetWidth = u.width || t.offsetWidth
                    }
                    return {element: t, style: e}
                }

                function r(t, e) {
                    for (var n in t) {
                        if (n in e) t[n] = e[n]; else {
                            var i = n.replace(/[A-Z]/g, "-$&").toLowerCase();
                            i in e && (t[n] = e[i])
                        }
                    }
                    "-js-display" in e && (t.display = e["-js-display"])
                }

                function o(t, e) {
                    for (var n; n = a.exec(e);) {
                        t[n[1].toLowerCase().replace(/-[a-z]/g, function (t) {
                            return t.slice(1).toUpperCase()
                        })] = n[2]
                    }
                }

                function s(t, e) {
                    for (var n in t) {
                        n in e && !/^(alignSelf|height|width)$/.test(n) && (t[n] = e[n])
                    }
                }

                e.exports = i;
                var a = /([^\s:;]+)\s*:\s*([^;]+?)\s*(;|$)/g, l = t("./getComputedLength")
            }, {"./getComputedLength": 14}], 16: [function (t, e, n) {
                function i(t) {
                    var e = [];
                    return r(t, e), e
                }

                function r(t, e) {
                    for (var n, i = o(t), a = [], l = -1; n = t.childNodes[++l];) {
                        var u = 3 === n.nodeType && !/^\s*$/.test(n.nodeValue);
                        if (i && u) {
                            var c = n;
                            n = t.insertBefore(document.createElement("flex-item"), c), n.appendChild(c)
                        }
                        if (n instanceof Element) {
                            var f = r(n, e);
                            if (i) {
                                var d = n.style;
                                d.display = "inline-block", d.position = "absolute", f.style = s(n).style, a.push(f)
                            }
                        }
                    }
                    var p = {element: t, children: a};
                    return i && (p.style = s(t).style, e.push(p)), p
                }

                function o(t) {
                    var e = t instanceof Element, n = e && t.getAttribute("data-style"),
                        i = e && t.currentStyle && t.currentStyle["-js-display"];
                    return a.test(n) || l.test(i)
                }

                e.exports = i;
                var s = t("../read"), a = /(^|;)\s*display\s*:\s*(inline-)?flex\s*(;|$)/i, l = /^(inline-)?flex$/i
            }, {"../read": 15}], 17: [function (t, e, n) {
                function i(t) {
                    o(t);
                    var e = t.element.style, n = "inline" === t.mainAxis ? ["main", "cross"] : ["cross", "main"];
                    e.boxSizing = "content-box", e.display = "block", e.position = "relative", e.width = r(t.flexStyle[n[0]] - t.flexStyle[n[0] + "InnerBefore"] - t.flexStyle[n[0] + "InnerAfter"] - t.flexStyle[n[0] + "BorderBefore"] - t.flexStyle[n[0] + "BorderAfter"]), e.height = r(t.flexStyle[n[1]] - t.flexStyle[n[1] + "InnerBefore"] - t.flexStyle[n[1] + "InnerAfter"] - t.flexStyle[n[1] + "BorderBefore"] - t.flexStyle[n[1] + "BorderAfter"]);
                    for (var i, s = -1; i = t.children[++s];) {
                        var a = i.element.style, l = "inline" === i.mainAxis ? ["main", "cross"] : ["cross", "main"];
                        a.boxSizing = "content-box", a.display = "block", a.position = "absolute", "auto" !== i.flexStyle[l[0]] && (a.width = r(i.flexStyle[l[0]] - i.flexStyle[l[0] + "InnerBefore"] - i.flexStyle[l[0] + "InnerAfter"] - i.flexStyle[l[0] + "BorderBefore"] - i.flexStyle[l[0] + "BorderAfter"])), "auto" !== i.flexStyle[l[1]] && (a.height = r(i.flexStyle[l[1]] - i.flexStyle[l[1] + "InnerBefore"] - i.flexStyle[l[1] + "InnerAfter"] - i.flexStyle[l[1] + "BorderBefore"] - i.flexStyle[l[1] + "BorderAfter"])), a.top = r(i.flexStyle[l[1] + "Start"]), a.left = r(i.flexStyle[l[0] + "Start"]), a.marginTop = r(i.flexStyle[l[1] + "Before"]), a.marginRight = r(i.flexStyle[l[0] + "After"]), a.marginBottom = r(i.flexStyle[l[1] + "After"]), a.marginLeft = r(i.flexStyle[l[0] + "Before"])
                    }
                }

                function r(t) {
                    return "string" == typeof t ? t : Math.max(t, 0) + "px"
                }

                e.exports = i;
                var o = t("../flexbox")
            }, {"../flexbox": 7}], 18: [function (t, e, n) {
                function i(t) {
                    for (var e, n = -1; e = t[++n];) r(e)
                }

                e.exports = i;
                var r = t("../write")
            }, {"../write": 17}]
        }, {}, [13])(13)
    })
}, function (t, e, n) {
    "use strict";
    var i, r;
    !function (o, s) {
        i = s, void 0 !== (r = "function" == typeof i ? i.call(e, n, e, t) : i) && (t.exports = r)
    }(0, function (t, e, n) {
        function i(t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }

        function r(t) {
            var e = t.getBoundingClientRect(), n = {};
            for (var i in e) n[i] = e[i];
            if (t.ownerDocument !== document) {
                var o = t.ownerDocument.defaultView.frameElement;
                if (o) {
                    var s = r(o);
                    n.top += s.top, n.bottom += s.top, n.left += s.left, n.right += s.left
                }
            }
            return n
        }

        function o(t) {
            var e = getComputedStyle(t) || {}, n = e.position, i = [];
            if ("fixed" === n) return [t];
            for (var r = t; (r = r.parentNode) && r && 1 === r.nodeType;) {
                var o = void 0;
                try {
                    o = getComputedStyle(r)
                } catch (t) {
                }
                if (void 0 === o || null === o) return i.push(r), i;
                var s = o, a = s.overflow, l = s.overflowX;
                /(auto|scroll)/.test(a + s.overflowY + l) && ("absolute" !== n || ["relative", "absolute", "fixed"].indexOf(o.position) >= 0) && i.push(r)
            }
            return i.push(t.ownerDocument.body), t.ownerDocument !== document && i.push(t.ownerDocument.defaultView), i
        }

        function s() {
            C && document.body.removeChild(C), C = null
        }

        function a(t) {
            var e = void 0;
            t === document ? (e = document, t = document.documentElement) : e = t.ownerDocument;
            var n = e.documentElement, i = r(t), o = I();
            return i.top -= o.top, i.left -= o.left, void 0 === i.width && (i.width = document.body.scrollWidth - i.left - i.right), void 0 === i.height && (i.height = document.body.scrollHeight - i.top - i.bottom), i.top = i.top - n.clientTop, i.left = i.left - n.clientLeft, i.right = e.body.clientWidth - i.width - i.left, i.bottom = e.body.clientHeight - i.height - i.top, i
        }

        function l(t) {
            return t.offsetParent || document.documentElement
        }

        function u() {
            if (O) return O;
            var t = document.createElement("div");
            t.style.width = "100%", t.style.height = "200px";
            var e = document.createElement("div");
            c(e.style, {
                position: "absolute",
                top: 0,
                left: 0,
                pointerEvents: "none",
                visibility: "hidden",
                width: "200px",
                height: "150px",
                overflow: "hidden"
            }), e.appendChild(t), document.body.appendChild(e);
            var n = t.offsetWidth;
            e.style.overflow = "scroll";
            var i = t.offsetWidth;
            n === i && (i = e.clientWidth), document.body.removeChild(e);
            var r = n - i;
            return O = {width: r, height: r}
        }

        function c() {
            var t = arguments.length <= 0 || void 0 === arguments[0] ? {} : arguments[0], e = [];
            return Array.prototype.push.apply(e, arguments), e.slice(1).forEach(function (e) {
                if (e) for (var n in e) ({}).hasOwnProperty.call(e, n) && (t[n] = e[n])
            }), t
        }

        function f(t, e) {
            if (void 0 !== t.classList) e.split(" ").forEach(function (e) {
                e.trim() && t.classList.remove(e)
            }); else {
                var n = new RegExp("(^| )" + e.split(" ").join("|") + "( |$)", "gi"), i = h(t).replace(n, " ");
                m(t, i)
            }
        }

        function d(t, e) {
            if (void 0 !== t.classList) e.split(" ").forEach(function (e) {
                e.trim() && t.classList.add(e)
            }); else {
                f(t, e);
                var n = h(t) + " " + e;
                m(t, n)
            }
        }

        function p(t, e) {
            if (void 0 !== t.classList) return t.classList.contains(e);
            var n = h(t);
            return new RegExp("(^| )" + e + "( |$)", "gi").test(n)
        }

        function h(t) {
            return t.className instanceof t.ownerDocument.defaultView.SVGAnimatedString ? t.className.baseVal : t.className
        }

        function m(t, e) {
            t.setAttribute("class", e)
        }

        function g(t, e, n) {
            n.forEach(function (n) {
                -1 === e.indexOf(n) && p(t, n) && f(t, n)
            }), e.forEach(function (e) {
                p(t, e) || d(t, e)
            })
        }

        function i(t, e) {
            if (!(t instanceof e)) throw new TypeError("Cannot call a class as a function")
        }

        function v(t, e) {
            if ("function" != typeof e && null !== e) throw new TypeError("Super expression must either be null or a function, not " + typeof e);
            t.prototype = Object.create(e && e.prototype, {
                constructor: {
                    value: t,
                    enumerable: !1,
                    writable: !0,
                    configurable: !0
                }
            }), e && (Object.setPrototypeOf ? Object.setPrototypeOf(t, e) : t.__proto__ = e)
        }

        function y(t, e) {
            var n = arguments.length <= 2 || void 0 === arguments[2] ? 1 : arguments[2];
            return t + n >= e && e >= t - n
        }

        function b() {
            return "undefined" != typeof performance && void 0 !== performance.now ? performance.now() : +new Date
        }

        function _() {
            for (var t = {top: 0, left: 0}, e = arguments.length, n = Array(e), i = 0; i < e; i++) n[i] = arguments[i];
            return n.forEach(function (e) {
                var n = e.top, i = e.left;
                "string" == typeof n && (n = parseFloat(n, 10)), "string" == typeof i && (i = parseFloat(i, 10)), t.top += n, t.left += i
            }), t
        }

        function x(t, e) {
            return "string" == typeof t.left && -1 !== t.left.indexOf("%") && (t.left = parseFloat(t.left, 10) / 100 * e.width), "string" == typeof t.top && -1 !== t.top.indexOf("%") && (t.top = parseFloat(t.top, 10) / 100 * e.height), t
        }

        function w(t, e) {
            return "scrollParent" === e ? e = t.scrollParents[0] : "window" === e && (e = [pageXOffset, pageYOffset, innerWidth + pageXOffset, innerHeight + pageYOffset]), e === document && (e = e.documentElement), void 0 !== e.nodeType && function () {
                var t = e, n = a(e), i = n, r = getComputedStyle(e);
                if (e = [i.left, i.top, n.width + i.left, n.height + i.top], t.ownerDocument !== document) {
                    var o = t.ownerDocument.defaultView;
                    e[0] += o.pageXOffset, e[1] += o.pageYOffset, e[2] += o.pageXOffset, e[3] += o.pageYOffset
                }
                Y.forEach(function (t, n) {
                    t = t[0].toUpperCase() + t.substr(1), "Top" === t || "Left" === t ? e[n] += parseFloat(r["border" + t + "Width"]) : e[n] -= parseFloat(r["border" + t + "Width"])
                })
            }(), e
        }

        var S = function () {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var i = e[n];
                    i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
                }
            }

            return function (e, n, i) {
                return n && t(e.prototype, n), i && t(e, i), e
            }
        }(), E = void 0;
        void 0 === E && (E = {modules: []});
        var C = null, T = function () {
            var t = 0;
            return function () {
                return ++t
            }
        }(), A = {}, I = function () {
            var t = C;
            t && document.body.contains(t) || (t = document.createElement("div"), t.setAttribute("data-tether-id", T()), c(t.style, {
                top: 0,
                left: 0,
                position: "absolute"
            }), document.body.appendChild(t), C = t);
            var e = t.getAttribute("data-tether-id");
            return void 0 === A[e] && (A[e] = r(t), D(function () {
                delete A[e]
            })), A[e]
        }, O = null, k = [], D = function (t) {
            k.push(t)
        }, N = function () {
            for (var t = void 0; t = k.pop();) t()
        }, P = function () {
            function t() {
                i(this, t)
            }

            return S(t, [{
                key: "on", value: function (t, e, n) {
                    var i = !(arguments.length <= 3 || void 0 === arguments[3]) && arguments[3];
                    void 0 === this.bindings && (this.bindings = {}), void 0 === this.bindings[t] && (this.bindings[t] = []), this.bindings[t].push({
                        handler: e,
                        ctx: n,
                        once: i
                    })
                }
            }, {
                key: "once", value: function (t, e, n) {
                    this.on(t, e, n, !0)
                }
            }, {
                key: "off", value: function (t, e) {
                    if (void 0 !== this.bindings && void 0 !== this.bindings[t]) if (void 0 === e) delete this.bindings[t]; else for (var n = 0; n < this.bindings[t].length;) this.bindings[t][n].handler === e ? this.bindings[t].splice(n, 1) : ++n
                }
            }, {
                key: "trigger", value: function (t) {
                    if (void 0 !== this.bindings && this.bindings[t]) {
                        for (var e = 0, n = arguments.length, i = Array(n > 1 ? n - 1 : 0), r = 1; r < n; r++) i[r - 1] = arguments[r];
                        for (; e < this.bindings[t].length;) {
                            var o = this.bindings[t][e], s = o.handler, a = o.ctx, l = o.once, u = a;
                            void 0 === u && (u = this), s.apply(u, i), l ? this.bindings[t].splice(e, 1) : ++e
                        }
                    }
                }
            }]), t
        }();
        E.Utils = {
            getActualBoundingClientRect: r,
            getScrollParents: o,
            getBounds: a,
            getOffsetParent: l,
            extend: c,
            addClass: d,
            removeClass: f,
            hasClass: p,
            updateClasses: g,
            defer: D,
            flush: N,
            uniqueId: T,
            Evented: P,
            getScrollBarSize: u,
            removeUtilElements: s
        };
        var L = function () {
            function t(t, e) {
                var n = [], i = !0, r = !1, o = void 0;
                try {
                    for (var s, a = t[Symbol.iterator](); !(i = (s = a.next()).done) && (n.push(s.value), !e || n.length !== e); i = !0) ;
                } catch (t) {
                    r = !0, o = t
                } finally {
                    try {
                        !i && a.return && a.return()
                    } finally {
                        if (r) throw o
                    }
                }
                return n
            }

            return function (e, n) {
                if (Array.isArray(e)) return e;
                if (Symbol.iterator in Object(e)) return t(e, n);
                throw new TypeError("Invalid attempt to destructure non-iterable instance")
            }
        }(), S = function () {
            function t(t, e) {
                for (var n = 0; n < e.length; n++) {
                    var i = e[n];
                    i.enumerable = i.enumerable || !1, i.configurable = !0, "value" in i && (i.writable = !0), Object.defineProperty(t, i.key, i)
                }
            }

            return function (e, n, i) {
                return n && t(e.prototype, n), i && t(e, i), e
            }
        }(), j = function (t, e, n) {
            for (var i = !0; i;) {
                var r = t, o = e, s = n;
                i = !1, null === r && (r = Function.prototype);
                var a = Object.getOwnPropertyDescriptor(r, o);
                if (void 0 !== a) {
                    if ("value" in a) return a.value;
                    var l = a.get;
                    if (void 0 === l) return;
                    return l.call(s)
                }
                var u = Object.getPrototypeOf(r);
                if (null === u) return;
                t = u, e = o, n = s, i = !0, a = u = void 0
            }
        };
        if (void 0 === E) throw new Error("You must include the utils.js file before tether.js");
        var B = E.Utils, o = B.getScrollParents, a = B.getBounds, l = B.getOffsetParent, c = B.extend, d = B.addClass,
            f = B.removeClass, g = B.updateClasses, D = B.defer, N = B.flush, u = B.getScrollBarSize,
            s = B.removeUtilElements, V = function () {
                if ("undefined" == typeof document) return "";
                for (var t = document.createElement("div"), e = ["transform", "WebkitTransform", "OTransform", "MozTransform", "msTransform"], n = 0; n < e.length; ++n) {
                    var i = e[n];
                    if (void 0 !== t.style[i]) return i
                }
            }(), F = [], R = function () {
                F.forEach(function (t) {
                    t.position(!1)
                }), N()
            };
        !function () {
            var t = null, e = null, n = null, i = function i() {
                if (void 0 !== e && e > 16) return e = Math.min(e - 16, 250), void(n = setTimeout(i, 250));
                void 0 !== t && b() - t < 10 || (null != n && (clearTimeout(n), n = null), t = b(), R(), e = b() - t)
            };
            "undefined" != typeof window && void 0 !== window.addEventListener && ["resize", "scroll", "touchmove"].forEach(function (t) {
                window.addEventListener(t, i)
            })
        }();
        var M = {center: "center", left: "right", right: "left"}, H = {middle: "middle", top: "bottom", bottom: "top"},
            W = {top: 0, left: 0, middle: "50%", center: "50%", bottom: "100%", right: "100%"}, U = function (t, e) {
                var n = t.left, i = t.top;
                return "auto" === n && (n = M[e.left]), "auto" === i && (i = H[e.top]), {left: n, top: i}
            }, q = function (t) {
                var e = t.left, n = t.top;
                return void 0 !== W[t.left] && (e = W[t.left]), void 0 !== W[t.top] && (n = W[t.top]), {left: e, top: n}
            }, z = function (t) {
                var e = t.split(" "), n = L(e, 2);
                return {top: n[0], left: n[1]}
            }, $ = z, Q = function (t) {
                function e(t) {
                    var n = this;
                    i(this, e), j(Object.getPrototypeOf(e.prototype), "constructor", this).call(this), this.position = this.position.bind(this), F.push(this), this.history = [], this.setOptions(t, !1), E.modules.forEach(function (t) {
                        void 0 !== t.initialize && t.initialize.call(n)
                    }), this.position()
                }

                return v(e, t), S(e, [{
                    key: "getClass", value: function () {
                        var t = arguments.length <= 0 || void 0 === arguments[0] ? "" : arguments[0],
                            e = this.options.classes;
                        return void 0 !== e && e[t] ? this.options.classes[t] : this.options.classPrefix ? this.options.classPrefix + "-" + t : t
                    }
                }, {
                    key: "setOptions", value: function (t) {
                        var e = this, n = arguments.length <= 1 || void 0 === arguments[1] || arguments[1],
                            i = {offset: "0 0", targetOffset: "0 0", targetAttachment: "auto auto", classPrefix: "tether"};
                        this.options = c(i, t);
                        var r = this.options, s = r.element, a = r.target, l = r.targetModifier;
                        if (this.element = s, this.target = a, this.targetModifier = l, "viewport" === this.target ? (this.target = document.body, this.targetModifier = "visible") : "scroll-handle" === this.target && (this.target = document.body, this.targetModifier = "scroll-handle"), ["element", "target"].forEach(function (t) {
                                if (void 0 === e[t]) throw new Error("Tether Error: Both element and target must be defined");
                                void 0 !== e[t].jquery ? e[t] = e[t][0] : "string" == typeof e[t] && (e[t] = document.querySelector(e[t]))
                            }), d(this.element, this.getClass("element")), !1 !== this.options.addTargetClasses && d(this.target, this.getClass("target")), !this.options.attachment) throw new Error("Tether Error: You must provide an attachment");
                        this.targetAttachment = $(this.options.targetAttachment), this.attachment = $(this.options.attachment), this.offset = z(this.options.offset), this.targetOffset = z(this.options.targetOffset), void 0 !== this.scrollParents && this.disable(), "scroll-handle" === this.targetModifier ? this.scrollParents = [this.target] : this.scrollParents = o(this.target), !1 !== this.options.enabled && this.enable(n)
                    }
                }, {
                    key: "getTargetBounds", value: function () {
                        if (void 0 === this.targetModifier) return a(this.target);
                        if ("visible" === this.targetModifier) {
                            if (this.target === document.body) return {
                                top: pageYOffset,
                                left: pageXOffset,
                                height: innerHeight,
                                width: innerWidth
                            };
                            var t = a(this.target), e = {height: t.height, width: t.width, top: t.top, left: t.left};
                            return e.height = Math.min(e.height, t.height - (pageYOffset - t.top)), e.height = Math.min(e.height, t.height - (t.top + t.height - (pageYOffset + innerHeight))), e.height = Math.min(innerHeight, e.height), e.height -= 2, e.width = Math.min(e.width, t.width - (pageXOffset - t.left)), e.width = Math.min(e.width, t.width - (t.left + t.width - (pageXOffset + innerWidth))), e.width = Math.min(innerWidth, e.width), e.width -= 2, e.top < pageYOffset && (e.top = pageYOffset), e.left < pageXOffset && (e.left = pageXOffset), e
                        }
                        if ("scroll-handle" === this.targetModifier) {
                            var t = void 0, n = this.target;
                            n === document.body ? (n = document.documentElement, t = {
                                left: pageXOffset,
                                top: pageYOffset,
                                height: innerHeight,
                                width: innerWidth
                            }) : t = a(n);
                            var i = getComputedStyle(n),
                                r = n.scrollWidth > n.clientWidth || [i.overflow, i.overflowX].indexOf("scroll") >= 0 || this.target !== document.body,
                                o = 0;
                            r && (o = 15);
                            var s = t.height - parseFloat(i.borderTopWidth) - parseFloat(i.borderBottomWidth) - o, e = {
                                width: 15,
                                height: .975 * s * (s / n.scrollHeight),
                                left: t.left + t.width - parseFloat(i.borderLeftWidth) - 15
                            }, l = 0;
                            s < 408 && this.target === document.body && (l = -11e-5 * Math.pow(s, 2) - .00727 * s + 22.58), this.target !== document.body && (e.height = Math.max(e.height, 24));
                            var u = this.target.scrollTop / (n.scrollHeight - s);
                            return e.top = u * (s - e.height - l) + t.top + parseFloat(i.borderTopWidth), this.target === document.body && (e.height = Math.max(e.height, 24)), e
                        }
                    }
                }, {
                    key: "clearCache", value: function () {
                        this._cache = {}
                    }
                }, {
                    key: "cache", value: function (t, e) {
                        return void 0 === this._cache && (this._cache = {}), void 0 === this._cache[t] && (this._cache[t] = e.call(this)), this._cache[t]
                    }
                }, {
                    key: "enable", value: function () {
                        var t = this, e = arguments.length <= 0 || void 0 === arguments[0] || arguments[0];
                        !1 !== this.options.addTargetClasses && d(this.target, this.getClass("enabled")), d(this.element, this.getClass("enabled")), this.enabled = !0, this.scrollParents.forEach(function (e) {
                            e !== t.target.ownerDocument && e.addEventListener("scroll", t.position)
                        }), e && this.position()
                    }
                }, {
                    key: "disable", value: function () {
                        var t = this;
                        f(this.target, this.getClass("enabled")), f(this.element, this.getClass("enabled")), this.enabled = !1, void 0 !== this.scrollParents && this.scrollParents.forEach(function (e) {
                            e.removeEventListener("scroll", t.position)
                        })
                    }
                }, {
                    key: "destroy", value: function () {
                        var t = this;
                        this.disable(), F.forEach(function (e, n) {
                            e === t && F.splice(n, 1)
                        }), 0 === F.length && s()
                    }
                }, {
                    key: "updateAttachClasses", value: function (t, e) {
                        var n = this;
                        t = t || this.attachment, e = e || this.targetAttachment;
                        var i = ["left", "top", "bottom", "right", "middle", "center"];
                        void 0 !== this._addAttachClasses && this._addAttachClasses.length && this._addAttachClasses.splice(0, this._addAttachClasses.length), void 0 === this._addAttachClasses && (this._addAttachClasses = []);
                        var r = this._addAttachClasses;
                        t.top && r.push(this.getClass("element-attached") + "-" + t.top), t.left && r.push(this.getClass("element-attached") + "-" + t.left), e.top && r.push(this.getClass("target-attached") + "-" + e.top), e.left && r.push(this.getClass("target-attached") + "-" + e.left);
                        var o = [];
                        i.forEach(function (t) {
                            o.push(n.getClass("element-attached") + "-" + t), o.push(n.getClass("target-attached") + "-" + t)
                        }), D(function () {
                            void 0 !== n._addAttachClasses && (g(n.element, n._addAttachClasses, o), !1 !== n.options.addTargetClasses && g(n.target, n._addAttachClasses, o), delete n._addAttachClasses)
                        })
                    }
                }, {
                    key: "position", value: function () {
                        var t = this, e = arguments.length <= 0 || void 0 === arguments[0] || arguments[0];
                        if (this.enabled) {
                            this.clearCache();
                            var n = U(this.targetAttachment, this.attachment);
                            this.updateAttachClasses(this.attachment, n);
                            var i = this.cache("element-bounds", function () {
                                return a(t.element)
                            }), r = i.width, o = i.height;
                            if (0 === r && 0 === o && void 0 !== this.lastSize) {
                                var s = this.lastSize;
                                r = s.width, o = s.height
                            } else this.lastSize = {width: r, height: o};
                            var c = this.cache("target-bounds", function () {
                                    return t.getTargetBounds()
                                }), f = c, d = x(q(this.attachment), {width: r, height: o}), p = x(q(n), f),
                                h = x(this.offset, {width: r, height: o}), m = x(this.targetOffset, f);
                            d = _(d, h), p = _(p, m);
                            for (var g = c.left + p.left - d.left, v = c.top + p.top - d.top, y = 0; y < E.modules.length; ++y) {
                                var b = E.modules[y], w = b.position.call(this, {
                                    left: g,
                                    top: v,
                                    targetAttachment: n,
                                    targetPos: c,
                                    elementPos: i,
                                    offset: d,
                                    targetOffset: p,
                                    manualOffset: h,
                                    manualTargetOffset: m,
                                    scrollbarSize: A,
                                    attachment: this.attachment
                                });
                                if (!1 === w) return !1;
                                void 0 !== w && "object" == typeof w && (v = w.top, g = w.left)
                            }
                            var S = {
                                page: {top: v, left: g},
                                viewport: {
                                    top: v - pageYOffset,
                                    bottom: pageYOffset - v - o + innerHeight,
                                    left: g - pageXOffset,
                                    right: pageXOffset - g - r + innerWidth
                                }
                            }, C = this.target.ownerDocument, T = C.defaultView, A = void 0;
                            return T.innerHeight > C.documentElement.clientHeight && (A = this.cache("scrollbar-size", u), S.viewport.bottom -= A.height), T.innerWidth > C.documentElement.clientWidth && (A = this.cache("scrollbar-size", u), S.viewport.right -= A.width), -1 !== ["", "static"].indexOf(C.body.style.position) && -1 !== ["", "static"].indexOf(C.body.parentElement.style.position) || (S.page.bottom = C.body.scrollHeight - v - o, S.page.right = C.body.scrollWidth - g - r), void 0 !== this.options.optimizations && !1 !== this.options.optimizations.moveElement && void 0 === this.targetModifier && function () {
                                var e = t.cache("target-offsetparent", function () {
                                    return l(t.target)
                                }), n = t.cache("target-offsetparent-bounds", function () {
                                    return a(e)
                                }), i = getComputedStyle(e), r = n, o = {};
                                if (["Top", "Left", "Bottom", "Right"].forEach(function (t) {
                                        o[t.toLowerCase()] = parseFloat(i["border" + t + "Width"])
                                    }), n.right = C.body.scrollWidth - n.left - r.width + o.right, n.bottom = C.body.scrollHeight - n.top - r.height + o.bottom, S.page.top >= n.top + o.top && S.page.bottom >= n.bottom && S.page.left >= n.left + o.left && S.page.right >= n.right) {
                                    var s = e.scrollTop, u = e.scrollLeft;
                                    S.offset = {
                                        top: S.page.top - n.top + s - o.top,
                                        left: S.page.left - n.left + u - o.left
                                    }
                                }
                            }(), this.move(S), this.history.unshift(S), this.history.length > 3 && this.history.pop(), e && N(), !0
                        }
                    }
                }, {
                    key: "move", value: function (t) {
                        var e = this;
                        if (void 0 !== this.element.parentNode) {
                            var n = {};
                            for (var i in t) {
                                n[i] = {};
                                for (var r in t[i]) {
                                    for (var o = !1, s = 0; s < this.history.length; ++s) {
                                        var a = this.history[s];
                                        if (void 0 !== a[i] && !y(a[i][r], t[i][r])) {
                                            o = !0;
                                            break
                                        }
                                    }
                                    o || (n[i][r] = !0)
                                }
                            }
                            var u = {top: "", left: "", right: "", bottom: ""}, f = function (t, n) {
                                if (!1 !== (void 0 !== e.options.optimizations ? e.options.optimizations.gpu : null)) {
                                    var i = void 0, r = void 0;
                                    t.top ? (u.top = 0, i = n.top) : (u.bottom = 0, i = -n.bottom), t.left ? (u.left = 0, r = n.left) : (u.right = 0, r = -n.right), window.matchMedia && (window.matchMedia("only screen and (min-resolution: 1.3dppx)").matches || window.matchMedia("only screen and (-webkit-min-device-pixel-ratio: 1.3)").matches || (r = Math.round(r), i = Math.round(i))), u[V] = "translateX(" + r + "px) translateY(" + i + "px)", "msTransform" !== V && (u[V] += " translateZ(0)")
                                } else t.top ? u.top = n.top + "px" : u.bottom = n.bottom + "px", t.left ? u.left = n.left + "px" : u.right = n.right + "px"
                            }, d = !1;
                            if ((n.page.top || n.page.bottom) && (n.page.left || n.page.right) ? (u.position = "absolute", f(n.page, t.page)) : (n.viewport.top || n.viewport.bottom) && (n.viewport.left || n.viewport.right) ? (u.position = "fixed", f(n.viewport, t.viewport)) : void 0 !== n.offset && n.offset.top && n.offset.left ? function () {
                                    u.position = "absolute";
                                    var i = e.cache("target-offsetparent", function () {
                                        return l(e.target)
                                    });
                                    l(e.element) !== i && D(function () {
                                        e.element.parentNode.removeChild(e.element), i.appendChild(e.element)
                                    }), f(n.offset, t.offset), d = !0
                                }() : (u.position = "absolute", f({
                                    top: !0,
                                    left: !0
                                }, t.page)), !d) if (this.options.bodyElement) this.options.bodyElement.appendChild(this.element); else {
                                for (var p = !0, h = this.element.parentNode; h && 1 === h.nodeType && "BODY" !== h.tagName;) {
                                    if ("static" !== getComputedStyle(h).position) {
                                        p = !1;
                                        break
                                    }
                                    h = h.parentNode
                                }
                                p || (this.element.parentNode.removeChild(this.element), this.element.ownerDocument.body.appendChild(this.element))
                            }
                            var m = {}, g = !1;
                            for (var r in u) {
                                var v = u[r];
                                this.element.style[r] !== v && (g = !0, m[r] = v)
                            }
                            g && D(function () {
                                c(e.element.style, m), e.trigger("repositioned")
                            })
                        }
                    }
                }]), e
            }(P);
        Q.modules = [], E.position = R;
        var G = c(Q, E), L = function () {
                function t(t, e) {
                    var n = [], i = !0, r = !1, o = void 0;
                    try {
                        for (var s, a = t[Symbol.iterator](); !(i = (s = a.next()).done) && (n.push(s.value), !e || n.length !== e); i = !0) ;
                    } catch (t) {
                        r = !0, o = t
                    } finally {
                        try {
                            !i && a.return && a.return()
                        } finally {
                            if (r) throw o
                        }
                    }
                    return n
                }

                return function (e, n) {
                    if (Array.isArray(e)) return e;
                    if (Symbol.iterator in Object(e)) return t(e, n);
                    throw new TypeError("Invalid attempt to destructure non-iterable instance")
                }
            }(), B = E.Utils, a = B.getBounds, c = B.extend, g = B.updateClasses, D = B.defer,
            Y = ["left", "top", "right", "bottom"];
        E.modules.push({
            position: function (t) {
                var e = this, n = t.top, i = t.left, r = t.targetAttachment;
                if (!this.options.constraints) return !0;
                var o = this.cache("element-bounds", function () {
                    return a(e.element)
                }), s = o.height, l = o.width;
                if (0 === l && 0 === s && void 0 !== this.lastSize) {
                    var u = this.lastSize;
                    l = u.width, s = u.height
                }
                var f = this.cache("target-bounds", function () {
                    return e.getTargetBounds()
                }), d = f.height, p = f.width, h = [this.getClass("pinned"), this.getClass("out-of-bounds")];
                this.options.constraints.forEach(function (t) {
                    var e = t.outOfBoundsClass, n = t.pinnedClass;
                    e && h.push(e), n && h.push(n)
                }), h.forEach(function (t) {
                    ["left", "top", "right", "bottom"].forEach(function (e) {
                        h.push(t + "-" + e)
                    })
                });
                var m = [], v = c({}, r), y = c({}, this.attachment);
                return this.options.constraints.forEach(function (t) {
                    var o = t.to, a = t.attachment, u = t.pin;
                    void 0 === a && (a = "");
                    var c = void 0, f = void 0;
                    if (a.indexOf(" ") >= 0) {
                        var h = a.split(" "), g = L(h, 2);
                        f = g[0], c = g[1]
                    } else c = f = a;
                    var b = w(e, o);
                    "target" !== f && "both" !== f || (n < b[1] && "top" === v.top && (n += d, v.top = "bottom"), n + s > b[3] && "bottom" === v.top && (n -= d, v.top = "top")), "together" === f && ("top" === v.top && ("bottom" === y.top && n < b[1] ? (n += d, v.top = "bottom", n += s, y.top = "top") : "top" === y.top && n + s > b[3] && n - (s - d) >= b[1] && (n -= s - d, v.top = "bottom", y.top = "bottom")), "bottom" === v.top && ("top" === y.top && n + s > b[3] ? (n -= d, v.top = "top", n -= s, y.top = "bottom") : "bottom" === y.top && n < b[1] && n + (2 * s - d) <= b[3] && (n += s - d, v.top = "top", y.top = "top")), "middle" === v.top && (n + s > b[3] && "top" === y.top ? (n -= s, y.top = "bottom") : n < b[1] && "bottom" === y.top && (n += s, y.top = "top"))), "target" !== c && "both" !== c || (i < b[0] && "left" === v.left && (i += p, v.left = "right"), i + l > b[2] && "right" === v.left && (i -= p, v.left = "left")), "together" === c && (i < b[0] && "left" === v.left ? "right" === y.left ? (i += p, v.left = "right", i += l, y.left = "left") : "left" === y.left && (i += p, v.left = "right", i -= l, y.left = "right") : i + l > b[2] && "right" === v.left ? "left" === y.left ? (i -= p, v.left = "left", i -= l, y.left = "right") : "right" === y.left && (i -= p, v.left = "left", i += l, y.left = "left") : "center" === v.left && (i + l > b[2] && "left" === y.left ? (i -= l, y.left = "right") : i < b[0] && "right" === y.left && (i += l, y.left = "left"))), "element" !== f && "both" !== f || (n < b[1] && "bottom" === y.top && (n += s, y.top = "top"), n + s > b[3] && "top" === y.top && (n -= s, y.top = "bottom")), "element" !== c && "both" !== c || (i < b[0] && ("right" === y.left ? (i += l, y.left = "left") : "center" === y.left && (i += l / 2, y.left = "left")), i + l > b[2] && ("left" === y.left ? (i -= l, y.left = "right") : "center" === y.left && (i -= l / 2, y.left = "right"))), "string" == typeof u ? u = u.split(",").map(function (t) {
                        return t.trim()
                    }) : !0 === u && (u = ["top", "left", "right", "bottom"]), u = u || [];
                    var _ = [], x = [];
                    n < b[1] && (u.indexOf("top") >= 0 ? (n = b[1], _.push("top")) : x.push("top")), n + s > b[3] && (u.indexOf("bottom") >= 0 ? (n = b[3] - s, _.push("bottom")) : x.push("bottom")), i < b[0] && (u.indexOf("left") >= 0 ? (i = b[0], _.push("left")) : x.push("left")), i + l > b[2] && (u.indexOf("right") >= 0 ? (i = b[2] - l, _.push("right")) : x.push("right")), _.length && function () {
                        var t = void 0;
                        t = void 0 !== e.options.pinnedClass ? e.options.pinnedClass : e.getClass("pinned"), m.push(t), _.forEach(function (e) {
                            m.push(t + "-" + e)
                        })
                    }(), x.length && function () {
                        var t = void 0;
                        t = void 0 !== e.options.outOfBoundsClass ? e.options.outOfBoundsClass : e.getClass("out-of-bounds"), m.push(t), x.forEach(function (e) {
                            m.push(t + "-" + e)
                        })
                    }(), (_.indexOf("left") >= 0 || _.indexOf("right") >= 0) && (y.left = v.left = !1), (_.indexOf("top") >= 0 || _.indexOf("bottom") >= 0) && (y.top = v.top = !1), v.top === r.top && v.left === r.left && y.top === e.attachment.top && y.left === e.attachment.left || (e.updateAttachClasses(y, v), e.trigger("update", {
                        attachment: y,
                        targetAttachment: v
                    }))
                }), D(function () {
                    !1 !== e.options.addTargetClasses && g(e.target, m, h), g(e.element, m, h)
                }), {top: n, left: i}
            }
        });
        var B = E.Utils, a = B.getBounds, g = B.updateClasses, D = B.defer;
        E.modules.push({
            position: function (t) {
                var e = this, n = t.top, i = t.left, r = this.cache("element-bounds", function () {
                    return a(e.element)
                }), o = r.height, s = r.width, l = this.getTargetBounds(), u = n + o, c = i + s, f = [];
                n <= l.bottom && u >= l.top && ["left", "right"].forEach(function (t) {
                    var e = l[t];
                    e !== i && e !== c || f.push(t)
                }), i <= l.right && c >= l.left && ["top", "bottom"].forEach(function (t) {
                    var e = l[t];
                    e !== n && e !== u || f.push(t)
                });
                var d = [], p = [], h = ["left", "top", "right", "bottom"];
                return d.push(this.getClass("abutted")), h.forEach(function (t) {
                    d.push(e.getClass("abutted") + "-" + t)
                }), f.length && p.push(this.getClass("abutted")), f.forEach(function (t) {
                    p.push(e.getClass("abutted") + "-" + t)
                }), D(function () {
                    !1 !== e.options.addTargetClasses && g(e.target, p, d), g(e.element, p, d)
                }), !0
            }
        });
        var L = function () {
            function t(t, e) {
                var n = [], i = !0, r = !1, o = void 0;
                try {
                    for (var s, a = t[Symbol.iterator](); !(i = (s = a.next()).done) && (n.push(s.value), !e || n.length !== e); i = !0) ;
                } catch (t) {
                    r = !0, o = t
                } finally {
                    try {
                        !i && a.return && a.return()
                    } finally {
                        if (r) throw o
                    }
                }
                return n
            }

            return function (e, n) {
                if (Array.isArray(e)) return e;
                if (Symbol.iterator in Object(e)) return t(e, n);
                throw new TypeError("Invalid attempt to destructure non-iterable instance")
            }
        }();
        return E.modules.push({
            position: function (t) {
                var e = t.top, n = t.left;
                if (this.options.shift) {
                    var i = this.options.shift;
                    "function" == typeof this.options.shift && (i = this.options.shift.call(this, {top: e, left: n}));
                    var r = void 0, o = void 0;
                    if ("string" == typeof i) {
                        i = i.split(" "), i[1] = i[1] || i[0];
                        var s = i, a = L(s, 2);
                        r = a[0], o = a[1], r = parseFloat(r, 10), o = parseFloat(o, 10)
                    } else r = i.top, o = i.left;
                    return e += r, n += o, {top: e, left: n}
                }
            }
        }), G
    })
}, function (t, e, n) {
    "use strict";
    var i;
    i = function () {
        return this
    }();
    try {
        i = i || Function("return this")() || (0, eval)("this")
    } catch (t) {
        "object" == typeof window && (i = window)
    }
    t.exports = i
}, function (t, e, n) {
    (function (e) {
        t.exports = e.Tether = n(23)
    }).call(e, n(24))
}, function (t, e, n) {
    n(5), t.exports = n(6)
}]);
!function (a, b) {
    function e(b, c) {
        var d, e, g, h = b.nodeName.toLowerCase();
        return "area" === h ? (d = b.parentNode, e = d.name, !(!b.href || !e || "map" !== d.nodeName.toLowerCase()) && (g = a("img[usemap=#" + e + "]")[0], !!g && f(g))) : (/input|select|textarea|button|object/.test(h) ? !b.disabled : "a" === h ? b.href || c : c) && f(b)
    }

    function f(b) {
        return a.expr.filters.visible(b) && !a(b).parents().addBack().filter(function () {
            return "hidden" === a.css(this, "visibility")
        }).length
    }

    var c = 0, d = /^ui-id-\d+$/;
    a.ui = a.ui || {}, a.extend(a.ui, {
        version: "1.10.3",
        keyCode: {
            BACKSPACE: 8,
            COMMA: 188,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            LEFT: 37,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SPACE: 32,
            TAB: 9,
            UP: 38
        }
    }), a.fn.extend({
        focus: function (b) {
            return function (c, d) {
                return "number" == typeof c ? this.each(function () {
                    var b = this;
                    setTimeout(function () {
                        a(b).focus(), d && d.call(b)
                    }, c)
                }) : b.apply(this, arguments)
            }
        }(a.fn.focus), scrollParent: function () {
            var b;
            return b = a.ui.ie && /(static|relative)/.test(this.css("position")) || /absolute/.test(this.css("position")) ? this.parents().filter(function () {
                return /(relative|absolute|fixed)/.test(a.css(this, "position")) && /(auto|scroll)/.test(a.css(this, "overflow") + a.css(this, "overflow-y") + a.css(this, "overflow-x"))
            }).eq(0) : this.parents().filter(function () {
                return /(auto|scroll)/.test(a.css(this, "overflow") + a.css(this, "overflow-y") + a.css(this, "overflow-x"))
            }).eq(0), /fixed/.test(this.css("position")) || !b.length ? a(document) : b
        }, zIndex: function (c) {
            if (c !== b) return this.css("zIndex", c);
            if (this.length) for (var e, f, d = a(this[0]); d.length && d[0] !== document;) {
                if (e = d.css("position"), ("absolute" === e || "relative" === e || "fixed" === e) && (f = parseInt(d.css("zIndex"), 10), !isNaN(f) && 0 !== f)) return f;
                d = d.parent()
            }
            return 0
        }, uniqueId: function () {
            return this.each(function () {
                this.id || (this.id = "ui-id-" + ++c)
            })
        }, removeUniqueId: function () {
            return this.each(function () {
                d.test(this.id) && a(this).removeAttr("id")
            })
        }
    }), a.extend(a.expr[":"], {
        data: a.expr.createPseudo ? a.expr.createPseudo(function (b) {
            return function (c) {
                return !!a.data(c, b)
            }
        }) : function (b, c, d) {
            return !!a.data(b, d[3])
        }, focusable: function (b) {
            return e(b, !isNaN(a.attr(b, "tabindex")))
        }, tabbable: function (b) {
            var c = a.attr(b, "tabindex"), d = isNaN(c);
            return (d || c >= 0) && e(b, !d)
        }
    }), a("<a>").outerWidth(1).jquery || a.each(["Width", "Height"], function (c, d) {
        function h(b, c, d, f) {
            return a.each(e, function () {
                c -= parseFloat(a.css(b, "padding" + this)) || 0, d && (c -= parseFloat(a.css(b, "border" + this + "Width")) || 0), f && (c -= parseFloat(a.css(b, "margin" + this)) || 0)
            }), c
        }

        var e = "Width" === d ? ["Left", "Right"] : ["Top", "Bottom"], f = d.toLowerCase(), g = {
            innerWidth: a.fn.innerWidth,
            innerHeight: a.fn.innerHeight,
            outerWidth: a.fn.outerWidth,
            outerHeight: a.fn.outerHeight
        };
        a.fn["inner" + d] = function (c) {
            return c === b ? g["inner" + d].call(this) : this.each(function () {
                a(this).css(f, h(this, c) + "px")
            })
        }, a.fn["outer" + d] = function (b, c) {
            return "number" != typeof b ? g["outer" + d].call(this, b) : this.each(function () {
                a(this).css(f, h(this, b, !0, c) + "px")
            })
        }
    }), a.fn.addBack || (a.fn.addBack = function (a) {
        return this.add(null == a ? this.prevObject : this.prevObject.filter(a))
    }), a("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (a.fn.removeData = function (b) {
        return function (c) {
            return arguments.length ? b.call(this, a.camelCase(c)) : b.call(this)
        }
    }(a.fn.removeData)), a.ui.ie = !!/msie [\w.]+/.exec(navigator.userAgent.toLowerCase()), a.support.selectstart = "onselectstart" in document.createElement("div"), a.fn.extend({
        disableSelection: function () {
            return this.bind((a.support.selectstart ? "selectstart" : "mousedown") + ".ui-disableSelection", function (a) {
                a.preventDefault()
            })
        }, enableSelection: function () {
            return this.unbind(".ui-disableSelection")
        }
    }), a.extend(a.ui, {
        plugin: {
            add: function (b, c, d) {
                var e, f = a.ui[b].prototype;
                for (e in d) f.plugins[e] = f.plugins[e] || [], f.plugins[e].push([c, d[e]])
            }, call: function (a, b, c) {
                var d, e = a.plugins[b];
                if (e && a.element[0].parentNode && 11 !== a.element[0].parentNode.nodeType) for (d = 0; d < e.length; d++) a.options[e[d][0]] && e[d][1].apply(a.element, c)
            }
        }, hasScroll: function (b, c) {
            if ("hidden" === a(b).css("overflow")) return !1;
            var d = c && "left" === c ? "scrollLeft" : "scrollTop", e = !1;
            return b[d] > 0 || (b[d] = 1, e = b[d] > 0, b[d] = 0, e)
        }
    })
}(jQuery), function (a, b) {
    var c = 0, d = Array.prototype.slice, e = a.cleanData;
    a.cleanData = function (b) {
        for (var d, c = 0; null != (d = b[c]); c++) try {
            a(d).triggerHandler("remove")
        } catch (a) {
        }
        e(b)
    }, a.widget = function (b, c, d) {
        var e, f, g, h, i = {}, j = b.split(".")[0];
        b = b.split(".")[1], e = j + "-" + b, d || (d = c, c = a.Widget), a.expr[":"][e.toLowerCase()] = function (b) {
            return !!a.data(b, e)
        }, a[j] = a[j] || {}, f = a[j][b], g = a[j][b] = function (a, b) {
            return this._createWidget ? void(arguments.length && this._createWidget(a, b)) : new g(a, b)
        }, a.extend(g, f, {
            version: d.version,
            _proto: a.extend({}, d),
            _childConstructors: []
        }), h = new c, h.options = a.widget.extend({}, h.options), a.each(d, function (b, d) {
            return a.isFunction(d) ? void(i[b] = function () {
                var a = function () {
                    return c.prototype[b].apply(this, arguments)
                }, e = function (a) {
                    return c.prototype[b].apply(this, a)
                };
                return function () {
                    var f, b = this._super, c = this._superApply;
                    return this._super = a, this._superApply = e, f = d.apply(this, arguments), this._super = b, this._superApply = c, f
                }
            }()) : void(i[b] = d)
        }), g.prototype = a.widget.extend(h, {widgetEventPrefix: f ? h.widgetEventPrefix : b}, i, {
            constructor: g,
            namespace: j,
            widgetName: b,
            widgetFullName: e
        }), f ? (a.each(f._childConstructors, function (b, c) {
            var d = c.prototype;
            a.widget(d.namespace + "." + d.widgetName, g, c._proto)
        }), delete f._childConstructors) : c._childConstructors.push(g), a.widget.bridge(b, g)
    }, a.widget.extend = function (c) {
        for (var h, i, e = d.call(arguments, 1), f = 0, g = e.length; f < g; f++) for (h in e[f]) i = e[f][h], e[f].hasOwnProperty(h) && i !== b && (a.isPlainObject(i) ? c[h] = a.isPlainObject(c[h]) ? a.widget.extend({}, c[h], i) : a.widget.extend({}, i) : c[h] = i);
        return c
    }, a.widget.bridge = function (c, e) {
        var f = e.prototype.widgetFullName || c;
        a.fn[c] = function (g) {
            var h = "string" == typeof g, i = d.call(arguments, 1), j = this;
            return g = !h && i.length ? a.widget.extend.apply(null, [g].concat(i)) : g, h ? this.each(function () {
                var d, e = a.data(this, f);
                return e ? a.isFunction(e[g]) && "_" !== g.charAt(0) ? (d = e[g].apply(e, i), d !== e && d !== b ? (j = d && d.jquery ? j.pushStack(d.get()) : d, !1) : void 0) : a.error("no such method '" + g + "' for " + c + " widget instance") : a.error("cannot call methods on " + c + " prior to initialization; attempted to call method '" + g + "'")
            }) : this.each(function () {
                var b = a.data(this, f);
                b ? b.option(g || {})._init() : a.data(this, f, new e(g, this))
            }), j
        }
    }, a.Widget = function () {
    }, a.Widget._childConstructors = [], a.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {disabled: !1, create: null},
        _createWidget: function (b, d) {
            d = a(d || this.defaultElement || this)[0], this.element = a(d), this.uuid = c++, this.eventNamespace = "." + this.widgetName + this.uuid, this.options = a.widget.extend({}, this.options, this._getCreateOptions(), b), this.bindings = a(), this.hoverable = a(), this.focusable = a(), d !== this && (a.data(d, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function (a) {
                    a.target === d && this.destroy()
                }
            }), this.document = a(d.style ? d.ownerDocument : d.document || d), this.window = a(this.document[0].defaultView || this.document[0].parentWindow)), this._create(), this._trigger("create", null, this._getCreateEventData()), this._init()
        },
        _getCreateOptions: a.noop,
        _getCreateEventData: a.noop,
        _create: a.noop,
        _init: a.noop,
        destroy: function () {
            this._destroy(), this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(a.camelCase(this.widgetFullName)), this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled ui-state-disabled"), this.bindings.unbind(this.eventNamespace), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")
        },
        _destroy: a.noop,
        widget: function () {
            return this.element
        },
        option: function (c, d) {
            var f, g, h, e = c;
            if (0 === arguments.length) return a.widget.extend({}, this.options);
            if ("string" == typeof c) if (e = {}, f = c.split("."), c = f.shift(), f.length) {
                for (g = e[c] = a.widget.extend({}, this.options[c]), h = 0; h < f.length - 1; h++) g[f[h]] = g[f[h]] || {}, g = g[f[h]];
                if (c = f.pop(), d === b) return g[c] === b ? null : g[c];
                g[c] = d
            } else {
                if (d === b) return this.options[c] === b ? null : this.options[c];
                e[c] = d
            }
            return this._setOptions(e), this
        },
        _setOptions: function (a) {
            var b;
            for (b in a) this._setOption(b, a[b]);
            return this
        },
        _setOption: function (a, b) {
            return this.options[a] = b, "disabled" === a && (this.widget().toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !!b).attr("aria-disabled", b), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")), this
        },
        enable: function () {
            return this._setOption("disabled", !1)
        },
        disable: function () {
            return this._setOption("disabled", !0)
        },
        _on: function (b, c, d) {
            var e, f = this;
            "boolean" != typeof b && (d = c, c = b, b = !1), d ? (c = e = a(c), this.bindings = this.bindings.add(c)) : (d = c, c = this.element, e = this.widget()), a.each(d, function (d, g) {
                function h() {
                    if (b || f.options.disabled !== !0 && !a(this).hasClass("ui-state-disabled")) return ("string" == typeof g ? f[g] : g).apply(f, arguments)
                }

                "string" != typeof g && (h.guid = g.guid = g.guid || h.guid || a.guid++);
                var i = d.match(/^(\w+)\s*(.*)$/), j = i[1] + f.eventNamespace, k = i[2];
                k ? e.delegate(k, j, h) : c.bind(j, h)
            })
        },
        _off: function (a, b) {
            b = (b || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, a.unbind(b).undelegate(b)
        },
        _delay: function (a, b) {
            function c() {
                return ("string" == typeof a ? d[a] : a).apply(d, arguments)
            }

            var d = this;
            return setTimeout(c, b || 0)
        },
        _hoverable: function (b) {
            this.hoverable = this.hoverable.add(b), this._on(b, {
                mouseenter: function (b) {
                    a(b.currentTarget).addClass("ui-state-hover")
                }, mouseleave: function (b) {
                    a(b.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        _focusable: function (b) {
            this.focusable = this.focusable.add(b), this._on(b, {
                focusin: function (b) {
                    a(b.currentTarget).addClass("ui-state-focus")
                }, focusout: function (b) {
                    a(b.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        _trigger: function (b, c, d) {
            var e, f, g = this.options[b];
            if (d = d || {}, c = a.Event(c), c.type = (b === this.widgetEventPrefix ? b : this.widgetEventPrefix + b).toLowerCase(), c.target = this.element[0], f = c.originalEvent) for (e in f) e in c || (c[e] = f[e]);
            return this.element.trigger(c, d), !(a.isFunction(g) && g.apply(this.element[0], [c].concat(d)) === !1 || c.isDefaultPrevented())
        }
    }, a.each({show: "fadeIn", hide: "fadeOut"}, function (b, c) {
        a.Widget.prototype["_" + b] = function (d, e, f) {
            "string" == typeof e && (e = {effect: e});
            var g, h = e ? e === !0 || "number" == typeof e ? c : e.effect || c : b;
            e = e || {}, "number" == typeof e && (e = {duration: e}), g = !a.isEmptyObject(e), e.complete = f, e.delay && d.delay(e.delay), g && a.effects && a.effects.effect[h] ? d[b](e) : h !== b && d[h] ? d[h](e.duration, e.easing, f) : d.queue(function (c) {
                a(this)[b](), f && f.call(d[0]), c()
            })
        }
    })
}(jQuery), function (a, b) {
    var c = !1;
    a(document).mouseup(function () {
        c = !1
    }), a.widget("ui.mouse", {
        version: "1.10.3",
        options: {cancel: "input,textarea,button,select,option", distance: 1, delay: 0},
        _mouseInit: function () {
            var b = this;
            this.element.bind("mousedown." + this.widgetName, function (a) {
                return b._mouseDown(a)
            }).bind("click." + this.widgetName, function (c) {
                if (!0 === a.data(c.target, b.widgetName + ".preventClickEvent")) return a.removeData(c.target, b.widgetName + ".preventClickEvent"), c.stopImmediatePropagation(), !1
            }), this.started = !1
        },
        _mouseDestroy: function () {
            this.element.unbind("." + this.widgetName), this._mouseMoveDelegate && a(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate)
        },
        _mouseDown: function (b) {
            if (!c) {
                this._mouseStarted && this._mouseUp(b), this._mouseDownEvent = b;
                var d = this, e = 1 === b.which,
                    f = !("string" != typeof this.options.cancel || !b.target.nodeName) && a(b.target).closest(this.options.cancel).length;
                return !(e && !f && this._mouseCapture(b)) || (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function () {
                    d.mouseDelayMet = !0
                }, this.options.delay)), this._mouseDistanceMet(b) && this._mouseDelayMet(b) && (this._mouseStarted = this._mouseStart(b) !== !1, !this._mouseStarted) ? (b.preventDefault(), !0) : (!0 === a.data(b.target, this.widgetName + ".preventClickEvent") && a.removeData(b.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function (a) {
                    return d._mouseMove(a)
                }, this._mouseUpDelegate = function (a) {
                    return d._mouseUp(a)
                }, a(document).bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), b.preventDefault(), c = !0, !0))
            }
        },
        _mouseMove: function (b) {
            return a.ui.ie && (!document.documentMode || document.documentMode < 9) && !b.button ? this._mouseUp(b) : this._mouseStarted ? (this._mouseDrag(b), b.preventDefault()) : (this._mouseDistanceMet(b) && this._mouseDelayMet(b) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, b) !== !1, this._mouseStarted ? this._mouseDrag(b) : this._mouseUp(b)), !this._mouseStarted)
        },
        _mouseUp: function (b) {
            return a(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate), this._mouseStarted && (this._mouseStarted = !1, b.target === this._mouseDownEvent.target && a.data(b.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(b)), !1
        },
        _mouseDistanceMet: function (a) {
            return Math.max(Math.abs(this._mouseDownEvent.pageX - a.pageX), Math.abs(this._mouseDownEvent.pageY - a.pageY)) >= this.options.distance
        },
        _mouseDelayMet: function () {
            return this.mouseDelayMet
        },
        _mouseStart: function () {
        },
        _mouseDrag: function () {
        },
        _mouseStop: function () {
        },
        _mouseCapture: function () {
            return !0
        }
    })
}(jQuery), function (a, b) {
    a.widget("ui.draggable", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "drag",
        options: {
            addClasses: !0,
            appendTo: "parent",
            axis: !1,
            connectToSortable: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            iframeFix: !1,
            opacity: !1,
            refreshPositions: !1,
            revert: !1,
            revertDuration: 500,
            scope: "default",
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: !1,
            snapMode: "both",
            snapTolerance: 20,
            stack: !1,
            zIndex: !1,
            drag: null,
            start: null,
            stop: null
        },
        _create: function () {
            "original" !== this.options.helper || /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative"), this.options.addClasses && this.element.addClass("ui-draggable"), this.options.disabled && this.element.addClass("ui-draggable-disabled"), this._mouseInit()
        },
        _destroy: function () {
            this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"), this._mouseDestroy()
        },
        _mouseCapture: function (b) {
            var c = this.options;
            return !(this.helper || c.disabled || a(b.target).closest(".ui-resizable-handle").length > 0) && (this.handle = this._getHandle(b), !!this.handle && (a(c.iframeFix === !0 ? "iframe" : c.iframeFix).each(function () {
                a("<div class='ui-draggable-iframeFix' style='background: #fff;'></div>").css({
                    width: this.offsetWidth + "px",
                    height: this.offsetHeight + "px",
                    position: "absolute",
                    opacity: "0.001",
                    zIndex: 1e3
                }).css(a(this).offset()).appendTo("body")
            }), !0))
        },
        _mouseStart: function (b) {
            var c = this.options;
            return this.helper = this._createHelper(b), this.helper.addClass("ui-draggable-dragging"), this._cacheHelperProportions(), a.ui.ddmanager && (a.ui.ddmanager.current = this), this._cacheMargins(), this.cssPosition = this.helper.css("position"), this.scrollParent = this.helper.scrollParent(), this.offsetParent = this.helper.offsetParent(), this.offsetParentCssPosition = this.offsetParent.css("position"), this.offset = this.positionAbs = this.element.offset(), this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            }, this.offset.scroll = !1, a.extend(this.offset, {
                click: {
                    left: b.pageX - this.offset.left,
                    top: b.pageY - this.offset.top
                }, parent: this._getParentOffset(), relative: this._getRelativeOffset()
            }), this.originalPosition = this.position = this._generatePosition(b), this.originalPageX = b.pageX, this.originalPageY = b.pageY, c.cursorAt && this._adjustOffsetFromHelper(c.cursorAt), this._setContainment(), this._trigger("start", b) === !1 ? (this._clear(), !1) : (this._cacheHelperProportions(), a.ui.ddmanager && !c.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b), this._mouseDrag(b, !0), a.ui.ddmanager && a.ui.ddmanager.dragStart(this, b), !0)
        },
        _mouseDrag: function (b, c) {
            if ("fixed" === this.offsetParentCssPosition && (this.offset.parent = this._getParentOffset()), this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), !c) {
                var d = this._uiHash();
                if (this._trigger("drag", b, d) === !1) return this._mouseUp({}), !1;
                this.position = d.position
            }
            return this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"), a.ui.ddmanager && a.ui.ddmanager.drag(this, b), !1
        },
        _mouseStop: function (b) {
            var c = this, d = !1;
            return a.ui.ddmanager && !this.options.dropBehaviour && (d = a.ui.ddmanager.drop(this, b)), this.dropped && (d = this.dropped, this.dropped = !1), !("original" === this.options.helper && !a.contains(this.element[0].ownerDocument, this.element[0])) && ("invalid" === this.options.revert && !d || "valid" === this.options.revert && d || this.options.revert === !0 || a.isFunction(this.options.revert) && this.options.revert.call(this.element, d) ? a(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function () {
                c._trigger("stop", b) !== !1 && c._clear()
            }) : this._trigger("stop", b) !== !1 && this._clear(), !1)
        },
        _mouseUp: function (b) {
            return a("div.ui-draggable-iframeFix").each(function () {
                this.parentNode.removeChild(this)
            }), a.ui.ddmanager && a.ui.ddmanager.dragStop(this, b), a.ui.mouse.prototype._mouseUp.call(this, b)
        },
        cancel: function () {
            return this.helper.is(".ui-draggable-dragging") ? this._mouseUp({}) : this._clear(), this
        },
        _getHandle: function (b) {
            return !this.options.handle || !!a(b.target).closest(this.element.find(this.options.handle)).length
        },
        _createHelper: function (b) {
            var c = this.options,
                d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b])) : "clone" === c.helper ? this.element.clone().removeAttr("id") : this.element;
            return d.parents("body").length || d.appendTo("parent" === c.appendTo ? this.element[0].parentNode : c.appendTo), d[0] === this.element[0] || /(fixed|absolute)/.test(d.css("position")) || d.css("position", "absolute"), d
        },
        _adjustOffsetFromHelper: function (b) {
            "string" == typeof b && (b = b.split(" ")), a.isArray(b) && (b = {
                left: +b[0],
                top: +b[1] || 0
            }), "left" in b && (this.offset.click.left = b.left + this.margins.left), "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left), "top" in b && (this.offset.click.top = b.top + this.margins.top), "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top)
        },
        _getParentOffset: function () {
            var b = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()), (this.offsetParent[0] === document.body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = {
                top: 0,
                left: 0
            }), {
                top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function () {
            if ("relative" === this.cssPosition) {
                var a = this.element.position();
                return {
                    top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {top: 0, left: 0}
        },
        _cacheMargins: function () {
            this.margins = {
                left: parseInt(this.element.css("marginLeft"), 10) || 0,
                top: parseInt(this.element.css("marginTop"), 10) || 0,
                right: parseInt(this.element.css("marginRight"), 10) || 0,
                bottom: parseInt(this.element.css("marginBottom"), 10) || 0
            }
        },
        _cacheHelperProportions: function () {
            this.helperProportions = {width: this.helper.outerWidth(), height: this.helper.outerHeight()}
        },
        _setContainment: function () {
            var b, c, d, e = this.options;
            return e.containment ? "window" === e.containment ? void(this.containment = [a(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, a(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, a(window).scrollLeft() + a(window).width() - this.helperProportions.width - this.margins.left, a(window).scrollTop() + (a(window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]) : "document" === e.containment ? void(this.containment = [0, 0, a(document).width() - this.helperProportions.width - this.margins.left, (a(document).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]) : e.containment.constructor === Array ? void(this.containment = e.containment) : ("parent" === e.containment && (e.containment = this.helper[0].parentNode), c = a(e.containment), d = c[0], void(d && (b = "hidden" !== c.css("overflow"), this.containment = [(parseInt(c.css("borderLeftWidth"), 10) || 0) + (parseInt(c.css("paddingLeft"), 10) || 0), (parseInt(c.css("borderTopWidth"), 10) || 0) + (parseInt(c.css("paddingTop"), 10) || 0), (b ? Math.max(d.scrollWidth, d.offsetWidth) : d.offsetWidth) - (parseInt(c.css("borderRightWidth"), 10) || 0) - (parseInt(c.css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (b ? Math.max(d.scrollHeight, d.offsetHeight) : d.offsetHeight) - (parseInt(c.css("borderBottomWidth"), 10) || 0) - (parseInt(c.css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relative_container = c))) : void(this.containment = null)
        },
        _convertPositionTo: function (b, c) {
            c || (c = this.position);
            var d = "absolute" === b ? 1 : -1,
                e = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent;
            return this.offset.scroll || (this.offset.scroll = {
                top: e.scrollTop(),
                left: e.scrollLeft()
            }), {
                top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : this.offset.scroll.top) * d,
                left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : this.offset.scroll.left) * d
            }
        },
        _generatePosition: function (b) {
            var c, d, e, f, g = this.options,
                h = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                i = b.pageX, j = b.pageY;
            return this.offset.scroll || (this.offset.scroll = {
                top: h.scrollTop(),
                left: h.scrollLeft()
            }), this.originalPosition && (this.containment && (this.relative_container ? (d = this.relative_container.offset(), c = [this.containment[0] + d.left, this.containment[1] + d.top, this.containment[2] + d.left, this.containment[3] + d.top]) : c = this.containment, b.pageX - this.offset.click.left < c[0] && (i = c[0] + this.offset.click.left), b.pageY - this.offset.click.top < c[1] && (j = c[1] + this.offset.click.top), b.pageX - this.offset.click.left > c[2] && (i = c[2] + this.offset.click.left), b.pageY - this.offset.click.top > c[3] && (j = c[3] + this.offset.click.top)), g.grid && (e = g.grid[1] ? this.originalPageY + Math.round((j - this.originalPageY) / g.grid[1]) * g.grid[1] : this.originalPageY, j = c ? e - this.offset.click.top >= c[1] || e - this.offset.click.top > c[3] ? e : e - this.offset.click.top >= c[1] ? e - g.grid[1] : e + g.grid[1] : e, f = g.grid[0] ? this.originalPageX + Math.round((i - this.originalPageX) / g.grid[0]) * g.grid[0] : this.originalPageX, i = c ? f - this.offset.click.left >= c[0] || f - this.offset.click.left > c[2] ? f : f - this.offset.click.left >= c[0] ? f - g.grid[0] : f + g.grid[0] : f)), {
                top: j - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : this.offset.scroll.top),
                left: i - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : this.offset.scroll.left)
            }
        },
        _clear: function () {
            this.helper.removeClass("ui-draggable-dragging"), this.helper[0] === this.element[0] || this.cancelHelperRemoval || this.helper.remove(), this.helper = null, this.cancelHelperRemoval = !1
        },
        _trigger: function (b, c, d) {
            return d = d || this._uiHash(), a.ui.plugin.call(this, b, [c, d]), "drag" === b && (this.positionAbs = this._convertPositionTo("absolute")), a.Widget.prototype._trigger.call(this, b, c, d)
        },
        plugins: {},
        _uiHash: function () {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            }
        }
    }), a.ui.plugin.add("draggable", "connectToSortable", {
        start: function (b, c) {
            var d = a(this).data("ui-draggable"), e = d.options, f = a.extend({}, c, {item: d.element});
            d.sortables = [], a(e.connectToSortable).each(function () {
                var c = a.data(this, "ui-sortable");
                c && !c.options.disabled && (d.sortables.push({
                    instance: c,
                    shouldRevert: c.options.revert
                }), c.refreshPositions(), c._trigger("activate", b, f))
            })
        }, stop: function (b, c) {
            var d = a(this).data("ui-draggable"), e = a.extend({}, c, {item: d.element});
            a.each(d.sortables, function () {
                this.instance.isOver ? (this.instance.isOver = 0, d.cancelHelperRemoval = !0, this.instance.cancelHelperRemoval = !1, this.shouldRevert && (this.instance.options.revert = this.shouldRevert), this.instance._mouseStop(b), this.instance.options.helper = this.instance.options._helper, "original" === d.options.helper && this.instance.currentItem.css({
                    top: "auto",
                    left: "auto"
                })) : (this.instance.cancelHelperRemoval = !1, this.instance._trigger("deactivate", b, e))
            })
        }, drag: function (b, c) {
            var d = a(this).data("ui-draggable"), e = this;
            a.each(d.sortables, function () {
                var f = !1, g = this;
                this.instance.positionAbs = d.positionAbs, this.instance.helperProportions = d.helperProportions, this.instance.offset.click = d.offset.click, this.instance._intersectsWith(this.instance.containerCache) && (f = !0, a.each(d.sortables, function () {
                    return this.instance.positionAbs = d.positionAbs, this.instance.helperProportions = d.helperProportions, this.instance.offset.click = d.offset.click, this !== g && this.instance._intersectsWith(this.instance.containerCache) && a.contains(g.instance.element[0], this.instance.element[0]) && (f = !1), f
                })), f ? (this.instance.isOver || (this.instance.isOver = 1, this.instance.currentItem = a(e).clone().removeAttr("id").appendTo(this.instance.element).data("ui-sortable-item", !0), this.instance.options._helper = this.instance.options.helper, this.instance.options.helper = function () {
                    return c.helper[0]
                }, b.target = this.instance.currentItem[0], this.instance._mouseCapture(b, !0), this.instance._mouseStart(b, !0, !0), this.instance.offset.click.top = d.offset.click.top, this.instance.offset.click.left = d.offset.click.left, this.instance.offset.parent.left -= d.offset.parent.left - this.instance.offset.parent.left, this.instance.offset.parent.top -= d.offset.parent.top - this.instance.offset.parent.top, d._trigger("toSortable", b), d.dropped = this.instance.element, d.currentItem = d.element, this.instance.fromOutside = d), this.instance.currentItem && this.instance._mouseDrag(b)) : this.instance.isOver && (this.instance.isOver = 0, this.instance.cancelHelperRemoval = !0, this.instance.options.revert = !1, this.instance._trigger("out", b, this.instance._uiHash(this.instance)), this.instance._mouseStop(b, !0), this.instance.options.helper = this.instance.options._helper, this.instance.currentItem.remove(), this.instance.placeholder && this.instance.placeholder.remove(), d._trigger("fromSortable", b), d.dropped = !1)
            })
        }
    }), a.ui.plugin.add("draggable", "cursor", {
        start: function () {
            var b = a("body"), c = a(this).data("ui-draggable").options;
            b.css("cursor") && (c._cursor = b.css("cursor")), b.css("cursor", c.cursor)
        }, stop: function () {
            var b = a(this).data("ui-draggable").options;
            b._cursor && a("body").css("cursor", b._cursor)
        }
    }), a.ui.plugin.add("draggable", "opacity", {
        start: function (b, c) {
            var d = a(c.helper), e = a(this).data("ui-draggable").options;
            d.css("opacity") && (e._opacity = d.css("opacity")), d.css("opacity", e.opacity)
        }, stop: function (b, c) {
            var d = a(this).data("ui-draggable").options;
            d._opacity && a(c.helper).css("opacity", d._opacity)
        }
    }), a.ui.plugin.add("draggable", "scroll", {
        start: function () {
            var b = a(this).data("ui-draggable");
            b.scrollParent[0] !== document && "HTML" !== b.scrollParent[0].tagName && (b.overflowOffset = b.scrollParent.offset())
        }, drag: function (b) {
            var c = a(this).data("ui-draggable"), d = c.options, e = !1;
            c.scrollParent[0] !== document && "HTML" !== c.scrollParent[0].tagName ? (d.axis && "x" === d.axis || (c.overflowOffset.top + c.scrollParent[0].offsetHeight - b.pageY < d.scrollSensitivity ? c.scrollParent[0].scrollTop = e = c.scrollParent[0].scrollTop + d.scrollSpeed : b.pageY - c.overflowOffset.top < d.scrollSensitivity && (c.scrollParent[0].scrollTop = e = c.scrollParent[0].scrollTop - d.scrollSpeed)), d.axis && "y" === d.axis || (c.overflowOffset.left + c.scrollParent[0].offsetWidth - b.pageX < d.scrollSensitivity ? c.scrollParent[0].scrollLeft = e = c.scrollParent[0].scrollLeft + d.scrollSpeed : b.pageX - c.overflowOffset.left < d.scrollSensitivity && (c.scrollParent[0].scrollLeft = e = c.scrollParent[0].scrollLeft - d.scrollSpeed))) : (d.axis && "x" === d.axis || (b.pageY - a(document).scrollTop() < d.scrollSensitivity ? e = a(document).scrollTop(a(document).scrollTop() - d.scrollSpeed) : a(window).height() - (b.pageY - a(document).scrollTop()) < d.scrollSensitivity && (e = a(document).scrollTop(a(document).scrollTop() + d.scrollSpeed))), d.axis && "y" === d.axis || (b.pageX - a(document).scrollLeft() < d.scrollSensitivity ? e = a(document).scrollLeft(a(document).scrollLeft() - d.scrollSpeed) : a(window).width() - (b.pageX - a(document).scrollLeft()) < d.scrollSensitivity && (e = a(document).scrollLeft(a(document).scrollLeft() + d.scrollSpeed)))), e !== !1 && a.ui.ddmanager && !d.dropBehaviour && a.ui.ddmanager.prepareOffsets(c, b)
        }
    }), a.ui.plugin.add("draggable", "snap", {
        start: function () {
            var b = a(this).data("ui-draggable"), c = b.options;
            b.snapElements = [], a(c.snap.constructor !== String ? c.snap.items || ":data(ui-draggable)" : c.snap).each(function () {
                var c = a(this), d = c.offset();
                this !== b.element[0] && b.snapElements.push({
                    item: this,
                    width: c.outerWidth(),
                    height: c.outerHeight(),
                    top: d.top,
                    left: d.left
                })
            })
        }, drag: function (b, c) {
            var d, e, f, g, h, i, j, k, l, m, n = a(this).data("ui-draggable"), o = n.options, p = o.snapTolerance,
                q = c.offset.left, r = q + n.helperProportions.width, s = c.offset.top,
                t = s + n.helperProportions.height;
            for (l = n.snapElements.length - 1; l >= 0; l--) h = n.snapElements[l].left, i = h + n.snapElements[l].width, j = n.snapElements[l].top, k = j + n.snapElements[l].height, r < h - p || q > i + p || t < j - p || s > k + p || !a.contains(n.snapElements[l].item.ownerDocument, n.snapElements[l].item) ? (n.snapElements[l].snapping && n.options.snap.release && n.options.snap.release.call(n.element, b, a.extend(n._uiHash(), {snapItem: n.snapElements[l].item})), n.snapElements[l].snapping = !1) : ("inner" !== o.snapMode && (d = Math.abs(j - t) <= p, e = Math.abs(k - s) <= p, f = Math.abs(h - r) <= p, g = Math.abs(i - q) <= p, d && (c.position.top = n._convertPositionTo("relative", {
                top: j - n.helperProportions.height,
                left: 0
            }).top - n.margins.top), e && (c.position.top = n._convertPositionTo("relative", {
                top: k,
                left: 0
            }).top - n.margins.top), f && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: h - n.helperProportions.width
            }).left - n.margins.left), g && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: i
            }).left - n.margins.left)), m = d || e || f || g, "outer" !== o.snapMode && (d = Math.abs(j - s) <= p, e = Math.abs(k - t) <= p, f = Math.abs(h - q) <= p, g = Math.abs(i - r) <= p, d && (c.position.top = n._convertPositionTo("relative", {
                top: j,
                left: 0
            }).top - n.margins.top), e && (c.position.top = n._convertPositionTo("relative", {
                top: k - n.helperProportions.height,
                left: 0
            }).top - n.margins.top), f && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: h
            }).left - n.margins.left), g && (c.position.left = n._convertPositionTo("relative", {
                top: 0,
                left: i - n.helperProportions.width
            }).left - n.margins.left)), !n.snapElements[l].snapping && (d || e || f || g || m) && n.options.snap.snap && n.options.snap.snap.call(n.element, b, a.extend(n._uiHash(), {snapItem: n.snapElements[l].item})), n.snapElements[l].snapping = d || e || f || g || m)
        }
    }), a.ui.plugin.add("draggable", "stack", {
        start: function () {
            var b, c = this.data("ui-draggable").options, d = a.makeArray(a(c.stack)).sort(function (b, c) {
                return (parseInt(a(b).css("zIndex"), 10) || 0) - (parseInt(a(c).css("zIndex"), 10) || 0)
            });
            d.length && (b = parseInt(a(d[0]).css("zIndex"), 10) || 0, a(d).each(function (c) {
                a(this).css("zIndex", b + c)
            }), this.css("zIndex", b + d.length))
        }
    }), a.ui.plugin.add("draggable", "zIndex", {
        start: function (b, c) {
            var d = a(c.helper), e = a(this).data("ui-draggable").options;
            d.css("zIndex") && (e._zIndex = d.css("zIndex")), d.css("zIndex", e.zIndex)
        }, stop: function (b, c) {
            var d = a(this).data("ui-draggable").options;
            d._zIndex && a(c.helper).css("zIndex", d._zIndex)
        }
    })
}(jQuery), function (a, b) {
    function c(a, b, c) {
        return a > b && a < b + c
    }

    a.widget("ui.droppable", {
        version: "1.10.3",
        widgetEventPrefix: "drop",
        options: {
            accept: "*",
            activeClass: !1,
            addClasses: !0,
            greedy: !1,
            hoverClass: !1,
            scope: "default",
            tolerance: "intersect",
            activate: null,
            deactivate: null,
            drop: null,
            out: null,
            over: null
        },
        _create: function () {
            var b = this.options, c = b.accept;
            this.isover = !1, this.isout = !0, this.accept = a.isFunction(c) ? c : function (a) {
                return a.is(c)
            }, this.proportions = {
                width: this.element[0].offsetWidth,
                height: this.element[0].offsetHeight
            }, a.ui.ddmanager.droppables[b.scope] = a.ui.ddmanager.droppables[b.scope] || [], a.ui.ddmanager.droppables[b.scope].push(this), b.addClasses && this.element.addClass("ui-droppable")
        },
        _destroy: function () {
            for (var b = 0, c = a.ui.ddmanager.droppables[this.options.scope]; b < c.length; b++) c[b] === this && c.splice(b, 1);
            this.element.removeClass("ui-droppable ui-droppable-disabled")
        },
        _setOption: function (b, c) {
            "accept" === b && (this.accept = a.isFunction(c) ? c : function (a) {
                return a.is(c)
            }), a.Widget.prototype._setOption.apply(this, arguments)
        },
        _activate: function (b) {
            var c = a.ui.ddmanager.current;
            this.options.activeClass && this.element.addClass(this.options.activeClass), c && this._trigger("activate", b, this.ui(c))
        },
        _deactivate: function (b) {
            var c = a.ui.ddmanager.current;
            this.options.activeClass && this.element.removeClass(this.options.activeClass), c && this._trigger("deactivate", b, this.ui(c))
        },
        _over: function (b) {
            var c = a.ui.ddmanager.current;
            c && (c.currentItem || c.element)[0] !== this.element[0] && this.accept.call(this.element[0], c.currentItem || c.element) && (this.options.hoverClass && this.element.addClass(this.options.hoverClass), this._trigger("over", b, this.ui(c)))
        },
        _out: function (b) {
            var c = a.ui.ddmanager.current;
            c && (c.currentItem || c.element)[0] !== this.element[0] && this.accept.call(this.element[0], c.currentItem || c.element) && (this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("out", b, this.ui(c)))
        },
        _drop: function (b, c) {
            var d = c || a.ui.ddmanager.current, e = !1;
            return !(!d || (d.currentItem || d.element)[0] === this.element[0]) && (this.element.find(":data(ui-droppable)").not(".ui-draggable-dragging").each(function () {
                var b = a.data(this, "ui-droppable");
                if (b.options.greedy && !b.options.disabled && b.options.scope === d.options.scope && b.accept.call(b.element[0], d.currentItem || d.element) && a.ui.intersect(d, a.extend(b, {offset: b.element.offset()}), b.options.tolerance)) return e = !0, !1
            }), !e && (!!this.accept.call(this.element[0], d.currentItem || d.element) && (this.options.activeClass && this.element.removeClass(this.options.activeClass), this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("drop", b, this.ui(d)), this.element)))
        },
        ui: function (a) {
            return {
                draggable: a.currentItem || a.element,
                helper: a.helper,
                position: a.position,
                offset: a.positionAbs
            }
        }
    }), a.ui.intersect = function (a, b, d) {
        if (!b.offset) return !1;
        var e, f, g = (a.positionAbs || a.position.absolute).left, h = g + a.helperProportions.width,
            i = (a.positionAbs || a.position.absolute).top, j = i + a.helperProportions.height, k = b.offset.left,
            l = k + b.proportions.width, m = b.offset.top, n = m + b.proportions.height;
        switch (d) {
            case"fit":
                return k <= g && h <= l && m <= i && j <= n;
            case"intersect":
                return k < g + a.helperProportions.width / 2 && h - a.helperProportions.width / 2 < l && m < i + a.helperProportions.height / 2 && j - a.helperProportions.height / 2 < n;
            case"pointer":
                return e = (a.positionAbs || a.position.absolute).left + (a.clickOffset || a.offset.click).left, f = (a.positionAbs || a.position.absolute).top + (a.clickOffset || a.offset.click).top, c(f, m, b.proportions.height) && c(e, k, b.proportions.width);
            case"touch":
                return (i >= m && i <= n || j >= m && j <= n || i < m && j > n) && (g >= k && g <= l || h >= k && h <= l || g < k && h > l);
            default:
                return !1
        }
    }, a.ui.ddmanager = {
        current: null, droppables: {default: []}, prepareOffsets: function (b, c) {
            var d, e, f = a.ui.ddmanager.droppables[b.options.scope] || [], g = c ? c.type : null,
                h = (b.currentItem || b.element).find(":data(ui-droppable)").addBack();
            a:for (d = 0; d < f.length; d++) if (!(f[d].options.disabled || b && !f[d].accept.call(f[d].element[0], b.currentItem || b.element))) {
                for (e = 0; e < h.length; e++) if (h[e] === f[d].element[0]) {
                    f[d].proportions.height = 0;
                    continue a
                }
                f[d].visible = "none" !== f[d].element.css("display"), f[d].visible && ("mousedown" === g && f[d]._activate.call(f[d], c), f[d].offset = f[d].element.offset(), f[d].proportions = {
                    width: f[d].element[0].offsetWidth,
                    height: f[d].element[0].offsetHeight
                })
            }
        }, drop: function (b, c) {
            var d = !1;
            return a.each((a.ui.ddmanager.droppables[b.options.scope] || []).slice(), function () {
                this.options && (!this.options.disabled && this.visible && a.ui.intersect(b, this, this.options.tolerance) && (d = this._drop.call(this, c) || d), !this.options.disabled && this.visible && this.accept.call(this.element[0], b.currentItem || b.element) && (this.isout = !0, this.isover = !1, this._deactivate.call(this, c)))
            }), d
        }, dragStart: function (b, c) {
            b.element.parentsUntil("body").bind("scroll.droppable", function () {
                b.options.refreshPositions || a.ui.ddmanager.prepareOffsets(b, c)
            })
        }, drag: function (b, c) {
            b.options.refreshPositions && a.ui.ddmanager.prepareOffsets(b, c), a.each(a.ui.ddmanager.droppables[b.options.scope] || [], function () {
                if (!this.options.disabled && !this.greedyChild && this.visible) {
                    var d, e, f, g = a.ui.intersect(b, this, this.options.tolerance),
                        h = !g && this.isover ? "isout" : g && !this.isover ? "isover" : null;
                    h && (this.options.greedy && (e = this.options.scope, f = this.element.parents(":data(ui-droppable)").filter(function () {
                        return a.data(this, "ui-droppable").options.scope === e
                    }), f.length && (d = a.data(f[0], "ui-droppable"), d.greedyChild = "isover" === h)), d && "isover" === h && (d.isover = !1, d.isout = !0, d._out.call(d, c)), this[h] = !0, this["isout" === h ? "isover" : "isout"] = !1, this["isover" === h ? "_over" : "_out"].call(this, c), d && "isout" === h && (d.isout = !1, d.isover = !0, d._over.call(d, c)))
                }
            })
        }, dragStop: function (b, c) {
            b.element.parentsUntil("body").unbind("scroll.droppable"), b.options.refreshPositions || a.ui.ddmanager.prepareOffsets(b, c)
        }
    }
}(jQuery), function (a, b) {
    function c(a) {
        return parseInt(a, 10) || 0
    }

    function d(a) {
        return !isNaN(parseInt(a, 10))
    }

    a.widget("ui.resizable", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: !1,
            animate: !1,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: !1,
            autoHide: !1,
            containment: !1,
            ghost: !1,
            grid: !1,
            handles: "e,s,se",
            helper: !1,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            zIndex: 90,
            resize: null,
            start: null,
            stop: null
        },
        _create: function () {
            var b, c, d, e, f, g = this, h = this.options;
            if (this.element.addClass("ui-resizable"), a.extend(this, {
                    _aspectRatio: !!h.aspectRatio,
                    aspectRatio: h.aspectRatio,
                    originalElement: this.element,
                    _proportionallyResizeElements: [],
                    _helper: h.helper || h.ghost || h.animate ? h.helper || "ui-resizable-helper" : null
                }), this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i) && (this.element.wrap(a("<div class='ui-wrapper' style='overflow: hidden;'></div>").css({
                    position: this.element.css("position"),
                    width: this.element.outerWidth(),
                    height: this.element.outerHeight(),
                    top: this.element.css("top"),
                    left: this.element.css("left")
                })), this.element = this.element.parent().data("ui-resizable", this.element.data("ui-resizable")), this.elementIsWrapper = !0, this.element.css({
                    marginLeft: this.originalElement.css("marginLeft"),
                    marginTop: this.originalElement.css("marginTop"),
                    marginRight: this.originalElement.css("marginRight"),
                    marginBottom: this.originalElement.css("marginBottom")
                }), this.originalElement.css({
                    marginLeft: 0,
                    marginTop: 0,
                    marginRight: 0,
                    marginBottom: 0
                }), this.originalResizeStyle = this.originalElement.css("resize"), this.originalElement.css("resize", "none"), this._proportionallyResizeElements.push(this.originalElement.css({
                    position: "static",
                    zoom: 1,
                    display: "block"
                })), this.originalElement.css({margin: this.originalElement.css("margin")}), this._proportionallyResize()), this.handles = h.handles || (a(".ui-resizable-handle", this.element).length ? {
                    n: ".ui-resizable-n",
                    e: ".ui-resizable-e",
                    s: ".ui-resizable-s",
                    w: ".ui-resizable-w",
                    se: ".ui-resizable-se",
                    sw: ".ui-resizable-sw",
                    ne: ".ui-resizable-ne",
                    nw: ".ui-resizable-nw"
                } : "e,s,se"), this.handles.constructor === String) for ("all" === this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw"), b = this.handles.split(","), this.handles = {}, c = 0; c < b.length; c++) d = a.trim(b[c]), f = "ui-resizable-" + d, e = a("<div class='ui-resizable-handle " + f + "'></div>"), e.css({zIndex: h.zIndex}), "se" === d && e.addClass("ui-icon ui-icon-gripsmall-diagonal-se"), this.handles[d] = ".ui-resizable-" + d, this.element.append(e);
            this._renderAxis = function (b) {
                var c, d, e, f;
                b = b || this.element;
                for (c in this.handles) this.handles[c].constructor === String && (this.handles[c] = a(this.handles[c], this.element).show()), this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i) && (d = a(this.handles[c], this.element), f = /sw|ne|nw|se|n|s/.test(c) ? d.outerHeight() : d.outerWidth(), e = ["padding", /ne|nw|n/.test(c) ? "Top" : /se|sw|s/.test(c) ? "Bottom" : /^e$/.test(c) ? "Right" : "Left"].join(""), b.css(e, f), this._proportionallyResize()), a(this.handles[c]).length
            }, this._renderAxis(this.element), this._handles = a(".ui-resizable-handle", this.element).disableSelection(), this._handles.mouseover(function () {
                g.resizing || (this.className && (e = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i)), g.axis = e && e[1] ? e[1] : "se")
            }), h.autoHide && (this._handles.hide(), a(this.element).addClass("ui-resizable-autohide").mouseenter(function () {
                h.disabled || (a(this).removeClass("ui-resizable-autohide"), g._handles.show())
            }).mouseleave(function () {
                h.disabled || g.resizing || (a(this).addClass("ui-resizable-autohide"), g._handles.hide())
            })), this._mouseInit()
        },
        _destroy: function () {
            this._mouseDestroy();
            var b, c = function (b) {
                a(b).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()
            };
            return this.elementIsWrapper && (c(this.element), b = this.element, this.originalElement.css({
                position: b.css("position"),
                width: b.outerWidth(),
                height: b.outerHeight(),
                top: b.css("top"),
                left: b.css("left")
            }).insertAfter(b), b.remove()), this.originalElement.css("resize", this.originalResizeStyle), c(this.originalElement), this
        },
        _mouseCapture: function (b) {
            var c, d, e = !1;
            for (c in this.handles) d = a(this.handles[c])[0], (d === b.target || a.contains(d, b.target)) && (e = !0);
            return !this.options.disabled && e
        },
        _mouseStart: function (b) {
            var d, e, f, g = this.options, h = this.element.position(), i = this.element;
            return this.resizing = !0, /absolute/.test(i.css("position")) ? i.css({
                position: "absolute",
                top: i.css("top"),
                left: i.css("left")
            }) : i.is(".ui-draggable") && i.css({
                position: "absolute",
                top: h.top,
                left: h.left
            }), this._renderProxy(), d = c(this.helper.css("left")), e = c(this.helper.css("top")), g.containment && (d += a(g.containment).scrollLeft() || 0, e += a(g.containment).scrollTop() || 0), this.offset = this.helper.offset(), this.position = {
                left: d,
                top: e
            }, this.size = this._helper ? {width: i.outerWidth(), height: i.outerHeight()} : {
                width: i.width(),
                height: i.height()
            }, this.originalSize = this._helper ? {width: i.outerWidth(), height: i.outerHeight()} : {
                width: i.width(),
                height: i.height()
            }, this.originalPosition = {left: d, top: e}, this.sizeDiff = {
                width: i.outerWidth() - i.width(),
                height: i.outerHeight() - i.height()
            }, this.originalMousePosition = {
                left: b.pageX,
                top: b.pageY
            }, this.aspectRatio = "number" == typeof g.aspectRatio ? g.aspectRatio : this.originalSize.width / this.originalSize.height || 1, f = a(".ui-resizable-" + this.axis).css("cursor"), a("body").css("cursor", "auto" === f ? this.axis + "-resize" : f), i.addClass("ui-resizable-resizing"), this._propagate("start", b), !0
        },
        _mouseDrag: function (b) {
            var c, d = this.helper, e = {}, f = this.originalMousePosition, g = this.axis, h = this.position.top,
                i = this.position.left, j = this.size.width, k = this.size.height, l = b.pageX - f.left || 0,
                m = b.pageY - f.top || 0, n = this._change[g];
            return !!n && (c = n.apply(this, [b, l, m]), this._updateVirtualBoundaries(b.shiftKey), (this._aspectRatio || b.shiftKey) && (c = this._updateRatio(c, b)), c = this._respectSize(c, b), this._updateCache(c), this._propagate("resize", b), this.position.top !== h && (e.top = this.position.top + "px"), this.position.left !== i && (e.left = this.position.left + "px"), this.size.width !== j && (e.width = this.size.width + "px"), this.size.height !== k && (e.height = this.size.height + "px"), d.css(e), !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(), a.isEmptyObject(e) || this._trigger("resize", b, this.ui()), !1)
        },
        _mouseStop: function (b) {
            this.resizing = !1;
            var c, d, e, f, g, h, i, j = this.options, k = this;
            return this._helper && (c = this._proportionallyResizeElements, d = c.length && /textarea/i.test(c[0].nodeName), e = d && a.ui.hasScroll(c[0], "left") ? 0 : k.sizeDiff.height, f = d ? 0 : k.sizeDiff.width, g = {
                width: k.helper.width() - f,
                height: k.helper.height() - e
            }, h = parseInt(k.element.css("left"), 10) + (k.position.left - k.originalPosition.left) || null, i = parseInt(k.element.css("top"), 10) + (k.position.top - k.originalPosition.top) || null, j.animate || this.element.css(a.extend(g, {
                top: i,
                left: h
            })), k.helper.height(k.size.height), k.helper.width(k.size.width), this._helper && !j.animate && this._proportionallyResize()), a("body").css("cursor", "auto"), this.element.removeClass("ui-resizable-resizing"), this._propagate("stop", b), this._helper && this.helper.remove(), !1
        },
        _updateVirtualBoundaries: function (a) {
            var b, c, e, f, g, h = this.options;
            g = {
                minWidth: d(h.minWidth) ? h.minWidth : 0,
                maxWidth: d(h.maxWidth) ? h.maxWidth : 1 / 0,
                minHeight: d(h.minHeight) ? h.minHeight : 0,
                maxHeight: d(h.maxHeight) ? h.maxHeight : 1 / 0
            }, (this._aspectRatio || a) && (b = g.minHeight * this.aspectRatio, e = g.minWidth / this.aspectRatio, c = g.maxHeight * this.aspectRatio, f = g.maxWidth / this.aspectRatio, b > g.minWidth && (g.minWidth = b), e > g.minHeight && (g.minHeight = e), c < g.maxWidth && (g.maxWidth = c), f < g.maxHeight && (g.maxHeight = f)), this._vBoundaries = g
        },
        _updateCache: function (a) {
            this.offset = this.helper.offset(), d(a.left) && (this.position.left = a.left), d(a.top) && (this.position.top = a.top), d(a.height) && (this.size.height = a.height), d(a.width) && (this.size.width = a.width)
        },
        _updateRatio: function (a) {
            var b = this.position, c = this.size, e = this.axis;
            return d(a.height) ? a.width = a.height * this.aspectRatio : d(a.width) && (a.height = a.width / this.aspectRatio), "sw" === e && (a.left = b.left + (c.width - a.width), a.top = null), "nw" === e && (a.top = b.top + (c.height - a.height), a.left = b.left + (c.width - a.width)), a
        },
        _respectSize: function (a) {
            var b = this._vBoundaries, c = this.axis, e = d(a.width) && b.maxWidth && b.maxWidth < a.width,
                f = d(a.height) && b.maxHeight && b.maxHeight < a.height,
                g = d(a.width) && b.minWidth && b.minWidth > a.width,
                h = d(a.height) && b.minHeight && b.minHeight > a.height,
                i = this.originalPosition.left + this.originalSize.width, j = this.position.top + this.size.height,
                k = /sw|nw|w/.test(c), l = /nw|ne|n/.test(c);
            return g && (a.width = b.minWidth), h && (a.height = b.minHeight), e && (a.width = b.maxWidth), f && (a.height = b.maxHeight), g && k && (a.left = i - b.minWidth), e && k && (a.left = i - b.maxWidth), h && l && (a.top = j - b.minHeight), f && l && (a.top = j - b.maxHeight), a.width || a.height || a.left || !a.top ? a.width || a.height || a.top || !a.left || (a.left = null) : a.top = null, a
        },
        _proportionallyResize: function () {
            if (this._proportionallyResizeElements.length) {
                var a, b, c, d, e, f = this.helper || this.element;
                for (a = 0; a < this._proportionallyResizeElements.length; a++) {
                    if (e = this._proportionallyResizeElements[a], !this.borderDif) for (this.borderDif = [], c = [e.css("borderTopWidth"), e.css("borderRightWidth"), e.css("borderBottomWidth"), e.css("borderLeftWidth")], d = [e.css("paddingTop"), e.css("paddingRight"), e.css("paddingBottom"), e.css("paddingLeft")], b = 0; b < c.length; b++) this.borderDif[b] = (parseInt(c[b], 10) || 0) + (parseInt(d[b], 10) || 0);
                    e.css({
                        height: f.height() - this.borderDif[0] - this.borderDif[2] || 0,
                        width: f.width() - this.borderDif[1] - this.borderDif[3] || 0
                    })
                }
            }
        },
        _renderProxy: function () {
            var b = this.element, c = this.options;
            this.elementOffset = b.offset(), this._helper ? (this.helper = this.helper || a("<div style='overflow:hidden;'></div>"), this.helper.addClass(this._helper).css({
                width: this.element.outerWidth() - 1,
                height: this.element.outerHeight() - 1,
                position: "absolute",
                left: this.elementOffset.left + "px",
                top: this.elementOffset.top + "px",
                zIndex: ++c.zIndex
            }), this.helper.appendTo("body").disableSelection()) : this.helper = this.element
        },
        _change: {
            e: function (a, b) {
                return {width: this.originalSize.width + b}
            }, w: function (a, b) {
                var c = this.originalSize, d = this.originalPosition;
                return {left: d.left + b, width: c.width - b}
            }, n: function (a, b, c) {
                var d = this.originalSize, e = this.originalPosition;
                return {top: e.top + c, height: d.height - c}
            }, s: function (a, b, c) {
                return {height: this.originalSize.height + c}
            }, se: function (b, c, d) {
                return a.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [b, c, d]))
            }, sw: function (b, c, d) {
                return a.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [b, c, d]))
            }, ne: function (b, c, d) {
                return a.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [b, c, d]))
            }, nw: function (b, c, d) {
                return a.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [b, c, d]))
            }
        },
        _propagate: function (b, c) {
            a.ui.plugin.call(this, b, [c, this.ui()]), "resize" !== b && this._trigger(b, c, this.ui())
        },
        plugins: {},
        ui: function () {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            }
        }
    }), a.ui.plugin.add("resizable", "animate", {
        stop: function (b) {
            var c = a(this).data("ui-resizable"), d = c.options, e = c._proportionallyResizeElements,
                f = e.length && /textarea/i.test(e[0].nodeName),
                g = f && a.ui.hasScroll(e[0], "left") ? 0 : c.sizeDiff.height, h = f ? 0 : c.sizeDiff.width,
                i = {width: c.size.width - h, height: c.size.height - g},
                j = parseInt(c.element.css("left"), 10) + (c.position.left - c.originalPosition.left) || null,
                k = parseInt(c.element.css("top"), 10) + (c.position.top - c.originalPosition.top) || null;
            c.element.animate(a.extend(i, k && j ? {top: k, left: j} : {}), {
                duration: d.animateDuration,
                easing: d.animateEasing,
                step: function () {
                    var d = {
                        width: parseInt(c.element.css("width"), 10),
                        height: parseInt(c.element.css("height"), 10),
                        top: parseInt(c.element.css("top"), 10),
                        left: parseInt(c.element.css("left"), 10)
                    };
                    e && e.length && a(e[0]).css({
                        width: d.width,
                        height: d.height
                    }), c._updateCache(d), c._propagate("resize", b)
                }
            })
        }
    }), a.ui.plugin.add("resizable", "containment", {
        start: function () {
            var b, d, e, f, g, h, i, j = a(this).data("ui-resizable"), k = j.options, l = j.element, m = k.containment,
                n = m instanceof a ? m.get(0) : /parent/.test(m) ? l.parent().get(0) : m;
            n && (j.containerElement = a(n), /document/.test(m) || m === document ? (j.containerOffset = {
                left: 0,
                top: 0
            }, j.containerPosition = {left: 0, top: 0}, j.parentData = {
                element: a(document),
                left: 0,
                top: 0,
                width: a(document).width(),
                height: a(document).height() || document.body.parentNode.scrollHeight
            }) : (b = a(n), d = [], a(["Top", "Right", "Left", "Bottom"]).each(function (a, e) {
                d[a] = c(b.css("padding" + e))
            }), j.containerOffset = b.offset(), j.containerPosition = b.position(), j.containerSize = {
                height: b.innerHeight() - d[3],
                width: b.innerWidth() - d[1]
            }, e = j.containerOffset, f = j.containerSize.height, g = j.containerSize.width, h = a.ui.hasScroll(n, "left") ? n.scrollWidth : g, i = a.ui.hasScroll(n) ? n.scrollHeight : f, j.parentData = {
                element: n,
                left: e.left,
                top: e.top,
                width: h,
                height: i
            }))
        }, resize: function (b) {
            var c, d, e, f, g = a(this).data("ui-resizable"), h = g.options, i = g.containerOffset, j = g.position,
                k = g._aspectRatio || b.shiftKey, l = {top: 0, left: 0}, m = g.containerElement;
            m[0] !== document && /static/.test(m.css("position")) && (l = i), j.left < (g._helper ? i.left : 0) && (g.size.width = g.size.width + (g._helper ? g.position.left - i.left : g.position.left - l.left), k && (g.size.height = g.size.width / g.aspectRatio), g.position.left = h.helper ? i.left : 0), j.top < (g._helper ? i.top : 0) && (g.size.height = g.size.height + (g._helper ? g.position.top - i.top : g.position.top), k && (g.size.width = g.size.height * g.aspectRatio), g.position.top = g._helper ? i.top : 0), g.offset.left = g.parentData.left + g.position.left, g.offset.top = g.parentData.top + g.position.top, c = Math.abs((g._helper ? g.offset.left - l.left : g.offset.left - l.left) + g.sizeDiff.width), d = Math.abs((g._helper ? g.offset.top - l.top : g.offset.top - i.top) + g.sizeDiff.height), e = g.containerElement.get(0) === g.element.parent().get(0), f = /relative|absolute/.test(g.containerElement.css("position")), e && f && (c -= g.parentData.left), c + g.size.width >= g.parentData.width && (g.size.width = g.parentData.width - c, k && (g.size.height = g.size.width / g.aspectRatio)), d + g.size.height >= g.parentData.height && (g.size.height = g.parentData.height - d, k && (g.size.width = g.size.height * g.aspectRatio))
        }, stop: function () {
            var b = a(this).data("ui-resizable"), c = b.options, d = b.containerOffset, e = b.containerPosition,
                f = b.containerElement, g = a(b.helper), h = g.offset(), i = g.outerWidth() - b.sizeDiff.width,
                j = g.outerHeight() - b.sizeDiff.height;
            b._helper && !c.animate && /relative/.test(f.css("position")) && a(this).css({
                left: h.left - e.left - d.left,
                width: i,
                height: j
            }), b._helper && !c.animate && /static/.test(f.css("position")) && a(this).css({
                left: h.left - e.left - d.left,
                width: i,
                height: j
            })
        }
    }), a.ui.plugin.add("resizable", "alsoResize", {
        start: function () {
            var b = a(this).data("ui-resizable"), c = b.options, d = function (b) {
                a(b).each(function () {
                    var b = a(this);
                    b.data("ui-resizable-alsoresize", {
                        width: parseInt(b.width(), 10),
                        height: parseInt(b.height(), 10),
                        left: parseInt(b.css("left"), 10),
                        top: parseInt(b.css("top"), 10)
                    })
                })
            };
            "object" != typeof c.alsoResize || c.alsoResize.parentNode ? d(c.alsoResize) : c.alsoResize.length ? (c.alsoResize = c.alsoResize[0], d(c.alsoResize)) : a.each(c.alsoResize, function (a) {
                d(a)
            })
        }, resize: function (b, c) {
            var d = a(this).data("ui-resizable"), e = d.options, f = d.originalSize, g = d.originalPosition, h = {
                height: d.size.height - f.height || 0,
                width: d.size.width - f.width || 0,
                top: d.position.top - g.top || 0,
                left: d.position.left - g.left || 0
            }, i = function (b, d) {
                a(b).each(function () {
                    var b = a(this), e = a(this).data("ui-resizable-alsoresize"), f = {},
                        g = d && d.length ? d : b.parents(c.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                    a.each(g, function (a, b) {
                        var c = (e[b] || 0) + (h[b] || 0);
                        c && c >= 0 && (f[b] = c || null)
                    }), b.css(f)
                })
            };
            "object" != typeof e.alsoResize || e.alsoResize.nodeType ? i(e.alsoResize) : a.each(e.alsoResize, function (a, b) {
                i(a, b)
            })
        }, stop: function () {
            a(this).removeData("resizable-alsoresize")
        }
    }), a.ui.plugin.add("resizable", "ghost", {
        start: function () {
            var b = a(this).data("ui-resizable"), c = b.options, d = b.size;
            b.ghost = b.originalElement.clone(), b.ghost.css({
                opacity: .25,
                display: "block",
                position: "relative",
                height: d.height,
                width: d.width,
                margin: 0,
                left: 0,
                top: 0
            }).addClass("ui-resizable-ghost").addClass("string" == typeof c.ghost ? c.ghost : ""), b.ghost.appendTo(b.helper)
        }, resize: function () {
            var b = a(this).data("ui-resizable");
            b.ghost && b.ghost.css({position: "relative", height: b.size.height, width: b.size.width})
        }, stop: function () {
            var b = a(this).data("ui-resizable");
            b.ghost && b.helper && b.helper.get(0).removeChild(b.ghost.get(0))
        }
    }), a.ui.plugin.add("resizable", "grid", {
        resize: function () {
            var b = a(this).data("ui-resizable"), c = b.options, d = b.size, e = b.originalSize, f = b.originalPosition,
                g = b.axis, h = "number" == typeof c.grid ? [c.grid, c.grid] : c.grid, i = h[0] || 1, j = h[1] || 1,
                k = Math.round((d.width - e.width) / i) * i, l = Math.round((d.height - e.height) / j) * j,
                m = e.width + k, n = e.height + l, o = c.maxWidth && c.maxWidth < m, p = c.maxHeight && c.maxHeight < n,
                q = c.minWidth && c.minWidth > m, r = c.minHeight && c.minHeight > n;
            c.grid = h, q && (m += i), r && (n += j), o && (m -= i), p && (n -= j), /^(se|s|e)$/.test(g) ? (b.size.width = m, b.size.height = n) : /^(ne)$/.test(g) ? (b.size.width = m, b.size.height = n, b.position.top = f.top - l) : /^(sw)$/.test(g) ? (b.size.width = m, b.size.height = n, b.position.left = f.left - k) : (b.size.width = m, b.size.height = n, b.position.top = f.top - l, b.position.left = f.left - k)
        }
    })
}(jQuery), function (a, b) {
    a.widget("ui.selectable", a.ui.mouse, {
        version: "1.10.3",
        options: {
            appendTo: "body",
            autoRefresh: !0,
            distance: 0,
            filter: "*",
            tolerance: "touch",
            selected: null,
            selecting: null,
            start: null,
            stop: null,
            unselected: null,
            unselecting: null
        },
        _create: function () {
            var b, c = this;
            this.element.addClass("ui-selectable"), this.dragged = !1, this.refresh = function () {
                b = a(c.options.filter, c.element[0]), b.addClass("ui-selectee"), b.each(function () {
                    var b = a(this), c = b.offset();
                    a.data(this, "selectable-item", {
                        element: this,
                        $element: b,
                        left: c.left,
                        top: c.top,
                        right: c.left + b.outerWidth(),
                        bottom: c.top + b.outerHeight(),
                        startselected: !1,
                        selected: b.hasClass("ui-selected"),
                        selecting: b.hasClass("ui-selecting"),
                        unselecting: b.hasClass("ui-unselecting")
                    })
                })
            }, this.refresh(), this.selectees = b.addClass("ui-selectee"), this._mouseInit(), this.helper = a("<div class='ui-selectable-helper'></div>")
        },
        _destroy: function () {
            this.selectees.removeClass("ui-selectee").removeData("selectable-item"), this.element.removeClass("ui-selectable ui-selectable-disabled"), this._mouseDestroy()
        },
        _mouseStart: function (b) {
            var c = this, d = this.options;
            this.opos = [b.pageX, b.pageY], this.options.disabled || (this.selectees = a(d.filter, this.element[0]), this._trigger("start", b), a(d.appendTo).append(this.helper), this.helper.css({
                left: b.pageX,
                top: b.pageY,
                width: 0,
                height: 0
            }), d.autoRefresh && this.refresh(), this.selectees.filter(".ui-selected").each(function () {
                var d = a.data(this, "selectable-item");
                d.startselected = !0, b.metaKey || b.ctrlKey || (d.$element.removeClass("ui-selected"), d.selected = !1, d.$element.addClass("ui-unselecting"), d.unselecting = !0, c._trigger("unselecting", b, {unselecting: d.element}))
            }), a(b.target).parents().addBack().each(function () {
                var d, e = a.data(this, "selectable-item");
                if (e) return d = !b.metaKey && !b.ctrlKey || !e.$element.hasClass("ui-selected"), e.$element.removeClass(d ? "ui-unselecting" : "ui-selected").addClass(d ? "ui-selecting" : "ui-unselecting"), e.unselecting = !d, e.selecting = d, e.selected = d, d ? c._trigger("selecting", b, {selecting: e.element}) : c._trigger("unselecting", b, {unselecting: e.element}), !1
            }))
        },
        _mouseDrag: function (b) {
            if (this.dragged = !0, !this.options.disabled) {
                var c, d = this, e = this.options, f = this.opos[0], g = this.opos[1], h = b.pageX, i = b.pageY;
                return f > h && (c = h, h = f, f = c), g > i && (c = i, i = g, g = c), this.helper.css({
                    left: f,
                    top: g,
                    width: h - f,
                    height: i - g
                }), this.selectees.each(function () {
                    var c = a.data(this, "selectable-item"), j = !1;
                    c && c.element !== d.element[0] && ("touch" === e.tolerance ? j = !(c.left > h || c.right < f || c.top > i || c.bottom < g) : "fit" === e.tolerance && (j = c.left > f && c.right < h && c.top > g && c.bottom < i), j ? (c.selected && (c.$element.removeClass("ui-selected"), c.selected = !1), c.unselecting && (c.$element.removeClass("ui-unselecting"), c.unselecting = !1), c.selecting || (c.$element.addClass("ui-selecting"), c.selecting = !0, d._trigger("selecting", b, {selecting: c.element}))) : (c.selecting && ((b.metaKey || b.ctrlKey) && c.startselected ? (c.$element.removeClass("ui-selecting"), c.selecting = !1, c.$element.addClass("ui-selected"), c.selected = !0) : (c.$element.removeClass("ui-selecting"), c.selecting = !1, c.startselected && (c.$element.addClass("ui-unselecting"), c.unselecting = !0), d._trigger("unselecting", b, {unselecting: c.element}))), c.selected && (b.metaKey || b.ctrlKey || c.startselected || (c.$element.removeClass("ui-selected"), c.selected = !1, c.$element.addClass("ui-unselecting"), c.unselecting = !0, d._trigger("unselecting", b, {unselecting: c.element})))))
                }), !1
            }
        },
        _mouseStop: function (b) {
            var c = this;
            return this.dragged = !1, a(".ui-unselecting", this.element[0]).each(function () {
                var d = a.data(this, "selectable-item");
                d.$element.removeClass("ui-unselecting"), d.unselecting = !1, d.startselected = !1, c._trigger("unselected", b, {unselected: d.element})
            }), a(".ui-selecting", this.element[0]).each(function () {
                var d = a.data(this, "selectable-item");
                d.$element.removeClass("ui-selecting").addClass("ui-selected"), d.selecting = !1, d.selected = !0, d.startselected = !0, c._trigger("selected", b, {selected: d.element})
            }), this._trigger("stop", b), this.helper.remove(), !1
        }
    })
}(jQuery), function (a, b) {
    function c(a, b, c) {
        return a > b && a < b + c
    }

    function d(a) {
        return /left|right/.test(a.css("float")) || /inline|table-cell/.test(a.css("display"))
    }

    a.widget("ui.sortable", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "sort",
        ready: !1,
        options: {
            appendTo: "parent",
            axis: !1,
            connectWith: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            dropOnEmpty: !0,
            forcePlaceholderSize: !1,
            forceHelperSize: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            items: "> *",
            opacity: !1,
            placeholder: !1,
            revert: !1,
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            scope: "default",
            tolerance: "intersect",
            zIndex: 1e3,
            activate: null,
            beforeStop: null,
            change: null,
            deactivate: null,
            out: null,
            over: null,
            receive: null,
            remove: null,
            sort: null,
            start: null,
            stop: null,
            update: null
        },
        _create: function () {
            var a = this.options;
            this.containerCache = {}, this.element.addClass("ui-sortable"), this.refresh(), this.floating = !!this.items.length && ("x" === a.axis || d(this.items[0].item)), this.offset = this.element.offset(), this._mouseInit(), this.ready = !0
        },
        _destroy: function () {
            this.element.removeClass("ui-sortable ui-sortable-disabled"), this._mouseDestroy();
            for (var a = this.items.length - 1; a >= 0; a--) this.items[a].item.removeData(this.widgetName + "-item");
            return this
        },
        _setOption: function (b, c) {
            "disabled" === b ? (this.options[b] = c, this.widget().toggleClass("ui-sortable-disabled", !!c)) : a.Widget.prototype._setOption.apply(this, arguments)
        },
        _mouseCapture: function (b, c) {
            var d = null, e = !1, f = this;
            return !this.reverting && (!this.options.disabled && "static" !== this.options.type && (this._refreshItems(b), a(b.target).parents().each(function () {
                if (a.data(this, f.widgetName + "-item") === f) return d = a(this), !1
            }), a.data(b.target, f.widgetName + "-item") === f && (d = a(b.target)), !!d && (!(this.options.handle && !c && (a(this.options.handle, d).find("*").addBack().each(function () {
                this === b.target && (e = !0)
            }), !e)) && (this.currentItem = d, this._removeCurrentsFromItems(), !0))))
        },
        _mouseStart: function (b, c, d) {
            var e, f, g = this.options;
            if (this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(b), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = {
                    top: this.offset.top - this.margins.top,
                    left: this.offset.left - this.margins.left
                }, a.extend(this.offset, {
                    click: {left: b.pageX - this.offset.left, top: b.pageY - this.offset.top},
                    parent: this._getParentOffset(),
                    relative: this._getRelativeOffset()
                }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(b), this.originalPageX = b.pageX, this.originalPageY = b.pageY, g.cursorAt && this._adjustOffsetFromHelper(g.cursorAt), this.domPosition = {
                    prev: this.currentItem.prev()[0],
                    parent: this.currentItem.parent()[0]
                }, this.helper[0] !== this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), g.containment && this._setContainment(), g.cursor && "auto" !== g.cursor && (f = this.document.find("body"), this.storedCursor = f.css("cursor"), f.css("cursor", g.cursor), this.storedStylesheet = a("<style>*{ cursor: " + g.cursor + " !important; }</style>").appendTo(f)), g.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", g.opacity)), g.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", g.zIndex)), this.scrollParent[0] !== document && "HTML" !== this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", b, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !d) for (e = this.containers.length - 1; e >= 0; e--) this.containers[e]._trigger("activate", b, this._uiHash(this));
            return a.ui.ddmanager && (a.ui.ddmanager.current = this), a.ui.ddmanager && !g.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b), this.dragging = !0, this.helper.addClass("ui-sortable-helper"), this._mouseDrag(b), !0
        },
        _mouseDrag: function (b) {
            var c, d, e, f, g = this.options, h = !1;
            for (this.position = this._generatePosition(b), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll && (this.scrollParent[0] !== document && "HTML" !== this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - b.pageY < g.scrollSensitivity ? this.scrollParent[0].scrollTop = h = this.scrollParent[0].scrollTop + g.scrollSpeed : b.pageY - this.overflowOffset.top < g.scrollSensitivity && (this.scrollParent[0].scrollTop = h = this.scrollParent[0].scrollTop - g.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - b.pageX < g.scrollSensitivity ? this.scrollParent[0].scrollLeft = h = this.scrollParent[0].scrollLeft + g.scrollSpeed : b.pageX - this.overflowOffset.left < g.scrollSensitivity && (this.scrollParent[0].scrollLeft = h = this.scrollParent[0].scrollLeft - g.scrollSpeed)) : (b.pageY - a(document).scrollTop() < g.scrollSensitivity ? h = a(document).scrollTop(a(document).scrollTop() - g.scrollSpeed) : a(window).height() - (b.pageY - a(document).scrollTop()) < g.scrollSensitivity && (h = a(document).scrollTop(a(document).scrollTop() + g.scrollSpeed)), b.pageX - a(document).scrollLeft() < g.scrollSensitivity ? h = a(document).scrollLeft(a(document).scrollLeft() - g.scrollSpeed) : a(window).width() - (b.pageX - a(document).scrollLeft()) < g.scrollSensitivity && (h = a(document).scrollLeft(a(document).scrollLeft() + g.scrollSpeed))), h !== !1 && a.ui.ddmanager && !g.dropBehaviour && a.ui.ddmanager.prepareOffsets(this, b)), this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" === this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" === this.options.axis || (this.helper[0].style.top = this.position.top + "px"), c = this.items.length - 1; c >= 0; c--) if (d = this.items[c], e = d.item[0], f = this._intersectsWithPointer(d), f && d.instance === this.currentContainer && !(e === this.currentItem[0] || this.placeholder[1 === f ? "next" : "prev"]()[0] === e || a.contains(this.placeholder[0], e) || "semi-dynamic" === this.options.type && a.contains(this.element[0], e))) {
                if (this.direction = 1 === f ? "down" : "up", "pointer" !== this.options.tolerance && !this._intersectsWithSides(d)) break;
                this._rearrange(b, d), this._trigger("change", b, this._uiHash());
                break
            }
            return this._contactContainers(b), a.ui.ddmanager && a.ui.ddmanager.drag(this, b), this._trigger("sort", b, this._uiHash()), this.lastPositionAbs = this.positionAbs, !1
        },
        _mouseStop: function (b, c) {
            if (b) {
                if (a.ui.ddmanager && !this.options.dropBehaviour && a.ui.ddmanager.drop(this, b), this.options.revert) {
                    var d = this, e = this.placeholder.offset(), f = this.options.axis, g = {};
                    f && "x" !== f || (g.left = e.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] === document.body ? 0 : this.offsetParent[0].scrollLeft)), f && "y" !== f || (g.top = e.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] === document.body ? 0 : this.offsetParent[0].scrollTop)), this.reverting = !0, a(this.helper).animate(g, parseInt(this.options.revert, 10) || 500, function () {
                        d._clear(b)
                    })
                } else this._clear(b, c);
                return !1
            }
        },
        cancel: function () {
            if (this.dragging) {
                this._mouseUp({target: null}), "original" === this.options.helper ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
                for (var b = this.containers.length - 1; b >= 0; b--) this.containers[b]._trigger("deactivate", null, this._uiHash(this)), this.containers[b].containerCache.over && (this.containers[b]._trigger("out", null, this._uiHash(this)), this.containers[b].containerCache.over = 0)
            }
            return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" !== this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), a.extend(this, {
                helper: null,
                dragging: !1,
                reverting: !1,
                _noFinalSort: null
            }), this.domPosition.prev ? a(this.domPosition.prev).after(this.currentItem) : a(this.domPosition.parent).prepend(this.currentItem)), this
        },
        serialize: function (b) {
            var c = this._getItemsAsjQuery(b && b.connected), d = [];
            return b = b || {}, a(c).each(function () {
                var c = (a(b.item || this).attr(b.attribute || "id") || "").match(b.expression || /(.+)[\-=_](.+)/);
                c && d.push((b.key || c[1] + "[]") + "=" + (b.key && b.expression ? c[1] : c[2]))
            }), !d.length && b.key && d.push(b.key + "="), d.join("&")
        },
        toArray: function (b) {
            var c = this._getItemsAsjQuery(b && b.connected), d = [];
            return b = b || {}, c.each(function () {
                d.push(a(b.item || this).attr(b.attribute || "id") || "")
            }), d
        },
        _intersectsWith: function (a) {
            var b = this.positionAbs.left, c = b + this.helperProportions.width, d = this.positionAbs.top,
                e = d + this.helperProportions.height, f = a.left, g = f + a.width, h = a.top, i = h + a.height,
                j = this.offset.click.top, k = this.offset.click.left,
                l = "x" === this.options.axis || d + j > h && d + j < i,
                m = "y" === this.options.axis || b + k > f && b + k < g, n = l && m;
            return "pointer" === this.options.tolerance || this.options.forcePointerForContainers || "pointer" !== this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > a[this.floating ? "width" : "height"] ? n : f < b + this.helperProportions.width / 2 && c - this.helperProportions.width / 2 < g && h < d + this.helperProportions.height / 2 && e - this.helperProportions.height / 2 < i
        },
        _intersectsWithPointer: function (a) {
            var b = "x" === this.options.axis || c(this.positionAbs.top + this.offset.click.top, a.top, a.height),
                d = "y" === this.options.axis || c(this.positionAbs.left + this.offset.click.left, a.left, a.width),
                e = b && d, f = this._getDragVerticalDirection(), g = this._getDragHorizontalDirection();
            return !!e && (this.floating ? g && "right" === g || "down" === f ? 2 : 1 : f && ("down" === f ? 2 : 1))
        },
        _intersectsWithSides: function (a) {
            var b = c(this.positionAbs.top + this.offset.click.top, a.top + a.height / 2, a.height),
                d = c(this.positionAbs.left + this.offset.click.left, a.left + a.width / 2, a.width),
                e = this._getDragVerticalDirection(), f = this._getDragHorizontalDirection();
            return this.floating && f ? "right" === f && d || "left" === f && !d : e && ("down" === e && b || "up" === e && !b)
        },
        _getDragVerticalDirection: function () {
            var a = this.positionAbs.top - this.lastPositionAbs.top;
            return 0 !== a && (a > 0 ? "down" : "up")
        },
        _getDragHorizontalDirection: function () {
            var a = this.positionAbs.left - this.lastPositionAbs.left;
            return 0 !== a && (a > 0 ? "right" : "left")
        },
        refresh: function (a) {
            return this._refreshItems(a), this.refreshPositions(), this
        },
        _connectWith: function () {
            var a = this.options;
            return a.connectWith.constructor === String ? [a.connectWith] : a.connectWith
        },
        _getItemsAsjQuery: function (b) {
            var c, d, e, f, g = [], h = [], i = this._connectWith();
            if (i && b) for (c = i.length - 1; c >= 0; c--) for (e = a(i[c]), d = e.length - 1; d >= 0; d--) f = a.data(e[d], this.widgetFullName), f && f !== this && !f.options.disabled && h.push([a.isFunction(f.options.items) ? f.options.items.call(f.element) : a(f.options.items, f.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), f]);
            for (h.push([a.isFunction(this.options.items) ? this.options.items.call(this.element, null, {
                options: this.options,
                item: this.currentItem
            }) : a(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]), c = h.length - 1; c >= 0; c--) h[c][0].each(function () {
                g.push(this)
            });
            return a(g)
        },
        _removeCurrentsFromItems: function () {
            var b = this.currentItem.find(":data(" + this.widgetName + "-item)");
            this.items = a.grep(this.items, function (a) {
                for (var c = 0; c < b.length; c++) if (b[c] === a.item[0]) return !1;
                return !0
            })
        },
        _refreshItems: function (b) {
            this.items = [], this.containers = [this];
            var c, d, e, f, g, h, i, j, k = this.items,
                l = [[a.isFunction(this.options.items) ? this.options.items.call(this.element[0], b, {item: this.currentItem}) : a(this.options.items, this.element), this]],
                m = this._connectWith();
            if (m && this.ready) for (c = m.length - 1; c >= 0; c--) for (e = a(m[c]), d = e.length - 1; d >= 0; d--) f = a.data(e[d], this.widgetFullName), f && f !== this && !f.options.disabled && (l.push([a.isFunction(f.options.items) ? f.options.items.call(f.element[0], b, {item: this.currentItem}) : a(f.options.items, f.element), f]), this.containers.push(f));
            for (c = l.length - 1; c >= 0; c--) for (g = l[c][1], h = l[c][0], d = 0, j = h.length; d < j; d++) i = a(h[d]), i.data(this.widgetName + "-item", g), k.push({
                item: i,
                instance: g,
                width: 0,
                height: 0,
                left: 0,
                top: 0
            })
        },
        refreshPositions: function (b) {
            this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
            var c, d, e, f;
            for (c = this.items.length - 1; c >= 0; c--) d = this.items[c], d.instance !== this.currentContainer && this.currentContainer && d.item[0] !== this.currentItem[0] || (e = this.options.toleranceElement ? a(this.options.toleranceElement, d.item) : d.item, b || (d.width = e.outerWidth(), d.height = e.outerHeight()), f = e.offset(), d.left = f.left, d.top = f.top);
            if (this.options.custom && this.options.custom.refreshContainers) this.options.custom.refreshContainers.call(this); else for (c = this.containers.length - 1; c >= 0; c--) f = this.containers[c].element.offset(), this.containers[c].containerCache.left = f.left, this.containers[c].containerCache.top = f.top, this.containers[c].containerCache.width = this.containers[c].element.outerWidth(), this.containers[c].containerCache.height = this.containers[c].element.outerHeight();
            return this
        },
        _createPlaceholder: function (b) {
            b = b || this;
            var c, d = b.options;
            d.placeholder && d.placeholder.constructor !== String || (c = d.placeholder, d.placeholder = {
                element: function () {
                    var d = b.currentItem[0].nodeName.toLowerCase(),
                        e = a("<" + d + ">", b.document[0]).addClass(c || b.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper");
                    return "tr" === d ? b.currentItem.children().each(function () {
                        a("<td>&#160;</td>", b.document[0]).attr("colspan", a(this).attr("colspan") || 1).appendTo(e)
                    }) : "img" === d && e.attr("src", b.currentItem.attr("src")), c || e.css("visibility", "hidden"), e
                }, update: function (a, e) {
                    c && !d.forcePlaceholderSize || (e.height() || e.height(b.currentItem.innerHeight() - parseInt(b.currentItem.css("paddingTop") || 0, 10) - parseInt(b.currentItem.css("paddingBottom") || 0, 10)), e.width() || e.width(b.currentItem.innerWidth() - parseInt(b.currentItem.css("paddingLeft") || 0, 10) - parseInt(b.currentItem.css("paddingRight") || 0, 10)))
                }
            }), b.placeholder = a(d.placeholder.element.call(b.element, b.currentItem)), b.currentItem.after(b.placeholder), d.placeholder.update(b, b.placeholder)
        },
        _contactContainers: function (b) {
            var e, f, g, h, i, j, k, l, m, n, o = null, p = null;
            for (e = this.containers.length - 1; e >= 0; e--) if (!a.contains(this.currentItem[0], this.containers[e].element[0])) if (this._intersectsWith(this.containers[e].containerCache)) {
                if (o && a.contains(this.containers[e].element[0], o.element[0])) continue;
                o = this.containers[e], p = e
            } else this.containers[e].containerCache.over && (this.containers[e]._trigger("out", b, this._uiHash(this)), this.containers[e].containerCache.over = 0);
            if (o) if (1 === this.containers.length) this.containers[p].containerCache.over || (this.containers[p]._trigger("over", b, this._uiHash(this)), this.containers[p].containerCache.over = 1); else {
                for (g = 1e4, h = null, n = o.floating || d(this.currentItem), i = n ? "left" : "top", j = n ? "width" : "height", k = this.positionAbs[i] + this.offset.click[i], f = this.items.length - 1; f >= 0; f--) a.contains(this.containers[p].element[0], this.items[f].item[0]) && this.items[f].item[0] !== this.currentItem[0] && (n && !c(this.positionAbs.top + this.offset.click.top, this.items[f].top, this.items[f].height) || (l = this.items[f].item.offset()[i], m = !1, Math.abs(l - k) > Math.abs(l + this.items[f][j] - k) && (m = !0, l += this.items[f][j]), Math.abs(l - k) < g && (g = Math.abs(l - k), h = this.items[f], this.direction = m ? "up" : "down")));
                if (!h && !this.options.dropOnEmpty) return;
                if (this.currentContainer === this.containers[p]) return;
                h ? this._rearrange(b, h, null, !0) : this._rearrange(b, null, this.containers[p].element, !0), this._trigger("change", b, this._uiHash()), this.containers[p]._trigger("change", b, this._uiHash(this)), this.currentContainer = this.containers[p], this.options.placeholder.update(this.currentContainer, this.placeholder), this.containers[p]._trigger("over", b, this._uiHash(this)), this.containers[p].containerCache.over = 1
            }
        },
        _createHelper: function (b) {
            var c = this.options,
                d = a.isFunction(c.helper) ? a(c.helper.apply(this.element[0], [b, this.currentItem])) : "clone" === c.helper ? this.currentItem.clone() : this.currentItem;
            return d.parents("body").length || a("parent" !== c.appendTo ? c.appendTo : this.currentItem[0].parentNode)[0].appendChild(d[0]), d[0] === this.currentItem[0] && (this._storedCSS = {
                width: this.currentItem[0].style.width,
                height: this.currentItem[0].style.height,
                position: this.currentItem.css("position"),
                top: this.currentItem.css("top"),
                left: this.currentItem.css("left")
            }), d[0].style.width && !c.forceHelperSize || d.width(this.currentItem.width()), d[0].style.height && !c.forceHelperSize || d.height(this.currentItem.height()), d
        },
        _adjustOffsetFromHelper: function (b) {
            "string" == typeof b && (b = b.split(" ")), a.isArray(b) && (b = {
                left: +b[0],
                top: +b[1] || 0
            }), "left" in b && (this.offset.click.left = b.left + this.margins.left), "right" in b && (this.offset.click.left = this.helperProportions.width - b.right + this.margins.left), "top" in b && (this.offset.click.top = b.top + this.margins.top), "bottom" in b && (this.offset.click.top = this.helperProportions.height - b.bottom + this.margins.top)
        },
        _getParentOffset: function () {
            this.offsetParent = this.helper.offsetParent();
            var b = this.offsetParent.offset();
            return "absolute" === this.cssPosition && this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) && (b.left += this.scrollParent.scrollLeft(), b.top += this.scrollParent.scrollTop()), (this.offsetParent[0] === document.body || this.offsetParent[0].tagName && "html" === this.offsetParent[0].tagName.toLowerCase() && a.ui.ie) && (b = {
                top: 0,
                left: 0
            }), {
                top: b.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: b.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function () {
            if ("relative" === this.cssPosition) {
                var a = this.currentItem.position();
                return {
                    top: a.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: a.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {top: 0, left: 0}
        },
        _cacheMargins: function () {
            this.margins = {
                left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
                top: parseInt(this.currentItem.css("marginTop"), 10) || 0
            }
        },
        _cacheHelperProportions: function () {
            this.helperProportions = {width: this.helper.outerWidth(), height: this.helper.outerHeight()}
        },
        _setContainment: function () {
            var b, c, d, e = this.options;
            "parent" === e.containment && (e.containment = this.helper[0].parentNode), "document" !== e.containment && "window" !== e.containment || (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, a("document" === e.containment ? document : window).width() - this.helperProportions.width - this.margins.left, (a("document" === e.containment ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), /^(document|window|parent)$/.test(e.containment) || (b = a(e.containment)[0], c = a(e.containment).offset(), d = "hidden" !== a(b).css("overflow"), this.containment = [c.left + (parseInt(a(b).css("borderLeftWidth"), 10) || 0) + (parseInt(a(b).css("paddingLeft"), 10) || 0) - this.margins.left, c.top + (parseInt(a(b).css("borderTopWidth"), 10) || 0) + (parseInt(a(b).css("paddingTop"), 10) || 0) - this.margins.top, c.left + (d ? Math.max(b.scrollWidth, b.offsetWidth) : b.offsetWidth) - (parseInt(a(b).css("borderLeftWidth"), 10) || 0) - (parseInt(a(b).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, c.top + (d ? Math.max(b.scrollHeight, b.offsetHeight) : b.offsetHeight) - (parseInt(a(b).css("borderTopWidth"), 10) || 0) - (parseInt(a(b).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top])
        },
        _convertPositionTo: function (b, c) {
            c || (c = this.position);
            var d = "absolute" === b ? 1 : -1,
                e = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                f = /(html|body)/i.test(e[0].tagName);
            return {
                top: c.top + this.offset.relative.top * d + this.offset.parent.top * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : f ? 0 : e.scrollTop()) * d,
                left: c.left + this.offset.relative.left * d + this.offset.parent.left * d - ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : f ? 0 : e.scrollLeft()) * d
            }
        },
        _generatePosition: function (b) {
            var c, d, e = this.options, f = b.pageX, g = b.pageY,
                h = "absolute" !== this.cssPosition || this.scrollParent[0] !== document && a.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent,
                i = /(html|body)/i.test(h[0].tagName);
            return "relative" !== this.cssPosition || this.scrollParent[0] !== document && this.scrollParent[0] !== this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset()), this.originalPosition && (this.containment && (b.pageX - this.offset.click.left < this.containment[0] && (f = this.containment[0] + this.offset.click.left), b.pageY - this.offset.click.top < this.containment[1] && (g = this.containment[1] + this.offset.click.top), b.pageX - this.offset.click.left > this.containment[2] && (f = this.containment[2] + this.offset.click.left), b.pageY - this.offset.click.top > this.containment[3] && (g = this.containment[3] + this.offset.click.top)), e.grid && (c = this.originalPageY + Math.round((g - this.originalPageY) / e.grid[1]) * e.grid[1], g = this.containment ? c - this.offset.click.top >= this.containment[1] && c - this.offset.click.top <= this.containment[3] ? c : c - this.offset.click.top >= this.containment[1] ? c - e.grid[1] : c + e.grid[1] : c, d = this.originalPageX + Math.round((f - this.originalPageX) / e.grid[0]) * e.grid[0], f = this.containment ? d - this.offset.click.left >= this.containment[0] && d - this.offset.click.left <= this.containment[2] ? d : d - this.offset.click.left >= this.containment[0] ? d - e.grid[0] : d + e.grid[0] : d)), {
                top: g - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" === this.cssPosition ? -this.scrollParent.scrollTop() : i ? 0 : h.scrollTop()),
                left: f - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" === this.cssPosition ? -this.scrollParent.scrollLeft() : i ? 0 : h.scrollLeft())
            }
        },
        _rearrange: function (a, b, c, d) {
            c ? c[0].appendChild(this.placeholder[0]) : b.item[0].parentNode.insertBefore(this.placeholder[0], "down" === this.direction ? b.item[0] : b.item[0].nextSibling), this.counter = this.counter ? ++this.counter : 1;
            var e = this.counter;
            this._delay(function () {
                e === this.counter && this.refreshPositions(!d)
            })
        },
        _clear: function (a, b) {
            this.reverting = !1;
            var c, d = [];
            if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] === this.currentItem[0]) {
                for (c in this._storedCSS) "auto" !== this._storedCSS[c] && "static" !== this._storedCSS[c] || (this._storedCSS[c] = "");
                this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")
            } else this.currentItem.show();
            for (this.fromOutside && !b && d.push(function (a) {
                this._trigger("receive", a, this._uiHash(this.fromOutside))
            }), !this.fromOutside && this.domPosition.prev === this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent === this.currentItem.parent()[0] || b || d.push(function (a) {
                this._trigger("update", a, this._uiHash())
            }), this !== this.currentContainer && (b || (d.push(function (a) {
                this._trigger("remove", a, this._uiHash())
            }), d.push(function (a) {
                return function (b) {
                    a._trigger("receive", b, this._uiHash(this))
                }
            }.call(this, this.currentContainer)), d.push(function (a) {
                return function (b) {
                    a._trigger("update", b, this._uiHash(this))
                }
            }.call(this, this.currentContainer)))), c = this.containers.length - 1; c >= 0; c--) b || d.push(function (a) {
                return function (b) {
                    a._trigger("deactivate", b, this._uiHash(this))
                }
            }.call(this, this.containers[c])), this.containers[c].containerCache.over && (d.push(function (a) {
                return function (b) {
                    a._trigger("out", b, this._uiHash(this))
                }
            }.call(this, this.containers[c])), this.containers[c].containerCache.over = 0);
            if (this.storedCursor && (this.document.find("body").css("cursor", this.storedCursor), this.storedStylesheet.remove()), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" === this._storedZIndex ? "" : this._storedZIndex), this.dragging = !1, this.cancelHelperRemoval) {
                if (!b) {
                    for (this._trigger("beforeStop", a, this._uiHash()), c = 0; c < d.length; c++) d[c].call(this, a);
                    this._trigger("stop", a, this._uiHash())
                }
                return this.fromOutside = !1, !1
            }
            if (b || this._trigger("beforeStop", a, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.helper[0] !== this.currentItem[0] && this.helper.remove(), this.helper = null, !b) {
                for (c = 0; c < d.length; c++) d[c].call(this, a);
                this._trigger("stop", a, this._uiHash())
            }
            return this.fromOutside = !1, !0
        },
        _trigger: function () {
            a.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel()
        },
        _uiHash: function (b) {
            var c = b || this;
            return {
                helper: c.helper,
                placeholder: c.placeholder || a([]),
                position: c.position,
                originalPosition: c.originalPosition,
                offset: c.positionAbs,
                item: c.currentItem,
                sender: b ? b.element : null
            }
        }
    })
}(jQuery), function (a, b) {
    var c = "ui-effects-";
    a.effects = {effect: {}}, function (a, b) {
        function m(a, b, c) {
            var d = h[b.type] || {};
            return null == a ? c || !b.def ? null : b.def : (a = d.floor ? ~~a : parseFloat(a), isNaN(a) ? b.def : d.mod ? (a + d.mod) % d.mod : 0 > a ? 0 : d.max < a ? d.max : a)
        }

        function n(b) {
            var c = f(), d = c._rgba = [];
            return b = b.toLowerCase(), l(e, function (a, e) {
                var f, h = e.re.exec(b), i = h && e.parse(h), j = e.space || "rgba";
                if (i) return f = c[j](i), c[g[j].cache] = f[g[j].cache], d = c._rgba = f._rgba, !1
            }), d.length ? ("0,0,0,0" === d.join() && a.extend(d, k.transparent), c) : k[b]
        }

        function o(a, b, c) {
            return c = (c + 1) % 1, 6 * c < 1 ? a + (b - a) * c * 6 : 2 * c < 1 ? b : 3 * c < 2 ? a + (b - a) * (2 / 3 - c) * 6 : a
        }

        var k,
            c = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",
            d = /^([\-+])=\s*(\d+\.?\d*)/, e = [{
                re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                parse: function (a) {
                    return [a[1], a[2], a[3], a[4]]
                }
            }, {
                re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                parse: function (a) {
                    return [2.55 * a[1], 2.55 * a[2], 2.55 * a[3], a[4]]
                }
            }, {
                re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/, parse: function (a) {
                    return [parseInt(a[1], 16), parseInt(a[2], 16), parseInt(a[3], 16)]
                }
            }, {
                re: /#([a-f0-9])([a-f0-9])([a-f0-9])/, parse: function (a) {
                    return [parseInt(a[1] + a[1], 16), parseInt(a[2] + a[2], 16), parseInt(a[3] + a[3], 16)]
                }
            }, {
                re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
                space: "hsla",
                parse: function (a) {
                    return [a[1], a[2] / 100, a[3] / 100, a[4]]
                }
            }], f = a.Color = function (b, c, d, e) {
                return new a.Color.fn.parse(b, c, d, e)
            }, g = {
                rgba: {
                    props: {
                        red: {idx: 0, type: "byte"},
                        green: {idx: 1, type: "byte"},
                        blue: {idx: 2, type: "byte"}
                    }
                },
                hsla: {
                    props: {
                        hue: {idx: 0, type: "degrees"},
                        saturation: {idx: 1, type: "percent"},
                        lightness: {idx: 2, type: "percent"}
                    }
                }
            }, h = {byte: {floor: !0, max: 255}, percent: {max: 1}, degrees: {mod: 360, floor: !0}}, i = f.support = {},
            j = a("<p>")[0], l = a.each;
        j.style.cssText = "background-color:rgba(1,1,1,.5)", i.rgba = j.style.backgroundColor.indexOf("rgba") > -1, l(g, function (a, b) {
            b.cache = "_" + a, b.props.alpha = {idx: 3, type: "percent", def: 1}
        }), f.fn = a.extend(f.prototype, {
            parse: function (c, d, e, h) {
                if (c === b) return this._rgba = [null, null, null, null], this;
                (c.jquery || c.nodeType) && (c = a(c).css(d), d = b);
                var i = this, j = a.type(c), o = this._rgba = [];
                return d !== b && (c = [c, d, e, h], j = "array"), "string" === j ? this.parse(n(c) || k._default) : "array" === j ? (l(g.rgba.props, function (a, b) {
                    o[b.idx] = m(c[b.idx], b)
                }), this) : "object" === j ? (c instanceof f ? l(g, function (a, b) {
                    c[b.cache] && (i[b.cache] = c[b.cache].slice())
                }) : l(g, function (b, d) {
                    var e = d.cache;
                    l(d.props, function (a, b) {
                        if (!i[e] && d.to) {
                            if ("alpha" === a || null == c[a]) return;
                            i[e] = d.to(i._rgba)
                        }
                        i[e][b.idx] = m(c[a], b, !0)
                    }), i[e] && a.inArray(null, i[e].slice(0, 3)) < 0 && (i[e][3] = 1, d.from && (i._rgba = d.from(i[e])))
                }), this) : void 0
            }, is: function (a) {
                var b = f(a), c = !0, d = this;
                return l(g, function (a, e) {
                    var f, g = b[e.cache];
                    return g && (f = d[e.cache] || e.to && e.to(d._rgba) || [], l(e.props, function (a, b) {
                        if (null != g[b.idx]) return c = g[b.idx] === f[b.idx]
                    })), c
                }), c
            }, _space: function () {
                var a = [], b = this;
                return l(g, function (c, d) {
                    b[d.cache] && a.push(c)
                }), a.pop()
            }, transition: function (a, b) {
                var c = f(a), d = c._space(), e = g[d], i = 0 === this.alpha() ? f("transparent") : this,
                    j = i[e.cache] || e.to(i._rgba), k = j.slice();
                return c = c[e.cache], l(e.props, function (a, d) {
                    var e = d.idx, f = j[e], g = c[e], i = h[d.type] || {};
                    null !== g && (null === f ? k[e] = g : (i.mod && (g - f > i.mod / 2 ? f += i.mod : f - g > i.mod / 2 && (f -= i.mod)), k[e] = m((g - f) * b + f, d)))
                }), this[d](k)
            }, blend: function (b) {
                if (1 === this._rgba[3]) return this;
                var c = this._rgba.slice(), d = c.pop(), e = f(b)._rgba;
                return f(a.map(c, function (a, b) {
                    return (1 - d) * e[b] + d * a
                }))
            }, toRgbaString: function () {
                var b = "rgba(", c = a.map(this._rgba, function (a, b) {
                    return null == a ? b > 2 ? 1 : 0 : a
                });
                return 1 === c[3] && (c.pop(), b = "rgb("), b + c.join() + ")"
            }, toHslaString: function () {
                var b = "hsla(", c = a.map(this.hsla(), function (a, b) {
                    return null == a && (a = b > 2 ? 1 : 0), b && b < 3 && (a = Math.round(100 * a) + "%"), a
                });
                return 1 === c[3] && (c.pop(), b = "hsl("), b + c.join() + ")"
            }, toHexString: function (b) {
                var c = this._rgba.slice(), d = c.pop();
                return b && c.push(~~(255 * d)), "#" + a.map(c, function (a) {
                    return a = (a || 0).toString(16), 1 === a.length ? "0" + a : a
                }).join("")
            }, toString: function () {
                return 0 === this._rgba[3] ? "transparent" : this.toRgbaString()
            }
        }), f.fn.parse.prototype = f.fn, g.hsla.to = function (a) {
            if (null == a[0] || null == a[1] || null == a[2]) return [null, null, null, a[3]];
            var k, l, b = a[0] / 255, c = a[1] / 255, d = a[2] / 255, e = a[3], f = Math.max(b, c, d),
                g = Math.min(b, c, d), h = f - g, i = f + g, j = .5 * i;
            return k = g === f ? 0 : b === f ? 60 * (c - d) / h + 360 : c === f ? 60 * (d - b) / h + 120 : 60 * (b - c) / h + 240, l = 0 === h ? 0 : j <= .5 ? h / i : h / (2 - i), [Math.round(k) % 360, l, j, null == e ? 1 : e]
        }, g.hsla.from = function (a) {
            if (null == a[0] || null == a[1] || null == a[2]) return [null, null, null, a[3]];
            var b = a[0] / 360, c = a[1], d = a[2], e = a[3], f = d <= .5 ? d * (1 + c) : d + c - d * c, g = 2 * d - f;
            return [Math.round(255 * o(g, f, b + 1 / 3)), Math.round(255 * o(g, f, b)), Math.round(255 * o(g, f, b - 1 / 3)), e]
        }, l(g, function (c, e) {
            var g = e.props, h = e.cache, i = e.to, j = e.from;
            f.fn[c] = function (c) {
                if (i && !this[h] && (this[h] = i(this._rgba)), c === b) return this[h].slice();
                var d, e = a.type(c), k = "array" === e || "object" === e ? c : arguments, n = this[h].slice();
                return l(g, function (a, b) {
                    var c = k["object" === e ? a : b.idx];
                    null == c && (c = n[b.idx]), n[b.idx] = m(c, b)
                }), j ? (d = f(j(n)), d[h] = n, d) : f(n)
            }, l(g, function (b, e) {
                f.fn[b] || (f.fn[b] = function (f) {
                    var k, g = a.type(f), h = "alpha" === b ? this._hsla ? "hsla" : "rgba" : c, i = this[h](),
                        j = i[e.idx];
                    return "undefined" === g ? j : ("function" === g && (f = f.call(this, j), g = a.type(f)), null == f && e.empty ? this : ("string" === g && (k = d.exec(f), k && (f = j + parseFloat(k[2]) * ("+" === k[1] ? 1 : -1))), i[e.idx] = f, this[h](i)))
                })
            })
        }), f.hook = function (b) {
            var c = b.split(" ");
            l(c, function (b, c) {
                a.cssHooks[c] = {
                    set: function (b, d) {
                        var e, g, h = "";
                        if ("transparent" !== d && ("string" !== a.type(d) || (e = n(d)))) {
                            if (d = f(e || d), !i.rgba && 1 !== d._rgba[3]) {
                                for (g = "backgroundColor" === c ? b.parentNode : b; ("" === h || "transparent" === h) && g && g.style;) try {
                                    h = a.css(g, "backgroundColor"), g = g.parentNode
                                } catch (a) {
                                }
                                d = d.blend(h && "transparent" !== h ? h : "_default")
                            }
                            d = d.toRgbaString()
                        }
                        try {
                            b.style[c] = d
                        } catch (a) {
                        }
                    }
                }, a.fx.step[c] = function (b) {
                    b.colorInit || (b.start = f(b.elem, c), b.end = f(b.end), b.colorInit = !0), a.cssHooks[c].set(b.elem, b.start.transition(b.end, b.pos))
                }
            })
        }, f.hook(c), a.cssHooks.borderColor = {
            expand: function (a) {
                var b = {};
                return l(["Top", "Right", "Bottom", "Left"], function (c, d) {
                    b["border" + d + "Color"] = a
                }), b
            }
        }, k = a.Color.names = {
            aqua: "#00ffff",
            black: "#000000",
            blue: "#0000ff",
            fuchsia: "#ff00ff",
            gray: "#808080",
            green: "#008000",
            lime: "#00ff00",
            maroon: "#800000",
            navy: "#000080",
            olive: "#808000",
            purple: "#800080",
            red: "#ff0000",
            silver: "#c0c0c0",
            teal: "#008080",
            white: "#ffffff",
            yellow: "#ffff00",
            transparent: [null, null, null, 0],
            _default: "#ffffff"
        }
    }(jQuery), function () {
        function e(b) {
            var c, d,
                e = b.ownerDocument.defaultView ? b.ownerDocument.defaultView.getComputedStyle(b, null) : b.currentStyle,
                f = {};
            if (e && e.length && e[0] && e[e[0]]) for (d = e.length; d--;) c = e[d], "string" == typeof e[c] && (f[a.camelCase(c)] = e[c]); else for (c in e) "string" == typeof e[c] && (f[c] = e[c]);
            return f
        }

        function f(b, c) {
            var f, g, e = {};
            for (f in c) g = c[f], b[f] !== g && (d[f] || !a.fx.step[f] && isNaN(parseFloat(g)) || (e[f] = g));
            return e
        }

        var c = ["add", "remove", "toggle"], d = {
            border: 1,
            borderBottom: 1,
            borderColor: 1,
            borderLeft: 1,
            borderRight: 1,
            borderTop: 1,
            borderWidth: 1,
            margin: 1,
            padding: 1
        };
        a.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function (b, c) {
            a.fx.step[c] = function (a) {
                ("none" !== a.end && !a.setAttr || 1 === a.pos && !a.setAttr) && (jQuery.style(a.elem, c, a.end), a.setAttr = !0)
            }
        }), a.fn.addBack || (a.fn.addBack = function (a) {
            return this.add(null == a ? this.prevObject : this.prevObject.filter(a))
        }), a.effects.animateClass = function (b, d, g, h) {
            var i = a.speed(d, g, h);
            return this.queue(function () {
                var h, d = a(this), g = d.attr("class") || "", j = i.children ? d.find("*").addBack() : d;
                j = j.map(function () {
                    var b = a(this);
                    return {el: b, start: e(this)}
                }), h = function () {
                    a.each(c, function (a, c) {
                        b[c] && d[c + "Class"](b[c])
                    })
                }, h(), j = j.map(function () {
                    return this.end = e(this.el[0]), this.diff = f(this.start, this.end), this
                }), d.attr("class", g), j = j.map(function () {
                    var b = this, c = a.Deferred(), d = a.extend({}, i, {
                        queue: !1, complete: function () {
                            c.resolve(b)
                        }
                    });
                    return this.el.animate(this.diff, d), c.promise()
                }), a.when.apply(a, j.get()).done(function () {
                    h(), a.each(arguments, function () {
                        var b = this.el;
                        a.each(this.diff, function (a) {
                            b.css(a, "")
                        })
                    }), i.complete.call(d[0])
                })
            })
        }, a.fn.extend({
            addClass: function (b) {
                return function (c, d, e, f) {
                    return d ? a.effects.animateClass.call(this, {add: c}, d, e, f) : b.apply(this, arguments)
                }
            }(a.fn.addClass), removeClass: function (b) {
                return function (c, d, e, f) {
                    return arguments.length > 1 ? a.effects.animateClass.call(this, {remove: c}, d, e, f) : b.apply(this, arguments)
                }
            }(a.fn.removeClass), toggleClass: function (c) {
                return function (d, e, f, g, h) {
                    return "boolean" == typeof e || e === b ? f ? a.effects.animateClass.call(this, e ? {add: d} : {remove: d}, f, g, h) : c.apply(this, arguments) : a.effects.animateClass.call(this, {toggle: d}, e, f, g)
                }
            }(a.fn.toggleClass), switchClass: function (b, c, d, e, f) {
                return a.effects.animateClass.call(this, {add: c, remove: b}, d, e, f)
            }
        })
    }(), function () {
        function d(b, c, d, e) {
            return a.isPlainObject(b) && (c = b, b = b.effect), b = {effect: b}, null == c && (c = {}), a.isFunction(c) && (e = c, d = null, c = {}), ("number" == typeof c || a.fx.speeds[c]) && (e = d, d = c, c = {}), a.isFunction(d) && (e = d, d = null), c && a.extend(b, c), d = d || c.duration, b.duration = a.fx.off ? 0 : "number" == typeof d ? d : d in a.fx.speeds ? a.fx.speeds[d] : a.fx.speeds._default, b.complete = e || c.complete, b
        }

        function e(b) {
            return !(b && "number" != typeof b && !a.fx.speeds[b]) || ("string" == typeof b && !a.effects.effect[b] || (!!a.isFunction(b) || "object" == typeof b && !b.effect))
        }

        a.extend(a.effects, {
            version: "1.10.3", save: function (a, b) {
                for (var d = 0; d < b.length; d++) null !== b[d] && a.data(c + b[d], a[0].style[b[d]])
            }, restore: function (a, d) {
                var e, f;
                for (f = 0; f < d.length; f++) null !== d[f] && (e = a.data(c + d[f]), e === b && (e = ""), a.css(d[f], e))
            }, setMode: function (a, b) {
                return "toggle" === b && (b = a.is(":hidden") ? "show" : "hide"), b
            }, getBaseline: function (a, b) {
                var c, d;
                switch (a[0]) {
                    case"top":
                        c = 0;
                        break;
                    case"middle":
                        c = .5;
                        break;
                    case"bottom":
                        c = 1;
                        break;
                    default:
                        c = a[0] / b.height
                }
                switch (a[1]) {
                    case"left":
                        d = 0;
                        break;
                    case"center":
                        d = .5;
                        break;
                    case"right":
                        d = 1;
                        break;
                    default:
                        d = a[1] / b.width
                }
                return {x: d, y: c}
            }, createWrapper: function (b) {
                if (b.parent().is(".ui-effects-wrapper")) return b.parent();
                var c = {width: b.outerWidth(!0), height: b.outerHeight(!0), float: b.css("float")},
                    d = a("<div></div>").addClass("ui-effects-wrapper").css({
                        fontSize: "100%",
                        background: "transparent",
                        border: "none",
                        margin: 0,
                        padding: 0
                    }), e = {width: b.width(), height: b.height()}, f = document.activeElement;
                try {
                    f.id
                } catch (a) {
                    f = document.body
                }
                return b.wrap(d), (b[0] === f || a.contains(b[0], f)) && a(f).focus(), d = b.parent(), "static" === b.css("position") ? (d.css({position: "relative"}), b.css({position: "relative"})) : (a.extend(c, {
                    position: b.css("position"),
                    zIndex: b.css("z-index")
                }), a.each(["top", "left", "bottom", "right"], function (a, d) {
                    c[d] = b.css(d), isNaN(parseInt(c[d], 10)) && (c[d] = "auto")
                }), b.css({
                    position: "relative",
                    top: 0,
                    left: 0,
                    right: "auto",
                    bottom: "auto"
                })), b.css(e), d.css(c).show()
            }, removeWrapper: function (b) {
                var c = document.activeElement;
                return b.parent().is(".ui-effects-wrapper") && (b.parent().replaceWith(b), (b[0] === c || a.contains(b[0], c)) && a(c).focus()), b
            }, setTransition: function (b, c, d, e) {
                return e = e || {}, a.each(c, function (a, c) {
                    var f = b.cssUnit(c);
                    f[0] > 0 && (e[c] = f[0] * d + f[1])
                }), e
            }
        }), a.fn.extend({
            effect: function () {
                function g(c) {
                    function h() {
                        a.isFunction(e) && e.call(d[0]), a.isFunction(c) && c()
                    }

                    var d = a(this), e = b.complete, g = b.mode;
                    (d.is(":hidden") ? "hide" === g : "show" === g) ? (d[g](), h()) : f.call(d[0], b, h)
                }

                var b = d.apply(this, arguments), c = b.mode, e = b.queue, f = a.effects.effect[b.effect];
                return a.fx.off || !f ? c ? this[c](b.duration, b.complete) : this.each(function () {
                    b.complete && b.complete.call(this)
                }) : e === !1 ? this.each(g) : this.queue(e || "fx", g)
            }, show: function (a) {
                return function (b) {
                    if (e(b)) return a.apply(this, arguments);
                    var c = d.apply(this, arguments);
                    return c.mode = "show", this.effect.call(this, c)
                }
            }(a.fn.show), hide: function (a) {
                return function (b) {
                    if (e(b)) return a.apply(this, arguments);
                    var c = d.apply(this, arguments);
                    return c.mode = "hide", this.effect.call(this, c)
                }
            }(a.fn.hide), toggle: function (a) {
                return function (b) {
                    if (e(b) || "boolean" == typeof b) return a.apply(this, arguments);
                    var c = d.apply(this, arguments);
                    return c.mode = "toggle", this.effect.call(this, c)
                }
            }(a.fn.toggle), cssUnit: function (b) {
                var c = this.css(b), d = [];
                return a.each(["em", "px", "%", "pt"], function (a, b) {
                    c.indexOf(b) > 0 && (d = [parseFloat(c), b])
                }), d
            }
        })
    }(), function () {
        var b = {};
        a.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function (a, c) {
            b[c] = function (b) {
                return Math.pow(b, a + 2)
            }
        }), a.extend(b, {
            Sine: function (a) {
                return 1 - Math.cos(a * Math.PI / 2)
            }, Circ: function (a) {
                return 1 - Math.sqrt(1 - a * a)
            }, Elastic: function (a) {
                return 0 === a || 1 === a ? a : -Math.pow(2, 8 * (a - 1)) * Math.sin((80 * (a - 1) - 7.5) * Math.PI / 15)
            }, Back: function (a) {
                return a * a * (3 * a - 2)
            }, Bounce: function (a) {
                for (var b, c = 4; a < ((b = Math.pow(2, --c)) - 1) / 11;) ;
                return 1 / Math.pow(4, 3 - c) - 7.5625 * Math.pow((3 * b - 2) / 22 - a, 2)
            }
        }), a.each(b, function (b, c) {
            a.easing["easeIn" + b] = c, a.easing["easeOut" + b] = function (a) {
                return 1 - c(1 - a)
            }, a.easing["easeInOut" + b] = function (a) {
                return a < .5 ? c(2 * a) / 2 : 1 - c(a * -2 + 2) / 2
            }
        })
    }()
}(jQuery), function (a, b) {
    var c = 0, d = {}, e = {};
    d.height = d.paddingTop = d.paddingBottom = d.borderTopWidth = d.borderBottomWidth = "hide", e.height = e.paddingTop = e.paddingBottom = e.borderTopWidth = e.borderBottomWidth = "show", a.widget("ui.accordion", {
        version: "1.10.3",
        options: {
            active: 0,
            animate: {},
            collapsible: !1,
            event: "click",
            header: "> li > :first-child,> :not(li):even",
            heightStyle: "auto",
            icons: {activeHeader: "ui-icon-triangle-1-s", header: "ui-icon-triangle-1-e"},
            activate: null,
            beforeActivate: null
        },
        _create: function () {
            var b = this.options;
            this.prevShow = this.prevHide = a(), this.element.addClass("ui-accordion ui-widget ui-helper-reset").attr("role", "tablist"), b.collapsible || b.active !== !1 && null != b.active || (b.active = 0), this._processPanels(), b.active < 0 && (b.active += this.headers.length), this._refresh()
        },
        _getCreateEventData: function () {
            return {
                header: this.active,
                panel: this.active.length ? this.active.next() : a(),
                content: this.active.length ? this.active.next() : a()
            }
        },
        _createIcons: function () {
            var b = this.options.icons;
            b && (a("<span>").addClass("ui-accordion-header-icon ui-icon " + b.header).prependTo(this.headers), this.active.children(".ui-accordion-header-icon").removeClass(b.header).addClass(b.activeHeader), this.headers.addClass("ui-accordion-icons"))
        },
        _destroyIcons: function () {
            this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()
        },
        _destroy: function () {
            var a;
            this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"), this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").each(function () {
                /^ui-accordion/.test(this.id) && this.removeAttribute("id")
            }), this._destroyIcons(), a = this.headers.next().css("display", "").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").each(function () {
                /^ui-accordion/.test(this.id) && this.removeAttribute("id")
            }), "content" !== this.options.heightStyle && a.css("height", "")
        },
        _setOption: function (a, b) {
            return "active" === a ? void this._activate(b) : ("event" === a && (this.options.event && this._off(this.headers, this.options.event), this._setupEvents(b)), this._super(a, b), "collapsible" !== a || b || this.options.active !== !1 || this._activate(0), "icons" === a && (this._destroyIcons(), b && this._createIcons()), void("disabled" === a && this.headers.add(this.headers.next()).toggleClass("ui-state-disabled", !!b)))
        },
        _keydown: function (b) {
            if (!b.altKey && !b.ctrlKey) {
                var c = a.ui.keyCode, d = this.headers.length, e = this.headers.index(b.target), f = !1;
                switch (b.keyCode) {
                    case c.RIGHT:
                    case c.DOWN:
                        f = this.headers[(e + 1) % d];
                        break;
                    case c.LEFT:
                    case c.UP:
                        f = this.headers[(e - 1 + d) % d];
                        break;
                    case c.SPACE:
                    case c.ENTER:
                        this._eventHandler(b);
                        break;
                    case c.HOME:
                        f = this.headers[0];
                        break;
                    case c.END:
                        f = this.headers[d - 1]
                }
                f && (a(b.target).attr("tabIndex", -1), a(f).attr("tabIndex", 0), f.focus(), b.preventDefault())
            }
        },
        _panelKeyDown: function (b) {
            b.keyCode === a.ui.keyCode.UP && b.ctrlKey && a(b.currentTarget).prev().focus()
        },
        refresh: function () {
            var b = this.options;
            this._processPanels(), b.active === !1 && b.collapsible === !0 || !this.headers.length ? (b.active = !1, this.active = a()) : b.active === !1 ? this._activate(0) : this.active.length && !a.contains(this.element[0], this.active[0]) ? this.headers.length === this.headers.find(".ui-state-disabled").length ? (b.active = !1, this.active = a()) : this._activate(Math.max(0, b.active - 1)) : b.active = this.headers.index(this.active), this._destroyIcons(), this._refresh()
        },
        _processPanels: function () {
            this.headers = this.element.find(this.options.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"), this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").filter(":not(.ui-accordion-content-active)").hide()
        },
        _refresh: function () {
            var b, d = this.options, e = d.heightStyle, f = this.element.parent(),
                g = this.accordionId = "ui-accordion-" + (this.element.attr("id") || ++c);
            this.active = this._findActive(d.active).addClass("ui-accordion-header-active ui-state-active ui-corner-top").removeClass("ui-corner-all"), this.active.next().addClass("ui-accordion-content-active").show(), this.headers.attr("role", "tab").each(function (b) {
                var c = a(this), d = c.attr("id"), e = c.next(), f = e.attr("id");
                d || (d = g + "-header-" + b, c.attr("id", d)), f || (f = g + "-panel-" + b, e.attr("id", f)), c.attr("aria-controls", f), e.attr("aria-labelledby", d)
            }).next().attr("role", "tabpanel"), this.headers.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }).next().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }).hide(), this.active.length ? this.active.attr({
                "aria-selected": "true",
                tabIndex: 0
            }).next().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }) : this.headers.eq(0).attr("tabIndex", 0), this._createIcons(), this._setupEvents(d.event), "fill" === e ? (b = f.height(), this.element.siblings(":visible").each(function () {
                var c = a(this), d = c.css("position");
                "absolute" !== d && "fixed" !== d && (b -= c.outerHeight(!0))
            }), this.headers.each(function () {
                b -= a(this).outerHeight(!0)
            }), this.headers.next().each(function () {
                a(this).height(Math.max(0, b - a(this).innerHeight() + a(this).height()))
            }).css("overflow", "auto")) : "auto" === e && (b = 0, this.headers.next().each(function () {
                b = Math.max(b, a(this).css("height", "").height())
            }).height(b))
        },
        _activate: function (b) {
            var c = this._findActive(b)[0];
            c !== this.active[0] && (c = c || this.active[0], this._eventHandler({
                target: c,
                currentTarget: c,
                preventDefault: a.noop
            }))
        },
        _findActive: function (b) {
            return "number" == typeof b ? this.headers.eq(b) : a()
        },
        _setupEvents: function (b) {
            var c = {keydown: "_keydown"};
            b && a.each(b.split(" "), function (a, b) {
                c[b] = "_eventHandler"
            }), this._off(this.headers.add(this.headers.next())), this._on(this.headers, c), this._on(this.headers.next(), {keydown: "_panelKeyDown"}), this._hoverable(this.headers), this._focusable(this.headers)
        },
        _eventHandler: function (b) {
            var c = this.options, d = this.active, e = a(b.currentTarget), f = e[0] === d[0], g = f && c.collapsible,
                h = g ? a() : e.next(), i = d.next(),
                j = {oldHeader: d, oldPanel: i, newHeader: g ? a() : e, newPanel: h};
            b.preventDefault(), f && !c.collapsible || this._trigger("beforeActivate", b, j) === !1 || (c.active = !g && this.headers.index(e), this.active = f ? a() : e, this._toggle(j), d.removeClass("ui-accordion-header-active ui-state-active"), c.icons && d.children(".ui-accordion-header-icon").removeClass(c.icons.activeHeader).addClass(c.icons.header), f || (e.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"), c.icons && e.children(".ui-accordion-header-icon").removeClass(c.icons.header).addClass(c.icons.activeHeader), e.next().addClass("ui-accordion-content-active")))
        },
        _toggle: function (b) {
            var c = b.newPanel, d = this.prevShow.length ? this.prevShow : b.oldPanel;
            this.prevShow.add(this.prevHide).stop(!0, !0), this.prevShow = c, this.prevHide = d, this.options.animate ? this._animate(c, d, b) : (d.hide(), c.show(), this._toggleComplete(b)), d.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }), d.prev().attr("aria-selected", "false"), c.length && d.length ? d.prev().attr("tabIndex", -1) : c.length && this.headers.filter(function () {
                return 0 === a(this).attr("tabIndex")
            }).attr("tabIndex", -1), c.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }).prev().attr({"aria-selected": "true", tabIndex: 0})
        },
        _animate: function (a, b, c) {
            var f, g, h, i = this, j = 0, k = a.length && (!b.length || a.index() < b.index()),
                l = this.options.animate || {}, m = k && l.down || l, n = function () {
                    i._toggleComplete(c)
                };
            return "number" == typeof m && (h = m), "string" == typeof m && (g = m), g = g || m.easing || l.easing, h = h || m.duration || l.duration, b.length ? a.length ? (f = a.show().outerHeight(), b.animate(d, {
                duration: h,
                easing: g,
                step: function (a, b) {
                    b.now = Math.round(a)
                }
            }), void a.hide().animate(e, {
                duration: h, easing: g, complete: n, step: function (a, c) {
                    c.now = Math.round(a), "height" !== c.prop ? j += c.now : "content" !== i.options.heightStyle && (c.now = Math.round(f - b.outerHeight() - j), j = 0)
                }
            })) : b.animate(d, h, g, n) : a.animate(e, h, g, n)
        },
        _toggleComplete: function (a) {
            var b = a.oldPanel;
            b.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"), b.length && (b.parent()[0].className = b.parent()[0].className), this._trigger("activate", null, a)
        }
    })
}(jQuery), function (a, b) {
    var c = 0;
    a.widget("ui.autocomplete", {
        version: "1.10.3",
        defaultElement: "<input>",
        options: {
            appendTo: null,
            autoFocus: !1,
            delay: 300,
            minLength: 1,
            position: {my: "left top", at: "left bottom", collision: "none"},
            source: null,
            change: null,
            close: null,
            focus: null,
            open: null,
            response: null,
            search: null,
            select: null
        },
        pending: 0,
        _create: function () {
            var b, c, d, e = this.element[0].nodeName.toLowerCase(), f = "textarea" === e, g = "input" === e;
            this.isMultiLine = !!f || !g && this.element.prop("isContentEditable"), this.valueMethod = this.element[f || g ? "val" : "text"], this.isNewMenu = !0, this.element.addClass("ui-autocomplete-input").attr("autocomplete", "off"), this._on(this.element, {
                keydown: function (e) {
                    if (this.element.prop("readOnly")) return b = !0, d = !0, void(c = !0);
                    b = !1, d = !1, c = !1;
                    var f = a.ui.keyCode;
                    switch (e.keyCode) {
                        case f.PAGE_UP:
                            b = !0, this._move("previousPage", e);
                            break;
                        case f.PAGE_DOWN:
                            b = !0, this._move("nextPage", e);
                            break;
                        case f.UP:
                            b = !0, this._keyEvent("previous", e);
                            break;
                        case f.DOWN:
                            b = !0, this._keyEvent("next", e);
                            break;
                        case f.ENTER:
                        case f.NUMPAD_ENTER:
                            this.menu.active && (b = !0, e.preventDefault(), this.menu.select(e));
                            break;
                        case f.TAB:
                            this.menu.active && this.menu.select(e);
                            break;
                        case f.ESCAPE:
                            this.menu.element.is(":visible") && (this._value(this.term), this.close(e), e.preventDefault());
                            break;
                        default:
                            c = !0, this._searchTimeout(e)
                    }
                }, keypress: function (d) {
                    if (b) return b = !1, void(this.isMultiLine && !this.menu.element.is(":visible") || d.preventDefault());
                    if (!c) {
                        var e = a.ui.keyCode;
                        switch (d.keyCode) {
                            case e.PAGE_UP:
                                this._move("previousPage", d);
                                break;
                            case e.PAGE_DOWN:
                                this._move("nextPage", d);
                                break;
                            case e.UP:
                                this._keyEvent("previous", d);
                                break;
                            case e.DOWN:
                                this._keyEvent("next", d)
                        }
                    }
                }, input: function (a) {
                    return d ? (d = !1, void a.preventDefault()) : void this._searchTimeout(a)
                }, focus: function () {
                    this.selectedItem = null, this.previous = this._value()
                }, blur: function (a) {
                    return this.cancelBlur ? void delete this.cancelBlur : (clearTimeout(this.searching), this.close(a), void this._change(a))
                }
            }), this._initSource(), this.menu = a("<ul>").addClass("ui-autocomplete ui-front").appendTo(this._appendTo()).menu({role: null}).hide().data("ui-menu"), this._on(this.menu.element, {
                mousedown: function (b) {
                    b.preventDefault(), this.cancelBlur = !0, this._delay(function () {
                        delete this.cancelBlur
                    });
                    var c = this.menu.element[0];
                    a(b.target).closest(".ui-menu-item").length || this._delay(function () {
                        var b = this;
                        this.document.one("mousedown", function (d) {
                            d.target === b.element[0] || d.target === c || a.contains(c, d.target) || b.close()
                        })
                    })
                }, menufocus: function (b, c) {
                    if (this.isNewMenu && (this.isNewMenu = !1, b.originalEvent && /^mouse/.test(b.originalEvent.type))) return this.menu.blur(), void this.document.one("mousemove", function () {
                        a(b.target).trigger(b.originalEvent)
                    });
                    var d = c.item.data("ui-autocomplete-item");
                    !1 !== this._trigger("focus", b, {item: d}) ? b.originalEvent && /^key/.test(b.originalEvent.type) && this._value(d.value) : this.liveRegion.text(d.value)
                }, menuselect: function (a, b) {
                    var c = b.item.data("ui-autocomplete-item"), d = this.previous;
                    this.element[0] !== this.document[0].activeElement && (this.element.focus(), this.previous = d, this._delay(function () {
                        this.previous = d, this.selectedItem = c
                    })), !1 !== this._trigger("select", a, {item: c}) && this._value(c.value), this.term = this._value(), this.close(a), this.selectedItem = c
                }
            }), this.liveRegion = a("<span>", {
                role: "status",
                "aria-live": "polite"
            }).addClass("ui-helper-hidden-accessible").insertBefore(this.element), this._on(this.window, {
                beforeunload: function () {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _destroy: function () {
            clearTimeout(this.searching), this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"), this.menu.element.remove(), this.liveRegion.remove()
        },
        _setOption: function (a, b) {
            this._super(a, b), "source" === a && this._initSource(), "appendTo" === a && this.menu.element.appendTo(this._appendTo()), "disabled" === a && b && this.xhr && this.xhr.abort()
        },
        _appendTo: function () {
            var b = this.options.appendTo;
            return b && (b = b.jquery || b.nodeType ? a(b) : this.document.find(b).eq(0)), b || (b = this.element.closest(".ui-front")), b.length || (b = this.document[0].body), b
        },
        _initSource: function () {
            var b, c, d = this;
            a.isArray(this.options.source) ? (b = this.options.source, this.source = function (c, d) {
                d(a.ui.autocomplete.filter(b, c.term))
            }) : "string" == typeof this.options.source ? (c = this.options.source, this.source = function (b, e) {
                d.xhr && d.xhr.abort(), d.xhr = a.ajax({
                    url: c, data: b, dataType: "json", success: function (a) {
                        e(a)
                    }, error: function () {
                        e([])
                    }
                })
            }) : this.source = this.options.source
        },
        _searchTimeout: function (a) {
            clearTimeout(this.searching), this.searching = this._delay(function () {
                this.term !== this._value() && (this.selectedItem = null, this.search(null, a))
            }, this.options.delay)
        },
        search: function (a, b) {
            return a = null != a ? a : this._value(), this.term = this._value(), a.length < this.options.minLength ? this.close(b) : this._trigger("search", b) !== !1 ? this._search(a) : void 0
        },
        _search: function (a) {
            this.pending++, this.element.addClass("ui-autocomplete-loading"), this.cancelSearch = !1, this.source({term: a}, this._response())
        },
        _response: function () {
            var a = this, b = ++c;
            return function (d) {
                b === c && a.__response(d), a.pending--, a.pending || a.element.removeClass("ui-autocomplete-loading")
            }
        },
        __response: function (a) {
            a && (a = this._normalize(a)), this._trigger("response", null, {content: a}), !this.options.disabled && a && a.length && !this.cancelSearch ? (this._suggest(a), this._trigger("open")) : this._close()
        },
        close: function (a) {
            this.cancelSearch = !0, this._close(a)
        },
        _close: function (a) {
            this.menu.element.is(":visible") && (this.menu.element.hide(), this.menu.blur(), this.isNewMenu = !0, this._trigger("close", a))
        },
        _change: function (a) {
            this.previous !== this._value() && this._trigger("change", a, {item: this.selectedItem})
        },
        _normalize: function (b) {
            return b.length && b[0].label && b[0].value ? b : a.map(b, function (b) {
                return "string" == typeof b ? {label: b, value: b} : a.extend({
                    label: b.label || b.value,
                    value: b.value || b.label
                }, b)
            })
        },
        _suggest: function (b) {
            var c = this.menu.element.empty();
            this._renderMenu(c, b), this.isNewMenu = !0, this.menu.refresh(), c.show(), this._resizeMenu(), c.position(a.extend({of: this.element}, this.options.position)), this.options.autoFocus && this.menu.next()
        },
        _resizeMenu: function () {
            var a = this.menu.element;
            a.outerWidth(Math.max(a.width("").outerWidth() + 1, this.element.outerWidth()))
        },
        _renderMenu: function (b, c) {
            var d = this;
            a.each(c, function (a, c) {
                d._renderItemData(b, c)
            })
        },
        _renderItemData: function (a, b) {
            return this._renderItem(a, b).data("ui-autocomplete-item", b)
        },
        _renderItem: function (b, c) {
            return a("<li>").append(a("<a>").text(c.label)).appendTo(b)
        },
        _move: function (a, b) {
            return this.menu.element.is(":visible") ? this.menu.isFirstItem() && /^previous/.test(a) || this.menu.isLastItem() && /^next/.test(a) ? (this._value(this.term), void this.menu.blur()) : void this.menu[a](b) : void this.search(null, b)
        },
        widget: function () {
            return this.menu.element
        },
        _value: function () {
            return this.valueMethod.apply(this.element, arguments)
        },
        _keyEvent: function (a, b) {
            this.isMultiLine && !this.menu.element.is(":visible") || (this._move(a, b), b.preventDefault())
        }
    }), a.extend(a.ui.autocomplete, {
        escapeRegex: function (a) {
            return a.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
        }, filter: function (b, c) {
            var d = new RegExp(a.ui.autocomplete.escapeRegex(c), "i");
            return a.grep(b, function (a) {
                return d.test(a.label || a.value || a)
            })
        }
    }), a.widget("ui.autocomplete", a.ui.autocomplete, {
        options: {
            messages: {
                noResults: "No search results.",
                results: function (a) {
                    return a + (a > 1 ? " results are" : " result is") + " available, use up and down arrow keys to navigate."
                }
            }
        }, __response: function (a) {
            var b;
            this._superApply(arguments), this.options.disabled || this.cancelSearch || (b = a && a.length ? this.options.messages.results(a.length) : this.options.messages.noResults, this.liveRegion.text(b))
        }
    })
}(jQuery), function (a, b) {
    var c, d, e, f, g = "ui-button ui-widget ui-state-default ui-corner-all", h = "ui-state-hover ui-state-active ",
        i = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only",
        j = function () {
            var b = a(this);
            setTimeout(function () {
                b.find(":ui-button").button("refresh")
            }, 1)
        }, k = function (b) {
            var c = b.name, d = b.form, e = a([]);
            return c && (c = c.replace(/'/g, "\\'"), e = d ? a(d).find("[name='" + c + "']") : a("[name='" + c + "']", b.ownerDocument).filter(function () {
                return !this.form
            })), e
        };
    a.widget("ui.button", {
        version: "1.10.3",
        defaultElement: "<button>",
        options: {disabled: null, text: !0, label: null, icons: {primary: null, secondary: null}},
        _create: function () {
            this.element.closest("form").unbind("reset" + this.eventNamespace).bind("reset" + this.eventNamespace, j), "boolean" != typeof this.options.disabled ? this.options.disabled = !!this.element.prop("disabled") : this.element.prop("disabled", this.options.disabled), this._determineButtonType(), this.hasTitle = !!this.buttonElement.attr("title");
            var b = this, h = this.options, i = "checkbox" === this.type || "radio" === this.type,
                l = i ? "" : "ui-state-active", m = "ui-state-focus";
            null === h.label && (h.label = "input" === this.type ? this.buttonElement.val() : this.buttonElement.html()), this._hoverable(this.buttonElement), this.buttonElement.addClass(g).attr("role", "button").bind("mouseenter" + this.eventNamespace, function () {
                h.disabled || this === c && a(this).addClass("ui-state-active")
            }).bind("mouseleave" + this.eventNamespace, function () {
                h.disabled || a(this).removeClass(l)
            }).bind("click" + this.eventNamespace, function (a) {
                h.disabled && (a.preventDefault(), a.stopImmediatePropagation())
            }), this.element.bind("focus" + this.eventNamespace, function () {
                b.buttonElement.addClass(m)
            }).bind("blur" + this.eventNamespace, function () {
                b.buttonElement.removeClass(m)
            }), i && (this.element.bind("change" + this.eventNamespace, function () {
                f || b.refresh()
            }), this.buttonElement.bind("mousedown" + this.eventNamespace, function (a) {
                h.disabled || (f = !1, d = a.pageX, e = a.pageY)
            }).bind("mouseup" + this.eventNamespace, function (a) {
                h.disabled || d === a.pageX && e === a.pageY || (f = !0)
            })), "checkbox" === this.type ? this.buttonElement.bind("click" + this.eventNamespace, function () {
                if (h.disabled || f) return !1
            }) : "radio" === this.type ? this.buttonElement.bind("click" + this.eventNamespace, function () {
                if (h.disabled || f) return !1;
                a(this).addClass("ui-state-active"), b.buttonElement.attr("aria-pressed", "true");
                var c = b.element[0];
                k(c).not(c).map(function () {
                    return a(this).button("widget")[0]
                }).removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : (this.buttonElement.bind("mousedown" + this.eventNamespace, function () {
                return !h.disabled && (a(this).addClass("ui-state-active"), c = this, void b.document.one("mouseup", function () {
                    c = null
                }))
            }).bind("mouseup" + this.eventNamespace, function () {
                return !h.disabled && void a(this).removeClass("ui-state-active")
            }).bind("keydown" + this.eventNamespace, function (b) {
                return !h.disabled && void(b.keyCode !== a.ui.keyCode.SPACE && b.keyCode !== a.ui.keyCode.ENTER || a(this).addClass("ui-state-active"))
            }).bind("keyup" + this.eventNamespace + " blur" + this.eventNamespace, function () {
                a(this).removeClass("ui-state-active")
            }), this.buttonElement.is("a") && this.buttonElement.keyup(function (b) {
                b.keyCode === a.ui.keyCode.SPACE && a(this).click()
            })), this._setOption("disabled", h.disabled), this._resetButton()
        },
        _determineButtonType: function () {
            var a, b, c;
            this.element.is("[type=checkbox]") ? this.type = "checkbox" : this.element.is("[type=radio]") ? this.type = "radio" : this.element.is("input") ? this.type = "input" : this.type = "button", "checkbox" === this.type || "radio" === this.type ? (a = this.element.parents().last(), b = "label[for='" + this.element.attr("id") + "']", this.buttonElement = a.find(b), this.buttonElement.length || (a = a.length ? a.siblings() : this.element.siblings(), this.buttonElement = a.filter(b), this.buttonElement.length || (this.buttonElement = a.find(b))), this.element.addClass("ui-helper-hidden-accessible"), c = this.element.is(":checked"), c && this.buttonElement.addClass("ui-state-active"), this.buttonElement.prop("aria-pressed", c)) : this.buttonElement = this.element
        },
        widget: function () {
            return this.buttonElement
        },
        _destroy: function () {
            this.element.removeClass("ui-helper-hidden-accessible"), this.buttonElement.removeClass(g + " " + h + " " + i).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()), this.hasTitle || this.buttonElement.removeAttr("title")
        },
        _setOption: function (a, b) {
            return this._super(a, b), "disabled" === a ? void(b ? this.element.prop("disabled", !0) : this.element.prop("disabled", !1)) : void this._resetButton()
        },
        refresh: function () {
            var b = this.element.is("input, button") ? this.element.is(":disabled") : this.element.hasClass("ui-button-disabled");
            b !== this.options.disabled && this._setOption("disabled", b), "radio" === this.type ? k(this.element[0]).each(function () {
                a(this).is(":checked") ? a(this).button("widget").addClass("ui-state-active").attr("aria-pressed", "true") : a(this).button("widget").removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : "checkbox" === this.type && (this.element.is(":checked") ? this.buttonElement.addClass("ui-state-active").attr("aria-pressed", "true") : this.buttonElement.removeClass("ui-state-active").attr("aria-pressed", "false"))
        },
        _resetButton: function () {
            if ("input" === this.type) return void(this.options.label && this.element.val(this.options.label));
            var b = this.buttonElement.removeClass(i),
                c = a("<span></span>", this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(b.empty()).text(),
                d = this.options.icons, e = d.primary && d.secondary, f = [];
            d.primary || d.secondary ? (this.options.text && f.push("ui-button-text-icon" + (e ? "s" : d.primary ? "-primary" : "-secondary")), d.primary && b.prepend("<span class='ui-button-icon-primary ui-icon " + d.primary + "'></span>"), d.secondary && b.append("<span class='ui-button-icon-secondary ui-icon " + d.secondary + "'></span>"), this.options.text || (f.push(e ? "ui-button-icons-only" : "ui-button-icon-only"), this.hasTitle || b.attr("title", a.trim(c)))) : f.push("ui-button-text-only"), b.addClass(f.join(" "))
        }
    }), a.widget("ui.buttonset", {
        version: "1.10.3",
        options: {items: "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(ui-button)"},
        _create: function () {
            this.element.addClass("ui-buttonset")
        },
        _init: function () {
            this.refresh()
        },
        _setOption: function (a, b) {
            "disabled" === a && this.buttons.button("option", a, b), this._super(a, b)
        },
        refresh: function () {
            var b = "rtl" === this.element.css("direction");
            this.buttons = this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function () {
                return a(this).button("widget")[0]
            }).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(b ? "ui-corner-right" : "ui-corner-left").end().filter(":last").addClass(b ? "ui-corner-left" : "ui-corner-right").end().end()
        },
        _destroy: function () {
            this.element.removeClass("ui-buttonset"), this.buttons.map(function () {
                return a(this).button("widget")[0]
            }).removeClass("ui-corner-left ui-corner-right").end().button("destroy")
        }
    })
}(jQuery), function (a, b) {
    function e() {
        this._curInst = null, this._keyEvent = !1, this._disabledInputs = [], this._datepickerShowing = !1, this._inDialog = !1, this._mainDivId = "ui-datepicker-div", this._inlineClass = "ui-datepicker-inline", this._appendClass = "ui-datepicker-append", this._triggerClass = "ui-datepicker-trigger", this._dialogClass = "ui-datepicker-dialog", this._disableClass = "ui-datepicker-disabled", this._unselectableClass = "ui-datepicker-unselectable", this._currentClass = "ui-datepicker-current-day", this._dayOverClass = "ui-datepicker-days-cell-over", this.regional = [], this.regional[""] = {
            closeText: "Done",
            prevText: "Prev",
            nextText: "Next",
            currentText: "Today",
            monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            weekHeader: "Wk",
            dateFormat: "mm/dd/yy",
            firstDay: 0,
            isRTL: !1,
            showMonthAfterYear: !1,
            yearSuffix: ""
        }, this._defaults = {
            showOn: "focus",
            showAnim: "fadeIn",
            showOptions: {},
            defaultDate: null,
            appendText: "",
            buttonText: "...",
            buttonImage: "",
            buttonImageOnly: !1,
            hideIfNoPrevNext: !1,
            navigationAsDateFormat: !1,
            gotoCurrent: !1,
            changeMonth: !1,
            changeYear: !1,
            yearRange: "c-10:c+10",
            showOtherMonths: !1,
            selectOtherMonths: !1,
            showWeek: !1,
            calculateWeek: this.iso8601Week,
            shortYearCutoff: "+10",
            minDate: null,
            maxDate: null,
            duration: "fast",
            beforeShowDay: null,
            beforeShow: null,
            onSelect: null,
            onChangeMonthYear: null,
            onClose: null,
            numberOfMonths: 1,
            showCurrentAtPos: 0,
            stepMonths: 1,
            stepBigMonths: 12,
            altField: "",
            altFormat: "",
            constrainInput: !0,
            showButtonPanel: !1,
            autoSize: !1,
            disabled: !1
        }, a.extend(this._defaults, this.regional[""]), this.dpDiv = f(a("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))
    }

    function f(b) {
        var c = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
        return b.delegate(c, "mouseout", function () {
            a(this).removeClass("ui-state-hover"), this.className.indexOf("ui-datepicker-prev") !== -1 && a(this).removeClass("ui-datepicker-prev-hover"), this.className.indexOf("ui-datepicker-next") !== -1 && a(this).removeClass("ui-datepicker-next-hover")
        }).delegate(c, "mouseover", function () {
            a.datepicker._isDisabledDatepicker(d.inline ? b.parent()[0] : d.input[0]) || (a(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"), a(this).addClass("ui-state-hover"), this.className.indexOf("ui-datepicker-prev") !== -1 && a(this).addClass("ui-datepicker-prev-hover"), this.className.indexOf("ui-datepicker-next") !== -1 && a(this).addClass("ui-datepicker-next-hover"))
        })
    }

    function g(b, c) {
        a.extend(b, c);
        for (var d in c) null == c[d] && (b[d] = c[d]);
        return b
    }

    a.extend(a.ui, {datepicker: {version: "1.10.3"}});
    var d, c = "datepicker";
    a.extend(e.prototype, {
        markerClassName: "hasDatepicker",
        maxRows: 4,
        _widgetDatepicker: function () {
            return this.dpDiv
        },
        setDefaults: function (a) {
            return g(this._defaults, a || {}), this
        },
        _attachDatepicker: function (b, c) {
            var d, e, f;
            d = b.nodeName.toLowerCase(), e = "div" === d || "span" === d, b.id || (this.uuid += 1, b.id = "dp" + this.uuid), f = this._newInst(a(b), e), f.settings = a.extend({}, c || {}), "input" === d ? this._connectDatepicker(b, f) : e && this._inlineDatepicker(b, f)
        },
        _newInst: function (b, c) {
            var d = b[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1");
            return {
                id: d,
                input: b,
                selectedDay: 0,
                selectedMonth: 0,
                selectedYear: 0,
                drawMonth: 0,
                drawYear: 0,
                inline: c,
                dpDiv: c ? f(a("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>")) : this.dpDiv
            }
        },
        _connectDatepicker: function (b, d) {
            var e = a(b);
            d.append = a([]), d.trigger = a([]), e.hasClass(this.markerClassName) || (this._attachments(e, d), e.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp), this._autoSize(d), a.data(b, c, d), d.settings.disabled && this._disableDatepicker(b))
        },
        _attachments: function (b, c) {
            var d, e, f, g = this._get(c, "appendText"), h = this._get(c, "isRTL");
            c.append && c.append.remove(), g && (c.append = a("<span class='" + this._appendClass + "'>" + g + "</span>"), b[h ? "before" : "after"](c.append)), b.unbind("focus", this._showDatepicker), c.trigger && c.trigger.remove(), d = this._get(c, "showOn"), "focus" !== d && "both" !== d || b.focus(this._showDatepicker), "button" !== d && "both" !== d || (e = this._get(c, "buttonText"), f = this._get(c, "buttonImage"), c.trigger = a(this._get(c, "buttonImageOnly") ? a("<img/>").addClass(this._triggerClass).attr({
                src: f,
                alt: e,
                title: e
            }) : a("<button type='button'></button>").addClass(this._triggerClass).html(f ? a("<img/>").attr({
                src: f,
                alt: e,
                title: e
            }) : e)), b[h ? "before" : "after"](c.trigger), c.trigger.click(function () {
                return a.datepicker._datepickerShowing && a.datepicker._lastInput === b[0] ? a.datepicker._hideDatepicker() : a.datepicker._datepickerShowing && a.datepicker._lastInput !== b[0] ? (a.datepicker._hideDatepicker(), a.datepicker._showDatepicker(b[0])) : a.datepicker._showDatepicker(b[0]), !1
            }))
        },
        _autoSize: function (a) {
            if (this._get(a, "autoSize") && !a.inline) {
                var b, c, d, e, f = new Date(2009, 11, 20), g = this._get(a, "dateFormat");
                g.match(/[DM]/) && (b = function (a) {
                    for (c = 0, d = 0, e = 0; e < a.length; e++) a[e].length > c && (c = a[e].length, d = e);
                    return d
                }, f.setMonth(b(this._get(a, g.match(/MM/) ? "monthNames" : "monthNamesShort"))), f.setDate(b(this._get(a, g.match(/DD/) ? "dayNames" : "dayNamesShort")) + 20 - f.getDay())), a.input.attr("size", this._formatDate(a, f).length)
            }
        },
        _inlineDatepicker: function (b, d) {
            var e = a(b);
            e.hasClass(this.markerClassName) || (e.addClass(this.markerClassName).append(d.dpDiv), a.data(b, c, d), this._setDate(d, this._getDefaultDate(d), !0), this._updateDatepicker(d), this._updateAlternate(d), d.settings.disabled && this._disableDatepicker(b), d.dpDiv.css("display", "block"))
        },
        _dialogDatepicker: function (b, d, e, f, h) {
            var i, j, k, l, m, n = this._dialogInst;
            return n || (this.uuid += 1, i = "dp" + this.uuid, this._dialogInput = a("<input type='text' id='" + i + "' style='position: absolute; top: -100px; width: 0px;'/>"), this._dialogInput.keydown(this._doKeyDown), a("body").append(this._dialogInput), n = this._dialogInst = this._newInst(this._dialogInput, !1), n.settings = {}, a.data(this._dialogInput[0], c, n)), g(n.settings, f || {}), d = d && d.constructor === Date ? this._formatDate(n, d) : d, this._dialogInput.val(d), this._pos = h ? h.length ? h : [h.pageX, h.pageY] : null, this._pos || (j = document.documentElement.clientWidth, k = document.documentElement.clientHeight, l = document.documentElement.scrollLeft || document.body.scrollLeft, m = document.documentElement.scrollTop || document.body.scrollTop, this._pos = [j / 2 - 100 + l, k / 2 - 150 + m]), this._dialogInput.css("left", this._pos[0] + 20 + "px").css("top", this._pos[1] + "px"), n.settings.onSelect = e, this._inDialog = !0, this.dpDiv.addClass(this._dialogClass), this._showDatepicker(this._dialogInput[0]), a.blockUI && a.blockUI(this.dpDiv), a.data(this._dialogInput[0], c, n), this
        },
        _destroyDatepicker: function (b) {
            var d, e = a(b), f = a.data(b, c);
            e.hasClass(this.markerClassName) && (d = b.nodeName.toLowerCase(), a.removeData(b, c), "input" === d ? (f.append.remove(), f.trigger.remove(), e.removeClass(this.markerClassName).unbind("focus", this._showDatepicker).unbind("keydown", this._doKeyDown).unbind("keypress", this._doKeyPress).unbind("keyup", this._doKeyUp)) : "div" !== d && "span" !== d || e.removeClass(this.markerClassName).empty())
        },
        _enableDatepicker: function (b) {
            var d, e, f = a(b), g = a.data(b, c);
            f.hasClass(this.markerClassName) && (d = b.nodeName.toLowerCase(), "input" === d ? (b.disabled = !1, g.trigger.filter("button").each(function () {
                this.disabled = !1
            }).end().filter("img").css({
                opacity: "1.0",
                cursor: ""
            })) : "div" !== d && "span" !== d || (e = f.children("." + this._inlineClass), e.children().removeClass("ui-state-disabled"), e.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !1)), this._disabledInputs = a.map(this._disabledInputs, function (a) {
                return a === b ? null : a
            }))
        },
        _disableDatepicker: function (b) {
            var d, e, f = a(b), g = a.data(b, c);
            f.hasClass(this.markerClassName) && (d = b.nodeName.toLowerCase(), "input" === d ? (b.disabled = !0, g.trigger.filter("button").each(function () {
                this.disabled = !0
            }).end().filter("img").css({
                opacity: "0.5",
                cursor: "default"
            })) : "div" !== d && "span" !== d || (e = f.children("." + this._inlineClass), e.children().addClass("ui-state-disabled"), e.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !0)), this._disabledInputs = a.map(this._disabledInputs, function (a) {
                return a === b ? null : a
            }), this._disabledInputs[this._disabledInputs.length] = b)
        },
        _isDisabledDatepicker: function (a) {
            if (!a) return !1;
            for (var b = 0; b < this._disabledInputs.length; b++) if (this._disabledInputs[b] === a) return !0;
            return !1
        },
        _getInst: function (b) {
            try {
                return a.data(b, c)
            } catch (a) {
                throw"Missing instance data for this datepicker"
            }
        },
        _optionDatepicker: function (c, d, e) {
            var f, h, i, j, k = this._getInst(c);
            return 2 === arguments.length && "string" == typeof d ? "defaults" === d ? a.extend({}, a.datepicker._defaults) : k ? "all" === d ? a.extend({}, k.settings) : this._get(k, d) : null : (f = d || {}, "string" == typeof d && (f = {}, f[d] = e), void(k && (this._curInst === k && this._hideDatepicker(), h = this._getDateDatepicker(c, !0), i = this._getMinMaxDate(k, "min"), j = this._getMinMaxDate(k, "max"), g(k.settings, f), null !== i && f.dateFormat !== b && f.minDate === b && (k.settings.minDate = this._formatDate(k, i)), null !== j && f.dateFormat !== b && f.maxDate === b && (k.settings.maxDate = this._formatDate(k, j)), "disabled" in f && (f.disabled ? this._disableDatepicker(c) : this._enableDatepicker(c)), this._attachments(a(c), k), this._autoSize(k), this._setDate(k, h), this._updateAlternate(k), this._updateDatepicker(k))))
        },
        _changeDatepicker: function (a, b, c) {
            this._optionDatepicker(a, b, c)
        },
        _refreshDatepicker: function (a) {
            var b = this._getInst(a);
            b && this._updateDatepicker(b)
        },
        _setDateDatepicker: function (a, b) {
            var c = this._getInst(a);
            c && (this._setDate(c, b), this._updateDatepicker(c), this._updateAlternate(c))
        },
        _getDateDatepicker: function (a, b) {
            var c = this._getInst(a);
            return c && !c.inline && this._setDateFromField(c, b), c ? this._getDate(c) : null
        },
        _doKeyDown: function (b) {
            var c, d, e, f = a.datepicker._getInst(b.target), g = !0, h = f.dpDiv.is(".ui-datepicker-rtl");
            if (f._keyEvent = !0, a.datepicker._datepickerShowing) switch (b.keyCode) {
                case 9:
                    a.datepicker._hideDatepicker(), g = !1;
                    break;
                case 13:
                    return e = a("td." + a.datepicker._dayOverClass + ":not(." + a.datepicker._currentClass + ")", f.dpDiv), e[0] && a.datepicker._selectDay(b.target, f.selectedMonth, f.selectedYear, e[0]), c = a.datepicker._get(f, "onSelect"), c ? (d = a.datepicker._formatDate(f), c.apply(f.input ? f.input[0] : null, [d, f])) : a.datepicker._hideDatepicker(), !1;
                case 27:
                    a.datepicker._hideDatepicker();
                    break;
                case 33:
                    a.datepicker._adjustDate(b.target, b.ctrlKey ? -a.datepicker._get(f, "stepBigMonths") : -a.datepicker._get(f, "stepMonths"), "M");
                    break;
                case 34:
                    a.datepicker._adjustDate(b.target, b.ctrlKey ? +a.datepicker._get(f, "stepBigMonths") : +a.datepicker._get(f, "stepMonths"), "M");
                    break;
                case 35:
                    (b.ctrlKey || b.metaKey) && a.datepicker._clearDate(b.target), g = b.ctrlKey || b.metaKey;
                    break;
                case 36:
                    (b.ctrlKey || b.metaKey) && a.datepicker._gotoToday(b.target), g = b.ctrlKey || b.metaKey;
                    break;
                case 37:
                    (b.ctrlKey || b.metaKey) && a.datepicker._adjustDate(b.target, h ? 1 : -1, "D"), g = b.ctrlKey || b.metaKey, b.originalEvent.altKey && a.datepicker._adjustDate(b.target, b.ctrlKey ? -a.datepicker._get(f, "stepBigMonths") : -a.datepicker._get(f, "stepMonths"), "M");
                    break;
                case 38:
                    (b.ctrlKey || b.metaKey) && a.datepicker._adjustDate(b.target, -7, "D"), g = b.ctrlKey || b.metaKey;
                    break;
                case 39:
                    (b.ctrlKey || b.metaKey) && a.datepicker._adjustDate(b.target, h ? -1 : 1, "D"), g = b.ctrlKey || b.metaKey, b.originalEvent.altKey && a.datepicker._adjustDate(b.target, b.ctrlKey ? +a.datepicker._get(f, "stepBigMonths") : +a.datepicker._get(f, "stepMonths"), "M");
                    break;
                case 40:
                    (b.ctrlKey || b.metaKey) && a.datepicker._adjustDate(b.target, 7, "D"), g = b.ctrlKey || b.metaKey;
                    break;
                default:
                    g = !1
            } else 36 === b.keyCode && b.ctrlKey ? a.datepicker._showDatepicker(this) : g = !1;
            g && (b.preventDefault(), b.stopPropagation())
        },
        _doKeyPress: function (b) {
            var c, d, e = a.datepicker._getInst(b.target);
            if (a.datepicker._get(e, "constrainInput")) return c = a.datepicker._possibleChars(a.datepicker._get(e, "dateFormat")), d = String.fromCharCode(null == b.charCode ? b.keyCode : b.charCode), b.ctrlKey || b.metaKey || d < " " || !c || c.indexOf(d) > -1
        },
        _doKeyUp: function (b) {
            var c, d = a.datepicker._getInst(b.target);
            if (d.input.val() !== d.lastVal) try {
                c = a.datepicker.parseDate(a.datepicker._get(d, "dateFormat"), d.input ? d.input.val() : null, a.datepicker._getFormatConfig(d)), c && (a.datepicker._setDateFromField(d), a.datepicker._updateAlternate(d), a.datepicker._updateDatepicker(d))
            } catch (a) {
            }
            return !0
        },
        _showDatepicker: function (b) {
            if (b = b.target || b, "input" !== b.nodeName.toLowerCase() && (b = a("input", b.parentNode)[0]), !a.datepicker._isDisabledDatepicker(b) && a.datepicker._lastInput !== b) {
                var c, d, e, f, h, i, j;
                c = a.datepicker._getInst(b), a.datepicker._curInst && a.datepicker._curInst !== c && (a.datepicker._curInst.dpDiv.stop(!0, !0), c && a.datepicker._datepickerShowing && a.datepicker._hideDatepicker(a.datepicker._curInst.input[0])), d = a.datepicker._get(c, "beforeShow"), e = d ? d.apply(b, [b, c]) : {}, e !== !1 && (g(c.settings, e), c.lastVal = null, a.datepicker._lastInput = b, a.datepicker._setDateFromField(c), a.datepicker._inDialog && (b.value = ""), a.datepicker._pos || (a.datepicker._pos = a.datepicker._findPos(b), a.datepicker._pos[1] += b.offsetHeight), f = !1, a(b).parents().each(function () {
                    return f |= "fixed" === a(this).css("position"), !f
                }), h = {
                    left: a.datepicker._pos[0],
                    top: a.datepicker._pos[1]
                }, a.datepicker._pos = null, c.dpDiv.empty(), c.dpDiv.css({
                    position: "absolute",
                    display: "block",
                    top: "-1000px"
                }), a.datepicker._updateDatepicker(c), h = a.datepicker._checkOffset(c, h, f), c.dpDiv.css({
                    position: a.datepicker._inDialog && a.blockUI ? "static" : f ? "fixed" : "absolute",
                    display: "none",
                    left: h.left + "px",
                    top: h.top + "px"
                }), c.inline || (i = a.datepicker._get(c, "showAnim"), j = a.datepicker._get(c, "duration"), c.dpDiv.zIndex(a(b).zIndex() + 1), a.datepicker._datepickerShowing = !0, a.effects && a.effects.effect[i] ? c.dpDiv.show(i, a.datepicker._get(c, "showOptions"), j) : c.dpDiv[i || "show"](i ? j : null), a.datepicker._shouldFocusInput(c) && c.input.focus(), a.datepicker._curInst = c))
            }
        },
        _updateDatepicker: function (b) {
            this.maxRows = 4, d = b, b.dpDiv.empty().append(this._generateHTML(b)), this._attachHandlers(b), b.dpDiv.find("." + this._dayOverClass + " a").mouseover();
            var c, e = this._getNumberOfMonths(b), f = e[1], g = 17;
            b.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""), f > 1 && b.dpDiv.addClass("ui-datepicker-multi-" + f).css("width", g * f + "em"), b.dpDiv[(1 !== e[0] || 1 !== e[1] ? "add" : "remove") + "Class"]("ui-datepicker-multi"), b.dpDiv[(this._get(b, "isRTL") ? "add" : "remove") + "Class"]("ui-datepicker-rtl"), b === a.datepicker._curInst && a.datepicker._datepickerShowing && a.datepicker._shouldFocusInput(b) && b.input.focus(), b.yearshtml && (c = b.yearshtml, setTimeout(function () {
                c === b.yearshtml && b.yearshtml && b.dpDiv.find("select.ui-datepicker-year:first").replaceWith(b.yearshtml), c = b.yearshtml = null
            }, 0))
        },
        _shouldFocusInput: function (a) {
            return a.input && a.input.is(":visible") && !a.input.is(":disabled") && !a.input.is(":focus")
        },
        _checkOffset: function (b, c, d) {
            var e = b.dpDiv.outerWidth(), f = b.dpDiv.outerHeight(), g = b.input ? b.input.outerWidth() : 0,
                h = b.input ? b.input.outerHeight() : 0,
                i = document.documentElement.clientWidth + (d ? 0 : a(document).scrollLeft()),
                j = document.documentElement.clientHeight + (d ? 0 : a(document).scrollTop());
            return c.left -= this._get(b, "isRTL") ? e - g : 0, c.left -= d && c.left === b.input.offset().left ? a(document).scrollLeft() : 0, c.top -= d && c.top === b.input.offset().top + h ? a(document).scrollTop() : 0, c.left -= Math.min(c.left, c.left + e > i && i > e ? Math.abs(c.left + e - i) : 0), c.top -= Math.min(c.top, c.top + f > j && j > f ? Math.abs(f + h) : 0), c
        },
        _findPos: function (b) {
            for (var c, d = this._getInst(b), e = this._get(d, "isRTL"); b && ("hidden" === b.type || 1 !== b.nodeType || a.expr.filters.hidden(b));) b = b[e ? "previousSibling" : "nextSibling"];
            return c = a(b).offset(), [c.left, c.top]
        },
        _hideDatepicker: function (b) {
            var d, e, f, g, h = this._curInst;
            !h || b && h !== a.data(b, c) || this._datepickerShowing && (d = this._get(h, "showAnim"), e = this._get(h, "duration"), f = function () {
                a.datepicker._tidyDialog(h)
            }, a.effects && (a.effects.effect[d] || a.effects[d]) ? h.dpDiv.hide(d, a.datepicker._get(h, "showOptions"), e, f) : h.dpDiv["slideDown" === d ? "slideUp" : "fadeIn" === d ? "fadeOut" : "hide"](d ? e : null, f), d || f(), this._datepickerShowing = !1, g = this._get(h, "onClose"), g && g.apply(h.input ? h.input[0] : null, [h.input ? h.input.val() : "", h]), this._lastInput = null, this._inDialog && (this._dialogInput.css({
                position: "absolute",
                left: "0",
                top: "-100px"
            }), a.blockUI && (a.unblockUI(), a("body").append(this.dpDiv))), this._inDialog = !1)
        },
        _tidyDialog: function (a) {
            a.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")
        },
        _checkExternalClick: function (b) {
            if (a.datepicker._curInst) {
                var c = a(b.target), d = a.datepicker._getInst(c[0]);
                (c[0].id === a.datepicker._mainDivId || 0 !== c.parents("#" + a.datepicker._mainDivId).length || c.hasClass(a.datepicker.markerClassName) || c.closest("." + a.datepicker._triggerClass).length || !a.datepicker._datepickerShowing || a.datepicker._inDialog && a.blockUI) && (!c.hasClass(a.datepicker.markerClassName) || a.datepicker._curInst === d) || a.datepicker._hideDatepicker()
            }
        },
        _adjustDate: function (b, c, d) {
            var e = a(b), f = this._getInst(e[0]);
            this._isDisabledDatepicker(e[0]) || (this._adjustInstDate(f, c + ("M" === d ? this._get(f, "showCurrentAtPos") : 0), d), this._updateDatepicker(f))
        },
        _gotoToday: function (b) {
            var c, d = a(b), e = this._getInst(d[0]);
            this._get(e, "gotoCurrent") && e.currentDay ? (e.selectedDay = e.currentDay, e.drawMonth = e.selectedMonth = e.currentMonth, e.drawYear = e.selectedYear = e.currentYear) : (c = new Date, e.selectedDay = c.getDate(), e.drawMonth = e.selectedMonth = c.getMonth(), e.drawYear = e.selectedYear = c.getFullYear()), this._notifyChange(e), this._adjustDate(d)
        },
        _selectMonthYear: function (b, c, d) {
            var e = a(b), f = this._getInst(e[0]);
            f["selected" + ("M" === d ? "Month" : "Year")] = f["draw" + ("M" === d ? "Month" : "Year")] = parseInt(c.options[c.selectedIndex].value, 10), this._notifyChange(f), this._adjustDate(e)
        },
        _selectDay: function (b, c, d, e) {
            var f, g = a(b);
            a(e).hasClass(this._unselectableClass) || this._isDisabledDatepicker(g[0]) || (f = this._getInst(g[0]), f.selectedDay = f.currentDay = a("a", e).html(), f.selectedMonth = f.currentMonth = c, f.selectedYear = f.currentYear = d, this._selectDate(b, this._formatDate(f, f.currentDay, f.currentMonth, f.currentYear)))
        },
        _clearDate: function (b) {
            var c = a(b);
            this._selectDate(c, "")
        },
        _selectDate: function (b, c) {
            var d, e = a(b), f = this._getInst(e[0]);
            c = null != c ? c : this._formatDate(f), f.input && f.input.val(c), this._updateAlternate(f), d = this._get(f, "onSelect"), d ? d.apply(f.input ? f.input[0] : null, [c, f]) : f.input && f.input.trigger("change"), f.inline ? this._updateDatepicker(f) : (this._hideDatepicker(), this._lastInput = f.input[0], "object" != typeof f.input[0] && f.input.focus(), this._lastInput = null)
        },
        _updateAlternate: function (b) {
            var c, d, e, f = this._get(b, "altField");
            f && (c = this._get(b, "altFormat") || this._get(b, "dateFormat"), d = this._getDate(b), e = this.formatDate(c, d, this._getFormatConfig(b)), a(f).each(function () {
                a(this).val(e)
            }))
        },
        noWeekends: function (a) {
            var b = a.getDay();
            return [b > 0 && b < 6, ""]
        },
        iso8601Week: function (a) {
            var b, c = new Date(a.getTime());
            return c.setDate(c.getDate() + 4 - (c.getDay() || 7)), b = c.getTime(), c.setMonth(0), c.setDate(1), Math.floor(Math.round((b - c) / 864e5) / 7) + 1
        },
        parseDate: function (b, c, d) {
            if (null == b || null == c) throw"Invalid arguments";
            if (c = "object" == typeof c ? c.toString() : c + "", "" === c) return null;
            var e, f, g, t, h = 0, i = (d ? d.shortYearCutoff : null) || this._defaults.shortYearCutoff,
                j = "string" != typeof i ? i : (new Date).getFullYear() % 100 + parseInt(i, 10),
                k = (d ? d.dayNamesShort : null) || this._defaults.dayNamesShort,
                l = (d ? d.dayNames : null) || this._defaults.dayNames,
                m = (d ? d.monthNamesShort : null) || this._defaults.monthNamesShort,
                n = (d ? d.monthNames : null) || this._defaults.monthNames, o = -1, p = -1, q = -1, r = -1, s = !1,
                u = function (a) {
                    var c = e + 1 < b.length && b.charAt(e + 1) === a;
                    return c && e++, c
                }, v = function (a) {
                    var b = u(a), d = "@" === a ? 14 : "!" === a ? 20 : "y" === a && b ? 4 : "o" === a ? 3 : 2,
                        e = new RegExp("^\\d{1," + d + "}"), f = c.substring(h).match(e);
                    if (!f) throw"Missing number at position " + h;
                    return h += f[0].length, parseInt(f[0], 10)
                }, w = function (b, d, e) {
                    var f = -1, g = a.map(u(b) ? e : d, function (a, b) {
                        return [[b, a]]
                    }).sort(function (a, b) {
                        return -(a[1].length - b[1].length)
                    });
                    if (a.each(g, function (a, b) {
                            var d = b[1];
                            if (c.substr(h, d.length).toLowerCase() === d.toLowerCase()) return f = b[0], h += d.length, !1
                        }), f !== -1) return f + 1;
                    throw"Unknown name at position " + h
                }, x = function () {
                    if (c.charAt(h) !== b.charAt(e)) throw"Unexpected literal at position " + h;
                    h++
                };
            for (e = 0; e < b.length; e++) if (s) "'" !== b.charAt(e) || u("'") ? x() : s = !1; else switch (b.charAt(e)) {
                case"d":
                    q = v("d");
                    break;
                case"D":
                    w("D", k, l);
                    break;
                case"o":
                    r = v("o");
                    break;
                case"m":
                    p = v("m");
                    break;
                case"M":
                    p = w("M", m, n);
                    break;
                case"y":
                    o = v("y");
                    break;
                case"@":
                    t = new Date(v("@")), o = t.getFullYear(), p = t.getMonth() + 1, q = t.getDate();
                    break;
                case"!":
                    t = new Date((v("!") - this._ticksTo1970) / 1e4), o = t.getFullYear(), p = t.getMonth() + 1, q = t.getDate();
                    break;
                case"'":
                    u("'") ? x() : s = !0;
                    break;
                default:
                    x()
            }
            if (h < c.length && (g = c.substr(h), !/^\s+/.test(g))) throw"Extra/unparsed characters found in date: " + g;
            if (o === -1 ? o = (new Date).getFullYear() : o < 100 && (o += (new Date).getFullYear() - (new Date).getFullYear() % 100 + (o <= j ? 0 : -100)), r > -1) for (p = 1, q = r; ;) {
                if (f = this._getDaysInMonth(o, p - 1), q <= f) break;
                p++, q -= f
            }
            if (t = this._daylightSavingAdjust(new Date(o, p - 1, q)), t.getFullYear() !== o || t.getMonth() + 1 !== p || t.getDate() !== q) throw"Invalid date";
            return t
        },
        ATOM: "yy-mm-dd",
        COOKIE: "D, dd M yy",
        ISO_8601: "yy-mm-dd",
        RFC_822: "D, d M y",
        RFC_850: "DD, dd-M-y",
        RFC_1036: "D, d M y",
        RFC_1123: "D, d M yy",
        RFC_2822: "D, d M yy",
        RSS: "D, d M y",
        TICKS: "!",
        TIMESTAMP: "@",
        W3C: "yy-mm-dd",
        _ticksTo1970: 24 * (718685 + Math.floor(492.5) - Math.floor(19.7) + Math.floor(4.925)) * 60 * 60 * 1e7,
        formatDate: function (a, b, c) {
            if (!b) return "";
            var d, e = (c ? c.dayNamesShort : null) || this._defaults.dayNamesShort,
                f = (c ? c.dayNames : null) || this._defaults.dayNames,
                g = (c ? c.monthNamesShort : null) || this._defaults.monthNamesShort,
                h = (c ? c.monthNames : null) || this._defaults.monthNames, i = function (b) {
                    var c = d + 1 < a.length && a.charAt(d + 1) === b;
                    return c && d++, c
                }, j = function (a, b, c) {
                    var d = "" + b;
                    if (i(a)) for (; d.length < c;) d = "0" + d;
                    return d
                }, k = function (a, b, c, d) {
                    return i(a) ? d[b] : c[b]
                }, l = "", m = !1;
            if (b) for (d = 0; d < a.length; d++) if (m) "'" !== a.charAt(d) || i("'") ? l += a.charAt(d) : m = !1; else switch (a.charAt(d)) {
                case"d":
                    l += j("d", b.getDate(), 2);
                    break;
                case"D":
                    l += k("D", b.getDay(), e, f);
                    break;
                case"o":
                    l += j("o", Math.round((new Date(b.getFullYear(), b.getMonth(), b.getDate()).getTime() - new Date(b.getFullYear(), 0, 0).getTime()) / 864e5), 3);
                    break;
                case"m":
                    l += j("m", b.getMonth() + 1, 2);
                    break;
                case"M":
                    l += k("M", b.getMonth(), g, h);
                    break;
                case"y":
                    l += i("y") ? b.getFullYear() : (b.getYear() % 100 < 10 ? "0" : "") + b.getYear() % 100;
                    break;
                case"@":
                    l += b.getTime();
                    break;
                case"!":
                    l += 1e4 * b.getTime() + this._ticksTo1970;
                    break;
                case"'":
                    i("'") ? l += "'" : m = !0;
                    break;
                default:
                    l += a.charAt(d)
            }
            return l
        },
        _possibleChars: function (a) {
            var b, c = "", d = !1, e = function (c) {
                var d = b + 1 < a.length && a.charAt(b + 1) === c;
                return d && b++, d
            };
            for (b = 0; b < a.length; b++) if (d) "'" !== a.charAt(b) || e("'") ? c += a.charAt(b) : d = !1; else switch (a.charAt(b)) {
                case"d":
                case"m":
                case"y":
                case"@":
                    c += "0123456789";
                    break;
                case"D":
                case"M":
                    return null;
                case"'":
                    e("'") ? c += "'" : d = !0;
                    break;
                default:
                    c += a.charAt(b)
            }
            return c
        },
        _get: function (a, c) {
            return a.settings[c] !== b ? a.settings[c] : this._defaults[c]
        },
        _setDateFromField: function (a, b) {
            if (a.input.val() !== a.lastVal) {
                var c = this._get(a, "dateFormat"), d = a.lastVal = a.input ? a.input.val() : null,
                    e = this._getDefaultDate(a), f = e, g = this._getFormatConfig(a);
                try {
                    f = this.parseDate(c, d, g) || e
                } catch (a) {
                    d = b ? "" : d
                }
                a.selectedDay = f.getDate(), a.drawMonth = a.selectedMonth = f.getMonth(), a.drawYear = a.selectedYear = f.getFullYear(), a.currentDay = d ? f.getDate() : 0, a.currentMonth = d ? f.getMonth() : 0, a.currentYear = d ? f.getFullYear() : 0, this._adjustInstDate(a)
            }
        },
        _getDefaultDate: function (a) {
            return this._restrictMinMax(a, this._determineDate(a, this._get(a, "defaultDate"), new Date))
        },
        _determineDate: function (b, c, d) {
            var e = function (a) {
                    var b = new Date;
                    return b.setDate(b.getDate() + a), b
                }, f = function (c) {
                    try {
                        return a.datepicker.parseDate(a.datepicker._get(b, "dateFormat"), c, a.datepicker._getFormatConfig(b))
                    } catch (a) {
                    }
                    for (var d = (c.toLowerCase().match(/^c/) ? a.datepicker._getDate(b) : null) || new Date, e = d.getFullYear(), f = d.getMonth(), g = d.getDate(), h = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g, i = h.exec(c); i;) {
                        switch (i[2] || "d") {
                            case"d":
                            case"D":
                                g += parseInt(i[1], 10);
                                break;
                            case"w":
                            case"W":
                                g += 7 * parseInt(i[1], 10);
                                break;
                            case"m":
                            case"M":
                                f += parseInt(i[1], 10), g = Math.min(g, a.datepicker._getDaysInMonth(e, f));
                                break;
                            case"y":
                            case"Y":
                                e += parseInt(i[1], 10), g = Math.min(g, a.datepicker._getDaysInMonth(e, f))
                        }
                        i = h.exec(c)
                    }
                    return new Date(e, f, g)
                },
                g = null == c || "" === c ? d : "string" == typeof c ? f(c) : "number" == typeof c ? isNaN(c) ? d : e(c) : new Date(c.getTime());
            return g = g && "Invalid Date" === g.toString() ? d : g, g && (g.setHours(0), g.setMinutes(0), g.setSeconds(0), g.setMilliseconds(0)), this._daylightSavingAdjust(g)
        },
        _daylightSavingAdjust: function (a) {
            return a ? (a.setHours(a.getHours() > 12 ? a.getHours() + 2 : 0), a) : null
        },
        _setDate: function (a, b, c) {
            var d = !b, e = a.selectedMonth, f = a.selectedYear,
                g = this._restrictMinMax(a, this._determineDate(a, b, new Date));
            a.selectedDay = a.currentDay = g.getDate(), a.drawMonth = a.selectedMonth = a.currentMonth = g.getMonth(), a.drawYear = a.selectedYear = a.currentYear = g.getFullYear(), e === a.selectedMonth && f === a.selectedYear || c || this._notifyChange(a), this._adjustInstDate(a), a.input && a.input.val(d ? "" : this._formatDate(a))
        },
        _getDate: function (a) {
            var b = !a.currentYear || a.input && "" === a.input.val() ? null : this._daylightSavingAdjust(new Date(a.currentYear, a.currentMonth, a.currentDay));
            return b
        },
        _attachHandlers: function (b) {
            var c = this._get(b, "stepMonths"), d = "#" + b.id.replace(/\\\\/g, "\\");
            b.dpDiv.find("[data-handler]").map(function () {
                var b = {
                    prev: function () {
                        a.datepicker._adjustDate(d, -c, "M")
                    }, next: function () {
                        a.datepicker._adjustDate(d, +c, "M")
                    }, hide: function () {
                        a.datepicker._hideDatepicker()
                    }, today: function () {
                        a.datepicker._gotoToday(d)
                    }, selectDay: function () {
                        return a.datepicker._selectDay(d, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this), !1
                    }, selectMonth: function () {
                        return a.datepicker._selectMonthYear(d, this, "M"), !1
                    }, selectYear: function () {
                        return a.datepicker._selectMonthYear(d, this, "Y"), !1
                    }
                };
                a(this).bind(this.getAttribute("data-event"), b[this.getAttribute("data-handler")])
            })
        },
        _generateHTML: function (a) {
            var b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J,
                K, L, M, N, O = new Date,
                P = this._daylightSavingAdjust(new Date(O.getFullYear(), O.getMonth(), O.getDate())),
                Q = this._get(a, "isRTL"), R = this._get(a, "showButtonPanel"), S = this._get(a, "hideIfNoPrevNext"),
                T = this._get(a, "navigationAsDateFormat"), U = this._getNumberOfMonths(a),
                V = this._get(a, "showCurrentAtPos"), W = this._get(a, "stepMonths"), X = 1 !== U[0] || 1 !== U[1],
                Y = this._daylightSavingAdjust(a.currentDay ? new Date(a.currentYear, a.currentMonth, a.currentDay) : new Date(9999, 9, 9)),
                Z = this._getMinMaxDate(a, "min"), $ = this._getMinMaxDate(a, "max"), _ = a.drawMonth - V,
                aa = a.drawYear;
            if (_ < 0 && (_ += 12, aa--), $) for (b = this._daylightSavingAdjust(new Date($.getFullYear(), $.getMonth() - U[0] * U[1] + 1, $.getDate())), b = Z && b < Z ? Z : b; this._daylightSavingAdjust(new Date(aa, _, 1)) > b;) _--, _ < 0 && (_ = 11, aa--);
            for (a.drawMonth = _, a.drawYear = aa, c = this._get(a, "prevText"), c = T ? this.formatDate(c, this._daylightSavingAdjust(new Date(aa, _ - W, 1)), this._getFormatConfig(a)) : c, d = this._canAdjustMonth(a, -1, aa, _) ? "<a class='ui-datepicker-prev ui-corner-all' data-handler='prev' data-event='click' title='" + c + "'><span class='ui-icon ui-icon-circle-triangle-" + (Q ? "e" : "w") + "'>" + c + "</span></a>" : S ? "" : "<a class='ui-datepicker-prev ui-corner-all ui-state-disabled' title='" + c + "'><span class='ui-icon ui-icon-circle-triangle-" + (Q ? "e" : "w") + "'>" + c + "</span></a>", e = this._get(a, "nextText"), e = T ? this.formatDate(e, this._daylightSavingAdjust(new Date(aa, _ + W, 1)), this._getFormatConfig(a)) : e, f = this._canAdjustMonth(a, 1, aa, _) ? "<a class='ui-datepicker-next ui-corner-all' data-handler='next' data-event='click' title='" + e + "'><span class='ui-icon ui-icon-circle-triangle-" + (Q ? "w" : "e") + "'>" + e + "</span></a>" : S ? "" : "<a class='ui-datepicker-next ui-corner-all ui-state-disabled' title='" + e + "'><span class='ui-icon ui-icon-circle-triangle-" + (Q ? "w" : "e") + "'>" + e + "</span></a>", g = this._get(a, "currentText"), h = this._get(a, "gotoCurrent") && a.currentDay ? Y : P, g = T ? this.formatDate(g, h, this._getFormatConfig(a)) : g, i = a.inline ? "" : "<button type='button' class='ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all' data-handler='hide' data-event='click'>" + this._get(a, "closeText") + "</button>", j = R ? "<div class='ui-datepicker-buttonpane ui-widget-content'>" + (Q ? i : "") + (this._isInRange(a, h) ? "<button type='button' class='ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all' data-handler='today' data-event='click'>" + g + "</button>" : "") + (Q ? "" : i) + "</div>" : "", k = parseInt(this._get(a, "firstDay"), 10), k = isNaN(k) ? 0 : k, l = this._get(a, "showWeek"), m = this._get(a, "dayNames"), n = this._get(a, "dayNamesMin"), o = this._get(a, "monthNames"), p = this._get(a, "monthNamesShort"), q = this._get(a, "beforeShowDay"), r = this._get(a, "showOtherMonths"), s = this._get(a, "selectOtherMonths"), t = this._getDefaultDate(a), u = "", w = 0; w < U[0]; w++) {
                for (x = "", this.maxRows = 4, y = 0; y < U[1]; y++) {
                    if (z = this._daylightSavingAdjust(new Date(aa, _, a.selectedDay)), A = " ui-corner-all", B = "", X) {
                        if (B += "<div class='ui-datepicker-group", U[1] > 1) switch (y) {
                            case 0:
                                B += " ui-datepicker-group-first", A = " ui-corner-" + (Q ? "right" : "left");
                                break;
                            case U[1] - 1:
                                B += " ui-datepicker-group-last", A = " ui-corner-" + (Q ? "left" : "right");
                                break;
                            default:
                                B += " ui-datepicker-group-middle", A = ""
                        }
                        B += "'>"
                    }
                    for (B += "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" + A + "'>" + (/all|left/.test(A) && 0 === w ? Q ? f : d : "") + (/all|right/.test(A) && 0 === w ? Q ? d : f : "") + this._generateMonthYearHeader(a, _, aa, Z, $, w > 0 || y > 0, o, p) + "</div><table class='ui-datepicker-calendar'><thead><tr>", C = l ? "<th class='ui-datepicker-week-col'>" + this._get(a, "weekHeader") + "</th>" : "", v = 0; v < 7; v++) D = (v + k) % 7, C += "<th" + ((v + k + 6) % 7 >= 5 ? " class='ui-datepicker-week-end'" : "") + "><span title='" + m[D] + "'>" + n[D] + "</span></th>";
                    for (B += C + "</tr></thead><tbody>", E = this._getDaysInMonth(aa, _), aa === a.selectedYear && _ === a.selectedMonth && (a.selectedDay = Math.min(a.selectedDay, E)), F = (this._getFirstDayOfMonth(aa, _) - k + 7) % 7, G = Math.ceil((F + E) / 7), H = X && this.maxRows > G ? this.maxRows : G, this.maxRows = H, I = this._daylightSavingAdjust(new Date(aa, _, 1 - F)), J = 0; J < H; J++) {
                        for (B += "<tr>", K = l ? "<td class='ui-datepicker-week-col'>" + this._get(a, "calculateWeek")(I) + "</td>" : "", v = 0; v < 7; v++) L = q ? q.apply(a.input ? a.input[0] : null, [I]) : [!0, ""], M = I.getMonth() !== _, N = M && !s || !L[0] || Z && I < Z || $ && I > $, K += "<td class='" + ((v + k + 6) % 7 >= 5 ? " ui-datepicker-week-end" : "") + (M ? " ui-datepicker-other-month" : "") + (I.getTime() === z.getTime() && _ === a.selectedMonth && a._keyEvent || t.getTime() === I.getTime() && t.getTime() === z.getTime() ? " " + this._dayOverClass : "") + (N ? " " + this._unselectableClass + " ui-state-disabled" : "") + (M && !r ? "" : " " + L[1] + (I.getTime() === Y.getTime() ? " " + this._currentClass : "") + (I.getTime() === P.getTime() ? " ui-datepicker-today" : "")) + "'" + (M && !r || !L[2] ? "" : " title='" + L[2].replace(/'/g, "&#39;") + "'") + (N ? "" : " data-handler='selectDay' data-event='click' data-month='" + I.getMonth() + "' data-year='" + I.getFullYear() + "'") + ">" + (M && !r ? "&#xa0;" : N ? "<span class='ui-state-default'>" + I.getDate() + "</span>" : "<a class='ui-state-default" + (I.getTime() === P.getTime() ? " ui-state-highlight" : "") + (I.getTime() === Y.getTime() ? " ui-state-active" : "") + (M ? " ui-priority-secondary" : "") + "' href='#'>" + I.getDate() + "</a>") + "</td>", I.setDate(I.getDate() + 1), I = this._daylightSavingAdjust(I);
                        B += K + "</tr>"
                    }
                    _++, _ > 11 && (_ = 0, aa++), B += "</tbody></table>" + (X ? "</div>" + (U[0] > 0 && y === U[1] - 1 ? "<div class='ui-datepicker-row-break'></div>" : "") : ""), x += B
                }
                u += x
            }
            return u += j, a._keyEvent = !1, u
        },
        _generateMonthYearHeader: function (a, b, c, d, e, f, g, h) {
            var i, j, k, l, m, n, o, p, q = this._get(a, "changeMonth"), r = this._get(a, "changeYear"),
                s = this._get(a, "showMonthAfterYear"), t = "<div class='ui-datepicker-title'>", u = "";
            if (f || !q) u += "<span class='ui-datepicker-month'>" + g[b] + "</span>"; else {
                for (i = d && d.getFullYear() === c, j = e && e.getFullYear() === c, u += "<select class='ui-datepicker-month' data-handler='selectMonth' data-event='change'>", k = 0; k < 12; k++) (!i || k >= d.getMonth()) && (!j || k <= e.getMonth()) && (u += "<option value='" + k + "'" + (k === b ? " selected='selected'" : "") + ">" + h[k] + "</option>");
                u += "</select>"
            }
            if (s || (t += u + (!f && q && r ? "" : "&#xa0;")), !a.yearshtml) if (a.yearshtml = "", f || !r) t += "<span class='ui-datepicker-year'>" + c + "</span>"; else {
                for (l = this._get(a, "yearRange").split(":"), m = (new Date).getFullYear(), n = function (a) {
                    var b = a.match(/c[+\-].*/) ? c + parseInt(a.substring(1), 10) : a.match(/[+\-].*/) ? m + parseInt(a, 10) : parseInt(a, 10);
                    return isNaN(b) ? m : b
                }, o = n(l[0]), p = Math.max(o, n(l[1] || "")), o = d ? Math.max(o, d.getFullYear()) : o, p = e ? Math.min(p, e.getFullYear()) : p, a.yearshtml += "<select class='ui-datepicker-year' data-handler='selectYear' data-event='change'>"; o <= p; o++) a.yearshtml += "<option value='" + o + "'" + (o === c ? " selected='selected'" : "") + ">" + o + "</option>";
                a.yearshtml += "</select>", t += a.yearshtml, a.yearshtml = null
            }
            return t += this._get(a, "yearSuffix"), s && (t += (!f && q && r ? "" : "&#xa0;") + u), t += "</div>"
        },
        _adjustInstDate: function (a, b, c) {
            var d = a.drawYear + ("Y" === c ? b : 0), e = a.drawMonth + ("M" === c ? b : 0),
                f = Math.min(a.selectedDay, this._getDaysInMonth(d, e)) + ("D" === c ? b : 0),
                g = this._restrictMinMax(a, this._daylightSavingAdjust(new Date(d, e, f)));
            a.selectedDay = g.getDate(), a.drawMonth = a.selectedMonth = g.getMonth(), a.drawYear = a.selectedYear = g.getFullYear(), "M" !== c && "Y" !== c || this._notifyChange(a)
        },
        _restrictMinMax: function (a, b) {
            var c = this._getMinMaxDate(a, "min"), d = this._getMinMaxDate(a, "max"), e = c && b < c ? c : b;
            return d && e > d ? d : e
        },
        _notifyChange: function (a) {
            var b = this._get(a, "onChangeMonthYear");
            b && b.apply(a.input ? a.input[0] : null, [a.selectedYear, a.selectedMonth + 1, a])
        },
        _getNumberOfMonths: function (a) {
            var b = this._get(a, "numberOfMonths");
            return null == b ? [1, 1] : "number" == typeof b ? [1, b] : b
        },
        _getMinMaxDate: function (a, b) {
            return this._determineDate(a, this._get(a, b + "Date"), null)
        },
        _getDaysInMonth: function (a, b) {
            return 32 - this._daylightSavingAdjust(new Date(a, b, 32)).getDate()
        },
        _getFirstDayOfMonth: function (a, b) {
            return new Date(a, b, 1).getDay()
        },
        _canAdjustMonth: function (a, b, c, d) {
            var e = this._getNumberOfMonths(a),
                f = this._daylightSavingAdjust(new Date(c, d + (b < 0 ? b : e[0] * e[1]), 1));
            return b < 0 && f.setDate(this._getDaysInMonth(f.getFullYear(), f.getMonth())), this._isInRange(a, f)
        },
        _isInRange: function (a, b) {
            var c, d, e = this._getMinMaxDate(a, "min"), f = this._getMinMaxDate(a, "max"), g = null, h = null,
                i = this._get(a, "yearRange");
            return i && (c = i.split(":"), d = (new Date).getFullYear(), g = parseInt(c[0], 10), h = parseInt(c[1], 10), c[0].match(/[+\-].*/) && (g += d), c[1].match(/[+\-].*/) && (h += d)), (!e || b.getTime() >= e.getTime()) && (!f || b.getTime() <= f.getTime()) && (!g || b.getFullYear() >= g) && (!h || b.getFullYear() <= h)
        },
        _getFormatConfig: function (a) {
            var b = this._get(a, "shortYearCutoff");
            return b = "string" != typeof b ? b : (new Date).getFullYear() % 100 + parseInt(b, 10), {
                shortYearCutoff: b,
                dayNamesShort: this._get(a, "dayNamesShort"),
                dayNames: this._get(a, "dayNames"),
                monthNamesShort: this._get(a, "monthNamesShort"),
                monthNames: this._get(a, "monthNames")
            }
        },
        _formatDate: function (a, b, c, d) {
            b || (a.currentDay = a.selectedDay, a.currentMonth = a.selectedMonth, a.currentYear = a.selectedYear);
            var e = b ? "object" == typeof b ? b : this._daylightSavingAdjust(new Date(d, c, b)) : this._daylightSavingAdjust(new Date(a.currentYear, a.currentMonth, a.currentDay));
            return this.formatDate(this._get(a, "dateFormat"), e, this._getFormatConfig(a))
        }
    }), a.fn.datepicker = function (b) {
        if (!this.length) return this;
        a.datepicker.initialized || (a(document).mousedown(a.datepicker._checkExternalClick), a.datepicker.initialized = !0), 0 === a("#" + a.datepicker._mainDivId).length && a("body").append(a.datepicker.dpDiv);
        var c = Array.prototype.slice.call(arguments, 1);
        return "string" != typeof b || "isDisabled" !== b && "getDate" !== b && "widget" !== b ? "option" === b && 2 === arguments.length && "string" == typeof arguments[1] ? a.datepicker["_" + b + "Datepicker"].apply(a.datepicker, [this[0]].concat(c)) : this.each(function () {
            "string" == typeof b ? a.datepicker["_" + b + "Datepicker"].apply(a.datepicker, [this].concat(c)) : a.datepicker._attachDatepicker(this, b)
        }) : a.datepicker["_" + b + "Datepicker"].apply(a.datepicker, [this[0]].concat(c))
    }, a.datepicker = new e, a.datepicker.initialized = !1, a.datepicker.uuid = (new Date).getTime(), a.datepicker.version = "1.10.3"
}(jQuery), function (a, b) {
    var c = {buttons: !0, height: !0, maxHeight: !0, maxWidth: !0, minHeight: !0, minWidth: !0, width: !0},
        d = {maxHeight: !0, maxWidth: !0, minHeight: !0, minWidth: !0};
    a.widget("ui.dialog", {
        version: "1.10.3",
        options: {
            appendTo: "body",
            autoOpen: !0,
            buttons: [],
            closeOnEscape: !0,
            closeText: "close",
            dialogClass: "",
            draggable: !0,
            hide: null,
            height: "auto",
            maxHeight: null,
            maxWidth: null,
            minHeight: 150,
            minWidth: 150,
            modal: !1,
            position: {
                my: "center", at: "center", of: window, collision: "fit", using: function (b) {
                    var c = a(this).css(b).offset().top;
                    c < 0 && a(this).css("top", b.top - c)
                }
            },
            resizable: !0,
            show: null,
            title: null,
            width: 300,
            beforeClose: null,
            close: null,
            drag: null,
            dragStart: null,
            dragStop: null,
            focus: null,
            open: null,
            resize: null,
            resizeStart: null,
            resizeStop: null
        },
        _create: function () {
            this.originalCss = {
                display: this.element[0].style.display,
                width: this.element[0].style.width,
                minHeight: this.element[0].style.minHeight,
                maxHeight: this.element[0].style.maxHeight,
                height: this.element[0].style.height
            }, this.originalPosition = {
                parent: this.element.parent(),
                index: this.element.parent().children().index(this.element)
            }, this.originalTitle = this.element.attr("title"), this.options.title = this.options.title || this.originalTitle, this._createWrapper(), this.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(this.uiDialog), this._createTitlebar(), this._createButtonPane(), this.options.draggable && a.fn.draggable && this._makeDraggable(), this.options.resizable && a.fn.resizable && this._makeResizable(), this._isOpen = !1
        },
        _init: function () {
            this.options.autoOpen && this.open()
        },
        _appendTo: function () {
            var b = this.options.appendTo;
            return b && (b.jquery || b.nodeType) ? a(b) : this.document.find(b || "body").eq(0)
        },
        _destroy: function () {
            var a, b = this.originalPosition;
            this._destroyOverlay(), this.element.removeUniqueId().removeClass("ui-dialog-content ui-widget-content").css(this.originalCss).detach(), this.uiDialog.stop(!0, !0).remove(), this.originalTitle && this.element.attr("title", this.originalTitle), a = b.parent.children().eq(b.index), a.length && a[0] !== this.element[0] ? a.before(this.element) : b.parent.append(this.element)
        },
        widget: function () {
            return this.uiDialog
        },
        disable: a.noop,
        enable: a.noop,
        close: function (b) {
            var c = this;
            this._isOpen && this._trigger("beforeClose", b) !== !1 && (this._isOpen = !1, this._destroyOverlay(), this.opener.filter(":focusable").focus().length || a(this.document[0].activeElement).blur(), this._hide(this.uiDialog, this.options.hide, function () {
                c._trigger("close", b)
            }))
        },
        isOpen: function () {
            return this._isOpen
        },
        moveToTop: function () {
            this._moveToTop()
        },
        _moveToTop: function (a, b) {
            var c = !!this.uiDialog.nextAll(":visible").insertBefore(this.uiDialog).length;
            return c && !b && this._trigger("focus", a), c
        },
        open: function () {
            var b = this;
            return this._isOpen ? void(this._moveToTop() && this._focusTabbable()) : (this._isOpen = !0, this.opener = a(this.document[0].activeElement), this._size(), this._position(), this._createOverlay(), this._moveToTop(null, !0), this._show(this.uiDialog, this.options.show, function () {
                b._focusTabbable(), b._trigger("focus")
            }), void this._trigger("open"))
        },
        _focusTabbable: function () {
            var a = this.element.find("[autofocus]");
            a.length || (a = this.element.find(":tabbable")), a.length || (a = this.uiDialogButtonPane.find(":tabbable")), a.length || (a = this.uiDialogTitlebarClose.filter(":tabbable")), a.length || (a = this.uiDialog), a.eq(0).focus()
        },
        _keepFocus: function (b) {
            function c() {
                var b = this.document[0].activeElement, c = this.uiDialog[0] === b || a.contains(this.uiDialog[0], b);
                c || this._focusTabbable()
            }

            b.preventDefault(), c.call(this), this._delay(c)
        },
        _createWrapper: function () {
            this.uiDialog = a("<div>").addClass("ui-dialog ui-widget ui-widget-content ui-corner-all ui-front " + this.options.dialogClass).hide().attr({
                tabIndex: -1,
                role: "dialog"
            }).appendTo(this._appendTo()), this._on(this.uiDialog, {
                keydown: function (b) {
                    if (this.options.closeOnEscape && !b.isDefaultPrevented() && b.keyCode && b.keyCode === a.ui.keyCode.ESCAPE) return b.preventDefault(), void this.close(b);
                    if (b.keyCode === a.ui.keyCode.TAB) {
                        var c = this.uiDialog.find(":tabbable"), d = c.filter(":first"), e = c.filter(":last");
                        b.target !== e[0] && b.target !== this.uiDialog[0] || b.shiftKey ? b.target !== d[0] && b.target !== this.uiDialog[0] || !b.shiftKey || (e.focus(1), b.preventDefault()) : (d.focus(1), b.preventDefault())
                    }
                }, mousedown: function (a) {
                    this._moveToTop(a) && this._focusTabbable()
                }
            }), this.element.find("[aria-describedby]").length || this.uiDialog.attr({"aria-describedby": this.element.uniqueId().attr("id")})
        },
        _createTitlebar: function () {
            var b;
            this.uiDialogTitlebar = a("<div>").addClass("ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix").prependTo(this.uiDialog), this._on(this.uiDialogTitlebar, {
                mousedown: function (b) {
                    a(b.target).closest(".ui-dialog-titlebar-close") || this.uiDialog.focus()
                }
            }), this.uiDialogTitlebarClose = a("<button></button>").button({
                label: this.options.closeText,
                icons: {primary: "ui-icon-closethick"},
                text: !1
            }).addClass("ui-dialog-titlebar-close").appendTo(this.uiDialogTitlebar), this._on(this.uiDialogTitlebarClose, {
                click: function (a) {
                    a.preventDefault(), this.close(a)
                }
            }), b = a("<span>").uniqueId().addClass("ui-dialog-title").prependTo(this.uiDialogTitlebar), this._title(b), this.uiDialog.attr({"aria-labelledby": b.attr("id")})
        },
        _title: function (a) {
            this.options.title || a.html("&#160;"), a.text(this.options.title)
        },
        _createButtonPane: function () {
            this.uiDialogButtonPane = a("<div>").addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"), this.uiButtonSet = a("<div>").addClass("ui-dialog-buttonset").appendTo(this.uiDialogButtonPane), this._createButtons()
        },
        _createButtons: function () {
            var b = this, c = this.options.buttons;
            return this.uiDialogButtonPane.remove(), this.uiButtonSet.empty(), a.isEmptyObject(c) || a.isArray(c) && !c.length ? void this.uiDialog.removeClass("ui-dialog-buttons") : (a.each(c, function (c, d) {
                var e, f;
                d = a.isFunction(d) ? {
                    click: d,
                    text: c
                } : d, d = a.extend({type: "button"}, d), e = d.click, d.click = function () {
                    e.apply(b.element[0], arguments)
                }, f = {
                    icons: d.icons,
                    text: d.showText
                }, delete d.icons, delete d.showText, a("<button></button>", d).button(f).appendTo(b.uiButtonSet)
            }), this.uiDialog.addClass("ui-dialog-buttons"), void this.uiDialogButtonPane.appendTo(this.uiDialog))
        },
        _makeDraggable: function () {
            function d(a) {
                return {position: a.position, offset: a.offset}
            }

            var b = this, c = this.options;
            this.uiDialog.draggable({
                cancel: ".ui-dialog-content, .ui-dialog-titlebar-close",
                handle: ".ui-dialog-titlebar",
                containment: "document",
                start: function (c, e) {
                    a(this).addClass("ui-dialog-dragging"), b._blockFrames(), b._trigger("dragStart", c, d(e))
                },
                drag: function (a, c) {
                    b._trigger("drag", a, d(c))
                },
                stop: function (e, f) {
                    c.position = [f.position.left - b.document.scrollLeft(), f.position.top - b.document.scrollTop()], a(this).removeClass("ui-dialog-dragging"), b._unblockFrames(), b._trigger("dragStop", e, d(f))
                }
            })
        },
        _makeResizable: function () {
            function g(a) {
                return {
                    originalPosition: a.originalPosition,
                    originalSize: a.originalSize,
                    position: a.position,
                    size: a.size
                }
            }

            var b = this, c = this.options, d = c.resizable, e = this.uiDialog.css("position"),
                f = "string" == typeof d ? d : "n,e,s,w,se,sw,ne,nw";
            this.uiDialog.resizable({
                cancel: ".ui-dialog-content",
                containment: "document",
                alsoResize: this.element,
                maxWidth: c.maxWidth,
                maxHeight: c.maxHeight,
                minWidth: c.minWidth,
                minHeight: this._minHeight(),
                handles: f,
                start: function (c, d) {
                    a(this).addClass("ui-dialog-resizing"), b._blockFrames(), b._trigger("resizeStart", c, g(d))
                },
                resize: function (a, c) {
                    b._trigger("resize", a, g(c))
                },
                stop: function (d, e) {
                    c.height = a(this).height(), c.width = a(this).width(), a(this).removeClass("ui-dialog-resizing"), b._unblockFrames(), b._trigger("resizeStop", d, g(e))
                }
            }).css("position", e)
        },
        _minHeight: function () {
            var a = this.options;
            return "auto" === a.height ? a.minHeight : Math.min(a.minHeight, a.height)
        },
        _position: function () {
            var a = this.uiDialog.is(":visible");
            a || this.uiDialog.show(), this.uiDialog.position(this.options.position), a || this.uiDialog.hide()
        },
        _setOptions: function (b) {
            var e = this, f = !1, g = {};
            a.each(b, function (a, b) {
                e._setOption(a, b), a in c && (f = !0), a in d && (g[a] = b)
            }), f && (this._size(), this._position()), this.uiDialog.is(":data(ui-resizable)") && this.uiDialog.resizable("option", g)
        },
        _setOption: function (a, b) {
            var c, d, e = this.uiDialog;
            "dialogClass" === a && e.removeClass(this.options.dialogClass).addClass(b), "disabled" !== a && (this._super(a, b), "appendTo" === a && this.uiDialog.appendTo(this._appendTo()), "buttons" === a && this._createButtons(), "closeText" === a && this.uiDialogTitlebarClose.button({label: "" + b}), "draggable" === a && (c = e.is(":data(ui-draggable)"), c && !b && e.draggable("destroy"), !c && b && this._makeDraggable()), "position" === a && this._position(), "resizable" === a && (d = e.is(":data(ui-resizable)"), d && !b && e.resizable("destroy"), d && "string" == typeof b && e.resizable("option", "handles", b), d || b === !1 || this._makeResizable()), "title" === a && this._title(this.uiDialogTitlebar.find(".ui-dialog-title")))
        },
        _size: function () {
            var a, b, c, d = this.options;
            this.element.show().css({
                width: "auto",
                minHeight: 0,
                maxHeight: "none",
                height: 0
            }), d.minWidth > d.width && (d.width = d.minWidth), a = this.uiDialog.css({
                height: "auto",
                width: d.width
            }).outerHeight(), b = Math.max(0, d.minHeight - a), c = "number" == typeof d.maxHeight ? Math.max(0, d.maxHeight - a) : "none", "auto" === d.height ? this.element.css({
                minHeight: b,
                maxHeight: c,
                height: "auto"
            }) : this.element.height(Math.max(0, d.height - a)), this.uiDialog.is(":data(ui-resizable)") && this.uiDialog.resizable("option", "minHeight", this._minHeight())
        },
        _blockFrames: function () {
            this.iframeBlocks = this.document.find("iframe").map(function () {
                var b = a(this);
                return a("<div>").css({
                    position: "absolute",
                    width: b.outerWidth(),
                    height: b.outerHeight()
                }).appendTo(b.parent()).offset(b.offset())[0]
            })
        },
        _unblockFrames: function () {
            this.iframeBlocks && (this.iframeBlocks.remove(), delete this.iframeBlocks)
        },
        _allowInteraction: function (b) {
            return !!a(b.target).closest(".ui-dialog").length || !!a(b.target).closest(".ui-datepicker").length
        },
        _createOverlay: function () {
            if (this.options.modal) {
                var b = this, c = this.widgetFullName;
                a.ui.dialog.overlayInstances || this._delay(function () {
                    a.ui.dialog.overlayInstances && this.document.bind("focusin.dialog", function (d) {
                        b._allowInteraction(d) || (d.preventDefault(), a(".ui-dialog:visible:last .ui-dialog-content").data(c)._focusTabbable())
                    })
                }), this.overlay = a("<div>").addClass("ui-widget-overlay ui-front").appendTo(this._appendTo()), this._on(this.overlay, {mousedown: "_keepFocus"}), a.ui.dialog.overlayInstances++
            }
        },
        _destroyOverlay: function () {
            this.options.modal && this.overlay && (a.ui.dialog.overlayInstances--, a.ui.dialog.overlayInstances || this.document.unbind("focusin.dialog"), this.overlay.remove(), this.overlay = null)
        }
    }), a.ui.dialog.overlayInstances = 0, a.uiBackCompat !== !1 && a.widget("ui.dialog", a.ui.dialog, {
        _position: function () {
            var e, b = this.options.position, c = [], d = [0, 0];
            b ? (("string" == typeof b || "object" == typeof b && "0" in b) && (c = b.split ? b.split(" ") : [b[0], b[1]], 1 === c.length && (c[1] = c[0]), a.each(["left", "top"], function (a, b) {
                +c[a] === c[a] && (d[a] = c[a], c[a] = b)
            }), b = {
                my: c[0] + (d[0] < 0 ? d[0] : "+" + d[0]) + " " + c[1] + (d[1] < 0 ? d[1] : "+" + d[1]),
                at: c.join(" ")
            }), b = a.extend({}, a.ui.dialog.prototype.options.position, b)) : b = a.ui.dialog.prototype.options.position, e = this.uiDialog.is(":visible"), e || this.uiDialog.show(), this.uiDialog.position(b), e || this.uiDialog.hide()
        }
    })
}(jQuery), function (a, b) {
    var c = /up|down|vertical/, d = /up|left|vertical|horizontal/;
    a.effects.effect.blind = function (b, e) {
        var p, q, r, f = a(this), g = ["position", "top", "bottom", "left", "right", "height", "width"],
            h = a.effects.setMode(f, b.mode || "hide"), i = b.direction || "up", j = c.test(i),
            k = j ? "height" : "width", l = j ? "top" : "left", m = d.test(i), n = {}, o = "show" === h;
        f.parent().is(".ui-effects-wrapper") ? a.effects.save(f.parent(), g) : a.effects.save(f, g), f.show(), p = a.effects.createWrapper(f).css({overflow: "hidden"}), q = p[k](), r = parseFloat(p.css(l)) || 0, n[k] = o ? q : 0, m || (f.css(j ? "bottom" : "right", 0).css(j ? "top" : "left", "auto").css({position: "absolute"}), n[l] = o ? r : q + r), o && (p.css(k, 0), m || p.css(l, r + q)), p.animate(n, {
            duration: b.duration,
            easing: b.easing,
            queue: !1,
            complete: function () {
                "hide" === h && f.hide(), a.effects.restore(f, g), a.effects.removeWrapper(f), e()
            }
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.bounce = function (b, c) {
        var q, r, s, d = a(this), e = ["position", "top", "bottom", "left", "right", "height", "width"],
            f = a.effects.setMode(d, b.mode || "effect"), g = "hide" === f, h = "show" === f, i = b.direction || "up",
            j = b.distance, k = b.times || 5, l = 2 * k + (h || g ? 1 : 0), m = b.duration / l, n = b.easing,
            o = "up" === i || "down" === i ? "top" : "left", p = "up" === i || "left" === i, t = d.queue(),
            u = t.length;
        for ((h || g) && e.push("opacity"), a.effects.save(d, e), d.show(), a.effects.createWrapper(d), j || (j = d["top" === o ? "outerHeight" : "outerWidth"]() / 3), h && (s = {opacity: 1}, s[o] = 0, d.css("opacity", 0).css(o, p ? 2 * -j : 2 * j).animate(s, m, n)), g && (j /= Math.pow(2, k - 1)), s = {}, s[o] = 0, q = 0; q < k; q++) r = {}, r[o] = (p ? "-=" : "+=") + j, d.animate(r, m, n).animate(s, m, n), j = g ? 2 * j : j / 2;
        g && (r = {opacity: 0}, r[o] = (p ? "-=" : "+=") + j, d.animate(r, m, n)), d.queue(function () {
            g && d.hide(), a.effects.restore(d, e), a.effects.removeWrapper(d), c()
        }), u > 1 && t.splice.apply(t, [1, 0].concat(t.splice(u, l + 1))), d.dequeue()
    }
}(jQuery), function (a, b) {
    a.effects.effect.clip = function (b, c) {
        var m, n, o, d = a(this), e = ["position", "top", "bottom", "left", "right", "height", "width"],
            f = a.effects.setMode(d, b.mode || "hide"), g = "show" === f, h = b.direction || "vertical",
            i = "vertical" === h, j = i ? "height" : "width", k = i ? "top" : "left", l = {};
        a.effects.save(d, e), d.show(), m = a.effects.createWrapper(d).css({overflow: "hidden"}), n = "IMG" === d[0].tagName ? m : d, o = n[j](), g && (n.css(j, 0), n.css(k, o / 2)), l[j] = g ? o : 0, l[k] = g ? 0 : o / 2, n.animate(l, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                g || d.hide(), a.effects.restore(d, e), a.effects.removeWrapper(d), c()
            }
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.drop = function (b, c) {
        var l, d = a(this), e = ["position", "top", "bottom", "left", "right", "opacity", "height", "width"],
            f = a.effects.setMode(d, b.mode || "hide"), g = "show" === f, h = b.direction || "left",
            i = "up" === h || "down" === h ? "top" : "left", j = "up" === h || "left" === h ? "pos" : "neg",
            k = {opacity: g ? 1 : 0};
        a.effects.save(d, e), d.show(), a.effects.createWrapper(d), l = b.distance || d["top" === i ? "outerHeight" : "outerWidth"](!0) / 2, g && d.css("opacity", 0).css(i, "pos" === j ? -l : l), k[i] = (g ? "pos" === j ? "+=" : "-=" : "pos" === j ? "-=" : "+=") + l, d.animate(k, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                "hide" === f && d.hide(), a.effects.restore(d, e), a.effects.removeWrapper(d), c()
            }
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.explode = function (b, c) {
        function s() {
            l.push(this), l.length === d * e && t()
        }

        function t() {
            f.css({visibility: "visible"}), a(l).remove(), h || f.hide(), c()
        }

        var m, n, o, p, q, r, d = b.pieces ? Math.round(Math.sqrt(b.pieces)) : 3, e = d, f = a(this),
            g = a.effects.setMode(f, b.mode || "hide"), h = "show" === g,
            i = f.show().css("visibility", "hidden").offset(), j = Math.ceil(f.outerWidth() / e),
            k = Math.ceil(f.outerHeight() / d), l = [];
        for (m = 0; m < d; m++) for (p = i.top + m * k, r = m - (d - 1) / 2, n = 0; n < e; n++) o = i.left + n * j, q = n - (e - 1) / 2, f.clone().appendTo("body").wrap("<div></div>").css({
            position: "absolute",
            visibility: "visible",
            left: -n * j,
            top: -m * k
        }).parent().addClass("ui-effects-explode").css({
            position: "absolute",
            overflow: "hidden",
            width: j,
            height: k,
            left: o + (h ? q * j : 0),
            top: p + (h ? r * k : 0),
            opacity: h ? 0 : 1
        }).animate({
            left: o + (h ? 0 : q * j),
            top: p + (h ? 0 : r * k),
            opacity: h ? 1 : 0
        }, b.duration || 500, b.easing, s)
    }
}(jQuery), function (a, b) {
    a.effects.effect.fade = function (b, c) {
        var d = a(this), e = a.effects.setMode(d, b.mode || "toggle");
        d.animate({opacity: e}, {queue: !1, duration: b.duration, easing: b.easing, complete: c})
    }
}(jQuery), function (a, b) {
    a.effects.effect.fold = function (b, c) {
        var o, p, d = a(this), e = ["position", "top", "bottom", "left", "right", "height", "width"],
            f = a.effects.setMode(d, b.mode || "hide"), g = "show" === f, h = "hide" === f, i = b.size || 15,
            j = /([0-9]+)%/.exec(i), k = !!b.horizFirst, l = g !== k, m = l ? ["width", "height"] : ["height", "width"],
            n = b.duration / 2, q = {}, r = {};
        a.effects.save(d, e), d.show(), o = a.effects.createWrapper(d).css({overflow: "hidden"}), p = l ? [o.width(), o.height()] : [o.height(), o.width()], j && (i = parseInt(j[1], 10) / 100 * p[h ? 0 : 1]), g && o.css(k ? {
            height: 0,
            width: i
        } : {
            height: i,
            width: 0
        }), q[m[0]] = g ? p[0] : i, r[m[1]] = g ? p[1] : 0, o.animate(q, n, b.easing).animate(r, n, b.easing, function () {
            h && d.hide(), a.effects.restore(d, e), a.effects.removeWrapper(d), c()
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.highlight = function (b, c) {
        var d = a(this), e = ["backgroundImage", "backgroundColor", "opacity"],
            f = a.effects.setMode(d, b.mode || "show"), g = {backgroundColor: d.css("backgroundColor")};
        "hide" === f && (g.opacity = 0), a.effects.save(d, e), d.show().css({
            backgroundImage: "none",
            backgroundColor: b.color || "#ffff99"
        }).animate(g, {
            queue: !1, duration: b.duration, easing: b.easing, complete: function () {
                "hide" === f && d.hide(), a.effects.restore(d, e), c()
            }
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.pulsate = function (b, c) {
        var n, d = a(this), e = a.effects.setMode(d, b.mode || "show"), f = "show" === e, g = "hide" === e,
            h = f || "hide" === e, i = 2 * (b.times || 5) + (h ? 1 : 0), j = b.duration / i, k = 0, l = d.queue(),
            m = l.length;
        for (!f && d.is(":visible") || (d.css("opacity", 0).show(), k = 1), n = 1; n < i; n++) d.animate({opacity: k}, j, b.easing), k = 1 - k;
        d.animate({opacity: k}, j, b.easing), d.queue(function () {
            g && d.hide(), c()
        }), m > 1 && l.splice.apply(l, [1, 0].concat(l.splice(m, i + 1))), d.dequeue()
    }
}(jQuery), function (a, b) {
    a.effects.effect.puff = function (b, c) {
        var d = a(this), e = a.effects.setMode(d, b.mode || "hide"), f = "hide" === e,
            g = parseInt(b.percent, 10) || 150, h = g / 100,
            i = {height: d.height(), width: d.width(), outerHeight: d.outerHeight(), outerWidth: d.outerWidth()};
        a.extend(b, {
            effect: "scale",
            queue: !1,
            fade: !0,
            mode: e,
            complete: c,
            percent: f ? g : 100,
            from: f ? i : {
                height: i.height * h,
                width: i.width * h,
                outerHeight: i.outerHeight * h,
                outerWidth: i.outerWidth * h
            }
        }), d.effect(b)
    }, a.effects.effect.scale = function (b, c) {
        var d = a(this), e = a.extend(!0, {}, b), f = a.effects.setMode(d, b.mode || "effect"),
            g = parseInt(b.percent, 10) || (0 === parseInt(b.percent, 10) ? 0 : "hide" === f ? 0 : 100),
            h = b.direction || "both", i = b.origin,
            j = {height: d.height(), width: d.width(), outerHeight: d.outerHeight(), outerWidth: d.outerWidth()},
            k = {y: "horizontal" !== h ? g / 100 : 1, x: "vertical" !== h ? g / 100 : 1};
        e.effect = "size", e.queue = !1, e.complete = c, "effect" !== f && (e.origin = i || ["middle", "center"], e.restore = !0), e.from = b.from || ("show" === f ? {
            height: 0,
            width: 0,
            outerHeight: 0,
            outerWidth: 0
        } : j), e.to = {
            height: j.height * k.y,
            width: j.width * k.x,
            outerHeight: j.outerHeight * k.y,
            outerWidth: j.outerWidth * k.x
        }, e.fade && ("show" === f && (e.from.opacity = 0, e.to.opacity = 1), "hide" === f && (e.from.opacity = 1, e.to.opacity = 0)), d.effect(e)
    }, a.effects.effect.size = function (b, c) {
        var d, e, f, g = a(this),
            h = ["position", "top", "bottom", "left", "right", "width", "height", "overflow", "opacity"],
            i = ["position", "top", "bottom", "left", "right", "overflow", "opacity"],
            j = ["width", "height", "overflow"], k = ["fontSize"],
            l = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"],
            m = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"],
            n = a.effects.setMode(g, b.mode || "effect"), o = b.restore || "effect" !== n, p = b.scale || "both",
            q = b.origin || ["middle", "center"], r = g.css("position"), s = o ? h : i,
            t = {height: 0, width: 0, outerHeight: 0, outerWidth: 0};
        "show" === n && g.show(), d = {
            height: g.height(),
            width: g.width(),
            outerHeight: g.outerHeight(),
            outerWidth: g.outerWidth()
        }, "toggle" === b.mode && "show" === n ? (g.from = b.to || t, g.to = b.from || d) : (g.from = b.from || ("show" === n ? t : d), g.to = b.to || ("hide" === n ? t : d)), f = {
            from: {
                y: g.from.height / d.height,
                x: g.from.width / d.width
            }, to: {y: g.to.height / d.height, x: g.to.width / d.width}
        }, "box" !== p && "both" !== p || (f.from.y !== f.to.y && (s = s.concat(l), g.from = a.effects.setTransition(g, l, f.from.y, g.from), g.to = a.effects.setTransition(g, l, f.to.y, g.to)), f.from.x !== f.to.x && (s = s.concat(m), g.from = a.effects.setTransition(g, m, f.from.x, g.from), g.to = a.effects.setTransition(g, m, f.to.x, g.to))), "content" !== p && "both" !== p || f.from.y !== f.to.y && (s = s.concat(k).concat(j), g.from = a.effects.setTransition(g, k, f.from.y, g.from), g.to = a.effects.setTransition(g, k, f.to.y, g.to)), a.effects.save(g, s), g.show(), a.effects.createWrapper(g), g.css("overflow", "hidden").css(g.from), q && (e = a.effects.getBaseline(q, d), g.from.top = (d.outerHeight - g.outerHeight()) * e.y, g.from.left = (d.outerWidth - g.outerWidth()) * e.x, g.to.top = (d.outerHeight - g.to.outerHeight) * e.y, g.to.left = (d.outerWidth - g.to.outerWidth) * e.x), g.css(g.from), "content" !== p && "both" !== p || (l = l.concat(["marginTop", "marginBottom"]).concat(k), m = m.concat(["marginLeft", "marginRight"]), j = h.concat(l).concat(m), g.find("*[width]").each(function () {
            var c = a(this),
                d = {height: c.height(), width: c.width(), outerHeight: c.outerHeight(), outerWidth: c.outerWidth()};
            o && a.effects.save(c, j), c.from = {
                height: d.height * f.from.y,
                width: d.width * f.from.x,
                outerHeight: d.outerHeight * f.from.y,
                outerWidth: d.outerWidth * f.from.x
            }, c.to = {
                height: d.height * f.to.y,
                width: d.width * f.to.x,
                outerHeight: d.height * f.to.y,
                outerWidth: d.width * f.to.x
            }, f.from.y !== f.to.y && (c.from = a.effects.setTransition(c, l, f.from.y, c.from), c.to = a.effects.setTransition(c, l, f.to.y, c.to)), f.from.x !== f.to.x && (c.from = a.effects.setTransition(c, m, f.from.x, c.from), c.to = a.effects.setTransition(c, m, f.to.x, c.to)), c.css(c.from), c.animate(c.to, b.duration, b.easing, function () {
                o && a.effects.restore(c, j)
            })
        })), g.animate(g.to, {
            queue: !1, duration: b.duration, easing: b.easing, complete: function () {
                0 === g.to.opacity && g.css("opacity", g.from.opacity), "hide" === n && g.hide(), a.effects.restore(g, s), o || ("static" === r ? g.css({
                    position: "relative",
                    top: g.to.top,
                    left: g.to.left
                }) : a.each(["top", "left"], function (a, b) {
                    g.css(b, function (b, c) {
                        var d = parseInt(c, 10), e = a ? g.to.left : g.to.top;
                        return "auto" === c ? e + "px" : d + e + "px"
                    })
                })), a.effects.removeWrapper(g), c()
            }
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.shake = function (b, c) {
        var q, d = a(this), e = ["position", "top", "bottom", "left", "right", "height", "width"],
            f = a.effects.setMode(d, b.mode || "effect"), g = b.direction || "left", h = b.distance || 20,
            i = b.times || 3, j = 2 * i + 1, k = Math.round(b.duration / j),
            l = "up" === g || "down" === g ? "top" : "left", m = "up" === g || "left" === g, n = {}, o = {}, p = {},
            r = d.queue(), s = r.length;
        for (a.effects.save(d, e), d.show(), a.effects.createWrapper(d), n[l] = (m ? "-=" : "+=") + h, o[l] = (m ? "+=" : "-=") + 2 * h, p[l] = (m ? "-=" : "+=") + 2 * h, d.animate(n, k, b.easing), q = 1; q < i; q++) d.animate(o, k, b.easing).animate(p, k, b.easing);
        d.animate(o, k, b.easing).animate(n, k / 2, b.easing).queue(function () {
            "hide" === f && d.hide(), a.effects.restore(d, e), a.effects.removeWrapper(d), c()
        }), s > 1 && r.splice.apply(r, [1, 0].concat(r.splice(s, j + 1))), d.dequeue()
    }
}(jQuery), function (a, b) {
    a.effects.effect.slide = function (b, c) {
        var k, d = a(this), e = ["position", "top", "bottom", "left", "right", "width", "height"],
            f = a.effects.setMode(d, b.mode || "show"), g = "show" === f, h = b.direction || "left",
            i = "up" === h || "down" === h ? "top" : "left", j = "up" === h || "left" === h, l = {};
        a.effects.save(d, e), d.show(), k = b.distance || d["top" === i ? "outerHeight" : "outerWidth"](!0), a.effects.createWrapper(d).css({overflow: "hidden"}), g && d.css(i, j ? isNaN(k) ? "-" + k : -k : k), l[i] = (g ? j ? "+=" : "-=" : j ? "-=" : "+=") + k, d.animate(l, {
            queue: !1,
            duration: b.duration,
            easing: b.easing,
            complete: function () {
                "hide" === f && d.hide(), a.effects.restore(d, e), a.effects.removeWrapper(d), c()
            }
        })
    }
}(jQuery), function (a, b) {
    a.effects.effect.transfer = function (b, c) {
        var d = a(this), e = a(b.to), f = "fixed" === e.css("position"), g = a("body"), h = f ? g.scrollTop() : 0,
            i = f ? g.scrollLeft() : 0, j = e.offset(),
            k = {top: j.top - h, left: j.left - i, height: e.innerHeight(), width: e.innerWidth()}, l = d.offset(),
            m = a("<div class='ui-effects-transfer'></div>").appendTo(document.body).addClass(b.className).css({
                top: l.top - h,
                left: l.left - i,
                height: d.innerHeight(),
                width: d.innerWidth(),
                position: f ? "fixed" : "absolute"
            }).animate(k, b.duration, b.easing, function () {
                m.remove(), c()
            })
    }
}(jQuery), function (a, b) {
    a.widget("ui.menu", {
        version: "1.10.3",
        defaultElement: "<ul>",
        delay: 300,
        options: {
            icons: {submenu: "ui-icon-carat-1-e"},
            menus: "ul",
            position: {my: "left top", at: "right top"},
            role: "menu",
            blur: null,
            focus: null,
            select: null
        },
        _create: function () {
            this.activeMenu = this.element, this.mouseHandled = !1, this.element.uniqueId().addClass("ui-menu ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menu-icons", !!this.element.find(".ui-icon").length).attr({
                role: this.options.role,
                tabIndex: 0
            }).bind("click" + this.eventNamespace, a.proxy(function (a) {
                this.options.disabled && a.preventDefault()
            }, this)), this.options.disabled && this.element.addClass("ui-state-disabled").attr("aria-disabled", "true"), this._on({
                "mousedown .ui-menu-item > a": function (a) {
                    a.preventDefault()
                }, "click .ui-state-disabled > a": function (a) {
                    a.preventDefault()
                }, "click .ui-menu-item:has(a)": function (b) {
                    var c = a(b.target).closest(".ui-menu-item");
                    !this.mouseHandled && c.not(".ui-state-disabled").length && (this.mouseHandled = !0, this.select(b), c.has(".ui-menu").length ? this.expand(b) : this.element.is(":focus") || (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menu").length && clearTimeout(this.timer)))
                }, "mouseenter .ui-menu-item": function (b) {
                    var c = a(b.currentTarget);
                    c.siblings().children(".ui-state-active").removeClass("ui-state-active"), this.focus(b, c)
                }, mouseleave: "collapseAll", "mouseleave .ui-menu": "collapseAll", focus: function (a, b) {
                    var c = this.active || this.element.children(".ui-menu-item").eq(0);
                    b || this.focus(a, c)
                }, blur: function (b) {
                    this._delay(function () {
                        a.contains(this.element[0], this.document[0].activeElement) || this.collapseAll(b)
                    })
                }, keydown: "_keydown"
            }), this.refresh(), this._on(this.document, {
                click: function (b) {
                    a(b.target).closest(".ui-menu").length || this.collapseAll(b), this.mouseHandled = !1
                }
            })
        },
        _destroy: function () {
            this.element.removeAttr("aria-activedescendant").find(".ui-menu").addBack().removeClass("ui-menu ui-widget ui-widget-content ui-corner-all ui-menu-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(), this.element.find(".ui-menu-item").removeClass("ui-menu-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function () {
                var b = a(this);
                b.data("ui-menu-submenu-carat") && b.remove()
            }), this.element.find(".ui-menu-divider").removeClass("ui-menu-divider ui-widget-content")
        },
        _keydown: function (b) {
            function i(a) {
                return a.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
            }

            var c, d, e, f, g, h = !0;
            switch (b.keyCode) {
                case a.ui.keyCode.PAGE_UP:
                    this.previousPage(b);
                    break;
                case a.ui.keyCode.PAGE_DOWN:
                    this.nextPage(b);
                    break;
                case a.ui.keyCode.HOME:
                    this._move("first", "first", b);
                    break;
                case a.ui.keyCode.END:
                    this._move("last", "last", b);
                    break;
                case a.ui.keyCode.UP:
                    this.previous(b);
                    break;
                case a.ui.keyCode.DOWN:
                    this.next(b);
                    break;
                case a.ui.keyCode.LEFT:
                    this.collapse(b);
                    break;
                case a.ui.keyCode.RIGHT:
                    this.active && !this.active.is(".ui-state-disabled") && this.expand(b);
                    break;
                case a.ui.keyCode.ENTER:
                case a.ui.keyCode.SPACE:
                    this._activate(b);
                    break;
                case a.ui.keyCode.ESCAPE:
                    this.collapse(b);
                    break;
                default:
                    h = !1, d = this.previousFilter || "", e = String.fromCharCode(b.keyCode), f = !1, clearTimeout(this.filterTimer), e === d ? f = !0 : e = d + e, g = new RegExp("^" + i(e), "i"), c = this.activeMenu.children(".ui-menu-item").filter(function () {
                        return g.test(a(this).children("a").text())
                    }), c = f && c.index(this.active.next()) !== -1 ? this.active.nextAll(".ui-menu-item") : c, c.length || (e = String.fromCharCode(b.keyCode), g = new RegExp("^" + i(e), "i"), c = this.activeMenu.children(".ui-menu-item").filter(function () {
                        return g.test(a(this).children("a").text())
                    })), c.length ? (this.focus(b, c), c.length > 1 ? (this.previousFilter = e, this.filterTimer = this._delay(function () {
                        delete this.previousFilter
                    }, 1e3)) : delete this.previousFilter) : delete this.previousFilter
            }
            h && b.preventDefault()
        },
        _activate: function (a) {
            this.active.is(".ui-state-disabled") || (this.active.children("a[aria-haspopup='true']").length ? this.expand(a) : this.select(a))
        },
        refresh: function () {
            var b, c = this.options.icons.submenu, d = this.element.find(this.options.menus);
            d.filter(":not(.ui-menu)").addClass("ui-menu ui-widget ui-widget-content ui-corner-all").hide().attr({
                role: this.options.role,
                "aria-hidden": "true",
                "aria-expanded": "false"
            }).each(function () {
                var b = a(this), d = b.prev("a"),
                    e = a("<span>").addClass("ui-menu-icon ui-icon " + c).data("ui-menu-submenu-carat", !0);
                d.attr("aria-haspopup", "true").prepend(e), b.attr("aria-labelledby", d.attr("id"))
            }), b = d.add(this.element), b.children(":not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role", "presentation").children("a").uniqueId().addClass("ui-corner-all").attr({
                tabIndex: -1,
                role: this._itemRole()
            }), b.children(":not(.ui-menu-item)").each(function () {
                var b = a(this);
                /[^\-\u2014\u2013\s]/.test(b.text()) || b.addClass("ui-widget-content ui-menu-divider")
            }), b.children(".ui-state-disabled").attr("aria-disabled", "true"), this.active && !a.contains(this.element[0], this.active[0]) && this.blur()
        },
        _itemRole: function () {
            return {menu: "menuitem", listbox: "option"}[this.options.role]
        },
        _setOption: function (a, b) {
            "icons" === a && this.element.find(".ui-menu-icon").removeClass(this.options.icons.submenu).addClass(b.submenu), this._super(a, b)
        },
        focus: function (a, b) {
            var c, d;
            this.blur(a, a && "focus" === a.type), this._scrollIntoView(b), this.active = b.first(), d = this.active.children("a").addClass("ui-state-focus"), this.options.role && this.element.attr("aria-activedescendant", d.attr("id")), this.active.parent().closest(".ui-menu-item").children("a:first").addClass("ui-state-active"), a && "keydown" === a.type ? this._close() : this.timer = this._delay(function () {
                this._close()
            }, this.delay), c = b.children(".ui-menu"), c.length && /^mouse/.test(a.type) && this._startOpening(c), this.activeMenu = b.parent(), this._trigger("focus", a, {item: b})
        },
        _scrollIntoView: function (b) {
            var c, d, e, f, g, h;
            this._hasScroll() && (c = parseFloat(a.css(this.activeMenu[0], "borderTopWidth")) || 0, d = parseFloat(a.css(this.activeMenu[0], "paddingTop")) || 0, e = b.offset().top - this.activeMenu.offset().top - c - d, f = this.activeMenu.scrollTop(), g = this.activeMenu.height(), h = b.height(), e < 0 ? this.activeMenu.scrollTop(f + e) : e + h > g && this.activeMenu.scrollTop(f + e - g + h))
        },
        blur: function (a, b) {
            b || clearTimeout(this.timer), this.active && (this.active.children("a").removeClass("ui-state-focus"), this.active = null, this._trigger("blur", a, {item: this.active}))
        },
        _startOpening: function (a) {
            clearTimeout(this.timer), "true" === a.attr("aria-hidden") && (this.timer = this._delay(function () {
                this._close(), this._open(a)
            }, this.delay))
        },
        _open: function (b) {
            var c = a.extend({of: this.active}, this.options.position);
            clearTimeout(this.timer), this.element.find(".ui-menu").not(b.parents(".ui-menu")).hide().attr("aria-hidden", "true"), b.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(c)
        },
        collapseAll: function (b, c) {
            clearTimeout(this.timer), this.timer = this._delay(function () {
                var d = c ? this.element : a(b && b.target).closest(this.element.find(".ui-menu"));
                d.length || (d = this.element), this._close(d), this.blur(b), this.activeMenu = d
            }, this.delay)
        },
        _close: function (a) {
            a || (a = this.active ? this.active.parent() : this.element), a.find(".ui-menu").hide().attr("aria-hidden", "true").attr("aria-expanded", "false").end().find("a.ui-state-active").removeClass("ui-state-active")
        },
        collapse: function (a) {
            var b = this.active && this.active.parent().closest(".ui-menu-item", this.element);
            b && b.length && (this._close(), this.focus(a, b))
        },
        expand: function (a) {
            var b = this.active && this.active.children(".ui-menu ").children(".ui-menu-item").first();
            b && b.length && (this._open(b.parent()), this._delay(function () {
                this.focus(a, b)
            }))
        },
        next: function (a) {
            this._move("next", "first", a)
        },
        previous: function (a) {
            this._move("prev", "last", a)
        },
        isFirstItem: function () {
            return this.active && !this.active.prevAll(".ui-menu-item").length
        },
        isLastItem: function () {
            return this.active && !this.active.nextAll(".ui-menu-item").length
        },
        _move: function (a, b, c) {
            var d;
            this.active && (d = "first" === a || "last" === a ? this.active["first" === a ? "prevAll" : "nextAll"](".ui-menu-item").eq(-1) : this.active[a + "All"](".ui-menu-item").eq(0)), d && d.length && this.active || (d = this.activeMenu.children(".ui-menu-item")[b]()), this.focus(c, d)
        },
        nextPage: function (b) {
            var c, d, e;
            return this.active ? void(this.isLastItem() || (this._hasScroll() ? (d = this.active.offset().top, e = this.element.height(), this.active.nextAll(".ui-menu-item").each(function () {
                return c = a(this), c.offset().top - d - e < 0
            }), this.focus(b, c)) : this.focus(b, this.activeMenu.children(".ui-menu-item")[this.active ? "last" : "first"]()))) : void this.next(b)
        },
        previousPage: function (b) {
            var c, d, e;
            return this.active ? void(this.isFirstItem() || (this._hasScroll() ? (d = this.active.offset().top, e = this.element.height(), this.active.prevAll(".ui-menu-item").each(function () {
                return c = a(this), c.offset().top - d + e > 0
            }), this.focus(b, c)) : this.focus(b, this.activeMenu.children(".ui-menu-item").first()))) : void this.next(b)
        },
        _hasScroll: function () {
            return this.element.outerHeight() < this.element.prop("scrollHeight")
        },
        select: function (b) {
            this.active = this.active || a(b.target).closest(".ui-menu-item");
            var c = {item: this.active};
            this.active.has(".ui-menu").length || this.collapseAll(b, !0), this._trigger("select", b, c)
        }
    })
}(jQuery), function (a, b) {
    function m(a, b, c) {
        return [parseFloat(a[0]) * (k.test(a[0]) ? b / 100 : 1), parseFloat(a[1]) * (k.test(a[1]) ? c / 100 : 1)]
    }

    function n(b, c) {
        return parseInt(a.css(b, c), 10) || 0
    }

    function o(b) {
        var c = b[0];
        return 9 === c.nodeType ? {
            width: b.width(),
            height: b.height(),
            offset: {top: 0, left: 0}
        } : a.isWindow(c) ? {
            width: b.width(),
            height: b.height(),
            offset: {top: b.scrollTop(), left: b.scrollLeft()}
        } : c.preventDefault ? {width: 0, height: 0, offset: {top: c.pageY, left: c.pageX}} : {
            width: b.outerWidth(),
            height: b.outerHeight(),
            offset: b.offset()
        }
    }

    a.ui = a.ui || {};
    var c, d = Math.max, e = Math.abs, f = Math.round, g = /left|center|right/, h = /top|center|bottom/,
        i = /[\+\-]\d+(\.[\d]+)?%?/, j = /^\w+/, k = /%$/, l = a.fn.position;
    a.position = {
        scrollbarWidth: function () {
            if (c !== b) return c;
            var d, e,
                f = a("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"),
                g = f.children()[0];
            return a("body").append(f), d = g.offsetWidth, f.css("overflow", "scroll"), e = g.offsetWidth, d === e && (e = f[0].clientWidth), f.remove(), c = d - e
        }, getScrollInfo: function (b) {
            var c = b.isWindow ? "" : b.element.css("overflow-x"), d = b.isWindow ? "" : b.element.css("overflow-y"),
                e = "scroll" === c || "auto" === c && b.width < b.element[0].scrollWidth,
                f = "scroll" === d || "auto" === d && b.height < b.element[0].scrollHeight;
            return {width: f ? a.position.scrollbarWidth() : 0, height: e ? a.position.scrollbarWidth() : 0}
        }, getWithinInfo: function (b) {
            var c = a(b || window), d = a.isWindow(c[0]);
            return {
                element: c,
                isWindow: d,
                offset: c.offset() || {left: 0, top: 0},
                scrollLeft: c.scrollLeft(),
                scrollTop: c.scrollTop(),
                width: d ? c.width() : c.outerWidth(),
                height: d ? c.height() : c.outerHeight()
            }
        }
    }, a.fn.position = function (b) {
        if (!b || !b.of) return l.apply(this, arguments);
        b = a.extend({}, b);
        var c, k, p, q, r, s, t = a(b.of), u = a.position.getWithinInfo(b.within), v = a.position.getScrollInfo(u),
            w = (b.collision || "flip").split(" "), x = {};
        return s = o(t), t[0].preventDefault && (b.at = "left top"), k = s.width, p = s.height, q = s.offset, r = a.extend({}, q), a.each(["my", "at"], function () {
            var c, d, a = (b[this] || "").split(" ");
            1 === a.length && (a = g.test(a[0]) ? a.concat(["center"]) : h.test(a[0]) ? ["center"].concat(a) : ["center", "center"]), a[0] = g.test(a[0]) ? a[0] : "center", a[1] = h.test(a[1]) ? a[1] : "center", c = i.exec(a[0]), d = i.exec(a[1]), x[this] = [c ? c[0] : 0, d ? d[0] : 0], b[this] = [j.exec(a[0])[0], j.exec(a[1])[0]]
        }), 1 === w.length && (w[1] = w[0]), "right" === b.at[0] ? r.left += k : "center" === b.at[0] && (r.left += k / 2), "bottom" === b.at[1] ? r.top += p : "center" === b.at[1] && (r.top += p / 2), c = m(x.at, k, p), r.left += c[0], r.top += c[1], this.each(function () {
            var g, h, i = a(this), j = i.outerWidth(), l = i.outerHeight(), o = n(this, "marginLeft"),
                s = n(this, "marginTop"), y = j + o + n(this, "marginRight") + v.width,
                z = l + s + n(this, "marginBottom") + v.height, A = a.extend({}, r),
                B = m(x.my, i.outerWidth(), i.outerHeight());
            "right" === b.my[0] ? A.left -= j : "center" === b.my[0] && (A.left -= j / 2), "bottom" === b.my[1] ? A.top -= l : "center" === b.my[1] && (A.top -= l / 2), A.left += B[0], A.top += B[1], a.support.offsetFractions || (A.left = f(A.left), A.top = f(A.top)), g = {
                marginLeft: o,
                marginTop: s
            }, a.each(["left", "top"], function (d, e) {
                a.ui.position[w[d]] && a.ui.position[w[d]][e](A, {
                    targetWidth: k,
                    targetHeight: p,
                    elemWidth: j,
                    elemHeight: l,
                    collisionPosition: g,
                    collisionWidth: y,
                    collisionHeight: z,
                    offset: [c[0] + B[0], c[1] + B[1]],
                    my: b.my,
                    at: b.at,
                    within: u,
                    elem: i
                })
            }), b.using && (h = function (a) {
                var c = q.left - A.left, f = c + k - j, g = q.top - A.top, h = g + p - l, m = {
                    target: {element: t, left: q.left, top: q.top, width: k, height: p},
                    element: {element: i, left: A.left, top: A.top, width: j, height: l},
                    horizontal: f < 0 ? "left" : c > 0 ? "right" : "center",
                    vertical: h < 0 ? "top" : g > 0 ? "bottom" : "middle"
                };
                k < j && e(c + f) < k && (m.horizontal = "center"), p < l && e(g + h) < p && (m.vertical = "middle"), d(e(c), e(f)) > d(e(g), e(h)) ? m.important = "horizontal" : m.important = "vertical", b.using.call(this, a, m)
            }), i.offset(a.extend(A, {using: h}))
        })
    }, a.ui.position = {
        fit: {
            left: function (a, b) {
                var j, c = b.within, e = c.isWindow ? c.scrollLeft : c.offset.left, f = c.width,
                    g = a.left - b.collisionPosition.marginLeft, h = e - g, i = g + b.collisionWidth - f - e;
                b.collisionWidth > f ? h > 0 && i <= 0 ? (j = a.left + h + b.collisionWidth - f - e, a.left += h - j) : i > 0 && h <= 0 ? a.left = e : h > i ? a.left = e + f - b.collisionWidth : a.left = e : h > 0 ? a.left += h : i > 0 ? a.left -= i : a.left = d(a.left - g, a.left)
            }, top: function (a, b) {
                var j, c = b.within, e = c.isWindow ? c.scrollTop : c.offset.top, f = b.within.height,
                    g = a.top - b.collisionPosition.marginTop, h = e - g, i = g + b.collisionHeight - f - e;
                b.collisionHeight > f ? h > 0 && i <= 0 ? (j = a.top + h + b.collisionHeight - f - e, a.top += h - j) : i > 0 && h <= 0 ? a.top = e : h > i ? a.top = e + f - b.collisionHeight : a.top = e : h > 0 ? a.top += h : i > 0 ? a.top -= i : a.top = d(a.top - g, a.top)
            }
        }, flip: {
            left: function (a, b) {
                var n, o, c = b.within, d = c.offset.left + c.scrollLeft, f = c.width,
                    g = c.isWindow ? c.scrollLeft : c.offset.left, h = a.left - b.collisionPosition.marginLeft,
                    i = h - g, j = h + b.collisionWidth - f - g,
                    k = "left" === b.my[0] ? -b.elemWidth : "right" === b.my[0] ? b.elemWidth : 0,
                    l = "left" === b.at[0] ? b.targetWidth : "right" === b.at[0] ? -b.targetWidth : 0,
                    m = -2 * b.offset[0];
                i < 0 ? (n = a.left + k + l + m + b.collisionWidth - f - d, (n < 0 || n < e(i)) && (a.left += k + l + m)) : j > 0 && (o = a.left - b.collisionPosition.marginLeft + k + l + m - g, (o > 0 || e(o) < j) && (a.left += k + l + m))
            }, top: function (a, b) {
                var o, p, c = b.within, d = c.offset.top + c.scrollTop, f = c.height,
                    g = c.isWindow ? c.scrollTop : c.offset.top, h = a.top - b.collisionPosition.marginTop, i = h - g,
                    j = h + b.collisionHeight - f - g, k = "top" === b.my[1],
                    l = k ? -b.elemHeight : "bottom" === b.my[1] ? b.elemHeight : 0,
                    m = "top" === b.at[1] ? b.targetHeight : "bottom" === b.at[1] ? -b.targetHeight : 0,
                    n = -2 * b.offset[1];
                i < 0 ? (p = a.top + l + m + n + b.collisionHeight - f - d, a.top + l + m + n > i && (p < 0 || p < e(i)) && (a.top += l + m + n)) : j > 0 && (o = a.top - b.collisionPosition.marginTop + l + m + n - g, a.top + l + m + n > j && (o > 0 || e(o) < j) && (a.top += l + m + n))
            }
        }, flipfit: {
            left: function () {
                a.ui.position.flip.left.apply(this, arguments), a.ui.position.fit.left.apply(this, arguments)
            }, top: function () {
                a.ui.position.flip.top.apply(this, arguments), a.ui.position.fit.top.apply(this, arguments)
            }
        }
    }, function () {
        var b, c, d, e, f, g = document.getElementsByTagName("body")[0], h = document.createElement("div");
        b = document.createElement(g ? "div" : "body"), d = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        }, g && a.extend(d, {position: "absolute", left: "-1000px", top: "-1000px"});
        for (f in d) b.style[f] = d[f];
        b.appendChild(h), c = g || document.documentElement, c.insertBefore(b, c.firstChild), h.style.cssText = "position: absolute; left: 10.7432222px;", e = a(h).offset().left, a.support.offsetFractions = e > 10 && e < 11, b.innerHTML = "", c.removeChild(b)
    }()
}(jQuery), function (a, b) {
    a.widget("ui.progressbar", {
        version: "1.10.3",
        options: {max: 100, value: 0, change: null, complete: null},
        min: 0,
        _create: function () {
            this.oldValue = this.options.value = this._constrainedValue(), this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({
                role: "progressbar",
                "aria-valuemin": this.min
            }), this.valueDiv = a("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element), this._refreshValue()
        },
        _destroy: function () {
            this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"), this.valueDiv.remove()
        },
        value: function (a) {
            return a === b ? this.options.value : (this.options.value = this._constrainedValue(a), void this._refreshValue())
        },
        _constrainedValue: function (a) {
            return a === b && (a = this.options.value), this.indeterminate = a === !1, "number" != typeof a && (a = 0), !this.indeterminate && Math.min(this.options.max, Math.max(this.min, a))
        },
        _setOptions: function (a) {
            var b = a.value;
            delete a.value, this._super(a), this.options.value = this._constrainedValue(b), this._refreshValue()
        },
        _setOption: function (a, b) {
            "max" === a && (b = Math.max(this.min, b)), this._super(a, b)
        },
        _percentage: function () {
            return this.indeterminate ? 100 : 100 * (this.options.value - this.min) / (this.options.max - this.min)
        },
        _refreshValue: function () {
            var b = this.options.value, c = this._percentage();
            this.valueDiv.toggle(this.indeterminate || b > this.min).toggleClass("ui-corner-right", b === this.options.max).width(c.toFixed(0) + "%"), this.element.toggleClass("ui-progressbar-indeterminate", this.indeterminate), this.indeterminate ? (this.element.removeAttr("aria-valuenow"), this.overlayDiv || (this.overlayDiv = a("<div class='ui-progressbar-overlay'></div>").appendTo(this.valueDiv))) : (this.element.attr({
                "aria-valuemax": this.options.max,
                "aria-valuenow": b
            }), this.overlayDiv && (this.overlayDiv.remove(), this.overlayDiv = null)), this.oldValue !== b && (this.oldValue = b, this._trigger("change")), b === this.options.max && this._trigger("complete")
        }
    })
}(jQuery), function (a, b) {
    var c = 5;
    a.widget("ui.slider", a.ui.mouse, {
        version: "1.10.3",
        widgetEventPrefix: "slide",
        options: {
            animate: !1,
            distance: 0,
            max: 100,
            min: 0,
            orientation: "horizontal",
            range: !1,
            step: 1,
            value: 0,
            values: null,
            change: null,
            slide: null,
            start: null,
            stop: null
        },
        _create: function () {
            this._keySliding = !1, this._mouseSliding = !1, this._animateOff = !0, this._handleIndex = null, this._detectOrientation(), this._mouseInit(), this.element.addClass("ui-slider ui-slider-" + this.orientation + " ui-widget ui-widget-content ui-corner-all"), this._refresh(), this._setOption("disabled", this.options.disabled), this._animateOff = !1
        },
        _refresh: function () {
            this._createRange(), this._createHandles(), this._setupEvents(), this._refreshValue()
        },
        _createHandles: function () {
            var b, c, d = this.options,
                e = this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),
                f = "<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>", g = [];
            for (c = d.values && d.values.length || 1, e.length > c && (e.slice(c).remove(), e = e.slice(0, c)), b = e.length; b < c; b++) g.push(f);
            this.handles = e.add(a(g.join("")).appendTo(this.element)), this.handle = this.handles.eq(0), this.handles.each(function (b) {
                a(this).data("ui-slider-handle-index", b)
            })
        },
        _createRange: function () {
            var b = this.options, c = "";
            b.range ? (b.range === !0 && (b.values ? b.values.length && 2 !== b.values.length ? b.values = [b.values[0], b.values[0]] : a.isArray(b.values) && (b.values = b.values.slice(0)) : b.values = [this._valueMin(), this._valueMin()]), this.range && this.range.length ? this.range.removeClass("ui-slider-range-min ui-slider-range-max").css({
                left: "",
                bottom: ""
            }) : (this.range = a("<div></div>").appendTo(this.element), c = "ui-slider-range ui-widget-header ui-corner-all"), this.range.addClass(c + ("min" === b.range || "max" === b.range ? " ui-slider-range-" + b.range : ""))) : this.range = a([])
        },
        _setupEvents: function () {
            var a = this.handles.add(this.range).filter("a");
            this._off(a), this._on(a, this._handleEvents), this._hoverable(a), this._focusable(a)
        },
        _destroy: function () {
            this.handles.remove(), this.range.remove(), this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-widget ui-widget-content ui-corner-all"), this._mouseDestroy()
        },
        _mouseCapture: function (b) {
            var c, d, e, f, g, h, i, j, k = this, l = this.options;
            return !l.disabled && (this.elementSize = {
                width: this.element.outerWidth(),
                height: this.element.outerHeight()
            }, this.elementOffset = this.element.offset(), c = {
                x: b.pageX,
                y: b.pageY
            }, d = this._normValueFromMouse(c), e = this._valueMax() - this._valueMin() + 1, this.handles.each(function (b) {
                var c = Math.abs(d - k.values(b));
                (e > c || e === c && (b === k._lastChangedValue || k.values(b) === l.min)) && (e = c, f = a(this), g = b)
            }), h = this._start(b, g), h !== !1 && (this._mouseSliding = !0, this._handleIndex = g, f.addClass("ui-state-active").focus(), i = f.offset(), j = !a(b.target).parents().addBack().is(".ui-slider-handle"), this._clickOffset = j ? {
                left: 0,
                top: 0
            } : {
                left: b.pageX - i.left - f.width() / 2,
                top: b.pageY - i.top - f.height() / 2 - (parseInt(f.css("borderTopWidth"), 10) || 0) - (parseInt(f.css("borderBottomWidth"), 10) || 0) + (parseInt(f.css("marginTop"), 10) || 0)
            }, this.handles.hasClass("ui-state-hover") || this._slide(b, g, d), this._animateOff = !0, !0))
        },
        _mouseStart: function () {
            return !0
        },
        _mouseDrag: function (a) {
            var b = {x: a.pageX, y: a.pageY}, c = this._normValueFromMouse(b);
            return this._slide(a, this._handleIndex, c), !1
        },
        _mouseStop: function (a) {
            return this.handles.removeClass("ui-state-active"), this._mouseSliding = !1, this._stop(a, this._handleIndex), this._change(a, this._handleIndex), this._handleIndex = null, this._clickOffset = null, this._animateOff = !1, !1
        },
        _detectOrientation: function () {
            this.orientation = "vertical" === this.options.orientation ? "vertical" : "horizontal"
        },
        _normValueFromMouse: function (a) {
            var b, c, d, e, f;
            return "horizontal" === this.orientation ? (b = this.elementSize.width, c = a.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left : 0)) : (b = this.elementSize.height, c = a.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top : 0)), d = c / b, d > 1 && (d = 1), d < 0 && (d = 0), "vertical" === this.orientation && (d = 1 - d), e = this._valueMax() - this._valueMin(), f = this._valueMin() + d * e, this._trimAlignValue(f)
        },
        _start: function (a, b) {
            var c = {handle: this.handles[b], value: this.value()};
            return this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()), this._trigger("start", a, c)
        },
        _slide: function (a, b, c) {
            var d, e, f;
            this.options.values && this.options.values.length ? (d = this.values(b ? 0 : 1), 2 === this.options.values.length && this.options.range === !0 && (0 === b && c > d || 1 === b && c < d) && (c = d), c !== this.values(b) && (e = this.values(), e[b] = c, f = this._trigger("slide", a, {
                handle: this.handles[b],
                value: c,
                values: e
            }), d = this.values(b ? 0 : 1), f !== !1 && this.values(b, c, !0))) : c !== this.value() && (f = this._trigger("slide", a, {
                handle: this.handles[b],
                value: c
            }), f !== !1 && this.value(c))
        },
        _stop: function (a, b) {
            var c = {handle: this.handles[b], value: this.value()};
            this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()), this._trigger("stop", a, c)
        },
        _change: function (a, b) {
            if (!this._keySliding && !this._mouseSliding) {
                var c = {handle: this.handles[b], value: this.value()};
                this.options.values && this.options.values.length && (c.value = this.values(b), c.values = this.values()), this._lastChangedValue = b, this._trigger("change", a, c)
            }
        },
        value: function (a) {
            return arguments.length ? (this.options.value = this._trimAlignValue(a), this._refreshValue(), void this._change(null, 0)) : this._value()
        },
        values: function (b, c) {
            var d, e, f;
            if (arguments.length > 1) return this.options.values[b] = this._trimAlignValue(c), this._refreshValue(), void this._change(null, b);
            if (!arguments.length) return this._values();
            if (!a.isArray(arguments[0])) return this.options.values && this.options.values.length ? this._values(b) : this.value();
            for (d = this.options.values, e = arguments[0], f = 0; f < d.length; f += 1) d[f] = this._trimAlignValue(e[f]), this._change(null, f);
            this._refreshValue()
        },
        _setOption: function (b, c) {
            var d, e = 0;
            switch ("range" === b && this.options.range === !0 && ("min" === c ? (this.options.value = this._values(0), this.options.values = null) : "max" === c && (this.options.value = this._values(this.options.values.length - 1), this.options.values = null)), a.isArray(this.options.values) && (e = this.options.values.length), a.Widget.prototype._setOption.apply(this, arguments), b) {
                case"orientation":
                    this._detectOrientation(), this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-" + this.orientation), this._refreshValue();
                    break;
                case"value":
                    this._animateOff = !0, this._refreshValue(), this._change(null, 0), this._animateOff = !1;
                    break;
                case"values":
                    for (this._animateOff = !0, this._refreshValue(), d = 0; d < e; d += 1) this._change(null, d);
                    this._animateOff = !1;
                    break;
                case"min":
                case"max":
                    this._animateOff = !0, this._refreshValue(), this._animateOff = !1;
                    break;
                case"range":
                    this._animateOff = !0, this._refresh(), this._animateOff = !1
            }
        },
        _value: function () {
            var a = this.options.value;
            return a = this._trimAlignValue(a)
        },
        _values: function (a) {
            var b, c, d;
            if (arguments.length) return b = this.options.values[a], b = this._trimAlignValue(b);
            if (this.options.values && this.options.values.length) {
                for (c = this.options.values.slice(), d = 0; d < c.length; d += 1) c[d] = this._trimAlignValue(c[d]);
                return c
            }
            return []
        },
        _trimAlignValue: function (a) {
            if (a <= this._valueMin()) return this._valueMin();
            if (a >= this._valueMax()) return this._valueMax();
            var b = this.options.step > 0 ? this.options.step : 1, c = (a - this._valueMin()) % b, d = a - c;
            return 2 * Math.abs(c) >= b && (d += c > 0 ? b : -b), parseFloat(d.toFixed(5))
        },
        _valueMin: function () {
            return this.options.min
        },
        _valueMax: function () {
            return this.options.max
        },
        _refreshValue: function () {
            var b, c, d, e, f, g = this.options.range, h = this.options, i = this, j = !this._animateOff && h.animate,
                k = {};
            this.options.values && this.options.values.length ? this.handles.each(function (d) {
                c = (i.values(d) - i._valueMin()) / (i._valueMax() - i._valueMin()) * 100, k["horizontal" === i.orientation ? "left" : "bottom"] = c + "%", a(this).stop(1, 1)[j ? "animate" : "css"](k, h.animate), i.options.range === !0 && ("horizontal" === i.orientation ? (0 === d && i.range.stop(1, 1)[j ? "animate" : "css"]({left: c + "%"}, h.animate), 1 === d && i.range[j ? "animate" : "css"]({width: c - b + "%"}, {
                    queue: !1,
                    duration: h.animate
                })) : (0 === d && i.range.stop(1, 1)[j ? "animate" : "css"]({bottom: c + "%"}, h.animate), 1 === d && i.range[j ? "animate" : "css"]({height: c - b + "%"}, {
                    queue: !1,
                    duration: h.animate
                }))), b = c
            }) : (d = this.value(), e = this._valueMin(), f = this._valueMax(), c = f !== e ? (d - e) / (f - e) * 100 : 0, k["horizontal" === this.orientation ? "left" : "bottom"] = c + "%", this.handle.stop(1, 1)[j ? "animate" : "css"](k, h.animate), "min" === g && "horizontal" === this.orientation && this.range.stop(1, 1)[j ? "animate" : "css"]({width: c + "%"}, h.animate), "max" === g && "horizontal" === this.orientation && this.range[j ? "animate" : "css"]({width: 100 - c + "%"}, {
                queue: !1,
                duration: h.animate
            }), "min" === g && "vertical" === this.orientation && this.range.stop(1, 1)[j ? "animate" : "css"]({height: c + "%"}, h.animate), "max" === g && "vertical" === this.orientation && this.range[j ? "animate" : "css"]({height: 100 - c + "%"}, {
                queue: !1,
                duration: h.animate
            }))
        },
        _handleEvents: {
            keydown: function (b) {
                var d, e, f, g, h = a(b.target).data("ui-slider-handle-index");
                switch (b.keyCode) {
                    case a.ui.keyCode.HOME:
                    case a.ui.keyCode.END:
                    case a.ui.keyCode.PAGE_UP:
                    case a.ui.keyCode.PAGE_DOWN:
                    case a.ui.keyCode.UP:
                    case a.ui.keyCode.RIGHT:
                    case a.ui.keyCode.DOWN:
                    case a.ui.keyCode.LEFT:
                        if (b.preventDefault(), !this._keySliding && (this._keySliding = !0, a(b.target).addClass("ui-state-active"), d = this._start(b, h), d === !1)) return
                }
                switch (g = this.options.step, e = f = this.options.values && this.options.values.length ? this.values(h) : this.value(), b.keyCode) {
                    case a.ui.keyCode.HOME:
                        f = this._valueMin();
                        break;
                    case a.ui.keyCode.END:
                        f = this._valueMax();
                        break;
                    case a.ui.keyCode.PAGE_UP:
                        f = this._trimAlignValue(e + (this._valueMax() - this._valueMin()) / c);
                        break;
                    case a.ui.keyCode.PAGE_DOWN:
                        f = this._trimAlignValue(e - (this._valueMax() - this._valueMin()) / c);
                        break;
                    case a.ui.keyCode.UP:
                    case a.ui.keyCode.RIGHT:
                        if (e === this._valueMax()) return;
                        f = this._trimAlignValue(e + g);
                        break;
                    case a.ui.keyCode.DOWN:
                    case a.ui.keyCode.LEFT:
                        if (e === this._valueMin()) return;
                        f = this._trimAlignValue(e - g)
                }
                this._slide(b, h, f)
            }, click: function (a) {
                a.preventDefault()
            }, keyup: function (b) {
                var c = a(b.target).data("ui-slider-handle-index");
                this._keySliding && (this._keySliding = !1, this._stop(b, c), this._change(b, c), a(b.target).removeClass("ui-state-active"))
            }
        }
    })
}(jQuery), function (a) {
    function b(a) {
        return function () {
            var b = this.element.val();
            a.apply(this, arguments), this._refresh(), b !== this.element.val() && this._trigger("change")
        }
    }

    a.widget("ui.spinner", {
        version: "1.10.3",
        defaultElement: "<input>",
        widgetEventPrefix: "spin",
        options: {
            culture: null,
            icons: {down: "ui-icon-triangle-1-s", up: "ui-icon-triangle-1-n"},
            incremental: !0,
            max: null,
            min: null,
            numberFormat: null,
            page: 10,
            step: 1,
            change: null,
            spin: null,
            start: null,
            stop: null
        },
        _create: function () {
            this._setOption("max", this.options.max), this._setOption("min", this.options.min), this._setOption("step", this.options.step), this._value(this.element.val(), !0), this._draw(), this._on(this._events), this._refresh(), this._on(this.window, {
                beforeunload: function () {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _getCreateOptions: function () {
            var b = {}, c = this.element;
            return a.each(["min", "max", "step"], function (a, d) {
                var e = c.attr(d);
                void 0 !== e && e.length && (b[d] = e)
            }), b
        },
        _events: {
            keydown: function (a) {
                this._start(a) && this._keydown(a) && a.preventDefault()
            }, keyup: "_stop", focus: function () {
                this.previous = this.element.val()
            }, blur: function (a) {
                return this.cancelBlur ? void delete this.cancelBlur : (this._stop(), this._refresh(), void(this.previous !== this.element.val() && this._trigger("change", a)))
            }, mousewheel: function (a, b) {
                if (b) {
                    if (!this.spinning && !this._start(a)) return !1;
                    this._spin((b > 0 ? 1 : -1) * this.options.step, a), clearTimeout(this.mousewheelTimer), this.mousewheelTimer = this._delay(function () {
                        this.spinning && this._stop(a)
                    }, 100), a.preventDefault()
                }
            }, "mousedown .ui-spinner-button": function (b) {
                function d() {
                    var a = this.element[0] === this.document[0].activeElement;
                    a || (this.element.focus(), this.previous = c, this._delay(function () {
                        this.previous = c
                    }))
                }

                var c;
                c = this.element[0] === this.document[0].activeElement ? this.previous : this.element.val(), b.preventDefault(), d.call(this), this.cancelBlur = !0, this._delay(function () {
                    delete this.cancelBlur, d.call(this)
                }), this._start(b) !== !1 && this._repeat(null, a(b.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, b)
            }, "mouseup .ui-spinner-button": "_stop", "mouseenter .ui-spinner-button": function (b) {
                if (a(b.currentTarget).hasClass("ui-state-active")) return this._start(b) !== !1 && void this._repeat(null, a(b.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, b)
            }, "mouseleave .ui-spinner-button": "_stop"
        },
        _draw: function () {
            var a = this.uiSpinner = this.element.addClass("ui-spinner-input").attr("autocomplete", "off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());
            this.element.attr("role", "spinbutton"), this.buttons = a.find(".ui-spinner-button").attr("tabIndex", -1).button().removeClass("ui-corner-all"), this.buttons.height() > Math.ceil(.5 * a.height()) && a.height() > 0 && a.height(a.height()), this.options.disabled && this.disable()
        },
        _keydown: function (b) {
            var c = this.options, d = a.ui.keyCode;
            switch (b.keyCode) {
                case d.UP:
                    return this._repeat(null, 1, b), !0;
                case d.DOWN:
                    return this._repeat(null, -1, b), !0;
                case d.PAGE_UP:
                    return this._repeat(null, c.page, b), !0;
                case d.PAGE_DOWN:
                    return this._repeat(null, -c.page, b), !0
            }
            return !1
        },
        _uiSpinnerHtml: function () {
            return "<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"
        },
        _buttonHtml: function () {
            return "<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon " + this.options.icons.up + "'>&#9650;</span></a><a class='ui-spinner-button ui-spinner-down ui-corner-br'><span class='ui-icon " + this.options.icons.down + "'>&#9660;</span></a>"
        },
        _start: function (a) {
            return !(!this.spinning && this._trigger("start", a) === !1) && (this.counter || (this.counter = 1), this.spinning = !0, !0)
        },
        _repeat: function (a, b, c) {
            a = a || 500, clearTimeout(this.timer), this.timer = this._delay(function () {
                this._repeat(40, b, c)
            }, a), this._spin(b * this.options.step, c)
        },
        _spin: function (a, b) {
            var c = this.value() || 0;
            this.counter || (this.counter = 1), c = this._adjustValue(c + a * this._increment(this.counter)), this.spinning && this._trigger("spin", b, {value: c}) === !1 || (this._value(c), this.counter++)
        },
        _increment: function (b) {
            var c = this.options.incremental;
            return c ? a.isFunction(c) ? c(b) : Math.floor(b * b * b / 5e4 - b * b / 500 + 17 * b / 200 + 1) : 1
        },
        _precision: function () {
            var a = this._precisionOf(this.options.step);
            return null !== this.options.min && (a = Math.max(a, this._precisionOf(this.options.min))), a
        },
        _precisionOf: function (a) {
            var b = a.toString(), c = b.indexOf(".");
            return c === -1 ? 0 : b.length - c - 1
        },
        _adjustValue: function (a) {
            var b, c, d = this.options;
            return b = null !== d.min ? d.min : 0, c = a - b, c = Math.round(c / d.step) * d.step, a = b + c, a = parseFloat(a.toFixed(this._precision())), null !== d.max && a > d.max ? d.max : null !== d.min && a < d.min ? d.min : a
        },
        _stop: function (a) {
            this.spinning && (clearTimeout(this.timer), clearTimeout(this.mousewheelTimer), this.counter = 0, this.spinning = !1, this._trigger("stop", a))
        },
        _setOption: function (a, b) {
            if ("culture" === a || "numberFormat" === a) {
                var c = this._parse(this.element.val());
                return this.options[a] = b, void this.element.val(this._format(c))
            }
            "max" !== a && "min" !== a && "step" !== a || "string" == typeof b && (b = this._parse(b)), "icons" === a && (this.buttons.first().find(".ui-icon").removeClass(this.options.icons.up).addClass(b.up), this.buttons.last().find(".ui-icon").removeClass(this.options.icons.down).addClass(b.down)), this._super(a, b), "disabled" === a && (b ? (this.element.prop("disabled", !0), this.buttons.button("disable")) : (this.element.prop("disabled", !1), this.buttons.button("enable")))
        },
        _setOptions: b(function (a) {
            this._super(a), this._value(this.element.val())
        }),
        _parse: function (a) {
            return "string" == typeof a && "" !== a && (a = window.Globalize && this.options.numberFormat ? Globalize.parseFloat(a, 10, this.options.culture) : +a), "" === a || isNaN(a) ? null : a
        },
        _format: function (a) {
            return "" === a ? "" : window.Globalize && this.options.numberFormat ? Globalize.format(a, this.options.numberFormat, this.options.culture) : a
        },
        _refresh: function () {
            this.element.attr({
                "aria-valuemin": this.options.min,
                "aria-valuemax": this.options.max,
                "aria-valuenow": this._parse(this.element.val())
            })
        },
        _value: function (a, b) {
            var c;
            "" !== a && (c = this._parse(a), null !== c && (b || (c = this._adjustValue(c)), a = this._format(c))), this.element.val(a), this._refresh()
        },
        _destroy: function () {
            this.element.removeClass("ui-spinner-input").prop("disabled", !1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"), this.uiSpinner.replaceWith(this.element)
        },
        stepUp: b(function (a) {
            this._stepUp(a)
        }),
        _stepUp: function (a) {
            this._start() && (this._spin((a || 1) * this.options.step), this._stop())
        },
        stepDown: b(function (a) {
            this._stepDown(a)
        }),
        _stepDown: function (a) {
            this._start() && (this._spin((a || 1) * -this.options.step), this._stop())
        },
        pageUp: b(function (a) {
            this._stepUp((a || 1) * this.options.page)
        }),
        pageDown: b(function (a) {
            this._stepDown((a || 1) * this.options.page)
        }),
        value: function (a) {
            return arguments.length ? void b(this._value).call(this, a) : this._parse(this.element.val())
        },
        widget: function () {
            return this.uiSpinner
        }
    })
}(jQuery), function (a, b) {
    function e() {
        return ++c
    }

    function f(a) {
        return a.hash.length > 1 && decodeURIComponent(a.href.replace(d, "")) === decodeURIComponent(location.href.replace(d, ""))
    }

    var c = 0, d = /#.*$/;
    a.widget("ui.tabs", {
        version: "1.10.3",
        delay: 300,
        options: {
            active: null,
            collapsible: !1,
            event: "click",
            heightStyle: "content",
            hide: null,
            show: null,
            activate: null,
            beforeActivate: null,
            beforeLoad: null,
            load: null
        },
        _create: function () {
            var b = this, c = this.options;
            this.running = !1, this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible", c.collapsible).delegate(".ui-tabs-nav > li", "mousedown" + this.eventNamespace, function (b) {
                a(this).is(".ui-state-disabled") && b.preventDefault()
            }).delegate(".ui-tabs-anchor", "focus" + this.eventNamespace, function () {
                a(this).closest("li").is(".ui-state-disabled") && this.blur()
            }), this._processTabs(), c.active = this._initialActive(), a.isArray(c.disabled) && (c.disabled = a.unique(c.disabled.concat(a.map(this.tabs.filter(".ui-state-disabled"), function (a) {
                return b.tabs.index(a)
            }))).sort()), this.options.active !== !1 && this.anchors.length ? this.active = this._findActive(c.active) : this.active = a(), this._refresh(), this.active.length && this.load(c.active)
        },
        _initialActive: function () {
            var b = this.options.active, c = this.options.collapsible, d = location.hash.substring(1);
            return null === b && (d && this.tabs.each(function (c, e) {
                if (a(e).attr("aria-controls") === d) return b = c, !1
            }), null === b && (b = this.tabs.index(this.tabs.filter(".ui-tabs-active"))), null !== b && b !== -1 || (b = !!this.tabs.length && 0)), b !== !1 && (b = this.tabs.index(this.tabs.eq(b)), b === -1 && (b = !c && 0)), !c && b === !1 && this.anchors.length && (b = 0), b
        },
        _getCreateEventData: function () {
            return {tab: this.active, panel: this.active.length ? this._getPanelForTab(this.active) : a()}
        },
        _tabKeydown: function (b) {
            var c = a(this.document[0].activeElement).closest("li"), d = this.tabs.index(c), e = !0;
            if (!this._handlePageNav(b)) {
                switch (b.keyCode) {
                    case a.ui.keyCode.RIGHT:
                    case a.ui.keyCode.DOWN:
                        d++;
                        break;
                    case a.ui.keyCode.UP:
                    case a.ui.keyCode.LEFT:
                        e = !1, d--;
                        break;
                    case a.ui.keyCode.END:
                        d = this.anchors.length - 1;
                        break;
                    case a.ui.keyCode.HOME:
                        d = 0;
                        break;
                    case a.ui.keyCode.SPACE:
                        return b.preventDefault(), clearTimeout(this.activating), void this._activate(d);
                    case a.ui.keyCode.ENTER:
                        return b.preventDefault(), clearTimeout(this.activating), void this._activate(d !== this.options.active && d);
                    default:
                        return
                }
                b.preventDefault(), clearTimeout(this.activating), d = this._focusNextTab(d, e), b.ctrlKey || (c.attr("aria-selected", "false"), this.tabs.eq(d).attr("aria-selected", "true"), this.activating = this._delay(function () {
                    this.option("active", d)
                }, this.delay))
            }
        },
        _panelKeydown: function (b) {
            this._handlePageNav(b) || b.ctrlKey && b.keyCode === a.ui.keyCode.UP && (b.preventDefault(), this.active.focus())
        },
        _handlePageNav: function (b) {
            return b.altKey && b.keyCode === a.ui.keyCode.PAGE_UP ? (this._activate(this._focusNextTab(this.options.active - 1, !1)), !0) : b.altKey && b.keyCode === a.ui.keyCode.PAGE_DOWN ? (this._activate(this._focusNextTab(this.options.active + 1, !0)), !0) : void 0
        },
        _findNextTab: function (b, c) {
            function e() {
                return b > d && (b = 0), b < 0 && (b = d), b
            }

            for (var d = this.tabs.length - 1; a.inArray(e(), this.options.disabled) !== -1;) b = c ? b + 1 : b - 1;
            return b
        },
        _focusNextTab: function (a, b) {
            return a = this._findNextTab(a, b), this.tabs.eq(a).focus(), a
        },
        _setOption: function (a, b) {
            return "active" === a ? void this._activate(b) : "disabled" === a ? void this._setupDisabled(b) : (this._super(a, b), "collapsible" === a && (this.element.toggleClass("ui-tabs-collapsible", b), b || this.options.active !== !1 || this._activate(0)), "event" === a && this._setupEvents(b), void("heightStyle" === a && this._setupHeightStyle(b)))
        },
        _tabId: function (a) {
            return a.attr("aria-controls") || "ui-tabs-" + e()
        },
        _sanitizeSelector: function (a) {
            return a ? a.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g, "\\$&") : ""
        },
        refresh: function () {
            var b = this.options, c = this.tablist.children(":has(a[href])");
            b.disabled = a.map(c.filter(".ui-state-disabled"), function (a) {
                return c.index(a)
            }), this._processTabs(), b.active !== !1 && this.anchors.length ? this.active.length && !a.contains(this.tablist[0], this.active[0]) ? this.tabs.length === b.disabled.length ? (b.active = !1, this.active = a()) : this._activate(this._findNextTab(Math.max(0, b.active - 1), !1)) : b.active = this.tabs.index(this.active) : (b.active = !1, this.active = a()), this._refresh()
        },
        _refresh: function () {
            this._setupDisabled(this.options.disabled), this._setupEvents(this.options.event), this._setupHeightStyle(this.options.heightStyle), this.tabs.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }), this.panels.not(this._getPanelForTab(this.active)).hide().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }), this.active.length ? (this.active.addClass("ui-tabs-active ui-state-active").attr({
                "aria-selected": "true",
                tabIndex: 0
            }), this._getPanelForTab(this.active).show().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            })) : this.tabs.eq(0).attr("tabIndex", 0)
        },
        _processTabs: function () {
            var b = this;
            this.tablist = this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role", "tablist"), this.tabs = this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({
                role: "tab",
                tabIndex: -1
            }), this.anchors = this.tabs.map(function () {
                return a("a", this)[0]
            }).addClass("ui-tabs-anchor").attr({
                role: "presentation",
                tabIndex: -1
            }), this.panels = a(), this.anchors.each(function (c, d) {
                var e, g, h, i = a(d).uniqueId().attr("id"), j = a(d).closest("li"), k = j.attr("aria-controls");
                f(d) ? (e = d.hash, g = b.element.find(b._sanitizeSelector(e))) : (h = b._tabId(j), e = "#" + h, g = b.element.find(e), g.length || (g = b._createPanel(h), g.insertAfter(b.panels[c - 1] || b.tablist)), g.attr("aria-live", "polite")), g.length && (b.panels = b.panels.add(g)), k && j.data("ui-tabs-aria-controls", k), j.attr({
                    "aria-controls": e.substring(1),
                    "aria-labelledby": i
                }), g.attr("aria-labelledby", i)
            }), this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role", "tabpanel")
        },
        _getList: function () {
            return this.element.find("ol,ul").eq(0)
        },
        _createPanel: function (b) {
            return a("<div>").attr("id", b).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy", !0)
        },
        _setupDisabled: function (b) {
            a.isArray(b) && (b.length ? b.length === this.anchors.length && (b = !0) : b = !1);
            for (var d, c = 0; d = this.tabs[c]; c++) b === !0 || a.inArray(c, b) !== -1 ? a(d).addClass("ui-state-disabled").attr("aria-disabled", "true") : a(d).removeClass("ui-state-disabled").removeAttr("aria-disabled");
            this.options.disabled = b
        },
        _setupEvents: function (b) {
            var c = {
                click: function (a) {
                    a.preventDefault()
                }
            };
            b && a.each(b.split(" "), function (a, b) {
                c[b] = "_eventHandler"
            }), this._off(this.anchors.add(this.tabs).add(this.panels)), this._on(this.anchors, c), this._on(this.tabs, {keydown: "_tabKeydown"}), this._on(this.panels, {keydown: "_panelKeydown"}), this._focusable(this.tabs), this._hoverable(this.tabs)
        },
        _setupHeightStyle: function (b) {
            var c, d = this.element.parent();
            "fill" === b ? (c = d.height(), c -= this.element.outerHeight() - this.element.height(), this.element.siblings(":visible").each(function () {
                var b = a(this), d = b.css("position");
                "absolute" !== d && "fixed" !== d && (c -= b.outerHeight(!0))
            }), this.element.children().not(this.panels).each(function () {
                c -= a(this).outerHeight(!0)
            }), this.panels.each(function () {
                a(this).height(Math.max(0, c - a(this).innerHeight() + a(this).height()))
            }).css("overflow", "auto")) : "auto" === b && (c = 0, this.panels.each(function () {
                c = Math.max(c, a(this).height("").height())
            }).height(c))
        },
        _eventHandler: function (b) {
            var c = this.options, d = this.active, e = a(b.currentTarget), f = e.closest("li"), g = f[0] === d[0],
                h = g && c.collapsible, i = h ? a() : this._getPanelForTab(f),
                j = d.length ? this._getPanelForTab(d) : a(),
                k = {oldTab: d, oldPanel: j, newTab: h ? a() : f, newPanel: i};
            b.preventDefault(), f.hasClass("ui-state-disabled") || f.hasClass("ui-tabs-loading") || this.running || g && !c.collapsible || this._trigger("beforeActivate", b, k) === !1 || (c.active = !h && this.tabs.index(f), this.active = g ? a() : f, this.xhr && this.xhr.abort(), j.length || i.length || a.error("jQuery UI Tabs: Mismatching fragment identifier."), i.length && this.load(this.tabs.index(f), b), this._toggle(b, k))
        },
        _toggle: function (b, c) {
            function g() {
                d.running = !1, d._trigger("activate", b, c)
            }

            function h() {
                c.newTab.closest("li").addClass("ui-tabs-active ui-state-active"), e.length && d.options.show ? d._show(e, d.options.show, g) : (e.show(), g())
            }

            var d = this, e = c.newPanel, f = c.oldPanel;
            this.running = !0, f.length && this.options.hide ? this._hide(f, this.options.hide, function () {
                c.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), h()
            }) : (c.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), f.hide(), h()), f.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }), c.oldTab.attr("aria-selected", "false"), e.length && f.length ? c.oldTab.attr("tabIndex", -1) : e.length && this.tabs.filter(function () {
                return 0 === a(this).attr("tabIndex")
            }).attr("tabIndex", -1), e.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }), c.newTab.attr({"aria-selected": "true", tabIndex: 0})
        },
        _activate: function (b) {
            var c, d = this._findActive(b);
            d[0] !== this.active[0] && (d.length || (d = this.active), c = d.find(".ui-tabs-anchor")[0], this._eventHandler({
                target: c,
                currentTarget: c,
                preventDefault: a.noop
            }))
        },
        _findActive: function (b) {
            return b === !1 ? a() : this.tabs.eq(b)
        },
        _getIndex: function (a) {
            return "string" == typeof a && (a = this.anchors.index(this.anchors.filter("[href$='" + a + "']"))), a
        },
        _destroy: function () {
            this.xhr && this.xhr.abort(), this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"), this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"), this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeUniqueId(), this.tabs.add(this.panels).each(function () {
                a.data(this, "ui-tabs-destroy") ? a(this).remove() : a(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")
            }), this.tabs.each(function () {
                var b = a(this), c = b.data("ui-tabs-aria-controls");
                c ? b.attr("aria-controls", c).removeData("ui-tabs-aria-controls") : b.removeAttr("aria-controls")
            }), this.panels.show(), "content" !== this.options.heightStyle && this.panels.css("height", "")
        },
        enable: function (c) {
            var d = this.options.disabled;
            d !== !1 && (c === b ? d = !1 : (c = this._getIndex(c), d = a.isArray(d) ? a.map(d, function (a) {
                return a !== c ? a : null
            }) : a.map(this.tabs, function (a, b) {
                return b !== c ? b : null
            })), this._setupDisabled(d))
        },
        disable: function (c) {
            var d = this.options.disabled;
            if (d !== !0) {
                if (c === b) d = !0; else {
                    if (c = this._getIndex(c), a.inArray(c, d) !== -1) return;
                    d = a.isArray(d) ? a.merge([c], d).sort() : [c]
                }
                this._setupDisabled(d)
            }
        },
        load: function (b, c) {
            b = this._getIndex(b);
            var d = this, e = this.tabs.eq(b), g = e.find(".ui-tabs-anchor"), h = this._getPanelForTab(e),
                i = {tab: e, panel: h};
            f(g[0]) || (this.xhr = a.ajax(this._ajaxSettings(g, c, i)), this.xhr && "canceled" !== this.xhr.statusText && (e.addClass("ui-tabs-loading"), h.attr("aria-busy", "true"), this.xhr.success(function (a) {
                setTimeout(function () {
                    h.html(a), d._trigger("load", c, i)
                }, 1)
            }).complete(function (a, b) {
                setTimeout(function () {
                    "abort" === b && d.panels.stop(!1, !0), e.removeClass("ui-tabs-loading"), h.removeAttr("aria-busy"), a === d.xhr && delete d.xhr
                }, 1)
            })))
        },
        _ajaxSettings: function (b, c, d) {
            var e = this;
            return {
                url: b.attr("href"), beforeSend: function (b, f) {
                    return e._trigger("beforeLoad", c, a.extend({jqXHR: b, ajaxSettings: f}, d))
                }
            }
        },
        _getPanelForTab: function (b) {
            var c = a(b).attr("aria-controls");
            return this.element.find(this._sanitizeSelector("#" + c))
        }
    })
}(jQuery), function (a) {
    function c(b, c) {
        var d = (b.attr("aria-describedby") || "").split(/\s+/);
        d.push(c), b.data("ui-tooltip-id", c).attr("aria-describedby", a.trim(d.join(" ")))
    }

    function d(b) {
        var c = b.data("ui-tooltip-id"), d = (b.attr("aria-describedby") || "").split(/\s+/), e = a.inArray(c, d);
        e !== -1 && d.splice(e, 1), b.removeData("ui-tooltip-id"), d = a.trim(d.join(" ")), d ? b.attr("aria-describedby", d) : b.removeAttr("aria-describedby")
    }

    var b = 0;
    a.widget("ui.tooltip", {
        version: "1.10.3", options: {
            content: function () {
                var b = a(this).attr("title") || "";
                return a("<a>").text(b).html()
            },
            hide: !0,
            items: "[title]:not([disabled])",
            position: {my: "left top+15", at: "left bottom", collision: "flipfit flip"},
            show: !0,
            tooltipClass: null,
            track: !1,
            close: null,
            open: null
        }, _create: function () {
            this._on({
                mouseover: "open",
                focusin: "open"
            }), this.tooltips = {}, this.parents = {}, this.options.disabled && this._disable()
        }, _setOption: function (b, c) {
            var d = this;
            return "disabled" === b ? (this[c ? "_disable" : "_enable"](), void(this.options[b] = c)) : (this._super(b, c), void("content" === b && a.each(this.tooltips, function (a, b) {
                d._updateContent(b)
            })))
        }, _disable: function () {
            var b = this;
            a.each(this.tooltips, function (c, d) {
                var e = a.Event("blur");
                e.target = e.currentTarget = d[0], b.close(e, !0)
            }), this.element.find(this.options.items).addBack().each(function () {
                var b = a(this);
                b.is("[title]") && b.data("ui-tooltip-title", b.attr("title")).attr("title", "")
            })
        }, _enable: function () {
            this.element.find(this.options.items).addBack().each(function () {
                var b = a(this);
                b.data("ui-tooltip-title") && b.attr("title", b.data("ui-tooltip-title"))
            })
        }, open: function (b) {
            var c = this, d = a(b ? b.target : this.element).closest(this.options.items);
            d.length && !d.data("ui-tooltip-id") && (d.attr("title") && d.data("ui-tooltip-title", d.attr("title")), d.data("ui-tooltip-open", !0), b && "mouseover" === b.type && d.parents().each(function () {
                var d, b = a(this);
                b.data("ui-tooltip-open") && (d = a.Event("blur"), d.target = d.currentTarget = this, c.close(d, !0)), b.attr("title") && (b.uniqueId(), c.parents[this.id] = {
                    element: this,
                    title: b.attr("title")
                }, b.attr("title", ""))
            }), this._updateContent(d, b))
        }, _updateContent: function (a, b) {
            var c, d = this.options.content, e = this, f = b ? b.type : null;
            return "string" == typeof d ? this._open(b, a, d) : (c = d.call(a[0], function (c) {
                a.data("ui-tooltip-open") && e._delay(function () {
                    b && (b.type = f), this._open(b, a, c)
                })
            }), void(c && this._open(b, a, c)))
        }, _open: function (b, d, e) {
            function j(a) {
                i.of = a, f.is(":hidden") || f.position(i)
            }

            var f, g, h, i = a.extend({}, this.options.position);
            if (e) {
                if (f = this._find(d), f.length) return void f.find(".ui-tooltip-content").html(e);
                d.is("[title]") && (b && "mouseover" === b.type ? d.attr("title", "") : d.removeAttr("title")), f = this._tooltip(d), c(d, f.attr("id")), f.find(".ui-tooltip-content").html(e), this.options.track && b && /^mouse/.test(b.type) ? (this._on(this.document, {mousemove: j}), j(b)) : f.position(a.extend({of: d}, this.options.position)), f.hide(), this._show(f, this.options.show), this.options.show && this.options.show.delay && (h = this.delayedShow = setInterval(function () {
                    f.is(":visible") && (j(i.of), clearInterval(h))
                }, a.fx.interval)), this._trigger("open", b, {tooltip: f}), g = {
                    keyup: function (b) {
                        if (b.keyCode === a.ui.keyCode.ESCAPE) {
                            var c = a.Event(b);
                            c.currentTarget = d[0], this.close(c, !0)
                        }
                    }, remove: function () {
                        this._removeTooltip(f)
                    }
                }, b && "mouseover" !== b.type || (g.mouseleave = "close"), b && "focusin" !== b.type || (g.focusout = "close"), this._on(!0, d, g)
            }
        }, close: function (b) {
            var c = this, e = a(b ? b.currentTarget : this.element), f = this._find(e);
            this.closing || (clearInterval(this.delayedShow), e.data("ui-tooltip-title") && e.attr("title", e.data("ui-tooltip-title")), d(e), f.stop(!0), this._hide(f, this.options.hide, function () {
                c._removeTooltip(a(this))
            }), e.removeData("ui-tooltip-open"), this._off(e, "mouseleave focusout keyup"), e[0] !== this.element[0] && this._off(e, "remove"), this._off(this.document, "mousemove"), b && "mouseleave" === b.type && a.each(this.parents, function (b, d) {
                a(d.element).attr("title", d.title), delete c.parents[b]
            }), this.closing = !0, this._trigger("close", b, {tooltip: f}), this.closing = !1)
        }, _tooltip: function (c) {
            var d = "ui-tooltip-" + b++, e = a("<div>").attr({
                id: d,
                role: "tooltip"
            }).addClass("ui-tooltip ui-widget ui-corner-all ui-widget-content " + (this.options.tooltipClass || ""));
            return a("<div>").addClass("ui-tooltip-content").appendTo(e), e.appendTo(this.document[0].body), this.tooltips[d] = c, e
        }, _find: function (b) {
            var c = b.data("ui-tooltip-id");
            return c ? a("#" + c) : a()
        }, _removeTooltip: function (a) {
            a.remove(), delete this.tooltips[a.attr("id")]
        }, _destroy: function () {
            var b = this;
            a.each(this.tooltips, function (c, d) {
                var e = a.Event("blur");
                e.target = e.currentTarget = d[0], b.close(e, !0), a("#" + c).remove(), d.data("ui-tooltip-title") && (d.attr("title", d.data("ui-tooltip-title")), d.removeData("ui-tooltip-title"))
            })
        }
    })
}(jQuery);
$(document).ready(function () {
    var $searchWidget = $('#search_widget');
    var $searchBox = $searchWidget.find('input[type=text]');
    var searchURL = $searchWidget.attr('data-search-controller-url');
    $.widget('prestashop.psBlockSearchAutocomplete', $.ui.autocomplete, {
        _renderItem: function (ul, product) {
            return $("<li>").append($("<a>").append($("<span>").html(product.category_name).addClass("category")).append($("<span>").html(' > ').addClass("separator")).append($("<span>").html(product.name).addClass("product"))).appendTo(ul)
        }
    });
    $searchBox.psBlockSearchAutocomplete({
        source: function (query, response) {
            $.post(searchURL, {s: query.term, resultsPerPage: 10}, null, 'json').then(function (resp) {
                response(resp.products)
            }).fail(response)
        }, select: function (event, ui) {
            var url = ui.item.url;
            window.location.href = url
        },
    })
});
$(document).ready(function () {
    prestashop.blockcart = prestashop.blockcart || {};
    var showModal = prestashop.blockcart.showModal || function (modal) {
        var $body = $('body');
        $body.append(modal);
        $body.one('click', '#blockcart-modal', function (event) {
            if (event.target.id === 'blockcart-modal') {
                $(event.target).remove()
            }
        })
    };
    $(document).ready(function () {
        prestashop.on('updateCart', function (event) {
            var refreshURL = $('.blockcart').data('refresh-url');
            var requestData = {};
            if (event && event.reason) {
                requestData = {
                    id_product_attribute: event.reason.idProductAttribute,
                    id_product: event.reason.idProduct,
                    action: event.reason.linkAction
                }
            }
            $.post(refreshURL, requestData).then(function (resp) {
                $('.blockcart').replaceWith($(resp.preview).find('.blockcart'));
                if (resp.modal) {
                    showModal(resp.modal)
                }
            }).fail(function (resp) {
                prestashop.emit('handleError', {eventType: 'updateShoppingCart', resp: resp})
            })
        })
    })
})