

function HasManyForm(formSelector, changesetSelector, exclude, record, parentForm) {
	if(parentForm) {
		//this.parent = parentForm;
		this.form = parentForm.form;
		this.inputs = parentForm.inputs;
		this.exclude = parentForm.exclude;
		this.changeset = parentForm.changeset;
		this.changesetSelector = parentForm.changesetSelector;
		this.record = parentForm.record;
		this.objectCount = parent.objectCount;
	}else{
		this.form = $(formSelector);
		this.inputs;
		this.exclude = exclude;
		this.changeset = {};
		this.changesetSelector = changesetSelector;
		this.record = record;
		this.objectCount = {};
	}
}


HasManyForm.prototype.watchForm = function() {
	var self = this;
	if($(this.changesetSelector).val()) {
		// ##ToDo: this has to be applied to the entire form for the admin view
		//this.changeset = JSON.parse($(this.changesetSelector).val());
		
		// for the time being: don't mess, reset everything! (discard entered data)
		window.location.reload(true);
	}
	this.inputs = this.form.find(':input').not('[type=hidden], [value=submit], ' + this.exclude + ', ' + this.changesetSelector);
	$.each(self.inputs, function(index, input) {
		var wf = self.initInput(input);
		// the actual on-change method!
		$(input).change(function() {
			self.processInput(wf, input);
		});
	});
};

HasManyForm.prototype.processInput = function(wf, input) {
	this.getValue(wf, input);
	this.getRecord(wf);
	this.createChangeset(wf);
	// rewrite the resulting changeset
	$(this.changesetSelector).val(JSON.stringify(this.changeset, null, 4));
};

HasManyForm.prototype.initInput = function(input) {
	var wf = {};	// the watchForm variables object - to be passed around...
	wf.path = $(input).attr('datapath').split('.');
	wf.relation = {src: wf.path[0], type: false, target: false, cross: false};
	
	if($(input).attr('datarelation')) {
		var split = $(input).attr('datarelation').split('.');
		if(split[0]) wf.relation['src'] = split[0];
		if(split[1]) wf.relation['type'] = split[1];	// habtm, hasmany, hasone(?), belongsto
		if(split[2]) wf.relation['target'] = split[2];
		if(split[3] && wf.relation.type === 'habtm') {
			wf.relation['cross'] = split[3];
		}else{
			// having an Inflector::pluralize() would be nice...
			wf.relation['cross'] = wf.relation.src + 's' + wf.relation.target;
		}
	}
	wf.mainObjectFk = wf.relation.src.toLowerCase() + '_id';
	return wf;
}

HasManyForm.prototype.createChangeset = function(wf) {
	var self = this;
	var lastBranch = this.changeset;
	var branches = [];
	var realPath = [];
	var compareObject = (wf.relation.type === 'hasmany' && wf.path[wf.path.length-1] === 'id');
	$.each(wf.path, function(i, frag) {
		realPath.push(frag);
		if(wf.path.length == i + 1) {
			if(wf.matchResult.match & !compareObject) {
				delete lastBranch[frag];
				self.tidyTree(wf.path, branches);
			}
		}else if(wf.path.length == i + 2) {
			if(wf.relation.type === 'habtm' && frag === wf.relation.cross) {
				if(wf.matchResult.match) {
					// remove
					branches[i] = lastBranch[frag];
					if(typeof lastBranch[frag][0] !== 'undefined') {
						$.each(lastBranch[frag], function(n, set) {
							if((set[wf.path[i+1]] === wf.valueOption) || (set.id && set.id === wf.matchResult.obj.id)) {
								realPath.push(n);
								// delete the array element
								lastBranch[frag].remove(n);
								self.tidyTree(realPath, branches);
								return false;
							}
						});
					}
					
				}else{
					// add
					if(typeof lastBranch[wf.relation.cross] === 'undefined') lastBranch[wf.relation.cross] = [];
					var len = lastBranch[wf.relation.cross].length;
					lastBranch[wf.relation.cross][len] = wf.resultObject;
				}
				return false;
			}else{
				if(!compareObject) {
					// apply common resultObjects
					if(!wf.matchResult.match) {
						if(typeof lastBranch[frag] === 'undefined') lastBranch[frag] = {};
						$.each(wf.resultObject, function(key, value) {
							lastBranch[frag][key] = value;
						});
					}
					// continue to stoplevel 2 and check match - eventually remove it there again :o)
				}
			}
		}else if(wf.path.length == i + 3 && compareObject) {
			if(typeof lastBranch[frag] === 'undefined') lastBranch[frag] = {};
			lastBranch[frag][wf.path[i+1]] = wf.matchResult.obj;
			// if object added, return - if deleted go on to tidy the tree
			if(!$.isEmptyObject(wf.matchResult.obj)) {
				return false;
			}else{
				delete lastBranch[frag][wf.path[i+1]];
				branches[i] = lastBranch;
				self.tidyTree(wf.path, branches);
			}
		}
		
		if(wf.matchResult.match) {
			// error
			if(typeof lastBranch[frag] === 'undefined') return false;
		}else{
			if(typeof lastBranch[frag] === 'undefined') lastBranch[frag] = {};
		}
		
		branches[i] = lastBranch;
		lastBranch = lastBranch[frag];
	});
};

HasManyForm.prototype.tidyTree = function(p, branches) {
	var path = p.slice(0);
	while(path.length > 0) {
		var reverse = path.slice(0);	// clone the array
		$.each(reverse.reverse(), function(n, frag) {
			var reversekey = path.length - 1 - n;
			
			if(branches[reversekey]
			&& (branches[reversekey][frag] || branches[reversekey].hasOwnProperty(frag))) {
				
				if(Object.keys(branches[reversekey][frag]).length === 1
				&& branches[reversekey][frag]['id']) {
					delete branches[reversekey][frag]['id'];
				}
				
				// habtm arrays
				if(Object.keys(branches[reversekey][frag]).length === 1) {
					// habtm arrays
					var keys = Object.keys(branches[reversekey][frag])
					if(branches[reversekey][frag][keys[0]].constructor === Array
					&& branches[reversekey][frag][keys[0]].length === 0) {
						delete branches[reversekey][frag];
					}
				}
				
				// hasmany objects - otherwise delete objects set's the corresponding array value to NULL
				if(branches[reversekey][frag] && branches[reversekey][frag].constructor === Array) {
					var copy = branches[reversekey][frag]
					$.each(copy, function(key, value) {
						if(typeof value === 'undefined') branches[reversekey][frag].remove(key);
					});
					if(branches[reversekey][frag].length === 0) {
						delete branches[reversekey][frag];
					}
				}
				
				if($.isEmptyObject(branches[reversekey][frag])) {
					delete branches[reversekey][frag];
				}
			}
			
		});
		path.pop();
	}
};

HasManyForm.prototype.parseRecord = function(wf) {
	var tree = this.record;
	var path = wf.path;
	var tagExists = false;
	var result = {match:false, path:[], obj:{}};
	$.each(path, function(i, frag) {
		if(wf.relation.type === 'habtm' && frag === wf.relation.cross) {
			// iterate over existing objects, create a new one if none matches
			if(typeof tree[0] !== 'undefined') {
				$.each(tree, function(n, set) {
					// get the crossTable entry from a sub-relation
					if(typeof set[frag] !== 'undefined') set = set[frag];
					if(set[path[i+1]] === wf.valueOption) tagExists = true;
					if((!set[path[i+1]] && !wf.value) || (wf.value == set[path[i+1]])) {
						result.match = true;
						result.obj = set;
						result.path.push(n);
						return false;	// the current tree branch matches - break tree loop
					}
					// tag exists, but current choice does not
					if(tagExists && wf.value !== wf.valueOption) {
						// the empty record (id only) bedoelt: delete!
						result.obj['id'] = set.id;
						result.path.push(n);
					}
				});
			}
			// record does not exist - create one
			if(!tagExists) {
				result.obj['id'] = '';
				result.obj[wf.mainObjectFk] = wf.mainObjectId;
				result.obj[path[i+1]] = wf.value;
			}
			return false;	// break path loop
		}
		else if(wf.relation.type === 'hasmany' && path.length === i + 1 && frag === 'id') {
			// we're checking if a subobject is being removed
			if(tree[frag] && wf.value === tree[frag]) {
				// removing an existing record, create a "delete-object"
				result.match = false;
				result.obj['id'] = tree.id;
			}
			// removing/adding a non-existing record: we won't get here
			result.path = path;
			return false;
		}
		else{
			// primary data tree, skip on relation. OR evaluate on "hasmany" & no relation(!)
			// return object or value according to path
			// provide matching for primary data
			if(path.length == i + 1) {
				if((!tree[frag] && !wf.value) || (tree[frag] == wf.value)) {
					// the property does not exist - return current branch
					result.match = true
					if(typeof tree[frag] === 'undefined') return false;
				}else{
					if(typeof tree[frag] === 'undefined') tree[frag] = {};
				}
			}
			// return either the final object, the value or the empty object created 3 lines above
			
			// matching against empty hasmany objects
			if(typeof tree[frag] === 'undefined') return false;
			
			tree = tree[frag];
		}
		result.path.push(frag);
	});
	// removing a created habtm tag that didn't exist before
	if(wf.relation.type === 'habtm' && !wf.value && !tagExists) {
		result.match = true;
		// return empty default object
	}
	// the add method requires the parent foreignKey to be processed
	if(wf.relation.type === 'hasmany' && wf.path[wf.path.length - 1] === wf.mainObjectFk && wf.value) {
		// adding a nonexistant object
		result.match = false;	// add
		result.obj['id'] = '';
		result.obj[wf.mainObjectFk] = wf.value;
		// how to identify these cases? - add: parent_foreignKey - remove: id (empty) -> path-key!
	}
	if(wf.relation.type === 'hasmany' && wf.path[wf.path.length - 1] === 'id' && !wf.value) {
		// removing a nonexistant object
		result.path = wf.path;
		result.match = true;	// remove
	}
	return result;
}

HasManyForm.prototype.getRecord = function(wf) {
	// get the object, the path to the object and id of that object, where these changes go to
	wf.resultObject = {};
	if(!wf.relation.type || wf.relation.type === 'hasmany') {
		// straight data - no relation
		wf.idPath = wf.path.slice(0);
		wf.idPath[wf.idPath.length - 1] = 'id';	// doesn't apply for tagging!
		wf.mainObjectId = $('[datapath="' + wf.idPath.join('.') + '"]').val();
		// value should be fetched after change (take care where to invoke this function!)
		wf.resultObject['id'] = wf.mainObjectId;
		wf.resultObject[wf.path[wf.path.length - 1]] = wf.value;
		wf.matchResult = this.parseRecord(wf);	// wf.mainObjectId must be defined before
	}
	else{
		// relation types
		if(wf.relation.type === 'habtm') {
			wf.mainObjectId = $('[datapath="' + wf.relation.src + '.id' + '"]').val();
		}
		wf.matchResult = this.parseRecord(wf);	// wf.mainObjectId must be defined before
		wf.resultObject = wf.matchResult.obj;
	}
};

HasManyForm.prototype.getValue = function(wf, input) {
	// determine the value, depending on input type
	wf.value = $(input).val();
	wf.valueOption = wf.value;
	if($(input).attr('type') === 'checkbox') {
		if(wf.relation.type === 'habtm') {
			if(!input.checked) wf.value = '';
			//else value = $(input).next('label').text();	// use the text instead of the id
		}else{
			if(!input.checked) wf.value = 0;
		}
	}
	return wf.value;
};


HasManyForm.prototype.populateForm = function(container, schema, data) {
	var self = this;
	$.each(data, function(index, record) {
		self.buildForm(container, schema, index, record);
	});
	
	// extend the form - add new object
	var add = document.createElement('a');
	$(add).attr({id:$(container).attr('id') + 'add', class:'add button'});
	$(add).text('add another ' + $(container).attr('id'));
	$(add).on('click', function() {
		self.buildForm(container, schema);
		var i = self.objectCount[$(container).attr('id')+ '-formIndex'] - 1;
		var idObj = $('#' + $(container).attr('id') + i + 'Id');
		var idWf = self.initInput(idObj);
		var obj = $('#' + $(container).attr('id') + i + idWf.relation.src + 'Id');
		var wf = self.initInput(obj);
		self.processInput(wf, obj);
		// add to watched inputs
		return false;
	});
	$(add).appendTo(container);
}

HasManyForm.prototype.buildForm = function(container, schema, index, record) {
	var userAdded = false;
	if(!index && index !== 0) {
		if(typeof this.objectCount !== 'undefined')
			index = this.objectCount[$(container).attr('id')+ '-formIndex'];
		else index = 0;
		userAdded = true;	// the user has created an additional form
	}
	var baseId = $(container).attr('id');
	var fieldset = document.createElement('fieldset');
	var self = this;
	var newObject = {};
	$(fieldset).attr({id:baseId + index});
	
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
		attributes.id = model + index + self.camelize(field);
		
		if(attributes.type != 'hidden') {
			if(!options.label) options.label = self.humanize(field);
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
		
		$(input).attr(attributes);
		
		if(record) {
			// special rule for link text-field
			if(model == 'ProjectLink' && field == 'title') {
				if(self.record.Project.name == record['title'] ) {
					record['title'] = '';
				}
			}
			$(input).val(record[field]);
		}
		else{
			// adding a new object - use the parent_foreignKey to initialize the changeset generation
			newObject = self.initInput(input);
			if(field === newObject.mainObjectFk) {
				var pos = $.inArray(newObject.relation.src, newObject.path);
				if(pos === -1) {
					// we're at the base level - get the project's id
					$(input).val($('#' + newObject.relation.src + 'Id').val());
				}else{
					// ##ToDo: test this for deeper nested hasmany relations
					$(input).val($('#' + newObject.path.slice(0, pos + 1) + 'Id').val());
				}
			}
		}
		
		$(input).appendTo(div);
		$(div).appendTo(fieldset);
	});
	
	// button to remove the last object - but only if all visible fields are empty!
	var remove = document.createElement('a');
	$(remove).attr({id:baseId + index + 'remove', data:baseId + index, class:'remove button'});
	$(remove).text('remove this ' + baseId);
	$(remove).on('click', function() {
		var target = $('#' + $(this).attr('data'));
		// get this object's id field
		var ex = $('#' + $(this).attr('data') + 'Id');
		var wf = self.initInput(ex);
		self.processInput(wf, ex);
		target.remove();
		return false;
	});
	$(remove).appendTo(fieldset);
	
	// make sure the "add another..." button remains on the container bottom
	if(!userAdded) $(fieldset).appendTo(container);
	else $(fieldset).insertAfter($('#' + baseId + (index - 1)));
	
	// store the next fieldset index for this container
	if(typeof this.objectCount === 'undefined') this.objectCount = {};
	this.objectCount[$(container).attr('id')+ '-formIndex'] = index + 1;
}

HasManyForm.prototype.camelize = function(str) {
	return str.toLowerCase()
    // Replaces any - or _ characters with a space 
    .replace( /[-._]+/g, ' ')
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

HasManyForm.prototype.humanize = function(str) {
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

// Array Remove - By John Resig (MIT Licensed)
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};






