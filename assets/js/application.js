function growl(body, span_class, d, auto_hide)
{
	var span = $("<span />").addClass(span_class).html(body);
	var msg = $("<div />").addClass("msg").attr('style', 'display: none;').html(span);
	
	$('#growl').append(msg);
	
	if(d == undefined)
	{
		d = 300;
	}
		
	if(auto_hide == undefined || auto_hide)
	{
		msg.click(function() { $(this).remove(); });
		
		msg.delay(d).fadeIn(300).delay(6000).slideUp(300, function(){ $(this).remove(); });
	} else
	{
		msg.delay(d).fadeIn(300);
	}
	
	return msg;
}

function hide_growl(msg)
{
	$(msg).slideUp(300, function(){ $(this).remove(); });
}


function setCookie(name,value,days) {
	if (days) {
	    var date = new Date();
	    date.setTime(date.getTime()+(days*24*60*60*1000));
	    var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function deleteCookie(name) {
    setCookie(name,"",-1);
}

function checkUS(dropdown)
{
	if($(dropdown).val() == 'US')
	{
		$('#us_states').show();
	} else
	{
		$('#us_states').hide();
	}
}

$.fn.spin = function(opts) {
  this.each(function() {
    var $this = $(this),
        data = $this.data();

    if (data.spinner) {
      data.spinner.stop();
      delete data.spinner;
    }
    if (opts !== false) {
      data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
    }
  });
  return this;
};