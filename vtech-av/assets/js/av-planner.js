/* VTECH AV Planner (v5.65.0). Runs entirely in the browser; nothing is sent anywhere. */
(function () {
	'use strict';
	function num(id) { var el = document.getElementById(id); var v = el ? parseFloat(el.value) : NaN; return isFinite(v) ? v : NaN; }
	function out(id, html) { var el = document.getElementById(id); if (el) { el.innerHTML = html; } }
	function f(n, d) { return Number(n).toFixed(d === undefined ? 1 : d).replace(/\.0+$/, ''); }
	var PITCHES = [1.2, 1.5, 1.9, 2.5, 2.9, 3.9, 4.8, 6, 8, 10];
	var MODULE = 0.5; // metres, common cabinet size

	function led(e) {
		if (e) { e.preventDefault(); }
		var near = num('ap-led-near'), far = num('ap-led-far'), wall = num('ap-led-wall');
		var outdoor = (document.getElementById('ap-led-env') || {}).value === 'outdoor';
		if (!(near > 0) || !(far > 0) || far < near) { out('ap-led-out', '<p class="ap-err">Enter the closest and farthest viewer distances in metres (farthest must be larger).</p>'); return; }
		var pitch = PITCHES[0];
		for (var i = 0; i < PITCHES.length; i++) { if (PITCHES[i] <= near) { pitch = PITCHES[i]; } }
		if (outdoor && pitch < 3.9) { pitch = 3.9; }
		var h = far / 6, w = h * 16 / 9;
		var capped = false;
		if (wall > 0 && w > wall) { w = wall; h = w * 9 / 16; capped = true; }
		var cw = Math.max(1, Math.ceil(w / MODULE)), ch = Math.max(1, Math.ceil(h / MODULE));
		var W = cw * MODULE, H = ch * MODULE;
		var rx = Math.round(W * 1000 / pitch), ry = Math.round(H * 1000 / pitch);
		out('ap-led-out',
			'<table class="rp-table"><tbody>' +
			'<tr><th scope="row">Suggested pixel pitch</th><td>P' + f(pitch) + ' (' + (outdoor ? 'outdoor-rated' : 'indoor') + ')</td></tr>' +
			'<tr><th scope="row">Suggested screen size</th><td>' + f(W) + ' m wide x ' + f(H) + ' m high (' + f(W * H) + ' m&sup2;)</td></tr>' +
			'<tr><th scope="row">Cabinets (0.5 m)</th><td>' + cw + ' across x ' + ch + ' high = ' + (cw * ch) + '</td></tr>' +
			'<tr><th scope="row">Approximate resolution</th><td>' + rx + ' x ' + ry + ' pixels</td></tr>' +
			'</tbody></table>' +
			(capped ? '<p class="rp-note">Limited by the wall width you entered, so people at the back will see a smaller image than ideal.</p>' : '') +
			'<p class="rp-note">Guideline only: pitch from the closest viewer (about 1 mm per metre) and height of about one sixth of the farthest viewing distance. We confirm brightness, structure and power at a free site survey.</p>');
	}

	function room(e) {
		if (e) { e.preventDefault(); }
		var seats = num('ap-room-seats'), far = num('ap-room-far');
		if (!(seats > 0) || !(far > 0)) { out('ap-room-out', '<p class="ap-err">Enter the number of seats and the farthest seat distance in metres.</p>'); return; }
		var h = far / 6, diag = (h / 0.49) / 0.0254;
		var sizes = [55, 65, 75, 86, 98], disp = '';
		for (var i = 0; i < sizes.length; i++) { if (sizes[i] >= diag) { disp = sizes[i] + '-inch display'; break; } }
		if (!disp) { disp = 'fine-pitch LED wall (P1.2 to P1.9) or projection'; }
		var tier, cam, mic;
		if (seats <= 6) { tier = 'Huddle room'; cam = 'All-in-one video bar'; mic = 'Built into the video bar'; }
		else if (seats <= 12) { tier = 'Meeting room'; cam = 'Conference camera with wide view'; mic = 'Table or ceiling microphones with room speakers'; }
		else if (seats <= 20) { tier = 'Boardroom'; cam = 'PTZ or auto-framing cameras'; mic = 'Ceiling microphone arrays with audio processing (DSP)'; }
		else { tier = 'Council chamber or training hall'; cam = 'Several cameras with speaker tracking'; mic = 'Delegate microphone system with chair unit, plus PA'; }
		var two = seats > 12 ? ' (two displays recommended: one for people, one for content)' : '';
		out('ap-room-out',
			'<table class="rp-table"><tbody>' +
			'<tr><th scope="row">Room type</th><td>' + tier + '</td></tr>' +
			'<tr><th scope="row">Display</th><td>At least a ' + disp + two + '</td></tr>' +
			'<tr><th scope="row">Camera</th><td>' + cam + '</td></tr>' +
			'<tr><th scope="row">Microphones</th><td>' + mic + '</td></tr>' +
			'<tr><th scope="row">Control</th><td>' + (seats > 6 ? 'One-touch control panel' : 'Simple one-touch join') + '</td></tr>' +
			'</tbody></table>' +
			'<p class="rp-note">Guideline only: image height of about one sixth of the farthest seat distance. Acoustics, network and lighting are checked at a free site survey.</p>');
	}

	function bind(id, fn) { var el = document.getElementById(id); if (el) { el.addEventListener('submit', fn); } }
	bind('ap-led-form', led);
	bind('ap-room-form', room);
})();
