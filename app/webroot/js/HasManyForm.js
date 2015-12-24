

function HasManyForm(formSelector, changesetSelector, exclude, record, schema) {
	this.form = $(formSelector);
	this.inputs = this.form.find(':input').not('[type=hidden], ' + exclude + ', ' + changesetSelector);
	this.changeset = {};
	if($(changesetSelector).val()) this.changeset = JSON.parse($(changesetSelector).val());
	this.record = record;
	this.schema = schema;
	this.wf = {};	// the watchForm variables object - to be passed around...
}


HasManyForm.prototype.watchForm = function() {
	$.each(this.inputs, function(index, input) {
		this.wf.path = $(input).attr('datapath').split('.');
		this.wf.relation = {src: path[0], type: false, target: false, cross: false};
		
		if($(input).attr('datarelation')) {
			var split = $(input).attr('datarelation').split('.');
			if(split[0]) this.wf.relation['src'] = split[0];
			if(split[1]) this.wf.relation['type'] = split[1];	// habtm, hasmany, hasone(?), belongsto
			if(split[2]) this.wf.relation['target'] = split[2];
			if(split[3] && this.wf.relation.type === 'habtm') {
				this.wf.relation['cross'] = split[3];
			}else{
				// having an Inflector::pluralize() would be nice...
				this.wf.relation['cross'] = this.wf.relation.src + 's' + this.wf.relation.target;
			}
		}
		this.wf.mainObjectFk = this.wf.relation.src.toLowerCase() + '_id';
		
		// the actual on-change method!
		$(input).change(function() {
			this.getObject();
			this.getValue(input);
			
			
		});
	});
};


HasManyForm.prototype.getObject = function() {
	// get the object, the path to the object and id of that object, where these changes go to
	this.wf.resultObject = {};
	this.wf.idPath = this.wf.path.slice(0);
	if(!this.wf.relation.type) {
		// straight data - no relation
		this.wf.idPath[this.wf.idPath.length - 1] = 'id';	// doesn't apply for tagging!
		this.wf.mainObjectId = $('[datapath="'+this.wf.idPath.join('.')+'"]').val();
		// value should be fetched after change (take care where to invoke this function!)
		this.wf.resultObject['id'] = this.wf.mainObjectId;
		this.wf.resultObject[this.wf.path[this.wf.path.length - 1]] = this.wf.value;
		
	}else{
		// all relation types
		if(this.wf.relation.type === 'habtm') {
			this.wf.mainObjectId = $('[datapath="'+this.wf.relation.src+'.id'+'"]').val();
			this.parseTree();
		}else{
			// ##todo
		}
	}
};

HasManyForm.prototype.parseTree = function() {
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

HasManyForm.prototype.getValue = function(input) {
	// determine the value, depending on input type
	this.wf.value = $(input).val();
	if($(input).attr('type') === 'checkbox') {
		if(this.wf.relation.type === 'habtm') {
			if(!input.checked) this.wf.value = '';
			//else value = $(input).next('label').text();	// use the text instead of the id
		}else{
			if(!input.checked) this.wf.value = 0;
		}
	}
	return this.wf.value;
};