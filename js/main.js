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
			loggingIn();	
		}else if(page == "list"){
			if(typeof $("body").data("error") == "undefined"){
				displayingRecords();
				clearInput();			
			}else{
				MsgBox.error("Failed to display customers.");
			}
		}else if(page == "customer"){
			if(typeof $("body").data("error") == "undefined"){
				editing();
				canceling();
				saving();				
			}else{
				MsgBox.error("Failed to get customer data.");
			}
		}else if(page == "new"){
			checkingAvailability();
			adding();
		}
	}
	
	/* ---------------
	 *    Private
	 * --------------- */			
	function loggingIn(){
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
				if(data){
					window.location = "list.php";
				}else{
					MsgBox.error("Invalid Credentials");
				}
			},"json");			
		}			
	}
	
	function clearInput(){
		$("input").val("");
	}
	
	function displayingRecords(){
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
				 call: "generateCustomersTable"
				,requestedOrder: order
				,reverse: reverse
				,search:search
			},function(data){
				if(data){
					$("#records").html(data);
					$("#recordTable").find("thead").find(".arrow").html("");
					if(reverse){
						orderColumn.find(".arrow").html("\u25BC");
					}else{
						orderColumn.find(".arrow").html("\u25B2");
					}					
				}else{
					MsgBox.error("Failed to display records");
				}
			},"json")
		}
	}
	
	function editing(){
		$("#edit-button").click(function(){
			$(".info").hide();
			$(".infoTextbox").show();
			$("#edit-button").hide();
			$("#cancel-button").show();
			$("#save-button").show();
		})
	}

	function canceling(){
		$("#cancel-button").click(function(){
			$(".info").each(function(){
				var info = $(this);
				if(info.attr("id") == "company-name"){
					var infoTextbox = info.next();
				}else{
					var infoTextbox = $(this).parent().find(".infoTextbox");				
				}
				
				var newInfoTextboxVal = info.html() == "unspecified" ? "" : info.html();
				info.show();
				infoTextbox.val(newInfoTextboxVal).hide();			
			});
			
			$("#edit-button").show();
			$("#cancel-button").hide();
			$("#save-button").hide();
		});
	}
	
	function saving(){
		$("#save-button").click(function(){
			$.post("php/customers.class.php",{ 
				 call			: "updateCustomer"
				,id				: $("#customer-id").html()
				,companyName	: $("#company-name-input").val()
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
				if(data){
					MsgBox.success("Saved");	
					$(".info").each(function(){
						var info = $(this);
						if(info.attr("id") == "company-name"){
							var infoTextbox = info.next();
						}else{
							var infoTextbox = $(this).parent().find(".infoTextbox");				
						}
						
						var newInfo = infoTextbox.val() == "" ? "unspecified" : infoTextbox.val();
						info.html(newInfo);
						infoTextbox.hide();
						info.show();		
					});	
									
					$("#edit-button").show();
					$("#cancel-button").hide();
					$("#save-button").hide();			
				}else{
					MsgBox.error("Failed to update");
				}
			},"json");			
		})
	}
	
	function validId(id){
		var valid = true;
		if(id.length > 5){
			valid = false;
			MsgBox.error("Invalid ID. Max length of 5 charachters",3000);
		}else if(id == ""){
			valid = false;
			MsgBox.error("ID is required");
		}
		return valid;
	}
	
	function checkingAvailability(){
		var invalidInput = "";
		$("#customer-id-input").on("focusout keyup",function(e){
			if(e.type == "focusout" || e.keyCode == 13){
				var id = $("#customer-id-input").val();
				if(validId(id)){
					$.post("php/customers.class.php",{ 
						 call			: "checkAvailability"
						,id				: id
					}, function(available) {	
						if(!available){
							MsgBox.error("ID Unavailable",false,3000);
							invalidInput = id;
							$("#customer-id-input").addClass("invalid");
							
						}else{
							$("#customer-id-input").removeClass("invalid");
						}
						
					},"json");				
				}else{
					$("#customer-id-input").addClass("invalid");
				}				
			}			
		});
	}
	
	function adding(){
		$("#add-button").click(function(){
			var id = $("#customer-id-input").val();
			if(validId(id)){
				$.post("php/customers.class.php",{ 
					 call			: "addCustomer"
					,id				: id
					,companyName	: $("#company-name-input").val()
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
					if(data){
						MsgBox.success("<a href = 'customer.php?id=" + id + "'>Customer added. Click here to view</a>", false, 4000);	
						clearInput();
					}else{
						MsgBox.error("ID Unavailable");
					}
				},"json");					
			}
				
		})
	}

	return {
		run:run
	}
})();

$(document).ready(function(){
	NorthWind.run();
});