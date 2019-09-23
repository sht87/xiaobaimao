 //
  $('.radio_btn span').click(function(){
    	$(this).addClass('cur').siblings().removeClass('cur');
    })  
    
     
   function autoScroll(obj){  
			$(obj).find("ul").animate({  
				marginTop : "-30px"  
			},500,function(){  
				$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
			})  
		}  
    $(function(){  
         setInterval('autoScroll(".apple")',2000);
          
    }) 
    
    function autoScroll(obj){  
			$(obj).find("ul").animate({  
				marginTop : "-30px"  
			},500,function(){  
				$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
			})  
		}  
    $(function(){  
         setInterval('autoScroll(".apple1")',5000);
          
    }) 
    
//5.24
$('.ul_biao a').click(function(){
    $('.ul_biao a').removeClass('cur')
    $(this).addClass("cur");
    var name = $(this).attr("name");
    $(".biao_box").hide();
    $(".check_con").each(function (){   
     if($(this).attr("name")==name) $(this).show();
   });
});

//
var sl=$(".ul_zhan a").length;
if(sl>6){
	$(".ul_zhan a").each(function(index){
		if(index>5){
			$(this).hide(0)
		}
	})
	$(".ul_zhan .more").show()
}else{
	$(".ul_zhan .more").hide()
}

function opene(){
	$(".ul_zhan a").each(function(index){
		if(index>5){
			$(this).slideToggle(300);
			$(this).find(".t").removeClass("hover");
			$(this).find(".txt").slideUp();
		}
	})
}

$(".ul_zhan .more").click(function() {
	opene();
	$(this).toggleClass("hover");
	var text=$(this).find("span").text();
	if(text=="展开"){
		$(this).find("span").text("收起");
	}else if(text=="收起"){
        $(this).find("span").text("展开");
	}
});

//
$('.help_select .dl_select').click(function(){
	$(this).toggleClass('cur_show').siblings().removeClass('cur_show');
//	$('.dl_select dt').toggleClass('show');
//	
//  $(this).addClass('cur').siblings().removeClass('cur');
	
})

 //4.28
 $('.tan_alap').click(function(){
	 $('.tan_alap').hide();
	 $('.tan_con').hide();
 })
 $('.btn_close').click(function(){
	 $('.tan_alap').hide();
	 $('.tan_con').hide();
 })
    
    