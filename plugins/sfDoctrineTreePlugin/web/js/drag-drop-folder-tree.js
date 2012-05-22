/************************************************************************************************************
This is a modified version for sfDoctrineTree to work with Prototype, Doctrine and Symfony.
It add an Add Node item to the context menu and sends Ajax requests though Prototype.
Author: Jacques Philip - 2008
jphilipatnoatakdotcom
************************************************************************************************************
Drag and drop folder tree
Copyright (C) 2006  DTHMLGoodies.com, Alf Magne Kalleland

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

Dhtmlgoodies.com., hereby disclaims all copyright interest in this script
written by Alf Magne Kalleland.

Alf Magne Kalleland, 2006
Owner of DHTMLgoodies.com


************************************************************************************************************/	
		
	var JSTreeObj;
	var treeUlCounter = 0;
	var nodeId = 1;
		
	/* Constructor */
	function JSDragDropTree()
	{
		var idOfTree;
		var imageFolder;
		var rootId;
    var model;
		var nameField;
		var deleteIcon;
		var renameIcon;
		var addIcon;
		var folderImage;
		var plusImage;
		var minusImage;
		var maximumDepth;
		var dragNode_source;
		var dragNode_parent;
		var dragNode_sourceNextSib;
		var dragNode_noSiblings;
		var menuModel;
    var menuModelRenameOnly;
    var menuModelDeleteOnly;
    var menuModelAddOnly;
    var menuModelRenameDelete;
    var menuModelRenameAdd;
    var menuModelDeleteAdd;
    var linkPartial;
    var dragHistory;
		
		var dragNode_destination;
		var floatingContainer;
		var dragDropTimer;
		var dropTargetIndicator;
		var insertAsSub;
		var indicator_offsetX;
		var indicator_offsetX_sub;
		var indicator_offsetY;
		
		this.rootId = 0;
		this.model = '';
		this.nameField = 'name';
		this.imageFolder = '/sfDoctrineTreePlugin/images/';
		this.folderImage = 'dhtmlgoodies_folder.gif';
		this.plusImage = 'dhtmlgoodies_plus.gif';
		this.minusImage = 'dhtmlgoodies_minus.gif';		
    this.deleteIcon = 'tree_delete.png';
    this.renameIcon = 'tree_rename.png';
    this.addIcon = 'tree_add.png';
		this.maximumDepth = 6;
    this.dragHistory = new Array();
		var messageMaximumDepthReached;
		var filePathRenameItem;
		var filePathDeleteItem;
		var filePathAddItem;
    var filePathSaveTree;
		var additionalRenameRequestParameters = {};
		var additionalDeleteRequestParameters = {};
    var additionalAddRequestParameters = {};

		var renameAllowed;
		var deleteAllowed;
		var addAllowed;
		var currentlyActiveItem;
		var contextMenu;
		var currentItemToEdit;		// Reference to item currently being edited(example: renamed)
		var helpObj;
		
		this.contextMenu = false;
		this.floatingContainer = document.createElement('UL');
		this.floatingContainer.style.position = 'absolute';
		this.floatingContainer.style.display='none';
		this.floatingContainer.id = 'floatingContainer';
		this.insertAsSub = false;
		document.body.appendChild(this.floatingContainer);
		this.dragDropTimer = -1;
		this.dragNode_noSiblings = false;
		this.currentItemToEdit = false;
		
		if(document.all){
			this.indicator_offsetX = 2;	// Offset position of small black lines indicating where nodes would be dropped.
			this.indicator_offsetX_sub = 4;
			this.indicator_offsetY = 2;
		}else{
			this.indicator_offsetX = 1;	// Offset position of small black lines indicating where nodes would be dropped.
			this.indicator_offsetX_sub = 3;
			this.indicator_offsetY = 2;			
		}
		if(Prototype.Browser.Opera){
			this.indicator_offsetX = 2;	// Offset position of small black lines indicating where nodes would be dropped.
			this.indicator_offsetX_sub = 3;
			this.indicator_offsetY = -7;				
		}

		this.messageMaximumDepthReached = ''; // Use '' if you don't want to display a message 
		
		this.renameAllowed = true;
		this.deleteAllowed = true;
		this.addAllowed = true;
		this.currentlyActiveItem = false;
		this.filePathRenameItem = 'folderTree_updateItem.php';
		this.filePathDeleteItem = 'folderTree_updateItem.php';
		this.filePathAddItem = 'folderTree_addItem.php';
    this.filePathSaveTree = 'folderTree_addItem.php';
    this.linkPartial = 'folderTree_addItem.php';
		this.ajaxObjects = new Array();
		this.helpObj = false;
		
		this.RENAME_STATE_BEGIN = 1;
		this.RENAME_STATE_CANCELED = 2;
		this.RENAME_STATE_REQUEST_SENDED = 3;
		this.renameState = null;
		this.ADD_STATE_BEGIN = 4;
		this.ADD_STATE_CANCELED = 5;
		this.ADD_STATE_REQUEST_SENDED = 6;
		this.addState = null;
	}
	
	
	/* JSDragDropTree class */
	JSDragDropTree.prototype = {
		addEvent : function(whichObject,eventType,functionName)
		{ 
		  if(whichObject.attachEvent){ 
		    whichObject['e'+eventType+functionName] = functionName; 
		    whichObject[eventType+functionName] = function(){whichObject['e'+eventType+functionName]( window.event );} 
		    whichObject.attachEvent( 'on'+eventType, whichObject[eventType+functionName] ); 
		  } else 
		    whichObject.addEventListener(eventType,functionName,false); 	    
		} 
		// }}}	
		,	
		removeEvent : function(whichObject,eventType,functionName)
		{ 
		  if(whichObject.detachEvent){ 
		    whichObject.detachEvent('on'+eventType, whichObject[eventType+functionName]); 
		    whichObject[eventType+functionName] = null; 
		  } else 
		    whichObject.removeEventListener(eventType,functionName,false); 
		} 
		,	
		Get_Cookie : function(name) { 
		   var start = document.cookie.indexOf(name+"="); 
		   var len = start+name.length+1; 
		   if ((!start) && (name != document.cookie.substring(0,name.length))) return null; 
		   if (start == -1) return null; 
		   var end = document.cookie.indexOf(";",len); 
		   if (end == -1) end = document.cookie.length; 
		   return unescape(document.cookie.substring(len,end)); 
		} 
		,
		// This function has been slightly modified
		Set_Cookie : function(name,value,expires,path,domain,secure) { 
			expires = expires * 60*60*24*1000;
			var today = new Date();
			var expires_date = new Date( today.getTime() + (expires) );
		    var cookieString = name + "=" +escape(value) + 
		       ( (expires) ? ";expires=" + expires_date.toGMTString() : "") + 
		       ( (path) ? ";path=" + path : "") + 
		       ( (domain) ? ";domain=" + domain : "") + 
		       ( (secure) ? ";secure" : ""); 
		    document.cookie = cookieString; 
		} 
		,
		setFileNameRename : function(newFileName)
		{
			this.filePathRenameItem = newFileName;
		}
		,
		setFileNameAdd : function(newFileName)
		{
			this.filePathAddItem = newFileName;
		}
		,
    setFileNameSave : function(newFileName)
    {
      this.filePathSaveTree = newFileName;
    }
    ,
    setLinkPartial : function(newLinkPartial)
    {
      this.linkPartial = newLinkPartial;
    }
    ,
		setFileNameDelete : function(newFileName)
		{
			this.filePathDeleteItem = newFileName;
		}
		,
		setAdditionalRenameRequestParameters : function(requestParameters)
		{
			this.additionalRenameRequestParameters = requestParameters;
		}
		,
		setAdditionalAddRequestParameters : function(requestParameters)
		{
			this.additionalAddRequestParameters = requestParameters;
		}
		,
		setAdditionalDeleteRequestParameters : function(requestParameters)
		{
			this.additionalDeleteRequestParameters = requestParameters;
		}
		,setRenameAllowed : function(renameAllowed)
		{
			this.renameAllowed = renameAllowed;			
		}
		,setaddAllowed : function(addAllowed)
		{
			this.addAllowed = addAllowed;			
		}
		,
		setDeleteAllowed : function(deleteAllowed)
		{
			this.deleteAllowed = deleteAllowed;	
		}
		,setMaximumDepth : function(maxDepth)
		{
			this.maximumDepth = maxDepth;	
		}
		,setMessageMaximumDepthReached : function(newMessage)
		{
			this.messageMaximumDepthReached = newMessage;
		}
		,	
		setImageFolder : function(path)
		{
			this.imageFolder = path;	
		}
		, 
    setNameField : function(name)
    {
      this.nameField = name;  
    }
    , 
    setRootId : function(id)
    {
      this.rootId = id;  
    }
    , 
    setModel : function(m)
    {
      this.model = m;  
    }
		,
		setFolderImage : function(imagePath)
		{
			this.folderImage = imagePath;			
		}
    ,
    setDeleteIcon : function(deleteIcon)
    {
      this.deleteIcon = deleteIcon;     
    }
    ,
    setRenameIcon : function(renameIcon)
    {
      this.renameIcon = renameIcon;     
    }
    ,
    setAddIcon : function(addIcon)
    {
      this.addIcon = addIcon;     
    }
		,
		setPlusImage : function(imagePath)
		{
			this.plusImage = imagePath;				
		}
		,
		setMinusImage : function(imagePath)
		{
			this.minusImage = imagePath;			
		}
		,		
		setTreeId : function(idOfTree)
		{
			this.idOfTree = idOfTree;			
		}	
		,
		saveTreeState: function()
		{
		  var menuItems = document.getElementById(this.idOfTree).getElementsByTagName('LI');
      initExpandedNodes = ',';
      for(var no=0;no<menuItems.length;no++){
        var subItems = menuItems[no].getElementsByTagName('UL');
        if(subItems.length>0 && subItems[0].style.display=='block'){
          thisNode = menuItems[no].getElementsByTagName('IMG')[0]; 
          if(thisNode.src.indexOf(JSTreeObj.minusImage)>=0)
            initExpandedNodes = initExpandedNodes +  menuItems[no].id.substring(4) + ',';
        }
      }
      JSTreeObj.Set_Cookie('dhtmlgoodies_expandedNodes',initExpandedNodes,500);
		}
		,
		expand : function(level)
		{
			var menuItems = document.getElementById(this.idOfTree).getElementsByTagName('LI');
			for(var no=0;no<level;no++){
				var subItems = menuItems[no].getElementsByTagName('UL');
				if(subItems.length>0 && subItems[0].style.display!='block'){
					JSTreeObj.showHideNode(false,menuItems[no].id);
				}			
			}
		}	
    ,
    expandAll : function()
    {
      var menuItems = document.getElementById(this.idOfTree).getElementsByTagName('LI');
      this.expand(menuItems.length);
    } 
		,
		collapseAll : function()
		{
			var menuItems = document.getElementById(this.idOfTree).getElementsByTagName('LI');
			for(var no=0;no<menuItems.length;no++){
				var subItems = menuItems[no].getElementsByTagName('UL');
				if(subItems.length>0 && subItems[0].style.display=='block'){
					JSTreeObj.showHideNode(false,menuItems[no].id);
				}			
			}		
		}	
		,
		/*
		Find top pos of a tree node
		*/
		getTopPos : function(obj){
			var top = obj.offsetTop/1;
			while((obj = obj.offsetParent) != null){
				if(obj.tagName!='HTML')top += obj.offsetTop;
			}			
			if(document.all)top = top/1 + 13; else top = top/1 + 4;		
			return top;
		}
		,	
		/*
		Find left pos of a tree node
		*/
		getLeftPos : function(obj){
			var left = obj.offsetLeft/1 + 1;
			while((obj = obj.offsetParent) != null){
				if(obj.tagName!='HTML')left += obj.offsetLeft;
			}
	  			
			if(document.all)left = left/1 - 2;
			return left;
		}	
			
		,
		showHideNode : function(e,inputId)
		{
			if(inputId){
				if(!document.getElementById(inputId))return;
				thisNode = document.getElementById(inputId).getElementsByTagName('IMG')[0]; 
			}else {
				thisNode = this;
				if(this.tagName=='A')thisNode = this.parentNode.getElementsByTagName('IMG')[0];	
				
			}
			if(!thisNode)return;
			if(thisNode.style.visibility=='hidden')return;		
			var parentNode = thisNode.parentNode;
			inputId = parentNode.id.substring(4);
			if(thisNode.src.indexOf(JSTreeObj.plusImage)>=0){
				thisNode.src = thisNode.src.replace(JSTreeObj.plusImage,JSTreeObj.minusImage);
				var ul = parentNode.getElementsByTagName('UL')[0];
				ul.style.display='block';
				if(!initExpandedNodes)initExpandedNodes = ',';
				if(initExpandedNodes.indexOf(',' + inputId + ',')<0) initExpandedNodes = initExpandedNodes + inputId + ',';
			}else{
				thisNode.src = thisNode.src.replace(JSTreeObj.minusImage,JSTreeObj.plusImage);
				parentNode.getElementsByTagName('UL')[0].style.display='none';
				initExpandedNodes = initExpandedNodes.replace(',' + inputId,'');
			}	
			JSTreeObj.Set_Cookie('dhtmlgoodies_expandedNodes',initExpandedNodes,500);			
			return false;						
		}
		,
		/* Initialize drag */
		initDrag : function(e)
		{
			if(document.all)e = event;	
			
			var subs = JSTreeObj.floatingContainer.getElementsByTagName('LI');
			if(subs.length>0){
				if(JSTreeObj.dragNode_sourceNextSib){
					JSTreeObj.dragNode_parent.insertBefore(JSTreeObj.dragNode_source,JSTreeObj.dragNode_sourceNextSib);
				}else{
					JSTreeObj.dragNode_parent.appendChild(JSTreeObj.dragNode_source);
				}					
			}
			
			JSTreeObj.dragNode_source = this.parentNode;
			JSTreeObj.dragNode_parent = this.parentNode.parentNode;
			JSTreeObj.dragNode_sourceNextSib = false;

			
			if(JSTreeObj.dragNode_source.nextSibling)
			  JSTreeObj.dragNode_sourceNextSib = JSTreeObj.dragNode_source.nextSibling;
			JSTreeObj.dragNode_destination = false;
			JSTreeObj.dragDropTimer = 0;
			JSTreeObj.timerDrag();
			return false;
		}
		,
		timerDrag : function()
		{	
			if(this.dragDropTimer>=0 && this.dragDropTimer<10){
				this.dragDropTimer = this.dragDropTimer + 1;
				setTimeout('JSTreeObj.timerDrag()',20);
				return;
			}
			if(this.dragDropTimer==10)
			{
				JSTreeObj.floatingContainer.style.display='block';
				JSTreeObj.floatingContainer.appendChild(JSTreeObj.dragNode_source);	
			}
		}
		,
		moveDragableNodes : function(e)
		{
			if(JSTreeObj.dragDropTimer<10)return;
			if(document.all)e = event;
			dragDrop_x = e.clientX/1 + 5 + document.body.scrollLeft;
			dragDrop_y = e.clientY/1 + 5 + document.documentElement.scrollTop;	
					
			JSTreeObj.floatingContainer.style.left = dragDrop_x + 'px';
			JSTreeObj.floatingContainer.style.top = dragDrop_y + 'px';
			
			var thisObj = this;
			if(thisObj.tagName=='A' || thisObj.tagName=='IMG')thisObj = thisObj.parentNode;

			JSTreeObj.dragNode_noSiblings = false;
			var tmpVar = thisObj.getAttribute('noSiblings');
			if(!tmpVar)tmpVar = thisObj.noSiblings;
			if(tmpVar=='true')JSTreeObj.dragNode_noSiblings=true;
					
			if(thisObj && thisObj.id)
			{
				JSTreeObj.dragNode_destination = thisObj;
				var img = thisObj.getElementsByTagName('IMG')[1];
				var tmpObj= JSTreeObj.dropTargetIndicator;
				tmpObj.style.display='block';
				
				var eventSourceObj = this;
				if(JSTreeObj.dragNode_noSiblings && eventSourceObj.tagName=='IMG')eventSourceObj = eventSourceObj.nextSibling;
				
				var tmpImg = tmpObj.getElementsByTagName('IMG')[0];
				if(this.tagName=='A' || JSTreeObj.dragNode_noSiblings){
					tmpImg.src = tmpImg.src.replace('ind1','ind2');	
					JSTreeObj.insertAsSub = true;
					tmpObj.style.left = (JSTreeObj.getLeftPos(eventSourceObj) + JSTreeObj.indicator_offsetX_sub) + 'px';
				}else{
					tmpImg.src = tmpImg.src.replace('ind2','ind1');
					JSTreeObj.insertAsSub = false;
					tmpObj.style.left = (JSTreeObj.getLeftPos(eventSourceObj) + JSTreeObj.indicator_offsetX) + 'px';
				}
				
				
				tmpObj.style.top = (JSTreeObj.getTopPos(thisObj) + JSTreeObj.indicator_offsetY) + 'px';
			}
			
			return false;
			
		}
		,
		dropDragableNodes:function()
		{
			if(JSTreeObj.dragDropTimer<10){				
				JSTreeObj.dragDropTimer = -1;
				return;
			}
			var showMessage = false;
			if(JSTreeObj.dragNode_destination){	// Check depth
				var countUp = JSTreeObj.dragDropCountLevels(JSTreeObj.dragNode_destination,'up');
				var countDown = JSTreeObj.dragDropCountLevels(JSTreeObj.dragNode_source,'down');
				var countLevels = countUp/1 + countDown/1 + (JSTreeObj.insertAsSub?1:0);		
				
				if(countLevels>JSTreeObj.maximumDepth){
					JSTreeObj.dragNode_destination = false;
					showMessage = true; 	// Used later down in this function
				}
			}
			
			
			if(JSTreeObj.dragNode_destination){			
				if(JSTreeObj.insertAsSub){
					var uls = JSTreeObj.dragNode_destination.getElementsByTagName('UL');
					if(uls.length>0){
						ul = uls[0];
						ul.style.display='block';
						
						var lis = ul.getElementsByTagName('LI');

						if(lis.length>0){	// Sub elements exists - drop dragable node before the first one
							ul.insertBefore(JSTreeObj.dragNode_source,lis[0]);	
						}else {	// No sub exists - use the appendChild method - This line should not be executed unless there's something wrong in the HTML, i.e empty <ul>
							ul.appendChild(JSTreeObj.dragNode_source);	
						}
					}else{
						var ul = document.createElement('UL');
						ul.style.display='block';
						JSTreeObj.dragNode_destination.appendChild(ul);
						ul.appendChild(JSTreeObj.dragNode_source);
					}
					var img = JSTreeObj.dragNode_destination.getElementsByTagName('IMG')[0];					
					img.style.visibility='visible';
					img.src = img.src.replace(JSTreeObj.plusImage,JSTreeObj.minusImage);					
					
					
				}else{
				  
          var nextSib = Element.next(JSTreeObj.dragNode_destination,'li');
					if(nextSib){
						nextSib.parentNode.insertBefore(JSTreeObj.dragNode_source,nextSib);
					}else{
						JSTreeObj.dragNode_destination.parentNode.appendChild(JSTreeObj.dragNode_source);
					}
				}	
				/* Clear parent object */
				var tmpObj = JSTreeObj.dragNode_parent;
				var lis = tmpObj.getElementsByTagName('LI');
				if(lis.length==0){
					var img = tmpObj.parentNode.getElementsByTagName('IMG')[0];
					img.style.visibility='hidden';	// Hide [+],[-] icon
					tmpObj.parentNode.removeChild(tmpObj);						
				}
				JSTreeObj.saveTreeState();
				
				//Save the drag operation as a 2 dim array: source-operation-target 
				if (JSTreeObj.insertAsSub)
				  op = [JSTreeObj.dragNode_source.id.substring(4), 'moveAsFirstChildOf', JSTreeObj.dragNode_destination.id.substring(4)];
        else if (nextSib)
          op = [JSTreeObj.dragNode_source.id.substring(4), 'moveAsPrevSiblingOf', nextSib.id.substring(4)];
        else
				  op = [JSTreeObj.dragNode_source.id.substring(4), 'moveAsLastChildOf', JSTreeObj.dragNode_destination.parentNode.parentNode.id.substring(4)];
        JSTreeObj.dragHistory.push(op) ;
			}else{
				// Putting the item back to it's original location
				
				if(JSTreeObj.dragNode_sourceNextSib){
					JSTreeObj.dragNode_parent.insertBefore(JSTreeObj.dragNode_source,JSTreeObj.dragNode_sourceNextSib);
				}else{
					JSTreeObj.dragNode_parent.appendChild(JSTreeObj.dragNode_source);
				}			
					
			}
			JSTreeObj.dropTargetIndicator.style.display='none';		
			JSTreeObj.dragDropTimer = -1;	
			if(showMessage && JSTreeObj.messageMaximumDepthReached)alert(JSTreeObj.messageMaximumDepthReached);
		}
		,
		createDropIndicator : function()
		{
			this.dropTargetIndicator = document.createElement('DIV');
			this.dropTargetIndicator.style.position = 'absolute';
			this.dropTargetIndicator.style.display='none';			
			var img = document.createElement('IMG');
			img.src = this.imageFolder + 'dragDrop_ind1.gif';
			img.id = 'dragDropIndicatorImage';
			this.dropTargetIndicator.appendChild(img);
			document.body.appendChild(this.dropTargetIndicator);
			
		}
		,
		dragDropCountLevels : function(obj,direction,stopAtObject){
			var countLevels = 0;
			if(direction=='up'){
				while(obj.parentNode && obj.parentNode!=stopAtObject){
					obj = obj.parentNode;
					if(obj.tagName=='UL')countLevels = countLevels/1 +1;
				}		
				return countLevels;
			}	
			
			if(direction=='down'){ 
				var subObjects = obj.getElementsByTagName('LI');
				for(var no=0;no<subObjects.length;no++){
					countLevels = Math.max(countLevels,JSTreeObj.dragDropCountLevels(subObjects[no],"up",obj));
				}
				return countLevels;
			}	
		}		
		,
		cancelEvent : function()
		{
			return false;	
		}
		,
		cancelSelectionEvent : function()
		{
			
			if(JSTreeObj.dragDropTimer<10)return true;
			return false;	
		}		
		,highlightItem : function(inputObj,e)
		{
			if(JSTreeObj.currentlyActiveItem)JSTreeObj.currentlyActiveItem.className = '';
			this.className = 'highlightedNodeItem';
			JSTreeObj.currentlyActiveItem = this;
		}
		,
		removeHighlight : function()
		{
			if(JSTreeObj.currentlyActiveItem)JSTreeObj.currentlyActiveItem.className = '';
			JSTreeObj.currentlyActiveItem = false;
		}
		,
		hasSubNodes : function(obj)
		{
			var subs = obj.getElementsByTagName('LI');
			if(subs.length>0)return true;
			return false;	
		}
		,
		deleteItem : function(obj1,obj2)
		{
			var message = 'Click OK to delete item ' + obj2.innerHTML;
			if(this.hasSubNodes(obj2.parentNode)) message = message + ' and it\'s sub nodes';
			if(confirm(message)){
				this.__deleteItem_step2(obj2.parentNode);	// Sending <LI> tag to the __deleteItem_step2 method	
			}
		}
    ,
		__refreshDisplay : function(obj)
		{
			if(this.hasSubNodes(obj))return;

			var img = obj.getElementsByTagName('IMG')[0];
			img.style.visibility = 'hidden';	
		}
		,
		__deleteItem_step2 : function(obj)
		{
			new Ajax.Request(JSTreeObj.filePathDeleteItem, {
        parameters: {id: obj.id.substring(4), model: this.model, field: this.nameField },
        onFailure: function(){ alert('Error in the Ajax request'); },
        onSuccess: function(transport){ JSTreeObj.__deleteComplete(transport.responseText, obj) },
        onLoading: function() { Element.show('doctrine_tree_indicator'); },
        onComplete: function() { Element.hide('doctrine_tree_indicator'); }
        });
		}		
    ,
		__deleteComplete : function(response,obj)
		{
			if(response!='OK'){
				alert('ERROR WHEN TRYING TO DELETE NODE: ' + response); 	// Delete failed
			}else{
				var parentRef = obj.parentNode.parentNode;
				obj.parentNode.removeChild(obj);
				this.__refreshDisplay(parentRef);
				
			}			
			
		}
		,
		__renameComplete : function(e,inputObj,response)
		{
			if (response == 'OK') {
			   inputObj.style.display='none';
         inputObj.nextSibling.style.visibility='visible';
         if(inputObj.value.length>0)
           inputObj.nextSibling.innerHTML = inputObj.value;
      }  
			else {
			  alert('ERROR WHEN TRYING TO RENAME NODE: ' + response);
			  JSTreeObj.__cancelRename(e, inputObj);
			}
		}
		,
		__saveRenameTextBoxChanges : function(e,inputObj)
		{
		  if(inputObj && inputObj.value.length<=0)
		    this.__cancelRename(false,this);
			if(!inputObj && this)inputObj = this;
			if(document.all)e = event;
			if(e.keyCode && e.keyCode==27){
				JSTreeObj.__cancelRename(e,inputObj);
				return;
			}
				
				// Send changes to the server.
				if (JSTreeObj.renameState != JSTreeObj.RENAME_STATE_BEGIN) {
					return;
				}
				JSTreeObj.renameState = JSTreeObj.RENAME_STATE_REQUEST_SENDED;
				
				renameId = inputObj.parentNode.id.substring(4);
				
				new Ajax.Request(JSTreeObj.filePathRenameItem, {
        parameters: {id: renameId, model: JSTreeObj.model, field: JSTreeObj.nameField, value: inputObj.value},
        onFailure: function(){ alert('Error in the Ajax request'); JSTreeObj.__cancelRename(false, inputObj); },
        onSuccess: function(transport){ JSTreeObj.__renameComplete(e, inputObj, transport.responseText) },
        onLoading: function() { Element.show('doctrine_tree_indicator'); },
        onComplete: function() { Element.hide('doctrine_tree_indicator');JSTreeObj.renameState = null; }
        });
		}
		,
		__cancelRename : function(e,inputObj)
		{
			JSTreeObj.renameState = JSTreeObj.RENAME_STATE_CANCELED;
			if(!inputObj && this)inputObj = this;
			inputObj.value = JSTreeObj.helpObj.innerHTML;
			inputObj.nextSibling.innerHTML = JSTreeObj.helpObj.innerHTML;
			inputObj.style.display = 'none';
			inputObj.nextSibling.style.visibility = 'visible';
		}
		,
		__renameCheckKeyCode : function(e)
		{
			if(document.all)e = event;
			inputObj = Event.element(e);
			if(e.keyCode==13){	// Enter pressed
				JSTreeObj.__saveRenameTextBoxChanges(e,inputObj);	
			}	
			if(e.keyCode==27){	// ESC pressed
				JSTreeObj.__cancelRename(false,inputObj);
			}
		}
		,
		__createTextBox : function(obj)
		{
			var textBox = document.createElement('INPUT');
			textBox.className = 'folderTreeTextBox';
			textBox.value = obj.innerHTML;
			obj.parentNode.insertBefore(textBox,obj);	
			textBox.id = 'textBox' + obj.parentNode.id.substring(4);
			textBox.onblur = this.__saveRenameTextBoxChanges;	
			textBox.onkeydown = this.__renameCheckKeyCode;
			this.__renameEnableTextBox(obj);
		}
		,
		__renameEnableTextBox : function(obj)
		{
			JSTreeObj.renameState = JSTreeObj.RENAME_STATE_BEGIN;
			obj.style.visibility = 'hidden';
			obj.previousSibling.value = obj.innerHTML;
			obj.previousSibling.style.display = 'inline';	
			obj.previousSibling.select();
		}
		,
		renameItem : function(obj1,obj2)
		{
		  if (JSTreeObj.renameState == JSTreeObj.RENAME_STATE_REQUEST_SENDED) {
          return;
        }
			currentItemToEdit = obj2.parentNode;	// Reference to the <li> tag.
			if(!obj2.previousSibling || obj2.previousSibling.tagName.toLowerCase()!='input'){
				this.__createTextBox(obj2);
			}else{
				this.__renameEnableTextBox(obj2);
			}
			this.helpObj.innerHTML = obj2.innerHTML;

		}
    ,
    __addComplete: function(response, obj)
    {
      if (response == 'ERROR')
        alert('There was an error saving the record to the database.');
      else
      {
        id = obj.parentNode.id.substring(4);
        initExpandedNodes = this.Get_Cookie('dhtmlgoodies_expandedNodes'); 
        if(initExpandedNodes.indexOf(',' + id + ',')<0)
          initExpandedNodes = initExpandedNodes + id + ',';
        JSTreeObj.Set_Cookie('dhtmlgoodies_expandedNodes',initExpandedNodes,500);
        
        hResponse = response.evalJSON(true);
        var ul = Element.down(obj.parentNode,'ul');
        if (!ul)
        {
          ul = document.createElement('UL');
          obj.parentNode.appendChild(ul);
        }
        var li = document.createElement('LI');
        li.id = 'node' +  hResponse.id;
        ul.appendChild(li);
        var a = document.createElement('A');
        a.id = 'nodeATag' + hResponse.id;
        li.appendChild(a);
        Element.replace('nodeATag' + hResponse.id, hResponse.partial);
        a.id = 'nodeATag' + hResponse.id;
        JSTreeObj.addNode(li);
        var img = Element.down(obj.parentNode,'img');
        img.style.visibility='visible';
        if (img.src.indexOf(JSTreeObj.plusImage)>=0)
          JSTreeObj.showHideNode(false,obj.parentNode.id);
      }
    }
		,
		__saveAddItem: function(child, parent, childName)
		{
		  if (JSTreeObj.addState != JSTreeObj.ADD_STATE_BEGIN) {
          return;
      }
      JSTreeObj.addState = JSTreeObj.ADD_STATE_BEGIN;
        
      parentId = parent.parentNode.id.substring(4);
      
      new Ajax.Request(JSTreeObj.filePathAddItem, {
      parameters: {id: parentId, model: this.model, field: this.nameField, value: childName, root: this.rootId, linkPartial: this.linkPartial},
      onFailure: function(){ alert('Error in the Ajax request.\nPlease refresh the page.'); },
      onSuccess: function(transport){ JSTreeObj.__addComplete(transport.responseText,parent) },
      onLoading: function() { Element.show('doctrine_tree_indicator') },
      onComplete: function() { Element.hide('doctrine_tree_indicator');JSTreeObj.addState = null; }
      });
		}
		,
    addItem : function(child, parent)
    {
      var count = JSTreeObj.dragDropCountLevels(parent,'up');
      if (count >= JSTreeObj.maximumDepth && JSTreeObj.messageMaximumDepthReached)
      {
        alert(JSTreeObj.messageMaximumDepthReached);
        return;
      }
      if (JSTreeObj.addState == JSTreeObj.ADD_STATE_REQUEST_SENDED) {
          return;
      }
      var childName =  prompt('Add a child to ' + parent.text + ':');
      if (childName && childName.length > 0)
      {
        JSTreeObj.addState = JSTreeObj.ADD_STATE_BEGIN;
        this.__saveAddItem(child, parent, childName);
      }
    }
    ,
    saveTree: function()
    {
      if (JSTreeObj.dragHistory.length == 0)
      {
        alert('It does not look like you moved any node in the tree.\nThe changes for adding, renaming and inserting nodes are saved automatically.')
        return;
      }
      new Ajax.Request(JSTreeObj.filePathSaveTree, {
      parameters: {model: this.model, field: this.nameField, rootId: this.rootId, dragHistory: JSTreeObj.dragHistory.toJSON()},
      onFailure: function(){ alert('Error in the Ajax request'); },
      onSuccess: function(transport){ JSTreeObj.__saveComplete(transport.responseText) },
      onLoading: function() { Element.show('doctrine_tree_indicator') },
      onComplete: function() { Element.hide('doctrine_tree_indicator'); }
      });
    }
    ,
    __saveComplete: function(response)
    {
      if (response != 'OK')
        alert('There was an error saving the order of the tree.');
      else
      {  
        alert('The tree has been saved.');
        JSTreeObj.dragHistory.clear();
      }
    }
    ,
    addNode : function(node) {
    // No children var set ?
        var noChildren = false;
        var tmpVar = node.getAttribute('noChildren');
        if(!tmpVar)tmpVar = node.noChildren;
        if(tmpVar=='true')noChildren=true;
        // No drag var set ?
        var noDrag = false;
        var tmpVar = node.getAttribute('noDrag');
        if(!tmpVar)tmpVar = node.noDrag;
        if(tmpVar=='true' || tmpVar==true)noDrag=true;
             
        nodeId++;
        var subItems = node.getElementsByTagName('UL');
        var img = document.createElement('IMG');
        img.src = this.imageFolder + this.plusImage;
        img.onclick = JSTreeObj.showHideNode;
        
        if(subItems.length==0)img.style.visibility='hidden';else{
          subItems[0].id = 'tree_ul_' + treeUlCounter;
          treeUlCounter++;
        }
        var aTag = node.getElementsByTagName('A')[0];
        aTag.id = 'nodeATag' + node.id.substring(4);
        //aTag.onclick = JSTreeObj.showHideNode;
        if(!noDrag)aTag.onmousedown = JSTreeObj.initDrag;
        if(!noChildren)aTag.onmousemove = JSTreeObj.moveDragableNodes;
        node.insertBefore(img,aTag);
        //node.id = 'dhtmlgoodies_treeNode' + nodeId;
        var folderImg = document.createElement('IMG');
        if(!noDrag)folderImg.onmousedown = JSTreeObj.initDrag;
        folderImg.onmousemove = JSTreeObj.moveDragableNodes;
        if(node.className){
          folderImg.src = this.imageFolder + node.className;
        }else{
          folderImg.src = this.imageFolder + this.folderImage;
        }
        node.insertBefore(folderImg,aTag);
        
        if(this.contextMenu){
          var noDelete = node.getAttribute('noDelete');
          if(!noDelete)noDelete = node.noDelete;
          var noRename = node.getAttribute('noRename');
          if(!noRename)noRename = node.noRename;
          var noAdd = node.getAttribute('noAdd');
          if(!noAdd)noAdd = node.noAdd;
          
          if(noRename=='true' && noDelete=='true' && noAdd=='true')
            {}
          else{ if(noRename=='true' && !noDelete==true && !noAdd==true)
            {this.contextMenu.attachToElement(aTag,false,menuModelDeleteAdd);}
          else{ if(noRename=='true' && noDelete=='true' && !noAdd==true)
            {this.contextMenu.attachToElement(aTag,false,menuModelAddOnly);}
          else{ if(noRename=='true' && !noDelete==true && noAdd=='true')
            {this.contextMenu.attachToElement(aTag,false,menuModelDeleteOnly);}
          else{ if(!noRename==true && !noDelete==true && !noAdd==true)
            {this.contextMenu.attachToElement(aTag,false,menuModel);}
          else{ if(!noRename==true && noDelete=='true' && !noAdd==true)
            {this.contextMenu.attachToElement(aTag,false,menuModelRenameAdd);}
          else{ if(!noRename==true && !noDelete==true && noAdd=='true')
            {this.contextMenu.attachToElement(aTag,false,menuModelRenameDelete);}
          else{ if(!noRename==true && noDelete=='true' && noAdd=='true')
            {this.contextMenu.attachToElement(aTag,false,menuModelRenameOnly);}
          }}}}}}}
        }
        this.addEvent(aTag,'contextmenu',this.highlightItem);
    }
		,
		initTree : function()
		{
			JSTreeObj = this;
			JSTreeObj.createDropIndicator();
			document.documentElement.onselectstart = JSTreeObj.cancelSelectionEvent;
			document.documentElement.ondragstart = JSTreeObj.cancelEvent;
			document.documentElement.onmousedown = JSTreeObj.removeHighlight;
			
			/* Creating help object for storage of values */
			this.helpObj = document.createElement('DIV');
			this.helpObj.style.display = 'none';
			document.body.appendChild(this.helpObj);
			
			/* Create context menu */
			if(this.deleteAllowed || this.renameAllowed || this.addAllowed){
				try{
					/* Creating menu model for the context menu, i.e. the datasource */
					menuModel = new DHTMLGoodies_menuModel();
          if(this.renameAllowed)menuModel.addItem(1,'Rename',JSTreeObj.imageFolder +  JSTreeObj.renameIcon,'',false,'JSTreeObj.renameItem');
					if(this.deleteAllowed)menuModel.addItem(2,'Delete',JSTreeObj.imageFolder +  JSTreeObj.deleteIcon,'',false,'JSTreeObj.deleteItem');
					if(this.addAllowed)menuModel.addItem(3,'Add child',JSTreeObj.imageFolder +  JSTreeObj.addIcon,'',false,'JSTreeObj.addItem');
					menuModel.init();	
					
					menuModelRenameOnly = new DHTMLGoodies_menuModel();
					if(this.renameAllowed)menuModelRenameOnly.addItem(4,'Rename',JSTreeObj.imageFolder +  JSTreeObj.renameIcon,'',false,'JSTreeObj.renameItem');
					menuModelRenameOnly.init();
					
					menuModelDeleteOnly = new DHTMLGoodies_menuModel();
					if(this.deleteAllowed)menuModelDeleteOnly.addItem(5,'Delete',JSTreeObj.imageFolder +  JSTreeObj.deleteIcon,'',false,'JSTreeObj.deleteItem');
					menuModelDeleteOnly.init();	 
          
          menuModelAddOnly = new DHTMLGoodies_menuModel();
          if(this.addAllowed)menuModelAddOnly.addItem(6,'Add child',JSTreeObj.imageFolder +  JSTreeObj.addIcon,'',false,'JSTreeObj.addItem');
          menuModelAddOnly.init(); 
          
          menuModelRenameDelete = new DHTMLGoodies_menuModel();
          if(this.renameAllowed)menuModelRenameDelete.addItem(7,'Rename',JSTreeObj.imageFolder +  JSTreeObj.deleteIcon,'',false,'JSTreeObj.renameItem');
          if(this.deleteAllowed)menuModelRenameDelete.addItem(8,'Delete',JSTreeObj.imageFolder +  JSTreeObj.renameIcon,'',false,'JSTreeObj.deleteItem');
          menuModelRenameDelete.init();          
          
          menuModelRenameAdd = new DHTMLGoodies_menuModel();
          if(this.renameAllowed)menuModelRenameAdd.addItem(9,'Rename',JSTreeObj.imageFolder +  JSTreeObj.renameIcon,'',false,'JSTreeObj.renameItem');
          if(this.addAllowed)menuModelRenameAdd.addItem(10,'Add child',JSTreeObj.imageFolder +  JSTreeObj.addIcon,'',false,'JSTreeObj.addItem');
          menuModelRenameAdd.init();  
					
          menuModelDeleteAdd = new DHTMLGoodies_menuModel();
          if(this.deleteAllowed)menuModelDeleteAdd.addItem(11,'Delete',JSTreeObj.imageFolder +  JSTreeObj.deleteIcon,'',false,'JSTreeObj.deleteItem');
          if(this.addAllowed)menuModelDeleteAdd.addItem(12,'Add child',JSTreeObj.imageFolder +  JSTreeObj.addIcon,'',false,'JSTreeObj.addItem');
          menuModelDeleteAdd.init();
          
					window.refToDragDropTree = this;
					
					this.contextMenu = new DHTMLGoodies_contextMenu();
					this.contextMenu.setWidth(120);
					referenceToDHTMLSuiteContextMenu = this.contextMenu;
				}catch(e){
					
				}
			}
					
			var nodeId = 0;
			var dhtmlgoodies_tree = document.getElementById(this.idOfTree);
			var menuItems = dhtmlgoodies_tree.getElementsByTagName('LI');	// Get an array of all menu items
			for(var no=0;no<menuItems.length;no++){
			  this.addNode(menuItems[no]);				
			}	
			
			initExpandedNodes = this.Get_Cookie('dhtmlgoodies_expandedNodes');
			if(initExpandedNodes){
				var nodes = initExpandedNodes.split(',');
				for(var no=0;no<nodes.length;no++){
					if(nodes[no])this.showHideNode(false,'node'+nodes[no]);	
				}			
			}			
			
			
			document.documentElement.onmousemove = JSTreeObj.moveDragableNodes;	
			document.documentElement.onmouseup = JSTreeObj.dropDragableNodes;
		}
		,
		__addAdditionalRequestParameters : function(ajax, parameters)
		{
			for (var parameter in parameters) {
				ajax.setVar(parameter, parameters[parameter]);
			}
		}
	}