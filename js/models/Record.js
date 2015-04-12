var Record = Backbone.Model.extend({

	defaults: function() {
		return {
			title: "New Record",
			lineNumber: 0,
			code: "<div></div>"
		};
	},

	url: function() {
		// return this.id ? '/records/' + this.id : '/records'; 
		return 'records.json'
	} 

});