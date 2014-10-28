var NorthWind = (function(){
	/* ---------------
	 *    Public
	 * --------------- */	
	function run(){
		//
		// Initilize Events
		//
		var page = $("body").data("page");
		if(page == "login"){
			loginEvent();	
		}else if(page == "list"){
			displayRecordsEvent();
			clearInput();
		}
	}
	
	/* ---------------
	 *    Private
	 * --------------- */			
	function loginEvent(){
		$("#login-button").click(login);
		$(document).keydown(function(e){
			if(e.keyCode == 13){
				login();
			}
		})		
		
		function login(){
			$.post("php/admin.class.php",{ 
				 call: "login"
				,name: $("#username").val()
				,password: $("#password").val()
			}, function(data) {	
				data = data == "true";
				if(data){
					window.location = "list.php";
				}else{
					MsgBox.error("Invalid Credentials");
				}
			});			
		}			
	}
	
	function clearInput(){
		$("input").val("");
		$("input").prop("checked",false);
	}
	
	function displayRecordsEvent(){
		var order = "CompanyName";
		var search = "";
		var reverse = false;
		var orderColumn = $("#companyHeader");
		
		$("#recordTable").on("click","#companyHeader,#contactHeader,#cityHeader",function(){
			orderColumn = $(this);
			if(order == orderColumn.data("field")){
				reverse = !reverse;
			}else{
				reverse = false;
			}
								
			order = orderColumn.data("field");
			
			displayRecords();
		});
		
		$("#search").keyup(function(){
			search = $("#search").val();
			displayRecords();
		});
		
		$("#checkAll").find("input").change(function(){
			$("#records").find("input").prop("checked",$(this).is(":checked"));
		})
		
		$("#records").on("change","input",function(){
			$("#checkAll").find("input").prop("checked",false);
		})
		
		function displayRecords(callback){
			$.post("php/customers.class.php",{
				 call: "getTableRows"
				,order: order
				,reverse: reverse
				,search:search
			},function(data){
				$("#records").html(data);
				$("#recordTable").find("thead").find(".arrow").html("");
				if(reverse){
					orderColumn.find(".arrow").html("\u25BC");
				}else{
					orderColumn.find(".arrow").html("\u25B2");
				}
			})
		}
	}
	
	
	return {
		run:run
	}
})();

$(document).ready(function(){
	NorthWind.run();
});