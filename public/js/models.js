



var ModalData = Backbone.Model.extend({});
var modalData = new  ModalData();
var ModalSettings = Backbone.Model.extend({});
var modalSettings = new ModalSettings();


var Timelog = Backbone.Model.extend({
	initialize: function(){
		this.on('change', this.setName, this);
	},
	defaults:{
		employee: '',
		date: '',
		time: '',
		txncode: '',
	},
	setName: function(){
		console.log(this.model.toJSON());
	}
});
var timelog = new Timelog();