



var ModalData = Backbone.Model.extend({

	defaults: {
		code : '',
		lastname : '',
		firstname : '',
		middlename : '',
		position : '',
		rfid : '',
		id : ''
	}
	
});
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


var Employee = Backbone.Model.extend({
	initialize: function(){
		this.changePaytype();
	},
		changePaytype: function(){
			// if office
			/*
			if(this.get('paytype') == 2){
				this.set('status', 'Extra');
			} else if(this.get('paytype') == 1){
				this.set('status', 'Regular');
			} else {
	
			}
			*/
			
			// if plant
			
			if(this.get('paytype') == 2){
				this.set('status', 'Extra');
			} else if(this.get('paytype') == 1){
				this.set('status', 'Regular');
			} else {
	
			}
			
		}
});
var employee = new Employee();