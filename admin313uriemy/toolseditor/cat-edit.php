<?php
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($input['search_txt'])) $input['search_txt'] = "";
if(isset($input['startrec'])) $startrec = intval($input['startrec']);
else $startrec = 0;
if(isset($input['numrecs'])) $numrecs = intval($input['numrecs']);
else $numrecs = 250;
if($numrecs == 0) $numrecs = 250;
if(!isset($input['id_lang'])) $input['id_lang']="";
if(!isset($input['id_shop'])) $input['id_shop']="1";
$id_shop = intval($input["id_shop"]);

/* full set: "name","parent","depth","position","active","description","linkrewrite","metatitle","metakeys","metadescription","groups","image" */
  if(empty($input['fields'])) // if not set, set default set of active fields
    $input['fields'] = array("name","parent","depth","position","active","description","linkrewrite","image");

  $infofields = array();
  $if_index = 0;
   /*	0		1		2		3								4						5						6			7	 */
   /* title, keyover, source, display(0=not;1=yes;2=edit;), fieldwidth(0=not set), align(0=default;1=right), sortfield, Editable */
  define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display
  define("LEFT", 0); define("RIGHT", 1); // align
  define("NO_SORTER", 0); define("SORTER", 1); /* sortfield => 0=no escape removal; 1=escape removal; */
  define("NOT_EDITABLE", 0); define("INPUT", 1); define("TEXTAREA", 2); 
  $infofields[$if_index++] = array("","", "", DISPLAY, 0, LEFT, 0,0);
  $infofields[$if_index++] = array("id","", "id_category", DISPLAY, 0, RIGHT, NO_SORTER,NOT_EDITABLE);
 
  $field_array = array(
   "name" => array("name","", "name", DISPLAY, 0, LEFT, NO_SORTER, INPUT),
   "parent" => array("parent","", "parentname", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE),
   "depth" => array("depth","", "level_depth", DISPLAY, 200, LEFT, NO_SORTER, NOT_EDITABLE),
   "position" => array("position","", "position", DISPLAY, 0, RIGHT, NO_SORTER, NOT_EDITABLE),
   "active" => array("active","", "active", DISPLAY, 0, LEFT, NO_SORTER, INPUT),
   "description" => array("description","", "description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA),
   "linkrewrite" => array("link_rewrite","", "link_rewrite", DISPLAY, 0, LEFT, NO_SORTER, INPUT),
   "metatitle" => array("meta_title","", "meta_title", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA),
   "metakeywords" => array("meta_keywords","", "meta_keywords", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA),
   "metadescription" => array("meta_description","", "meta_description", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA),
   "groups" => array("groups","", "groups", DISPLAY, 0, LEFT, NO_SORTER, TEXTAREA), 
   "image" => array("image","", "image", DISPLAY, 0, LEFT, NO_SORTER, NOT_EDITABLE),
   
	/* statistics */
   "prodcount" => array("prodcount",null, "prodcount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "visitcount"),
   "activecount" => array("activecount",null, "activecount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "visitedpages"),
   "dircount" => array("dircount",null, "dircount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "salescount"),
   "activedircount" => array("activedircount",null, "activedircount", DISPLAY, 0, LEFT, null, NOT_EDITABLE, "revenue"),
   );
  
  foreach($field_array AS $key => $value)
  { if (in_array($key, $input["fields"]))
    { 	$infofields[$if_index++] = $value;
	}
  }

$rewrite_settings = get_rewrite_settings();

/* get default language: we use this for the categories, manufacturers */
$query="select value, l.name, iso_code from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$def_lang = $row['value'];
$def_langname = $row['name'];
$iso_code = $row['iso_code'];

/* Get default language if none provided */
if($input['id_lang'] == "") 
  $id_lang = $def_lang;
else
{ $query="select name, iso_code from ". _DB_PREFIX_."lang WHERE id_lang='".(int)$input['id_lang']."'";
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  $languagename = $row['name'];
  $id_lang = $input['id_lang'];
  $iso_code = $row['iso_code'];
}

$and_code = "and"; /* this is the word that will be used instead of the ampersand when you regenerate link_rewrites */
if($iso_code == "fr") $and_code = "et";
if($iso_code == "es") $and_code = "y";
if($iso_code == "de") $and_code = "und";
if($iso_code == "it") $and_code = "e";
if($iso_code == "nl") $and_code = "en";

/* make group block */
if(in_array('groups', $input["fields"]))
{ $query = "select id_group,name from ". _DB_PREFIX_."group_lang WHERE id_lang='".$id_lang."'";
  $res=dbquery($query);
  $groupblock0 = '<input type=hidden name="mygroupsCQX">';
  $groupblock0 .= '<table cellspacing=8><tr><td><select id="grouplistCQX" size=4 multiple>';
  $groupblock1 = "";
  while ($row=mysqli_fetch_array($res)) 
  { $groupblock1 .= '<option value="'.$row['id_group'].'">'.str_replace("'","\'",$row['name']).'</option>';
  } 
  $groupblock1 .= '</select>';
  $groupblock2 = '</td><td><a href=# onClick=" AddGroup(\\\'CQX\\\'); reg_change(this); return false;"><img src=add.gif border=0></a><br><br>';
  $groupblock2 .= '<a href=# onClick="RemoveGroup(\\\'CQX\\\'); reg_change(this); return false;"><img src=remove.gif border=0></a></td><td><select id=groupselCQX size=3><option>none</option></select></td></tr></table>';
}  
else 
  $groupblock0 = $groupblock1 = $groupblock2 = ""; 

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<?php  // for security reasons the location of Prestools should be secret. So we dont give referer when you click on Prestools.com 
if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false  || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false) 
  echo '<meta name="referrer" content="no-referrer">';
else
  echo '<meta name="referrer" content="none">';	
?>
<title>Prestashop Category Multiedit</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script src="tinymce/tinymce.min.js"></script> <!-- Prestashop settings can be found at /js/tinymce.inc.js -->
<script type="text/javascript">
var rowsremoved = 0;
var groupblock0 = '<?php echo $groupblock0 ?>';
var groupblock1 = '<?php echo $groupblock1 ?>';
var groupblock2 = '<?php echo $groupblock2 ?>';

function RemoveRow(row)
{ var tblEl = document.getElementById("offTblBdy");
  var trow = document.getElementById("trid"+row).parentNode;
  trow.innerHTML = "<td></td>";
  rowsremoved++;
}

function rorem_alert()
{ alert("You cannot sort the table after rows have been removed!");
}

function RowSubmit(elt)
{ var subtbl = document.getElementById("subtable");
  var row = elt.parentNode.parentNode;
  var rowno = row.childNodes[0].id.substr(4);
  subtbl.innerHTML = '<tr>'+row.innerHTML+'</tr>';
  // field contents are not automatically copied
  var inputs = row.getElementsByTagName('input');
  for(var k=0;k<inputs.length;k++)
  { if(((inputs[k].name.substring(0,6) == "active")) && (inputs[k].type == "hidden"))
	{ elt = document.rowform[inputs[k].name][0]; /* the trick with the hidden field works not with the rowsubmit so we delete it */
	  elt.parentNode.removeChild(elt);
	  continue;
	}
    else if(inputs[k].type != "button")
    { if(((inputs[k].name.substring(0,6) == "active")) && (inputs[k].type == "checkbox"))
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
  rowform.verbose.value = ListForm.verbose.checked;
  rowform.skipindexation.value = IndexForm.skipindexation.checked;  
  document.rowform['id_row'].value = row.childNodes[0].id;
  document.rowform.submit();
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

function reg_unchange(num)	/* change status of row back to unchanged after it has been submitted */
{ var elt = document.getElementById('trid'+num);
  var row = elt.parentNode;
  row.cells[0].setAttribute("changed", "0");
  row.style.backgroundColor="#AAF";
}

function getElsByClassName(classname){
	var rv = []; 
	var elems  = document.getElementsByTagName('*')
	if (elems.length){
		for (var x in elems ){
			if (elems[x] && elems[x].className && elems[x].className == classname){
				rv.push(elems[x]);
			}
		}
	}
	return rv; 
}

function useTinyMCE(elt, field)
{ elt.parentNode.parentNode.childNodes[0].cols="125";
  tinymce.init({
	selector: "#"+field, 
  });
  elt.parentNode.style.display = "none";
}

/* the arguments for this version were derived from source code of the "classic" example on the TinyMCE website */
/* some buttons were removed bu all plugins were maintained */
function useTinyMCE2(elt, field)
{ elt.parentNode.parentNode.childNodes[0].cols="125";
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
  elt.parentNode.style.display = "none";
}  


function AddGroup(plIndex)
{ var list = document.getElementById('grouplist'+plIndex); /* available groups */
  var sel = document.getElementById('groupsel'+plIndex);	/* selected groups */
  var listindex = list.selectedIndex;
  if(listindex==-1) return; /* none selected */
  var i, max = sel.options.length;
  group = list.options[listindex].text;
  grp_id = list.options[listindex].value;
  list.options[listindex]=null;		/* remove from available groups list */
  if(sel.options[0].value == "none")
  { sel.options.length = 0;
    max = 0;
  }
  i=0;
  var base = sel.options;
  while((i<max) && (group > base[i].text)) i++;
  if(i==max)
    base[max] = new Option(group);
  else
  { newOption = new Option(group);
    if (document.createElement && (newOption = document.createElement('option'))) 
    { newOption.appendChild(document.createTextNode(group));
	}
    sel.insertBefore(newOption, base[i]);
  }
  base[i].value = grp_id;
  var mygroups = eval("document.Mainform.mygroups"+plIndex);
  mygroups.value = mygroups.value+','+grp_id;
}

function RemoveGroup(plIndex)
{ var list = document.getElementById('grouplist'+plIndex);
  var sel = document.getElementById('groupsel'+plIndex);
  var selindex = sel.selectedIndex;
  if(selindex==-1) return; /* none selected */
  var i, max = list.options.length;
  group = sel.options[selindex].text;
  if(group == "none") return; /* none selected */
  grp_id = sel.options[selindex].value;
  classname = sel.options[selindex].className;
  if(sel.options.length == 1)
  { alert('There must always be at least one selected group!');
    return; /* leave selection not empty */
  }
  sel.options[selindex]=null;
  i=0;
  while((i<max) && (group > list.options[i].text)) i++;
  if(i==max)
    list.options[max] = new Option(group);
  else
  { newOption = new Option(group);
    if (document.createElement && (newOption = document.createElement('option'))) 
      newOption.appendChild(document.createTextNode(group));
    list.insertBefore(newOption, list.options[i]);
  }
  if(sel.options.length == 0)
    sel.options[0] = new Option("none");
  list.options[i].value = grp_id;
  var mygroups = eval("document.Mainform.mygroups"+plIndex);
  mygroups.value = mygroups.value.replace(','+grp_id, '');
}

function fillGroups(idx,grps)
{ var list = document.getElementById('grouplist'+idx);
  var sel = document.getElementById('groupsel'+idx);
  for(var i=0; i< grps.length; i++)
  { for(var j=0; j< list.length; j++)
	{ if(list.options[j].value == grps[i])
	  { list.selectedIndex = j;
		AddGroup(idx);
	  }
	}
  }
}


parts_stat = 0;
desc_stat = 0;
function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp;
  if(val == '0')
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
      if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='none';
  }
  if((val == '1') || (val=='2'))
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='table-cell';
  }
  if(val=='2')
  { tab = document.getElementById('Maintable');
    var tblEl = document.getElementById(id);
    field = tab.rows[0].cells[fieldno].children[0].innerHTML;
	for(var i=0; i<tblEl.rows.length; i++)
    { if(!tblEl.rows[i].cells[fieldno]) continue; 
      row = tblEl.rows[i].cells[0].childNodes[1].name.substr(11); /* tr td id id_category7 => 7 */
	  tmp = tblEl.rows[i].cells[fieldno].innerHTML;
      if((field=="meta_description"))
        tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" rows="4" cols="35" onchange="reg_change(this);">'+tmp+'</textarea>';
	  else if(field == "description")
      { tblEl.rows[i].cells[fieldno].innerHTML = '<textarea name="'+field+row+'" id="'+field+row+'" rows="4" cols="35" onchange="reg_change(this);">'+tmp+'</textarea>'
		tblEl.rows[i].cells[fieldno].innerHTML += '<div class="TinyLine"><a href="#" onclick="useTinyMCE(this, \''+field+row+'\'); return false;">TinyMCE</a>&nbsp;<a href="#" onclick="useTinyMCE2(this, \''+field+row+'\'); return false;">TinyMCE-deluxe</a></div>';
	  }
      else if(field == "active")
	  { if(tmp==1) checked="checked"; else checked="";
	    tblEl.rows[i].cells[fieldno].innerHTML = '<input type=hidden name="'+field+row+'" id="'+field+row+'" value="0" /><input type=checkbox name="'+field+row+'" id="'+field+row+'" onchange="reg_change(this);" value="1" '+checked+' />';
	  }
      else if(field == "groups")
      { var grps = new Array();
	    var tab = document.getElementById('groups'+row);
	    if(tab) /* do nothing for the root */
		{ for(var y=0; y<tab.rows.length; y++)
		  {	grps[y] = tab.rows[y].cells[0].id;
		  }
	      tblEl.rows[i].cells[fieldno].innerHTML = (groupblock0.replace(/CQX/g, row))+groupblock1+(groupblock2.replace(/CQX/g, row));
	      fillGroups(row,grps);	  
		}
	  }
	  else
	  { tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp.replace("'","\'")+'" onchange="reg_change(this);" />';
      }
	}
	var cell = elt.parentElement;
    tmp = cell.innerHTML.replace(/<br.*$/,'');
	if((field == "description") || (field == "description_short") || (field == "meta_description"))
	  cell.innerHTML = tmp+'<br>Edit<br><img src=minus.png title="make field less high" onclick="grow_textarea(\''+field+'\','+fieldno+', -1, 0);"><b>H</b><img src=plus.png title="make field higher" onclick="grow_textarea(\''+field+'\','+fieldno+', 1, 0);"><br><img src=minus.png title="make field less wide" onclick="grow_textarea(\''+field+'\','+fieldno+', 0, -7);"><b>W</b><img src=plus.png title="make field wider" onclick="grow_textarea(\''+field+'\','+fieldno+', 0, 7);">';	
	else if((field == "meta_keywords") || (field == "meta_title") || (field == "name"))
	  cell.innerHTML = tmp+'<br>Edit<br><nobr><img src="minus.png" title="make field less wide" onclick="grow_input(\''+field+'\','+fieldno+', -7);"><b>W</b><img src="plus.png" title="make field wider" onclick="grow_input(\''+field+'\','+fieldno+', 7);"></nobr>';
	else
	  cell.innerHTML = tmp+"<br><br>Edit";
  }
  return;
}

function grow_input(field, fieldno, width)
{ var tblEl = document.getElementById("offTblBdy");
  var size = -1;
  for(var i=0; i<tblEl.rows.length; i++)
  { if(!tblEl.rows[i].cells[fieldno]) continue; 
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(11); /* id_category is 11 chars long */
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
	row = tblEl.rows[i].cells[0].childNodes[1].name.substring(11); /* id_category is 11 chars long */
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

function SubmitForm()
{ var submitted=0;
  var reccount = Mainform.reccount.value;
  for(i=0; i<reccount; i++)
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
	    docfield.value = tidy_html(docfield.value)
	}
	if(chg == 0)
      divje.parentNode.innerHTML='';
	else
	  submitted++;
  }
  Mainform.verbose.value = ListForm.verbose.checked;
  Mainform.skipindexation.value = IndexForm.skipindexation.checked; 
  Mainform.urlsrc.value = location.href;  
  Mainform.action = 'cat-proc.php?c='+reccount+'&d='+submitted;
  Mainform.submit();
}

function set_visibility()
{ var browserName=navigator.appName;
  for(i=0; i<silencers.length; i++)
  { col = document.getElementById("col"+silencers[i]);
    if (browserName=="Microsoft Internet Explorer")
      col.style.display = "none";
    else
      col.style.visibility = "collapse";
  }
}

	var myarray = []; /* define which actions to show for which fields */
	/* 	0=set, 1=insert before, 2=insert after, 3=replace, 4=regenerate, 5=copy from default lang, 6=copy from field, 7=add, 8=remove */
	
<?php
/* the following section is for Mass Edit */
    if($id_lang == $def_lang)	/* prepare for "copy from default lang" */
	{ echo '
	  myarray["name"] = 				[1,1,1,1,0,0,0,0,0];
	  myarray["description"] = 			[1,1,1,1,0,0,1,0,0];	  
	  myarray["meta_title"] = 			[1,1,1,1,0,0,0,0,0];
	  myarray["meta_keywords"] = 		[1,1,1,1,0,0,0,0,0];
	  myarray["link_rewrite"] = 		[1,1,1,1,1,0,0,0,0];
	  myarray["meta_description"] = 	[1,1,1,1,0,0,1,0,0];';	  
	}
	else
	{ echo '
	  myarray["name"] = 				[1,1,1,1,0,1,0,0,0];
	  myarray["description"] = 			[1,1,1,1,0,1,1,0,0];	  
	  myarray["meta_title"] = 			[1,1,1,1,0,1,0,0,0];
	  myarray["meta_keywords"] = 		[1,1,1,1,0,1,0,0,0];
	  myarray["link_rewrite"] = 		[1,1,1,1,1,1,0,0,0];
	  myarray["meta_description"] = 	[1,1,1,1,0,1,1,0,0];';
	}
	echo '
	  myarray["active"] = 				[1,0,0,0,0,0,0,0,0];
	  myarray["groups"] = 				[0,0,0,0,0,0,0,1,1];
		
	  function changeMfield()  /* change input fields for mass update when field is selected */
	  { base = eval("document.massform.field");
		fieldtext = base.options[base.selectedIndex].text;
		myarr = myarray[fieldtext];
		var muspan = document.getElementById("muval");
		for(i=0; i<9; i++) /*  */
		{	if(myarr[i] == 0)
				document.massform.action.options[i+1].style.display = "none";
			else
				document.massform.action.options[i+1].style.display = "block";
		}
		document.massform.action.selectedIndex = 0;
		
		if(fieldtext == "groups") muspan.innerHTML = "<select name=\"myvalue\">"+groupblock1;	
		else muspan.innerHTML = "value: <input name=\"myvalue\">";
	  }
	  
	function changeMAfield()
	{ var base = eval("document.massform.action");
	  var action = base.options[base.selectedIndex].text;
	  base = eval("document.massform.field");
	  var fieldname = base.options[base.selectedIndex].text;
	  var muspan = document.getElementById("muval");
	  if((fieldname=="active") &&(action=="set"))
		muspan.innerHTML = "value: <input type=\"checkbox\" name=\"myvalue\">";
	  else if (action == "replace") muspan.innerHTML = "old: <input name=\"oldval\"> new: <input name=\"myvalue\">";
	  else if (action == "copy from default lang") muspan.innerHTML = "This affects name, description and meta fields";
	  else if (action == "copy from field") 
	  { tmp = document.massform.field.innerHTML;
	    tmp = tmp.replace("select a field","select field to copy from");
		tmp = tmp.replace("<option value=\""+fieldname+"\">"+fieldname+"</option>","");
		tmp = tmp.replace("<option value=\"active\">active</option>","");
		tmp = tmp.replace("<option value=\"category\">category</option>","");
	    muspan.innerHTML = "<select name=copyfield>"+tmp+"</select>";
	  }
	  else if ((action != "add") && (action != "remove"))
		muspan.innerHTML = "value: <input name=\"myvalue\">";
	}
		  
	  function massUpdate()
	  { var i, j, k, x, tmp, base, changed;
	    base = eval("document.massform.field");
		fieldtext = base.options[base.selectedIndex].text;
		fieldname = base.options[base.selectedIndex].value;
		if(fieldname.substr(1,8) == "elect a "){ alert("You must select a fieldname!"); return;}
		base = eval("document.massform.action");
		action = base.options[base.selectedIndex].text;
		if(action.substr(1,8) == "elect an") { alert("You must select an action!"); return;}
		if(action == "copy from default lang")
		{ var potentials = new Array("name","description","meta_title","meta_keywords","link_rewrite","meta_description");
		  var categories = new Array();
		  var fields = new Array();
		  var fields_checked = false;
		  j=0; k=0;
		  for(i=0; i < '.$numrecs.'; i++) 
		  { cat_base = eval("document.Mainform.id_category"+i);
		    if(!cat_base) continue;
			id_category = cat_base.value;
		    if(!fields_checked)
			{ for(x=0; x<potentials.length; x++)
			  { field = eval("document.Mainform."+potentials[x]+i);
			    if(field) fields[j++] = potentials[x];
			  }
			  if(fields.length == 0) return;
			  fields_checked = true;
			}
			categories[k++] = id_category;
		  }
		  document.copyForm.categories.value = categories.join(",");
		  document.copyForm.fields.value = fields.join(",");
		  document.copyForm.submit(); /* copyForm comes back with the prepare_update() function */
		  return;
		}
		if(action != "copy from field")
		   myval = document.massform.myvalue.value;
		if((fieldname == "description") && (action == "set") && (myval.length != 0) &&(myval.substring(0,2)!="<p"))
				myval = "<p>"+myval+"</p>";
		if(fieldtext == "groups")
		{	myval = document.massform.myvalue.value;
			fieldname = "groupsel"; 
		}
		if(fieldtext == "active")
		{	myval = document.massform.myvalue.checked;
		}
		if(action == "copy from field")
		{	copyfield = document.massform.copyfield.options[document.massform.copyfield.selectedIndex].text;
			cellindex = getColumn(copyfield);
		}
		for(i=0; i < '.$numrecs.'; i++) 
		{ 	changed = false;
			field = eval("document.Mainform."+fieldname+i);
			if(!field) continue;
			if((fieldname == "description") && (field.parentNode.childNodes[0].tagName == "DIV"))
				field.value = tinyMCE.get(fieldname+i).getContent();	

			if(action == "insert before")
			{	if((fieldname == "description") || (fieldtext == "shortdescription"))
				{	orig = field.value.replace(/^<p>/, "");
					myval2 = "<p>"+myval+orig;
				}
				else
					myval2 = myval+field.value;
				changed = true;
			}
			else if(action == "insert after")
			{	if((fieldname == "description") || (fieldtext == "shortdescription"))
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
			else if(action == "regenerate")
			{	field = eval("document.Mainform."+fieldname+i);
				oldvalue = field.value;
				myval2 = str2url(field.parentNode.parentNode.childNodes[2].innerHTML);
				if(oldvalue != myval2)
				  changed = true;
			}
			else if(action == "replace")
			{	src = document.massform.oldval.value;
				evax = new RegExp(src,"g");
				oldvalue = field.value;
				myval2 = field.value.replace(evax, myval);
				if(oldvalue != myval2)
				  changed = true;
			}
			else if(action == "copy from field") 
			{ oldvalue = field.value;
			  myval2 = field.parentNode.parentNode.cells[cellindex].innerHTML;
			  if(fieldname == "meta_description")
			    myval2 = myval2.replace(/<(?:.|\n)*?>/gm, "");
			  if(oldvalue != myval2) changed = true;
			}
			else if(action == "add")  /* add group to category */
			{ var list = document.getElementById("grouplist"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
				{ list.selectedIndex = x;
				  AddGroup(i);
				  changed = true;
				  break;
				}
			  }
			}
			else if(action == "remove")  /* remove group from category */
		    { var list = document.getElementById("groupsel"+i);
			  len = list.length;
			  for(x=0; x<len; x++)
			  { if(list[x].value == myval)
			    { list.selectedIndex = x;
				  RemoveGroup(i);
				  changed = true;
				  break;
			    }
			  }
		    }
			else myval2 = myval;
			if(fieldtext == "active")
			{   field = field[1];
				oldvalue = field.checked;
			    field.checked = myval;
				if(oldvalue != myval) changed = true;
			}
			else if((action != "add") && (action != "remove"))
			{	oldvalue = field.value;
				field.value = myval2;
				if(oldvalue != myval2) changed = true;
			}
			if((fieldname == "description") && (field.parentNode.childNodes[0].tagName == "DIV"))
				base.value = tinyMCE.get(fieldname+i).setContent(field.value);	

			if(changed) /* we flag only those really changed */
				reg_change(field);
		}
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

	  function prepare_update()
	  { records = new Array();
	  	for(i=0; i < '.$numrecs.'; i++) 
		{ cat_base = eval("document.Mainform.id_category"+i);
		  if(!cat_base) continue;
		  id_category = cat_base.value;
		  records[id_category] = i;
		}
	  }
	  
	  function update_field(product, field, value)
	  { var base = eval("document.Mainform."+field+records[product]);
	    if(base.value != value)
			reg_change(base);
	    base.value = value;
	  }
	  
function getColumn(name)
{ var tbl = document.getElementById("Maintable");
  var len = tbl.tHead.rows[1].cells.length;
  for(var i=0;i<len; i++)
  { if(tbl.tHead.rows[1].cells[i].firstChild.innerHTML == name)
      return i;
  }
}

function tidy_html(html) {
    var d = document.createElement("div");
    d.innerHTML = html;
    return d.innerHTML;
}

function newwin_check()
{ if(search_form.newwin.checked)
   	search_form.target = "_blank";
  else
  	search_form.target = "";
}


</script>
</head>

<body onload="set_visibility()">
';
  print_menubar();
  echo "<center><b>Category Multi-Edit</b></center><br>";

  echo '<table class="triplesearch"><tr><td>
	<form name="search_form" method="get" action="cat-edit.php" onsubmit="newwin_check()">
<table class="tripleminimal"><tr><td>Language: <select name="id_lang">';
	  $query=" select * from ". _DB_PREFIX_."lang ";
	  $res=dbquery($query);
	  while ($language=mysqli_fetch_array($res)) {
		$selected='';
	  	if ($language['id_lang']==$id_lang) $selected=' selected="selected" ';
	        echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
  echo '</select>';

  if($input['id_lang'] == "")
    echo " &nbsp; This language was derived from your configuration settings.";

	echo ' &nbsp; &nbsp; &nbsp; shop <select name="id_shop">';
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($shop=mysqli_fetch_array($res)) {
		if ($shop['id_shop']==$id_shop) {$selected=' selected="selected" ';} else $selected="";
	        echo '<option  value="'.$shop['id_shop'].'" '.$selected.'>'.$shop['id_shop']."-".$shop['name'].'</option>';
	}	
	echo '</select><br>';
	
	echo 'Search: <input name=search_txt value="'.$input['search_txt'].'"> &nbsp; &nbsp; ';
	echo 'Startrec: <input size=3 name=startrec value="'.$startrec.'">';
	echo ' &nbsp &nbsp; Number of recs: <input size=3 name=numrecs value="'.$numrecs.'">';
	echo '</td></tr></table>';
	echo '<hr/>';
	echo '<table ><tr>';
	$checked = in_array("name", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="name" '.$checked.' />Name</td>';
	$checked = in_array("parent", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="parent" '.$checked.' />Parent</td>';
	$checked = in_array("depth", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="depth" '.$checked.' />Depth</td>';
	$checked = in_array("position", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="position" '.$checked.' />Position</td>';
	$checked = in_array("active", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="active" '.$checked.' />Active</td>';
	$checked = in_array("description", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="description" '.$checked.' />Description</td>';
	$checked = in_array("linkrewrite", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="linkrewrite" '.$checked.' />Link-rewrite</td>';
	$checked = in_array("metatitle", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metatitle" '.$checked.' />MetaTitle</td>';
	$checked = in_array("metakeywords", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metakeywords" '.$checked.' />MetaKeys</td>';
	$checked = in_array("metadescription", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="metadescription" '.$checked.' />MetaDesc</td>';
	$checked = in_array("groups", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="groups" '.$checked.' />Groups</td>';	
	$checked = in_array("image", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="image" '.$checked.' />Image</td>';
	echo '</tr><tr>';
	$checked = in_array("prodcount", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="prodcount" '.$checked.' />#prods</td>';
	$checked = in_array("activecount", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="activecount" '.$checked.' />#active</td>';	
	$checked = in_array("dircount", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="dircount" '.$checked.' />#dirs</td>';
	$checked = in_array("activedircount", $input["fields"]) ? "checked" : "";
	echo '<td><input type="checkbox" name="fields[]" value="activedircount" '.$checked.' />#activedirs</td>';
	echo '</tr></table></td><td><input type=checkbox name=newwin>new<br/>window<p/><input type="submit" value="search" /></td>';
	echo '</tr></table></form>';

	/* ***********************************MASS UPDATE********************************************* */
	
	echo '<hr/><div style="background-color:#CCCCCC; position: relative;">Mass update<form name="massform" onsubmit="massUpdate(); return false;">
	<select name="field" onchange="changeMfield()"><option>Select a field</option>';
	foreach($field_array AS $key => $field)
	{	if(in_array($key, $input['fields']))
			echo '<option value="'.$field[0].'">'.$key.'</option>';
	}
	echo '</select>';
	echo '<select name="action" onchange="changeMAfield()" style="width:120px"><option>Select an action</option>';
	echo '<option>set</option>';
	echo '<option>insert before</option>';
	echo '<option>insert after</option>';
	echo '<option>replace</option>';
	echo '<option>regenerate</option>';
	echo '<option>copy from default lang</option>';
	echo '<option>copy from field</option>';
	echo '<option>add</option>';
	echo '<option>remove</option>';
	echo '</select>';
	echo '&nbsp; <span id="muval">value: <input name="myvalue"></span>';
	echo ' &nbsp; &nbsp; <input type="submit" value="update all editable records"></form>';
	echo 'NB: Prior to mass update you need to make the field editable. Afterwards you need to submit the records.';
	echo '</div><hr/>';
	
	

  // "*********************************************************************";
  echo '<form name="copyForm" action="copy_category_language.php" target="tank" method="post"><input type="hidden" name="categories"><input type="hidden" name="id_shop" value="'.$id_shop.'"><input type="hidden" name="id_lang" value="'.$def_lang.'"><input type=hidden name=fields></form>';


//id_category 	id_parent 	level_depth 	nleft 	nright 	active 	date_add 	date_upd 	position
//id_category  id_lang 	name 	description 	link_rewrite 	meta_title 	meta_keywords 	meta_description

  $silencers = ""; /* take care of invisible fields */
  for($i=0; $i<sizeof($infofields); $i++)
  { if($infofields[$i][3] == 0)
      $silencers .= ',"'.$i.'"';
  }
  echo "<script>silencers = new Array(".substr($silencers,1).");</script>\r\n";

  $query="select SQL_CALC_FOUND_ROWS c.*,cl.*,m.name AS parentname,d.id_category AS dtest from ". _DB_PREFIX_."category c";
  $query .= " LEFT JOIN ". _DB_PREFIX_."category_lang cl on cl.id_lang='".mysqli_real_escape_string($conn, $id_lang)."' AND c.id_category=cl.id_category AND cl.id_shop='".$id_shop."'";
  $query .= " LEFT JOIN ". _DB_PREFIX_."category d ON c.id_parent=d.id_category";
  $query .= " LEFT JOIN ". _DB_PREFIX_."category_lang m on m.id_lang='".mysqli_real_escape_string($conn, $id_lang)."' AND d.id_category=m.id_category AND m.id_shop='".$id_shop."'";
  if($input['search_txt'] != "")
    $query .= " WHERE l.name LIKE '%".$input['search_txt']."%' OR l.description LIKE '%".$input['search_txt']."%'";
  $query .= " ORDER BY c.nleft"; /* order as displyed on screen in shop */
  $query .= " LIMIT ".$startrec.",".$numrecs;
  $res=dbquery($query);
  
  $res2=dbquery("SELECT FOUND_ROWS() AS foundrows");
  $row2 = mysqli_fetch_array($res2);
  $totalrecs = $row2['foundrows'];
  // "*********************************************************************";
  echo '<form name=IndexForm action="reindex.php" target="tank" method="post"><table style="width:100%"><tr><td style="width:80%"><input type=checkbox name=skipindexation> Skip indexation (much faster, but you need to re-index later)</td>';
  $iquery = "SELECT COUNT(DISTINCT id_product) AS prodcount FROM ". _DB_PREFIX_."product_shop WHERE indexed=0 AND visibility IN ('both', 'search') AND `active` = 1";
  $ires=dbquery($iquery);
  list($unindexedcount) = mysqli_fetch_row($ires); 
  echo '<td style="text-align:right;"><button onclick="reindex(); return false;">Re-index <span id=reindexspan>'.$unindexedcount.'</span> products</button></td>';
  echo '</tr></table></form><hr>';
  // "*********************************************************************";
  echo '<form name=ListForm><table class="tripleswitch"><tr><td><br>Hide<br>Show<br>Edit</td>';
  for($i=2; $i< sizeof($infofields); $i++)
  { $checked0 = $checked1 = $checked2 = "";
    if($infofields[$i][3] == 0) $checked0 = "checked"; 
    if($infofields[$i][3] == 1) $checked1 = "checked"; 
    echo '<td>'.$infofields[$i][0].'<br>';
    if(($infofields[$i][3] == 0) || ($infofields[$i][3] == 1))
    { echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
      echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
      if($infofields[$i][7]!="NOT_EDITABLE")
        echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_edit" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
      else
        echo "&nbsp;";
    }
    else echo "<br/>edit";
    echo "</td
>";
  }
  echo '<td><iframe name=tank width="300" height="70"></iframe></td><td><input type=checkbox name=verbose> verbose<p><input type=button value="Submit all" onClick="return SubmitForm();"></td>';
  echo '</tr></table></form>';

  echo "Showing ".mysqli_num_rows($res)." categories of ".$totalrecs;
  echo '<form name="Mainform" method=post><input type=hidden name=urlsrc>';
  echo '<input type=hidden name=id_shop value='.$id_shop.'><input type=hidden name=verbose>';
  echo '<input type=hidden name=startrec><input type=hidden name=numrec><input type=hidden name=skipindexation>'; 
  echo '<input type=hidden name=reccount value='.mysqli_num_rows($res).'><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
  for($i=0; $i<sizeof($infofields); $i++)
  { $align = $visibility = $namecol = "";
    if($infofields[$i][5] == 1)
      $align = ' style="text-align:right"';
	if($infofields[$i][0] == "name")
      $namecol = ' class="namecol"';
    echo "<col id='col".$i."'".$align.$visibility.$namecol."></col>";
  }

  echo "</colgroup><thead><tr>";

  for($i=0; $i<sizeof($infofields); $i++)
    echo '<th><a href="" onclick="this.blur(); if(rowsremoved>0) { rorem_alert(); return false;}; return sortTable(\'offTblBdy\', '.$i.', false);" title="'.$infofields[$i][1].'">'.$infofields[$i][0].'</a></th
>';

  echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
  echo "</tr></thead><tbody id='offTblBdy'>"; /* end of header */
  $x=0;
  $statfields = array("prodcount","activecount","dircount","activedircount");
  $haserrors = false;
  while($datarow = mysqli_fetch_array($res))
  { 
    if(($datarow['name'] == "") || ($datarow['parentname'] == "") || ($datarow['dtest'] === NULL) || ($datarow['id_category'] === NULL))
	{ $haserrors = true;
	  continue;
	}
    echo '<tr><td id="trid'.$x.'" changed="0"><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide line from display" /><input type=hidden name="id_category'.$x.'" value="'.$datarow['id_category'].'"></td>';
    for($i=1; $i< sizeof($infofields); $i++)
    { if(($infofields[$i][2] != "image") && ($infofields[$i][2] != "groups") && (!in_array($infofields[$i][2],$statfields)))
	    $myvalue = $datarow[$infofields[$i][2]];
      if($i == 1) /* id */
	  { if ($rewrite_settings == '1')
          echo "<td><a href='".get_base_uri().$datarow[$infofields[$i][2]]."-".$datarow['link_rewrite']."' target='_blank'>".addspaces($myvalue)."</a></td>";
		else
		  echo "<td><a href='../index.php?id_category=".$datarow[$infofields[$i][2]]."&controller=category' target='_blank'>".addspaces($myvalue)."</a></td>";
	  }
      else if($infofields[$i][2] == "image")
	  { get_image_extension(0, $datarow[$infofields[1][2]], "category");
        if(file_exists('../img/c/'.$datarow[$infofields[1][2]].$selected_img_extension))
          echo '<td><a href="../img/c/'.$datarow[$infofields[1][2]].'.jpg" target="_blank"><img src="../img/c/'.$datarow[$infofields[1][2]].$selected_img_extension.'" width="45px" height="45px" /></a></td>';
	    else if(file_exists('../../img/c/'.$datarow[$infofields[1][2]].$selected_img_extension))
          echo '<td><a href="../../img/c/'.$datarow[$infofields[1][2]].'.jpg" target="_blank"><img src="../../img/c/'.$datarow[$infofields[1][2]].$selected_img_extension.'" width="45px" height="45px" /></a></td>';
        else
          echo "<td>X</td>";
 	  }
	  else if($infofields[$i][2] == "prodcount")
	  { $squery = "SELECT COUNT(*) AS prodcount FROM ". _DB_PREFIX_."category_product";
		$squery .= " WHERE id_category=".$datarow["id_category"];
		$sres = dbquery($squery);
		$srow = mysqli_fetch_array($sres);
		echo '<td><a href=product-edit.php?id_category='.$datarow['id_category'].' target=_blank>'.$srow["prodcount"].'</a></td>';
	  }
	  else if($infofields[$i][2] == "activecount")
	  { $squery = "SELECT COUNT(DISTINCT cp.id_product) AS activecount FROM ". _DB_PREFIX_."category_product cp";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_shop ps ON cp.id_product=ps.id_product";
		$squery .= " WHERE cp.id_category=".$datarow['id_category']." AND ps.active=1";
		$sres = dbquery($squery);
		$srow = mysqli_fetch_array($sres);
		echo '<td>'.$srow["activecount"].'</td>';
	  }
	  else if($infofields[$i][2] == "dircount")
	  { $squery = "SELECT COUNT(DISTINCT id_category) AS dircount FROM ". _DB_PREFIX_."category";
		$squery .= " WHERE id_parent=".$datarow["id_category"];
		$sres = dbquery($squery);
		$srow = mysqli_fetch_array($sres);
		echo '<td>'.$srow["dircount"].'</td>';
	  }
	  else if($infofields[$i][2] == "activedircount")
	  { $squery = "SELECT COUNT(DISTINCT id_category) AS dircount FROM ". _DB_PREFIX_."category";
		$squery .= " WHERE id_parent=".$datarow["id_category"]." AND active=1";
		$sres = dbquery($squery);
		$srow = mysqli_fetch_array($sres);
		echo '<td>'.$srow["dircount"].'</td>';
	  }  
	  else if($infofields[$i][2] == "groups")
	  { $gquery = "select cg.id_group,gl.name FROM ". _DB_PREFIX_."category_group cg";
		$gquery .= " LEFT JOIN `"._DB_PREFIX_."group_lang` gl ON (cg.id_group=gl.id_group AND gl.id_lang=".$id_lang.")";
		$gquery .= " WHERE id_category='".$datarow["id_category"]."'";
		$gres=dbquery($gquery);
		if(mysqli_num_rows($gres) == 0) /* this shouldn't happen: PS doesn't allow you to delete all groups from a category */
		{ $squery = "select value FROM ". _DB_PREFIX_."configuration WHERE name='PS_ROOT_CATEGORY'";
		  $sres=dbquery($squery);
		  if(mysqli_num_rows($sres) != 0)
		  { $srow = mysqli_fetch_array($sres);
		    if($srow["value"] == $datarow["id_category"]) /* check for the exception: root categories have no groups: so we don't allow them either */
		    { echo "<td></td>";
			  continue;  
			}
		  }
		}
		echo "<td><table id='groups".$x."'>";
		while($grow = mysqli_fetch_array($gres))
          echo "<tr><td id='".$grow["id_group"]."'>".$grow["name"]."</td></tr>";
	    echo "</table></td>";
	  }
	  else
      { $sorttxt = "";
  	    if($infofields[$i][6] == SORTER)
	      $sorttxt = "srt='".str_replace("'", "\'",$myvalue)."'";
	    if($infofields[$i][3] == EDIT)
	    { if($infofields[$i][7] == INPUT)
            echo '<td '.$sorttxt.'><input name="'.$infofields[$i][0].'" value="'.$myvalue.'" /></td>';
  	      else if($infofields[$i][7] == TEXTAREA)
 	        echo '<td '.$sorttxt.'><textarea name="'.$infofields[$i][0].'" rows=3>'.$myvalue.'</textarea></td>';
	      else colordie("Infofields table was incorrectly filled: NOT_EDITABLE with EDIT");
	    }
	    else
          echo "<td ".$sorttxt.">".$myvalue."</td>";
      }
    }
    echo '<td><img src="enter.png" title="submit row '.$x.'" onclick=RowSubmit(this)></td>';
    echo "</tr
>";
    $x++;
  }
  echo "</tbody></table></div></form>";
//  if($haserrors)
//	  echo '<script>alert("Your shop database has errors. Run Integrity Checks (under Tools&Stats) or try to repair them in another way!");</script>';

  echo '<div style="display:block;"><form name=rowform action="cat-proc.php" method=post target=tank>';
  echo '<table id=subtable></table><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=id_shop value="'.$id_shop.'"><input type=hidden name=verbose>';
  echo '<input type=hidden name=id_row><input type=hidden name=skipindexation></form></div>';

  include "footer1.php";
?>
</body>
</html>