function confirmDelete(msg){
	if(typeof msg == 'undefined'){
		msg = 'Do you really want to delete this element?';
	}

	return confirm(msg);
}