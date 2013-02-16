(function ($) {
	var _initializing = false;
	var Class = function () {};
	Class.extend = function (name, body) {
		_initializing = true;
		var proto = new this();
		_initializing = false;
		function Class() {
			if (!_initializing && this.init) this.init.apply(this, arguments);
		}

		var superProto = this.prototype;
		for (var key in body) {
			proto[key] =
				typeof body[key] === 'function' && typeof superProto[key] === 'function'
					? (function (key, fn) {
							return function () {
								this._super = superProto[key]; // '_super' in method
								var ret = fn.apply(this, arguments);
								delete this._super;
								return ret;
							};
					  })(key, body[key])
					: body[key];
		}
		proto._class = Class;

		Class.prototype = proto;
		Class.constructor = Class;
		Class.extend = arguments.callee;
		Class._super = this;
		Class._name = name;
		Class._body = body;

		return Class;
	};

	var Interface = Class.extend('Interface', {
		init: function (name, body) {
			this.name = name;
			this.body = body;
		},
		statics: function (prop) {
			$.extend(this, prop);
			this._statics = prop;
			return this;
		}
	});
	var Module = Class.extend('Module', {
		init: function (name, body) {
			this.name = name;
			this.body = body;
		},
		statics: function (prop) {
			$.extend(this, prop);
			this._statics = prop;
			return this;
		}
	});
	window.Interface = function (name, body, expose) {
		var ret = new Interface(name, body);
		expose === false || (this[name] = ret);
		return ret;
	};
	window.Module = function (name, body, expose) {
		var ret = new Module(name, body);
		expose === false || (this[name] = ret);
		return ret;
	};

	window.Class = function (name, head, body, expose) {
		var ex = head.extend,
			im = head.implement, // array
			mx = head.mixin; // array
		var ret = (ex ? ex : Class).extend(name, body);
		if (im) {
			$(im).each(function () {
				var b = this.body;
				for (var k in b) {
					ret.prototype[k] === undefined && (ret.prototype[k] = b[k]);
				}
			});
		}
		if (mx) {
			$(mx).each(function () {
				var b = this.body;
				$.extend(ret.prototype, b);
			});
		}

		ret.statics = function (prop) {
			$.extend(this, prop);
			this._statics = prop;
			return this;
		};
		expose === false || (this[name] = ret);
		return ret;
	};

	var Package = window.Class(
		'Package',
		{},
		{
			init: function (name) {
				this.name = name;
			},
			statics: function (prop) {
				$.extend(this, prop);
				this._statics = prop;
				return this;
			},
			Package: function (name) {
				return (this[name] = new Package(name));
			},
			Class: window.Class
		},
		false
	);
	window.Package = Package.prototype.Package;
})(window.jQuery);
