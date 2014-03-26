

var Apvhdrs = Backbone.Collection.extend({
	model: Apvhdr,
	initialize: function(){
		this.on('reset', function(){
			//console.log('reset apvhdrs');
			//console.log(this);
		}, this);
		this.on('add', function(){
			//console.log(this);
		}, this);
		this.on('reset', this.resetVars, this);
	},
	getFieldTotal: function(field){
		return this.reduce(function(memo,value){
			if(value.get('posted')==0){
				//console.log('unposted');
			} else {
				//console.log('posted');
			}
			
			return memo + parseFloat(value.get(field));
			memo = accounting.toFixed(memo,2);
			memo = parseFloat(memo);
		}, 0)
	},
	getPosted: function(){
		return this.where({posted: 1});
	},
	getUnposted: function(){
		x = cars.reduce(function(m, e) {
		    var brand = e.get('brand');
		    if(!m[brand])
		        m[brand] = 0;
		    m[brand] += parseFloat(e.get('amount'));
		    return m;
		}, { });
	},
	resetVars: function(){

	},
	getUnpostedTotal: function(field){
		return this.reduce(function(memo,value){
			return memo + parseFloat(value.get(field));
		}, 0)
	},
	loadAgeData: function(date){


		this._date = date;


		this._age0 = [];
		this._age30 = [];
		this._age60 = [];
		this._age90 = [];
		this._age120 = [];
		this._age150 = [];

		this.each(this.loadData, this);
	},
	loadData: function(apvhdrs){

		
		var now = new Date(this._date.replace(/-/g, ','));
		var date = new Date(apvhdrs.get('due').replace(/-/g, ','));

		var timeDiff = Math.abs(now.getTime() - date.getTime());
		var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)); 
		//console.log(diffDays);

		if(diffDays == 0){
			//console.log('1 mons');
			//console.log(apvhdrs.get('due'));
			this._age0.push(apvhdrs.toJSON());

		} else if(diffDays >= 1 && diffDays <= 30){
			//console.log('1 mons');
			//console.log(apvhdrs.get('due'));
			this._age30.push(apvhdrs.toJSON());

		} else if(diffDays >= 31 && diffDays <= 60){
			//console.log('2 mons');
			//console.log(apvhdrs.get('due'));
			this._age60.push(apvhdrs.toJSON());

		} else if(diffDays >= 61 && diffDays <= 90){
			//console.log('3 mons');
			//console.log(apvhdrs.get('due'));
			this._age90.push(apvhdrs.toJSON());

		} else if(diffDays >= 91 && diffDays <= 120){
			//console.log('4 mons');
			//console.log(apvhdrs.get('due'));
			this._age120.push(apvhdrs.toJSON());

		} else if(diffDays >= 120 ){
			//console.log('5 mons');
			//console.log(apvhdrs.get('due'));
			this._age150.push(apvhdrs.toJSON());

		}
	},
});

var apvhdrs = new Apvhdrs();

