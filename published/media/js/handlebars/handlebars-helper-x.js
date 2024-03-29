  // for demo: http://jsbin.com/jeqesisa/7/edit
  // for detailed comments, see my SO answer here http://stackoverflow.com/questions/8853396/logical-operator-in-a-handlebars-js-if-conditional/21915381#21915381
  
  /* a helper to execute an IF statement with any expression
    USAGE:
   -- Yes you NEED to properly escape the string literals, or just alternate single and double quotes 
   -- to access any global function or property you should use window.functionName() instead of just functionName()
   -- this example assumes you passed this context to your handlebars template( {name: 'Sam', age: '20' } ), notice age is a string, just for so I can demo parseInt later
   <p>
     {{#xif " this.name == 'Sam' && this.age === '12' " }}
       BOOM
     {{else}}
       BAMM
     {{/xif}}
   </p>
   */

  Handlebars.registerHelper("xif", function (expression, options) {
    return Handlebars.helpers["x"].apply(this, [expression, options]) ? options.fn(this) : options.inverse(this);
  });

  /* a helper to execute javascript expressions
   USAGE:
   -- Yes you NEED to properly escape the string literals or just alternate single and double quotes 
   -- to access any global function or property you should use window.functionName() instead of just functionName(), notice how I had to use window.parseInt() instead of parseInt()
   -- this example assumes you passed this context to your handlebars template( {name: 'Sam', age: '20' } )
   <p>Url: {{x " \"hi\" + this.name + \", \" + window.location.href + \" <---- this is your href,\" + " your Age is:" + window.parseInt(this.age, 10) "}}</p>
   OUTPUT:
   <p>Url: hi Sam, http://example.com <---- this is your href, your Age is: 20</p>
  */
 
  Handlebars.registerHelper("x", function (expression, options) {
    var fn = function(){}, result;
    try {
      fn = Function.apply(this, ["window", "return " + expression + " ;"]);
    } catch (e) {
      console.warn("{{x " + expression + "}} has invalid javascript", e);
    }

    try {
      result = fn.call(this, window);
    } catch (e) {
      console.warn("{{x " + expression + "}} hit a runtime error", e);
    }
    return result;
  });
  
  
  /* 
    if you want access upper level scope, this one is slightly different
    the expression is the JOIN of all arguments
    usage: say context data looks like this:
    	
    	// data
    	{name: 'Sam', age: '20', address: { city: 'yomomaz' } }
    	
    	// in template
    	// notice how the expression wrap all the string with quotes, and even the variables
    	// as they will become strings by the time they hit the helper
    	// play with it, you will immediately see the errored expressions and figure it out
    	
    	{{#with address}}
        	{{z '"hi " + "' ../this.name '" + " you live with " + "' city '"' }}
       	{{/with}}
  */
  Handlebars.registerHelper("z", function () {
    var options = arguments[arguments.length - 1]
    delete arguments[arguments.length - 1];
    return Handlebars.helpers["x"].apply(this, [Array.prototype.slice.call(arguments, 0).join(''), options]);
  });
  
  Handlebars.registerHelper("zif", function () {
    var options = arguments[arguments.length - 1]
    delete arguments[arguments.length - 1];
    return Handlebars.helpers["x"].apply(this, [Array.prototype.slice.call(arguments, 0).join(''), options]) ? options.fn(this) : options.inverse(this);
  });
  
  
  
/*
 More goodies since you're reading this gist.
*/

// say you have some utility object with helpful functions which you want to use inside of your handlebars templates

util = {

  // a helper to safely access object properties, think ot as a lite xpath accessor
  // usage: 
  // var greeting = util.prop( { a: { b: { c: { d: 'hi'} } } }, 'a.b.c.d');
  // greeting -> 'hi'
  
  // [IMPORTANT] THIS .prop function is REQUIRED if you want to use the handlebars helpers below, 
  // if you decide to move it somewhere else, update the helpers below accordingly
  prop: function() {
    	if (typeof props == 'string') {
			  props = props.split('.');
		  }
		  if (!props || !props.length) {
			  return obj;
		  }
		  if (!obj || !Object.prototype.hasOwnProperty.call(obj, props[0])) {
			  return null;
		  } else {
			  var newObj = obj[props[0]];
			  props.shift();
			  return util.prop(newObj, props);  
      }
  },
  
  // some more helpers .. just examples, none is required
  isNumber: function(n) {
		return !isNaN(parseFloat(n)) && isFinite(n);
	},
	daysInMonth: function (m, y) {
		y = y || (new Date).getFullYear();
		return /8|3|5|10/.test(m) ? 30 : m == 1 ? (!(y % 4) && y % 100) || !(y % 400) ? 29 : 28 : 31;
	},
	uppercaseFirstLetter: function (str) {
		str || (str = '');
		return str.charAt(0).toUpperCase() + str.slice(1);
	},
	hasNumber: function (n) {
		return !isNaN(parseFloat(n));
	},
	truncate: function (str, len) {
		if (typeof str != 'string') return str;
		len = util.isNumber(len) ? len : 20;
		return str.length <= len ? str : str.substr(0, len - 3) + '...';
	}
};

// a helper to execute any util functions and get its return
// usage: {{u 'truncate' this.title 30}} to truncate the title 
Handlebars.registerHelper('u', function() {
    var key = '';
    var args = Array.prototype.slice.call(arguments, 0);
    
	if (args.length) {
        key = args[0];
        // delete the util[functionName] as the first element in the array
        args.shift();
        // delete the options arguments passed by handlebars, which is the last argument
        args.pop();
    }
	if (util.hasOwnProperty(key)) {
	  // notice the reference to util here
		return typeof util[key] == 'function' ?
			util[key].apply(util, args) :
			util[key];
	} else {
		log.error('util.' + key + ' is not a function nor a property');
	}
});

// a helper to execute any util function as an if helper, 
// that util function should have a boolean return if you want to use this properly
// usage: {{uif 'isNumber' this.age}} {{this.age}} {{else}} this.dob {{/uif}}
Handlebars.registerHelper('uif', function() {
	var options = arguments[arguments.length - 1];
	return Handlebars.helpers['u'].apply(this, arguments) ? options.fn(this) : options.inverse(this);
});

// a helper to execute any global function or get global.property
// say you have some globally accessible metadata i.e 
// window.meta = {account: {state: 'MA', foo: function() { .. }, isBar: function() {...} } }
// usage: 
// {{g 'meta.account.state'}} to print the state

// or will execute a function
// {{g 'meta.account.foo'}} to print whatever foo returns
Handlebars.registerHelper('g', function() {
    var path, value;
	if (arguments.length) {
    path = arguments[0];
    delete arguments[0];
        
    // delete the options arguments passed by handlebars
    delete arguments[arguments.length - 1];
  }
  
  // notice the util.prop is required here  
	value = util.prop(window, path);
	if (typeof value != 'undefined' && value !== null) {
		return typeof value == 'function' ?
			value.apply({}, arguments) :
			value;
	} else {
		log.warn('window.' + path + ' is not a function nor a property');
	}
});

// global if 
// usage: 
// {{gif 'meta.account.isBar'}} // to execute isBar() and behave based on its truthy or not return
// or just check if a property is truthy or not
// {{gif 'meta.account.state'}} State is valid ! {{/gif}}
Handlebars.registerHelper('gif', function() {
	var options = arguments[arguments.length - 1];
	return Handlebars.helpers['g'].apply(this, arguments) ? options.fn(this) : options.inverse(this);
});

// just an {{#each}} warpper to iterate over a global array, 
// usage say you have: window.meta = { data: { countries: [ {name: 'US', code: 1}, {name: 'UK', code: '44'} ... ] } }
// {{geach 'meta.data.countries'}} {{this.code}} {{/geach}}

Handlebars.registerHelper('geach', function(path, options) {
	var value = util.prop(window, arguments[0]);
	if (!_.isArray(value))
		value = [];
	return Handlebars.helpers['each'].apply(this, [value, options]);
});

//GET GLOBAL VARIABLES
Handlebars.registerHelper("getGlobalVar", function(variable) {     
  var value = eval(variable);  
  value = value.toLocaleString();    
  
  return value;  
});

Handlebars.registerHelper("formatNumber", function(x) {     
  x = parseFloat(x).toLocaleString();    
  return x;  
});

Handlebars.registerHelper('sumVariables', function() {
  var sum = 0, v;
  for (var i=0; i<arguments.length; i++) {
	v = parseFloat(arguments[i]);
	if (!isNaN(v)) sum += v;
  }
  //sum = parseInt(sum).toLocaleString();
  return sum;
});

Handlebars.registerHelper('multiplyVariables', function(x, y) {
  var ans = x * y;    
  ans = parseFloat(ans);  
  return (!isNaN(ans) && ans !== Infinity)?ans:0;
});

Handlebars.registerHelper('divideVariables', function(x, y) {
  var ans = x/y;    
  //ans = parseFloat(ans);       
  return (!isNaN(ans) && ans !== Infinity)?ans:0;
  
});

Handlebars.registerHelper('subtractVariables', function(x, y) {
  var ans = x-y;    
  ans = parseFloat(ans);  
  return (!isNaN(ans) && ans !== Infinity)?ans:0;
});

Handlebars.registerHelper('roundNumbers', function(value, digits) {  
  var ans; 
  ans = ((Math.round((value*Math.pow(10,digits)).toFixed(digits-1)) / Math.pow(10,digits)).toFixed(digits)) * 1; 
  //(Math.round((value*Math.pow(10,digits)).toFixed(digits-1))/Math.pow(10,digits)).toFixed(digits);  
  return (!isNaN(ans) && ans !== Infinity)?ans:0;
}); 


Handlebars.registerHelper('strPad', function(input, pad_length, pad_string, pad_type) {  
	//  discuss at: http://phpjs.org/functions/str_pad/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Michael White (http://getsprink.com)
  //    input by: Marco van Oort
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  //   example 1: str_pad('Kevin van Zonneveld', 30, '-=', 'STR_PAD_LEFT');
  //   returns 1: '-=-=-=-=-=-Kevin van Zonneveld'
  //   example 2: str_pad('Kevin van Zonneveld', 30, '-', 'STR_PAD_BOTH');
  //   returns 2: '------Kevin van Zonneveld-----'

  var half = '',
    pad_to_go;

  var str_pad_repeater = function(s, len) {
    var collect = '',
      i;

    while (collect.length < len) {
      collect += s;
    }
    collect = collect.substr(0, len);

    return collect;
  };

  input += '';
  pad_string = pad_string !== undefined ? pad_string : ' ';

  if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
    pad_type = 'STR_PAD_RIGHT';
  }
  if ((pad_to_go = pad_length - input.length) > 0) {
    if (pad_type === 'STR_PAD_LEFT') {
      input = str_pad_repeater(pad_string, pad_to_go) + input;
    } else if (pad_type === 'STR_PAD_RIGHT') {
      input = input + str_pad_repeater(pad_string, pad_to_go);
    } else if (pad_type === 'STR_PAD_BOTH') {
      half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
      input = half + input + half;
      input = input.substr(0, pad_length);
    }
  }

  return input;

	
});

Handlebars.registerHelper('ucwords', function(str) {
  	var result_str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
		return letter.toUpperCase();
	}); 

  return new Handlebars.SafeString(result_str);
});

Handlebars.registerHelper('formatDate', function(date_str, format_date) {    
	if(format_date === undefined)format_date = "YYYY-MM-DD"; 
	var sdate = 1000*date_str;  
  	var new_date = "";
	new_date = moment(sdate).format(format_date);  
	//new_date = sdate.format(format_date);  
	
  return new_date; 
});

Handlebars.registerHelper('formatMoney', function(n, c, d, t) {    
	//var formatted = number.formatMoney(2, '.', ','); //in common.js 
	
	c = isNaN(c = Math.abs(c)) ? 2 : c, 
	d = d == undefined ? "." : d, 
	t = t == undefined ? "," : t, 
	s = n < 0 ? "-" : "", 
	i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
	j = (j = i.length) > 3 ? j % 3 : 0; 
	
   formatted = s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	
	return formatted; 
}); 


Handlebars.registerHelper('formatNumberComma', function(x) {    
	//var formatted = number.formatMoney(2, '.', ','); //in common.js 
	x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}); 

