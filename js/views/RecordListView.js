/**
* RecordView, view of a list of records
*/
var RecordListView = Backbone.View.extend({
	el: $('body'),

	initialize: function(){
		_.bindAll(this, 'render', 'addItem', 'appendItem');

		this.collection = new RecordList();
		this.collection.bind('add', this.appendItem);

		this.collection.fetch();

		this.render();
	},

	fetch: function(){
		return this.collection.fetch();
	},

	render: function(){
		var self = this;
		_(this.collection.models).each(function(item){
			self.appendItem(item);
		}, this);
	},
	addItem: function(title){
		var record = new Record();
		record.set({
			title: title,
		});
		this.collection.add(record);
	},

	appendItem: function(record){
		var recordView = new RecordView({
			model: record
		});
		$(this.el).append(recordView.render().el);
	}
});