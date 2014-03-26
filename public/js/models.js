

var Apvhdr = Backbone.Model.extend({
		urlRoot: "../api/t/apvhdr",
		initialize: function(){
			// on init set id for apvdtls

			
		},
		defaults: {
			refno: '',
			date: '',
			supplierid: '',
			supprefno: '',
			porefno: '',
			terms: '',
			totamount: '',
			balance: '',
			notes: '',
			posted: '',
			cancelled: '',
			printctr: '',
			totline: ''
		}, 
		validation: {
			apvhdr: {
		    	required: true,
				msg: 'Please enter a value'
		   	},
		   	itemid: {
		    	required: true,
				msg: 'Please enter a account number'
		   	}	
		},
		blank: function(){
			this.clear();
			return this.defaults;
			
		},
		post: function(){
			var aData;

			$.ajax({
		        type: 'POST',
		        contentType: 'application/json',
				url: '../api/txn/post/apvhdr/' + this.get('id') ,
		        dataType: "json",
		        async: false,
		        //data: formData,
		        success: function(data, textStatus, jqXHR){
					aData = data; 			
		        },
		        error: function(jqXHR, textStatus, errorThrown){
		            alert(textStatus + 'Failed on creating '+ table +' data');
		        }
		    });

		    return aData;

			
		}
	});
	//var apvhdr = new Apvhdr();




