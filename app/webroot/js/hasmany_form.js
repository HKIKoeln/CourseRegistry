

function watchForm(form, record) {
	var inputs = form.find(':input').not('[type=hidden], #ProjectReviewChangesetJson, #ProjectReviewEmail, #ProjectReviewComment, #ProjectReviewDone');
	var changeset = {};
	var test = $('#ProjectReviewChangesetJson').val();
	if(test) {
		var changeset = JSON.parse($('#ProjectReviewChangesetJson').val());
	}
	$.each(inputs, function(index, input) {
		// on-change function
		$(input).change(function() {
			var value = $(input).val();
			if($(input).attr('type') == 'checkbox') {
				if(!input.checked) value = '';
				else value = $(input).next('label').text();
			}
			
			var path = getPath($(input).attr('datapath'));
			var idPath = path.slice(0);
			idPath[idPath.length - 1] = 'id';	// doesn't apply for tagging!
			var objectId = $('[datapath="'+idPath.join('.')+'"]').val();
			// get the master value from the record tree
			var master = record;
			$.each(path, function(i, frag) {
				if(typeof master[frag] !== 'undefined') master = master[frag];
			});
			
			var lastBranch = changeset;
			var branches = [];
			
			$.each(path, function(i, frag) {
				if(path.length == i + 1) {
					if((!master && !value) || (master == value)) {
						delete lastBranch[frag];
						
						// tidy up the tree
						while(path.length > 0) {
							var reverse = path.slice(0);	// clone the array
							$.each(reverse.reverse(), function(n, frag) {
								var reversekey = path.length - 1 - n;
								if(branches[reversekey]
								&& branches[reversekey][frag]) {
									if(Object.keys(branches[reversekey][frag]).length === 1
									&& branches[reversekey][frag]['id']) {
										delete branches[reversekey][frag]['id'];
									}
									if($.isEmptyObject(branches[reversekey][frag])) {
										delete branches[reversekey][frag];
									}
								}
							});
							path.pop();
						}
					}else{
						// assign to changeset
						lastBranch['id'] = objectId;
						lastBranch[frag] = value;
					}
				}else{
					if((!master && !value) || (master == value)) {
						if(typeof lastBranch[frag] === 'undefined') return false;
					}else{
						if(typeof lastBranch[frag] === 'undefined') lastBranch[frag] = {};
					}
					branches[i] = lastBranch;
					lastBranch = lastBranch[frag];
				}
			});
			
			$('#ProjectReviewChangesetJson').val(JSON.stringify(changeset, null, 4));
		});
	});
}

function getPath(string) {
	var path = [];
	path = string.split('.');
	return path;
}

function populateForm(container, schema, data) {
	var i = 0;
	$.each(data, function(index, record) {
		buildForm(container, schema, index, record);
		i = index + 1;
		window[container.id + '-formIndex'] = i;
	});
	
	var add = document.createElement('button');
	$(add).attr({id:$(container).attr('id') + 'add', class:'add button'});
	$(add).text('add another ' + $(container).attr('id'));
	$(add).on('click', function() {
		//$($(this).attr('data')).remove();
		return false;
	});
	$(add).appendTo(container);
}

function buildForm(container, schema, index, record) {
	var baseId = $(container).attr('id');
	var fieldset = document.createElement('fieldset');
	$(fieldset).attr({id:baseId + '-' + index});
	
	$.each(schema, function(key, options) {
		var keysplit = key.split('.');
		var field = keysplit[0];
		var model = baseId;	// pretend the parent container ID resembels the model name
		if(keysplit[1]) {
			field = keysplit[1];
			model = keysplit[0];
		}
		var attributes, div, label, input, selectoptions;
		div = document.createElement('div');
		
		attributes = options.attributes;
		attributes.name = 'data[' + model + '][][' + field + ']';
		attributes.datapath = model+'.'+index+'.'+field;
		if(!attributes.id) {
			attributes.id = model + index + camelize(field);
		}
		
		if(attributes.type != 'hidden') {
			if(!options.label) options.label = humanize(field);
			label = document.createElement('label');
			$(label).attr({for:attributes.id});
			$(label).text(options.label);
			$(div).append(label);
		}
		
		// field types
		if(attributes.type == 'hidden') {
			input = document.createElement('input');
			$(div).attr({style:'display:none;'});
		}
		else if(attributes.type == 'select') {
			input = document.createElement('select');
			$(div).addClass('input select');
			selectoptions = window[options.options];
			$.each(selectoptions, function(okey, ovalue) {
				var option = document.createElement('option');
				$(option).attr({value:okey}).text(ovalue);
				$(option).attr(attributes).appendTo(input);
			});
		}
		else if(attributes.type == 'textarea') {
			input = document.createElement('textarea');
			$(div).addClass('input textarea');
		}
		else{
			input = document.createElement('input');
			$(div).addClass('input text');
		}
		
		if(record) {
			// special rule for link text-field
			if(model == 'ProjectLink' && field == 'title') {
				if(window['record'].Project.name == record['title'] ) {
					record['title'] = '';
				}
			}
			$(input).val(record[field]);
		}
		
		$(input).attr(attributes).appendTo(div);
		$(div).appendTo(fieldset);
	});
	
	// button to remove the last object - but only if all visible fields are empty!
	var remove = document.createElement('button');
	$(remove).attr({id:baseId + index + 'remove', data:baseId + '-' + index, class:'remove button'});
	$(remove).text('remove this ' + baseId);
	$(remove).on('click', function() {
		var target = $('#' + $(this).attr('data'));
		target.remove();
		return false;
	});
	$(remove).appendTo(fieldset);
	
	$(fieldset).appendTo(container);
}

function camelize(str) {
	return str.toLowerCase()
    // Replaces any - or _ characters with a space 
    .replace( /[-_]+/g, ' ')
    // Removes any non alphanumeric characters 
    .replace( /[^\w\s]/g, '')
    // Uppercases the first character in each group immediately following a space 
    // (delimited by spaces) 
    .replace( / (.)/g, function($1) { return $1.toUpperCase(); })
    // Removes spaces 
    .replace( / /g, '' )
	// ucfirst
	.replace(/^(.)/g, function($1) { return $1.toUpperCase(); });
}
function humanize(str) {
	return str.toLowerCase()
    // Replaces any - or _ characters with a space 
    .replace( /[-_]+/g, ' ')
    // Removes any non alphanumeric characters 
    .replace( /[^\w\s]/g, '')
    // Uppercases the first character in each group immediately following a space 
    // (delimited by spaces) 
    .replace( / (.)/g, function($1) { return $1.toUpperCase(); })
    // ucfirst
	.replace(/^(.)/g, function($1) { return $1.toUpperCase(); });
}