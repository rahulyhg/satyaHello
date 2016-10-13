</div>

</div>


<div class="container">
	<div class="offset3">

	</div>
</div>


<script>
$(function(){
       $(".select2").select2({
             placeholder: "Select a value",
             width: 'resolve'
        });
        
        $( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
		$( "#date2" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
       $("[rel=tooltip]").popover({
            'animation' : 'true',
            'trigger': 'hover',
        });
        
        
});
    
</script>







</body>
</html>
