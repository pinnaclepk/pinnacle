var lb_widgets = 0;

function getHeight( e )
{
	if ( e.style.Height )
	{
		return e.style.Height;
	}
	else
	{
		return e.offsetHeight;
	}
}

function getWidth( e )
{
	if ( e.style.Width )
	{
		return e.style.Width;
	}
	else
	{
		return e.offsetWidth;
	}
}

function getPageSize(){
	
	var xScroll, yScroll;
	
	if (window.innerHeight && window.scrollMaxY) {	
		xScroll = document.body.scrollWidth;
		yScroll = window.innerHeight + window.scrollMaxY;
	} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
		xScroll = document.body.scrollWidth;
		yScroll = document.body.scrollHeight;
	} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
		xScroll = document.body.offsetWidth;
		yScroll = document.body.offsetHeight;
	}
	
	var windowWidth, windowHeight;
	if (self.innerHeight) {	// all except Explorer
		windowWidth = self.innerWidth;
		windowHeight = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
		windowWidth = document.documentElement.clientWidth;
		windowHeight = document.documentElement.clientHeight;
	} else if (document.body) { // other Explorers
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}	
	
	// for small pages with total height less then height of the viewport
	if(yScroll < windowHeight){
		pageHeight = windowHeight;
	} else { 
		pageHeight = yScroll;
	}

	// for small pages with total width less then width of the viewport
	if(xScroll < windowWidth){	
		pageWidth = windowWidth;
	} else {
		pageWidth = xScroll;
	}


	arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	return arrayPageSize;
}

//
function getPageScroll(){

	var yScroll;

	if (self.pageYOffset) {
		yScroll = self.pageYOffset;
	} else if (document.documentElement && document.documentElement.scrollTop){	 // Explorer 6 Strict
		yScroll = document.documentElement.scrollTop;
	} else if (document.body) {// all other Explorers
		yScroll = document.body.scrollTop;
	}

	arrayPageScroll = new Array('',yScroll) 
	return arrayPageScroll;
}

function addWidget( content )
{
	 
	lb_widgets++;
	hideSelects( 'hidden' );
	 
	var objBody = document.getElementsByTagName('body').item(0);
	var zIndex = lb_widgets ? lb_widgets * 1000 : 1000;	
	var arrayPageSize = getPageSize();
	var arrayPageScroll = getPageScroll();
	
	var objOverlay = document.createElement('div');
	objOverlay.setAttribute('id','lb_layer' + lb_widgets );
	objOverlay.style.display = 'none';
	objOverlay.style.position = 'absolute';
	objOverlay.style.top = '0';
	objOverlay.style.left = '0';
	objOverlay.style.zIndex = zIndex;
 	objOverlay.style.width = '100%';
 	objOverlay.className = 'lb_overlay';
 	objOverlay.style.height = (arrayPageSize[1] + 'px');	
	objBody.insertBefore(objOverlay, objBody.firstChild);	
	
	var objLockbox = document.createElement("div"); 	
	objLockbox.setAttribute('id','lb_content' + lb_widgets );
	objLockbox.style.visibility = 'hidden';
	objLockbox.style.position = 'fixed';
	objLockbox.style.top = '0';
	objLockbox.style.left = '0';
	objLockbox.style.zIndex = zIndex + 1 ;	
	objBody.insertBefore(objLockbox, objBody.firstChild);
	
	content = content.replace(/\\n/g, '\n');
	content = content.replace(/\\t/g, '\t');
	content = content.replace(/\\r/g, '\r');
	content = content.replace( /^\s+/g, "" );
	
	objLockbox.innerHTML = content;
	
	var objContent = objLockbox.firstChild;
	height = getHeight( objContent );
	width = getWidth( objContent );
	
	cltop   = (arrayPageScroll[1] + ( (arrayPageSize[3] -  height ) / 2 ) );
	clleft	= (                     ( (arrayPageSize[0] -  width ) / 2 ) );
	
	//objLockbox.style.top  = cltop  < 0 ? '0px' : cltop  + 'px'; // By Khushbu
	objLockbox.style.top  = '10px'; // By Khushbu
	
	objLockbox.style.left = clleft < 0 ? '0px' : clleft + 'px';
	
	objOverlay.style.display = '';
	objLockbox.style.visibility = '';
	
}

function close_widget()
{
	var activewidget = lb_widgets;
	
	hideSelects(''); 
	
	lId = 'lb_layer' + activewidget;
	cId = 'lb_content' + activewidget;

	objElement = document.getElementById(cId);
	
	if (objElement && objElement.parentNode && objElement.parentNode.removeChild)
	{
		objElement.parentNode.removeChild(objElement);
	}	
	
	objElement = document.getElementById(lId);
	
	if (objElement && objElement.parentNode && objElement.parentNode.removeChild)
	{
		objElement.parentNode.removeChild(objElement);
		lb_widgets--;
	}
}

function hideSelects( visibility )
{
	selects = document.getElementsByTagName('select');
	
	for(i = 0; i < selects.length; i++)
	{
		if ( !selects[i].rel )
		{
			selects[i].rel = 'ddl_' + lb_widgets;
		}
		
		if ( selects[i].rel == 'ddl_' + lb_widgets )
		{
			selects[i].style.visibility = visibility;
		}
	}
}