var RecordView = Backbone.View.extend({
	tagName: 'li',
	initialize: function(){
		_.bindAll(this, 'render');
	},
	render: function(){
		$(this.el).append('<p>' + this.model.get('title') + '</p>');
		return this;//Chainable calls
	},

	unrender: function(){
		$(this.el).remove();
	},

	remove: function(){
		this.model.destroy();
	}
});