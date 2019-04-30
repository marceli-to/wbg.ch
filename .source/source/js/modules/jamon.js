var JamonUi = (function() {

	var config = {
		message:  "Made with ♥ by Jam'on digital",
		txtColor: "#f24444",
	};	
	
	var _showMessage = function() {
		console.log("%c%s", "color: " + config.txtColor + "; font-size: 11px; padding: 5px 2px; width: 100%;", ""+ config.message +"");
	};

	return {
		show: _showMessage,
	};
})();

// Initialize
JamonUi.show();

