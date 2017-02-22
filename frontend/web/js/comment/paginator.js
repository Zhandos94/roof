/**
 * [Paginator constructor]
 *
 * @property {Integer} _defualt_size private prop count for record render page
 * @property {Array} _all_record private prop
 * @property {Integer} _pager_count private prop
 * @method _pager private method render pager
 * @method setRecords public method
 * @method record public method
 * @method pageRender public method
 */
function Paginator () {
	var _defualt_size, _all_record,
		_pager_count, _counter, _pager
	;

	_defualt_size = 3;

	_all_record = [];

	// кнопки пагинации внизу
	_pager_count = 0;

	/**
	 * [_pager description]
	 * @return {void}
	 */
	_pager = function () {
		$('.pagination').remove();
		var paginatorEl = $('<ul class="pagination"></ul>');
		for (var i = 0; i < _pager_count; ++i) {
			paginatorEl.append('<li><a href="#" class="pager" data-page="'+ (i * _defualt_size) +'">' + (i + 1) + '</a></li>');
			$('#comments').after(paginatorEl);
		}
	};

	/**
	 * [setRecords description]
	 * @param {Array} all_record
	 */
	this.setRecords = function (all_record) {
		_all_record = all_record;
	};

	/**
	 * [record description]
	 * @param  {Oject} record
	 * @return {Array}
	 */
	this.record = function (record) {
		if (record) {
			_all_record.push(record);
		}
		return _all_record;
	}

	/**
	 * [pageRender description]
	 * @param  {Integer}  page
	 * @param  {Boolean} isNew
	 * @return {void}
	 */
	this.pageRender = function (page, isNew) {
		var size, page, levelOne, allRecordChildren, forRender, _allRecordLevelOne;

		_allRecordLevelOne = _all_record.filter(function (item) {
			if (item.group_id == group_id) {
				return item;
			}
		});

		if (_allRecordLevelOne.length > _defualt_size) {
			_pager_count = Math.ceil(_allRecordLevelOne.length / _defualt_size);
			_pager();
		}

		if (!isNaN(page)) {
			size = _defualt_size + page;
		} else {
			page = 0;
			size = _defualt_size;
		}

		if (isNew) {
			if (_defualt_size < _allRecordLevelOne.length) {
				page = (_pager_count * _defualt_size) - _defualt_size;
				size = page +_defualt_size;
			} else {
				page = 0;
			}
		}

		levelOne = _allRecordLevelOne.slice(page, size);

		allRecordChildren = _all_record.filter(function (item) {
			if (item.group_id == 2) {
				return item;
			}
		});

		forRender = levelOne.concat(allRecordChildren);

		$('.item-comment').detach();

		this.renderComments(forRender);
	};


}

/*----------*/
/* Events */
/*----------*/
$('.panel').delegate('.pager', 'click', function(e) {
	e.preventDefault();
	$('.panel-body').append($('.comment-form').hide());
	paginator.pageRender($(this).data('page'));
});

Paginator.prototype = renderComment();
