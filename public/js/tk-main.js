var socket = io.connect('https://api-mfitk.herokuapp.com');
//var socket = io.connect('http://localhost:3000');
var loc = $('body').data('location');

$.ajaxSetup({
	beforeSend: function(jqXHR, obj) {
    	$('.notify').css('display', 'block');
  	}
});


var buildEmployeesTimelogs = function(data){
	/*	
	var tmlgs = getEmployeeTimelogs(data.data.id)
	var html = htmlEmployeeTimelogs2(tmlgs);
	*/
	getEmployeeTimelogs(data.data.id).done(function(data){
		htmlEmployeeTimelogs2(data);
	});
};

var getEmployeeTimelogs = function(id){
	//var aData;
	return $.ajax({
        type: 'GET',
        contentType: 'application/json',
		url: '/api/timelog/employee/'+ id,
        dataType: "json",
        //async: false,
        success: function(data, textStatus, jqXHR){
            //aData = data;
			$('.notify').css('display', 'none');
        },
        error: function(jqXHR, textStatus, errorThrown){
			$('.message-group').html('<div class="alert alert-danger">Could not connect to server!</div>');
            //alert(textStatus + ' Failed on posting data');
        }
    });	
	
	//return aData;
}

var htmlEmployeeTimelogs2 = function(data){
	//console.log(data);
	var len = 15;
	var ctr = 0;
	
	$('#TimelogModal .modal-body').html('');
	
	var html = '<div class="row"><div class="col-md-12">';
	html += '<table class="table table-condensed">';
	html += '<thead><tr><th>Day</th><th>Time In</th><th>Time Out</th></tr></thead><tbody class="tk-tb">';
	html += '</tbody></table></div></div>';
	
	$('#TimelogModal .modal-body').prepend(html);
	
	for(i=0; i<data.length; i++) {
		
		var h = '';
		if(data[i].day == 'Sun'){
			h += '<tr class="warning">';
		} else {
			h += '<tr>';
		}
		
		h += '<td><em>'+ data[i].day +'</em> '+ moment(data[i].date).format("MMMM D, YYYY") +'</td>';
		h += '<td>'+ data[i].ti +'</td>';
		h += '<td>'+ data[i].to +'</td>';
		h += '</tr>';
		
		$('.tk-tb').prepend(h);	
	}
}


var htmlEmployeeTimelogs = function(data){
	console.log(data);
	var len = 15;
	var ctr = 0;
	
	var html = '<div class="row"><div class="col-md-6">';
	html += '<table class="table table-condensed">';
	html += '<thead><tr><th>Day</th><th>Time In</th><th>Time Out</th></tr></thead><tbody>';
	for(i=0; i<data.length; i++) {
		if(i==len){
			html += '</tbody></table></div><div class="col-md-6">';
			html += '<table class="table table-condensed">';
			html += '<thead><tr><th>Day</th><th>Time In</th><th>Time Out</th></tr></thead><tbody>';
		} 
		if(data[i].day == 'Sun'){
			html += '<tr class="warning">';
		} else {
			html += '<tr>';
		}
		
		html += '<td>'+ (i+1) +' <em>('+ data[i].day +')</em></td>';
		html += '<td>'+ data[i].ti +'</td>';
		html += '<td>'+ data[i].to +'</td>';
		html += '</tr>';
		//console.log(data[i].date);
	}
	html += '</tbody></table></div></div>';
	
	
	return html;
}




var appendToTkList = function(data){
	
	var d = moment(data.data.date+' '+data.data.time).tz("Asia/Manila").format('hh:mm:ss A');
	var c = moment(data.data.date+' '+data.data.time).tz("Asia/Manila").format('MMM D');
	
		var html = '<tr class="'+ data.data.txncode +'"><td>'+ data.data.empno +'</td>';
			html += '<td>'+ data.data.lastname +', '+ data.data.firstname +'</td>'
			html += '<td><span> '+ c +' </span>&nbsp; '+ d +' </td>'
			html += '<td>'+ data.data.txnname;
			html += '<span id="'+ data.data.timelogid +'" ';
			html += 'class="glyphicon glyphicon-remove-circle pull-right" style="opacity: .5;">';
			html += '</span></td></tr>';
			
		if($('.emp-tk-list tr').length== 20){
			$('.emp-tk-list tr:last-child').empty();
			$('.emp-tk-list tr:last-child').remove();
		}
	
		$('.emp-tk-list tr:first-child').before(html)
			.prev().find('td').each(function(){
				$(this).effect("highlight", {}, 1000);
			});
}

var updateEmpView = function(data){
	
	$('#emp-img').attr('src', 'images/employees/'+ data.data.empno +'.jpg');
	$('#emp-code').text(data.data.empno);
	$('#emp-name').text(data.data.lastname +', '+ data.data.firstname);
	$('#emp-pos').text(data.data.position);

}

var updateEmpViewModal = function(data){
	$('#mdl-emp-img').attr('src', 'images/employees/'+ data.data.code +'.jpg');
	$('#mdl-emp-code').text(data.data.code);
	$('#mdl-emp-name').text(data.data.lastname +', '+ data.data.firstname);
	$('#mdl-emp-pos').text(data.data.position);
	
}


var updateTK = function(data){
	console.log('updateTK');
	console.log(data.data);
	data = data || {};
		
	var html = '<div class="alert alert-'+ data.status +'">'+ data.message +'</div>';	
	$('.message-group').html(html);
	
	if(data && data.code=='200' || data.code=='201'){
		appendToTkList(data);	
		updateEmpView(data);
		//var loc = $('body').data('location');
		
		//console.log(loc);
		//console.log(loc+'-'+data.data.txncode);
		
		//socket.emit(loc+'-'+data.data.txncode, data.data);

		setInterval( function() {
			$('.message-group div').fadeOut(1600);
		},3000);
	} else {
		console.log('error');
	}
}

var updateTKmodal = function(data){
	//console.log(data);
	data = data || {};
		
	var html = '<div class="alert alert-'+ data.status +'">'+ data.message +'</div>';	
	$('.message-group').html(html);
	
	if(data && data.code=='200'){
		//appendToTkList(data);	
		updateEmpViewModal(data);
		$('#TKModal').modal('show');
		setInterval( function() {
			$('.message-group div').fadeOut(1600);
		},3000);
	} else {
		console.log('error');
	}
}

var isInt = function(n) {	
   return n % 1 === 0;
}

var validateEmpno = function(empno){
	if(empno!=undefined && isInt(empno) && empno.length==10){
	//if(empno!=undefined && empno.length==10){
		return true;
	} else {
		console.log('Error on validate');
		
		var html = '<div class="alert alert-info">Unknown RFID: '+ empno +'</div>';	
		$('.message-group').html(html);
		
		setInterval( function() {
			$('.message-group div').fadeOut(1600);
		},3000);
		return false;
	}
}

// send a curl request 
var replicate = function(data){

	//console.log(data);

	var formData = {
		timelogid : data.data.timelogid
	}
	
	return $.ajax({
        type: 'POST',
        contentType: 'application/x-www-form-urlencoded',
		url: '/api/replicate',
        dataType: "json",
        //async: false,
        data: formData,
        beforeSend: function(jqXHR, obj) {
       		//('.notify .inner').html('Replicating...');
	    	//$('.notify').css('display', 'block');
	    	beforeSync();
  		},
        success: function(data, textStatus, jqXHR){
			//$('.notify').css('display', 'none');
			//$('.notify .inner').html('Done...');
			synced(data);
        },
        error: function(jqXHR, textStatus, errorThrown){
			$('.message-group').html('<div class="alert alert-danger">Could not connect to server!</div>');
            //alert(textStatus + ' Failed on posting data');
        }
    });	
}

var beforeSync = function(){
	el = $('.emp-tk-list tr:first-child td:last-child span');
	el.removeClass('glyphicon-remove-circle');
	el.addClass('rotate');
	el.addClass('glyphicon-refresh');
	delete el;
}

var synced = function(data){
	el = $('.emp-tk-list tr:first-child td:last-child span');
	el.removeClass('glyphicon-refresh');
	el.removeClass('rotate');
	if(data.code == 200){
		el.addClass('glyphicon-cloud');
		el.attr('title', 'synced');
	} else {
		el.addClass('glyphicon-remove-circle');
		el.attr('title', 'not synced');
	}
	el.parent().effect("highlight", {}, 1000);
	delete el;
	console.log('synced!');
}

var preparePostTimelogData = function(empno, tc){

	return {
		rfid : empno,
		datetime: moment().tz("Asia/Manila").format('YYYY-MM-DD HH:mm:ss'),
		txncode: tc,
		entrytype: '1',
		//terminalid: 'plant' gethostname
	}

}

var postTimelog = function(data, source){
	//var aData;

	source = source || loc;
	
	var formData = data;
	console.log(formData);
	
	return $.ajax({
        type: 'POST',
        contentType: 'application/x-www-form-urlencoded',
		url: '/api/timelog',
        dataType: "json",
        //async: false,
        data: formData,
        beforeSend: function(jqXHR, obj) {
       		$('.notify .inner').html('Saving...');
	    	$('.notify').css('display', 'block');

  		},
        success: function(data, textStatus, jqXHR){
            //aData = data;
			//updateTK(data);
			$('.notify').css('display', 'none');
			$('.notify .inner').html('Loading...');
        },
        error: function(jqXHR, textStatus, errorThrown){
			$('.message-group').html('<div class="alert alert-danger">Could not connect to server!</div>');
            //alert(textStatus + ' Failed on posting data');
        }
    });	
}


var getEmployee = function(empno){
	//var aData;
	
	return $.ajax({
        type: 'GET',
        contentType: 'application/json',
		url: '/api/employee/rfid/'+ empno,
        dataType: "json",
        //async: false,
        success: function(data, textStatus, jqXHR){
            //aData = data;
			//updateTKmodal(data);
			//console.log('success..');
			$('.notify').css('display', 'none');
        },
        error: function(jqXHR, textStatus, errorThrown){
			$('.message-group').html('<div class="alert alert-danger">Could not connect to server!</div>');
            //alert(textStatus + ' Failed on posting data');
            //console.log('error..');
        }
    });	
	
	//return aData;
}


var keypressInit = function(){
	
	var data = {};
	var empno = '';
	var posted = false;
	
	
	var endCapture = false;
	var arr = [];
	var last_empno = '';
	var empData = {};
	
	
	$('#TKModal').on('hidden.bs.modal', function (e) {
		//console.log('modal hide');
		endCapture = false;
		arr = [];
		last_empno = '';
		empData = {};
	});
	
	
	$(this).bind('keypress', function(e){
		var code = e.which || e.keyCode;
		$('.empno').text('');
		//console.log('keypress');
		//console.log(code);		
		
		if(code == 13) { //Enter keycode

			//$('.empno').text(arr.join('',','));
			empno = arr.join('',',');
			//console.log(empno);
			//console.log('Press Enter');
			if(validateEmpno(empno) && last_empno != empno){
				//console.log('Fetching employee: '+ empno);
				
				//empData = getEmployee(empno);
				//updateTKmodal(empData);

				getEmployee(empno).done(function(data){
					updateTKmodal(data);
					empData = data;
				});

				last_empno = empno;
			} else {
				console.log('Same Empno');	
			}
			
			endCapture = true;
			arr = [];
														 // capslock jenn pc
		} else if((code == 105 || code == 102 || code == 70) && endCapture){ // timein    49="1"
				
			if(validateEmpno(empno)){
				//console.log('Time In: '+ empno);
				//postTimelog(empno,'ti');
/*
				postTimelog(preparePostTimelogData(empno,'ti'), 'local')
				.done(function(data){
					//updateTK(data); update when socket emit
					console.log('emit');
					//socket.emit(loc+'-'+data.data.txncode, data);
					socket.emit('timein', data);
					//$('#TKModal').modal('hide');
				});
*/
				socket.emit('ti', preparePostTimelogData(empno,'ti'));
				$('#TKModal').modal('hide');
			}		
			
			/* on modal hide do this
			endCapture = false;
			arr = [];
			last_empno = '';
			*/                                         // capslock jenn pc
		} else if((code == 111 || code == 106 || code == 74) && endCapture){ // timeout	50="2"	or 48 ="0"
			
			if(validateEmpno(empno)){
				//console.log('Time Out: '+ empno);
				//postTimelog(empno,'to');
				/*
				postTimelog(preparePostTimelogData(empno,'to'), 'local')
				.done(function(data){
					//updateTK(data); update when socket emit
					console.log('emit');
					//socket.emit(loc+'-'+data.data.txncode, data);
					socket.emit('timeout', data);
					//$('#TKModal').modal('hide');
				});
				*/

				socket.emit('to', preparePostTimelogData(empno,'to'));
				$('#TKModal').modal('hide');
			}
			
			/*
			endCapture = false;
			arr = [];
			last_empno = '';
			*/
		} else if((code == 116 || code == 64 || code == 114 || code == 84) && endCapture){ // press view timelogs
			$('#TKModal').modal('hide');
			if(validateEmpno(empno)){
				console.log('Get Employee Timelog: '+ empno);
				//postTimelog(empno,'to');
				buildEmployeesTimelogs(empData);
				$('#TimelogModal').modal('show');
				
			}
			
			endCapture = false;
			arr = [];
			last_empno = '';
		} else {
			
			arr.push(String.fromCharCode(code));
			//console.log(arr.join(''));
			var html = '<div class="alert alert-info">'+ arr.join('') +'</div>';	
			$('.message-group').html(html);
			
				
			
			/*
			if(posted){
				arr = [];
				posted = false;	
			} else {
				
			}
			
			if(code == 105 && endCapture && arr.length == 1){
				console.log('time in')
				posted = true;
				$('#TKModal').modal('hide');
			}
			
			if(code == 111 && endCapture && arr.length == 1){
				console.log('time out')
				posted = true;
				$('#TKModal').modal('hide');
			}
			*/
			
		}	
	});
}

var InitClock = function(){
	
	//var timezone = moment.tz(DateWithTimezone.getTimezone()).format("Z")
	
	
	setInterval( function() {
		$('.ts').html(moment().tz("Asia/Manila").format('hh:mm:ss'));
	},1000);
	
	setInterval( function() {
		$('.am').html(moment().tz("Asia/Manila").format('a'));
	},1000);
	//},64000); // 1 min
	
	setInterval( function() {
		$('.day').html(moment().tz("Asia/Manila").format('dddd'));
		//$('.day').html(moment().tz("Asia/Manila").format('MMM D'));
		$('#date time').html(moment().tz("Asia/Manila").format("MMMM D, YYYY"));
	},1000);
	//},3600000); 
	
	
}


$(document).ready(function(){
		
	//$('.modal').modal('show');
	
	InitClock();
	
	keypressInit();
	
	//$('body').flowtype();

	// paco event
	
	socket.on('paco-push-ti', function(data){
		console.log('socket push-paco-ti');
        console.log(data);
        updateTK(data);
    });

    socket.on('paco-push-to', function(data){
    	console.log('socket push-paco-to');
        console.log(data);
        updateTK(data);
    });
	


    // plant event
    
    socket.on('plant-push-ti', function(data){
    	console.log('socket push-plant-ti');
        console.log(data);
        updateTK(data);
    });

    socket.on('plant-push-to', function(data){
    	console.log('socket push-plant-to');
        console.log(data);
        updateTK(data);
    });


    // both plant and paco
    socket.on('push-timein', function(data){
    	console.log('socket push-timein');
        console.log(data);
        updateTK(data);
        beforeSync();
    });

    socket.on('push-timeout', function(data){
    	console.log('socket push-timeout');
        console.log(data);
        updateTK(data);
        beforeSync();
    });


    socket.on('ti', function(data){

        postTimelog(data)
		.done(function(data){
			updateTK(data);
			beforeSync(); 	
		})
		.fail(function(data) {
   	 		
  		})
  		.always(function(data) {
    		synced(data);
  		});

    });


     socket.on('to', function(data){

        postTimelog(data)
		.done(function(data){
			updateTK(data);
			beforeSync(); 	
		})
		.fail(function(data) {
   	 		
  		})
  		.always(function(data) {
    		synced(data);
  		});

    });

    
    
	

});
