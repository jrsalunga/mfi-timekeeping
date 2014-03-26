

var vPie = Backbone.View.extend({
	el: "#c-pie",
	initialize: function(){

		this.collection.on('reset', this.render, this);

		
	},
	render: function(){
		this.hData = [];
		this.loadData();


		this.$el.find('.c-pie-img')
		.highcharts({
	        chart: {
	            plotBackgroundColor: null,
	            plotBorderWidth: null,
	            plotShadow: false
	        },
	        title: {
	            text: ''
	        },
	        tooltip: {
	    	    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
	        },
	        plotOptions: {
	            pie: {
	                allowPointSelect: true,
	                cursor: 'pointer',
	                dataLabels: {
	                    enabled: true,
	                    color: '#000000',
	                    connectorColor: '#ccc',
	                    format: '<b>{point.code}</b>: {point.percentage:.2f} %'
	                }
	            }
	        },
	        series: [{
	            type: 'pie',
	            name: 'Browser share',
	            data: this.hData
	            /*
	            [
	            
	                {
	                    name: 'Firefox',
	                    y: 45.0
	                },
	                ['IE',       26.8],
	                {
	                    name: 'Chrome',
	                    y: 12.8,
	                    sliced: true,
	                    selected: true
	                },
	                ['Safari',    8.5],
	                ['Opera',     6.2],
	                ['Others',   0.7]
	            ]
	            */
	        }]
	    });


	},
	loadOne: function(apvhdr){
		var pct = (apvhdr.get('totamount')/this.collection.getFieldTotal('totamount'))*100;
		
		this.hData.push(
			{
				name: apvhdr.get('supplier'),
				y: pct,
				code: apvhdr.get('suppliercode')
			}
		);
	},
	loadData: function(){
		this.collection.forEach(this.loadOne, this);                 
	}
});  