// @ts-nocheck
var _arg = {
	url: "",
	selector: "",
	selectorPagination: "#paginationTable",
	pagination: 1,
	hotReload: false,
	search: "",
	perPage: 10,
	callback: function () {
		// Do NOTHING
	},
	failCallback: function () {
		// Do NOTHING
	},
	debug: false,
	customUrl: "",
};

var _mock = [];
var _mockFiltered = [];

/**
 *
 * @param {[object]} data
 */
var _ligne = function () {
	var tr = "";
	var limit = _arg.perPage * _arg.pagination;
	var offset = _arg.perPage * (_arg.pagination - 1);
	var count = 0;

	if (_arg.search.length > 1) {
		_mockFiltered = [];

		_mock.find((element) => {
			var newArr = Object.values(element);
			newArr.forEach((el) => {
				if (
					el.trim().toLowerCase().substring(0, _arg.search.length) ===
					_arg.search.trim().toLowerCase()
				) {
					_mockFiltered.push(element);
					return true;
				}
				return false;
			});
		});
	} else {
		_mockFiltered = _mock;
	}

	_renderPagination();

	_mockFiltered.forEach((element) => {
		if (offset !== 0 && count < offset) {
			count++;
		} else {
			if (count < limit) {
				var newArr = Object.values(element);
				var td = "";
				newArr.forEach((el) => {
					td +=
						'<td style="max-width:55vw; white-space: normal">' + el + "</td>";
				});
				tr += "<tr>" + td + "</tr>";
			}
			count++;
		}
	});

	return tr;
};

var _renderPagination = function () {
	var lenData = _mockFiltered.length;

	var nearNbPage = lenData / _arg.perPage;
	var NombrePage = 1;

	if (Number.isInteger(nearNbPage)) {
		NombrePage = nearNbPage;
	} else {
		NombrePage = Math.ceil(nearNbPage);
	}

	var startPage =
		'<li class="page-item"><a class="page-link" href="#" onclick="changePage(1)"><span class="ni ni-bold-left"></span></a></li>';
	var endPage =
		'<li class="page-item"><a class="page-link" href="#" onclick="changePage(' +
		NombrePage +
		')"><span class="ni ni-bold-right"></span></a></li>';
	var buttonPage = "";

	for (let i = 1; i <= NombrePage; i++) {
		var active = "";

		if (_arg.pagination === i) active = "active";

		var closest = Math.abs(_arg.pagination - i);

		if (closest < 3) {
			buttonPage +=
				'<li class="page-item ' +
				active +
				'"><a class="page-link" href="#" onclick="changePage(' +
				i +
				')">' +
				i +
				"</a></li>";
		}
	}

	$(_arg.selectorPagination).html(startPage + buttonPage + endPage);
};

var _mocking = function () {
	$.ajax({
		url: _arg.url,
		type: "POST",
		dataType: "json",
		async: true,
		success: function (response) {
			if (response.success === true) {
				_mock = response.data;
				$(_arg.selector).html(_ligne());
				_arg.callback();
			} else {
				if (_arg.debug === true) {
					console.log(response);
				}
				_arg.failCallback();
			}

			$(".modal-backdrop").remove();
			$("#modal-spinner").modal("hide");
		},
		error: function (response) {
			if (_arg.debug === true) {
				console.log(response);
			}
			$(".modal-backdrop").remove();
			$("#modal-spinner").modal("hide");
		},
	});
};

var _mockingCustom = function () {
	$.ajax({
		url: _arg.customUrl,
		type: "POST",
		dataType: "json",
		async: true,
		success: function (response) {
			if (response.success === true) {
				_mock = response.data;
				$(_arg.selector).html(_ligne());
				_arg.callback();
			} else {
				if (_arg.debug === true) {
					console.log(response);
				}
				_arg.failCallback();
			}

			$(".modal-backdrop").remove();
			$("#modal-spinner").modal("hide");
		},
		error: function (response) {
			if (_arg.debug === true) {
				console.log(response);
			}
			$(".modal-backdrop").remove();
			$("#modal-spinner").modal("hide");
		},
	});
};

var changePage = function (pagination) {
	_arg.pagination = pagination;
	$(_arg.selector).html(_ligne());
};

var loadGrid = function (arg) {
	_arg = { ..._arg, ...arg };
	_mocking();
};

var searching = function (str) {
	if (str.trim().length > 1) {
		_arg.search = str;
		reloadGrid();
	}
};

var reloadGrid = function () {
	$("#modal-spinner").modal("show");
	if (_arg.hotReload === true) {
		_mocking();
	} else {
		$(_arg.selector).html(_ligne());
	}

	$(".modal-backdrop").remove();
	$("#modal-spinner").modal("hide");
};

var reloadCustom = function (customUrl) {
	_arg.customUrl = customUrl;
	_mockingCustom();
}

var forceHotReload = function () {
	_arg.search = "";
	_mocking();
};
