function checkPrices()
{ rv = document.getElementsByClassName("price");
  for(var i in rv) { 
    if(rv[i].value.indexOf(',') != -1) { 
      alert("Please use dots instead of comma's for the prices!");
      rv.focus();
      return false;
    }
  }
  return true;
}

var rowsremoved = 0;
function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
  rowsremoved++;
}

function cat_change(num)
{ var list = eval("Mainform.categorylist"+num);
  var fld = document.getElementById('category_number'+num);
  fld.value = list.options[list.selectedIndex].value;
}

function catsel_change(num)
{ var list = eval("Mainform.categorysel"+num);
  var fld = document.getElementById('category_number'+num);
  fld.value = list.options[list.selectedIndex].value;
}

/* the following section takes care of conditional inclusion of the TinyMCE file */
var TMCE_head = document.getElementsByTagName('head')[0];
TMCE_js = document.createElement("script");
TMCE_js.type = "text/javascript";
TMCE_loaded = false;

function TMCE_loadonce()
{ if(TMCE_loaded)
    return;
//  Prestashop settings can be found at /js/tinymce.inc.js
//  TMCE_js.src = "http://cdn.tinymce.com/4/tinymce.min.js";
  TMCE_js.src = "tinymce/tinymce.min.js";
  TMCE_head.appendChild(TMCE_js);
  TMCE_loaded = true;
}

function check_shopz(rowno)
{	var shopz_arr = document.getElementsByName("shopz"+rowno+"[]");
	if(shopz_arr.length > 0)          
	{ var found = false;
      for(var x=0; x<shopz_arr.length; x++)
		  if(shopz_arr[x].checked)
			  found=true;
	  if(!found)
	  { alert("At least one shop must be selected for a product!");
		return false;		  
	  }
	}
	return true;
}

function RowSubmit(elt)
{ 
  var subtbl = document.getElementById("subtable");
  var row = elt.parentNode.parentNode;
  var rowno = row.childNodes[0].id.substr(4);
  
  if(!check_discounts(rowno)) return false; /* check for duplicate key errors in discounts that would result in errors */
  if(!check_shopz(rowno)) return false; /* check that at least one shop is selected */
  var subrow = subtbl.appendChild(row.cloneNode(true));
  
  var selects = row.getElementsByTagName('select');
  for(var k=0;k<selects.length;k++)  
  { if(!selects[k].name) continue;
    document.rowform[selects[k].name].selectedIndex = selects[k].selectedIndex;
  }

  var areas = row.getElementsByTagName('textarea');
  for(var k=0;k<areas.length;k++)  
  { if(((areas[k].name.substring(0,17) == "description_short") || (areas[k].name.substring(0,11) == "description")) && (areas[k].parentNode.childNodes[0].tagName == "DIV"))
    { //alert(tinyMCE.get(areas[k].name).getContent());
	  document.rowform[areas[k].name].value = tinyMCE.get(areas[k].name).getContent();
	}
  }
  var tmp = document.getElementById('featureblock0');
  if(tmp.style.display == 'none')
    rowform.featuresset.value = 0;
  else
    rowform.featuresset.value = 1;
  rowform.verbose.value = SwitchForm.verbose.checked;
  if(SwitchForm.verbose.checked)
  { rowform.target="_blank";
  }
  else
  { rowform.target="tank";
  }  
  rowform.skipindexation.value = IndexForm.skipindexation.checked;  
  rowform.allshops.value = Mainform.allshops.value;  
  rowform.submittedrow.value = rowno; 
  rowform.submit();
  subtbl.removeChild(subrow);
}

/* RowSubmit2 is no longer used and only stored for "spare parts" */
function RowSubmit2(elt)
{ var subtbl = document.getElementById("subtable");
  var row = elt.parentNode.parentNode;
  var rowno = row.childNodes[0].id.substr(4);
  if(!check_discounts(rowno)) return false; /* check for duplicate key errors in discounts that would result in errors */
  if(!check_shopz(rowno)) return false; /* check that at least one shop is selected */
  var find = "shopz"+rowno+"\\[\\]";
  var re = new RegExp(find, 'g');
  var tmp = row.innerHTML.replace(re, "shopz[]"); /* we need to rename shopz here as otherwise getElementsByName won't work because of duplicates */
  subtbl.innerHTML = '<tr>'+tmp+'</tr>';
  // field contents are not automatically copied
  var inputs = row.getElementsByTagName('input');
  var shopz_done = false;
  for(var k=0;k<inputs.length;k++)
  { if(((inputs[k].name.substring(0,6) == "active") || (inputs[k].name.substring(0,7) == "on_sale") || (inputs[k].name.substring(0,11) == "online_only") || (inputs[k].name.substring(0,9) == "dl_delete") || (inputs[k].name.substring(0,10) == "is_virtual")) && (inputs[k].type == "hidden"))
	{ elt = document.rowform[inputs[k].name][0]; /* the trick with the hidden field works not with the rowsubmit so we delete it */
	  elt.parentNode.removeChild(elt);
	  continue;
	}
	/* for shopz we copy and remove the number for several fields with the same name (like "shopz5[]") */
	else if(inputs[k].name.substring(0,5) == "shopz")
	{ if(shopz_done) continue;
	  shopz_done = true;
	  var tabfields = document.getElementsByName("shopz"+rowno+"[]");
	  var rowfields = document.getElementsByName("shopz[]");	  
	  for(var m=0; m<tabfields.length; m++)
	  { rowfields[m].checked = tabfields[m].checked;
		rowfields[m].value = tabfields[m].value;
	  }
	}
    else if(inputs[k].type != "button")
    { if(inputs[k].name == "") continue; /* skip category_number: the field between the category select tables */
	  if(((inputs[k].name.substring(0,6) == "active") || (inputs[k].name.substring(0,7) == "on_sale") 
		|| (inputs[k].name.substring(0,11) == "online_only") || (inputs[k].name.substring(0,10) == "is_virtual")
		|| (inputs[k].name.substring(0,9) == "dl_delete")) 
		&& (inputs[k].type == "checkbox"))
	  { document.rowform[inputs[k].name].type = "text";
	    if(!inputs[k].checked) document.rowform[inputs[k].name].value = "0"; /* value will initially always be "1" */
	  }
	  else	
	  {	document.rowform[inputs[k].name].value = inputs[k].value;
	  }
      var temp = document.rowform[inputs[k].name].name;
      temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
      document.rowform[inputs[k].name].name = temp;
    }
  }
  var areas = row.getElementsByTagName('textarea');
  for(var k=0;k<areas.length;k++)  
  { if(((areas[k].name.substring(0,17) == "description_short") || (areas[k].name.substring(0,11) == "description")) && (areas[k].parentNode.childNodes[0].tagName == "DIV"))
    { //alert(tinyMCE.get(areas[k].name).getContent());
	  document.rowform[areas[k].name].value = tinyMCE.get(areas[k].name).getContent();
	}
	else if((areas[k].name.substring(0,17) == "description_short") || (areas[k].name.substring(0,11) == "description"))
	{ document.rowform[areas[k].name].value = tidy_html(areas[k].value);
	}
    else
	{ document.rowform[areas[k].name].value = areas[k].value;
	}
    var temp = document.rowform[areas[k].name].name;
    temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
    document.rowform[areas[k].name].name = temp;
  }
  var selects = row.getElementsByTagName('select');
  for(var k=0;k<selects.length;k++)  
  { if(!selects[k].name) continue;
    document.rowform[selects[k].name].selectedIndex = selects[k].selectedIndex;
    var temp = document.rowform[selects[k].name].name;
    temp = temp.replace(/[0-9]*$/, ""); /* chance "description1" into "description" */
    document.rowform[selects[k].name].name = temp;
  }
  var tmp = document.getElementById('featureblock0');
  if(tmp.style.display == 'none')
    rowform.featuresset.value = 0;
  else
    rowform.featuresset.value = 1;
  if(rowform.carriersel0 && rowform.carriersel0.options[0].value == "none")
    rowform.carriersel0.options.length=0;
  rowform.verbose.value = SwitchForm.verbose.checked;
  if(SwitchForm.verbose.checked)
  { rowform.method="get";
    rowform.target="_blank";
  }
  else
  { rowform.method="post";
    rowform.target="tank";
  }  
  rowform.skipindexation.value = IndexForm.skipindexation.checked;  
  rowform.allshops.value = Mainform.allshops.value;  
  document.rowform['id_row'].value = row.childNodes[0].id;
  document.rowform.submit();
}

function reindex()
{ IndexForm.verbose.value = SwitchForm.verbose.checked;
  document.IndexForm.submit();
}

/* modify the number in "Re-index the ... unindexed products" */
function update_index(value)
{ var tmp = document.getElementById('reindexspan');
  tmp.innerHTML = value;	
}

function price_change(elt)
{ var price = elt.value;
  reg_change(elt);  
  var VATcol = getColumn("VAT");
  if(VATcol === undefined) return;
  var VAT = elt.parentNode.parentNode.cells[VATcol].innerHTML;
  var pvcol = getColumn("priceVAT");
  if(pvcol === undefined) return;
  var newprice = price * (1 + (VAT / 100));
  newprice = Math.round(newprice*1000000)/1000000; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[pvcol].innerHTML = newprice;
}

function priceVAT_change(elt)
{ var priceVAT = elt.value;
  var VATcol = getColumn("VAT");
  var VAT = elt.parentNode.parentNode.cells[VATcol].innerHTML;
  var thisrow = elt.name.substring(8);
  var pcol = getColumn("price");
  var newprice = priceVAT / (1 + (VAT / 100));
  newprice = Math.round(newprice*1000000)/1000000; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[pcol].innerHTML = newprice;
  pricefield = eval("Mainform.price"+thisrow);
  pricefield.value = newprice;
  reg_change(elt);
}

function VAT_change(elt)
{ reg_change(elt);
  var col1 = getColumn("price");
  if(col1 === undefined) return;
  var col2 = getColumn("priceVAT");
  if(col2 === undefined) return;
  var VAT = elt.options[elt.selectedIndex].getAttribute("rate");
  price = elt.parentNode.parentNode.cells[col1].innerHTML;
  var newpriceVAT = price * (1 + (VAT / 100));
  newpriceVAT = Math.round(newpriceVAT*1000000)/1000000; /* round to 6 decimals */
  elt.parentNode.parentNode.cells[col2].innerHTML = newpriceVAT;
}

function stockflags_change(elt)
{ reg_change(elt);
  var row = elt.parentNode.parentNode;
  var rowno = row.childNodes[0].id.substr(4);
  var warehousespan = document.getElementById("stockspan"+rowno);
  if(warehousespan) /* it is not there if the initial value was "ASM with warehousing" */
  { if(elt.value == 3)
	  warehousespan.style.display = "inline";
    else
	  warehousespan.style.display = "none";	
  }
}

/* change stockflags in the mass edit menu */
function stockflags_mass_change(elt)
{ var warehousespan = document.getElementById("stockspan");
  if(elt.value == 3)
	  warehousespan.style.display = "block";
  else
	  warehousespan.style.display = "none";	
}

function getColumn(name)
{ var tbl = document.getElementById("Maintable");
  var len = tbl.tHead.rows[0].cells.length;
  for(var i=0;i<len; i++)
  { if(tbl.tHead.rows[0].cells[i].firstChild.getAttribute("fieldname") == name)
	  return i;
  }
  alert("Not Found "+name);
}

/* remove unmatched closing tags; close unmatched opening tags (like <div> and <b>) */
/* every time you submit a field that can contain html tags it is moved through this function */
function tidy_html(html) {
    var d = document.createElement('div');
    d.innerHTML = html;
    return d.innerHTML;
}

/* clean string and give warnings */
function check_string(myelt,taboos)
{ var patt = new RegExp( "[" + taboos + "]", "g" );
  if(myelt.value.search(patt) == -1)
    return true;
  else
  { alert("The following characters are not allowed and have been removed or replaced: "+taboos+". HTML tags have been removed as a whole.");
	var patt2 = new RegExp('<[^>]*>', 'g'); /* first remove html tags */
    myelt.value = myelt.value.replace(patt2,"");
	var patt2 = new RegExp(';', 'g'); /* replace ";" with "." */
    myelt.value = myelt.value.replace(patt2,".");
	var patt2 = new RegExp('{', 'g'); /* replace "{" with "[" */
    myelt.value = myelt.value.replace(patt2,"[");
	var patt2 = new RegExp('}', 'g'); /* replace "}" with "]" */
    myelt.value = myelt.value.replace(patt2,"]");
    myelt.value = myelt.value.replace(patt,""); /* then remove the rest of the forbidden chars */
    return false;
  }
}

function feature_change_event(evt)
{ if(evt.key == "Tab")
	return;
  feature_change(evt.target);
}

/* take care that only one option is active at the same time */
function feature_change(elt)
{ var myform = elt;
  while (myform.nodeName != "FORM" && myform.parentNode) // find form (either massform or Mainform) 
  { myform = myform.parentNode;
  }
  if(!myform) alert("error finding form");
  if(elt.name.indexOf("_sel")>0)
  { var input = elt.name.replace("_sel","");
	myform[input].value="";
  }
  else
  { if(!check_string(elt,"<>;=#{}"))
      return;
    var patt1=/([0-9]*)$/;
    var sel = elt.name.replace(patt1, "_sel$1");
	if((elt.value != "") && myform[sel])  /* as the feature_change_event test seemed not to catch all tabs this (!="") is a second test to prevent tabs resetting the select */
	  myform[sel].selectedIndex = 0;
  }
  if(myform.name == "Mainform")
    reg_change(elt);
}

var tabchanged = 0;
function reg_change(elt)	/* register changed row so that it will be colored and only changed rows will be submitted */
{ var elts = Array();
  elts[0] = elt;
  elts[1] = elts[0].parentNode;
  var i=1;
  while (elts[i] && (!elts[i].name || (elts[i].name != 'Mainform')))
  { elts[i+1] = elts[i].parentNode;
	i++;
  }
  elts[i-4].cells[0].setAttribute("changed", "1");
  elts[i-4].style.backgroundColor="#DDD";
  tabchanged = 1;
}

function reg_unchange(num, updateblock)	/* change status of row back to unchanged after it has been submitted */
{ var elt = document.getElementById('trid'+num);
  var row = elt.parentNode;
  row.cells[0].setAttribute("changed", "0");
  row.style.backgroundColor="#AAF";
  var elt = eval('Mainform.discount_count'+num);
  for (var field in updateblock)
  { if(field == "discounts")
    { for (var idx in updateblock[field]) 
	  { var elu = eval('Mainform.discount_id'+idx+'s'+num);
	    elu.value = updateblock[field][idx];
	    var elu = eval('Mainform.discount_status'+idx+'s'+num);
        elu.value = "update";
		var elv = eval('Mainform.discount_shop'+idx+'s'+num);
	    var txt = "";
	    if(elv.value==0)
	      txt = "all";
	    else
	      txt = elv.value;
	    elv.type = "hidden";
	    var elv = eval('Mainform.discount_attribute'+idx+'s'+num);
	    if(elv.value==0)
	      txt += " all";
	    else
	      txt += " "+elv.value;
	    elv.parentNode.insertBefore(document.createTextNode(txt) ,elv);
	    elv.type = "hidden";
	  }
	}
  }
}


parts_stat = 0;
desc_stat = 0;
trioflag = false; /* check that only one of price, priceVAT and VAT is editable at a time */
function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp, tmp2, val, checked;
  var advanced_stock = has_combinations = change_stockflags = has_catalogue_rules = false;
  if(val == '0') /* hide */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++)
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='none';
  }
  if((val == '1') || (val=='2') || (val=='3') || (val=='4')) /* 1 = show */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='table-cell'; /* undo display="none" */
  }
  if((val=='2') ||(val == '3') || (val=='4')) /* 2 = edit */
  { tab = document.getElementById('Maintable');
    var tblEl = document.getElementById(id);
    field = tab.tHead.rows[0].cells[fieldno].children[0].getAttribute("fieldname");
    if((trioflag == true) && ((field == "price") || (field == "VAT") || (field == "priceVAT") || (field == "unitPrice") || (field == "discount")))
    { alert("You may edit only one of the following fields at a time: price, VAT, priceVAT, unitPrice");
      return;
    }
    if((field == "price") || (field == "VAT") || (field == "priceVAT") || (field == "unitPrice") || (field == "discount"))
      trioflag = true;
	else if (field == "image")
	{ var imgsuffix = '';
	}
    else if(field=="stockflags") 
  	  change_stockflags = true;
    if((field=="image") || (field=="virtualp"))
	{ Mainform.enctype = 'multipart/form-data';
	  rowform.enctype = 'multipart/form-data';
	}
    if((field=="carrier") && (prestools_notbought.indexOf("carrier") !== -1))
		balert("In Prestools Free the carrier field is in demo mode and your changes cannot be saved.\nFor full functionality buy Prestools Professional or the specific plugin at www.Prestools.com.");
    else if((field=="discounts") && (prestools_notbought.indexOf("discounts") !== -1))
		balert("In Prestools Free the Special Prices/Discounts field is in demo mode and your changes cannot be saved.\nFor full functionality buy Prestools Professional or the specific plugin at www.Prestools.com.");
    else if((field=="combinations") && (val == 3) && (prestools_notbought.indexOf("embedded attribute") !== -1))
		balert("In Prestools Free the embedded attribute quantity field is in demo mode and your changes cannot be saved.\nFor full functionality buy Prestools Professional or the specific plugin at www.Prestools.com.");
    else if((field=="features") && (prestools_notbought.indexOf("features") !== -1))
		balert("In Prestools Free the Features field is in demo mode and your changes cannot be saved.\nFor full functionality buy Prestools Professional or the specific plugin at www.Prestools.com.");
    else if((field=="supplier") && (prestools_notbought.indexOf("supplier") !== -1))
		balert("In Prestools Free the Suppliers field is in demo mode and your changes cannot be saved.\nFor full functionality buy Prestools Professional or the specific plugin at www.Prestools.com.");
    else if((field=="tags") && (prestools_notbought.indexOf("tags") !== -1))
		balert("In Prestools Free the Tags field is in demo mode and your changes cannot be saved.\nFor full functionality buy Prestools Professional or the specific plugin at www.Prestools.com.");

    for(var i=0; i<tblEl.rows.length; i++)
    { if(!tblEl.rows[i].cells[fieldno]) continue;
	  tmp = tblEl.rows[i].cells[fieldno].innerHTML;
      tmp2 = tmp.replace("'","\'");
      row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10); /* fieldname id_product7 => 7 */
	  
	  /* fields in alphabetical order: not mentioned = default */
	  if((field=="accessories") || (field == "name"))
      { tmp2 = tmp.replace(/<[^>]*>/g,'');
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2.replace(/"/g, '&quot;')+'" class="extrawide" onchange="reg_change(this);" />';
	  }
      else if((field=="active") || (field=="on_sale") || (field=="online_only"))
	  { if(tmp==1) checked="checked"; else checked="";
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input type=hidden name="'+field+row+'" id="'+field+row+'" value="0" /><input type=checkbox name="'+field+row+'" id="'+field+row+'" onchange="reg_change(this);" value="1" '+checked+' />';
	  }
	  else if(field=="attachmnts") 
      { tmp = tblEl.rows[i].cells[fieldno].getElementsByTagName("a");
	    var atids = [];
		for(var j=0; j< tmp.length; j++)
		  atids[j] = tmp[j].title;
	    tblEl.rows[i].cells[fieldno].innerHTML = (attachmentblock0.replace(/CQX/g, row))+attachmentblock1+(attachmentblock2.replace(/CQX/g, row));
	    fillAttachments(row,atids);
	  }
      else if(field=="availorder")
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="availorder'+row+'" onchange="reg_change(this);">'+availorderblock.replace('>'+tmp.replace('&amp;','&')+'<', ' selected>'+tmp+'<');
	  }
	  else if(field=="carrier") 
      { var cars = new Array();
	    var tab = document.getElementById('carriers'+row);
	    if(tab)
		{ for(var y=0; y<tab.rows.length; y++)
		  {	cars[y] = tab.rows[y].cells[0].id;
		  }
		}
	    tblEl.rows[i].cells[fieldno].innerHTML = (carrierblock0.replace(/CQX/g, row))+carrierblock1+(carrierblock2.replace(/CQX/g, row));
	    fillCarriers(row,cars);
	  }
	  else if(field=="category") 
      { tblEl.rows[i].cells[fieldno].innerHTML = (categoryblock0.replace(/CQX/g, row))+categoryblock1+(categoryblock2.replace(/CQX/g, row));
	    fillCategories(row,tmp);
	  }
      else if(field=="combinations")
	  { if(tblEl.rows[i].cells[fieldno].children[0].tagName == "TABLE")
		{ tblEl.rows[i].cells[fieldno].children[0].style.display = "table";
	      if(val==3) /* if quantity editable */
		  { var atts = [];
		    var warehouseflag = tblEl.rows[i].cells[fieldno].children[0].getAttribute("data-wh");
			if(warehouseflag == 0)
			{ var mytable = tblEl.rows[i].cells[fieldno].children[0];
			  for(var y=0; y<mytable.rows.length; y++)
			  { var pa_id = mytable.rows[y].id.substring(2);
				var pa_quantity = mytable.rows[y].cells[1].innerHTML;
				mytable.rows[y].cells[1].innerHTML = "<input name='combination"+pa_id+"quantity"+row+"' value="+pa_quantity+">";
				atts.push(pa_id);
			  }
			  tblEl.rows[i].cells[fieldno].innerHTML += "<input type=hidden name=combinations"+row+" value='"+atts.join()+"'>";
			}
		  }
		}
	  }
      else if(field=="condition")
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="condition'+row+'" onchange="reg_change(this)">'+conditionblock.replace('>'+tmp+'<', ' selected >'+tmp+'<');
      }
      else if((field=="description") || (field=="description_short"))
      { tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" id="'+field+row+'" rows="4" cols="35" onchange="reg_change(this);">'+tmp+'</textarea>';
		tblEl.rows[i].cells[fieldno].innerHTML += '<div class="TinyLine"><a href="#" onclick="useTinyMCE(this, \''+field+row+'\'); return false;">TinyMCE</a>&nbsp;<a href="#" onclick="useTinyMCE2(this, \''+field+row+'\'); return false;">TinyMCE-deluxe</a></div>';
      }
	  else if(field=="discount")
      { /* 					0			1				2		3		4			5		6			7			8			9	 			10			11	  12	*/
	    /* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_tax, reduction_type, from, to */
		var tab = document.getElementById('discount'+row); /* this is the table */
	    if(tab)
		{ blob = "";
	      var z = 0;
		  for(var y=0; y<tab.rows.length; y++)
		  { if(tab.rows[y].getAttribute("rule")== 0)
			{ blob += "<div>";
			  var newprices = tab.rows[y].cells[13].innerHTML.split('/ ');
		      blob += fill_discount(row,z,tab.rows[y].getAttribute("specid"),"update",tab.rows[y].cells[0].innerHTML,tab.rows[y].cells[1].innerHTML,tab.rows[y].cells[2].innerHTML,tab.rows[y].cells[3].innerHTML,tab.rows[y].cells[4].innerHTML,tab.rows[y].cells[5].innerHTML,tab.rows[y].cells[6].innerHTML,tab.rows[y].cells[7].innerHTML,tab.rows[y].cells[8].innerHTML,tab.rows[y].cells[9].innerHTML,tab.rows[y].cells[10].innerHTML,tab.rows[y].cells[11].innerHTML,tab.rows[y].cells[12].innerHTML,newprices[0],newprices[1]);
		      blob += "</div>";
			  tab.rows[y].innerHTML = "";
			  z++;
			}
			else
				has_catalogue_rules = true;
		  }
		  var blob = '<input type=hidden name="discount_count'+row+'" value="'+z+'">' + blob;
		  blob += '<a href="#" onclick="return add_discount('+row+');" class="TinyLine" id="discount_adder'+row+'">Add discount rule</a>';
		  tblEl.rows[i].cells[fieldno].innerHTML += blob;
		}
	  }
      else if(field=="image")
      { if((val==2)||(val==3))
		{ if(tblEl.rows[i].cells[fieldno].innerHTML=='X') continue;
		  /* note that the title looks like "33;|22|television|33|radio" with the first figure being the default image id */
		  var tmp = tblEl.rows[i].cells[fieldno].firstChild.title;
		  if(imgwidth == 0) /* check the size only once */
		  { imgwidth = tblEl.rows[i].cells[fieldno].firstChild.firstChild.style.width;
		  }
		  if(imgsuffix == '')
		  { imgsuffix = tblEl.rows[i].cells[fieldno].firstChild.firstChild.src;
		    imgsuffix = imgsuffix.match(/-[^-]*$/);
		  }
		  var parts = tmp.split(';');
		  var imparts = parts[1].split('|');
		  var str = '<table><tr>';

		  for (var j = 1; j < imparts.length; j++)  
		  { if((j%12 == 1) && (j>1))
			  str += '</tr><tr>';
		    str += '<td><a href=\"'+triplepath+'img/p'+getpath(imparts[j])+'/'+imparts[j]+'.jpg\" target=\"_blank\" ><img';
		    if(imparts[j] == parts[0]) /* default image gets extra border */
			  str += ' border=3';
		    else 
			  if(val==2) continue; /* with left button show only cover images */
		    str += ' src=\"'+triplepath+'img/p'+getpath(imparts[j])+'/'+imparts[j]+imgsuffix+'\" width=\"'+imgwidth+'\" height=\"auto\" /></a>';
		    if(j==1)
			  var imagelist = imparts[j];
		    else
			  imagelist += "-"+imparts[j];
		    j++;
		    if(val==4) /* if add/remove pictures */
		      str += '<br><input type=checkbox name="image_delete'+imparts[j-1]+'s'+row+'" onchange="image_delete(this);">Delete';
		    else
		      str += '<br><textarea name="image_legend'+imparts[j-1]+'s'+row+'" style="width:'+imgwidth+'; height:13px" onchange="reg_change(this);">'+imparts[j]+'</textarea>';
		    if(j==(imparts.length-1)) /* store image list with last image block */
			  str += '<input type=hidden name="image_list'+row+'" value="'+imagelist+'">';
		    str += '</td>';
		  }
		  str += '</tr></table>';
		  var id_product = eval('Mainform.id_product'+row+'.value');
		  str += '<center><a href="image-edit.php?id_product='+id_product+'&id_shop='+id_shop+'" title="Edit images in separate window" target="_blank" class="TinyLine" >edit</a></center>';
 		  tblEl.rows[i].cells[fieldno].innerHTML = str;
		}
		else if(val == 4) /* add/remove images */
		{ str = '<td><button>Add Files</button>';
		  str += '<form class="mupload--form"><input type="file" accept="image/*" class="mupload--input" multiple></form>';
		  str += '<form action="submit.php" method="post" class="mupload--frows" enctype="application/x-www-form-urlencoded"></form>';

		  str += '</td>';
		  tblEl.rows[i].cells[fieldno].innerHTML = str;
		}
	  }
      else if(field=="manufacturer") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="manufacturer'+row+'" onchange="reg_change(this);">'+manufacturerblock.replace('>'+tmp.replace('&amp;','&')+'<', ' selected>'+tmp+'<');
      }
	  else if(field=="meta_description")
        tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" rows="4" cols="35" onchange="reg_change(this);">'+tmp+'</textarea>';
	  /* field == on_sale / field = online_only: see active */
	  /* field == name: see accessories */
	  else if(field=="out_of_stock")
	  { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="out_of_stock'+row+'" onchange="reg_change(this)">'+out_of_stockblock.replace('>'+tmp, ' selected>'+tmp);
      }
      else if(field=="price")
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="price_change(this)" />';
      else if(field=="priceVAT") 
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="priceVAT_change(this)" /><input type=hidden name="price'+row+'" value="'+tblEl.rows[i].cells[fieldno-2].innerHTML+'">';
      else if(field=="pack_stock_type")
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="pack_stock_type'+row+'" onchange="reg_change(this)">'+pack_stock_typeblock.replace('>'+tmp+'<', ' selected >'+tmp+'<');
      }	 
	  else if(field=="quantity")
	  { if(tblEl.rows[i].cells[fieldno].style.backgroundColor != "")
	    { if(tblEl.rows[i].cells[fieldno].style.backgroundColor == "yellow")
		    advanced_stock = true;
		  else /* combinations */
			has_combinations = true;
		  continue;
		}
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="reg_change(this);" />';
	  }
      else if(field=="shopz")
      { var shopz = tmp2.split(",");
		var myshops = shop_ids.split(","); 
		tmp = '';
		for(var x=0; x<myshops.length; x++)
		{ var checked = '';
		  if(inArray(myshops[x],shopz))
			 checked = 'checked';
		  tmp += '<input type="checkbox" name="shopz'+row+'[]" value='+myshops[x]+' '+checked+' onchange="reg_change(this);"> '+myshops[x]+'<br>';
        }
		tblEl.rows[i].cells[fieldno].innerHTML = tmp;
      }
	  
      else if(field=="stockflags") 
      { var warning = "For packs this field cannot be edited";
        if (tmp.substring(0,warning.length) != warning)
		{ tblEl.rows[i].cells[fieldno].innerHTML = '<select name="stockflags'+row+'" onchange="stockflags_change(this);">'+stockflagsblock.replace('>'+tmp.replace('&amp;','&')+'<', ' selected>'+tmp+'<');
		  if(tmp != "ASM with Warehousing")
		  { if (tblEl.rows[i].cells[fieldno].getAttribute("haswarehouses") == "0")
			  tblEl.rows[i].cells[fieldno].innerHTML += '<br><span id=stockspan'+row+' style="display:none">Move stock of shop(s) to warehouse:<br><select name=stockflags_warehouse'+row+'>'+warehouseblock+'</span>';
			else	/* with the stock_reinstate flag we exclude the case that the previous state was also ASM with WH */
			  tblEl.rows[i].cells[fieldno].innerHTML += '<br><input type=hidden name="stock_reinstate'+row+'" value="yes"><span id=stockspan'+row+' style="display:none">Existing warehouses will be reinstated</span>';
		  }
	    }
	  }
      else if(field=="supplier") 
      { var trow = document.getElementById("trid"+row).parentNode;
  	    var sups = trow.cells[fieldno].getAttribute("sups");
	    var attrs = trow.cells[fieldno].getAttribute("attrs");
	    var default_supplier = trow.cells[fieldno].getAttribute("def_supplier");		
	  
		var blob = '<input type=hidden name="supplier_attribs'+row+'" value="'+attrs+'">';
		blob += '<input type=hidden name="old_suppliers'+row+'" value="'+sups+'">';
	    blob += (supplierblock0.replace(/CQX/g, row))+supplierblock1+(supplierblock2.replace(/CQX/g, row));

	    var attributes = attrs.split(",");
		
		for(var a=0; a< attributes.length; a++)
		{ var tab = document.getElementById("suppliers"+attributes[a]+"s"+row);
		  blob += '<table id="suppliertable'+attributes[a]+'s'+row+'" class="suppliertable" title="'+tab.title+'">';
		  if(tab)
		  { var first = 0;
	        for(var y=0; y<tab.rows.length; y++)
		    { blob += '<tr><td>'+tab.rows[y].cells[0].innerHTML+'</td>';
			  blob += '<td><input name="supplier_reference'+attributes[a]+'t'+tab.rows[y].title+'s'+row+'" value="'+tab.rows[y].cells[1].innerHTML.replace(/"/g, '&quot;')+'" onchange="reg_change(this);"></td>';
			  blob += '<td><input name="supplier_price'+attributes[a]+'t'+tab.rows[y].title+'s'+row+'" value="'+tab.rows[y].cells[2].innerHTML.replace(/"/g, '&quot;')+'" onchange="reg_change(this);"></td>';
			  blob += '<td><select name="supplier_currency'+attributes[a]+'t'+tab.rows[y].title+'s'+row+'" onchange="reg_change(this);">'+currencyblock.replace(">"+tab.rows[y].cells[3].innerHTML+"<"," selected>"+tab.rows[y].cells[3].innerHTML+"<")+'</select></td>';
			  if(first++ == 0) 
			  { blob += '<td rowspan="'+tab.rows.length+'">'+tab.rows[y].cells[4].innerHTML+'</td>';
			  }
			  blob += '</tr>';
			}
		  }
		  blob += '</table>';
		}
		trow.cells[fieldno].innerHTML = blob;
		var list = document.getElementById('supplierlist'+row);
		var sel = document.getElementById('suppliersel'+row);
		var suppliers = sups.split(",");
		for (var x=0; x< suppliers.length; x++)
		{ for(var y=0; y< list.length; y++)
		  { if(list.options[y].value == suppliers[x])
			{ list.selectedIndex = y;
			  var selrow = Addsupplier(row,0);
			  if(suppliers[x] == default_supplier)
				  sel.options[selrow].className = "defcat";
			}	
		  }
		}
		var def_supplier_fld = eval("document.Mainform.supplier_default"+row);
		def_supplier_fld.value = '0'; /* '0' stands for unchanged */
	  }
      else if(field=="tags")
      { tmp = tmp.replace(/<\/?nobr>/gi, "");
	    tmp = tmp.replace(/\<br>/gi, ",");
	    tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" rows="4" cols="25" onchange="reg_change(this);">'+tmp+'</textarea>';
	  }
	  else if(field=="unitPrice") 
	  { var col = getColumn("price");
  /* note that unitPrice is a calculated value. The database field is unit_price_ratio. To calculate that we need the price field. */
        tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'"  onchange="reg_change(this);" /><input type=hidden name="price'+row+'" value="'+tblEl.rows[i].cells[col].innerHTML+'">';
      }
      else if(field=="VAT")
      { tmp = tblEl.rows[i].cells[fieldno].getAttribute("idx");
        tblEl.rows[i].cells[fieldno].innerHTML = '<select name="VAT'+row+'" onchange="VAT_change(this)">'+taxblock.replace('value="'+tmp+'"', 'value="'+tmp+'" selected');
      }
	  else if(field=="virtualp")
      { var mycell = tblEl.rows[i].cells[fieldno];
		if(mycell.style.backgroundColor != "") continue; /* prods with combinations cannot be virtual */
		var myrow = mycell.childNodes[0].rows[1];		
		var filecell = myrow.cells[1].innerHTML;
		if(myrow.cells[1].innerHTML != '') /* if there is a file */
		{ filecell += ' <nobr><input type="hidden" name="dl_delete'+row+'" id="dl_delete'+row+'" value="0">';
		  filecell += '<input type=checkbox name="dl_delete'+row+'" onchange="virtual_delete(this);" value="1"> delete</nobr><br><span id="dl_span'+row+'" style="display:none">';
		  myrow.cells[2].style.display = "table-cell";
		  mycell.childNodes[0].rows[0].cells[2].style.display = "table-cell"; /* the "name" header */
		}
	    myrow.cells[2].innerHTML = '<textarea name="dl_display_filename'+row+'" cols=18 rows=1 onchange="reg_change(this);" />'+myrow.cells[2].innerHTML+'</textarea>';
		myrow.cells[3].innerHTML = '<input name="date_expiration'+row+'" value="'+myrow.cells[3].innerHTML+'" size=8 onchange="reg_change(this);" />';
		myrow.cells[4].innerHTML = '<input name="nb_days_accessible'+row+'" value="'+myrow.cells[4].innerHTML+'"  onchange="reg_change(this);" style="width:45px;" />';
		myrow.cells[5].innerHTML = '<input name="nb_downloadable'+row+'" value="'+myrow.cells[5].innerHTML+'"  onchange="reg_change(this);" style="width:45px;" />';
		filecell += '<input type="file" name="dl_upload'+row+'" onchange="upload_change(this);">';
		if(myrow.cells[1].innerHTML != '') filecell += '</span>';
		myrow.cells[1].innerHTML = filecell;
		if(myrow.cells[0].innerHTML==1) checked="checked"; else checked="";
	    myrow.cells[0].innerHTML = '<input type=hidden name="is_virtual'+row+'" value="0" />';
		myrow.cells[0].innerHTML += '<input type=checkbox name="is_virtual'+row+'" id="is_virtual'+row+'" onchange="virtual_change(this);" value="1" '+checked+' />';
      }
      else if(field=="visibility")
      { tblEl.rows[i].cells[fieldno].innerHTML = '<select name="visibility'+row+'" onchange="reg_change(this)">'+visibilityblock.replace('>'+tmp+'<', ' selected >'+tmp+'<');
      }
	  else if((pos = featurelist.indexOf(field)) != -1) /* features */
      { if(tmp.match("<b>")) 
		{ custom = 0; /* "custom" = in dropdown select */
		  tmp = tmp.replace(/<[^>]*>/g,"");
		  tmp3 = "";
		}
		else
		{ custom=1;
		  tmp3 = tmp2;
		}
		fieldname = "feature"+featurekeys[pos]+"field";
		if((val == 2) || (val == 4)) /* note that we will only use it for val==4 when there is no-custom content */
		  inserta = ' <input name="'+fieldname+row+'" value="'+tmp3.replace(/"/g, '&quot;')+'" onkeyup="feature_change_event(event);" />';
		else if(val == 3)
		  inserta = ' <textarea name="'+fieldname+row+'" id="'+fieldname+row+'" rows="4" cols="35" onkeyup="feature_change_event(event);">'+tmp3+'</textarea>';

	    if(featureblocks[pos] == "")
			tblEl.rows[i].cells[fieldno].innerHTML = inserta;
		else if ((custom == 0) || ((val==4) && (tmp2 == "")))
		{ if(val == 4) /* no input */
		    tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="reg_change(this)" >'+featureblocks[pos].replace('>'+tmp+'<', ' selected>'+tmp+'<')+'<input type=hidden name="'+fieldname+row+'" value="" />';
		  else
		    tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="feature_change(this)">'+featureblocks[pos].replace('>'+tmp+'<', ' selected>'+tmp+'<')+inserta;
		}
		else if (custom == 1)
		{  tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="feature_change(this)">'+featureblocks[pos]+inserta;
		}
		else // custom=-1 => field not set
		{  tblEl.rows[i].cells[fieldno].innerHTML = '<select name="'+fieldname+'_sel'+row+'" onchange="feature_change(this)">'+featureblocks[pos]+inserta;
		}
      }
      else
      { 
		tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2.replace(/"/g, '&quot;')+'" onchange="reg_change(this);" />';
	  }
    }
	/* now we adapt the switchblock (hide-show-edit); with "edit" hide and show are no longer avialable and we may add + and - for resizing the field */
	if((featurelist.indexOf(field) == -1) && (field != "image") && (field != "combinations")) /* if not a feature and not an image */
		var cell = elt.parentElement; /* td cell */
	else
		var cell = elt.parentElement.parentElement;
	tmp = cell.innerHTML.replace(/<br.*$/,'');  /* remove everything except for the field name */
	var blockfields = ["description","description_short","meta_description"];
	var linefields = ["meta_keywords","meta_title","name","ean","upc","discount"];	
	if(blockfields.indexOf(field) >= 0)
	{ TMCE_loadonce();
 	  cell.innerHTML = tmp+'<br>Edit<br><img src=minus.png title="make field less high" onclick="grow_textarea(\''+field+'\','+fieldno+', -1, 0);"><b>H</b><img src=plus.png title="make field higher" onclick="grow_textarea(\''+field+'\','+fieldno+', 1, 0);"><br><img src=minus.png title="make field less wide" onclick="grow_textarea(\''+field+'\','+fieldno+', 0, -7);"><b>W</b><img src=plus.png title="make field wider" onclick="grow_textarea(\''+field+'\','+fieldno+', 0, 7);">';	
	}
	else if(linefields.indexOf(field) >= 0)
	  cell.innerHTML = tmp+'<br>Edit<br><nobr><img src="minus.png" title="make field less wide" onclick="grow_input(\''+field+'\','+fieldno+', -7);"><b>W</b><img src="plus.png" title="make field wider" onclick="grow_input(\''+field+'\','+fieldno+', 7);"></nobr>';
	else if(field == "image")
 	  cell.innerHTML = tmp+'<br>Edit<br><img src=minus.png title="make field less high" onclick="grow_image(\''+field+'\','+fieldno+', -15, 0);"><b>H</b><img src=plus.png title="make field higher" onclick="grow_image(\''+field+'\','+fieldno+', 15, 0);"><br><img src=minus.png title="make field less wide" onclick="grow_image(\''+field+'\','+fieldno+', 0, -7);"><b>W</b><img src=plus.png title="make field wider" onclick="grow_image(\''+field+'\','+fieldno+', 0, 7);">';	
    else if ((typeof fieldname !== 'undefined') && (fieldname == "feature"+featurekeys[pos]+"field"))
	{ if(val == 2)
		cell.innerHTML = tmp+'<br>Edit<br><img src="minus.png" title="make field less wide" onclick="grow_input(\''+fieldname+'\','+fieldno+', -7);"><b>W</b><img src="plus.png" title="make field wider" onclick="grow_input(\''+fieldname+'\','+fieldno+', 7);">';	
	  else if(val == 3) /* val==3 */
		cell.innerHTML = tmp+'<br>Edit<br><img src=minus.png title="make field less high" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', -1, 0);"><b>H</b><img src=plus.png title="make field higher" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', 1, 0);"><br><img src=minus.png title="make field less wide" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', 0, -7);"><b>W</b><img src=plus.png title="make field wider" onclick="grow_textarea(\''+fieldname+'\','+fieldno+', 0, 7);">';	
	  else  /* val==4 */
		cell.innerHTML = tmp+'<br><br>Edit';	
	}
	else
	  cell.innerHTML = tmp+"<br><br>Edit";
  }
  var warning = document.getElementById("warning").innerHTML;
  if(advanced_stock)
    warning += "Quantity fields of products with warehousing - marked in yellow - cannot be changed.<br>";
  if(has_combinations)
    warning += "Quantity fields for products with combinations - marked in red - cannot be changed here.<br>";
  if(change_stockflags)
  { warning += "In order to automate changing stockflags in mass edit shortcuts and a reduction of flexibility compared to the PS back office was necessary.<br>";	
	warning += "Consult the manual for more information.<br>";	
  } 
  if(has_catalogue_rules)
  { warning += "Discount rules that cannot be changed result from catalogue rules.<br>";	 
  }
  var tmp = document.getElementById("warning");
  tmp.innerHTML = warning;
  return;
}

function virtual_change(elt)
{ reg_change(elt);
  var val = elt.checked;
  var setting = "none";
  if(val == 1)
	  setting = "table-cell";
  var tbl = elt.parentNode.parentNode.parentNode;
  
  for(var i=1; i<6; i++)
  { tbl.rows[0].cells[i].style.display = setting;
	tbl.rows[1].cells[i].style.display = setting;
  }	
}

function virtual_delete(elt)
{ reg_change(elt);
  var rownum = elt.name.substring(9);
  var myspan = document.getElementById("dl_span"+rownum)
  if(elt.checked)
	  myspan.style.display = "inline";
  else
	  myspan.style.display = "none";	  
}

function image_delete(elt)
{ reg_change(elt);
	
}

function upload_change(elt)
{ reg_change(elt);
  var rownum = elt.name.substring(9);
  var setting = "none";
  if(elt.value != "")
	  setting = "table-cell";
  var tbl = elt.parentNode.parentNode.parentNode.parentNode;
  
  for(var i=2; i<6; i++)
  { tbl.rows[0].cells[i].style.display = setting;
	tbl.rows[1].cells[i].style.display = setting;
  }
  if(elt.value != "") /* if there is a file */
  { var fld = eval('Mainform.dl_display_filename'+rownum);
    fld.value = elt.value.replace(/^.*[\\\/]/, '');
    var fld = eval('Mainform.date_expiration'+rownum);
    if(fld.value == "") fld.value = "0000-00-00";
    var fld = eval('Mainform.nb_days_accessible'+rownum);
    if(fld.value == "") fld.value = "0";
    var fld = eval('Mainform.nb_downloadable'+rownum);
    if(fld.value == "") fld.value = "0";
  }
}

/* the grow() functions handle the -W+ and -H+ buttons that appear in the hide-show-edit fields in edit mode */
function grow_image(field, fieldno, height, width)
{ var tblEl = document.getElementById("offTblBdy");
  var rows = -1, cols;
  var imagecol = getColumn("image");
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue;
    var images = tblEl.rows[i].cells[fieldno].getElementsByTagName("img");
	var textareas = tblEl.rows[i].cells[fieldno].getElementsByTagName("textarea");
	for(j=0; j<images.length; j++)
	{ var iwidth = images[j].width;
      iwidth += width;
	  if(iwidth < 45) iwidth = 45;
	  images[j].style.width = iwidth+"px";
	  textareas[j].style.width = iwidth+"px";

	  var iheight = textareas[j].style.height.replace("px","");
	  iheight = parseInt(iheight) + height;
	  if(iheight <12) iheight = 12; 
	  textareas[j].style.height = iheight+"px";		
	}
  }
}

function grow_input(field, fieldno, width)
{ var tblEl = document.getElementById("offTblBdy");
  var size = -1;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10); /* "id_product" is 10 chars long; so this gives row number */
	if(field != "discount")
	{ myfield = eval("Mainform."+field+row);
      if(size == -1)  /* this way we need to read only onc */
	  { size = myfield.size;
	    size += width;
	    if(size < 10) size = 10;
	  }
	  myfield.size = size;
	}
	else /* discount */
	{ var flds = tblEl.rows[i].cells[fieldno].getElementsByTagName("input");
	  var item;
	  for(var j=0; j<flds.length; j++)
	  { if(flds[j].getAttribute("type") == "hidden")
		  continue;
	    fldwidth = flds[j].style.width;
		fldwidth = fldwidth.substring(0,fldwidth.length-2);
		
		if(width > 0)
	      fldwidth = parseInt(fldwidth)+10;
		else 
	      fldwidth = parseInt(fldwidth)-10;
	    if(fldwidth < 10) fldwidth = 10;
	    flds[j].style.width = fldwidth+"px";
	  }
	}
  }
}

function grow_feature(field, fieldno, width)
{ var tblEl = document.getElementById("offTblBdy");
  var size = -1;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10);  /* id_product is 10 chars long */
	myfield = eval("Mainform."+field+row);
    if(size == -1)
	{ size = myfield.size;
	  size += width;
	  if(size < 10) size = 10;
	}
	myfield.size = size;
  }
}

function grow_textarea(field, fieldno, height, width)
{ var tblEl = document.getElementById("offTblBdy");
  var rows = -1, cols;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(10);  /* id_product is 10 chars long */
	myfield = eval("Mainform."+field+row);
    if(rows == -1)
	{ rows = myfield.rows;
	  cols = myfield.cols;
	  rows += height;
	  cols += width;
	  if(cols < 10) cols = 10;
	  if(rows < 2) rows = 2;	  
	}
	myfield.cols = cols;
	myfield.rows = rows;	
  }
}

function balert(txt)
{ var warning = document.getElementById("warning");
  warning.innerHTML = warning.innerHTML+txt.replace('www.Prestools.com','<a href="http://www.prestools.com/">www.Prestools.com</a>');
  alert(txt);
}

function add_discount(row)
{ var count_root = eval('Mainform.discount_count'+row);
  var dcount = parseInt(count_root.value);
/* function fill_discount(row,entry,id,status, shop,attribute,currency,country,group,customer,price,quantity,reduction,reductiontax,reductiontype,from,to,newpricex,newpricei)             */
  var blob = fill_discount(row,dcount,"","new","",	"",			"",		"0",	"0",	"0",	"",		"1",	"",			"1",		"",			"","",   0,         0);
  var new_div = document.createElement('div');
  new_div.innerHTML = blob;
  var adder = document.getElementById("discount_adder"+row);
  adder.parentNode.insertBefore(new_div,adder);
  count_root.value = dcount+1;
  return false;
}

/* clicking on the pencil calls this function to create the dhtml window: copy all the fields from the main window */
function edit_discount(row, entry)
{ var changed = 0;
  var status = eval('Mainform.discount_status'+entry+'s'+row+'.value');
  var shop = eval('Mainform.discount_shop'+entry+'s'+row+'.value');
  var currency = eval('Mainform.discount_currency'+entry+'s'+row+'.value');
  var group = eval('Mainform.discount_group'+entry+'s'+row+'.value');
  var country = eval('Mainform.discount_country'+entry+'s'+row+'.value');
  
  var blob = '<form name="dhform"><input type=hidden name=row value="'+row+'"><input type=hidden name=entry value="'+entry+'">';
  	blob += '<input type=hidden name="discount_status" value="'+status+'">';	
  	blob += '<input type=hidden name="discount_id" value="'+eval('Mainform.discount_id'+entry+'s'+row+'.value')+'">';			
	blob += '<table id="discount_table" cellpadding="2"';
	blob += '<tr><td><b>Shop id</b></td>';
	if(status == "update")
	{	blob += '<td><input type=hidden name="discount_shop" value="'+eval('Mainform.discount_shop'+entry+'s'+row+'.value')+'">';
		if(shop == "") blob += 'all</td></tr>';
		else blob+=''+shop+'</td></tr>';
		blob += '<tr><td><b>Attribute</b></td><td><input type=hidden name="discount_attribute" value="'+eval('Mainform.discount_attribute'+entry+'s'+row+'.value')+'">';
	}
	else /* insert */
	{	blob += '<td><select name="discount_shop" onchange="changed = 1;">';
		blob += '<option value="0">All</option>'+(((shop == "") || (shop == 0))? shopblock : shopblock.replace(">"+shop+"-", " selected>"+shop+"-"))+'</select></td></tr>';
		blob += '<tr><td><b>Attribute</b></td><td><input name="discount_attribute" value="'+eval('Mainform.discount_attribute'+entry+'s'+row+'.value')+'" onchange="changed = 1;"></td></tr>';
	}
	
	blob += '<tr><td><b>Currency</b></td>';
	blob += '<td><select name="discount_currency" onchange="changed = 1;">';
	blob += '<option value="0">All</option>'+((currency == "")? currencyblock : currencyblock.replace(">"+currency+"<", " selected>"+currency+"<"))+'</select></td></tr>';

	blob += '<tr><td><b>Country</b></td>';
	blob += '<td><select name="discount_country" onchange="changed = 1;">';
	blob += '<option value="0">All</option>'+((country == "")? countryblock : countryblock.replace(">"+country+"-", " selected>"+country+"-"))+'</select></td></tr>';
	
	blob += '<tr><td><b>Group</b></td>';
	blob += '<td><select name="discount_group" onchange="changed = 1;">';
	blob += '<option value="0">All</option>'+((group == "")? groupblock : groupblock.replace(">"+group+"-", " selected>"+group+"-"))+'</select></td></tr>';

	blob += '<tr><td><b>Customer id</b></td><td><input name="discount_customer" value="'+eval('Mainform.discount_customer'+entry+'s'+row+'.value')+'" onchange="changed = 1;"> &nbsp; 0=all customers</td></tr>';
	
	blob += '<tr><td><b>Price</b></td><td><input name="discount_price" value="'+eval('Mainform.discount_price'+entry+'s'+row+'.value')+'" onchange="changed = 1; discount_change(this,0,0);" style="width:70px"> &nbsp; From price ex Vat. Leave empty when equal to normal price.</td></tr>';
	blob += '<tr><td><b>Quantity</b></td><td><input name="discount_quantity" value="'+eval('Mainform.discount_quantity'+entry+'s'+row+'.value')+'" onchange="changed = 1;"> &nbsp; Threshold for reduction.</td></tr>';
	blob += '<tr><td><b>Reduction</b></td><td><input name="discount_reduction" value="'+eval('Mainform.discount_reduction'+entry+'s'+row+'.value')+'" onchange="changed = 1; discount_change(this,0,0);"></td></tr>';
	var reductiontax = eval('Mainform.discount_reductiontax'+entry+'s'+row);
	blob += '<tr><td><b>Red. tax</b></td><td><select name="discount_reductiontax" onchange="changed = 1; discount_change(this,0,0);">';
	if(prestashop_version >= "1.6.0.11")	/* for PS >= 1.6.0.11 */
	{ if(reductiontax.value == 1)
	     blob += '<option value=0>excl tax</option><option value=1 selected>incl tax</option>';
	  else
	     blob += '<option value=0 selected>excl tax</option><option value=1>incl tax</option>';
	}
	else
	   blob += '<option value=1>incl tax</option>';		
	blob += '</select> &nbsp; only relevant with amounts and PS > 1.6.0.11</td></tr>';	
	blob += '<td><b>Red. type</b></td><td><select name="discount_reductiontype" onchange="changed = 1; discount_change(this,0,0);">';
    if(eval('Mainform.discount_reductiontype'+entry+'s'+row+'.selectedIndex') == 1)
	   blob += '<option>amt</option><option selected>pct</option>';
	else
	   blob += '<option selected>amt</option><option>pct</option>';
	blob += '</select></td></tr>';
	blob += '<tr><td><nobr><b>From date</b></nobr></td><td><input name="discount_from" value="'+eval('Mainform.discount_from'+entry+'s'+row+'.value')+'" class="datum" onchange="changed = 1;"> &nbsp; format: yyyy-mm-dd</td></tr>';
	blob += '<tr><td><b>To date</b></td><td><input name="discount_to" value="'+eval('Mainform.discount_to'+entry+'s'+row+'.value')+'" class="datum" onchange="changed = 1;"> &nbsp; format: yyyy-mm-dd</td></tr>';
    var newpricex_fld = document.getElementById("discount_newprice_excl"+entry+"s"+row);
    var newpricei_fld = document.getElementById("discount_newprice_incl"+entry+"s"+row);
	blob += '<tr><td><b>New Price</b></td><td><input id="discount_newprice_excl" value="'+newpricex_fld.value+'" onchange="discount_change(this,0,0)" style="width:60px;" class="calculated"> Excl';
	blob += ' &nbsp; <input id="discount_newprice_incl" value="'+newpricei_fld.value+'" onchange="discount_change(this,0,0)" style="width:60px;" class="calculated"> Incl VAT - (calculated values)</td></tr>';
	blob += '<tr><td></td><td align="right"><input type=button value="submit" onclick="submit_dh_discount()"></td></tr></table></form>'; 
    googlewin=dhtmlwindow.open("Edit_discount", "inline", blob, "Edit discount", "width=580px,height=425px,resize=1,scrolling=1,center=1", "recal");
  return false;
}

function submit_dh_discount()	/* submit dhtml window and enter data in main page */
{ /*					row				entry				id					status					shop			attribute			*/
  var currency = dhform.discount_currency.options[dhform.discount_currency.selectedIndex].text;
  var country = dhform.discount_country.options[dhform.discount_country.selectedIndex].text;
  country = country.substring(0,country.indexOf('-'));
  var group = dhform.discount_group.options[dhform.discount_group.selectedIndex].text;
  group = group.substring(0,group.indexOf('-'));
  var reductiontype = dhform.discount_reductiontype.options[dhform.discount_reductiontype.selectedIndex].text;
  var reductiontax = dhform.discount_reductiontax.options[dhform.discount_reductiontax.selectedIndex].value;
  var newpricex_fld = document.getElementById("discount_newprice_excl");
  var newpricei_fld = document.getElementById("discount_newprice_incl");
  var blob = fill_discount(dhform.row.value,dhform.entry.value,dhform.discount_id.value,dhform.discount_status.value,dhform.discount_shop.value,dhform.discount_attribute.value,currency,country,group,dhform.discount_customer.value,dhform.discount_price.value,dhform.discount_quantity.value,dhform.discount_reduction.value,reductiontax,reductiontype,dhform.discount_from.value,dhform.discount_to.value,newpricex_fld.value,newpricei_fld.value);
  var eltname = 'discount_table'+dhform.entry.value+'s'+dhform.row.value;
  var target = document.getElementById(eltname);
  target = target.parentNode;
  target.innerHTML = blob;
  reg_change(target);
  googlewin.close();
}

function del_discount(row, entry)
{ var tab = document.getElementById("discount_table"+entry+"s"+row);
  tab.innerHTML = "";
  var statusfield = eval('Mainform.discount_status'+entry+'s'+row);
  statusfield.value = "deleted";
  reg_change(tab);
  return false;
}

/* the ps_specific_prices table has two unique keys that forbid that two too similar reductions are inserted.
 * This function - called before submit - checks for them. 
 * Without this check you get errors like: 
 *   Duplicate entry '113-0-0-0-0-0-0-0-15-0000-00-00 00:00:00-0000-00-00 00:00:00' for key 'id_product_2'
 * This key contains the following fields: id_product, id_shop,id_shop_group,id_currency,id_country,id_group,id_customer,id_product_attribute,from_quantity,from,to 
 * Note that this key has changed over different PS versions. So the check here may be to strong for some versions and to weak for others. */
 function check_discounts(rowno)
{ var field = eval("Mainform.discount_count"+rowno);
  if (!field || (field.value == 0))
    return true;
  var keys2 = new Array();
  for(var i=0; i< field.value; i++)
  { if(eval("Mainform.discount_status"+i+"s"+rowno+".value") == "deleted")
      continue;
    var key = eval("Mainform.id_product"+rowno+".value")+"-"+eval("Mainform.discount_shop"+i+"s"+rowno+".value")+"-0-"+eval("Mainform.discount_currency"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_country"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_group"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_customer"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_attribute"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_quantity"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_from"+i+"s"+rowno+".value")+"-"+eval("Mainform.discount_to"+i+"s"+rowno+".value");
    for(var j = 0; j < keys2.length; j++) {
        if(keys2[j] == key) 
		{ var tbl= document.getElementById("offTblBdy");
		  var productno = tbl.rows[rowno].cells[1].childNodes[0].text;
		  alert("You have two or more price rules for a product that are too similar for product "+productno+" on row "+rowno+"! Please correct this!");
		  return false;
		}
    }
	keys2[j] = key;
  }
  return true;
}

/* 					0			1				2		3		4			5		6			7			8			9	 			10			11	  12	*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_tax, reduction_type, from, to */
function fill_discount(row,entry,id,status, shop,attribute,currency,country,group,customer,price,quantity,reduction,reductiontax,reductiontype,from,to,newpricex,newpricei)
{ 	var blob = '<input type=hidden name="discount_id'+entry+'s'+row+'" value="'+id+'">';
	blob += '<input type=hidden name="discount_status'+entry+'s'+row+'" value="'+status+'">';		
	blob += '<table id="discount_table'+entry+'s'+row+'" class="discount_table"><tr><td rowspan=3><a href="#" onclick="return edit_discount('+row+','+entry+')"><img src="pen.png"></a></td>';
	
	if(customer == "") customer = 0;
	if(country == "") country = 0;
	if(group == "") group = 0;
	if(attribute == "") attribute = 0;
	if(quantity == "") quantity = 1;
	if(shop == "") shop = 0;
	
	if(status == "update")
	{	blob += '<td class="nobr"><input type=hidden name="discount_shop'+entry+'s'+row+'" value="'+shop+'">';
		if(shop == "0") blob += "all";
		else blob+=shop;
		blob += '-<input type=hidden name="discount_attribute'+entry+'s'+row+'" value="'+attribute+'">';
		if(attribute == "0") blob += "all";
		else blob+=attribute;
	}
	else /* insert */
	{	blob += '<td class="nobr"><input name="discount_shop'+entry+'s'+row+'" style="width:20px" value="'+shop+'" title="shop id" onchange="reg_change(this);"> &nbsp;';
		blob += '<input name="discount_attribute'+entry+'s'+row+'" style="width:20px" value="'+attribute+'" title="product_attribute id" onchange="reg_change(this);"> &nbsp;';
	}
	
	blob += '<select name="discount_currency'+entry+'s'+row+'" value="'+currency+'" title="currency" onchange="reg_change(this);">';
	blob += '<option value="0">All</option>'+((currency == "")? currencyblock : currencyblock.replace(">"+currency+"<", " selected>"+currency+"<"))+'</select> &nbsp;';

	blob += '<input name="discount_country'+entry+'s'+row+'" style="width:20px" value="'+country+'" title="country id" onchange="reg_change(this);"> &nbsp;';
	blob += '<input name="discount_group'+entry+'s'+row+'" style="width:20px" value="'+group+'" title="group id" onchange="reg_change(this);"></td>';
	
	blob += '<td rowspan=3><a href="#" onclick="return del_discount('+row+','+entry+')"><img src="del.png"></a></td></tr><tr>';
	blob += '<td class="nobr"><input style="width:15px" name="discount_customer'+entry+'s'+row+'" value="'+customer+'" title="customer id" onchange="reg_change(this);"> &nbsp; ';

	blob += '<input name="discount_price'+entry+'s'+row+'" style="width:40px" value="'+price+'" title="From Price Excl" onchange="reg_change(this); discount_change(this,'+row+','+entry+')"> &nbsp; ';
	blob += '<input name="discount_quantity'+entry+'s'+row+'" style="width:30px" value="'+quantity+'" title="From Quantity" onchange="reg_change(this);"> &nbsp;';
	blob += '<input name="discount_reduction'+entry+'s'+row+'" style="width:40px" value="'+reduction+'" title="Reduction" onchange="reg_change(this); discount_change(this,'+row+','+entry+')">';
	blob += '</tr><tr><td>';
	
	blob += '<select name="discount_reductiontax'+entry+'s'+row+'" title="Reduction Tax status" onchange="reg_change(this); discount_change(this,'+row+','+entry+')">';
	if(prestashop_version >= "1.6.0.11")	/* for PS >= 1.6.0.11 */
	{ if(reductiontax == "1")
	    blob += '<option value=0>Excl</option><option value=1 selected>Incl</option>';
	  else
	    blob += '<option value=0 selected>Excl</option><option value=1>Incl</option>';
	}
	else
	    blob += '<option value=1>Incl</option>';	  
	blob += '</select> ';	
	
	blob += '<select name="discount_reductiontype'+entry+'s'+row+'" title="Reduction Type" onchange="reg_change(this); discount_change(this,'+row+','+entry+')">';
	if(reductiontype == "pct")
	   blob += '<option>amt</option><option selected>pct</option>';
	else
	   blob += '<option selected>amt</option><option>pct</option>';
	blob += '</select>';
	blob += ' <input name="discount_from'+entry+'s'+row+'" style="width:65px" value="'+from+'" title="From Date" class="datum" onchange="reg_change(this);">';
	blob += ' <input name="discount_to'+entry+'s'+row+'" style="width:65px" value="'+to+'" title="To Date" class="datum" onchange="reg_change(this);">';

	blob += ' <input id="discount_newprice_excl'+entry+'s'+row+'" style="width:40px" value="'+newpricex+'" onchange="discount_change(this,'+row+','+entry+')" title="calculated price excl VAT" class="calculated">';
    blob += ' <input id="discount_newprice_incl'+entry+'s'+row+'" style="width:40px" value="'+newpricei+'" onchange="discount_change(this,'+row+','+entry+')" title="calculated price incl VAT" class="calculated">';
	blob += "</td></tr></table><hr/>";
	return blob;
}

/* when you add a discount block you cannot immediately execute javascript on it. For that reason the discount_change
 * function that generates the calculated resulting prices for them is executed with a delay. 
 * All the discount blocks that need such an calculation are collected in an array (discount_delayed) that is then 
 * processed by this function. As discount_change() needs to know which field's change is guiding we provide a 
 * "target". For the "add" function this is always "reduction". For the "add fixed target discount" function 
 * it is either "newprice_excl" or "newprice_incl". */
function delayed_discount_change(target)
{ var len = discount_delayed.length;
  for(var i=0; i<len; i++)
  { var elta = eval("Mainform.discount_"+target+discount_delayed[i][1]+"s"+discount_delayed[i][0]);
	if(!elta) alert("Delayed discount target not found "+target);
    discount_change(elta, discount_delayed[i][0],discount_delayed[i][1]);
  }
}

/* discount_change is called when one of the fields is changed. It calculates the new discounted price */
function discount_change(elt,row,entry)
{ var name = elt.name;
  var myform = elt.form.name;
  var suffix = "";
  if(myform == "Mainform")
	  suffix = entry+"s"+row;
  var tblEl = document.getElementById("offTblBdy");
  var baseprice = eval(myform+".discount_price"+suffix+".value");
  if(!baseprice)
  { var pricecol = getColumn("price");
    if(pricecol !== undefined)
        var baseprice = parseFloat(tblEl.rows[row].cells[pricecol].innerHTML);
  }
  else
	  baseprice = parseFloat(baseprice);
  var VATcol = getColumn("VAT");
  if(VATcol !== undefined) 
    var VAT = parseFloat(tblEl.rows[row].cells[VATcol].innerHTML);
  else 
	var VAT = 0;
  var reductionfield = eval(myform+".discount_reduction"+suffix);
  if(reductionfield.value=="") reductionfield.value="0";
  var reduction = parseFloat(reductionfield.value);
  var reductiontype = eval(myform+".discount_reductiontype"+suffix+".value");
  var reductiontax = parseFloat(eval(myform+".discount_reductiontax"+suffix+".value"));
  
  if(elt.id.substring(0,17) == "discount_newprice") /* if the newprice was changed: change the reduction */
  { if(elt.id.substring(0,22) == "discount_newprice_incl")
	{ var newpricei = parseFloat(eval(myform+".discount_newprice_incl"+suffix+".value"));
	  var newpricex = newpricei * (100/(VAT+100));
	}
	else
	{ var newpricex = parseFloat(eval(myform+".discount_newprice_excl"+suffix+".value"));
	}
	if(reductiontype == "pct")
	  var reduction = Math.round((baseprice - newpricex) / (baseprice * 100));
    else
    { var reduction = baseprice - newpricex;
      if(reductiontax)
		reduction = (reduction * ((100+VAT)/100)).toFixed(2);
    }
	reductionfield.value = reduction;
  }
  if(reductiontype == "pct")
	var newpricex = baseprice * (1 - (reduction/100));
  else
  { if(reductiontax)
		reduction = reduction *(100/(VAT+100));
    var newpricex = baseprice - reduction;
  }
  var newpricei = newpricex * (1 + VAT/100);
  var newpricex_fld = document.getElementById("discount_newprice_excl"+suffix);
  newpricex_fld.value = newpricex.toFixed(2);
  var newpricei_fld = document.getElementById("discount_newprice_incl"+suffix);
  newpricei_fld.value = newpricei.toFixed(2);
}

function useTinyMCE(elt, field)
{ if (typeof tinymce == 'undefined')
  { alert("TinyMCE could not be loaded! Please check your internet connection!"); return false; }
  while (elt.nodeName != "TD")
  {  elt = elt.parentNode;
  }
  elt.childNodes[0].cols="125";
  elt.childNodes[1].style.display = "none";  /* hide the links */
  tinymce.init({
//	content_css: "http://localhost/css/my_tiny_styles.css",
//    fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt",	
	selector: "#"+field,
    toolbar: "...| removeformat | ...",
//	width:500
//	setup: function (ed) {
//  	ed.on("change", function () {
//        })
//	}
  });		// Note: onchange_callback was for TinyMCE 3.x and doesn't work in 4.x
}

/* the arguments for this version were derived from source code of the "classic" example on the TinyMCE website */
/* some buttons were removed but all plugins were maintained */
function useTinyMCE2(elt, field)
{ if (typeof tinymce == 'undefined')
  { alert("TinyMCE could not be loaded! Please check your internet connection!"); return false; }
  while (elt.nodeName != "TD")
  {  elt = elt.parentNode;
  }
  elt.childNodes[0].cols="160";
  elt.childNodes[1].style.display = "none";  /* hide the links */
  tinymce.init({
  	selector: "#"+field, 
	plugins: [
		"advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
		"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
		"table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
	],
	toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
	toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview",
	toolbar3: "forecolor backcolor | table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking",
	menubar: false,
	toolbar_items_size: 'small',
	style_formats: [
		{title: 'Bold text', inline: 'b'},
		{title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
		{title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
		{title: 'Example 1', inline: 'span', classes: 'example1'},
		{title: 'Example 2', inline: 'span', classes: 'example2'},
		{title: 'Table styles'},
		{title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
	],
	width: 660,
	autosave_ask_before_unload: false
  });
}  


function Addcarrier(plIndex)
{ var list = document.getElementById('carrierlist'+plIndex); /* available carriers */
  var sel = document.getElementById('carriersel'+plIndex);	/* selected carriers */
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  carrier = list.options[listindex].text;
  car_id = list.options[listindex].value;
  list.options[listindex]=null;		/* remove from available carriers list */
  if(sel.options[0].value == "none")
  { sel.options.length = 0;
    max = 0;
  }
  i=0;
  var base = sel.options;
  while((i<max) && (carrier > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(carrier);
  else
  { newOption = new Option(carrier);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(carrier));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = car_id;
  var mycars = eval("document.Mainform.mycars"+plIndex);
  mycars.value = mycars.value+','+car_id;
}

function Removecarrier(plIndex)
{ var list = document.getElementById('carrierlist'+plIndex);
  var sel = document.getElementById('carriersel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  carrier = sel.options[selindex].text;
  if(carrier == "none") return; /* none selected */
  car_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (carrier > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(carrier);
  else
  { newOption = new Option(carrier);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(carrier));
    list.insertBefore(newOption, list.options[i]);
  }
  if(sel.options.length == 0)
    sel.options[0] = new Option("none");
  list.options[i].value = car_id;
  var mycars = eval("document.Mainform.mycars"+plIndex);
  mycars.value = mycars.value.replace(','+car_id, '');
}

function fillCarriers(idx,cars)
{ var list = document.getElementById('carrierlist'+idx);
  var sel = document.getElementById('carriersel'+idx);
  for(var i=0; i< cars.length; i++)
  { for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == cars[i])
	  { list.selectedIndex = j;
		Addcarrier(idx);
	  }
	}
  }
}

function Addsupplier(plIndex, init)
{ var list = document.getElementById('supplierlist'+plIndex);
  var sel = document.getElementById('suppliersel'+plIndex);
  var sellen = sel.options.length;
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  var supplier = list.options[listindex].text;
  var sup_id = list.options[listindex].value;
  list.options[listindex]=null;
  i=0;
  var base = sel.options;
  while((i<max) && (supplier > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(supplier);
  else
  { newOption = new Option(supplier);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(supplier));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = sup_id;
  if(init == 1) /* if this function is called by the add button (= not from SwitchDisplay) we need to add a line for each attribute with zero values */
  { var attributes = eval("document.Mainform.supplier_attribs"+plIndex+".value");
    var myattribs = attributes.split(",");
    if(sellen == 0) /* if there was no supplier we must make this one default */
    { sel.options[0].className = 'defcat';
	  var default_supplier = eval("document.Mainform.supplier_default"+plIndex);
	  default_supplier.value = sel.options[0].value;
    }
    for(i=0; i < myattribs.length; i++)
    { var tab = document.getElementById("suppliertable"+myattribs[i]+"s"+plIndex);
	  if(tab.rows[0])
		tab.rows[0].deleteCell(3); /* the attribute name is shared. If it is already there we delete it so that we can re-add it with the new line */
      for (j=0; j<= tab.rows.length; j++)
	  { if(!tab.rows[j] || tab.rows[j].cells[0].innerHTML > supplier)
	    { if(sellen == 0) /* if there was no supplier we must make this one default */
			var rowstart = '<td class="defcat">';
		  else
			var rowstart = '<td>';			  
		  var newRow = tab.insertRow(j);
		  newRow.innerHTML=rowstart+supplier+'</td><td><input name="supplier_reference'+myattribs[i]+'t'+sup_id+'s'+plIndex+'" value="" onchange="reg_change(this);" /></td><td><input name="supplier_price'+myattribs[i]+'t'+sup_id+'s'+plIndex+'" value="0.000000" onchange="reg_change(this);" /></td>';
		  break;
		}
	  }
	  tab.rows[0].innerHTML += '<td rowspan="'+tab.rows.length+'">'+tab.title+'</td>';
	}
  }  
  var mysups = eval("document.Mainform.mysups"+plIndex);
  mysups.value = mysups.value+','+sup_id;
  return i;
}

function Removesupplier(plIndex)
{ var list = document.getElementById('supplierlist'+plIndex);
  var sel = document.getElementById('suppliersel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, j, max = list.options.length;
  var supplier = sel.options[selindex].text;
  var sup_id = sel.options[selindex].value;
  var classname = sel.options[selindex].className;
  var sellen = sel.options.length;
  sel.options[selindex]=null;
  if(classname == 'defcat')
  { var default_supplier = eval("document.Mainform.supplier_default"+plIndex);
	if(sellen > 1)
	{ default_supplier.value = sel.options[0].value;
	  sel.options[0].className = 'defcat';
	}
    else
	  default_supplier.value = 0;
  }
  i=0; /* now add it to the list */
  while((i<max) && (supplier > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(supplier);
  else
  { newOption = new Option(supplier);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(supplier));
    list.insertBefore(newOption, list.options[i]);
  }
  list.options[i].value = sup_id;
  var attributes = eval("document.Mainform.supplier_attribs"+plIndex+".value");
  var myattribs = attributes.split(",");
  for(i=0; i < myattribs.length; i++)
  { var tab = document.getElementById("suppliertable"+myattribs[i]+"s"+plIndex);
    tab.rows[0].deleteCell(3);
    for (j=0; j< tab.rows.length; j++)
	{ if(tab.rows[j].cells[0].innerHTML == supplier)
	  { tab.deleteRow(j);
	  }
	}
	if(tab.rows.length > 0)
	{ tab.rows[0].innerHTML += '<td rowspan="'+tab.rows.length+'">'+tab.title+'</td>';
	  if(classname == 'defcat') /* if deleted row was default supplier, make the first row so */
	    tab.rows[0].cells[0].className = "defcat";
    }
  }
  var mysups = eval("document.Mainform.mysups"+plIndex);
  mysups.value = mysups.value.replace(','+sup_id, '');
}

function get_product_id(row)
{ var prod_base = eval("document.Mainform.id_product"+row);
  if(!prod_base) return 0;
  return prod_base.value;
}

function fillSuppliers(idx,tmp)
{ tmp = tmp.replace(/ /g,''); /* remove the spaces that we added for linebreaks */
  var cats = tmp.split(',<');
  var list = document.getElementById('categorylist'+idx);
  var sel = document.getElementById('categorysel'+idx);
  var defcatvalue = -1;
  for(var i=0; i< cats.length; i++)
  { if(i!=0)
	  cats[i] = "<"+cats[i];
	if(!cats[i].match("text-decoration"))
	  defcatvalue = cats[i]= striptags(cats[i]);
	else 
	  cats[i]= striptags(cats[i]);
    for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == cats[i])
	  { list.selectedIndex = j;
		Addcategory(idx);
	  }
	}
  }
  for(var k=0; k< sel.length; k++)
  { if(sel.options[k].value == defcatvalue)
    { defcat = k; break; 
	}
  }
  if(defcatvalue >= 0)
  { sel.options[defcat].className = 'defcat';
    var default_cat = eval("document.Mainform.category_default"+idx);
    default_cat.value = '0'; // zero indicates that is was not changed
  }
  else
  { alert("No default supplier found for row "+idx+" (product "+get_product_id(idx)+"). First available taken.");
	sel.options[0].className = 'defcat';
	var default_cat = eval("document.Mainform.category_default"+idx);
    default_cat.value = sel.options[0].value;
	reg_change(sel);
//alternative solution: next rows makes changing impossible
// list.parentNode.parentNode.parentNode.parentNode.parentNode.innerHTML = "No change allowed!"; // Remove the whole cell.
//    alert("No default found for row "+idx+" ");
  }
}

function MakeSupplierDefault(idx)
{ var sel = document.getElementById('suppliersel'+idx);
  for(var j=0; j< sel.length; j++)
	sel.options[j].className = '';
  if(sel.selectedIndex < 0) return;
  sel.options[sel.selectedIndex].className = 'defcat';
  var default_supplier = eval("document.Mainform.supplier_default"+idx);
  default_supplier.value = sel.options[sel.selectedIndex].value;
}

function Addcategory(plIndex)
{ var list = document.getElementById('categorylist'+plIndex);
  var sel = document.getElementById('categorysel'+plIndex);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  category = list.options[listindex].text;
  cat_id = list.options[listindex].value;
  list.options[listindex]=null;
  i=0;
  var base = sel.options;
  while((i<max) && (category > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(category);
  else
  { newOption = new Option(category);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(category));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = cat_id;
  var mycats = eval("document.Mainform.mycats"+plIndex);
  mycats.value = mycats.value+','+cat_id;
}

function Removecategory(plIndex)
{ var list = document.getElementById('categorylist'+plIndex);
  var sel = document.getElementById('categorysel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  category = sel.options[selindex].text;
  cat_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  if(sel.options.length == 1)
  { alert('There must always be at least one selected category!');
    return; /* leave selection not empty */
  }
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (category > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(category);
  else
  { newOption = new Option(category);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(category));
    list.insertBefore(newOption, list.options[i]);
  }
  list.options[i].value = cat_id;
  if(classname == 'defcat')
  { sel.options[0].className = 'defcat';
    var default_cat = eval("document.Mainform.category_default"+plIndex);
	default_cat.value = sel.options[0].value;
  }
  var mycats = eval("document.Mainform.mycats"+plIndex);
  mycats.value = mycats.value.replace(','+cat_id, '');
}

function fillCategories(idx,tmp)
{ tmp = tmp.replace(/ /g,''); /* remove the spaces that we added for linebreaks */
  var cats = tmp.split(',<');
  var list = document.getElementById('categorylist'+idx);
  var sel = document.getElementById('categorysel'+idx);
  var defcatvalue = -1;
  for(var i=0; i< cats.length; i++)
  { if(i!=0)
	  cats[i] = "<"+cats[i];
	if(!cats[i].match("text-decoration"))
	  defcatvalue = cats[i]= striptags(cats[i]);
	else 
	  cats[i]= striptags(cats[i]);
    for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == cats[i])
	  { list.selectedIndex = j;
		Addcategory(idx);
	  }
	}
  }
  for(var k=0; k< sel.length; k++)
  { if(sel.options[k].value == defcatvalue)
    { defcat = k; break; 
	}
  }
  if(defcatvalue >= 0)
  { sel.options[defcat].className = 'defcat';
    var default_cat = eval("document.Mainform.category_default"+idx);
    default_cat.value = '0'; // zero indicates that is was not changed
  }
  else
  { alert("No default category found for row "+idx+" (product "+get_product_id(idx)+"). First available taken.");
	sel.options[0].className = 'defcat';
	var default_cat = eval("document.Mainform.category_default"+idx);
    default_cat.value = sel.options[0].value;
	reg_change(sel);
//alternative solution: next rows makes changing impossible
// list.parentNode.parentNode.parentNode.parentNode.parentNode.innerHTML = "No change allowed!"; // Remove the whole cell.
//    alert("No default found for row "+idx+" ");
  }
}

function striptags(mystr) /* remove html tags from text */
{ var regex = /(<([^>]+)>)/ig;
  return mystr.replace(regex, "");
}

function MakeCategoryDefault(idx)
{ var sel = document.getElementById('categorysel'+idx);
  for(var j=0; j< sel.length; j++)
	sel.options[j].className = '';
  if(sel.selectedIndex < 0) return;
  sel.options[sel.selectedIndex].className = 'defcat';
  var default_cat = eval("document.Mainform.category_default"+idx);
  default_cat.value = sel.options[sel.selectedIndex].value;
}

function change_categories()
{ var tmp = document.getElementById('cat_order'); /* this is the "sort by" "position" option that must be hidden when "all categories" is selected */
  var tmp2 = document.getElementById('category_number');
  if(document.search_form.id_category.selectedIndex ==0)
  {	tmp.style.display = 'none';
	tmp.disabled = true;
	document.search_form.order.selectedIndex = 1;
	tmp2.value = '';
	document.search_form.subcats.checked = false;
  }
  else
  { if (tmp.style.display == 'none') /* if not visible: show and select order option "positions" */
    { tmp.style.display = 'inline';
	  tmp.disabled = false;	
      document.search_form.order.selectedIndex = 0;
	}
	tmp3 = document.search_form.id_category;
	tmp2.value = tmp3.options[tmp3.selectedIndex].value;
  }
}

function change_category_number(num)
{ if(num=='x') /* in the searchbox */
  { var tmp = document.getElementById('category_number');
    var mysel = search_form.id_category;
  }
  else /* selecting in one of the edit rows (num = rownum) */
  { var tmp = document.getElementById('category_number'+num);
	var mysel = eval('Mainform.categorylist'+num);
  } 
  var mysellen = mysel.length;
  var myoptions = mysel.options;
  var val = tmp.value;
  if(isNaN(val))  /* if it is not non-numeric we do a text search among the categories on the value */
  { val = val.toLowerCase();
    for(var i=1; i<mysellen; i++)
	{ if (myoptions[i].text.substr(0, val.length).toLowerCase() == val)
	  { mysel.selectedIndex = i;
		break;			
	  }
	}
  }  
  else
  { var found = false;
    for(var i=1; i<mysellen; i++)
	{ if(myoptions[i].value == val)
	  { mysel.selectedIndex = i;
		found = true;	
	  }
	}
	if(!found)
	  mysel.selectedIndex = 0;
  }
  if(num=='x') /* in the searchbox */
  { var tmp = document.getElementById('cat_order');
    if(mysel.selectedIndex == 0)
    { tmp.style.display = 'none';
	  document.search_form.order.selectedIndex = 1;
	  document.search_form.subcats.checked = false;
    }
    else
    { if (tmp.style.display == 'none')
	  { tmp.style.display = 'inline';
        document.search_form.order.selectedIndex = 0;		  
	  }
	}
  }
}

function change_subcats()
{ if(document.search_form.id_category.selectedIndex ==0) 
	document.search_form.subcats.checked = false;
  var tmp = document.getElementById('cat_order');
  if(document.search_form.subcats.checked)
  { tmp.style.display = 'none';
//	document.search_form.order.selectedIndex = 1;
  }
  else if (tmp.style.display == 'none') /* if not visible: show and select order option "positions" */
  { tmp.style.display = 'inline';
//    document.search_form.order.selectedIndex = 0;
  }
}

function Addattachment(plIndex)
{ var list = document.getElementById('attachmentlist'+plIndex);
  var sel = document.getElementById('attachmentsel'+plIndex);
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  attachment = list.options[listindex].text;
  attach_id = list.options[listindex].value;
  list.options[listindex]=null;
  if(sel.options[0].value == "none")
  { sel.options.length = 0;
    max = 0;
  }
  i=0;
  var base = sel.options;
  while((i<max) && (attachment > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(attachment);
  else
  { newOption = new Option(attachment);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(attachment));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = attach_id;
  var myattachments = eval("document.Mainform.myattachments"+plIndex);
  myattachments.value = myattachments.value+','+attach_id;
}

function Removeattachment(plIndex)
{ var list = document.getElementById('attachmentlist'+plIndex);
  var sel = document.getElementById('attachmentsel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  attachment = sel.options[selindex].text;
  if(attachment == "none") return; /* none selected */
  attach_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (attachment > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(attachment);
  else
  { newOption = new Option(attachment);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(attachment));
    list.insertBefore(newOption, list.options[i]);
  }
  if(sel.options.length == 0)
    sel.options[0] = new Option("none");
  list.options[i].value = attach_id;
  var myattachments = eval("document.Mainform.myattachments"+plIndex);
  myattachments.value = myattachments.value.replace(','+attach_id, '');
}

function fillAttachments(idx,attas)
{ var list = document.getElementById('attachmentlist'+idx);
  var sel = document.getElementById('attachmentsel'+idx);
//  alert("PPP "+attas[0]);
  for(var i=0; i< attas.length; i++)
  { for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == attas[i])
	  { list.selectedIndex = j;
		Addattachment(idx);
	  }
	}
  }
}

/* handle changes in the order (Sort By) field in the search block */
function change_order()
{ if(document.search_form.order.selectedIndex < 6) 
	document.search_form.rising.selectedIndex = 0;
  else
  	document.search_form.rising.selectedIndex = 1;
}

/* handle changes in the search fields in the search block */
function change_sfield(elt)
{ var value = elt.value;
  var name = elt.name;
  if(name=="search_fld1")
	  var target = search_form.search_cmp1;
  else 
	  var target = search_form.search_cmp2;	  
  if((value == "ps.id_category_default") || (value == "p.id_product") || (value == "cp.position") || (value == "cl.id_category"))
	target.selectedIndex = 4;
  else
	target.selectedIndex = 0;
}

	function isNumber(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	}
	  
	function newwin_check()
	{ if(search_form.newwin.checked)
		search_form.target = "_blank";
	  else
		search_form.target = "";
	}
	  
	/* swapfeatures adds/removes a row with feature field names to the field names block in the search block */
	function swapFeatures(elt)
	{ var myrow, i;
	  if ((elt.checked) && (prestools_notbought.indexOf("features") !== -1))
	      alert("Features is a plugin that needs to be bought seperately at www.Prestools.com.\nWithout the plugin you are in demo-mode: you can make changes but they will not be saved!");
	  for(i=0; i<9; i++)
	  { if(myrow = document.getElementById("featureblock"+i))
		{ if(elt.checked)
		    myrow.style.display = "table-row";
		  else
		  { myrow.style.display = "none";
			var elts = myrow.getElementsByTagName("input");
			for(j=0; j<elts.length; j++)
			    elts[j].checked = false;
		  }
		}
	  }
	}
  
  /* swapStats adds/removes a row with statistics fields to the field names block in the search block */
  function swapStats(elt)
  { var myrow = document.getElementById("statsblock");
	if(elt.checked)
	  myrow.style.display = "table-row";
	else
	{ myrow.style.display = "none";
	  var elts = myrow.getElementsByTagName("input");
	  for(j=0; j<elts.length; j++)
		elts[j].checked = false;
	}
  }
  
  /* this function is called after clicking the "List products" button and produces a product overview taht can be used for stock taking */
  function ListProducts()
  { var div = document.getElementById("listsearchdiv");
    var block = document.getElementById("searchblock");
	var p = block.cloneNode(true);
    div.appendChild(p);
	listerform.verbose.value = SwitchForm.verbose.checked;
	listerform.submit();
	div.innerHTML = "";
  }
  
  function submitCSV()
  { var div = document.getElementById("csvsearchdiv");
    var block = document.getElementById("searchblock");
	var p = block.cloneNode(true);
    div.appendChild(p);
	csvform.verbose.value = SwitchForm.verbose.checked;
	csvform.submit();
	div.innerHTML = "";
  }
  
  function importCSV()
  { csvimportform.verbose.value = SwitchForm.verbose.checked;
	var tbl = document.getElementById("offTblBdy");
	var len = tbl.rows.length;
	var myprods = [];
	var myprodidxs = [];
	var found = 0;
	for(var i=0; i<len; i++)
	{ if(tbl.rows[i].innerHTML == "<td></td>") {continue; }
	  myprods.push(tbl.rows[i].childNodes[0].childNodes[1].value);
	  myprodidxs.push(tbl.rows[i].childNodes[0].childNodes[1].name.substring(10));
	  found = i;
	}
	csvimportform.myprods.value = myprods.join();
	csvimportform.myprodidxs.value = myprodidxs.join();	
	
	var tblmain = document.getElementById("Maintable");
	var myfields = [];
	len = tbl.rows[found].cells.length;
	for(var i=2; i<len-1; i++)
	{ var switcher = eval("SwitchForm.disp"+i);
      if(!switcher)
		myfields.push(tblmain.tHead.rows[0].cells[i].firstChild.getAttribute("fieldname"));
	}
	csvimportform.myfields.value = myfields.join();	
	csvimportform.submit();
  }
  
  function prepare_update()
  { records = new Array();
	for(i=0; i < numrecs; i++) 
	{ prod_base = eval("document.Mainform.id_product"+i);
	  if(!prod_base) continue;
	  id_product = prod_base.value;
	  records[id_product] = i;
	}
  }
  
  /* this function is called from the iframe to update language fields from a different language */
  function update_field(product, field, value)
  { var base = eval("document.Mainform."+field+records[product]);
	if(base.value != value)
		reg_change(base);
	base.value = value;
  }
  
	 //&id_category=&fields%5B%5D=name&fields%5B%5D=VAT&fields%5B%5D=category&fields%5B%5D=description&fields%5B%5D=price&fields%5B%5D=ean&fields%5B%5D=image&fields%5B%5D=description_short
	  
  function massUpdate()
  { var i, j, k, x, tmp, base, changed;
	base = eval("document.massform.field");
	/* fieldtext is the recognizer. fieldname is the formfield that is to be updated */
	fieldname = fieldtext = base.options[base.selectedIndex].value;
	var tbl= document.getElementById("offTblBdy");
	if(fieldtext.substr(1,8) == "elect a "){ alert("You must select a fieldname!"); return;}
	base = eval("document.massform.action");
	action = base.options[base.selectedIndex].text;
	if(action.substr(1,8) == "elect an") { alert("You must select an action!"); return;}
	if(action == "copy from other lang")
	{ var potentials = new Array("name","description_short","description","meta_title","meta_keywords","link_rewrite","meta_description");
	  var products = new Array();
	  var fields = new Array();
	  var fields_checked = false;
	  j=0; k=0;
	  for(i=0; i < numrecs; i++) 
	  { prod_base = eval("document.Mainform.id_product"+i);
		if(!prod_base) continue;
		id_product = prod_base.value;
		if(!fields_checked)
		{ for(x=0; x<potentials.length; x++)
		  { field = eval("document.Mainform."+potentials[x]+i);
			if(field) fields[j++] = potentials[x];
		  }
		  if(fields.length == 0) return;
		  fields_checked = true;
		}
		products[k++] = id_product;
	  }
	  document.copyForm.products.value = products.join(",");
	  document.copyForm.fields.value = fields.join(",");
	  document.copyForm.id_lang.value = massform.copylang.value;		  
	  document.copyForm.submit(); /* copyForm comes back with the prepare_update() function */
	  return;
	}
	if((action != "copy from field") && (action != "replace from field") && (fieldtext != "discount") && (fieldtext != "virtualp") && (action != "TinyMCE") && (action != "TinyMCE-deluxe"))
	   myval = document.massform.myvalue.value;
	if(((fieldtext == "price") || (fieldtext == "priceVAT")) && !isNumber(myval)) { alert("Only numeric prices are allowed!\nUse decimal points!"); return;}
	if(((fieldtext == "description") || (fieldtext == "description_short")) && (action == "set") && (myval.length != 0) &&(myval.substring(0,2)!="<p"))
			myval = "<p>"+myval+"</p>";
	if((fieldtext == "VAT") || (fieldtext == "manufacturer"))
		myval = document.massform.myvalue.selectedIndex;
	if(fieldtext == "stockflags")
	{	myval = document.massform.myvalue.value;
		if(myval=="3")
			mywarehouse = massform.stockflags_warehouse.value;
	}
	if((action == "add") && (fieldtext == "discount"))
	{	shop = massform.shop.options[massform.shop.selectedIndex].value;
		currency = massform.currency.options[massform.currency.selectedIndex].value;
		country = massform.country.options[massform.country.selectedIndex].value;
		group = massform.group.options[massform.group.selectedIndex].value;			
		price = massform.price.value;
		quantity = massform.quantity.value;
		reduction = massform.reduction.value;
		reductiontax = massform.reductiontax.value;
		reductiontype = massform.reductiontype.options[massform.reductiontype.selectedIndex].text;
		datefrom = massform.datefrom.value;
		dateto = massform.dateto.value;
		discount_delayed = [];
		setTimeout(function(){delayed_discount_change("reduction");}, 100);
	}
	if((action == "remove") && (fieldtext == "discount"))
	{	var subfieldname = massform.fieldname.options[massform.fieldname.selectedIndex].text;
		var subfield = massform.subfield.value;
	}
	if((action == "add fixed target discount") && (fieldtext == "discount"))
	{	shop = massform.shop.options[massform.shop.selectedIndex].value;
		currency = massform.currency.options[massform.currency.selectedIndex].value;
		country = massform.country.options[massform.country.selectedIndex].value;
		group = massform.group.options[massform.group.selectedIndex].value;	
		quantity = massform.quantity.value;
		datefrom = massform.datefrom.value;
		dateto = massform.dateto.value;
		targetprice = massform.targetprice.value;
		targetpriceVAT = massform.targetpriceVAT.value;
		discount_delayed = [];
		if(targetprice != "")
		{ reductiontax = "0";
		  setTimeout(function(){delayed_discount_change("newprice_excl");}, 100);
		}
		else if(targetpriceVAT != "")
		{ reductiontax = "1";
		  setTimeout(function(){delayed_discount_change("newprice_incl");}, 100);
	    }
		else return; /* neither field had a value */
		reductiontype = "amt";
		/* the following is set pro forma. It's real setting will happen in delayed_discount_change() */
		reduction = 0; 
	}
	if((fieldtext == "active") || (fieldtext == "on_sale") || (fieldtext == "online_only"))
	{	myval = document.massform.myvalue.checked;
	}
	if(fieldtext == "category")
	{	myval = document.massform.myvalue.value;
		fieldname = "categorysel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
	}
	if(fieldtext == "supplier")
	{	myval = document.massform.myvalue.value;
		fieldname = "suppliersel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
	}
	if(fieldtext == "attachmnts")
	{	myval = document.massform.myvalue.value;
		fieldname = "attachmentsel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
	}
	if(fieldtext == "carrier")
	{	myval = document.massform.myvalue.value;
		fieldname = "carriersel"; /* needed because we check the field for existence/edibility at the beginning of the loop */
	}
	if(fieldtext == "image")
	{	var emptyonly = document.massform.emptyonly.checked;
		var coverage = document.massform.coverage.value;
		var imagecol = getColumn("image");
	}
	if(fieldtext == "virtualp")
	{	var is_virtual = document.massform.is_virtual.value;
		var date_expiration = document.massform.date_expiration.value;
		var nb_days_accessible = document.massform.nb_days_accessible.value;
		var nb_downloadable = document.massform.nb_downloadable.value;
	}
	if((action == "copy from field") || (action == "replace from field"))
	{	copyfield = document.massform.copyfield.options[document.massform.copyfield.selectedIndex].value;
		cellindex = getColumn(copyfield);
		if(action == "replace from field")
			oldval = document.massform.oldval.value;
		tmp = eval("SwitchForm.disp"+cellindex);
		if(!tmp) 
		{ alert("The field which you copy or replace from should not be in editable mode!");
		  return;
		}
	}
	if(fieldtext.match(/feature[0-9]+field/))
	{	custom = 1;
		if((document.massform.myvalue_sel) && (document.massform.myvalue_sel.selectedIndex!=0))
		{ custom=0;
		  myval = document.massform.myvalue_sel.selectedIndex;
		}
		if(action == "replace")
		{ oldcustom = 1;
		  oldval = document.massform.oldval.value;
		  if((document.massform.oldval_sel) && (document.massform.oldval_sel.selectedIndex!=0))
		  { oldcustom=0;
			oldval = document.massform.oldval_sel.selectedIndex;
		  }
		  if(custom != oldcustom)
		  { alert("The old and the new values should be of the same type: either select or freetext!");
			return;
		  }
		}
	}
	for(i=0; i < numrecs; i++)
	{ 	changed = false;
		if(fieldname == "discount")
		   fieldname = "discount_count";
		if(fieldname == "image")
		  field = eval("document.Mainform.image_list"+i);
		else if(fieldname == "shopz")
		{ field = document.getElementsByName("shopz"+i+"[]");
	    }
		else if(fieldname == "virtualp")
		  field = eval("document.Mainform.date_expiration"+i); /* field must have a value and virtualp is not a fieldname */
 	    else
		  field = eval("document.Mainform."+fieldname+i);
		if(!field) { continue; } /* deal with clicked away lines */
		if(((fieldname == "description") || (fieldtext == "description_short")) && (field.parentNode.childNodes[0].tagName == "DIV"))
			field.value = tinyMCE.get(fieldname+i).getContent();	
		if(fieldname == "image")	
		{ myval2 = striptags(tbl.rows[i].cells[cellindex].innerHTML);
		  var images = tbl.rows[i].cells[imagecol].getElementsByTagName("img");
		  var textareas = tbl.rows[i].cells[imagecol].getElementsByTagName("textarea");
		  for(j=0; j<images.length; j++)
		  { var border = images[j].border;
			var legend = textareas[j].value;
			if(((legend=="") || (!emptyonly)) && ((border) || (coverage == "all")))
			{ textareas[j].value = myval2;
			  if(legend != myval2) changed = true;
			}
		  }
		}
		else if(action == "insert before")
		{	if((fieldname == "description") || (fieldtext == "description_short"))
			{   if(myval.substring(0,3) == "<p>")
				{ myval2 = myval+field.value;
				}
				else
				{ orig = field.value.replace(/^<p>/, "");
				  myval2 = "<p>"+myval+orig;
				}
			}
			else
				myval2 = myval+field.value;
			changed = true;
		}
		else if(action == "increase%")
		{ tmp = field.value * (parseFloat(myval)+100);
		  myval2 = tmp / 100;
		  if(myval2 != 0)
			changed = true;
		}
		else if(action == "increase amount")
		{ myval2 = parseFloat(field.value) + parseFloat(myval);
		  if(fieldname == "qty")
			myval2 = parseInt(myval2);
		  if(myval2 != field.value)
			changed = true;
		}			
		else if(action == "insert after")
		{	if((fieldname == "description") || (fieldtext == "description_short"))
			{	if( myval.charAt(0) == "<") /* new alinea */
				{	myval2 = field.value+myval;
				}
				else	/* insert in last alinea */
				{	orig = field.value.replace(/<\/p>$/, "");
					myval2 = orig+myval+"</p>";
				}
			}
			else
				myval2 = field.value+myval;
			changed = true;
		}
		else if(action == "TinyMCE")
		{	field = eval("document.Mainform."+fieldname+i);
			useTinyMCE(field, fieldname+i);
		}
		else if(action == "TinyMCE-deluxe")
		{	field = eval("document.Mainform."+fieldname+i);
			useTinyMCE2(field, fieldname+i);
		}
		else if(action == "regenerate")
		{	field = eval("document.Mainform."+fieldname+i);
			oldvalue = field.value;
			var namecol = getColumn("name");
			if(namecol === undefined) return;
			var node = field.parentNode.parentNode.cells[namecol].childNodes[0];
			if(node.nodeName == "INPUT")
			{ alert("For link-rewrite regeneration the Name field can not be editable!")
			  return;
			}
			myval2 = str2url(node.innerHTML);
			if(oldvalue != myval2)
			  changed = true;
		}
		else if(action == "replace")
		{	if((pos = featurelist.indexOf(fieldtext)) != -1)
			{ if(custom == 1)
			  { // The replace string was found at http://dumpsite.com/forum/index.php?topic=4.msg8#msg8 and http://stackoverflow.com/questions/2116558/fastest-method-to-replace-all-instances-of-a-character-in-a-string
				src2 = oldval.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&");
				oldvalue = field.value;
				myval2 = field.value.replace(new RegExp(src2, 'g'), myval);
			  }
			}
			else
			{ src = document.massform.oldval.value;
			  src2 = src.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g, "\\$&");
			  oldvalue = field.value;
			  myval2 = field.value.replace(new RegExp(src, 'g'), myval);
 			  if(oldvalue != myval2)
				changed = true;		
			}
		}
		else if(action == "set as default")  /* set category as default */
		{ 	if(fieldtext == "category")
			{ var list = document.getElementById("categorysel"+i);
		      len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  MakeCategoryDefault(i); 
				  changed = true;
				  break;
				}
			  }
			}
		 	else if(fieldtext == "supplier")
			{ var list = document.getElementById("suppliersel"+i);
		      len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  MakeSupplierDefault(i); 
				  changed = true;
				  break;
				}
			  }	  
		  }
		}
		else if(action == "add")  /* add category or discount to product */
		{ 	if(fieldtext == "attachmnts")
			{ var list = document.getElementById("attachmentlist"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  Addattachment(i, 1);
				  changed = true;
				  break;
				}
			  }
			}
			else if(fieldtext == "carrier")
			{ var list = document.getElementById("carrierlist"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  Addcarrier(i);
				  changed = true;
				  break;
				}
			  }
			}
			else if(fieldtext == "category")
			{ var list = document.getElementById("categorylist"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  Addcategory(i);
				  changed = true;
				  break;
				}
			  }
			}
			else if (fieldtext == "discount")
			{  	var count_root = eval("Mainform.discount_count"+i);
				var dcount = parseInt(count_root.value);
/* function 			 fill_discount(row,entry, id,status,shop,attribute,currency,country,group,customer,price,quantity,reduction,reductiontax,reductiontype,from,	to,newpricex,newpricei)             */
				var blob = fill_discount(i,dcount,"","new", shop,"",	   currency,country,group,"",	   price,quantity,reduction,reductiontax,reductiontype,datefrom,dateto,0,     0);
				var new_div = document.createElement("div");
				new_div.innerHTML = blob;
				var adder = document.getElementById("discount_adder"+i);
				adder.parentNode.insertBefore(new_div,adder);
				discount_delayed.push([i, dcount]);
				count_root.value = dcount+1;
				changed = true;
			}
			else if(fieldname == "shopz")
			{ var chklength = field.length;             
			  for(var k=0;k< chklength;k++)
			  { if((field[k].value == myval) && !field[k].checked)
				{ field[k].checked = true;
				  changed = true;
			    }
			  }
			  field = field[0]; /* prepare for reg_change call */
			}
			else if(fieldtext == "supplier")
			{ var list = document.getElementById("supplierlist"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  Addsupplier(i, 1);
				  changed = true;
				  break;
				}
			  }
			}
			else if (fieldtext == "tags")
			{ myval = myval.substring(0,32);
			  if(field.value != "")
			  { myval2 = field.value.split(",");
				var tagfound = false;
				for (index = 0; index < myval2.length; index++) 
				{ if(myval2[index] == myval)
					tagfound = true;
				}
				if(tagfound) continue;
				myval2[index] = myval;
				field.value = myval2.join(",");
			  }
			  else
				field.value = myval;
			  changed = true;
			}
		}
		else if ((action=="add fixed target discount") && (fieldtext == "discount"))
		{  	/* first we need to know the old price */
			var pricecol = getColumn("price");
			if(!pricecol) {alert("Price column must be present!"); return;}
			var baseprice = parseFloat(tbl.rows[i].cells[pricecol].innerHTML);
			var VATcol = getColumn("VAT");
			if(!VATcol) {alert("VAT column must be present!"); return;}
			var VAT = parseFloat(tbl.rows[row].cells[VATcol].innerHTML);
			if((targetprice != "") && (parseFloat(targetprice) >= baseprice)) continue;
			if((targetpriceVAT != "") && (parseFloat(targetpriceVAT) >= (baseprice * (1 + (VAT/100))))) continue;
			var count_root = eval("Mainform.discount_count"+i);
			var dcount = parseInt(count_root.value);
/* function 		 fill_discount(row,entry, id,status,shop,attribute,currency,country,group,customer,price,quantity,reduction,reductiontax,reductiontype,from,	to,newpricex,newpricei)             */
			var blob = fill_discount(i,dcount,"","new", shop,"",	   currency,country,group,"",	   	"",	quantity,reduction,reductiontax,reductiontype,datefrom,dateto,targetprice, targetpriceVAT);
			var new_div = document.createElement("div");
			new_div.innerHTML = blob;
			var adder = document.getElementById("discount_adder"+i);
			adder.parentNode.insertBefore(new_div,adder);
			discount_delayed.push([i, dcount]);
			count_root.value = dcount+1;
			changed = true;
		}
		else if(action == "remove") 
		{ if(fieldtext == "attachmnts") /* attachment remove */
		  { var list = document.getElementById("attachmentsel"+i);
			len = list.length;
			for(x=0; x<len; x++)
			{ if(list[x].value == myval)
			  { list.selectedIndex = x;
				Removeattachment(i);
				changed = true;
				break;
			  }
			}
		  }
		  else if(fieldtext == "carrier")			/* remove carrier from product */
		  {	var list = document.getElementById("carriersel"+i);
			len = list.length;
			for(x=0; x<len; x++)
			{ if(list[x].value == myval)
			  { list.selectedIndex = x;
				Removecarrier(i);
				changed = true;
				break;
			  }
			}
		  }
		  else if(fieldtext == "category")			/* remove category from product */
		  {	var list = document.getElementById("categorysel"+i);
			len = list.length;
			for(x=0; x<len; x++)
			{ if(list[x].value == myval)
			  { list.selectedIndex = x;
				Removecategory(i);
				changed = true;
				break;
			  }
			}
		  }
		  else if (fieldtext == "discount")	/* discount remove */
		  { var count_root = eval("Mainform.discount_count"+i);
			var dcount = parseInt(count_root.value);
			for(x=0; x<dcount; x++)
			{ if((subfieldname == "shop") || (subfieldname == "currency") ||(subfieldname == "reductiontype"))
			  {	var subroot = eval("Mainform.discount_"+subfieldname+x+"s"+i);
				var subvalue = subroot.value;
			  }
			  else
				var subvalue = eval("Mainform.discount_"+subfieldname+x+"s"+i+".value");
			  if(subvalue == subfield)
			  { del_discount(i,x);
			  }
			}
		  }
		  else if(fieldname == "shopz")
		  { var chklength = field.length;             
			for(var k=0;k< chklength;k++)
			{ if((field[k].value == myval) && field[k].checked)
			  { field[k].checked = false;
			    changed = true;
			  }
			}
			field = field[0]; /* prepare for reg_change call */
		  }	
		  else if(fieldtext == "supplier")			/* remove supplier from product */
		  {	var list = document.getElementById("suppliersel"+i);
			len = list.length;
			for(x=0; x<len; x++)
			{ if(list[x].value == myval)
			  { list.selectedIndex = x;
				Removesupplier(i);
				changed = true;
				break;
			  }
			}
		  }
		  else if (fieldtext == "tags")	/* tags remove */
		  { myval2 = field.value.split(",");
			var tagfound = false;
			for (index = 0; index < myval2.length; index++) 
			{ if(myval2[index] == myval)
			  { myval2.splice(index,1);
				tagfound=true;
				break;
			  }
			}
			if(!tagfound) continue;
			field.value = myval2.join(",");
			changed = true;
		  }
		}
		else if(action == "replace from field") 
		{ oldvalue = field.value;
		  myval2 = field.parentNode.parentNode.cells[cellindex].innerHTML;
		  if(copyfield == "name")
			myval2 = myval2.replace(/<[^>]*>/gm, "");
		  if(fieldname == "meta_description")
			myval2 = myval2.replace(/<(?:.|\n)*?>/gm, "");
		  evax = new RegExp(oldval,"g");
		  myval2 = field.value.replace(evax, myval2);
		  if(oldvalue != myval2) changed = true;
		}
		else if(action == "copy from field")
		{ oldvalue = field.value;
		  myval2 = field.parentNode.parentNode.cells[cellindex].innerHTML;
		  if(copyfield == "name")
			myval2 = myval2.replace(/<[^>]*>/gm, "");
		  if(fieldname == "meta_description")
			myval2 = myval2.replace(/<(?:.|\n)*?>/gm, "");
		  else if((pos = featurelist.indexOf(fieldtext)) != -1)
			myval2 = myval2.replace(/<\/?b>/gm, "");			  
		  if(oldvalue != myval2) changed = true;
		}
		else if(fieldtext == "virtualp")
		{ oldvalue = field.value;
		  if(date_expiration != "")
		  { if(date_expiration != field.value) changed = true;
			field.value = date_expiration;
		  }
		  field = document.getElementById("is_virtual"+i); /* due to the double fieldname trick you cannot use Mainform.is_virtual here */
		  if(is_virtual != "")
		  { if(field.checked && (is_virtual == "0"))
			{ field.checked = false; changed = true; }
		    if(!(field.checked) && (is_virtual == "1"))
			{ field.checked = true; changed = true; }
		  }	
		  field = eval("document.Mainform.nb_days_accessible"+i);
		  if(nb_days_accessible != "")
		  { if(nb_days_accessible != field.value) changed = true;
			field.value = nb_days_accessible;
		  }
		  field = eval("document.Mainform.nb_downloadable"+i);
		  if(nb_downloadable != "")
		  { if(nb_downloadable != field.value) changed = true;
			field.value = nb_downloadable;	
		  }
		}
		else if (fieldtext == "tags")
		  myval2 = myval.substring(0,32);
		else myval2 = myval;
		
		/* now implement the new values */
		if((fieldname == "VAT") || (fieldtext == "manufacturer"))
		{	oldvalue = field.selectedIndex;
			field.selectedIndex = myval2;
			if(oldvalue != myval2) changed = true;
		}
		else if((fieldtext == "active") || (fieldtext == "on_sale") || (fieldtext == "online_only"))
		{   field = field[1];
			oldvalue = field.checked;
			field.checked = myval;
			if(oldvalue != myval) changed = true;
		}
		else if(fieldtext.match(/feature[0-9]+field/))
		{	field2 = eval("document.Mainform."+fieldname+"_sel"+i);
			oldvalue = field.value;
			if(field2)
				oldvalue2 = field2.selectedIndex;
			if(custom == 0)
			{	if((action != "replace") || (field2.selectedIndex == oldval))
				{ field2.selectedIndex = myval;
				  field.value = "";
				}
			}
			else
			{	if(field2) // if there is select box
				  field2.selectedIndex = 0;
				field.value = myval2;
			}
			if((oldvalue != field.value) || (field2 && (oldvalue2 != field2.selectedIndex)))
				changed = true;
		}
		else if((action != "add") && (action != "remove") && (action != "set as default") && (action != "TinyMCE") 
			&& (action != "TinyMCE-deluxe") && (action != "add fixed target discount") && (fieldname != "image")
			&& (fieldname != "virtualp"))
		{	oldvalue = field.value;
			field.value = myval2;
			if(oldvalue != myval2) changed = true;
		}
		if((fieldname == "price") && changed)
			price_change(field);
		else if((fieldname == "priceVAT") && changed)
			priceVAT_change(field);
		else if((fieldname == "VAT") && changed)
			VAT_change(field);
		else if(fieldname == "image")
		{ 
			
		}
		else if(fieldname == "stockflags") /* you cannot check here for "changed" as that would exclude a second mass edit with a different warehouse */
		{	if(changed)
			  stockflags_change(field);
			if(myval=="3")
			{ var warehouse = eval("document.Mainform.stockflags_warehouse"+i);
			  if(warehouse) /* warehouse is not present for products that are already ASM with Warehousing */
				warehouse.value = mywarehouse;
			}
		}
		if(((fieldname == "description") || (fieldtext == "description_short")) && (field.parentNode.childNodes[0].tagName == "DIV"))
			base.value = tinyMCE.get(fieldname+i).setContent(field.value);	

		if(changed) /* we flag only those really changed */
			reg_change(field);
	}
  }

  
  function changeMfield()  /* change input fields for mass update when field is selected */
  { base = eval("document.massform.field");
	fieldtext = base.options[base.selectedIndex].value;
	
	if(myarray[fieldtext])
	  myarr = myarray[fieldtext];
	else
	  myarr = myarray["default"];
	var muspan = document.getElementById("muval");
	for(i=0; i<myarray["name"].length; i++) /* use here .length to prepare for extra elements */
	{	if(myarr[i] == 0)
		{	document.massform.action.options[i+1].style.display = "none";
			document.massform.action.options[i+1].disabled = true;
		}
		else
		{	document.massform.action.options[i+1].style.display = "block";
			document.massform.action.options[i+1].disabled = false;
		}
	}
	document.massform.action.selectedIndex = 0;

	if(fieldtext == "VAT")  muspan.innerHTML = "<select name=\"myvalue\">"+taxblock; 
	else if(fieldtext == "category") muspan.innerHTML = "<select name=\"myvalue\">"+categoryblock1;	
	else if(fieldtext == "carrier") muspan.innerHTML = "<select name=\"myvalue\">"+carrierblock1;		
	else if(fieldtext == "supplier") muspan.innerHTML = "<select name=\"myvalue\">"+supplierblock1;	
	else if(fieldtext == "attachmnts") muspan.innerHTML = "<select name=\"myvalue\">"+attachmentblock1;	
	else if(fieldtext == "manufacturer") muspan.innerHTML = "<select name=\"myvalue\">"+manufacturerblock;
	else if(fieldtext == "availorder") muspan.innerHTML = "<select name=\"myvalue\">"+availorderblock;
	else if(fieldtext == "visibility") muspan.innerHTML = "<select name=\"myvalue\">"+visibilityblock;
	else if(fieldtext == "condition") muspan.innerHTML = "<select name=\"myvalue\">"+conditionblock;
	else if(fieldtext == "pack_stock_type") muspan.innerHTML = "<select name=\"myvalue\">"+pack_stock_typeblock;		
	else if(fieldtext == "out_of_stock") muspan.innerHTML = "<select name=\"myvalue\">"+out_of_stockblock;		
	else if(fieldtext == "stockflags") muspan.innerHTML = "<select name=\"myvalue\" onchange=\"stockflags_mass_change(this)\"><option value=\"1\">Manual</option><option value=\"2\">Adv Stock Management</option><option value=\"3\">ASM with Warehousing</option></select>"+
		" <div id=stockspan style=\"display:none\">Move stock of shop(s) to warehouse: <select name=stockflags_warehouse>"+warehouseblock+"</div>";
	else if(fieldtext == "shopz") 
	{ var shopz = shop_ids.split(",");
	  tmp = " shop nr. <select name=\"myvalue\">";
	  for(var i=0; i<shopz.length; i++)
	  { tmp += "<option>"+shopz[i]+"</option>";
	  }
	  muspan.innerHTML = tmp+"</select>";
	}
	else muspan.innerHTML = "value: <textarea name=\"myvalue\" class=\"masstarea\"></textarea>";
  }
	  
/* this function comes from admin.js in PS 1.4.9 */
function str2url(str)
{
	str = str.toUpperCase();
	str = str.toLowerCase();

	/* Lowercase */
	str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E4\u00E5\u0101\u0103\u0105]/g, "a");
	str = str.replace(/[\u00E7\u0107\u0109\u010D]/g, "c");
	str = str.replace(/[\u010F\u0111]/g, "d");
	str = str.replace(/[\u00E8\u00E9\u00EA\u00EB\u0113\u0115\u0117\u0119\u011B]/g, "e");
	str = str.replace(/[\u011F\u0121\u0123]/g, "g");
	str = str.replace(/[\u0125\u0127]/g, "h");
	str = str.replace(/[\u00EC\u00ED\u00EE\u00EF\u0129\u012B\u012D\u012F\u0131]/g, "i");
	str = str.replace(/[\u0135]/g, "j");
	str = str.replace(/[\u0137\u0138]/g, "k");
	str = str.replace(/[\u013A\u013C\u013E\u0140\u0142]/g, "l");
	str = str.replace(/[\u00F1\u0144\u0146\u0148\u0149\u014B]/g, "n");
	str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F6\u00F8\u014D\u014F\u0151]/g, "o");
	str = str.replace(/[\u0155\u0157\u0159]/g, "r");
	str = str.replace(/[\u015B\u015D\u015F\u0161]/g, "s");
	str = str.replace(/[\u00DF]/g, "ss");
	str = str.replace(/[\u0163\u0165\u0167]/g, "t");
	str = str.replace(/[\u00F9\u00FA\u00FB\u00FC\u0169\u016B\u016D\u016F\u0171\u0173]/g, "u");
	str = str.replace(/[\u0175]/g, "w");
	str = str.replace(/[\u00FF\u0177\u00FD]/g, "y");
	str = str.replace(/[\u017A\u017C\u017E]/g, "z");
	str = str.replace(/[\u00E6]/g, "ae");
	str = str.replace(/[\u0153]/g, "oe");

	/* Uppercase */
	str = str.replace(/[\u0100\u0102\u0104\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5]/g, "A");
	str = str.replace(/[\u00C7\u0106\u0108\u010A\u010C]/g, "C");
	str = str.replace(/[\u010E\u0110]/g, "D");
	str = str.replace(/[\u00C8\u00C9\u00CA\u00CB\u0112\u0114\u0116\u0118\u011A]/g, "E");
	str = str.replace(/[\u011C\u011E\u0120\u0122]/g, "G");
	str = str.replace(/[\u0124\u0126]/g, "H");
	str = str.replace(/[\u0128\u012A\u012C\u012E\u0130]/g, "I");
	str = str.replace(/[\u0134]/g, "J");
	str = str.replace(/[\u0136]/g, "K");
	str = str.replace(/[\u0139\u013B\u013D\u0139\u0141]/g, "L");
	str = str.replace(/[\u00D1\u0143\u0145\u0147\u014A]/g, "N");
	str = str.replace(/[\u00D3\u014C\u014E\u0150]/g, "O");
	str = str.replace(/[\u0154\u0156\u0158]/g, "R");
	str = str.replace(/[\u015A\u015C\u015E\u0160]/g, "S");
	str = str.replace(/[\u0162\u0164\u0166]/g, "T");
	str = str.replace(/[\u00D9\u00DA\u00DB\u00DC\u0168\u016A\u016C\u016E\u0170\u0172]/g, "U");
	str = str.replace(/[\u0174]/g, "W");
	str = str.replace(/[\u0176]/g, "Y");
	str = str.replace(/[\u0179\u017B\u017D]/g, "Z");
	str = str.replace(/[\u00C6]/g, "AE");
	str = str.replace(/[\u0152]/g, "OE");
	str = str.toLowerCase();

	str = str.replace(/\&amp\;/," '.$and_code.' "); /* added */
	str = str.replace(/[^a-z0-9\s\/\'\:\[\]-]/g,"");
	str = str.replace(/[\u0028\u0029\u0021\u003F\u002E\u0026\u005E\u007E\u002B\u002A\u003A\u003B\u003C\u003D\u003E]/g, "");
	str = str.replace(/[\s\'\:\/\[\]-]+/g, " ");

	// Add special char not used for url rewrite
	str = str.replace(/[ ]/g, "-");
//	str = str.replace(/[\/\'\"|,;]*/g, "");

	str = str.replace(/-$/,""); /* added */

	return str;
}

	function changeMAfield()
	{ var base = eval("document.massform.action");
	  var action = base.options[base.selectedIndex].text;
	  base = eval("document.massform.field");
	  var fieldname = base.options[base.selectedIndex].value;
	  var muspan = document.getElementById("muval");
	  if(((fieldname=="active") || (fieldname=="on_sale") || (fieldname=="online_only")) &&(action=="set"))
		muspan.innerHTML = "value: <input type=\"checkbox\" name=\"myvalue\">";
	  else if ((action == "copy from field") || (action == "replace from field"))
	  { tmp = document.massform.field.innerHTML;
	    tmp = tmp.replace("Select a field","Select field to copy from");
		tmp = tmp.replace("<option value=\""+fieldname+"\">"+fieldname+"</option>","");
		tmp = tmp.replace("<option value=\"active\">active</option>","");
		tmp = tmp.replace("<option value=\"category\">category</option>","");
		tmp = tmp.replace("<option value=\"image\">image</option>","");
		tmp = tmp.replace("<option value=\"accessories\">accessories</option>","");
		tmp = tmp.replace("<option value=\"combinations\">combinations</option>","");
		tmp = tmp.replace("<option value=\"discount\">discount</option>","");
		tmp = tmp.replace("<option value=\"carrier\">carrier</option>","");
		if (action == "copy from field")
	       muspan.innerHTML = "<select name=copyfield>"+tmp+"</select>";
		else /* replace from field */
			muspan.innerHTML = "text to replace <textarea name=\"oldval\" class=\"masstarea\"></textarea> <select name=copyfield>"+tmp+"</select>";
		if(fieldname == "image")
			muspan.innerHTML += " &nbsp; covers only <input type=radio name=coverage value=cover> &nbsp; <input type=radio name=coverage value=all checked> all images &nbsp; &nbsp; <input type=checkbox name=emptyonly> Empty only";
	  }
	  else if(fieldname.match(/feature[0-9]*field/))
	  { var fld = fieldname.substr(7).replace("field","");
		var idx = featurekeys.indexOf(fld);
		if(featureblocks[idx] == "")
		{ if (action == "set")
			   muspan.innerHTML = "value: <textarea name=\"myvalue\" class=\"masstarea\" onkeyup=\"feature_change_event(event)\"></textarea>"; 
		  else /* replace */
			   muspan.innerHTML = "old: <textarea name=\"oldval\" class=\"masstarea\"></textarea> new: <textarea name=\"myvalue\" class=\"masstarea\"></textarea>";
		}
		else
	  	{ if (action == "set")
				muspan.innerHTML = "<select name=\"myvalue_sel\" onchange=\"feature_change(this)\">"+featureblocks[idx]+"<textarea name=\"myvalue\" class=\masstarea\" onkeyup=\"feature_change_event(event)\"></textarea>"; 
		  else /* replace */
			    muspan.innerHTML = "old: <select name=\"oldval_sel\" onchange=\"feature_change(this)\">"+featureblocks[idx]+"<textarea name=\"oldval\" class=\"masstarea\" onkeyup=\"feature_change_event(event)\"></textarea>"
					+"new: <select name=\"myvalue_sel\" onchange=\"feature_change(this)\">"+featureblocks[idx]+"<textarea name=\"myvalue\" class=\"masstarea\" onkeyup=\"feature_change_event(event)\"></textarea>";
		}
	  }
	  else if (action == "replace") muspan.innerHTML = "old: <textarea name=\"oldval\" class=\"masstarea\"></textarea> new: <textarea name=\"myvalue\" class=\"masstarea\"></textarea>";
	  else if (action == "increase%") muspan.innerHTML = "Percentage (can be negative): <input name=\"myvalue\">";
	  else if (action == "increase amount") muspan.innerHTML = "Amount (can be negative): <input name=\"myvalue\">";
	  else if (action == "copy from other lang") muspan.innerHTML = "Select language to copy from: <select name=copylang>"+langcopyselblock+"</select>. This affects name, description and meta fields.";
	  else if((action=="TinyMCE") || (action=="TinyMCE-deluxe"))
	    muspan.innerHTML = "";
	  else if ((fieldname=="discount") &&(action=="add"))
/* 					0			1				2		3		4			5		6			7			8			9	 			10			11	  12	*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_tax, reduction_type, from, to */
	  { tmp = "<br/>";
	    tmp += "<select name=shop style=\"width:100px\"><option value=0>All shops</option>"+shopblock.replace(" selected","")+"</select>";
		tmp += " &nbsp; ";
	    tmp += "<select name=currency><option value=0>All cs</option>"+currencyblock+"</select>";
	    tmp += " &nbsp; <select name=country style=\"width:100px\"><option value=0>All countries</option>"+countryblock+"</select>";
		tmp += " &nbsp; <select name=group style=\"width:90px\"><option value=0>All groups</option>"+groupblock+"</select>";
		tmp += " &nbsp; Cust.id<input name=customer style=\"width:30px\">";
		tmp += " &nbsp; FromPrice<input name=price style=\"width:50px\">";
		tmp += " &nbsp; Min.Qu.<input name=quantity style=\"width:20px\" value=\"1\">";
		tmp += " &nbsp; discount<input name=reduction style=\"width:50px\">";
		if (prestashop_version >= "1.6.0.11")
			tmp += "<select name=reductiontax><option value=0>excl tax</option><option value=1 selected>incl tax</option></select>";
		else
			tmp += "<select name=reductiontax><option value=1 selected>incl tax</option></select>";
		tmp += " &nbsp; <select name=reductiontype><option>amt</option><option>pct</option></select>";
		tmp += " &nbsp;period:<input name=datefrom style=\"width:70px\">";
		tmp += "-<input name=dateto style=\"width:70px\"> (yyyy-mm-dd)";
		tmp += "<br/>";
	    muspan.innerHTML = tmp;
	  }
	  else if ((fieldname=="discount") &&(action=="add fixed target discount"))
/* 					0			1				2		3		4			5		6			7			8			9	 			10			11	  12	*/
/* discount fields: shop, product_attribute, currency, country, group, id_customer, price, from_quantity, reduction, reduction_tax, reduction_type, from, to */
	  { tmp = "<select name=shop style=\"width:100px\"><option value=0>All shops</option>"+shopblock.replace(" selected","")+"</select>";
		tmp += "<select name=currency><option value=0>All cs</option>"+currencyblock+"</select>";
	    tmp += " &nbsp; <select name=country style=\"width:100px\"><option value=0>All countries</option>"+countryblock+"</select>";
		tmp += " &nbsp; <select name=group style=\"width:90px\"><option value=0>All groups</option>"+groupblock+"</select>";
		tmp += " &nbsp; Cust.id<input name=customer style=\"width:30px\">";
		tmp += " &nbsp; Min.Qu.<input name=quantity style=\"width:20px\" value=\"1\">";
		tmp += " &nbsp;period:<input name=datefrom style=\"width:70px\">";
		tmp += "-<input name=dateto style=\"width:70px\"> (yyyy-mm-dd)";
		tmp += " &nbsp; Target price: <input name=targetprice style=\"width:30px\" onkeyup=\"fixed_target_discount_change(this)\"> Excl VAT";
		tmp += " &nbsp; <input name=targetpriceVAT style=\"width:30px\" onkeyup=\"fixed_target_discount_change(this)\"> Incl VAT<br/>";
		tmp += "This specialized function creates discounts with the same outcome.<br/>If you";
	    tmp += " set a target of 10 and a product costs 12 it gets a discount of 2. If it"; 
		tmp += " costs 40 its discount will be 30. If its price is below 10 no discount is added.<br/>";
	    muspan.innerHTML = tmp;
	  }	  
	  else if ((fieldname=="discount") &&(action=="remove"))
	  { tmp = " &nbsp; where &nbsp; ";
	    tmp += "<select name=fieldname style=\"width:150px\" onchange=\"dc_field_optioner()\"><option>Select subfield</option><option>shop</option><option>currency</option><option>country</option><option>group</option>";
	    tmp += "<option>price</option><option>quantity</option><option>reduction</option><option>reductiontype</option><option>date_from</option><option>date_to</option></select>";
		tmp += "<span id=\"dc_options\">";
	    muspan.innerHTML = tmp;
	  }
	  else if (fieldname=="virtualp")
	  { tmp = " &nbsp; ";
		tmp += 'is_virtual <input size=1 name="is_virtual"> &nbsp; ';
	    tmp += 'Exp date <input name="date_expiration" > &nbsp; Days <input name="nb_days_accessible" style="width:45px;">';
		tmp += ' &nbsp; Downloads <input name="nb_downloadable" style="width:45px;">';
		tmp += '<br>Only filled fields will be updated!';
	    muspan.innerHTML = tmp;
	  }
	  else if (document.massform.action.options[3].style.display == "block")
		muspan.innerHTML = "value: <textarea name=\"myvalue\" class=\"masstarea\"></textarea>";
	  else if (((fieldname=="price") || (fieldname=="priceVAT") || (fieldname=="qty")) &&(action=="set"))
		muspan.innerHTML = "value: <input name=\"myvalue\">";
	}
	
	/* for massedit discount remove: gives subfield options */
	function dc_field_optioner()
	{ var base = eval("document.massform.fieldname");
	  var fieldname = base.options[base.selectedIndex].text;
	  var tmp = "";
	  if (fieldname == "shop") 
	    tmp = "<select name=subfield style=\"width:100px\"><option value=0>'.t('All shops').'</option>"+shopblock+"</select>";
	  else if (fieldname == "currency") 
	    tmp = "<select name=subfield style=\"width:100px\"><option value=0>'.t('All currencies').'</option>"+currencyblock+"</select>";	
	  else if (fieldname == "country") 
	    tmp = "<select name=subfield style=\"width:100px\"><option value=0>'.t('All countries').'</option>"+countryblock+"</select>";
	  else if (fieldname == "group") 
	    tmp = "<select name=subfield style=\"width:100px\"><option value=0>'.t('All groups').'</option>"+groupblock+"</select>";	
	  else if (fieldname == "reductiontype") 
	    tmp = "<select name=subfield style=\"width:100px\"><option>amt</option><option>pct</option></select>";		
	  else 
	    tmp = "<input name=subfield size=40>";
	  var fld = document.getElementById("dc_options");
	  fld.innerHTML = " = "+tmp;
	}
	  
	function salesdetails(product)
	{ window.open("product-sales.php?product="+product+"&startdate="+startdate+"&enddate="+enddate+"&id_shop="+id_shop,"", "resizable,scrollbars,location,menubar,status,toolbar");
      return false;
    }

function fixed_target_discount_change(elt)
{ if(elt.name == "targetprice")
    massform.targetpriceVAT.value = "";
  else
    massform.targetprice.value = "";    	  
}

function sortTheTable(tab,col,flag) 
{ if(tabchanged != 0)
  { alert('You can only sort the table if it hasn\'t been changed yet!');
    /* copying fields will not copy changed contents!!! */
    return flag;
  }
  return sortTable(tab, col, flag);
}

/* getpath() takes a string like '189' and returns something like '/1/8/9' */
function getpath(name)
{ str = '';
  for (var i=0; i<name.length; i++)
  { str += '/'+name[i];
  }
  return str;
} 

function change_allshops(flag)
{ if(flag == '1')
	document.body.style.backgroundColor = '#ff7';
  else if(flag == '2')
	document.body.style.backgroundColor = '#fc1';
  else
	document.body.style.backgroundColor = '#fff';
}

function SubmitForm()
{ reccount = numrecs3;
//baseName = window.location.pathname.substring(window.location.pathname.lastIndexOf('/')+1) om return adres door te geven
  submitted=0;
  for(var i=0; i<reccount; i++)
  { divje = document.getElementById('trid'+i);
    if(!divje)
      continue;
	var chg = divje.getAttribute('changed');

	var docfield = eval("document.Mainform.description"+i);
	if(docfield)
	{ if (docfield.parentNode.childNodes[0].tagName == "DIV")
	  { var tmp = tinyMCE.get(docfield.name).getContent();
	    if(tmp != docfield.value)
	    { docfield.value = tmp;
	      chg = 1;
		}
	  }
	  else
	    docfield.value = tidy_html(docfield.value);
	}
	
	docfield = eval("document.Mainform.description_short"+i);
	if(docfield)
	{ if (docfield.parentNode.childNodes[0].tagName == "DIV")
	  { var tmp = tinyMCE.get(docfield.name).getContent();
	    if(tmp != docfield.value)
	    { docfield.value = tmp;
	      chg = 1;
		}
	  }
	  else
	    docfield.value = tidy_html(docfield.value);
	}
	
	var carrier = eval("document.Mainform.carriersel"+i);
	if(carrier)			/* note that this  will cause the carrier to be empty rather than none when there is an error message */
	{ if (carrier.options[0].value == "none")
	  { carrier.options.length=0;
	  }
	}
	
    if(chg == 0)
    { divje.parentNode.innerHTML='';
    }
	else
	{ submitted++;
	  if((fields.indexOf("discount") !== -1) && (!check_discounts(i))) return false;
	  if(!check_shopz(i)) return false; /* check that at least one shop is selected */
	}
  }
  var tmp = document.getElementById('featureblock0');
  if(tmp.style.display == 'none')
    Mainform.featuresset.value = 0;
  else
    Mainform.featuresset.value = 1;  
  Mainform.verbose.value = SwitchForm.verbose.checked;
  Mainform.skipindexation.value = IndexForm.skipindexation.checked;
  Mainform.urlsrc.value = location.href;
  Mainform.action = 'product-proc.php?c='+reccount+'&d='+submitted;
  Mainform.submit();
}

