$(document).ready(function () {
	$("#remunerations-list").dataTable({
		"aaSorting": [[ 0, "asc" ]],
		"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
            /* Append the grade to the default row class name */
			var str = aData[0];
			var arr = str.split(":::");
            $('td:eq(0)', nRow).html( '<a href="' + arr[1] + '">' + arr[0] + '</a>' );
            
            var str = aData[2];
			var arr = str.split(" ");
			var str = arr[0];
			var arr = str.split("-");
			
			$('td:eq(2)', nRow).html($.datepicker.formatDate( "M dd, yy", new Date(arr[0], arr[1] - 1, arr[2])));
			
			$(nRow).show();
        },
        "aoColumnDefs": [ {
                "sClass": "center",
                "aTargets": [ -1, -2 ]
        } ],
		"fnFooterCallback": function() {
			$("#remunerations-list").show();
		}
	});
});