
if($ == undefined){
	$ = jQuery;
}

$(document).ready(function(){

	$('#your-profile').after('<div style="text-align: center"><a href="http://dulabs.com/frontend-edit-profile-for-wordpress/">Powered by Frontend Edit Profile</a></div>');
	
	$('#pass1').simplePassMeter({
	  'showOnValue': true,
	  'Container': '#pass-strength-result'
	});
	
	$('#pass-strength-result').hide();
	
});