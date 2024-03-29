﻿(function($) {	
 
var settings; var group1; var group2; var onSort; $.configureBoxes = function(options) { settings = { box1View: 'box1View', box1Storage: 'box1Storage', box1Filter: 'box1Filter', box1Clear: 'box1Clear', box1Counter: 'box1Counter', box2View: 'box2View', box2Storage: 'box2Storage', box2Filter: 'box2Filter', box2Clear: 'box2Clear', box2Counter: 'box2Counter', to1: 'to1', to2up : 'to2up' , to2down : 'to2down' , allTo1: 'allTo1', to2: 'to2', allTo2: 'allTo2', transferMode: 'move', sortBy: 'text', useFilters: true, useCounters: true, useSorting: false }; $.extend(settings, options); group1 = { view: settings.box1View, storage: settings.box1Storage, filter: settings.box1Filter, clear: settings.box1Clear, counter: settings.box1Counter }; group2 = { view: settings.box2View, storage: settings.box2Storage, filter: settings.box2Filter, clear: settings.box2Clear, counter: settings.box2Counter }; if (settings.sortBy == 'text') { onSort = function(a, b) { var aVal = a.text.toLowerCase(); var bVal = b.text.toLowerCase(); if (aVal < bVal) { return -1; } if (aVal > bVal) { return 1; } return 0; }; } else { onSort = function(a, b) { var aVal = a.value.toLowerCase(); var bVal = b.value.toLowerCase(); if (aVal < bVal) { return -1; } if (aVal > bVal) { return 1; } return 0; }; } if (settings.useFilters) { $('#' + group1.filter).keyup(function() { Filter(group1); }); $('#' + group2.filter).keyup(function() { Filter(group2); }); $('#' + group1.clear).click(function() { ClearFilter(group1); }); $('#' + group2.clear).click(function() { ClearFilter(group2); }); } if (IsMoveMode()) { $('#' + group2.view).dblclick(function() { MoveSelected(group2, group1); }); $('#' + settings.to1).click(function() { MoveSelected(group2, group1); }); $('#' + settings.allTo1).click(function() { MoveAll(group2, group1); }); } else { $('#' + group2.view).dblclick(function() { RemoveSelected(group2, group1); }); $('#' + settings.to1).click(function() { RemoveSelected(group2, group1); }); $('#' + settings.allTo1).click(function() { RemoveAll(group2, group1); }); } $('#' + group1.view).dblclick(function() { MoveSelected(group1, group2); }); 
		   $('#' + settings.to2up).click(function() { 
					 
					 var listbox =  $('#' + settings.box2View);
					 var selIndex  =  $('#' + settings.box2View).attr("selectedIndex"); 
					
								
					var sel_length =  $('#' + settings.box2View).attr("length");
					if (selIndex <=0 || sel_length < 2 ){
						 return ;			
					}
					else{
					if(selIndex == 0 ) {
						 increment =   1;
					}else{
						 increment =   -1;
					}
			var newIndex = eval(selIndex+ increment);	
			var selValue =  $('#' + settings.box2View + ' option:selected').val();	  
			var selText = $('#' + settings.box2View + ' option:selected').text(); 
			 
			var pass_val = $('#' + settings.box2View + '  option:eq('+newIndex+')').val(); 
			var pass_text = $('#' + settings.box2View + '  option:eq('+newIndex+')').text() ; 
			
			$('#' + settings.box2View + ' option:selected').val(pass_val);  
			$('#' + settings.box2View + ' option:selected').text(pass_text) ; 
			
			 $('#' + settings.box2View + '  option:eq('+newIndex+')').val(selValue);
			 $('#' + settings.box2View + '  option:eq('+newIndex+')').text(selText); 
			 
			  $('#' + settings.box2View + '  option:eq('+selIndex+')').attr("selected",""); 
			  $('#' + settings.box2View + '  option:eq('+newIndex+')').attr("selected","selected"); 
					}
				});  
		   
		   
		   
		    $('#' + settings.to2down).click(function() { 
					 
					 var listbox =  $('#' + settings.box2View);
					 var selIndex  =  $('#' + settings.box2View).attr("selectedIndex");
					  
					var sel_length =  $('#' + settings.box2View).attr("length");
				 
					
					if (selIndex<0 || selIndex ==  sel_length-1 || sel_length < 2){ 
						 return;
					}else{
			
				 if(selIndex == 0 ) {
						 increment =   1;
					}else{
						 increment =   1;
					}
					
					var newIndex = eval(selIndex+ increment);	 
			var selValue =  $('#' + settings.box2View + ' option:selected').val();	  
			var selText = $('#' + settings.box2View + ' option:selected').text(); 
			 
			var pass_val = $('#' + settings.box2View + '  option:eq('+newIndex+')').val(); 
			var pass_text = $('#' + settings.box2View + '  option:eq('+newIndex+')').text() ; 
			
			$('#' + settings.box2View + ' option:selected').val(pass_val);  
			$('#' + settings.box2View + ' option:selected').text(pass_text) ; 
			
			 $('#' + settings.box2View + '  option:eq('+newIndex+')').val(selValue);
			 $('#' + settings.box2View + '  option:eq('+newIndex+')').text(selText); 
			 
			 
		 	  $('#' + settings.box2View + '  option:eq('+selIndex+')').attr("selected",""); 
			  $('#' + settings.box2View + '  option:eq('+newIndex+')').attr("selected","selected"); 
					}
				});  
		   
		   $('#' + settings.to2).click(function() {  MoveSelected(group1, group2); }); $('#' + settings.allTo2).click(function() { MoveAll(group1, group2); }); if (settings.useCounters) { UpdateLabel(group1); UpdateLabel(group2); } if (settings.useSorting) { SortOptions(group1); SortOptions(group2); } $('#' + group1.storage + ',#' + group2.storage).css('display', 'none'); }; function UpdateLabel(group) { var showingCount = $("#" + group.view + " option").size(); var hiddenCount = $("#" + group.storage + " option").size(); $("#" + group.counter).text('Showing ' + showingCount + ' of ' + (showingCount + hiddenCount)); } function Filter(group) { var filterLower; if (settings.useFilters) { filterLower = $('#' + group.filter).val().toString().toLowerCase(); } else { filterLower = ''; } $('#' + group.view + ' option').filter(function(i) { var toMatch = $(this).text().toString().toLowerCase(); return toMatch.indexOf(filterLower) == -1; }).appendTo('#' + group.storage); $('#' + group.storage + ' option').filter(function(i) { var toMatch = $(this).text().toString().toLowerCase(); return toMatch.indexOf(filterLower) != -1; }).appendTo('#' + group.view); try { $('#' + group.view + ' option').removeAttr('selected'); } catch (ex) { } if (settings.useSorting) { SortOptions(group); } if (settings.useCounters) { UpdateLabel(group); } } function SortOptions(group) { var $toSortOptions = $('#' + group.view + ' option'); $toSortOptions.sort(onSort); $('#' + group.view).empty().append($toSortOptions); } function MoveSelected(fromGroup, toGroup) { if (IsMoveMode()) { $('#' + fromGroup.view + ' option:selected').appendTo('#' + toGroup.view); } else { $('#' + fromGroup.view + ' option:selected:not([class*=copiedOption])').clone().appendTo('#' + toGroup.view).end().end().addClass('copiedOption'); } try { $('#' + fromGroup.view + ' option,#' + toGroup.view + ' option').removeAttr('selected'); } catch (ex) { } Filter(toGroup); if (settings.useCounters) { UpdateLabel(fromGroup); } } function MoveAll(fromGroup, toGroup) { if (IsMoveMode()) { $('#' + fromGroup.view + ' option').appendTo('#' + toGroup.view); } else { $('#' + fromGroup.view + ' option:not([class*=copiedOption])').clone().appendTo('#' + toGroup.view).end().end().addClass('copiedOption'); } try { $('#' + fromGroup.view + ' option,#' + toGroup.view + ' option').removeAttr('selected'); } catch (ex) { } Filter(toGroup); if (settings.useCounters) { UpdateLabel(fromGroup); } } function RemoveSelected(removeGroup, otherGroup) { $('#' + otherGroup.view + ' option.copiedOption').add('#' + otherGroup.storage + ' option.copiedOption').remove(); try { $('#' + removeGroup.view + ' option:selected').appendTo('#' + otherGroup.view).removeAttr('selected'); } catch (ex) { } $('#' + removeGroup.view + ' option').add('#' + removeGroup.storage + ' option').clone().addClass('copiedOption').appendTo('#' + otherGroup.view); Filter(otherGroup); if (settings.useCounters) { UpdateLabel(removeGroup); } } function RemoveAll(removeGroup, otherGroup) { $('#' + otherGroup.view + ' option.copiedOption').add('#' + otherGroup.storage + ' option.copiedOption').remove(); try { $('#' + removeGroup.storage + ' option').clone().addClass('copiedOption').add('#' + removeGroup.view + ' option').appendTo('#' + otherGroup.view).removeAttr('selected'); } catch (ex) { } Filter(otherGroup); if (settings.useCounters) { UpdateLabel(removeGroup); } } function ClearFilter(group) { $('#' + group.filter).val(''); $('#' + group.storage + ' option').appendTo('#' + group.view); try { $('#' + group.view + ' option').removeAttr('selected'); } catch (ex) { } if (settings.useSorting) { SortOptions(group); } if (settings.useCounters) { UpdateLabel(group); } } function IsMoveMode() { return settings.transferMode == 'move'; } })(jQuery);