/**
 * [Comments constructor]
 *
 * @property {Array} _all_comments private prop
 * @method _sortByComments private method
 * @method _groupIdOne private method
 * @method _groupIdTwo private method
 * @method _sortComment private method
 * @method getAjaxRequest public method
 *
 */
function Comments () {
	var _sortComments, _groupIdOne, _groupIdTwo,
		_sortComment, _all_comments
	;

	/**
	 * [_all_comments description]
	 * @type {Array}
	 */
	_all_comments = [];


	/**
	 * [_sortComments description]
	 * @param  {Array} comments all comments of DB
	 * @return {Array} sorts comments
	 */
	_sortComments = function (comments) {;
		var levelArrFirst, levelArrSecond;
		levelArrFirst = comments.filter(_groupIdOne);
		levelArrSecond = comments.filter(_groupIdTwo);
		comments = undefined;
		levelArrFirst.sort(_sortComment);
		levelArrSecond.sort(_sortComment);
		return levelArrFirst.concat(levelArrSecond);
	};

	_groupIdOne = function (element) {
		if (element.group_id == group_id) {
			return element.level = 'L1';
		}
	};

	_groupIdTwo = function (element) {
		if (element.group_id == 2) {
			return element.level = 'L2';
		}
	};

	_sortComment = function (a,b) {
		return a.id - b.id;
	};

	/**
	 * [getAjaxRequest description]
	 * @return {Deferred}
	 */
	this.getAjaxRequest = function () {
		return $.ajax({
			url: '/comments/default/get-comments?subject_id=' + subject_id + '&global_group_id=' + group_id,
			success: function(response) {
				if (response) {
					var comments = JSON.parse(response);
					if (Array.isArray(comments)) {
						_all_comments = _sortComments(comments);
					} else {
						$('#comments').append('<div class="alert alert-danger">' + _t("Не пришли комментарий") + '</div>');
					}
				}
			}
		});
	};
}

Comments.prototype = renderComment();

/*----------*/
/* Events */
/*----------*/

$('.panel').delegate('.button-comment', 'click', function (e) {
	e.preventDefault();

	$('#group_id').val($(this).data('group-id'));
	$('#global_group_id').val($(this).data('global-group-id'));
	$('#parent_id').val($(this).data('subject-id'));

	if ($(this).data('subject-id') != $('.comment-form').attr('subject-id')) {
		$('.comment-form').hide().removeAttr('subject-id').attr('subject-id', $(this).data('subject-id'));
	}

	$('.comment-form').slideToggle('slow');

	// $('.form-close').css({
	// 	'left': '90%',
	// 	'top': '5px'
	// 	//'top': '-' + ($(this).height() + 20) + 'px'
	// });

	if ($(this).hasClass('button-reply')) {
		$('.comment-form').addClass('child-comment');
		var parentEl = $(this).closest('.item-comment');
		$(parentEl).append($('.comment-form'));
	} else {
		$('.comment-form').removeClass('child-comment');
		$(this).after($('.comment-form'));
	}



});

$('.comment-form').submit(function(e) {
	//e.preventDefault();
	var form = $('#comment-form'),
		url = $(form).attr('action'),
		dataForm = $(form).serialize();

	$.ajax({
		context: this,
		type: 'post',
		url: url,
		data: dataForm,
		success: function(response) {
			var item, div;
			item = JSON.parse(response);
			if (item.code == 'cn_e006') {
				comments.renderError(item);
				setTimeout(function () {
					$('.no-moderation').detach();
				}, 5000);
				$('.comment-form').hide();
			} else if (item !== 'error') {
				if ($(this).hasClass('child-comment')) {
					paginator.record(item);
					comments.renderComment(item);
				} else {
					paginator.record(item);
					paginator.pageRender(false, true);
				}
				$('.comment-form').hide();
				//div = $('<div class="custom-success">' + _t("Comment add") + '</div>');
				//div = $('<div class="custom-success">' + "Comment add" + '</div>');
				// $('.panel').prepend(div);
				// setTimeout(function () {
				// 	$('.custom-success').detach();
				// }, 8000);
				// $('#modal').modal('show');
				// setTimeout(function () {
				// 	$('#modal').modal('hide');
				// }, 1500);
			}

			$.pjax.reload({container:'#form-save-comments'});
		}
	});
	return false;
});

$('.panel').delegate('.like', 'click', function(e) {
	var comment_id = $(this).closest('.item-comment').data('comment-id');
	var status = $(this).data('like-status');
	if (!$(this).hasClass('likedAfterDisabled')) {
		$.ajax({
			context: this,
			type: "post",
			url: '/comments/default/like',
			data: 'comment_id=' + comment_id + '&status=' + status,
			success: function(response) {
				if (response != 'error') {
					var result = JSON.parse(response);
					$(this).closest('.likes').find('.like-count-up').text(result.up);
					$(this).closest('.likes').find('.like-count-down').text(result.down);
					$(this).addClass('likedAfterDisabled');
					$(this).parent('.like-count').siblings().find('.like').removeClass('likedAfterDisabled');
				}
			}
		});
	}

});

$('.panel').delegate('.form-close', 'click', function(e) {
	$('.comment-form').hide('slow').removeAttr('subject-id').attr('subject-id', $(this).data('subject-id'));
});
