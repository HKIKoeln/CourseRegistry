

function populateForm(container, schema, data) {
	var i = 0;
	$.each(data, function(index, record) {
		buildForm(container, schema, index, record);
		i = index + 1;
		window[container.id + '-formIndex'] = i;
	});
	
	var add = document.createElement('button');
	$(add).attr({id:$(container).attr('id') + 'add'});
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
		attributes = options.attributes;
		
		attributes.name = 'data[' + model + '][][' + field + ']';
		// input id: modelname + ucfirst(field)
		if(!attributes.id) {
			attributes.id = model + index + camelize(field);
		}
		div = document.createElement('div');
		
		// field types
		if(attributes.type == 'hidden') {
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
		
		if(attributes.type != 'hidden') {
			if(!options.label) options.label = camelize(field);
			label = document.createElement('label');
			$(label).attr({for:attributes.id});
			$(label).text(options.label);
			$(div).append(label);
		}
		
		$(input).attr(attributes).appendTo(div);
		$(div).appendTo(fieldset);
	});
	
	var remove = document.createElement('button');
	$(remove).attr({id:baseId + index + 'remove', data:baseId + '-' + index});
	$(remove).text('remove this ' + baseId);
	$(remove).on('click', function() {
		$('#' + $(this).attr('data')).remove();
		// possibly use detach to preserve the existing record ID? The other data may be dumped though...
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