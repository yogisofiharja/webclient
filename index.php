<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="codebase/skins/touch.css" type="text/css" media="screen" charset="utf-8">
	<script src="codebase/webix.js" type="text/javascript" charset="utf-8"></script>
	<script src="codebase/jquery.js" type="text/javascript" charset="utf-8"></script>
	<title>Testing web client</title>
</head>
<body>
	<form>
		<div id="areaA"></div>
		<div id="areaB"></div>
	</form>

	<script>


	webix.ready(function(){
		var films = webix.ajax().sync().post("http://io.nowdb.net/collection/select_all",{
			"token":"52f866f58d909e13236110e5",
			"project":"movie",
			"collection":"films"
		}, function(text, xml, xhr){
			return text;
		});
	// films = JSON.stringify(films);
	var datas = films.responseText;
	// console.log(datas);
	var form1 = [
		{ view:"text", label:'Title', name:"title" },
		{ view:"text", label:'Synopsis', name:"synopsis" },
		{ view:"text", label:'Rating', name:"rating" },
		{ view:"button", value: "Submit", click:function(){
			var form = this.getParentView();
			// var data = this.getValues();
			if (form.validate()){
				webix.ajax().sync().post("http://io.nowdb.net/collection/insert",{
					"token":"52f866f58d909e13236110e5",
					"project":"movie",
					"collection":"films",
					"title":title.value,
					"synopsis":synopsis.value,
					"rating":rating.value
				}, function(){					
					webix.message({ type:"debug", text:"Film is saved", expire:-1});
					$$("movies").sync(datas);
				});
			}
			else {
				webix.message({ type:"error", text:"Form data is invalid" });
			}
		}
	}
	];
	webix.ui.fullScreen();
	webix.ui({
		rows:[
		{
			view:"tabbar",
			type:"iconTop",
			multiview:true,
			options: [
			{id:"movies", icon:"film", value:"Movie"},
			{id:"actors", icon:"user", value:"Actors"},
			{id:"genres", icon:"tags", value:"Genres"},
			{id:"insert", icon:"tags", value:"Insert Film"}
			]
		},
		{
			view:"multiview",
			cells:[
			{
				view:"unitlist",
				id:"movies",
				scroll:true,
				sort:{
					by:"#title#",
					dir:"asc",
				},				
				uniteBy:function(obj){
					return obj.title.substr(0,1);
				},
				template:"#title#, rating : #rating#",
				data : datas,
				select:true
			},{
				id:"actors",
				view:"list"
			},{
				id:"genres",
				view:"list"
			},{
				id:"insert",
				view:"form",

				elements: form1,scroll:true,
				rules:{
					"title":webix.rules.isNotEmpty,
					"synopsis":webix.rules.isNotEmpty,
					"synopsis":webix.rules.isNotEmpty
				},
				elementsConfig:{
					labelPosition:"top"
				}

			}
			]	
		}

		]
	});
});
</script>
</body>
</html>