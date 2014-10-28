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
		}else if(page == "customer"){
			editEvent();
			cancelEvent();
			saveEvent();
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
	
	function editEvent(){
		$("#edit-button").click(function(){
			$(".infoContainer").each(function(){
				$(this).find(".info").hide();
				$(this).find(".infoTextbox").show();
			})	
			$("#edit-button").hide();
			$("#cancel-button").show();
			$("#save-button").show();
		})
	}

	function cancelEvent(){
		$("#cancel-button").click(function(){
			$(".infoContainer").each(function(){
				var info = $(this).find(".info");
				var infoTextbox = $(this).find(".infoTextbox");
				var newInfoTextboxVal = info.html() == "unspecified" ? "" : info.html();
				info.show();
				infoTextbox.val(newInfoTextboxVal).hide();			
			})		
			$("#edit-button").show();
			$("#cancel-button").hide();
			$("#save-button").hide();
		});
	}
	

	function saveEvent(){
		$("#save-button").click(function(){
			$.post("php/customers.class.php",{ 
				 call			: "updateCustomer"
				,id				: $("#customer-id").html()
				,contactName	: $("#contact-name-input").val()
				,contactTitle	: $("#contact-title-input").val()
				,address		: $("#address-input").val()
				,city			: $("#city-input").val()
				,region			: $("#region-input").val()
				,postalCode		: $("#postal-code-input").val()
				,country		: $("#country-input").val()
				,phone			: $("#phone-input").val()
				,fax			: $("#fax-input").val()
			}, function(data) {	
				data = data == "true";
				if(data){
					MsgBox.success("Saved");
					$(".infoContainer").each(function(){
						var info = $(this).find(".info");
						var infoTextbox = $(this).find(".infoTextbox");
						var newInfo = infoTextbox.val() == "" ? "unspecified" : infoTextbox.val();
						info.html(newInfo);
						infoTextbox.hide();
						info.show();					
					});					
					$("#edit-button").show();
					$("#cancel-button").hide();
					$("#save-button").hide();			
				}else{
					MsgBox.error("Invalid");
				}
			});			
		})
	}
	

	
	return {
		run:run
	}
})();

$(document).ready(function(){
	NorthWind.run();
});