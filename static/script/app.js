/*
send form data via ajax and return the data to callback function 
*/
function send_form( name , func )
{
	var url = $('#'+name).attr('action');
	
	var params = {};
	$.each( $('#'+name).serializeArray(), function(index,value) 
	{
		params[value.name] = value.value;
	});
	
	
	$.post( url , params , func );	
}

/*
send form data via ajax and show the return content to pop div 
*/

function send_form_pop( name )
{
	return send_form( name , function( data ){ show_pop_box( data ); } );
}

/*
send form data via ajax and show the return content in front of the form 
*/
function send_form_in( name )
{	
	return send_form( name , function( data ){ set_form_notice( name , data ) } );
}


function set_form_notice( name , data )
{
	data = '<span class="label label-default">' + data + '</span>';
	
	if( $('#form_'+name+'_notice').length != 0 )
	{
		$('#form_'+name+'_notice').html(data);
	}
	else
	{
		var odiv = $( "<div class='form_notice'></div>" );
		odiv.attr( 'id' , 'form_'+name+'_notice' );
		odiv.html(data);
		$('#'+name).prepend( odiv );
	} 
	
}


function show_pop_box( data , popid )
{
	if( popid == undefined ) popid = 'lp_pop_box'
	//console.log($('#' + popid) );
	if( $('#' + popid).length == 0 )
	{
		var did = $('<div><div id="' + 'lp_pop_container' + '"></div></div>');
		did.attr( 'id' , popid );
		did.css( 'display','none' );
		$('body').prepend(did);
	} 
	
	if( data != '' )
		$('#lp_pop_container').html(data);
	
	var left = ($(window).width() - $('#' + popid ).width())/2;
	
	$('#' + popid ).css('left',left);
	$('#' + popid ).css('display','block');
}

function hide_pop_box( popid )
{
	if( popid == undefined ) popid = 'lp_pop_box'
	$('#' + popid ).css('display','none');
}

function send_talk()
{
	return send_form( 'talk_form' , function( data )
	{
		if( data == 'NOT_LOGIN' ) location = '?c=weibo&a=login';
		console.log(data);
		$('#talk_data').val('');
	} );
}

function bind_menu()
{
	$('ul#menus li').off('click');
	$('ul#menus li').on('click',function()
	{
		switch_to_channel( $(this).data('ckey') );
	});	
}

function switch_to_channel( ckey )
{
	$('ul#screens>li').removeClass('current');
	$('ul#menus>li').removeClass('current');

	$('#screen-' + ckey).addClass('current');
	$('#menu-' + ckey).addClass('current');

	// clean count
	$('#ccount-'+ckey).html('');

	// change title
	$(".ban-title").html('#&nbsp;'+ckey);

}

function send_to_ban( data )
{
	var data_obj = jQuery.parseJSON( data );
	if( data_obj.data ) send_to_channel( data );
}

function send_to_channel( data )
{
	var mdata_obj = jQuery.parseJSON( data );
	console.log( mdata_obj );
	ckey = mdata_obj.ckey || 'everything';
	//alert( ckey );
	
	// check if screen exists
	if( $('#menu-'+ckey).length < 1 )
	{
		$('ul#menus').append('<li id="menu-'+ckey+'" data-ckey="'+ckey+'">#&nbsp;'+ckey+' <span class="badge pull-right" id="ccount-'+ckey+'"></span></li>');
		bind_menu();
	}

	// incr count
	var now_count = parseInt($('#ccount-' + ckey).html());
	if( isNaN( now_count ) ) $('#ccount-' + ckey).html(1);
	else 	$('#ccount-' + ckey).html(now_count+1);

	// check if screen exists
	if( $('#feed-'+ckey).length < 1 )
	{
		$('ul#screens').prepend('<li id="screen-' + ckey + '"><ul id="feed-'+ckey+'" class="feeds"></ul></li>');  
	}

	// add data to screen
	var text = mdata_obj.data;
	if( mdata_obj.wbname ) text = '<span class="label label-danger">'+mdata_obj.wbname + '</span>&nbsp;' + text;

	$('#feed-'+ckey).prepend('<li><span class="source label label-primary">' + mdata_obj.source + '</span> ' + text + '&nbsp; <span class="timeline">'+ mdata_obj.timeline +'</span></li>')

	// do clean 
	var max = 50;

	if( $('#feed-'+ckey+' li').length > max )
	{
		$('#feed-'+ckey + ' li:last-child').remove();
		//$('#ccount-'+ckey).html(max);
	}
}



/* post demo
$.post( 'url&get var'  , { 'post':'value'} , function( data )
{
	var data_obj = jQuery.parseJSON( data );
	console.log( data_obj  );
	
	if( data_obj.err_code == 0  )
	{
					
	}
	else
	{
		
	}	
} );

*/