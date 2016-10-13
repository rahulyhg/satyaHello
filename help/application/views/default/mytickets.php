<div class="">
	<div class="well-white">
		<div class="page-header">
			<h1>Tickets</h1>
		</div>

		<div class="body">
			
			
			
		
	<table class="sort" >
    <thead>
    <tr>
    <th>Ticket#</th>
    <th>Subject</th>
	<th>Status</th>
    </tr>
    </thead>
     <tfoot>
                    <tr>
                        <th colspan="7" class="pager form-horizontal">
                        <button class="btn first">
                            <i class="icon-step-backward"></i>
                        </button>
                        <button class="btn prev">
                            <i class="icon-arrow-left"></i>
                        </button><span class="pagedisplay"></span><!-- this can be any element, including an input -->
                        <button class="btn next">
                            <i class="icon-arrow-right"></i>
                        </button>
                        <button class="btn last">
                            <i class="icon-step-forward"></i>
                        </button>
                        <select class="pagesize input-mini" title="Select page size">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                        </select><select class="pagenum input-mini" title="Select page number"></select></th>

                    </tr>
                </tfoot>
                
    <tbody>
    
<?php
if(isset($tickets))
foreach($tickets as $ticket) : ?>
    
    <tr>
    <td><a href="<?php echo site_url("tickets/viewticket") .'/'.  $clienthash . '/' . $ticket['id'];?>"><?php echo $ticket['id'];?></a></td>
    <td><?php echo $ticket['subject'];?></td>
    <td><?php echo ucfirst($ticket['status']);?></td>
    </tr>
<?php endforeach ; ?>
    
    </tbody>
    </table>	
			
			
			
			
			
			
			
		</div>

	</div>

</div>
