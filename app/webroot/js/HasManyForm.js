

function HasManyForm(formSelector, changesetSelector, exclude, record, schema) {
	this.form = $(formSelector);
	this.inputs;
	this.exclude = exclude;
	this.changeset = {};
	this.changesetSelector = changesetSelector;
	if($(changesetSelector).val()) this.changeset = JSON.parse($(changesetSelector).val());
	this.record = record;
	this.schema = schema;
	
}


HasManyForm.prototype.watchForm = function() {
	var self = this;
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
	$(this.changesetSelector).val(JSON.stringify(this.changeset, null, 4));
};

HasManyForm.prototype.initInput = function(input) {
	var wf = {};	// the watchForm variables object - to be passed around...
	console.log($(input));
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
	var realPath = []
	$.each(wf.path, function(i, frag) {
		realPath.push(frag);
		if(wf.path.length == i + 1) {
			// delete the matching results from changeset
			if(wf.matchResult.match) {
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
				// apply common resultObjects
				if(!wf.matchResult.match) {
					$.each(wf.resultObject, function(key, value) {
						if(typeof lastBranch[frag] === 'undefined') lastBranch[frag] = {};
						lastBranch[frag][key] = value;
					});
				}
				// continue to stoplevel 2 and check match - eventually remove it there again :o)
				branches[i] = lastBranch;
				lastBranch = lastBranch[frag];
			}
		}else{
			if(wf.matchResult.match) {
				if(typeof lastBranch[frag] === 'undefined') return false;
			}else{
				if(typeof lastBranch[frag] === 'undefined') lastBranch[frag] = {};
			}
			branches[i] = lastBranch;
			lastBranch = lastBranch[frag];
		}
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
				
				if(Object.keys(branches[reversekey][frag]).length === 1) {
					var keys = Object.keys(branches[reversekey][frag])
					if(branches[reversekey][frag][keys[0]].constructor === Array
					&& branches[reversekey][frag][keys[0]].length === 0)
						delete branches[reversekey][frag];
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
					tree = set[frag];
					if(tree[path[i+1]] === wf.valueOption) tagExists = true;
					if((!tree[path[i+1]] && !wf.value) || (wf.value == tree[path[i+1]])) {
						result.match = true;
						result.obj = tree;
						result.path.push(n);
						return false;	// the current tree branch matches - break tree loop
					}
					// tag exists, but current choice does not
					if(tree[path[i+1]] === wf.valueOption && wf.value !== wf.valueOption) {
						// the empty record (id only) bedoelt: delete!
						result.obj['id'] = tree.id;
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
		else{
			// primary data tree, skip on relation. OR evaluate on no relation(!)
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
			tree = tree[frag];
		}
		result.path.push(frag);
	});
	// removing a created tag that didn't exist before
	if(wf.relation.type == 'habtm' && !wf.value && !tagExists) {
		result.match = true;
		result.obj['id'] = '';
		result.obj[wf.mainObjectFk] = wf.mainObjectId;
		result.obj[path[path.length - 1]] = wf.valueOption;
	}
	return result;
}

HasManyForm.prototype.getRecord = function(wf) {
	// get the object, the path to the object and id of that object, where these changes go to
	wf.resultObject = {};
	if(!wf.relation.type) {
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
		}else{
			// ##todo: other relation types
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
	var i = 0;
	var self = this;
	$.each(data, function(index, record) {
		self.buildForm(container, schema, index, record);
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

HasManyForm.prototype.buildForm = function(container, schema, index, record) {
	var baseId = $(container).attr('id');
	var fieldset = document.createElement('fieldset');
	var self = this;
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
		if(!attributes.id) {
			attributes.id = model + index + self.camelize(field);
		}
		
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
	$(remove).attr({id:baseId + index + 'remove', data:baseId + index, class:'remove button'});
	$(remove).text('remove this ' + baseId);
	$(remove).on('click', function() {
		var target = $('#' + $(this).attr('data'));
		// get this object's id field
		var ex = $('#' + $(this).attr('data') + 'Id');
		var wf = self.initInput(ex);
		self.processInput(wf, ex);
		// cool. except for we don't get any changeset :-(
		target.remove();
		return false;
	});
	$(remove).appendTo(fieldset);
	
	$(fieldset).appendTo(container);
}

HasManyForm.prototype.camelize = function(str) {
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






