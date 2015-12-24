



function watchForm(form, record) {
	var inputs = form.find(':input').not('[type=hidden], #ProjectReviewChangesetJson, #ProjectReviewEmail, #ProjectReviewComment, #ProjectReviewDone');
	var changeset = {};
	if($('#ProjectReviewChangesetJson').val()) {
		var changeset = JSON.parse($('#ProjectReviewChangesetJson').val());
	}
	$.each(inputs, function(index, input) {
		// on-change function
		$(input).change(function() {
			var modelName, relModelName, crossTable, objectId, objectFk, mainObjectId;
			var path = getPath($(input).attr('datapath'));
			var relation = {src: path[0], type: false, target: false, cross: false};
			
			if($(input).attr('datarelation')) {
				var split = $(input).attr('datarelation').split('.');
				if(split[0]) relation['src'] = split[0];
				if(split[1]) relation['type'] = split[1];	// habtm, hasmany, hasone(?), belongsto
				if(split[2]) relation['target'] = split[2];
				if(split[3] && relation.type === 'habtm') {
					relation['cross'] = split[3];
				}else{
					// having an Inflector::pluralize() would be nice...
					relation['cross'] = relation.src + 's' + relation.target;
				}
			}
			var mainObjectFk = relation.src.toLowerCase() + '_id';
			
			
			var idPath = path.slice(0);	// clone path
			// build the path to the id of the main model
			if(relation.type === 'habtm') {
				mainObjectId = $('[datapath="'+relation.src+'.id'+'"]').val();
				//object = record;
				
				var object = parseTree(record, idPath, relation);
				
				$.each(idPath, function(i, frag) {
					if(frag === relation.cross) {
						if(typeof object[0] !== 'undefined') {
							var match = false;
							$.each(object, function(n, set) {
								object = set[frag];
								if($(input).val() == object[idPath[i+1]]) {
									console.log('Match!');
									match = true;
									return false;
								}
							});
						}
						// record does not exist - create one
						objectFk = idPath[i+1];
						if(!match) {
							object = {};
							object['id'] = '';
							object[mainObjectFk] = mainObjectId;
							object[objectFk] = $(input).val();
						};
						return false;
					}else{
						if(typeof object[frag] !== 'undefined') object = object[frag];
					}
				});
			}else{
				idPath[idPath.length - 1] = 'id';	// doesn't apply for tagging!
				mainObjectId = $('[datapath="'+idPath.join('.')+'"]').val();
			}
			
			var value = $(input).val();
			if($(input).attr('type') === 'checkbox') {
				if(relation.type === 'habtm') {
					if(!input.checked) value = '';
					//else value = $(input).next('label').text();
				}else{
					if(!input.checked) value = 0;
				}
			}
			
			// get the master value from the record tree
			var master = parseTree(record, path)
			var lastBranch = changeset;
			var branches = [];
			$.each(path, function(i, frag) {
				if(relation.type === 'habtm') {
					if(path.length == i + 2) {
						if((!master && !value) || (master == value)) {
							// matching requires something else...
						}else{
							// assign to changeset
							if(typeof lastBranch[relation.cross] === 'undefined') lastBranch[relation.cross] = [];
							var len = lastBranch[relation.cross].length;
							lastBranch[relation.cross][len] = object;
							return false;
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
				}else{
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
							lastBranch['id'] = mainObjectId;
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
				}
			});
			
			$('#ProjectReviewChangesetJson').val(JSON.stringify(changeset, null, 4));
		});
	});
}

function parseTree(tree, path, relation) {
	$.each(path, function(i, frag) {
		if(relation.type && frag === relation.cross) {
			if(typeof tree[0] !== 'undefined') {
				var match = false;
				$.each(tree, function(n, set) {
					tree = set[frag];
					if($(input).val() == object[path[i+1]]) {
						console.log('Match!');
						match = true;
						return false;
					}
				});
			}
			// record does not exist - create one
			if(!match) {
				tree = {};
				tree['id'] = '';
				tree[mainObjectFk] = mainObjectId;
				tree[path[i+1]] = $(input).val();
			};
			return false;
		}else{
			// primary data tree (no relation)
			if(typeof tree[frag] !== 'undefined') tree = tree[frag];
		}
		
		
		if(typeof tree[frag] !== 'undefined') tree = tree[frag];
		// ##todo: nested arrays
	});
	// no match
	return tree;
}

function matchTree(tree, path, value) {
	var branches = [];
	$.each(path, function(i, frag) {
		
		if((!tree && !value) || (tree == value)) {
			// the property does not exist - return current branch
			if(typeof tree[frag] === 'undefined') return false;
		}else{
			if(typeof tree[frag] === 'undefined') tree[frag] = {};
		}
		branches[i] = tree;
		tree = tree[frag];
	});
	// no match
	return tree;
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
	
	var add = document.createElement('a');
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
	var remove = document.createElement('a');
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