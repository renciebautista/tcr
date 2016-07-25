function GetSelectValues(select){
	var foo = [];
		select.each(function(i, selected){
			foo[i] = $(selected).val();
		});
	return foo;
}