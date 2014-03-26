
var appendToTkList = function(data){
	
		var html = '<tr><td>'+ data.data.empno +'</td>';
			html += '<td>'+ data.data.lastname +', '+ data.data.firstname +'</td>'
			html += '<td>'+ data.data.time +'</td>'
			html += '<td>'+ data.data.txncode +'</td></tr>';
			
		if($('.emp-tk-list tr').length== 15){
			$('.emp-tk-list tr:last-child').empty();
			$('.emp-tk-list tr:last-child').remove();
		}
	
		$('.emp-tk-list tr:first-child').before(html)
			.prev().find('td').each(function(){
				$(this).effect("highlight", {}, 3000);
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
	console.log(data);
	data = data || {};
		
	var html = '<div class="alert alert-'+ data.status +'">'+ data.message +'</div>';	
	$('.message-group').html(html);
	
	if(data && data.code=='200'){
		appendToTkList(data);	
		updateEmpView(data);
		
		setInterval( function() {
			$('.message-group div').fadeOut(1600);
		},3000);
	} else {
		console.log('error');
	}
}

var updateTKmodal = function(data){
	console.log(data);
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

var postTimelog = function(empno, tc){
	var aData;
	var formData = {
		rfid : empno,
		datetime: moment().format('YYYY-MM-DD hh:mm:ss'),
		txncode: tc,
		entrytype: '1',
		terminalid: 'plant' 
	}
	
	console.log(formData);
	
	$.ajax({
        type: 'POST',
        contentType: 'application/x-www-form-urlencoded',
		url: '/api/timelog',
        dataType: "json",
        async: false,
        data: formData,
        success: function(data, textStatus, jqXHR){
            aData = data;
			updateTK(data);
        },
        error: function(jqXHR, textStatus, errorThrown){
			$('.message-group').html('<div class="alert alert-danger">Could not connect to server!</div>');
            //alert(textStatus + ' Failed on posting data');
        }
    });	
}


var getEmployee = function(empno){
	var aData;
	
	$.ajax({
        type: 'GET',
        contentType: 'application/json',
		url: '/api/employee/rfid/'+ empno,
        dataType: "json",
        async: false,
       // data: formData,
        success: function(data, textStatus, jqXHR){
            aData = data;
			updateTKmodal(data);
        },
        error: function(jqXHR, textStatus, errorThrown){
			$('.message-group').html('<div class="alert alert-danger">Could not connect to server!</div>');
            //alert(textStatus + ' Failed on posting data');
        }
    });	
}

var keypressInit = function(){
	
	var data = {};
	var arr = [];
	var endCapture = false;
	var posted = false;
	var empno = '';
	var last_empno = '';
	
	
	$('#TKModal').on('hidden.bs.modal', function (e) {
		console.log('modal hide');
		endCapture = false;
		arr = [];
		last_empno = '';
	});
	
	
	$(this).bind('keypress', function(e){
		var code = e.which || e.keyCode;
		$('.empno').text('');
		console.log('keypress');
		//console.log(code);		
		
		if(code == 13) { //Enter keycode

			//$('.empno').text(arr.join('',','));
			empno = arr.join('',',');
			console.log(empno);
			console.log('Press Enter');
			if(validateEmpno(empno) && last_empno != empno){
				console.log('Fetching employee: '+ empno);
				
				getEmployee(empno);
				last_empno = empno;
			} else {
				console.log('Same Empno')	
			}
			
			endCapture = true;
			arr = [];
			
		} else if((code == 105 || code == 102) && endCapture){ // timein    49="1"
				
			if(validateEmpno(empno)){
				console.log('Time In: '+ empno);
				postTimelog(empno,'ti');
			}		
			$('#TKModal').modal('hide');
			/* on modal hide do this
			endCapture = false;
			arr = [];
			last_empno = '';
			*/
		} else if((code == 111 || code == 106) && endCapture){ // timeout	50="2"	or 48 ="0"
			
			if(validateEmpno(empno)){
				console.log('Time Out: '+ empno);
				postTimelog(empno,'to');
			}
			$('#TKModal').modal('hide');
			endCapture = false;
			arr = [];
			last_empno = '';
		} else {
		
			arr.push(String.fromCharCode(code));
			
				
			
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
	
	setInterval( function() {
		$('.ts').html(moment().format('hh:mm:ss'));
	},1000);
	
	setInterval( function() {
		$('.am').html(moment().format('a'));
	},1000);
	//},64000); // 1 min
	
	setInterval( function() {
		$('.day').html(moment().format('ddd'));
		$('#date time').html(moment().format("MMMM D, YYYY"));
	//},1000);
	},3600000); // 1 hr
	
	
}


$(document).ready(function(){
		
	
	InitClock();
	
	keypressInit();
	
	//$('body').flowtype();
	

});
