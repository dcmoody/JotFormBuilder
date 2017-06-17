

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<script>
var idlist=["",""] //list of form IDs
var i =0 //number used throughout for element ids, for the purposes of linking elements and sending values between functions.
$( document ).ready(function(){ //when page loads, this sends the IDs to jotformbuild with AJAX and runs the handleResult function on the returned data
	$.ajax({
	url: "jotformbuild.php",
        data: {"idlist": idlist},
	type: "POST",
	async: true,
	dataType: "JSON",
	success: function(data) {
		handleResult(data);
	},
    error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError);
    }
});
});
function handleResult(json){ //function that builds the forms identified by the ids
	var jsondata=JSON.stringify(json); //"stringifies" the JSON
	var result =$.parseJSON(jsondata).root; //parses the JSON at the root so that it can be separated into key, value pairs
	$.each(result, function(key, value) {
		var htmlstring=""; //empty string that will contain the data for forms
		var headstring=""; //empty string that will have the title and description
		$.each(value, function(key2, value2){
			if ("subHeader" in value2){ //'if' statement that finds the form's title and description and loads it into 'headstring'.
				var info=value2["subHeader"];
				var head=value2["text"];
				headstring += "<h1>" + head + "</h1><p>" + info + "</p>";
			}
			if("sublabels" in value2){
				var qid=value2["qid"];
				var test=value2["sublabels"];
				var type=value2["type"];
				var fname=test["first"];
				var lname=test["last"];
				htmlstring+= fname+"<input name='"+qid+"[]'"+ " id='"+fname+"' value=''"+"><br>"+lname+"<input name='"+qid+"[]'"+ " id='"+lname+"' value=''"+"><br>";
			}
			if (value2["type"]!=="control_button" && value2["type"]!=="control_head" && value2["type"]!=="control_fullname" && value2["type"]!=="control_radio" && value2["type"]!=="control_dropdown"){
				var qid=value2["qid"];
				var type=value2["type"];
				var name=value2["text"];
				htmlstring+= ""+name+"<input name='"+qid+"'"+ "id='"+name+"' value=''"+"><br>";
			}
			if ("options" in value2){
				var listing=value2["options"];
				var qid=value2["qid"];
				var type=value2["type"];
				var name=value2["text"];
				var l=listing.split("|"); //splits the options into an array 
				$.each (l, function(k, item){	//applies each value in the l-array to the item variable and loads each in turn into the htmlstring variable
					htmlstring+= name+": "+item+"<input type='radio' name='"+qid+"'"+ "id='"+name+"' value='"+item+"'><br>";	
				}
			)
				;
			}
			;
		}
	); 
		var num=idlist[i]; //variable that gets the value of the idlist during each loop. It is used later to set the hidden input for 'formid'.  
		var newDiv = document.createElement("form"); //creates the form element for each form
		newDiv.setAttribute("id", i); //gives each form an id linked with it's position relative to the formIDs that can be used by AJAX to serialize individual forms. 
		$(newDiv).append(headstring).html();
		$(newDiv).append("<button type='button' onClick=showHide("+i+") class='register' data-id='"+i+"' value='show'>click to display registration form</button>").html() //creates the button that will show/hide individual forms. uses the 'i' variable to send the number through the showHide() function and relate the function to the div that it shows/hides.
		var boxDiv=document.createElement("div"); //creates a div to hold the forms
		boxDiv.setAttribute("id", "main"+i); //gives the div an id associated with the form. It uses the 'i' variable to link 
		boxDiv.setAttribute("style", "display:none");
		$(newDiv).append(boxDiv); //places the form inside the div
		$(boxDiv).append(htmlstring).html();
		$(boxDiv).append(" <input type='hidden' name='formid' value="+num+">").html();
		$(boxDiv).append("<input type='button' name='submit' id='submit' onClick=submitFunction("+i+") class='button' value='Submit'/>").html;
		$(boxDiv).append("<div id='errorbox"+i+"'></div>").html()
		$("body").append(newDiv);
		i++;
	}
)
	;
}

function showHide(idnum){ //function to show or hide and individual "main[x]" div if the id of the div matches [x]. [x] comes through the idnum argument. 
	var idnum=idnum;
    var x = document.getElementById('main'+idnum);
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }

}

function submitFunction(formid){
	var formid=formid;
	var regex = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;
	var params = '';
	for( var a=0; a<document.getElementById(formid).elements.length; a++ )
	{
		var fieldValue = document.getElementById(formid).elements[a].value;
		var fieldID=document.getElementById(formid).elements[a].id;
		if (fieldValue=="" && fieldID.includes("E-mail")!==true){
			params += fieldID+" must be entered<br/>";}
		if (fieldID.includes("E-mail"))
			if(!regex.test(fieldValue))
				params +="You must enter a valid e-mail address<br/>";
	}
	if(params !==""){ 
		$("#errorbox"+formid).append(params);
		return false}
			var response=$.ajax({
			url: "jotformsubmit.php",
				dataType: "json",
				data: $('#'+formid).serialize(),
				type:"GET",
				async: false
		})
			alert("Thank you for registering")
			document.getElementById(formid).reset();
		$("#errorbox"+formid).empty();
}


</script>
<body></body>
