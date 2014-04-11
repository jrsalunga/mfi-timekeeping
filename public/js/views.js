

var TimelogView = Backbone.View.extend({
	tagName: 'tr',
	initialize: function(){
		this.model.on('change', this.render, this);
        this.template = _.template('<td><%- code %>'
        						+'<% if (entrytype == "2") { %><div class="tb-data-action">'
        						+'<a class="row-edit" href="#"><span class="glyphicon glyphicon-pencil"></span></a> '
								+'<a class="row-delete" href="#"><span class="glyphicon glyphicon-trash"></span></a>'							
								+'</div><% } %></td>'
        						+'<td><%- lastname %>, <%- firstname %></td>'
        						+'<td><%- date %></td>'
        						+'<td><%- time %></td>'
        						+'<td><%- type %></td>'
        						+'<td><%- entrytype %></td>');
	},
	events: {
        'click .row-edit': 'editModel',
        'click .row-delete': 'deleteModel'
    },
	render: function(){
        this.$el.html(this.template(this.model.toJSON()));
   
        return this;
    },
    editModel: function(e){
    	e.preventDefault();
    	modalSettings.set({ mode:'edit', title:'Edit Record'});
    	modalData.clear({silent:true});
    	modalData.set(this.model.toJSON());
    	$('.modal').modal('show');
    },
    deleteModel: function(e){
    	e.preventDefault();
    	modalSettings.set({mode:'delete', title: 'Delete Record'});
    	modalData.clear({silent:true});
    	modalData.set(this.model.toJSON());
    	$('.modal').modal('show');
    }
});




var TimelogsView = Backbone.View.extend({
	el: '.tb-timelog tbody',
	initialize: function(){

	},
	render:function () {
		this.addAll();
        return this;
	},
	addOne: function(timelog){
		timelog.set({employee: timelog.get('lastname')+', '+timelog.get('firstname')}, {silent: true});
		var timelogView = new TimelogView({model: timelog});
		this.$el.append(timelogView.render().el);
	},
	addAll: function(){
		this.collection.forEach(this.addOne, this);
	}
});







var ModalView = Backbone.View.extend({
	el: '.modal-dialog',
	initialize: function(){
		this.settings = this.options.settings;
		this.model.on('change', this.populate, this);
		this.settings.on('change:mode', this.checkMode, this);
	},
	events: {
		'change input': 'checkValidity',
		'change select': 'checkValidity',
	},
	populate: function(){
		var attrs = { }, k;
		for(k in this.model.attributes) {
			//console.log(k);

			this.$el.find("#"+k).val(this.model.get(k));
		}		

	},
	checkMode: function(){
		//console.log(this.settings.get('mode'));
		var mode = this.settings.get('mode');
		this.changeFormMethod(mode);
		console.log(mode);
        if(mode==='delete' || mode==='posting'){
 
        	this.modelInputsDisable();
        } else {
        	this.modelInputsEnable();
        	
        }

	    this.modalChangeTitle(); 
	},
	changeFormMethod: function(mode){
		var m;
		//console.log(mode);
		if(mode == 'edit'){
			m = 'PUT';
		} else if(mode == 'delete'){
			m = 'DELETE';
		} else {
			m = 'POST';
		}
		
		//this.$el.find("#"+k).val(this.model.get(k));
		this.$el.find('.table-model input[name="_method"]').val(m);
	},
	modalChangeTitle: function(){
    	$('.modal-title').text(this.settings.get('title')); 	
    	
    	var btn, mode = this.settings.get('mode');

        if(mode=='delete'){
        	btn = '<p style="display: inline; float: left; margin: 10px 0; color: #3B5998;">Are you sure you want to delete this record?</p>';
        	btn += '<button type="submit" id="modal-btn-delete-yes" class="btn btn-primary model-btn-yes">Yes</button>';
          	btn += '<button type="button" id="modal-btn-delete-no" class="btn btn-default model-btn-no" data-dismiss="modal" >No</button>';
        
          	//this.$('.modal-footer').html(btn);
        } else if(mode=='posting'){

            btn = '<p style="display: inline; float: left; text-align: left; margin: 0; color: #3B5998;">'
            btn += 'You are about to POST this transaction. ';
            btn += '<br>Posted transactions may not be unposted; use adjusting transactions to reverse.';
            btn += '<br>Are you really sure?';
            btn += '</p>';
        	btn += '<button type="button" id="modal-btn-post-yes" class="btn btn-primary model-btn-yes" data-dismiss="modal">Yes</button>';
          	btn += '<button type="button" id="modal-btn-post-no" class="btn btn-default model-btn-no" data-dismiss="modal" >No</button>';
        } else {
          	btn = '<button type="submit" id="modal-btn-save" class="btn btn-primary model-btn-save" disabled>Save</button>';
          	btn += '<button type="button" id="modal-btn-cancel" class="btn btn-default model-btn-cancel" data-dismiss="modal">Cancel</button>';
        	
        	//this.$('.modal-footer').html(btn);
        }
        this.$('.modal-footer').html(btn);  
    },
    checkValidity: function(e){

		var that = this;

		var req = this.$('.table-model input[required]', '.table-model');

		if(req){
			req.each(function(){
				if($(this).val()==''){
					that.btnSaveDisable();
				} else {
					that.btnSaveEnable();
				}
			});
		} else {
			that.btnSaveEnable();
		}	
	},
	 btnSaveEnable: function(){
    	$(".model-btn-save").prop('disabled', false);	
		$(".model-btn-save-blank").prop('disabled', false);
    },
    btnSaveDisable: function(){
    	$(".model-btn-save").attr('disabled', true);	
		$(".model-btn-save-blank").attr('disabled', true);
    },
    dataActionHide: function(){
	    $('.modal-tb-detail .tb-data-action').hide();
	    $('.modal-table-detail').hide();
	},
	dataActionShow: function(){
	    $('.modal-tb-detail .tb-data-action').show();
	    $('.modal-table-detail').show();
	},
	modelInputsDisable: function(){
		var attrs = { }, k;
		console.log(this.model.attributes);
		for(k in this.model.attributes) { 
			console.log(k);
	         this.$el.find(".table-model #"+k).prop( "disabled", true );
	    }
	    this.$el.find(".table-model .toggle").prop( "disabled", true );
	},
	modelInputsEnable: function(){
		var attrs = { }, k;
		for(k in this.model.attributes) {
	         this.$el.find(".table-model #"+k).prop("disabled", false );
	    }
	    this.$el.find(".table-model .toggle").prop( "disabled", false );
	},
});