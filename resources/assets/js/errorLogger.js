let errorsCount = 0;

function errorLog(message, source, lineNo, colno, trace){
	if (errorsCount < 3) {
		errorsCount++;
		axios.post('/jserror', {
			page: window.location.href,
			userAgent: navigator.userAgent,
			message,
			source,
			lineNo,
			colno,
			trace: trace.stack
		});
	}
}

window.onerror = errorLog;
