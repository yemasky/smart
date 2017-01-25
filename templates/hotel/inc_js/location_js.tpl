<script type="text/javascript">
	$(document).ready(function(){
		//省
		var xml_data;
		$.ajax({url:"static/area/Area.xml",
			success:function(xml){
				xml_data = xml;
				$(xml).find("province").each(function(){
					var t = $(this).attr("name");//this->
					var location = $(this).attr("location");
					var selected = '';
					if(location == '<%$location_province%>') {
						selected = 'selected';
					}
					$("#location_province").append("<option value='"+location+"' "+selected+">"+t+"</option>");
					setCity();
				});
			},
			error: function(e) {
				alert(e);
			} 
		});
		//市
		$("#location_province").change(function(){
			$("#location_city>option").remove();
			//$("#location_province").next().find('span').text("<%$arrayLaguage['please_select']['page_laguage_value']%>");
			$("#location_town>option").remove();
			//$("#location_city").next().find('span').text("<%$arrayLaguage['please_select']['page_laguage_value']%>");
			var pname = $("#location_province").val();
			$(xml_data).find("province[location='"+pname+"']>city").each(function(){
				var city = $(this).attr("name");//this->
				var location = $(this).attr("location");
				$("#location_city").append("<option value='"+location+"'>"+city+"</option>");
			});
			///查找<city>下的所有第一级子元素(即区域)
			var cname = $("#location_city").val();
			$(xml_data).find("city[location='"+cname+"']>country").each(function(){
				var area = $(this).attr("name");//this->
				var location = $(this).attr("location");
				$("#location_town").append("<option value='"+location+"'>"+area+"</option>");
			});
		});
		//区
		$("#location_city").change(function(){
			$("#location_town>option").remove();
			//$("#location_city").next().find('span').text("<%$arrayLaguage['please_select']['page_laguage_value']%>");
			var cname = $("#location_city").val();
			$(xml_data).find("city[location='"+cname+"']>country").each(function(){
				var area = $(this).attr("name");//this->
				var location = $(this).attr("location");
				$("#location_town").append("<option value='"+location+"'>"+area+"</option>");
			});
		});
		function setCity() {
			$("#location_city>option").remove();
			//$("#location_province").next().find('span').text("<%$arrayLaguage['please_select']['page_laguage_value']%>");
			$("#location_town>option").remove();
			//$("#location_city").next().find('span').text("<%$arrayLaguage['please_select']['page_laguage_value']%>");
			var pname = '<%$location_province%>';
			var selected = '';
			$(xml_data).find("province[location='"+pname+"']>city").each(function(){
				var city = $(this).attr("name");//this->
				var location = $(this).attr("location");
				if(location == '<%$location_city%>') {
					selected = 'selected';
				}
				$("#location_city").append("<option value='"+location+"' "+selected+">"+city+"</option>");
				selected = '';
			});
			///查找<city>下的所有第一级子元素(即区域)
			var cname = $("#location_city").val();
			$(xml_data).find("city[location='"+cname+"']>country").each(function(){
				var area = $(this).attr("name");//this->
				var location = $(this).attr("location");
				if(location == '<%$location_town%>') {
					selected = 'selected';
				}
				$("#location_town").append("<option value='"+location+"' "+selected+">"+area+"</option>");
				selected = '';
			});
		}
	});
	
	
</script>