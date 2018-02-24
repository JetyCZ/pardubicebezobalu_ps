<link href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet"> 
<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

<!--Buttons-->
<script>

$(document).ready(function() {
    $('#example').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    }
	);
	
//nka 

NkaExport();
NkaHidden();	 
	 
} ); 

 function NkaHidden() {
    var table = $('#example').DataTable();
 
    new $.fn.dataTable.Buttons( table, {
        buttons: [
		        //'columnsToggle',	
          /*  {
                text: 'Button 1',
                action: function ( e, dt, node, conf ) {
                    console.log( 'Button 1 clicked on' );
                }
            },
            {
                text: 'Button 2',
                action: function ( e, dt, node, conf ) {
                    console.log( 'Button 2 clicked on' );
                }
            }*/
        ]
    } );
 
    table.buttons( 0, null ).container().prependTo(
        table.table().container()
    );
} 

function NkaExport(){

var table = $('#example').DataTable();
 
    new $.fn.dataTable.Buttons( table, {
     buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'            		
        ]	  
    } );
 
    table.buttons( 0, null ).container().prependTo(
        table.table().container()
    );

}

</script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- end of Buttons-->

<h2 style="color:green;">{$message}</h2>
<table id="example" class="display" cellspacing="0" width="100%">

        <thead>
            <tr>  
            <th>ID</th>
      <th>
	  Désignation	  
	  </th>
      <th>Attributs</th>
      <th>Conditionnement</th>
	  <th>Quantités en stock</th>
	  <th>Prix</th>
	  <th>Qté à commander</th>
	  <th>
	  <input type="submit" name="submitAddThierryAllProduct" value="{l s='Valider' mod='listeproduitstockfr'}" {literal}onclick="if (confirm('Ajouter ce produit ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="add product" class="edit btn btn-default">
	  </th>
          </tr>  
        </thead> 

<tfoot>
     <tr>
      <th>ID</th>
      <th>Désignation</th>
      <th>Attributs</th>
      <th >Conditionnement</th>
	  <th>Quantités en stock</th>
	  <th>Prix</th>
	  <th>Qté à commander</th>
    </tr>       
  </tfoot>		
        <tbody>
		
       {foreach from=$all_products item=product}
<form id="configuration_form" action="{$url_submit}"   method="post"  class="defaultForm form-horizontal productorderspro">   
    <tr>	
      <th scope="row">
	  {$product.id_product}
	  <input type="hidden" name="id_product" value="{$product.id_product}">
	  </th>
      <td>{$product.name}</td>
      <td>
	  {*$html*}	
      <select name="3" size="1" id="row-5-office">
  <option value="3" selected="selected">Couleur</option>
  <option value="5">Gris</option><option value="6">Taupe</option>
  <option value="7">Beige</option><option value="8">Blanc</option>
  <option value="9">Blanc cassé</option><option value="10">Rouge</option>
  <option value="11">Noir</option><option value="12">Camel</option>
  <option value="13">Orange</option><option value="14">Bleu</option>
  <option value="15">Vert</option><option value="16">Jaune</option>
  <option value="17">Marron</option><option value="24">Rose</option>
  </select>	  
	  </td>
      <td>{$product.condition}</td>
	  <td>{StockAvailable::getQuantityAvailableByProduct($product.id_product, 0)}</td>
	  <td>{displayPrice price=$product.price}</td>
	  <td>	  
	  <input type="text"  name="ordered_qty"  value="{$product.minimal_quantity}">
	  </td>
	<td class="text-right">	
<div class="btn-group-action">				
<div class="btn-group pull-right">
<input type="submit" name="submitAddThierryProduct" value="{l s='Add Product' mod='listeproduitstockfr'}" {literal}onclick="if (confirm('Ajouter ce produit ?')){return true;}else{event.stopPropagation(); event.preventDefault();};"{/literal} title="add product" class="edit btn btn-default">
</div>
</div>
</td>  
    </tr>
</form>		
  {/foreach}      
       
 </tbody>	
		
<!--<tfoot>
<tr>
<th rowspan="1" colspan="1">
<select></select>
</th>
<th rowspan="1" colspan="1">
<select></select>
</th>
<th rowspan="1" colspan="1">
<select></select>
</th>
<th rowspan="1" colspan="1">
<select></select>
</th>
<th rowspan="1" colspan="1">
<select></select>
</th>
<th rowspan="1" colspan="1">
<select></select>
</th>
<th rowspan="1" colspan="1">
<select></select>
</th>
</tr>
</tfoot>-->
		
</table>