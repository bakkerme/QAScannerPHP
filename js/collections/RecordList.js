var RecordList = Backbone.Collection.extend({
	model: Record,
	url: 'records.json',

	parse: function(response){
		return response.data;
	}
});