<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<style>
	 { margin: 0; padding: 0; }
body { font: 14px/1.4 Georgia, serif; }<!---->
#page-wrap { width: 800px; margin: 0 auto; background-color:lightgray;}
textarea { border: 0; font: 14px Georgia, Serif; overflow: hidden; resize: none; }
table { border-collapse: collapse; }
table td, table th { border: 1px solid black; padding: 5px; }
#header { height: 15px; width: 100%; margin: 20px 0; background: #222; text-align: center; color: white; font: bold 15px Helvetica, Sans-Serif; text-decoration: uppercase; letter-spacing: 20px; padding: 8px 0px; }
#address { width: 260px; height: 80px; float: left; }
#customer { overflow: hidden; }
#logo { text-align: right; float: right; position: relative; margin-right: 85px; max-width: 540px; max-height: 100px; overflow: hidden; }
#customer-title { font-size: 20px; font-weight: bold; float: left; }
#meta { margin-top: 1px; width: 799px; }
#meta td { text-align: right;  }
#meta td.meta-head { text-align: left; background: #eee; }
#meta td textarea { width: 100%; height: 20px; text-align: right; }
#meta1 { margin-top: 1px;
    width: 399px;
    float: left }
#meta1 td { text-align: right;  }
#meta1 td.meta-head { text-align: left; background: #eee; }
#meta1 td textarea { width: 100%; height: 20px; text-align: right; }
#items { width: 397px;  border: 1px solid black; margin-left:2px; float:left; }
#items th { background: #eee; }
#items textarea { width: 80px; height: 50px; }
#items tr.item-row td { border: 0; vertical-align: top; }
#items td.description { width: 300px; }
#items td.item-name { width: 175px; }
#items td.description textarea, #items td.item-name textarea { width: 100%; }
#items td.total-line { border-right: 0; text-align: right; }
#items td.total-value { border-left: 0; padding: 10px; }
#items td.total-value textarea { height: 20px; background: none; }
#items td.balance { background: #eee; }
#items td.blank { border: 0; }
#terms { text-align: center; width:800px; margin-top:20px;}
#terms h5 { text-transform: uppercase; font: 13px Helvetica, Sans-Serif; letter-spacing: 10px; border-bottom: 1px solid black; padding: 0 0 8px 0; margin: 0 0 8px 0;font-weight:bold; }
#terms textarea { width: 100%; text-align: center;}
textarea:hover, textarea:focus, #items td.total-value textarea:hover, #items td.total-value textarea:focus, .delete:hover { background-color:#EEFF88; }
	</style>
</head>
<body>
	<div id="page-wrap">
		<textarea id="header" readonly="readonly">INVOICE</textarea>		
		<div id="identity">		
            <span id="address">
			<strong><?= $UserName;?></strong><br/>
			<?= $usAddrs1.$userAdd2;?>,<?= $userCity;?><br/>			
			<?= $userState;?>,<?= $usrCoty;?><br/>
			<?= $userEml;?>,<?= $usrMobNo;?>			
			</span>
            <div id="logo">
              <img id="image" src="http://localhost/hello42/public/image/logo.png" alt="logo" />			 
            </div>
		</div>
		<div style="clear:both"></div>
		<span style="font-weight:bold;font-size:14px;padding-left:335px;">Vehicle Details</span>
		<div id="customer">           
            <table id="meta">
                <tr>
                    <td class="meta-head">Driver Name</td>
                    <td><?= $DriverFirstName.$DriverLastName;?></td>
					<td class="meta-head">Vehicle Number</td>
                    <td><?= $vehicle_number;?> </td>
                </tr>
                <tr>
				    <td class="meta-head">Driver Mobile No.</td>
                    <td><?= $DriverMobNo;?> </td>                    
					 <td class="meta-head">Vehicle Name</td>
                    <td><?= $vehicle_name;?> </td>
                </tr>
                <tr>
                    <td class="meta-head">Driver Email</td>
                    <td><?= $Driveremail;?></td> 
					<td class="meta-head">Vehicle Type</td>
                    <td><?= $vechile_type;?></td>
                </tr>
            </table>			
		</div>	
        <span style="font-weight:bold;font-size:14px;padding-left:335px;">Booking Details</span>
		<div style="width:800px;">
			<table id="meta1">
						<tr>
							<td class="meta-head">Booking Date</td>
							<td><?= $BookingDate;?></td>					
						</tr>
						<tr>
							<td class="meta-head">Return Date</td>
							<td><?= $ReturnDate;?></td>
						</tr>
						<tr>
							<td class="meta-head">Pickup Address</td>
							<td><?= $PickupAddress;?></td>
						</tr>
						<tr>
							<td class="meta-head">Pickup Date</td>
							<td><?= $PickupDate;?></td>
						</tr>
						<tr>
							<td class="meta-head">Pickup Time</td>
							<td><?= $PickupTime;?></td>
						</tr>						
						<tr>
							<td class="meta-head">Pickup City</td>
							<td><?= $PickupCity;?></td>
						</tr>
						<tr>
							<td class="meta-head">Drop Address</td>
							<td><?= $DropAddress;?></td>					
						</tr>
						<tr>
							<td class="meta-head">Drop City</td>
							<td><?= $DestinationCity;?></td>					
						</tr>						
						<tr>
							<td class="meta-head">Estimated Distance</td>
							<td><?= $EstimatedDistance;?></td>					
						</tr>
						<tr>
							<td class="meta-head">Estimated Time</td>
							<td><?= $EstimatedTime;?></td>
						</tr>
		    </table>		
			<table id="items">	
					  <tr>		      
						  <td class="total-line">Waiting Charges : </td>
						  <td class="total-value"><div id="subtotal"><i class="fa fa-inr"></i>&nbsp;<?= $waiting_charge;?></div></td>
					  </tr>
					  <tr>		      
						  <td class="total-line">Night Charges : </td>
						  <td class="total-value"><div id="total"><i class="fa fa-inr"></i>&nbsp;<?= $night_charges;?></div></td>
					  </tr>
					  <tr>
					<tr>		      
						  <td class="total-line">Road Tax : </td>
						  <td class="total-value"><div id="subtotal"><i class="fa fa-inr"></i>&nbsp;<?= $road_tax;?></div></td>
					  </tr>
					  <tr>		      
						  <td class="total-line">Tool Tax : </td>
						  <td class="total-value"><div id="total"><i class="fa fa-inr"></i>&nbsp;<?= $toll_tax;?></div></td>
					  </tr>
					  <tr>
					<tr>		      
						  <td class="total-line">Other Charges : </td>
						  <td class="total-value"><div id="subtotal"><i class="fa fa-inr"></i>&nbsp;<?= $other_tax;?></div></td>
					  </tr>
					  <tr>		      
						  <td class="total-line"><strong>Total Fare : </strong></td>
						  <td class="total-value"><div id="total"><strong><i class="fa fa-inr"></i>&nbsp;<?= $amount;?></strong></div></td>
					  </tr>
					  <tr>					  
						  <td class="total-line"><strong>Amount Paid : </strong></td>
						  <td class="total-value"><span id="paid"><strong><i class="fa fa-inr"></i>&nbsp;<?= $paid_amount;?></strong></span></td>
					  </tr>
					  <tr>		      
						  <td class="total-line balance"><strong>Balance Due : </strong></td>
						  <td class="total-value balance"><div class="due"><strong><i class="fa fa-inr"></i>&nbsp;<?= $due_balance;?></strong></div></td>
					  </tr>
					 
			</table>				
		</div>
		<div id="terms">
			  <h5>&nbsp;</h5>
			  
			  <span><strong>Terms : </strong><small>NET 30 Days. Finance Charge of 1.5% will be made on unpaid balances after 30 days.</small></span>
		</div>	
	</div>	
</body>
</html>