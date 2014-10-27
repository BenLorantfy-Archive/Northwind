MsgBox = (function(){

	var msgBox = $("<div id = 'msgBox'></div>");
	
	var style = "position: fixed; top:-40px; left:50%; width:200px; height:30px; box-sizing:border-box; -moz-box-sizing: border-box; margin-left:-100px; padding-top:5px; border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; color:white; text-align: center;";
	
	var redGradient = "background: #a90329; background: -moz-linear-gradient(top,  #a90329 0%, #8f0222 44%, #6d0019 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#a90329), color-stop(44%,#8f0222), color-stop(100%,#6d0019)); background: -webkit-linear-gradient(top,  #a90329 0%,#8f0222 44%,#6d0019 100%); background: -o-linear-gradient(top,  #a90329 0%,#8f0222 44%,#6d0019 100%); background: -ms-linear-gradient(top,  #a90329 0%,#8f0222 44%,#6d0019 100%); background: linear-gradient(to bottom,  #a90329 0%,#8f0222 44%,#6d0019 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a90329', endColorstr='#6d0019',GradientType=0 );";
	
	var greenGradient = "background: #33b70e; background: -moz-linear-gradient(top,  #33b70e 0%, #0b7509 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#33b70e), color-stop(100%,#0b7509)); background: -webkit-linear-gradient(top,  #33b70e 0%,#0b7509 100%); background: -o-linear-gradient(top,  #33b70e 0%,#0b7509 100%); background: -ms-linear-gradient(top,  #33b70e 0%,#0b7509 100%); background: linear-gradient(to bottom,  #33b70e 0%,#0b7509 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#33b70e', endColorstr='#0b7509',GradientType=0 );";
	
	//Constructor	
	$(document).ready(function(){
		//Private:
		
		msgBox.attr("style",style);	
		$("body").append(msgBox);
	});
	
	function animate(stay){
		msgBox.stop();
		msgBox.css("top",-1*(msgBox.height() + 10));
		msgBox.animate({ top: 0 }, 600, "easeOutQuart",function(){
			if(!stay){
				msgBox.delay(1300).animate({ top: -1*(msgBox.height() + 10) }, 600, "easeOutQuart");
			}	
		});		
	}
	
	//Public:
	function error(msg,stay){
		msgBox.html(msg);
		msgBox.attr("style",style + redGradient);
		animate(stay);
	}

	function success(msg,stay){
		msgBox.html(msg);
		msgBox.attr("style",style + greenGradient);
		animate(stay);
	}	
	
	function close(){
		msgBox.animate({ top: -1*(msgBox.height() + 10)}, 600, "easeOutQuart");
	}
	
	//return public methods so they are accessible
	return{
		error:error,
		success:success,
		close:close
	}
})();