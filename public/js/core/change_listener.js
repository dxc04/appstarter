var change_listener = null;

$(document).ready(function () {
	change_listener = new ChangeListener();

	$("input.form-control").keyup(function() {
		change_listener.setChanged();
	});
	
	$("select.form-control, input.form-control, input[type='checkbox']").change(function() {
		change_listener.setChanged();
	});
	
	 $(window).bind('beforeunload', function() {
			if (change_listener.isChanged()) {
				return 'There are unsaved changes on this page. If you leave this page, the changes will be lost.';
			}
    }); 
});

function ChangeListener() {
	this.is_changed = false;
	this.save_page = $('input[type="submit"]');
	
	this.listenSubmit();
}

ChangeListener.prototype.listenSubmit = function(){
	var self = this;
	
	this.save_page.click(function () {
		self.clearChanges();
	});
}

ChangeListener.prototype.setChanged = function(){
	this.is_changed = true;
	this.save_page.removeAttr('disabled');
}

ChangeListener.prototype.clearChanges = function(){
	this.is_changed = false;
}

ChangeListener.prototype.isChanged = function(){
	return this.is_changed;
}
